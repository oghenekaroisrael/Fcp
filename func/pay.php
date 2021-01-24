<?php 
	$db = mysqli_connect("localhost","root","","greenhousehms");
	if (isset($_GET['val']) AND !empty($_GET['val'])) {
		$val = $_GET['val'];
		$oid = $_GET['oid'];
if ($_GET['ins'] === "company_bill") {
			$stat1 = mysqli_query($db, "UPDATE accounts SET payment_status = 3, company_id = ".$_POST['company'].",amount = ".$_GET['amount'].", date_paid = NOW() WHERE order_id = '".$oid."'");
			$stat2 = mysqli_query($db, "UPDATE payment SET payment_status = 3, pdate_added = NOW() WHERE reference = '".$oid."'");
			$stat3 = mysqli_query($db, "UPDATE prescription SET status = 3, pdate_added = NOW() WHERE reference = '".$oid."'");

			if ($stat1 AND $stat2 AND $stat3) {
				$select = mysqli_query($db, "SELECT * FROM accounts WHERE order_id = '".$oid."'");
				while ($it = mysqli_fetch_array($select)) {
					$update = mysqli_query($db, "INSERT INTO `company_bill`(`order_id`,`items`, `amount`, `company_id`, `patient_id`, `position`, `date_added`) VALUES('".$oid."','".$it['item']."',".$_GET['amount'].",".$_POST['company'].",".$val.",'".$_POST['position']."',NOW())");
					if ($update) {
						header("location: ../module1/payment_daily.php");
					}
				}
			}else{
				return false;
			}
		}

if ($_GET['ins'] === "company_bill2") {
			$stat1 = mysqli_query($db, "UPDATE accounts SET payment_status = 3, company_id = ".$_POST['company'].",amount = ".$_GET['amount'].", date_paid = NOW() WHERE order_id = '".$oid."'");
			$stat2 = mysqli_query($db, "UPDATE payment SET payment_status = 3, pdate_added = NOW() WHERE reference = '".$oid."'");
			$stat3 = mysqli_query($db, "UPDATE prescription SET status = 3, pdate_added = NOW() WHERE reference = '".$oid."'");

			if ($stat1 AND $stat2 AND $stat3) {
				$select = mysqli_query($db, "SELECT * FROM accounts WHERE order_id = '".$oid."'");
				while ($it = mysqli_fetch_array($select)) {
					$update = mysqli_query($db, "INSERT INTO `company_bill`(`order_id`,`items`, `amount`, `company_id`, `patient_id`, `position`, `date_added`) VALUES('".$oid."','".$it['item']."',".$_GET['amount'].",".$_POST['company'].",".$val.",'".$_POST['position']."',NOW())");
					if ($update) {
						header("location: ../module5/index.php");
					}
				}
			}else{
				return false;
			}
		}
	}
	
?>