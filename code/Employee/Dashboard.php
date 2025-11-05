
<?php
session_start();
?>
<!DOCTYPE html>
<html >
<head>
        <link rel="stylesheet" href="../style/button.css">
        <link rel="stylesheet" href="../style/Heading.css">

    <title>Dashboard</title>
    <style>
        #whole_container{
            display:flex;
            gap:12px;
            background-color:#F5F3F3;
            margin-top: 12px;
        }
        #right_container{
            background-color:#F5F3F3;
            width: 500px;
            margin-right:12px;
            margin-top:12px;
        }
        #left_container{
            height: 90vh;
            padding: 24 0 0 0;
            background-color:#F5F3F3;
            
        }
        .profile{
            border-radius:50%;
            margin-top:1%;
            background-size: cover;
            background-repeat: no-repeat;
        }
        #welcome{
            background-color:white;
            width: 700px;
            margin-left: 70px;
            margin-top: 24px;
            display:flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px 8px 12px;
            border-radius: 12px;
            height: 50px;
        }
        #welcome h2{
          font-weight:bold;
        }
        #timetable{
            margin-left: 70px;
            padding: 8px 12px 8px 12px;
            width: 700px;
            margin-top:12px;
            border-radius:12px;
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
            width: 12px;
        }
        thead th:last-child{
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .add{
          margin-left:24px;
          width:210px;
          
        }
        .right-bottom{
          margin-top:12px;
          height:300px;
          padding:12px;
          border-radius: 12px;
          
        }
        .right-top{
          margin-top:12px;
          height:300px;
          padding:12px;
          border-radius: 12px;
          
        }
        .right-bottom h3{
          margin: 0px;
          margin-bottom: 12px;
        }
        .right-top h3{
          margin: 0px;
          margin-bottom: 12px;
        }
        .mycal-container {
          width: 400px;
          border: 1px solid #ccc;
          border-radius: 8px;
          padding: 10px;
        }
        .mycal-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 5px;
          font-size:1rem;
          vertical-align: middle;
        }
        .mycal-btn {
          cursor: pointer;
          background: none;
          color:black;
          border: none;
          font-size: 16px;
        }
        .mycal-days, .mycal-weekdays {
          display: grid;
          grid-template-columns: repeat(7, 1fr);
          text-align: center;
        }
        .mycal-weekdays div {
          font-weight: bold;
          color: #555;
        }
        .mycal-day {
          padding: 6px;
          cursor: pointer;
          border-radius: 4px;
        }
        .mycal-day:hover {
          background: #e0e7ff;
        }
        .mycal-today {
          background: #dbeafe;
          color: #2563eb;
          font-weight: bold;
        }
        #cal-indicator{
          display:flex; 
          gap:12px;
        }
    </style>
</head>

<body>

        <?php include ("../fixed/sidebar.php") ?>
    <div id="whole_container">
<div id="left_container">
    <div id="welcome">
        <h2>WELCOME, NAME</h2>
        <img src="#" alt="Pic" height="40px"width="40px" class="profile">
    </div>

    <div id="timetable">
        <h3>Timetable</h3>
        <table width=100%>

        <thead >
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Available</th>
        </thead>

        <tr>
            <td>1</td>
            <td>employee name</td>
            <td>9888888888</td>
            <td>employee</td>
            <td>9:00-10:00</td>
        </tr>
    </table>
    </div>

</div>

<div id="right_container">

    <!-- timetable div -->

<div class="right-top">
  <h3>Calender</h3>
    <div class="mycal-container">
      <div class="mycal-header">
        <button class="mycal-btn" id="mycal-prev">◀</button>
        <div id="cal-indicator"><span id="mycal-month"></span> <span id="mycal-year"></span></div>
        <button class="mycal-btn" id="mycal-next">▶</button>
      </div>
      <div class="mycal-weekdays">
        <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
      </div>
      <div class="mycal-days" id="mycal-days"></div>
    </div>

    <script>
    (function(){
      const monthNames=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
      let viewDate=new Date();

      const monthEl=document.getElementById('mycal-month');
      const yearEl=document.getElementById('mycal-year');
      const daysEl=document.getElementById('mycal-days');
      const prev=document.getElementById('mycal-prev');
      const next=document.getElementById('mycal-next');

      function render(){
        const year=viewDate.getFullYear();
        const month=viewDate.getMonth();
        monthEl.textContent=monthNames[month];
        yearEl.textContent=year;
        daysEl.innerHTML='';
        const first=new Date(year,month,1);
        const start=first.getDay();
        const days=new Date(year,month+1,0).getDate();
        for(let i=0;i<start;i++){
          daysEl.appendChild(document.createElement('div'));
        }
        const today=new Date();
        for(let d=1;d<=days;d++){
          const cell=document.createElement('div');
          cell.textContent=d;
          cell.className='mycal-day';
          const thisDate=new Date(year,month,d);
          if(thisDate.toDateString()===today.toDateString()){
            cell.classList.add('mycal-today');
          }
          cell.onclick=()=>location.href='otherpage.php?date='+thisDate.toISOString().split('T')[0];
          daysEl.appendChild(cell);
        }
      }

      prev.onclick=()=>{viewDate.setMonth(viewDate.getMonth()-1);render();};
      next.onclick=()=>{viewDate.setMonth(viewDate.getMonth()+1);render();};
      render();
    })();
    </script>
</div>
  
<div class="right-bottom">
  <h3>Timetable</h3>
    <table style="width:100%">
        <thead>
            <th>id</th>
            <th>name</th>
            <th>time</th>
            <th>details</th>
        </thead>
            <tr>
                <td>1</td>
                <td>Luniva tuladhar</td>
                <td>9:00am</td>
                <td>view details</td>
            </tr>
    </table>
  </div>
</div>

</div>

</body>
</html>