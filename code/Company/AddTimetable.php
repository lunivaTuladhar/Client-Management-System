<?php
session_start();
include('../db.php');

if(!isset($_SESSION['company_id'])){
    die("Unauthorized access");
}

$company_id = $_SESSION['company_id'];


// ================= AJAX: Fetch Employee ID by Name =================
if(isset($_GET['get_emp_name'])){

    $emp_name = $_GET['get_emp_name'];

    $stmt = $conn->prepare("SELECT Emp_ID FROM employee WHERE Name = ? AND Company_ID = ?");
    $stmt->bind_param("si", $emp_name, $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        echo $result->fetch_assoc()['Emp_ID'];
    } else {
        echo "";
    }
    exit;
}


// ================= FORM SUBMISSION =================
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $emp_id = intval($_POST['emp_id']);
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $days = $_POST['days'] ?? [];
    if(empty($days)){
    die("<script>alert('Please select at least one day'); window.history.back();</script>");
}

    // Validate employee belongs to same company
    $stmt = $conn->prepare("SELECT Emp_ID FROM employee WHERE Emp_ID = ? AND Company_ID = ?");
    $stmt->bind_param("ii", $emp_id, $company_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 0){
        die("<script>alert('Employee not found'); window.history.back();</script>");
    }

    // Validate time
    if($end_time <= $start_time){
        die("<script>alert('End time must be after start time'); window.history.back();</script>");
    }

    // Check duplicate timetable (same time slot)
    $duplicate_check = $conn->prepare("
        SELECT t.Time_ID FROM timetable t
        INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
        WHERE t.Emp_ID = ? AND ts.Start_Time = ? AND ts.End_Time = ?
    ");
    $duplicate_check->bind_param("iss", $emp_id, $start_time, $end_time);
    $duplicate_check->execute();
    $duplicate_check->store_result();

    if($duplicate_check->num_rows > 0){
        die("<script>alert('This timetable already exists for the employee'); window.history.back();</script>");
    }

    // Insert into time_stamp
    $stmt = $conn->prepare("INSERT INTO time_stamp (Start_Time, End_Time) VALUES (?, ?)");
    $stmt->bind_param("ss", $start_time, $end_time);
    $stmt->execute();
    $time_id = $conn->insert_id;

    // Convert days into boolean flags
    $Sun = in_array('Sun', $days) ? 1 : 0;
    $Mon = in_array('Mon', $days) ? 1 : 0;
    $Tue = in_array('Tue', $days) ? 1 : 0;
    $Wed = in_array('Wed', $days) ? 1 : 0;
    $Thu = in_array('Thu', $days) ? 1 : 0;
    $Fri = in_array('Fri', $days) ? 1 : 0;
    $Sat = in_array('Sat', $days) ? 1 : 0;

    // Insert into timetable
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

    echo "<script>alert('Timetable added successfully'); window.location='Timetable.php';</script>";
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
        .error-message { color: #d93025; display: none; margin-top: 5px; font-size: 0.9em; }
    </style>
    <script>
        function fetchEmpid(){
            let empname = document.getElementById('emp_name').value;
            let idField = document.getElementById('emp_id');

            if(empname){
                fetch('?get_emp_name=' + encodeURIComponent(empname))
                    .then(response => response.text())
                    .then(data => idField.value = data);
            } else {
                idField.value = '';
            }
        }
    </script>
</head>

<body>

<form method="POST" align="center">
    <h3>Add Timetable</h3><br>

    <div id="add_emp">
        <div style="width:100%;display:flex;justify-content:space-between;gap:24px;">
            <div style="width:50%">
                <label>Name:</label><br>
                <input type="text" name="emp_name" id="emp_name" oninput="fetchEmpid()"     required><br><br>

                <label>Start Time:</label><br>
                <input type="time" name="start_time" required><br>
            </div>

            <div style="width:50%;">
                <label style="opacity:0.6;">Employee ID:</label><br>
                <input type="number" name="emp_id" id="emp_id" readonly style="opacity:0.6;"><br><br>

                <label>End Time:</label><br>
                <input type="time" name="end_time" required><br>
            </div>
        </div>
    </div>

    <br>
    <p style="text-align:left;margin-left:12px;">Select Days:</p>
    <div id="days">

        <label><input type="checkbox" name="days[]" value="Sun" > Sun</label>
        <label><input type="checkbox" name="days[]" value="Mon" > Mon</label>
        <label><input type="checkbox" name="days[]" value="Tue" > Tue</label>
        <label><input type="checkbox" name="days[]" value="Wed" > Wed</label>
        <label><input type="checkbox" name="days[]" value="Thu" > Thu</label>
        <label><input type="checkbox" name="days[]" value="Fri" > Fri</label>
        <label><input type="checkbox" name="days[]" value="Sat" > Sat</label>
    </div>
    
    <p id="error" class="error-message">Please select at least one option before submitting.</p>

    <div id="button">
        <button type="submit">Add</button>
        <button id="cancel" type="button" onclick="window.location='Timetable.php'">Cancel</button>
    </div>
</form><script>
document.querySelector("form").addEventListener("submit", function(e){

    let checkboxes = document.querySelectorAll('input[name="days[]"]');
    let isChecked = false;

    checkboxes.forEach(function(box){
        if(box.checked){
            isChecked = true;
        }
    });

    if(!isChecked){
        e.preventDefault();
        alert("Please select at least one day.");
    }
});
</script>


</body>
</html>
