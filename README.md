# Formulaire de contact PHP avec PHPMailer & Mailtrap

Ce projet propose un formulaire de contact sécurisé en PHP, utilisant [PHPMailer](https://github.com/PHPMailer/PHPMailer) pour l’envoi d’e-mails via [Mailtrap](https://mailtrap.io/) en environnement de test.

## Prérequis

- PHP >= 8.3
- [Composer](https://getcomposer.org/)
- Un compte [Mailtrap](https://mailtrap.io/)

## Installation

1. **Clone le dépôt ou copie les fichiers dans ton projet**

   ```bash
   git clone https://github.com/PythonTester971/phpmailerform.git
   ```

2. **Installe les dépendances PHP**

   ```sh
   composer install
   ```

3. **Configure les variables d’environnement**

   Renomme le fichier `.env.example` en `.env` si besoin, puis renseigne tes identifiants Mailtrap et les infos d’expéditeur/destinataire :

   ```
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=VOTRE_USER_MAILTRAP
   MAIL_PASSWORD=VOTRE_MDP_MAILTRAP
   MAIL_ENCRYPTION=tls

   MAIL_FROM_ADDRESS=no-reply@votresite.test
   MAIL_FROM_NAME="Formulaire du site"

   MAIL_TO_ADDRESS=admin@exemple.com
   MAIL_TO_NAME="Admin du site"

   APP_NAME="NomDuProjet"
   ```

4. **Accède au formulaire**

   Ouvre `contact.php` dans ton navigateur. Les messages envoyés arriveront dans ta boîte Mailtrap.

## Structure du projet

- `contact.php` : Formulaire HTML
- `public/send-contact.php` : Traitement et envoi du mail
- `src/Mailer.php` : Classe d’envoi via PHPMailer
- `.env.example` : Configuration (non versionnée)
- `vendor/` : Dépendances Composer

## Personnalisation

- Modifie le design du formulaire dans `contact.php`.
- Adapte le contenu du mail dans [`src/Mailer.php`](src/Mailer.php).

## Dépannage

- Vérifie tes identifiants Mailtrap dans `.env`.
- Consulte les logs Mailtrap en cas d’erreur.
- Active le mode debug PHPMailer si besoin :

  ```php
  $this->mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
  ```

## Ressources

- [Documentation PHPMailer](https://github.com/PHPMailer/PHPMailer)