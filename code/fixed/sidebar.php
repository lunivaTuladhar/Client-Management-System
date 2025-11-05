    <?php
    $user_type=$_SESSION['user_type'];
    include ("../db.php");
    // Logged-in user info
$emp_name = $_SESSION['name'] ;
$profile_pic = "";
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $res = $conn->query("SELECT Name, Profile FROM employee WHERE Email='$email' LIMIT 1");
    if($res && $res->num_rows>0){
        $user = $res->fetch_assoc();
        $emp_name = ucfirst($user['Name']);
        $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_profile.png";
    }
}
    ?>

    <style>
     body {
    margin: 0;
    background-color: #ffffff;
}

/* SIDEBAR */
#side_nav {
    position: fixed;
    top: 50px;
    left: 0;
    width: 200px;
    height: calc(100vh - 50px);
    border-right: 1px solid rgba(0, 0, 0, 0.2);
    background-color: #ffffff;
    padding: 12px 8px;
    overflow: hidden;
    transition: width 0.1s ease;
}

#side_nav.collapsed {
    width: 45px;
}

/* MENU ITEMS */
.menu_item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 8px;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.menu_item:hover {
    background-color: rgba(14, 62, 217, 0.1);
}

.menu_item a {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: inherit;
    width: 100%;
}

.menu_item p {
    margin: 0;
    font-size: 14px;
    white-space: nowrap;
}

/* Hide text when collapsed */
#side_nav.collapsed .menu_item p {
    display: none;
}

/* Active link */
.menu_item a.active svg,
.menu_item a.active p {
    color: rgba(14, 62, 217, 0.9);
    font-weight: bold;
}

/* ICONS */
svg {
    color: rgba(45, 45, 45, 0.9);
    min-width: 20px;
    height: 20px;
}

#side_nav.collapsed svg {
    margin: 0 auto;
}

/* TOP NAV */
.top_nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 50px;
    width: 98%;
    border-bottom: 1px solid rgba(0, 0, 0, 0.2);
    background-color: #ffffff;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
    padding: 0 14px;
}

.top_nav h2 {
    margin: 0;
    font-size: 18px;
    color: rgba(45, 45, 45, 0.9);
}

.top_nav a {
    text-decoration: none;
    color: rgba(45, 45, 45, 0.9);
    font-size: 14px;
}

/* LOGO */
#logo {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

#logo svg {
    color: rgba(45, 45, 45, 0.9);
}

/* PROFILE AREA */
#profile {
    display: flex;
    align-items: center;
    gap: 10px;
}

#profile img {
    height: 38px;
    width: 38px;
    border-radius: 50%;
    object-fit: cover;
    background-color: #e0e0e0; /* grey circle if no image loads */
    border: 1px solid rgba(0, 0, 0, 0.1);
}

#profile a {
    font-weight: 500;
}

/* COLLAPSED CONTENT ADJUSTMENT */
.collapsed ~ .main_content {
    margin-left: 50px;
}

.main_content {
    margin-left: 200px;
    padding-top: 60px;
    transition: margin-left 0.2s ease;
}
        
    </style>

<?php
// Get current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- client nav bar -->
<?php
if ( $_SESSION['user_type'] == "client") :?>
    <!-- Sidebar (starts collapsed) -->
    <nav id="side_nav" class="collapsed">
        <div class="menu_item">
            <a href="../Client/Dashboard.php">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
            <p>Dashboard</p></a>
        </div>
        
        <div class="menu_item">
            <a href="../Client/Browse.php">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
            <p>Browse Organization</p></a>
        </div>

        <div class="menu_item">
            <a href="../Client/MyAppointment.php">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
            <p>My Appointments</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Client/MyHistory.php">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9 17H7v-7h2zm4 0h-2V7h2zm4 0h-2v-4h2zm2 2H5V5h14v14.1M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2"/></svg>
                <p>My History</p>
            </a>
        </div>
    </nav>

<!--employee nav bar  -->
<?php
elseif( $_SESSION['user_type'] == "employee") :?>
<nav id="side_nav" class="collapsed">
        <div class="menu_item">
            <a href="../Employee/Dashboard.php">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
                <p>Dashboard</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Employee/Appointment.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 1024 1024"><path fill="currentColor" d="M928 224H768v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H548v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H328v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H96c-17.7 0-32 14.3-32 32v576c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V256c0-17.7-14.3-32-32-32m-40 568H136V296h120v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h120zM416 496H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m0 136H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m308.2-177.4L620.6 598.3l-52.8-73.1c-3-4.2-7.8-6.6-12.9-6.6H500c-6.5 0-10.3 7.4-6.5 12.7l114.1 158.2a15.9 15.9 0 0 0 25.8 0l165-228.7c3.8-5.3 0-12.7-6.5-12.7H737c-5-.1-9.8 2.4-12.8 6.5"/></svg>
                <p>Appointment</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Employee/Timetable.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
                <p>Timetable</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Employee/History.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 11.11V5a2 2 0 0 0-2-2h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14a2 2 0 0 0 2 2h6.11c1.26 1.24 2.98 2 4.89 2c3.87 0 7-3.13 7-7c0-1.91-.76-3.63-2-4.89M12 3c.55 0 1 .45 1 1s-.45 1-1 1s-1-.45-1-1s.45-1 1-1M5 19V5h2v2h10V5h2v4.68c-.91-.43-1.92-.68-3-.68H7v2h4.1c-.6.57-1.06 1.25-1.42 2H7v2h2.08c-.05.33-.08.66-.08 1c0 1.08.25 2.09.68 3zm11 2c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z"/></svg>
                 <p>History</p>
            </a>
        </div>
    </nav>

