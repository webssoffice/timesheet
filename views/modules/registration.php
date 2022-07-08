<?php
    if (!$_SESSION["validation"] || $_SESSION["level"] != '1' || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Add employee</span>
</nav>

<form method="post">
    <?php
        $createEmployeeData = new MvcController();
        $createEmployeeData->createEmployeeData();

        if (isset($_GET["action"])) {
            $actual_link_success = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $find_success = 'success';
            $pos_success = strpos($actual_link_success, $find_success);

            if ($pos_success !== true) {
                $link_success = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $final_link_success = str_replace('/success', '', $link_success);
            }

            if ($_GET["action"] == 'success') {
                echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

                header("refresh:3; url=$final_link_success");
            } elseif ($_GET['action'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
            } elseif ($_GET['action'] == 'duplicate') {
                echo '<div class="alert alert-danger" role="alert">E-mail already exists!</div>';
            }
        }
    ?>

    <div class="my-2 form-group">
        <label for="nome">Name and Surname</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Name and Surname" required>
    </div>

    <div class="my-2 form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="E-mail" required>

    </div>

    <div class="my-2 form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="my-2 form-group">
        <label for="level">Level</label>
        <select id="level" name="level" class="form-select">
            <option value="" selected disabled>Select</option>
            <option value="1">Admin</option>
            <option value="2">Employee</option>
        </select>
    </div>

    <div class="my-2 form-group">
        <label for="rate">Rate/Hour (&euro;)</label>
        <input type="number" id="rate" name="rate" class="form-control" placeholder="25">
    </div>

    <div class="my-2 form-group">
        <button type="submit" id="register" name="register" class="btn btn-block btn-primary">Send</button>
    </div>
</form>