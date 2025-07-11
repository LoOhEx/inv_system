<!-- Container -->
<div class="container-fixed">
<div class="card min-w-full">
    <div class="card-header">
        <h3 class="card-title" style="width: 100%;">
            Data Item Inventory
            <div class="input-group input-sm" style="float: right;">
                <input class="input input-sm" placeholder="Search" type="text" value="" id="key_item" />
                <span class="btn btn-light btn-sm" data-modal-toggle="#modal_filter_item">Filter</span>
                <span class="btn btn-primary btn-sm" onclick="showDataItem();" id="btn_search_item">Search</span>
                
                <!-- Tombol Input dengan dropdown -->
                <div style="position: relative; display: inline-block; margin-left: 5px;">
                    <button class="btn btn-sm" style="background: #28a745; color: white;" onclick="toggleInputDropdown()" id="input_btn" type="button">Input ▼</button>
                    <div id="input_dropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); min-width: 120px; z-index: 1000; margin-top: 2px;">
                        <button type="button" onclick="openInputModal('in')" style="display: block; width: 100%; padding: 8px 15px; color: #333; text-decoration: none; font-size: 14px; border: none; background: none; text-align: left; border-bottom: 1px solid #f0f0f0; cursor: pointer;">In</button>
                        <button type="button" onclick="openInputModal('out')" style="display: block; width: 100%; padding: 8px 15px; color: #333; text-decoration: none; font-size: 14px; border: none; background: none; text-align: left; border-bottom: 1px solid #f0f0f0; cursor: pointer;">Out</button>
                        <button type="button" onclick="openInputModal('move')" style="display: block; width: 100%; padding: 8px 15px; color: #333; text-decoration: none; font-size: 14px; border: none; background: none; text-align: left; cursor: pointer;">Move</button>
                    </div>
                </div>
            </div>
            <a class="btn btn-sm btn-icon-lg btn-light" onclick="showDataItem('export');" style="float: right;">
                <i class="ki-filled ki-exit-down !text-base"></i>Export
            </a>
        </h3>
    </div>
    <div id="show_data_item"></div>
</div>
</div>

<!-- Modal Filter - ORIGINAL -->
<div class="modal" data-modal="true" id="modal_filter_item">
<div class="modal-content max-w-[600px] top-[10%]">
    <div class="modal-header">
        <h3 class="modal-title">Data Filter</h3>
        <button class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
            <i class="ki-outline ki-cross"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="grid lg:grid-cols-3 gap-2.5 lg:gap-2.5">
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Device Size</span>
                <select class="select" id="dvc_size">
                    <option value="">All</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                    <option value="3XL">3XL</option>
                    <option value="ALL">ALL SIZE</option>
                    <option value="Cus">Cus</option>
                </select>
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Device Color</span>
                <select class="select" id="dvc_col">
                    <option value="">All</option>
                    <option value="Dark Gray">Dark Gray</option>
                    <option value="Black">Black</option>
                    <option value="Grey">Grey</option>
                    <option value="Blue Navy">Blue Navy</option>
                    <option value="Green Army">Green Army</option>
                    <option value="Red Maroon">Red Maroon</option>
                    <option value="Custom">Custom</option>
                    <option value="-">-</option>
                </select>
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">QC Status</span>
                <select class="select" id="dvc_qc">
                    <option value="">All</option>
                    <option value="0">Pending</option>
                    <option value="1">Passed</option>
                    <option value="2">Failed</option>
                </select>
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Date From</span>
                <input class="input calendar" type="text" value="" id="date_from" />
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Date To</span>
                <input class="input calendar" type="text" value="" id="date_to" />
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Location</span>
                <select class="select" id="loc_move">
                    <option value="">All</option>
                    <option value="Lantai 2">Lantai 2</option>
                    <option value="Bang Toni">Bang Toni</option>
                    <option value="Om Bob">Om Bob</option>
                    <option value="Rekanan">Rekanan</option>
                    <option value="LN">LN</option>
                    <option value="ECBS">ECBS</option>
                    <option value="LN Office">LN Office</option>
                    <option value="Lantai 1">Lantai 1</option>
                    <option value="Unknow">Unknow</option>
                </select>
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Sort By</span>
                <select class="select" id="sort_by">
                    <option value="">None</option>
                    <option value="id_act_asc">ID ASC</option>
                    <option value="id_act_desc">ID DESC</option>
                    <option value="dvc_sn_asc">Serial Number ASC</option>
                    <option value="dvc_sn_desc">Serial Number DESC</option>
                </select>
            </div>
            <div class="col-span-1 lg:col-span-1">
                <span class="form-hint">Data View</span>
                <select class="select" id="data_view_item">
                    <option value="5">5</option>
                    <option value="10" selected="selected">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-body m_foot">
        <div class="flex gap-4" style="float: right;">
            <button class="btn btn-light" data-modal-dismiss="true">Cancel</button>
            <button class="btn btn-primary" data-modal-dismiss="true" onclick="showDataItem();" style="float:right;">Submit</button>
        </div>
    </div>
