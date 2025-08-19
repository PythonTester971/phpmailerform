<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
  private PHPMailer $mail;

  public function __construct(array $config)
  {
    $this->mail = new PHPMailer(true);     // true => Exceptions activées
    $this->mail->isSMTP();                 // On utilisera SMTP (Mailtrap)
    $this->mail->Host       = $config['host'];
    $this->mail->Port       = (int) $config['port'];
    $this->mail->SMTPAuth   = true;
    $this->mail->Username   = $config['username'];
    $this->mail->Password   = $config['password'];
    $this->mail->CharSet    = 'UTF-8';

    // Chiffrement
    if (!empty($config['encryption'])) {
      // "tls" (STARTTLS) convient bien avec le port 2525 de Mailtrap
      $this->mail->SMTPSecure = $config['encryption']; // 'tls' ou 'ssl'
    }

    // Expéditeur par défaut
    $this->mail->setFrom($config['from_address'], $config['from_name']);
  }

  /**
   * Envoie un message "Contact".
   * $data attend: name, email, subject, message
   * Retourne [ok => bool, error => string|null]
   */
  public function sendContact(array $data): array
  {
    try {
      $this->mail->clearAllRecipients();

      // Destinataire (l’admin)
      $this->mail->addAddress($data['to_address'], $data['to_name']);

      // Met le visiteur en reply-to (utile pour lui répondre)
      if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $this->mail->addReplyTo($data['email'], $data['name'] ?? '');
      }

      $this->mail->Subject = $data['subject'];

      // Corps HTML + texte brut alternatif
      $html = <<<HTML
                <h2>Nouveau message de contact</h2>
                <p><strong>Nom :</strong> {$this->escape($data['name'] ?? '')}</p>
                <p><strong>Email :</strong> {$this->escape($data['email'] ?? '')}</p>
                <p><strong>Message :</strong><br>{$this->nl2p($data['message'] ?? '')}</p>
                <hr>
                <p style="font-size:12px;color:#666">Projet : {$this->escape($data['app_name'] ?? 'N/A')}</p>
            HTML;

      $text = "Nouveau message de contact\n"
        . "Nom: " . ($data['name'] ?? '') . "\n"
        . "Email: " . ($data['email'] ?? '') . "\n"
        . "Message:\n" . ($data['message'] ?? '') . "\n";

      $this->mail->isHTML(true);
      $this->mail->Body    = $html;
      $this->mail->AltBody = $text;

      $this->mail->send();

      return ['ok' => true, 'error' => null];
    } catch (Exception $e) {
      // En prod, logge $e->getMessage()
      return ['ok' => false, 'error' => $e->getMessage()];
    }
  }

  private function escape(string $value): string
  {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }

  private function nl2p(string $text): string
  {
    $text = $this->escape($text);
    $paras = array_map(fn($p) => "<p>{$p}</p>", array_filter(array_map('trim', preg_split('/\R+/', $text))));
    return implode("\n", $paras);
  }
}
