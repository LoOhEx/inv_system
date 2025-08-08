<script type="text/javascript">
	/**
 * Universal Inventory Management System
 * File JavaScript universal untuk inv_ecbs.php dan inv_ecct.php
 *
 * Deteksi otomatis berdasarkan INVENTORY_TYPE yang di-set di PHP
 */

// =================== GLOBAL VARIABLES ===================
var currentTable = ""
var currentType = "app" // app atau osc
var inventoryType = "" // 'ECBS' atau 'ECCT'
var CONFIG = window.CONFIG || {} // Declare CONFIG variable
var inputMode = "singular" // "singular" atau "massive"
var currentPage = "" // Current page context (inv_ecct, inv_ecbs, inv_week, etc.)
var currentYear = new Date().getFullYear()
var currentMonth = new Date().getMonth() + 1

// Debug: log CONFIG object
console.log("CONFIG:", CONFIG)

// =================== INITIALIZATION ===================
document.addEventListener("DOMContentLoaded", () => {
  // Deteksi inventory type dari global variable yang di-set di PHP
  inventoryType = window.INVENTORY_TYPE || "ECBS"

  // Set current table berdasarkan inventory type
  if (inventoryType === "ECBS") {
    currentTable = "ecbs"
  } else if (inventoryType === "ECCT") {
    currentTable = "ecct"
  }

  // Detect current page from URL or page context
  detectCurrentPage()

  // Initialize
  initializeEventListeners()
  renderToolbar()
  
  // Only show main data and input tab if not on inv_week page
  if (currentPage !== "inv_week") {
    showMainData()
    showInputTab("in")
  } else {
    // For inv_week page, load the weekly data
    showInvWeekData()
  }
})

// =================== PAGE DETECTION ===================
function detectCurrentPage() {
  // Try to detect from URL first
  const pathname = window.location.pathname
  
  if (pathname.includes('inv_week')) {
    currentPage = "inv_week"
  } else if (pathname.includes('inv_ecct')) {
    currentPage = "inv_ecct"
  } else if (pathname.includes('inv_ecbs')) {
    currentPage = "inv_ecbs"
  } else {
    // Fallback: try to detect from page elements
    const invWeekElements = document.querySelectorAll('[onclick*="openModal(\'modal_input_period\')"]')
    if (invWeekElements.length > 0) {
      currentPage = "inv_week"
    }
  }
}

// =================== MODAL UTILITIES ===================
function openModal(modalId) {
  const overlay = document.getElementById("modal_overlay")
  const modal = document.getElementById(modalId)

  if (overlay && modal) {
    overlay.style.display = "block"
    modal.style.display = "block"

    // Initialize auto-submit listeners when modal opens
    if (modalId === "modal_input_all") {
      if (currentPage === "inv_week") {
        // For inv_week page, render the period generator interface
        renderInvWeekInputMode()
      } else {
        // For other pages, use the normal input interface
        initializeAutoSubmitListeners()
        renderInputMode()
      }
    }
  }
}

function closeModal(modalId) {
  const overlay = document.getElementById("modal_overlay")
  const modal = document.getElementById(modalId)

  if (overlay && modal) {
    overlay.style.display = "none"
    modal.style.display = "none"
  }

  if (modalId === "modal_input_all") {
    resetInputForm()
  }
}

function resetInputForm() {
  // Reset singular inputs
  document.getElementById("in_serial_number").value = ""
  document.getElementById("out_serial_number").value = ""
  document.getElementById("move_serial_number").value = ""
  document.getElementById("move_location").value = ""

  // Reset massive inputs
  document.getElementById("in_serial_numbers_massive").value = ""
  document.getElementById("out_serial_numbers_massive").value = ""
  document.getElementById("move_serial_numbers_massive").value = ""

  // Clear result messages
  const resultDivs = ["in_result_message", "out_result_message", "move_result_message"]
  resultDivs.forEach((id) => {
    const div = document.getElementById(id)
    if (div) {
      div.style.display = "none"
      div.innerText = ""
    }
  })
}

// =================== INPUT MODE SWITCHING ===================
function switchInput(mode) {
  inputMode = mode
  renderInputMode()
}

function renderInputMode() {
  // Update toggle buttons
  const singularBtn = document.getElementById("btn_singular")
  const massiveBtn = document.getElementById("btn_massive")

  if (singularBtn && massiveBtn) {
    if (inputMode === "singular") {
      singularBtn.className = "input-mode-btn active"
      massiveBtn.className = "input-mode-btn"
    } else {
      singularBtn.className = "input-mode-btn"
      massiveBtn.className = "input-mode-btn active"
    }
  }

  // Show/hide appropriate input elements based on current tab and mode
  const currentTab = document.querySelector(".input-tab-btn.active").id.replace("tabBtn_", "")
  updateInputDisplay(currentTab)
}

