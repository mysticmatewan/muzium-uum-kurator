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

// SQL query to fetch all records from the pameran table with backticks for column names
$sql = "SELECT `MASUK`, `TARIKH DAN MASA MASUK`, `GAMBAR BAHAN MASUK`, `KELUAR`, `TARIKH DAN MASA KELUAR`, `GAMBAR BAHAN KELUAR`, `TARIKH DAN MASA DIREKOD`, `NAMA PEREKOD` FROM pameran";
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
    <title>Show Exhibition Data</title>
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 align="center">Exhibition Data</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Masuk</th>
                <th>Tarikh Masuk</th>
                <th>Gambar Bahan Masuk</th>
                <th>Keluar</th>
                <th>Tarikh Keluar</th>
                <th>Gambar Bahan Keluar</th>
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
                    echo "<td>" . htmlspecialchars($row['MASUK']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA MASUK']) . "</td>";

                    // Check if there is an image for 'Gambar Bahan Masuk'
                    if ($row['GAMBAR BAHAN MASUK']) {
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN MASUK']) . "' alt='Gambar Bahan Masuk' width='100'></td>";
                    } else {
                        echo "<td>No Image</td>";
                    }

                    echo "<td>" . htmlspecialchars($row['KELUAR']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA KELUAR']) . "</td>";

                    // Check if there is an image for 'Gambar Bahan Keluar'
                    if ($row['GAMBAR BAHAN KELUAR']) {
                        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['GAMBAR BAHAN KELUAR']) . "' alt='Gambar Bahan Keluar' width='100'></td>";
                    } else {
                        echo "<td>No Image</td>";
                    }

                    echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA DIREKOD']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NAMA PEREKOD']) . "</td>";
                    echo "</tr>";
                }
            } else {
                // If no rows are found
                echo "<tr><td colspan='8'>No data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
<br><br>
    <p align="center"><a href="index.php">Kembali</a></p>
	<br><br>
</body>
</html>