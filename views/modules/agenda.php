<?php
    if (!$_SESSION["validation"] || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Agenda</span>
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
        } elseif ($_GET["action"] == 'success') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

            header("refresh:3; url=$final_link_success");
        } elseif ($_GET["action"] == 'error') {
            echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
        }
    }
?>

<button class="btn btn-secondary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiv" aria-expanded="false" aria-controls="collapseDiv">&#10010;</button>

<div class="collapse" id="collapseDiv">
    <form method="post">
        <?php
            $addAgenda = new MvcController();
            $addAgenda->addAgenda();
        ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">
                        <div class="my-2 form-group">
                        <label for="datetime">Date</label>
                            <input type="date" id="datetime" name="datetime" class="form-control" required>
                            <input type="hidden" id="related_employee" name="related_employee" value="<?php echo $_SESSION["id"]; ?>">
                        </div>
                    </th>
                    <th scope="col">
                        <div class="my-2 form-group">
                        <label for="timedate">Time</label>
                            <input type="time" id="timedate" name="timedate" class="form-control" required>
                        </div>
                    </th>
                    <th scope="col">
                        <div class="my-2 form-group">
                            <label for="event">Event</label>
                            <input type="text" id="event" name="event" class="form-control" placeholder="Event" required>
                        </div>
                    </th>
                    <th scope="col">
                        <div class="my-2 form-group">
                            <button type="submit" id="addAgenda" name="addAgenda" class="btn btn-block btn-primary">Send</button>
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </form>
</div>

<?php
    $viewAgenda = new MvcController();
    $viewAgenda->viewAgenda();
    $viewAgenda->deleteAgendaData();
?>