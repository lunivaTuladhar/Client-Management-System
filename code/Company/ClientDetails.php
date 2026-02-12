<?php
session_start();
include("../db.php");

// Get client ID from query parameter
if (!isset($_GET['id'])) {
    echo "No client selected.";
    exit();
}
$client_id = intval($_GET['id']);

// Fetch client details
$stmt = $conn->prepare("SELECT * FROM client WHERE Client_ID = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

// Fetch the most recent appointment for this client
$appt_stmt = $conn->prepare("
    SELECT b.Date, b.Time, e.Name AS EmpName, c.Name AS CompanyName
    FROM book_appt b
    LEFT JOIN employee e ON b.Emp_ID = e.Emp_ID
    LEFT JOIN company c ON b.Company_ID = c.Company_ID
    WHERE b.Client_ID = ?
    ORDER BY b.Date DESC, b.Time DESC
    LIMIT 1
");
$appt_stmt->bind_param("i", $client_id);
$appt_stmt->execute();
$appt = $appt_stmt->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Details</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        #whole_container{
            background-color: #F5F3F3;
            height: 100%;
            margin-top: 50px;
            margin-left: 50px;
            padding:24px;
        }
        #container{
            border-radius:24px;
            padding:24px;
            background:#fff;
        }
        .appt_details{
            background-color: #F5F3F3;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .appt_details label{
            width:180px;
            color: rgba(14, 62, 217, 0.9);
            font-weight:600;
        }
        .appt_details p{
            margin:0;
        }
        #container img{
            height:150px;
            width:150px;
            border-radius:50%;
            display:block;
            margin: 0 auto 12px auto;
            background:#ccc;
        }
        #profilepic{
            display:flex;
            justify-content:center;
        }
        #details{
            padding:12px;
            background-color: #F5F3F3;
            border-radius:12px;
        }
    </style>
</head>
<body>
    <?php include("../fixed/sidebar.php")?>

    <div id="whole_container">
        <div id="container">
            <div id="profilepic">
                <img src="<?php echo $profile_pic; ?>" alt="Client Picture">
            </div>
            
            <div id="details">
                <div class="appt_details"><label>Name:</label><p><?php echo htmlspecialchars($client['Name']); ?></p></div>
                <div class="appt_details"><label>Email:</label><p><?php echo htmlspecialchars($client['Email']); ?></p></div>
                <div class="appt_details"><label>Phone:</label><p><?php echo htmlspecialchars($client['Phone']); ?></p></div>
                <div class="appt_details"><label>Address:</label><p><?php echo htmlspecialchars($client['Address']); ?></p></div>
                <div class="appt_details"><label>DOB:</label><p><?php echo htmlspecialchars($client['DOB']); ?></p></div>
                <div class="appt_details"><label>Recent Appointment:</label>
                    <p>
                        <?php
                        if ($appt) {
                            echo htmlspecialchars($appt['Date']) . " at " . htmlspecialchars($appt['Time']) . " with " . htmlspecialchars($appt['EmpName']);
                        } else {
                            echo "No recent appointment";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
