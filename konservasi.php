<?php
session_start();
require_once "config.php"; // Connect to the database

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $bahan_organik = $_POST['bahan_organik'];
    $bahan_bukan_organik = $_POST['bahan_bukan_organik'];
    $deskripsi_bahan_bukan_organik = $_POST['deskripsi_bahan_bukan_organik'];
    $komposit = $_POST['komposit'];
    $tarikh_mula = $_POST['tarikh_mula']; // Updated field name
    $gambar_bahan_mula = $_FILES['gambar_bahan_mula']['tmp_name'];
    $tarikh_siap = $_POST['tarikh_siap']; // Updated field name
    $gambar_bahan_siap = $_FILES['gambar_bahan_siap']['tmp_name'];
    $tarikh_direkod = date("Y-m-d H:i:s");
    $nama_perekod = $_SESSION['username']; // Record name from logged-in user
    
    // Process images if provided
    if ($gambar_bahan_mula) {
        $gambar_bahan_mula_data = file_get_contents($gambar_bahan_mula);
    } else {
        $gambar_bahan_mula_data = null;
    }

    if ($gambar_bahan_siap) {
        $gambar_bahan_siap_data = file_get_contents($gambar_bahan_siap);
    } else {
        $gambar_bahan_siap_data = null;
    }

    // Check if database connection is OK
    if ($conn === false) {
        die("Connection failed to the database.");
    }

    // SQL query to insert data
    $sql = "INSERT INTO konservasi 
            (`BAHAN ORGANIK`, `BAHAN BUKAN ORGANIK`, `DESKRIPSI BAHAN BUKAN ORGANIK`, `KOMPOSIT`, `MULA`, `GAMBAR BAHAN MULA`, `SIAP`, `GAMBAR BAHAN SIAP`, `TARIKH DIREKOD DAN MASA`, `NAMA PEREKOD`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: 's' for string, 'd' for datetime, 'b' for blob
        $stmt->bind_param("ssssssssss", $bahan_organik, $bahan_bukan_organik, $deskripsi_bahan_bukan_organik, $komposit, $tarikh_mula, $gambar_bahan_mula_data, $tarikh_siap, $gambar_bahan_siap_data, $tarikh_direkod, $nama_perekod);

        // Execute query
        if ($stmt->execute()) {
            echo "Conservation data successfully saved!";
        } else {
            echo "Failed to save conservation data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "SQL query failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Konservasi Data</title>
	<!-- External CSS link -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="logo-banner">
    <div class="logo-container">
        <img src="https://library.uum.edu.my/wp-content/uploads/2024/06/LOGO-MUSEUM-PNG-1024x143.png" alt="Logo" class="logo">
    </div>
    <div class="banner-text">
        <h1>Sistem Pengurusan Repositori, Pameran dan Konservasi</h1>
        <p>Memudahkan pengurusan bahan sejarah dan konservasi digital.</p>
    </div>
</div>
<!-- Taskbar (Navigation Bar) -->
<nav class="taskbar">
    <button class="dropdown-btn" onclick="toggleDropdown()">☰</button>
    <ul class="taskbar-list">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="logout.php">Log Keluar</a></li>
    </ul>
<script>// Function to toggle the visibility of the dropdown menu
function toggleDropdown() {
    var menu = document.querySelector('.taskbar-list');
    menu.classList.toggle('show');
}</script>
</nav>

<!-- Banner Section -->
<section class="banner">
    <div class="banner-content">
        <h1>Selamat Datang ke Sistem Kurator</h1>
        <p>Sila selesakan diri anda</p>
        <p class="banner-btn">Selamat Bertugas!</p>
    </div>
</section>

<div class="container">
    <h1>Tambah Konservasi Data</h1>

    <form action="konservasi.php" method="post" enctype="multipart/form-data">
        <label for="bahan_organik">Bahan Organik:</label><br>
        <input type="text" id="bahan_organik" name="bahan_organik" required><br><br>

        <label for="bahan_bukan_organik">Bahan Bukan Organik:</label><br>
        <select name="bahan_bukan_organik" id="bahan_bukan_organik" required>
            <option value="KURATIF">KURATIF</option>
            <option value="PREVENTIF">PREVENTIF</option>
            <option value="RESTORASI">RESTORASI</option>
            <option value="TIADA">TIADA</option>
        </select><br><br>

        <label for="deskripsi_bahan_bukan_organik">Deskripsi Bahan Bukan Organik:</label><br>
        <input type="text" id="deskripsi_bahan_bukan_organik" name="deskripsi_bahan_bukan_organik" required><br><br>

        <label for="komposit">Komposit:</label><br>
        <input type="text" id="komposit" name="komposit" required><br><br>

        <label for="tarikh_mula">Tarikh Mula dan Masa:</label><br>
        <input type="datetime-local" id="tarikh_mula" name="tarikh_mula" required><br><br>

        <label for="gambar_bahan_mula">Gambar Bahan Mula (optional):</label><br>
        <input type="file" id="gambar_bahan_mula" name="gambar_bahan_mula"><br><br>

        <label for="tarikh_siap">Tarikh Siap dan Masa:</label><br>
        <input type="datetime-local" id="tarikh_siap" name="tarikh_siap" required><br><br>

        <label for="gambar_bahan_siap">Gambar Bahan Siap (optional):</label><br>
        <input type="file" id="gambar_bahan_siap" name="gambar_bahan_siap"><br><br>
		
		<!-- Nama Perekod Field (Dropdown) -->
        <label for="nama_perekod">Nama Perekod:</label><br>
        <select id="nama_perekod" name="nama_perekod" required>
            <option value="nama_perekod">auto username</option>
        </select><br><br>

        <input type="submit" value="Save Data"><br><br>
		<a align="center" href="show_konservasi.php"><strong>Tunjuk Data Konservasi<strong></a>
    </form>
</div><br><br><br><br>
	<footer>
        <p>&copy; 2024 Muzium UUM. Semua hak cipta terpelihara. / Dikuasai oleh <a href="http://rimalibs.netlify.app"><strong>Rimalibs<strong></a></p>
    </footer>
</body>
</html>