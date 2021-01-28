<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "transcript";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $matNumber = $_SESSION['userSession'];
  $deptID = Database::getInstance()->get_name_from_id("department_id","students","matNo",$matNumber);
  $dept = Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID);
  $bid = Database::getInstance()->get_name_from_id("bulletin","departments","department_id",$deptID);
  $req = Database::getInstance()->get_name_from_id("gradRequirements","bulletin","bulletinID",$bid);
  if (isset($_GET['n'])) {
    Database::getInstance()->notify_viewed($_GET['n']);
  }
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
                <h2 class="card-title">My Transcript</h2>
                <H4>Department: <?php echo $dept; ?> </H4>
                <div class="row">
                  <?php 
                    $dur = Database::getInstance()->get_name_from_id("duration","departments","department_id",$deptID)*100;
                    for ($i=100; $i <= $dur; $i+=100) { 
                      ?>
                        <div class="col-md-3">
                          <center><h4><?php echo $i; ?> Level GPA</h4></center>
                          <div class="row">
                            <div class="col-md-4">
                              <h4>1<sup>st</sup>: <i><?php echo $k = Database::getInstance()->calcGPA($matNumber,$i,1);?></i></h4>
                            </div>
                            <div class="col-md-4">
                              <h4>2<sup>nd</sup>: <i><?php echo $j = Database::getInstance()->calcGPA($matNumber,$i,2);?></i></h4>
                            </div>
                            
                            <div class="col-md-4">
                              <h4>CGPA: <i><?php echo $tot = ($k+$j)/2;?></i></h4>
                            </div>
                          </div>
                        </div>
                      <?php
                    }
                  ?>
                </div>
                <div id="get_dets"></div>
              </div>
              <div class="card-body">
              <div class="container">
              <a href="compute.php" class="btn btn-primary">Update Scores</a>
              <a onclick="pushToOfficer()" class="btn btn-info text-white pull-right">Send Scores</a>
              </div>
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
                        <th class="text-center">
                            Level
                        </th>
                        <th class="text-center">
                            Score
                        </th>
                        <th class="text-center">
                            Remark
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                         $trans = Database::getInstance()->select_from_where2("transcripttemp","matNo",$matNumber);
                          foreach ($trans as $row) {
                            $code = Database::getInstance()->get_name_from_id("code","courses","courseID",$row['courseID']);
                            $title = Database::getInstance()->get_name_from_id("title","courses","courseID",$row['courseID']);
                            $semester = $row['semester'];
                            $level = $row['level'];
                            $ctype = Database::getInstance()->get_name_from_id("courseType","courses","courseID",$row['courseID']);
                            $score = $row['score'];

                            ?>
                              <tr>
                                <td class="text-center">
                                  <?php echo $code; ?>
                                </td>
                                <td class="text-center">
                                <?php echo $title; ?>
                                </td>
                                <td class="text-center">
                                <?php if ($semester == 1) {echo "1st";}else if($semester == 2){echo "2nd";}else{echo "Summer";} ?>
                                </td>
                                <td class="text-center">
                                <?php echo $level; ?>
                                </td>
                                <td class="text-center">
                                <?php echo $score; ?>
                                </td>
                                <td class="text-center">
                                <?php 
                                if ($ctype == 1 && $score >= 0 && $score <=39) {
                                  echo "<div class='badge badge-danger'>Fail</div>";
                                }else if ($ctype == 1 && $score >=  40 && $score <=100) {
                                  echo "<div class='badge badge-success'>Pass</div>";
                                }else if ($ctype > 1 && $score >= 0 && $score <=49) {
                                  echo "<div class='badge badge-danger'>Fail</div>";
                                }else if ($ctype > 1 && $score >= 50 && $score <=100) {
                                  echo "<div class='badge badge-success'>Pass</div>";
                                }
                                ?>
                                </td>
                              </tr>
                            <?php
                          }  
                      
                      ?>
                      
                    </tbody>
                  </table>
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
  function congrats(){ 

    s.notify({
        icon: 'pe-7s-trash',
        message: "Score Was Sent Successfully"

      },{
          type: 'success',
          timer: 100000
      });
    }
		
		function pushToOfficer(){ 
		var val = "<?php echo $matNumber; ?>";
          s.ajax({
            type: 'post',
            url: '../func/verify.php',
            data: "matNumber=" + val +  '&ins=pushScores',
             success: function(data)
            {
				if (data === 'Done') {
					console.log(data);
           // window.location = 'transcript.php';
           congrats();
				  }
				  else {
					   
						jQuery('#get_dets').html(data).show();
				  }
            }
          });
		}

    </script>