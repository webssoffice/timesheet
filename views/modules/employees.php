<?php
    if (empty($_SESSION["validation"]) || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Employees</span>
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

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="d-none d-lg-table-cell">Name and Surname</th>
            <th scope="col">E-mail</th>
            <th scope="col" class="d-none d-lg-table-cell">Registration date</th>
            <th scope="col">Level</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>

    <tbody>
        <?php
            $viewAllEmployee = new MvcController();
            $viewAllEmployee->viewAllEmployee();
            $viewAllEmployee->deleteEmployeeData();
        ?>
    </tbody>
</table>