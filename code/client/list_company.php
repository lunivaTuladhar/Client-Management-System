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

  // Fetch 6 recommended companies
  $search = isset($_POST['search']) ? trim($_POST['search']) : '';

if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT Company_ID, Name, Address, Logo, Description
        FROM company
        WHERE Name LIKE ? OR Address LIKE ?
        LIMIT 15
    ");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $company_result = $stmt->get_result();
} else {
    $company_result = $conn->query("
        SELECT Company_ID, Name, Address, Logo, Description
        FROM company
        LIMIT 15
    ");
}


  // Fetch future appointments for logged-in user
  $client_id = $_SESSION['user_id']; // Assuming user_id is client ID

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>company list</title>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/button.css">
    <style>
        #search{
            width:99%;
           display:flex;
           justify-content:space-between;
           gap:20%;
           

        }
        #search form{
            width:30%;
            margin-left: 0%; 
            margin-right: 0%;
            padding:0;
            
        }
        #recomended-content{
          background-color: #F5F3F3; 
          border-radius:12px;
          display: grid;
          padding:12px;
          grid-template-columns: repeat(3, 1fr);
          gap:12px;
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
        .company-card-link {
            text-decoration: none;
            color: black;
        }
        #container{
          background-color:white;
          margin: 74px ;
          width: 91%;
          padding:12px;
          border-radius:12px;
        }

    </style>
</head>

<?php include ("../fixed/sidebar.php"); ?>
<body style="background-color:#F3F3F3;">
  <div id="container">
    <div id="search">
      <h3>Browse</h3>
<form method="post">
    <input type="text" name="search" placeholder="search your company"
           value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">

        <button type="submit" style="display:none">submit</button>
    </form>
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
</body>
</html>