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

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if ($action == 'logout') {
    session_destroy();
    header("Location: app.php");
    exit();
}

if ($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user'] = $username;
        $_SESSION['user_id'] = $user['id'];

        header("Location: app.php?action=dashboard");
        exit();
    } else {
        $error = "Utilizator sau parolă incorectă.";
    }
}

if ($action == 'register' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Parolele nu se potrivesc!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Numele de utilizator există deja!";
        } else {
            $hashed_password = md5($password);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $hashed_password]);

            $success = "Contul a fost creat cu succes! <a href='app.php'>Autentificare</a>";
        }
    }
}

if ($action == 'dashboard' && isLoggedIn()) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE user_id = :user_id ORDER BY date_time ASC");
    $stmt->execute(['user_id' => $user_id]);
    $appointments = $stmt->fetchAll();
}

if ($action == 'add' && isLoggedIn() && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $pet_name = $_POST['pet_name'];
    $owner_name = $_POST['owner_name'];
    $date_time = $_POST['date_time'];
    $notes = $_POST['notes'];
    $user_id = $_SESSION['user_id'];

    // Verificăm suprapunerile
    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE date_time = :date_time");
    $stmt->execute(['date_time' => $date_time]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $error = "Există deja o programare pentru data și ora selectată.";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (pet_name, owner_name, date_time, notes, user_id) 
                                VALUES (:pet_name, :owner_name, :date_time, :notes, :user_id)");
        $stmt->execute([
            'pet_name' => $pet_name,
            'owner_name' => $owner_name,
            'date_time' => $date_time,
            'notes' => $notes,
            'user_id' => $user_id,
        ]);

        header("Location: app.php?action=dashboard");
        exit();
    }
}

if ($action == 'edit' && isLoggedIn() && isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $pet_name = $_POST['pet_name'];
        $owner_name = $_POST['owner_name'];
        $date_time = $_POST['date_time'];
        $notes = $_POST['notes'];

        $stmt = $conn->prepare("UPDATE appointments 
                                SET pet_name = :pet_name, owner_name = :owner_name, date_time = :date_time, notes = :notes 
                                WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'pet_name' => $pet_name,
            'owner_name' => $owner_name,
            'date_time' => $date_time,
            'notes' => $notes,
            'id' => $id,
            'user_id' => $user_id,
        ]);

        header("Location: app.php?action=dashboard");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $id, 'user_id' => $user_id]);
    $appointment = $stmt->fetch();
}

if ($action == 'delete' && isLoggedIn() && isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $id, 'user_id' => $user_id]);

    header("Location: app.php?action=dashboard");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cabinet Veterinar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        h2 {
            color: #4CAF50;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action == 'login' && !isLoggedIn()): ?>
            <h2>Autentificare</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="app.php?action=login">
                <input type="text" name="username" placeholder="Utilizator" required><br>
                <input type="password" name="password" placeholder="Parolă" required><br>
                <button type="submit">Log In</button>
            </form>
            <p>Nu ai cont? <a href="app.php?action=register">Înregistrează-te aici</a>.</p>
        <?php elseif ($action == 'register'): ?>
            <h2>Înregistrare Utilizator Nou</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <form method="POST" action="app.php?action=register">
                <input type="text" name="username" placeholder="Nume utilizator" required><br>
                <input type="password" name="password" placeholder="Parolă" required><br>
                <input type="password" name="confirm_password" placeholder="Confirmă Parola" required><br>
                <button type="submit">Înregistrează-te</button>
            </form>
        <?php elseif ($action == 'dashboard' && isLoggedIn()): ?>
            <h2>Bun venit, <?= htmlspecialchars($_SESSION['user']); ?></h2>
            <a href="app.php?action=logout">Deconectare</a>
            <h3>Programările Tale</h3>
            <a href="app.php?action=add">Adaugă Programare</a>
            <table>
                <tr>
                    <th>Nume Animal</th>
                    <th>Nume Proprietar</th>
                    <th>Data și Ora</th>
                    <th>Note</th>
                    <th>Acțiuni</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['pet_name']); ?></td>
                        <td><?= htmlspecialchars($appointment['owner_name']); ?></td>
                        <td><?= htmlspecialchars($appointment['date_time']); ?></td>
                        <td><?= htmlspecialchars($appointment['notes']); ?></td>
                        <td>
                            <a href="app.php?action=edit&id=<?= $appointment['id']; ?>">Editează</a>
                            <a href="app.php?action=delete&id=<?= $appointment['id']; ?>" onclick="return confirm('Ești sigur?');">Șterge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($action == 'add' && isLoggedIn()): ?>
            <h2>Adaugă Programare</h2>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="app.php?action=add">
                <input type="text" name="pet_name" placeholder="Nume Animal" required><br>
                <input type="text" name="owner_name" placeholder="Nume Proprietar" required><br>
                <input type="datetime-local" name="date_time" required><br>
                <textarea name="notes" placeholder="Note"></textarea><br>
                <button type="submit">Adaugă</button>
            </form>
        <?php elseif ($action == 'edit' && isLoggedIn()): ?>
            <h2>Editează Programare</h2>
            <form method="POST" action="app.php?action=edit&id=<?= $appointment['id']; ?>">
                <input type="text" name="pet_name" value="<?= htmlspecialchars($appointment['pet_name']); ?>" required><br>
                <input type="text" name="owner_name" value="<?= htmlspecialchars($appointment['owner_name']); ?>" required><br>
                <input type="datetime-local" name="date_time" value="<?= htmlspecialchars($appointment['date_time']); ?>" required><br>
                <textarea name="notes"><?= htmlspecialchars($appointment['notes']); ?></textarea><br>
                <button type="submit">Salvează</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>