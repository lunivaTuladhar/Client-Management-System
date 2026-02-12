<?php
session_start();

$conn = new mysqli("localhost", "root", "", "cms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$company_id = $_SESSION['company_id'] ?? 1;
$today = date('D'); // Sun, Mon, Tue, Wed, Thu, Fri, Sat
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        
        #whole_container{
          display:flex;
          gap:8px;
          margin-top:38px;
          margin-left:5%;
          background-color:#F5F3F3; 
          height:550px;
        }
        #left_container{
          width:700px
        }
        #right_container{
          width:700px;
          margin-top:12px;
        }
        #welcome{
            background-color:white; 
            margin-left:12px; 
            margin-right:12px; 
            margin-top:24px; 
            display:flex; 
            justify-content:space-between; 
            align-items:center; 
            padding:8px 12px; 
            border-radius:12px; 
            height:50px; 
        }
        #timetable,.right-top,.right-bottom{
            background:white;
            border-radius:12px;
            padding:12px;
            margin:12px;
        }
        table{
          width:100%;
          background:#F5F3F3;
          border-radius:12px;
        }
        th,td{
          padding:10px;
        }
        th{
          background:rgba(14,62,217,.2);
          color:rgba(14,62,217,.9);
        }
        .right-top{
          margin-top:12px;
          height:300px;
          padding:12px;
          border-radius: 12px;
          background-color:white;
        }
        
        
    </style>
</head>

<body>
<?php include("../fixed/sidebar.php"); ?>

<div id="whole_container">

<!-- LEFT SIDE -->
<div id="left_container">

    <div id="welcome">
        <h2>WELCOME, <?php echo strtoupper($emp_name); ?></h2>
        <img src="<?php echo $profile_pic; ?>" alt="Pic" height="40px" width="40px" class="profile" style="border-radius:50%">
    </div>

    <!-- MAIN TIMETABLE -->
    <div id="timetable">
        <p>Appointment</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th></th>
                </tr>
            </thead>

            <?php
            $emp_id=$_SESSION['user_id'];
            $company_id=$_SESSION['company_id'];
            $sql = "
            Select ba.Appt_ID ,c.Name AS cName, ba.Date,ba.Time 
            from book_appt ba
            INNER JOIN client c ON ba.client_ID = c.client_ID
            where ba.Emp_ID =$emp_id and ba.status ='Approved'
            ";

            $res = $conn->query($sql);
            $sn = 1;

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    echo "<tr>
                        <td>{$sn}</td>
                        <td>{$row['cName']}</td>
                        <td>{$row['Date']}</td>
                        <td>{$row['Time']}</td>
                       <td><a href='ViewAppointment.php?id={$row['Appt_ID']}''> view details </a></td>
                    </tr>";
                    $sn++;
                }
            } else {
                echo "<tr><td colspan='5'>No appointments today</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- RIGHT SIDE -->
<div id="right_container">
    <!-- SMALL TIMETABLE -->
    <div class="right-bottom">
        <p>Timetable</p>
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
