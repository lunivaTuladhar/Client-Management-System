<?php
include('../db.php');
header('Content-Type: application/json');

if (!isset($_GET['emp_id']) || !isset($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$empId = intval($_GET['emp_id']);
$date = $_GET['date'];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) { echo json_encode([]); exit; }

$dayName = date('D', strtotime($date)); // Mon, Tue, Wed...
$dayColumn = $dayName;

// Fetch employee shift for that day
$sql = "SELECT ts.Start_Time, ts.End_Time
        FROM timetable t
        INNER JOIN time_stamp ts ON t.Time_ID = ts.Time_ID
        WHERE t.Emp_ID = ? AND t.$dayColumn = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $empId);
$stmt->execute();
$res = $stmt->get_result();

$shifts = [];
while ($row = $res->fetch_assoc()) {
    $shifts[] = [
        "start" => substr($row['Start_Time'],0,5),
        "end" => substr($row['End_Time'],0,5)
    ];
}

if (empty($shifts)) { echo json_encode([]); exit; }

// Create 1-hour slots
$slots = [];
foreach ($shifts as $s) {
    $start = new DateTime("$date {$s['start']}");
    $end   = new DateTime("$date {$s['end']}");
    $cursor = clone $start;
    while (true) {
        $slotEnd = (clone $cursor)->modify('+1 hour');
        if ($slotEnd > $end) break;
        $slots[] = [
            "start" => $cursor->format("H:i"),
            "end" => $slotEnd->format("H:i")
        ];
        $cursor->modify('+1 hour');
    }
}

// Fetch already booked times
$bookedStmt = $conn->prepare("SELECT Time FROM book_appt WHERE Emp_ID=? AND Date=?");
$bookedStmt->bind_param("is",$empId,$date);
$bookedStmt->execute();
$bRes = $bookedStmt->get_result();
$booked = [];
while($b=$bRes->fetch_assoc()){ $booked[] = substr($b['Time'],0,5); }

// Filter available slots
$available = [];
foreach($slots as $s){
    if (!in_array($s['start'],$booked)) $available[] = $s;
}

echo json_encode($available);
?>
