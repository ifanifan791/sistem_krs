<?php
include '../config/database.php';

try {
    // Cek apakah form untuk input mahasiswa telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Input data mahasiswa
        if (isset($_POST['namaMahasiswa'], $_POST['nim'], $_POST['ipk'])) {
            $namaMhs = $_POST['namaMahasiswa'];
            $nim = $_POST['nim'];
            $ipk = $_POST['ipk'];

            // Tentukan SKS berdasarkan IPK
            $sks = ($ipk >= 3) ? 24 : 20;

            // Insert data mahasiswa ke tabel inputmhs
            $query = "INSERT INTO inputmhs (namaMhs, nim, ipk, sks) VALUES (:namaMhs, :nim, :ipk, :sks)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':namaMhs', $namaMhs);
            $stmt->bindParam(':nim', $nim);
            $stmt->bindParam(':ipk', $ipk);
            $stmt->bindParam(':sks', $sks);
            $stmt->execute();
        }

        // Redirect kembali ke halaman KRS atau edit
        header("Location: ../views/krs.php");
        exit;
    }

    // Cek apakah ada request untuk menghapus data mahasiswa
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];

        // Hapus data mahasiswa dari tabel inputmhs
        $query = "DELETE FROM inputmhs WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirect kembali ke halaman KRS
        header("Location: ../views/krs.php");
        exit;
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
