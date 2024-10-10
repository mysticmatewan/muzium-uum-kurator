<?php
session_start();
require_once "config.php"; // Connect to the database

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// SQL query to fetch all records from the repositori table
$sql = "SELECT * FROM repositori";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Repositori Data</title>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 align="center">Repository Data</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Penyerahan</th>
                <th>Tarikh dan Masa Penyerahan</th>
                <th>Gambar Bahan Penyerahan</th>
                <th>Bahan Keluar</th>
                <th>Tarikh dan Masa Bahan Keluar</th>
                <th>Gambar Bahan Keluar</th>
                <th>Bahan Masuk</th>
                <th>Tarikh dan Masa Bahan Masuk</th>
                <th>Gambar Bahan Masuk</th>
                <th>Tarikh Direkod dan Masa</th>
                <th>Nama Perekod</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display data row by row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['PENYERAHAN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA PENYERAHAN']) . "</td>";

                // Check if there is an image for 'Gambar Bahan Penyerahan'
                if ($row['GAMBAR BAHAN PENYERAHAN']) {
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN PENYERAHAN']) . "' width='100' height='100'></td>";
                } else {
                    echo "<td>No Image</td>";
                }

                echo "<td>" . htmlspecialchars($row['BAHAN KELUAR']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA BAHAN KELUAR']) . "</td>";

                // Check if there is an image for 'Gambar Bahan Keluar'
                if ($row['GAMBAR BAHAN KELUAR']) {
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN KELUAR']) . "' width='100' height='100'></td>";
                } else {
                    echo "<td>No Image</td>";
                }

                echo "<td>" . htmlspecialchars($row['BAHAN MASUK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA BAHAN MASUK']) . "</td>";

                // Check if there is an image for 'Gambar Bahan Masuk'
                if ($row['GAMBAR BAHAN MASUK']) {
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN MASUK']) . "' width='100' height='100'></td>";
                } else {
                    echo "<td>No Image</td>";
                }

                echo "<td>" . htmlspecialchars($row['TARIKH DIREKOD DAN MASA']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NAMA PEREKOD']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<br><br>
    <p align="center"><a href="index.php">Kembali</a></p><br><br>
</body>
</html>