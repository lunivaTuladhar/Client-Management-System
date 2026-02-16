<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("location:../login/log in.php");
    exit();
}

$clientId = $_SESSION['user_id'];
$companyId = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

// Fetch client details
$client_query = $conn->prepare("SELECT Name, Phone FROM client WHERE Client_ID = ?");
$client_query->bind_param("i", $clientId);
$client_query->execute();
$client_result = $client_query->get_result();
$client = $client_result->fetch_assoc();

// Fetch employees for company
$employee_query = $conn->prepare("SELECT Emp_ID, Name FROM employee WHERE Company_ID = ? and Isadmin =0");
$employee_query->bind_param("i", $companyId);
$employee_query->execute();
$employees = $employee_query->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId = $_POST['employee'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    if ($date < date('Y-m-d')) {
        echo "<script>alert('You cannot book an appointment for a past date!');</script>";
        exit();
    }

    $insert = $conn->prepare("INSERT INTO book_appt (Client_ID, Company_ID, Emp_ID, Date, Time, Reason, Status) 
                              VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $insert->bind_param("iiisss", $clientId, $companyId, $empId, $date, $time, $reason);

    if ($insert->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='../Client/Dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error booking appointment. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment</title>
<link rel="stylesheet" href="../style/form.css">
<link rel="stylesheet" href="../style/button.css">
<style>body {
        background-color: #f0f2f5; /* Light background for a modern look */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Clearer font */
        margin: 0;
        padding: 20px;
        display: flex; /* Use flex to center the container */
        justify-content: center;
        align-items: flex-start; /* Align to the top of the viewport */
        min-height: 100vh;
    }
    
    /* Form Container Styling */
    .form-container {
        width: 100%;
        max-width: 700px; /* Max width to prevent it from getting too wide on large screens */
        margin: 40px 0;
        background: #ffffff;
        padding: 30px; /* Increased padding */
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1); /* Slightly stronger shadow */
        border: 1px solid #e0e0e0; /* Subtle border */
    }
    
    /* Heading Style */
    h2 {
        text-align: center;
        color: #0E3ED9;
        margin-bottom: 25px; /* Added margin below the title */
        font-size: 1.8rem;
        border-bottom: 2px solid #0E3ED9;
        display: inline-block;
        padding-bottom: 5px;
        width: auto;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    /* Flexbox for Form Details (Name/Contact, Date/Time/Employee/Reason) */
    #form-details {
        display: flex;
        gap: 20px; /* Space between the two main columns */
        margin-bottom: 20px;
    }
    
    #form-details > div {
        flex: 1; /* Make both columns take equal space */
    }

    /* Label Styling */
    label {
        font-weight: 600; /* Slightly bolder */
        margin-top: 15px; /* Increased margin-top for better spacing */
        display: block;
        color: #333;
        font-size: 0.95rem;
    }
    
    /* Input, Select, Textarea Styling */
    input, select, textarea {
        width: 100%;
        padding: 12px; /* Slightly more padding */
        border: 1px solid #ccc;
        border-radius: 8px; /* More rounded corners */
        margin-top: 5px;
        box-sizing: border-box; /* Include padding/border in the element's total width and height */
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #0E3ED9; /* Highlight border on focus */
        box-shadow: 0 0 5px rgba(14, 62, 217, 0.3);
        outline: none; /* Remove default focus outline */
    }

    /* Readonly Input Specific Styling */
    input[readonly] {
        background-color: #e9ecef; /* Different background for readonly fields */
        color: #555;
        cursor: default;
    }
    
    textarea {
        resize: vertical; /* Allow vertical resizing, but not horizontal */
        min-height: 80px;
        height: auto;
    }
    
    /* Button Container */
    .btn-container {
        margin-top: 30px;
        display: flex;
        justify-content: space-between; /* Space out the buttons */
        gap: 20px;
    }
    
    /* General Button Styling */
    .btn {
        flex: 1; /* Make buttons take equal space */
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: background-color 0.3s, transform 0.1s;
    }
    
    /* Submit Button */
    .submit-btn {
        background-color: #0E3ED9;
        color: white;
    }
    
    .submit-btn:hover { 
        background-color: #0b33b8; 
        transform: translateY(-2px); /* Slight lift on hover */
    }
    
    /* Cancel Button */
    .cancel-btn {
        background-color: #6c757d; /* A professional grey */
        color: white;
    }
    
    .cancel-btn:hover { 
        background-color: #5a6268; 
        transform: translateY(-2px);
    }

    /* Media Query for Responsiveness on smaller screens (e.g., mobile) */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
            margin: 20px auto;
        }
        
        #form-details {
            flex-direction: column; /* Stack columns vertically on small screens */
            gap: 0;
        }
        
        .btn-container {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            width: 100%; /* Full width buttons on mobile */
        }
    }</style>
</head>
<body>

<div class="form-container">
<h2>Book Appointment</h2>
<form method="POST">
<div id="form-details">
<div>
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($client['Name']); ?>" readonly>
    
    <label>Date</label>
    <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>

    <label>Employee</label>
    <select name="employee" id="employee" required onchange="fetchTimeSlots(this.value)">
        <option value="">Select Employee</option>
        <?php while ($row = $employees->fetch_assoc()) { ?>
            <option value="<?php echo $row['Emp_ID']; ?>"><?php echo htmlspecialchars($row['Name']); ?></option>
        <?php } ?>
    </select>
</div>
<div>
    <label>Contact</label>
    <input type="text" name="contact" value="<?php echo htmlspecialchars($client['Phone']); ?>" readonly>

    <label>Time</label>
    <select name="time" id="time" required>
        <option value="">Select time</option>
    </select>

    <label>Reason (optional)</label>
    <textarea name="reason" placeholder="Enter reason..."></textarea>
</div>
</div>
<div class="btn-container">
    <button type="submit" class="btn submit-btn">Submit</button>
    <button type="button" class="btn cancel-btn" onclick="window.history.back()">Cancel</button>
</div>
</form>
</div>

<script>
function fetchTimeSlots(empId) {
    const date = document.getElementById('date').value;
    const timeSelect = document.getElementById('time');
    timeSelect.innerHTML = '<option value="">Select time</option>';
    if (!empId || !date) return;

    fetch(`get_time_slots.php?emp_id=${empId}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                const opt = document.createElement('option');
                opt.textContent = 'No available slots';
                timeSelect.appendChild(opt);
            } else {
                data.forEach(slot => {
                    const opt = document.createElement('option');
                    opt.value = slot.start;
                    opt.textContent = `${slot.start} - ${slot.end}`;
                    timeSelect.appendChild(opt);
                });
            }
        });
}

document.getElementById('date').addEventListener('change', () => {
    const emp = document.getElementById('employee').value;
    if (emp) fetchTimeSlots(emp);
});
</script>

</body>
</html>
