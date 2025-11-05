<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "cms");

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
    $priority = $_POST['priority'];

    // You can set company ID manually or get from session if login system exists
    $company_id = 1;

    // Default password for new employee (can be changed later)
    $password = password_hash("P@ss1234", PASSWORD_DEFAULT);

    $sql = "INSERT INTO employee (Name, Email, Phone, Role, Address, Company_ID, Password) 
            VALUES ('$name', '$email', '$phone', '$role', '$address', '$company_id', '$password')";

    if ($conn->query($sql)) {
        echo "<script>alert('Employee added successfully!'); window.location='EmployeeList.php';</script>";
    } else {
        echo "<script>alert('Error adding employee: " . $conn->error . "');</script>";
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
    </style>
</head>
<body>
    <form method="POST" align="center">
        <h3>Add Employee</h3><br>
        <div id="add_emp">
            <!-- Left Column -->
            <div align="left" style="width:50%">
                <label>Name:</label><br>
                <input type="text" name="name" placeholder="enter employee name" required /><br>

                <label>Phone:</label><br>
                <input type="tel" name="phone" placeholder="enter employee phone" required/><br>

                <label>Role:</label><br>
                <select name="role" required>
                    <option value="admin">admin</option>
                    <option value="doctor">doctor</option>
                    <option value="nurse">nurse</option>
                    <option value="receptionist">receptionist</option>
                </select>
            </div>

            <!-- Right Column -->
            <div align="left" style="width:50%">
                <label>Email:</label><br>
                <input type="email" name="email" placeholder="enter employee email" required/><br>

                <label>Address:</label><br>
                <input type="text" name="address" placeholder="enter employee address" required/><br>

                <label>Priority:</label><br>
                <select name="priority" required>
                    <option value="high">high</option>
                    <option value="medium">medium</option>
                    <option value="low">low</option>
                </select>
            </div>
        </div><br>
        <div id="button">
            <button type="submit" id="added">Add</button>
            <button type="button" id="cancel" onclick="window.location='EmployeeView.php'">Cancel</button>
        </div>
    </form>
</body>
</html>
