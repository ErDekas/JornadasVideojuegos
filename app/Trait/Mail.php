<?php

namespace App\Trait;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

trait Mail
{
    private PHPMailer $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);

        // Configuración de SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = env('SMTP_HOST'); // Usando configuración .env
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = env('SMTP_USERNAME');
        $this->mailer->Password = env('SMTP_PASSWORD');
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = env('SMTP_PORT', 587); // Puerto predeterminado 587
        
        $this->mailer->setFrom(env('SMTP_USERNAME'), "Tu Empresa"); // Remitente
        $this->mailer->isHTML(true);
    }

    protected function initializeMailer() {
        if (!isset($this->mailer)) {
            $this->mailer = new PHPMailer(true);

            // Configuración de SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = env('SMTP_HOST');
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = env('SMTP_USERNAME');
            $this->mailer->Password = env('SMTP_PASSWORD');
            $this->mailer->SMTPSecure = env('SMTPSECURE');
            $this->mailer->Port = env('SMTP_PORT', 587);
            
            $this->mailer->setFrom(env('SMTP_USERNAME'), "Tu Empresa");
            $this->mailer->isHTML(true);
        }
    }

    public function sendConfirmationEmail(string $email, string $nombre, string $token): bool {
        $this->intializeMailer();
        try {
            $confirmUrl = env('APP_URL') . "/usuarios/confirmarCuenta?token=" . $token;

            // Añadir destinatario
            $this->mailer->addAddress($email, $nombre);
            $this->mailer->Subject = 'Confirma tu cuenta';

            // Crear contenido del correo en HTML
            $content = "
                <!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Confirmación de cuenta</title>
</head>
<body style='font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;'>
    <table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f9f9f9; padding: 20px;'>
        <tr>
            <td align='center'>
                <table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden;'>
                    <tr>
                        <td style='padding: 20px; text-align: center; background-color: #4CAF50; color: #ffffff;'>
                            <h1 style='margin: 0; font-size: 24px;'>Confirma tu cuenta</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px; text-align: left; color: #333;'>
                            <p style='margin: 0 0 10px;'>Hola {$nombre},</p>
                            <p style='margin: 0 0 10px;'>Para confirmar tu cuenta, haz clic en el siguiente enlace:</p>
                            <p style='margin: 20px 0; text-align: center;'>
                                <a href='{$confirmUrl}' style='background-color: #4CAF50; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;'>Confirmar cuenta</a>
                            </p>
                            <p style='margin: 0 0 10px;'>Si no has creado esta cuenta, puedes ignorar este mensaje.</p>
                            <p style='margin: 0 0 10px;'>El enlace expirará en 24 horas.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; text-align: center; background-color: #f1f1f1; font-size: 12px; color: #888;'>
                            <p style='margin: 0;'>© 2025 Tu Empresa. Todos los derechos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
            ";
            
            $this->mailer->Body = $content;
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $e->getMessage());
            return false;
        }
    }
}
