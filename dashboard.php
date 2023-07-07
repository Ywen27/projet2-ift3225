<?php if (isset($_GET['source']))
    die(highlight_file(__FILE__, 1));
session_start();

include('connectionDB.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$categories = $conn->query("SELECT * FROM categories");
$categoryNames = array();
while ($row = $categories->fetch_assoc()) {
    $categoryNames[$row['categorie_id']] = $row['name'];
}
$categories->data_seek(0);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 90%;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="logout.php" class="btn btn-danger float-right">Se déconnecter</a>
        <h3>Bienvenue
            <span style="font-style: oblique; color: #1E90FF;">
                <?php echo $_SESSION['username']; ?>
            </span>! Allons gérer vos tâches ensemble.
        </h3>
        <br>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTaskModal">Créer une nouvelle
            tâche</button>
        <hr>
        <h5>Voulez-vous filtrer vos tâches</h5>
        <form id="filterForm" method="post" action="">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><label for="filter-title" class="col-form-label">par le titre?</label></th>
                        <th><label for="filter-start-date" class="col-form-label">par la date de début?</label></th>
                        <th><label for="filter-end-date" class="col-form-label">par la date de fin?</label></th>
                        <th><label for="filter-category" class="col-form-label">par leur Catégorie?</label></th>
                        <th><label for="filter-state" class="col-form-label">par leur état?</label></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" class="form-control" id="filter-title" name="filter-title"
                                placeholder="Recherche">
                        </td>
                        <td>
                            <input type="date" class="form-control" id="filter-start-date" name="filter-start-date">
                        </td>
                        <td>
                            <input type="date" class="form-control" id="filter-end-date" name="filter-end-date">
                        </td>
                        <td>
                            <select class="form-control" id="filter-category" name="filter-category">
                                <option value=""></option>
                                <?php
                                while ($row = $categories->fetch_assoc()) {
                                    echo '<option value="' . $row['categorie_id'] . '">' . $row['name'] . '</option>';
                                }
                                $categories->data_seek(0);
                                ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="filter-state" name="filter-state">
                                <option value=""></option>
                                <option value="complete">complétée</option>
                                <option value="en cours">en cours</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div class="d-flex justify-content-between align-items-center">
            <h3>Dashboard</h3>
            <button id="resetFilter" class="btn btn-primary" style="display: none;">Revoir toutes les tâches</button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Catégorie</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="listeTaches">
            </tbody>
        </table>
        <p id="noTasks" class="text-center" style="color: grey;"></p>

        <!--Formulaire pour créer une nouvelle tâche-->
        <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newTaskModalLabel">Nouvelle Tâche</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="newTaskForm" method="post" action="createTask.php">
                            <div class="form-group">
                                <label for="task-title" class="col-form-label">Titre*:</label>
                                <input type="text" class="form-control" id="task-title" name="task-title">
                            </div>
                            <div class="form-group">
                                <label for="task-start-date" class="col-form-label">Date de début*:</label>
                                <input type="date" class="form-control" id="task-start-date" name="task-start-date">
                            </div>
                            <div class="form-group">
                                <label for="task-category" class="col-form-label">Catégorie*:</label>
                                <select class="form-control" id="task-category" name="task-category">
                                    <option value=""></option>
                                    <?php
                                    while ($row = $categories->fetch_assoc()) {
                                        echo '<option value="' . $row['categorie_id'] . '">' . $row['name'] . '</option>';
                                    }
                                    $categories->data_seek(0);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="task-description" class="col-form-label">Description:</label>
                                <textarea class="form-control" id="task-description" name="task-description"></textarea>
                            </div>
                        </form>
                        <p style="color: grey;">Tous les champs avec * sont obligatoires</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="createTaskBtn">Créer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            fetchTasks();

            $('#createTaskBtn').click(function (e) {
                e.preventDefault();
                $('#newTaskForm').submit();
            });

            $('#resetFilter').on('click', function () {
                $(this).hide();
                fetchTasks();
                $('#filterForm').trigger('reset');
            });

            $('#newTaskForm').on('submit', function (e) {
                e.preventDefault();

                var title = $('#task-title').val();
                var startDate = $('#task-start-date').val();
                var category = $('#task-category').val();
                var description = $('#task-description').val();

                if (!title || !startDate || !category) {
                    alert('Tous les champs avec * sont obligatoire');
                    return;
                }

                $.ajax({
                    url: 'createTask.php',
                    type: 'POST',
                    data: {
                        taskTitle: title,
                        taskStartDate: startDate,
                        taskCategory: category,
                        taskDescription: description
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            $('#newTaskForm').trigger('reset');
                            $('#newTaskModal').modal('hide');
                            fetchTasks();
                            alert('Création de la tâche "' + title + '" est réussite!');
                        } else {
                            console.log("create task failed, " + response.message);
                        }
                    }
                });
            });

            var categoryNames = <?php echo json_encode($categoryNames); ?>;
            // Fetch tasks from the server
            function fetchTasks() {
                $.ajax({
                    url: 'fetchAllTasks.php',
                    type: 'GET',
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            var tasks = response.tasks;
                            var tasksHtml = '';

                            tasks.forEach(function (task) {
                                var categoryName = categoryNames[task.categorie_id];
                                tasksHtml += `
                            <tr>
                                <td style="word-wrap: break-word; max-width: 150px;">
                                    ${task.nom_tache}
                                </td>
                                <td style=" word-wrap: break-word; max-width: 100px;">
                                    ${task.date_debut}
                                </td>
                                <td style="word-wrap: break-word; max-width: 100px;">
                                    ${task.date_fin ? task.date_fin : '-'}
                                </td>
                                <td style="word-wrap: break-word; max-width: 150px;">
                                    ${categoryName}
                                </td>
                                <td style="word-wrap: break-word; max-width: 300px;">
                                    ${task.description ? task.description : '-'}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary ">Modifier</button>
                                    <button type="button" class="btn btn-danger delete-task" data-task-id="${task.tache_id}" data-task-name="${task.nom_tache}">
                                        Supprimer
                                    </button>
                                    <button type="button" class="btn btn-success">Terminer</button>
                                </td>
                            </tr>`;
                            });

                            $('#listeTaches').html(tasksHtml);
                            if (tasks.length == 0) {
                                $('#noTasks').text("Vous avez aucune tâche pour l'instant.");
                            } else {
                                $('#noTasks').text("");
                            }
                        } else {
                            console.log("fetch tasks failed, " + response.message);
                        }
                    }
                });
            }


            $('#filterForm').on('submit', function (e) {
                e.preventDefault();

                var filterTitle = $('#filter-title').val();
                var filterStartDate = $('#filter-start-date').val();
                var filterEndDate = $('#filter-end-date').val();
                var filterCategory = $('#filter-category').val();
                var filterState = $('#filter-state').val();

                if (!filterTitle && !filterStartDate && !filterEndDate && !filterCategory && !filterState) {
                    alert('Veuillez remplir au moins un champ pour le filtrage!');
                    return;
                }

                $.ajax({
                    url: 'filterTask.php',
                    type: 'POST',
                    data: {
                        filterTitle: filterTitle,
                        filterStartDate: filterStartDate,
                        filterEndDate: filterEndDate,
                        filterCategory: filterCategory,
                        filterState: filterState
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success == true) {
                            var tasks = response.tasks;
                            var tasksHtml = '';

                            tasks.forEach(function (task) {
                                var categoryName = categoryNames[task.categorie_id];
                                tasksHtml += `
                                <tr>
                                <td style="word-wrap: break-word; max-width: 150px;">
                                    ${task.nom_tache}
                                </td>
                                <td style=" word-wrap: break-word; max-width: 100px;">
                                    ${task.date_debut}
                                </td>
                                <td style="word-wrap: break-word; max-width: 100px;">
                                    ${task.date_fin ? task.date_fin : '-'}
                                </td>
                                <td style="word-wrap: break-word; max-width: 150px;">
                                    ${categoryName}
                                </td>
                                <td style="word-wrap: break-word; max-width: 300px;">
                                    ${task.description ? task.description : '-'}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary ">Modifier</button>
                                    <button type="button" class="btn btn-danger delete-task" data-task-id="${task.tache_id}" data-task-name="${task.nom_tache}">
                                        Supprimer
                                    </button>
                                    <button type="button" class="btn btn-success">Terminer</button>
                                </td>
                            </tr>`;
                            });

                            $('#listeTaches').html(tasksHtml);
                            if (tasks.length == 0) {
                                $('#noTasks').text("Aucune tâche ne correspond à la recherche");
                            } else {
                                $('#noTasks').text("");
                            }
                            $('#resetFilter').show();
                        } else {
                            console.log("filter tasks failed, " + response.message);
                        }
                    }
                });
            });

            $(document).on('click', '.delete-task', function () {
                var taskId = $(this).data('task-id');
                var taskName = $(this).data('task-name');

                if (confirm('Êtes-vous sûr de vouloir supprimer la tâche "'+ taskName +'" ?')) {
                    $.ajax({
                        url: 'deleteTask.php',
                        type: 'POST',
                        data: {
                            taskId: taskId
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.success == true) {
                                fetchTasks();
                            } else {
                                console.log("Delete task failed, " + response.message);
                            }
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>