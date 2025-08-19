<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Formulaire de contact</title>
  <!-- Lien Bootstrap 5 depuis CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm rounded-4">
          <div class="card-body p-4">
            <h1 class="h4 mb-4 text-center">Formulaire de contact</h1>

            <!-- Formulaire -->
            <form action="public/send-contact.php" method="post" id="contact-form" novalidate>

              <!-- Nom -->
              <div class="mb-3">
                <label for="name" class="form-label">Votre nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Jean Dupont" required>
                <div class="invalid-feedback">Veuillez entrer votre nom.</div>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Votre e-mail</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="exemple@mail.com" required>
                <div class="invalid-feedback">Veuillez entrer une adresse e-mail valide.</div>
              </div>

              <!-- Sujet -->
              <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="subject" name="subject" value="Contact" required>
                <div class="invalid-feedback">Veuillez indiquer un sujet.</div>
              </div>

              <!-- Message -->
              <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="6" placeholder="Votre message..." required></textarea>
                <div class="invalid-feedback">Veuillez écrire un message.</div>
              </div>

              <!-- Honeypot (invisible pour humains) -->
              <div style="display:none">
                <label for="company">Company</label>
                <input type="text" id="company" name="company" autocomplete="off">
              </div>

              <!-- Bouton -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Envoyer</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>


</body>

</html>