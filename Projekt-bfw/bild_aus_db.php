<?php
// Fehler anzeigen für Debugging (kann später entfernt werden)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verbindung zur Datenbank
$con = mysqli_connect("localhost", "d0440899", "FINK2501", "d0440899");
if (!$con) {
    die("Fehler bei der DB-Verbindung: " . mysqli_connect_error());
}

// ID aus URL lesen und absichern
$s_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// SQL-Abfrage vorbereiten
$sql = "SELECT bild, bildname FROM sneaker WHERE s_id = $s_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    $bild = $row['bild'];
    $bildname = $row['bildname'];

    // Dateityp anhand der Endung bestimmen
    $endung = strtolower(pathinfo($bildname, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];

    // MIME-Type setzen oder Standard verwenden
    $contentType = $mimeTypes[$endung] ?? 'application/octet-stream';
    header("Content-Type: $contentType");

    // Bilddaten ausgeben
    echo $bild;

} else {
    http_response_code(404);
    echo "Bild nicht gefunden.";
}

// Verbindung schließen
mysqli_close($con);
?>
