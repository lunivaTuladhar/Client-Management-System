<?php
include("../db.php");
session_start();
$company_id = $_SESSION['company_id'];
?>

<html>
<head>
    <title>Client View</title>
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
            display: flex;
            align-items: center;
            gap: 12px;
        }
       
        #top-right form{
            display:flex;
            margin:0;
            gap: 12px;
        }
        input {
            height: 32px;
            width: 300px;
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
        table {
            background-color: #F5F3F3;
            padding: 10px;
            border-radius: 12px;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: rgba(14, 62, 217, 0.2);
            text-align: left;
            color: rgba(14, 62, 217, 0.9);
        }
        tr:hover {
            background-color: #eaeaea;
        }
        #role_select {
            background-color: #ffffff;
            border: 1px solid rgba(45, 45, 45, 0.2);
            color: rgba(45, 45, 45, 0.9);
            width: 250px;
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
            <h3>Clients</h3>
            <div id="top-right">
                <form method="GET" action=""style="width:75%;margin-left:20%;">
                    <input type="text" name="search" placeholder="Search by name or email" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <select id="role_select" name="role">
                            <option value="all">All</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="doctor">Doctor</option>
                        </select>
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>More</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                // Get client IDs that have at least one appointment in this company
                $client_ids_sql = "SELECT DISTINCT Client_ID FROM book_appt WHERE Company_ID = ?";
                $stmt = $conn->prepare($client_ids_sql);
                $stmt->bind_param("i", $company_id);
                $stmt->execute();
                $result_ids = $stmt->get_result();

                $client_ids = [];
                while($row = $result_ids->fetch_assoc()){
                    $client_ids[] = $row['Client_ID'];
                }

                if(count($client_ids) > 0){
                    // Fetch clients
                    $placeholders = implode(',', array_fill(0, count($client_ids), '?'));
                    $types = str_repeat('i', count($client_ids));
                    $sql = "SELECT * FROM client WHERE Client_ID IN ($placeholders)";

                    // Add search filter
                    if(!empty($search)){
                        $sql .= " AND (Name LIKE ? OR Email LIKE ?)";
                        $types .= "ss";
                    }

                    $stmt2 = $conn->prepare($sql);

                    // Bind parameters dynamically
                    $bind_names[] = & $types;
                    foreach($client_ids as $k => $id){
                        $bind_names[] = & $client_ids[$k];
                    }
                    if(!empty($search)){
                        $search_param = "%$search%";
                        $bind_names[] = & $search_param;
                        $bind_names[] = & $search_param;
                    }
                    call_user_func_array([$stmt2, 'bind_param'], $bind_names);
                    $stmt2->execute();
                    $result = $stmt2->get_result();

                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<tr>
                                <td>{$row['Client_ID']}</td>
                                <td>{$row['Name']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['Phone']}</td>
                                <td><a href='ClientDetails.php?id={$row['Client_ID']}'>View</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>No clients found</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No clients with appointments found</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
