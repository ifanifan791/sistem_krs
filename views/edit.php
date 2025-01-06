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

    // Ambil daftar mata kuliah dari tabel `jwl_matakuliah`
    $stmt = $conn->prepare("SELECT * FROM jwl_matakuliah");
    $stmt->execute();
    $mataKuliah = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil mata kuliah yang sudah diambil mahasiswa dari tabel `jwl_mhs`
    $stmt = $conn->prepare("SELECT matakuliah, sks, kelp, ruangan, id FROM jwl_mhs WHERE mhs_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $krs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Proses penyimpanan mata kuliah yang dipilih
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pastikan mata kuliah dipilih
        if (!empty($_POST['matakuliah'])) {
            $matakuliah_id = $_POST['matakuliah'];

            // Cek apakah mahasiswa sudah mengambil mata kuliah ini
            $stmt = $conn->prepare("SELECT * FROM jwl_mhs WHERE mhs_id = :id AND matakuliah = :matakuliah");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':matakuliah', $matakuliah_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Mata kuliah sudah diambil
                echo "Mata kuliah sudah diambil.";
            } else {
                // Ambil detail mata kuliah yang dipilih
                $stmt = $conn->prepare("SELECT * FROM jwl_matakuliah WHERE id = :matakuliah_id");
                $stmt->bindParam(':matakuliah_id', $matakuliah_id, PDO::PARAM_INT);
                $stmt->execute();
                $matakuliah = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($matakuliah) {
                    // Simpan data mata kuliah ke tabel `jwl_mhs`
                    $stmt = $conn->prepare("INSERT INTO jwl_mhs (mhs_id, matakuliah, sks, kelp, ruangan) 
                                        VALUES (:mhs_id, :matakuliah, :sks, :kelp, :ruangan)");
                    $stmt->bindParam(':mhs_id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':matakuliah', $matakuliah['matakuliah'], PDO::PARAM_STR);
                    $stmt->bindParam(':sks', $matakuliah['sks'], PDO::PARAM_INT);
                    $stmt->bindParam(':kelp', $matakuliah['kelp'], PDO::PARAM_STR);
                    $stmt->bindParam(':ruangan', $matakuliah['ruangan'], PDO::PARAM_STR);
                    $stmt->execute();

                    // Redirect setelah berhasil menyimpan
                    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
                    exit;
                } else {
                    echo "Mata kuliah tidak ditemukan.";
                }
            }
        } else {
            echo "Silakan pilih mata kuliah.";
        }
    }

    // Proses penghapusan mata kuliah
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Hapus mata kuliah dari tabel `jwl_mhs`
        $stmt = $conn->prepare("DELETE FROM jwl_mhs WHERE id = :delete_id AND mhs_id = :mhs_id");
        $stmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
        $stmt->bindParam(':mhs_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect setelah berhasil menghapus
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Sistem Input Kartu Rencana Studi (KRS)</h2>
        <p class="text-center">Input data KRS mahasiswa dengan mudah dan cepat</p>
        <h2>Pilih Mata Kuliah</h2>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Nama Mahasiswa: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['namaMhs']); ?></span></h5>
                <p class="card-text mb-1">NIM: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['nim']); ?></span></p>
                <p class="card-text mb-1">IPK: <span class="fw-bold"><?= htmlspecialchars($mahasiswa['ipk']); ?></span></p>
                <a href="krs.php" class="btn btn-secondary mt-3 w-100">Kembali ke Halaman KRS</a>
            </div>
        </div>

        <form class="mb-3" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($mahasiswa['id']); ?>">

            <div class="mb-3">
                <label for="matakuliah" class="form-label">Mata Kuliah</label>
                <select class="form-select" name="matakuliah" id="matakuliah">
                    <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                    <?php foreach ($mataKuliah as $mk): ?>
                        <option value="<?= htmlspecialchars($mk['id']); ?>">
                            <?= htmlspecialchars($mk['matakuliah']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-100">Simpan</button>
        </form>

        <!-- Tabel Mahasiswa -->
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-light">
                <tr>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kelp</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
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
                            <td>
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
</body>

</html>