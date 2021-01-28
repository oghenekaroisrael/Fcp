<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "oustanding";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $uid = $_SESSION['userSession'];
  $student = Database::getInstance()->select_from_while("students","matNo",$_GET['id']);
  $matNumber = $_GET['id'];
  foreach ($student as $std) {
    $fullname = ucwords($std['lastName']." ".$std['firstName']." ".$std['middleName']);
    $deptID = $std['department_id'];
    $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID));
    $level = $std['level'];
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
                <h2 class="card-title">Outstandings</h2>                
                <h5>Fullname: <?php echo $fullname; ?></h5>
                <h5>Department : <?php echo $dpt; ?> </h5>
                <h5>Level: <?php echo $level; ?></h5>
              </div>
              <div class="card-body">
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
                         $trans = Database::getInstance()->select_outstanding1($_GET['id']);
                          foreach ($trans as $row) {
                            $code = Database::getInstance()->get_name_from_id("code","courses","courseID",$row['courseID']);
                            $title = Database::getInstance()->get_name_from_id("title","courses","courseID",$row['courseID']);
                            $semester = Database::getInstance()->get_name_from_id("semester","courses","courseID",$row['courseID']);
                            $level = Database::getInstance()->get_name_from_id("level","courses","courseID",$row['courseID']);
                            $ctype = Database::getInstance()->get_name_from_id("courseType","courses","courseID",$row['courseID']);
                            $score = $row['score'];
                            $courseID = $row['courseID'];
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
                <div class="container">
                  <br><br>
                <div class="typography-line">
                    <form id="newRemark">
                    <div class="form-group">
                        <span>General Remark</span>
                        <textarea style="white-space: nowrap;" rows="4" cols="80" class="form-control" placeholder="Remark For Student" name="reason">
                          <?php 
                              echo Database::getInstance()->get_name_from_id2("remark","remarks","matNO",$_GET['id'],"type",2,"remarkID");
                          ?>
                        </textarea>
                      </div>
                      <input type="submit" value="Submit" class="btn btn-primary">
                    </form>
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
<script type="text/javascript">
	 var a=jQuery .noConflict();
	 a(document).ready(function(){
        a('#newRemark').on('submit', function (e) {
        e.preventDefault();
          var formData = new FormData(a(this)[0]);
          var ins = "newRemark";
          var matNumb = "<?php echo $_GET['id']; ?>";
          var val = 2;
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
              a("#get_result").html(data).fadeIn("slow");
                  }
                });
        });
      });
</script>