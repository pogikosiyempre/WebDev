<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "patientdb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for updating consultation status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $consultation_status = $_POST['consultation_status'];

    // Validate consultation status input
    if (!empty($patient_id) && !empty($consultation_status)) {
        // Use prepared statements for safe SQL execution
        $update_sql = "UPDATE patients SET consultation_status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $consultation_status, $patient_id); // "si" means string and integer
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Consultation status updated successfully');</script>";
        } else {
            echo "<script>alert('No changes made.');</script>";
        }
        $stmt->close();
    }
}

// Get the selected date from the query string
$date = isset($_GET['date']) ? $_GET['date'] : null;

if ($date) {
    // SQL query to fetch patient records for the given consultation date
    $sql = "SELECT id, name, email, contact, consultation_reason, consultation_status 
            FROM patients 
            WHERE consultation_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date); // "s" means string
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details - <?php echo $date ? date('d/m/Y', strtotime($date)) : 'No Date Selected'; ?></title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        h2 {
            margin: 0;
            font-size: 24px;
        }
        nav {
            background-color: #222;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
            border-radius: 5px;
        }
        nav a:hover {
            background-color: #444;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        form {
            margin: 0;
        }
        select {
            padding: 5px;
        }
    </style>
</head>
<body>

<header>
    <h2>Patient Details for <?php echo $date ? date('d/m/Y', strtotime($date)) : 'No Date Selected'; ?></h2>
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_report.php">Report</a>
    <a href="admin_logout.php" class="logout-link">Logout</a>
</nav>

<div class="container">
    <?php if ($date): ?>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Consultation Reason</th>
                    <th>Consultation Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['consultation_reason']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="patient_id" value="<?php echo $row['id']; ?>">
                                <select name="consultation_status" onchange="this.form.submit()">
                                    <option value="Not Attended" <?php echo $row['consultation_status'] == 'Not Attended' ? 'selected' : ''; ?>>Not Attended</option>
                                    <option value="Being Consulted" <?php echo $row['consultation_status'] == 'Being Consulted' ? 'selected' : ''; ?>>Being Consulted</option>
                                    <option value="Done" <?php echo $row['consultation_status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No patients found for this date.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No date selected. Please go back and select a date.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

</body>
</html>
