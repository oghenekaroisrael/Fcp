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
  $uid = $_SESSION['userSession'];
  $dept= Database::getInstance()->get_name_from_id("department_id","users","uid",$uid);
  $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$dept));
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
                <h2 class="card-title">Bulletins</h2>
                <H4>Degree: <?php echo $dpt; ?></H4>
                <a href="newBulletin.php" class="btn btn-primary pull-right">New Bulletin</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th class="text-center">
                          Starting Year
                        </th>
                        <th class="text-center">
                          Ending Year
                        </th>
                        <th class="text-center">
                          Bulletin
                        </th>
                        <th class="text-center">View</th>
                        <th class="text-center">Update</th>                        
                        <th class="text-center">Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $dept = Database::getInstance()->get_name_from_id("department_id","users","uid",$_SESSION['userSession']);
                         $bulletins = Database::getInstance()->select_from_where2("bulletin","department_id",$dept);
                              foreach ($bulletins as $bulletin) {
                                $startY=$bulletin['startingYear'];
                                $endY=$bulletin['endingYear'];
                                $stat = $bulletin['status'];
                                $bid=$bulletin['bulletinID'];
                                $desc = $startY." To ".$endY;
                                ?>
                                <tr>
                                  <td class="text-center">
                                    <?php echo $startY; ?>
                                  </td>
                                  <td class="text-center">
                                  <?php echo $endY; ?>
                                  </td>
                                  <td class="text-center">
                                  <?php 
                                      if ($stat==0) {
                                        echo "<div class='badge badge-default'>Passed</div>";
                                      }else{
                                        echo "<div class='badge badge-success'>Current</div>";
                                      }
                                  ?>
                                  </td>
                                  <td class="text-center">
                                    <a href="view_bulletin.php?bid=<?php echo $bid; ?>"><i class="fas fa-eye" style="color:#fff;"></i></a>
                                  </td>
                                  <td class="text-center">
                                    <a href="updatebulletin.php?bid=<?php echo $bid; ?>"><i class="fas fa-edit" style="color:#fff;"></i></a>
                                  </td>
                                  <td class="text-center">
                                    <a onclick="sure(<?php echo $bid; ?>,'<?php echo $desc; ?>')"><i class="fas fa-trash" style="color:#fff;"></i></a>
                                  </td>
                                </tr>
                        <?php } ?>
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
  
		function sure(ID,name){ 

        	s.notify({
            	icon: 'pe-7s-trash',
            	message: "Are you sure you want to delete Bulletin From <b>"+name+"</b>? </br><button type='button' class='btn pop-btn' onclick='delet("+ID+")'>Delete</button>"

            },{
                type: 'danger',
                timer: 100000
            });

      }
      
      function congrats(){ 

          s.notify({
              icon: 'pe-7s-trash',
              message: "Deleted Successfully"

            },{
                type: 'success',
                timer: 100000
            });

          }
		
		function delet(ID){ 
		var val = ID;
          s.ajax({
            type: 'post',
            url: '../func/del.php',
            data: "val=" + val +  '&ins=delBulletin',
             success: function(data)
            {
				if (data === 'Done') {
					console.log(data);
            window.location = 'bulletin.php';
				  }
				  else {
					   
						jQuery('#get_det'+ID).html(data).show();
				  }
            }
          });
		}

    </script>