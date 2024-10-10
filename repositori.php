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
    $penyerahan = $_POST['penyerahan'] ?? '';
    $tarikh_penyerahan = $_POST['tarikh_penyerahan'] ?? '';
    $gambar_bahan_penyerahan = $_FILES['gambar_bahan_penyerahan']['tmp_name'] ?? null;
    $bahan_keluar = $_POST['bahan_keluar'] ?? '';
    $tarikh_bahan_keluar = $_POST['tarikh_bahan_keluar'] ?? '';
    $gambar_bahan_keluar = $_FILES['gambar_bahan_keluar']['tmp_name'] ?? null;
    $bahan_masuk = $_POST['bahan_masuk'] ?? '';
    $tarikh_bahan_masuk = $_POST['tarikh_bahan_masuk'] ?? '';
    $gambar_bahan_masuk = $_FILES['gambar_bahan_masuk']['tmp_name'] ?? null;
    $tarikh_direkod = date("Y-m-d H:i:s");
    $nama_perekod = $_SESSION['username']; // Record name from logged-in user
    
    // Process images if provided
    if ($gambar_bahan_penyerahan) {
        $gambar_bahan_penyerahan_data = file_get_contents($gambar_bahan_penyerahan);
    } else {
        $gambar_bahan_penyerahan_data = null;
    }

    if ($gambar_bahan_keluar) {
        $gambar_bahan_keluar_data = file_get_contents($gambar_bahan_keluar);
    } else {
        $gambar_bahan_keluar_data = null;
    }

    if ($gambar_bahan_masuk) {
        $gambar_bahan_masuk_data = file_get_contents($gambar_bahan_masuk);
    } else {
        $gambar_bahan_masuk_data = null;
    }

    // Check if database connection is OK
    if ($conn === false) {
        die("Connection failed to the database.");
    }

    // SQL query to insert data with backticks for column names
    $sql = "INSERT INTO repositori 
            (`PENYERAHAN`, `TARIKH DAN MASA PENYERAHAN`, `GAMBAR BAHAN PENYERAHAN`, `BAHAN KELUAR`, `TARIKH DAN MASA BAHAN KELUAR`, `GAMBAR BAHAN KELUAR`, `BAHAN MASUK`, `TARIKH DAN MASA BAHAN MASUK`, `GAMBAR BAHAN MASUK`, `TARIKH DIREKOD DAN MASA`, `NAMA PEREKOD`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: 's' for string, 'd' for datetime, 'b' for blob
        $stmt->bind_param("sssssssssss", $penyerahan, $tarikh_penyerahan, $gambar_bahan_penyerahan_data, $bahan_keluar, $tarikh_bahan_keluar, $gambar_bahan_keluar_data, $bahan_masuk, $tarikh_bahan_masuk, $gambar_bahan_masuk_data, $tarikh_direkod, $nama_perekod);

        // Execute query
        if ($stmt->execute()) {
            echo "Repository data successfully saved!";
        } else {
            echo "Failed to save repository data: " . $stmt->error;
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
    <title>Tambah Repositori Data</title>
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
    <h1>Tambah Repositori Data</h1>

    <form action="repositori.php" method="post" enctype="multipart/form-data">
        <!-- Penyerahan Field -->
        <label for="penyerahan">Penyerahan:</label><br>
        <input type="text" id="penyerahan" name="penyerahan" required><br><br>

        <!-- Tarikh Penyerahan Field -->
        <label for="tarikh_penyerahan">Tarikh Penyerahan:</label><br>
        <input type="datetime-local" id="tarikh_penyerahan" name="tarikh_penyerahan" required><br><br>

        <!-- Gambar Bahan Penyerahan -->
        <label for="gambar_bahan_penyerahan">Gambar Bahan Penyerahan (optional):</label><br>
        <input type="file" id="gambar_bahan_penyerahan" name="gambar_bahan_penyerahan"><br><br>

        <!-- Bahan Keluar Field -->
        <label for="bahan_keluar">Bahan Keluar:</label><br>
        <input type="text" id="bahan_keluar" name="bahan_keluar" required><br><br>

        <!-- Tarikh Bahan Keluar Field -->
        <label for="tarikh_bahan_keluar">Tarikh Bahan Keluar:</label><br>
        <input type="datetime-local" id="tarikh_bahan_keluar" name="tarikh_bahan_keluar" required><br><br>

        <!-- Gambar Bahan Keluar -->
        <label for="gambar_bahan_keluar">Gambar Bahan Keluar (optional):</label><br>
        <input type="file" id="gambar_bahan_keluar" name="gambar_bahan_keluar"><br><br>

        <!-- Bahan Masuk Field -->
        <label for="bahan_masuk">Bahan Masuk:</label><br>
        <input type="text" id="bahan_masuk" name="bahan_masuk" required><br><br>

        <!-- Tarikh Bahan Masuk Field -->
        <label for="tarikh_bahan_masuk">Tarikh Bahan Masuk:</label><br>
        <input type="datetime-local" id="tarikh_bahan_masuk" name="tarikh_bahan_masuk" required><br><br>

        <!-- Gambar Bahan Masuk -->
        <label for="gambar_bahan_masuk">Gambar Bahan Masuk (optional):</label><br>
        <input type="file" id="gambar_bahan_masuk" name="gambar_bahan_masuk"><br><br>

        <!-- Nama Perekod Field (Dropdown) -->
        <label for="nama_perekod">Nama Perekod:</label><br>
        <select id="nama_perekod" name="nama_perekod" required>
			<option value="nama_perekod">auto username</option>
        </select><br><br>

        <!-- Submit Button -->
        <input type="submit" value="Save Data">
    </form>
<a align="center" href="show_repositori.php"><strong>Tunjuk Data Repositori<strong></a>
    </form>
</div><br><br><br><br>
	<footer>
        <p>&copy; 2024 Muzium UUM. Semua hak cipta terpelihara. / Dikuasai oleh <a href="http://rimalibs.netlify.app"><strong>Rimalibs<strong></a></p>
    </footer>
</body>
</html>