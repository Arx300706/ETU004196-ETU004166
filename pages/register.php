<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Inscription</h2>
            <form method="post" action="register.php">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="mdp" name="mdp" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-link">Déjà inscrit ?</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
