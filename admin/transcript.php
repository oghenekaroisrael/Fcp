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
  $uid = $_SESSION['userSession'];
  $student = Database::getInstance()->select_from_while("students","matNo",$_GET['id']);
  $matNumber = $_GET['id'];
  $status = intval(Database::getInstance()->select_transcript($matNumber));
  foreach ($student as $std) {
    $fullname = ucwords($std['lastName']." ".$std['firstName']." ".$std['middleName']);
    $deptID = $std['department_id'];
    $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID));
    $level = $std['level'];
  }
  $dur = Database::getInstance()->get_name_from_id("duration","departments","department_id",$deptID)*100;
  
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
                <div id="get_result"></div>
                <h2 class="card-title">Transcript</h2>                
                <h5>Fullname: <?php echo $fullname; ?></h5>
                <h5>Department : <?php echo $dpt; ?> </h5>
                <h5>Level: <?php echo $level; ?></h5>
                <h5>Status: <?php if ($status == 1) {?>
                <div class="badge badge-success">Accepted</div>
                <?php 
                } elseif($status == 2) {
                  ?>
                  <div class="badge badge-danger">Rejected</div>
                  <?php
                }elseif($status == 0) {
                  ?>
                  <div class="badge badge-default">None</div>
                  <?php
                }
                 ?></h5>
                 <h5>
                  Total Outstandings : <?php $det = Database::getInstance()->select_outstanding1($matNumber);
                  foreach ($det as $value) {
                    echo $value['outstandings'];
                  } ?> &#160;&#160;&#160;&#160;<a href="outstanding.php?id=<?php echo $matNumber; ?>"><i class="fas fa-eye text-primary"></i></a>
                 </h5>
              </div>
              <div class="card-body">
                <?php 
                  if ($status == 0) {
                    ?>
                    <div class="row">
                      <div class="container">
                      <button type="button" onclick="accept(`<?php echo $matNumber; ?>`)" class="btn btn-success pull-left">Accept Transcript</button>
                      <button type="button" data-toggle="modal" data-target="#reject" class="btn btn-info pull-right">Reject Transcript</button>
                      </div>  
                    </div>
                    <?php
                  }else if ($status == 1) {
                    ?>
                    <div class="row">
                      <div class="container">
                      <button type="button" data-toggle="modal" data-target="#reject" class="btn btn-info pull-right">Reject Transcript</button>
                      </div>  
                    </div>
                    <?php
                  }else if ($status == 2) {
                    ?>
                    <div class="row">
                      <div class="container">
                      <button type="button" onclick="accept(`<?php echo $matNumber; ?>`)" class="btn btn-success pull-left">Accept Transcript</button>
                    
                      </div>
                    </div>
                    <?php
                  }
                ?>
                <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="rejectlabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="rejectLabel">Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <form id="newForm">
                              <div class="form-group">
                                <label for="reason">Reason</label>
                                <textarea rows="4" cols="80" id="reason" class="form-control" style="color: #000;" placeholder="Remark For Student" name="reason"></textarea>
                              </div>
                              <input type="submit" value="Send" class="btn btn-info">
                          </form>
                      </div>
                      <div class="modal-footer">

                      </div>
                    </div>
                  </div>
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
                            <div class="">
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
                            <div class="">
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
	 var a=jQuery .noConflict();
	 a(document).ready(function(){
        a('#newForm').on('submit', function (e) {
        e.preventDefault();
          var formData = new FormData(a(this)[0]);
          var ins = "newRemark";
          var matNumb = "<?php echo $_GET['id']; ?>";
          var val = 1;
          var user = "<?php echo $uid; ?>"
          formData.append('matNumber',matNumb);
          formData.append('val',val);
          formData.append('user',user);
          formData.append('ins',ins);
                a.ajax({
                  type: 'post',
            data: formData,  
            cache: false,
            contentType: false,
            processData: false,
                  url: '../func/verify.php',						
                  success: function(data)
                  {
                    document.getElementById("reject").hidden = "true";
              a("#get_result").html(data).fadeIn("slow");
                  }
                });
        });
      });

      function accept(ID){ 
        a.ajax({
            type: 'post',
            url: '../func/verify.php',
            data: "matNumber=" + ID +  '&ins=acceptResult',
             success: function(data)
            {
				if (data === 'Done') {
					console.log(data);
            window.location = 'transcript.php?id=<?php echo $matNumber; ?>';
				  }
				  else {
					   
						jQuery('#get_result').html(data).show();
				  }
            }
          });
      }

      function sure(ID,name){ 
        a.notify({
            icon: 'pe-7s-trash',
            message: "Transcript Rejected"

          },{
              type: 'success',
              timer: 100000
          });

        }
</script>