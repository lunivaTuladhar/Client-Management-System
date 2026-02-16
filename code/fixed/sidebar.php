<?php
// Get current file name
$current_page = basename($_SERVER['PHP_SELF']);
    if (!$_SESSION['user_type']) {
        header("location:../login/log in.php");
    }
    $user_type=$_SESSION['user_type'];

    include ("../db.php");
    // Logged-in user info
$emp_name = $_SESSION['name'] ;
$profile_pic = "";
if($user_type!="client"){
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $res = $conn->query("SELECT Name, Profile FROM employee WHERE Email='$email' LIMIT 1");
    if($res && $res->num_rows>0){
        $user = $res->fetch_assoc();
        $emp_name = ucfirst($user['Name']);
        $profile_pic = $user['Profile'] ? $user['Profile'] :"../images/default_company.png";
    }
}}
else{
    
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $res = $conn->query("SELECT Name, Profile FROM client WHERE Email='$email' LIMIT 1");
    if($res && $res->num_rows>0){
        $user = $res->fetch_assoc();
        $emp_name = ucfirst($user['Name']);
        $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_company.png";
    }
}
}
    ?>

    <style>
/* --- GLOBAL & RESET --- */
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #ffffff;
}

/* --- TOP NAVIGATION --- */
.top_nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 50px;
    width: 100%; /* Changed to 100% for full coverage */
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    background-color: rgba(14, 62, 217, 0.8); /* Slightly more opaque for readability */
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000; /* Ensure it stays above everything */
    padding: 0 20px;
    box-sizing: border-box;
}

.top_nav h2 {
    margin: 0;
    font-size: 18px;
    color: white;
}

#logo {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

#logo svg {
    color: white;
}

/* --- PROFILE DROPDOWN --- */
#profile {
    position: relative; /* Essential for absolute positioning of dropdown */
    display: flex;
    align-items: center;
    gap: 12px;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown_content {
    display: none; /* Hidden by default */
    position: absolute;
    right: 0;
    top: 100%;
    background-color: #ffffff;
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.15);
    border-radius: 8px;
    z-index: 1100;
    padding: 8px 0;
    margin-top: 10px;
}

/* Show the dropdown when hovering over the profile container */
#profile:hover .dropdown_content {
    display: block;
}

.dropdown_content a{
    padding: 10px 16px;
    text-decoration: none;
    display: block;
    font-size: 14px;
    transition: background-color 0.2s;
}
.top{
    color:rgba(14,62,217,0.9);
}
.top:hover{
    background-color: rgba(14,62,217,0.4);
}

.dropdown_content hr {
    border: 0;
    border-top: 1px solid rgba(0,0,0,0.05);
    margin: 4px 0;
}

.logout_link{
    color: rgba(239,24,24);
}
.logout_link:hover {
    background-color: rgba(239,24,24,0.4); 
}

/* Little arrow on top of dropdown */
.dropdown_content::before {
    content: "";
    position: absolute;
    top: -6px;
    right: 15px;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #ffffff;
}


/* --- SIDEBAR BASE --- */
#side_nav {
    position: fixed;
    top: 50px;
    left: 0;
    width: 220px; /* Fully expanded width */
    height: calc(100vh - 50px);
    border-right: 1px solid rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    padding: 15px 8px;
    overflow: hidden;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999;
    box-sizing: border-box;
}


#side_nav.collapsed {
    width: 55px;
}

#side_nav:not(.collapsed), 
#side_nav.collapsed:hover {
    width: 220px;
    
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
}

.menu_item {
    display: flex;
    align-items: center;
    border-radius: 8px;
    margin-bottom: 4px;
    transition: background-color 0.2s ease;
}

.menu_item:hover {
    background-color: rgba(14, 62, 217, 0.08);
}

.menu_item a {
    display: flex;
    align-items: center;
    padding: 10px;
    text-decoration: none;
    color: #444;
    width: 100%;
    overflow: hidden;
}

.menu_item svg {
    min-width: 20px;

    height: 20px;
    margin-right: 15px; 
    color: #555;
}


.menu_item p {
    margin: 0;
    font-size: 14px;
    white-space: nowrap;
    opacity: 1;
    transition: opacity 0.2s ease;
}

/* Hide text ONLY when sidebar is collapsed AND not being hovered */
#side_nav.collapsed:not(:hover) .menu_item p {
    opacity: 0;
    pointer-events: none;
}

