<?php
session_start();
include('../db.php'); // your database connection

// Handle AJAX request to get employee name
if(isset($_GET['get_emp_name'])){
    $emp_id = intval($_GET['get_emp_name']);
    $res = $conn->query("SELECT Name FROM employee WHERE Emp_ID = $emp_id");
    if($res->num_rows > 0){
        echo $res->fetch_assoc()['Name'];
    } else {
        echo "";
    }
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $emp_id = $_POST['emp_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $days = $_POST['days'] ?? [];

    // Get the company of the employee
    $res = $conn->query("SELECT Company_ID FROM employee WHERE Emp_ID = $emp_id");
    if($res->num_rows == 0){
        die("<script>alert('Employee not found'); window.history.back();</script>");
    }
    $company_id = $res->fetch_assoc()['Company_ID'];

    // Check for duplicate timetable for this employee and time
    $duplicate_check = $conn->prepare("
        SELECT t.Time_ID FROM timetable t
        INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
        WHERE t.Emp_ID = ? AND ts.Start_Time = ? AND ts.End_Time = ?
    ");
    $duplicate_check->bind_param("iss", $emp_id, $start_time, $end_time);
    $duplicate_check->execute();
    $duplicate_check->store_result();
    if($duplicate_check->num_rows > 0){
        echo "<script>alert('This timetable already exists for the employee'); window.history.back();</script>";
        exit;
    }

    // Insert into time_stamp table
    $stmt = $conn->prepare("INSERT INTO time_stamp (Start_Time, End_Time) VALUES (?, ?)");
    $stmt->bind_param("ss", $start_time, $end_time);
    $stmt->execute();
    $time_id = $conn->insert_id;

    // Convert selected days to boolean flags
    $Sun = in_array('Sun', $days) ? 1 : 0;
    $Mon = in_array('Mon', $days) ? 1 : 0;
    $Tue = in_array('Tue', $days) ? 1 : 0;
    $Wed = in_array('Wed', $days) ? 1 : 0;
    $Thu = in_array('Thu', $days) ? 1 : 0;
    $Fri = in_array('Fri', $days) ? 1 : 0;
    $Sat = in_array('Sat', $days) ? 1 : 0;

    // Insert into timetable table with day booleans
    $stmt2 = $conn->prepare("
        INSERT INTO timetable
        (Company_ID, Emp_ID, Time_ID, Sun, Mon, Tue, Wed, Thu, Fri, Sat)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param(
        "iiiiiiiiii",
        $company_id,
        $emp_id,
        $time_id,
        $Sun,
        $Mon,
        $Tue,
        $Wed,
        $Thu,
        $Fri,
        $Sat
    );
    $stmt2->execute();

    echo "<script>alert('Timetable added successfully'); window.location='Employeelist.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Timetable</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            border: 1px solid black;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            border-radius:12px;
        }

        h3 {
            margin-bottom: 20px;
            font-weight:bold:
        }

        #add_emp {
           
            gap: 3%;
        }

        /* Labels all same height */
        label {
            display: inline-block;
            float:left;
            margin-left:12px;
            height: 24px;
            line-height: 24px;
            margin-bottom: 4px;
            font-weight: 500;
        }      
        
        /* Role row with plus button */
        .role-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-right:15px
        }

        .role-row button {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #2563eb; /* blue */
            border-radius: 20%;
            cursor: pointer;
        }
        .role-row button svg {
            width: 16px;
            height: 16px;
            fill: white;
        }
        #button{
            display: flex;
            width: 200px;
            margin-left: auto;   /* pushes it to the right */
            align-items: center; /* vertical centering */
            justify-content: center; /* horizontal centering inside button */
            gap: 32px;
        }
        #added{
            background-color: rgba(14,62,217,0.2);
            border: 2px rgba(14,62,217,0.9) solid;
            color:rgba(14,62,217,0.9);
        }
        #added:hover{
            background-color: rgba(14,62,217,0.4);
        }
        #cancel{
            background-color: rgba(239,24,24,0.2);
            border: 2px rgba(239,24,24,0.9) solid;
            color:rgba(239,24,24,0.9);
        }
        #cancel:hover{
            background-color: rgba(239,24,24,0.4);
        }
        #days{
            display:flex;
            justify-content:space-between;
            gap:12px;
            margin-bottom:60px;
            margin-left:12px;
        }
        #days button{
            background-color:white;
            border:1px solid black;
            color: black;
            width:4rem;
        }
        #days button:hover{
            background-color:blue;
            color:white;
        }
        #days input{
            height:20px;
            margin-left:12px;
            width:25px;
        }
    </style>
    <script>
        // Fetch employee name dynamically when ID changes
        function fetchEmpName(){
            let empId = document.getElementById('emp_id').value;
            let nameField = document.getElementById('emp_name');
            if(empId){
                fetch('?get_emp_name=' + empId)
                    .then(response => response.text())
                    .then(data => nameField.value = data);
            } else {
                nameField.value = '';
            }
        }
    </script>
</head>
<body>
    <form method="POST" align="center">
        <h3>Add Timetable</h3><br>
        <div id="add_emp">
            <div style="width:100%;display:flex;justify-content:space-between;">
                <div style="width:50%">
                    <label>Employee id: </label>
                    <input type="number" name="emp_id" id="emp_id" oninput="fetchEmpName()" required>
                    <label>Start-time:</label><br>
                    <input type="time" name="start_time" required/><br>
                </div>
                <div style="width:50%">
                    <label style="opacity:50%;">Name: </label>
                    <input type="text" name="emp_name" id="emp_name" readonly style="opacity:50%;">
                    <label>End-time:</label><br>
                    <input type="time" name="end_time" required/><br>
                </div>
            </div>
        </div>

        <p style="text-align:left;margin-left:12px;">Select Days</p>
        <div id="days">
            <label>Sun<input type="checkbox" name="days[]" value="Sun">
            
        </label>
            <label>Mon<input type="checkbox" name="days[]" value="Mon">
            
        </label>
            <label>Tue<input type="checkbox" name="days[]" value="Tue">
            
        </label>
            <label>Wed<input type="checkbox" name="days[]" value="Wed">
            
        </label>
            <label>Thu<input type="checkbox" name="days[]" value="Thu">
            
        </label>
            <label>Fri<input type="checkbox" name="days[]" value="Fri">
            
        </label>
            <label>Sat<input type="checkbox" name="days[]" value="Sat">
            
        </label>
        </div>

        <div id="button">
            <button type="submit" id="added">Add</button>
            <button type="button" id="cancel" onclick="window.location='Employeelist.php'">Cancel</button>
        </div>
    </form>
</body>
</html>
