<?php
    require_once 'connection.php';

    class Data extends Connection {
        // Create
        public static function createEmployeeData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT email FROM $table WHERE email = :email");
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->execute();

            $count = (int)$stmt->fetchAll();

            if ($count >= "1") {
                return 'duplicate';
            } else {
                $stmt = Connection::connect()->prepare("INSERT INTO $table (name, email, password, level, employee_rate, csrf) VALUES (:name, :email, :password, :level, :employee_rate, :csrf)");
                $stmt->bindParam(":name", $inputFormData["name"], PDO::PARAM_STR);
                $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
                $stmt->bindParam(":password", $inputFormData["password"], PDO::PARAM_STR);
                $stmt->bindParam(":level", $inputFormData["level"], PDO::PARAM_INT);
                $stmt->bindParam(":employee_rate", $inputFormData["rate"], PDO::PARAM_INT);
                $stmt->bindParam(":csrf", $inputFormData["password"], PDO::PARAM_STR);
                
                if ($stmt->execute()) {
                    return 'success';
                } else {
                    return 'error';
                }
            }

            $stmt = null;
        }

        public static function startTime($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("INSERT INTO $table (related_employee, related_project, start_time, comment) VALUES (:related_employee, :related_project, :start_time, :comment)");
            $date = new DateTime('now', new DateTimeZone("Europe/Rome"));
            $now_time = $date->format("Y-m-d H:i:s");
            $stmt->bindParam(":related_employee", $inputFormData["related_employee"], PDO::PARAM_STR);
            $stmt->bindParam(":related_project", $inputFormData["project"], PDO::PARAM_STR);
            $stmt->bindParam(":start_time", $now_time, PDO::PARAM_STR);
            $stmt->bindParam(":comment", $inputFormData["comment"], PDO::PARAM_STR);
           
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }

            $stmt = null;
        }

        public static function addAgenda($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("INSERT INTO $table (related_employee, agenda_date, agenda_time, event) VALUES (:related_employee, :agenda_date, :agenda_time, :event)");
            $stmt->bindParam(":related_employee", $inputFormData["related_employee"], PDO::PARAM_STR);
            $stmt->bindParam(":agenda_date", $inputFormData["datetime"], PDO::PARAM_STR);
            $stmt->bindParam(":agenda_time", $inputFormData["timedate"], PDO::PARAM_STR);
            $stmt->bindParam(":event", $inputFormData["event"], PDO::PARAM_STR);
           
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }

            $stmt = null;
        }

        public static function addProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("INSERT INTO $table (project, details) VALUES (:project, :details)");
            $stmt->bindParam(":project", $inputFormData["project"], PDO::PARAM_STR);
            $stmt->bindParam(":details", $inputFormData["details"], PDO::PARAM_STR);
           
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }

            $stmt = null;
        }

        // Read
        public static function readEmployeeData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT id, email, password, level, csrf FROM $table WHERE email = :email");
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

            $stmt = null;
        }

        public static function recoverPassword($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT email FROM $table WHERE email = :email");
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

            $stmt = null;
        }

        public static function viewAllEmployee($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewAllProjects($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewProjects($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewAgenda($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE agenda_date = :agenda_date");
            $stmt->bindParam(':agenda_date' , $inputFormData, PDO::PARAM_STR);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewAllInvoices($inputFormData, $table) {
            if ($_SESSION["level"] == '1') {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table GROUP BY related_project");
            } else {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :id GROUP BY related_project");
                $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            }
            
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function paidStatus($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewAllWork($inputFormData, $table) {
            if (empty($inputFormData)) {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table ORDER BY related_project, related_employee, paid, id DESC");
            } else {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :id ORDER BY related_project, related_employee, paid, id DESC");
                $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            }

            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function viewWorkInvoice($related_project, $related_employee, $table) {
            if ($_SESSION["level"] == '1') {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_project = :related_project AND end_time <> '' ORDER BY id ASC");
                $stmt->bindParam(':related_project' , $related_project, PDO::PARAM_INT);
            } else {
                $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :related_employee AND related_project = :related_project AND end_time <> '' ORDER BY id ASC");
                $stmt->bindParam(':related_project' , $related_project, PDO::PARAM_INT);
                $stmt->bindParam(':related_employee' , $related_employee, PDO::PARAM_INT);
            }

            $stmt->execute();

            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function updateEmployee($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        public static function viewRelatedProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        public static function updateTimeSheet($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }
        
        public static function getProjectName($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        public static function getProjectNameInvoices($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        public static function viewEmployee($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt = null;
        }

        public static function getEmployeeName($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        public static function updateProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt = null;
        }

        // Update
        public static function updateEmployeeData($inputFormData , $table) {
            if (!empty($inputFormData["password"])) {
                $stmt = Connection::connect()->prepare("UPDATE $table SET name = :name, email = :email, password = :password, level = :level, employee_rate = :rate, csrf = :csrf WHERE id = :id");
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":name", $inputFormData["name"], PDO::PARAM_STR);
                $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
                $stmt->bindParam(":password", crypt($inputFormData["password"], '$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$'), PDO::PARAM_STR);
                $stmt->bindParam(":level", $inputFormData["level"], PDO::PARAM_STR);
                $stmt->bindParam(":rate", $inputFormData["rate"], PDO::PARAM_INT);
                $stmt->bindParam(":csrf", crypt($inputFormData["password"], '$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$'), PDO::PARAM_STR);
            } else {
                $stmt = Connection::connect()->prepare("UPDATE $table SET name = :name, email = :email, level = :level, employee_rate = :rate WHERE id = :id");
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":name", $inputFormData["name"], PDO::PARAM_STR);
                $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
                $stmt->bindParam(":level", $inputFormData["level"], PDO::PARAM_STR);
                $stmt->bindParam(":rate", $inputFormData["rate"], PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function changePassword($inputFormData , $table) {
            $stmt = Connection::connect()->prepare("UPDATE $table SET password = :password, csrf = :password WHERE email = :email");
            $secureRandomPassword = crypt($inputFormData["randomPassword"], '$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$');
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->bindParam(":password", $secureRandomPassword, PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function endTime($inputFormData , $table) {
            $stmt = Connection::connect()->prepare("UPDATE $table SET end_time = :end_time WHERE id = :id");
            $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
            $stmt->bindParam(":end_time", $inputFormData["end_time"], PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function updateTimeSheetData($inputFormData , $table) {
            if (!empty($inputFormData["end_time"])) {
                $stmt = Connection::connect()->prepare("UPDATE $table SET related_project = :related_project, related_employee = :related_employee, start_time = :start_time, end_time = :end_time, comment = :comment, paid = :paid, edit_by = :edit_by WHERE id = :id");
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":related_project", $inputFormData["project"], PDO::PARAM_STR);
                $stmt->bindParam(":related_employee", $inputFormData["employee"], PDO::PARAM_STR);
                $stmt->bindParam(":start_time", $inputFormData["start_time"], PDO::PARAM_STR);
                $stmt->bindParam(":end_time", $inputFormData["end_time"], PDO::PARAM_STR);
                $stmt->bindParam(":comment", $inputFormData["comment"], PDO::PARAM_STR);
                $stmt->bindParam(":paid", $inputFormData["paid"], PDO::PARAM_INT);
                $stmt->bindParam(":edit_by", $_SESSION["id"], PDO::PARAM_INT);
            } else {
                $stmt = Connection::connect()->prepare("UPDATE $table SET related_project = :related_project, related_employee = :related_employee, start_time = :start_time, comment = :comment, paid = :paid, edit_by = :edit_by WHERE id = :id");
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":related_project", $inputFormData["project"], PDO::PARAM_STR);
                $stmt->bindParam(":related_employee", $inputFormData["employee"], PDO::PARAM_STR);
                $stmt->bindParam(":start_time", $inputFormData["start_time"], PDO::PARAM_STR);
                $stmt->bindParam(":comment", $inputFormData["comment"], PDO::PARAM_STR);
                $stmt->bindParam(":paid", $inputFormData["paid"], PDO::PARAM_INT);
                $stmt->bindParam(":edit_by", $_SESSION["id"], PDO::PARAM_INT);
            }
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function updateProjectData($inputFormData , $table) {
            $stmt = Connection::connect()->prepare("UPDATE $table SET project = :project, details = :details WHERE id = :id");
            $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
            $stmt->bindParam(":project", $inputFormData["project"], PDO::PARAM_STR);
            $stmt->bindParam(":details", $inputFormData["details"], PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        // Delete
        public static function deleteEmployeeData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function deleteProjectData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function deleteWorkData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function deleteWorkUserData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE related_employee = :related_employee");
            $stmt->bindParam(':related_employee', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function deleteAgendaData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }

        public static function deleteAgendaUserData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE related_employee = :related_employee");
            $stmt->bindParam(':related_employee', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt = null;
        }
    }