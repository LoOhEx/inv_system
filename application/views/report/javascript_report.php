<script type="text/javascript">
var currentEditMode = false;

// Simple toast notification - positioned at bottom
function showToast(message, type = 'success') {
    var toast = document.getElementById('toast') || document.createElement('div');
    toast.id = 'toast';
    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;padding:12px 20px;border-radius:6px;color:white;z-index:9999;transition:all 0.3s;';
    toast.style.backgroundColor = type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#10b981';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

// Toggle edit mode
function toggleEditMode() {
    if (currentEditMode && hasChanges() && !confirm('Discard changes?')) return;
    
    currentEditMode = !currentEditMode;
    $('.needs-input').prop('disabled', !currentEditMode);
    
    if (currentEditMode) {
        $('#editModeBtn').text('Cancel').removeClass('btn-primary').addClass('btn-secondary');
        $('#saveAllDataBtn').show();
        // Store original values
            $('.needs-input').each(function() { $(this).data('original', $(this).val()); });
    } else {
        $('#editModeBtn').text('Edit').removeClass('btn-secondary').addClass('btn-primary');
        $('#saveAllDataBtn').hide();
        // Restore if cancelled
        if (hasChanges()) {
            $('.needs-input').each(function() { $(this).val($(this).data('original') || 0); });
            calculateTotals();
        }
    }
}

// Check if there are unsaved changes
function hasChanges() {
    var changed = false;
    $('.needs-input').each(function() {
        if (($(this).data('original') || 0) != ($(this).val() || 0)) {
            changed = true;
            return false;
        }
    });
    return changed;
}

function calculateTotals() {
    var sizes = ['xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', 'all', 'cus'];
    var qc_types = ['LN', 'DN'];
    var grandTotal = 0;
    var rowSubtotals = {};
    
    // Calculate row subtotals
    $('.needs-input').each(function() {
        var key = $(this).data('id-dvc') + '_' + ($(this).data('key') || $(this).data('color'));
        rowSubtotals[key] = (rowSubtotals[key] || 0) + (parseInt($(this).val()) || 0);
    });
    
    // Update row subtotals and calculate grand total
    for (var key in rowSubtotals) {
        $('#subtotal_' + key).text(rowSubtotals[key]);
        grandTotal += rowSubtotals[key];
    }
    
    // Update size+qc totals and percentages
    sizes.forEach(function(size) {
        qc_types.forEach(function(qc) {
            var sizeQcTotal = 0;
            $('.needs-input[data-size="' + size + '"][data-qc="' + qc + '"]').each(function() {
                sizeQcTotal += parseInt($(this).val()) || 0;
            });
            $('#total_' + size + '_' + qc).text(sizeQcTotal);
            $('#percent_' + size + '_' + qc).text(grandTotal > 0 ? Math.round((sizeQcTotal / grandTotal) * 1000) / 10 : 0);
        });
    });
    
    // Update grand total and row percentages
    $('#grand_total').text(grandTotal);
    for (var key in rowSubtotals) {
        $('#percentage_' + key).text(grandTotal > 0 ? Math.round((rowSubtotals[key] / grandTotal) * 1000) / 10 : 0);
    }
}

function saveAllData() {
    var data = [];
    var hasData = false;
    var actualChanges = 0;
    
    $('.needs-input').each(function() {
        var qty = parseInt($(this).val()) || 0;
        var original = parseInt($(this).data('original')) || 0;
        
        if (qty > 0 || original > 0) {
            hasData = true;
            
            if (qty !== original) {
                actualChanges++;
            }
            
            data.push({
                id_dvc: $(this).data('id-dvc'),
                dvc_size: $(this).data('size'),
                dvc_col: $(this).data('color'),
                dvc_qc: $(this).data('qc'),
                needs_qty: qty,
                original_qty: original
            });
        }
    });
    
    if (!hasData) {
        showToast('No data to save', 'warning');
        return;
    }
    
    if (actualChanges === 0) {
        showToast('No changes detected', 'warning');
        return;
    }
    
    var $btn = $('#saveAllDataBtn');
    $btn.prop('disabled', true).html('Saving...');
    
    var batchSize = 100;
    var batches = [];
    for (var i = 0; i < data.length; i += batchSize) {
        batches.push(data.slice(i, i + batchSize));
    }
    
    var completed = 0;
    var totalActions = {inserted: 0, updated: 0, deleted: 0, unchanged: 0};
    
    function processBatch(index) {
        if (index >= batches.length) {
            $btn.prop('disabled', false).html('Save All Data');
            var actions = [];
            if (totalActions.inserted) actions.push(totalActions.inserted + ' added');
            if (totalActions.updated) actions.push(totalActions.updated + ' updated');  
            if (totalActions.deleted) actions.push(totalActions.deleted + ' removed');
            
            showToast('Saved successfully' + (actions.length ? ' (' + actions.join(', ') + ')' : ''));
            
            $('.needs-input').each(function() { $(this).data('original', $(this).val()); });
            setTimeout(() => toggleEditMode(), 500);
            return;
        }
        
        $.ajax({
            url: '<?php echo base_url(); ?>inventory/save_all_needs_data',
            type: 'POST',
            data: {data: batches[index]},
            dataType: 'json',
            success: function(response) {
                if (response.success !== false && response.actions) {
                    ['inserted', 'updated', 'deleted', 'unchanged'].forEach(action => {
                        if (response.actions[action]) totalActions[action] += response.actions[action];
                    });
                    processBatch(index + 1);
                } else {
                    $btn.prop('disabled', false).html('Save All Data');
                    showToast('Save failed', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('Save All Data');
                showToast('Save failed', 'error');
            }
        });
    }
    
    processBatch(0);
}

function setEditMode(mode) {
    currentEditMode = mode;
    toggleEditMode();
    if (!mode) toggleEditMode();
}

$(document).ready(function() {
    calculateTotals();
    setEditMode(false);
    
    $(document).on('input', '.needs-input', calculateTotals);
    
    $(document).keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
            e.preventDefault();
            if (currentEditMode) saveAllData();
        }
        if (e.keyCode === 27 && currentEditMode) toggleEditMode();
    });
});
</script>