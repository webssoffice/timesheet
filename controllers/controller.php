<?php
    ob_start();

    class MvcController {
        public function viewTemplate() {
            include 'views/template.php';
        }

        public function viewPages() {
            if (isset($_GET["page"])) {
                $link = $_GET["page"];
            } else {
                $link = 'index';
            }
            
            $response = linkPage::urlPage($link);

            include $response;
        }
        
        public function seoPages() {
            if (isset($_GET["page"])) {
                if ($_GET["page"] == "show-invoice") {
                    $date = new DateTime('now', new DateTimeZone("Europe/Rome"));
                    $now_time = $date->format("YmdHis");

                    echo '<title>Invoice &#8470; #' . $now_time . '</title>' . "\r\n";
                    echo '<meta name="description" content="' . $_GET["page"] . '">' . "\r\n";
                    echo '<meta name="keywords" content="' . $_GET["page"] . '">' . "\r\n";
                } else {
                    echo '<title>' . ucfirst($_GET["page"]) . ' - TimeSheet Management</title>' . "\r\n";
                    echo '<meta name="description" content="' . $_GET["page"] . '">' . "\r\n";
                    echo '<meta name="keywords" content="' . $_GET["page"] . '">' . "\r\n";
                }
            } else {
                echo '<title>TimeSheet Management</title>' . "\r\n";
                echo '<meta name="description" content="TimeSheet Management">' . "\r\n";
                echo '<meta name="keywords" content="TimeSheet Management">' . "\r\n";
            }
        }

        // Create
        public function createEmployeeData() {
            if (isset($_POST["register"])) {
                $securePassword = crypt($_POST["password"], "$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$");

                $dataController = array(
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "password" => $securePassword,
                    "level" => $_POST["level"],
                    "rate" => $_POST["rate"],
                    "csrf" => $_POST["password"]
                );

                $responseDb = Data::createEmployeeData($dataController, "employees");

                if ($responseDb == "success") {
                    header("location: /registration/success");
                } elseif ($responseDb == "duplicate") {
                    header("location: /registration/duplicate");
                } else {
                    header("location: /registration/error");
                }
            }
        }

        public function timeManagement() {
            if (isset($_POST["startTime"])) {
                if (!empty($_POST["project"]) && isset($_POST["related_employee"])) {
                    $responseDbEmployee = Data::updateEmployee($_POST["related_employee"], "employees");

                    $dataController = array(
                        "project" => $_POST["project"],
                        "comment" => $_POST["comment"],
                        "related_employee" => $_POST["related_employee"],
                        "employee_rate" => $responseDbEmployee["employee_rate"]
                    );

                    $responseDb = Data::startTime($dataController, "works");

                    if ($responseDb == "success") {
                        header("location: /worksheet/success");
                    } else {
                        header("location: /worksheet/error");
                    }
                } else {
                    header("location: /worksheet/error");
                }
            }

            if (isset($_POST["endTime"])) {
                $date = new DateTime('now', new DateTimeZone("Europe/Rome"));
                $now_time = $date->format("Y-m-d H:i:s");

                $dataController = array(
                    "id" => $_POST["id"],
                    "end_time" => $now_time
                );

                $responseDb = Data::endTime($dataController, "works");

                if ($responseDb == "success") {
                    header("location: /worksheet/success");
                } else {
                        header("location: /worksheet/error");
                }
            }
        }

        public function addAgenda() {
            if (isset($_POST["addAgenda"])) {
                if (!empty($_POST["datetime"]) && !empty($_POST["timedate"]) && !empty($_POST["event"])) {
                    $dataController = array(
                        "related_employee" => $_POST["related_employee"],
                        "datetime" => $_POST["datetime"],
                        "timedate" => $_POST["timedate"],
                        "event" => $_POST["event"]
                    );

                    $responseDb = Data::addAgenda($dataController, "agenda");

                    if ($responseDb == "success") {
                        header("location: /agenda/success");
                    } else {
                        header("location: /agenda/error");
                    }
                } else {
                    header("location: /agenda/error");
                }
            }
        }

        public function addProject() {
            if (isset($_POST["addProject"])) {
                $dataController = array(
                        "project" => $_POST["project"],
                        "details" => $_POST["details"]
                    );

                    $responseDb = Data::addProject($dataController, "projects");

                    if ($responseDb == "success") {
                        header("location: /projects/success");
                    } else {
                        header("location: /projects/error");
                    }
            }
        }

        // Read
        public function readEmployeeData() {
            if (isset($_POST["login"])) {
                $securePassword = crypt($_POST["password"], "$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$");

                $dataController = array(
                    "email" => $_POST["email"],
                    "password" => $securePassword
                );
    
                $responseDb = Data::readEmployeeData($dataController, "employees");
    
                if ($responseDb["email"] == $_POST["email"] && $responseDb["password"] == $securePassword) {
                    $_SESSION["validation"] = true;
                    $_SESSION["id"] = $responseDb["id"];
                    $_SESSION["name"] = $responseDb["name"];
                    $_SESSION["email"] = $responseDb["email"];
                    $_SESSION["password"] = $responseDb["password"];
                    $_SESSION["level"] = $responseDb["level"];
                    $_SESSION["csrf"] = $responseDb["csrf"];
    
                    header("location: /worksheet");
                } else {
                    header("location: /login/error");
                }
            }
        }

        public function recoverPassword() {
            if (isset($_POST["recover"])) {
                $dataController = array(
                    "email" => $_POST["email"]
                );
    
                $responseDb = Data::recoverPassword($dataController, "employees");
    
                if ($responseDb["email"] == $_POST["email"] && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $randomPassword = substr(md5(uniqid(mt_rand(), true)), 0, 8);

                    $dataController = array(
                        "email" => $responseDb["email"],
                        "randomPassword" => $randomPassword
                    );

                    $responseDbPassword = Data::changePassword($dataController, "employees");

                    if ($responseDbPassword == "success") {
                        $to = $responseDb["email"];
                        $subject = 'Recover your account';
                        $message = '<b>Recover your account</b> <br><br> Your new password is: ' . $randomPassword . ' <br><br> Remember to change your password.';

                        $headers[] = 'MIME-Version: 1.0';
                        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                        $headers[] = 'From: noreplay@webss.ro <noreplay@webss.ro>';

                        if (mail($to, $subject, $message, implode("\r\n", $headers))) {
                            header("location: /recover/success");
                        } else {
                            header("location: /recover/error");
                        }
                    } else {
                        header("location: /recover/error");
                    }
                } else {
                    header("location: /recover/error");
                }
            }
        }

        public function viewEmployeeInfo() {
            if (!empty($_SESSION["name"]) && !empty($_SESSION["level"])) {
                echo $_SESSION["name"];

                if ($_SESSION["level"] == '1') {
                    echo '&nbsp;(Admin)';
                } elseif ($_SESSION["level"] == '2') {
                    echo '&nbsp;(Employee)';
                }
            }
        }

        public function viewAllEmployee() {
            $responseDb = Data::viewAllEmployee("employees");

            if (count($responseDb) == '0') {
                echo '<tr>
                        <td scope="row" colspan="6">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                if ($data["level"] == '1') {
                    $level = 'Admin';
                } else {
                    $level = 'Employee';
                }

                echo '<tr>
                        <td class="align-middle d-none d-lg-table-cell">' . $data["name"] . '</td>
                        <td class="align-middle">' . $data["email"] . '</td>
                        <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["registration_date"])) . '</td>
                        <td class="align-middle">' . $level . '</td>';

                        if ($data["level"] == '1') {
                            if ($_SESSION["id"] == $data["related_employee"]) {
                                echo '<td class="align-middle"><a href="/update-employee/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>';
                            } else {
                                echo '<td>&nbsp;</td>';
                            }
                        } else {
                            echo '<td class="align-middle"><a href="/update-employee/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>';
                        }
                        
                        echo '<td class="align-middle"><a href="/employees/delete-employee/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#9587;</button></a></td>
                    </tr>';
            }
        }

        public function viewAllProjects() {
            $responseDb = Data::viewAllProjects("projects");

            if (count($responseDb) == '0') {
                echo '<tr>
                        <td scope="row" colspan="2">No data!</td>
                        <td scope="row" colspan="1"></td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                $responseDbDetails = Data::updateProject($data["id"], "projects");

                echo '<tr>
                        <td class="align-middle col-9">' . $data["project"] . '</td>';

                if ($_SESSION["level"] == '1') {
                    if (!empty($responseDbDetails["details"])) {
                        echo '<td class="align-middle"><a href="/project-details/' . $data["id"] . '" title="View"><button class="btn btn-warning">&#128065;</button></a></td>';
                    } else {
                        echo '<td scope="row" colspan="1"></td>';
                    }

                    echo '<td class="align-middle"><a href="/update-project/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>
                        <td class="align-middle"><a href="/projects/delete-project/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#9587;</button></a></td>';
                } else {
                    echo '<td scope="row" colspan="1"></td>
                        <td scope="row" colspan="1"></td>';
                    
                    if (!empty($responseDbDetails["details"])) {
                        echo '<td class="align-middle d-flex justify-content-end"><a href="/project-details/' . $data["id"] . '" title="View"><button class="btn btn-success">&#128065;</button></a></td>';
                    } else {
                        echo '<td scope="row" colspan="1"></td>';
                    }
                }

                echo '</tr>';
            }
        }

        public function viewProjects() {
            $responseDb = Data::viewProjects("projects");
    
            foreach ($responseDb as $row => $data) {
                echo '<option value="' . $data["id"] . '">' . $data["project"] . '</option>';
            }
        }

        public function viewAgenda() {
            date_default_timezone_set("Europe/Rome");
            setlocale(LC_TIME, 'en_EN');
            
            if (!empty($_GET["action"])) {
                $string = $_GET["action"];
                $isThereNumber = false;

                for ($i = 0; $i < strlen($string); $i++) {
                    if (ctype_digit($string[$i])) {
                        $isThereNumber = true;

                        break;
                    }
                }
                
                if ($isThereNumber) {
                    $data = $_GET["action"];	
                } else {
                    $data = date("Y-m-d");
                }
            } else {
                $data = date("Y-m-d");
            }
            
            $day = date('d', strtotime($data));
            $month = date('m', strtotime($data));
            $year = date('Y', strtotime($data));
            $firstDay = mktime(0,0,0,$month, 1, $year);
            $currentMonth = date('F', $firstDay);
            $dayMonth = cal_days_in_month(0, $month, $year);
            $nextSunday = strtotime('next Sunday');
            $daysWeek = array();
            $beforeDay = date("Y-m-d", strtotime($data ." -1 month"));
            $afterDay = date("Y-m-d", strtotime($data ." +1 month"));
            
            for ($i = 0; $i < 7; $i++) {
                $daysWeek[] = date('l', $nextSunday);
                $nextSunday = strtotime('+1 day', $nextSunday);
            }
            
            $emptyRow = date('w', strtotime("{$year}-{$month}-01"));
        
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th class="col-md-5">'.ucfirst($currentMonth).' '.$year.'</th>
                            <th class="col-md-1"><button type="button" class="btn btn-primary rounded-pill"><a href="/agenda/'.$beforeDay.'" title="'.$beforeDay.'" class="text-white text-decoration-none">&#8592;</a></button></th>
                            <th class="col-md-1"><button type="button" class="btn btn-primary rounded-pill"><a href="/agenda/'.$afterDay.'" title="'.$afterDay.'" class="text-white text-decoration-none">&#8594;</a></button></th>
                        </tr>
                    </thead>
                </table>
                
                <table class="table text-center">
                    <thead>
                        <tr>';
                        
            foreach ($daysWeek as $key => $dayWeek) {
                echo '<th class="col-md-1">'.ucfirst($dayWeek).'</th>';
            }

            echo '</tr></thead>
                    <tbody><tr>';

            for ($i = 0; $i < $emptyRow; $i++) {
                echo '<td class="bg-light"></td>';
            }

            for ($i = '1'; $i <= $dayMonth; $i++) {
                $num_padded = sprintf("%02d", $i);

                if ($month.$day == date("m").$num_padded) {
                    echo '<td>
                            <div class="p-2 mb-2 bg-white text-dark fw-bolder">' . $i . '</div>';

                    $month_days = $year . '-' . $month . '-' . $i;
                    $responseDbEvent = Data::viewAgenda($month_days, "agenda");

                    foreach ($responseDbEvent as $row => $data) {
                        if ($_SESSION["level"] == '1' || $_SESSION["id"] == $data["related_employee"]) {
                            if ($_SESSION["level"] == '1') {
                                $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
                            }

                            if (date("Y-m", strtotime($data["agenda_date"])) == $year.'-'.$month) {
                                echo '<div class="p-1 mb-3 bg-warning rounded position-relative">
                                        &#9202; ' . date("H:i", strtotime($data["agenda_time"])) . '<br> &#128197; ' . $data["event"];
                                        
                                        if (!empty($responseDbEmployee["name"])) {
                                            echo '<br> &#128100;  ' . $responseDbEmployee["name"];
                                        }

                                        echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <a href="/agenda-delete/' . $data["id"] . '" title="Delete" class="text-white text-decoration-none">&#10005;</a>
                                        </span>
                                    </div>';
                            }
                        }
                    }

                    echo '</td>';
                } else {
                    $num_padded = sprintf("%02d", $i);

                    if ($month.$day > date("m").$num_padded) {
                        echo '<td>
                                <div class="p-2 mb-2 bg-white text-dark">' . $i .'</div>';

                        $month_days = $year . '-' . $month . '-' . $i;
                        $responseDbEvent = Data::viewAgenda($month_days, "agenda");

                        foreach ($responseDbEvent as $row => $data) {
                            if ($_SESSION["level"] == '1' || $_SESSION["id"] == $data["related_employee"]) {
                                if ($_SESSION["level"] == '1') {
                                    $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
                                }

                                if (date("Y-m", strtotime($data["agenda_date"])) == $year.'-'.$month) {
                                    echo '<div class="p-1 mb-3 bg-secondary rounded position-relative">
                                            &#9202; <s>' . date("H:i", strtotime($data["agenda_time"])) . '</s><br> &#128197; <s>' . $data["event"] . '</s>';
                                            
                                            if (!empty($responseDbEmployee["name"])) {
                                                echo '<br> &#128100;  <s>' . $responseDbEmployee["name"] . '</s>';
                                            }
                                            
                                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                <a href="/agenda-delete/' . $data["id"] . '" title="Delete" class="text-white text-decoration-none">&#10005;</a>
                                            </span>
                                        </div>';
                                }
                            }
                        }

                        echo '</td>';
                    } else {
                        echo '<td>
                                <div class="p-2 mb-2 bg-white text-dark">' . $i .'</div>';

                        $month_days = $year . '-' . $month . '-' . $i;
                        $responseDbEvent = Data::viewAgenda($month_days, "agenda");

                        foreach ($responseDbEvent as $row => $data) {
                            if ($_SESSION["level"] == '1' || $_SESSION["id"] == $data["related_employee"]) {
                                if ($_SESSION["level"] == '1') {
                                    $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
                                }

                                if (date("Y-m", strtotime($data["agenda_date"])) == $year.'-'.$month) {
                                    echo '<div class="p-1 mb-3 bg-info rounded position-relative">
                                            &#9202; ' . date("H:i", strtotime($data["agenda_time"])) . '<br> &#128197; ' . $data["event"];
                                            
                                            if (!empty($responseDbEmployee["name"])) {
                                                echo '<br> &#128100;  ' . $responseDbEmployee["name"];
                                            }
                                            
                                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                <a href="/agenda-delete/' . $data["id"] . '" title="Delete" class="text-white text-decoration-none">&#10005;</a>
                                            </span>
                                        </div>';
                                }
                            }
                        }

                        echo '</td>';
                    }
                }
                        
                if (($i + $emptyRow) % 7 == '0') {
                    echo '</tr><tr>';
                }
            }
            
            for ($i = 0; ($i + $emptyRow + $dayMonth) % 7 != '0'; $i++) {
                echo '<td class="bg-light"></td>';
            }

            echo '</tr></tbody></table>';
        }

        public function viewAllInvoices() {
            $responseDb = Data::viewAllInvoices($_SESSION["id"], "works");

            if (count($responseDb) == '0') {
                echo '<tr>
                        <td scope="row" colspan="1">No data!</td>
                        <td scope="row" colspan="1"></td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                $responseDbProject = Data::getProjectNameInvoices($data["related_project"], "projects");
                $responseDbLink = Data::viewWorkInvoice($data["related_project"], $_SESSION["id"], "works");

                echo '<tr>
                        <td class="align-middle col-9">' . $responseDbProject["project"] . '</td>';

                        if ($_SESSION["level"] == '1') {
                            echo '<td class="align-middle d-flex justify-content-end"><a href="/show-invoice/' . $data["related_project"] . '" title="Show invoice"><button class="btn btn-success">&#128065;</button></a></td>';
                        } else {
                            if (count($responseDbLink) != '0') {
                                echo '<td class="align-middle d-flex justify-content-end"><a href="/show-invoice/' . $data["related_project"] . '" title="Show invoice"><button class="btn btn-success">&#128065;</button></a></td>';
                            } else {
                                echo '<td>&nbsp;</td>';
                            }
                        }

                    echo '</tr>';
            }
        }

        public function paidStatus() {
            $responseDb = Data::paidStatus($_GET["id"], "works");
    
            foreach ($responseDb as $row => $data) {
                if ($data["paid"] == '1') {
                    echo '<option value="1">Yes</option>
                        <option value="0">No</option>';
                } else {
                    echo '<option value="0">No</option>
                        <option value="1">Yes</option>';
                }                
            }
        }

        public function viewAllWork() {
            if ($_SESSION["level"] == '1') {
                $responseDb = Data::viewAllWork("", "works");
            } else {
                $responseDb = Data::viewAllWork($_SESSION["id"], "works");
            }

            if (count($responseDb) == '0') {
                echo '<tr>
                        <td scope="row" colspan="11">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                $responseDb = Data::viewRelatedProject($data["related_project"], "projects");
                $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
                $start_time = new DateTimeImmutable("" . $data["start_time"] . " Europe/Rome");
                $end_time = new DateTimeImmutable("" . $data["end_time"] . " Europe/Rome");
                $interval = $end_time->diff($start_time);    
                $time = ($interval->format("%a") * 24) + $interval->format("%H"). ":". $interval->format("%I");
                list($h, $m) = explode(':',$time);
                $decimal = $m/60;
                $hoursAsDecimal = $h+$decimal;
                $price = $responseDbEmployee["employee_rate"];
                $result = $hoursAsDecimal*$price;
                $totalResult[] = $result;
                $totalTime[] = $time;

                if ($data["paid"] == '0') {
                    $start_time_partial = new DateTimeImmutable("" . $data["start_time"] . " Europe/Rome");
                    $end_time_partial = new DateTimeImmutable("" . $data["end_time"] . " Europe/Rome");
                    $interval_partial = $end_time_partial->diff($start_time_partial);    
                    $time_partial = ($interval_partial->format("%a") * 24) + $interval_partial->format("%H"). ":". $interval_partial->format("%I");
                    list($h, $m) = explode(':',$time_partial);
                    $decimal_partial = $m/60;
                    $hoursAsDecimal_partial = $h+$decimal_partial;
                    $price = $responseDbEmployee["employee_rate"];
                    $result_partial = $hoursAsDecimal_partial*$price;
                    $totalResultPartial[] = $result_partial;
                    $totalTimePartial[] = $time_partial;
                    $employee = explode(" ", $responseDbEmployee["name"]);
                    $classId = str_shuffle(preg_replace('/[^a-zA-Z\s!?.,\'\"]+/', '', strtolower($responseDb["project"])));

                    echo '<tr>
                            <th scope="row" class="align-middle">' . $responseDb["project"] . '</th>
                            <td class="align-middle d-none d-lg-table-cell">' . $employee[0] . '</td>
                            <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                            <td class="align-middle d-none d-lg-table-cell">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } else { echo '<div id="' . $classId . '"></div>'; } echo '</td>
                            <td class="align-middle">' . $time . '</td>
                            <td class="align-middle d-none d-lg-table-cell"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                            <td class="align-middle">' . nl2br($data["comment"]) . '</td>
                            <td class="col-sm-2 align-middle d-none d-lg-table-cell">'; if ($data["paid"] == '1') { echo '<p class="text-success">Yes</p>'; } else { echo '<p class="text-danger">No</p>'; }  echo '</td>';

                        if (empty($data["end_time"])) {
                            echo '<td class="align-middle">
                                    <form method="post">
                                        <input type="hidden" value="' . $data["id"] . '" name="id">
                                        <button type="submit" id="endTime" name="endTime" class="btn btn-block btn-primary">Stop</button>
                                    </form>
                                </td>';
                        } else {
                            echo '<td class="align-middle">&nbsp;</td>';
                        }
                        
                        echo '<td class="align-middle"><a href="/update-timesheet/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>
                        <td class="align-middle"><a href="/worksheet/delete-timesheet/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#9587;</button></a></td>
                    </tr>';
                    
                    if (empty($data["end_time"])) {
                        echo '<script type="text/javascript">
                                function ' . $classId . '() {
                                    var today = new Date();
                                    var start_date = new Date("' . date("Y-m-d H:i:s", strtotime($data["start_time"])) . '");
                                    var diffMs = (today - start_date);
                                    var diffDays = Math.floor(diffMs / 86400000);
                                    var diffHrs = Math.floor((diffMs % 86400000) / 3600000);
                                    var diffMins = Math.floor(((diffMs % 86400000) % 3600000) / 60000);
                                    var diffSecs = Math.floor((((diffMs % 86400000) % 3600000) % 60000) / 1000 );

                                    var hrs = diffHrs;
                                    if (hrs < 10)
                                    hrs = "0" + hrs;

                                    var mins = diffMins;
                                    if (mins < 10)
                                    mins = "0" + mins;

                                    var secs = diffSecs;
                                    if (secs < 10)
                                    secs = "0" + secs;

                                    var diff = hrs + ":" + mins + ":" + secs;
                                
                                    document.getElementById(\'' . $classId . '\').textContent = diff;
                                }

                                setInterval(' . $classId . ', 1000);
                            </script>';
                    }
                } else {
                    echo '<tr class="target">
                        <th scope="row" class="align-middle">' . $responseDb["project"] . '</th>
                        <td class="align-middle d-none d-lg-table-cell">' . $responseDbEmployee["name"] . '</td>
                        <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                        <td class="align-middle d-none d-lg-table-cell">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } echo '</td>
                        <td class="align-middle">' . $time . '</td>
                        <td class="align-middle d-none d-lg-table-cell"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                        <td class="align-middle">' . nl2br($data["comment"]) . '</td>
                        <td class="col-sm-2 align-middle d-none d-lg-table-cell">'; if ($data["paid"] == '1') { echo '<p class="text-success">Yes</p>'; } else { echo '<p class="text-danger">No</p>'; }  echo '</td>';

                        if (empty($data["end_time"])) {
                            echo '<td class="align-middle">
                                    <form method="post">
                                        <input type="hidden" value="' . $data["id"] . '" name="id">
                                        <button type="submit" id="endTime" name="endTime" class="btn btn-block btn-primary">Stop</button>
                                    </form>
                                </td>';
                        } else {
                            echo '<td class="align-middle">&nbsp;</td>';
                        }
                        
                        echo '<td class="align-middle"><a href="/update-timesheet/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>
                        <td class="align-middle"><a href="/worksheet/delete-timesheet/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#9587;</button></a></td>
                    </tr>';
                }
            }

            if (!empty($totalResult)) {
                $sum = strtotime('00:00:00');
                $time = 0;
                
                foreach ($totalTime as $element) {
                    $timeInSec = strtotime($element) - $sum;
                    $time = $time + $timeInSec;
                }
                
                $h = intval($time / 3600);
                $time = $time - ($h * 3600);
                $m = str_pad(intval($time / 60), 2, '0', STR_PAD_LEFT);
                $timeOutput = ("$h:$m");
                $sum_partial = strtotime('00:00:00');
                $time_partial = 0;
                
                if (is_array($totalTimePartial)) {
                    foreach ($totalTimePartial as $element_partial) {
                        $timeInSecPartial = strtotime($element_partial) - $sum_partial;
                        $time_partial = $time_partial + $timeInSecPartial;
                    }
                    
                    $h_partial = intval($time_partial / 3600);
                    $time_partial = $time_partial - ($h_partial * 3600);
                    $m_partial = str_pad(intval($time_partial / 60), 2, '0', STR_PAD_LEFT);
                    $timeOutputPartial = ("$h_partial:$m_partial");
                } else {
                    $timeOutputPartial = '0:00';
                    $totalResultPartial[] = null;
                }

                echo '<tr class="table-dark">
                        <th colspan="3" class="d-none d-lg-table-cell">&nbsp;</th>
                        <th class="text-end" scope="row">TOTAL:</th>
                        <th scope="row">' . $timeOutputPartial . '</th>
                        <th scope="row"> &euro; ' . number_format(array_sum($totalResultPartial), 2, ',', '.') . '</th>
                        <th colspan="5"> GRAND TOTAL: ' . $timeOutput . ' &euro; ' . number_format(array_sum($totalResult), 2, ',', '.') . '</th>
                    </tr>';  
            }
        }

        public function viewInvoice() {
            $responseDb = Data::viewWorkInvoice($_GET["id"], $_SESSION["id"], "works");
            $responseDbProject = Data::viewRelatedProject($_GET["id"], "projects");

            foreach ($responseDb as $row => $data) {
                $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
            }

            $date = new DateTime('now', new DateTimeZone("Europe/Rome"));
            $now_time = $date->format("H:i:s d-m-Y");

            echo '<hr>
                <div class="container p-1">
                    <div class="row">
                        <div class="col-2 p-2">Date:</div>
                        <div class="col p-2">' . $now_time . '</div>
                    </div>
                </div>

                <div class="container p-1">
                    <div class="row">
                        <div class="col-2 p-2">Employee name:</div>
                        <div class="col p-2">' . $responseDbEmployee["name"] . '</div>
                    </div>
                </div>

                <div class="container p-1">
                    <div class="row">
                        <div class="col-2 p-2">Project name:</div>
                        <div class="col p-2">' . $responseDbProject["project"] . '</div>
                    </div>
                </div>

                <div class="container p-1">
                    <div class="row">
                        <div class="col-2 p-2">Rate/Hour:</div>
                        <div class="col p-2"> &euro; ' . $responseDbEmployee["employee_rate"] . '</div>
                    </div>
                </div>

                <hr>
            
                <thead>
                    <tr>
                        <th scope="col" class="col-md-2">Start</th>
                        <th scope="col" class="col-md-2">Stop</th>
                        <th scope="col">Total</th>
                        <th scope="col" class="col-md-1">Rate</th>
                        <th scope="col" class="col-md-5">Comment</th>
                        <th scope="col" class="col-md-1">Paid</th>
                    </tr>
                </thead>';
    
            foreach ($responseDb as $row => $data) {
                $start_time = new DateTimeImmutable("" . $data["start_time"] . " Europe/Rome");
                $end_time = new DateTimeImmutable("" . $data["end_time"] . " Europe/Rome");
                $interval = $end_time->diff($start_time);    
                $time = ($interval->format("%a") * 24) + $interval->format("%H"). ":". $interval->format("%I");
                list($h, $m) = explode(':',$time);
                $decimal = $m/60;
                $hoursAsDecimal = $h+$decimal;
                $price = $responseDbEmployee["employee_rate"];
                $result = $hoursAsDecimal*$price;
                $totalRate[] = $result;
                $totalTime[] = $time;

                if ($data["paid"] == '1') {
                    $paid = 'Yes';
                } else {
                    $paid = 'No';
                }
                
                echo '<tbody>
                        <tr>
                            <td class="w-15 align-middle">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                            <td class="w-15 align-middle">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } echo '</td>
                            <td class="w-15 align-middle">' . $time . '</td>
                            <td class="w-15 align-middle"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                            <td class="w-25 align-middle">' . nl2br($data["comment"]) . '</td>
                            <td class="w-15 align-middle">' . $paid . '</td>
                        </tr>
                    </tbody>';
            }

            if (!empty($totalRate)) {             
                $sum = strtotime('00:00:00');
                $time = 0;
                
                foreach ($totalTime as $element) {
                    $timeInSec = strtotime($element) - $sum;
                    $time = $time + $timeInSec;
                }
                
                $h = intval($time / 3600);
                $time = $time - ($h * 3600);
                $m = str_pad(intval($time / 60), 2, '0', STR_PAD_LEFT);
                $timeOutput = ("$h:$m");
            
            
                echo '<tr class="table-primary">
                        <th colspan="1">&nbsp;</th>
                        <th class="text-end" scope="row">TOTAL:</th>
                        <th scope="row">' . $timeOutput . '</th>
                        <th colspan="4"> &euro; ' . number_format(array_sum($totalRate), 2, ',', '.') . '</th>
                    </tr>';
            }
        }

        public function viewEmployee() {
            $responseDb = Data::viewEmployee("employees");
    
            foreach ($responseDb as $row => $data) {
                echo '<option value="' . $data["id"] . '">' . $data["name"] . '</option>';
            }
        }

        public function viewProjectDetails() {
            $dataController = $_GET["id"];
            $responseDb = Data::updateProject($dataController, "projects");

            echo '<div class="my-2 form-group">
                        <label>Project name</label>
                        <input type="text" class="form-control" value="' . $responseDb["project"] . '" readonly>
                    </div>
                    
                    <div class="my-2 form-group">
                        <label>Project details</label>
                        <textarea rows="15" class="form-control" readonly>' . $responseDb["details"] . '</textarea>
                    </div>';
        }

        // Update
        public function updateEmployee() {
            if ($_SESSION["level"] == '1') {
                $dataController = $_GET["id"];
            } else {
                if ($_GET["id"] == $_SESSION["id"]) {
                    $dataController = $_GET["id"];
                } else {
                    header("location: /404");
                }
            }

            $responseDb = Data::updateEmployee($dataController, "employees");
    
            echo '<div class="my-2 form-group">
                        <label for="name">Name and Surname</label>
                        <input type="text" id="name" name="name" class="form-control" value="' . $responseDb["name"] . '" required>
                        <input type="hidden" value="' . $responseDb["id"] . '" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control"  value="' . $responseDb["email"] . '" aria-describedby="emailHelp" required>
                    </div>

                    <div class="my-2 form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="" class="form-control">
                    </div>';

                    if ($_SESSION["level"] == '1') {
                        echo '<div class="my-2 form-group">
                            <label for="level">Level</label>
                            <select id="level" name="level" class="form-select" required>
                                <option value="' . $responseDb["level"] . '" selected>'; if ($responseDb["level"] == '1') { echo 'Admin'; } elseif ($responseDb["level"] == 2) { echo 'Employee'; }  echo '</option>
                                <option value="" disabled>Select</option>
                                <option value="1">Admin</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>';
                    }

                    echo '<div class="my-2 form-group">
                            <label for="rate">Rate/Hour (&euro;)</label>';

                            if ($_SESSION["level"] == '1') {
                                echo '<input type="number" id="rate" name="rate" class="form-control" value="' . $responseDb["employee_rate"] . '">';
                            } else {
                                echo '<input type="number" id="rate" name="rate" class="form-control" value="' . $responseDb["employee_rate"] . '" readonly>';
                            }
                        
                        echo '</div>

                        <div class="my-2 form-group">
                            <button type="submit" id="update-employee" name="update-employee" class="btn btn-block btn-primary">Send</button>
                        </div>';
        }

        public function updateEmployeeData() {
            if (isset($_POST["update-employee"])) {        
                $dataController = array(
                    "id" => $_POST["id"],
                    "name" => $_POST["name"],
                    "email" => $_POST["email"],
                    "password" => $_POST["password"],
                    "level" => $_POST["level"],
                    "rate" => $_POST["rate"],
                    "csrf" => $_POST["password"]
                );
        
                $responseDb = Data::updateEmployeeData($dataController, "employees");
        
                if ($responseDb == "success") {
                    header("location: /update-employee/" . $_POST["id"] . "/update");
                } else {
                    header("location: /update-employee/" . $_POST["id"] . "/error");
                }
        
            }
        }

        public function updateTimeSheet() {
            $dataController = $_GET["id"];
            $responseDb = Data::updateTimeSheet($dataController, "works");
            $responseDbProject = Data::getProjectName($responseDb["related_project"], "projects");
            $responseDbEmployee = Data::getEmployeeName($responseDb["related_employee"], "employees");
    
            echo '<div class="my-2 form-group">
                        <label for="project">Project</label>
                        <select class="form-select" id="project "name="project">
                            <option value="' . $responseDb["related_project"] . '" selected>' . $responseDbProject["project"] . '</option>
                            <option value="" disabled>Select</option>';
                                $viewProjects = new MvcController();
                                $viewProjects->viewProjects();
                        echo '</select>
                        <input type="hidden" value="' . $responseDb["id"] . '" id="id" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <label for="employee">Employee</label>
                        <select class="form-select" id="employee "name="employee">
                            <option value="' . $responseDb["related_employee"] . '" selected>' . $responseDbEmployee["name"] . '</option>
                            <option value="" disabled>Select</option>';
                                $viewEmployee = new MvcController();
                                $viewEmployee->viewEmployee();
                        echo '</select>
                    </div>

                    <div class="my-2 form-group">
                        <label for="start_time">Start</label>
                        <input type="text" id="start_time" name="start_time" value="' . $responseDb["start_time"] . '" class="form-control">
                    </div>

                    <div class="my-2 form-group">
                        <label for="end_time">Stop</label>
                        <input type="text" id="end_time" name="end_time" value="' . $responseDb["end_time"] . '" class="form-control">
                    </div>

                    <div class="my-2 form-group">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3">' . $responseDb["comment"] . '</textarea>
                    </div>

                    <div class="my-2 form-group">
                        <label for="rate">Rate/Hour</label>
                        <input type="number" id="rate" name="rate" value="' . $responseDbEmployee["employee_rate"] . '" class="form-control" readonly>
                    </div>
                    
                    <div class="my-2 form-group">
                        <label for="paid">Paid</label>
                            <select class="form-select" id="paid "name="paid">';
                                $paidStatus = new MvcController();
                                $paidStatus->paidStatus();
                    echo '</select>
                        </div>

                    <div class="my-2 form-group">
                        <label for="last_edit" class="form-label">Last edit on ' . date("d-m-Y H:i", strtotime($responseDb["last_edit"])) . ' by ' . $responseDbEmployee["name"] . '</label>
                    </div>
                        
                    <div class="my-2 form-group">
                        <button type="submit" id="update-timesheet" name="update-timesheet" class="btn btn-block btn-primary">Send</button>
                    </div>';
        }

        public function updateTimeSheetData() {
            if (isset($_POST["update-timesheet"])) {
                date_default_timezone_set('Europe/Rome');
                
                $dataController = array(
                    "id" => $_POST["id"],
                    "project" => $_POST["project"],
                    "employee" => $_POST["employee"],
                    "start_time" => $_POST["start_time"],
                    "end_time" => $_POST["end_time"],
                    "comment" => $_POST["comment"],
                    "rate" => $_POST["rate"],
                    "paid" => $_POST["paid"],
                    "last_edit" => date('Y-m-d H:i:s')
                );
        
                $responseDb = Data::updateTimeSheetData($dataController, "works");
        
                if ($responseDb == "success") {
                    header("location: /worksheet/update");
                } else {
                    header("location: /worksheet/error");
                }
        
            }
        }

        public function updateProject() {
            $dataController = $_GET["id"];
            $responseDb = Data::updateProject($dataController, "projects");
    
            echo '<div class="my-2 form-group">
                        <label for="project">Project name</label>
                        <input type="text" id="project" name="project" class="form-control" value="' . $responseDb["project"] . '" required>
                        <input type="hidden" value="' . $responseDb["id"] . '"id="id" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <label for="details">Project details</label>
                        <textarea id="details" name="details" class="form-control" rows="15">' . $responseDb["details"] . '</textarea>
                    </div>

                    <div class="my-2 form-group">
                        <button type="submit" id="update-project" name="update-project" class="btn btn-block btn-primary">Send</button>
                    </div>';
        }

        public function updateProjectData() {
            if (isset($_POST["update-project"])) {        
                $dataController = array(
                    "id" => $_POST["id"],
                    "project" => $_POST["project"],
                    "details" => $_POST["details"]
                );
        
                $responseDb = Data::updateProjectData($dataController, "projects");
        
                if ($responseDb == "success") {
                    header("location: /projects/update");
                } else {
                    header("location: /projects/error");
                }
        
            }
        }

        // Delete
        public function deleteEmployeeData() {
            if (isset($_GET["delete-employee"])) {
                $dataController = $_GET["delete-employee"];
                $responseDB = Data::deleteEmployeeData($dataController, "employees");
                $responseDBWorks = Data::deleteWorkUserData($dataController, "works");
                $responseDBAgenda = Data::deleteAgendaUserData($dataController, "agenda");

                if ($responseDB == "success" && $responseDBWorks == "success" && $responseDBAgenda == "success") {
                    header("location: /employees/delete");
                } else {
                    header("location: /employees/error");
                }
            }
        }

        public function deleteProjectData() {
            if (isset($_GET["delete-project"])) {
                $dataController = $_GET["delete-project"];
                $responseDB = Data::deleteProjectData($dataController, "projects");

                if ($responseDB == "success") {
                    header("location: /projects/delete");
                } else {
                    header("location: /projects/error");
                }
            }
        }

        public function deleteWorkData() {
            if (isset($_GET["delete-timesheet"])) {
                $dataController = $_GET["delete-timesheet"];
                $responseDB = Data::deleteWorkData($dataController, "works");

                if ($responseDB == "success") {
                    header("location: /worksheet/delete");
                } else {
                    header("location: /worksheet/error");
                }
            }
        }

        public function deleteAgendaData() {
            if (isset($_GET["delete-agenda"])) {
                $dataController = $_GET["delete-agenda"];
                $responseDB = Data::deleteAgendaData($dataController, "agenda");

                if ($responseDB == "success") {
                    header("location: /agenda/delete");
                } else {
                    header("location: /agenda/error");
                }
            }
        }
    }