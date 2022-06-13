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
                    $date = new DateTime(null, new DateTimeZone("Europe/Rome"));
                    $now_time = $date->format("YmdHis");

                    echo '<title>Invoice &#8470; #' . $now_time . '</title>' . "\r\n";
                    echo '<meta name="description" content="' . $_GET["page"] . '">' . "\r\n";
                    echo '<meta name="keywords" content="' . $_GET["page"] . '">' . "\r\n";
                } else {
                    echo '<title>' . ucfirst($_GET["page"]) . ' - WebssWork</title>' . "\r\n";
                    echo '<meta name="description" content="' . $_GET["page"] . '">' . "\r\n";
                    echo '<meta name="keywords" content="' . $_GET["page"] . '">' . "\r\n";
                }
            } else {
                echo '<title>WebssWork</title>' . "\r\n";
                echo '<meta name="description" content="WebssWork">' . "\r\n";
                echo '<meta name="keywords" content="WebssWork">' . "\r\n";
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
                    "rate" => $_POST["rate"]
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
                    $dataController = array(
                        "project" => $_POST["project"],
                        "comment" => $_POST["comment"],
                        "related_employee" => $_POST["related_employee"]
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
                $date = new DateTime(null, new DateTimeZone("Europe/Rome"));
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

        public function addProject() {
            if (isset($_POST["addProject"])) {
                $dataController = array(
                        "project" => $_POST["project"]
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
                    $_SESSION["level"] = $responseDb["level"];
                    $_SESSION["password"] = $responseDb["password"];
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
    
                if ($responseDb["email"] == $_POST["email"]) {
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

        public function viewAllEmployee() {
            $responseDb = Data::viewAllEmployee("employees");

            if (count($responseDb) == 0) {
                echo '<tr>
                        <td scope="row" colspan="6">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                if ($data["level"] == 1) {
                    $level = 'Admin';
                } else {
                    $level = 'Employee';
                }

                echo '<tr>
                        <td class="align-middle d-none d-lg-table-cell">' . $data["name"] . '</td>
                        <td class="align-middle">' . $data["email"] . '</td>
                        <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["registration_date"])) . '</td>
                        <td class="align-middle">' . $level . '</td>
                        <td class="align-middle"><a href="/update-employee/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>
                        <td class="align-middle"><a href="/employees/delete-employee/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#10006;</button></a></td>
                    </tr>';
            }
        }

        public function viewAllProjects() {
            $responseDb = Data::viewAllProjects("projects");

            if (count($responseDb) == 0) {
                echo '<tr>
                        <td scope="row" colspan="1">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                echo '<tr>
                        <td class="align-middle col-9">' . $data["project"] . '</td>
                        <td class="align-middle"><a href="/update-project/' . $data["id"] . '" title="Edit"><button class="btn btn-success">&#9998;</button></a></td>
                        <td class="align-middle"><a href="/projects/delete-project/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#10006;</button></a></td>
                    </tr>';
            }
        }

        public function viewProjects() {
            $responseDb = Data::viewProjects("projects");
    
            foreach ($responseDb as $row => $data) {
                echo '<option value="' . $data["id"] . '">' . $data["project"] . '</option>';
            }
        }

        public function viewAllInvoices() {
            $responseDb = Data::viewAllInvoices($_SESSION["id"], "works");

            if (count($responseDb) == 0) {
                echo '<tr>
                        <td scope="row" colspan="1">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                $responseDbProject = Data::getProjectNameInvoices($data["related_project"], "projects");

                echo '<tr>
                        <td class="align-middle col-9">' . $responseDbProject["project"] . '</td>
                        <td class="align-middle"><a href="/show-invoice/' . $data["related_project"] . '" title="Show invoice"><button class="btn btn-success">&#128065;</button></a></td>
                    </tr>';
            }
        }

        public function paidStatus() {
            $responseDb = Data::paidStatus($_GET["id"], "works");
    
            foreach ($responseDb as $row => $data) {
                if ($data["paid"] == 1) {
                    echo '<option value="1">Yes</option>
                        <option value="0">No</option>';
                } else {
                    echo '<option value="0">No</option>
                        <option value="1">Yes</option>';
                }                
            }
        }

        public function viewAllWork() {
            if ($_SESSION["level"] == "1") {
                $responseDb = Data::viewAllWork("", "works");
            } else {
                $responseDb = Data::viewAllWork($_SESSION["id"], "works");
            }

            if (count($responseDb) == 0) {
                echo '<tr>
                        <td scope="row" colspan="8">No data!</td>
                    </tr>';
            }
    
            foreach ($responseDb as $row => $data) {
                $responseDb = Data::viewRelatedProject($data["related_project"], "projects");
                $responseDbEmployee = Data::updateEmployee($data["related_employee"], "employees");
                $start_time = new DateTime($data["start_time"], new DateTimeZone("Europe/Rome"));
                $end_time = new DateTime($data["end_time"], new DateTimeZone("Europe/Rome"));
                $interval = $end_time->diff($start_time);    
                $time = ($interval->format("%a") * 24) + $interval->format("%H"). ":". $interval->format("%I");
                list($h, $m) = explode(':',$time);
                $decimal = $m/60;
                $hoursAsDecimal = $h+$decimal;
                $price = $responseDbEmployee["employee_rate"];
                $result = $hoursAsDecimal*$price;
                $totalResult[] = $result;
                $totalTime[] = $time;

                if ($data["paid"] == 0) {
                    $start_time_partial = new DateTime($data["start_time"], new DateTimeZone("Europe/Rome"));
                    $end_time_partial = new DateTime($data["end_time"], new DateTimeZone("Europe/Rome"));
                    $interval_partial = $end_time_partial->diff($start_time_partial);    
                    $time_partial = ($interval_partial->format("%a") * 24) + $interval_partial->format("%H"). ":". $interval_partial->format("%I");
                    list($h, $m) = explode(':',$time_partial);
                    $decimal_partial = $m/60;
                    $hoursAsDecimal_partial = $h+$decimal_partial;
                    $price = $responseDbEmployee["employee_rate"];
                    $result_partial = $hoursAsDecimal_partial*$price;
                    $totalResultPartial[] = $result_partial;
                    $totalTimePartial[] = $time_partial;

                    echo '<tr>
                        <th scope="row" class="align-middle">' . $responseDb["project"] . '</th>
                        <td class="align-middle d-none d-lg-table-cell">' . $responseDbEmployee["name"] . '</td>
                        <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                        <td class="align-middle d-none d-lg-table-cell">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } echo '</td>
                        <td class="align-middle">' . $time . '</td>
                        <td class="align-middle d-none d-lg-table-cell"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                        <td class="align-middle">' . nl2br($data["comment"]) . '</td>
                        <td class="col-sm-2 align-middle d-none d-lg-table-cell">'; if ($data["paid"] == 1) { echo '<p class="text-success">Yes</p>'; } else { echo '<p class="text-danger">No</p>'; }  echo '</td>';

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
                        <td class="align-middle"><a href="/worksheet/delete-timesheet/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#10006;</button></a></td>
                    </tr>';
                } else {
                    echo '<tr class="target">
                        <th scope="row" class="align-middle">' . $responseDb["project"] . '</th>
                        <td class="align-middle d-none d-lg-table-cell">' . $responseDbEmployee["name"] . '</td>
                        <td class="align-middle d-none d-lg-table-cell">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                        <td class="align-middle d-none d-lg-table-cell">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } echo '</td>
                        <td class="align-middle">' . $time . '</td>
                        <td class="align-middle d-none d-lg-table-cell"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                        <td class="align-middle">' . nl2br($data["comment"]) . '</td>
                        <td class="col-sm-2 align-middle d-none d-lg-table-cell">'; if ($data["paid"] == 1) { echo '<p class="text-success">Yes</p>'; } else { echo '<p class="text-danger">No</p>'; }  echo '</td>';

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
                        <td class="align-middle"><a href="/worksheet/delete-timesheet/' . $data["id"] . '" title="Delete"><button class="btn btn-danger">&#10006;</button></a></td>
                    </tr>';
                }
            }

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
             
            foreach ($totalTimePartial as $element_partial) {
                $timeInSecPartial = strtotime($element_partial) - $sum_partial;
                $time_partial = $time_partial + $timeInSecPartial;
            }
             
            $h_partial = intval($time_partial / 3600);
            $time_partial = $time_partial - ($h_partial * 3600);
            $m_partial = str_pad(intval($time_partial / 60), 2, '0', STR_PAD_LEFT);
            $timeOutputPartial = ("$h_partial:$m_partial");

            if (!empty($totalResult)) {
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
            $responseDbEmployee = Data::updateEmployee($_SESSION["id"], "employees");
            $date = new DateTime(null, new DateTimeZone("Europe/Rome"));
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
                        <th scope="col" class="col-md-6">Comment</th>
                    </tr>
                </thead>';
    
            foreach ($responseDb as $row => $data) {
                $start_time = new DateTime($data["start_time"], new DateTimeZone("Europe/Rome"));
                $end_time = new DateTime($data["end_time"], new DateTimeZone("Europe/Rome"));
                $interval = $end_time->diff($start_time);    
                $time = ($interval->format("%a") * 24) + $interval->format("%H"). ":". $interval->format("%I");
                list($h, $m) = explode(':',$time);
                $decimal = $m/60;
                $hoursAsDecimal = $h+$decimal;
                $price = $responseDbEmployee["employee_rate"];
                $result = $hoursAsDecimal*$price;
                $totalRate[] = $result;
                $totalTime[] = $time;
                
                echo '<tbody>
                        <tr>
                            <td class="w-15 align-middle">' . date("d-m-Y H:i", strtotime($data["start_time"])) . '</td>
                            <td class="w-15 align-middle">'; if (!empty($data["end_time"])) { echo date("d-m-Y H:i", strtotime($data["end_time"])); } echo '</td>
                            <td class="w-15 align-middle">' . $time . '</td>
                            <td class="w-15 align-middle"> &euro; ' . number_format($result, 2, ',', '.') . '</td>
                            <td class="w-25 align-middle">' . nl2br($data["comment"]) . '</td>
                        </tr>
                    </tbody>';
            }
             
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
            
            if (!empty($totalRate)) {
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

        // Update
        public function updateEmployee() {
            if ($_SESSION["level"] == "1") {
                $dataController = $_GET["id"];
            } else {
                if ($_GET["id"] == $_SESSION["id"]) {
                    $dataController = $_GET["id"];
                } else {
                    header("location: /404");
                }
            }

            $responseDb = Data::updateEmployee($dataController, "employees");
    
            $id = $responseDb["id"];
            $name = $responseDb["name"];
            $email = $responseDb["email"];
            $password = $responseDb["password"];
            $level = $responseDb["level"];
            $employeeRate = $responseDb["employee_rate"];
    
            echo '<div class="my-2 form-group">
                        <label for="name">Name and Surname</label>
                        <input type="text" id="name" name="name" class="form-control" value="' . $name . '" required>
                        <input type="hidden" value="' . $id . '" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control"  value="' . $email . '" aria-describedby="emailHelp" required>
                    </div>

                    <div class="my-2 form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="" class="form-control">
                    </div>';

                    if ($_SESSION["level"] == "1") {
                        echo '<div class="my-2 form-group">
                            <label for="level">Level</label>
                            <select id="level" name="level" class="form-select" required>
                                <option value="' . $level . '" selected>'; if ($level == 1) { echo 'Admin'; } elseif ($level == 2) { echo 'Employee'; }  echo '</option>
                                <option value="" disabled>Select</option>
                                <option value="1">Admin</option>
                                <option value="2">Employee</option>
                            </select>
                        </div>';
                    }

                    echo '<div class="my-2 form-group">
                            <label for="rate">Rate/Hour (&euro;)</label>
                            <input type="number" id="rate" name="rate" class="form-control" value="' . $employeeRate . '">
                        </div>

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
                    "rate" => $_POST["rate"]
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
    
            $id = $responseDb["id"];
            $project = $responseDb["related_project"];
            $employee = $responseDb["related_employee"];
            $start_time = $responseDb["start_time"];
            $end_time = $responseDb["end_time"];
            $comment = $responseDb["comment"];

            $responseDbProject = Data::getProjectName($project, "projects");
            $responseDbEmployee = Data::getEmployeeName($employee, "employees");
    
            echo '<div class="my-2 form-group">
                        <label for="project">Project</label>
                        <select class="form-select" id="project "name="project">
                            <option value="' . $project . '" selected>' . $responseDbProject["project"] . '</option>';
                                $viewProjects = new MvcController();
                                $viewProjects->viewProjects();
                        echo '</select>
                        <input type="hidden" value="' . $id . '" id="id" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <label for="employee">Employee</label>
                        <select class="form-select" id="employee "name="employee">
                            <option value="' . $employee . '" selected>' . $responseDbEmployee["name"] . '</option>';
                                $viewEmployee = new MvcController();
                                $viewEmployee->viewEmployee();
                        echo '</select>
                    </div>

                    <div class="my-2 form-group">
                        <label for="start_time">Start</label>
                        <input type="text" id="start_time" name="start_time" value="' . $start_time . '" class="form-control">
                    </div>

                    <div class="my-2 form-group">
                        <label for="end_time">Stop</label>
                        <input type="text" id="end_time" name="end_time" value="' . $end_time . '" class="form-control">
                    </div>

                    <div class="my-2 form-group">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3">' . $comment . '</textarea>
                    </div>
                    
                    <div class="my-2 form-group">
                        <label for="paid">Paid</label>
                            <select class="form-select" id="paid "name="paid">';
                                $paidStatus = new MvcController();
                                $paidStatus->paidStatus();
                    echo '</select>
                        </div>
                        
                    <div class="my-2 form-group">
                        <button type="submit" id="update-timesheet" name="update-timesheet" class="btn btn-block btn-primary">Send</button>
                    </div>';
        }

        public function updateTimeSheetData() {
            if (isset($_POST["update-timesheet"])) {
                $dataController = array(
                    "id" => $_POST["id"],
                    "project" => $_POST["project"],
                    "employee" => $_POST["employee"],
                    "start_time" => $_POST["start_time"],
                    "end_time" => $_POST["end_time"],
                    "comment" => $_POST["comment"],
                    "paid" => $_POST["paid"]
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
    
            $id = $responseDb["id"];
            $project = $responseDb["project"];
    
            echo '<div class="my-2 form-group">
                        <label for="project">Project name</label>
                        <input type="text" id="project" name="project" class="form-control" value="' . $project . '" required>
                        <input type="hidden" value="' . $id . '"id="id" name="id">
                    </div>

                    <div class="my-2 form-group">
                        <button type="submit" id="update-project" name="update-project" class="btn btn-block btn-primary">Send</button>
                    </div>';
        }

        public function updateProjectData() {
            if (isset($_POST["update-project"])) {        
                $dataController = array(
                    "id" => $_POST["id"],
                    "project" => $_POST["project"]
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

                if ($responseDB == "success") {
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
    }