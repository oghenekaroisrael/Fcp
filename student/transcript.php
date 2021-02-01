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
                    // for ($i=100; $i <= $dur; $i+=100) { 
                    //   $k = Database::getInstance()->calcGPA($matNumber,$i,1);
                    //   $j = Database::getInstance()->calcGPA($matNumber,$i,2);
                    //   $tot = ($k+$j)/2;
                    // }
                  ?>
                </div>
                <div id="get_dets"></div>
              </div>
              <div class="card-body">
              <div class="container">
              <a href="compute.php" class="btn btn-primary">Add Scores</a>
              <a onclick="pushToOfficer()" class="btn btn-info text-white pull-right">Submit Scores</a>
              </div>
                <div class="row">
                    <?php 
                      for ($i=100; $i <= $dur; $i+=100) { 
                        for ($j=1; $j <= 3; $j++) { 
                          if ($j != 3) {
                            
                           ?>
                           <div class="col-md-6">
                            <div class="header text-center">
                              <h4 class="title">
                              <?php echo $i." Level"; 
                              if ($j == 1) {
                                echo "<br>First Semester";
                              }else if ($j == 2) {
                                echo "<br>Second Semester";
                              }if ($j == 3) {
                                echo "<br>Summer";
                              }
                              ?>
                              </h4> 
                            </div>
                            <div class="table-responsive">
                              <table class="table tablesorter">
                                <thead class=" text-primary">
                                  <tr>
                                    <th class="text-center">
                                      Course Code
                                    </th>
                                    <th class="text-center">
                                      Course Title
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
                                    $trans = Database::getInstance()->showTranscript($i,$j);
                                      foreach ($trans as $row) {
                                        $code = $row['code'];
                                        $title = $row['title'];
                                        $ctype = $row['courseType'];
                                        $score1 = Database::getInstance()->get_name_from_id2("score","transcripttemp","matNo",$matNumber,"courseID",$row['courseID'],"courseID");
                                        if (!empty($score1) && $score1 > 0) {
                                          $score = $score1;
                                        }else{
                                          $score = 0;
                                        }
                                        ?>
                                          <tr>
                                            <td class="text-center">
                                              <?php echo $code; ?>
                                            </td>
                                            <td class="text-center">
                                            <?php echo $title; ?>
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
                                            }elseif ($ctype > 0 && $score == 0) {
                                              echo "<div class='badge badge-default'>NG</div>";
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
                           <?php
                          } else {
                            
                           ?>
                           <div class="col-md-12" style="margin-top: 30px;margin-bottom:30px;">
                            <div class="header text-center">
                              <h4 class="title">
                              <?php echo $i." Level"; 
                              if ($j == 1) {
                                echo "<br>First Semester";
                              }else if ($j == 2) {
                                echo "<br>Second Semester";
                              }if ($j == 3) {
                                echo "<br>Summer";
                              }
                              ?>
                              </h4> 
                            </div>
                            <div class="table-responsive">
                              <table class="table tablesorter">
                                <thead class=" text-primary">
                                  <tr>
                                    <th class="text-center">
                                      Course Code
                                    </th>
                                    <th class="text-center">
                                      Course Title
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
                                    $trans = Database::getInstance()->showSummer($i,$j,$matNumber);
                                      foreach ($trans as $row) {
                                        $code = Database::getInstance()->get_name_from_id("code","courses","courseID",$row['courseID']);
                                        $title = Database::getInstance()->get_name_from_id("title","courses","courseID",$row['courseID']);
                                        $semester = $j;
                                        $level = $i;
                                        $ctype = Database::getInstance()->get_name_from_id("courseType","courses","courseID",$row['courseID']);
                                        $score1 = $row['score'];
                                        if (!empty($score1) && $score1 > 0) {
                                          $score = $score1;
                                        }else{
                                          $score = 0;
                                        }
                                        ?>
                                          <tr>
                                            <td class="text-center">
                                              <?php echo $code; ?>
                                            </td>
                                            <td class="text-center">
                                            <?php echo $title; ?>
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
                           <?php
                        
                          }
                          
                        }
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