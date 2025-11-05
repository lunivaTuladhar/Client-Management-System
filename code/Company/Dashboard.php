<?php
session_start();
if(!isset($_SESSION['user_type'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php"); // Database connection
$company_id = $_SESSION['company_id']; // Current company ID

// Logged-in user info
$emp_name = $_SESSION['name'] ;
$profile_pic = "";
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $res = $conn->query("SELECT Name, Profile FROM employee WHERE Email='$email' LIMIT 1");
    if($res && $res->num_rows>0){
        $user = $res->fetch_assoc();
        $emp_name = ucfirst($user['Name']);
        $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_profile.png";
    }
}

if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $res = $conn->query("SELECT Name, Profile FROM employee WHERE Email='$email' LIMIT 1");
    if($res && $res->num_rows>0){
        $user = $res->fetch_assoc();
        $emp_name = ucfirst($user['Name']);
        $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_profile.png";
    }
}

// Fetch employees for left timetable
$emp_query = "SELECT Emp_ID, Name, Phone, Role FROM employee WHERE Company_ID = ? ORDER BY Emp_ID ASC";
$stmt_emp = $conn->prepare($emp_query);
$stmt_emp->bind_param("i", $company_id);
$stmt_emp->execute();
$emp_result = $stmt_emp->get_result();

// Fetch appointment requests/history for right table
$appt_query = "SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status, c.Name AS Client_Name, e.Name AS Employee_Name
               FROM book_appt ba
               LEFT JOIN client c ON ba.Client_ID = c.Client_ID
               LEFT JOIN employee e ON ba.Emp_ID = e.Emp_ID
               WHERE ba.Company_ID = ?
               ORDER BY ba.Date DESC, ba.Time ASC";
$stmt_appt = $conn->prepare($appt_query);
$stmt_appt->bind_param("i", $company_id);
$stmt_appt->execute();
$appt_result = $stmt_appt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <title>Dashboard</title>
    <style>
        #whole_container{ 
          display:flex; 
          gap:12px; 
          background-color:#F5F3F3; 
          margin-top:45px; 
        }
        #right_container{ 
          background-color:#F5F3F3; 
          width:500px; 
          margin-right:12px; 
          margin-top: 12px;
        }
        #left_container{ 
          height:90vh;  
          background-color:#F5F3F3; 
        }
        .profile{ 
          border-radius:50%; 
          margin-top:1%; 
          background-size:cover; 
          background-repeat:no-repeat;
        }
        #welcome{ 
          background-color:white; 
          width:700px; 
          margin-left:70px; 
          margin-top:24px; 
          display:flex; 
          justify-content:space-between; 
          align-items:center; 
          padding:8px 12px; 
          border-radius:12px; 
          height:50px;
        }
        #welcome h2{ 
          font-weight:bold;
        }
        #timetable{ 
          margin-left:70px; 
          padding:8px 12px; 
          width:700px; 
          margin-top:12px; 
          border-radius:12px;
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
          border-bottom:1px solid #ccc; 
        }
        th{ 
          background-color: rgba(14,62,217,0.2); 
          text-align:left; 
          color: rgba(14,62,217,0.9);
        }
        tr:hover{ background-color:#eaeaea; }
        thead th:first-child{ 
          border-top-left-radius:12px; 
          border-bottom-left-radius:12px; 
        }
        thead th:last-child{ 
          border-top-right-radius:12px; 
          border-bottom-right-radius:12px;
        }
        
        .right-bottom{ 
          margin-top:12px; 
          height:auto; 
          padding:12px; 
          border-radius:12px; 
          background:white; 
        }
        .right-bottom h3{ 
          margin:0;
          margin-bottom:12px; 
        }
        .mycal-container{ 
          width:400px; 
          border:1px solid #ccc; 
          border-radius:8px; 
          padding:10px; 
        }
        .mycal-header{ 
          display:flex; 
          justify-content:space-between; 
          align-items:center; 
          margin-bottom:5px; 
          font-size:1rem; 
        }
        .mycal-btn{ 
          cursor:pointer; 
          background:none; 
          color:black; 
          border:none; 
          font-size:16px; 
        }
        .mycal-days, .mycal-weekdays{ 
          display:grid; 
          grid-template-columns:repeat(7,1fr); 
          text-align:center;
        }
        .mycal-weekdays div{ 
          font-weight:bold; 
          color:#555; 
        }
        .mycal-day{ 
          padding:6px; 
          cursor:pointer; 
          border-radius:4px; 
        }
        .mycal-day:hover{ 
          background:#e0e7ff; 
        }
        .mycal-today{ 
          background:#dbeafe; 
          color:#2563eb; 
          font-weight:bold; 
        }
        #cal-indicator{ 
          display:flex; 
          gap:12px; 
        }
    </style>
