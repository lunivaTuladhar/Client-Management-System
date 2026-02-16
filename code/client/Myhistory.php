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
    WHERE b.Client_ID = ? AND b.Status IN ('Completed','Rejected')
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
        
.container{
    background-color:#F5F3F3;
    height: 89vh;
    margin-top:50px;
    padding-top:8px;

}
#content{
    padding: 12px;
    margin-left:74px;
    margin-top:12px;
    border-radius:12px;
    background-color: white ;
    margin-right: 14px;
    width:91%;
}
h3{
    color: #2d2d2dff;
    margin:0;
    vertical-align:middle;
}
table{
    padding:10px;
    border-radius:12px;
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:12px;
    text-align:left;
}
th{
    background-color: rgba(14,62,217,0.2); 
    color: rgba(14,62,217,0.9);
    
}
th:last-child{
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}
th:first-child{
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}
#table{
    padding:12px;
    background-color:#F5F3F3;
    border-radius:12px;
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
        .completed { color: #28a745; }
        .rejected { color: #dc3545; }
    </style>
</head>
<?php include ("../fixed/sidebar.php"); ?>
<body>  

<div class="container">
    <div id="content">
        <h3>Appointment History</h3>

        <div id="table">
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
                
        <?php if ($result->num_rows > 0) { ?>
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
                                 (strtolower($row['Status']) == 'approved' ? 'approved' :
                                 (strtolower($row['Status']) == 'completed' ? 'completed' :

                                  'rejected')
                                  );
                            ?>">
                            <?php echo $row['Status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
                </tbody> <?php } else { ?>
                    <tr >
                        <td colspan="6" style="text-align:center; color:#555;">No history found.</td>
                    </tr>
                <?php }
            ?>
            </table>
            </div>
                
    </div>
</div>

</body>
</html>