<!--superadmin nav bar  -->
<?php
elseif( $_SESSION['user_type'] == "superadmin") :?>
<nav id="side_nav" class="collapsed">
        <div class="menu_item">
            <a href="../Company/Dashboard.php" class="<?php if ($current_page == 'Dashboard.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
                <p>Dashboard</p>
            </a>
        </div>
        
        <div class="menu_item">
            <a href="../Company/Appointment.php" class="<?php if ($current_page == 'Appointment.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 1024 1024"><path fill="currentColor" d="M928 224H768v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H548v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H328v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H96c-17.7 0-32 14.3-32 32v576c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V256c0-17.7-14.3-32-32-32m-40 568H136V296h120v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h120zM416 496H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m0 136H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m308.2-177.4L620.6 598.3l-52.8-73.1c-3-4.2-7.8-6.6-12.9-6.6H500c-6.5 0-10.3 7.4-6.5 12.7l114.1 158.2a15.9 15.9 0 0 0 25.8 0l165-228.7c3.8-5.3 0-12.7-6.5-12.7H737c-5-.1-9.8 2.4-12.8 6.5"/></svg>
                <p>Appointment</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Company/EmployeeList.php" class="<?php if ($current_page == 'Employees.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9 13.75c-2.34 0-7 1.17-7 3.5V19h14v-1.75c0-2.33-4.66-3.5-7-3.5M4.34 17c.84-.58 2.87-1.25 4.66-1.25s3.82.67 4.66 1.25zM9 12c1.93 0 3.5-1.57 3.5-3.5S10.93 5 9 5S5.5 6.57 5.5 8.5S7.07 12 9 12m0-5c.83 0 1.5.67 1.5 1.5S9.83 10 9 10s-1.5-.67-1.5-1.5S8.17 7 9 7m7.04 6.81c1.16.84 1.96 1.96 1.96 3.44V19h4v-1.75c0-2.02-3.5-3.17-5.96-3.44M15 12c1.93 0 3.5-1.57 3.5-3.5S16.93 5 15 5c-.54 0-1.04.13-1.5.35c.63.89 1 1.98 1 3.15s-.37 2.26-1 3.15c.46.22.96.35 1.5.35"/></svg>
                <p>Employees</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Company/MyClients.php" class="<?php if ($current_page == 'MyClients.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 12q-1.65 0-2.825-1.175T8 8t1.175-2.825T12 4t2.825 1.175T16 8t-1.175 2.825T12 12m-8 8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13t3.25.388t3.15 1.162q.725.375 1.163 1.088T20 17.2V20zm2-2h12v-.8q0-.275-.137-.5t-.363-.35q-1.35-.675-2.725-1.012T12 15t-2.775.338T6.5 16.35q-.225.125-.363.35T6 17.2zm6-8q.825 0 1.413-.587T14 8t-.587-1.412T12 6t-1.412.588T10 8t.588 1.413T12 10m0 8"/></svg>
                <p>My Clients</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Company/Timetable.php" class="<?php if ($current_page == 'Timetable.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd"
                d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
                <p>Timetable</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Company/History.php" class="<?php if ($current_page == 'History.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21 11.11V5a2 2 0 0 0-2-2h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14a2 2 0 0 0 2 2h6.11c1.26 1.24 2.98 2 4.89 2c3.87 0 7-3.13 7-7c0-1.91-.76-3.63-2-4.89M12 3c.55 0 1 .45 1 1s-.45 1-1 1s-1-.45-1-1s.45-1 1-1M5 19V5h2v2h10V5h2v4.68c-.91-.43-1.92-.68-3-.68H7v2h4.1c-.6.57-1.06 1.25-1.42 2H7v2h2.08c-.05.33-.08.66-.08 1c0 1.08.25 2.09.68 3zm11 2c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5m.5-4.75l2.86 1.69l-.75 1.22L15 17v-5h1.5z"/></svg>
                <p>History</p>
            </a>
        </div>
    </nav>

<?php endif;?>

    <!-- Top Nav -->
    <div class="top_nav">
        <div id="logo">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
                d="M4 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m0 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m1 5a1 1 0 1 0 0 2h14a1 1 0 1 0 0-2z"/></svg>
            <h2>Logo</h2>
        </div>
        <div id="profile">
            <img src="<?php echo $profile_pic; ?>" alt='pic' height="40px" width="40px" class="profile">

            <a href="profile.php">My Account</a>
        </div>
    </div>

    <script>
        const logo = document.getElementById("logo");
        const sidebar = document.getElementById("side_nav");

        logo.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    </script>
