<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cms");
session_start();
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $company_id = $_SESSION['company_id'];

    // 🔹 CHECK UNIQUE EMAIL USING SELECT
    $check = "SELECT Email FROM employee WHERE Email = '$email'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {

        $sql = "INSERT INTO employee 
                (Name, Email, Phone, Role, Address, Company_ID, Password) 
                VALUES 
                ('$name', '$email', '$phone', '$role', '$address', '$company_id', '$password')";

        if ($conn->query($sql)) {
            echo "<script>alert('Employee added successfully!'); 
                  window.location='EmployeeList.php';</script>";
        } else {
            echo "<script>alert('Error adding employee');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        body { 
            font-family: Arial, 
            sans-serif; 
        }
        form { 
            border: 1px solid black; 
            padding: 20px; 
            max-width: 700px; 
            margin: auto; 
            border-radius: 12px; 
        }
        h3 { 
            margin-bottom: 20px; 
        }
        #add_emp { display: flex; gap: 3%; }
        label { 
            display: inline-block; 
            margin-bottom: 4px; 
            font-weight: 500;
        }
        #button { 
            display: flex; 
            width: 200px; 
            margin-left: auto; 
            align-items: center; 
            justify-content: center; 
            gap: 12px; 
        }
        #added { 
            background-color: rgba(14,62,217,0.2); 
            border: 2px rgba(14,62,217,0.9) solid; 
            color: rgba(14,62,217,0.9); 
        }
        #added:hover { 
            background-color: rgba(14,62,217,0.4); 
        }
        #cancel { 
            background-color: rgba(239,24,24,0.2); 
            border: 2px rgba(239,24,24,0.9) solid; 
            color: rgba(239,24,24,0.9);
        }
        #cancel:hover { 
            background-color: rgba(239,24,24,0.4); 
        }
        input{
            padding-left:8px; 
        }
        #role{
            margin-bottom:-12px;
        }
        select{
            padding-left:4px; 
        }
    </style>
</head>
<body>
    <form method="POST" name="empForm" onsubmit="return validateForm()" align="center">
        <h3>Add Employee</h3><br>
        <div id="add_emp">
            <!-- Left Column -->
            <div align="left" style="width:50%">
                <label>Name:</label><br>
                <input type="text" name="name" placeholder="enter employee name" required /><br>

                <label>Phone:</label><br>
                <input type="tel" name="phone" placeholder="enter employee phone" required/><br>

                <label id="role">Role:</label><br>
                <select name="role" required>
                    <option value="admin">admin</option>
                    <option value="employee">employee</option>
                    
                </select>
            </div>

            <!-- Right Column -->
            <div align="left" style="width:50%">
                <label>Email:</label><br>
                <input type="email" name="email" placeholder="enter employee email" required/><br>

                <label>Address:</label><br>
                <input type="text" name="address" placeholder="enter employee address" required/><br>
                
                <label>Password:</label><br>
                <input type="password" name="password" placeholder="enter employee password" required/><br>
               
            </div>
        </div><br>
        <div id="button">
            <button type="submit" id="added">Add</button>
            <button type="button" id="cancel" onclick="window.location='EmployeeList.php'">Cancel</button>
        </div>
    </form>
</body>
<script>
function validateForm() {
    let name = document.forms["empForm"]["name"].value;
    let email = document.forms["empForm"]["email"].value;
    let phone = document.forms["empForm"]["phone"].value;
    let password = document.forms["empForm"]["password"].value;

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

</html>
