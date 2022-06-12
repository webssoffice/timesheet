<?php
    if (!$_SESSION["validation"] || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Invoices</span>
</nav>

<?php
    if (isset($_GET["action"])) {
        if ($_GET["action"] == 'delete') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';
        } elseif ($_GET["action"] == 'update') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';
        } elseif ($_GET["action"] == 'success') {
            echo '<div class="alert alert-success" role="alert">Action performed successfully!</div>';
        }
    }
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="col-10">Projects</th>
        </tr>
    </thead>

    <tbody>
        <?php
            $viewAllInvoices = new MvcController();
            $viewAllInvoices->viewAllInvoices();
        ?>
    </tbody>
</table>