<!-- Container -->
<div class="container-fixed">
    <div class="card min-w-full">
        <div class="card-header">
            <h3 class="card-title" style="width: 100%;">
                Inventory Report
                
                <!-- Toggle Buttons - Horizontal Layout -->
                <div style="float: right; display: flex; align-items: center; gap: 15px;">
                    <!-- Generate Data Button -->
                    <button class="btn btn-sm btn-success" id="btn_generate" onclick="generateInventoryReport()" title="Generate inventory report data from database">
                        <i class="ki-filled ki-setting !text-base"></i>Generate Data
                    </button>
                    
                    <!-- Input On PMS Button -->
                    <button class="btn btn-sm btn-primary" id="btn_input_pms" onclick="showInputPmsModal()">
                        <i class="ki-filled ki-plus !text-base"></i>Input On PMS
                    </button>
                    
                    <!-- ECBS/ECCT Group -->
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary" id="btn_ecbs" onclick="selectTech('ecbs')">ECBS</button>
                        <button class="btn btn-sm btn-light" id="btn_ecct" onclick="selectTech('ecct')">ECCT</button>
                    </div>
                    
                    <!-- APP/OSC Group -->
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary" id="btn_app" onclick="selectType('app')">APP</button>
                        <button class="btn btn-sm btn-light" id="btn_osc" onclick="selectType('osc')">OSC</button>
                    </div>
                    
                    <!-- Export Button -->
                    <a class="btn btn-sm btn-icon-lg btn-light" onclick="exportData();">
                        <i class="ki-filled ki-exit-down !text-base"></i>Export
                    </a>
                </div>
            </h3>
        </div>
        <div id="show_data">Loading...</div>
    </div>
</div>

