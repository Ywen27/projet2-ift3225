<?php if (isset($_GET['source'])) die(highlight_file(__FILE__, 1));
session_start();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté, dirigez-le vers le tableau de bord
    header('Location: login.php');
    exit;
}
session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .container {
      margin-top: 50px;
    }

    .truncate {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  </style>
</head>

<body>
  <div class="container">
    <h3>Bienvenue <?php echo $_SESSION['username'];?>! Allons gérer vos tâches ensemble.</h3>
    <button type="button" class="btn btn-primary" onclick="">Créer une nouvelle tâche</button>
    <hr>
    <h3>Dashboard</h3>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date début</th>
          <th>Date fin</th>
          <th>Catégorie</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="listeTaches">
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>