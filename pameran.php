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
    $masuk = $_POST['masuk'] ?? '';
    $tarikh_masuk = $_POST['tarikh_masuk'] ?? ''; // Updated field name
    $gambar_bahan_masuk = $_FILES['gambar_bahan_masuk']['tmp_name'] ?? null;
    $keluar = $_POST['keluar'] ?? '';
    $tarikh_keluar = $_POST['tarikh_keluar'] ?? ''; // Updated field name
    $gambar_bahan_keluar = $_FILES['gambar_bahan_keluar']['tmp_name'] ?? null;
    $tarikh_direkod = date("Y-m-d H:i:s");
    $nama_perekod = $_SESSION['username']; // Record name from logged-in user
    
    // Process images if provided
    if ($gambar_bahan_masuk) {
        $gambar_bahan_masuk_data = file_get_contents($gambar_bahan_masuk);
    } else {
        $gambar_bahan_masuk_data = null;
    }

    if ($gambar_bahan_keluar) {
        $gambar_bahan_keluar_data = file_get_contents($gambar_bahan_keluar);
    } else {
        $gambar_bahan_keluar_data = null;
    }

    // Check if database connection is OK
    if ($conn === false) {
        die("Connection failed to the database.");
    }

    // SQL query to insert data with backticks for column names
    $sql = "INSERT INTO pameran 
            (`MASUK`, `TARIKH DAN MASA MASUK`, `GAMBAR BAHAN MASUK`, `KELUAR`, `TARIKH DAN MASA KELUAR`, `GAMBAR BAHAN KELUAR`, `TARIKH DAN MASA DIREKOD`, `NAMA PEREKOD`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: 's' for string, 'd' for datetime, 'b' for blob
        $stmt->bind_param("ssssssss", $masuk, $tarikh_masuk, $gambar_bahan_masuk_data, $keluar, $tarikh_keluar, $gambar_bahan_keluar_data, $tarikh_direkod, $nama_perekod);

        // Execute query
        if ($stmt->execute()) {
            echo "Exhibition data successfully saved!";
        } else {
            echo "Failed to save Exhibition data: " . $stmt->error;
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
    <title>Tambah Pameran Data</title>
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
    <h1>Tambah Pameran Data</h1>

    <form action="pameran.php" method="post" enctype="multipart/form-data">
        <label for="masuk">Masuk:</label><br>
        <input type="text" id="masuk" name="masuk" required><br><br>

        <label for="tarikh_masuk">Tarikh Masuk dan Masa:</label><br>
        <input type="datetime-local" id="tarikh_masuk" name="tarikh_masuk" required><br><br>

        <label for="gambar_bahan_masuk">Gambar Bahan Masuk (optional):</label><br>
        <input type="file" id="gambar_bahan_masuk" name="gambar_bahan_masuk"><br><br>

        <label for="keluar">Keluar:</label><br>
        <input type="text" id="keluar" name="keluar" required><br><br>

        <label for="tarikh_keluar">Tarikh Keluar dan Masa:</label><br>
        <input type="datetime-local" id="tarikh_keluar" name="tarikh_keluar" required><br><br>

        <label for="gambar_bahan_keluar">Gambar Bahan Keluar (optional):</label><br>
        <input type="file" id="gambar_bahan_keluar" name="gambar_bahan_keluar"><br><br>
		
		<!-- Nama Perekod Field (Dropdown) -->
        <label for="nama_perekod">Nama Perekod:</label><br>
        <select id="nama_perekod" name="nama_perekod" required>
			<option value="nama_perekod">auto username</option>
        </select><br><br>

        <input type="submit" value="Save Data">
    </form>
<a align="center" href="show_pameran.php"><strong>Tunjuk Data Pameran<strong></a>
    </form>
</div><br><br><br><br>
	<footer>
        <p>&copy; 2024 Muzium UUM. Semua hak cipta terpelihara. / Dikuasai oleh <a href="http://rimalibs.netlify.app"><strong>Rimalibs<strong></a></p>
    </footer>
</body>
</html>