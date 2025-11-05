<html>
<head>
    <title>View own appointment</title>
        <link rel="stylesheet" href="../style/button.css">
        <link rel="stylesheet" href="../style/Heading.css">

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
        background-color: rgb(14,62,217,0.2);
        padding:12px;
        text-align:left;
        color: rgb(14,62,217,0.9);
        width:100px;
    }
    tr{
        height:32px;
    }
    thead th:first-child{
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        width: 12px;
    }
    thead th:last-child{
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    #topic{
        display:flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    #top-div{
            margin-top:30px;
            display:flex;
            background:none;
            border-radius:25px;
    }
    .toggle-container {
            display: flex;
            border: 1px solid #ccc;
            border-radius: 35px;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        .toggle-option {
          padding: 8px 0px;
          border: none;
          background: none;
          cursor: pointer;
          outline: none;
          color: #555;
          transition: all 0.3s ease;
          width:70px;
          height:32px;
        }

        .toggle-option:not(:last-child) {
          border-right: 1px solid #ddd;
        }

        .toggle-option.active {
          background-color: #dbe3ff;
          color: #2563eb;
          border-radius: 20px;
        } 
    
    </style>

<body>
    <?php include ("../fixed/sidebar.php") ?>
<div id="container">

<div id="content">

<div id="top">
    <div id="topic">
        <h3 style="color:2D2D2D ">Appointments</h3>
                <div class="toggle-container">
                    <button class="toggle-option active" onclick="setActive(this)" style="font-size:.7rem">All</button>
                    <button class="toggle-option" onclick="setActive(this)"style="font-size:.7rem">Approved</button>
                    <button class="toggle-option" onclick="setActive(this)"style="font-size:.7rem">Requests</button>
                </div>

                <script>
                    function setActive(element) {
                        const buttons = document.querySelectorAll('.toggle-option');
                        buttons.forEach(btn => btn.classList.remove('active'));
                        element.classList.add('active');
                    }
                </script>
    </div>    
</div>

<script>
  function setActive(element) {
    const buttons = document.querySelectorAll('.toggle-option');
    buttons.forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
  }
</script>

    <table width="100%">
        <thead >
            <th>SN</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th></th>
        </thead>
        <tr>

            <td>1</td>
            <td>client name</td>
            <td>aug 12</td>
            <td>9;00</td>
            <td>confrimed</td>
            <td><a href="#">View Details</a></td>
            
        </tr>
    </table>

</div>
</div>
</body>
</html>