<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "compute";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $matNumber = $_SESSION['userSession'];
  $dept= Database::getInstance()->get_name_from_id("department_id","students","matNo",$matNumber);
  $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept));
  $lvl = intval(Database::getInstance()->get_name_from_id("duration","departments","department_id",$dept))*100;
  $bulletin = Database::getInstance()->get_name_from_id("bulletin","departments","department_id",$dept);
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
                <h2 class="card-title">Student Transcript</h2>
                <H4>Department: <?php echo $dpt; ?> </H4>
              </div>
              <div class="card-body">
              <div class="container">
                      <form id="searchForm" action="" method="POST">
                          <div class="row">
                          <div class="col-md-4">
                              <div class="form-group">
                                <label>Level</label>
                                <select name="level" class="form-control" id="level">
                                  <option selected>Select A Level</option>
                                  <?php 
                                    for ($level=100; $level <= $lvl; $level+=100) { 
                                      ?>
                                        <option value="<?php echo $level; ?>"><?php echo $level; ?></option>
                                      <?php
                                    }
                                  ?>
                                </select>
                              </div>
                          </div>
                          
                          <div class="col-md-4" id="semCont" style="display: none;">
                          <div class="form-group">
                          <label>Semester</label>
                          <select name="semester" class="form-control" id="semester">
                            <option selected>Select A Semester</option>
                                  <option value="1">First Semester</option>
                                  <option value="2">Second Semester</option>
                                  <option value="3">Summer</option>
                          </select>
                        </div>
                          </div>
                          </div>
                      </form>

                      <?php
                        if (isset($_POST['level']) && !empty($_POST['level']) && isset($_POST['semester']) && !empty($_POST['semester'])) {
                          $courses = Database::getInstance()->select_from_where_amt_no3("courses","bulletin",$bulletin,"level",$_POST['level'],"semester",$_POST['semester']);
                            ?>
                            <div class="header">
                              <h3 class="title text-center">
                              <?php echo $_POST['level']." Level "; ?>

                              <?php 
                              if ($_POST['semester'] == 1) {
                                echo "First Semester";
                              }else if ($_POST['semester'] == 2) {
                                echo "Second Semester";
                              }else if ($_POST['semester'] == 3) {
                                echo "Summer";
                              }
                              ?>
                              </h3>
                            </div>
                              <div class="table-responsive">
                                <form id="updateForm">
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
                                            Score
                                        </th>
                                      </tr>
                                    </thead>
                                    <tbody id="wrapper">
                                      <?php 
                                       if ($_POST['semester'] == 1 || $_POST['semester'] == 2) {
                                        foreach ($courses as $course) {
                                          ?>
                                              <tr>
                                                <td class="text-center">
                                                  <?php echo $course['code'] ?>
                                                </td>
                                                <td class="text-center">
                                                <?php echo $course['title'] ?>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="score[]" id="" class="form-control" placeholder="Enter Score [0 - 100]">
                                                    <input type="hidden" name="id[]" value="<?php echo $course['courseID'] ?>">
                                                </td>
                                              </tr>
                                          <?php }
                                       }else{
                                          ?>
                                            <tr class="newRow">
                                            <td class="text-center" colspan="2">
                                              <select name="id[]" class="form-control">
                                                <?php 
                                                  $val = Database::getInstance()->select_from_where2("courses","department",$dept);
                                                  foreach ($val as $lue) {
                                                    ?>
                                                      <option value="<?php echo $lue['courseID']; ?>"><?php echo $lue['title']; ?> (<?php echo $lue['code']; ?>)</option>
                                                    <?php
                                                  }
                                                ?>
                                              </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" name="score[]" id="" class="form-control" placeholder="Enter Score [0 - 100]">
                                            </td>
                                            <td></td>
                                          </tr>
                                          <?php
                                       }
                                      
                                      ?>
                                    </tbody>
                                    <tfoot>
                                    <?php 
                                      if ($_POST['semester']  == 3) {
                                        ?>
                                        <tr>
                                          <td colspan="3"><p class="addUp btn btn-info"><i class="fa fa-plus"></i> Add More</p></td>
                                        </tr>
                                        <?php
                                      }
                                    
                                    ?>
                                        <tr>

                                        <td colspan="3">
                                        <input type="submit" value="Save" class="btn btn-white">
                                        </td>
                                      </tr>
                                    </tfoot>
                                  </table>
                                </form>
                              </div>
                            <?php } ?>
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
	var n=jQuery .noConflict();
	var wrapper = n("#wrapper"); //Fields row
	var add_button = n(".addUp"); //Add button ID
					
	var x = 1; //initlal text box count
	n(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		x++; //text box increment
		n(wrapper).append('<tr class="newRow"><td class="text-center" colspan="2"><select name="id[]" class="form-control"><?php $val = Database::getInstance()->select_from_where2("courses","department",$dept); foreach ($val as $lue) {?> <option value="<?php echo $lue['courseID']; ?>"><?php echo $lue['title']; ?> (<?php echo $lue['code']; ?>)</option><?php } ?></select></td><td class="text-center"><input type="text" name="score[]" id="" class="form-control" placeholder="Enter Score [0 - 100]"></td><td><a href="#" class="removeUpBtn btn btn-info"><i class="fa fa-times"></i></a></td></tr>'); //add input box
	});
					
	n(wrapper).on("click",".removeUpBtn", function(e){ //user click on remove text
    e.preventDefault(); 
    n(this).parents("tr").remove();
    x--;
	})
</script>
<script type="text/javascript">
	 var a=jQuery .noConflict();
	 a(document).ready(function(){

        a('#level'). on('change', function(e) {
          document.getElementById("semCont").style.display = "block";
        });

        a('#semester'). on('change', function(e) {
          document.getElementById("searchForm").submit();
        });

        a('#updateForm').on('submit', function (e) {
        e.preventDefault();
          var formData = new FormData(a(this)[0]);
          var ins = "updateTranscript";
          var matNumber = "<?php echo $matNumber; ?>";
          var level = "<?php echo $_POST['level']; ?>";
          var semester = "<?php echo $_POST['semester']; ?>";
          formData.append('matNo',matNumber);
          formData.append('ins',ins);
          formData.append('level',level);
          formData.append('semester',semester);
                a.ajax({
                  type: 'POST',
            data: formData,  
            cache: false,
            contentType: false,
            processData: false,
                  url: '../func/verify.php',						
                  success: function(data)
                  {
              a("#get_result").html(data).fadeIn("slow");
              window.location = 'transcript.php';
                  }
                });

        });
      });
</script>