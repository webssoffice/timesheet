<?php
    if (!$_SESSION["validation"] || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Projects</span>
</nav>

<?php
    if (isset($_GET["action"])) {
        $actual_link_delete = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $find_delete = 'delete';
        $pos_delete = strpos($actual_link_delete, $find_delete);

        if ($pos_delete !== true) {
            $link_delete = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $final_link_delete = str_replace('/delete', '', $link_delete);
        }

        $actual_link_update = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $find_update = 'update';
        $pos_update = strpos($actual_link_update, $find_update);

        if ($pos_update !== true) {
            $link_update = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $final_link_update = str_replace('/update', '', $link_update);
        }

        $actual_link_success = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $find_success = 'success';
        $pos_success = strpos($actual_link_success, $find_success);

        if ($pos_success !== true) {
            $link_success = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $final_link_success = str_replace('/success', '', $link_success);
        }

        if ($_GET["action"] == 'delete') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

            header("refresh:3; url=$final_link_delete");
        } elseif ($_GET["action"] == 'update') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

            header("refresh:3; url=$final_link_update");
        } elseif ($_GET["action"] == 'success') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

            header("refresh:3; url=$final_link_success");
        }
    }

    if ($_SESSION["level"] == '1') {
?>

<button class="btn btn-secondary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiv" aria-expanded="false" aria-controls="collapseDiv">&#10010;</button>

<div class="collapse" id="collapseDiv">
    <form method="post">
        <?php
            $addProject = new MvcController();
            $addProject->addProject();
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">
                        <div class="my-2 form-group">
                            <label for="project">Project name</label>
                            <input type="text" id="project" name="project" class="form-control" placeholder="Project name" required>
                        </div>

                        <div class="my-2 form-group">
                            <label for="details">Project details</label>
                            <textarea class="form-control" id="details" name="details" rows="3" placeholder="Project details"></textarea>
                        </div>
                        
                        <div class="my-2 form-group">
                            <button type="submit" id="addProject" name="addProject" class="btn btn-block btn-primary">Send</button>
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </form>
</div>

<?php
    }
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="col-9">Projects</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>

    <tbody>
        <?php
            $viewAllProjects = new MvcController();
            $viewAllProjects->viewAllProjects();
            $viewAllProjects->deleteProjectData();
        ?>
    </tbody>
</table>