function updateInputDisplay(tab) {
  const singularContainer = document.getElementById(`${tab}_singular_container`)
  const massiveContainer = document.getElementById(`${tab}_massive_container`)

  if (singularContainer && massiveContainer) {
    if (inputMode === "singular") {
      singularContainer.style.display = "block"
      massiveContainer.style.display = "none"
    } else {
      singularContainer.style.display = "none"
      massiveContainer.style.display = "block"
    }
  }
}

// =================== AUTO SUBMIT LISTENERS ===================
function initializeAutoSubmitListeners() {
  // Event listener untuk input IN
  const inSerialInput = document.getElementById("in_serial_number")
  if (inSerialInput) {
    // Remove existing listeners to prevent duplicates
    inSerialInput.removeEventListener("input", handleInSerialInput)
    inSerialInput.addEventListener("input", handleInSerialInput)
  }

  // Event listener untuk input OUT
  const outSerialInput = document.getElementById("out_serial_number")
  if (outSerialInput) {
    outSerialInput.removeEventListener("input", handleOutSerialInput)
    outSerialInput.addEventListener("input", handleOutSerialInput)
  }

  // Event listener untuk input MOVE
  const moveSerialInput = document.getElementById("move_serial_number")
  if (moveSerialInput) {
    moveSerialInput.removeEventListener("input", handleMoveSerialInput)
    moveSerialInput.addEventListener("input", handleMoveSerialInput)
  }
}

function handleInSerialInput(event) {
  const serialNumberInput = event.target // Get the input element directly
  const serialNumber = serialNumberInput.value.trim()
  if (serialNumber.length === 15) {
    const resultDiv = document.getElementById("in_result_message")
    if (validateSerialNumber(serialNumber, "in", resultDiv)) {
      submitInput("in")
    } else {
      // Client-side validation failed, clear the input immediately
      serialNumberInput.value = ""
    }
  }
}

function handleOutSerialInput(event) {
  const serialNumberInput = event.target // Get the input element directly
  const serialNumber = serialNumberInput.value.trim()
  if (serialNumber.length === 15) {
    const resultDiv = document.getElementById("out_result_message")
    if (validateSerialNumber(serialNumber, "out", resultDiv)) {
      submitInput("out")
    } else {
      // Client-side validation failed, clear the input immediately
      serialNumberInput.value = ""
    }
  }
}

function handleMoveSerialInput(event) {
  const serialNumberInput = event.target // Get the input element directly
  const serialNumber = serialNumberInput.value.trim()
  if (serialNumber.length === 15) {
    const resultDiv = document.getElementById("move_result_message")
    if (validateSerialNumber(serialNumber, "move", resultDiv)) {
      // Check if location is selected for move
      const location = document.getElementById("move_location").value
      if (!location) {
        resultDiv.innerText = "Pilih lokasi tujuan terlebih dahulu!"
        resultDiv.className = "input-result-message error"
        resultDiv.style.display = "block"
        serialNumberInput.value = "" // Clear if location is missing
        return
      }
      submitInput("move")
    } else {
      // Client-side validation failed, clear the input immediately
      serialNumberInput.value = ""
    }
  }
}

// =================== EVENT LISTENERS ===================
function initializeEventListeners() {
  const overlay = document.getElementById("modal_overlay")

  // Klik overlay untuk menutup modal
  overlay.addEventListener("click", (event) => {
    if (event.target === overlay) {
      const modals = ["modal_filter_ecbs", "modal_filter_ecct", "modal_filter_item", "modal_input_all"]
      modals.forEach((modalId) => {
        const modal = document.getElementById(modalId)
        if (modal && modal.style.display === "block") {
          closeModal(modalId)
        }
      })
    }
  })

  // ESC key untuk menutup modal
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      const modals = ["modal_filter_ecbs", "modal_filter_ecct", "modal_filter_item", "modal_input_all"]
      modals.forEach((modalId) => {
        const modal = document.getElementById(modalId)
        if (modal && modal.style.display === "block") {
          closeModal(modalId)
        }
      })
    }
  })
}

// =================== COMMON UTILITIES ===================
function loadData(link) {
  if (typeof window.$ !== "undefined") {
    // Use window.$ to avoid undeclared variable error
    window.$("#show_data").load(link)
  } else {
    fetch(link)
      .then((response) => response.text())
      .then((data) => {
        document.getElementById("show_data").innerHTML = data
      })
      .catch((error) => {
        document.getElementById("show_data").innerHTML =
          '<div style="padding: 20px; text-align: center; color: red;">Error loading data</div>'
      })
  }
}

