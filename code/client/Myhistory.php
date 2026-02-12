<?php
session_start();
include('../db.php'); 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location:../login/log in.php");
    exit();
}

$clientId = $_SESSION['user_id'];

// Fetch all appointments of client
$query = $conn->prepare("
    SELECT b.Appt_ID, b.Date, b.Time, b.Reason, b.Status,
           c.Name,
           e.Name AS Emp_Name
    FROM book_appt b
    INNER JOIN company c ON b.Company_ID = c.Company_ID
    INNER JOIN employee e ON b.Emp_ID = e.Emp_ID
    WHERE b.Client_ID = ?
    ORDER BY b.Date DESC, b.Time DESC
");

$query->bind_param("i", $clientId);
$query->execute();
$result = $query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment History</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        #container{

    background-color:#F5F3F3;
    height: 86vh;
    
   
}
table{
    background-color:#F5F3F3;
    padding:10px;
    border-radius: 12px;
    width:100%;
}
td{
    padding:12px;
    background-color:#F5F3F3;
}
th{
    background-color: rgb(14,62,217,0.2);
    padding:12px;
    text-align:left;
    color: rgb(14,62,217,0.9);
}
tr{
    height:32px;
}
        .status {
            padding: 6px 12px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            text-align: center;
            display: inline-block;
        }

        .pending { color: #ffc107; }
        .approved { color: #28a745; }
        .rejected { color: #dc3545; }
    </style>
</head>
<?php include ("../fixed/sidebar.php"); ?>
<body>

<div class="container" style="margin-left:74px; margin-top: 50px;">
    <h3>Appointment History</h3>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td data-label="Company"><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td data-label="Employee"><?php echo htmlspecialchars($row['Emp_Name']); ?></td>
                    <td data-label="Date"><?php echo $row['Date']; ?></td>
                    <td data-label="Time"><?php echo $row['Time']; ?></td>
                    <td data-label="Reason"><?php echo htmlspecialchars($row['Reason']); ?></td>
                    <td data-label="Status">
                        <span class="status 
                        <?php 
                        echo strtolower($row['Status']) == 'pending' ? 'pending' : 
                             (strtolower($row['Status']) == 'approved' ? 'approved' : 'rejected');
                        ?>">
                        <?php echo $row['Status']; ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>
        <p style="text-align:center; color:#555;">No history found.</p>
    <?php } ?>

</div>

</body>
</html>
