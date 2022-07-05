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

                $csrf = crypt($inputFormData["password"], '$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$');

                $stmt->bindParam(":name", $inputFormData["name"], PDO::PARAM_STR);
                $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
                $stmt->bindParam(":password", $inputFormData["password"], PDO::PARAM_STR);
                $stmt->bindParam(":level", $inputFormData["level"], PDO::PARAM_INT);
                $stmt->bindParam(":employee_rate", $inputFormData["rate"], PDO::PARAM_INT);
                $stmt->bindParam(":csrf", $csrf, PDO::PARAM_STR);
                
                if ($stmt->execute()) {
                    return 'success';
                } else {
                    return 'error';
                }
            }

            $stmt->close();
        }

        public static function startTime($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("INSERT INTO $table (related_employee, related_project, start_time, comment) VALUES (:related_employee, :related_project, :start_time, :comment)");

            $date = new DateTime(null, new DateTimeZone("Europe/Rome"));
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

            $stmt->close();
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

            $stmt->close();
        }

        public static function addProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("INSERT INTO $table (project) VALUES (:project)");

            $stmt->bindParam(":project", $inputFormData["project"], PDO::PARAM_STR);
           
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }

            $stmt->close();
        }

        // Read
        public static function readEmployeeData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT id, email, password, level, csrf FROM $table WHERE email = :email");
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

            $stmt->close();
        }

        public static function recoverPassword($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT email FROM $table WHERE email = :email");
            $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch();

            $stmt->close();
        }

        public static function viewAllEmployee($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function viewAllProjects($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function viewProjects($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function viewAgenda($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_STR);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function viewAgendaEvent($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE agenda_date = :agenda_date");
            $stmt->bindParam(':agenda_date' , $inputFormData, PDO::PARAM_STR);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function viewAllInvoices($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :id GROUP BY related_project");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function paidStatus($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
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
            
            $stmt->close();
        }

        public static function viewWorkInvoice($related_project, $related_employee, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE related_employee = :related_employee AND related_project = :related_project AND end_time <> '' AND paid = 0 ORDER BY id ASC");
            $stmt->bindParam(':related_project' , $related_project, PDO::PARAM_INT);
            $stmt->bindParam(':related_employee' , $related_employee, PDO::PARAM_INT);

            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function updateEmployee($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        public static function viewRelatedProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        public static function updateTimeSheet($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }
        
        public static function getProjectName($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        public static function getProjectNameInvoices($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        public static function viewEmployee($table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table");
            $stmt->execute();
        
            return $stmt->fetchAll();
            
            $stmt->close();
        }

        public static function getEmployeeName($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        public static function updateProject($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("SELECT * FROM  $table WHERE id = :id");
            $stmt->bindParam(':id' , $inputFormData, PDO::PARAM_INT);
            $stmt->execute();
        
            return $stmt->fetch();
            
            $stmt->close();
        }

        // Update
        public static function updateEmployeeData($inputFormData , $table) {
            if (!empty($inputFormData["password"])) {
                $stmt = Connection::connect()->prepare("UPDATE $table SET name = :name, email = :email, password = :password, level = :level, employee_rate = :rate, csrf = :password WHERE id = :id");
        
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":name", $inputFormData["name"], PDO::PARAM_STR);
                $stmt->bindParam(":email", $inputFormData["email"], PDO::PARAM_STR);
                $stmt->bindParam(":password", crypt($inputFormData["password"], '$5$5crHBIc6qyFtq66Vc3PAge3vQcT3Hvu7Zy4_0VzaQxJ_1pRFVP$'), PDO::PARAM_STR);
                $stmt->bindParam(":level", $inputFormData["level"], PDO::PARAM_STR);
                $stmt->bindParam(":rate", $inputFormData["rate"], PDO::PARAM_INT);
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
        
            $stmt->close();
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
        
            $stmt->close();
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
        
            $stmt->close();
        }

        public static function updateTimeSheetData($inputFormData , $table) {
            if (!empty($inputFormData["end_time"])) {
                $stmt = Connection::connect()->prepare("UPDATE $table SET related_project = :related_project, related_employee = :related_employee, start_time = :start_time, end_time = :end_time, comment = :comment, paid = :paid WHERE id = :id");
        
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":related_project", $inputFormData["project"], PDO::PARAM_STR);
                $stmt->bindParam(":related_employee", $inputFormData["employee"], PDO::PARAM_STR);
                $stmt->bindParam(":start_time", $inputFormData["start_time"], PDO::PARAM_STR);
                $stmt->bindParam(":end_time", $inputFormData["end_time"], PDO::PARAM_STR);
                $stmt->bindParam(":comment", $inputFormData["comment"], PDO::PARAM_STR);
                $stmt->bindParam(":paid", $inputFormData["paid"], PDO::PARAM_INT);
            } else {
                $stmt = Connection::connect()->prepare("UPDATE $table SET related_project = :related_project, related_employee = :related_employee, start_time = :start_time, comment = :comment, paid = :paid WHERE id = :id");
        
                $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
                $stmt->bindParam(":related_project", $inputFormData["project"], PDO::PARAM_STR);
                $stmt->bindParam(":related_employee", $inputFormData["employee"], PDO::PARAM_STR);
                $stmt->bindParam(":start_time", $inputFormData["start_time"], PDO::PARAM_STR);
                $stmt->bindParam(":comment", $inputFormData["comment"], PDO::PARAM_STR);
                $stmt->bindParam(":paid", $inputFormData["paid"], PDO::PARAM_INT);
            }
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt->close();
        }

        public static function updateProjectData($inputFormData , $table) {
            $stmt = Connection::connect()->prepare("UPDATE $table SET project = :project WHERE id = :id");
        
            $stmt->bindParam(":id", $inputFormData["id"], PDO::PARAM_INT);
            $stmt->bindParam(":project", $inputFormData["project"], PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt->close();
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
        
            $stmt->close();
        }

        public static function deleteProjectData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt->close();
        }

        public static function deleteWorkData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt->close();
        }

        public static function deleteAgendaData($inputFormData, $table) {
            $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $inputFormData, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
                return 'success';
            } else {
                return 'error';
            }
        
            $stmt->close();
        }
    }