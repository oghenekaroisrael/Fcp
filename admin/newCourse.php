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
  $user_id = $_SESSION['userSession'];
  $bid = $_GET['bid'];
  $deptID = Database::getInstance()->get_name_from_id("department_id","users","uid",$user_id);
  $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID));

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
                <h2 class="card-title">New Course</h2>
                <h4>Degree: <?php echo $dpt; ?></h4>
                <div id="get_result"></div>
              </div>
              <div class="card-body">
                <div class="container">
                <form id="newForm">
                    <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Level</label>
                              <select name="level" class="form-control">
                                <option selected>Select Level</option>
                                <?php
                                  $lvl = Database::getInstance()->get_name_from_id("duration","departments","department_id",$deptID)*100;
                                  for ($level=100; $level <= $lvl; $level+=100) { 
                                    ?>
                                    <option value="<?php echo $level;?>"><?php echo $level;?></option>
                                    <?php }	?>
                              </select>
                          </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Semester</label>
                              <select name="semester" class="form-control">
                                <option selected>Select Semester</option>
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                              </select>
                          </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Course Type</label>
                              <select name="ctype" class="form-control">
                                <option selected>Select Course Type</option>
                                <?php
                                  $userDetails = Database::getInstance()->select('courseTypes');
                                  foreach($userDetails as $ow):
                                    $ctypeID = $ow['ctypeID'];
                                    $name = $ow['name'];	
                                  
                                ?>
                                <option value="<?php echo $ctypeID;?>"><?php echo $name;?></option>
                                <?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Course Code</label>
                              <input type="text" name="code" class="form-control" placeholder="COSC 301">
                          </div>
                        </div>
                        <div class="col-md-8">
                           <div class="form-group">
                              <label>Course Title</label>
                              <input type="text" name="title" class="form-control" placeholder="Intro To Programming">
                          </div>
                        </div>

                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Course Unit</label>
                              <input type="number" name="unit" class="form-control" placeholder="0-10">
                          </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                  <div class="form-group">
                                    <label>Course Description</label>
                                    <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
                                  </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                  <div class="form-group">
                                    <label>Assigned Lecturer</label>
                                    <input type="text" name="lect" class="form-control" placeholder="Dr Something"/>
                                  </div>
                        </div>
                    </div>
                    <input type="submit" value="Add Course" class="btn btn-info pull-right">
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
        a('#newForm').on('submit', function (e) {
        e.preventDefault();
          var formData = new FormData(a(this)[0]);
          var ins = "newCourse";
          var bulletin = "<?php echo $bid; ?>";
          var dept = "<?php echo $deptID; ?>"
          formData.append('bulletin',bulletin);
          formData.append('dept',dept);
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