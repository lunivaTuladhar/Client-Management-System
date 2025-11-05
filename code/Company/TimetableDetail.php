<?php
session_start();
include('../db.php'); // your DB connection

// Get employee ID from GET parameter
$emp_id = isset($_GET['emp_id']) ? intval($_GET['emp_id']) : 0;
if($emp_id <= 0){
    die("Invalid Employee ID.");
}

// Fetch timetable details only for this employee
$sql = "SELECT e.Emp_ID, e.Name AS Emp_Name, ts.Start_Time, ts.End_Time, t.Time_ID,
               t.Sun, t.Mon, t.Tue, t.Wed, t.Thu, t.Fri, t.Sat
        FROM timetable t
        INNER JOIN employee e ON t.Emp_ID = e.Emp_ID
        INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
        WHERE e.Emp_ID = ?
        ORDER BY ts.Start_Time";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $emp_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare timetable array
$timetable = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $timeId = $row['Time_ID'];
        $days = [];
        if($row['Sun']) $days[] = 'Sun';
        if($row['Mon']) $days[] = 'Mon';
        if($row['Tue']) $days[] = 'Tue';
        if($row['Wed']) $days[] = 'Wed';
        if($row['Thu']) $days[] = 'Thu';
        if($row['Fri']) $days[] = 'Fri';
        if($row['Sat']) $days[] = 'Sat';

        $timetable[$timeId] = [
            'Emp_Name' => $row['Emp_Name'],
            'Start_Time' => $row['Start_Time'],
            'End_Time' => $row['End_Time'],
            'Days' => $days
        ];
    }
} else {
    die("No timetable found for this employee.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timetable Details</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #2d2d2d;
            text-align: left;
        }
        th {
            background-color: rgba(14,62,217,0.2);
            color: rgba(14,62,217,0.9);
        }
        tr:nth-child(even){
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Timetable for Employee ID: <?php echo $emp_id; ?></h2>
    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Days</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($timetable as $timeId => $info){
                echo "<tr>";
                echo "<td>{$emp_id}</td>";
                echo "<td>{$info['Emp_Name']}</td>";
                echo "<td>{$info['Start_Time']}</td>";
                echo "<td>{$info['End_Time']}</td>";
                echo "<td>".(!empty($info['Days']) ? implode(", ", $info['Days']) : "No Days Selected")."</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
