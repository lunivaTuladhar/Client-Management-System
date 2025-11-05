<html>
<head>
    <title>Employee View</title>
        <link rel="stylesheet" href="../style/button.css">
        <link rel="stylesheet" href="../style/form.css">
        <link rel="stylesheet" href="../style/Heading.css">

<style>
    
    #content{
        padding: 12px;
        margin-left:74px;
        border-radius:12px;
        background-color: white ;
        margin-right: 14;
        
    }
    #top{
        display:flex;
        justify-content: space-between;
        margin-bottom:12px;
        align-items: center;
    }
    #top-right{
        width: auto;
        display:flex;
        align-items: center;
        gap:12px;
    }
    input{
        height: 32px;
        width: 200px;
        margin:0px;
    }
    h3{
        color: #2d2d2dff;
        margin:0;
        vertical-align:middle;
    }
    #container{
        background-color:#F5F3F3;
        height: 86vh;
        padding: 74 0 0 0;
        
        
    }
    table{
        background-color:#F5F3F3;
        padding:10px;
        border-radius: 12px;
    }
    th td{
        border:1px solid blue;
    }
    td{
        padding:12px;
        background-color:#F5F3F3;
    }
    th{
        background-color: rgb(14,62,217,0.2);
        padding:12px;
        text-align:left;
        color: rgb(14,62,217,0.9);
    }
    tr{
        height:32px;
    }
    thead th:first-child{
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width:350px;
    }
    thead th:last-child{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    #role_select{
        background-color:#ffffff;
        border: 1px solid rgba(45,45,45,0.2);
        color: rgba(45,45,45,0.9);
        width:150px;
        height: 32px;
        margin: 0;
    }
    #top-right button{
        width:150px;
    }

    </style>
</head>

<body >
    <div id="container"style="">
        <?php include('../fixed/sidebar.php');?>
        <div id="content">

            <div id="top">
                <h3 style="color:2D2D2D ">Timetable</h3>
            </div>

            <table width="100%">
                <thead >
                    <th>Days</th>
                    <th>Start</th>
                    <th>End</th>
                </thead>
                <tr>
                    <td>Sunday</td>
                    <td>9:00</td>
                    <td>9:00</td>
                    
                </tr>
            </table>
        </div>
    </div>
</body>
</html>