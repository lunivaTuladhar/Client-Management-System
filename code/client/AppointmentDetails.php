<?php
session_start();
if(!isset($_SESSION['email'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php");

if(!isset($_GET['Appt_ID'])){
    die("No appointment specified.");
}

$appt_id = $_GET['Appt_ID'];

// Fetch appointment details
$appt_query = $conn->prepare("
    SELECT ba.*, e.Name AS Emp_Name, c.Name AS Company_Name
    FROM book_appt ba
    LEFT JOIN employee e ON ba.Emp_ID=e.Emp_ID
    LEFT JOIN company c ON ba.Company_ID=c.Company_ID
    WHERE ba.Appt_ID=?
    LIMIT 1
");
$appt_query->bind_param("i", $appt_id);
$appt_query->execute();
$appt_result = $appt_query->get_result();

if($appt_result->num_rows==0){
    die("Appointment not found.");
}

$appt = $appt_result->fetch_assoc();
$company_id = $appt['Company_ID'];

// Fetch employees of the same company
$emp_query = $conn->prepare("SELECT Emp_ID, Name FROM employee WHERE Company_ID=?");
$emp_query->bind_param("i", $company_id);
$emp_query->execute();
$emp_result = $emp_query->get_result();

// Fetch all time slots
$time_result = $conn->query("SELECT Time_ID, Start_Time, End_Time FROM time_stamp ORDER BY Start_Time ASC");

// Handle update
if(isset($_POST['save_appt'])){
    $new_date = $_POST['date'];
    $new_time = $_POST['time'];
    $new_emp = $_POST['employee'];
    $new_reason = $_POST['reason'];

    $update_stmt = $conn->prepare("UPDATE book_appt SET Date=?, Time=?, Emp_ID=?, Reason=? WHERE Appt_ID=?");
    $update_stmt->bind_param("ssisi", $new_date, $new_time, $new_emp, $new_reason, $appt_id);
    $update_stmt->execute();

    header("Location: AppointmentDetails.php?Appt_ID=".$appt_id);
    exit();
}

// Handle cancel
if(isset($_POST['cancel_appt'])){
    $cancel_stmt = $conn->prepare("Delete from book_appt WHERE Appt_ID=?");
    $cancel_stmt->bind_param("i", $appt_id);
    $cancel_stmt->execute();
    header("Location: MyAppointment.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/button.css">
    <style>
        #whole{padding:12px;margin:28px 12px 12px 70px;border-radius:12px;background-color:white;}
        #top{display:flex;gap:12px;align-items:center;}
        #content{padding:12px;background:#F5F3F3;border-radius:12px;}
        label{width:120px;font-weight:bold;color:rgba(14,62,217,0.9);}
        input, select, textarea{padding:6px;margin-bottom:8px;border-radius:6px;border:1px solid #ccc;}
        textarea{width:98%;}
        button{padding:6px 12px;border-radius:6px;cursor:pointer;margin-right:6px;}
        .btn-save{background:#0e3ed9;color:white;border:none;}
        .btn-cancel{background:rgba(239,24,24);color:white;border:none;}
        .btn-cancel:hover{background-color: rgba(239,24,24,0.4);}
        #button{display:flex; gap:24px;}
    </style>
</head>
<body>
<?php include ("../fixed/sidebar.php") ?>

<div id="container">
    <div id="whole">
        <div id="top">
            <a href="MyAppointment.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/>
                </svg>
            </a>
            <h3>Edit Appointment</h3>
        </div>
        <div id="content">
            <form method="POST">
                <div class="appt_details">
                    <label>Company:</label>
                    <p><?php echo $appt['Company_Name']; ?></p>
                </div>

                <div class="appt_details">
                    <label>Date:</label>
                    <input type="date" name="date" value="<?php echo $appt['Date']; ?>" required>
                </div>

                <div class="appt_details">
                    <label>Time:</label>
                    <select name="time" required>
                        <?php
                        if($time_result->num_rows>0){
                            while($t = $time_result->fetch_assoc()){
                                $time_str = $t['Start_Time'].' - '.$t['End_Time'];
                                $selected = ($appt['Time'] == $t['Start_Time']) ? 'selected' : '';
                                echo "<option value='{$t['Start_Time']}' {$selected}>{$time_str}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="appt_details">
                    <label>Employee:</label>
                    <select name="employee" required>
                        <?php
                        if($emp_result->num_rows>0){
                            while($e = $emp_result->fetch_assoc()){
                                $selected = ($appt['Emp_ID']==$e['Emp_ID']) ? 'selected' : '';
                                echo "<option value='{$e['Emp_ID']}' {$selected}>{$e['Name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="appt_details">
                    <label>Reason:</label>
                    <textarea name="reason" rows="4" required><?php echo $appt['Reason']; ?></textarea>
                </div>
                <div id="button">
                <button type="submit" name="save_appt" class="btn-save">Save Changes</button>
                <button type="submit" name="cancel_appt" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
