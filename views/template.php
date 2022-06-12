<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">

        <script src="/assets/js/jquery.min.js"></script>

        <?php 
            $seoPages = new MvcController();
            $seoPages->seoPages();
        ?>
    </head>

    <body>
        <nav class="navbar navbar-light">
            <div class="container d-flex justify-content-center">
                <a class="navbar-brand" href="/">
                    <img src="/assets/img/logo.svg" alt="webss">
                </a>
            </div>
        </nav>

        <div class="container">
            <?php include 'modules/navigation.php'; ?>
        </div>

        <div class="container">
            <?php
                $mvc = new MvcController();
                $mvc->viewPages();
            ?>
        </div>

        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/main.js"></script>

        <footer>
            <div class="text-center p-3 d-print-none">© <?php echo date("Y"); ?>. Made in <a class="text-dark" href="https://www.webss.ro">webss</a> lab.</div>
        </footer>

        <?php
            if (!empty($_GET["action"]) && $_GET["action"] == 'logout' || !empty($_GET["id"]) && $_GET["id"] == 'logout') {
        ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#alertModal').modal('show');
                });
            </script>

            <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header alert alert-danger">
                            <h5 class="modal-title" id="exampleModalLabel">ALERT!</h5>
                        </div>

                        <div class="modal-body">Are you sure you want to logout?</div>
                            <div class="modal-footer">
                                <a class="btn btn-primary" href="/logout">Yes</a>
                                <a class="btn btn-secondary" href="/">No</a>
                                <!-- <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">No</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>

        <?php
            if (!empty($_GET["action"]) && $_GET["action"] == 'delete-timesheet' && !empty($_GET["timesheet"]) && is_numeric($_GET["timesheet"])) {
        ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#alertModal').modal('show');
                });
            </script>

            <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header alert alert-danger">
                            <h5 class="modal-title" id="exampleModalLabel">ALERT!</h5>
                        </div>

                        <div class="modal-body">Are you sure you want to delete?</div>
                            <div class="modal-footer">
                                <a class="btn btn-primary" href="/delete-timesheet/<?php if (!empty($_GET["timesheet"]) && is_numeric($_GET["timesheet"])) { echo $_GET["timesheet"]; } ?>">Yes</a>
                                <a class="btn btn-secondary" href="/">No</a>
                                <!-- <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">No</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>

        <?php
            if (!empty($_GET["action"]) && $_GET["action"] == 'delete-project' && !empty($_GET["project"]) && is_numeric($_GET["project"])) {
        ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#alertModal').modal('show');
                });
            </script>

            <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header alert alert-danger">
                            <h5 class="modal-title" id="exampleModalLabel">ALERT!</h5>
                        </div>

                        <div class="modal-body">Are you sure you want to delete?</div>
                            <div class="modal-footer">
                                <a class="btn btn-primary" href="/delete-project/<?php if (!empty($_GET["project"]) && is_numeric($_GET["project"])) { echo $_GET["project"]; } ?>">Yes</a>
                                <a class="btn btn-secondary" href="/projects">No</a>
                                <!-- <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">No</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>

        <?php
            if (!empty($_GET["action"]) && $_GET["action"] == 'delete-employee' && !empty($_GET["employee"]) && is_numeric($_GET["employee"])) {
        ?>
            <script type="text/javascript">
                $(window).on('load', function() {
                    $('#alertModal').modal('show');
                });
            </script>

            <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header alert alert-danger">
                            <h5 class="modal-title" id="exampleModalLabel">ALERT!</h5>
                        </div>

                        <div class="modal-body">Are you sure you want to delete?</div>
                            <div class="modal-footer">
                                <a class="btn btn-primary" href="/delete-employee/<?php if (!empty($_GET["employee"]) && is_numeric($_GET["employee"])) { echo $_GET["employee"]; } ?>">Yes</a>
                                <a class="btn btn-secondary" href="/employees">No</a>
                                <!-- <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">No</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>
    </body>
</html>