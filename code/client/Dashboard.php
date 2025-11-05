<?php
session_start();
if(!isset($_SESSION['email'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php");

$email = $_SESSION['email'];

// Fetch logged-in user info
$user_query = $conn->prepare("SELECT Name, Profile FROM employee WHERE Email=? LIMIT 1");
$user_query->bind_param("s", $email);
$user_query->execute();
$user_result = $user_query->get_result();

if($user_result->num_rows>0){
    $user = $user_result->fetch_assoc();
    $emp_name = ucfirst($user['Name']);
    $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_profile.png";
}else{
    $emp_name = "User";
    $profile_pic = "../images/default_profile.png";
}

// Fetch 4 recommended companies
$company_query = "SELECT Company_ID, Name FROM company LIMIT 4";
$company_result = $conn->query($company_query);

// Fetch future appointments for logged-in user
$appt_query = $conn->prepare("SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status, c.Name AS Client_Name
                              FROM book_appt ba
                              LEFT JOIN client c ON ba.Client_ID=c.Client_ID
                              LEFT JOIN employee e ON ba.Emp_ID=e.Emp_ID
                              WHERE e.Email=? AND ba.Status!='Completed'
                              ORDER BY ba.Date ASC, ba.Time ASC");
$appt_query->bind_param("s", $email);
$appt_query->execute();
$appt_result = $appt_query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="../style/button.css">
        <link rel="stylesheet" href="../style/Heading.css">
    <title>Dashboard</title>
    <style>
        #whole_container{ display:flex; gap:12px; background-color:#F5F3F3; margin-top:12px; }
        #recomended{ display:flex; gap:12px; margin-top:12px; }
        .company-card{ width:calc(50% - 6px); background:white; border-radius:12px; padding:12px; display:flex; flex-direction:column; align-items:center; }
        .company-card img{ width:80px; height:80px; object-fit:cover; border-radius:50%; margin-bottom:8px; }
        #right_container{ background-color:#F5F3F3; width:500px; margin-right:12px; }
        #left_container{ height:90vh; padding:24px 0 0 0; background-color:#F5F3F3; }
        .profile{ border-radius:50%; margin-top:1%; background-size: cover; background-repeat: no-repeat; }
        #welcome{ background-color:white; width:700px; margin-left:70px; margin-top:24px; display:flex; justify-content:space-between; align-items:center; padding:8px 12px; border-radius:12px; height:50px; }
        #welcome h2{ font-weight:bold; }
        #timetable{ margin-left:70px; padding:8px 12px; width:700px; margin-top:12px; border-radius:12px; }
        table{ background-color:#F5F3F3; padding:10px; border-radius:12px; width:100%; border-collapse:collapse; }
        th, td{ padding:12px; border-bottom:1px solid #ccc; text-align:left; }
        th{ background-color: rgba(14,62,217,0.2); color: rgba(14,62,217,0.9); }
        tr:hover{ background-color:#eaeaea; }
        .right-top{ margin-top:12px; height:295px; padding:12px; border-radius:12px; }
        .right-bottom{ margin-top:12px; height:auto; padding:12px; border-radius:12px; background:white; }
        .mycal-container{ width:415px; border:1px solid #ccc; border-radius:8px; padding:10px; }
        .mycal-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:5px; font-size:1rem; }
        .mycal-btn{ cursor:pointer; background:none; color:black; border:none; font-size:16px; }
        .mycal-days, .mycal-weekdays{ display:grid; grid-template-columns:repeat(7,1fr); text-align:center; }
        .mycal-weekdays div{ font-weight:bold; color:#555; }
        .mycal-day{ padding:6px; cursor:pointer; border-radius:4px; }
        .mycal-day:hover{ background:#e0e7ff; }
        .mycal-today{ background:#dbeafe; color:#2563eb; font-weight:bold; }
        #cal-indicator{ display:flex; gap:12px; }
    </style>
</head>

<body>

<?php include ("../fixed/sidebar.php"); ?>

<div id="whole_container">

<div id="left_container">
    <div id="welcome">
        <h2>WELCOME, <?php echo strtoupper($emp_name); ?></h2>
        <img src="<?php echo $profile_pic; ?>" alt="Pic" height="40px" width="40px" class="profile">
    </div>

    <div id="timetable">
      <h2>Recommended Organizations</h2>
      <div id="recomended">
        <?php
        if($company_result && $company_result->num_rows>0){
            while($comp=$company_result->fetch_assoc()){
                echo "<div class='company-card'>
                       
                        <p>{$comp['Name']}</p>
                      </div>";
            }
        }else{
            echo "<p>No companies found</p>";
        }
        ?>
      </div>
    </div>

</div>

<div id="right_container">

<div class="right-top">
  <p>Calendar</p>
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

<div class="right-bottom">
  <p>My Appointments</p>
    <table style="width:100%">
        <thead>
            <th>ID</th>
            <th>Client</th>
            <th>Time</th>
            <th>Details</th>
        </thead>
        <tbody>
        <?php
        if($appt_result && $appt_result->num_rows>0){
            while($appt=$appt_result->fetch_assoc()){
                echo "<tr>
                        <td>{$appt['Appt_ID']}</td>
                        <td>{$appt['Client_Name']}</td>
                        <td>{$appt['Time']}</td>
                        <td><a href='AppointmentDetails.php?Appt_ID={$appt['Appt_ID']}'>View</a></td>
                      </tr>";
            }
        }else{
            echo "<tr><td colspan='4' style='text-align:center;'>No upcoming appointments</td></tr>";
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
$company_result->free();
$appt_query->close();
$conn->close();
?>
