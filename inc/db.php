<?php
error_reporting(1);
	class Database {
		
    private $db;
    private static $instance;

	// private constructor
    private function __construct() {
		$servername = "localhost";
		$username = "root";
		$password = "";


		try {
			$this->db = new PDO("mysql:host=$servername;dbname=fcp_babcock;", $username, $password);
			// set the PDO error mode to exception
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Connected successfully";
			// I won't echo thsi message but use it to for checking if you connected to the db
			//incase you don't get an error message
			}
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
		}
    }
	
    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }
	
	public function get_name_from_id($tab,$col,$whe,$id){
		try{
			$que= $this->db->prepare("SELECT $tab FROM $col where $whe =?");
			$que->execute([$id]);
			$SingleVar = $que->fetchColumn();
			return $SingleVar;
			$que = null;			
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}	
	}

	public function get_name_from_id2($tab,$col,$whe,$id,$whe2,$id2,$ord){
		try{
			$que= $this->db->prepare("SELECT $tab FROM $col WHERE $whe = ? AND $whe2 = ? ORDER BY $ord DESC");
			$que->execute([$id,$id2]);
			$SingleVar = $que->fetchColumn();
			return $SingleVar;
			$que = null;			
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}	
    }
    
    public function get_name_from_id_2($tab,$col,$whe,$id,$whe2,$id2){
		try{
			$que= $this->db->prepare("SELECT $tab FROM $col WHERE $whe = ? AND $whe2 = ?");
			$que->execute([$id,$id2]);
			$SingleVar = $que->fetchColumn();
			return $SingleVar;
			$que = null;			
		} catch(PDOException $e){
			echo 'Error: ' . $e->getMessage();
		}	
	}
	
	//delete
	public function delete_from_where($table, $col, $id){
		try {
			$stmt = $this->db->prepare("DELETE FROM $table WHERE $col = ?");
			$stmt->execute([$id]);
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
		
	
	//delete
	public function delete_things($tab,$col,$value) {
		try{
			$stmt = $this->db->prepare("DELETE FROM $tab WHERE $col=?");		
			$stmt->execute([$value]);
			$success = 'Done';
			return $success;
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}	
	}

	//delete
	public function delete_things_where2($tab,$col,$value,$col2,$value2) {
		try{
			$stmt = $this->db->prepare("DELETE FROM $tab WHERE $col=? AND $col2 = ?");		
			$stmt->execute([$value,$value2]);
			$success = 'Done';
			return $success;
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}	
	}
	
	//alter
	public function alter_things($tab,$col,$where, $value) {
		try{
			$empty = "";
			$stmt = $this->db->prepare("UPDATE $tab SET $col = ? WHERE $where = ?")->execute([$empty, $value]);
			$stmt = null;
			$success = 'Done';
			return $success;
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}	
	}
	
	//select order
	public function select_from_where_no($table,$col,$valEmp){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col != ?");
			$que->execute([$valEmp]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	
	public function select_from_where_no3_group($table,$col,$val,$col2,$val2,$col3,$val3,$group,$id,$ord){
		try {
			$que = $this->db->prepare("SELECT * FROM $table WHERE $col = ? AND $col2 = ? AND $col3 = ? GROUP BY $group ORDER BY $id $ord");
			$que->execute([$val,$val2,$val3]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
		}
	}

	//sum from table with 4 parameters
	public function sum_where4( $col,$table, $col2,$val1, $col3, $val2,$col4,$val3,$col5,$val4){
		$stmt = $this->db->prepare("SELECT SUM($col) AS totalAmt FROM $table WHERE $col2 = ? AND $col3 = ? AND $col4 = ? AND $col5 = ? LIMIT 1");
		$stmt->execute([$val1, $val2,$val3,$val4]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['totalAmt'];
		$stmt = null;
	}

	public function select_transcript($value){
		try {
			$que = $this->db->prepare("SELECT status FROM transcript WHERE matNo = ? GROUP BY matNo LIMIT 1");
			$que->execute([$value]);
            $arr = $que->fetch(PDO::FETCH_COLUMN);
			return $arr;
			$que = null;		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
		}
	}
	//count notifications
	public function count_notifications($status,$user){
		$stmt = $this->db->prepare("SELECT * FROM notifications WHERE status = ? AND toID = ? ");
		$stmt->execute([$status,$user]);
		$count = $stmt->rowCount();
		return $count;
		$stmt = null;
	}

	public function select_count($col, $table){
		$stmt = $this->db->prepare("SELECT COUNT($col) FROM $table");
		$stmt->execute([]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		echo $count;
		$stmt = null;
	}
	
	public function count_all_user_id($table, $where, $user_id){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $where = ?");
		$stmt->execute([$user_id]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		echo $count;
		$stmt = null;
	}
	
	public function count_not_empty($table, $where, $where2){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $where <> '' AND $where2 <> ''");
		$stmt->execute([]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		echo $count;
		$stmt = null;
	}
	
	public function count_is_empty($table, $where, $where2){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $where = '' AND $where2 = ''");
		$stmt->execute([]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		echo $count;
		$stmt = null;
	}
	
	public function count_partial($table, $where, $where2){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $where <> '' OR $where2 <> ''");
		$stmt->execute([]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		echo $count;
		$stmt = null;
	}
	
	
	
	public function select_from_where_limit_user($table,$col,$user_id){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = ? LIMIT 20");
			$que->execute([$user_id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_where_amt_limit($table,$col,$amt, $col2, $val, $col3, $val2){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = ? AND $col2 = ? AND $col3 = ? LIMIT 20");
			$que->execute([$amt, $val, $val2]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
    }
    
    public function select_from_where_amt_no3($table,$col,$amt, $col2, $val, $col3, $val2){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = ? AND $col2 = ? AND $col3 = ? LIMIT 20");
			$que->execute([$amt, $val, $val2]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_where_amt_limit1($table,$col,$amt, $col2, $val){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = ? AND $col2 = ? LIMIT 1");
			$que->execute([$amt, $val]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function show_notification($staff_id, $status){
		try {
			$que= $this->db->prepare("SELECT * FROM notifications WHERE toID = $staff_id AND status = $status");
			$que->execute();
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function show_notification_all($staff_id){
		try {
			$que= $this->db->prepare("SELECT * FROM notifications WHERE toID = ? ORDER BY status,date_added ASC LIMIT 0,5");
			$que->execute([$staff_id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_where_amt_limit2($table,$col,$amt, $col2, $val){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = ? AND $col2 = ? LIMIT 20");
			$que->execute([$amt, $val]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function count_all($table, $user_id){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE user_id = ?");
		$stmt->execute([$user_id]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
		$stmt = null;
	}


	public function count_from($table,$col, $id){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $col = ?");
		$stmt->execute([$id]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
		$stmt = null;
	}
	
	public function count_where_not($table, $id){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE id < ? ORDER BY id DESC");
		$stmt->execute([$id]);
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
		$stmt = null;
	}
	
	public function count_limit_admin($table){
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM $table");
		$stmt->execute();
		$count = $stmt->fetch(PDO::FETCH_COLUMN);
		return $count;
		$stmt = null;
	}
	
	//This method is for general select
	public function select($table){
		$stmt = $this->db->prepare("SELECT * FROM $table");
		$stmt->execute();
		$arr = $stmt->fetchAll();
		return $arr;
		$stmt = null;	
    }//end class
    
	//I am using this function with 2 cos I chnaged $id to $user_id
	public function select_from_where2($table,$col,$user_id){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$user_id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	


	//I am using this function with 2 cos I chnaged $id to $user_id
	public function select_from_where2_DESC($table,$col,$user_id){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ? ORDER BY id DESC");
			$que->execute([$user_id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}


	

	//I am using this function with 2 cos I chnaged $id to $user_id
	public function select_from_where3($table,$col,$user_id){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ? ORDER BY id DESC");
			$que->execute([$user_id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

		
	//select order
	public function select_order($table,$col,$inv_num){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$inv_num]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	//Selecet order
	public function select_from_ord($table,$id,$ord){
		try {
			$que = $this->db->prepare("SELECT * FROM $table ORDER BY $id $ord");
			$que->execute();
			$arr = $que->fetchAll();
			return $arr;
			$que = null;		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_from_where_ord($tab,$col,$whe,$tab_id,$ord){
		try {
			
			$stmt = $this->db->prepare("SELECT * FROM $tab WHERE $col = $whe ORDER BY $tab_id $ord");
			$stmt->execute([$whe]);
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;	
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}		
	}
	
	public function select_from_where_ord2($tab,$col,$whe, $col2, $whe2,$tab_id,$ord){
		try {
			$stmt = $this->db->prepare("SELECT * FROM $tab WHERE $col = $whe AND $col2 = '".$whe2."' ORDER BY $tab_id $ord");
			$stmt->execute([$whe,$whe2]);
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;	
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}		
	}
	
	public function select_from_where_or_ord3($tab,$col,$whe, $col2, $whe2,$tab_id,$ord){
		try {
			
			$stmt = $this->db->prepare("SELECT * FROM $tab WHERE $col = ? OR $col2 = ? ORDER BY $tab_id $ord");
			$stmt->execute([$whe,$whe2]);
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;	
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}		
	}

	public function select_from_where_ord4($tab,$col,$whe, $col2,$tab_id,$ord){
		try {
			
			$stmt = $this->db->prepare("SELECT * FROM $tab WHERE $col = $whe AND $col2 = '' ORDER BY $tab_id $ord");
			$stmt->execute([$whe]);
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;	
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}		
	}

	public function select_from_where_ord5($tab,$col,$whe,$col2,$ord){
		try {
			
			$stmt = $this->db->prepare("SELECT * FROM $tab WHERE $col = $whe AND $col2 = '' ORDER BY patient_test_group_id $ord");
			$stmt->execute();
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;	
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}		
	}

	public function select_from_val($table,$col,$val){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ? LIMIT 1");
			$que->execute([$val]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
    }

	public function select_from_val_ord($table,$col,$val,$id,$ord){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ? ORDER BY $id $ord LIMIT 1");
			$que->execute([$val]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_no_limit($table,$col,$value){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$value]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
    }
    public function getCredit($score)
    {
        try {
            if ($score >= 80 && $score <= 100) {
                $credit = 5; 
            }else if ($score >= 60 && $score <= 79) {
                $credit = 4; 
            }else if ($score >= 50 && $score <= 69) {
                $credit = 3; 
            }else if ($score >= 45 && $score <= 49) {
                $credit = 2; 
            }else if ($score >= 40 && $score <= 44) {
                $credit = 1; 
            }else if ($score >= 0 && $score <= 39) {
                $credit = 0;
            }
            return $credit;
		} catch (Exception $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
    }
    public function calcGPA($matNumber,$level,$semester)
    {
        try {
			$que= $this->db->prepare("SELECT a.score as score,a.courseID as courseID,b.unit as unit 
            FROM transcript a 
            RIGHT JOIN courses b ON a.courseID = b.courseID 
            WHERE a.matNo = ? AND b.level = ? 
            AND b.semester = ?");
			$que->execute([$matNumber,$level,$semester]);
			$arr = $que->fetchAll(PDO::FETCH_ASSOC);
			$count = $que->rowCount();
            $CaU = 0;
            $tu = 0;
            foreach ($arr as $row) {
                $credit = $this->getCredit($row['score']);
                $CaU += ($credit * $row['unit']);
                $tu += $row['unit'];
            }
            if($count > 0){
				return round(($CaU/$tu),2);
			}else{
				return '0.0';
			}
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function getSuggestion($matNumb,$courseID)
	{
		try {
			$dept = $this->get_name_from_id("department_id","students","matNo",$matNumb);
			$courseDur = $this->get_name_from_id("duration","departments","department_id",$dept)*100;
			$failed = [];
			$unitsPerSemester = [];
			$passedUnitsPerSemester = 0;			
			$failedUnitsPerSemester = 0;
			for ($i=100; $i <= $courseDur; $i+=100) { 
				for ($sem=1; $sem <= 2; $sem++) { 
					$que= $this->db->prepare("SELECT a.courseID,a.unit,a.courseType,b.score FROM `courses` a 
					RIGHT JOIN transcript b ON a.courseID = b.courseID
					WHERE a.level = ? AND a.semester = ? AND b.matNO = ?");
					$que->execute([$i,$sem,$matNumb]);
					$arr = $que->fetchAll(PDO::FETCH_ASSOC);
					$tu = 0;
					foreach ($arr as $row) {
						$tu += $row['unit'];
						$score = $row['score'];
						$credit = $this->getCredit($score);
						if ($row['courseType'] == 1) {
							if ($score >= 0  && $score <= 39) {
								$newCour = array($row['courseType'] => $score);
								array_push($failed,$newCour);
								$failedUnitsPerSemester += $row['unit'];
							}else{
								$passedUnitsPerSemester += $row['unit'];
							}
						}else if ($row['courseType'] > 1) {
							if ($score >= 0  && $score <= 49) {
								$newCour = array($row['courseType'] => $score);
								array_push($failed,$newCour);
								$failedUnitsPerSemester += $row['unit'];
							}else{
								$passedUnitsPerSemester += $row['unit'];
							}
						}
					}
					$ups = array($i,$sem,$tu,$passedUnitsPerSemester,$failedUnitsPerSemester);
					array_push($unitsPerSemester,$ups);
					$failedUnitsPerSemester = 0;
					$passedUnitsPerSemester = 0;
					$tu = 0;
					$this->analyzeData($unitsPerSemester);
				}
			}
			return $this->analyzeData($unitsPerSemester);

		} catch (Exception $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
		}
	}
	public function analyzeData($arr)
	{
		try {
			for ($i=0; $i < count($arr); $i++) { 
				$next = $i++;
				$failed = $arr[4];
				$passed = $arr[3];
			}
		} catch (Exception $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
		}
	}
	public function select_for_cart($table,$col,$value){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$value]);
			$arr = $que->fetchAll(PDO::FETCH_ASSOC);
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_while_user_id($user_id, $col){
		try {
			$que= $this->db->prepare("SELECT * FROM users WHERE $col = ?");
			$que->execute([$user_id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_payment($table,$col,$ref){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$ref]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_where($table,$col,$id){ 
		
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= $id LIMIT 1"); //using LIMIt fro optimization purpose
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
		public function select_from_where6($table,$col,$id){ 
		
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col LIKE '%".$id."%' LIMIT 1"); //using LIMIt fro optimization purpose
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_where60($table,$col,$id,$order){  
		
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col = '".$id."' ORDER BY $order DESC"); //using LIMIt fro optimization purpose
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_where61($table,$col,$id,$col2,$id2,$order){ 
		
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col LIKE '".$id."' AND $col2 LIKE '".$col2."' ORDER BY $order DESC"); //using LIMIt fro optimization purpose
			$que->execute([$id,$id2]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_where8($id){ 
		
		try {
			$que= $this->db->prepare("SELECT DISTINCT patient_id , GROUP_CONCAT(order_id) FROM accounts WHERE order_id = ".$id."");
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
		public function select_from_where7($table,$col,$id){ 
		
		try {
			$que= $this->db->prepare("SELECT *, SUM(amount) FROM $table WHERE $col LIKE '%".$id."%' LIMIT 1"); //using LIMIt fro optimization purpose
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}


	public function select_from_where_no_lim($table,$col,$id){
		
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?"); //using LIMIt fro optimization purpose
			$que->execute([$id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_while($table,$col,$val){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ?");
			$que->execute([$val]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_double($table,$col,$val, $col2, $val2){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE $col= ? AND $col2 = ?");
			$que->execute([$val, $val2]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_table($table){
		$stmt = $this->db->prepare("SELECT * FROM $table");
		$stmt->execute();			
		return $stmt;
		$stmt = null;	
	}//end class

	
	public function select_from_user($table, $user_id){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE id = ?");
			$que->execute([$user_id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	//selecet limit
	public function select_from_where_limit($table, $user_id, $offset, $limit){
		try {
			$que= $this->db->prepare("SELECT * FROM $table WHERE user_id = ? ORDER BY id DESC LIMIT $offset, $limit");
			$que->execute([$user_id]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_where_limit_admin($table, $col, $offset, $limit){
		try {
			$que= $this->db->prepare("SELECT * FROM $table ORDER BY $col DESC LIMIT $offset, $limit");
			$que->execute([]);
			return $que;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	
	public function select_from_where_limit_where($val, $offset, $limit){
		try {		
			$val2 = 0;
			$que= $this->db->prepare("SELECT * FROM extra_inv_details WHERE client_id= ? AND invoice_sent != ? ORDER BY id DESC LIMIT $offset, $limit");
			$que->execute([$val, $val2]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error			
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_front_desk_id($pat,$doc){
		try {		
			$val2 = 0;
			$que= $this->db->prepare("SELECT * FROM patient_test_group WHERE patient_id = ? AND patient_appointment_id = 0 AND front_desk != '' AND doctor_id = ? ORDER BY patient_test_group_id DESC");
			$que->execute([$pat,$doc]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error			
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_where_limit_draft( $val, $offset, $limit){
		try {		
			$val2 = 0;
			$que= $this->db->prepare("SELECT * FROM extra_inv_details WHERE client_id= ? AND invoice_sent = ? ORDER BY id DESC LIMIT $offset, $limit");
			$que->execute([$val, $val2]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error			
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function select_from_where_limit_all( $val, $offset, $limit){
		try {		
			$que= $this->db->prepare("SELECT * FROM extra_inv_details WHERE client_id= ? ORDER BY id DESC LIMIT $offset, $limit");
			$que->execute([$val]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error			
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function select_from_wherenot_ord($table,$col,$id,$orid,$ord){
		try {
			$que= $this->db->prepare("SELECT * FROM $table where $col!=? ORDER BY $orid $ord");
			$que->execute([$id]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	//Method to check if user exists before registration
	public function check_email($username){
			
		try {
			$stmt= $this->db->prepare("SELECT username FROM staff WHERE username= ?");
			$stmt->execute([$username]);
			$row=$stmt->fetch(PDO::FETCH_OBJ);
			return $row;			
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	//For user registration
	public function insert_user($first, $last, $username, $role, $hash, $position,$ward){
		try {
		$stmt = $this->db->prepare("INSERT INTO staff(first_name,last_name,username, role_id, password, position,ward_id) 
		VALUES (?,?,?,?,?,?,?)");
		$stmt->execute([$first, $last, $username, $role, $hash, $position,$ward]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
    }
    public function insert_bulletin($dept,$syear,$gyear,$req,$user,$status)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO bulletin(department_id,startingYear,endingYear, gradRequirements,addedBY,status,date_added) 
            VALUES (?,?,?,?,?,?,NOW())");
            $stmt->execute([$dept,$syear,$gyear,$req,$user,$status]);
            $last = $this->db->lastInsertId();
            $stmt = null;

            if ($status == 1) {
                $stmt = $this->db->prepare("UPDATE bulletin SET status = 0 WHERE bulletinID != ?")->execute([$last]);
		        $stmt = null;
                
                $stmt = $this->db->prepare("UPDATE departments SET bulletin = ? WHERE department_id = ?")->execute([$last,$dept]);
		        $stmt = null;
            }
            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function insert_remark($reason,$matNumber,$user,$val)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO remarks(matNO,remark,userID,type) 
            VALUES (?,?,?,?)");
            $stmt->execute([$matNumber,$reason,$user,$val]);
            $stmt = null;

            if ($val == 1) {
                $status = 2;
                $stmt = $this->db->prepare("UPDATE transcript SET status = ? WHERE matNo = ?");
                $stmt->execute([$status,$matNumber]);
                $stmt = null;
            }

            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function acceptResult($matNumber)
    {
        try {
            $status = 1;
            $stmt = $this->db->prepare("UPDATE transcript SET status = ? WHERE matNo = ?");
            $stmt->execute([$status,$matNumber]);
			$stmt = null;
			
			// send notification
			$did = $this->get_name_from_id("department_id","students","matNo",$matNumber);
			$msg = "Your Result Was Accepted";
			$link = "transcript.php";
			$fromID = $this->get_name_from_id("uid","users","department_id",$did);
			$stmt = $this->db->prepare("INSERT INTO notifications(message,link,fromID,toID,date_added) VALUES(?,?,?,?,NOW())");
			$stmt->execute([$msg,$link,$fromID,$matNumber]);
			$stmt = null;

            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function update_bulletin($dept,$syear,$gyear,$req,$user,$status,$id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE bulletin SET department_id = ?,startingYear = ?,endingYear = ?, gradRequirements = ?,addedBY =?,status=?,date_added=NOW()  WHERE bulletinID = ?");
            $stmt->execute([$dept,$syear,$gyear,$req,$user,$status,$id]);
            $stmt = null;

            if ($status == 1) {
                $stmt = $this->db->prepare("UPDATE bulletin SET status = 0 WHERE bulletinID != ?")->execute([$id]);
		        $stmt = null;
                
                $stmt = $this->db->prepare("UPDATE departments SET bulletin = ? WHERE department_id = ?")->execute([$id,$dept]);
		        $stmt = null;
            }
            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function insert_course($dept,$bulletin,$level,$semester,$code,$title,$ctype,$unit,$description,$lect)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO courses(`department`,`bulletin`,`level`,`semester`,`code`,`title`,`courseType`,`unit`,`description`,`assignedLecturer`) 
            VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$dept,$bulletin,$level,$semester,$code,$title,$ctype,$unit,$description,$lect]);
            $stmt = null;
            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function update_course($dept,$bulletin,$level,$semester,$code,$title,$ctype,$unit,$description,$lect,$val)
    {
        try {
            $stmt = $this->db->prepare("UPDATE courses SET `department` = ?,`bulletin` = ?,`level` = ?,`semester` = ?,`code` = ?,`title` = ?,`courseType` = ?,`unit` = ?,`description` = ?,`assignedLecturer` = ? WHERE courseID = ?");
            $stmt->execute([$dept,$bulletin,$level,$semester,$code,$title,$ctype,$unit,$description,$lect,$val]);
            $stmt = null;
            return "Done";
            
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }
    public function updateTranscript($id, $score,$matNumb,$level,$semester)
    {
        try {
            $status = 0;
            for ($i=0; $i < count($id); $i++) { 
                $que=$this->db->prepare("SELECT TransID FROM transcripttemp WHERE `courseID` = ? AND `matNo` = ?");
                $que->execute([$id[$i],$matNumb]);
                $count = $que->rowCount();
                if ($count == 0) {
                    if (!empty($score[$i])) {
                        $stmt = $this->db->prepare("INSERT INTO transcripttemp(`courseID`,`score`,`matNO`,`status`,`level`,`semester`) 
                    VALUES (?,?,?,?,?,?)");
                    $stmt->execute([$id[$i],$score[$i],$matNumb,$status,$level,$semester]);
                    $stmt = null;
                    }
                }else{
                    if (!empty($score[$i])) {
                        $stmt = $this->db->prepare("UPDATE transcripttemp SET `courseID` = ?,`score` = ?,`matNO` = ?,`status` = ?,`level`=?,`semester` = ? WHERE `courseID` = ? AND `matNo` = ?");
                        $stmt->execute([$id[$i],$score[$i],$matNumb,$status,$level,$semester,$id[$i],$matNumb]);
                        $stmt = null;
                    }
                }
            }
            return "Done";
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
    }

    public function pushScores($matNumb)
    {
        try {
            //solve
                $que=$this->db->prepare("SELECT a.TransID as id,a.score as score FROM `transcripttemp` a 
                WHERE a.matNo = ?
                AND NOT EXISTS(SELECT courseID FROM `transcript` b WHERE a.courseID = b.courseID) 
                ORDER BY a.TransID ASC");
                $que->execute([$matNumb]);
                $counta = $que->rowCount();
                $rowa=$que->fetchAll();
                $que = null;
                
                $que=$this->db->prepare("SELECT a.TransID as id,
                    b.TransID as id_trans, a.score as score FROM `transcripttemp` a 
                    RIGHT JOIN `transcript` b ON a.courseID = b.courseID
                                        WHERE a.matNo = ? 
                                        AND a.score <> b.score
                                        AND EXISTS(SELECT courseID FROM `transcript` b WHERE a.TransID = b.TransID) 
                                        ORDER BY a.TransID ASC");
                $que->execute([$matNumb]);
                $countb = $que->rowCount();
                $rowb=$que->fetchAll();

                if ($counta > 0) {
                    foreach ($rowa as $data) {
                        $stmt = $this->db->prepare("INSERT INTO transcript (courseID, score, matNO,level,semester)
                        SELECT courseID, score, matNO,level,semester FROM transcripttemp
                        WHERE TransID= ?;");
                        $stmt->execute([$data['id']]);
                        $stmt = null;  
                        $stmt = $this->db->prepare("UPDATE transcripttemp SET `status`=1 WHERE TransID= ?;");
                        $stmt->execute([$data['id']]);
                        $stmt = null;
                    }
                }

                    if ($countb > 0) {
                        foreach ($rowb as $val) {
                                $stmt = $this->db->prepare("UPDATE transcript
                                SET score = ? WHERE TransID = ?
                                ");
                                $stmt->execute([$val['score'],$val['id_trans']]);
                                $stmt = null;

                                $stmt = $this->db->prepare("UPDATE transcripttemp SET `status`=1 WHERE TransID= ?;");
                                $stmt->execute([$val['id']]);
                                $stmt = null;
                        }
					}
				// get fullname of student
					$que=$this->db->prepare("SELECT firstName,lastName,middleName,department_id FROM students WHERE matNo = ? LIMIT 1");
					$que->execute([$matNumb]);
					$row=$que->fetchAll();
					$que = null;
					foreach ($row as $value) {
						$fullname = $value['lastName']." ".$value['firstName']." ".$value['middleName'];
						$did = $value['department_id'];
					}
				$msg = $fullname." just sent His Scores";
				$link = "transcript.php?id=".$matNumb;
				$toID = $this->get_name_from_id("uid","users","department_id",$did);
				$stmt = $this->db->prepare("INSERT INTO notifications(message,link,fromID,toID,date_added) VALUES(?,?,?,?,NOW())");
				$stmt->execute([$msg,$link,$matNumb,$toID]);
				$stmt = null;
                return "Done";
            } catch (PDOException $e) {
                // For handling error
                echo 'Error: ' . $e->getMessage();			
            }
	}
	
	//count in-patient_bill
	public function notifi($user){
		try {
			$que= $this->db->prepare("SELECT * FROM `notifications` WHERE toID = ?");
			$que->execute([$aid]);
			$arr = $que->rowCount();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	//count column
	public function count_it($tab,$col,$aid){
		try {
			$que= $this->db->prepare("SELECT * FROM $tab WHERE $col = ?");
			$que->execute([$aid]);
			$arr = $que->rowCount();
			return $arr;
			$que = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_staff($first_name, $last_name, $username, $role, $hash, $position, $val,$ward){		
		try {
			$stmt = $this->db->prepare("UPDATE staff SET first_name = ?, last_name =?, username = ?, role_id=?, password=?, position=?,ward_id=? WHERE user_id = ?");
			$stmt->execute([$first_name, $last_name, $username, $role, $hash, $position, $val,$ward]);
			$stmt = null;
			return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	//determinf Students Bull
	public function determineBulletin($matNumb)
	{
		try {
			$session = intval(substr($matNumb,0,2));
			$stmt= $this->db->prepare("SELECT `startingYear`, `endingYear`,`bulletinID` FROM bulletin");
			$stmt->execute();
			$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($arr as $value) {
				$i = substr($value['startingYear'],2,2);
				$j = substr($value['endingYear'],2,2);
				if (in_array($session,range($i,$j))) {
					$r = $value['bulletinID'];
					break;
				}
			}	
			$stmt = null;
			$stmt= $this->db->prepare("UPDATE `students` SET `bulletin` = ? WHERE `matNo` = ?");
			$stmt->execute([$r,$matNumb]);
			$stmt = null;
		} catch (Exception $e) {
			//For handling error
			echo 'Error: '. $e->getMessage();
		}
	}
	//user login
	public function check_pass($username,$password){	
		if(Empty($username)){
			$sign = 'emptyUsername';
			$loc = '';
			echo json_encode(array("value" => $sign, "value2" => $loc));
		} else if(Empty($password)){
			$sign = 'emptyPass';
			$loc = '';
			echo json_encode(array("value" => $sign, "value2" => $loc));
		} else{
			$stmt= $this->db->prepare("SELECT usr FROM users WHERE usr = ? LIMIT 1");
			$stmt->execute([$username]);
			$arr = $stmt->fetchAll();
			if(!$arr){
				$sign = 'no';
				$loc = '';
				echo json_encode(array("value" => $sign, "value2" => $loc));
			} else{
				$stmt= $this->db->prepare("SELECT `uid`, `usr`, `pwd`, `log_status` FROM users WHERE `usr` = ? LIMIT 1");
				$stmt->execute([$username]);
				$row=$stmt->fetch(PDO::FETCH_OBJ);
				$realusername = $row->usr;
				$realpassword = $row->pwd;
				$user_id = $row->uid;
				$log = $row->log_status;
		
				if(password_verify($password, $realpassword)){
					//add value for loggedin user
					$logged_in = 1;
							
					$stmt2 = $this->db->prepare("UPDATE users SET log_status = ? WHERE usr = ? ")->execute([$logged_in, $username]);
					$stmt2 = null;
							
					$sign = 'Login';
					$page_id = '';
					$loc='';
					session_start();
					$_SESSION['userSession'] = $user_id;
                    $page = "dashboard.php";
                    
					echo json_encode(array("value" => $sign, "value2" => $loc, "value3" => $user_id, "page" => $page));
				} else {
					$result = "<div class='alert alert-danger'>Please enter correct password ! $password</div>";
					$sign = 'false';
					echo json_encode(array("value" => $sign, "value2" => $result, "value3" => $user_id));
				} 
	
			}
			$stmt = null;
		}	
    }
    
    //student login
	public function verifyMatNumb($matNumb){	
		if(Empty($matNumb)){
			$sign = 'emptyMatNumb';
			$loc = '';
			echo json_encode(array("value" => $sign, "value2" => $loc));
		} else{
				$stmt= $this->db->prepare("SELECT `status` FROM `students` WHERE `matNo` = ? LIMIT 1");
				$stmt->execute([$matNumb]);
				$n=$stmt->rowCount();
                if ($n > 0) {
                        //add value for loggedin student
                        $logged_in = 1;
                                
                        $stmt2 = $this->db->prepare("UPDATE students SET status = ? WHERE matNo = ? ")->execute([$logged_in, $matNumb]);
                        $stmt2 = null;
                                
                        $sign = 'Login';
                        $page_id = '';
                        $loc='';
                        session_start();
                        $_SESSION['userSession'] = $matNumb;
                        $page = "dashboard.php";

                        echo json_encode(array("value" => $sign, "value2" => $loc, "value3" => $matNumb, "page" => $page));    
                } else {
					$result = "<div class='alert alert-danger'>Please enter an assigned Matric Number</div>";
					$sign = 'false';
					echo json_encode(array("value" => $sign, "value2" => $result, "value3" => $matNumb));
				} 
                $stmt = null;
		}	
    }
    
   
	
	//update db fro when a user logs
	public function logout_user($user_id){
		try{
			$logout = 0;
			$stmt = $this->db->prepare("UPDATE staff SET logged_in = ? WHERE user_id = ?")->execute([$logout, $user_id]);
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	//update db fro when a user logs
	public function notify_viewed($id){
		$stmt = $this->db->prepare("UPDATE notifications SET status = 1 WHERE notificationID = ?")->execute([$id]);
	}

	//update db fro when a user logs output_add_rewrite_var
	public function logout($admin_id){
		try{
			unset($_SESSION['admin_id']);
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	//insert patient
	public function insert_patient($first,$surname, $m_name, $title, $reg_num, $sex, $blood, $address, $city, $state
	, $religion, $ethnic, $nationality, $natid, $insurance, $nhis, $enr, $age, $ageType, $dob, $tel1, $tel2, $mobile, $email, $ntel, $nadd, $pre, $photo, $card_type,$company){
		try {
			$front = uniqid();
			if (!empty($card_type) AND $card_type == 11) {
				$comp = $company;
			}else{
				$comp = 0;
			}
		$stmt = $this->db->prepare("INSERT INTO patients(front_desk,first_name,surname,middle_name,title, reg_num, sex, blood_group, address, city, state, religion, ethnic, nationality
		, national_id, insurance_type_id, nhis_num, enrollee_num, contact_method_id, age, age_type, dob, tel_one, tel_two, mobile, email, next_kin_phone, next_kin_address, photo, card_type,company_id) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$front,$first,$surname, $m_name, $title, $reg_num, $sex, $blood, $address, $city, $state, $religion, $ethnic, $nationality, $natid, $insurance, $nhis, $enr, $pre, 
		$age, $ageType, $dob, $tel1, $tel2, $mobile, $email, $ntel, $nadd, $photo, $card_type,$comp]);
		$stmt = null;
		
		//get id
		$last = $this->db->lastInsertId();
		//lets now pay for card
		$pay = 1;
		$stmt = $this->db->prepare("UPDATE patients SET card_pay = ? WHERE id = ?")->execute([$pay, $last]);
		$stmt = null;
		
		//send to accounts
		$test = 5;
		$app_id = 0;
		
		$que= $this->db->prepare("SELECT * FROM card_types WHERE id = ?");
		$que->execute([$card_type]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$cost = $row->cost;
		
		$que = null;

		$que= $this->db->prepare("SELECT front_desk FROM patients WHERE id = ?");
		$que->execute([$last]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$front2 = $row->front_desk;
		
		$que = null;
		
		$code = rand(1000,100000);
		$date = date("Y-m-d");

		if ($card_type == 14) {
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,item, patient_id, card_type, app_id, to_pay, order_id, date_added,amount,payment_status) 
		VALUES (?,?,?,?,?,?,?,?,0,1)");
		$stmt->execute([$front2,$test, $last, $card_type, $app_id, $cost, $code, $date]);
		}elseif($card_type == 11){
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,item, patient_id, card_type, app_id, to_pay,amount,payment_status, order_id, date_added,company_id) 
		VALUES (?,?,?,?,?,?,0,1,?,?,?)");
		$stmt->execute([$front2,$test, $last, $card_type, $app_id, $cost, $code, $date,$company]);
		}elseif ($card_type == 27) {
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,item, patient_id, card_type, app_id, to_pay, order_id, date_added,amount,payment_status) 
		VALUES (?,?,?,?,?,?,?,?,0,1)");
		$stmt->execute([$front2,$test, $last, $card_type, $app_id, $cost, $code, $date]);
		}else{			
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,item, patient_id, card_type, app_id, to_pay, order_id, date_added) 
		VALUES (?,?,?,?,?,?,?,?)");
		$stmt->execute([$front2,$test, $last, $card_type, $app_id, $cost, $code, $date]);
		}
		
		$stmt = null;
		
		return "yesi";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_patient($first,$surname, $m_name, $title, $reg_num, $sex, $blood, $address, $city, $state, $religion, $ethnic, $nationality, $natid, $insurance, $nhis, 
								$enr, $age, $ageType, $dob, $tel1, $tel2, $mobile, $email, $ntel, $nadd, $pre, $photo,$oldfile, $card_type,$company, $val){
		try {
			$stmt = $this->db->prepare("UPDATE patients SET first_name = ?, surname = ?,middle_name=?,title=?, reg_num=?, sex=?, blood_group=?, address=?, city=?, state=?
			, religion=?, ethnic=?,  nationality=?, national_id= ?, insurance_type_id= ?, nhis_num= ?, enrollee_num= ?, contact_method_id= ?, age=?, age_type=?, dob=?, tel_one=?, 
			tel_two=?, mobile=?, email=?, next_kin_phone= ?, next_kin_address= ?, photo= ?, card_type=?, company_id =? WHERE id = ?");
			$stmt->execute([$first,$surname, $m_name, $title, $reg_num, $sex, $blood, $address, $city, $state, $religion, $ethnic, $nationality, $natid, $insurance, $nhis, $enr, $pre, 
			$age, $ageType, $dob, $tel1, $tel2, $mobile, $email, $ntel, $nadd, $photo, $card_type,$company, $val]);
			$stmt = null;
			if($oldfile != "none"){
				$success = 'yesi';
				unlink('../photo/'.$oldfile);
			}
			return "yesi";
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Patient details could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	//For user registration
	public function insert_doc($name, $phone){
		try {
		$stmt = $this->db->prepare("INSERT INTO doctors(name,phone) 
		VALUES (?,?)");
		$stmt->execute([$name, $phone]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_doc($name, $phone, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE doctors SET name = ?,phone=? WHERE id = ?");
			$stmt->execute([$name, $phone,$val]);
			$stmt = null;
			$success = '<div class="alert alert-success">
							Doctor details updated
						</div>';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Doctor details could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function insert_schedule($doctor, $dayofweek, $timein, $timeout, $dateday){
		try {
		$stmt = $this->db->prepare("INSERT INTO doctor_schedule(doctor_id,day_of_week,time_in, time_out, day_date) 
		VALUES (?,?,?,?,?)");
		$stmt->execute([$doctor, $dayofweek, $timein, $timeout, $dateday]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_shce($dayofweek, $timein, $timeout, $dateday, $doctor, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE doctor_schedule SET day_of_week=?, time_in=?,time_out=?,day_date=?, doctor_id =? WHERE id = ?");
			$stmt->execute([$dayofweek, $timein, $timeout, $dateday, $doctor, $val]);
			$stmt = null;
			$success = '<div class="alert alert-success">
							Schedule details updated
						</div>';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Schedule details could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	
	public function notify_account($p_id){
		try {
			$que= $this->db->prepare("SELECT id FROM patient_appointment WHERE patient_id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$app = $row->id;
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('front_desk',".$p_id.",'Payment Needed For: ','payment_daily.php',NOW(),".$status.")");
			$stmt->execute([$p_id,$status]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}



	public function notify($doctor, $p_id){
		try {
			$que= $this->db->prepare("SELECT id FROM patient_appointment WHERE patient_id = $p_id");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$app = $row->id;

			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES (".$doctor.", ".$p_id.",'lab_results?id=".$app."', 'You Have A New Appointment', '0', NOW())");
			$stmt->execute([$doctor, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify2($doctor, $p_id,$app){
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES (".$doctor.", ".$p_id.",'lab_results?id=".$app."', 'You Have A New Appointment', '0', NOW())");
			$stmt->execute([$doctor, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function notify_nurse($nurse, $p_id){
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES (".$nurse.", ".$p_id.",'new_request', 'You Have An Admission Request For: ', '0', NOW())");
			$stmt->execute([$nurse, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify_nurse2($val,$nurse, $p_id){
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_nurses_ad', ".$p_id.",'view_note?view=".$val."', 'You Have An Admission Request For: ', '0', NOW())");
			$stmt->execute([$val,$nurse, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify_nurse3($val,$nurse, $p_id){
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_nurses_ex', ".$p_id.",'view_note?view=".$val."', 'You Have An Examination Request For: ', '0', NOW())");
			$stmt->execute([$val,$nurse, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify_xray($val,$doc, $p_id){ 
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_xray', ".$p_id.",'lab_results?id=".$val."', 'You Have An Xray Request For: ', '0', NOW())");
			$stmt->execute([$val,$doc, $p_id,$time, $dateDay]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify_lab($app){
		try {
			$que= $this->db->prepare("SELECT patient_id FROM patient_appointment WHERE id = $app");
			$que->execute([$app]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$p_id = $row->patient_id;


			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_lab', '".$p_id."','index.php', 'Lab Tests Requested For: ', '0', NOW())");
			$stmt->execute([$p_id]);
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function notify_lab2($pid){
		try {
			$db = mysqli_connect("localhost","root","","greenhousehms");
			$sql = mysqli_query($db, "SELECT * FROM notifications WHERE patient_id = ".$pid." AND staff_id = 'all_lab' AND message = 'Payment Has Been Made For: ' AND status = 0");
			$num = mysqli_num_rows($sql);
			if ($num  > 1) {
				$dateDay = date("Y-m-d");
				$time = date("h:i");
				$stmt = $this->db->prepare("UPDATE notifications SET staff_id = 'all_lab', patient_id = ".$pid.",status = 0, time_taken = NOW()");
				$stmt->execute([$pid]);
				
				$stmt = null;
				return "Done";
			}else{
					$dateDay = date("Y-m-d");
						$time = date("h:i");
						$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_lab', ".$pid.",'index.php', 'Payment Has Been Made For: ', '0', NOW())");
						$stmt->execute([$pid]);
						
						$stmt = null;
						return "Done";
					}
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}




	public function notify_lab3($pid){
		try {
				$db = mysqli_connect("localhost","root","","greenhousehms");
					$sql = mysqli_query($db, "SELECT * FROM notifications WHERE patient_id = ".$pid." AND staff_id = 'all_lab' AND message = 'Payment Was Cancelled: ' AND status = 0");
					$num = mysqli_num_rows($sql);
					if ($num  > 1) {
						$dateDay = date("Y-m-d");
						$time = date("h:i");
						$stmt = $this->db->prepare("UPDATE notifications SET staff_id = 'all_lab', patient_id = ".$pid.",status = 0, time_taken = NOW()");
						$stmt->execute([$pid]);
						
						$stmt = null;
						return "Done";
					}else{
							$dateDay = date("Y-m-d");
								$time = date("h:i");
								$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status, time_taken) VALUES ('all_lab', ".$pid.",'index.php', 'Payment Was Cancelled: ', '0', NOW())");
								$stmt->execute([$pid]);
								
								$stmt = null;
								return "Done";
							}
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}


	public function notify_pharm($p_id){
		try {
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$stmt = $this->db->prepare("INSERT INTO notifications (staff_id,patient_id,link, message, status,time_taken) VALUES ('pharm', ".$p_id.",'presc.php?pid=".$p_id."', 'Doctor Prescribed Drugs For: ', '0', NOW())");
			$stmt->execute();
			
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_app($doctor, $p_id,$fee){
		try {
			$link = uniqid();
			$dateDay = date("Y-m-d");
			$time = date("h:i");
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$front2 = $row->front_desk;
			$card_type = $row->card_type;
			$company = $row->company_id;
			if (!empty($company) AND $company != 0) {
					$comp = $company;
				}else{
					$comp = 0;
				}	
			$que = null;
			$stmt = $this->db->prepare("INSERT INTO patient_appointment(front_desk,doctor_id,patient_id,time_added, date_added) 
			VALUES (?,?,?,?,?)");
			$stmt->execute([$front2,$doctor, $p_id,$time, $dateDay]);
			
			$stmt = null;

			$app_id = $this->db->lastInsertId();
				
			$code = rand(1000,100000);
			$date = date("Y-m-d");
			$test = 8;			
			$amount = 0;
			$sta = 0;
			if ($fee == 0) {
				$sta = 1;
			}
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,item, patient_id, card_type, app_id, to_pay, order_id, date_added,amount,payment_status,company_id) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$front2,$test,$p_id,$card_type,$app_id, $fee, $code, $date,$amount,$sta,$comp]);			
			$stmt = null;
					return "Done";
				
				} catch (PDOException $e) {
					// For handling error
					echo 'Error: ' . $e->getMessage();			
				}
	}

	public function edit_vitals($bpsts, $bpstd, $bpsis, $dssit, $pulse, $rds, $temp,$height, $weight,$bmi,$spo2, $allergies, $rbp,  $complaint, $respiratory, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE patient_appointment SET blood_press_stand_s = ?,blood_press_stand_d = ?,blood_press_sit_s = ?,blood_press_sit_d = ?,pulse_rate = ?
			,blood_sugar = ?, temperature=?, height=?,weight=?, bmi=?,spo2=?, allergies=?, routine_blood_pressure=?,  nurse_complaint=?, respiratory=?  WHERE id = ?");
			$stmt->execute([$bpsts, $bpstd, $bpsis, $dssit, $pulse, $rds, $temp,$height, $weight,$bmi,$spo2, $allergies, $rbp,  $complaint, $respiratory, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Vitals could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function extra_test($ecg, $ech, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE patient_appointment SET ecg_result = ?,echo_result = ? WHERE id = ?");
			$stmt->execute([$ecg,$ech,$val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Extra Test could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function seen_result($val){
		try {
		$seen =1;
		$stmt = $this->db->prepare("UPDATE patient_test_group SET seen_result = ? WHERE link_ref = ?");
		$stmt->execute([$seen,$val]);
		$stmt = null;
		return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function insert_test_type($name){
		try {
		$stmt = $this->db->prepare("INSERT INTO lab_test_type(lab_test_type) 
		VALUES (?)");
		$stmt->execute([$name]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_category($name){
		try {
		$stmt = $this->db->prepare("INSERT INTO xray_types(category) 
		VALUES (?)");
		$stmt->execute([$name]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_test_type($name, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE lab_test_type SET lab_test_type = ? WHERE lab_test_type_id = ?");
			$stmt->execute([$name, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test Type could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	public function edit_Category($name, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE xray_types SET category = ? WHERE xray_cat_id = ?");
			$stmt->execute([$name, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Category could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	public function insert_test($name, $fee, $type, $nvalue, $nrange, $rrange){
		try {
		$stmt = $this->db->prepare("INSERT INTO lab_test(lab_test,fee,lab_test_type_id,normal_value,normal_range,reference_range) 
		VALUES (?,?,?,?,?,?)");
		$stmt->execute([$name, $fee, $type, $nvalue, $nrange, $rrange]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function insert_xray($name, $fee, $type){
		try {
		$stmt = $this->db->prepare("INSERT INTO xray(name,fee,category) 
		VALUES (?,?,?)");
		$stmt->execute([$name, $fee, $type]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_note($note,$ipd,$by){
		try {
		$stmt = $this->db->prepare("INSERT INTO notes(note,ipd_numb,added_by,date_added) 
		VALUES (?,?,?,NOW())");
		$stmt->execute([$note,$ipd,$by]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_test($name, $fee, $type, $template, $nvalue, $nrange, $rrange, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE lab_test SET lab_test = ?,fee = ?,lab_test_type_id = ?,template = ?, normal_value = ?,normal_range = ?,reference_range = ? WHERE lab_test_id = ?");
			$stmt->execute([$name, $fee, $type, $template, $nvalue, $nrange, $rrange, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test could not be added
				  </div>: ' . $e->getMessage();
		}
	}

	public function edit_xray($name, $fee, $type,$val){		
		try {
			$stmt = $this->db->prepare("UPDATE xray SET name = ?,fee = ?,category = ? WHERE id = ?");
			$stmt->execute([$name, $fee, $type, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Xray could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function insert_test_request($test,$doctor,$val,$app) {
		try {
			$link = uniqid();
			$this->db->beginTransaction();
			$allfee = 0;
			$type = 2;
			$rows_inserted = 0;

			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$val]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;
			$front = $row->front_desk;
			$que = null;


			$stmt = $this->db->prepare("INSERT INTO patient_test(front_desk,patient_id, patient_appointment_id,link_ref,lab_test_id,lab_test_type_id,staff_id) 
			VALUES (?,?,?,?,?,?,?)");
			foreach($test as $row => $value){
				$fee = $this->get_name_from_id('fee','lab_test','lab_test_id',$row);
				$type = $this->get_name_from_id('lab_test_type_id','lab_test','lab_test_id',$row);
				$stmt->execute(array($front,$val,$app,$link,$row,$type,$doctor));
				$rows_inserted++;
				$allfee1+=$fee;
			}
			
			//get percentage for company
			$que= $this->db->prepare("SELECT * FROM `percentage`  WHERE id = 1");
			$que->execute();
			$row = $que->fetch(PDO::FETCH_OBJ);
			$percentage = $row->percentage;
			$que = null;
			
			if ($card_type == 11) {
				$priceToPay2 = (($percentage/100)*($allfee1));
				$allfee = (($allfee1)+($priceToPay2));
			}else{
				$allfee = $allfee1;
			}
			//query 2	
			$stmt = $this->db->prepare("INSERT INTO patient_test_group(front_desk,patient_id,doctor_id,link_ref,test_num,patient_appointment_id,total_fee)  
			VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$front,$val,$doctor,$link,$rows_inserted,$app,$allfee]);
			
			
			
			//query 3
			$item = 2;
			$status = 0;
			$date = date("Y-m-d");
			//$patient = $this->get_name_from_id('patient_id','patient_appointment','id',$val);
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,order_id,patient_id,app_id,item,to_pay,payment_status, date_added, card_type)  
			VALUES (?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$val,$app,$item,$allfee,$status, $date, $card_type]);
			
			//query 4
			$stmt = $this->db->prepare("INSERT INTO payment(front_desk,reference,patient_id,appointment_id,payment_type_id,amount,payment_status) 
			VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$val,$app,$type,$allfee,$status]);
			$this->db->commit();
			
			$stmt = null;
			$success = 'Done';

			//get sum
			$que= $this->db->prepare("SELECT SUM(to_pay) AS tot FROM `accounts`  WHERE front_desk LIKE ?");
			$que->execute([$front]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$sum = $row->tot;
			$que = null;
			
			//Notify Account
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('front_desk',?,'Payment Needed For: ','view_payment_details.php?id=".$front."&amount=".$sum."&pid=".$val."',NOW(),?)");
			$stmt->execute([$val,$status]);
			$stmt = null;
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test could not be added
				  </div>: ' . $e->getMessage();
		}
		
    }

    public function insert_xray_request($test,$doctor,$val) {
		try {
			$link = uniqid();
			$this->db->beginTransaction(); 
			$allfee = 0;
			$type = 2;
			$rows_inserted = 0;			
			$patient = $this->get_name_from_id('patient_id','patient_appointment','id',$val);
			foreach($test as $row => $value){
				$stmt = $this->db->prepare("INSERT INTO xray_requests (patient_id,appointment_id,link,name,type, staff_id) 
			VALUES (?,?,?,?,?,$doctor)");
			
				$fee = $this->get_name_from_id('fee','xray','id',$row);
				$type = $this->get_name_from_id('category','xray','id',$row);
				$stmt->execute(array($patient,$val,$link,$row,$type));
				$rows_inserted++;
				$allfee+=$fee;
			}
			
			//query 2	
			$stmt = $this->db->prepare("INSERT INTO patient_xray_group(patient_id,doctor_id,link_ref,xray_num,patient_appointment_id,total_fee)  
			VALUES (?,?,?,?,?,?)");
			$stmt->execute([$patient,$doctor,$link,$rows_inserted,$val,$allfee]);
			
			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$patient]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;
			$front = $row->front_desk;
			$que = null;
			//query 3
			$item = 6;
			$status = 0;
			$date = date("Y-m-d");
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,order_id,patient_id,app_id,item,to_pay,payment_status, date_added, card_type)  
			VALUES (?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$patient,$val,$item,$allfee,$status, $date, $card_type]);
			
			//query 4
			$stmt = $this->db->prepare("INSERT INTO payment(front_desk,reference,patient_id,appointment_id,payment_type_id,amount,payment_status) 
			VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$patient,$val,$type,$allfee,$status]);
			$this->db->commit();
			
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Xray could not be added
				  </div>: ' . $e->getMessage();
		}
		
    }

    
	
	public function insert_test_request_front($test,$doctor,$val) {
		try {
			$link = uniqid();
			$this->db->beginTransaction();
			$allfee = 0;
			$type = 2;
			$rows_inserted = 0;
			$app_id = 0;

			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$val]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;
			$front = $row->front_desk;
			$que = null;


			$stmt = $this->db->prepare("INSERT INTO patient_test(front_desk,patient_id, patient_appointment_id,link_ref,lab_test_id,lab_test_type_id) 
			VALUES (?,?,?,?,?,?)");
			foreach($test as $row => $value){
				$fee = $this->get_name_from_id('fee','lab_test','lab_test_id',$row);
				$type = $this->get_name_from_id('lab_test_type_id','lab_test','lab_test_id',$row);
				$stmt->execute(array($front,$val,$app_id,$link,$row,$type));
				$rows_inserted++;
				$allfee+=$fee;
			}
			
			//query 2	

			$doc = 0;
			$stmt = $this->db->prepare("INSERT INTO patient_test_group(front_desk,patient_id,doctor_id,link_ref,test_num,patient_appointment_id,total_fee)  
			VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$front,$val,$doc,$link,$rows_inserted,$app_id,$allfee]);
			
			
			
			//query 3
			$item = 2;
			$status = 0;
			$date = date("Y-m-d");
			//$patient = $this->get_name_from_id('patient_id','patient_appointment','id',$val);
			$stmt = $this->db->prepare("INSERT INTO accounts(front_desk,order_id,patient_id,app_id,item,to_pay,payment_status, date_added, card_type)  
			VALUES (?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$val,$app_id,$item,$allfee,$status, $date, $card_type]);
			
			//query 4
			$stmt = $this->db->prepare("INSERT INTO payment(front_desk,reference,patient_id,appointment_id,payment_type_id,amount,payment_status) 
			VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$front,$link,$val,$app_id,$type,$allfee,$status]);
			$this->db->commit();
			
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test could not be added
				  </div>: ' . $e->getMessage();
		}
		
    }
	
	public function insert_test_result($result, $o, $h, $remark, $stat, $val, $link){		
		try {
			$this->db->beginTransaction();
			$stmt = $this->db->prepare("UPDATE patient_test SET lab_result = ?,o = ?,h = ?,remarks = ?,tested = ? WHERE patient_test_id = ?");
			$stmt->execute([$result, $o, $h, $remark, $stat, $val]);
			
			//query 2
			$res = 1;
			$stmt = $this->db->prepare("UPDATE patient_test_group SET awaiting_result = ? WHERE link_ref = ?" );
			$stmt->execute([$res,$link]);
			$this->db->commit();
			
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test Result could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	
	public function insert_ress($p_id, $test, $id, $value, $temp, $name_id){		
		try {
			$stmt = $this->db->prepare("INSERT INTO patient_test_result(app_id,test_id,ref_id, value, test_name, lab_temp_id) 
			VALUES (?,?,?,?,?,?)");
			$stmt->execute([$p_id, $test, $id, $value, $temp, $name_id]);
			$stmt = null;
			
			//query 2
			$res = 1;
			$stmt = $this->db->prepare("UPDATE patient_test_group SET awaiting_result = ? WHERE link_ref = ?" );
			$stmt->execute([$res,$id]);
			
			
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Test Result could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function insert_xray_ress($p_id, $app_id, $xray_id, $ref_id,$file, $comment, $xname, $category){		
		try {
			
			$stmt = $this->db->prepare("INSERT INTO patient_xray_result(patient_id,app_id,xray_id,ref_id,file, comment, xray_name, category) 
			VALUES (?,?,?,?,?,?,?,?)");
			$stmt->execute([$p_id, $app_id, $xray_id, $ref_id,$file, $comment, $xname, $category]);
			$stmt = null;
			
			//query 2
			$res = 1;
			$stmt = $this->db->prepare("UPDATE patient_xray_group SET awaiting_result = ? WHERE link_ref = ?" );
			$stmt->execute([$res,$ref_id]);
			
			
			$stmt = null;
			$success = 'yesi';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Xray Result could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function insert_extra_file($name,$patient,$val,$fullname){
		try {
		$stmt = $this->db->prepare("INSERT INTO extra_file(name,patient_id,patient_appointment_id,extra_file) 
		VALUES (?,?,?,?)");
		$stmt->execute([$name,$patient,$val,$fullname]);
		$stmt = null;
		return "yesi";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Name Already Exist
				  </div>:' . $e->getMessage();			
		}
	}

	public function insert_xray_file($name,$fullname,$link,$pid){
		try {
		$stmt = $this->db->prepare("INSERT INTO scan_files(comment,extra_file,link_ref,patient_id) 
		VALUES (?,?,?,?)");
		$stmt->execute([$name,$fullname,$link,$pid]);
		$stmt = null;
		return "yesi";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Name Already Exist
				  </div>:' . $e->getMessage();			
		}
	}

	public function insert_ipdf($company, $breakfast, $lunch, $dinner, $amount, $val){		
		try {
			$stmt = $this->db->prepare("INSERT INTO ipd_food(patient_id,company,breakfast,lunch,dinner,amount) 
			VALUES (?,?,?,?,?,?)");
			$stmt->execute([$val,$company, $breakfast, $lunch, $dinner, $amount]);
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Ipd could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function edit_ipdf($company, $breakfast, $lunch, $dinner, $amount, $val){
		try {
		$stmt = $this->db->prepare("UPDATE ipd_food SET company = ?,breakfast = ?,lunch = ?,dinner = ?,amount = ? WHERE ipd_food_id = ?");
			$stmt->execute([$company, $breakfast, $lunch, $dinner, $amount, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Ipd could not be updated
				  </div>:' . $e->getMessage();			
		}
	}
	
	
	
	public function insert_case($diagnosis, $pharm, $tabs,$dosage,$duration, $quantity, $instruction, $doc_id, $id, $p_id,$stabs,$squantity,$sdosage,$sduration){		
		try {
			
			$status = 0;
			$type = 1;
			$ref = rand(1000,100000);
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$front = $row->front_desk;
			$comp = $row->company_id;

			if (empty($stabs)) {
				$stabs = "";
			}

			if (empty($squantity)) {
				$squantity = "";
			}

			if (empty($sdosage)) {
				$sdosage = "";
			}

			if (empty($sduration)) {
				$sduration = "";
			}

			$stmt = $this->db->prepare("INSERT INTO prescription(company_id,front_desk,reference,diagnosis,pharm_stock_id, tabs,dosage, duration, quantity_dispense,stabs,sdosage, sduration, squantity_dispense,instruction,doctor_id,appointment_id,patient_id) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$comp,$front,$ref,$diagnosis,$pharm, $tabs,$dosage, $duration, $quantity,$stabs,$sdosage, $sduration, $squantity, $instruction, $doc_id, $id, $p_id]);
			$stmt = null;
			$val = $this->db->lastInsertId();
			
			$stmt2 = $this->db->prepare("UPDATE patient_appointment SET diagnosis = ?, treated = ? WHERE id = ?" );
			$stmt2->execute([$diagnosis,$type,$id]);
			$stmt2 = null;
			
			$que= $this->db->prepare("SELECT * FROM prescription WHERE prescription_id = ?");
			$que->execute([$val]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$pharm_stock_id = $row->pharm_stock_id;
			$p_id = $row->patient_id;
			$app_id = $row->appointment_id;
			$tabs = $row->tabs;
			$que = null;
			
			$que= $this->db->prepare("SELECT * FROM pharm_stock WHERE id = ?");
			$que->execute([$pharm_stock_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$price = $row->price;
			$que = null;	
			

			//lets do the maths
			$priceToPay1 = $tabs * $price;
					
			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;			
			$front2 = $row->front_desk;
			$que = null;

			//get percentage for company
			$que= $this->db->prepare("SELECT * FROM `percentage`  WHERE id = 1");
			$que->execute();
			$row = $que->fetch(PDO::FETCH_OBJ);
			$percentage = $row->percentage;
			$que = null;

			if ($card_type == 11) {
				$priceToPay2 = (($percentage/100)*($priceToPay1));
				$priceToPay = (($priceToPay1)+($priceToPay2));
			}else{
				$priceToPay = $priceToPay1;
			}	

			$name = 3;					
			$date = date("Y-m-d");
			$stmt = $this->db->prepare("INSERT INTO accounts(company_id,front_desk,item, patient_id, app_id,  to_pay, order_id, date_added, card_type) 
			VALUES (?,?,?,?,?,?,?,?,?)");
			$stmt->execute([$comp,$front2,$name, $p_id, $app_id, $priceToPay, $ref, $date, $card_type]);
					
			$type = 3;
			
			$stmt2 = $this->db->prepare("INSERT INTO payment(company_id,front_desk,reference,patient_id,appointment_id,payment_type_id,amount) 
			VALUES (?,?,?,?,?,?,?)");
			$stmt2->execute([$comp,$front2,$ref,$p_id,$app_id,$type,$priceToPay]);
			$stmt2 = null;


			//get sum
			$que= $this->db->prepare("SELECT SUM(to_pay) AS tot FROM `accounts`  WHERE front_desk LIKE ?");
			$que->execute([$front2]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$sum = $row->tot;
			$que = null;

			//Notify Account
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('front_desk',?,'Payment Needed For: ','view_payment_details.php?id=".$front2."&amount=".$sum."&pid=".$p_id."',NOW(),?)");
			$stmt->execute([$p_id,$status]);

			//Notify Pharmacy
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('pharm',?,'Prescription Made For: ','presc.php?pid=".$p_id."',NOW(),?)");
			$stmt->execute([$p_id,$status]);

			header("Refresh:1; Location: ../module4/prescriptions");



			return "Done";
			
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Prescription could not be added
				  </div>: ' . $e->getMessage();
		}
	}

	public function edit_presc_case($diagnosis, $pharm, $tabs,$dosage,$duration, $quantity, $instruction, $id,$stabs,$squantity,$sdosage,$sduration,$p){		
		try {
			

			if (empty($stabs)) {
				$stabs = "";
			}

			if (empty($squantity)) {
				$squantity = "";
			}

			if (empty($sdosage)) {
				$sdosage = "";
			}

			if (empty($sduration)) {
				$sduration = "";
			}

			$stmt = $this->db->prepare("UPDATE `prescription` SET `diagnosis` = ?, `tabs` = ?, `dosage` = ?, `duration` = ?, `quantity_dispense` = ?, `stabs` = ?, `squantity_dispense` = ?, `sdosage` = ?, `sduration` = ?, `instruction` = ?,`status` = 0 WHERE `prescription_id` = ?;");
			$stmt->execute([$diagnosis,$tabs,$dosage, $duration, $quantity,$stabs,$sdosage, $sduration, $squantity, $instruction,$p]);
			$stmt = null;
			
			$que= $this->db->prepare("SELECT * FROM prescription WHERE prescription_id = ?");
			$que->execute([$p]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$pharm_stock_id = $row->pharm_stock_id;
			$p_id = $row->patient_id;
			$app_id = $row->appointment_id;
			$ref = $row->reference;
			$tabs = $row->tabs;
			$que = null;
			
			$que= $this->db->prepare("SELECT * FROM pharm_stock WHERE id = ?");
			$que->execute([$pharm_stock_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$price = $row->price;
			$que = null;	
			

			//lets do the maths
			$priceToPay1 = $tabs * $price;
					
			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;			
			$front2 = $row->front_desk;
			$que = null;

			//get percentage for company
			$que= $this->db->prepare("SELECT * FROM `percentage`  WHERE id = 1");
			$que->execute();
			$row = $que->fetch(PDO::FETCH_OBJ);
			$percentage = $row->percentage;
			$que = null;

			if ($card_type == 11) {
				$priceToPay2 = (($percentage/100)*($priceToPay1));
				$priceToPay = (($priceToPay1)+($priceToPay2));
			}else{
				$priceToPay = $priceToPay1;
			}	

			$stat = 3;					
			$date = date("Y-m-d");
			$stmt = $this->db->prepare("UPDATE accounts SET to_pay = ?,payment_status = ? WHERE order_id = ?");
			$stmt->execute([$priceToPay,$stat,$ref]);
					
			$type = 0;			
			$stmt2 = $this->db->prepare("UPDATE payment SET amount = ?,payment_status = ? WHERE reference = ?");
			$stmt2->execute([$priceToPay,$type,$ref]);
			$stmt2 = null;


			//get sum
			$que= $this->db->prepare("SELECT SUM(to_pay) AS tot FROM accounts  WHERE front_desk LIKE ?");
			$que->execute([$front2]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$sum = $row->tot;
			$que = null;

			//Notify Account
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('front_desk',?,'Payment Needed For: ','view_payment_details.php?id=".$front2."&amount=".$sum."&pid=".$p_id."',NOW(),?)");
			$stmt->execute([$p_id,$status]);

			//Notify Pharmacy
			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES ('pharm',?,'Prescription Altered For: ','presc.php?pid=".$p_id."',NOW(),?)");
			$stmt->execute([$p_id,$status]);

			return "Done";
			
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Prescription could not be added
				  </div>: ' . $e->getMessage();
		}
	}
	

	public function insert_lab_res($DataArr,$doc_id, $app_id, $p_id, $tech_id){
		try {
			$data_count = count($DataArr);
			for ($i=0; $i<$data_count; $i++) {
				$test = htmlspecialchars(ucfirst($_POST['test'][$i]));
				$test = stripslashes(ucfirst($_POST['test'][$i]));
				$test = trim(ucfirst($_POST['test'][$i]));
					
				$result = htmlspecialchars($_POST['result'][$i]);
				$result = stripslashes($_POST['result'][$i]);
				$result = trim($_POST['result'][$i]);
					
				$flag = htmlspecialchars($_POST['flag'][$i]);
				$flag = stripslashes($_POST['flag'][$i]);
				$flag = trim($_POST['flag'][$i]);
					
				$units = htmlspecialchars($_POST['units'][$i]);
				$units = stripslashes($_POST['units'][$i]);
				$units = trim($_POST['units'][$i]);

				$ref = htmlspecialchars($_POST['ref'][$i]);
				$ref = stripslashes($_POST['ref'][$i]);
				$ref = trim($_POST['ref'][$i]);

				$range = htmlspecialchars($_POST['range'][$i]);
				$range = stripslashes($_POST['range'][$i]);
				$range = trim($_POST['range'][$i]);

				$stmt = $this->db->prepare("INSERT INTO lab_result(lab_test, test_result, test_flag, test_units, test_ref, test_range, doctor_id, patient_id, appointment_id, lab_tech_id) 
					VALUES (?,?,?,?,?,?,?,?,?,?)");
					
				$stmt->execute(array($test, $result, $flag, $units, $ref, $range, $doc_id, $p_id, $app_id, $tech_id));					
				$stmt = null;
				
			}
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	

	public function insert_physiotherapy_request($app,$staff,$pid,$front_desk){
		try {
		$link = uniqid();
		$this->db->beginTransaction();
		//$status = 0;
		//$now = date("Y-m-d h:i:s");
		$stmt = $this->db->prepare("INSERT INTO `physiotherapy_requests` (`patient_id`, `staff_id`, `link_ref`, `front_desk`, `patient_appointment_id`, `status`, `date_added`) VALUES (".$pid.",".$staff.",'".$link."','".$front_desk."',".$app.",0,NOW())");
		$stmt->execute([$pid,$staff,$link,$front_desk,$app]);
		$stmt = null;
		
		//get card_type
		$stmt= $this->db->prepare("SELECT * FROM `patients` WHERE `id` = ?");
		$stmt->execute([$pid]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$card_type = $row->card_type;
		$stmt = null;

		//Bill patient	
		$test = 7;
		$cost = 2500;
		//$date = date("Y-m-d");
		$stmt = $this->db->prepare("INSERT INTO accounts(`front_desk`,`item`,`patient_id`, `card_type`, `app_id`, `to_pay`, `order_id`, `date_added`) 
		VALUES ('".$front_desk."',?,?,?,?,?,'".$link."',NOW())");
		$stmt->execute([$front_desk,$test, $pid, $card_type, $app, $cost, $link]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}


	public function insert_admission_request($val,$doc,$p_id,$ward){
		try {
		//get Front_desk
		$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
		$que->execute([$p_id]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$front2 = $row->front_desk;
		$fname = $row->title." ".$row->surname." ".$row->first_name;
		$sex = $row->sex;

		$stmt = $this->db->prepare("INSERT INTO admission_request(`appointment_id`,`doctor_id`,`patient_id`,`ward_id`,`front_desk`) 
		VALUES (?,?,?,?,?)");
		$stmt->execute([$val,$doc,$p_id,$ward,$front2]);

		$stmt = null;

		//Notify Nurses
		$status = 0;
		$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
		VALUES ('All_Nurses',?,'Admission Requested For: ','view_test?id=5cf97f936285c&n=Mr Oghenekaro Brume&s=Male&a=24&pid=1&did=57',NOW(),?)");
		$stmt->execute([$p_id,$status]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function update_admission_request($id,$stat){
		try {
		$stmt = $this->db->prepare("UPDATE admission_request SET status = ? WHERE admission_request_id = ?");
		$stmt->execute([$stat,$id]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function insert_cat($cat_name){
		try {
		$stmt = $this->db->prepare("INSERT INTO pharm_category(cat_name) 
		VALUES (?)");
		$stmt->execute([$cat_name]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_cat($cat_name, $val){
		try {
		
		$stmt = $this->db->prepare("UPDATE pharm_category SET cat_name = ? WHERE id = ?");
			$stmt->execute([$cat_name, $val]);
			
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function process_prescription($val,$status,$quantity,$pharm,$doc){
		try {
		$this->db->beginTransaction();
		
		$stmt = $this->db->prepare("UPDATE prescription SET pres_status = ? WHERE prescription_id = ?");
		$stmt->execute([$status, $val]);
		
		$stmt = $this->db->prepare("UPDATE pharm_stock SET stock = ? WHERE id = ?" );
		$stmt->execute([$quantity,$pharm]);
		
		
		$this->db->commit();
			
		$stmt = null;

		//Notify Doctor
		$stmt = $this->db->prepare("SELECT DISTINCT id FROM patient_appointment WHERE patient_id = ?");
		$stmt->execute([$p_id]);
		$row = $stmt->fetch(PDO::FETCH_OBJ);
		$app = $row->id;
		$stmt = null;


			$status = 0;
			$stmt = $this->db->prepare("INSERT INTO notifications(staff_id,patient_id,message,link,time_taken,status)  
			VALUES (?,?,'Prescription Given To: ','lab_results?id=".$app."',NOW(),?)");
			$stmt->execute([$doc,$p_id,$status]);

		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_bed($bed){
		try {
		$stmt = $this->db->prepare("INSERT INTO bed(bed) 
		VALUES (?)");
		$stmt->execute([$bed]);
		
		$stmt = null;
		$success = '<div class="alert alert-danger">
					Successful
				  </div>';
		echo $success;
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_bed($bed, $val){
		try {
		
		$stmt = $this->db->prepare("UPDATE bed SET bed = ? WHERE bed_id = ?");
		$stmt->execute([$bed, $val]);
		$stmt = null;
		$success = '<div class="alert alert-danger">
					Successfully updated
				  </div>';
		echo $success;
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_unit($unit_name){
		try {
		$stmt = $this->db->prepare("INSERT INTO pharm_units(unit_name) 
		VALUES (?)");
		$stmt->execute([$unit_name]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_unit($unit_name, $val){
		try {
		
		$stmt = $this->db->prepare("UPDATE pharm_units SET unit_name = ? WHERE id = ?");
			$stmt->execute([$unit_name, $val]);
			
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_stock($name, $cat, $unit,$cost, $price, $stock){
		try {
		$stmt = $this->db->prepare("INSERT INTO pharm_stock(name, category, units,cost_price, price, stock) 
		VALUES (?,?,?,?,?,?)");
		$stmt->execute([$name, $cat, $unit,$cost, $price, $stock]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_stock($name, $cat, $unit,$cost, $price, $stock, $val){
		try {
		
		$stmt = $this->db->prepare("UPDATE pharm_stock SET name = ?, category =?, units=?,cost_price = ?, price=?, stock=? WHERE id = ?");
			$stmt->execute([$name, $cat, $unit,$cost, $price, $stock, $val]);
			
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function get_the_price($proName, $proQty){
		try {
			$que= $this->db->prepare("SELECT price, stock FROM pharm_stock WHERE name = ?");
			$que->execute([$proName]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			if($row){
				$price = $row->price;
				$stock = $row->stock;
			
				$que = null;
				
				$sign = 'Done';

				$stock_level = "";

				$low_stock = '<div class="alert alert-danger">This product is out of stock '.$stock.' left</div>';

				if($stock < $proQty){
					echo json_encode(array("value" => $sign, "error" => $low_stock));

				} else if($stock > $proQty){
					$minVal = "";
					echo json_encode(array("value" => $sign, "value2" => $price));
				}
			} else {
				$no = "no";
				echo json_encode(array("value" => $no));
			}
						
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 An error occurred
				  </div>: ' . $e->getMessage();
		}
	}

	public function send_acc($DataArr, $app_id, $p_id, $code){
		try {
			$data_count = count($DataArr);
			for ($i=0; $i<$data_count; $i++) {
				$name = htmlspecialchars(ucfirst($_POST['name'][$i]));
				$name = stripslashes(ucfirst($_POST['name'][$i]));
				$name = trim(ucfirst($_POST['name'][$i]));
					
				$qty = htmlspecialchars($_POST['qty'][$i]);
				$qty = stripslashes($_POST['qty'][$i]);
				$qty = trim($_POST['qty'][$i]);
					
				$intake = htmlspecialchars($_POST['intake'][$i]);
				$intake = stripslashes($_POST['intake'][$i]);
				$intake = trim($_POST['intake'][$i]);
					
				$duration = htmlspecialchars($_POST['duration'][$i]);
				$duration = stripslashes($_POST['duration'][$i]);
				$duration = trim($_POST['duration'][$i]);
				
				$price = htmlspecialchars($_POST['price'][$i]);
				$price = stripslashes($_POST['price'][$i]);
				$price = trim($_POST['price'][$i]);
					
				//lets do the maths
				$toTake = ($qty * $intake) * $duration;
				$priceToPay = $toTake * $price;
				
				//get card_type
				$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
				$que->execute([$p_id]);
				$row = $que->fetch(PDO::FETCH_OBJ);
				$card_type = $row->card_type;
				$que = null;
			
							
				$date = date("Y-m-d");
				$stmt = $this->db->prepare("INSERT INTO accounts(item, patient_id, app_id, amount, to_pay, order_id, date_added, card_type) 
				VALUES (?,?,?,?,?,?,?,?)");
				$stmt->execute([$name, $p_id, $app_id, $price, $priceToPay, $code, $date, $card_type]);
				
				$type = 1;
				$stmt2 = $this->db->prepare("INSERT INTO payment(reference,patient_id,appointment_id,payment_type_id,amount) 
				VALUES (?,?,?,?,?)");
				$stmt2->execute([$code,$p_id,$app_id,$type,$priceToPay]);
				$stmt2 = null;
			}
			
			$stmt = null;
			echo json_encode(array("value" => "Done", "value2" => $priceToPay));
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	//Change order status
	public function change_status($status, $app_id, $patient_id, $order_id){		
		try {
			$stmt = $this->db->prepare("UPDATE accounts SET payment_status = ? WHERE order_id = ? ");
			$stmt->execute([$status,$order_id]);
			$stmt = null;

			$stmt = $this->db->prepare("UPDATE pharm_notifications SET status = ? WHERE appointment_id = ? AND patient_id = ?");
			$stmt->execute([$status,$app_id, $patient_id]);
			$stmt = null;

			//lets also update lab notification payent status
			$que= $this->db->prepare("SELECT appointment_id FROM lab_notifications WHERE appointment_id= ? LIMIT 1"); 
			$que->execute([$app_id]);
			$count = $que->rowCount();
			if($count == 1){
				$stmt = $this->db->prepare("UPDATE lab_notifications SET payment = ? WHERE appointment_id = ? AND patient_id = ?");
				$stmt->execute([$status,$app_id, $patient_id]);
				$stmt = null;
			}
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Payment status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function process_payment_defer_all($val){		
		try {
			$stmt = $this->db->prepare("UPDATE payment SET payment_status = ? WHERE patient_id = ?"); 
			$stmt->execute([$val, $status]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE accounts SET payment_status = ? WHERE patient_id = ?");
			$stmt->execute([$status, $val]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE prescription SET status = ? WHERE patient_id = ?");
			$stmt->execute([$status, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Defer payment is unsuccessful!
				  </div>: ' . $e->getMessage();
		}
	}

	public function process_payment($val,$status,$amount){		
		try {
			$stmt = $this->db->prepare("UPDATE `payment` SET `payment_status` = $status WHERE `reference` LIKE '".$val."'"); 
			$stmt->execute([$status,$val]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE `accounts` SET `payment_status` = $status, `amount` = $amount, `date_paid` = NOW() WHERE order_id LIKE '".$val."'");
			$stmt->execute([$status,$amount,$val]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE `prescription` SET `status` = $status WHERE `reference` LIKE '".$val."'");
			$stmt->execute([$status,$val]);
			$stmt = null;

			$stmt = $this->db->prepare("UPDATE `physiotherapy_requests` SET `status` = $status WHERE `link_ref` LIKE '".$val."'");
			$stmt->execute([$status,$val]);
			$stmt = null;

			$que= $this->db->prepare("SELECT * FROM accounts WHERE order_id LIKE ?");
			$que->execute([$val]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$item = $row->item;
			$pid = $row->patient_id;
			$app = $row->app_id;
			$front_desk = $row->front_desk;
			$que = null;

			if ($item == 2) {
				$staff = "all_lab";
				$msg = "Proceed To Enter Test Result For: ";
				$link = "view_test?id=".$val."&app=".$app;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 3) {
				$staff = "pharm";
				$msg = "Proceed To Process Drugs For: ";
				$link = "presc.php?pid=".$pid;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 4) {
				$staff = "nurses";
				$msg = "Proceed To Process Admission For: ";
				$link = "ipd?id=".$pid;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 6) {
				$staff = "all_xray";
				$msg = "Proceed To Process Xray Scans For: ";
				$link = "lab_results?id=".$app."&pid=".$pid."&ref=".$val;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 7) {
				$staff = "all_physiotherapy";
				$msg = "Payment Made For Therapy By: ";
				$link = "lab_results?id=".$front_desk."ref=".$app."&pid=".$pid."&ref=".$val;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Dispensing chart could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function process_payment_company($status,$comp_id){		
		try {
			$stmt = $this->db->prepare("UPDATE `company_bill` SET `status` = $status WHERE `company_id` = ".$comp_id.""); 
			$stmt->execute([$status,$comp_id]);
			$stmt = null;

			$que= $this->db->prepare("SELECT * FROM accounts WHERE  company_id = ?");
			$que->execute([$comp_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$item = $row->item;
			$pid = $row->patient_id;
			$app = $row->app_id;
			$front_desk = $row->front_desk;
			$val = $row->order_id;
			$que = null;

			if ($item == 2) {
				$staff = "all_lab";
				$msg = "Proceed To Enter Test Result For: ";
				$link = "view_test?id=".$val."&app=".$app;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 3) {
				$staff = "pharm";
				$msg = "Proceed To Process Drugs For: ";
				$link = "presc.php?pid=".$pid;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 4) {
				$staff = "nurses";
				$msg = "Proceed To Process Admission For: ";
				$link = "ipd?id=".$pid;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 6) {
				$staff = "all_xray";
				$msg = "Proceed To Process Xray Scans For: ";
				$link = "lab_results?id=".$app."&pid=".$pid."&ref=".$val;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}elseif ($item == 7) {
				$staff = "all_physiotherapy";
				$msg = "Payment Made For Therapy By: ";
				$link = "lab_results?id=".$front_desk."ref=".$app."&pid=".$pid."&ref=".$val;
				$status = 0;
				$stmt = $this->db->prepare("INSERT INTO notifications(staff_id, patient_id, appointment_id, message, link, status,time_taken) 
				VALUES (?,?,?,?,?,?,NOW())");
				$stmt->execute([$staff, $pid, $app, $msg, $link, $status]);
			}
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Dispensing chart could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function process_cpayment($val,$pid){		
		try {
			$stmt = $this->db->prepare("UPDATE `payment` SET `payment_status` = '0' WHERE `patient_id` = ? AND `reference` LIKE '".$val."'"); 
			$stmt->execute([$pid,$val]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE `accounts` SET `payment_status` = '0', `amount` = '0' WHERE `patient_id` = ? AND `order_id` LIKE '".$val."'");
			$stmt->execute([$pid,$val]);
			$stmt = null;
			$stmt = $this->db->prepare("UPDATE `prescription` SET `status` = '0' WHERE `patient_id` = ? AND `reference` LIKE '".$val."'");
			$stmt->execute([$pid,$val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Dispensing chart could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function send_test_acc($DataArr, $app_id, $p_id, $code){
		try {
			//get card_type
			$que= $this->db->prepare("SELECT * FROM patients WHERE id = ?");
			$que->execute([$p_id]);
			$row = $que->fetch(PDO::FETCH_OBJ);
			$card_type = $row->card_type;
			$que = null;
			
			$data_count = count($DataArr);
			for ($i=0; $i<$data_count; $i++) {
				$test = htmlspecialchars(ucfirst($_POST['test'][$i]));
				$test = stripslashes(ucfirst($_POST['test'][$i]));
				$test = trim(ucfirst($_POST['test'][$i]));
					
				$amt = htmlspecialchars($_POST['amt'][$i]);
				$amt = stripslashes($_POST['amt'][$i]);
				$amt = trim($_POST['amt'][$i]);
				
				$date = date("Y-m-d");
				$stmt = $this->db->prepare("INSERT INTO accounts(item, patient_id, app_id, to_pay, order_id, date_added, card_type) 
				VALUES (?,?,?,?,?,?,?)");
				$stmt->execute([$test, $p_id, $app_id, $amt, $code, $date, $card_type]);
			
			}
			$stmt = null;
			return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_ipd_patient($admin_no, $admin_date, $referred, $doctor, $room, $ward, $bed_num, $p_id, $code, $nurse, $adr){
		try {
		$stat = 1;
		$que= $this->db->prepare("SELECT appointment_id FROM admission_request WHERE patient_id = ? ORDER BY admission_request_id DESC LIMIT 1");
		$que->execute([$p_id]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$app_id = $row->appointment_id;

		if (empty($app_id)) {
			return "<div class='alert alert-danger'> 
					Error: An Appointment Is Needed For This Patient
					</div>";
		}

		$que2= $this->db->prepare("SELECT front_desk FROM patients WHERE id = ?");
		$que2->execute([$p_id]);
		$row = $que2->fetch(PDO::FETCH_OBJ);
		$front2 = $row->front_desk;

		$stmt = $this->db->prepare("INSERT INTO ipd_patients(front_desk,appointment_id,patient_id, order_id, admin_no,admin_date,ref, doctor_id, room, ward, bed_no, nurse) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$front2,$app_id,$p_id, $code, $admin_no, $admin_date, $referred, $doctor, $room, $ward, $bed_num, $nurse]);
		if($adr != 0){
			$this->update_admission_request($adr,$stat);
		}
		$stmt = null;
		return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_therapy_plan($val,$app,$p_id,$link,$comment){
		try {
		$stat = 1;
		$stmt = $this->db->prepare("INSERT INTO `therapy_plans` (`patient_id`, `link_ref`, `appointment_id`, `comment`, `doctor_id`, `time_added`)
		VALUES (?,?,?,?,?,NOW())");
		$stmt->execute([$p_id,$link,$app,$comment,$val]);
		$stmt = null;
		return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	public function insert_therapy_plan2($val,$app,$p_id,$link,$comment){
		try {
		$stat = 0;
		$stmt = $this->db->prepare("INSERT INTO `therapy_plans`(`patient_id`, `link_ref`, `appointment_id`, `front_desk`, `comment`, `doctor_id`, `time_added`)
		VALUES (?,?,?,?,?,?,NOW())");
		$stmt->execute([$p_id,$link,$stat,$app,$comment,$val]);
		$stmt = null;
		return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_therapy_plan($val,$comment){
		try {
		$stmt = $this->db->prepare("UPDATE `therapy_plans` SET `comment` = '".$comment."' WHERE `id` = ".$val."");
		 
		$stmt->execute([$comment,$val]);
		$stmt = null;
		return "Done";
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_admission_status($status, $val){		
		try {
			$date = date("Y-m-d H:i:s");
			$stmt = $this->db->prepare("UPDATE ipd_patients SET admission_status_id = ?,admission_status_date = ? WHERE id = ?");
			$stmt->execute([$status, $date, $val]);
			$stmt = null;
			$success = '<div class="alert alert-danger">
						status updated
					</div>';
			echo $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	
	public function insert_admin_stock($stock, $quantity, $taken, $patient, $quantityLeft){
		try {
		$this->db->beginTransaction();
		$stmt = $this->db->prepare("INSERT INTO admin_stock(pharm_stock_id, quantity, taken_by,patient_id) 
		VALUES (?,?,?,?)");
		$stmt->execute([$stock, $quantity, $taken, $patient]);
		
		$stmt = $this->db->prepare("UPDATE pharm_stock SET stock = ? WHERE id = ?");
		$stmt->execute([$quantityLeft,$stock]);
		$this->db->commit();
		$stmt = null;
		
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_ipd($admin_no, $admin_date, $referred, $doctor, $room, $ward, $bed_num, $nurse, $dis_date, $id){		
		try {
			$stmt = $this->db->prepare("UPDATE ipd_patients SET admin_no = ?,admin_date=?,ref=?, doctor_id=?, room=?, ward=?, bed_no=?, nurse=?, discharged=? WHERE admin_no = ?");
			$stmt->execute([$admin_no, $admin_date, $referred, $doctor, $room, $ward, $bed_num, $nurse, $dis_date, $id]);
			$stmt = null;
			$success = '<div class="alert alert-success">
							Patient details updated
						</div>';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Patient details could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function insert_diagnosis($diagnosis,$complaint, $exam,$patients_note,$adm_note, $val){		
		try {
			$one = 1;
			$stmt = $this->db->prepare("UPDATE patient_appointment SET diagnosis = ?, complaint=?, examination=?, patients_note = ?,adm_note = ?, treated = ? WHERE id = ?");
			$stmt->execute([$diagnosis,$complaint,$exam,$patients_note,$adm_note, $one, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Diagnosis could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	public function insert_obs($temp, $resr, $pulse, $bp, $intake, $output, $by, $ipd){
		try {
		$stmt = $this->db->prepare("INSERT INTO patient_obs(ipd_patient_id,temp, resr, pulse,bp,intake, output, done_by) 
		VALUES (?,?,?,?,?,?,?,?)");
		$stmt->execute([$ipd,$temp, $resr, $pulse, $bp, $intake, $output, $by]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Observation could not be addded
				  </div>: ' . $e->getMessage();			
		}
	}
	
	public function edit_obs($temp, $resr, $pulse, $bp, $intake, $output, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE patient_obs SET temp = ?, resr = ?, pulse = ? ,bp = ? ,intake = ? , output = ? WHERE patient_obs_id = ?");
			$stmt->execute([$temp, $resr, $pulse, $bp, $intake, $output, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Observation could not be updated
				  </div>: ' . $e->getMessage();
		}
	}

	
	public function insert_dis($pharm, $dosage, $meth, $remark, $by, $ipd){
		try {
		$stmt = $this->db->prepare("INSERT INTO dispensing_chart(ipd_patient_id,pharm_stock_id, dosage, meth_administration,given_by,remark) 
		VALUES (?,?,?,?,?,?)");
		$stmt->execute([$ipd,$pharm, $dosage, $meth, $by, $remark]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Dispensing chart could not be addded
				  </div>: ' . $e->getMessage();			
		}
	}
	
	public function edit_dis($pharm, $dosage, $meth, $remark, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE dispensing_chart SET pharm_stock_id = ?, dosage = ?, meth_administration = ? ,remark = ? WHERE dispensing_chart_id = ?");
			$stmt->execute([$pharm, $dosage, $meth, $remark, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Dispensing chart could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	
	public function insert_fluid($nature, $oral, $rectal, $iv, $other1, $total1, $urine, $vomit, $tube, $other2, $total2, $balance, $chloride, $ipd){
		try {
		$stmt = $this->db->prepare("INSERT INTO patient_fluid(ipd_patient_id,nature, oral,rectal,iv,intake_other,intake_total,urine,vomit,tube,output_other,output_total,
									balance,chloride) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$ipd,$nature, $oral, $rectal, $iv, $other1, $total1, $urine, $vomit, $tube, $other2, $total2, $balance, $chloride]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Fluid Chart could not be addded
				  </div>: ' . $e->getMessage();			
		}
	}
	
	public function edit_fluid($nature, $oral, $rectal, $iv, $other1, $total1, $urine, $vomit, $tube, $other2, $total2, $balance, $chloride, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE patient_fluid SET nature = ?, oral = ?,rectal = ?,iv = ?,intake_other = ?,intake_total = ?,urine = ?,vomit = ?,tube = ?
			,output_other = ?,output_total = ?,balance = ?,chloride = ? WHERE patient_fluid_id = ?");
			$stmt->execute([$nature, $oral, $rectal, $iv, $other1, $total1, $urine, $vomit, $tube, $other2, $total2, $balance, $chloride, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Fluid Chart could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function insert_surgery($allow, $patient, $address){
		try {
		$stmt = $this->db->prepare("INSERT INTO surgery_perm(patient_id,accepted_by, address) 
		VALUES (?,?,?)");
		$stmt->execute([$patient,$allow, $address]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					Surgery Agreement not sent	
				  </div>: ' . $e->getMessage();			
		}
	}
	
	
	public function select_transcripts(){
		try {
			$que= $this->db->prepare("SELECT * FROM students a 
            LEFT JOIN transcript b ON a.matNo = b.matNo
            GROUP BY b.matNo 
            ORDER BY b.TransID DESC");
			$que->execute([]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
    }
    
    public function select_outstandings(){
		try {
			$que= $this->db->prepare("SELECT COUNT(a.matNo) as outstandings,
            a.lastName as lastName,a.firstName as firstName, 
            a.middleName as middleName, a.matNo as matNo,a.level as level FROM students a 
            LEFT JOIN transcript b ON a.matNo = b.matNo                     
            LEFT JOIN courses c ON b.courseID = c.courseID           
            WHERE (b.score >= 0 AND b.score <= 39 AND c.courseType = 1)   
            OR (b.score >= 0 AND b.score <= 49 AND c.courseType > 1)    
            GROUP BY b.matNo 										
            ORDER BY b.TransID DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
    }
    
    public function select_outstanding($level,$lname){
		try {
			$que= $this->db->prepare("SELECT COUNT(a.matNo) as outstandings,
            a.lastName as lastName,a.firstName as firstName, 
            a.middleName as middleName, a.matNo as matNo,a.level as level FROM students a 
            LEFT JOIN transcript b ON a.matNo = b.matNo                     
            LEFT JOIN courses c ON b.courseID = c.courseID           
            WHERE (a.level = ? OR a.lastName = ?)
            AND(b.score >= 0 AND b.score <= 39 AND c.courseType = 1)   
            OR (b.score >= 0 AND b.score <= 49 AND c.courseType > 1)  
            GROUP BY b.matNo 										
            ORDER BY b.TransID DESC");
			$que->execute([$level,$lname]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
    }
    
    public function select_outstanding1($matNumber){
		try {
			$que= $this->db->prepare("SELECT
            b.courseID,b.score as score,b.level,b.semester FROM students a 
            LEFT JOIN transcripttemp b ON a.matNo = b.matNo                     
            LEFT JOIN courses c ON b.courseID = c.courseID           
            WHERE (b.score >= 0 AND b.score <= 39 AND c.courseType = 1)   
            OR (b.score >= 0 AND b.score <= 49 AND c.courseType > 1)  
            AND (a.matNo = ?)										
            ORDER BY b.TransID DESC");
			$que->execute([$matNumber]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_test_front(){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_test_group a 
										
										left join patients d on a.patient_id = d.id
										left join accounts e on a.link_ref = e.order_id
										ORDER BY a.patient_test_group_id DESC");
			$que->execute([]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_test_to($value){
		try {
			$que= $this->db->prepare("SELECT * FROM lab_test a 
										left join lab_test_type b on a.lab_test_type_id = b.lab_test_type_id
										WHERE a.lab_test_type_id =? ORDER BY b.lab_test_type ASC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_xray_to($value){
		try {
			$que= $this->db->prepare("SELECT * FROM xray a left join xray_types b on a.category = b.xray_cat_id WHERE a.category = ? ORDER BY  b.category ASC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_all_test($value){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_test a 
										left join lab_test_type b on a.lab_test_type_id = b.lab_test_type_id
										Where a.link_ref =?  group by a.lab_test_type_id ORDER BY b.lab_test_type ASC");
			$que->execute([$value]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_view_tests($value){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_test WHERE link_ref LIKE '%".$value."%' ORDER BY patient_test_id DESC");
			$que->execute([$value]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_view_xrays($value){
		try {
			$que= $this->db->prepare("SELECT * FROM xray_requests WHERE link LIKE '%".$value."%' ORDER BY id DESC");
			$que->execute([$value]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	

	public function select_all_test3($value,$ref){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_test a 
										left join lab_test b on a.lab_test_id = b.lab_test_id
										Where a.lab_test_type_id =? AND a.link_ref =? ORDER BY b.lab_test ASC");
			$que->execute([$value,$ref]);
			$arr = $que->fetchAll();
			return $arr;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_all_test2($value,$ref){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_test a 
										left join lab_test b on a.lab_test_id = b.lab_test_id 
										left join lab_test_type c on b.lab_test_type_id = c.lab_test_type_id
										Where c.lab_test_type_id =? AND a.link_ref =? ORDER BY b.lab_test ASC");
			$que->execute([$value,$ref]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_from_admin_stock(){
		try {
			$que= $this->db->prepare("SELECT * FROM admin_stock a 
										left join pharm_stock b on a.pharm_stock_id = b.id 
										left join patients c on a.patient_id = c.id
										ORDER BY a.admin_stock_id DESC");
			$que->execute([]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_from_obs($value){
		try {
			$que= $this->db->prepare("SELECT * FROM patient_obs a 
										left join staff b on a.done_by = b.user_id 
										where a.ipd_patient_id = ? ORDER BY a.patient_obs_id DESC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_from_physiotherapy_doc(){
		try {
			$que= $this->db->prepare("SELECT * FROM physiotherapy_requests a left join staff b on a.staff_id = b.user_id WHERE b.role_id = 5 ORDER BY a.physiotherapy_id DESC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function select_from_physiotherapy_front(){
		try {
			$que= $this->db->prepare("SELECT * FROM physiotherapy_requests a left join staff b on a.staff_id = b.user_id WHERE b.role_id = 2 OR b.role_id = 1 ORDER BY a.physiotherapy_id DESC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_from_dis($value){
		try {
			$que= $this->db->prepare("SELECT * FROM dispensing_chart a 
										left join staff b on a.given_by = b.user_id 
										left join pharm_stock c on a.pharm_stock_id = c.id 
										where a.ipd_patient_id = ? ORDER BY a.dispensing_chart_id DESC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_from_prescription($value){
		try {
			$que= $this->db->prepare("SELECT * FROM prescription a 
										left join staff b on a.doctor_id = b.user_id 
										left join pharm_stock c on a.pharm_stock_id = c.id
										where a.appointment_id = ? ORDER BY a.prescription_id DESC");
			$que->execute([$value]);
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	public function select_from_prescription_all(){
		try {
			$que= $this->db->prepare("SELECT * FROM prescription a 
										left join staff b on a.doctor_id = b.user_id 
										left join pharm_stock c on a.pharm_stock_id = c.id
										ORDER BY a.prescription_id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_add_admission(){
		try {
			$que= $this->db->prepare("SELECT * FROM admission_request a 
										left join patient_appointment b on a.appointment_id = b.id 
										left join patients c on b.patient_id = c.id 
										ORDER BY a.admission_request_id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_payment(){
		try {
			$que= $this->db->prepare("SELECT * FROM payment a 
										left join patients b on a.patient_id = b.id 
										left join payment_type c on a.payment_type_id = c.payment_type_id 
										ORDER BY a.payment_id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_payment2(){
		try {
			$que= $this->db->prepare("SELECT * FROM accounts ORDER BY id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

		public function select_payment3(){
		try {
			$que= $this->db->prepare("SELECT * , SUM(to_pay), GROUP_CONCAT(item) FROM accounts GROUP BY patient_id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}
	
	public function select_payment4(){
		try {
			$que= $this->db->prepare("SELECT * , SUM(to_pay),SUM(amount),GROUP_CONCAT(payment_status), GROUP_CONCAT(item), GROUP_CONCAT(order_id) FROM accounts GROUP BY patient_id DESC");
			$que->execute();
			return $que;
			$que = null;			
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();
			
		}
	}

	public function insert_card($name, $cost){
		try {
		$stmt = $this->db->prepare("INSERT INTO card_types(name, cost) 
		VALUES (?,?)");
		$stmt->execute([$name, $cost]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function insert_custom_result($res,$val){
		try {
		$stmt = $this->db->prepare("INSERT INTO custom_result(result, ref,date_added) 
		VALUES (?,?,NOW())");
		$stmt->execute([$res,$val]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function edit_card($name, $cost, $val){
		try {
			$stmt = $this->db->prepare("UPDATE card_types SET name = ?, cost=? WHERE id = ?");
			$stmt->execute([$name, $cost, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function insert_stat($morn, $bed, $v_bed, $t_pt, $adm, $disc, $delivery, $cs, $labour, $trans, $death, $comment){
		try {
		$stmt = $this->db->prepare("INSERT INTO duty_check(morn, bed, v_bed, t_pt, adm, disc, delivery, cs, labour, trans, death, comment) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$morn, $bed, $v_bed, $t_pt, $adm, $disc, $delivery, $cs, $labour, $trans, $death, $comment]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_stat($morn, $bed, $v_bed, $t_pt, $adm, $disc, $delivery, $cs, $labour, $trans, $death, $comment, $val){
		try {
			$stmt = $this->db->prepare("UPDATE duty_check SET morn = ?, bed=?, v_bed=?, t_pt=?, adm=?, disc=?, delivery=?, cs=?, labour=?, trans=?, death=?, comment=? WHERE id = ?");
			$stmt->execute([$morn, $bed, $v_bed, $t_pt, $adm, $disc, $delivery, $cs, $labour, $trans, $death, $comment, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function insert_ante($name, $pos, $sex, $dob, $house_num, $town, $village, $ward, $state, $lga, $mother_name,$mother_phone,$father_name, $father_phone, $cg, $cg_phone,
		$DataArr, $weigh, $twin, $fed, $support, $underweight, $exta_care, $bnum1, $v1, $dg1, $dn1, $cm1,$bnum2, $v2, $dg2, $dn2, $cm2,
		$bnum3, $v3, $dg3, $dn3, $cm3,$bnum4, $v4, $dg4, $dn4, $cm4, $bnum5, $v5, $dg5, $dn5, $cm5, $bnum6, $v6, $dg6, $dn6, $cm6, $bnum7, $v7, $dg7, $dn7, $cm7,
		$bnum8, $v8, $dg8, $dn8, $cm8, $bnum9, $v9, $dg9, $dn9, $cm9, $bnum10, $v10, $dg10, $dn10, $cm10, $bnum11, $v11, $dg11, $dn11, $cm11,
		$bnum12, $v12, $dg12, $dn12, $cm12, $bnum13, $v13, $dg13, $dn13, $cm13, $bnum15, $v15, $dg15, $dn15, $cm15, $bnum16, $v16, $dg16, $dn16, $cm16,
		$bnum17, $v17, $dg17, $dn17, $cm17, $bnum18, $v18, $dg18, $dn18, $cm18, $bnum19, $v19, $dg19, $dn19, $cm19, $bnum20, $v20, $dg20, $dn20, $cm20,
		$bnum21, $v21, $dg21, $dn21, $cm21,$DataArr2){
		try {
		$stmt = $this->db->prepare("INSERT INTO antenatal(name, pos, sex, dob, house_num, town, village, ward, state, lga, mother_name, mother_phone,
		father_name, father_phone, cg, cg_phone,
		weight, twin, fed, support, underweight, extra_care, bnum1, v1, dg1, dn1, cm1,bnum2, v2, dg2, dn2, cm2,
		bnum3, v3, dg3, dn3, cm3,bnum4, v4, dg4, dn4, cm4, bnum5, v5, dg5, dn5, cm5, bnum6, v6, dg6, dn6, cm6, bnum7, v7, dg7, dn7, cm7,
		bnum8, v8, dg8, dn8, cm8, bnum9, v9, dg9, dn9, cm9, bnum10, v10, dg10, dn10, cm10, bnum11, v11, dg11, dn11, cm11,
		bnum12, v12, dg12, dn12, cm12, bnum13, v13, dg13, dn13, cm13, bnum15, v15, dg15, dn15, cm15, bnum16, v16, dg16, dn16, cm16,
		bnum17, v17, dg17, dn17, cm17, bnum18, v18, dg18, dn18, cm18, bnum19, v19, dg19, dn19, cm19, bnum20, v20, dg20, dn20, cm20,
		bnum21, v21, dg21, dn21, cm21) 
		
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
		?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$name, $pos, $sex, $dob, $house_num, $town, $village, $ward, $state, $lga, $mother_name,$mother_phone,$father_name, $father_phone, $cg, $cg_phone,
		$weigh, $twin, $fed, $support, $underweight, $exta_care, $bnum1, $v1, $dg1, $dn1, $cm1,$bnum2, $v2, $dg2, $dn2, $cm2,
		$bnum3, $v3, $dg3, $dn3, $cm3,$bnum4, $v4, $dg4, $dn4, $cm4, $bnum5, $v5, $dg5, $dn5, $cm5, $bnum6, $v6, $dg6, $dn6, $cm6, $bnum7, $v7, $dg7, $dn7, $cm7,
		$bnum8, $v8, $dg8, $dn8, $cm8, $bnum9, $v9, $dg9, $dn9, $cm9, $bnum10, $v10, $dg10, $dn10, $cm10, $bnum11, $v11, $dg11, $dn11, $cm11,
		$bnum12, $v12, $dg12, $dn12, $cm12, $bnum13, $v13, $dg13, $dn13, $cm13, $bnum15, $v15, $dg15, $dn15, $cm15, $bnum16, $v16, $dg16, $dn16, $cm16,
		$bnum17, $v17, $dg17, $dn17, $cm17, $bnum18, $v18, $dg18, $dn18, $cm18, $bnum19, $v19, $dg19, $dn19, $cm19, $bnum20, $v20, $dg20, $dn20, $cm20,
		$bnum21, $v21, $dg21, $dn21, $cm21]);
		
		$stmt = null;
		
		$last_id = $this->db->lastInsertId();
		$data_count = count($DataArr);
		for ($i=0; $i<$data_count; $i++) {
			$c_year = htmlspecialchars(ucfirst($_POST['c_year'][$i]));
			$c_year = stripslashes(ucfirst($_POST['c_year'][$i]));
			$c_year = trim(ucfirst($_POST['c_year'][$i]));
					
			$c_health = htmlspecialchars($_POST['c_health'][$i]);
			$c_health = stripslashes($_POST['c_health'][$i]);
			$c_health = trim($_POST['c_health'][$i]);
					
			$c_sex = htmlspecialchars($_POST['c_sex'][$i]);
			$c_sex = stripslashes($_POST['c_sex'][$i]);
			$c_sex = trim($_POST['c_sex'][$i]);
			
			$stmt = $this->db->prepare("INSERT INTO ante_other_children(c_year, c_health, c_sex, ante_id) 
			VALUES (?,?,?,?)");
					
			$stmt->execute(array($c_year, $c_health, $c_sex, $last_id));					
			$stmt = null;
		}
		
		$data_count = count($DataArr2);
		for ($i=0; $i<$data_count; $i++) {
			$d_year = htmlspecialchars(ucfirst($_POST['d_year'][$i]));
			$d_year = stripslashes(ucfirst($_POST['d_year'][$i]));
			$d_year = trim(ucfirst($_POST['d_year'][$i]));
					
			$complaint = htmlspecialchars($_POST['complaint'][$i]);
			$complaint = stripslashes($_POST['complaint'][$i]);
			$complaint = trim($_POST['complaint'][$i]);
					
			$types = htmlspecialchars($_POST['types'][$i]);
			$types = stripslashes($_POST['types'][$i]);
			$types = trim($_POST['types'][$i]);
			
			$manag = htmlspecialchars($_POST['manag'][$i]);
			$manag = stripslashes($_POST['manag'][$i]);
			$manag = trim($_POST['manag'][$i]);
			
			$stmt = $this->db->prepare("INSERT INTO extra_effects(d_year, complaint, types, manag, ante_id) 
			VALUES (?,?,?,?,?)");
					
			$stmt->execute(array($d_year, $complaint, $types, $manag, $last_id));					
			$stmt = null;
		}
			
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function edit_ante($name, $pos, $sex, $dob, $house_num, $town, $village, $ward, $state, $lga, $mother_name,$mother_phone,$father_name, $father_phone, $cg, $cg_phone,
		$DataArr, $weigh, $twin, $fed, $support, $underweight, $exta_care, $bnum1, $v1, $dg1, $dn1, $cm1,$bnum2, $v2, $dg2, $dn2, $cm2,
		$bnum3, $v3, $dg3, $dn3, $cm3,$bnum4, $v4, $dg4, $dn4, $cm4, $bnum5, $v5, $dg5, $dn5, $cm5, $bnum6, $v6, $dg6, $dn6, $cm6, $bnum7, $v7, $dg7, $dn7, $cm7,
		$bnum8, $v8, $dg8, $dn8, $cm8, $bnum9, $v9, $dg9, $dn9, $cm9, $bnum10, $v10, $dg10, $dn10, $cm10, $bnum11, $v11, $dg11, $dn11, $cm11,
		$bnum12, $v12, $dg12, $dn12, $cm12, $bnum13, $v13, $dg13, $dn13, $cm13, $bnum15, $v15, $dg15, $dn15, $cm15, $bnum16, $v16, $dg16, $dn16, $cm16,
		$bnum17, $v17, $dg17, $dn17, $cm17, $bnum18, $v18, $dg18, $dn18, $cm18, $bnum19, $v19, $dg19, $dn19, $cm19, $bnum20, $v20, $dg20, $dn20, $cm20,
		$bnum21, $v21, $dg21, $dn21, $cm21,$DataArr2, $val){
		
		try {
		$stmt = $this->db->prepare("UPDATE antenatal SET name= ?, pos= ?, sex= ?, dob= ?, house_num= ?, town= ?, village= ?, ward= ?, 
		state= ?, lga= ?, mother_name= ?, mother_phone= ?,father_name= ?, father_phone= ?, cg= ?, cg_phone= ?,
		weight= ?, twin= ?, fed= ?, support= ?, underweight= ?, extra_care= ?, bnum1= ?, v1= ?, dg1= ?, dn1= ?, cm1= ?,bnum2= ?, v2= ?, dg2= ?, dn2= ?, cm2= ?,
		bnum3= ?, v3= ?, dg3= ?, dn3= ?, cm3= ?,bnum4= ?, v4= ?, dg4= ?, dn4= ?, cm4= ?, bnum5= ?, v5= ?, dg5= ?, dn5= ?, cm5= ?, bnum6= ?, v6= ?, 
		dg6= ?, dn6= ?, cm6= ?, bnum7= ?, v7= ?, dg7= ?, dn7= ?, cm7= ?,
		bnum8= ?, v8= ?, dg8= ?, dn8= ?, cm8= ?, bnum9= ?, v9= ?, dg9= ?, dn9= ?, cm9= ?, bnum10= ?, v10= ?, dg10= ?, dn10= ?, cm10= ?, 
		bnum11= ?, v11= ?, dg11= ?, dn11= ?, cm11= ?,
		bnum12= ?, v12= ?, dg12= ?, dn12= ?, cm12= ?, bnum13= ?, v13= ?, dg13= ?, dn13= ?, cm13= ?, bnum15= ?, v15= ?, dg15= ?, dn15= ?, cm15= ?, 
		bnum16= ?, v16= ?, dg16= ?, dn16= ?, cm16= ?,
		bnum17= ?, v17= ?, dg17= ?, dn17= ?, cm17= ?, bnum18= ?, v18= ?, dg18= ?, dn18= ?, cm18= ?, bnum19= ?, v19= ?, dg19= ?, dn19= ?, 
		cm19= ?, bnum20= ?, v20= ?, dg20= ?, dn20= ?, cm20= ?,
		bnum21= ?, v21= ?, dg21= ?, dn21= ?, cm21= ?");
		$stmt->execute([$name, $pos, $sex, $dob, $house_num, $town, $village, $ward, $state, $lga, $mother_name,$mother_phone,$father_name, $father_phone, $cg, $cg_phone,
		$weigh, $twin, $fed, $support, $underweight, $exta_care, $bnum1, $v1, $dg1, $dn1, $cm1,$bnum2, $v2, $dg2, $dn2, $cm2,
		$bnum3, $v3, $dg3, $dn3, $cm3,$bnum4, $v4, $dg4, $dn4, $cm4, $bnum5, $v5, $dg5, $dn5, $cm5, $bnum6, $v6, $dg6, $dn6, $cm6, $bnum7, $v7, $dg7, $dn7, $cm7,
		$bnum8, $v8, $dg8, $dn8, $cm8, $bnum9, $v9, $dg9, $dn9, $cm9, $bnum10, $v10, $dg10, $dn10, $cm10, $bnum11, $v11, $dg11, $dn11, $cm11,
		$bnum12, $v12, $dg12, $dn12, $cm12, $bnum13, $v13, $dg13, $dn13, $cm13, $bnum15, $v15, $dg15, $dn15, $cm15, $bnum16, $v16, $dg16, $dn16, $cm16,
		$bnum17, $v17, $dg17, $dn17, $cm17, $bnum18, $v18, $dg18, $dn18, $cm18, $bnum19, $v19, $dg19, $dn19, $cm19, $bnum20, $v20, $dg20, $dn20, $cm20,
		$bnum21, $v21, $dg21, $dn21, $cm21]);
		
		$stmt = null;
		
		$last_id = $this->db->lastInsertId();
		$data_count = count($DataArr);
		for ($i=0; $i<$data_count; $i++) {
			$c_year = htmlspecialchars(ucfirst($_POST['c_year'][$i]));
			$c_year = stripslashes(ucfirst($_POST['c_year'][$i]));
			$c_year = trim(ucfirst($_POST['c_year'][$i]));
					
			$c_health = htmlspecialchars($_POST['c_health'][$i]);
			$c_health = stripslashes($_POST['c_health'][$i]);
			$c_health = trim($_POST['c_health'][$i]);
					
			$c_sex = htmlspecialchars($_POST['c_sex'][$i]);
			$c_sex = stripslashes($_POST['c_sex'][$i]);
			$c_sex = trim($_POST['c_sex'][$i]);
			
			$stmt = $this->db->prepare("UPDATE ante_other_children(c_year= ?, c_health= ?, c_sex= ?, ante_id= ?) 
			VALUES (?,?,?,?)");
					
			$stmt->execute(array($c_year, $c_health, $c_sex, $last_id));					
			$stmt = null;
		}
		
		$data_count = count($DataArr2);
		for ($i=0; $i<$data_count; $i++) {
			$d_year = htmlspecialchars(ucfirst($_POST['d_year'][$i]));
			$d_year = stripslashes(ucfirst($_POST['d_year'][$i]));
			$d_year = trim(ucfirst($_POST['d_year'][$i]));
					
			$complaint = htmlspecialchars($_POST['complaint'][$i]);
			$complaint = stripslashes($_POST['complaint'][$i]);
			$complaint = trim($_POST['complaint'][$i]);
					
			$types = htmlspecialchars($_POST['types'][$i]);
			$types = stripslashes($_POST['types'][$i]);
			$types = trim($_POST['types'][$i]);
			
			$manag = htmlspecialchars($_POST['manag'][$i]);
			$manag = stripslashes($_POST['manag'][$i]);
			$manag = trim($_POST['manag'][$i]);
			
			$stmt = $this->db->prepare("UPDATE extra_effects(d_year= ?, complaint= ?, types, manag= ?, ante_id= ?) 
			VALUES (?,?,?,?,?)");
					
			$stmt->execute(array($d_year, $complaint, $types, $manag, $last_id));					
			$stmt = null;
		}
			
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function insert_expi($date_a, $code, $description, $approver, $recipient, $qty, $amt, $cash, $comment){
		try {
		$stmt = $this->db->prepare("INSERT INTO daily_expense(exp_date, code, description, approver, recipient, qty, amt, cash_bank, comment) 
		VALUES (?,?,?,?,?,?,?,?,?)");
		$stmt->execute([$date_a, $code, $description, $approver, $recipient, $qty, $amt, $cash, $comment]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_expi($date_a, $code, $description, $approver, $recipient, $qty, $amount, $cash, $comment, $val){
		try {
			$stmt = $this->db->prepare("UPDATE daily_expense SET exp_date=?, code=?, description=?, approver=?, recipient=?, qty=?, amt=?, cash_bank=?, comment=? WHERE id = ?");
			$stmt->execute([$date_a, $code, $description, $approver, $recipient, $qty, $amt, $cash, $comment, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	
	public function insert_c_bal($c_date, $description, $amt, $cash, $comment, $type){
		try {
		$stmt = $this->db->prepare("INSERT INTO credit_balance(c_date, particulars, amount, cash_bank, comment, bal_type) 
		VALUES (?,?,?,?,?,?)");
		$stmt->execute([$c_date, $description, $amt, $cash, $comment, $type]);
		
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_c_bal($c_date, $description, $amt, $cash, $comment, $type, $val){
		try {
			$stmt = $this->db->prepare("UPDATE credit_balance SET c_date=?, particulars=?, amount=?, cash_bank=?, comment=?, bal_type=? WHERE id = ?");
			$stmt->execute([$c_date, $description, $amt, $cash, $comment, $type, $val]);
			$stmt = null;
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}

	public function month_date($from, $to, $val){		
		try{
			$stmt = $this->db->prepare("SELECT * FROM accounts WHERE date_added >= ? AND date_added < ? AND card_type = ?");
			$stmt->execute([$from, $to, $val]);
			$arr = $stmt->fetchAll();
			return $arr;
			$stmt = null;
		} catch (PDOException $e) {
			// For handling error
			
			echo 'Error: ' . $e->getMessage();			
		}
	}	
	public function insert_lab_temp($DataArr,$name){
		try {
		$stmt = $this->db->prepare("INSERT INTO lab_temp_name(name) 
		VALUES (?)");
		$stmt->execute([$name]);
		$last_id = $this->db->lastInsertId();
		$stmt = null;
		
		$data_count = count($DataArr);
		for ($i=0; $i<$data_count; $i++) {
			
			$field = htmlspecialchars(lcfirst($_POST['fieldss'][$i]));
			$field = stripslashes(lcfirst($_POST['fieldss'][$i]));
			$field = trim(lcfirst($_POST['fieldss'][$i]));
			
			$field = str_replace(' ', '_', $field); // Replaces all spaces with hyphens.
   
			$field = preg_replace('/[^A-Za-z0-9\-]/', '_', $field); // Removes special chars.

			
			$stmt2 = $this->db->prepare("INSERT INTO lab_temps(temp_name, label_id) 
				VALUES (?,?)");
					
			$stmt2->execute(array($field, $last_id));					
			
		}
		$stmt2 = null;	
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function get_fields($temp){
		try {
			$que3= $this->db->prepare("SELECT name FROM lab_temp_name WHERE id = ?");
			$que3->execute([$temp]);
			$row = $que3->fetchAll();
			foreach ($row as $title):
			$que3 = null;
			$titlee = $title['name'];
			$title = str_replace('_', ' ', $titlee);?>
			<h3><?php ucwords($title);?></h3>
			
			<?php
			$stmt= $this->db->prepare("SELECT * FROM lab_temps WHERE label_id = ?");
			$stmt->execute([$temp]);
			$row=$stmt->fetchAll();
			
			
			foreach ($row as $dets) { 
    			$name = $dets['temp_name'];
    			$name = str_replace('_', ' ', $name);
				$name = ucwords($name);
    			?>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label><?php echo $name;?></label>
									<input type="text" class="form-control" name="<?php echo strtolower($name);?>" placeholder="<?php echo $name;?>" >
								</div>
							</div>
						</div>
					</div>
		
		<?php	} 
			endforeach;	
		} catch (PDOException $e) {
			echo 'Error: ' . $e->getMessage();			
		}
	}
		
		
	public function change_admi_status($status, $app_id){		
		try {
			$stmt = $this->db->prepare("UPDATE admission_request SET status = ? WHERE appointment_id = ?");
			$stmt->execute([$status,$app_id]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	
		public function change_prescription_status($status, $pre_id){		
		try {
			$stmt = $this->db->prepare("UPDATE prescription SET prescription_status = ? WHERE prescription_id = ?");
			$stmt->execute([$status, $pre_id]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	
	
	
	
	public function insert_exam_request($val,$doc, $p_id,$ward){
		try {
		$que= $this->db->prepare("SELECT front_desk FROM patients WHERE id = ?");
		$que->execute([$p_id]);
		$row = $que->fetch(PDO::FETCH_OBJ);
		$front2 = $row->front_desk;
		
		$que = null;
		$stmt = $this->db->prepare("INSERT INTO exam_request(front_desk,appointment_id,doctor_id, patient_id,ward_id) 
		VALUES (?,?,?,?,?)");
		$stmt->execute([$front2,$val,$doc, $p_id,$ward]);
		$stmt = null;
		return "Done";
		
		} catch (PDOException $e) {
			// For handling error
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function change_exam_status($status, $app_id){		
		try {
			$stmt = $this->db->prepare("UPDATE exam_request SET status = ? WHERE appointment_id = ?");
			$stmt->execute([$status,$app_id]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function change_staff_status($status, $staff_id){		
		try {
			$stmt = $this->db->prepare("UPDATE staff SET status = ? WHERE user_id = ?");
			$stmt->execute([$status,$staff_id]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function get_fields_edit($t_id){
		try {
	
			$stmt= $this->db->prepare("SELECT * FROM lab_temps WHERE label_id = ?");
			$stmt->execute([$t_id]);
			$row=$stmt->fetchAll();
			
			
			foreach ($row as $dets) { 
    			$name = $dets['temp_name'];
    			$tn_id = $dets['id'];
    			$name = str_replace('_', ' ', $name);
				$name = ucwords($name);
    			?>
					<div class="col-md-12">
						<form id="<?php echo $tn_id;?>">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										
										<p id="<?php echo $tn_id;?>"> <?php echo $name;?></p>
										<a href="edit_temp_choose?id=<?php echo $tn_id; ?>" style="margin-bottom:10px; background:#1eb902 ! important; border-color:#1eb902  !important;" class="btn btn-primary pull-left btn-flat btblack" id="addre">Edit</a>
										<a onclick="delf(<?php echo $tn_id; ?>,'<?php echo $name; ?>')" style="margin-bottom:10px;" class="btn btn-primary pull-left btn-flat btblack">Delete</a>
									</div>
								</div>
							</div>
									
						</form>
					</div>
					<script type="text/javascript">
						var s=jQuery .noConflict();
						function delf(ID,name){ 
							s.notify({
								icon: 'pe-7s-trash',
								message: "Are you sure you want to delete <b>"+name+"</b> from templates ? </br><button type='button' class='btn pop-btn' onclick='delet("+ID+")'>Delete</button>"
							},{
								type: 'danger',
								timer: 100000
							});
						}
						
						function delet(ID){ 
							var val = ID;
							document.getElementById("load").style.display = "block";
							s.ajax({
								type: 'post',
								url: '../func/del.php',
								data: "val=" + val +  '&ins=delTempp',
								success: function(data){
									document.getElementById("load").style.display = "block";
									if (data === 'Done') {
										console.log(data);
										location.reload();
									} else {
										jQuery('#get_det'+ID).html(data).show();
									}
								}
							});
						}
					</script>
		<?php	} 
				
		} catch (PDOException $e) {
			echo 'Error: ' . $e->getMessage();			
		}
	}
	
	public function edit_tempy($name, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE lab_temps SET temp_name = ? WHERE id = ?");
			$stmt->execute([$name, $val]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
	
	public function add_fields($val, $DataArr){
		$data_count = count($DataArr);
		for ($i=0; $i<$data_count; $i++) {
			$fieldsst = htmlspecialchars(ucfirst($_POST['fieldsst'][$i]));
			$fieldsst = stripslashes(ucfirst($_POST['fieldsst'][$i]));
			$fieldsst = trim(ucfirst($_POST['fieldsst'][$i]));
			
			$stmt = $this->db->prepare("INSERT INTO lab_temps(temp_name, label_id) 
			VALUES (?,?)");
					
			$stmt->execute(array($fieldsst, $val));	
			return "Done";			
			$stmt = null;
		}
	}
	
	public function edit_tempa($temp_name, $val){		
		try {
			$stmt = $this->db->prepare("UPDATE lab_temp_name SET name = ? WHERE id = ?");
			$stmt->execute([$temp_name, $val]);
			$stmt = null;
			
			$success = 'Done';
			return $success;
		} catch (PDOException $e) {
			// For handling error
			echo '<div class="alert alert-danger">
					 Status could not be updated
				  </div>: ' . $e->getMessage();
		}
	}
}	