<?php
session_start();
require_once "config.php"; // Include your database connection file

// Check if the user is logged in and has the correct jawatan
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['jawatan'] !== 'kurator') {
    header("Location: login.php"); // Redirect to login if not logged in as kurator
    exit();
}

// Handle the actions (edit or delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    // Delete action
    if ($action == 'delete') {
        $sql = "DELETE FROM konservasi WHERE id = ?";
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
        $sql = "SELECT * FROM konservasi WHERE id = ?";
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
    $bahan_organik = $_POST['bahan_organik'];
    $bahan_bukan_organik = $_POST['bahan_bukan_organik'];
    $deskripsi_bahan_bukan_organik = $_POST['deskripsi_bahan_bukan_organik'];
    $komposit = $_POST['komposit'];
    $mula = $_POST['mula'];
    $gambar_bahan_mula = $_FILES['gambar_bahan_mula']['tmp_name'] ?? null;
    $siap = $_POST['siap'];
    $gambar_bahan_siap = $_FILES['gambar_bahan_siap']['tmp_name'] ?? null;
    $tarikh_direkod = date("Y-m-d H:i:s");
    $nama_perekod = $_SESSION['username']; // Logged-in user's name

    // Process images if provided
    if ($gambar_bahan_mula) {
        $gambar_bahan_mula_data = file_get_contents($gambar_bahan_mula);
    } else {
        $gambar_bahan_mula_data = null;
    }

    if ($gambar_bahan_siap) {
        $gambar_bahan_siap_data = file_get_contents($gambar_bahan_siap);
    } else {
        $gambar_bahan_siap_data = null;
    }

    // SQL query to update the record
    $sql = "UPDATE konservasi 
            SET `BAHAN ORGANIK` = ?, `BAHAN BUKAN ORGANIK` = ?, `DESKRIPSI BAHAN BUKAN ORGANIK` = ?, 
                `KOMPOSIT` = ?, `MULA` = ?, `GAMBAR BAHAN MULA` = ?, 
                `SIAP` = ?, `GAMBAR BAHAN SIAP` = ?, `TARIKH DIREKOD DAN MASA` = ?, 
                `NAMA PEREKOD` = ? 
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssi", $bahan_organik, $bahan_bukan_organik, $deskripsi_bahan_bukan_organik, 
                          $komposit, $mula, $gambar_bahan_mula_data, $siap, 
                          $gambar_bahan_siap_data, $tarikh_direkod, $nama_perekod, $id);

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
    <title>Urus Data Konservasi</title>
</head>
<body>
    <h1>Urus Data Konservasi</h1>

    <!-- Displaying Records -->
    <table border="1">
        <thead>
            <tr>
                <th>Bahan Organik</th>
                <th>Bahan Bukan Organik</th>
                <th>Tarikh Mula</th>
                <th>Tarikh Siap</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display all records for editing/deleting
            $sql = "SELECT * FROM konservasi";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['BAHAN ORGANIK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['BAHAN BUKAN ORGANIK']) . "</td>";
                echo "<td>" . htmlspecialchars($row['MULA']) . "</td>";
                echo "<td>" . htmlspecialchars($row['SIAP']) . "</td>";
                echo "<td>";
                echo "<a href='urus_data_konservasi.php?action=edit&id=1" . $row['id'] . "'>Edit</a> | ";
                echo "<a href='urus_data_konservasi.php?action=delete&id=1" . $row['id'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Edit form -->
    <?php if (isset($row)): ?>
        <h2>Edit Data</h2>
        <form action="urus_data_konservasi.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <label for="bahan_organik">Bahan Organik:</label><br>
            <input type="text" id="bahan_organik" name="bahan_organik" value="<?php echo htmlspecialchars($row['BAHAN ORGANIK']); ?>" required><br><br>

            <label for="bahan_bukan_organik">Bahan Bukan Organik:</label><br>
            <select id="bahan_bukan_organik" name="bahan_bukan_organik" required>
                <option value="KURATIF" <?php echo ($row['BAHAN BUKAN ORGANIK'] == 'KURATIF') ? 'selected' : ''; ?>>KURATIF</option>
                <option value="PREVENTIF" <?php echo ($row['BAHAN BUKAN ORGANIK'] == 'PREVENTIF') ? 'selected' : ''; ?>>PREVENTIF</option>
                <option value="RESTORASI" <?php echo ($row['BAHAN BUKAN ORGANIK'] == 'RESTORASI') ? 'selected' : ''; ?>>RESTORASI</option>
				<option value="TIADA" <?php echo ($row['BAHAN BUKAN ORGANIK'] == 'TIADA') ? 'selected' : ''; ?>>TIADA</option>
            </select><br><br>

            <label for="deskripsi_bahan_bukan_organik">Deskripsi Bahan Bukan Organik:</label><br>
            <input type="text" id="deskripsi_bahan_bukan_organik" name="deskripsi_bahan_bukan_organik" value="<?php echo htmlspecialchars($row['DESKRIPSI BAHAN BUKAN ORGANIK']); ?>" required><br><br>

            <label for="komposit">Komposit:</label><br>
            <input type="text" id="komposit" name="komposit" value="<?php echo htmlspecialchars($row['KOMPOSIT']); ?>"><br><br>

            <label for="mula">Tarikh Mula:</label><br>
            <input type="datetime-local" id="mula" name="mula" value="<?php echo $row['MULA']; ?>" required><br><br>

            <label for="gambar_bahan_mula">Gambar Bahan Mula:</label><br>
            <input type="file" id="gambar_bahan_mula" name="gambar_bahan_mula"><br><br>

            <label for="siap">Tarikh Siap:</label><br>
            <input type="datetime-local" id="siap" name="siap" value="<?php echo $row['SIAP']; ?>" required><br><br>

            <label for="gambar_bahan_siap">Gambar Bahan Siap:</label><br>
            <input type="file" id="gambar_bahan_siap" name="gambar_bahan_siap"><br><br>

            <input type="submit" value="Update">
        </form>
    <?php endif; ?>
</body>
</html>