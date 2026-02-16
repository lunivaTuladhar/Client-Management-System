<?php
include("../db.php");
session_start();
$company_id = $_SESSION['company_id'];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type   = isset($_GET['type']) ? $_GET['type'] : 'all';
?>

<html>
<head>
    <title>Client View</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">

    <style>
        form{padding:0;}
        #content{padding:12px;margin-left:74px;border-radius:12px;background:white;margin-right:14px;}
        #top{display:flex;justify-content:space-between;align-items:center;}
        #top-right{display:flex;align-items:center;gap:12px;right:0;}
        #top-right form{display:flex;gap:12px;width:90%;}
        input{height:32px;width:300px;margin:0;}
        h3{margin:0;}
        #container{background:#F5F3F3;height:86vh;padding:74px 0 0 0;}
        table{background:#F5F3F3;padding:10px;border-radius:12px;width:100%;border-collapse:collapse;text-align:left;}
        th,td{padding:12px;}
        th{background:rgba(14,62,217,.2);color:rgba(14,62,217,.9);}
        
        #role_select{width:250px;height:32px;margin:0;}
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
    </style>
</head>

<body>
<div id="container">
<?php include('../fixed/sidebar.php'); ?>

<div id="content">

<div id="top">
    <h3>Clients</h3>
    <div id="top-right">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by name or email"
                   value="<?= htmlspecialchars($search) ?>">
            <select id="role_select" name="type" onchange="this.form.submit()">
                <option value="all" <?= $type=='all'?'selected':'' ?>>All</option>
                <option value="loyal" <?= $type=='loyal'?'selected':'' ?>>Loyal</option>
                <option value="normal" <?= $type=='normal'?'selected':'' ?>>Normal</option>
                <option value="recent" <?= $type=='recent'?'selected':'' ?>>Recent</option>
            </select>
        </form>
    </div>
</div>
<div id="table">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>More</th>
</tr>
</thead>

<tbody>
<?php
$sql = "
    SELECT 
        c.Client_ID, c.Name, c.Email, c.Phone,
        COUNT(b.Appt_ID) AS total_appts,
        MAX(b.`Date`) AS last_appt
    FROM client c
    INNER JOIN book_appt b ON b.Client_ID = c.Client_ID
    WHERE b.Company_ID = ?
";

if (!empty($search)) {
    $sql .= " AND (c.Name LIKE ? OR c.Email LIKE ?) ";
}

$sql .= " GROUP BY c.Client_ID ";

if ($type === 'loyal') {
    $sql .= " HAVING total_appts >= 5 ";
} elseif ($type === 'normal') {
    $sql .= " HAVING total_appts BETWEEN 1 AND 5 ";
} elseif ($type === 'recent') {
    $sql .= " HAVING last_appt >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $s = "%$search%";
    $stmt->bind_param("iss", $company_id, $s, $s);
} else {
    $stmt->bind_param("i", $company_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
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
?>
</tbody>
</table>
</div>

</div>
</div>
</body>
</html>
