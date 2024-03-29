<?php
    if (isset($_GET["page"])) {
        $baseName = $_GET["page"];
    } else {
        $baseName = 'worksheet';
    }

    $currentPage = basename($baseName);
?>

<nav class="navbar navbar-light justify-content-end">
    <span class="navbar-text">
        <?php 
            $viewEmployeeInfo = new MvcController();
            $viewEmployeeInfo->viewEmployeeInfo();
        ?>
    </span>
</nav>

<ul class="nav nav-pills nav-fill d-print-none">
    <?php if ($_SESSION == false) { ?>
        <li class="nav-item p-2"><a href="/login" title="Login" class="nav-link border border-info <?php if ($currentPage == "login") { echo "active"; } ?>">Login</a></li>
        <li class="nav-item p-2"><a href="/recover" title="Forgot your password?" class="nav-link border border-info <?php if ($currentPage == "recover") { echo "active"; } ?>">Forgot your password?</a></li>
    <?php } ?>
    <?php if ($_SESSION == true) { ?>
        <li class="nav-item p-2"><a href="/worksheet" title="WorkSheet" class="nav-link border border-info <?php if ($currentPage == "worksheet") { echo "active"; } ?>">WorkSheet</a></li>
        <li class="nav-item p-2"><a href="/agenda" title="Agenda" class="nav-link border border-info <?php if ($currentPage == "agenda") { echo "active"; } ?>">Agenda</a></li>
        <li class="nav-item p-2"><a href="/invoices" title="Invoices" class="nav-link border border-info <?php if ($currentPage == "invoices" || $currentPage == "show-invoice") { echo "active"; } ?>">Invoices</a></li>
        <li class="nav-item p-2"><a href="/projects" title="Projects" class="nav-link border border-info <?php if ($currentPage == "projects") { echo "active"; } ?>">Projects</a></li>
        <?php if ($_SESSION["level"] == '1') { ?>
            <li class="nav-item p-2"><a href="/employees" title="Employees" class="nav-link border border-info <?php if ($currentPage == "employees") { echo "active"; } ?>">Employees</a></li>
            <li class="nav-item p-2"><a href="/registration" title="Registration" class="nav-link border border-info <?php if ($currentPage == "registration") { echo "active"; } ?>">Registration</a></li>
        <?php } ?>
        <li class="nav-item p-2"><a href="/update-employee/<?php echo $_SESSION["id"]; ?>" title="Employees" class="nav-link border border-info <?php if ($currentPage == "profile" || $currentPage == "update-employee") { echo "active"; } ?>">Profile</a></li>
        <li class="nav-item p-2"><a href="/worksheet/logout" title="Logout" class="nav-link border border-info <?php if ($currentPage == "logout") { echo "active"; } ?>">Logout</a></li>
    <?php } ?>
</ul>