</div>
</div>

<!-- Overlay untuk modal input -->
<div id="modal_overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    
    <!-- Modal Input In -->
    <div id="modal_input_in" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; border-radius: 12px 12px 0 0;">
            <h3 style="font-size: 18px; font-weight: 600; color: #333; margin: 0;">📥 Input In - Barang Masuk</h3>
            <button onclick="closeInputModal()" style="background: #dc3545; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-size: 16px;">×</button>
        </div>
        <div style="padding: 30px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px;">Serial Number *</label>
                <input type="text" id="in_serial_number" placeholder="Contoh: ABC12D34E567890" style="width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s;" onfocus="this.style.borderColor='#1677ff'" onblur="this.style.borderColor='#ddd'" />
                <small style="color: #666; font-size: 12px;">Minimal 11 karakter untuk parsing otomatis</small>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px;">QC Status *</label>
                <select id="in_qc_status" style="width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="0">🟡 Pending</option>
                    <option value="1">✅ Passed</option>
                    <option value="2">❌ Failed</option>
                </select>
            </div>
        </div>
        <div style="padding: 20px 30px; border-top: 1px solid #f0f0f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8f9fa; border-radius: 0 0 12px 12px;">
            <button onclick="closeInputModal()" style="background: #6c757d; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">Cancel</button>
            <button onclick="submitInput('in')" style="background: #28a745; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">💾 Submit</button>
        </div>
    </div>

    <!-- Modal Input Out -->
    <div id="modal_input_out" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; border-radius: 12px 12px 0 0;">
            <h3 style="font-size: 18px; font-weight: 600; color: #333; margin: 0;">📤 Input Out - Barang Keluar</h3>
            <button onclick="closeInputModal()" style="background: #dc3545; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-size: 16px;">×</button>
        </div>
        <div style="padding: 30px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px;">Serial Number *</label>
                <input type="text" id="out_serial_number" placeholder="Masukkan nomor seri yang akan keluar" style="width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s;" onfocus="this.style.borderColor='#1677ff'" onblur="this.style.borderColor='#ddd'" />
                <small style="color: #666; font-size: 12px;">Serial number harus sudah ada di database</small>
            </div>
        </div>
        <div style="padding: 20px 30px; border-top: 1px solid #f0f0f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8f9fa; border-radius: 0 0 12px 12px;">
            <button onclick="closeInputModal()" style="background: #6c757d; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">Cancel</button>
            <button onclick="submitInput('out')" style="background: #dc3545; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">📤 Submit</button>
        </div>
    </div>

    <div id="modal_input_move" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <div style="padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; border-radius: 12px 12px 0 0;">
            <h3 style="font-size: 18px; font-weight: 600; color: #333; margin: 0;">🚚 Input Move - Pindah Lokasi</h3>
            <button onclick="closeInputModal()" style="background: #dc3545; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; font-size: 16px;">×</button>
        </div>
        <div style="padding: 30px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px;">Serial Number *</label>
                <input type="text" id="move_serial_number" placeholder="Masukkan nomor seri yang akan dipindah" style="width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s;" onfocus="this.style.borderColor='#1677ff'" onblur="this.style.borderColor='#ddd'" />
                <small style="color: #666; font-size: 12px;">Serial number harus sudah ada di database</small>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px;">Lokasi Tujuan *</label>
                <select id="move_location" style="width: 100%; padding: 12px 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="">-- Pilih Lokasi Tujuan --</option>
                    <option value="Lantai 2">🏢 Lantai 2</option>
                    <option value="Bang Toni">👨‍💼 Bang Toni</option>
                    <option value="Om Bob">👨‍💼 Om Bob</option>
                    <option value="Rekanan">🤝 Rekanan</option>
                    <option value="LN">🏭 LN</option>
                    <option value="ECBS">🏭 ECBS</option>
                    <option value="LN Office">🏢 LN Office</option>
                    <option value="Lantai 1">🏢 Lantai 1</option>
                    <option value="Unknow">❓ Unknown</option>
                </select>
            </div>
        </div>
        <div style="padding: 20px 30px; border-top: 1px solid #f0f0f0; display: flex; justify-content: flex-end; gap: 10px; background: #f8f9fa; border-radius: 0 0 12px 12px;">
            <button onclick="closeInputModal()" style="background: #6c757d; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">Cancel</button>
            <button onclick="submitInput('move')" style="background: #17a2b8; color: white; border: none; border-radius: 6px; padding: 10px 20px; cursor: pointer; font-weight: 500;">🚚 Submit</button>
        </div>
    </div>

