<?php

declare(strict_types=1);

use App\Mailer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Charger .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Récup config depuis l'env
$config = [
  'host'        => $_ENV['MAIL_HOST']        ?? '',
  'port'        => $_ENV['MAIL_PORT']        ?? '2525',
  'username'    => $_ENV['MAIL_USERNAME']    ?? '',
  'password'    => $_ENV['MAIL_PASSWORD']    ?? '',
  'encryption'  => $_ENV['MAIL_ENCRYPTION']  ?? 'tls',
  'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@example.com',
  'from_name'   => $_ENV['MAIL_FROM_NAME']   ?? 'Website',
];

// Lecture des champs du formulaire (adapte les names si besoin)
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? 'Contact');
$message = trim($_POST['message'] ?? '');

// Honeypot (un champ caché que les humains ne remplissent pas)
$hp = trim($_POST['company'] ?? ''); // par ex. input name="company" caché
if ($hp !== '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Spam détecté.']);
  exit;
}

// CSRF (optionnel) – si tu as mis un token dans le formulaire
if (!empty($_POST['csrf']) && (!isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf'])) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Token CSRF invalide.']);
  exit;
}

// Validations simples
$errors = [];
if ($name === '')   $errors[] = 'Le nom est requis.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
if ($message === '') $errors[] = 'Le message est requis.';

if ($errors) {
  http_response_code(422);
  echo json_encode(['ok' => false, 'error' => implode(' ', $errors)]);
  exit;
}

// Anti-flood : 1 message toutes les 30s par session
$now = time();
if (isset($_SESSION['last_send']) && ($now - (int)$_SESSION['last_send']) < 30) {
  http_response_code(429);
  echo json_encode(['ok' => false, 'error' => 'Merci d’attendre quelques secondes avant un nouvel envoi.']);
  exit;
}

// Envoi
$mailer = new Mailer($config);
$result = $mailer->sendContact([
  'to_address' => $_ENV['MAIL_TO_ADDRESS'] ?? 'admin@example.com',
  'to_name'    => $_ENV['MAIL_TO_NAME']    ?? 'Admin',
  'name'       => $name,
  'email'      => $email,
  'subject'    => $subject,
  'message'    => $message,
  'app_name'   => $_ENV['APP_NAME']        ?? 'App',
]);

if ($result['ok']) {
  $_SESSION['last_send'] = $now;
  echo json_encode(['ok' => true, 'message' => 'Message envoyé.']);
} else {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Échec de l’envoi : ' . $result['error']]);
}
