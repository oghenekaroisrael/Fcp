<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "bulletin";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $user_id = $_SESSION['userSession'];
  $bid = $_GET['bid'];
  $bulletinData = Database::getInstance()->select_from_where('bulletin',"bulletinID",$bid);
    foreach($bulletinData as $ow):
      $deptID = $ow['department_id'];
      $dept = Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID);
      $syear = $ow['startingYear'];
      $gyear = $ow['endingYear'];
      $req = $ow['gradRequirements'];
      $status = $ow['status'];
      if ($status == 0) {
        $stat = "Old";
      }else if($status == 1){
        $stat = "Current";
      }
    endforeach;
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
                <h2 class="card-title">Update Bulletin</h2>
                <H4>Degree: <?php echo ucwords($dept); ?></H4>
                <div id="get_result"></div>
              </div>
              <div class="card-body">
                <form id="newForm">
                    <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Department</label>
                              <select name="dept" class="form-control">
                                <option selected value="<?php echo $deptID; ?>"><?php echo ucwords($dept); ?></option>
                                <?php
                                  $userDetails = Database::getInstance()->select('departments');
                                  foreach($userDetails as $ow):
                                    $deptID = $ow['department_id'];
                                    $name = $ow['name'];	
                                  
                                ?>
                                <option value="<?php echo $deptID;?>"><?php echo $name;?></option>
                                <?php endforeach; ?>
                              </select>
                          </div>
                        </div>
                        
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Bulletin Status</label>
                              <select name="status" class="form-control">
                                <option selected value="<?php echo $status; ?>"><?php echo $stat; ?></option>
                                    <option value="0">Old</option>
                                    <option value="1">Current</option>
                              </select>
                          </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Entry Year</label>
                              <select name="syear" class="form-control">
                                <option selected value="<?php echo $syear; ?>"><?php echo $syear; ?></option>
                                <?php
                                  for ($i=intval(date("Y"))-20; $i <= 2050; $i++) { 
                                    ?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php
                                  } ?>
                              </select>
                          </div>
                        </div>
                        
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Graduating Year</label>
                            <select name="gyear" class="form-control">
                            <option selected value="<?php echo $gyear; ?>"><?php echo $gyear; ?></option>
                                 <?php
                                  for ($i=intval(date("Y")); $i <= 2050; $i++) { 
                                    ?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php
                                  } ?>
                              </select>
                          </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                  <div class="form-group">
                                    <label>Graduation Description</label>
                                    <textarea style="white-space: nowrap;" name="req" class="form-control" cols="30" rows="10">
                                      <?php echo $req;?>
                                    </textarea>
                                  </div>
                        </div>
                    </div>
                    <input type="submit" value="Update Bulletin" class="btn btn-info pull-right">
                </form>
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
    var ins = "updateBulletin";
    var user = "<?php echo $user_id; ?>";
    var id = "<?php echo $bid; ?>";
    formData.append('user',user);
    formData.append('id',id);
     formData.append('ins',ins);
     
          a.ajax({
            type: 'post',
			data: formData,  
			cache: false,
			contentType: false,
			processData: false,
            url: '../func/edit.php',						
            success: function(data)
            {
				a("#get_result").html(data).fadeIn("slow");
            }
          });

        });

      });
</script>