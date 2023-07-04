<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));

session_start();

include('connectionDB.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier si le nom d'utilisateur existe déjà
    $check_sql = "SELECT * FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Le nom d'utilisateur existe déjà
        $error = 'Ce nom d\'utilisateur existe déjà';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Requête pour insérer le nouvel utilisateur dans la base de données
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $email, $hashed_password);
        $stmt->execute();

        // Connectez l'utilisateur
        $_SESSION['user_id'] = $stmt->insert_id;
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f2f2f2;
        }
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .register-form {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        button[type="submit"] {
            font-weight: bold;
            text-transform: uppercase;
        }
        p {
            text-align: center;
            color: #666;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h1>Inscription</h1>
            <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            </form>
            <br>
            <p>Si vous avez déjà un compte, veuillez vous <a href="login.php">connecter</a>.</p>
        </div>
    </div>
</body>
</html>


