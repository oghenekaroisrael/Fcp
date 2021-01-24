<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "courses";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $matNumber = $_SESSION['userSession'];
}
?>
<body class="">
<div class="wrapper">
    <?php include_once("inc/sidebar.php"); ?>
    <div class="main-panel">
      <?php include_once("inc/navbar.php"); ?>
      <div class="content">
        <div class="row">
        <?php
            $userDetails = Database::getInstance()->select_from_where2('students', 'matNo', $matNumber);
								foreach($userDetails as $row):
                  $dept = $row['department_id'];
                  $dur = intval(Database::getInstance()->get_name_from_id("duration","departments","department_id",$dept))*100;
                  $lvl = $row['level'];
                endforeach; 
            ?>
          <div class="col-12">
            <div class="card card-chart">
              <div class="card-header ">
                <h2 class="card-title">My Courses</h2>
                <H4><?php echo ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept)); ?></H4>
              </div>
              <div class="card-body">
                <?php
                    for ($level=100; $level <= $lvl+100; $level+=100) { 
                      ?>
                        <div class="container-fluid">
                  <h3 class="title text-center"><?php echo $level; ?> Level Courses</h3>
                  <div class="table-responsive">
                    <table class="table tablesorter " id="">
                      <thead class=" text-primary">
                        <tr>
                          <th class="text-center">
                            Course Code
                          </th>
                          <th class="text-center">
                            Course Title
                          </th>
                          <th class="text-center">
                            Semester
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $courseTypes = Database::getInstance()->select('courseTypes');
                            foreach($courseTypes as $ctype):
                              $ctID = $ctype['ctypeID'];
                              $ctName = $ctype['name'];
                              ?>
                              <tr class="text-primary">
                                <th></th>
                                <th class="text-center"><?php echo ucwords($ctName); ?></th>
                                <th class="text-center">1<sup>st</sup></th>
                                <th class="text-center">2<sup>nd</sup></th>
                              </tr>
                              <?php

                              $courses = Database::getInstance()->select_from_where_amt_no3("courses","department",$dept,"level",$level,"courseType",$ctID);
                              foreach ($courses as $course) {
                                ?>
                                <tr>
                                  <td class="text-center">
                                    <?php echo($course['code']); ?>
                                  </td>
                                  <td class="text-center">
                                  <?php echo($course['title']); ?>
                                  </td>
                                  <?php 
                                    if ($course['semester'] == 1) {
                                      ?>
                                        <td class="text-center">
                                          <?php echo($course['unit']); ?>
                                        </td>
                                        <td class="text-center">
                                          -
                                        </td>
                                      <?php
                                    }else if ($course['semester'] == 2) {
                                      ?>
                                        <td class="text-center">
                                          -
                                        </td>
                                        <td class="text-center" >
                                        <?php echo($course['unit']); ?>
                                        </td>
                                      <?php
                                    }{}
                                  ?>
                                </tr>
                                <?php
                              }
                            endforeach; 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                      <?php
                    }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include_once("inc/footer.php"); ?>