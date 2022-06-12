<?php
    class linkPage {
        public static function urlPage($link) {
            if (
                $link == 'worksheet' ||
                $link == 'login' ||
                $link == 'logout' ||
                $link == 'registration' ||
                $link == 'employees' ||
                $link == 'projects' ||
                $link == 'update-employee' ||
                $link == 'update-timesheet' ||
                $link == 'update-project' ||
                $link == 'invoices' ||
                $link == 'show-invoice' ||
                $link == 'recover'
                ) {
                    
                $navigation = 'views/modules/' . $link . '.php';
            } elseif ($link == 'index') {
                $navigation = 'views/modules/worksheet.php';
            } else {
                $navigation = 'views/modules/404.php';
            }

            return $navigation;
        }
    }