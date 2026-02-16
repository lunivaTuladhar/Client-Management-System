<?php
session_start();
include('../db.php');

if(!isset($_SESSION['company_id'])){
    die("Unauthorized access");
}

$company_id = $_SESSION['company_id'];

if(!isset($_GET['id'])){
    header("location:Timetable.php");
    exit();
}

$time_id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT t.*, ts.Start_Time, ts.End_Time, e.Name, e.Emp_ID
    FROM timetable t
    INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
    INNER JOIN employee e ON t.Emp_ID = e.Emp_ID
    WHERE t.Time_ID = ? AND t.Company_ID = ?
");
$stmt->bind_param("ii", $time_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Unauthorized access");
}

$row = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $start_time = $_POST['start_time'];
    $end_time   = $_POST['end_time'];
    $days       = $_POST['days'] ?? [];

    if(empty($days)){
        die("<script>alert('Please select at least one day'); window.history.back();</script>");
    }

    if($end_time <= $start_time){
        die("<script>alert('End time must be after start time'); window.history.back();</script>");
    }

    $reset = $conn->prepare("
        UPDATE timetable 
        SET Sun=0, Mon=0, Tue=0, Wed=0, Thu=0, Fri=0, Sat=0
        WHERE Time_ID = ? AND Company_ID = ?
    ");
    $reset->bind_param("ii", $time_id, $company_id);
    $reset->execute();

    foreach($days as $day){
        $update_day = $conn->prepare("
            UPDATE timetable SET $day = 1
            WHERE Time_ID = ? AND Company_ID = ?
        ");
        $update_day->bind_param("ii", $time_id, $company_id);
        $update_day->execute();
    }

    $update_time = $conn->prepare("
        UPDATE time_stamp 
        SET Start_Time = ?, End_Time = ?
        WHERE Time_ID = ?
    ");
    $update_time->bind_param("ssi", $start_time, $end_time, $time_id);
    $update_time->execute();

    echo "<script>alert('Timetable updated successfully'); window.location='Timetable.php';</script>";
    exit();
}
?>

<html>
<head>
    <title>Edit Timetable</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/button.css">

    <style>
        body { font-family: Arial, sans-serif; }

        form {
            border: 1px solid black;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            border-radius:12px;
        }

        #days{
            display:flex;
            justify-content:space-between;
            gap:12px;
            margin-bottom:60px;
            margin-left:12px;
        }

        #days input{
            height:20px;
            margin-left:12px;
            width:25px;
        }

        #button{
            display: flex;
            width: 200px;
            margin-left: auto;
            align-items: center;
            justify-content: center;
            gap: 32px;
        }

        #cancel{
            background-color: rgba(239,24,24,0.2);
            border: 2px rgba(239,24,24,0.9) solid;
            color:rgba(239,24,24,0.9);
        }

        #cancel:hover{
            background-color: rgba(239,24,24,0.4);
        }
    </style>
</head>

<body>

<form method="POST" align="center">

    <h3>Edit Timetable</h3><br>

    <div style="width:100%;display:flex;justify-content:space-between;gap:24px;">

        <div style="width:50%">
            <label>Name:</label><br>
            <input type="text" value="<?php echo htmlspecialchars($row['Name']); ?>" readonly><br><br>

            <label>Start Time:</label><br>
            <input type="time" name="start_time"
                   value="<?php echo htmlspecialchars($row['Start_Time']); ?>" required><br>
        </div>

        <div style="width:50%">
            <label style="opacity:0.6;">Employee ID:</label><br>
            <input type="number" value="<?php echo $row['Emp_ID']; ?>" readonly style="opacity:0.6;"><br><br>

            <label>End Time:</label><br>
            <input type="time" name="end_time"
                   value="<?php echo htmlspecialchars($row['End_Time']); ?>" required><br>
        </div>

    </div>

    <br>
    <p style="text-align:left;margin-left:12px;">Select Days:</p>

    <div id="days">
        <label><input type="checkbox" name="days[]" value="Sun" <?php if($row['Sun']) echo "checked"; ?>> Sun</label>
        <label><input type="checkbox" name="days[]" value="Mon" <?php if($row['Mon']) echo "checked"; ?>> Mon</label>
        <label><input type="checkbox" name="days[]" value="Tue" <?php if($row['Tue']) echo "checked"; ?>> Tue</label>
        <label><input type="checkbox" name="days[]" value="Wed" <?php if($row['Wed']) echo "checked"; ?>> Wed</label>
        <label><input type="checkbox" name="days[]" value="Thu" <?php if($row['Thu']) echo "checked"; ?>> Thu</label>
        <label><input type="checkbox" name="days[]" value="Fri" <?php if($row['Fri']) echo "checked"; ?>> Fri</label>
        <label><input type="checkbox" name="days[]" value="Sat" <?php if($row['Sat']) echo "checked"; ?>> Sat</label>
    </div>

    <div id="button">
        <button type="submit">Update</button>
        <button id="cancel" type="button" onclick="window.location='Timetable.php'">Cancel</button>
    </div>

</form>

<script>
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
