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
        <!--Formulaire de recherche et de filtre-->
<h4>Optimisez vos recherches!</h4>
<form id="searchAndFilterForm">
    <div class="form-row align-items-center">
        <div class="col-auto">
            <label class="sr-only" for="searchInput">Recherche</label>
            <input type="text" class="form-control mb-2" id="searchInput" placeholder="Entrez nom user...">
        </div>
        <div class="col-auto">
            <label class="sr-only" for="categoryFilter">Catégorie</label>
            <select class="form-control mb-2" id="categoryFilter">
                <option value="">Veuillez choisir une catégorie...</option>
                <option value="developpement">Développement</option>
                <option value="communication">Communication</option>
                <option value="comptabilite">Comptabilité</option>
                <option value="multimedia">Multimédia</option>
                <option value="marketing">Marketing</option>
                <option value="ressources-humaines">Ressources humaines</option>
                <!--Ici, vous pouvez ajouter les options pour toutes les catégories possibles. -->
            </select>
        </div>
        <div class="col-auto">
            <label class="sr-only" for="dateFilter">Date</label>
            <input type="date" class="form-control mb-2" id="dateFilter">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-2">Filtrer</button>
        </div>
    </div>
    <br>
</form>
    <h3>Dashboard</h3>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Date début</th>
          <th>Catégorie</th>
          <th>Utilisateur</th>
          <th>Description</th>
          <th>Etat</th>
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
                            <label for="task-category" class="col-form-label">Catégorie:</label>
                            <select class="form-control" id="task-category">
                                <option value="">Veuillez choisir une catégorie...</option>
                                <option value="developpement">Développement</option>
                                <option value="communication">Communication</option>
                                <option value="comptabilite">Comptabilité</option>
                                <option value="multimedia">Multimédia</option>
                                <option value="marketing">Marketing</option>
                                <option value="ressources-humaines">Ressources humaines</option>
                              </select>
                        </div>
                        <div class="form-group">
                            <label for="task-user" class="col-form-label">Utilisateur:</label>
                            <input type="text" class="form-control" id="task-user">
                        </div>
                        <div class="form-group">
                            <label for="task-description" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="task-description"></textarea>
                        </div>
                        <!-- Dans le formulaire de création de tâche, définissez "En attente" comme valeur par défaut pour le statut -->
                        <div class="form-group">
                            <label for="task-status" class="col-form-label">État:</label>
                            <select class="form-control" id="task-status">
                            <option value="en attente" selected>En attente</option> <!-- Défini comme valeur par défaut -->
                            <option value="complet">Complet</option>
                            </select>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
  $('#createTaskBtn').click(function(e){
    e.preventDefault();

    // Récupère les données du formulaire
    var titre = $('#task-title').val();
    var dateDebut = $('#task-start-date').val();
    var categorie = $('#task-category').val();
    var utilisateur = $('#task-user').val();
    var description = $('#task-description').val();
    var etat = $('#task-status').val();

    // Crée une nouvelle ligne de tableau (tr) et des cellules de tableau (td)
    var newRow = $('<tr>');
    newRow.append('<td>' + titre + '</td>');
    newRow.append('<td>' + dateDebut + '</td>');
    newRow.append('<td>' + categorie + '</td>');
    newRow.append('<td>' + utilisateur + '</td>');
    newRow.append('<td>' + description + '</td>');
    // Utilise la valeur d'état pour déterminer ce qui doit être affiché dans la colonne "État"
    var statusCell = etat === 'complet' ? '<td><i class="fa fa-check-circle text-success"></i></td>' : '<td>En attente</td>';
    newRow.append(statusCell);
            
            // Ajoute le bouton Modifier et Supprimer à la ligne
            var editBtn = $('<button class="btn btn-primary btn-sm">Modifier</button>');
            editBtn.click(function() {
                $('#task-title').val(titre);
                $('#task-start-date').val(dateDebut);
                $('#task-category').val(categorie);
                $('#task-user').val(utilisateur);
                $('#task-description').val(description);
                $('#newTaskModal').modal('show');
            });
            newRow.append('<td>').append(editBtn);

            var deleteBtn = $('<button class="btn btn-danger btn-sm">Supprimer</button>');
            deleteBtn.click(function() {
                newRow.remove();
            });
            newRow.append('<td>').append(deleteBtn);

            // Ajoute la nouvelle ligne à la table
            $('#listeTaches').append(newRow);

            // Ferme le modal et réinitialise le formulaire
            $('#newTaskModal').modal('hide');
            $('#newTaskForm')[0].reset();
        });
    });
</script>
</body>

</html>