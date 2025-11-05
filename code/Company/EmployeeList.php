<?php
include("../db.php"); // your existing database connection
session_start();
$company_id = $_SESSION['company_id'];
?>

<html>
<head>
    <title>Employee View</title>
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
        table {
            padding: 10px;
            border-radius: 12px;
            width: 100%;
            border-collapse: collapse;
            background-color: #F5F3F3;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: rgba(14,62,217,0.2);
            text-align: left;
            color: rgba(14,62,217,0.9);
        }
        
        thead th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            width: 12px;
        }

        thead th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        td {
            background-color: #F5F3F3;
        }
        #table-content{
            background-color: #F5F3F3;
            padding:12px;
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
            margin: 0px;
            gap: 8px;
        }
        #top-right form {
            margin: 0;
        }
    </style>
</head>

<body>
    <div id="container">
        <?php include('../fixed/sidebar.php'); ?>
        <div id="content">

            <div id="top">
                <h3>Employees</h3>
                <div id="top-right">
                    <form method="GET" action="">
                        <div id="search-filter">
                            <?php
                            // Maintain values on reload
                            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                            $role = isset($_GET['role']) ? $_GET['role'] : 'all';
                            ?>
                            <input type="text" name="search" placeholder="Search by name or email"
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <select id="role_select" name="role" onchange="this.form.submit()">
                                <option value="all" <?php if ($role == 'all') echo 'selected'; ?>>All</option>
                                <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="staff" <?php if ($role == 'staff') echo 'selected'; ?>>Staff</option>
                                <option value="doctor" <?php if ($role == 'doctor') echo 'selected'; ?>>Doctor</option>
                            </select>
                        </div>
                    </form>
                    <button onclick="window.location='EmployeeAdd.php'">Add Employee</button>
                </div>
            </div>
            <div id="table-content">
                <table width:100%>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>More</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // --- Search & Filter Logic ---
                        $query = "SELECT * FROM employee WHERE Company_ID = '$company_id'";

                        if ($role !== 'all') {
                            $query .= " AND Role = '$role'";
                        }

                        if (!empty($search)) {
                            $query .= " AND (Name LIKE '%$search%' OR Email LIKE '%$search%')";
                        }

                        $query .= " ORDER BY Emp_ID ASC";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>{$row['Emp_ID']}</td>
                                    <td>{$row['Name']}</td>
                                    <td>{$row['Email']}</td>
                                    <td>{$row['Phone']}</td>
                                    <td>{$row['Role']}</td>
                                    <td><a href='EmployeeDetails.php?id={$row['Emp_ID']}'>View</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No employees found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
