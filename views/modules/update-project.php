<?php
    if (!$_SESSION["validation"] || $_SESSION["level"] != 1 || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Update project</span>
</nav>

<form method="POST">
    <?php
        $updateProjectData = new MvcController();
        $updateProjectData ->updateProjectData();
        $updateProjectData->updateProject();
    ?>
</form>