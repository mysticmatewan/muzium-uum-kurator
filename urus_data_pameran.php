<?php
session_start();
require_once "config.php"; // Include database connection

// Check if user is logged in and is a kurator
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['jawatan'] !== 'kurator') {
    header("Location: login.php"); // Redirect to login page if not logged in or not a curator
    exit();
}

// Handle the actions (edit or delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    // Delete action
    if ($action == 'delete') {
        $sql = "DELETE FROM pameran WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "Record deleted successfully.";
            } else {
                echo "Error deleting record: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Edit action
    if ($action == 'edit') {
        $sql = "SELECT * FROM pameran WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                echo "No data found for editing.";
            }
            $stmt->close();
        }
    }
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $masuk = $_POST['masuk'];
    $tarikh_masuk = $_POST['tarikh_masuk'];
    $gambar_bahan_masuk = $_FILES['gambar_bahan_masuk']['tmp_name'] ?? null;
    $keluar = $_POST['keluar'];
    $tarikh_keluar = $_POST['tarikh_keluar'];
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

    // SQL query to update the record
    $sql = "UPDATE pameran 
            SET MASUK = ?, `TARIKH DAN MASA MASUK` = ?, `GAMBAR BAHAN MASUK` = ?, 
                KELUAR = ?, `TARIKH DAN MASA KELUAR` = ?, `GAMBAR BAHAN KELUAR` = ?, 
                `TARIKH DAN MASA DIREKOD` = ?, `NAMA PEREKOD` = ? 
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssi", $masuk, $tarikh_masuk, $gambar_bahan_masuk_data, $keluar, $tarikh_keluar, $gambar_bahan_keluar_data, $tarikh_direkod, $nama_perekod, $id);
        
        if ($stmt->execute()) {
            echo "Record updated successfully.";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Data Pameran</title>
</head>
<body>
    <h1>Urus Data Pameran</h1>

    <!-- Displaying Records -->
    <table border="1">
        <thead>
            <tr>
                <th>Masuk</th>
                <th>Tarikh dan Masa Masuk</th>
                <th>Keluaran</th>
                <th>Tarikh dan Masa Keluaran</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display all records for editing/deleting
            $sql = "SELECT * FROM pameran";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['MASUK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA MASUK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['KELUAR']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA KELUAR']) . "</td>";
                echo "<td>";
                echo "<a href='urus_data_pameran.php?action=edit&id=" . $row['id'] . "'>Edit</a> | ";
                echo "<a href='urus_data_pameran.php?action=delete&id=" . $row['id'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Edit form -->
    <?php if (isset($row)): ?>
        <h2>Edit Data</h2>
        <form action="urus_data_pameran.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="masuk">Masuk:</label><br>
            <input type="text" id="masuk" name="masuk" value="<?php echo htmlspecialchars($row['MASUK']); ?>" required><br><br>

            <label for="tarikh_masuk">Tarikh Masuk:</label><br>
            <input type="datetime-local" id="tarikh_masuk" name="tarikh_masuk" value="<?php echo $row['TARIKH DAN MASA MASUK']; ?>" required><br><br>

            <label for="gambar_bahan_masuk">Gambar Bahan Masuk (optional):</label><br>
            <input type="file" id="gambar_bahan_masuk" name="gambar_bahan_masuk"><br><br>

            <label for="keluar">Keluaran:</label><br>
            <input type="text" id="keluar" name="keluar" value="<?php echo htmlspecialchars($row['KELUAR']); ?>" required><br><br>

            <label for="tarikh_keluar">Tarikh Keluaran:</label><br>
            <input type="datetime-local" id="tarikh_keluar" name="tarikh_keluar" value="<?php echo $row['TARIKH DAN MASA KELUAR']; ?>" required><br><br>

            <label for="gambar_bahan_keluar">Gambar Bahan Keluaran (optional):</label><br>
            <input type="file" id="gambar_bahan_keluar" name="gambar_bahan_keluar"><br><br>

            <input type="submit" value="Update Data">
        </form>
    <?php endif; ?>
    
    <p><a href="logout.php">Log Out</a></p>
</body>
</html>