<!DOCTYPE html>
<html lang="en">
<head>
    <title>Emp ApptDetails</title>
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
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }
    .popup-overlay.show {
        display: flex;
    }

    /* Popup Box */
    .popup-box {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 350px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        text-align: center;
        animation: fadeIn 0.3s ease;
    }
    .popup-box h4 {
        margin-bottom: 10px;
    }
    .popup-box textarea {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px;
        font-size: 14px;
        resize: none;
        outline: none;
        margin-bottom: 15px;
    }

    /* Buttons */
    .popup-buttons {
        display: flex;
        justify-content: space-between;
        gap:24px;
    }
    .submit-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
        
    }
    .back-btn{
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
        background-color: rgba(14, 62, 217, 0.2);
        border: 2px solid rgba(14, 62, 217, 0.9);
        color:rgba(14, 62, 217, 0.9);
    
    }
    @keyframes fadeIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
<body>
    <?php include ("../fixed/sidebar.php") ?>

<div id="container">
    <div id="whole">
        <div id="top">
        <a href="OwnAppt.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
        <h3>Appointments</h3>
        </div>
        <div id="content">
            <div class="appt_details"><label>Name:</label><p>Client's name</p></div>
            <div class="appt_details"><label>Date:</label><p>Date</p></div>
            <div class="appt_details"><label>Time:</label><p>Time</p></div>
            <div class="appt_details"><label>Reason:</label><p>Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb Reason sddnenejd kejnejndje kjdejdf ekjbdjkef kjbfjef kfkjfksdfkjhfenf kjbfjdfkjdbkjfuofhqof fb</p></div>

            <div id="button">
            <button id="openReasonPopupBtn" class="open-btn">Request For Reassignment</button>
            </div>
            <!-- Popup Overlay -->
            <div class="popup-overlay" id="reasonPopupOverlay">
                <div class="popup-box">
                    <h4>Reason for reassignment</h4>
                    <textarea id="reasonInput" placeholder="Type your reason here..." rows="4"></textarea>
                    <div class="popup-buttons">
                        <button class="submit-btn" id="submitReasonBtn">Submit</button>
                        <button class="back-btn" id="closeReasonPopupBtn">Cancel</button>
                    </div>
                </div>
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
    const reasonPopupOverlay = document.getElementById('reasonPopupOverlay');
    const openReasonPopupBtn = document.getElementById('openReasonPopupBtn');
    const closeReasonPopupBtn = document.getElementById('closeReasonPopupBtn');
    const submitReasonBtn = document.getElementById('submitReasonBtn');
    const reasonInput = document.getElementById('reasonInput');

    // Show popup
    openReasonPopupBtn.addEventListener('click', () => {
        reasonPopupOverlay.classList.add('show');
        reasonInput.focus();
    });

    // Close popup
    closeReasonPopupBtn.addEventListener('click', () => {
        reasonPopupOverlay.classList.remove('show');
        reasonInput.value = '';
    });

    // Close when clicking outside box
    reasonPopupOverlay.addEventListener('click', (e) => {
        if (e.target === reasonPopupOverlay) {
            reasonPopupOverlay.classList.remove('show');
            reasonInput.value = '';
        }
    });

    // Handle submit
    submitReasonBtn.addEventListener('click', () => {
        const reason = reasonInput.value.trim();
        if (reason === '') {
            alert('Please enter your reason before submitting.');
            reasonInput.focus();
            return;
        }

        // Example: You can send it to PHP via GET or POST here
        alert('Your request has been submitted.\nReason: ' + reason);

        // Reset and close
        reasonInput.value = '';
        reasonPopupOverlay.classList.remove('show');
    });
</script>
</body>
</html>