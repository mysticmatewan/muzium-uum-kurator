<?php
session_start();
require_once "config.php";  // Sambungkan ke pangkalan data

// Semak jika pengguna sudah log masuk
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: index.php");  // Arahkan pengguna ke halaman utama jika sudah log masuk
    exit();
}

// Proses log masuk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Semak sama ada pengguna wujud dalam pangkalan data
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Semak kata laluan
        if (password_verify($password, $user['password'])) {
            // Simpan maklumat log masuk dalam sesi
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['jawatan'] = $user['jawatan'];  // Simpan jawatan pengguna

            // Alihkan pengguna ke halaman utama (index.php)
            header("Location: index.php");
            exit();
        } else {
            echo "Kata laluan salah!";
        }
    } else {
        echo "Pengguna tidak ditemui!";
    }
}

// Proses pendaftaran akaun baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $jawatan = $_POST['jawatan'];  // Simpan jawatan pengguna (kurator atau penolong pustakawan)

    // Semak sama ada kata laluan dan pengesahan kata laluan sepadan
    if ($password != $confirm_password) {
        echo "Kata laluan tidak sepadan!";
    } else {
        // Hash kata laluan sebelum simpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Semak jika pengguna sudah wujud
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Nama pengguna sudah wujud!";
        } else {
            // Masukkan data pengguna baru ke dalam pangkalan data
            $sql = "INSERT INTO users (username, password, jawatan) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $jawatan);
            if ($stmt->execute()) {
                echo "Akaun berjaya didaftarkan! Sila log masuk.";
            } else {
                echo "Gagal mendaftar akaun.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Pendaftaran Akaun</title>
	<!-- External CSS link -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<br><br>
    <div class="login-container">
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Nama Pengguna:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Kata Laluan:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="login" value="Log Masuk">
    </form>
    <br><br>
    <h2>Atau, Daftar Akaun Baru</h2>
    <form action="login.php" method="post">
        <label for="new_username">Nama Pengguna Baru:</label>
        <input type="text" id="new_username" name="new_username" required><br><br>

        <label for="new_password">Kata Laluan Baru:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Sahkan Kata Laluan:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <label for="jawatan">Jawatan:</label>
        <select name="jawatan" id="jawatan" required>
            <option value="kurator">Kurator</option>
            <option value="penolong pustakawan">Penolong Pustakawan</option>
			<option value="praktikum">Praktikum</option>
        </select><br><br>

        <input type="submit" name="register" value="Daftar Akaun">
    </form>

    <p><a href="index.php">Kembali ke Halaman Utama</a></p>
	</div>
	<br><br>
</body>
</html>