// function showLoading() {
//   return '<div style="text-align: center; padding: 40px;"><div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #1677ff; border-radius: 50%; animation: spin 1s linear infinite;"></div><p style="margin-top: 10px;">Loading data...</p></div>'
// }

// =================== SERIAL NUMBER VALIDATION ===================
function validateSerialNumber(serialNumber, type, resultDiv) {
  const expectedChar = inventoryType === "ECBS" ? "S" : "T"
  const deviceName = inventoryType

  if (serialNumber.length < 6 || serialNumber.charAt(5).toUpperCase() !== expectedChar) {
    if (resultDiv) {
      resultDiv.innerText = `Masukan ${deviceName}!`
      resultDiv.className = "input-result-message error"
      resultDiv.style.display = "block"
    }
    return false
  }
  return true
}

// =================== TABLE SWITCHING ===================
function switchTable(type) {
  currentTable = type
  renderToolbar()

  if (inventoryType === "ECBS") {
    if (type === "ecbs") {
      showMainData()
    } else {
      showDataActivity()
    }
    document.getElementById("btn_ecbs").className =
      "btn btn-sm " + (currentTable === "ecbs" ? "btn-primary" : "btn-light")
    document.getElementById("btn_activity").className =
      "btn btn-sm " + (currentTable === "activity" ? "btn-primary" : "btn-light")
  } else if (inventoryType === "ECCT") {
    if (type === "ecct") {
      showMainData()
    } else {
      showDataAllItem()
    }
    document.getElementById("btn_ecct").className =
      "btn btn-sm " + (currentTable === "ecct" ? "btn-primary" : "btn-light")
    document.getElementById("btn_allitem").className =
      "btn btn-sm " + (currentTable === "allitem" ? "btn-primary" : "btn-light")
  }
}

let searchTimeout = null;

// =================== TOOLBAR RENDERING ===================
function renderToolbar() {
  var toolbarLeft = ""
  var toolbarRight = ""

  // Special handling for inv_week page
  if (currentPage === "inv_week") {
    // For inv_week page, show Input button with month/date generator functionality
    toolbarLeft +=
      '<button class="btn btn-sm" style="background: #28a745; color: white;" onclick="openModal(\'modal_input_all\')" id="input_btn" type="button">Input</button>'
    
    // Export button on the right
    toolbarRight +=
      '<a class="btn btn-sm btn-icon-lg btn-light" onclick="exportInvWeekData();" style="margin-left:4px;"><i class="ki-filled ki-exit-down !text-base"></i>Export</a>'
  } else {
    const isMainTable =
      (inventoryType === "ECBS" && currentTable === "ecbs") || (inventoryType === "ECCT" && currentTable === "ecct")

    if (isMainTable) {
      // Untuk main table (ECBS/ECCT), tombol Input di kiri, APP/OSC dan Export di kanan
      toolbarLeft +=
        '<button class="btn btn-sm" style="background: #28a745; color: white;" onclick="openModal(\'modal_input_all\')" id="input_btn" type="button">Input</button>'
      
      toolbarRight += '<div class="btn-group mr-2">'
      toolbarRight +=
        '<button id="btn_app" class="btn btn-sm ' +
        (currentType === "app" ? "btn-primary" : "btn-light") +
        '" onclick="switchMainType(\'app\')">APP</button>'
      toolbarRight +=
        '<button id="btn_osc" class="btn btn-sm ' +
        (currentType === "osc" ? "btn-primary" : "btn-light") +
        '" onclick="switchMainType(\'osc\')">OSC</button>'
      toolbarRight += "</div>"
      toolbarRight +=
        '<a class="btn btn-sm btn-icon-lg btn-light" onclick="showMainData(\'export\');" style="margin-left:4px;"><i class="ki-filled ki-exit-down !text-base"></i>Export</a>'
    } else {
      // Untuk activity/allitem, tombol Input di kiri
      toolbarLeft +=
        '<button class="btn btn-sm" style="background: #28a745; color: white;" onclick="openModal(\'modal_input_all\')" id="input_btn" type="button">Input</button>'

      // Search, Filter, dan Export di kanan
      toolbarRight += '<div class="input-group input-sm">'
      toolbarRight +=
        '<input class="input input-sm" placeholder="Search SN or Device Code..." type="text" id="key_activity" />'
      toolbarRight += '<span class="btn btn-light btn-sm" onclick="openModal(\'modal_filter_item\')">Filter</span>'
      toolbarRight += '<span class="btn btn-primary btn-sm" onclick="showSecondaryData();">Search</span>'
      toolbarRight += "</div>"
      toolbarRight +=
      '<a class="btn btn-sm btn-icon-lg btn-light" onclick="exportInvWeekData();" style="margin-left:4px; padding: 0.375rem 0.75rem; border-radius: 0.25rem; border: 1px solid #ddd; background-color: #f8f9fa; color: #495057;"><i class="ki-filled ki-exit-down !text-base"></i>Export</a>'
    }
  }

  // Update kedua div
  document.getElementById("toolbar_left").innerHTML = toolbarLeft
  document.getElementById("toolbar_right").innerHTML = toolbarRight
  
  // Setup auto search setelah toolbar di-render (only for non-inv_week pages)
  if (currentPage !== "inv_week") {
    setupAutoSearch()
  }
}

