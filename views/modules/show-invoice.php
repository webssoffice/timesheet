<?php
    if (empty($_SESSION["validation"]) || $_SESSION["password"] != $_SESSION["csrf"]) {
    
        header('location: /login');
    
        exit();
    }
?>

<style type="text/css" media="print">
    @page {
        size: A4;
        margin: 0;
    }

    @print {
        @page :footer {
            display: none;
        }
    
        @page :header {
            display: none;
        }
    }

    @media print {
        body {
            margin: 0;
            font-size: 12px;
        }
    }
</style>

<div class="float-end p-3">
    <button class="btn btn-success d-print-none" onclick="window.print()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>Print invoice</button>
</div>

<div class="p-3">
    <?php
        $date = new DateTime('now', new DateTimeZone("Europe/Rome"));
        $now_time = $date->format("YmdHis");
    ?>
    <h4>Invoice &#8470; #<?php echo $now_time; ?></h4>
</div>

<table class="table table-striped table-hover">
    <?php
        $viewInvoice = new MvcController();
        $viewInvoice->viewInvoice();
    ?>
</table>