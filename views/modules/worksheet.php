<?php
    if (!$_SESSION["validation"] || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">WorkSheet</span>
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
        } elseif ($_GET["action"] == 'error') {
            echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
        }
    }
?>

<button class="btn btn-secondary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiv" aria-expanded="false" aria-controls="collapseDiv">&#10010;</button>

<button class="btn btn-warning mt-2" type="button" id="toggle-button">&#9782;</button>

<div class="collapse" id="collapseDiv">
    <form method="post">
        <?php
            $timeManagement = new MvcController();
            $timeManagement->timeManagement();
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">
                        <div class="my-2 form-group">
                        <label for="project">Project</label>
                            <select id="project" name="project" class="form-select" required>
                                <option value="" selected disabled>Select</option>
                                <?php
                                    $viewProjects = new MvcController();
                                    $viewProjects->viewProjects();
                                ?>
                            </select>
                            <input type="hidden" id="related_employee" name="related_employee" value="<?php echo $_SESSION["id"]; ?>">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="my-2 form-group">
                            <label for="comment">Comment</label>
                            <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment" required>
                        </div>
                    </th>
                    <th scope="col">
                        <div class="my-2 form-group">
                            <button type="submit" id="startTime" name="startTime" class="btn btn-block btn-primary">Start</button>
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </form>
</div>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">Project</th>
            <th scope="col" class="d-none d-lg-table-cell">Employee</th>
            <th scope="col" class="col-md-2 d-none d-lg-table-cell">Start</th>
            <th scope="col" class="col-md-2 d-none d-lg-table-cell">Stop</th>
            <th scope="col">Total</th>
            <th scope="col" class="col-md-2 d-none d-lg-table-cell">Rate</th>
            <th scope="col" class="col-md-6">Comment</th>
            <th scope="col" class="col-md-1 d-none d-lg-table-cell">Paid</th>
        </tr>
    </thead>

    <tbody>
        <?php
            $viewAllWork = new MvcController();
            $viewAllWork->viewAllWork();
            $viewAllWork->deleteWorkData();
        ?>
    </tbody>

    <button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top">&#8686;</button>
</table>