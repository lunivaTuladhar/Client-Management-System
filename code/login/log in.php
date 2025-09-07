<?php
session_start();
require_once "../db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {

        // ---- Check SuperAdmin table FIRST ----
        $sql = "SELECT * FROM superadmin WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $superadmin = $result->fetch_assoc();

        // ---- If not superadmin, check Employee ----
        if (!$superadmin) {
            $sql = "SELECT * FROM employee WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $employee = $result->fetch_assoc();
        }

        // ---- If not employee, check Client ----
        if (!$superadmin && empty($employee)) {
            $sql = "SELECT * FROM client WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $client = $result->fetch_assoc();
        }

        // ---- Login Handling ----
        if (!empty($superadmin)) {
            $_SESSION['user_type'] = "superadmin";
            $_SESSION['user_id']   = $superadmin['admin_id']; // make sure col name is admin_id
            $_SESSION['name']      = $superadmin['name'];
            header("Location: superadmin_dashboard.php");
            exit();
        } elseif (!empty($employee)) {
            $_SESSION['user_type'] = "employee";
            $_SESSION['user_id']   = $employee['emp_id'];
            $_SESSION['name']      = $employee['name'];
            if($employee['role']==1){
                header("Location: admin_dashboard.php");
            }
            if($employee['role']==2){
                header("Location: employee_dashboard.php");
            }
            if($employee['role']==3){
                header("Location: receptionist_dashboard.php");
            }
            exit();
        } elseif (!empty($client)) {
            $_SESSION['user_type'] = "client";
            $_SESSION['user_id']   = $client['client_id'];
            $_SESSION['name']      = $client['name'];
            header("Location: client_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <title>Log In</title>
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
        <p>Don’t have an account? <a href="sign_up.php">Sign up</a></p>
    </form>
</body>
</html>
