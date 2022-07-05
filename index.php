<?php
    session_start();
    
    require_once 'controllers/controller.php';
    require_once 'models/crud.php';
    require_once 'models/routes.php';

    $mvc = new MvcController();
    $mvc->viewTemplate();