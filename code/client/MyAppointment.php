<?php
session_start();
include("../db.php");

// Ensure logged in
if (!isset($_SESSION['user_id'])) {
    header("location:../login/log in.php");
    exit();
}

$clientId = $_SESSION['user_id'];

// Fetch appointments
$sql = $conn->prepare("
    SELECT b.Appt_ID, b.Date, b.Time, b.Status,
           c.Name,
           e.Name AS Emp_Name
    FROM book_appt b
    INNER JOIN company c ON b.Company_ID = c.Company_ID
    INNER JOIN employee e ON b.Emp_ID = e.Emp_ID
    WHERE b.Client_ID = ? and b.status !='Cancelled'
    ORDER BY b.Date DESC, b.Time DESC
");
$sql->bind_param("i", $clientId);
$sql->execute();
$appointments = $sql->get_result();
?>
<html>
<head>
    <title>My appointments</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
</head>

<style>
/* Your same CSS unchanged */
button{
    width: 164px;
}
#content{
    padding: 12px;
    margin-left:74px;
    border-radius:12px;
    background-color: white ;
    margin-right: 14;
}
#top{
     display:flex;
    justify-content: space-between;
    margin-bottom:12px;
}
h3{
    color: #2d2d2dff;
    margin:0;
    vertical-align:middle;
}
#container{
    background-color:#F5F3F3;
    height: 86vh;
    padding: 74 0 0 0;
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
.pending { color: #ffc107; }
        .approved { color: #28a745; }
        .rejected { color: #dc3545; }
</style>

<body>
<?php include ("../fixed/sidebar.php") ?>

<div id="container">
<div id="content">

<div id="top">
    <div id="topic">
        <h3>My Appointments</h3>
    </div>    
</div>

<table>
    <thead>
        <th>SN</th>
        <th>Company</th>
        <th>Date</th>
        <th>Time</th>
        <th>With</th>
        <th>Status</th>
        <th></th>
    </thead>

    <?php
    $sn = 1;
    if ($appointments->num_rows > 0) {
        while ($row = $appointments->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo htmlspecialchars($row['Name']); ?></td>
            <td><?php echo htmlspecialchars($row['Date']); ?></td>
            <td><?php echo htmlspecialchars($row['Time']); ?></td>
            <td><?php echo htmlspecialchars($row['Emp_Name']); ?></td>
            <td><span class="status 
                        <?php 
                        echo strtolower($row['Status']) == 'pending' ? 'pending' : 
                             (strtolower($row['Status']) == 'approved' ? 'approved' : 'rejected');
                        ?>">
                        <?php echo $row['Status']; ?>
                        </span></td>
            <td><a href="ViewAppointment.php?id=<?php echo $row['Appt_ID']; ?>">View Details</a></td>
        </tr>
    <?php 
        }
    } else { 
    ?>
        <tr>
            <td colspan="7" style="text-align:center; padding:20px;">No appointments found.</td>
        </tr>
    <?php } ?>
</table>

</div>
</div>

</body>
</html>
