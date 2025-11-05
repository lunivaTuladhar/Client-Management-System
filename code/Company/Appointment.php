<?php 
session_start();
include("../db.php");

// Assuming you store company id in session
$company_id = $_SESSION['Company_ID'] ?? 0;

// Determine status filter from GET (toggle)
$status_filter = $_GET['status'] ?? 'All';

// Build SQL query based on filter
$sql = "SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status, 
               c.Name AS Client_Name, e.Name AS Employee_Name, ba.Emp_ID
        FROM book_appt ba
        LEFT JOIN client c ON ba.Client_ID = c.Client_ID
        LEFT JOIN employee e ON ba.Emp_ID = e.Emp_ID
        WHERE e.Company_ID = ? ";

$params = [$company_id];
$types = "i";

if($status_filter == "Approved"){
    $sql .= " AND ba.Status = ?";
    $params[] = "Approved";
    $types .= "s";
} elseif($status_filter == "Requests"){
    $sql .= " AND ba.Status = ?";
    $params[] = "Request";
    $types .= "s";
}

$sql .= " ORDER BY ba.Date, ba.Time";

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <title>Employee View</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
</head>
<style>
     
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
    #topic{
        margin-top:2px;
    } 
    table{
        background-color:#F5F3F3;
        padding:10px;
        border-radius: 12px;
    }
     th td{
        border:1px solid blue;
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
        width:100px;
    }
    tr{
        height:32px;
    }
    thead th:first-child{
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width: 12px;
    }
    thead th:last-child{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    #topic{
        display:flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    #top-div{
            margin-top:30px;
            display:flex;
            background:none;
            border-radius:25px;
    }
    .toggle-container {
        display: flex;
        border: 1px solid #ccc;
        border-radius: 35px;
        overflow: hidden;
        background-color: #f9f9f9;
    }

    .toggle-option {
      padding: 8px 0px;
      border: none;
      background: none;
      cursor: pointer;
      outline: none;
      color: #555;
      transition: all 0.3s ease;
      width:70px;
      height:32px;
    }
    .toggle-option:not(:last-child) {
      border-right: 1px solid #ddd;
    }
    .toggle-option.active {
      background-color: #dbe3ff;
      color: #2563eb;
      border-radius: 20px;
    } 
</style>
<body>
<?php include ("../fixed/sidebar.php") ?>
<div id="container">

<div id="content">

<div id="top">
    <div id="topic">
        <h3 style="color:2D2D2D ">Appointments</h3>
        <div class="toggle-container">
            <a href="?status=All"><button class="toggle-option <?= $status_filter=='All'?'active':'' ?>" style="font-size:.7rem">All</button></a>
            <a href="?status=Approved"><button class="toggle-option <?= $status_filter=='Approved'?'active':'' ?>" style="font-size:.7rem">Approved</button></a>
            <a href="?status=Requests"><button class="toggle-option <?= $status_filter=='Requests'?'active':'' ?>" style="font-size:.7rem">Requests</button></a>
        </div>
    </div>    
</div>

<table width="100%">
    <thead>
        <th>SN</th>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>With</th>
        <th>Status</th>
        <th></th>
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
                echo "<td><a href='TimetableDetail.php?Emp_ID={$row['Emp_ID']}'>View Details</a></td>";
                echo "</tr>";
                $sn++;
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No appointments found</td></tr>";
        }
        ?>
    </tbody>
</table>

</div>
</div>
</body>
</html>
