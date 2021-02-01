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
<script>
var coun = 1;
</script>
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
                      <?php
                        if (isset($_POST['level']) && !empty($_POST['level'])) {
                          $courses = Database::getInstance()->select_from_where_amt_no3("courses","bulletin",$bulletin,"level",$_POST['level'],"semester",1);
                          $courses2 = Database::getInstance()->select_from_where_amt_no3("courses","bulletin",$bulletin,"level",$_POST['level'],"semester",2);
                          ?>
                            <div class="header">
                              <h3 class="title text-center">
                              <?php echo $_POST['level']." Level "; ?>
                              </h3>
                            </div>
                            <form id="updateForm">
                              <div class="row">
                                  <div class="col-md-6">
                                    <div class="header text-center">First Semester</div>
                                    <div class="table-responsive">
                                        <table class="table tablesorter " id="">
                                          <thead class="text-primary">
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
                                          <tbody>
                                            <?php 
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
                                                          <input type="hidden" name="semester[]" value="1">
                                                      </td>
                                                    </tr>
                                                <?php }?>
                                          </tbody>
                                        </table>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="header text-center">Second Semester</div>
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
                                                  Score
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php 
                                              foreach ($courses2 as $course) {
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
                                                          <input type="hidden" name="semester[]" value="2">
                                                      </td>
                                                    </tr>
                                                <?php }?>
                                          </tbody>
                                        </table>
                                    </div>
                                  </div>
                              </div>
                              <div class="row" id="summer" style="display: none;">
                                  <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table tablesorter ">
                                            <tbody  id="wrapper">
                                                  <tr>
                                                    <td colspan="3"><p class="addUp btn btn-info"><i class="fa fa-plus"></i> Add Course</p></td>
                                                  </tr>
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
                                                  <input type="hidden" name="semester[]" value="3">
                                                      <input type="text" name="score[]" id="" class="form-control" placeholder="Enter Score [0 - 100]" value="0">
                                                  </td>
                                                  <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                  </div>
                              </div>
                                   <input type="button" onclick="save()" value="Save" class="btn btn-white">
                                  </form>
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
   function save(){ 
    if (coun == 1) {
      a.notify({
        message: "Did You Do Summer? </br><button type='button' class='btn pop-btn' onclick='yesSummer()'>Yes</button> <button type='button' class='btn pop-btn' onclick='noSummer()'>No</button>"

      },{
          type: 'info',
          timer: 100000
      });
      coun++;
    }else{
      noSummer();
    }

    }

    function yesSummer(){ 
      document.getElementById("summer").style.display = "block";
      a('.alert').alert('close');
      }
    
      function noSummer(){
          var matNumber = "<?php echo $matNumber; ?>";
          var level = "<?php echo $_POST['level']; ?>";
                a.ajax({
                  type: 'POST',
                  data: a('#updateForm').serialize()+'&ins=updateTranscript&matNo='+matNumber+'&level='+level,  
                  url: '../func/verify.php',						
                  success: function(data)
                  {
                    a("#get_result").html(data).fadeIn("slow");
                    window.location = 'dashboard.php?s=yes';
                  }
                });
      }
</script>