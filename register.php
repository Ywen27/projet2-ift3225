<?php if (isset($_GET['source']))
    die(highlight_file(__FILE__, 1));
session_start();

include('connectionDB.php');

$usernameError = '';
$emailError = '';
$generalError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier si le nom d'utilisateur existe déjà
    $check_username_sql = "SELECT * FROM users WHERE username = ?";
    $check_username_stmt = $conn->prepare($check_username_sql);
    $check_username_stmt->bind_param('s', $username);
    $check_username_stmt->execute();
    $check_username_result = $check_username_stmt->get_result();

    // Requête pour vérifier si l'email existe déjà
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param('s', $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();

    if ($check_username_result->num_rows > 0) {
        $usernameError = 'Le nom d\'utilisateur existe déjà.';
    }
    if ($check_email_result->num_rows > 0) {
        $emailError = 'Cet email est déjà associé à un compte.';
    }
    if (empty($usernameError) && empty($emailError)) {
        // Les données sont valides, procéder à l'insertion dans la base de données

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Requête pour insérer le nouvel utilisateur dans la base de données
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $email, $hashed_password);
        $stmt->execute();

        // Connectez l'utilisateur
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
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
            <form method="post" action="register.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" class="form-control" required
                        value="<?php echo isset($username) ? $username : ''; ?>">
                    <?php if (!empty($usernameError)): ?>
                        <p style="color: red;">
                            <?php echo $usernameError; ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required
                        value="<?php echo isset($email) ? $email : ''; ?>">
                    <?php if (!empty($emailError)): ?>
                        <p style="color: red;">
                            <?php echo $emailError; ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" class="form-control" required
                        value="<?php echo isset($password) ? $password : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            </form>
            <br>
            <p>Si vous avez déjà un compte, veuillez vous <a href="login.php">connecter</a>.</p>
        </div>
    </div>
</body>

</html>