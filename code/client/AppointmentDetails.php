<!DOCTYPE html>
<html lang="en">
<head>
    <title>ApptDetails</title>
    <link rel="stylesheet" href="../style/Heading.css">
    <link rel="stylesheet" href="../style/button.css">

</head>
<style>
    #whole{
        padding: 12px;
        margin-left:70px;
        margin-right: 12px;
        margin-top:12px;
        border-radius:12px;
        background-color: white ;
        
    }
    #top{
        display:flex;
        gap: 12px;
        align-items:center; 
    }
    #container{
        padding-top:4px;
        background-color:#F5F3F3;
        height: 90vh;
        padding: 74 0 0 0;
        margin-top:50px;
    }
    #whole h3{
        margin:0;
        margin-bottom: 12px;
        vertical-align:middle;
    }
    .appt_details{
        display:flex;
        align-items:center;
        gap: 12px;
        background-color:#F5F3F3;
        padding:8 0 0 0;
    }
    #content{
        background-color:#F5F3F3;
        padding: 0px 12px 12px 12px;
        align: right;
        gap: 12px;
        border-radius: 12px;
    }
    label{
        background-color:#F5F3F3;
        color:rgba(14, 62, 217, 0.9);
    }
    p{
        width:100%;
        padding: 4px;
    }

</style>
<body>
    <?php include ("../fixed/sidebar.php") ?>

<div id="container">
    <div id="whole">
        <div id="top">
        <a href="MyAppointment.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
        <h3>Appointments</h3>
        </div>
        <div id="content">
            <div class="appt_details"><label>Company:</label><p>Company's name</p></div>
            <div class="appt_details"><label>Date:</label><p>Date</p></div>
            <div class="appt_details"><label>Time:</label><p>Time</p></div>
            <div class="appt_details"><label>With:</label><p>With name</p></div>
            <div class="appt_details"><label>Status:</label><p>Confirmed</p></div>
            <div class="appt_details"><label>Reason:</label><p>Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb</p></div>
        </div>
    </div>
</div>
</body>
</html>