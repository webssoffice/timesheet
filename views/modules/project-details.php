<?php
    if (empty($_SESSION["validation"]) || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<nav class="navbar navbar-light bg-light">
    <span class="navbar-brand p-2 h3">Project details</span>
</nav>

<?php
    $ProjectData = new MvcController();
    $ProjectData ->viewProjectDetails();
?>