function setupAutoSearch() {
  const searchInput = document.getElementById("key_activity")
  
  if (searchInput) {
    // Remove existing event listeners untuk avoid duplicate
    searchInput.removeEventListener("input", handleAutoSearch)
    
    // Add event listener untuk auto search
    searchInput.addEventListener("input", handleAutoSearch)
    
    // Keep enter key functionality
    searchInput.addEventListener("keyup", function(event) {
      if (event.key === 'Enter') {
        // Clear timeout dan langsung search
        if (searchTimeout) {
          clearTimeout(searchTimeout)
        }
        showSecondaryData()
      }
    })
  }
}

// Handle auto search dengan debounce
function handleAutoSearch(event) {
  const searchTerm = event.target.value.trim()
  
  // Clear existing timeout
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  
  // Set timeout untuk debounce (500ms delay agar tidak terlalu cepat)
  searchTimeout = setTimeout(() => {
    showSecondaryData()
  }, 500)
}

// =================== TYPE SWITCHING ===================
function switchMainType(type) {
  currentType = type
  renderToolbar()
  showMainData()
}

// Alias functions untuk backward compatibility
function switchEcbsType(type) {
  switchMainType(type)
}
function switchEcctType(type) {
  switchMainType(type)
}

// =================== DATA LOADING FUNCTIONS ===================
function showMainData(page = 1) {
  // const loading = showLoading()
  // if (page !== "export") document.getElementById("show_data").innerHTML = loading

  var val = "?"
  val += "&type=" + currentType

  const deviceType = inventoryType.toLowerCase()

  if (page === "export") {
    var linkExport = CONFIG.urlMenu + `data/data_inv_${deviceType}_` + currentType + "_export" + val // Rename variable to avoid redeclaration
    window.open(linkExport, "_blank").focus()
    return
  }

  val += "&p=" + page
  var linkShow = CONFIG.urlMenu + `data/data_inv_${deviceType}_` + currentType + "_show" + val // Rename variable to avoid redeclaration

  loadData(linkShow)
}

function showSecondaryData(page = 1) {
  // const loading = showLoading()
  // if (page !== "export") document.getElementById("show_data").innerHTML = loading

  var val = "?"
  const fields = [
    "key_activity",
    "dvc_size",
    "dvc_col",
    "dvc_qc",
    "dvc_type",
    "dvc_code",
    "in_date_from",
    "in_date_to",
    "move_date_from",
    "move_date_to",
    "out_date_from",
    "out_date_to",
    "act_date_from",
    "act_date_to",
    "loc_move",
    "sort_by",
    "data_view_item",
    "activity",
  ]

  fields.forEach((field) => {
    var element = document.getElementById(field)
    if (element) {
      val += "&" + field + "=" + encodeURIComponent(element.value)
    }
  })

  // Tambahkan context berdasarkan inventory type
  val += "&context=inv_" + inventoryType.toLowerCase()

  if (page === "export") {
    var linkExport = CONFIG.urlMenu + "data/data_item_export" + val // Rename variable to avoid redeclaration
    window.open(linkExport, "_blank").focus()
    return
  }

  val += "&p=" + page

  // Tentukan link berdasarkan inventory type
  var linkPath = inventoryType === "ECBS" ? "data_item_show_ecbs" : "data_item_show"
  var linkShow = CONFIG.urlMenu + "data/" + linkPath + val // Rename variable to avoid redeclaration

  loadData(linkShow)
}

// Alias functions untuk backward compatibility
function showDataEcbs(page) {
  showMainData(page)
}
function showDataEcct(page) {
  showMainData(page)
}
function showDataActivity(page) {
  showSecondaryData(page)
}
function showDataAllItem(page) {
  showSecondaryData(page)
}

