<?php
session_start();

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Ambil maklumat pengguna dan jawatan dari sesi
$username = $_SESSION['username'];
$jawatan = $_SESSION['jawatan'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang ke Muzium UUM</title>

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
<header>
            <h1>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
            <p>Jawatan: <?php echo htmlspecialchars($jawatan); ?></p>
        </header>

<!-- Banner Section -->
<section class="banner">
    <div class="banner-content">
        <h1>Selamat Datang ke Sistem Kurator</h1>
        <p>Sila selesakan diri anda</p>
        <p class="banner-btn">Selamat Bertugas!</p>
    </div>
</section>

    <!-- Main Content Section -->
    <div class="content">

        <section class="main-menu">
            <h2>Menu Utama</h2>
            <ul>
                <li><a href="repositori.php">Tambah Repositori</a></li>
                <li><a href="pameran.php">Tambah Pameran</a></li>
                <li><a href="konservasi.php">Tambah Aktiviti Konservasi</a></li>
            </ul>
        </section>
    </div>
    <br><br><br><br>
    <footer>
        <p>&copy; 2024 Muzium UUM. Semua hak cipta terpelihara. / Dikuasai oleh <a href="http://rimalibs.netlify.app"><strong>Rimalibs<strong></a></p>
    </footer>

</body>
</html>