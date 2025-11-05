<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <title>EmployeeDetails</title>
    <style>
        #whole_container{
            background-color: #F5F3F3;
            height: 100%;
            margin-top: 50px;
            margin-left: 50px;
            padding:24px 24px 24px 24px;
        }
        #container{
            border-radius:24px;
            
            padding:24px 24px 24px 24px;
        }
        .appt_details{
            
            background-color: #F5F3F3;
            display: flex;
            align-items: center;
        }
        label{

            background-color: #F5F3F3;
            color: rgb(14, 62, 217, 0.9);
        }
        p{
            background-color: #F5F3F3;

        }
        #container img{
            height:150px;
            width:150px;
            border-radius:50%;
            margin: 0 auto 12px auto;
        }
        #profilepic{
            display:flex;
        }
        #details{
            padding:12px;
            background-color: #F5F3F3;
            border-radius:12px;
        }
    </style>
</head>
<body>
        <?php include("../fixed/sidebar.php")?>

    <div id="whole_container">
        <div id="container">
            <div id="profilepic">
                <img src="#" alt="pic" >
            </div>
            
            <div id="details">
                <div class="appt_details"><label>Name:</label><p>client's name</p></div>
                <div class="appt_details"><label>Email:</label><p>Date</p></div>
                <div class="appt_details"><label>Type:</label><p>Time</p></div>
                <div class="appt_details"><label>Phone:</label><p>With name</p></div>
                <div class="appt_details"><label>Address:</label><p>With name</p></div>
                <div class="appt_details"><label>Recent appointment:</label><p>With name</p></div>
            </div>
        </div>
    </div>
</body>
</html>