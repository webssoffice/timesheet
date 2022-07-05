<?php
    if (!$_SESSION["validation"] || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Update timesheet</span>
</nav>

<form method="POST">
    <?php
        $updateTimeSheetData = new MvcController();
        $updateTimeSheetData ->updateTimeSheetData();
        $updateTimeSheetData->updateTimeSheet();
    ?>
</form>