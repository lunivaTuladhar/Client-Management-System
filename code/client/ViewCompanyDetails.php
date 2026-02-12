<?php
    include("../db.php");
    session_start();
    // Validate and fetch company details
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $query = $conn->prepare("SELECT Name, Address, Logo, Description FROM company WHERE Company_ID = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if($result->num_rows > 0){
            $company = $result->fetch_assoc();
        } else {
            echo "<p>Company not found.</p>";
            exit;
        }
    } else {
        echo "<p>No company selected.</p>";
        exit;
    }// Fetch employees for left timetable
    $emp_query = "SELECT Emp_ID, Name, Phone, Role FROM employee WHERE Company_ID = ? and isadmin!=1 ORDER BY Emp_ID ASC";
    $stmt_emp = $conn->prepare($emp_query);
    $stmt_emp->bind_param("i", $id);
    $stmt_emp->execute();
    $emp_result = $stmt_emp->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($company['Name']); ?> - Details</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #ff0303ff;
        }
        .company-detail {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 24px;
            max-width: 89%;
            height:440px;
            margin: 65px 0px 0px 78px;
        }
        #timetable{ 
          margin-left:0px; 
          padding:8px 12px; 
          width:96%; 
          margin-top:12px; 
          border-radius:12px;
          background-color: #f3f3f3 ;
        }
        table{ 
          background-color:#F5F3F3; 
          padding:10px; 
          border-radius:12px; 
          width:100%; 
          border-collapse:collapse; 
        }
        th, td{ 
          padding:12px; 
        }
        th{ 
          background-color: rgba(14,62,217,0.2); 
          text-align:left; 
          color: rgba(14,62,217,0.9);
        }
        tr:hover{ background-color:#eaeaea; }
        thead th:first-child{ 
          border-top-left-radius:12px; 
          border-bottom-left-radius:12px; 
        }
        thead th:last-child{ 
          border-top-right-radius:12px; 
          border-bottom-right-radius:12px;
        }
        .company-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
        }

        .company-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .company-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: #0E3ED9;
            margin: 0;
        }

        .company-address {
            font-size: 1rem;
            color: #555;
        }

        .company-description {
            margin-top: 20px;
            font-size: 1rem;
            color: #333;
            line-height: 1.5;
            text-align: justify;
        }

        .back-btn {
            display: inline-block;
            margin-top: 24px;
            padding: 8px 16px;
            background-color: #0E3ED9;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background-color: #0c36c0;
        } 
        #bookappt{
            position:absolute;
            width:150px;
            right:20px;
            bottom:20px;
        } .info{
            background-color:#f3f3f3;
            padding-left:20px;
            padding-top:20px;
            margin-bottom:0px;
            border-radius:12px;
        }
    </style>
</head>
<?php include('../fixed/sidebar.php')?>
<body style="background-color:#f3f3f3;">
<div class="company-detail">
    <div class="company-header">
        <img src="<?php echo $company['Logo'] ?: '../images/default_company.png'; ?>" 
             alt="Company Logo" class="company-logo">
        <div>
            <p class="company-name"><?php echo htmlspecialchars($company['Name']); ?></p>
            <p class="company-address"><?php echo htmlspecialchars($company['Address']); ?></p>
        </div>
    </div>

    <div class="info">

        <div class="company-description">
            <?php echo nl2br(htmlspecialchars($company['Description'] ?: "No description available.")); ?>
        </div>
        <div id="timetable">
            <h3>Timetable</h3>
            <table>
                <thead>
                    <tr><th>ID</th><th>Employee</th><th>Phone</th><th>Role</th><th>Available</th></tr>
                </thead>
                <tbody>
                    <?php
            if($emp_result && $emp_result->num_rows>0){
                while($row = $emp_result->fetch_assoc()){
                    // Fetch available slots from timetable and time_stamp
                    $stmt_time = $conn->prepare("
    SELECT ts.Start_Time, ts.End_Time
    FROM timetable t
    JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
    WHERE t.Emp_ID = ? AND t.Company_ID = ?
    ORDER BY ts.Start_Time ASC
");
$stmt_time->bind_param("ii", $row['Emp_ID'], $id);
$stmt_time->execute();
$res_time = $stmt_time->get_result();

$slots = [];
while($slot = $res_time->fetch_assoc()){
    $slots[] = "{$slot['Start_Time']} - {$slot['End_Time']}";
}
echo "<tr>
<td>{$row['Emp_ID']}</td>
<td>{$row['Name']}</td>
<td>{$row['Phone']}</td>
<td>{$row['Role']}</td>
<td>" . (empty($slots) ? 'N/A' : implode('<br>', $slots)) . "</td>
</tr>";

                    $stmt_time->close();
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No employees found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<form action="BookAppointment.php" method="GET" id="bookappt">
    <input type="hidden" name="company_id" value="<?php echo $id?>">
    <button type="submit" class="book-btn">Book Appointment</button>
</form>

</body>
</html>
