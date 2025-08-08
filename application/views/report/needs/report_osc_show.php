<?php
$sizes = array('xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', 'all', 'cus');
$qc_types = array('LN', 'DN');
$model_data = isset($data) && is_array($data) ? $data : array();
$existing_needs = isset($existing_needs) && is_array($existing_needs) ? $existing_needs : array();

// Function to get existing value - FIXED: Use proper QC values
function getExistingValue($existing_needs, $id_dvc, $size, $color, $qc) {
    // QC should be 'LN' or 'DN', not from id_dvc
    $key = $id_dvc . '_' . $size . '_' . $color . '_' . $qc;
    return isset($existing_needs[$key]) ? $existing_needs[$key] : 0;
}

// Function to determine color based on device properties
function getDeviceColor($item) {
    $dvc_code = strtoupper($item['dvc_code']);
    $dvc_tech = strtolower($item['dvc_tech']);
    $dvc_type = strtoupper($item['dvc_type']);
    
    // For VOH devices, return null to use multiple colors
    if (stripos($dvc_code, 'VOH') === 0) {
        return null;
    }
    
    // For non-VOH devices, determine color based on tech and type
    if ($dvc_tech == 'ecct' && $dvc_type == 'APP') {
        return 'Dark Grey';
    } elseif ($dvc_tech == 'ecbs' && $dvc_type == 'APP') {
        return 'Black';
    } elseif ($dvc_type == 'OSC') {
        return ' '; // Empty string for OSC
    }
    
    // Default case - no default color
    return null;
}
?>

<div class="card-table">
    <div class="table-responsive">
        <table class="table table-border align-middle text-gray-700 text-s compact-table">
            <thead>
                <tr>
                    <th align="center" rowspan="2">No</th>
                    <th align="center" rowspan="2">Nama Barang</th>
                    <th align="center" rowspan="2">Kode</th>
                    <?php foreach ($sizes as $sz) { ?>
                        <th align="center" colspan="2"><?php echo strtoupper($sz); ?></th>
                    <?php } ?>
                    <th align="center" rowspan="2">Subtotal</th>
                    <th align="center" rowspan="2">%</th>
                </tr>
                <tr>
                    <?php foreach ($sizes as $sz) { ?>
                        <th align="center" style="font-size:11px;">LN</th>
                        <th align="center" style="font-size:11px;">DN</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($model_data) && !empty($model_data)) {
                    $no = 0;
                    $grouped_data = array();
                    $current_group = null;
                    
                    foreach ($model_data as $row) {
                        if ($current_group === null || $current_group['dvc_name'] !== $row['dvc_name']) {
                            if ($current_group !== null) {
                                $grouped_data[] = $current_group;
                            }
                            $current_group = array(
                                'dvc_name' => $row['dvc_name'],
                                'rows' => array($row),
                                'rowspan' => 1
                            );
                        } else {
                            $current_group['rows'][] = $row;
                            $current_group['rowspan']++;
                        }
                    }
                    
                    if ($current_group !== null) {
                        $grouped_data[] = $current_group;
                    }
                    
                    foreach ($grouped_data as $group) {
                        $first_row = true;
                        
                        foreach ($group['rows'] as $row) {
                            $no++;
                            $device_color = getDeviceColor($row);
                            $assigned_color = $device_color;
                            $color_display = $assigned_color === ' ' ? 'No Color' : ($assigned_color ? $assigned_color : 'No Color');
                            $sanitized_color = $assigned_color ? str_replace(' ', '-', strtolower($assigned_color)) : 'no-color';
                ?>
                <tr>
                    <td align="center"><?php echo $no; ?></td>
                    <?php if ($first_row) { ?>
                        <td align="left" rowspan="<?php echo $group['rowspan']; ?>"><?php echo htmlspecialchars($group['dvc_name']); ?></td>
                    <?php } ?>
                    <td align="center"><?php echo htmlspecialchars($row['dvc_code']); ?></td>
                    <?php foreach ($sizes as $sz) {
                        foreach ($qc_types as $qc) {
                            $input_id = $row['dvc_code'] . '_' . $sz . '_' . $sanitized_color . '_' . $qc;
                            $existing_value = getExistingValue($existing_needs, $row['id_dvc'], $sz, ($assigned_color ? $assigned_color : ' '), $qc);
                    ?>
                            <td align="center">
                                <input type="number"
                                        class="form-control form-control-sm needs-input"
                                        id="<?php echo $input_id; ?>"
                                        value="<?php echo $existing_value; ?>"
                                        min="0"
                                        style="width: 45px; text-align: center;"
                                       data-id-dvc="<?php echo $row['id_dvc']; ?>"
                                       data-size="<?php echo $sz; ?>"
                                       data-color="<?php echo ($assigned_color ? $assigned_color : ' '); ?>"
                                       data-key="<?php echo $sanitized_color; ?>"
                                       data-qc="<?php echo $qc; ?>"
                                       onchange="calculateTotals()">
                            </td>
                    <?php } } ?>
                    <td align="center"><strong><span id="subtotal_<?php echo $row['id_dvc']; ?>_<?php echo $sanitized_color; ?>">0</span></strong></td>
                    <td align="center"><span id="percentage_<?php echo $row['id_dvc']; ?>_<?php echo $sanitized_color; ?>">0</span>%</td>
                </tr>
                <?php
                            $first_row = false;
                        }
                    }
                } else {
                ?>
                <tr>
                    <td align="center" colspan="<?php echo 3 + (count($sizes) * 2) + 2; ?>"><i>No OSC Data Found</i></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #00bfff; color: white; font-weight: bold;">
                    <td align="center" colspan="3">TOTAL</td>
                    <?php foreach ($sizes as $sz) { 
                        foreach ($qc_types as $qc) { ?>
                            <td align="center"><span id="total_<?php echo $sz; ?>_<?php echo $qc; ?>">0</span></td>
                    <?php } } ?>
                    <td align="center" rowspan="2" style="vertical-align: middle;"><strong><span id="grand_total">0</span></strong></td>
                    <td align="center" rowspan="2" style="vertical-align: middle;"><strong>100%</strong></td>
                </tr>
                <tr style="background-color: #00bfff; color: white; font-weight: bold;">
                    <td align="center" colspan="3">PERSENTASE</td>
                    <?php foreach ($sizes as $sz) { 
                        foreach ($qc_types as $qc) { ?>
                            <td align="center"><span id="percent_<?php echo $sz; ?>_<?php echo $qc; ?>">0</span>%</td>
                    <?php } } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card-footer">
    <button class="btn btn-primary" id="editModeBtn" onclick="toggleEditMode()">Edit</button>
    <button class="btn btn-success" id="saveAllDataBtn" onclick="saveAllData()" style="display: none;">Save All Data</button>
</div>

<style>
.compact-table {
    font-size: 12px !important;
}
.compact-table th,
.compact-table td {
    padding: 2px 2px !important;
    line-height: 1.6 !important;
}
.compact-table th {
    font-size: 12px !important;
}
</style>

<?php $this->load->view('report/javascript_report'); ?>