</div>

<!-- Loading element yang hilang -->
<div id="loading" style="display: none;">
<div style="text-align: center; padding: 40px;">
    <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #1677ff; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    <p style="margin-top: 10px;">Loading data...</p>
</div>
</div>

<script type="text/javascript">
// Function untuk encode URL yang hilang
function encodeUrl(str) {
    return encodeURIComponent(str || '');
}

// ORIGINAL FUNCTION - JANGAN DIUBAH
function showDataItem(page = '', in_sort = '') {
    var loading = document.getElementById('loading').innerHTML;
    var val = "?";
    const arr = ["key_item", "dvc_size", "dvc_col", "dvc_qc", "date_from", "date_to", "loc_move", "sort_by", "data_view_item"];
    
    for (let i = 0; i < arr.length; i++) {
        var element = document.getElementById(arr[i]);
        if (element) {
            val = val + "&" + arr[i] + "=" + encodeUrl(element.value);
        }
    }
    
    if (page == 'export') {
        var link = "<?php echo $config['url_menu']; ?>data/data_item_export" + val;
        window.open(link, '_blank').focus();
    } else {
        val = val + "&p=" + page;
        var link = "<?php echo $config['url_menu']; ?>data/data_item_show" + val;
        document.getElementById('show_data_item').innerHTML = loading;
        
        // Perbaiki AJAX call - tambahkan fallback jika jQuery tidak ada
        if (typeof $ !== 'undefined') {
            $("#show_data_item").load(link);
        } else {
            // Fallback menggunakan fetch
            fetch(link)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('show_data_item').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('show_data_item').innerHTML = '<div style="padding: 20px; text-align: center; color: red;">Error loading data</div>';
                });
        }
    }
}

// NEW FUNCTIONS - Input functionality
function toggleInputDropdown() {
    const dropdown = document.getElementById('input_dropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function openInputModal(type) {
    // Hide dropdown
    document.getElementById('input_dropdown').style.display = 'none';
    
    // Show overlay and specific modal
    document.getElementById('modal_overlay').style.display = 'block';
    document.getElementById('modal_input_' + type).style.display = 'block';
    
    // Focus on first input
    setTimeout(function() {
        if (type === 'in') {
            document.getElementById('in_serial_number').focus();
        } else if (type === 'out') {
            document.getElementById('out_serial_number').focus();
        } else if (type === 'move') {
            document.getElementById('move_serial_number').focus();
        }
    }, 100);
}

function closeInputModal() {
    document.getElementById('modal_overlay').style.display = 'none';
    document.getElementById('modal_input_in').style.display = 'none';
    document.getElementById('modal_input_out').style.display = 'none';
    document.getElementById('modal_input_move').style.display = 'none';
}

function submitInput(type) {
    let data = {};
    let url = "<?php echo $config['url_menu']; ?>input_process";
    
    if (type === 'in') {
        data = {
            type: 'in',
            serial_number: document.getElementById('in_serial_number').value.trim(),
            qc_status: document.getElementById('in_qc_status').value
        };
    } else if (type === 'out') {
        data = {
            type: 'out',
            serial_number: document.getElementById('out_serial_number').value.trim()
        };
    } else if (type === 'move') {
        data = {
            type: 'move',
            serial_number: document.getElementById('move_serial_number').value.trim(),
            location: document.getElementById('move_location').value
        };
    }

    if (!data.serial_number) {
        alert('⚠️ Serial number harus diisi!');
        return;
    }
    
    if (type === 'move' && !data.location) {
        alert('⚠️ Lokasi harus dipilih!');
        return;
    }

    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Processing...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('✅ ' + result.message);
            closeInputModal();
            showDataItem();

            if (type === 'in') {
                document.getElementById('in_serial_number').value = '';
                document.getElementById('in_qc_status').value = '0';
            } else if (type === 'out') {
                document.getElementById('out_serial_number').value = '';
            } else if (type === 'move') {
                document.getElementById('move_serial_number').value = '';
                document.getElementById('move_location').value = '';
            }
        } else {
            alert('❌ Error: ' + result.message);
        }
    })
    .catch(error => {
        alert('❌ Error: ' + error.message);
    })
    .finally(() => {
        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('input_dropdown');
    const button = event.target.closest('#input_btn');
    if (!button && dropdown) {
        dropdown.style.display = 'none';
    }
});

document.getElementById('modal_overlay').addEventListener('click', function(event) {
    if (event.target === this) {
        closeInputModal();
    }
});

window.onload = function() {
    showDataItem();
}

const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
