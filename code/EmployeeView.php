<html>
<head>
    <title>Employee View</title>
        <link rel="stylesheet" href="button.css">

</head>
<style>
    
    button{
        width: 164px;
    }
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
    #topic{
        margin-top:2px;
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
         
        padding:12px;
        text-align:left;
    }
    tr{
        height:32px;
    }

    thead th:first-child{
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    thead th:last-child{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    </style>

<body >
    <div id="container"style="">

        <?php 
include('fixed/sidebar.php');?>
<div id="content">
    <div id="top">

        <div id="topic">
            <h3 style="color:2D2D2D ">Employees</h3>
        </div>
        <div>
            <button> Add Employee</button>
        </div>
    </div>

    <table width="100%">
        <thead >
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Available</th>
            <th>More</th>
        </thead>
        <tr>
            <td>apple</td>
            <td>apple</td>
            <td>apple</td>
            <td>apple</td>
            <td>apple</td>
            <td>apple</td>
            <td>apple</td>
            
        </tr>
    </table>

</div>
</div>
</body>
</html>