</head>

<body>
<?php include ("../fixed/sidebar.php"); ?>

<div id="whole_container">

  <!-- Left container: Employees -->
  <div id="left_container">
    <div id="welcome">
        <h2>WELCOME, <?php echo strtoupper($emp_name); ?></h2>
        <img src="<?php echo $profile_pic; ?>" alt="pic" height="40px" width="40px" class="profile">
    </div>

    <div id="timetable">
        <h3>Timetable</h3>
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Phone</th><th>Role</th><th>Available</th></tr>
            </thead>
            <tbody>
            <?php
            if($emp_result && $emp_result->num_rows>0){
                while($row = $emp_result->fetch_assoc()){
                    // Fetch available slots from timetable and time_stamp
                    $stmt_time = $conn->prepare("
                        SELECT ts.Start_Time, ts.End_Time
                        FROM timetable t
                        JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
                        WHERE t.Emp_ID = ? AND t.Company_ID = ?
                        ORDER BY ts.Start_Time ASC
                    ");
                    $stmt_time->bind_param("ii", $row['Emp_ID'], $company_id);
                    $stmt_time->execute();
                    $res_time = $stmt_time->get_result();
                    $available_slots = [];
                    while($slot = $res_time->fetch_assoc()){
                        $available_slots[] = $slot['Start_Time'] . '-' . $slot['End_Time'];
                    }
                    $available_str = !empty($available_slots) ? implode(", ", $available_slots) : "Not Available";

                    echo "<tr>
                            <td>{$row['Emp_ID']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Phone']}</td>
                            <td>{$row['Role']}</td>
                            <td>{$available_str}</td>
                          </tr>";
                    $stmt_time->close();
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No employees found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
  </div>

  <!-- Right container: Calendar + Appointments -->
  <div id="right_container">

   

    <script>
      function setActive(element){
        const buttons = document.querySelectorAll('.toggle-option');
        buttons.forEach(btn=>btn.classList.remove('active'));
        element.classList.add('active');
      }
    </script>

    <!-- Calendar -->
    <div class="right-bottom">
      <h3>Calendar</h3>
      <div class="mycal-container">
        <div class="mycal-header">
          <button class="mycal-btn" id="mycal-prev">◀</button>
          <div id="cal-indicator"><span id="mycal-month"></span> <span id="mycal-year"></span></div>
          <button class="mycal-btn" id="mycal-next">▶</button>
        </div>
        <div class="mycal-weekdays">
          <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
        </div>
        <div class="mycal-days" id="mycal-days"></div>
      </div>
      <script>
        (function(){
          const monthNames=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
          let viewDate=new Date();
          const monthEl=document.getElementById('mycal-month');
          const yearEl=document.getElementById('mycal-year');
          const daysEl=document.getElementById('mycal-days');
          const prev=document.getElementById('mycal-prev');
          const next=document.getElementById('mycal-next');

          function render(){
            const year=viewDate.getFullYear();
            const month=viewDate.getMonth();
            monthEl.textContent=monthNames[month];
            yearEl.textContent=year;
            daysEl.innerHTML='';
            const first=new Date(year,month,1);
            const start=first.getDay();
            const days=new Date(year,month+1,0).getDate();
            for(let i=0;i<start;i++){ daysEl.appendChild(document.createElement('div')); }
            const today=new Date();
            for(let d=1;d<=days;d++){
              const cell=document.createElement('div');
              cell.textContent=d;
              cell.className='mycal-day';
              const thisDate=new Date(year,month,d);
              if(thisDate.toDateString()===today.toDateString()){ cell.classList.add('mycal-today'); }
              cell.onclick=()=>location.href='otherpage.php?date='+thisDate.toISOString().split('T')[0];
              daysEl.appendChild(cell);
            }
          }

          prev.onclick=()=>{viewDate.setMonth(viewDate.getMonth()-1);render();};
          next.onclick=()=>{viewDate.setMonth(viewDate.getMonth()+1);render();};
          render();
        })();
      </script>
    </div>

    <!-- Appointments -->
    <div class="right-bottom">
      <h3>Appointment Request / History</h3>
      <table style="width:100%">
        <thead>
          <tr>
            
            <th>Client</th>
            <th>Employee</th>
            <th>Time</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if($appt_result && $appt_result->num_rows>0){
            while($row=$appt_result->fetch_assoc()){
                echo "<tr>
                       
                        <td>{$row['Client_Name']}</td>
                        <td>{$row['Employee_Name']}</td>
                       
                        <td>{$row['Time']}</td>
                        <td><a href='AppointmentDetails.php?Appt_ID={$row['Appt_ID']}'>View</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No appointments found</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

</body>
</html>

<?php
$stmt_emp->close();
$stmt_appt->close();
$conn->close();
?>
