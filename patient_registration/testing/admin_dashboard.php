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

// SQL query to fetch all patient records
$sql = "SELECT id, name, email, contact, consultation_date FROM patients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Patient Management</title>
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
    <h2>Welcome to Admin Dashboard</h2>
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_logout.php" class="logout-link">Logout</a>
</nav>

<div class="container">

    <h3>All Patient Records</h3>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Consultation Date</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            // Make sure the date is formatted correctly
            $consultationDate = $row['consultation_date'];
            if ($consultationDate) {
                $formattedDate = date('d/m/Y', strtotime($consultationDate));
            } else {
                $formattedDate = 'N/A'; // Display N/A if no date exists
            }

            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['contact'] . "</td>
                    <td>" . $formattedDate . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }

    $conn->close();
    ?>

</div>

</body>
</html>
