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

// Handle approve/decline actions
if(isset($_POST['action'])){
    $action = $_POST['action'];
    if($action == 'approve'){
        $stmt = $conn->prepare("UPDATE book_appt SET Status='Approved' WHERE Appt_ID=?");
        $stmt->bind_param("i", $appt_id);
        $stmt->execute();
        $msg = "Appointment approved successfully.";
    } elseif($action == 'decline'){
        $reason_decline = $_POST['decline_reason'] ?? '';
        if(!empty($reason_decline)){
            $stmt = $conn->prepare("UPDATE book_appt SET Status='Declined', Reason=? WHERE Appt_ID=?");
            $stmt->bind_param("si", $reason_decline, $appt_id);
            $stmt->execute();
            $msg = "Appointment declined successfully.";
        } else {
            $msg = "Decline reason cannot be empty.";
        }
    }
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment Details</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/button.css">
</head>
<style>
    /* Keep your original CSS */
    #whole{padding:12px;margin:24px 12px 12px 70px;border-radius:12px;background-color:white;}
    #top{display:flex;gap:12px;align-items:center;}
    #content{padding:12px;background:#F5F3F3;border-radius:12px;}
    label{width:120px;font-weight:bold;color:rgba(14,62,217,0.9);}
    input, select, textarea{width:100%;padding:6px;margin-bottom:8px;border-radius:6px;border:1px solid #ccc;background:#eaeaea;}
    textarea{resize:none;}
    #button { display:flex;width:200px;margin-left:auto;align-items:center;justify-content:center;gap:12px;background-color:#F5F3F3;}
    #approve{background-color: rgba(14,194,14,0.2); border:2px rgba(14,194,14,0.9) solid; color:rgba(14,194,14,0.9);}
    #approve:hover{background-color: rgba(14,194,14,0.4);}
    #decline{background-color: rgba(239,24,24,0.2); border:2px rgba(239,24,24,0.9) solid; color:rgba(239,24,24,0.9);}
    #decline:hover{background-color: rgba(239,24,24,0.4);}
</style>
<body>
<?php include ("../fixed/sidebar.php") ?>

<div id="container">
    <div id="whole">
        <div id="top">
            <a href="history.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/>
                </svg>
            </a>
            <h3>Appointment Details</h3>
        </div>

        <?php if(isset($msg)){ echo "<p style='color:green;font-weight:bold;'>$msg</p>"; } ?>

        <div id="content">
            <div class="appt_details"><label>Company:</label><input type="text" value="<?php echo $appt['Company_Name']; ?>" readonly></div>
            <div class="appt_details"><label>Date:</label><input type="date" value="<?php echo $appt['Date']; ?>" readonly></div>
            <div class="appt_details"><label>Time:</label>
                <select readonly disabled>
                    <?php
                    if($time_result->num_rows>0){
                        while($t = $time_result->fetch_assoc()){
                            $time_str = $t['Start_Time'].' - '.$t['End_Time'];
                            $selected = ($appt['Time'] == $t['Start_Time']) ? 'selected' : '';
                            echo "<option {$selected}>{$time_str}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="appt_details"><label>Employee:</label>
                <select readonly disabled>
                    <?php
                    if($emp_result->num_rows>0){
                        while($e = $emp_result->fetch_assoc()){
                            $selected = ($appt['Emp_ID']==$e['Emp_ID']) ? 'selected' : '';
                            echo "<option {$selected}>{$e['Name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="appt_details"><label>Reason:</label><textarea rows="4" readonly><?php echo $appt['Reason']; ?></textarea></div>
            <div class="appt_details"><label>Status:</label><input type="text" value="<?php echo $appt['Status']; ?>" readonly></div>

            <!-- Approve / Decline buttons -->
             <?php if ($appt['Status']!="Approved" && $appt['Status']!="Completed") :?>
            <form method="POST" id="actionForm">
                <div id="button">
                    <button type="submit" name="action" value="approve" id="approve">Approve</button>
                    <button type="button" id="decline" onclick="decline()">Decline</button>
                </div>
                <input type="hidden" name="decline_reason" id="decline_reason">
            </form>
            <?php endif;?>
        </div>
    </div>
</div>

<script>
function decline() {
    let reason = prompt("Please enter the reason for declining:");
    if(reason){
        document.getElementById('decline_reason').value = reason;
        document.getElementById('actionForm').submit();
    } else if(reason === ""){
        alert("Decline reason cannot be empty.");
    }
}
</script>
</body>
</html>
