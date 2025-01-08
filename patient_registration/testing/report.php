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

// SQL query to fetch distinct consultation dates
$sql = "SELECT DISTINCT consultation_date FROM patients ORDER BY consultation_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Consultation Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .container {
            margin: 20px;
        }
    </style>
    <script>
        function loadPatientDetails(date) {
            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("patientDetails").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "fetch_patients.php?date=" + date, true);
            xhttp.send();
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Consultation Report</h1>

        <!-- List all distinct consultation dates -->
        <h3>Consultation Dates</h3>
        <table>
            <tr>
                <th>Date</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $date = $row['consultation_date'];
                    echo "<tr onclick='loadPatientDetails(\"$date\")'>
                            <td>$date</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='1'>No consultation records found.</td></tr>";
            }
            ?>
        </table>

        <!-- Patient details will be displayed here -->
        <div id="patientDetails">
            <!-- Patient info will be loaded dynamically -->
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
