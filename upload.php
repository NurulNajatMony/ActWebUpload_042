<?php
$target_dir = "uploads/";

// Buat folder uploads otomatis jika belum ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// 1. PROSES UNGGAH FILE
if (isset($_POST["submit"])) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Proteksi: Jangan izinkan file PHP demi keamanan lab/nilai praktikum
    if ($fileType == "php") {
        echo "<script>alert('Maaf, file PHP tidak diperbolehkan!'); window.location.href='index.html';</script>";
        $uploadOk = 0;
    }

    if (file_exists($target_file) && $uploadOk == 1) {
        echo "<script>alert('Maaf, berkas sudah ada.'); window.location.href='index.html';</script>";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > 2000000 && $uploadOk == 1) {
        echo "<script>alert('Maaf, file terlalu besar (Maksimal 2MB).'); window.location.href='index.html';</script>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>alert('Berkas berhasil diunggah!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengunggah.'); window.location.href='index.html';</script>";
        }
    }
}

// 2. PROSES MEMBERIKAN DAFTAR FILE KE HTML (API LIST)
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    header('Content-Type: application/json');
    $files = array_diff(scandir($target_dir), array('.', '..'));
    echo json_encode(array_values($files));
    exit;
}

// 3. PROSES HAPUS FILE (DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $file_to_delete = $target_dir . basename($_GET['file']);
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        echo "Berkas berhasil dihapus!";
    } else {
        echo "Berkas tidak ditemukan.";
    }
    exit;
}
?>