<!-- Input On PMS Modal -->
<div class="modal fade" id="inputPmsModal" tabindex="-1" role="dialog" aria-labelledby="inputPmsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputPmsModalLabel">Input On PMS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pmsInputForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select_week">Select Week Period:</label>
                                <select class="form-control" id="select_week" name="select_week" required>
                                    <option value="">-- Select Week --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select_device">Select Device:</label>
                                <select class="form-control" id="select_device" name="select_device" required>
                                    <option value="">-- Select Device --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select_size">Size:</label>
                                <select class="form-control" id="select_size" name="select_size" required>
                                    <option value="">-- Select Size --</option>
                                    <option value="xs">XS</option>
                                    <option value="s">S</option>
                                    <option value="m">M</option>
                                    <option value="l">L</option>
                                    <option value="xl">XL</option>
                                    <option value="xxl">XXL</option>
                                    <option value="3xl">3XL</option>
                                    <option value="all">ALL</option>
                                    <option value="cus">CUS</option>
                                    <option value="-">-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select_color">Color:</label>
                                <select class="form-control" id="select_color" name="select_color" required>
                                    <option value="">-- Select Color --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select_qc">Quality Control:</label>
                                <select class="form-control" id="select_qc" name="select_qc" required>
                                    <option value="">-- Select QC --</option>
                                    <option value="LN">LN</option>
                                    <option value="DN">DN</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_on_pms">On PMS Quantity:</label>
                        <input type="number" class="form-control" id="input_on_pms" name="input_on_pms" min="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveOnPms()">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var selectedTech = 'ecbs';
    var selectedType = 'app';
    
    function selectTech(tech) {
        selectedTech = tech;
        
        // Update button states
        document.getElementById('btn_ecbs').className = tech === 'ecbs' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light';
        document.getElementById('btn_ecct').className = tech === 'ecct' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light';
        
        showData();
    }
    
    function selectType(type) {
        selectedType = type;
        
        // Update button states
        document.getElementById('btn_app').className = type === 'app' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light';
        document.getElementById('btn_osc').className = type === 'osc' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-light';
        
        showData();
    }
    
    function showData() {
        var link = "<?php echo base_url(); ?>inventory/inv_report_data/report_" + selectedTech + "_" + selectedType + "_show";
        
        document.getElementById('show_data').innerHTML = '<div style="text-align: center; padding: 20px;">Loading data...</div>';
        $("#show_data").load(link);
    }
    
    function exportData() {
        var link = "<?php echo base_url(); ?>inventory/inv_report_data/report_" + selectedTech + "_" + selectedType + "_export";
        window.open(link, '_blank').focus();
    }
    
    // Generate inventory report data
    function generateInventoryReport() {
        if (!confirm('This will generate inventory report data from existing tables. This may take some time. Continue?')) {
            return;
        }
        
        var btn = document.getElementById('btn_generate');
        btn.disabled = true;
        btn.innerHTML = '<i class="ki-filled ki-loading !text-base"></i>Generating...';
        
        $.post("<?php echo base_url(); ?>inventory/generate_inventory_report", function(response) {
            if (response.success) {
                showToast('Inventory report data generated successfully', 'success');
                showData(); // Refresh the table
            } else {
                showToast('Failed to generate: ' + (response.message || 'Unknown error'), 'error');
            }
        }, 'json').fail(function() {
            showToast('Failed to generate inventory report data', 'error');
        }).always(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="ki-filled ki-setting !text-base"></i>Generate Data';
        });
    }
    
    function showInputPmsModal() {
        // Load week periods and devices data
        loadWeekPeriods();
        loadDevices();
        $('#inputPmsModal').modal('show');
    }
    
    function loadWeekPeriods() {
        $.get("<?php echo base_url(); ?>inventory/get_week_periods", function(data) {
            var options = '<option value="">-- Select Week --</option>';
            if (data.success && data.weeks) {
                data.weeks.forEach(function(week) {
                    var startDate = new Date(week.date_start);
                    var endDate = new Date(week.date_finish);
                    var label = 'W' + week.period_w + '/' + week.period_m + '/' + week.period_y + ' (' + 
                               startDate.toLocaleDateString() + ' - ' + endDate.toLocaleDateString() + ')';
                    options += '<option value="' + week.id_week + '">' + label + '</option>';
                });
            }
            $('#select_week').html(options);
        }, 'json');
    }
    
    function loadDevices() {
        $.get("<?php echo base_url(); ?>inventory/get_devices_for_report", {tech: selectedTech, type: selectedType}, function(data) {
            var options = '<option value="">-- Select Device --</option>';
            if (data.success && data.devices) {
                data.devices.forEach(function(device) {
                    options += '<option value="' + device.id_dvc + '">' + device.dvc_code + ' - ' + device.dvc_name + '</option>';
                });
            }
            $('#select_device').html(options);
        }, 'json');
    }
    
    // Update color options based on selected device
    $('#select_device').change(function() {
        var deviceId = $(this).val();
        if (deviceId) {
            $.get("<?php echo base_url(); ?>inventory/get_device_colors", {id_dvc: deviceId}, function(data) {
                var options = '<option value="">-- Select Color --</option>';
                if (data.success && data.colors) {
                    data.colors.forEach(function(color) {
                        var displayColor = color === '' ? '(Empty)' : color;
                        options += '<option value="' + color + '">' + displayColor + '</option>';
                    });
                }
                $('#select_color').html(options);
            }, 'json');
        } else {
            $('#select_color').html('<option value="">-- Select Color --</option>');
        }
    });
    
    function saveOnPms() {
        var formData = {
            id_week: $('#select_week').val(),
            id_dvc: $('#select_device').val(),
            dvc_size: $('#select_size').val(),
            dvc_col: $('#select_color').val(),
            dvc_qc: $('#select_qc').val(),
            on_pms: $('#input_on_pms').val()
        };
        
        // Validate form
        if (!formData.id_week || !formData.id_dvc || !formData.dvc_size || 
            formData.dvc_col === '' || !formData.dvc_qc || !formData.on_pms) {
            showToast('Please fill all required fields', 'error');
            return;
        }
        
        $.post("<?php echo base_url(); ?>inventory/save_on_pms", formData, function(response) {
            if (response.success) {
                showToast('On PMS data saved successfully', 'success');
                $('#inputPmsModal').modal('hide');
                $('#pmsInputForm')[0].reset();
                showData(); // Refresh the table
            } else {
                showToast('Failed to save: ' + (response.message || 'Unknown error'), 'error');
            }
        }, 'json').fail(function() {
            showToast('Failed to save data', 'error');
        });
    }
    
    function showToast(msg, type = 'success') {
        var t = document.getElementById('toast') || document.createElement('div');
        t.id = 'toast';
        t.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:12px 20px;border-radius:6px;color:white;z-index:9999;transition:all 0.3s;';
        t.style.backgroundColor = type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#10b981';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }
    
    // Load default data on page load
    $(document).ready(function() {
        showData();
    });
</script>