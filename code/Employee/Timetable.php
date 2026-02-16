<?php
session_start();
?>
<html>
<head>
    <title>Employee View</title>
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
    #top{
        display:flex;
        justify-content: space-between;
        margin-bottom:12px;
        align-items: center;
    }
    #top button {
        right:0;
        width:150px;

    }
    #top-right{
        width: auto;
        display:flex;
        align-items: center;
        gap:12px;
    }
    input{
        height: 32px;
        width: 200px;
        margin:0px;
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
          border-radius:12px; 
          width:100%; 
          border-collapse:collapse; 
        }
        th, td{ 
          padding:12px; 
        }
        th{ 
          background-color: rgba(14,62,217,0.2); 
          text-align:left; 
          color: rgba(14,62,217,0.9);
        }
        
        thead th:first-child{ 
          border-top-left-radius:12px; 
          border-bottom-left-radius:12px; 
        }
        thead th:last-child{ 
          border-top-right-radius:12px; 
          border-bottom-right-radius:12px;
        }
        #table{
            padding:12px;
            background-color:#F5F3F3;
            border-radius:12px;
        }
    #role_select{
        background-color:#ffffff;
        border: 1px solid rgba(45,45,45,0.2);
        color: rgba(45,45,45,0.9);
        width:150px;
        height: 32px;
        margin: 0;
    }
    #top-right button{
        width:150px;
    }

    </style>
</head>

<body >
    <div id="container"style="">
        <?php include('../fixed/sidebar.php');?>
        <div id="content">

            <div id="top">
                <h3 style="color:2D2D2D ">Timetable</h3>
               
            </div>
            <div id="table">
           <table width="100%">
    <thead>
        <tr>
            <th>Day</th>
            <th>Start Time - End Time</th>
        </tr>
    </thead>

    <tbody>
<?php
$emp_id = $_SESSION['user_id'];

// fetch all time slots for this employee
$sql = "
    SELECT 
        ts.sun, ts.mon, ts.tue, ts.wed, ts.thu, ts.fri, ts.sat,
        t.Start_Time, t.End_Time
    FROM timetable ts
    INNER JOIN time_stamp t ON t.Time_ID = ts.Time_ID
    WHERE ts.Emp_ID = $emp_id
";

$res = $conn->query($sql);

// prepare static days
$days = [
    'Sunday'    => [],
    'Monday'    => [],
    'Tuesday'   => [],
    'Wednesday' => [],
    'Thursday'  => [],
    'Friday'    => [],
    'Saturday'  => []
];

if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {

        $time = $row['Start_Time'] . " - " . $row['End_Time'];

        if ($row['sun'] == 1) $days['Sunday'][] = $time;
        if ($row['mon'] == 1) $days['Monday'][] = $time;
        if ($row['tue'] == 1) $days['Tuesday'][] = $time;
        if ($row['wed'] == 1) $days['Wednesday'][] = $time;
        if ($row['thu'] == 1) $days['Thursday'][] = $time;
        if ($row['fri'] == 1) $days['Friday'][] = $time;
        if ($row['sat'] == 1) $days['Saturday'][] = $time;
    }
}

// print static rows
foreach ($days as $day => $times) {

    if (count($times) > 0) {
        $timeText = implode(", ", $times);
    } else {
        $timeText = "-";
    }

    echo "
    <tr>
        <td>$day</td>
        <td colspan='2'>$timeText</td>
    </tr>";
}
?>
</tbody>

</table>
</div>

        </div>
    </div>
</body>
</html>