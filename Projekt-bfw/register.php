<?php
// serverdaten
$servername = "localhost";
$username = "d0440899";
$password = "FINK2501";
$dbname = "d0440899";

// Wenn Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verbindung aufbauen
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Werte aus dem Formular sichern (sanitizing)
    $bname = $_POST["bname"];
    $kname = $_POST["kname"];
    $email = $_POST["email"];
    $pwd = $_POST["pwort"];
    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

    // Prepared Statement
    $stmt = $conn->prepare("INSERT INTO benutzer (bname, kname, email, pwort) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $bname, $kname, $email, $pwd);

    // Ausführen und Erfolg/Meldung speichern
    if ($stmt->execute()) {
        $meldung = "Registrierung erfolgreich!";
    } else {
        $meldung = "Fehler: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrierung</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="registration-page">
 <div class="logo-container">
    <img src="images/SF-Logo.png" alt="Sneaker-Fetischist Logo">
</div>
    <?php if (!empty($meldung)): ?>
        <p><strong><?php echo htmlspecialchars($meldung); ?></strong></p>
        <p><br><a href="index.php" class="button">Zurück zur Galerie</a></p>
    <?php else: ?>

    <div class="registration">
    <h2>Registrierung</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-group">
            <label for="bname">Benutzername</label>
            <input type="text" id="bname" name="bname" required>
        </div>
        <div class="form-group">
            <label for="kname">Klarname</label>
            <input type="text" id="kname" name="kname" required>
        </div>
        <div class="form-full">
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-full">
            <label for="pwort">Passwort</label>
            <input type="password" id="pwort" name="pwort" required>
        </div>
        <div class="form-full">
            <button type="submit">Registrieren</button>
        </div>
    </form>
</div>

    <?php endif; ?>

</body>
</html>
