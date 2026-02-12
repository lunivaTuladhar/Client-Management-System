<?php
session_start();
include ("../db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {

       
            $sql = "SELECT * FROM employee WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $employee = $result->fetch_assoc();
        

        // ---- If not employee, check Client ----
        if (empty($employee)) {
            $sql = "SELECT * FROM client WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $client = $result->fetch_assoc();
        }

        // ---- Login Handling ----
        if (!empty($employee)) {
            $_SESSION['company_id'] =$employee['Company_ID'];
            $_SESSION['user_id']   = $employee['Emp_ID'];
            $_SESSION['name']      = $employee['Name'];
            $_SESSION['email']      = $employee['Email'];

 // this is for appointmnet approve when logging in
$company_id = $_SESSION['company_id'];
$now = date('Y-m-d H:i:s', strtotime('-1 day'));


// 1 Mark past approved appointments as Completed
$update_stmt = $conn->prepare("
    UPDATE book_appt 
    SET Status='Completed' 
    WHERE Company_ID=? AND Status='Approved' AND CONCAT(Date,' ',Time) < ?
");
$update_stmt->bind_param("is", $company_id, $now);
$update_stmt->execute();

// 2 Delete all past appointments that were NOT Approved **or Completed**
$delete_stmt = $conn->prepare("
    DELETE FROM book_appt 
    WHERE Company_ID=? AND Status NOT IN ('Approved','Completed') AND CONCAT(Date,' ',Time) < ?
");
$delete_stmt->bind_param("is", $company_id, $now);
$delete_stmt->execute();

// --- End auto-complete ---

//php for role selection for dashboard
            if($employee['Isadmin']==1){
                $_SESSION['user_type'] = "superadmin";
                header("Location: ../company/dashboard.php");
            }else{
                $_SESSION['user_type'] = "employee";    
                header("Location: ../employee/dashboard.php");
            }
            exit();
        } elseif (!empty($client)) {
    $_SESSION['email']      = $client['Email'];
    $_SESSION['user_type']  = "client";
    $_SESSION['user_id']    = $client['Client_ID'];
    $_SESSION['name']       = $client['Name'];
    header("Location: ../client/dashboard.php");
    exit();
}
 else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <title>Log In</title>
    <style>
        form{
            width:40%;
            margin-left:300px;
        }
    </style>
</head>
<body>
    <form align="center" method="post">
        <h3>LOG IN</h3>

        <?php if ($error): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <div align="left">
            <label>Email</label><br>
            <input type="email" name="email" placeholder="Enter your Email" required><br>

            <label>Password</label><br>
            <input type="password" name="password" placeholder="Enter your Password" required><br>
        </div>

        <button type="submit">Log In</button>
        <p>Don’t have an account? <a href="sign up.php">Sign up</a></p>
    </form>
</body>
</html>
