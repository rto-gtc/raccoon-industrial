<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars($_POST['name'] ?? '');
    $email   = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? 'Brak tematu');
    $message = htmlspecialchars($_POST['message'] ?? 'Brak treści wiadomości');

    // Konfiguracja PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'hosting2403088.online.pro'; // Serwer SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'biuro@biznes-dom.pl';         // Adres e-mail nadawcy
        $mail->Password   = 'Sokooko712!';                 // Hasło do e-maila
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('biuro@biznes-dom.pl', 'Formularz Kontaktowy');
        $mail->addAddress('rto.gtc@gmail.com'); // Adres odbiorcy

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <p><strong>Imię i nazwisko:</strong> {$name}</p>
            <p><strong>Adres e-mail:</strong> {$email}</p>
            <p><strong>Wiadomość:</strong></p>
            <p>{$message}</p>
        ";
        $mail->AltBody = "Imię i nazwisko: {$name}\nE-mail: {$email}\n\nWiadomość:\n{$message}";

        // Obsługa załączników
        if (!empty($_FILES['attachment']['tmp_name']) && is_uploaded_file($_FILES['attachment']['tmp_name'])) {
            $tmpFilePath = $_FILES['attachment']['tmp_name'];
            $filename = basename($_FILES['attachment']['name']);

            // Sprawdzenie i przeniesienie pliku do bezpiecznej lokalizacji tymczasowej
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $finalFilePath = $uploadDir . $filename;
            if (move_uploaded_file($tmpFilePath, $finalFilePath)) {
                // Dodanie pliku jako załącznika
                $mail->addAttachment($finalFilePath, $filename);
            } else {
                echo "Nie udało się zapisać pliku.";
                exit;
            }
        }

        // Wysyłanie wiadomości
        $mail->send();

        // Usuwanie załącznika po wysyłce (opcjonalne)
        if (isset($finalFilePath) && file_exists($finalFilePath)) {
            unlink($finalFilePath);
        }

        // Wyświetlenie strony HTML po wysłaniu wiadomości
        echo "
        <!DOCTYPE html>
        <html lang='pl'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Dziękujemy za kontakt z Raccoon Services - Systemy Grzewcze</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f7f7f7;
                    color: #333;
                    text-align: center;
                    padding: 50px;
                    margin: 0;
                }
                .thank-you-container {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                    margin: 0 auto;
                    text-align: center;
                }
                .thank-you-container img {
                    max-width: 120px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #4caf50;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 16px;
                    margin-bottom: 20px;
                    line-height: 1.5;
                }
                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    font-size: 16px;
                    color: #fff;
                    background-color: #007bff;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
                .btn:hover {
                    background-color: #0056b3;
                }
                .contact-info {
                    margin-top: 30px;
                    font-size: 14px;
                    color: #666;
                }
                .contact-info a {
                    color: #007bff;
                    text-decoration: none;
                }
                .contact-info a:hover {
                    text-decoration: underline;
                }
                .links {
                    margin-top: 40px;
                    font-size: 14px;
                }
                .links a {
                    color: #007bff;
                    text-decoration: none;
                    margin: 0 10px;
                }
                .links a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class='thank-you-container'>
                <img src='https://static.wixstatic.com/media/e0bdea_7d03c5c4e30f449b9a47a051f1684f27~mv2.png' alt='Logo Raccoon Services Systemy Grzewcze'>
                <h1>Dziękujemy za kontakt!</h1>
                <p>Twoja wiadomość została pomyślnie wysłana. Odpowiemy na nią jak najszybciej!</p>
                <a href='javascript:history.back()' class='btn'>Wróć na poprzednią stronę</a>
                <div class='contact-info'>
                    <p>Masz dodatkowe pytania? Skontaktuj się z nami:</p>
                    <p><strong>Telefon:</strong> <a href='tel:+48698000441'>+48 698 000 441</a></p>
                    <p><strong>Email:</strong> <a href='mailto:raccoon-services@biznes-dom.pl'>raccoon-services@biznes-dom.pl</a></p>
                </div>
                <div class='links'>
                    <p>
                        <a href='https://biznes-dom.pl/hydraulik/index.html'>Strona Główna</a> | 
                        <a href='https://biznes-dom.pl/hydraulik/about.html'>O nas</a> | 
                        <a href='https://biznes-dom.pl/hydraulik/contact.html'>Kontakt</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";

    } catch (Exception $e) {
        echo "Wiadomość nie mogła zostać wysłana. Błąd: {$mail->ErrorInfo}";
    }
} else {
    echo "Nieprawidłowe żądanie.";
}
?>
