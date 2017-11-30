<?php
    class USER
    {
        private $db;
        function __construct($DB_con)
        {
            $this->db = $DB_con;
        }
        public $userFields = ['User_Username', 'User_Password', 'User_Role', 'User_Status'];
        public function check($username) {
            //Checks if the username that has been taken. True if not taken, false if taken.
            $stmt = $this->db->prepare("SELECT * FROM Users WHERE User_Username=:username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0)
            {
                return false;
            }
            return true;
        }
        
        
        
        public function savelog($user, $log) {
            //Logs an action
            
            date_default_timezone_set('Asia/Singapore');
            $time = date("Y-m-d H:i:s");
            $stmt = $this->db->prepare("INSERT INTO logs(user, time, log) VALUES (:user, :time, :log)");
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':log', $log);
            $stmt->execute();
        }
		
        
        
        
        
        
        public function register($username, $password, $role)
        //Adds the provided user into the database and activates it.
        
        {
            try
            {
                $new_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $this->db->prepare("INSERT INTO Users(User_Username,User_Password,User_Role,User_Status) 
                VALUES(:username, :password, :role, 'active')");
                
                $stmt->bindparam(":username", $username);
                $stmt->bindparam(":password", $new_password);
                $stmt->bindparam(":role", $role);
                $stmt->execute(); 
                $this->savelog($_SESSION['username'], "created $role $username");
                return $stmt; 
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }    
        }
        
        public function login($username,$password)
        //Checks the username / password combination for logging in. 
        //Sets session variables
        //Returns true / false
        {
            try
            {
                $stmt = $this->db->prepare("SELECT * FROM Users WHERE User_Username = :username AND User_Status='active' LIMIT 1");
                $stmt->bindparam(":username", $username);
                $stmt->execute();
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount() > 0)
                {
                    //Password verify 
                    if(password_verify($password, $userRow['User_Password']))
                    {
                        $_SESSION['user_ID'] = $userRow['User_ID'];
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $userRow['User_Role'];
                        $this->savelog($_SESSION['username'], 'logged in');
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        
        public function checkPassword($oldpassword) {
            //Checks if the password given is correct, before changing the password
            //Returns true / false
            try
            {
                $stmt = $this->db->prepare("SELECT * FROM Users WHERE User_Username = :username AND User_Status='active' LIMIT 1");
                $stmt->bindparam(":username", $_SESSION['username']);
                $stmt->execute();
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount() > 0)
                {
                    if(password_verify($oldpassword, $userRow['User_Password']))
                    {
                        return true;
                    }
                }
                return false;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        
        public function changePassword($newpassword) {
            //Changes the password of the current user logged in.
            //Check for the same password must be finished prior to this (This has no checks!)
            $new_password = password_hash($newpassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE Users SET User_Password=:password where User_Username=:username AND User_Status='active'");
            $stmt->bindParam(':username', $_SESSION['username']);
            $stmt->bindParam(':password', $new_password);
            $stmt->execute();
            $this->savelog($_SESSION['username'], 'changed password');
        }
        
        public function getLog() {
            //Retrieves the log list
            //Returns an array of the logs
            $stmt = $this->db->prepare("SELECT * FROM logs ORDER BY time DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function getUsers() {
            //Retrieves the user list
            //Returns an array of users
            $stmt = $this->db->prepare("SELECT User_Username, User_Role, User_Status, User_ID FROM Users");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function getModules() {
            //Retrieves the user list
            //Returns an array of Modules
            $stmt = $this->db->prepare("SELECT * FROM modules");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        
        public function getModule($module_ID) {
            //Get the details of a module
            //Return format
            //Array: {true/false, array of user information}
            //[0]: Module Information
            //[1]: Array of questions
            $result = array(false, false);
            $stmt = $this->db->prepare("SELECT * from modules where module_id=:module_ID");
            $stmt->bindParam(':module_ID', $module_ID);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $result[0] = $stmt->fetch();
                $stmt = $this->db->prepare("SELECT * from questions where module_id=:module_ID");
                $stmt->bindParam(':module_ID', $module_ID);
                $stmt->execute();
                $result[1] = $stmt->fetchAll();
            }
            return $result;
        }
        
        public function getUser($user_ID) {
            //Get the details of a user with user_ID=$user_ID
            //Return format
            //Array: {true/false, array of user information}
            //[0]: Result of retrieving. True if successful, False if failed
            //[1]: Array of user information
            $result = array(false, false);
            $stmt = $this->db->prepare("SELECT * from Users where user_ID=:user_ID");
            $stmt->bindParam(':user_ID', $user_ID);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $result[0] = true;
                $result[1] = $stmt->fetch();
            }
            return $result;
        }

        // desc: For a question based on its question_ID, retrieve all the end video IDs for that question
        // and returns an array of video IDs
        // params: $question_ID (int)
        // returns: $result (array of int)
        public function getEndVideosForQuestion($question_ID){
            $result = array(false);
            $stmt = $this->db->prepare("SELECT video_id FROM questions_end WHERE question_id=:question_id");
            $stmt->bindParam(':question_id', $question_ID);
            $stmt->execute();

            // if result are returned
            if ($stmt->rowCount() > 0){
                $dbResult = $stmt->fetchAll();

                // remove false from array
                array_pop($result);

                // transform result form db into array of video IDs
                foreach($dbResult as $video){
                    array_push($result, $video["video_id"]);
                }
            }
            return $result;
        }
        
        public function getQuestion($question_ID) {
            //Get the details of a question with question_ID=$question_ID
            //Return format
            //Array: {true/false, array of user information}
            //[0]: Result of retrieving. Question Row if successful, False if failed
            //[1]: Array of Videos in Question
            //[2]: Array of Options
            $result = array(false, false);
            $stmt = $this->db->prepare("SELECT * from questions where question_id=:question_id");
            $stmt->bindParam(':question_id', $question_ID);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $result[0] = $stmt->fetch();
                $result[0]["videos_end"] = $this->getEndVideosForQuestion($question_ID);    // add end video IDs to question
                $stmt = $this->db->prepare("SELECT * from videos where question_id=:question_id");
                $stmt->bindParam(':question_id', $question_ID);
                $stmt->execute();
                $result[1] = $stmt->fetchAll();
                $stmt = $this->db->prepare("SELECT * from options where question_id=:question_id");
                $stmt->bindParam(':question_id', $question_ID);
                $stmt->execute();
                $result[2] = $stmt->fetchAll();
            }
            return $result;
        }
        
        public function getVideo($video_ID) {
            //Get the video and Options
            //Return format
            //Array: {true/false, array of user information}
            //[0]: Result of retrieving. Video Row if successful, False if failed
            //[1]: Array of Options in Question
            $result = array(false, false);
            $stmt = $this->db->prepare("SELECT * from videos WHERE video_id=:video_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $result[0] = $stmt->fetch();
                $stmt = $this->db->prepare("SELECT * from options WHERE video_from=:video_from");
                $stmt->bindParam(':video_from', $video_ID);
                $stmt->execute();
                $result[1] = $stmt->fetchAll();
            }
            return $result;
        }
        
        public function getOption($optionID) {
            //Get the Options
            //Return format
            //Array: {true/false, array of user information}
            //[0]: Result of retrieving. Option Row if successful, False if failed
            //[1]: Array of Videos in Question
            $result = array(false, false);
            $stmt = $this->db->prepare("SELECT * from options WHERE option_id=:option_id");
            $stmt->bindParam(':option_id', $optionID);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $result[0] = $stmt->fetch();
                $stmt = $this->db->prepare("SELECT * from videos WHERE question_id=:question_id");
                $stmt->bindParam(':question_id', $result[0]['question_id']);
                $stmt->execute();
                $result[1] = $stmt->fetchAll();
            }
            return $result;
        }
        
        public function setEndingVideo($video_ID, $question_ID) {
            //Set the end video of a question
            $stmt = $this->db->prepare("INSERT INTO questions_end (`question_id`, `video_id`) VALUES (:question_id, :video_id)");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->bindParam(':question_id', $question_ID);
            $stmt->execute();
        }

        public function removeEndingVideo($video_ID, $question_ID) {
            // Remove the end video of a question
            $stmt = $this->db->prepare("DELETE FROM questions_end WHERE video_id=:video_id and question_id=:question_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->bindParam(':question_id', $question_ID);
            $stmt->execute();
        }

        public function setStartingVideo($video_ID, $question_ID) {
            //Set the start video of a question
            $stmt = $this->db->prepare("UPDATE questions SET video_start=:video_id WHERE question_id=:question_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->bindParam(':question_id', $question_ID);
            $stmt->execute();
        }
        
        public function editVideo($video_ID, $videoLink, $videoName) {
            $stmt = $this->db->prepare("UPDATE videos SET video_link=:video_link, video_text=:video_name WHERE video_id=:video_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->bindParam(':video_link', $videoLink);
            $stmt->bindParam(':video_name', $videoName);
            $stmt->execute();
        }
        
        public function editOption($optionID, $videoFrom, $videoTo, $optionName) {
            $stmt = $this->db->prepare("UPDATE options SET video_from=:video_from, video_to=:video_to, option_name=:option_name WHERE option_id=:option_id");
            $stmt->bindParam(':option_id', $optionID);
            $stmt->bindParam(':video_from', $videoFrom);
            $stmt->bindParam(':video_to', $videoTo);
            $stmt->bindParam(':option_name', $optionName);
            $stmt->execute();
        }
        
        public function editUser($source, $candidate) {
            //Edits the user information (Used in admin edit user panel)
            $sql = $this->prepareEditSQL($this->userFields, $source, $candidate);
            if ($sql[0] != "") {
                $sql[0] 				= "UPDATE user SET".$sql[0]." WHERE User_ID =:user_ID";
                $sql[1][':user_ID'] 	= $source['User_ID'];
                
                $stmt					= $this->db->prepare($sql[0]);
                $stmt->execute($sql[1]);
            }
            
            if ($source['User_Username'] == $candidate['User_Username']) {
                $this->savelog($_SESSION['username'], "edited user {$source['User_Username']}");
            }
            else {
                $this->savelog($_SESSION['username'], "edited user {$source['User_Username']} to {$candidate['User_Username']}");
            }
        }
        
        public function allTrue($array) {
            //Used for preparing fields for editUser()
            $result = true;
            foreach ($array as $key => $value) {
                if ($value === false) {
                    $result = false;
                    break;
                } 
            }
            
            return $result;
        }
        
        public function addModule($moduleName) {
            $stmt = $this->db->prepare("INSERT INTO modules(module_name) VALUES (:moduleName)");
            $stmt->bindParam(':moduleName', $moduleName);
            $stmt->execute();
        }
        
        public function addQuestion($questionName, $moduleId) {
            $stmt = $this->db->prepare("INSERT INTO questions(question_name, module_id) VALUES (:question_name, :module_id)");
            $stmt->bindParam(':question_name', $questionName);
            $stmt->bindParam(':module_id', $moduleId);
            $stmt->execute();
        }
        
        public function addVideo($videoLink, $questionId, $videoName) {
            $stmt = $this->db->prepare("INSERT INTO videos(question_id, video_link, video_text) VALUES (:question_id, :video_link, :video_name)");
            $stmt->bindParam(':question_id', $questionId);
            $stmt->bindParam(':video_link', $videoLink);
            $stmt->bindParam(':video_name', $videoName);
            $stmt->execute();
        }
        
        public function addOption($videoFrom, $videoTo, $optionName, $questionId) {
            $stmt = $this->db->prepare("INSERT INTO options(video_from, video_to, option_name, question_id) VALUES (:video_from, :video_to, :option_name, :question_id)");
            $stmt->bindParam(':video_from', $videoFrom);
            $stmt->bindParam(':video_to', $videoTo);
            $stmt->bindParam(':option_name', $optionName);
            $stmt->bindParam(':question_id', $questionId);
            $stmt->execute();
        }
        
        public function deleteVideo($video_ID) {
            $stmt = $this->db->prepare("DELETE FROM options WHERE video_from=:video_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->execute();
            $stmt = $this->db->prepare("DELETE FROM options WHERE video_to=:video_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->execute();
            $stmt = $this->db->prepare("DELETE FROM videos WHERE video_id=:video_id");
            $stmt->bindParam(':video_id', $video_ID);
            $stmt->execute();
        }
        
        public function deleteOption($optionID) {
            $stmt = $this->db->prepare("DELETE FROM options WHERE option_id=:option");
            $stmt->bindParam(':option', $optionID);
            $stmt->execute();
        }
        public function addOptionFrequency($optionId) {
            $stmt = $this->db->prepare("UPDATE options SET frequency=frequency + 1 WHERE option_id=:option_id");
            $stmt->bindParam(':option_id', $optionId);
            $stmt->execute();
        }
        public function prepareEditSql($fields, $source, $candidate) {
            //Prepares the center of the sql statement 
            foreach($fields as $value) {
                //Compares if the source and candidate entries are the same
                $same[$value] = ($source[$value] == $candidate[$value]);
            }
            
            //Ensure that there is at least one different field before iterating
            if (!$this->allTrue($same)) {
                $sql 			= "";
                $fieldArray 	= array();
                
                foreach ($fields as $value) {
                    if (!$same[$value]) {
                        //Example: asset_ID=:asset_ID
                        $sql									.= " {$value}=:{$value}";
                        
                        //Example: $fieldArray[":asset_ID", $candidate[":asset_ID"];
                        $fieldArray[":{$value}"]	= 			$candidate[$value];
                        
                        //Remove the field
                        unset($same[$value]);
                        
                        //If there are other fields that are different, add a comma to separate them.
                        if (!$this->allTrue($same)) {
                            $sql 	.= ",";
                        }
                        }
                    }
                    return array($sql, $fieldArray); 
            }
        }
        
    }
?>