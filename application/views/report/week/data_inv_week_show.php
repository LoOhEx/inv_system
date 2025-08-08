<?php 
// Debug: uncomment baris ini untuk debugging
// echo "<!-- DEBUG: Data count: " . count($data) . " -->";
// var_dump($data); // Uncomment untuk melihat data
?>

<?php if (!empty($data)): ?>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px;">Week</th>
                <th>Periode</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Durasi (Hari)</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <?php 
                $start_date = new DateTime($row['date_start']);
                $finish_date = new DateTime($row['date_finish']);
                $duration = $start_date->diff($finish_date)->days + 1;
                
                // Check if editing is allowed (within current period)
                $current_date = new DateTime();
                $can_edit = ($current_date >= $start_date && $current_date <= $finish_date);
            ?>
            <tr>
                <td style="text-align: center; font-weight: bold;">
                    <?= $row['period_w'] ?>
                </td>
                <td>
                    <strong><?= getMonthName($row['period_m']) ?> <?= $row['period_y'] ?></strong><br>
                    <small style="color: #666;">Minggu ke-<?= $row['period_w'] ?></small>
                </td>
                <td>
                    <?= $start_date->format('d/m/Y H:i') ?><br>
                    <small style="color: #666;"><?= getDayName($start_date->format('N')) ?></small>
                </td>
                <td>
                    <?= $finish_date->format('d/m/Y H:i') ?><br>
                    <small style="color: #666;"><?= getDayName($finish_date->format('N')) ?></small>
                </td>
                <td style="text-align: center;">
                    <span style="background: #e3f2fd; padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                        <?= $duration ?> hari
                    </span>
                </td>
                <td style="text-align: center;">
                    <i class="edit-icon <?= $can_edit ? '' : 'disabled' ?>" 
                       onclick="<?= $can_edit ? "editPeriod('{$row['id_week']}', '{$row['date_start']}', '{$row['date_finish']}')" : '' ?>"
                       title="<?= $can_edit ? 'Edit periode' : 'Tidak dapat edit di luar periode aktif' ?>">
                        ✏️
                    </i>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
        <h5 style="margin: 0 0 10px 0; color: #333;">Informasi Periode</h5>
        <p style="margin: 5px 0; font-size: 14px; color: #666;">
            <strong>Total Minggu:</strong> <?= count($data) ?> minggu
        </p>
        <p style="margin: 5px 0; font-size: 14px; color: #666;">
            <strong>Periode Penuh:</strong> 
            <?= (new DateTime($data[0]['date_start']))->format('d/m/Y H:i') ?> - 
            <?= (new DateTime(end($data)['date_finish']))->format('d/m/Y H:i') ?>
        </p>
        <p style="margin: 5px 0; font-size: 14px; color: #666;">
            <strong>Catatan:</strong> Periode dimulai dari tanggal 27 bulan sebelumnya (08:00) hingga tanggal 26 bulan ini (17:00)
        </p>
    </div>
</div>

<?php else: ?>
<div class="no-data">
    <p>Tidak ada data periode untuk bulan dan tahun yang dipilih.</p>
    <p>Silakan generate periode terlebih dahulu.</p>
</div>
<?php endif; ?>

<?php
function getMonthName($month) {
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return isset($months[$month]) ? $months[$month] : $month;
}

function getDayName($dayNumber) {
    $days = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
        5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
    ];
    return isset($days[$dayNumber]) ? $days[$dayNumber] : '';
}

?>
    