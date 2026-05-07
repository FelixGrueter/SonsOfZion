<?php
/**
 * Sons of Zion – Contact Form Handler
 *
 * Formulartyp:
 *   "contact" → Name, E-Mail, Telefon (optional), Nachricht (optional)
 *
 * Schutzmaßnahmen:
 *   - Honeypot (Feld "website")
 *   - Mindest-Ausfüllzeit 3s (Session)
 *   - Rate-Limiting 30s Cooldown (Session)
 *   - Input-Sanitization + Validierung
 *
 * Umgebung: Shared Hosting, PHP >= 7.4
 */

declare(strict_types=1);

/* --------------------------------------------------------------------------
 * Basis-Setup
 * -------------------------------------------------------------------------- */

session_start();
header('Content-Type: application/json; charset=utf-8');


/* --------------------------------------------------------------------------
 * Hilfsfunktion: reject()
 * -------------------------------------------------------------------------- */
function reject(
    int $statusCode = 400,
    string $message = 'Anfrage konnte nicht verarbeitet werden.'
): void {
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}


/* --------------------------------------------------------------------------
 * 1. Honeypot-Check
 * -------------------------------------------------------------------------- */
if (!empty($_POST['website'] ?? '')) {
    http_response_code(204);
    exit;
}


/* --------------------------------------------------------------------------
 * 2. Mindest-Ausfüllzeit (3 Sekunden)
 * -------------------------------------------------------------------------- */
$minTimeSeconds = 3;

if (
    !isset($_SESSION['form_time']) ||
    (time() - $_SESSION['form_time']) < $minTimeSeconds
) {
    reject(400);
}


/* --------------------------------------------------------------------------
 * 3. Rate-Limiting (30 Sekunden Cooldown)
 * -------------------------------------------------------------------------- */
$cooldownSeconds = 30;
$now = time();

if (
    isset($_SESSION['last_submit']) &&
    ($now - $_SESSION['last_submit']) < $cooldownSeconds
) {
    reject(
        429,
        'Bitte warte einen Moment, bevor du erneut sendest.'
    );
}

$_SESSION['last_submit'] = $now;


/* --------------------------------------------------------------------------
 * 4. Formulartyp prüfen
 * -------------------------------------------------------------------------- */
$formType = isset($_POST['form_type']) ? trim($_POST['form_type']) : '';

if ($formType !== 'contact') {
    reject(400, 'Ungültiger Formulartyp.');
}


/* --------------------------------------------------------------------------
 * 5. Felder auslesen & säubern
 * -------------------------------------------------------------------------- */
$name     = isset($_POST['name'])    ? trim(strip_tags($_POST['name']))    : '';
$emailRaw = isset($_POST['email'])   ? trim($_POST['email'])               : '';
$email    = filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ?: '';
$phone    = isset($_POST['phone'])   ? trim(strip_tags($_POST['phone']))   : '';
$message  = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

if ($name === '' || $email === '') {
    reject(400, 'Bitte fülle alle Pflichtfelder korrekt aus.');
}


/* --------------------------------------------------------------------------
 * 6. Mail-Parameter + Templates
 * -------------------------------------------------------------------------- */
$to        = 'info@sonsofzion.org';
$fromEmail = 'noreply@sonsofzion.org';
$subject   = "Sons of Zion – Kontakt: $name";

ob_start();
require __DIR__ . '/mail-templates/contact.txt.php';
$textMessage = ob_get_clean();

ob_start();
require __DIR__ . '/mail-templates/contact.html.php';
$htmlMessage = ob_get_clean();


/* --------------------------------------------------------------------------
 * 7. Mail-Vorbereitung (Multipart: Text + HTML)
 * -------------------------------------------------------------------------- */
$boundary = md5(uniqid((string)time(), true));

$headers  = "From: Sons of Zion Website <$fromEmail>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Entity-Ref-ID: " . uniqid('soz-', true) . "\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";

$body  = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $textMessage . "\r\n\r\n";

$body .= "--$boundary\r\n";
$body .= "Content-Type: text/html; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $htmlMessage . "\r\n\r\n";
$body .= "--$boundary--";


/* --------------------------------------------------------------------------
 * 8. Mail-Versand
 * -------------------------------------------------------------------------- */

// ===== LOKAL (Entwicklung / Test) =====
/*
file_put_contents(
    __DIR__ . '/mail-test.log',
    date('Y-m-d H:i:s') . ' [' . $formType . ']' . PHP_EOL .
    print_r($_POST, true) .
    PHP_EOL . "---------------------" . PHP_EOL,
    FILE_APPEND
);

echo json_encode([
    'success' => true,
    'message' => 'Mail wurde lokal simuliert.'
]);
exit;
*/

// ===== LIVE (Produktion) =====
if (mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'success' => true,
        'message' => 'Deine Nachricht wurde erfolgreich versendet!'
    ]);
} else {
    reject(
        500,
        'Es gab einen Serverfehler. Bitte versuche es später erneut.'
    );
}
