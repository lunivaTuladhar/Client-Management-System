<?php
include ("../db.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'] ;
    $contact  = $_POST['contact'] ;
    $email    = $_POST['email'] ;
    $password = $_POST['password'] ;
    $hasOrg   = isset($_POST['checkbox']) ? 1 : 0;
    if ($hasOrg) {
        // Save data into SESSION instead of DB
        $_SESSION['name'] =  $name;
        $_SESSION['contact'] =  $contact;
        $_SESSION['email'] =  $email;
        $_SESSION['password'] =  $password;
        
        // Redirect to company registration page
        header("Location: ../Company/register_company.php");
        exit();
    } else {
        // Insert into client table directly
        $sql = "INSERT INTO client (name, password, email, phone) 
                VALUES ('$name', '$password', '$email', '$contact')";
        if ($conn->query($sql) === TRUE) {
            header("Location: log in.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<style>
    #checkbox{
        text-align: left;
        gap:12px;
        margin-top:8px;
        margin-bottom:8px;
    }
    body{
        margin-top:3%;
        margin-bottom:3%;
    }
    #container{
        display: flex; 
        width: 100%; 
        gap: 10%;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <title>Sign up</title>
</head>
<body>
    <form align="center" method="post" onsubmit="return validateForm()">
        <h2>SIGN UP</h2>
        <div id="container">
            <div align="left" style="width: 45%;">
            
                <label>Name</label><br>
                <input type="text" placeholder="Enter your Name" name="name" id="name"><br>
                <label>Contact No.</label><br>
                <input type="tel" placeholder="Enter your Contact no."name="contact" id="contact"><br>
            </div>

            <div align="left" style="width: 45%;">
                <label>Email</label><br>
                <input type="email" placeholder="Enter your Email"name="email" id="email"><br>
                <label>Password</label><br>
                <input type="password" placeholder="Enter your Password" name="password" id="password"><br>

            
            </div>
        </div>
        <div id= "checkbox">
            <input type="checkbox" style="width: auto;  height:auto; margin-right:8px;" id="checkbox" name="checkbox">Do you have an organization?
        </div>
        <button type="submit">Sign Up</button>
        <p>Already have an account?<a href="log in.php">Log In</a></p>
    </form>
    <script>
function validateForm() {
    let name = document.getElementById("name").value;
    let email =document.getElementById("email").value;
    let phone = document.getElementById("contact").value;
    let password = document.getElementById("password").value;

    let namePattern = /^[A-Za-z ]+$/;
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let phonePattern = /^[0-9]{10}$/;

    if (!namePattern.test(name)) {
        alert("Name must contain only letters and spaces");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert("Phone number must be exactly 10 digits");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long");
        return false;
    }

    return true;
}
</script>
</body>
</html>
