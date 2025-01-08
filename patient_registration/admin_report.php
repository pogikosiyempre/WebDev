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

// Get the selected date from the query string
if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // SQL query to fetch patient records for the given consultation date
    $sql = "SELECT id, name, email, contact, consultation_reason, consultation_done FROM patients WHERE consultation_date = '$date'";
    $result = $conn->query($sql);
}

// Update the consultation_done status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $patient_id = $_POST['patient_id'];
    $consultation_done = $_POST['consultation_done'];

    // SQL query to update consultation_done status
    $update_sql = "UPDATE patients SET consultation_done = '$consultation_done' WHERE id = '$patient_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<p>Status updated successfully!</p>";
    } else {
        echo "<p>Error updating status: " . $conn->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details - <?php echo date('d/m/Y', strtotime($date)); ?></title>
    <style>
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
        .logout-link {
            color: #ff4d4d;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h2>Patient Details for <?php echo date('d/m/Y', strtotime($date)); ?></h2>
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_report.php">Report</a>
    <a href="admin_logout.php" class="logout-link">Logout</a>
</nav>

<div class="container">

    <?php
    // Display the details of patients for the selected date
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Consultation Reason</th>
                    <th>Consultation Status</th>
                    <th>Mark as Done</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            $consultation_status = $row['consultation_done'] ? 'Done' : 'Not Done';
            $checked = $row['consultation_done'] ? 'checked' : '';

            echo "<tr>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['contact'] . "</td>
                    <td>" . $row['consultation_reason'] . "</td>
                    <td>" . $consultation_status . "</td>
                    <td>
                        <form method='POST' action=''>
                            <input type='hidden' name='patient_id' value='" . $row['id'] . "'>
                            <input type='checkbox' name='consultation_done' value='1' $checked>
                            <button type='submit' name='update_status'>Update</button>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No patients found for this date.</p>";
    }

    $conn->close();
    ?>

</div>

</body>
</html>
