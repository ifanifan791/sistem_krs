<?php
// File: views/krs.php

// Include database connection
include_once '../config/database.php';

// Fetch data from the database
$query = "
    SELECT 
        i.id,
        i.namaMhs,
        i.nim,
        i.ipk,
        i.sks,
        GROUP_CONCAT(j.matakuliah SEPARATOR ', ') AS matakuliah
    FROM inputmhs i
    LEFT JOIN jwl_mhs j ON i.id = j.mhs_id
    GROUP BY i.id
";

$stmt = $conn->prepare($query);
$stmt->execute();
$krs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Sistem Input Kartu Rencana Studi (KRS)</h2>
        <p class="text-center">Input data Mahasiswa disini!</p>
        <!-- Form untuk Input Mahasiswa -->
        <form class="mb-3" method="POST" action="../controllers/KRScontroller.php">
            <div class="row">
                <div class="col">
                    <label for="namaMahasiswa">Nama Mahasiswa</label>
                    <input type="text" class="form-control" name="namaMahasiswa" id="namaMahasiswa" placeholder="Nama Mahasiswa" required>
                </div>
                <div class="col">
                    <label for="nim">NIM</label>
                    <input type="text" class="form-control" name="nim" id="nim" placeholder="NIM" required>
                </div>
                <div class="col">
                    <label for="ipk">IPK</label>
                    <input type="number" class="form-control" name="ipk" id="ipk" placeholder="IPK" step="0.01" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-primary w-100">Input Mahasiswa</button>
                </div>
            </div>
        </form>

        <!-- Tabel Mahasiswa -->
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>IPK</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($krs as $k): ?>
                    <tr>
                        <td><?= htmlspecialchars($k['nim']) ?></td>
                        <td><?= htmlspecialchars($k['namaMhs']) ?></td>
                        <td><?= htmlspecialchars($k['matakuliah'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($k['ipk']) ?></td>
                        <td><?= htmlspecialchars($k['sks'] ?? '-') ?></td>
                        <td>
                            <a href="../views/edit.php?id=<?= htmlspecialchars($k['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../controllers/KRScontroller.php?action=delete&id=<?= htmlspecialchars($k['id']) ?>" class="btn btn-danger btn-sm">Hapus</a>
                            <a href="../views/view.php?id=<?= htmlspecialchars($k['id']) ?>" class="btn btn-info btn-sm">Lihat</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>