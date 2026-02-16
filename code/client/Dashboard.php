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
    $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_company.png";
}else{
    $emp_name = "User";
    $profile_pic = "../images/default_company.png";
}

// Fetch 6 recommended companies
$company_query = "SELECT Company_ID, Name, Address, Logo, Description FROM company LIMIT 6";
$company_result = $conn->query($company_query);



// Fetch future appointments for logged-in user
$client_id = $_SESSION['user_id']; // Assuming user_id is client ID

$appt_query = $conn->prepare("
    SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status, e.Name AS Emp_Name
    FROM book_appt ba
    LEFT JOIN employee e ON ba.Emp_ID = e.Emp_ID
    WHERE ba.Client_ID=? AND ba.Status != 'Completed' AND ba.Status !='cancelled'
    ORDER BY ba.Date ASC, ba.Time ASC
");
$appt_query->bind_param("i", $client_id);
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
        #whole_container{ 
          display:flex; 
          gap:12px; 
          background-color:#F5F3F3; 
          margin-top:12px; 
          height:100%;
        }
        .company-card-link {
            text-decoration: none;
            color: black;
        }
        #fullcard{
          background: white;
          border-radius: 12px;
          border: 1px solid #ccc;
          padding: 12px;
          box-sizing: border-box;
          transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        #fullcard:hover{
          transform: translateY(-2px);
        }
        .company-card {
          display: flex;
          align-items: center;
          gap: 12px;

        }
        .company-logo {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          object-fit: cover;
          flex-shrink: 0;
          border: 1px solid #aaa;
        }
        .company-info {
          display: flex;
          flex-direction: column;
          justify-content: center;
        }
        .company-name {
          font-weight: bold;
          font-size: 1rem;
          margin: 0;
        }
        .company-address {
          margin: 4px 0 0 0;
          color: #555;
          font-size: 0.9rem;
        }
        .company-desc {
          margin-top: 4px;
          color: #444;
          font-size: 0.9rem;
          line-height: 1.3;
        }
        #right_container{ 
          background-color:#F5F3F3; 
          width:500px; 
          margin-right:8px; 
          margin-bottom:8px; 
          margin-top:38px;
        }
        #left_container{ 
          height:90vh; 
          padding:26px 0 0 0; 
          background-color:#F5F3F3; 
        }
        .profile-pic{ 
          border-radius:50%; 
          margin-top:1%; 
          background-size: cover; 
          background-repeat: no-repeat; 
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
        #recommend{
          background-color: white; 
          margin-left:70px; 
          padding:8px 12px; 
          width:700px; 
          margin-top:12px; 
          border-radius:12px; 
        }
        #recomended-content{
          background-color: #F5F3F3; 
          border-radius:12px;
          display: grid;
          padding:12px;
          grid-template-columns: repeat(2, 1fr);
          gap:12px;
          margin-top:4px; 
        }
        table{ 
          padding:10px; 
          border-radius:12px; 
          width:100%; 
          border-collapse:collapse; 
        }
        #tbl_container{
          background-color:#F5F3F3; 
          padding:12px;
          border-radius:12px;
        }
        th, td{ 
          padding:12px; 
          text-align:left; 
        }
        th{ 
          background-color: rgba(14,62,217,0.2); 
          color: rgba(14,62,217,0.9);
        }
        th:first-child{
          border-top-left-radius: 12px;
          border-bottom-left-radius: 12px;
        }
        th:last-child{
          border-top-right-radius: 12px;
          border-bottom-right-radius: 12px;
        }
        tr:hover{ 
          background-color:#eaeaea; 
        }
        .right-bottom{ 
          margin-top:12px; 
          height:auto; 
          padding:12px; 
          border-radius:12px; 
          background:white; 
          height:464px;
        }
        a{
          text-decoration:none;
        }
        .pending { color: #ffc107; }
        .approved { color: #28a745; }
        .rejected { color: #dc3545; }
        img{
          width:40px;
          height: 40px;
        }
    </style>
</head>

<body>

<?php include ("../fixed/sidebar.php"); ?>

<div id="whole_container" style="height:100%;">

<div id="left_container">
    <div id="welcome">
        <h2>WELCOME, <?php echo strtoupper($emp_name); ?></h2>
        <img src="<?php echo $profile_pic; ?>" class="profile-pic">
    </div>

    <div id="recommend">
      <div style="display:flex;justify-content:space-between; ">

        <h2>Recommended Organizations</h2>
        <a href="list_company.php"> <p> veiw more</p></a>
      </div>
      <div id="recomended-content" >
        <?php
          if($company_result && $company_result->num_rows > 0){
          while($comp = $company_result->fetch_assoc()){
          $logo = $comp['Logo'] ? $comp['Logo'] : "../images/default_company.png";
          $company_id = $comp['Company_ID'];
          echo "
          <a href='ViewCompanyDetails.php?id={$company_id}' class='company-card-link'>
          <div id='fullcard'> <div class='company-card'>
            
            <img src='{$logo}' alt='Company Logo' class='company-logo'/>
            <div class='company-info'>
                <p class='company-name'>{$comp['Name']}</p>
                <p class='company-address'>{$comp['Address']}</p>
               
          </div>
          </div> 
          </div>
          </a>";
          }
          } else {
              echo "<p>No companies found</p>";
          }
        ?>
    </div>
  </div>
</div>

<div id="right_container">

<div class="right-bottom">
  <p>My Appointments</p>
  <div id="tbl_container">
    <table style="width:100%">
    <thead>
        <tr>
            <th>With</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
if ($appt_result && $appt_result->num_rows > 0) {
    while ($appt = $appt_result->fetch_assoc()) {

        // determine status class
        $status = strtolower($appt['Status']);
        if ($status === 'pending') {
            $class = 'pending';
        } elseif ($status === 'approved') {
            $class = 'approved';
        } else {
            $class = 'rejected';
        }

        echo "<tr>
                <td>{$appt['Emp_Name']}</td>
                <td>{$appt['Date']}</td>
                <td>{$appt['Time']}</td>
                <td>
                    <span class='status {$class}'>
                        {$appt['Status']}
                    </span>
                </td>
                
              </tr>";
    }
} else {
    echo "<tr>
            <td colspan='5' style='text-align:center;'>
                No upcoming appointments
            </td>
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

<?php
$company_result->free();
$appt_query->close();
$conn->close();
?>
