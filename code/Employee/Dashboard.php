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
          margin-left:55px;
          background-color:#F5F3F3; 
          height:520px;
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
        .right-top{
          margin-top:12px;
          height:300px;
          padding:12px;
          border-radius: 12px;
          background-color:white;
        }
        img{
          width:40px;
          height: 40px;
        }
        #table{
            padding:12px;
            background-color:#F5F3F3;
            border-radius:12px;
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
<?php if(  empty($profile_pic)):?>
        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' class="profile-pic"><g fill='currentColor' fill-rule='evenodd' clip-rule='evenodd'><path d='M16 9a4 4 0 1 1-8 0a4 4 0 0 1 8 0m-2 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0'/><path d='M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1M3 12c0 2.09.713 4.014 1.908 5.542A8.99 8.99 0 0 1 12.065 14a8.98 8.98 0 0 1 7.092 3.458A9 9 0 1 0 3 12m9 9a8.96 8.96 0 0 1-5.672-2.012A6.99 6.99 0 0 1 12.065 16a6.99 6.99 0 0 1 5.689 2.92A8.96 8.96 0 0 1 12 21'/></g></svg>
            <?php else:?>
        <img src="<?php echo $profile_pic; ?>" class="profile-pic">
        <?php endif;?>    </div>

    <!-- MAIN TIMETABLE -->
    <div id="timetable">
        <p>Appointment</p>
        <div id="table">
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
                           <td><a href='ViewAppointment.php?id={$row['Appt_ID']}''> View </a></td>
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
</div>

<!-- RIGHT SIDE -->
<div id="right_container">
    <!-- SMALL TIMETABLE -->
    <div class="right-bottom">
        <p>Timetable</p>
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

</div>
</body>
</html>
