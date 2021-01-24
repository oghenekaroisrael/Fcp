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
  $uid = $_SESSION['userSession'];
  $bid = $_GET['bid'];
  $dur = 
  $dept = intval(Database::getInstance()->get_name_from_id("department_id","bulletin","bulletinID",$bid));
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
                <h2 class="card-title">Courses</h2>
                <H4>Department: <?php echo ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept)); ?> </H4>
              
                <a href="newCourse.php?bid=<?php echo $bid; ?>" class="btn btn-primary pull-right">New Course</a>
              </div>
              <?php 
                $lvl = Database::getInstance()->get_name_from_id("duration","departments","department_id",$dept)*100;
              
                for ($level=100; $level < $lvl; $level+=100) { 
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
                                  <th></th>
                                  <th></th>
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
                                        <th class="text-center" colspan="2"><?php echo ucwords($ctName); ?></th>
                                        <th class="text-center">1<sup>st</sup></th>
                                        <th class="text-center">2<sup>nd</sup></th>
                                        <th></th>
                                        <th></th>
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
                                            }
                                          ?>
                                          <td class="text-center">
                                            <a href="updateCourse.php?id=<?php echo $course['courseID']; ?>" class="btn btn-link"><i class="fas fa-edit text-white"></i></a>
                                          </td>
                                          <td class="text-center">
                                          <a onclick="sure(<?php echo $course['courseID']; ?>,`<?php echo $course['title']; ?>`)" class="btn btn-link"><i class="fas fa-trash text-white"></i></a>
                                          </td>
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
  </div>
<?php include_once("inc/footer.php"); ?>
<script type="text/javascript">
	var s=jQuery .noConflict();
  
		function sure(ID,name){ 

        	s.notify({
            	icon: 'pe-7s-trash',
            	message: "Are you sure you want to delete <b>"+name+"</b> From Courses? </br><button type='button' class='btn pop-btn' onclick='delet("+ID+")'>Yes</button>"

            },{
                type: 'danger',
                timer: 100000
            });

      }
		
		function delet(ID){ 
		var val = ID;
          s.ajax({
            type: 'post',
            url: '../func/del.php',
            data: "val=" + val +  '&ins=delCourse',
             success: function(data)
            {
				if (data === 'Done') {
					console.log(data);
            window.location = 'view_courses.php?bid=<?php echo $bid; ?>';
				  }
				  else {
					   
						jQuery('#get_det'+ID).html(data).show();
				  }
            }
          });
		}

    </script>