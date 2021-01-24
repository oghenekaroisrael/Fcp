<?php
	include('../inc/db.php');

	$functionto = $_POST['ins'];

	switch ($functionto) {
		
		
		case "delUser":
        delUser();
		break;

		case "delBulletin":
        delBulletin();
		break;

		case 'delCourse':
			delCourse();
			break;

		case "delInpatient_all":
		delInpatient_all();
		break;

		case "deltherapyPlan":
        deltherapyPlan();
		break;

		
		case "delappt":
        delappt();
		break;
		
		case "delPatientVital":
        delPatientVital();
		break;
		
		case "delTestType":
        delTestType();
		break;

		case "delCategory":
        delCategory();
		break;
		
		case "delTest":
        delTest();
		break;
		
		case "delExtraFile":
        delExtraFile();
		break;

		case "delXrayFile":
        delXrayFile();
		break;
		
		case "delIPDFood":
        delIPDFood();
		break;
		
		case "delObs":
        delObs();
		break;
		
		case "delDis":
        delDis();
		break;
		
		case "delFluid":
        delFluid();
		break;
		
		case "delSurgery":
        delSurgery();
		break;
		
		case "delDoc":
        delDoc();
		break;
		
		case "delDocSche":
        delDoc();
		break;

		case "delCat":
        delCat();
		break;
		
		case "delStock":
        delStock();
		break;

		case "delUnits":
        delUnits();
		break;

		case "delCard":
        delCard();
		break;
		
		case "delDuty":
        delDuty();
		break;
		
		case "delInpatientDet":
        delInpatientDet();
		break;

		case "delExp":
        delExp();
		break;

		case "delBall":
        delBall();
		break;

		case "delCompany":
        delCompany();
		break;

		case "delExamReq":
        delExamReq();
		break;
		
		case "delAdminReq":
        delAdminReq();
		break;
		
		case "delTempp":
        delTempp();
		break;

		case "del_lab_test_names":
        del_lab_test_names();
		break;

		default:
			echo '<div class="alert alert-danger">
				Function does not Exist
			</div>';
	}

	function delUser(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('staff','user_id',$value);
		echo"Done";
	}
	
	function delBulletin(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('bulletin','bulletinID',$value);
		echo"Done";
	}

	function delCourse(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('courses','courseID',$value);
		echo"Done";
	}

	
	function delExtraFile(){
		$value = $_POST['val'];
		$file = database::getInstance()->get_name_from_id('extra_file','extra_file','extra_file_id',$value);
		unlink('../extrafile/'.$file);
		database::getInstance()->delete_things('extra_file','extra_file_id',$value);
		echo"Done";
	}

	function delXrayFile(){
		$value = $_POST['val'];
		$file = database::getInstance()->get_name_from_id('file','patient_xray_result','id',$value);
		unlink('../extrafile/'.$file);
		database::getInstance()->delete_things('patient_xray_result','id',$value);
		echo"Done";
	}
	
	function delPatient(){
		$value = $_POST['val'];
		$file = database::getInstance()->get_name_from_id('photo','patients','id',$value);
		unlink('../photo/'.$file);
		database::getInstance()->delete_things('patients','id',$value);
		echo"Done";
	}

	function delInpatient_all(){
		$value = $_POST['val'];
		$main = database::getInstance()->delete_things_where2('accounts','app_id',$value,'item',9);
		if ($main == 'Done') {
			$first = database::getInstance()->delete_things('`in-patients`','app_id',$value);
		}else{
			$first = database::getInstance()->delete_things('`in-patients`','app_id',$value);
		}
		echo"Done";
	}

	function deltherapyPlan(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('therapy_plans','id',$value);
		echo"Done";
	}

	function delappt(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('patient_appointment','id',$value);
		echo"Done";
	}

	function delTestType(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('lab_test_type','lab_test_type_id',$value);
		echo"Done";
	}

	function delCategory(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('xray_types','xray_cat_id',$value);
		echo"Done";
	}
	
	function delTest(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('lab_test','lab_test_id',$value);
		echo"Done";
	}
	
	function delPatientVital(){
		$value = $_POST['val'];
		$bpsis = ""; $bpsid = ""; $bpsts = ""; $bpstd = ""; $pulse = ""; $sug = ""; $history = ""; $temp = ""; $weight = "";$height = "";$bmi = "";$spo2 = "";$allergies="";$rbp = "";$complaints="";$resp = "";$sixt = "";
		database::getInstance()->edit_vitals($bpsis, $bpsid, $bpsts, $bpstd, $pulse, $sug, $history, $temp,$height,$resp,$complaints,$rsp,$allergies, $weight,$sixt,$value);
		echo"Done";
	}
	
	function delInpatientDet(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('`in-patients`','id',$value);
		echo"Done";
	}

	function delDoc(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('doctors','id',$value);
		echo"Done";
	}
	
	function delIPDFood(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('ipd_food','ipd_food_id',$value);
		echo"Done";
	}
	
	function delObs(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('patient_obs','patient_obs_id',$value);
		echo"Done";
	}
	
	function delDis(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('dispensing_chart','dispensing_chart_id',$value);
		echo"Done";
	}
	
	function delFluid(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('patient_fluid','patient_fluid_id',$value);
		echo"Done";
	}

	function delSurgery(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('surgery_perm','surgery_perm_id',$value);
		echo"Done";
	}
	
	function delDocSche(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('doctor_schedule','id',$value);
		echo"Done";
	}

	function delCat(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('pharm_category','id',$value);
		echo"Done";
	}

	function delStock(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('pharm_stock','id',$value);
		echo"Done";
	}

	function delUnits(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('pharm_units','id',$value);
		echo"Done";
	}
	
	function delCard(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('card_types','id',$value);
		echo"Done";
	}
	
	function delDuty(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('duty_check','id',$value);
		echo"Done";
	}
	
	function delExp(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('daily_expense','id',$value);
		echo"Done";
	}
	
	function delBall(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('credit_balance','id',$value);
		echo"Done";
	}
	function delCompany(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('companies','id',$value);
		database::getInstance()->delete_things('accounts','company_id',$value);
		database::getInstance()->delete_things('payment','company_id',$value);
		database::getInstance()->delete_things('prescription','company_id',$value);
		database::getInstance()->delete_things('company_bill','company_id',$value);

		echo"Done";
	}
	
	
	function delExamReq(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('exam_request','id',$value);
		echo"Done";
	}
	
	function delAdminReq(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('admission_request','admission_request_id',$value);
		echo"Done";
	}
	
	function delTempp(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('lab_temps','id',$value);
		echo"Done";
	}
	
	
	function del_lab_test_names(){
		$value = $_POST['val'];
		database::getInstance()->delete_things('lab_temp_name','id',$value);
		echo"Done";
	}
?>