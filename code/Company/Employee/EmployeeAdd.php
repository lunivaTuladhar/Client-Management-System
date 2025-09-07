<html>
<head>
    <title>Add Employee Form</title>
    <link rel="stylesheet" href="../../style/form.css">
    <link rel="stylesheet" href="../../style/button.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            border: 1px solid black;
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }

        h3 {
            margin-bottom: 20px;
        }

        #add_emp {
            display: flex;
            gap: 3%;
        }

        /* Labels all same height */
        label {
            display: inline-block;
            height: 24px;
            line-height: 24px;
            margin-bottom: 4px;
            font-weight: 500;
        }

        input, select {
            width: 95%;
            padding: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 12px;
        }

        /* Role row with plus button */
        .role-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-right:15px
        }

        .role-row button {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #2563eb; /* blue */
            border-radius: 20%;
            cursor: pointer;
        }
        button{
            cursor:hand;
        }
        .role-row button svg {
            width: 16px;
            height: 16px;
            fill: white;
        }
    </style>
</head>
<body>
    <form align="center">
        <h3>Add Employee</h3><br>
        <div id="add_emp">
            <!-- Left Column -->
            <div align="left" style="width:50%">
                <label>Name:</label><br>
                <input type="text" placeholder="enter employee name" required /><br>

                <label>Phone:</label><br>
                <input type="tel" placeholder="enter employee phone"required/><br>

                <div class="role-row">
                    <label>Role:</label>
                    <button type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="5 4 14 16">
                            <path d="M19 12.998h-6v6h-2v-6H5v-2h6v-6h2v6h6z"/>
                        </svg>
                    </button>
                </div>
                <select>
                    <option>admin</option>
                    <option>doctor</option>
                    <option>nurse</option>
                    <option>receptionist</option>
                    <option>boyx</option>
                </select>
            </div>

            <!-- Right Column -->
            <div align="left" style="width:50%">
                <label>Email:</label><br>
                <input type="email" placeholder="enter employee email"required/><br>

                <label>Address:</label><br>
                <input type="text" placeholder="enter employee address"required/><br>

                <label>Priority:</label><br>
                <select>
                    <option>high</option>
                    <option>medium</option>
                    <option>low</option>
                </select>
            </div>
        </div><br>
        <button type="submit">Add employee</button>
    </form>
</body>
</html>
