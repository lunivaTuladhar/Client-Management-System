    <style>
        /* Sidebar */
        *{
            background-color:white;
        }
        #side_nav {
            padding: 8px;
            border: 1px solid black;
            width: 200px;
            height: 96vh;
            top: 50px;
            position: fixed;
            transition: width 0s ease;
            overflow: hidden;
        }

        /* Collapsed sidebar */
        #side_nav.collapsed {
            width: 40px;
        }

        .menu_item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 12px;
        }

        .menu_item p {
            margin: 0;
            font-size: 14px;
            transition: opacity 0s ease;
        }

        /* Hide text when collapsed */
        /* Hide only text when collapsed */
#side_nav.collapsed .menu_item p {
    display: none;
}

 .collapsed ~ .main_content {
            margin-left: 40px;
        }
        /* Top nav */
        .top_nav {
            display: flex;
            justify-content: space-between;
            border: 1px solid black;
            height: 50px;
            width: 100%;
            position: fixed;
            top: 0;
            background: #fff;
        }

        .top_nav h2 {
            margin: 0px 0 0 5px;
        }

        .top_nav a {
            text-decoration: none;
            color: black;
        }

        #profile {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-right: 10px;
        }

        #logo {
            display: flex;
            align-items: center;
            cursor: pointer; /* clickable */
            gap: 8px;
            margin-left: 14px;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
    </style>


    <!-- Sidebar (starts collapsed) -->
    <nav id="side_nav" class="collapsed">
        <div class="menu_item">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
            d="M11.336 2.253a1 1 0 0 1 1.328 0l9 8a1 1 0 0 1-1.328 1.494L20 11.45V19a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7.55l-.336.297a1 1 0 0 1-1.328-1.494zM6 9.67V19h3v-5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5h3V9.671l-6-5.333zM13 19v-4h-2v4z"/></svg>
            <p>Dashboard</p>
        </div>
        <div class="menu_item">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
            d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
            <p>Browse Organization</p>
        </div>
        <div class="menu_item">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd"
            d="M8 4h8V2h2v2h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V2h2zM5 8v12h14V8zm2 3h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zm0 4h2v2h-2zm-4 0h2v2h-2zm-4 0h2v2H7z"/></svg>
            <p>My Appointments</p>
        </div>
        <div class="menu_item">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
            d="M9 17H7v-7h2zm4 0h-2V7h2zm4 0h-2v-4h2zm2 2H5V5h14v14.1M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2"/></svg>
            <p>My History</p>
        </div>
    </nav>

    <!-- Top Nav -->
    <div class="top_nav">
        <div id="logo">
            <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor"
                d="M4 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m0 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1m1 5a1 1 0 1 0 0 2h14a1 1 0 1 0 0-2z"/></svg>
            <h2>Logo</h2>
        </div>
        <div id="profile">
            <img src="#" alt="#" width="24" height="24">
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
