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
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTaskModal">Créer une nouvelle tâche</button>
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

    <!--Formulaire pour créer une nouvelle tâche-->
    <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTaskModalLabel">Nouvelle Tâche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newTaskForm">
                        <div class="form-group">
                            <label for="task-title" class="col-form-label">Titre:</label>
                            <input type="text" class="form-control" id="task-title">
                        </div>
                        <div class="form-group">
                            <label for="task-start-date" class="col-form-label">Date de début:</label>
                            <input type="date" class="form-control" id="task-start-date">
                        </div>
                        <div class="form-group">
                            <label for="task-end-date" class="col-form-label">Date de fin:</label>
                            <input type="date" class="form-control" id="task-end-date">
                        </div>
                        <div class="form-group">
                            <label for="task-category" class="col-form-label">Catégorie:</label>
                            <input type="text" class="form-control" id="task-category">
                        </div>
                        <div class="form-group">
                            <label for="task-description" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="task-description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="createTaskBtn">Créer</button>
                </div>
            </div>
        </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>