// =================== PAGINATION HANDLING ===================
function handlePagination(page) {
  const isMainTable =
    (inventoryType === "ECBS" && currentTable === "ecbs") || (inventoryType === "ECCT" && currentTable === "ecct")

  if (isMainTable) {
    showMainData(page)
  } else {
    showSecondaryData(page)
  }
}

function getCurrentTableFunction() {
  const isMainTable =
    (inventoryType === "ECBS" && currentTable === "ecbs") || (inventoryType === "ECCT" && currentTable === "ecct")

  if (isMainTable) {
    return inventoryType === "ECBS" ? "showDataEcbs" : "showDataEcct"
  } else {
    return inventoryType === "ECBS" ? "showDataActivity" : "showDataAllItem"
  }
}

// =================== INPUT TAB FUNCTIONS ===================
function showInputTab(tab) {
  // Hide all tab contents
  document.querySelectorAll(".input-tab-content").forEach((el) => {
    el.style.display = "none"
  })
  // Remove active from all tab buttons
  document.querySelectorAll(".input-tab-btn").forEach((btn) => {
    btn.classList.remove("active")
  })
  // Show selected tab content
  document.getElementById("inputTab_" + tab).style.display = "flex"
  // Set active tab button
  document.getElementById("tabBtn_" + tab).classList.add("active")

  // Update input display based on current mode
  updateInputDisplay(tab)

  // Re-initialize auto-submit listeners when switching tabs
  setTimeout(() => {
    initializeAutoSubmitListeners()
  }, 100)
}

// =================== INPUT SUBMISSION ===================
function submitInput(type) {

  if (inputMode === "massive") {
    submitMassiveInput(type)
    return
  }

  let data = {}
  const url = CONFIG.urlMenu + "input_process"
  let resultDiv = ""
  let serialNumber = ""


  if (type === "in") {
    serialNumber = document.getElementById("in_serial_number").value.trim()
    data = {
      type: "in",
      serial_number: serialNumber,
      qc_status: document.getElementById("in_qc_status").value,
      // Untuk singular input, user_date tidak disediakan, sehingga inv_in akan menjadi current timestamp
    }
    resultDiv = document.getElementById("in_result_message")
  } else if (type === "out") {
    serialNumber = document.getElementById("out_serial_number").value.trim()
    data = {
      type: "out",
      serial_number: serialNumber,
    }
    resultDiv = document.getElementById("out_result_message")
  } else if (type === "move") {
    serialNumber = document.getElementById("move_serial_number").value.trim()
    data = {
      type: "move",
      serial_number: serialNumber,
      location: document.getElementById("move_location").value,
    }
    resultDiv = document.getElementById("move_result_message")
  }



  fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((result) => {
      if (resultDiv) {
        resultDiv.innerText = result.message
        if (result.success) {
          resultDiv.className = "input-result-message success"
          if (currentTable === "allitem") {
            showDataAllItem()
          } else if (currentTable === "ecct") {
            showDataEcct()
          } else if (currentTable === "ecbs") {
            showDataEcbs()
          } else if (currentTable === "activity") {
            showDataActivity()
          }
        } else {
          resultDiv.className = "input-result-message error"
        }
        resultDiv.style.display = "block"
      }
      // Clear the input field after submission, regardless of success or failure
      if (type === "in") {
        document.getElementById("in_serial_number").value = ""
      } else if (type === "out") {
        document.getElementById("out_serial_number").value = ""
      } else if (type === "move") {
        document.getElementById("move_serial_number").value = ""
        // Optionally, clear the move_location as well if desired
        // document.getElementById("move_location").value = ""
      }
    })
    .catch((error) => {
      resultDiv.innerText = "❌ Error: " + error.message
      // Clear the input field even if there's a network error
      if (type === "in") {
        document.getElementById("in_serial_number").value = ""
      } else if (type === "out") {
        document.getElementById("out_serial_number").value = ""
      } else if (type === "move") {
        document.getElementById("move_serial_number").value = ""
        // document.getElementById("move_location").value = ""
      }
    })
}

