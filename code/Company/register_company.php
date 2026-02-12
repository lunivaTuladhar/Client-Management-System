<?php
session_start();
require_once "../db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $org_name = $_POST['name'] ?? '';
    $contact  = $_POST['contact'] ?? '';
    $email    = $_POST['email'] ?? '';
    $type     = $_POST['type'] ?? '';
    $address  = $_POST['address'] ?? '';
    $city     = $_POST['city'] ?? '';
    $state    = $_POST['state'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';

    // Insert into organization
    $sql = "INSERT INTO company (name, address, phone, email, type, zip_code) 
            VALUES ('$org_name', '$address, $city, $state', '$contact', '$email', '$type', '$zip_code')";
    
    if ($conn->query($sql) === TRUE) {
        $org_id = $conn->insert_id; // get ID of just inserted organization

        // Insert employee from session
        $emp_name = $_SESSION['name'];
        $emp_pass = $_SESSION['password'];
        $emp_email = $_SESSION['email'];
        $emp_phone = $_SESSION['contact'];
        
        

        $sql2 = "INSERT INTO employee (name, password, email, phone, role,Company_ID, Isadmin) 
                 VALUES ('$emp_name', '$emp_pass', '$emp_email', '$emp_phone', 'Admin', $org_id, 1)";
        
        if ($conn->query($sql2) === TRUE) {
            // Clear session after registration
            unset($_SESSION['name']);
            unset($_SESSION['password']);
            unset($_SESSION['email']);
            unset($_SESSION['contact']);
            header("Location: ../login/log in.php");
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
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <title>Register Company</title>
    <style>

        form {
            background-color: white;
            padding: 20px;
            box-sizing: border-box;
        }

        #container {
            display: flex;
            width: 100%;
            justify-content: space-between;
            gap: 5%;
            box-sizing: border-box;
        }

        #first, #second {
            width:48%;
        }
        h2 {
            text-align: center;
        }

        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <form method="post">
        <h2>SIGN UP</h2>
        <div id="container">
            <div id="first">
                <h3>COMPANY DETAILS:</h3>
                <label>Name</label><br>
                <input type="text" placeholder="Enter your Name" name="name" id="name" required><br>

                <label>Email</label><br>
                <input type="email" placeholder="Enter your Email" name="email" id="email" required><br>

                <label>Contact No.</label><br>
                <input type="tel" placeholder="Enter your Contact no." name="contact" id="contact" required><br>

                <label>Type</label><br>
                <input type="text" placeholder="Enter your organization type" name="type" id="type" required><br>
            </div>

            <div id="second">
                <h3>COMPANY LOCATION:</h3>
                <label>Address</label><br>
                <input type="text" placeholder="Enter your Address" name="address" required><br>

                <label>City</label><br>
                <input type="text" placeholder="Enter your city" name="city"><br>

                <label>State</label><br>
                <input type="text" placeholder="Enter your State" name="state"><br>

                <label>Zip code</label><br>
                <input type="text" placeholder="Enter your zip code" name="zip_code"><br>
            </div>
        </div>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="../login/log in.php">Log In</a></p>
        <div >
        </div>
    </form>
</body>
</html>
