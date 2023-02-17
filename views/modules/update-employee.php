<?php
    if (empty($_SESSION["validation"]) || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Update employee profile</span>
</nav>

<?php
    if (isset($_GET["action"])) {
        $actual_link_update = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $find_update = 'update';
        $pos_update = strpos($actual_link_update, $find_update);

        if ($pos_update !== true) {
            $link_update = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            function str_replace_n($search, $replace, $subject, $occurrence) {
                $search = preg_quote($search);
                return preg_replace("/^((?:(?:.*?$search){".--$occurrence."}.*?))$search/", "$1$replace", $subject);
            }
            
            $final_link_update = substr_replace(str_replace_n('update', '', $link_update, 2), '', -1);
        }

    
        if ($_GET["action"] == 'update') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';

            header("refresh:3; url=$final_link_update");
        } elseif ($_GET["action"] == 'error') {
            echo '<div class="alert alert-danger" role="alert">Data entered is incorrect!</div>';
        }
    }
?>

<form method="POST">
    <?php
        $updateEmployeeData = new MvcController();
        $updateEmployeeData ->updateEmployeeData();
        $updateEmployeeData->updateEmployee();
    ?>
</form>