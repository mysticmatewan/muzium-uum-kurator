<?php
session_start();
require_once "config.php"; // Connect to the database

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if database connection is OK
if ($conn === false) {
    die("Connection failed to the database.");
}

// SQL query to fetch all records from the konservasi table
$sql = "SELECT * FROM konservasi";
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
    <title>Show Conservation Data</title>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 align="center">Conservation Data</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Bahan Organik</th>
                <th>Bahan Bukan Organik</th>
                <th>Deskripsi Bahan Bukan Organik</th>
                <th>Komposit</th>
                <th>Mula</th>
                <th>Gambar Bahan Mula</th>
                <th>Siap</th>
                <th>Gambar Bahan Siap</th>
                <th>Tarikh Direkod dan Masa</th>
                <th>Nama Perekod</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if the query has returned any rows
            if ($result->num_rows > 0) {
                // Fetch and display data row by row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['BAHAN ORGANIK']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['BAHAN BUKAN ORGANIK']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['DESKRIPSI BAHAN BUKAN ORGANIK']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['KOMPOSIT']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['MULA']) . "</td>";

                    // Check if there is an image for 'Gambar Bahan Mula'
                    if ($row['GAMBAR BAHAN MULA']) {
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN MULA']) . "' alt='Gambar Bahan Mula' width='100'></td>";
                    } else {
                        echo "<td>No Image</td>";
                    }

                    echo "<td>" . htmlspecialchars($row['SIAP']) . "</td>";

                    // Check if there is an image for 'Gambar Bahan Siap'
                    if ($row['GAMBAR BAHAN SIAP']) {
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN SIAP']) . "' alt='Gambar Bahan Siap' width='100'></td>";
                    } else {
                        echo "<td>No Image</td>";
                    }

                    echo "<td>" . htmlspecialchars($row['TARIKH DIREKOD DAN MASA']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NAMA PEREKOD']) . "</td>";
                    echo "</tr>";
                }
            } else {
                // If no rows are found
                echo "<tr><td colspan='10'>No data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br><br>
    <p align="center"><a href="index.php">Kembali</a></p>
	<br><br>
</body>
</html>