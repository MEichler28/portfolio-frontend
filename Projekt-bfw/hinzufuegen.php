<?php
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbindung zur Datenbank
$con = mysqli_connect("localhost", "d0440899", "FINK2501", "d0440899");
if (!$con) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8mb4");

// Kategorien abrufen
$sql = "SELECT * FROM marken";
$all_categories = mysqli_query($con, $sql);

// Wenn Formular abgeschickt wurde
if (isset($_POST['submit'])) {
    $modell = mysqli_real_escape_string($con, $_POST['name']);
    $preis = floatval($_POST['preis']);
    $kaufdatum = $_POST['kaufdatum'];
    $used = mysqli_real_escape_string($con, $_POST['zustand']);
    $marke = mysqli_real_escape_string($con, $_POST['hersteller']);
    $neue_marke = trim($_POST['neuerHersteller']);

    $bildname = '';
    $bildinhalt = '';

    // Neuer Hersteller
    if (!empty($neue_marke)) {
        $neue_marke = mysqli_real_escape_string($con, $neue_marke);
        $check = mysqli_query($con, "SELECT * FROM marken WHERE marke = '$neue_marke'");
        if (mysqli_num_rows($check) === 0) {
            mysqli_query($con, "INSERT INTO marken (marke) VALUES ('$neue_marke')");
            $marke = $neue_marke;
        } else {
            echo '<script>alert("Marke bereits vorhanden!")</script>';
            $marke = $neue_marke;
        }
    }

    // Bild
    if (isset($_FILES['bilddatei']) && $_FILES['bilddatei']['error'] === 0) {
        $upload_ordner = "images/";
        if (!is_dir($upload_ordner)) {
            mkdir($upload_ordner, 0777, true);
        }

        $dateiname = basename($_FILES["bilddatei"]["name"]);
        $zielpfad = $upload_ordner . $dateiname;
        $dateityp = strtolower(pathinfo($zielpfad, PATHINFO_EXTENSION));
        $bildarten = ["jpg", "jpeg", "png", "gif"];

        if (in_array($dateityp, $bildarten)) {
            if (!file_exists($zielpfad)) {
                if (move_uploaded_file($_FILES["bilddatei"]["tmp_name"], $zielpfad)) {
                    $bildinhalt = addslashes(file_get_contents($zielpfad));
                    $bildname = $dateiname;
                }
            } else {
                $bildinhalt = addslashes(file_get_contents($zielpfad));
                $bildname = $dateiname;
            }
        }
    }

    // Eintrag speichern
    $sql_insert = "INSERT INTO sneaker (modell, preis, kaufdatum, used, marke, bildname, bild)
                   VALUES ('$modell', $preis, '$kaufdatum', '$used', '$marke', '$bildname', '$bildinhalt')";

    if (mysqli_query($con, $sql_insert)) {
        echo "<script>alert('Sneaker erfolgreich hinzugefügt!'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Fehler beim Einfügen: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Neuer Sneaker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 <div class="logo-container">
    <img src="images/SF-Logo.png" alt="Sneaker-Fetischist Logo">
</div>
<a href="index.php" class="button-zurueck">← Zurück zur Übersicht</a>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <section class="form">
            <input type="text" id="name" name="name" placeholder="Name des Sneakers" required>
            <input type="number" id="preis" name="preis" placeholder="Preis" required>
            <input type="date" id="kaufdatum" name="kaufdatum" placeholder="Kaufdatum" required>
            <input type="file" id="bild" name="bilddatei">

            <select id="hersteller" name="hersteller" required>
                <option value="">Hersteller auswählen</option>
                <?php while ($category = mysqli_fetch_assoc($all_categories)): ?>
                    <option value="<?= htmlspecialchars($category['marke']) ?>">
                        <?= htmlspecialchars($category['marke']) ?>
                    </option>
                <?php endwhile; ?>
                <option value="Neu">+ Neuer Hersteller</option>
            </select>

            <input type="text" id="neuerHersteller" name="neuerHersteller"
                   placeholder="Neuen Hersteller eingeben" style="display:none;">

            <select id="zustand" name="zustand" required>
                <option value="">Zustand auswählen</option>
                <option value="Neu">Neu</option>
                <option value="Gut">Gut</option>
                <option value="Gebraucht">Gebraucht</option>
            </select>

            <input id="hinzufuegen" name="submit" value="✔ Sneaker hinzufügen" type="submit">
        </section>
    </form>

    <section class="vorschau" id="vorschau">
        <div>
            <h2 id="v-name">Name</h2>
            <p id="v-preis">Preis: - €</p>
            <p id="v-kaufdatum">Kaufdatum: -</p>
            <p id="v-hersteller">Hersteller: -</p>
            <p id="v-zustand">Zustand: -</p>
            <img id="v-bild" src="" alt="Vorschau Bild" style="max-width: 100%; max-height: 200px; display: none;">
        </div>
    </section>
</div>

<script src="js/hinzufuegen.js"></script>
</body>
</html>
