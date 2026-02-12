<?php
session_start();
include("../db.php");

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("location:../login/log in.php");
    exit();
}

$clientId = $_SESSION['user_id'];

// Check ID in URL
if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$appointmentId = intval($_GET['id']);

// Fetch appointment details
$sql = $conn->prepare("
    SELECT b.Appt_ID, b.Date, b.Time, b.Reason, b.Status, 
           e.Name AS Emp_Name, e.Phone AS Emp_Phone
    FROM book_appt b
    INNER JOIN employee e ON b.Emp_ID = e.Emp_ID
    WHERE b.Appt_ID = ? AND b.Client_ID = ?
    LIMIT 1
");

$sql->bind_param("ii", $appointmentId, $clientId);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows === 0) {
    die("Appointment not found or unauthorized access.");
}

$data = $result->fetch_assoc();


// Handle cancellation
if (isset($_POST['cancel'])) {
    $cancel = $conn->prepare("UPDATE book_appt SET Status='Cancelled' WHERE Appt_ID=? AND Client_ID=?");
    $cancel->bind_param("ii", $appointmentId, $clientId);

    if ($cancel->execute()) {
        echo "<script>alert('Appointment cancelled successfully!'); window.location.href='Dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Unable to cancel appointment.');</script>";
    }
}
?>

<html>
<head>
    <title>Appointment Details</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
</head>

<style>
#content{
    padding: 20px;
    margin-left:74px;
    border-radius:12px;
    background-color: white;
    margin-right: 14px;
}
#container{
    background-color:#F5F3F3;
    min-height: 86vh;
    padding-top: 74px;
}
.details-box{
    background-color:#F5F3F3;
    padding:20px;
    border-radius:12px;
    width: 60%;
    margin:auto;
}
.details-box h3{
    margin-bottom:20px;
}
.row{
    margin-bottom:15px;
}
.label{
    font-weight:bold;
    color:#2d2d2d;
}
.value{
    margin-left:10px;
    color:#333;
}
button{
    margin-top:20px;
    width:180px;
}
.back-btn{
    background:#6c757d;
    margin-right:10px;
}
.cancel-btn{
    background:#d11a2a;
}
</style>

<body>
<?php include("../fixed/sidebar.php"); ?>

<div id="container">
<div id="content">

<div class="details-box">
    <h3>Appointment Details</h3>

    <div class="row">
        <span class="label">Name (Employee):</span>
        <span class="value"><?php echo htmlspecialchars($data['Emp_Name']); ?></span>
    </div>

    <div class="row">
        <span class="label">Contact:</span>
        <span class="value"><?php echo htmlspecialchars($data['Emp_Phone']); ?></span>
    </div>

    <div class="row">
        <span class="label">Date:</span>
        <span class="value"><?php echo htmlspecialchars($data['Date']); ?></span>
    </div>

    <div class="row">
        <span class="label">Time:</span>
        <span class="value"><?php echo htmlspecialchars($data['Time']); ?></span>
    </div>

    <div class="row">
        <span class="label">Reason:</span>
        <span class="value"><?php echo htmlspecialchars($data['Reason']); ?></span>
    </div>

    <form method="POST">
        <button type="button" class="back-btn" onclick="window.history.back()">Back</button>

        <?php if (strtolower($data['Status']) !== 'cancelled') { ?>
            <button type="submit" name="cancel" class="cancel-btn">Cancel Appointment</button>
        <?php } else { ?>
            <button type="button" class="cancel-btn" disabled>Already Cancelled</button>
        <?php } ?>
    </form>

</div>

</div>
</div>

</body>
</html>
