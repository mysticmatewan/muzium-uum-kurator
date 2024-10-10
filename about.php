<?php
// Memulakan sesi
session_start();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Sistem Pengurusan Konservasi</title>
    <link rel="stylesheet" href="styles.css">
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
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
<br><br>
    <div class="container">
        <h1>Maklumat Tentang Sistem Pengurusan Repositori, Pameran dan Konservasi</h1>
        <p>Selamat datang ke sistem pengurusan repositori, pameran, dan konservasi. Sistem ini dibangunkan untuk membantu pengurusan bahan dan artefak dengan lebih efisien, memastikan pemeliharaan serta penyebaran maklumat yang lebih teratur dan terjamin.</p>

        <h2>Tujuan Sistem</h2>
        <p>Sistem ini membolehkan pekerja untuk memasukkan dan mengurus data berkaitan dengan pelbagai jenis bahan seperti bahan organik, bahan bukan organik, serta komposit. Ia juga menyokong pengurusan status bahan semasa, termasuk pameran bahan-bahan bersejarah dan konservasi bahan-bahan tersebut. Selain itu, pengguna boleh mengakses gambar bahan mula dan bahan siap yang terlibat dalam proses konservasi.</p>

        <h2>Repositori</h2>
        <p>Repositori dalam sistem ini berfungsi sebagai tempat penyimpanan bahan dan koleksi yang perlu diselenggara. Setiap bahan mempunyai maklumat terperinci seperti deskripsi, gambar, status pemeliharaan, dan tarikh rekod. Ia membolehkan pekerja dan pengurus untuk memantau setiap bahan dengan mudah serta memastikan bahan-bahan tersebut terpelihara dengan baik.</p>

        <h2>Pameran</h2>
        <p>Selain daripada pengurusan repositori, sistem ini turut menyediakan kemudahan untuk menguruskan pameran bahan yang dipamerkan kepada orang ramai. Ia membolehkan pekerja untuk menetapkan tarikh, lokasi, dan deskripsi bahan yang dipamerkan. Pameran yang diatur dengan baik dapat meningkatkan pemahaman dan kesedaran orang ramai terhadap nilai budaya dan sejarah bahan-bahan yang dipamerkan.</p>

        <h2>Konservasi</h2>
        <p>Konservasi adalah proses yang sangat penting dalam menjaga kelestarian bahan bersejarah dan warisan. Sistem ini menyediakan kemudahan untuk menguruskan status konservasi setiap bahan, termasuk sama ada bahan tersebut telah melalui proses pemeliharaan, pembaikan, atau masih memerlukan perhatian. Pekerja boleh memantau bahan-bahan yang memerlukan pemulihan dan menentukan langkah-langkah konservasi yang perlu diambil.</p>

        <h2>Peringatan</h2>
        <p>Jika pilihan yang dimahukan tidak tersedia dalam ruang yang ditulis, itu bermakna pilihan tersebut tidak wujud dalam sistem. Sila pilih pilihan yang telah disediakan. Jika anda memerlukan pilihan tambahan atau bantuan, sila hubungi admin.</p>
<br>
        <h3>Maklumat Pengguna</h3>
        <ul>
            <li><strong>Kurator:</strong> Bertanggungjawab untuk memasukkan dan mengurus data bahan, serta menyelenggara repositori dan pameran bahan konservasi.</li>
            <li><strong>Penolong Pustakawan:</strong> Membantu dalam penyediaan data dan pengurusan bahan, serta menyokong aktiviti pameran.</li>
            <li><strong>Admin:</strong> Mengurus sistem secara keseluruhan dan memberikan sokongan teknikal kepada pengguna lain.</li>
        </ul>

        <h2>Hubungi Kami</h2>
        <p>Untuk maklumat lanjut atau sokongan, sila hubungi pentadbir sistem melalui emel atau telefon yang disediakan dalam halaman <a href="http://rimalibs.netlify.app">"Hubungi Kami".</a></p>
    </div>
    </div>
	<br><br><br><br>
    <footer>
        <p>&copy; 2024 Muzium UUM. Semua hak cipta terpelihara. / Dikuasai oleh <a href="http://rimalibs.netlify.app"><strong>Rimalibs<strong></a></p>
    </footer>

</body>
</html>