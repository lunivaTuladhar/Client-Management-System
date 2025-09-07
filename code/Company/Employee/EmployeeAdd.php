<html>
<head>
    <title>Add Employee</title>
    <link rel="stylesheet" href="../../style/form.css">
    <link rel="stylesheet" href="../../style/button.css">
</head>

<style>
    #add_emp{
        display:flex;

    }
</style>
<body>
    <form align="center" style="border:1px black solid;">
        <h2>Add Employee</h2>
        <div id="add_emp">
        <div>
            Name: <br>
            <input type="text" placeholder="enter employee name"/><br>
            Phone: <br>
            <input type="tel" placeholder="enter employee phone"/><br>
            Role: <br>
            <select>
                <option>admin</option>
                <option>doctor</option>
                <option>Nurse</option>
                <option>receptionist</option>
                <option>boyx</option>
            </select>
        </div>
    
        <div>
            Email: <br>
            <input type="email" placeholder="enter employee email"/><br>
            Address: <br>
            <input type="text" placeholder="enter employee address"/><br>
            Priority: <br>
             <select>
                <option>high</option>
                <option>medium</option>
                <option>low</option>
            </select>
        </div>
        </div>

    </form>
</body>
</html>