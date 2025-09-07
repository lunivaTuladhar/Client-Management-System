<?php
session_start();
require_once "db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $org_name = $_POST['org_name'] ?? '';
    $contact  = $_POST['contact'] ?? '';
    $email    = $_POST['email'] ?? '';
    $type     = $_POST['type'] ?? '';
    $address  = $_POST['address'] ?? '';
    $city     = $_POST['city'] ?? '';
    $state    = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';

    // Insert into organization
    $sql = "INSERT INTO organization (name, address, phone, email, org_name, type, zip_code) 
            VALUES ('$org_name', '$address, $city, $state', '$contact', '$email', '$org_name', '$type', '$zip_code')";
    
    if ($conn->query($sql) === TRUE) {
        $org_id = $conn->insert_id; // get ID of just inserted organization

        // Insert employee from session
        $emp_name = $_SESSION['name'];
        $emp_pass = $_SESSION['password'];
        $emp_email = $_SESSION['email'];
        $emp_phone = $_SESSION['contact'];
        
        

        $sql2 = "INSERT INTO employee (name, password, email, phone, role, org_code) 
                 VALUES ('$emp_name', '$emp_pass', '$emp_email', '$emp_phone', 1, $org_id)";
        
        if ($conn->query($sql2) === TRUE) {
            // Clear session after registration
            unset($_SESSION['name']);
            unset($_SESSION['password']);
            unset($_SESSION['email']);
            unset($_SESSION['contact']);
            header("Location: log in.php");
            exit();
        } else {
            echo "Error inserting employee: " . $conn->error;
        }
    } else {
        echo "Error inserting organization: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="button.css">
    <title>Register Company</title>
    <style>
        #second{
    display:none;
}
    </style>
</head>
<body>
    <form align="center" style="width: 70%; margin-left: 10%; margin-right: 10%;" method="post">
        <h2>SIGN UP</h3>

            <div style="border: 1 rgb(14, 62, 217) solid; border-radius: 100%; background-color: rgb(14, 62, 217); width: 3.2rem; color: white;">1</div>
            <div id="first">
                <h3 align="left"> COMPANY DETAILS:</h3>
        <div style="display: flex; width: 100%; gap: 10%;">
        <div align="left" style="width: 45%;">
            
        <label>Name</label><br>
        <input type="text" placeholder="Enter your Name" name="name" id="name"><br>
        <label>Contact No.</label><br>
        <input type="tel" placeholder="Enter your Contact no." name="contact" id="contact"><br>
        </div>

        <div align="left" style="width: 45%;">
        <label>Email</label><br>
        <input type="email" placeholder="Enter your Email" name="email" id="email"><br>
        <label>type</label><br>
        <input type="text" placeholder="Enter your organizaiton type" name="type" id="type"><br>
        </div>
        </div>
        <button type="button" onclick="change()">Next</button>
        <p>Already have an account?<a href="log in.html">Log In</a></p>
        </div>


        <div id="second">
                    <h3 align="left"> COMPANY LOCATION:</h3>

        <div style=" width: 100%; gap: 2.4rem;">
        <div align="left" style="width: 45%;">
        <label>Address</label><br>
        <input type="text" placeholder="Enter your Address" name="address" ><br>
        <label>City</label><br>
        <input type="text" placeholder="Enter your city" name="city"><br>
        </div>

        <div align="left" style="width: 45%;">
        <label>State</label><br>
        <input type="text" placeholder="Enter your State" name="state"><br>
        <label>zip code</label><br>
        <input type="text" placeholder="Enter your zip code" name="zip_code"><br>
        <button type="submit"> register</button>
        </div>
        </div>
        </div>

        <script>
            function change(){
                document.getElementById("first").style.display="none";
                document.getElementById("second").style.display="inline";
            }
        </script>
    </form>
</body>
</html>