/* Active Link Styling */
.menu_item a.active {
    background-color: rgba(14, 62, 217, 0.1);
    border-radius:12px;
}

.menu_item a.active svg,
.menu_item a.active p {
    color: #0e3ed9;
    font-weight: 600;
    
}

/* --- MAIN CONTENT ADJUSTMENT --- */
.main_content {
    padding: 20px;
    padding-top: 70px; /* Accounts for Top Nav */
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Push content when sidebar is permanently toggled open */
#side_nav:not(.collapsed) ~ .main_content {
    margin-left: 220px;
}

/* Bring content back when sidebar is collapsed */
#side_nav.collapsed ~ .main_content {
    margin-left: 60px;
}


/* Note: Content does NOT jump when hovering (standard UX) */
</style>


<!-- client nav bar -->
<?php
if ( $_SESSION['user_type'] == "client") :?>
    <!-- Sidebar (starts collapsed) -->
    <nav id="side_nav" class="collapsed">
        <div class="menu_item">
            <a href="../Client/Dashboard.php" class="<?php if ($current_page == 'Dashboard.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
            <p>Dashboard</p></a>
        </div>
        
        <div class="menu_item">
            <a href="../Client/list_company.php" class="<?php if ($current_page == 'list_company.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
            <p>Browse Organization</p></a>
        </div>

        <div class="menu_item">
            <a href="../Client/MyAppointment.php"class="<?php if ($current_page == 'MyAppointment.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
            <p>My Appointments</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Client/MyHistory.php"class="<?php if ($current_page == 'MyHistory.php') echo'active'; ?>">
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
            <a href="../Employee/Dashboard.php" class="<?php if ($current_page == 'Dashboard.php') echo'active'; ?>">
                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
                <p>Dashboard</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Employee/OwnAppt.php"class="<?php if ($current_page == 'OwnAppt.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 1024 1024"><path fill="currentColor" d="M928 224H768v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H548v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H328v-56c0-4.4-3.6-8-8-8h-56c-4.4 0-8 3.6-8 8v56H96c-17.7 0-32 14.3-32 32v576c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V256c0-17.7-14.3-32-32-32m-40 568H136V296h120v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h148v56c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8v-56h120zM416 496H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m0 136H232c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8m308.2-177.4L620.6 598.3l-52.8-73.1c-3-4.2-7.8-6.6-12.9-6.6H500c-6.5 0-10.3 7.4-6.5 12.7l114.1 158.2a15.9 15.9 0 0 0 25.8 0l165-228.7c3.8-5.3 0-12.7-6.5-12.7H737c-5-.1-9.8 2.4-12.8 6.5"/></svg>
                <p>Appointment</p>
            </a>
        </div>

        <div class="menu_item">
            <a href="../Employee/Timetable.php" class="<?php if ($current_page == 'Timetable.php') echo'active'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
                <p>Timetable</p>
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
            <a href="../Company/EmployeeList.php" class="<?php if ($current_page == 'EmployeeList.php') echo'active'; ?>">
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
        <div id="logo" >
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
                d="M4 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m0 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m1 5a1 1 0 1 0 0 2h14a1 1 0 1 0 0-2z"/></svg>
            <h2>CMS</h2>
        </div>
        <div id="profile">
    <img src="<?php echo $profile_pic; ?>" class="profile-pic"style="height:40px; width:40px; border-radius:50%;">
    <div class="dropdown">
        <a href="javascript:void(0)" id="account_btn" style="color:white;">My Account</a>
        <div class="dropdown_content" id="account_dropdown">
            <a href="profile.php" class="top">View Profile</a>
            <?php if($user_type=='client'):?>
            <a href="edit_profile.php" class="top">Edit Profile</a>
            <?php elseif($user_type=='admin'):?>
                <a href="../company/edit_profile.php" class="top">Edit Profile</a>
            <a href="edit_profile.php" class="top">Edit Profile</a>
            <?php else:?>
                <a href="../employee/edit_profile.php" class="top">Edit Profile</a>
            <?php endif;?>
            <hr>
            <a href="../login/logout.php" class="logout_link" onclick="return confirm('Are you sure you want to logout?')">Log Out</a>
        </div>
    </div>
</div>
    </div>

    <script>
        const logo = document.getElementById("logo");
        const sidebar = document.getElementById("side_nav");

        logo.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    </script>
