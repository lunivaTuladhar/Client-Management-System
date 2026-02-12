<?php 
session_start();
include("../db.php");

// Make sure user is logged in
if(!isset($_SESSION['user_id'])){
    header("location:../login/log in.php");
    exit();
}

$emp_id = $_SESSION['user_id'];

// Get the company ID of logged-in employee
$company_query = $conn->prepare("SELECT Company_ID FROM employee WHERE Emp_ID=? LIMIT 1");
$company_query->bind_param("i", $emp_id);
$company_query->execute();
$company_result = $company_query->get_result();
if($company_result->num_rows==0){
    die("Employee not found.");
}
$company_row = $company_result->fetch_assoc();
$company_id = $company_row['Company_ID'];

// Get the date filter from GET or use today
$date_filter = $_GET['date'] ?? date('Y-m-d');

// Determine status filter from GET (toggle)
$status_filter = $_GET['status'] ?? 'All';

// Build SQL query
$sql = "SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status,
               c.Name AS Client_Name, e.Name AS Employee_Name, ba.Emp_ID
        FROM book_appt ba
        LEFT JOIN client c ON ba.Client_ID = c.Client_ID
        LEFT JOIN employee e ON ba.Emp_ID = e.Emp_ID
        WHERE e.Company_ID = ? AND ba.Date = ? ";

$params = [$company_id, $date_filter];
$types = "is";

if($status_filter == "Approved"){
    $sql .= " AND ba.Status = ?";
    $params[] = "Approved";
    $types .= "s";
} elseif($status_filter == "pending"){
    $sql .= " AND ba.Status = ?";
    $params[] = "pending";
    $types .= "s";
}

$sql .= " AND ba.Status !='Completed' ORDER BY ba.Date ASC, ba.Time ASC";

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <title>Appointment List</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
</head>
<style>
/* Keep your original styling */
button{width: 164px;}
#content{padding:12px;margin-left:74px;border-radius:12px;background-color:white;margin-right:14;}
#top{display:flex;justify-content: space-between;margin-bottom:12px;}
h3{color: #2d2d2dff;margin:0;vertical-align:middle;}
#container{background-color:#F5F3F3;height: 86vh;padding: 74 0 0 0;}
#topic{margin-top:2px; display:flex;width: 100%;justify-content: space-between;align-items: center;}
.toggle-container { display:flex;border:1px solid #ccc;border-radius:35px;overflow:hidden;background-color:#f9f9f9;}
.toggle-option { padding:8px 0; border:none;background:none;cursor:pointer;outline:none;color:#555;transition:all 0.3s ease;width:70px;height:32px;}
.toggle-option:not(:last-child) { border-right:1px solid #ddd; }
.toggle-option.active { background-color:#dbe3ff;color:#2563eb;border-radius:20px; }
table{background-color:#F5F3F3;padding:10px;border-radius:12px;width:100%;border-collapse:collapse;}
th, td{padding:12px;text-align:left;}
th{background-color: rgba(14,62,217,0.2); color: rgba(14,62,217,0.9);}
tr:hover{background-color:#eaeaea;}
</style>
<body>
<?php include ("../fixed/sidebar.php") ?>
<div id="container">
<div id="content">

<div id="top">
    <div id="topic">
        <h3>Appointments on 
            <form method="GET" style="display:inline;">
                <input type="date" name="date" value="<?= $date_filter ?>" onchange="this.form.submit()">
                <input type="hidden" name="status" value="<?= $status_filter ?>">
            </form>
        </h3>
        <div class="toggle-container">
            <a href="?date=<?= $date_filter ?>&status=All"><button class="toggle-option <?= $status_filter=='All'?'active':'' ?>">All</button></a>
            <a href="?date=<?= $date_filter ?>&status=Approved"><button class="toggle-option <?= $status_filter=='Approved'?'active':'' ?>">Approved</button></a>
            <a href="?date=<?= $date_filter ?>&status=pending"><button class="toggle-option <?= $status_filter=='pending'?'active':'' ?>">Requests</button></a>
        </div>
    </div>    
</div>

<table>
    <thead>
        <th>SN</th>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>With</th>
        <th>Status</th>
        <th>Action</th>
    </thead>
    <tbody>
        <?php
        if($result->num_rows > 0){
            $sn = 1;
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>{$sn}</td>";
                echo "<td>{$row['Client_Name']}</td>";
                echo "<td>{$row['Date']}</td>";
                echo "<td>{$row['Time']}</td>";
                echo "<td>{$row['Employee_Name']}</td>";
                echo "<td>{$row['Status']}</td>";
                echo "<td><a href='ApptDetails.php?Appt_ID={$row['Appt_ID']}'>View Details</a></td>";
                echo "</tr>";
                $sn++;
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No appointments found for this date</td></tr>";
        }
        ?>
    </tbody>
</table>

</div>
</div>
</body>
</html>
