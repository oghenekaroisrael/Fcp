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
  $dept= Database::getInstance()->get_name_from_id("department_id","users","uid",$uid);
  $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept));
}
?>
<style>
  option{
    color: #000;
  }
</style>
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
                <h2 class="card-title">View Courses</h2>
                <H4><?php echo ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept)); ?> </H4>
              </div>
                  <div class="container-fluid">
                      <h3 class="title text-center"><?php echo $level; ?> Select Bulletin</h3>
                  </div>
                    <form id="newForm">
                      <div class="form-group">
                        <label>Bulletins</label>
                        <select name="bulletin" class="form-control" id="bulletinList">
                          <option selected>Select A Bulletin</option>
                        <?php
                          $userDetails = Database::getInstance()->select_from_where2('bulletin', 'department_id', $dept);
                              foreach($userDetails as $row):
                                $bull = $row['bulletinID'];
                                $startingYear = $row['startingYear'];
                                $endingYear = $row['endingYear'];
                                $req = $row['gradRequirements'];
                                $status = $row['status'];
                                ?>
                                  <option value="<?php echo $bull; ?>"><?php echo $startingYear." To ".$endingYear; ?></option>
                                <?php
                              endforeach; 
                          ?>
                        </select>
                      </div>
                    </form>
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

        a('#bulletinList'). on('change', function(e) {
          var bulletin = document.getElementById("bulletinList").value;
          window.location="view_courses.php?bid="+bulletin;

        });
      });
</script>