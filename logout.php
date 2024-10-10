<?php
// Mulakan sesi
session_start();

// Hapuskan semua sesi
session_unset();

// Hapuskan sesi yang aktif
session_destroy();

// Alihkan pengguna ke halaman login selepas log keluar
header("Location: login.php");
exit();
?>
<html>
<head>
<title>Logging Out</title>
</head>
<body>
<!-- Logout Button -->
<button id="logout-btn" onclick="logout()">Log Out</button>

<!-- Loading Screen (hidden by default) -->
<div id="loading-screen" class="loading-screen">
    <div class="spinner"></div>
    <p>Logging out...</p>
</div>
<script>
function logout() {
    // Show the loading screen
    document.getElementById("loading-screen").style.display = "flex";

    // Simulate the logout process (e.g., redirect or make an API call)
    setTimeout(function() {
        // Here, replace this with the actual logout logic or redirect
        window.location.href = "login.php"; // Example redirect
    }, 2000); // Simulating a 2-second delay for the logout process
}
</script>
</body>
</html>