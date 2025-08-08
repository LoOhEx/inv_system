<?php
$voh_colors = array(
    array('name' => 'Black', 'hex' => '#000000'),
    array('name' => 'Navy', 'hex' => '#001f5b'),
    array('name' => 'Maroon', 'hex' => '#800000'),
    array('name' => 'Army', 'hex' => '#4b5320'),
    array('name' => 'Dark Gray', 'hex' => '#A9A9A9'),
    array('name' => 'Grey', 'hex' => '#808080'),
    array('name' => 'Custom', 'hex' => '#ffffff'),
);

$sizes = array('XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', 'ALL', 'CUS');
$qc_types = array('LN', 'DN');
$model_data = isset($data) && is_array($data) ? $data : array();
$existing_needs = isset($existing_needs) && is_array($existing_needs) ? $existing_needs : array();
$is_ecbs = isset($tech) && $tech == 'ecbs';

// Function to get existing value
function getExistingValue($existing_needs, $id_dvc, $size, $color, $qc) {
    $key = $id_dvc . '_' . $size . '_' . $color . '_' . $qc;
    return isset($existing_needs[$key]) ? $existing_needs[$key] : 0;
}

// Group VOH items together
$grouped_data = array();
$voh_items = array();
$regular_items = array();

foreach ($model_data as $item) {
    if ($is_ecbs && (stripos($item['dvc_name'], 'Vest Outer Hoodie') !== false || stripos($item['dvc_code'], 'VOH') === 0)) {
        $voh_items[] = $item;
    } else {
        $regular_items[] = $item;
    }
}

// Merge VOH items if they exist
if (!empty($voh_items)) {
    $merged_voh = $voh_items[0];
    $merged_voh['dvc_name'] = 'Vest Outer Hoodie Element';
    $grouped_data[] = $merged_voh;
}

// Add regular items
foreach ($regular_items as $item) {
    $grouped_data[] = $item;
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
                <?php if (!empty($grouped_data)) {
                    $row_display_no = 1;
                    foreach ($grouped_data as $item) {
                        $is_voh = ($is_ecbs && (stripos($item['dvc_name'], 'Vest Outer Hoodie') !== false || stripos($item['dvc_code'], 'VOH') === 0));
                        
                        if ($is_voh) {
                            foreach ($voh_colors as $color_idx => $color_info) {
                                $sanitized_color_name = str_replace(' ', '-', strtolower($color_info['name']));
                            ?>
                                <tr>
                                    <?php if ($color_idx == 0) { ?>
                                        <td align="center" rowspan="<?php echo count($voh_colors); ?>"><?php echo $row_display_no; ?></td>
                                        <td align="left" rowspan="<?php echo count($voh_colors); ?>"><?php echo htmlspecialchars($item['dvc_name']); ?></td>
                                    <?php } ?>
                                    <td style="text-align: left; vertical-align: top; padding-left: 8px;">
                                        <?php echo htmlspecialchars($item['dvc_code']); ?>
                                        <?php if (strtolower($color_info['name']) === 'custom') { ?>
                                            <span style="font-size:12px;font-weight:bold;margin-left:4px;">CUSTOM</span>
                                        <?php } else { ?>
                                            <span style="display:inline-block;width:16px;height:16px;background:<?php echo htmlspecialchars($color_info['hex']); ?>;border-radius:3px;vertical-align:baseline;border:1px solid #ccc;margin-left:4px;margin-right:8px;"></span><span style="vertical-align:baseline;"><?php echo htmlspecialchars($color_info['name']) ?></span>
                                        <?php } ?>
                                    </td>
                                    <?php foreach ($sizes as $sz) {
                                        foreach ($qc_types as $qc) {
                                            $input_id = $item['dvc_code'] . '_' . $sz . '_' . $sanitized_color_name . '_' . $qc;
                                            $existing_value = getExistingValue($existing_needs, $item['id_dvc'], $sz, $sanitized_color_name, $qc);
                                    ?>
                                            <td align="center">
                                                <input type="number"
                                                        class="form-control form-control-sm needs-input"
                                                        id="<?php echo $input_id; ?>"
                                                        value="<?php echo $existing_value; ?>"
                                                        min="0"
                                                        style="width: 45px; text-align: center;"
                                                       data-id-dvc="<?php echo $item['id_dvc']; ?>"
                                                       data-size="<?php echo $sz; ?>"
                                                       data-color="<?php echo $sanitized_color_name; ?>"
                                                       data-qc="<?php echo $qc; ?>"
                                                       onchange="calculateTotals()">
                                            </td>
                                    <?php } } ?>
                                    <td align="center"><strong><span id="subtotal_<?php echo $item['id_dvc']; ?>_<?php echo $sanitized_color_name; ?>">0</span></strong></td>
                                    <td align="center"><span id="percentage_<?php echo $item['id_dvc']; ?>_<?php echo $sanitized_color_name; ?>">0</span>%</td>
                                </tr>
                            <?php }
                            $row_display_no++;
                        } else { ?>
                            <tr>
                                <td align="center"><?php echo $row_display_no++; ?></td>
                                <td align="left"><?php echo htmlspecialchars($item['dvc_name']); ?></td>
                                <td align="center"><?php echo htmlspecialchars($item['dvc_code']); ?></td>
                                <?php foreach ($sizes as $sz) {
                                    foreach ($qc_types as $qc) {
                                        $input_id = $item['dvc_code'] . '_' . $sz . '_default_' . $qc;
                                        $existing_value = getExistingValue($existing_needs, $item['id_dvc'], $sz, 'default', $qc);
                                ?>
                                        <td align="center">
                                            <input type="number"
                                                    class="form-control form-control-sm needs-input"
                                                    id="<?php echo $input_id; ?>"
                                                    value="<?php echo $existing_value; ?>"
                                                    min="0"
                                                    style="width: 45px; text-align: center;"
                                                   data-id-dvc="<?php echo $item['id_dvc']; ?>"
                                                   data-size="<?php echo $sz; ?>"
                                                   data-color="default"
                                                   data-qc="<?php echo $qc; ?>"
                                                   onchange="calculateTotals()">
                                        </td>
                                <?php } } ?>
                                <td align="center"><strong><span id="subtotal_<?php echo $item['id_dvc']; ?>_default">0</span></strong></td>
                                <td align="center"><span id="percentage_<?php echo $item['id_dvc']; ?>_default">0</span>%</td>
                            </tr>
                        <?php }
                    }
                } else { ?>
                    <tr>
                        <td align="center" colspan="<?php echo 3 + (count($sizes) * 2) + 2; ?>"><i>No Data Found</i></td>
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