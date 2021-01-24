<?php
	include('../inc/db.php');
	require_once('../inc/formvalidator.php');

	$functionto = $_POST['ins'];

	switch ($functionto) {
		case "changePass":
		   changePass();
			break;

		case 'pushScores':
			pushScores();
			break;

		case "newUser":
		   newUser();
			break;
		case "login":
		   login();
			break;
		case "verify":
			verify();
			break;

		case 'newRemark':
			newRemark();
			break;
		case 'acceptResult':
			acceptResult();
			break;

		case "newBulletin":
		   newBulletin();
			break;

		case "newCourse":
			newCourse();
			break;
		case 'updateTranscript':
			updateTranscript();
			break;
		
		default:
			echo '<div class="alert alert-danger">
					Function does not Exist
				  </div>';
	}

	
	function newUser(){
		$first = ucfirst(htmlspecialchars($_POST['f_name']));
		$first = ucfirst(stripslashes($_POST['f_name']));
		$first = ucfirst(trim($_POST['f_name']));
		
		$last = ucfirst(htmlspecialchars($_POST['l_name']));
		$last = ucfirst(stripslashes($_POST['l_name']));
		$last = ucfirst(trim($_POST['l_name']));
		
		$username = lcfirst(htmlspecialchars($_POST['username']));
		$username = lcfirst(stripslashes($_POST['username']));
		$username = lcfirst(trim($_POST['username']));
		
		$position = lcfirst(htmlspecialchars($_POST['position']));
		$position = lcfirst(stripslashes($_POST['position']));
		$position = lcfirst(trim($_POST['position']));
		
		$role = lcfirst(htmlspecialchars($_POST['role']));
		$role = lcfirst(stripslashes($_POST['role']));
		$role = lcfirst(trim($_POST['role']));

		$ward = lcfirst(htmlspecialchars($_POST['ward']));
		$ward = lcfirst(stripslashes($_POST['ward']));
		$ward = lcfirst(trim($_POST['ward']));
		
		$mypassword = htmlspecialchars($_POST['password']);
		$mypassword = stripslashes($_POST['password']);
		$mypassword = trim($_POST['password']);
		
		$cpassword = htmlspecialchars($_POST['cpass']);
		$cpassword = stripslashes($_POST['cpass']);
		$cpassword = trim($_POST['cpass']);
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("f_name","req","Please fill in first name");
		$validator->addValidation("l_name","req","Please fill in last name");
		$validator->addValidation("position","req","Please fill in position");
									
		if($validator->ValidateForm()){
			if (!preg_match("/^[A-Z][a-zA-Z -]+$/",$first)) {
				$error = 'First Name must contain only letters.';
			}
										
			if (!preg_match("/^[A-Z][a-zA-Z -]+$/",$last)) {
				$error ='Last Name must contain only letters.';
			}
			
			if (EMPTY($first)) {
				$error ='First name cannot be empty';
			}

			if (EMPTY($last)) {
				$error ='Last name cannot be empty';
			}
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$hash = password_hash($mypassword, PASSWORD_DEFAULT);
				$insert = Database::getInstance()->insert_user($first, $last, $username, $role, $hash, $position,$ward);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
						User Added Successfully					
					</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function login(){
		$uperror = '';
		
		$username = lcfirst(htmlspecialchars($_POST['username']));
		$username = lcfirst(stripslashes($_POST['username']));
		$username = lcfirst(trim($_POST['username']));
		
		$password = htmlspecialchars($_POST['password']);
		$password = stripslashes($_POST['password']);
		$password = trim($_POST['password']);
		
		//$validator = new FormValidator();
						
		//$validator->addValidation("username","req","Please fill in your username");
		//$validator->addValidation("password","req","Please fill in Your password");
									
		//if($validator->ValidateForm()){
		
			if (empty($password)) {
				$uperror = '<div class="alert alert-danger">
					<strong>Warning!</strong> Please fill in password.
				</div>';
			}
			if (empty($username)) {
				$uperror = '<div class="alert alert-danger">
					<strong>Warning!</strong> Please fill in username.
				</div>';
			}
			
			if (!empty($username) && !empty($password)) {
				//$hash = password_hash($password, PASSWORD_DEFAULT);
				Database::getInstance()->check_pass($username, $password);
			}
		
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// } 			
	}

	function updateTranscript(){
		$uperror = '';
		
		$score = $_POST['score'];
		$id = $_POST['id'];
		$matNumb = $_POST['matNo'];
		
		//$validator = new FormValidator();
						
		//$validator->addValidation("username","req","Please fill in your username");
		//$validator->addValidation("password","req","Please fill in Your password");
									
		//if($validator->ValidateForm()){
		
			if (empty($matNo)) {
				$uperror = '<div class="alert alert-danger">
					<strong>Warning!</strong> Please logout and login.
				</div>';
			}
			
			if (count($score) != 0 && count($id) != 0) {
				Database::getInstance()->updateTranscript($id, $score,$matNumb);
			}
		
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// } 			
	}

	function verify(){
		$uperror = '';
		
		$matNumb = lcfirst(htmlspecialchars($_POST['matNumber']));
		$matNumb = lcfirst(stripslashes($_POST['matNumber']));
		$matNumb = lcfirst(trim($_POST['matNumber']));

			if (empty($matNumb)) {
				$uperror = '<div class="alert alert-danger">
					<strong>Warning!</strong> Please fill in Matric Number.
				</div>';
			}else{
				Database::getInstance()->verifyMatNumb($matNumb);
			}			
	}

	function pushScores()
	{
		$uperror = '';
		$matNumb = $_POST['matNumber'];

			if (empty($matNumb)) {
				$uperror = '<div class="alert alert-danger">
					<strong>Warning!</strong> Please fill in Matric Number.
				</div>';
			}else{
				echo Database::getInstance()->pushScores($matNumb);
			}
	}

	function newBulletin(){
		$dept = ucfirst(htmlspecialchars($_POST['dept']));
		$dept = ucfirst(stripslashes($_POST['dept']));
		$dept = ucfirst(trim(ucfirst($_POST['dept'])));

		$syear = ucfirst(htmlspecialchars($_POST['syear']));
		$syear = ucfirst(stripslashes($_POST['syear']));
		$syear = ucfirst(trim(ucfirst($_POST['syear'])));

		$gyear = ucfirst(htmlspecialchars($_POST['gyear']));
		$gyear = ucfirst(stripslashes($_POST['gyear']));
		$gyear = ucfirst(trim(ucfirst($_POST['gyear'])));

		$req = ucfirst(htmlspecialchars($_POST['req']));
		$req = ucfirst(stripslashes($_POST['req']));
		$req = ucfirst(trim(ucfirst($_POST['req'])));

		$user = ucfirst(htmlspecialchars($_POST['user']));
		$user = ucfirst(stripslashes($_POST['user']));
		$user = ucfirst(trim(ucfirst($_POST['user'])));

		$status = ucfirst(htmlspecialchars($_POST['status']));
		$status = ucfirst(stripslashes($_POST['status']));
		$status = ucfirst(trim(ucfirst($_POST['status'])));
		
		$error = '';
		// $validator = new FormValidator();
						
		// $validator->addValidation("req","req","Please ENTER graduation Requirements");
		// $validator->addValidation("dept","req","Please SELECT Department");
		// $validator->addValidation("syear","req","Please SELECT Entry Year");
		// $validator->addValidation("gyear","req","Please SELECT graduation year");
		// $validator->addValidation("user","req","Please LOGOUT and login Again");
									
		// if($validator->ValidateForm()){
			if (EMPTY($dept)) {
				$error ='Department cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_bulletin($dept,$syear,$gyear,$req,$user,$status);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
					<button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
					  <i class="fas fa-times"></i>
					</button>
					<span>Bulletin Added Successfully</span>
				  </div>';
				} else {
					echo $insert;
				}
			}
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// }
	}

	function newRemark(){
		$reason = ucfirst(htmlspecialchars($_POST['reason']));
		$reason = ucfirst(stripslashes($_POST['reason']));
		$reason = ucfirst(trim(ucfirst($_POST['reason'])));

		$matNumber = $_POST['matNumber'];
		$val = $_POST['val'];
		$user = $_POST['user'];
		
		$error = '';
		// $validator = new FormValidator();
						
		// $validator->addValidation("req","req","Please ENTER graduation Requirements");
		// $validator->addValidation("reason","req","Please SELECT Department");
		// $validator->addValidation("syear","req","Please SELECT Entry Year");
		// $validator->addValidation("gyear","req","Please SELECT graduation year");
		// $validator->addValidation("user","req","Please LOGOUT and login Again");
									
		// if($validator->ValidateForm()){
			if (EMPTY($reason)) {
				$error ='Reason cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_remark($reason,$matNumber,$user,$val);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
					<button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
					  <i class="fas fa-times"></i>
					</button>
					<span>Remark Sent Successfully</span>
				  </div>';
				} else {
					echo $insert;
				}
			}
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// }
	}
	function acceptResult(){

		$matNumber = $_POST['matNumber'];
		
		$error = '';
		// $validator = new FormValidator();
						
		// $validator->addValidation("req","req","Please ENTER graduation Requirements");
		// $validator->addValidation("reason","req","Please SELECT Department");
		// $validator->addValidation("syear","req","Please SELECT Entry Year");
		// $validator->addValidation("gyear","req","Please SELECT graduation year");
		// $validator->addValidation("user","req","Please LOGOUT and login Again");
									
		// if($validator->ValidateForm()){
			if (EMPTY($matNumber)) {
				$error ='Mat Number cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->acceptResult($matNumber);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
					<button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
					  <i class="fas fa-times"></i>
					</button>
					<span>Accepted Successfully</span>
				  </div>';
				} else {
					echo $insert;
				}
			}
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// }
	}

	function newCourse(){
		$dept = ucfirst(htmlspecialchars($_POST['dept']));
		$dept = ucfirst(stripslashes($_POST['dept']));
		$dept = ucfirst(trim(ucfirst($_POST['dept'])));

		$bulletin = ucfirst(htmlspecialchars($_POST['bulletin']));
		$bulletin = ucfirst(stripslashes($_POST['bulletin']));
		$bulletin = ucfirst(trim(ucfirst($_POST['bulletin'])));

		$level = ucfirst(htmlspecialchars($_POST['level']));
		$level = ucfirst(stripslashes($_POST['level']));
		$level = ucfirst(trim(ucfirst($_POST['level'])));

		$semester = ucfirst(htmlspecialchars($_POST['semester']));
		$semester = ucfirst(stripslashes($_POST['semester']));
		$semester = ucfirst(trim(ucfirst($_POST['semester'])));

		$ctype = ucfirst(htmlspecialchars($_POST['ctype']));
		$ctype = ucfirst(stripslashes($_POST['ctype']));
		$ctype = ucfirst(trim(ucfirst($_POST['ctype'])));

		$code = ucfirst(htmlspecialchars($_POST['code']));
		$code = ucfirst(stripslashes($_POST['code']));
		$code = ucfirst(trim(ucfirst($_POST['code'])));

		$title = ucfirst(htmlspecialchars($_POST['title']));
		$title = ucfirst(stripslashes($_POST['title']));
		$title = ucfirst(trim(ucfirst($_POST['title'])));

		$unit = ucfirst(htmlspecialchars($_POST['unit']));
		$unit = ucfirst(stripslashes($_POST['unit']));
		$unit = ucfirst(trim(ucfirst($_POST['unit'])));

		$description = ucfirst(htmlspecialchars($_POST['description']));
		$description = ucfirst(stripslashes($_POST['description']));
		$description = ucfirst(trim(ucfirst($_POST['description'])));

		$lect = ucfirst(htmlspecialchars($_POST['lect']));
		$lect = ucfirst(stripslashes($_POST['lect']));
		$lect = ucfirst(trim(ucfirst($_POST['lect'])));
		
		$error = '';
		// $validator = new FormValidator();
						
		// $validator->addValidation("semester","req","Please ENTER graduation Requirements");
		// $validator->addValidation("dept","req","Please SELECT Department");
		// $validator->addValidation("bulletin","req","Please SELECT Entry Year");
		// $validator->addValidation("level","req","Please SELECT graduation year");
		// $validator->addValidation("ctype","req","Please LOGOUT and login Again");
									
		// if($validator->ValidateForm()){
			if (EMPTY($dept)) {
				$error ='Department cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_course($dept,$bulletin,$level,$semester,$code,$title,$ctype,$unit,$description,$lect);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
					<button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
					  <i class="fas fa-times"></i>
					</button>
					<span>Course was Added Successfully</span>
				  </div>';
				} else {
					echo $insert;
				}
			}
		// } else {
		// 	$error_hash = $validator->GetErrors();
		// 	foreach($error_hash as $inpname => $inp_err){
		// 		echo '<div class="alert alert-danger">
		// 			<strong>Warning!</strong> ' . $inp_err . '
		// 		</div>';
		// 	}
		// }
	}
	
	
	
	function newDoc(){
		$name = ucfirst(htmlspecialchars($_POST['name']));
		$name = ucfirst(stripslashes($_POST['name']));
		$name = ucfirst(trim($_POST['name']));
		
		$phone = ucfirst(htmlspecialchars($_POST['phone']));
		$phone = ucfirst(stripslashes($_POST['phone']));
		$phone = ucfirst(trim($_POST['phone']));
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("name","req","Please fill in name");
		$validator->addValidation("phone","req","Please fill in phone");
									
		if($validator->ValidateForm()){
			
										
			if (strlen($phone) < 11) {
				$error ='Phone number must be 11 characters';
			}
			
			if (EMPTY($name)) {
				$error ='Name cannot be empty';
			}
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_doc($name, $phone);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Doctor Added Successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function newSchedule(){
		$doctor = ucfirst(htmlspecialchars($_POST['doctor']));
		$doctor = ucfirst(stripslashes($_POST['doctor']));
		$doctor = ucfirst(trim($_POST['doctor']));
		
		$dayofweek = ucfirst(htmlspecialchars($_POST['dayofweek']));
		$dayofweek = ucfirst(stripslashes($_POST['dayofweek']));
		$dayofweek = ucfirst(trim($_POST['dayofweek']));

		$timein = ucfirst(htmlspecialchars($_POST['timein']));
		$timein = ucfirst(stripslashes($_POST['timein']));
		$timein = ucfirst(trim($_POST['timein']));

		$timeout = ucfirst(htmlspecialchars($_POST['timeout']));
		$timeout = ucfirst(stripslashes($_POST['timeout']));
		$timeout = ucfirst(trim($_POST['timeout']));
		
		$dateday = ucfirst(htmlspecialchars($_POST['dateday']));
		$dateday = ucfirst(stripslashes($_POST['dateday']));
		$dateday = ucfirst(trim($_POST['dateday']));

		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("doctor","req","Please choose doctor");
		$validator->addValidation("dayofweek","req","Please choose day of week");
		$validator->addValidation("timein","req","Please enter time in");
		$validator->addValidation("timeout","req","Please enter time out");
		$validator->addValidation("dateday","req","Please enter date");
									
		if($validator->ValidateForm()){
			if (EMPTY($dayofweek)) {
				$error ='Day of week cannot be empty';
			}

			if (EMPTY($timein)) {
				$error ='Time in cannot be empty';
			}

			if (EMPTY($timeout)) {
				$error ='Time out cannot be empty';
			}

			if (EMPTY($dateday)) {
				$error ='Date cannot be empty';
			}
			
			if (EMPTY($doctor)) {
				$error ='Doctor cannot be empty';
			}
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_schedule($doctor, $dayofweek, $timein, $timeout, $dateday);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Schedule Successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function newAppointment(){
		$doctor = ucfirst(htmlspecialchars($_POST['doctor']));
		$doctor = ucfirst(stripslashes($_POST['doctor']));
		$doctor = ucfirst(trim($_POST['doctor']));
		

		$fee = ucfirst(htmlspecialchars($_POST['consult']));
		$fee = ucfirst(stripslashes($_POST['consult']));
		$fee = ucfirst(trim($_POST['consult']));
		$error = '';

		$p_id = $_POST['p_id'];
		$app = $_POST['app'];
		$validator = new FormValidator();
						
		$validator->addValidation("doctor","req","Please choose doctor");
		$validator->addValidation("consult","req","Please Enter A Consultation Fee");
		
									
		if($validator->ValidateForm()){
			
			if (EMPTY($doctor)) {
				$error ='Doctor cannot be empty';
			}
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>'; 
			}else{
					$insert = Database::getInstance()->insert_app($doctor, $p_id,$fee);
				$notify = Database::getInstance()->notify($doctor,$p_id,$app);
				//$acc = Database::getInstance()->notify_account($p_id);
				if($insert == 'Done' AND $notify == 'Done'){
						echo '<div class="alert alert-success">
							Appointment Successfully	 				
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function extraFile(){
	
				if(!isset($_FILES['file'])){
							echo '<div class="alert alert-warning">
									Please Choose a File
								  </div>';
				}else{
								$patient = $_POST['patient'];
								$name = $_POST['namea'];
								$val = $_POST['val'];
								$uploaderror ='';
								
								$validator = new FormValidator();
						
								$validator->addValidation("patient","req","Please choose Patient");
								$validator->addValidation("namea","req","Please insert Name");
								$validator->addValidation("val","req","Please Login");
								if($validator->ValidateForm()){
								$timee = time();
								$file_name = $_FILES['file']['name'];
								$temp_dir = $_FILES["file"]["tmp_name"];
								$ext_str = "jpg,jpeg,png,docx,doc,pdf";
								$ext = substr($file_name, strrpos($file_name, '.') + 1);
								$timee = time();
								$fullname = $timee.'.'.$ext;
								$target_dir = "../extrafile/".$fullname;
								$allowed_extensions=explode(',',$ext_str);
								$check = filesize($temp_dir);
								if(!$check){
									echo '<div class="alert alert-danger">
													<strong>Warning!</strong> no file
												</div>';
								}else{
									
									if (file_exists($target_dir)) {
												
												$uploaderror = "File already exist";
									}
											
											
									if(!in_array($ext, $allowed_extensions)) {
	
														$uploaderror = "File type not allowed";
									}
													
											
									if($uploaderror){
										echo '<div class="alert alert-danger">
													<strong>Warning!</strong> '. $uploaderror .' 
												</div>';
											
									}else{
										
										$insert = database::getInstance()-> insert_extra_file($name,$patient,$val,$fullname);
											
										if($insert != 'yesi'){
											 echo $insert;
										 }else{
											if (move_uploaded_file($temp_dir, $target_dir)) {
													echo '<div class="alert alert-success">
																File Uploaded Successfully
															</div>';
											} else {
														database::getInstance()->delete_things('extra_file','extra_file',$fullname);
														echo '<div class="alert alert-danger">
																<strong>Error!</strong> There was an error while Uploading File
															</div>';
													}
										}
									}
								}
								
								}else{
									$error_hash = $validator->GetErrors();
									foreach($error_hash as $inpname => $inp_err){
									echo '<div class="alert alert-danger">
											<strong>Warning!</strong> ' . $inp_err . '
									</div>';
					}
				}
								
							
				}
				
				
				
				
}


function xrayFiles(){
	
				if(!isset($_FILES['scan'])){
							echo '<div class="alert alert-warning">
									Please Choose a File
								  </div>';
				}else{
								$patient = $_POST['patient'];
								$app_id = $_POST['id'];
								$name = $_POST['comment'];
								$xname = $_POST['x_name'];
								$xid = $_POST['xray_id'];
								$ref = $_POST['ref'];
								$category = $_POST['category'];
								$uploaderror ='';
								
								$validator = new FormValidator();
						
								$validator->addValidation("patient","req","Please choose Patient");
								$validator->addValidation("comment","req","Please insert Comment");
								$validator->addValidation("x_name","req","no Xray Name Found");
								$validator->addValidation("xray_id","req","no Xray Id Found");
								$validator->addValidation("ref","req","no Reference Found");
								$validator->addValidation("category","req","no Category Found");
								$validator->addValidation("id","req","no Appointment ID");
								if($validator->ValidateForm()){
								$timee = time();
								$file_name = $_FILES['scan']['name'];
								$temp_dir = $_FILES["scan"]["tmp_name"];
								$ext_str = "jpg,jpeg,png,docx,doc,pdf";
								$ext = substr($file_name, strrpos($file_name, '.') + 1);
								$timee = time();
								$fullname = $timee.'.'.$ext;
								$target_dir = "../extrafile/".$fullname;
								$allowed_extensions=explode(',',$ext_str);
								$check = filesize($temp_dir);
								if(!$check){
									echo '<div class="alert alert-danger">
													<strong>Warning!</strong> no file
												</div>';
								}else{
									
									if (file_exists($target_dir)) {
												
												$uploaderror = "File already exist";
									}
											
											
									if(!in_array($ext, $allowed_extensions)) {
	
														$uploaderror = "File type not allowed";
									}
													
											
									if($uploaderror){
										echo '<div class="alert alert-danger">
													<strong>Warning!</strong> '. $uploaderror .' 
												</div>';
											
									}else{
										
										$insert = database::getInstance()-> insert_xray_ress($patient, $app_id, $xid, $ref,$fullname, $name, $xname, $category);
										
											
										if($insert != 'yesi'){
											 echo $insert;
										 }else{
											if (move_uploaded_file($temp_dir, $target_dir)) {
													echo '<div class="alert alert-success">
																File Uploaded Successfully
															</div>';
											} else {
														database::getInstance()->delete_things('patient_xray_result','file',$fullname);
														echo '<div class="alert alert-danger">
																<strong>Error!</strong> There was an error while Uploading File
															</div>';
													}
										}
									}
								}
								
								}else{
									$error_hash = $validator->GetErrors();
									foreach($error_hash as $inpname => $inp_err){
									echo '<div class="alert alert-danger">
											<strong>Warning!</strong> ' . $inp_err . '
									</div>';
					}
				}
								
							
				}
				
				
				
				
}


	function newCase(){
	
		$diagnosis = ucfirst(htmlspecialchars($_POST['diagnosis']));
		$diagnosis = ucfirst(stripslashes($_POST['diagnosis']));
		$diagnosis = ucfirst(trim($_POST['diagnosis']));
		
		$pharm = ucfirst(htmlspecialchars($_POST['pharm']));
		$pharm = ucfirst(stripslashes($_POST['pharm']));
		$pharm = ucfirst(trim($_POST['pharm']));
		
		$dosage = ucfirst(htmlspecialchars($_POST['frequency']));
		$dosage = ucfirst(stripslashes($_POST['frequency']));
		$dosage = ucfirst(trim($_POST['frequency']));
		
		$tabs = ucfirst(htmlspecialchars($_POST['tabs']));
		$tabs = ucfirst(stripslashes($_POST['tabs']));
		$tabs = ucfirst(trim($_POST['tabs']));
		
		$instruction = ucfirst(htmlspecialchars($_POST['instruction']));
		$instruction = ucfirst(stripslashes($_POST['instruction']));
		$instruction = ucfirst(trim($_POST['instruction']));

		$quantity = ucfirst(htmlspecialchars($_POST['quantity']));
		$quantity = ucfirst(stripslashes($_POST['quantity']));
		$quantity = ucfirst(trim($_POST['quantity']));
		
		$duration = ucfirst(htmlspecialchars($_POST['duration']));
		$duration = ucfirst(stripslashes($_POST['duration']));
		$duration = ucfirst(trim($_POST['duration']));

		$sdosage = ucfirst(htmlspecialchars($_POST['sfrequency']));
		$sdosage = ucfirst(stripslashes($_POST['sfrequency']));
		$sdosage = ucfirst(trim($_POST['sfrequency']));
		
		$stabs = ucfirst(htmlspecialchars($_POST['stabs']));
		$stabs = ucfirst(stripslashes($_POST['stabs']));
		$stabs = ucfirst(trim($_POST['stabs']));

		$squantity = ucfirst(htmlspecialchars($_POST['squantity']));
		$squantity = ucfirst(stripslashes($_POST['squantity']));
		$squantity = ucfirst(trim($_POST['squantity']));
		
		$sduration = ucfirst(htmlspecialchars($_POST['sduration']));
		$sduration = ucfirst(stripslashes($_POST['sduration']));
		$sduration = ucfirst(trim($_POST['sduration']));

		$error = '';

		$id = $_POST['id'];
		$doc_id = $_POST['doc_id'];
		$p_id = $_POST['p_id'];

		$validator = new FormValidator();
						
		$validator->addValidation("pharm","req","Please select drug");
									
		if($validator->ValidateForm()){
				
				//$amount = Database::getInstance()->get_name_from_id("price","pharm_stock","id",$pharm);
				$insert = Database::getInstance()->insert_case($diagnosis,$pharm, $tabs,$dosage,$duration,$quantity,$instruction, $doc_id, $id, $p_id,$stabs,$squantity,$sdosage,$sduration);
				if($insert == 'Done'){
					//Database::getInstance()->notify_pharm($p_id);
					echo '<div class="alert alert-success">
							Successful					
						</div>';
				} else {
					echo $insert;
				}
			
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function edit_Presc(){
	
		$diagnosis = ucfirst(htmlspecialchars($_POST['diagnosis']));
		$diagnosis = ucfirst(stripslashes($_POST['diagnosis']));
		$diagnosis = ucfirst(trim($_POST['diagnosis']));
		
		$pharm = ucfirst(htmlspecialchars($_POST['pharm']));
		$pharm = ucfirst(stripslashes($_POST['pharm']));
		$pharm = ucfirst(trim($_POST['pharm']));
		
		$dosage = ucfirst(htmlspecialchars($_POST['frequency']));
		$dosage = ucfirst(stripslashes($_POST['frequency']));
		$dosage = ucfirst(trim($_POST['frequency']));
		
		$tabs = ucfirst(htmlspecialchars($_POST['tabs']));
		$tabs = ucfirst(stripslashes($_POST['tabs']));
		$tabs = ucfirst(trim($_POST['tabs']));
		
		$instruction = ucfirst(htmlspecialchars($_POST['instruction']));
		$instruction = ucfirst(stripslashes($_POST['instruction']));
		$instruction = ucfirst(trim($_POST['instruction']));

		$quantity = ucfirst(htmlspecialchars($_POST['quantity']));
		$quantity = ucfirst(stripslashes($_POST['quantity']));
		$quantity = ucfirst(trim($_POST['quantity']));
		
		$duration = ucfirst(htmlspecialchars($_POST['duration']));
		$duration = ucfirst(stripslashes($_POST['duration']));
		$duration = ucfirst(trim($_POST['duration']));

		$sdosage = ucfirst(htmlspecialchars($_POST['sfrequency']));
		$sdosage = ucfirst(stripslashes($_POST['sfrequency']));
		$sdosage = ucfirst(trim($_POST['sfrequency']));
		
		$stabs = ucfirst(htmlspecialchars($_POST['stabs']));
		$stabs = ucfirst(stripslashes($_POST['stabs']));
		$stabs = ucfirst(trim($_POST['stabs']));

		$squantity = ucfirst(htmlspecialchars($_POST['squantity']));
		$squantity = ucfirst(stripslashes($_POST['squantity']));
		$squantity = ucfirst(trim($_POST['squantity']));
		
		$sduration = ucfirst(htmlspecialchars($_POST['sduration']));
		$sduration = ucfirst(stripslashes($_POST['sduration']));
		$sduration = ucfirst(trim($_POST['sduration']));

		$error = '';

		$id = $_POST['id'];
		$p = $_POST['p'];

		$validator = new FormValidator();
						
		$validator->addValidation("pharm","req","Please select drug");
									
		if($validator->ValidateForm()){
				
				//$amount = Database::getInstance()->get_name_from_id("price","pharm_stock","id",$pharm);
				$insert = Database::getInstance()->edit_presc_case($diagnosis,$pharm, $tabs,$dosage,$duration,$quantity,$instruction, $id,$stabs,$squantity,$sdosage,$sduration,$p);
				if($insert == 'Done'){
					//Database::getInstance()->notify_pharm($p_id);
					echo '<div class="alert alert-success">
							Successful					
						</div>';
				} else {
					echo $insert;
				}
			
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}



	function requestAdmission(){
	
		
		$val = $_POST['val'];
		$doc = $_POST['doc_id'];
		$p_id = $_POST['p_id'];
		$ward = $_POST['ward'];
		
		$validator = new FormValidator();
						
		$validator->addValidation("doc_id","req","Please Login");
		$validator->addValidation("val","req","Please select Appointment");
							
		if($validator->ValidateForm()){
			

				echo $insert = Database::getInstance()->insert_admission_request($val,$doc, $p_id,$ward);
					$notify = Database::getInstance()->notify_nurse2($val,$doc,$p_id); 
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function request_physiotherapy(){
		$app = $_POST['app'];
		$staff = $_POST['staff'];
		$pid = $_POST['pid'];
		$front_desk = $_POST['front'];
		
		$validator = new FormValidator();
						
		$validator->addValidation("staff","req","Please Login");
		$validator->addValidation("app","req","No Appointment");
		$validator->addValidation("front","req","No Front Desk");
		$validator->addValidation("pid","req","No Patient");
							
		if($validator->ValidateForm()){
			

				echo $insert = Database::getInstance()->insert_physiotherapy_request($app,$staff,$pid,$front_desk);
					//$notify = Database::getInstance()->notify_nurse2($val,$doc,$p_id); 
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function therapyPlan(){
	
		
		$val = $_POST['val'];
		$app = $_POST['app'];
		$p_id = $_POST['patient'];
		$link = $_POST['link_ref'];
		$comment = $_POST['comment'];
		$validator = new FormValidator();
						
		$validator->addValidation("patient","req","Please Login");
		$validator->addValidation("val","req","Please select Appointment");
							
		if($validator->ValidateForm()){
			

				$insert = Database::getInstance()->insert_therapy_plan($val,$app,$p_id,$link,$comment);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Therapy Plan Was Successfully Added!
						</div>';
				} else {
					echo $insert;
				}
					//$notify = Database::getInstance()->notify_nurse2($val,$doc,$p_id); 
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}


	
	function newCategory(){
		$name = ucfirst(htmlspecialchars($_POST['name']));
		$name = ucfirst(stripslashes($_POST['name']));
		$name = ucfirst(trim(ucfirst($_POST['name'])));
		
		$error = '';
		$validator = new FormValidator();
						
		$validator->addValidation("name","req","Please ENTER Category");
									
		if($validator->ValidateForm()){
			if (EMPTY($name)) {
				$error ='Category cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_category($name);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Category Added
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function labRes(){
		
		$error = '';

		$app_id = $_POST['app_id'];
		$doc_id = $_POST['doc_id'];
		$p_id = $_POST['p_id'];
		$tech_id = $_POST['tech_id'];
				
		$test = $_POST['test'];
		$result = $_POST['result'];
		$flag = $_POST['flag'];
		$units = $_POST['units'];
		$ref = $_POST['ref'];
		$range = $_POST['range'];
		$mainArray = [
			$test, 
			$result, 
			$flag, 
			$units,
			$ref,
			$range
		];
		//all of thsi is done so each line of array will be for one line of input fields
		foreach( $test as $key => $n ) {
			$DataArr[] = ($n." ".$result[$key]." ".$flag[$key]." ".$units[$key]." ".$ref[$key]." ".$range[$key]);
		}

		$validator = new FormValidator();
									
		if($validator->ValidateForm()){
			

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_lab_res($DataArr,$doc_id, $app_id, $p_id, $tech_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Lab result sent successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newCat(){
		$cat_name = ucfirst(htmlspecialchars($_POST['cat_name']));
		$cat_name = ucfirst(stripslashes($_POST['cat_name']));
		$cat_name = ucfirst(trim($_POST['cat_name']));
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("cat_name","req","Please enter name of category");
									
		if($validator->ValidateForm()){
			if (EMPTY($cat_name)) {
				$error ='Category cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_cat($cat_name);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Category entered successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function newUnit(){
		$unit_name = ucfirst(htmlspecialchars($_POST['unit_name']));
		$unit_name = ucfirst(stripslashes($_POST['unit_name']));
		$unit_name = ucfirst(trim($_POST['unit_name']));
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("unit_name","req","Please enter unit");
									
		if($validator->ValidateForm()){
			if (EMPTY($unit_name)) {
				$error ='Unit cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_unit($unit_name);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Unit entered successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function addDiagnosis(){
		$diagnosis = ucfirst(htmlspecialchars($_POST['diagnosis']));
		$diagnosis = ucfirst(stripslashes($_POST['diagnosis']));
		$diagnosis = ucfirst(trim($_POST['diagnosis']));
		
		$complaint = ucfirst(htmlspecialchars($_POST['complaint']));
		$complaint = ucfirst(stripslashes($_POST['complaint']));
		$complaint = ucfirst(trim($_POST['complaint']));
		
		$exam = ucfirst(htmlspecialchars($_POST['exam']));
		$exam = ucfirst(stripslashes($_POST['exam']));
		$exam = ucfirst(trim($_POST['exam']));
		
		$patients_note = ucfirst(htmlspecialchars($_POST['patients_note']));
		$patients_note = ucfirst(trim($_POST['patients_note']));
		$patients_note = str_replace("\n", "<br/>", $patients_note);

		$adm_note = ucfirst(htmlspecialchars($_POST['adm_note']));
		$adm_note = ucfirst(trim($_POST['adm_note']));
		$adm_note = str_replace("\n", "<br/>", $adm_note);

		$val = $_POST['val'];
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("diagnosis","req","Please enter Diagnosis");
		$validator->addValidation("complaint","req","Please enter Complaint");
		$validator->addValidation("exam","req","Please enter Examinantion");
		
		if($validator->ValidateForm()){
			if (EMPTY($diagnosis)) {
				$error ='field cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_diagnosis($diagnosis,$complaint,$exam, $patients_note,$adm_note,$val);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
						Doctor,s note entered successfully</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newAdminStock(){
		$stock = ucfirst(htmlspecialchars($_POST['stock']));
		$stock = ucfirst(stripslashes($_POST['stock']));
		$stock = ucfirst(trim($_POST['stock']));

		$quantity = ucfirst(htmlspecialchars($_POST['quantity']));
		$quantity = ucfirst(stripslashes($_POST['quantity']));
		$quantity = ucfirst(trim($_POST['quantity']));

		$taken = ucfirst(htmlspecialchars($_POST['taken']));
		$taken = ucfirst(stripslashes($_POST['taken']));
		$taken = ucfirst(trim($_POST['taken']));
		
		$patient = ucfirst(htmlspecialchars($_POST['patient']));
		$patient = ucfirst(stripslashes($_POST['patient']));
		$patient = ucfirst(trim($_POST['patient']));

		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("stock","req","Please select Stock");
		$validator->addValidation("quantity","req","Please enter quantity");
		$validator->addValidation("taken","req","Please enter Taken By");
		$validator->addValidation("patient","req","Please Select Patient");
		
		if($validator->ValidateForm()){
			$quantityBefore = Database::getInstance()->get_name_from_id('stock','pharm_stock','id',$stock);
			$quantityLeft = $quantityBefore - $quantity;
			if (EMPTY($stock)) {
				$error ='Stock cannot be empty';
			}

			if (EMPTY($patient)) {
				$error ='Patient cannot be empty';
			}
			
			if (EMPTY($quantity)) {
				$error ='Quantity cannot be empty';
			}

			if (EMPTY($taken)) {
				$error ='Taken By cannot be empty';
			}
			
			if ($quantityLeft < 0) {
				$error ='Stock is not enough for the quantity required';
			}
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				
				$insert = Database::getInstance()->insert_admin_stock($stock, $quantity, $taken, $patient, $quantityLeft);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Stock Removed successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	
	function newStock(){
		$name = ucfirst(htmlspecialchars($_POST['name']));
		$name = ucfirst(stripslashes($_POST['name']));
		$name = ucfirst(trim($_POST['name']));

		$cat = ucfirst(htmlspecialchars($_POST['cat']));
		$cat = ucfirst(stripslashes($_POST['cat']));
		$cat = ucfirst(trim($_POST['cat']));

		$unit = ucfirst(htmlspecialchars($_POST['unit']));
		$unit = ucfirst(stripslashes($_POST['unit']));
		$unit = ucfirst(trim($_POST['unit']));

		$cprice = ucfirst(htmlspecialchars($_POST['cost']));
		$cprice = ucfirst(stripslashes($_POST['cost']));
		$cprice = ucfirst(trim($_POST['cost']));

		$price = ucfirst(htmlspecialchars($_POST['price']));
		$price = ucfirst(stripslashes($_POST['price']));
		$price = ucfirst(trim($_POST['price']));

		$stock = ucfirst(htmlspecialchars($_POST['stock']));
		$stock = ucfirst(stripslashes($_POST['stock']));
		$stock = ucfirst(trim($_POST['stock']));
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("name","req","Please enter name of item");
		$validator->addValidation("cat","req","Please enter cat of item");
		$validator->addValidation("unit","req","Please enter unit of item");
		$validator->addValidation("cost","req","Please enter cost price of item");
		$validator->addValidation("price","req","Please enter price of item");
		$validator->addValidation("stock","req","Please enter stock of item");
									
		if($validator->ValidateForm()){
			if (EMPTY($name)) {
				$error ='Name cannot be empty';
			}

			if (EMPTY($cat)) {
				$error ='Category cannot be empty';
			}

			if (EMPTY($unit)) {
				$error ='Unit cannot be empty';
			}

			if (EMPTY($price)) {
				$error ='Price cannot be empty';
			}

			if (EMPTY($stock)) {
				$error ='Stock cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_stock($name, $cat, $unit,$cprice, $price, $stock);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Stock entered successfully					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function get_price(){			
		$proName = lcfirst(htmlspecialchars($_POST['proName']));
		$proName = lcfirst(stripslashes($_POST['proName']));
		$proName = lcfirst(trim($_POST['proName']));

		$proQty = lcfirst(htmlspecialchars($_POST['proQty']));
		$proQty = lcfirst(stripslashes($_POST['proQty']));
		$proQty = lcfirst(trim($_POST['proQty']));
		
				
		$error = '';

		Database::getInstance()->get_the_price($proName, $proQty);	
	}

	function sendToAcc(){
		$app_id = $_POST['app_id'];
		$p_id = $_POST['p_id'];
		$code = rand(1000,100000);

		$name = $_POST['name'];
		$qty = $_POST['qty'];
		$intake = $_POST['intake'];
		$duration = $_POST['duration'];
		$price = $_POST['price'];
		$mainArray = [
			$name, 
			$qty, 
			$intake, 
			$duration,
			$price
		];
		
		foreach( $name as $key => $n ) {
			$DataArr[] = ($n." ".$qty[$key]." ".$intake[$key]." ".$duration[$key]." ".$price[$key]);
		}

		
		$error = '';

		$validator = new FormValidator();
							
		if($validator->ValidateForm()){
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->send_acc($DataArr, $app_id, $p_id, $code);
				echo $insert;
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function changeOrderStatus(){			
		$status = htmlspecialchars($_POST['selected']);
		$status = stripslashes($_POST['selected']);
		$status = $_POST['selected'];
		
		$app_id = $_POST['app_id'];
		
		$patient_id = $_POST['patient_id'];

		$order_id = $_POST['order_id'];

		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("selected","req","Please pick a status");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->change_status($status, $app_id, $patient_id, $order_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Payment status updated
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}

	function sendTestAcc(){
		
		$app_id = $_POST['app_id'];
		$p_id = $_POST['p_id'];

		$code = rand(1000,100000);

		$test = $_POST['test'];
		$amt = $_POST['amt'];
		$mainArray = [
			$test, 
			$amt
		];
		
		foreach( $test as $key => $n ) {
			$DataArr[] = ($n." ".$amt[$key]);
		}

		
		$error = '';

		$validator = new FormValidator();
					
		if($validator->ValidateForm()){
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->send_test_acc($DataArr, $app_id, $p_id, $code);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Successful					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	
	function newIPDF(){
		$company = ucfirst(htmlspecialchars($_POST['company']));
		$company = ucfirst(stripslashes($_POST['company']));
		$company = ucfirst(trim($_POST['company']));

		$breakfast = ucfirst(htmlspecialchars($_POST['breakfast']));
		$breakfast = ucfirst(stripslashes($_POST['breakfast']));
		$breakfast = ucfirst(trim($_POST['breakfast']));

		$lunch = ucfirst(htmlspecialchars($_POST['lunch']));
		$lunch = ucfirst(stripslashes($_POST['lunch']));
		$lunch = ucfirst(trim($_POST['lunch']));

		$dinner = ucfirst(htmlspecialchars($_POST['dinner']));
		$dinner = ucfirst(stripslashes($_POST['dinner']));
		$dinner = ucfirst(trim($_POST['dinner']));

		$amount = ucfirst(htmlspecialchars($_POST['amount']));
		$amount = ucfirst(stripslashes($_POST['amount']));
		$amount = ucfirst(trim($_POST['amount']));
		
		$error = '';
		$val = $_POST['val'];

		$validator = new FormValidator();
						
		$validator->addValidation("company","req","Please enter name of Company");
		$validator->addValidation("breakfast","req","Please enter Breakfast");
		$validator->addValidation("lunch","req","Please enter Lunch");
		$validator->addValidation("dinner","req","Please enter Dinner");
		$validator->addValidation("amount","req","Please enter Amount");
									
		if($validator->ValidateForm()){
			
				$insert = Database::getInstance()->insert_ipdf($company, $breakfast, $lunch, $dinner, $amount, $val);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Food entered successfully					
						</div>';
				} else {
					echo $insert;
				}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newObs(){
		$temp = ucfirst(htmlspecialchars($_POST['temp']));
		$temp = ucfirst(stripslashes($_POST['temp']));
		$temp = ucfirst(trim($_POST['temp']));

		$resr = ucfirst(htmlspecialchars($_POST['resr']));
		$resr = ucfirst(stripslashes($_POST['resr']));
		$resr = ucfirst(trim($_POST['resr']));

		$pulse = ucfirst(htmlspecialchars($_POST['pulse']));
		$pulse = ucfirst(stripslashes($_POST['pulse']));
		$pulse = ucfirst(trim($_POST['pulse']));

		$bp = ucfirst(htmlspecialchars($_POST['bp']));
		$bp = ucfirst(stripslashes($_POST['bp']));
		$bp = ucfirst(trim($_POST['bp']));

		$intake = ucfirst(htmlspecialchars($_POST['intake']));
		$intake = ucfirst(stripslashes($_POST['intake']));
		$intake = ucfirst(trim($_POST['intake']));
		
		$output = ucfirst(htmlspecialchars($_POST['output']));
		$output = ucfirst(stripslashes($_POST['output']));
		$output = ucfirst(trim($_POST['output']));
		
		$error = '';
		$by = $_POST['by'];
		$ipd = $_POST['ipid'];

		$validator = new FormValidator();
						
		$validator->addValidation("temp","req","Please enter Temp");
		$validator->addValidation("resr","req","Please enter Resr");
		$validator->addValidation("pulse","req","Please enter Pulse");
		$validator->addValidation("bp","req","Please enter BP");
		$validator->addValidation("intake","req","Please enter Intake");
		$validator->addValidation("output","req","Please enter Output");
		$validator->addValidation("by","req","Please Login");
		$validator->addValidation("ipid","req","Please select a patient");
									
		if($validator->ValidateForm()){
			
				$insert = Database::getInstance()->insert_obs($temp, $resr, $pulse, $bp, $intake, $output, $by, $ipd);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Observation sent					
						</div>';
				} else {
					echo $insert;
				}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					 ' . $inp_err . '
				</div>';
			}
		}
	}

	function newDis(){
		$pharm = ucfirst(htmlspecialchars($_POST['pharm']));
		$pharm = ucfirst(stripslashes($_POST['pharm']));
		$pharm = ucfirst(trim($_POST['pharm']));

		$dosage = ucfirst(htmlspecialchars($_POST['dosage']));
		$dosage = ucfirst(stripslashes($_POST['dosage']));
		$dosage = ucfirst(trim($_POST['dosage']));

		$meth = ucfirst(htmlspecialchars($_POST['meth']));
		$meth = ucfirst(stripslashes($_POST['meth']));
		$meth = ucfirst(trim($_POST['meth']));

		$remark = ucfirst(htmlspecialchars($_POST['remark']));
		$remark = ucfirst(stripslashes($_POST['remark']));
		$remark = ucfirst(trim($_POST['remark']));

		$error = '';
		$by = $_POST['by'];
		$ipd = $_POST['ipid'];

		$validator = new FormValidator();
						
		$validator->addValidation("pharm","req","Please select drug");
		$validator->addValidation("dosage","req","Please enter dosage");
		$validator->addValidation("meth","req","Please enter Method Of Administration");
		$validator->addValidation("by","req","Please Login");
		$validator->addValidation("ipid","req","Please select a patient");
									
		if($validator->ValidateForm()){
			
				$insert = Database::getInstance()->insert_dis($pharm, $dosage, $meth, $remark, $by, $ipd);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Dispensing chart sent					
						</div>';
				} else {
					echo $insert;
				}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					 ' . $inp_err . '
				</div>';
			}
		}
	}

	function newFluid(){
		$nature = ucfirst(htmlspecialchars($_POST['nature']));
		$nature = ucfirst(stripslashes($_POST['nature']));
		$nature = ucfirst(trim($_POST['nature']));

		$oral = ucfirst(htmlspecialchars($_POST['oral']));
		$oral = ucfirst(stripslashes($_POST['oral']));
		$oral = ucfirst(trim($_POST['oral']));

		$rectal = ucfirst(htmlspecialchars($_POST['rectal']));
		$rectal = ucfirst(stripslashes($_POST['rectal']));
		$rectal = ucfirst(trim($_POST['rectal']));

		$iv = ucfirst(htmlspecialchars($_POST['iv']));
		$iv = ucfirst(stripslashes($_POST['iv']));
		$iv = ucfirst(trim($_POST['iv']));
		
		$other1 = ucfirst(htmlspecialchars($_POST['other1']));
		$other1 = ucfirst(stripslashes($_POST['other1']));
		$other1 = ucfirst(trim($_POST['other1']));
		
		$total1 = ucfirst(htmlspecialchars($_POST['total1']));
		$total1 = ucfirst(stripslashes($_POST['total1']));
		$total1 = ucfirst(trim($_POST['total1']));
		
		$urine = ucfirst(htmlspecialchars($_POST['urine']));
		$urine = ucfirst(stripslashes($_POST['urine']));
		$urine = ucfirst(trim($_POST['urine']));
		
		$vomit = ucfirst(htmlspecialchars($_POST['vomit']));
		$vomit = ucfirst(stripslashes($_POST['vomit']));
		$vomit = ucfirst(trim($_POST['vomit']));
		
		$tube = ucfirst(htmlspecialchars($_POST['tube']));
		$tube = ucfirst(stripslashes($_POST['tube']));
		$tube = ucfirst(trim($_POST['tube']));
		
		$other2 = ucfirst(htmlspecialchars($_POST['other2']));
		$other2 = ucfirst(stripslashes($_POST['other2']));
		$other2 = ucfirst(trim($_POST['other2']));
		
		$total2 = ucfirst(htmlspecialchars($_POST['total2']));
		$total2 = ucfirst(stripslashes($_POST['total2']));
		$total2 = ucfirst(trim($_POST['total2']));
		
		$balance = ucfirst(htmlspecialchars($_POST['balance']));
		$balance = ucfirst(stripslashes($_POST['balance']));
		$balance = ucfirst(trim($_POST['balance']));
		
		$chloride = ucfirst(htmlspecialchars($_POST['chloride']));
		$chloride = ucfirst(stripslashes($_POST['chloride']));
		$chloride = ucfirst(trim($_POST['chloride']));

		$error = '';
		$ipd = $_POST['ipid'];

		$validator = new FormValidator();
						
		$validator->addValidation("nature","req","Please enter Nature Of Fluid");
		$validator->addValidation("oral","req","Please enter Oral");
		$validator->addValidation("rectal","req","Please enter Rectal");
		$validator->addValidation("iv","req","Please enter IV");
		$validator->addValidation("other1","req","Please enter Intake Other Routes");
		$validator->addValidation("total1","req","Please enter Intake Total");
		$validator->addValidation("urine","req","Please enter Urine");
		$validator->addValidation("vomit","req","Please enter Vomit");
		$validator->addValidation("tube","req","Please enter Tube");
		$validator->addValidation("other2","req","Please enter Output Other Routes");
		$validator->addValidation("total2","req","Please enter Output Total");
		$validator->addValidation("balance","req","Please enter Balance");
		$validator->addValidation("chloride","req","Please enter Chloride in Urine");
		$validator->addValidation("ipid","req","Please select a patient");
									
		if($validator->ValidateForm()){
			
				$insert = Database::getInstance()->insert_fluid($nature, $oral, $rectal, $iv, $other1, $total1, $urine, $vomit, $tube, $other2, $total2, $balance, $chloride, $ipd);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Fluid Chart sent					
						</div>';
				} else {
					echo $insert;
				}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					 ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newSurgery(){
		$allow = ucfirst(htmlspecialchars($_POST['allow']));
		$allow = ucfirst(stripslashes($_POST['allow']));
		$allow = ucfirst(trim($_POST['allow']));

		$patient = htmlspecialchars($_POST['patient']);
		$patient = stripslashes($_POST['patient']);
		$patient = trim($_POST['patient']);

		$address = ucfirst(htmlspecialchars($_POST['address']));
		$address = ucfirst(stripslashes($_POST['address']));
		$address = ucfirst(trim($_POST['address']));

		$validator = new FormValidator();
						
		$validator->addValidation("patient","req","Please enter Patient");
		$validator->addValidation("allow","req","Please enter Who Allows");
		$validator->addValidation("address","req","Please enter Address");
									
		if($validator->ValidateForm()){
			
				$insert = Database::getInstance()->insert_surgery($allow, $patient, $address);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Sugery Agreement sent					
						</div>';
				} else {
					echo $insert;
				}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					 ' . $inp_err . '
				</div>';
			}
		}
	}

	
	function newIPD(){
		$admin_no = ucfirst(htmlspecialchars($_POST['admin_no']));
		$admin_no = ucfirst(stripslashes($_POST['admin_no']));
		$admin_no = ucfirst(trim($_POST['admin_no']));
		
		$admin_date = ucfirst(htmlspecialchars($_POST['admin_date']));
		$admin_date = ucfirst(stripslashes($_POST['admin_date']));
		$admin_date = ucfirst(trim($_POST['admin_date']));
		
		$referred = ucfirst(htmlspecialchars($_POST['referred']));
		$referred = ucfirst(stripslashes($_POST['referred']));
		$referred = ucfirst(trim($_POST['referred']));
		
		$doctor = ucfirst(htmlspecialchars($_POST['doctor']));
		$doctor = ucfirst(stripslashes($_POST['doctor']));
		$doctor = ucfirst(trim($_POST['doctor']));

		$nurse = ucfirst(htmlspecialchars($_POST['nurse']));
		$nurse = ucfirst(stripslashes($_POST['nurse']));
		$nurse = ucfirst(trim($_POST['nurse']));
		
		$room = htmlspecialchars($_POST['room']);
		$room = stripslashes($_POST['room']);
		$room = trim($_POST['room']);
		
		$ward = htmlspecialchars($_POST['ward']);
		$ward = stripslashes($_POST['ward']);
		$ward = trim($_POST['ward']);

		$bed_num = htmlspecialchars($_POST['bed_num']);
		$bed_num = stripslashes($_POST['bed_num']);
		$bed_num = trim($_POST['bed_num']);

		$p_id = trim($_POST['p_id']);
		$adr = trim($_POST['adr']);
		$code = rand(1000,100000);

		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("admin_no","req","Please fill in admission number");
		$validator->addValidation("admin_date","req","Please fill in admission date");
		$validator->addValidation("referred","req","Please fill in referral");
		$validator->addValidation("doctor","req","Please fill in doctor");
		$validator->addValidation("room","req","Please fill in room");
		$validator->addValidation("ward","req","Please fill in ward");
		$validator->addValidation("bed_num","req","Please fill in bed number");	
		$validator->addValidation("nurse","req","Please fill in nurse");
		if($validator->ValidateForm()){
									
													
			if (EMPTY($admin_no)) {
				$error ='Admission number cannot be empty';
			}

			if (EMPTY($admin_date)) {
				$error ='Admission date cannot be empty';
			}
			
			if (EMPTY($referred)) {
				$error ='Referral cannot be empty';
			}
			
			if (EMPTY($doctor)) {
				$error ='Doctor cannot be empty';
			}

			if (EMPTY($nurse)) {
				$error ='Nurse cannot be empty';
			}


			if (EMPTY($room)) {
				$error ='Room number cannot be empty';
			}

			if (EMPTY($ward)) {
				$error ='Ward cannot be empty';
			}
										
			if (EMPTY($bed_num)) {
				$error ='Bed number cannot be empty';
			}

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
					
					$insert = Database::getInstance()->insert_ipd_patient($admin_no, $admin_date, $referred, $doctor, $room, $ward, $bed_num, $p_id, $code, $nurse, $adr);
					if($insert == 'Done'){
						echo '<div class="alert alert-success">
							Patient Added Successfully					
						</div>';
					} else {
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}

	function newCard(){			
		$name = htmlspecialchars($_POST['name']);
		$name = stripslashes($_POST['name']);
		$name = $_POST['name'];
		
		$cost = htmlspecialchars($_POST['cost']);
		$cost = stripslashes($_POST['cost']);
		$cost = $_POST['cost'];
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("name","req","Please enter card type");
		$validator->addValidation("cost","req","Please enter cost");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_card($name, $cost);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Card type entered successfully
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	

	function customResult(){			
		$res = htmlspecialchars($_POST['result']);
		$res = stripslashes($_POST['result']);
		$res = str_replace("\n", "<br/>", $res);
		
		$val = $_POST['val'];
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("result","req","Please Enter result");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_custom_result($res,$val);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Custom Result entered successfully
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newDuty(){			
		$morn = htmlspecialchars($_POST['morn']);
		$morn = stripslashes($_POST['morn']);
		$morn = trim($_POST['morn']);
		
		$bed = htmlspecialchars($_POST['bed']);
		$bed = stripslashes($_POST['bed']);
		$bed = trim($_POST['bed']);
		
		$v_bed = htmlspecialchars($_POST['v_bed']);
		$v_bed = stripslashes($_POST['v_bed']);
		$v_bed = trim($_POST['v_bed']);
		
		$t_pt = htmlspecialchars($_POST['t_pt']);
		$t_pt = stripslashes($_POST['t_pt']);
		$t_pt = trim($_POST['t_pt']);
		
		$adm = htmlspecialchars($_POST['adm']);
		$adm = stripslashes($_POST['adm']);
		$adm = trim($_POST['adm']);
		
		$disc = htmlspecialchars($_POST['disc']);
		$disc = stripslashes($_POST['disc']);
		$disc = trim($_POST['disc']);
		
		$delivery = htmlspecialchars($_POST['delivery']);
		$delivery = stripslashes($_POST['delivery']);
		$delivery = trim($_POST['delivery']);
		
		$cs = htmlspecialchars($_POST['cs']);
		$cs = stripslashes($_POST['cs']);
		$cs = trim($_POST['cs']);
		
		$labour = htmlspecialchars($_POST['labour']);
		$labour = stripslashes($_POST['labour']);
		$labour = trim($_POST['labour']);
		
		$trans = htmlspecialchars($_POST['trans']);
		$trans = stripslashes($_POST['trans']);
		$trans = trim($_POST['trans']);
		
		$death = htmlspecialchars($_POST['death']);
		$death = stripslashes($_POST['death']);
		$death = trim($_POST['death']);
		
		$comment = htmlspecialchars($_POST['comment']);
		$comment = stripslashes($_POST['comment']);
		$comment = trim($_POST['comment']);
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("morn","req","Please enter duty time");
		$validator->addValidation("bed","req","Please enter bed");
		$validator->addValidation("v_bed","req","Please enter v bed");
		$validator->addValidation("t_pt","req","Please enter tpt");
		$validator->addValidation("adm","req","Please enter adm");
		$validator->addValidation("disc","req","Please enter disc");
		$validator->addValidation("delivery","req","Please enter delivery");
		$validator->addValidation("cs","req","Please enter cs");
		$validator->addValidation("labour","req","Please enter labour");
		$validator->addValidation("trans","req","Please enter trans");
		$validator->addValidation("death","req","Please enter death");
		
		if($validator->ValidateForm()){
		
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_stat($morn, $bed, $v_bed, $t_pt, $adm, $disc, $delivery, $cs, $labour, $trans, $death, $comment);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Duty check entered successfully
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newAnte(){			
		$name = htmlspecialchars($_POST['name']);
		$name = stripslashes($_POST['name']);
		$name = trim($_POST['name']);
		
		$pos = htmlspecialchars($_POST['pos']);
		$pos = stripslashes($_POST['pos']);
		$pos = trim($_POST['pos']);
		
		$sex = htmlspecialchars($_POST['sex']);
		$sex = stripslashes($_POST['sex']);
		$sex = trim($_POST['sex']);
		
		$dob = htmlspecialchars($_POST['dob']);
		$dob = stripslashes($_POST['dob']);
		$dob = trim($_POST['dob']);
		
		$house_num = htmlspecialchars($_POST['house_num']);
		$house_num = stripslashes($_POST['house_num']);
		$house_num = trim($_POST['house_num']);
		
		$town = htmlspecialchars($_POST['town']);
		$town = stripslashes($_POST['town']);
		$town = trim($_POST['town']);

		$village = htmlspecialchars($_POST['village']);
		$village = stripslashes($_POST['village']);
		$village = trim($_POST['village']);
		
		$ward = htmlspecialchars($_POST['ward']);
		$ward = stripslashes($_POST['ward']);
		$ward = trim($_POST['ward']);
		
		$state = htmlspecialchars($_POST['state']);
		$state = stripslashes($_POST['state']);
		$state = trim($_POST['state']);
		
		$lga = htmlspecialchars($_POST['lga']);
		$lga = stripslashes($_POST['lga']);
		$lga = trim($_POST['lga']);
		
		$mother_name = htmlspecialchars($_POST['mother_name']);
		$mother_name = stripslashes($_POST['mother_name']);
		$mother_name = trim($_POST['mother_name']);
		
		$mother_phone = htmlspecialchars($_POST['mother_phone']);
		$mother_phone = stripslashes($_POST['mother_phone']);
		$mother_phone = trim($_POST['mother_phone']);
		
		$father_phone = htmlspecialchars($_POST['father_phone']);
		$father_phone = stripslashes($_POST['father_phone']);
		$father_phone = trim($_POST['father_phone']);
		
		$father_name = htmlspecialchars($_POST['father_name']);
		$father_name = stripslashes($_POST['father_name']);
		$father_name = trim($_POST['father_name']);
		
		$cg = htmlspecialchars($_POST['cg']);
		$cg = stripslashes($_POST['cg']);
		$cg = trim($_POST['cg']);
		
		$cg_phone = htmlspecialchars($_POST['cg_phone']);
		$cg_phone = stripslashes($_POST['cg_phone']);
		$cg_phone = trim($_POST['cg_phone']);
		
		$c_year = $_POST['c_year'];
		$c_health = $_POST['c_health'];
		$c_sex = $_POST['c_sex'];
		$mainArray = [
			$c_year, 
			$c_health, 
			$c_sex
		];
		
		//all of thsi is done so each line of array will be for one line of input fields
		foreach( $c_year as $key => $n ) {
			$DataArr[] = ($n." ".$c_health[$key]." ".$c_sex[$key]);
		}
		
		$weigh = htmlspecialchars($_POST['weigh']);
		$weigh = stripslashes($_POST['weigh']);
		$weigh = trim($_POST['weigh']);
		
		$twin = htmlspecialchars($_POST['twin']);
		$twin = stripslashes($_POST['twin']);
		$twin = trim($_POST['twin']);
		
		$fed = htmlspecialchars($_POST['fed']);
		$fed = stripslashes($_POST['fed']);
		$fed = trim($_POST['fed']);
		
		$support = htmlspecialchars($_POST['support']);
		$support = stripslashes($_POST['support']);
		$support = trim($_POST['support']);

		$underweight = htmlspecialchars($_POST['underweight']);
		$underweight = stripslashes($_POST['underweight']);
		$underweight = trim($_POST['underweight']);
		
		$exta_care = htmlspecialchars($_POST['exta_care']);
		$exta_care = stripslashes($_POST['exta_care']);
		$exta_care = trim($_POST['exta_care']);
		
		$bnum1 = htmlspecialchars($_POST['bnum1']);
		$bnum1 = stripslashes($_POST['bnum1']);
		$bnum1 = trim($_POST['bnum1']);

		$v1 = htmlspecialchars($_POST['v1']);
		$v1 = stripslashes($_POST['v1']);
		$v1 = trim($_POST['v1']);
		
		$dg1 = htmlspecialchars($_POST['dg1']);
		$dg1 = stripslashes($_POST['dg1']);
		$dg1 = trim($_POST['dg1']);
		
		$dn1 = htmlspecialchars($_POST['dn1']);
		$dn1 = stripslashes($_POST['dn1']);
		$dn1 = trim($_POST['dn1']);
		
		$cm1 = htmlspecialchars($_POST['cm1']);
		$cm1 = stripslashes($_POST['cm1']);
		$cm1 = trim($_POST['cm1']);
		
		$bnum2 = htmlspecialchars($_POST['bnum2']);
		$bnum2 = stripslashes($_POST['bnum2']);
		$bnum2 = trim($_POST['bnum2']);
		
		$v2 = htmlspecialchars($_POST['v2']);
		$v2 = stripslashes($_POST['v2']);
		$v2 = trim($_POST['v2']);
		
		$dg2 = htmlspecialchars($_POST['dg2']);
		$dg2 = stripslashes($_POST['dg2']);
		$dg2 = trim($_POST['dg2']);
		
		$dn2 = htmlspecialchars($_POST['dn2']);
		$dn2 = stripslashes($_POST['dn2']);
		$dn2 = trim($_POST['dn2']);
		
		$cm2 = htmlspecialchars($_POST['cm2']);
		$cm2 = stripslashes($_POST['cm2']);
		$cm2 = trim($_POST['cm2']);
		
		$bnum3 = htmlspecialchars($_POST['bnum3']);
		$bnum3 = stripslashes($_POST['bnum3']);
		$bnum3 = trim($_POST['bnum3']);
		
		$v3 = htmlspecialchars($_POST['v3']);
		$v3 = stripslashes($_POST['v3']);
		$v3 = trim($_POST['v3']);
		
		$dg3 = htmlspecialchars($_POST['dg3']);
		$dg3 = stripslashes($_POST['dg3']);
		$dg3 = trim($_POST['dg3']);
		
		$dn3 = htmlspecialchars($_POST['dn3']);
		$dn3 = stripslashes($_POST['dn3']);
		$dn3 = trim($_POST['dn3']);
		
		$cm3 = htmlspecialchars($_POST['cm3']);
		$cm3 = stripslashes($_POST['cm3']);
		$cm3 = trim($_POST['cm3']);
		
		$bnum4 = htmlspecialchars($_POST['bnum4']);
		$bnum4 = stripslashes($_POST['bnum4']);
		$bnum4 = trim($_POST['bnum4']);
		
		$v4 = htmlspecialchars($_POST['v4']);
		$v4 = stripslashes($_POST['v4']);
		$v4 = trim($_POST['v4']);
		
		$dg4 = htmlspecialchars($_POST['dg4']);
		$dg4 = stripslashes($_POST['dg4']);
		$dg4 = trim($_POST['dg4']);
		
		$dn4 = htmlspecialchars($_POST['dn4']);
		$dn4 = stripslashes($_POST['dn4']);
		$dn4 = trim($_POST['dn4']);
		
		$cm4 = htmlspecialchars($_POST['cm4']);
		$cm4 = stripslashes($_POST['cm4']);
		$cm4 = trim($_POST['cm4']);
		
		$bnum5 = htmlspecialchars($_POST['bnum5']);
		$bnum5 = stripslashes($_POST['bnum5']);
		$bnum5 = trim($_POST['bnum5']);
		
		$v5 = htmlspecialchars($_POST['v5']);
		$v5 = stripslashes($_POST['v5']);
		$v5 = trim($_POST['v5']);
		
		$dg5 = htmlspecialchars($_POST['dg5']);
		$dg5 = stripslashes($_POST['dg5']);
		$dg5 = trim($_POST['dg5']);
		
		$dn5 = htmlspecialchars($_POST['dn5']);
		$dn5 = stripslashes($_POST['dn5']);
		$dn5 = trim($_POST['dn5']);
		
		$cm5 = htmlspecialchars($_POST['cm5']);
		$cm5 = stripslashes($_POST['cm5']);
		$cm5 = trim($_POST['cm5']);
		
		$bnum6 = htmlspecialchars($_POST['bnum6']);
		$bnum6 = stripslashes($_POST['bnum6']);
		$bnum6 = trim($_POST['bnum6']);
		
		$v6 = htmlspecialchars($_POST['v6']);
		$v6 = stripslashes($_POST['v6']);
		$v6 = trim($_POST['v6']);
		
		$dg6 = htmlspecialchars($_POST['dg6']);
		$dg6 = stripslashes($_POST['dg6']);
		$dg6 = trim($_POST['dg6']);
		
		$dn6 = htmlspecialchars($_POST['dn6']);
		$dn6 = stripslashes($_POST['dn6']);
		$dn6 = trim($_POST['dn6']);
		
		$cm6 = htmlspecialchars($_POST['cm6']);
		$cm6 = stripslashes($_POST['cm6']);
		$cm6 = trim($_POST['cm6']);
		
		$bnum7 = htmlspecialchars($_POST['bnum7']);
		$bnum7 = stripslashes($_POST['bnum7']);
		$bnum7 = trim($_POST['bnum7']);
		
		$v7 = htmlspecialchars($_POST['v7']);
		$v7 = stripslashes($_POST['v7']);
		$v7 = trim($_POST['v7']);
		
		$dg7 = htmlspecialchars($_POST['dg7']);
		$dg7 = stripslashes($_POST['dg7']);
		$dg7 = trim($_POST['dg7']);
		
		$dn7 = htmlspecialchars($_POST['dn7']);
		$dn7 = stripslashes($_POST['dn7']);
		$dn7 = trim($_POST['dn7']);
		
		$cm7 = htmlspecialchars($_POST['cm7']);
		$cm7 = stripslashes($_POST['cm7']);
		$cm7 = trim($_POST['cm7']);
		
		$bnum8 = htmlspecialchars($_POST['bnum8']);
		$bnum8 = stripslashes($_POST['bnum8']);
		$bnum8 = trim($_POST['bnum8']);
		
		$v8 = htmlspecialchars($_POST['v8']);
		$v8 = stripslashes($_POST['v8']);
		$v8 = trim($_POST['v8']);
		
		$dg8 = htmlspecialchars($_POST['dg8']);
		$dg8 = stripslashes($_POST['dg8']);
		$dg8 = trim($_POST['dg8']);
		
		$dn8 = htmlspecialchars($_POST['dn8']);
		$dn8 = stripslashes($_POST['dn8']);
		$dn8 = trim($_POST['dn8']);
		
		$cm8 = htmlspecialchars($_POST['cm8']);
		$cm8 = stripslashes($_POST['cm8']);
		$cm8 = trim($_POST['cm8']);
		
		$bnum9 = htmlspecialchars($_POST['bnum9']);
		$bnum9 = stripslashes($_POST['bnum9']);
		$bnum9 = trim($_POST['bnum9']);
		
		$v9 = htmlspecialchars($_POST['v9']);
		$v9 = stripslashes($_POST['v9']);
		$v9 = trim($_POST['v9']);
		
		$dg9 = htmlspecialchars($_POST['dg9']);
		$dg9 = stripslashes($_POST['dg9']);
		$dg9 = trim($_POST['dg9']);
		
		$dn9 = htmlspecialchars($_POST['dn9']);
		$dn9 = stripslashes($_POST['dn9']);
		$dn9 = trim($_POST['dn9']);
		
		$cm9 = htmlspecialchars($_POST['cm9']);
		$cm9 = stripslashes($_POST['cm9']);
		$cm9 = trim($_POST['cm9']);
		
		$bnum10 = htmlspecialchars($_POST['bnum10']);
		$bnum10 = stripslashes($_POST['bnum10']);
		$bnum10 = trim($_POST['bnum10']);
		
		$v10 = htmlspecialchars($_POST['v10']);
		$v10 = stripslashes($_POST['v10']);
		$v10 = trim($_POST['v10']);
		
		$dg10 = htmlspecialchars($_POST['dg10']);
		$dg10 = stripslashes($_POST['dg10']);
		$dg10 = trim($_POST['dg10']);
		
		$dn10 = htmlspecialchars($_POST['dn10']);
		$dn10 = stripslashes($_POST['dn10']);
		$dn10 = trim($_POST['dn10']);
		
		$cm10 = htmlspecialchars($_POST['cm10']);
		$cm10 = stripslashes($_POST['cm10']);
		$cm10 = trim($_POST['cm10']);
		
		$bnum11 = htmlspecialchars($_POST['bnum11']);
		$bnum11 = stripslashes($_POST['bnum11']);
		$bnum11 = trim($_POST['bnum11']);
		
		$v11 = htmlspecialchars($_POST['v11']);
		$v11 = stripslashes($_POST['v11']);
		$v11 = trim($_POST['v11']);
		
		$dg11 = htmlspecialchars($_POST['dg11']);
		$dg11 = stripslashes($_POST['dg11']);
		$dg11 = trim($_POST['dg11']);
		
		$dn11 = htmlspecialchars($_POST['dn11']);
		$dn11 = stripslashes($_POST['dn11']);
		$dn11 = trim($_POST['dn11']);
		
		$cm11 = htmlspecialchars($_POST['cm11']);
		$cm11 = stripslashes($_POST['cm11']);
		$cm11 = trim($_POST['cm11']);
		
		$bnum12 = htmlspecialchars($_POST['bnum12']);
		$bnum12 = stripslashes($_POST['bnum12']);
		$bnum12 = trim($_POST['bnum12']);
		
		$v12 = htmlspecialchars($_POST['v12']);
		$v12 = stripslashes($_POST['v12']);
		$v12 = trim($_POST['v12']);
		
		$dg12 = htmlspecialchars($_POST['dg12']);
		$dg12 = stripslashes($_POST['dg12']);
		$dg12 = trim($_POST['dg12']);
		
		$dn12 = htmlspecialchars($_POST['dn12']);
		$dn12 = stripslashes($_POST['dn12']);
		$dn12 = trim($_POST['dn12']);
		
		$cm12 = htmlspecialchars($_POST['cm12']);
		$cm12 = stripslashes($_POST['cm12']);
		$cm12 = trim($_POST['cm12']);
		
		
		$bnum13 = htmlspecialchars($_POST['bnum13']);
		$bnum13 = stripslashes($_POST['bnum13']);
		$bnum13 = trim($_POST['bnum13']);
		
		$v13 = htmlspecialchars($_POST['v13']);
		$v13 = stripslashes($_POST['v13']);
		$v13 = trim($_POST['v13']);
		
		$dg13 = htmlspecialchars($_POST['dg13']);
		$dg13 = stripslashes($_POST['dg13']);
		$dg13 = trim($_POST['dg13']);
		
		$dn13 = htmlspecialchars($_POST['dn13']);
		$dn13 = stripslashes($_POST['dn13']);
		$dn13= trim($_POST['dn13']);
		
		$cm13 = htmlspecialchars($_POST['cm13']);
		$cm13 = stripslashes($_POST['cm13']);
		$cm13 = trim($_POST['cm13']);
		
		$bnum14 = htmlspecialchars($_POST['bnum14']);
		$bnum14 = stripslashes($_POST['bnum14']);
		$bnum14 = trim($_POST['bnum14']);
		
		$v14 = htmlspecialchars($_POST['v14']);
		$v14 = stripslashes($_POST['v14']);
		$v14 = trim($_POST['v14']);
		
		$dg14 = htmlspecialchars($_POST['dg14']);
		$dg14 = stripslashes($_POST['dg14']);
		$dg14 = trim($_POST['dg14']);
		
		$dn14 = htmlspecialchars($_POST['dn14']);
		$dn14 = stripslashes($_POST['dn14']);
		$dn14 = trim($_POST['dn14']);
		
		$cm14 = htmlspecialchars($_POST['cm14']);
		$cm14 = stripslashes($_POST['cm14']);
		$cm14= trim($_POST['cm14']);
		
		$bnum15 = htmlspecialchars($_POST['bnum15']);
		$bnum15 = stripslashes($_POST['bnum15']);
		$bnum15 = trim($_POST['bnum15']);
		
		$v15 = htmlspecialchars($_POST['v15']);
		$v15 = stripslashes($_POST['v15']);
		$v15 = trim($_POST['v15']);
		
		$dg15 = htmlspecialchars($_POST['dg15']);
		$dg15 = stripslashes($_POST['dg15']);
		$dg15 = trim($_POST['dg15']);
		
		$dn15 = htmlspecialchars($_POST['dn15']);
		$dn15 = stripslashes($_POST['dn15']);
		$dn15 = trim($_POST['dn15']);
		
		$cm15 = htmlspecialchars($_POST['cm15']);
		$cm15 = stripslashes($_POST['cm15']);
		$cm15 = trim($_POST['cm15']);
		
		$bnum16 = htmlspecialchars($_POST['bnum16']);
		$bnum16 = stripslashes($_POST['bnum16']);
		$bnum16 = trim($_POST['bnum16']);
		
		$v16 = htmlspecialchars($_POST['v16']);
		$v16 = stripslashes($_POST['v16']);
		$v16 = trim($_POST['v16']);
		
		$dg16 = htmlspecialchars($_POST['dg16']);
		$dg16 = stripslashes($_POST['dg16']);
		$dg16 = trim($_POST['dg16']);
		
		$dn16 = htmlspecialchars($_POST['dn16']);
		$dn16 = stripslashes($_POST['dn16']);
		$dn16 = trim($_POST['dn16']);
		
		$cm16 = htmlspecialchars($_POST['cm16']);
		$cm16 = stripslashes($_POST['cm16']);
		$cm16 = trim($_POST['cm16']);
		
		$bnum17 = htmlspecialchars($_POST['bnum17']);
		$bnum17 = stripslashes($_POST['bnum17']);
		$bnum17  = trim($_POST['bnum17']);
		
		$v17 = htmlspecialchars($_POST['v17']);
		$v17 = stripslashes($_POST['v17']);
		$v17 = trim($_POST['v17']);
		
		$dg17 = htmlspecialchars($_POST['dg17']);
		$dg17 = stripslashes($_POST['dg17']);
		$dg17 = trim($_POST['dg17']);
		
		$dn17 = htmlspecialchars($_POST['dn17']);
		$dn17 = stripslashes($_POST['dn17']);
		$dn17 = trim($_POST['dn17']);
		
		$cm17 = htmlspecialchars($_POST['cm17']);
		$cm17 = stripslashes($_POST['cm17']);
		$cm17 = trim($_POST['cm17']);
		
		$bnum18 = htmlspecialchars($_POST['bnum18']);
		$bnum18 = stripslashes($_POST['bnum18']);
		$bnum18 = trim($_POST['bnum18']);
		
		$v18 = htmlspecialchars($_POST['v18']);
		$v18 = stripslashes($_POST['v18']);
		$v18 = trim($_POST['v18']);
		
		$dg18 = htmlspecialchars($_POST['dg18']);
		$dg18 = stripslashes($_POST['dg18']);
		$dg18 = trim($_POST['dg18']);
		
		$dn18 = htmlspecialchars($_POST['dn18']);
		$dn18 = stripslashes($_POST['dn18']);
		$dn18 = trim($_POST['dn18']);
		
		$cm18 = htmlspecialchars($_POST['cm18']);
		$cm18 = stripslashes($_POST['cm18']);
		$cm18 = trim($_POST['cm18']);
		
		$bnum19 = htmlspecialchars($_POST['bnum19']);
		$bnum19 = stripslashes($_POST['bnum19']);
		$bnum19 = trim($_POST['bnum19']);
		
		$v19 = htmlspecialchars($_POST['v19']);
		$v19 = stripslashes($_POST['v19']);
		$v19 = trim($_POST['v19']);
		
		$dg19 = htmlspecialchars($_POST['dg19']);
		$dg19 = stripslashes($_POST['dg19']);
		$dg19 = trim($_POST['dg19']);
		
		$dn19 = htmlspecialchars($_POST['dn19']);
		$dn19 = stripslashes($_POST['dn19']);
		$dn19 = trim($_POST['dn19']);
		
		$cm19 = htmlspecialchars($_POST['cm19']);
		$cm19 = stripslashes($_POST['cm19']);
		$cm19 = trim($_POST['cm19']);
		
		$bnum20 = htmlspecialchars($_POST['bnum20']);
		$bnum20 = stripslashes($_POST['bnum20']);
		$bnum20 = trim($_POST['bnum20']);
		
		$v20 = htmlspecialchars($_POST['v20']);
		$v20 = stripslashes($_POST['v20']);
		$v20 = trim($_POST['v20']);
		
		$dg20 = htmlspecialchars($_POST['dg20']);
		$dg20 = stripslashes($_POST['dg20']);
		$dg20 = trim($_POST['dg20']);
		
		$dn20 = htmlspecialchars($_POST['dn20']);
		$dn20 = stripslashes($_POST['dn20']);
		$dn20 = trim($_POST['dn20']);
		
		$cm20 = htmlspecialchars($_POST['cm20']);
		$cm20 = stripslashes($_POST['cm1']);
		$cm20 = trim($_POST['cm20']);
		
		$bnum21 = htmlspecialchars($_POST['bnum21']);
		$bnum21 = stripslashes($_POST['bnum21']);
		$bnum21 = trim($_POST['bnum21']);
		
		$v21 = htmlspecialchars($_POST['v21']);
		$v21 = stripslashes($_POST['v21']);
		$v21 = trim($_POST['v21']);
		
		$dg21 = htmlspecialchars($_POST['dg21']);
		$dg21 = stripslashes($_POST['dg21']);
		$dg21 = trim($_POST['dg21']);
		
		$dn21 = htmlspecialchars($_POST['dn21']);
		$dn21 = stripslashes($_POST['dn21']);
		$dn21 = trim($_POST['dn21']);
		
		$cm21 = htmlspecialchars($_POST['cm21']);
		$cm21 = stripslashes($_POST['cm21']);
		$cm21 = trim($_POST['cm21']);
		
		$d_year = $_POST['d_year'];
		$complaint = $_POST['complaint'];
		$types = $_POST['types'];
		$manag = $_POST['manag'];
		$mainArray = [
			$d_year, 
			$complaint, 
			$types, 
			$manag
		];
		
		//all of thsi is done so each line of array will be for one line of input fields
		foreach( $d_year as $key => $n ) {
			$DataArr2[] = ($n." ".$complaint[$key]." ".$types[$key]." ".$manag[$key]);
		}
		
		$error = '';

		$validator = new FormValidator();
		
		
		if($validator->ValidateForm()){
		
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_ante($name, $pos, $sex, $dob, $house_num, $town, $village, $ward, $state, $lga, $mother_name,$mother_phone,$father_name, $father_phone, $cg, $cg_phone,
		$DataArr, $weigh, $twin, $fed, $support, $underweight, $exta_care, $bnum1, $v1, $dg1, $dn1, $cm1,$bnum2, $v2, $dg2, $dn2, $cm2,
		$bnum3, $v3, $dg3, $dn3, $cm3,$bnum4, $v4, $dg4, $dn4, $cm4, $bnum5, $v5, $dg5, $dn5, $cm5, $bnum6, $v6, $dg6, $dn6, $cm6, $bnum7, $v7, $dg7, $dn7, $cm7,
		$bnum8, $v8, $dg8, $dn8, $cm8, $bnum9, $v9, $dg9, $dn9, $cm9, $bnum10, $v10, $dg10, $dn10, $cm10, $bnum11, $v11, $dg11, $dn11, $cm11,
		$bnum12, $v12, $dg12, $dn12, $cm12, $bnum13, $v13, $dg13, $dn13, $cm13, $bnum15, $v15, $dg15, $dn15, $cm15, $bnum16, $v16, $dg16, $dn16, $cm16,
		$bnum17, $v17, $dg17, $dn17, $cm17, $bnum18, $v18, $dg18, $dn18, $cm18, $bnum19, $v19, $dg19, $dn19, $cm19, $bnum20, $v20, $dg20, $dn20, $cm20,
		$bnum21, $v21, $dg21, $dn21, $cm21,$DataArr2);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Immunization entered successfully
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newExpi(){
		$date_a = ucfirst(htmlspecialchars($_POST['date_a']));
		$date_a = ucfirst(stripslashes($_POST['date_a']));
		$date_a = ucfirst(trim($_POST['date_a']));
		
		$code = ucfirst(htmlspecialchars($_POST['code']));
		$code = ucfirst(stripslashes($_POST['code']));
		$code = ucfirst(trim($_POST['code']));
		
		$description = lcfirst(htmlspecialchars($_POST['description']));
		$description = lcfirst(stripslashes($_POST['description']));
		$description = lcfirst(trim($_POST['description']));
		
		$approver = lcfirst(htmlspecialchars($_POST['approver']));
		$approver = lcfirst(stripslashes($_POST['approver']));
		$approver = lcfirst(trim($_POST['approver']));
		
		$recipient = htmlspecialchars($_POST['recipient']);
		$recipient = stripslashes($_POST['recipient']);
		$recipient = trim($_POST['recipient']);
		
		$qty = htmlspecialchars($_POST['qty']);
		$qty = stripslashes($_POST['qty']);
		$qty = trim($_POST['qty']);
		
		$amt = htmlspecialchars($_POST['amt']);
		$amt = stripslashes($_POST['amt']);
		$amt = trim($_POST['amt']);
		
		$cash = htmlspecialchars($_POST['cash']);
		$cash = stripslashes($_POST['cash']);
		$cash = trim($_POST['cash']);
		
		$comment = htmlspecialchars($_POST['comment']);
		$comment = stripslashes($_POST['comment']);
		$comment = trim($_POST['comment']);
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("date_a","req","Please fill in date");
		$validator->addValidation("code","req","Please fill in code");
		$validator->addValidation("description","req","Please fill in description");
		$validator->addValidation("approver","req","Please fill in approver");
		$validator->addValidation("recipient","req","Please fill in recipient");
		$validator->addValidation("qty","req","Please fill in qty");
		$validator->addValidation("amt","req","Please fill in amt");
		$validator->addValidation("cash","req","Please fill in cash");
									
		if($validator->ValidateForm()){
						
			if (EMPTY($qty)) {
				$error ='Qty cannot be empty';
			}
			
			if (EMPTY($amt)) {
				$error ='Amount cannot be empty';
			}

			if (EMPTY($recipient)) {
				$error ='Recipient cannot be empty';
			}

			if (EMPTY($approver)) {
				$error ='Approver cannot be empty';
			}
			
			if (EMPTY($description)) {
				$error ='Description cannot be empty';
			}
			
			if (EMPTY($code)) {
				$error ='Code cannot be empty';
			}
			
			if (EMPTY($date_a)) {
				$error ='Date cannot be empty';
			}
										
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_expi($date_a, $code, $description, $approver, $recipient, $qty, $amt, $cash, $comment);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
						Expense added successfully					
					</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newCBal(){
		$c_date = ucfirst(htmlspecialchars($_POST['c_date']));
		$c_date = ucfirst(stripslashes($_POST['c_date']));
		$c_date = ucfirst(trim($_POST['c_date']));
		
		$type = ucfirst(htmlspecialchars($_POST['type']));
		$type = ucfirst(stripslashes($_POST['type']));
		$type = ucfirst(trim($_POST['type']));
		
		$description = lcfirst(htmlspecialchars($_POST['description']));
		$description = lcfirst(stripslashes($_POST['description']));
		$description = lcfirst(trim($_POST['description']));
		
		$amt = htmlspecialchars($_POST['amt']);
		$amt = stripslashes($_POST['amt']);
		$amt = trim($_POST['amt']);
		
		$cash = htmlspecialchars($_POST['cash']);
		$cash = stripslashes($_POST['cash']);
		$cash = trim($_POST['cash']);
		
		$comment = htmlspecialchars($_POST['comment']);
		$comment = stripslashes($_POST['comment']);
		$comment = trim($_POST['comment']);
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("c_date","req","Please fill in date");
		$validator->addValidation("amt","req","Please fill in amt");
		$validator->addValidation("cash","req","Please fill in cash");
		$validator->addValidation("type","req","Please fill in type");
									
		if($validator->ValidateForm()){
			
			if (EMPTY($type)) {
				$error ='Type cannot be empty';
			}
			
			if (EMPTY($amt)) {
				$error ='Amount cannot be empty';
			}

						
			if (EMPTY($c_date)) {
				$error ='Date cannot be empty';
			}
										
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_c_bal($c_date, $description, $amt, $cash, $comment, $type);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
						Balance added successfully					
					</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function newMonth(){
		$from_date = ucfirst(htmlspecialchars($_POST['from_date']));
		$from_date = ucfirst(stripslashes($_POST['from_date']));
		$from_date = ucfirst(trim($_POST['from_date']));
		
		$to_date = ucfirst(htmlspecialchars($_POST['to_date']));
		$to_date = ucfirst(stripslashes($_POST['to_date']));
		$to_date = ucfirst(trim($_POST['to_date']));
		
		$error = '';
		
		$val = $_POST['val'];

		$validator = new FormValidator();
						
		$validator->addValidation("from_date","req","Please fill in from date");
		$validator->addValidation("to_date","req","Please fill in to date");
								
		if($validator->ValidateForm()){
			
			if (EMPTY($from_date)) {
				$error ='From date cannot be empty';
			}
										
			if (EMPTY($to_date)) {
				$error ='To date cannot be empty';
			}
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->month_date($from_date, $to_date, $val);
				
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
		
	function newTestResTemp(){
		
		$error = '';
		
		$name = ucfirst(htmlspecialchars($_POST['name']));
		$name = ucfirst(stripslashes($_POST['name']));
		$name = ucfirst(trim($_POST['name']));
		
		
		$fields = $_POST['fieldss'];
		
		$mainArray = [
			$fields
		];
		//all of thsi is done so each line of array will be for one line of input fields
		foreach( $fields as $key => $n ) {
			$DataArr[] = ($n." ".$fields[$key]);
		}

		$validator = new FormValidator();
									
		if($validator->ValidateForm()){
			

			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->insert_lab_temp($DataArr,$name);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Successful					
						</div>';
				} else {
					echo $insert;
				}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	
	function getFields(){		
		$error = '';
		
		$temp = $_POST['temp'];
		$temp = $_POST['temp'];
		$temp = $_POST['temp'];

		$validator = new FormValidator();
						
							
		if($validator->ValidateForm()){																	
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			} else{
				$insert = Database::getInstance()->get_fields($temp);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
						Gift Card Form added						
					</div>';
				} else {
					echo $insert;
				}
			}

		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function insertRes(){	

		$test = $_POST['test'];

		$id = $_POST['id'];

		$p_id = $_POST['app_id'];

		$temp = $_POST['temp']; 
		$error = '';
		
		$cardDets = Database::getInstance()->select_from_where2('lab_temps', 'label_id', $test);	
		foreach($cardDets as $row):
			$name = $row['temp_name'];
			$name_id = $row['id'];
			$value = $_POST[$name]; 
			$insert = Database::getInstance()->insert_ress($p_id, $test, $id, $value, $temp, $name_id);	
		endforeach;	
		if($insert === 'Done'){
			echo '<div class="alert alert-success">
				Result Entered
			</div>';
		} else {
			echo $insert;
		}
	}

	function insertXray_Res(){	

		$test = $_POST['test'];

		$id = $_POST['id'];

		$p_id = $_POST['p_id'];

		$temp = $_POST['temp'];
		
		$error = '';
		
		$cardDets = Database::getInstance()->select_from_where2('xray', 'id', $temp);	
		foreach($cardDets as $row):
			$name = $row['name'];
			$name_id = $row['id'];
			$value = !empty($_POST[$name]);
			
			$insert = Database::getInstance()->insert_xray_ress($p_id, $test, $id, $value, $temp, $name_id);	
		endforeach;	
		if($insert === 'Done'){
			echo '<div class="alert alert-success">
				Result Entered
			</div>';
		} else {
			echo $insert;
		}
	}
	
	function changeAdmiStatus(){			
		$status = htmlspecialchars($_POST['selected']);
		$status = stripslashes($_POST['selected']);
		$status = $_POST['selected'];
	
		$app_id = $_POST['app_id'];
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("selected","req","Please pick a status");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->change_admi_status($status, $app_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Status updated
						</div>';
						
						?>	
			<script>
			
			location.reload();
			</script>
								
								<?php
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	function changePrescriptionStatus(){			
		$status = htmlspecialchars($_POST['selected']);
		$status = stripslashes($_POST['selected']);
		$status = $_POST['selected'];
		
		$pre_id = $_POST['pre_id'];
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("selected","req","Please pick a status");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->change_prescription_status($status, $pre_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Status updated
						</div>';
			?>	
			<script>
			
			location.reload();
			</script>
								
								<?php
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
		
	}
	
	
	
	function requestExam(){
	
		
		$val = trim($_POST['val']);
		$doc = $_POST['doc_id'];
		$p_id = $_POST['p_id'];
		$ward = $_POST['ward'];
		
		$validator = new FormValidator();
						
		$validator->addValidation("doc_id","req","Please Login");
		$validator->addValidation("val","req","Please select Appointment");
							
		if($validator->ValidateForm()){
			

				echo $insert = Database::getInstance()->insert_exam_request($val,$doc, $p_id,$ward);
				//$notify = Database::getInstance()->notify_nurse3($val,$doc,$p_id); 
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> ' . $inp_err . '
				</div>';
			}
		}
	}
	
	function changeExamStatus(){			
		$status = htmlspecialchars($_POST['selected']);
		$status = stripslashes($_POST['selected']);
		$status = $_POST['selected'];
		
		$app_id = $_POST['app_id'];
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("selected","req","Please pick a status");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->change_exam_status($status, $app_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Status updated
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	function changeAppStatus(){			
		$status = htmlspecialchars($_POST['selected']);
		$status = stripslashes($_POST['selected']);
		$status = $_POST['selected'];
		
		$staff_id = $_POST['staff_id'];
		
		$error = '';

		$validator = new FormValidator();
						
		$validator->addValidation("selected","req","Please pick a status");
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->change_staff_status($status, $staff_id);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Status updated
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
	
	
	function add_more_fields(){			
		
		$val = $_POST['val'];
		
		$error = '';
		$fieldsst = $_POST['fieldsst'];
		$mainArray = [
			$fieldsst
		];
		//all of thsi is done so each line of array will be for one line of input fields
		foreach( $fieldsst as $key => $n ) {
			$DataArr[] = ($n." ".$fieldsst[$key]);
		}

		$validator = new FormValidator();
		
		if($validator->ValidateForm()){
			
			
			if($error){
				echo '<div class="alert alert-danger">
					<strong>Warning!</strong> '. $error .' 
				</div>';
			}else{
				$insert = Database::getInstance()->add_fields($val, $DataArr);
				if($insert == 'Done'){
					echo '<div class="alert alert-success">
							Fields added
						</div>';
					}else{
						echo $insert;
					}
			}
		} else {
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err){
				echo '<div class="alert alert-danger">
					' . $inp_err . '
				</div>';
			}
		}
	}
?>