// =================== MASSIVE INPUT SUBMISSION ===================
async function submitMassiveInput(type) {


  // Get elements
  const serialNumbersEl = document.getElementById(`${type}_serial_numbers_massive`)
  const qcStatusEl = document.getElementById(`${type}_qc_status`)
  const locationEl = document.getElementById(`${type}_location`)
  const resultEl = document.getElementById(`${type}_result_message`)
  const loadingEl = document.getElementById(`${type}_loading_spinner`)

  // Show loading
  loadingEl.style.display = "inline-block"
  resultEl.style.display = "none"

  // Parse input - handle both simple SN and SN with date
  const rawInputs = serialNumbersEl.value
    .split(/[\n]+/)
    .map((s) => s.trim())
    .filter((s) => s !== "")
  const processedInputs = []

  for (const line of rawInputs) {
    if (line.includes("\t")) {
      // Format: "25212TN15501013	01-07-2023"
      const [sn, date] = line.split("\t").map((s) => s.trim())
      processedInputs.push({ serial_number: sn, user_date: date })
    } else {
      // Just serial number
      processedInputs.push({ serial_number: line })
    }
  }

  const url = "<?php echo $config['base_url']; ?>/inventory/input_process"

  let successCount = 0,
    failCount = 0
  const failedSerials = []
  const failedInputs = [] // Simpan input asli yang gagal

  // If no inputs, send empty request to get server error message
  const inputsToProcess = processedInputs.length > 0 ? processedInputs : [{ serial_number: "" }]

  // Process each input (let server handle all validation)
  for (const input of inputsToProcess) {
    const data = { type, serial_number: input.serial_number }

    if (type === "in") {
      data.qc_status = qcStatusEl?.value
    }
    if (type === "move") data.location = locationEl?.value

    // Add user_date if available
    if (input.user_date) {
      data.user_date = input.user_date
    }

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      })
      const result = await response.json()

      if (result.success) {
        successCount++
      } else {
        failCount++
        failedSerials.push(`${input.serial_number}: ${result.message}`)
        failedInputs.push(input) // Simpan input asli
      }
    } catch (error) {
      failCount++
      failedSerials.push(`${input.serial_number}: ❌ Error ${error.message}`)
      failedInputs.push(input) // Simpan input asli
    }
  }


 

  // Show results
  loadingEl.style.display = "none"
  let message = `Processing complete: ${successCount} successful, ${failCount} failed.`



  if (failCount === 0) {

    serialNumbersEl.value = ""
    refreshCurrentData()
    resultEl.className = "input-result-message success"
  } else {
    message += "\n\nFailed serial numbers:\n" + failedSerials.join("\n")
    // Kembalikan input asli dengan format yang benar (SN + tab + tanggal jika ada)
    const failedLines = failedInputs.map((input) => {
      if (input.user_date) {
        return `${input.serial_number}\t${input.user_date}`
      } else {
        return input.serial_number
      }
    })
    serialNumbersEl.value = failedLines.join("\n")
    resultEl.className = "input-result-message error"
  }

  resultEl.innerText = message
  resultEl.style.display = "block"
}




// =================== REFRESH FUNCTIONS ===================
function refreshCurrentData() {
  const isMainTable =
    (inventoryType === "ECBS" && currentTable === "ecbs") || (inventoryType === "ECCT" && currentTable === "ecct")

  if (isMainTable) {
    showMainData()
  } else {
    showSecondaryData()
  }
}

// Backward compatibility aliases
function refreshCurrentTable() {
  refreshCurrentData()
}

// =================== WINDOW ONLOAD ===================
window.onload = () => {
  if (document.readyState !== "loading") {
    renderToolbar()
    showMainData()
  }
}

// Searchable Dropdown Class
class SearchableDropdown {
  constructor(containerId, options, hiddenInputId) {
      this.container = document.getElementById(containerId);
      if (!this.container) return;
      
      this.hiddenInput = document.getElementById(hiddenInputId);
      this.display = this.container.querySelector('.select-display');
      this.selectedSpan = this.container.querySelector('#dvc_code_selected');
      this.searchInput = this.container.querySelector('.search-input');
      this.optionsContainer = this.container.querySelector('.dropdown-options');
      this.options = options || [];
      this.filteredOptions = [...this.options];
      
      this.init();
  }
  
  init() {
      this.renderOptions();
      this.bindEvents();
  }
  
  renderOptions() {
      if (!this.optionsContainer) return;
      
      this.optionsContainer.innerHTML = '';
      
      // Add "All" option
      const allOption = document.createElement('div');
      allOption.className = 'dropdown-option';
      allOption.textContent = 'All';
      allOption.dataset.value = '';
      allOption.addEventListener('click', () => this.selectOption('', 'All'));
      this.optionsContainer.appendChild(allOption);
      
      // Add filtered options
      if (this.filteredOptions.length === 0) {
          const noResults = document.createElement('div');
          noResults.className = 'no-results';
          noResults.textContent = 'No results found';
          this.optionsContainer.appendChild(noResults);
      } else {
          this.filteredOptions.forEach(option => {
              const optionElement = document.createElement('div');
              optionElement.className = 'dropdown-option';
              optionElement.textContent = option.dvc_code;
              optionElement.dataset.value = option.dvc_code;
              optionElement.addEventListener('click', () => this.selectOption(option.dvc_code, option.dvc_code));
              this.optionsContainer.appendChild(optionElement);
          });
      }
  }
  
