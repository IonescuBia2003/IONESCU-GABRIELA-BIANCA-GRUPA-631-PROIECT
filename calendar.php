<?php
session_start();
$host = 'localhost';
$dbname = 'vet_clinic';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexiunea a eșuat: " . $e->getMessage());
}

$stmt = $conn->prepare("SELECT users.username, appointments.pet_name, appointments.owner_name, appointments.date_time, appointments.notes FROM appointments JOIN users ON appointments.user_id = users.id ORDER BY date_time ASC");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getCurrentMonthYear() {
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    return [$month, $year];
}
list($currentMonth, $currentYear) = getCurrentMonthYear();
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar Programări</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        .calendar-header {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            background-color: #f1f1f1;
            border-bottom: 2px solid #ccc;
        }
        .calendar-header div {
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            grid-template-rows: repeat(24, 1fr);
            gap: 1px;
            background-color: #ccc;
        }
        .time-slot {
            background-color: #f9f9f9;
            text-align: center;
            padding: 10px 5px;
            font-size: 12px;
        }
        .day-slot {
            background-color: #fff;
            padding: 5px;
            min-height: 50px;
            border: 1px solid #ddd;
            position: relative;
        }
        .appointment {
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            background-color: #4CAF50;
            color: white;
            padding: 5px;
            border-radius: 3px;
            font-size: 12px;
        }
        .month-year-selector {
            text-align: center;
            margin: 20px 0;
        }
        .month-year-selector select {
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Calendar Programări - Toți Utilizatorii</h2>
        <div class="month-year-selector">
            <form method="GET" action="">
                <select name="month">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year">
                    <?php for ($y = 2020; $y <= 2030; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit">Afișează</button>
            </form>
        </div>
        <div class="calendar-header">
            <div>Ora</div>
            <?php
            $daysOfWeek = ["Duminică", "Luni", "Marți", "Miercuri", "Joi", "Vineri", "Sâmbătă"];
            foreach ($daysOfWeek as $day) {
                echo "<div>$day</div>";
            }
            ?>
        </div>
        <div class="calendar-grid">
            <?php
            for ($hour = 0; $hour < 24; $hour++) {
                echo "<div class='time-slot'>" . str_pad($hour, 2, "0", STR_PAD_LEFT) . ":00</div>";
                for ($day = 0; $day < 7; $day++) {
                    echo "<div class='day-slot'>";
                    foreach ($appointments as $appointment) {
                        $appointmentDate = strtotime($appointment['date_time']);
                        $appointmentDay = date("w", $appointmentDate);
                        $appointmentHour = date("G", $appointmentDate);
                        $appointmentMonth = date("n", $appointmentDate);
                        $appointmentYear = date("Y", $appointmentDate);
                        if ($appointmentDay == $day && $appointmentHour == $hour && $appointmentMonth == $currentMonth && $appointmentYear == $currentYear) {
                            echo "<div class='appointment'>";
                            echo htmlspecialchars($appointment['pet_name']) . " - " . htmlspecialchars($appointment['owner_name']) . "<br>";
                            echo "Note: " . htmlspecialchars($appointment['notes']);
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

