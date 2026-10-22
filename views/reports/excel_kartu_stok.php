<?php
/**
 * @var array $item
 * @var string $month
 * @var int $saldoAwal
 * @var array $mutasi
 * @var int $totalMasuk
 * @var int $totalKeluar
 * @var int $saldoAkhir
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { background-color: #f8fafc; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; font-family: sans-serif; font-size: 12px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .summary-box { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">KARTU STOK BARANG</div>
    <table>
        <tr>
            <td class="font-bold" width="150">Kode Barang</td>
            <td><?= e($item['kode']) ?></td>
        </tr>
        <tr>
            <td class="font-bold">Nama Barang</td>
            <td><?= e($item['nama']) ?></td>
        </tr>
        <tr>
            <td class="font-bold">Periode</td>
            <td><?= date('F Y', strtotime($month . '-01')) ?></td>
        </tr>
    </table>

    <br>

    <table>
        <tr class="header">
            <th class="font-bold">Saldo Awal</th>
            <th class="font-bold">Total Masuk</th>
            <th class="font-bold">Total Keluar</th>
            <th class="font-bold">Saldo Akhir</th>
        </tr>
        <tr>
            <td class="text-right"><?= $saldoAwal ?></td>
            <td class="text-right"><?= $totalMasuk ?></td>
            <td class="text-right"><?= $totalKeluar ?></td>
            <td class="text-right font-bold"><?= $saldoAkhir ?></td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr class="header">
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Pihak/Tujuan</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" class="font-bold">SALDO AWAL</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold"><?= $saldoAwal ?></td>
            </tr>
            <?php foreach ($mutasi as $tx): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($tx['tanggal'])) ?></td>
                <td><?= e($tx['keterangan']) ?></td>
                <td><?= e($tx['pihak'] ?? '-') ?></td>
                <td class="text-right"><?= $tx['arah'] > 0 ? $tx['jumlah'] : '-' ?></td>
                <td class="text-right"><?= $tx['arah'] < 0 ? $tx['jumlah'] : '-' ?></td>
                <td class="text-right font-bold"><?= $tx['saldo_berjalan'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
