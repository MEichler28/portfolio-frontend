<?php
session_start();

// DB-Verbindung
$con = mysqli_connect("localhost", "d0440899", "FINK2501", "d0440899");
if (!$con) {
    die("Verbindungsfehler: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8mb4");

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Login prüfen
$loginError = "";
if (isset($_POST['login_submit'])) {
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");

    $sql = "SELECT bname, kname, pwort FROM benutzer WHERE bname = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['pwort'] === $password) {
            $_SESSION['username'] = $row['bname'];
            $_SESSION['lastname'] = $row['kname'];
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Benutzername oder Passwort falsch.";
        }
    } else {
        $loginError = "Benutzername oder Passwort falsch.";
    }

    mysqli_stmt_close($stmt);
}

// Löschen (nur für Benutzer 'pog')
if (isset($_GET['delete']) && isset($_SESSION['username']) && $_SESSION['username'] === 'pog') {
    $delete_id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM sneaker WHERE s_id = $delete_id");
   $message = "Sneaker erfolgreich gelöscht!";
echo "<script>alert(" . json_encode($message) . "); window.location.href='index.php';</script>";
exit;

}

// Kategorien abrufen
$marken = [];
$res = mysqli_query($con, "SELECT * FROM marken");
while ($row = mysqli_fetch_assoc($res)) {
    $marken[] = $row;
}

// Sneaker abrufen
$sneaker_res = mysqli_query($con, "SELECT * FROM sneaker ORDER BY marke");
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Startseite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="logo-container">
  <img src="images/SF-Logo.png" alt="Sneaker-Fetischist Logo">
</div>

<div class="layout">
  <aside class="sidebar">

    <?php if (!isset($_SESSION['username'])): ?>
      <h2>Login</h2>
      <?php if ($loginError): ?>
        <p style="color:red;"><?= htmlspecialchars($loginError) ?></p>
      <?php endif; ?>
      <form class="login-form" method="post">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required><br>
        <button type="submit" name="login_submit" class="button-anmelde">Anmelden</button>
      </form>
      <p style="margin-top:10px; text-align:center;">
        Noch keinen Account? <a href="register.php" style="color:#c62828; font-weight:bold;">Registrieren</a>
      </p>
    <?php else: ?>
      <h2>Willkommen, <?= htmlspecialchars($_SESSION['lastname']) ?>!</h2>
      <form method="post">
        <button type="submit" name="logout" class="button-anmelde">Logout</button>
      </form>
    <?php endif; ?>

    <h2>Filter</h2>
    <div class="filterbar">
      <label for="filter">Filtern nach:</label>
      <select id="filter">
        <option value="alle">Alle</option>
        <?php foreach ($marken as $m): ?>
          <option value="<?= strtolower($m['marke']) ?>"><?= htmlspecialchars($m['marke']) ?></option>
        <?php endforeach; ?>
        <option value="preis-auf">Preis aufsteigend</option>
        <option value="preis-ab">Preis absteigend</option>
      </select>

      <label for="suche">Modell suchen:</label>
      <input type="text" id="suche" placeholder="Modell suchen">

      <label for="anzahl">Anzahl pro Seite:</label>
      <select id="anzahl">
        <option value="999">Alle anzeigen</option>
        <option value="3">3 anzeigen</option>
        <option value="6">6 anzeigen</option>
      </select>

      <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'pog'): ?>
        <a href="hinzufuegen.php" class="button-neu">Neu hinzufügen</a>
      <?php endif; ?>
    </div>
  </aside>

  <main class="content">
    <header>
      <h1>Galerie</h1>
    </header>

    <?php
    $currentMarke = "";
    $nr = 0;

    while ($sneaker = mysqli_fetch_assoc($sneaker_res)) {
        $marke = $sneaker['marke'];
        $markeLower = strtolower($marke);
        $s_id = $sneaker['s_id'];
        $kdatum = date("d.m.Y", strtotime($sneaker['kaufdatum']));
        $zustand = htmlspecialchars($sneaker['used']);

        if ($currentMarke !== $markeLower) {
            if ($nr > 0) {
                echo "</div></section>";
            }
            echo "<section class='markenblock' data-marke='$markeLower'>";
            echo "<h3 class='marke'>" . htmlspecialchars($marke) . "</h3>";
            echo "<div class='grid'>";
            $currentMarke = $markeLower;
            $nr = 0;
        }

        echo "<div class='item' data-preis='" . $sneaker['preis'] . "' data-marke='$markeLower'>
                <img src='bild_aus_db.php?id=$s_id' alt='" . htmlspecialchars($sneaker['modell']) . "'>
                <h3>" . htmlspecialchars($sneaker['modell']) . "</h3>
                <p class='kdatum'>Kaufdatum: $kdatum</p>
                <p class='zustand'>Zustand: $zustand</p>
                <p class='preis'>Preis: €" . $sneaker['preis'] . "</p>";

        if (isset($_SESSION['username']) && $_SESSION['username'] === 'pog') {
            echo "<a href='index.php?delete=$s_id' class='button-loeschen' onclick='return confirm(\"Diesen Sneaker wirklich löschen?\");'>🗑️ Löschen</a>";
        }

        echo "</div>";
        $nr++;
    }

    if ($nr > 0) {
        echo "</div></section>";
    }

    mysqli_close($con);
    ?>

    <div id="pagination"></div>
  </main>
</div>

<!-- Popup nur einmal im HTML vorhanden -->
<div id="popup-info" class="popup" style="display:none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    Sneaker wurde erfolgreich gelöscht!
  </div>
</div>


<script src="js/index.js" defer></script>
</body>
</html>
