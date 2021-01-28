<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "dashboard";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $matNumber = $_SESSION['userSession'];
  Database::getInstance()->determineBulletin($matNumber);
}
?>
<body class="">
<div class="wrapper">
    <?php include_once("inc/sidebar.php"); ?>
    <div class="main-panel">
      <?php include_once("inc/navbar.php"); ?>
      <div class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-chart">
              <div class="card-header ">
                <div class="row">
                  <div class="col-sm-6 text-left">
                    <h2 class="card-title">Personal Details</h2>
                    <?php
                          $userDetails = Database::getInstance()->select_from_where2('students', 'matNo', $matNumber);
														foreach($userDetails as $row):
                              $fname = $row['firstName'];	
                              $lname = $row['lastName'];
                              $mname = $row['middleName'];
                              $dept = $row['department_id'];
                              $lvl = $row['level'];
                              $bulletin = $row['bulletin'];
                            endforeach; 
                            
                            $bull = Database::getInstance()->select_from_where2('bulletin', 'bulletinID', $bulletin);
														foreach($bull as $row):
                              $syear = $row['startingYear'];	
                              $eyear =  $row['endingYear'];
														endforeach; 
                    ?>
                    <div class="typography-line">
                      <h4><span>Fullname: </span> <?php echo ucfirst($lname);?> <?php echo ucfirst($mname);?> <?php echo ucfirst($fname);?></h4>
                    </div>
                    <div class="typography-line">
                      <h4><span>Level: </span> <?php echo $lvl;?> </h4>
                    </div>
                    <div class="typography-line">
                      <h4><span>Matric Number: </span> <?php echo $matNumber;?> </h4>
                    </div>
                    <div class="typography-line">
                      <h4><span>Department: </span> <?php echo Database::getInstance()->get_name_from_id("name","departments","department_id",$dept);?> </h4>
                    </div>
                    <div class="typography-line">
                      <h4><span>Bulletin Used: </span> <?php echo $syear." - ".$eyear; ?> </h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
              <div class="row">
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
              <div class="card card-stats bg-info">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    Course Units
                  </div>
                  <p class="card-category">Stat</p>
                  <h3 class="card-title">49/169
                    <small>Units</small>
                  </h3>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
              <div class="card card-stats bg-info">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    Years
                  </div>
                  <p class="card-category">Stat</p>
                  <h3 class="card-title">3/4
                    <small>Years</small>
                  </h3>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
              <div class="card card-stats bg-info">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    Semesters
                  </div>
                  <p class="card-category">Stat</p>
                  <h3 class="card-title">5/8
                    <small>Semesters</small>
                  </h3>
                </div>
              </div>
            </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once("inc/footer.php"); ?>