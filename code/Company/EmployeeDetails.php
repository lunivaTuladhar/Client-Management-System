<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cms");
session_start();
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employee ID from URL
if (isset($_GET['id'])) {
    $emp_id = intval($_GET['id']); // sanitize input

    // Fetch employee details
    $sql = "SELECT * FROM employee WHERE Emp_ID = $emp_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "<script>alert('Employee not found!'); window.location='EmployeeView.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No employee selected!'); window.location='EmployeeView.php';</script>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <title>Employee Details</title>
    <style>
        #whole_container{
            background-color: #F5F3F3;
            height: 100%;
            margin-top: 50px;
            margin-left: 50px;
            padding:24px;
        }
        #container{
            border-radius:24px;
            padding:24px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin:auto;
        }
        .appt_details{
            background-color: #F5F3F3;
            display: flex;
            align-items: left;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        label{
            background-color: #F5F3F3;
            color: rgb(14, 62, 217, 0.9);
            font-weight: bold;
        }
        p{
            background-color: #F5F3F3;
            margin: 0;
            color: #2d2d2d;
            font-weight: 500;
        }
        #container img{
            height:150px;
            width:150px;
            border-radius:50%;
            margin: 0 auto 12px auto;
            display:block;
            border:3px solid rgba(14, 62, 217, 0.4);
        }
        #details{
            padding:12px;
            background-color: #F5F3F3;
            border-radius:12px;
        }
        #back_btn {
            display: inline-block;
            background-color: rgba(14,62,217,0.2);
            border: 2px solid rgba(14,62,217,0.9);
            color: rgba(14,62,217,0.9);
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s;
            margin-top: 16px;
        }
        #back_btn:hover {
            background-color: rgba(14,62,217,0.4);
        }
        #profilepic{
            display:flex;
            vertical-align:top;
        }
    </style>
</head>
<body>
    <?php include("../fixed/sidebar.php")?>

    <div id="whole_container">
        <div id="container">
            <div id="profilepic">
                <a href="EmployeeList.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
                <?php if (!empty($employee['Profile'])): ?>
                    <img src="<?php echo htmlspecialchars($employee['Profile']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="../images/default-profile.png" alt="Default Profile">
                <?php endif; ?>
            </div>
            
            <div id="details">
                <div class="appt_details"><label>ID:</label><p><?php echo $employee['Emp_ID']; ?></p></div>
                <div class="appt_details"><label>Company ID:</label><p><?php echo htmlspecialchars($employee['Company_ID']); ?></p></div>
                <div class="appt_details"><label>Name:</label><p><?php echo htmlspecialchars($employee['Name']); ?></p></div>
                <div class="appt_details"><label>Email:</label><p><?php echo htmlspecialchars($employee['Email']); ?></p></div>
                <div class="appt_details"><label>Phone:</label><p><?php echo htmlspecialchars($employee['Phone']); ?></p></div>
                <div class="appt_details"><label>Department:</label><p><?php echo htmlspecialchars($employee['Department'] ?: '—'); ?></p></div>
                <div class="appt_details"><label>DOB:</label><p><?php echo htmlspecialchars($employee['DOB'] ?: '—'); ?></p></div>
                <div class="appt_details"><label>Role:</label><p><?php echo htmlspecialchars($employee['Role']); ?></p></div>
                <div class="appt_details"><label>Address:</label><p><?php echo htmlspecialchars($employee['Address'] ?: '—'); ?></p></div>
            </div>
        </div>
    </div>
</body>
</html>
