<div class="card-table">
    <div class="table-responsive">
        <table class="table table-border align-middle text-gray-700 text-xs">
            <thead>
                <tr class="text-xs">
                    <th align="center" width="30">No</th>
                    <th align="center" width="50">Action ID</th>
                    <th align="center" width="100">Device Info</th>
                    <th align="center" width="70">Size/Color</th>
                    <th align="center" width="80">Serial Number</th>
                    <th align="center" width="60">QC Status</th>
                    <th align="center" width="120">Inventory Timeline</th>
                    <th align="center" width="100">Admin Timeline</th>
                    <th align="center" width="70">Location Move</th>
                    <th align="center" width="60">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 0;
                if($data['query'] && $data['query']->num_rows() > 0) {
                    foreach ($data['query']->result_array() as $row) {
                        $no++;
                ?>
                <tr>
                    <td align="center" class="text-xs"><?php echo $no + ($data['page']['show'] * (isset($_GET['p']) ? (int)$_GET['p'] : 0)); ?></td>
                    <td align="center" class="text-xs"><?php echo $row['id_act']; ?></td>
                    <td class="text-xs">
                        <div class="font-medium"><?php echo $row['dvc_name']; ?></div>
                        <div class="text-gray-500"><?php echo $row['dvc_code']; ?></div>
                    </td>
                    <td align="center">
                        <div class="text-xs">
                            <?php if ($row['dvc_size'] == 'small') { ?>
                                <span class="badge badge-xs badge-success badge-outline">S</span>
                            <?php } elseif ($row['dvc_size'] == 'medium') { ?>
                                <span class="badge badge-xs badge-warning badge-outline">M</span>
                            <?php } elseif ($row['dvc_size'] == 'large') { ?>
                                <span class="badge badge-xs badge-danger badge-outline">L</span>
                            <?php } else { ?>
                                <span class="badge badge-xs badge-light badge-outline"><?php echo $row['dvc_size']; ?></span>
                            <?php } ?>
                            <span class="badge badge-xs badge-light badge-outline" style="background-color: <?php echo $row['dvc_col']; ?>; color: <?php echo ($row['dvc_col'] == 'white' || $row['dvc_col'] == 'yellow') ? 'black' : 'white'; ?>;">
                                <?php echo substr(ucfirst($row['dvc_col']), 0, 3); ?>
                            </span>
                        </div>
                    </td>
                    <td align="center" class="text-xs"><?php echo $row['dvc_sn']; ?></td>
                    <td align="center">
                        <?php if ($row['dvc_qc'] == '1') { ?>
                            <span class="badge badge-xs badge-success badge-outline">Passed</span>
                        <?php } elseif ($row['dvc_qc'] == '2') { ?>
                            <span class="badge badge-xs badge-danger badge-outline">Failed</span>
                        <?php } else { ?>
                            <span class="badge badge-xs badge-warning badge-outline">Pending</span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="text-xs">
                            <?php if ($row['inv_in']) { ?>
                                <div><span class="font-medium">In:</span> <?php echo date("d/m/y H:i", strtotime($row['inv_in'])); ?></div>
                            <?php } ?>
                            <?php if ($row['inv_move']) { ?>
                                <div><span class="font-medium">Move:</span> <?php echo date("d/m/y H:i", strtotime($row['inv_move'])); ?></div>
                            <?php } ?>
                            <?php if ($row['inv_out']) { ?>
                                <div><span class="font-medium">Out:</span> <?php echo date("d/m/y H:i", strtotime($row['inv_out'])); ?></div>
                            <?php } ?>
                            <?php if ($row['inv_rls']) { ?>
                                <div><span class="font-medium">Release:</span> <?php echo date("d/m/y H:i", strtotime($row['inv_rls'])); ?></div>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <div class="text-xs">
                            <?php if (isset($row['adm_in']) && $row['adm_in']) { ?>
                                <div><span class="font-medium">In:</span> <?php echo (int) $row['adm_in']; ?></div>
                            <?php } ?>
                            <?php if (isset($row['adm_move']) && $row['adm_move']) { ?>
                                <div><span class="font-medium">Move:</span> <?php echo (int) $row['adm_move']; ?></div>
                            <?php } ?>
                            <?php if (isset($row['adm_out']) && $row['adm_out']) { ?>
                                <div><span class="font-medium">Out:</span> <?php echo (int) $row['adm_out']; ?></div>
                            <?php } ?>
                            <?php if (isset($row['adm_rls']) && $row['adm_rls']) { ?>
                                <div><span class="font-medium">Release:</span> <?php echo (int) $row['adm_rls']; ?></div>
                            <?php } ?>
                        </div>
                    </td>
                    <td align="center">
                        <?php 
                        if ($row['loc_move'] == 1) {
                            echo '<span class="badge badge-xs badge-primary badge-outline">Warehouse A</span>';
                        } elseif ($row['loc_move'] == 2) {
                            echo '<span class="badge badge-xs badge-info badge-outline">Warehouse B</span>';
                        } elseif ($row['loc_move'] == 3) {
                            echo '<span class="badge badge-xs badge-success badge-outline">Lab</span>';
                        } else {
                            echo '<span class="badge badge-xs badge-light badge-outline">' . $row['loc_move'] . '</span>';
                        }
                        ?>
                    </td>
                    <td align="center">
                        <button class="btn btn-xs btn-warning" title="Edit" onclick="openEditModal('<?php echo $row['id_act']; ?>', '<?php echo htmlspecialchars($row['dvc_sn'], ENT_QUOTES); ?>', '<?php echo $row['dvc_qc']; ?>', '<?php echo htmlspecialchars($row['loc_move'], ENT_QUOTES); ?>')">
                            ✏️
                        </button>
                        <button class="btn btn-xs btn-danger" title="Hapus" onclick="confirmDelete('<?php echo $row['id_act']; ?>')">
                            🗑️
                        </button>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td align="center" colspan="10" class="text-xs"><i>No Data Found</i></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($data['page']['sum'] > $data['page']['show']) { ?>
<div class="card-footer justify-center">
    <div class="pagination">
        <?php
        $total_pages = ceil($data['page']['sum'] / $data['page']['show']);
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 0;
        
        // Previous button
        if ($current_page > 0) {
            echo '<a href="javascript:showDataItem(' . ($current_page - 1) . ')" class="btn btn-xs btn-light">Previous</a>';
        }
        
        // Page numbers
        $start_page = max(0, $current_page - 2);
        $end_page = min($total_pages - 1, $current_page + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $current_page) {
                echo '<span class="btn btn-xs btn-primary">' . ($i + 1) . '</span>';
            } else {
                echo '<a href="javascript:showDataItem(' . $i . ')" class="btn btn-xs btn-light">' . ($i + 1) . '</a>';
            }
        }
        
        // Next button
        if ($current_page < $total_pages - 1) {
            echo '<a href="javascript:showDataItem(' . ($current_page + 1) . ')" class="btn btn-xs btn-light">Next</a>';
        }
        ?>
    </div>
</div>
<?php } ?>

<!-- Modal Edit Data Item -->
<div id="modal_edit_item" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; max-width:350px; width:90%; margin:auto; padding:20px; position:relative; top:10vh;">
        <h3 style="margin-bottom:15px; font-size:16px;">Edit Data Item</h3>
        <input type="hidden" id="edit_id_act">
        
        <div style="margin-bottom:10px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:12px;">Serial Number</label>
            <input type="text" id="edit_dvc_sn" class="input" style="width:100%; padding:6px; border:1px solid #ddd; border-radius:4px; font-size:12px;">
        </div>
        
        <div style="margin-bottom:10px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:12px;">QC Status</label>
            <select id="edit_dvc_qc" class="select" style="width:100%; padding:6px; border:1px solid #ddd; border-radius:4px; font-size:12px;">
                <option value="0">Pending</option>
                <option value="1">Passed</option>
                <option value="2">Failed</option>
            </select>
        </div>
        
        <div style="margin-bottom:15px;">
            <label style="display:block; margin-bottom:4px; font-weight:bold; font-size:12px;">Location Move</label>
            <select id="edit_loc_move" class="select" style="width:100%; padding:6px; border:1px solid #ddd; border-radius:4px; font-size:12px;">
                <option value="0">-</option>
                <option value="Lantai 2">🏢 Lantai 2</option>
                <option value="Bang Toni">👨‍💼 Bang Toni</option>
                <option value="Om Bob">👨‍💼 Om Bob</option>
                <option value="Rekanan">🤝 Rekanan</option>
                <option value="LN">🏭 LN</option>
                <option value="ECBS">🏭 ECBS</option>
                <option value="LN Office">🏢 LN Office</option>
                <option value="Lantai 1">🏢 Lantai 1</option>
                <option value="Unknown">❓ Unknown</option>
            </select>
        </div>
        
        <div style="text-align:right; margin-top:15px;">
            <button class="btn btn-xs btn-light" onclick="closeEditModal()" style="margin-right:6px; padding:6px 12px; font-size:12px;">Batal</button>
            <button class="btn btn-xs btn-primary" onclick="submitEditItem()" style="padding:6px 12px; font-size:12px;">Simpan</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
function openEditModal(id_act, dvc_sn, dvc_qc, loc_move) {
    document.getElementById('edit_id_act').value = id_act;
    document.getElementById('edit_dvc_sn').value = dvc_sn || '';
    document.getElementById('edit_dvc_qc').value = dvc_qc || '0';
    document.getElementById('edit_loc_move').value = loc_move || '0';
    document.getElementById('modal_edit_item').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modal_edit_item').style.display = 'none';
}

function confirmDelete(id_act) {
    if(confirm('Yakin ingin menghapus data ini?')) {
        fetch('<?php echo $config['base_url']; ?>inventory/input_delete_process', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_act: id_act })
        })
        .then(res => res.json())
        .then(result => {
            alert(result.message);
            if(result.success) window.location.reload();
        })
        .catch(err => alert('Gagal menghapus data!'));
    }
}

function submitEditItem() {
    const id_act = document.getElementById('edit_id_act').value;
    const dvc_sn = document.getElementById('edit_dvc_sn').value.trim();
    const dvc_qc = document.getElementById('edit_dvc_qc').value;
    const loc_move = document.getElementById('edit_loc_move').value.trim();
    
    fetch('<?php echo $config['base_url']; ?>inventory/input_edit_process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_act, dvc_sn, dvc_qc, loc_move })
    })
    .then(res => res.json())
    .then(result => {
        alert(result.message);
        if(result.success) window.location.reload();
    })
    .catch(err => alert('Gagal update data!'));
    
    closeEditModal();
}
</script>