<?php
include("../db.php");
session_start();
$company_id = $_SESSION['company_id'];
?>

<html>
<head>
    <title>Appointment History</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">

    <style>
        #content {
            padding: 12px;
            margin-left: 74px;
            border-radius: 12px;
            background-color: white;
            margin-right: 14px;
        }
        #top {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            align-items: center;
        }
        #top-right {
            width: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        input {
            height: 32px;
            width: 200px;
            margin: 0;
        }
        h3 {
            color: #2d2d2dff;
            margin: 0;
        }
        #container {
            background-color: #F5F3F3;
            height: 86vh;
            padding: 74px 0 0 0;
        }
        table{background:#F5F3F3;padding:10px;border-radius:12px;width:100%;border-collapse:collapse;text-align:left;}
        th,td{padding:12px;}
        th{background:rgba(14,62,217,.2);color:rgba(14,62,217,.9);}
        
        th:last-child{
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        th:first-child{
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }
        #table{
            padding:12px;
            background-color:#F5F3F3;
            border-radius:12px;
        }
        #role_select {
            background-color: #ffffff;
            border: 1px solid rgba(45, 45, 45, 0.2);
            color: rgba(45, 45, 45, 0.9);
            width: 150px;
            height: 32px;
            margin: 0;
        }
        #top-right button {
            width: 150px;
        }
        #search-filter {
            display: flex;
            margin-right: 24px;
            gap: 8px;
        }
        #role_select {
            background-color: #ffffff;
            border: 1px solid rgba(45, 45, 45, 0.2);
            color: rgba(45, 45, 45, 0.9);
            width: 150px;
            height: 32px;
            margin: 0;
        }
    </style>
</head>

<body>
<div id="container">
    <?php include('../fixed/sidebar.php'); ?>
    <div id="content">

        <div id="top">
            <h3>Appointment History</h3>
            <div id="top-right">
                <form method="GET" action="">
                    <div id="search-filter">
                            <?php
                            // Maintain values on reload
                            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                           
                            
                            ?>
                            <input type="text" name="search" placeholder="Search by name or email"
                                   value="<?php echo htmlspecialchars($search); ?>">
                            
                        </div>
                </form>
            </div>
        </div>
        <div id="table">
            <table>
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Client Name</th>
                        <th>Employee Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>More</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                    // Base query for completed appointments
                    $sql = "SELECT ba.Appt_ID, ba.Date, ba.Time, ba.Status, 
                                c.Name AS Client_Name, e.Name AS Employee_Name
                            FROM book_appt ba
                            LEFT JOIN client c ON ba.Client_ID = c.Client_ID
                            LEFT JOIN employee e ON ba.Emp_ID = e.Emp_ID
                            WHERE ba.Company_ID = ? AND ba.Status='Completed'";

                    // Add search filter if provided
                    if(!empty($search)){
                        $sql .= " AND (c.Name LIKE ? OR e.Name LIKE ?)";
                    }

                    $stmt = $conn->prepare($sql);

                    if(!empty($search)){
                        $param = "%$search%";
                        $stmt->bind_param("iss", $company_id, $param, $param);
                    } else {
                        $stmt->bind_param("i", $company_id);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if($result->num_rows > 0){
                        $sn = 1;
                        while($row = $result->fetch_assoc()){
                            echo "<tr>
                                <td>{$sn}</td>
                                <td>{$row['Client_Name']}</td>
                                <td>{$row['Employee_Name']}</td>
                                <td>{$row['Date']}</td>
                                <td>{$row['Time']}</td>
                                <td><a href='AppointmentDetails.php?Appt_ID={$row['Appt_ID']}'>View</a></td>
                            </tr>";
                            $sn++;
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>No completed appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
    </div>
</div>
</body>
</html>
