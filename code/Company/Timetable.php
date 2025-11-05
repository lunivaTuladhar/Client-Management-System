<?php
session_start();
include('../db.php'); // your database connection
?>

<html>
<head>
    <title>Timetable View</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
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
        table{
            background-color:#F5F3F3;
            padding:10px;
            border-radius: 12px;
            width:100%;
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

        <table>
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>More</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Fetch timetable details
            $sql = "SELECT e.Emp_ID, e.Name AS EmpName, e.Email, e.Phone, e.Role,
                           c.Name AS Name, ts.Start_Time, ts.End_Time
                    FROM timetable t
                    INNER JOIN employee e ON t.Emp_ID = e.Emp_ID
                    INNER JOIN company c ON t.Company_ID = c.Company_ID
                    INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
                    ORDER BY e.Name ASC, ts.Start_Time ASC";

            $result = $conn->query($sql);

            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($row['EmpName'])."</td>";
                    echo "<td>".htmlspecialchars($row['Email'])."</td>";
                    echo "<td>".htmlspecialchars($row['Start_Time'])."</td>";
                    echo "<td>".htmlspecialchars($row['End_Time'])."</td>";
                    echo "<td><a href='TimetableDetail.php?emp_id=".$row['Emp_ID']."'>View</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center'>No timetable assigned</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