  bindEvents() {
      if (!this.display || !this.searchInput) return;
      
      this.display.addEventListener('click', (e) => {
          e.stopPropagation();
          this.toggle();
      });
      
      this.searchInput.addEventListener('input', (e) => {
          const searchTerm = e.target.value.toLowerCase();
          this.filteredOptions = this.options.filter(option => 
              option.dvc_code.toLowerCase().includes(searchTerm)
          );
          this.renderOptions();
      });
      
      this.container.addEventListener('click', (e) => {
          e.stopPropagation();
      });
      
      document.addEventListener('click', () => {
          this.close();
      });
      
      this.searchInput.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') {
              e.preventDefault();
          }
      });
  }
  
  toggle() {
      if (this.container.classList.contains('active')) {
          this.close();
      } else {
          this.open();
      }
  }
  
  open() {
      this.container.classList.add('active');
      if (this.searchInput) {
          this.searchInput.focus();
          this.searchInput.value = '';
      }
      this.filteredOptions = [...this.options];
      this.renderOptions();
  }
  
  close() {
      this.container.classList.remove('active');
  }
  
  selectOption(value, text) {
      if (this.selectedSpan) {
          this.selectedSpan.textContent = text;
      }
      if (this.hiddenInput) {
          this.hiddenInput.value = value;
      }
      this.close();
  }
}

// Initialize searchable dropdown setelah DOM ready
document.addEventListener('DOMContentLoaded', function() {
  // Cek apakah window.deviceCodes ada dan tidak kosong
  if (typeof window.deviceCodes !== 'undefined' && window.deviceCodes.length > 0) {
      new SearchableDropdown('dvc_code_dropdown', window.deviceCodes, 'dvc_code');
  }
});

// =================== INV_WEEK SPECIFIC FUNCTIONS ===================
function showInvWeekData() {
    // Use global currentYear and currentMonth variables
    if (currentYear && currentMonth) {
        console.log('Loading data for:', currentYear, currentMonth);
        loadInvWeekData(currentYear, currentMonth);
    } else {
        console.log('No year/month set, showing empty state');
        document.getElementById("show_data").innerHTML = '<div class="no-data"><p>Silakan klik tombol <strong>Input</strong> untuk generate periode mingguan.</p><p>Pilih tahun dan bulan, lalu klik Generate Periode.</p></div>';
    }
}

function loadInvWeekData(year, month) {
    const link = window.location.origin + '/cdummy/inventory/data/data_inv_week_show/' + year + '?month=' + month;
    console.log('Loading data from:', link);
    loadData(link);
}

function loadData(link) {
    console.log('loadData called with link:', link);
    
    // Show loading indicator
    document.getElementById("show_data").innerHTML = '<div style="padding: 20px; text-align: center;"><div class="loading-spinner"></div> Loading data...</div>';
    
    if (typeof window.$ !== "undefined") {
        console.log('Using jQuery load');
        window.$("#show_data").load(link, function(response, status, xhr) {
            if (status === "error") {
                console.error('jQuery load error:', xhr.status, xhr.statusText);
                document.getElementById("show_data").innerHTML = 
                    '<div style="padding: 20px; text-align: center; color: red;">Error loading data: ' + xhr.statusText + '</div>';
            }
        });
    } else {
        console.log('Using fetch');
        fetch(link)
            .then((response) => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.text();
            })
            .then((data) => {
                console.log('Data loaded successfully, length:', data.length);
                if (data.trim() === '') {
                    document.getElementById("show_data").innerHTML = 
                        '<div class="no-data"><p>Tidak ada data periode untuk bulan dan tahun yang dipilih.</p><p>Silakan generate periode terlebih dahulu.</p></div>';
                } else {
                    document.getElementById("show_data").innerHTML = data;
                }
            })
            .catch((error) => {
                console.error('Error loading data:', error);
                document.getElementById("show_data").innerHTML =
                    '<div style="padding: 20px; text-align: center; color: red;">Error loading data: ' + error.message + '</div>';
            });
    }
}

