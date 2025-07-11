<div class="card-table">
    <div class="table-responsive">
        <table class="table table-border align-middle text-gray-700 font-medium text-sm">
            <thead>
                <tr>
                    <th align="center" width="40">No</th>
                    <th align="center" width="200">Nama Barang</th>
                    <th align="center" width="100">Kode</th>
                    <th align="center" width="100">Jumlah*</th>
                    <th align="center" width="100">Tech</th>
                    <th align="center" width="100">Type</th>
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
                    <td align="center"><?php echo $no + ($data['page']['show'] * (isset($_GET['p']) ? (int)$_GET['p'] : 0)); ?></td>
                    <td align="left"><?php echo $row['dvc_name']; ?></td>
                    <td align="center"><?php echo $row['dvc_code']; ?></td>
                    <td align="center"><strong><?php echo $row['total_count']; ?></strong></td>
                    <td align="center"><?php echo $row['dvc_tech']; ?></td>
                    <td align="center"><?php echo $row['dvc_type']; ?></td>
                </tr>
                <?php 
                    }
                } else {
                ?>
                <tr>
                    <td align="center" colspan="6"><i>No ECCT OSC Data Found</i></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div style="padding: 10px; font-size: 11px; color: #666;">
            <strong>*Keterangan:</strong> Jumlah hanya menghitung item yang belum keluar (inv_out masih kosong)
        </div>
    </div>
</div>

<!-- Pagination & Info Data -->
<?php 
$page = isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 ? intval($_GET['p']) : 1;
$show = isset($data['page']['show']) ? intval($data['page']['show']) : 10;
$sum = isset($data['page']['sum']) ? intval($data['page']['sum']) : 0;
$last = ($sum + ($show - ($sum % $show))) / $show;
if ($sum % $show == 0) { $last -= 1; }
$from = ($sum == 0) ? 0 : (($page - 1) * $show + 1);
$to = min($page * $show, $sum);
?>
<?php if($sum > $show){ ?>
<div style="width:100%;text-align: center;padding-top:10px;height:10px;vertical-align: middle;float:left;margin-bottom: 30px;font-size:14px;">
  <?php echo "Data <b>".$from. "</b> - <b>".$to."</b> / <b>".$sum."</b> Total Data";?>
</div>
<div style="text-align:center;">
  <?php if($page>1){ ?>
    <a class="btn btn-light btn-sm" onclick="showDataEcct(1);" role="button"><b><<</b></a>
    <a class="btn btn-light btn-sm" onclick="showDataEcct(<?php echo $page-1; ?>);" role="button"><b><</b></a>
  <?php } ?>
  <?php for($i=1;$i<=$last;$i++){ if($last!=1 && abs($i-$page)<=5){ ?>
    <a class="btn btn-<?php if($i==$page){ echo "primary"; }else { echo "light"; } ?> btn-sm" role="button" onclick="showDataEcct(<?php echo $i; ?>);"><b><?php echo $i; ?></b></a>
  <?php }} ?>
  <?php if($page<$last){ ?>
    <a class="btn btn-light btn-sm" onclick="showDataEcct(<?php echo $page+1; ?>);" role="button"><b>></b></a>
    <a class="btn btn-light btn-sm" onclick="showDataEcct(<?php echo $last; ?>);" role="button"><b>>></b></a>
  <?php } ?>
</div>
<?php } ?>

<style>
.card-table {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 16px 8px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

.table-responsive table {
    min-width: 1000px;
    width: 100%;
    white-space: nowrap;
}

.table-responsive td, .table-responsive th {
    padding: 8px 6px;
    font-size: 12px;
    text-align: center;
}
</style>
