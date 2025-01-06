<?php
include '../config/database.php';

// Cek apakah parameter `id` diberikan
if (!isset($_GET['id'])) {
    die("ID mahasiswa tidak ditemukan!");
}

$id = $_GET['id'];

try {
    // Ambil data mahasiswa berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM inputmhs WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mahasiswa) {
        die("Data mahasiswa tidak ditemukan!");
    }

    // Ambil mata kuliah yang sudah diambil mahasiswa dari tabel `jwl_mhs`
    $stmt = $conn->prepare("SELECT matakuliah, sks, kelp, ruangan, id FROM jwl_mhs WHERE mhs_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $krs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Hanya tampilkan area cetak saat print */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5 print-area">
        <h2 class="text-center">Sistem Input Kartu Rencana Studi (KRS)</h2>
        <p class="text-center">Input data KRS mahasiswa dengan mudah dan cepat</p>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Nama Mahasiswa: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['namaMhs']); ?></span></h5>
                <p class="card-text mb-1">NIM: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['nim']); ?></span></p>
                <p class="card-text mb-1">IPK: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['ipk']); ?></span></p>
            </div>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-light">
                <tr>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kelp</th>
                    <th>Ruangan</th>
                    <th class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($krs)): ?>
                    <?php foreach ($krs as $mk): ?>
                        <tr>
                            <td><?= htmlspecialchars($mk['matakuliah']) ?></td>
                            <td><?= htmlspecialchars($mk['sks']) ?></td>
                            <td><?= htmlspecialchars($mk['kelp'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($mk['ruangan'] ?? '-') ?></td>
                            <td class="no-print">
                                <a href="?id=<?= $id ?>&delete_id=<?= $mk['id'] ?>" 
                                   class="btn btn-danger btn-sm">
                                   Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada mata kuliah yang diambil.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Tombol cetak -->
    <div class="no-print mt-3 text-center">
        <button class="btn btn-primary" onclick="window.print()">Cetak KRS</button>
        <a href="krs.php" class="btn btn-secondary">Kembali ke Halaman KRS</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
