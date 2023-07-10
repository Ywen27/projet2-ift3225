<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));

session_start();

include('connectionDB.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Vérifiez si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // Le mot de passe est correct, connectez l'utilisateur
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if($_SESSION['role'] == 'user'){
            header('Location: dashboard.php');
            exit;
        }else{
            header('Location: dashboardAdmin.php');
            exit;
        }
        
    } else {
        // Le mot de passe est incorrect, affichez une erreur
        $error = 'Le nom d\'utilisateur ou le mot de passe est incorrect';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
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
        .login-form {
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
        <div class="login-form">
            <h1>Connexion</h1>
            <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>
            <p>Si vous n'avez pas encore de compte, veuillez vous <a href="register.php">inscrire</a> d'abord.</p>
        </div>
    </div>
</body>
</html>

