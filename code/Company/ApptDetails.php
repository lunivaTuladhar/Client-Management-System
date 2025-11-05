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
    #button {
        display: flex;
        width: 200px;
        margin-left: auto;   /* pushes it to the right */
        align-items: center; /* vertical centering */
        justify-content: center; /* horizontal centering inside button */
        gap: 12px;
        background-color: #F5F3F3;
    }
    #approve{
        background-color: rgba(14,194,14,0.2);
        border: 2px rgba(14,194,14,0.9) solid;
        color:rgba(14,194,14,0.9);
    }
    #approve:hover{
        background-color: rgba(14,194,14,0.4);
    }
    #decline{
        background-color: rgba(239,24,24,0.2);
        border: 2px rgba(239,24,24,0.9) solid;
        color:rgba(239,24,24,0.9);
    }
    #decline:hover{
        background-color: rgba(239,24,24,0.4);
    }
</style>
<body>
    <?php include ("../fixed/sidebar.php") ?>

<div id="container">
    <div id="whole">
        <div id="top">
        <a href="Appointment.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
        <h3>Appointments</h3>
        </div>
        <div id="content">
            <div class="appt_details"><label>Name:</label><p>Client's name</p></div>
            <div class="appt_details"><label>Date:</label><p>Date</p></div>
            <div class="appt_details"><label>Time:</label><p>Time</p></div>
            <div class="appt_details"><label>With:</label><p>With name</p></div>
            <div class="appt_details"><label>Reason:</label><p>Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb</p></div>

            <div id="button">
            <button id="approve" onclick="approve()">Approve</button>
            <button id="decline" onclick="decline()">Decline</button>
            </div>

        </div>
    </div>
</div>
<script>
function decline() {
    
            let reason = prompt("Please enter the reason for declining:");
            if (reason) {
                alert("Appointment declined for reason: " + reason);
            } else if (reason === "") {
                alert("Decline reason cannot be empty.");
            } else {
                alert("Decline canceled.");
            }
        }
</script>
</body>
</html>