<?php
session_start();
require_once "config.php"; // Connect to the database

// Check if user is logged in and is a kurator
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['jawatan'] !== 'kurator') {
    header("Location: login.php"); // Redirect to login page if not logged in or not a curator
    exit();
}

// Check if an action is requested (edit or delete)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    // If action is delete
    if ($action == 'delete') {
        // SQL query to delete data
        $sql = "DELETE FROM repositori WHERE id = ?";
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

    // If action is edit, fetch the current data
    if ($action == 'edit') {
        $sql = "SELECT * FROM repositori WHERE id = ?";
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

// Handle the form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $penyerahan = $_POST['penyerahan'];
    $tarikh_penyerahan = $_POST['tarikh_penyerahan'];
    $gambar_bahan_penyerahan = $_FILES['gambar_bahan_penyerahan']['tmp_name'] ?? null;
    $bahan_keluar = $_POST['bahan_keluar'];
    $tarikh_bahan_keluar = $_POST['tarikh_bahan_keluar'];
    $gambar_bahan_keluar = $_FILES['gambar_bahan_keluar']['tmp_name'] ?? null;
    $bahan_masuk = $_POST['bahan_masuk'];
    $tarikh_bahan_masuk = $_POST['tarikh_bahan_masuk'];
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

    // SQL query to update the record
    $sql = "UPDATE repositori 
            SET PENYERAHAN = ?, `TARIKH DAN MASA PENYERAHAN` = ?, `GAMBAR BAHAN PENYERAHAN` = ?, 
                BAHA KELUAR = ?, `TARIKH DAN MASA BAHAN KELUAR` = ?, `GAMBAR BAHAN KELUAR` = ?, 
                BAHA MASUK = ?, `TARIKH DAN MASA BAHAN MASUK` = ?, `GAMBAR BAHAN MASUK` = ?, 
                `TARIKH DIREKOD DAN MASA` = ?, `NAMA PEREKOD` = ? 
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssssi", $penyerahan, $tarikh_penyerahan, $gambar_bahan_penyerahan_data, $bahan_keluar, $tarikh_bahan_keluar, $gambar_bahan_keluar_data, $bahan_masuk, $tarikh_bahan_masuk, $gambar_bahan_masuk_data, $tarikh_direkod, $nama_perekod, $id);
        
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
    <title>Urus Data - Repositori</title>
</head>
<body>
    <h1>Urus Data Repositori</h1>

    <!-- Displaying Records -->
    <table border="1">
        <thead>
            <tr>
                <th>Penyerahan</th>
                <th>Tarikh dan Masa Penyerahan</th>
                <th>Bahan Keluar</th>
                <th>Tarikh dan Masa Bahan Keluar</th>
                <th>Bahan Masuk</th>
                <th>Tarikh dan Masa Bahan Masuk</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display all records for editing/deleting
            $sql = "SELECT * FROM repositori";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['PENYERAHAN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA PENYERAHAN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['BAHAN KELUAR']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA BAHAN KELUAR']) . "</td>";
                echo "<td>" . htmlspecialchars($row['BAHAN MASUK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TARIKH DAN MASA BAHAN MASUK']) . "</td>";
                echo "<td>";
                echo "<a href='urus_data.php?action=edit&id=" . $row['id'] . "'>Edit</a> | ";
                echo "<a href='urus_data.php?action=delete&id=" . $row['id'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Edit form -->
    <?php if (isset($row)): ?>
        <h2>Edit Data</h2>
        <form action="urus_data.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="penyerahan">Penyerahan:</label><br>
            <input type="text" id="penyerahan" name="penyerahan" value="<?php echo htmlspecialchars($row['PENYERAHAN']); ?>" required><br><br>

            <label for="tarikh_penyerahan">Tarikh Penyerahan:</label><br>
            <input type="datetime-local" id="tarikh_penyerahan" name="tarikh_penyerahan" value="<?php echo $row['TARIKH DAN MASA PENYERAHAN']; ?>" required><br><br>

            <label for="gambar_bahan_penyerahan">Gambar Bahan Penyerahan (optional):</label><br>
            <input type="file" id="gambar_bahan_penyerahan" name="gambar_bahan_penyerahan"><br><br>

            <label for="bahan_keluar">Bahan Keluar:</label><br>
            <input type="text" id="bahan_keluar" name="bahan_keluar" value="<?php echo htmlspecialchars($row['BAHAN KELUAR']); ?>" required><br><br>

            <label for="tarikh_bahan_keluar">Tarikh Bahan Keluar:</label><br>
            <input type="datetime-local" id="tarikh_bahan_keluar" name="tarikh_bahan_keluar" value="<?php echo $row['TARIKH DAN MASA BAHAN KELUAR']; ?>" required><br><br>

            <label for="gambar_bahan_keluar">Gambar Bahan Keluar (optional):</label><br>
            <input type="file" id="gambar_bahan_keluar" name="gambar_bahan_keluar"><br><br>

            <label for="bahan_masuk">Bahan Masuk:</label><br>
            <input type="text" id="bahan_masuk" name="bahan_masuk" value="<?php echo htmlspecialchars($row['BAHAN MASUK']); ?>" required><br><br>

            <label for="tarikh_bahan_masuk">Tarikh Bahan Masuk:</label><br>
            <input type="datetime-local" id="tarikh_bahan_masuk" name="tarikh_bahan_masuk" value="<?php echo $row['TARIKH DAN MASA BAHAN MASUK']; ?>" required><br><br>

            <label for="gambar_bahan_masuk">Gambar Bahan Masuk (optional):</label><br>
            <input type="file" id="gambar_bahan_masuk" name="gambar_bahan_masuk"><br><br>

            <input type="submit" value="Update Data">
        </form>
    <?php endif; ?>
    
    <p><a href="logout.php">Log Out</a></p>
</body>
</html>