function generateInvWeekPeriods() {
    const year = document.getElementById('year').value;
    const month = document.getElementById('month').value;
    const loadingSpinner = document.getElementById('generate_loading_spinner');
    const modalResultDiv = document.getElementById('modal_result_message');
    
    console.log('generateInvWeekPeriods called with year:', year, 'month:', month);
    
    if (!year || !month) {
        showModalMessage('Pilih tahun dan bulan terlebih dahulu', 'error');
        return;
    }
    
    // Show loading
    loadingSpinner.style.display = 'inline-block';
    showModalMessage('Generating periods dengan logika 27-26, waktu 08:00-17:00, dan minggu kerja Senin-Jumat...', 'success');
    
    const requestData = {
        year: parseInt(year),
        month: parseInt(month)
    };
    
    console.log('Sending request data:', requestData);
    
    fetch(window.location.origin + '/cdummy/inventory/generate_inv_week_periods', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Generate response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Generate response data:', data);
        loadingSpinner.style.display = 'none';
        
        if (data.success) {
            showModalMessage('Periode berhasil di-generate dengan waktu 08:00-17:00 dan minggu kerja Senin-Jumat', 'success');
            currentYear = year;
            currentMonth = month;
            
            // Reload data after successful generation
            setTimeout(() => {
                loadInvWeekData(year, month);
            }, 1000);
            
            // Auto close modal after 3 seconds
            setTimeout(() => {
                closeModal('modal_input_all');
            }, 3000);
        } else {
            showModalMessage(data.message || 'Gagal generate periode', 'error');
        }
    })
    .catch(error => {
        console.error('Generate error:', error);
        loadingSpinner.style.display = 'none';
        showModalMessage('Error: ' + error.message, 'error');
    });
}

function exportInvWeekData() {
    const year = currentYear || new Date().getFullYear();
    const month = currentMonth || (new Date().getMonth() + 1);
    
    if (!currentYear || !currentMonth) {
        showMessage('Generate periode terlebih dahulu sebelum export', 'error');
        return;
    }
    
    window.open(window.location.origin + '/cdummy/inventory/export_inv_week?year=' + year + '&month=' + month, '_blank');
}

function showMessage(message, type) {
    const element = document.getElementById('result_message');
    if (element) {
        element.textContent = message;
        element.className = 'input-result-message ' + type;
        element.style.display = 'block';
        
        setTimeout(() => {
            element.style.display = 'none';
        }, 5000);
    }
}

function showModalMessage(message, type) {
    const element = document.getElementById('modal_result_message');
    if (element) {
        element.textContent = message;
        element.className = 'input-result-message ' + type;
        element.style.display = 'block';
    }
}

function renderInvWeekInputMode() {
    // Get the modal body
    const modalBody = document.querySelector('#modal_input_all .modal-body');
    if (!modalBody) return;
    
    // Render the inv_week period generator interface
    modalBody.innerHTML = `
        <div class="form-group">
            <span class="form-hint">Tahun</span>
            <select class="select" id="year">
                <option value="">Pilih Tahun</option>
                ${generateYearOptions()}
            </select>
        </div>
        <div class="form-group">
            <span class="form-hint">Bulan</span>
            <select class="select" id="month">
                <option value="">Pilih Bulan</option>
                ${generateMonthOptions()}
            </select>
        </div>
        <div id="modal_result_message" class="input-result-message"></div>
    `;
    
    // Update modal title and footer
    const modalTitle = document.querySelector('#modal_input_all .modal-title');
    if (modalTitle) {
        modalTitle.textContent = 'Generate Periode Mingguan';
    }
    
    const modalFooter = document.querySelector('#modal_input_all .modal-footer');
    if (modalFooter) {
        modalFooter.innerHTML = `
            <button class="btn btn-secondary" onclick="closeModal('modal_input_all')">Batal</button>
            <button class="btn btn-primary" onclick="generateInvWeekPeriods()">
                Generate Periode <span id="generate_loading_spinner" class="loading-spinner" style="display:none;"></span>
            </button>
        `;
    }
}

function generateYearOptions() {
    const currentYear = new Date().getFullYear();
    let options = '';
    for (let i = currentYear - 2; i <= currentYear + 2; i++) {
        const selected = i === currentYear ? 'selected' : '';
        options += `<option value="${i}" ${selected}>${i}</option>`;
    }
    return options;
}

function generateMonthOptions() {
    const currentMonth = new Date().getMonth() + 1;
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    let options = '';
    months.forEach((month, index) => {
        const monthNumber = index + 1;
        const selected = monthNumber === currentMonth ? 'selected' : '';
        options += `<option value="${monthNumber}" ${selected}>${month}</option>`;
    });
    return options;
}

</script>