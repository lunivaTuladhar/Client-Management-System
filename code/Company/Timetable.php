<?php
session_start();
include('../db.php'); 

if(isset($_GET['delete'])){

    $time_id = intval($_GET['delete']);

     $stmt = $conn->prepare("DELETE FROM time_stamp WHERE Time_ID = ? ");
    $stmt->bind_param("i", $time_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM timetable WHERE Time_ID = ? AND Company_ID = ?");
    $stmt->bind_param("ii", $time_id, $company_id);
    $stmt->execute();

    if($stmt){

        echo "<script>alert('Timetable deleted successfully'); window.location='Timetable.php';</script>";
        exit;

    } else {
        echo "<script>alert('Delete failed or not authorized'); window.location='Timetable.php';</script>";
        exit;
    }
}


?>

<html>
<head>
    <title>Timetable View</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">

    <style>

        input{height:32px;width:300px;margin:0;}

        #content{
            padding: 12px;
            margin-left:74px;
            border-radius:12px;
            background-color: white ;
            margin-right: 14;
        }
        #container{
        background-color:#F5F3F3;
        height: 86vh;
        padding: 74 0 0 0;
        }
        #top{ 
            display:flex; 
            justify-content: space-between; 
            margin-bottom:12px; 
            align-items: center;
        }
        #top-right{ 
            display:flex; 
            gap:12px; 
        }
        table{background:#F5F3F3;padding:10px;border-radius:12px;width:100%;border-collapse:collapse;text-align:left;}
        th,td{padding:12px;}
        th{background:rgba(14,62,217,.2);color:rgba(14,62,217,.9);}
        
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
        h3{
            margin:0px;
        }
    </style>
</head>
<body>
<div id="container">
    <?php include('../fixed/sidebar.php'); ?>
    <div id="content">
        <div id="top">
            <h3>Timetable</h3>
            <div id="top-right">
                <input type="text" placeholder="Search" style="width:300px;"/>
                <button onclick="window.location='AddTimetable.php'">Add Timetable</button>
            </div>
        </div>
        <div id="table">
        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Days</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
$company_id = $_SESSION['company_id'];

// Fetch timetable details including days
$sql = "SELECT t.Time_ID,
               e.Emp_ID, e.Name AS EmpName, e.Email,
               ts.Start_Time, ts.End_Time,
               t.Sun, t.Mon, t.Tue, t.Wed, t.Thu, t.Fri, t.Sat
        FROM timetable t
        INNER JOIN employee e ON t.Emp_ID = e.Emp_ID
        INNER JOIN company c ON t.Company_ID = c.Company_ID 
            AND c.Company_ID = $company_id
        INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
        ORDER BY e.Name ASC, ts.Start_Time ASC";


$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){

        // Prepare days array
        $days = [];
        if($row['Sun']) $days[] = 'Sun';
        if($row['Mon']) $days[] = 'Mon';
        if($row['Tue']) $days[] = 'Tue';
        if($row['Wed']) $days[] = 'Wed';
        if($row['Thu']) $days[] = 'Thu';
        if($row['Fri']) $days[] = 'Fri';
        if($row['Sat']) $days[] = 'Sat';

        echo "<tr>";
        echo "<td>".htmlspecialchars($row['EmpName'])."</td>";
        echo "<td>".htmlspecialchars($row['Email'])."</td>";
        echo "<td>".htmlspecialchars($row['Start_Time'])."</td>";
        echo "<td>".htmlspecialchars($row['End_Time'])."</td>";
        echo "<td>".(!empty($days) ? implode(", ", $days) : "No Days Selected")."</td>";
      echo "<td>
      <div style='display:flex; gap:12px;'>
      
      <a href='edit_timetable.php?id=".$row['Time_ID']."'
           style='color:#007bff; text-decoration:none;'>
           <p>Edit</p>
        </a>

        <a href='?delete=".$row['Time_ID']."'
           onclick=\"return confirm('Are you sure you want to delete this timetable?');\"
           style='color:rgba(239,24,24,0.9); ;text-decoration:none;'>
           <p>Delete</p>
        </a>

        </div>
      </td>";


        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center'>No timetable assigned</td></tr>";
}

?>

            </tbody>
        </table>
</div>
    </div>
</div>
</body>
</html>
