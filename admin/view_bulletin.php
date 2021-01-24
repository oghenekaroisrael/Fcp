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
  $bid = $_GET['bid'];
  $stat = Database::getInstance()->get_name_from_id("status","bulletin","bulletinID",$bid);
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
                <h2 class="card-title">
                <?php 
                    if ($stat == 0) {
                      echo "Old";
                    }else{
                      echo"Current";
                    }
                ?> Bulletin</h2>
                <h4>Degree: <?php echo $dpt; ?></h4>
              </div>
              <div class="card-body">
                <p>
                <?php echo Database::getInstance()->get_name_from_id("gradRequirements","bulletin","bulletinID",$bid); ?>
                </p>
                <div class="table-responsive">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th class="text-center">
                          Level
                        </th>
                        <th class="text-center">
                          GEDS Courses
                        </th>
                        <th class="text-center">
                          Departmental Courses
                        </th>
                        <th class="text-center">
                          Certificate Support
                        </th>
                        <th class="text-center">
                          Total
                        </th>
                        <th class="text-center">View Courses</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $t1=0;
                      $t2=0;
                      $t3=0;
                      $t4=4;
                      $lvl = Database::getInstance()->get_name_from_id("duration","departments","department_id",$dept)*100;
                      for ($i=100; $i <= $lvl; $i+=100) { 
                        $bulletins = Database::getInstance()->select_from_where_no3_group("courses","bulletin",$bid,"department",$dept,"level",$i,"bulletin","courseType","ASC");
                        foreach ($bulletins as $bulletin) {
                          ?>
                            <tr>
                              <td class="text-center">
                                <?php echo $i; ?>
                              </td>

                              <td class="text-center">
                                <?php echo $t1+=$tu1 = Database::getInstance()->sum_where4("unit","courses","courseType",1,"level",$i,"department",$dept,"bulletin",$bid);?>
                              </td>
                              
                              <td class="text-center">
                              <?php echo $t2+=$tu2 = Database::getInstance()->sum_where4("unit","courses","courseType",2,"level",$i,"department",$dept,"bulletin",$bid);?>
                              </td>
                              
                              <td class="text-center">
                              <?php echo $t3+=$tu3 = Database::getInstance()->sum_where4("unit","courses","courseType",3,"level",$i,"department",$dept,"bulletin",$bid);?>
                              </td>
                              
                              <td class="text-center">
                              <?php echo $t4+=($tu1+$tu2+$tu3+$tu4); ?>
                              </td>
                              
                              <td class="text-center">
                                <a href="courses.php?level=<?php echo $i; ?>&bid=<?php echo $bid; ?>"><i class="fas fa-eye" style="color:#fff;"></i></a>
                              </td>
                            </tr>
                      <?php
                        }
                      }
                      ?>
                      
                    </tbody>
                    <tfoot class=" text-primary">
                      <tr>
                        <th class="text-center">
                          SUB TOTAL
                        </th>
                        <th class="text-center">
                        <?php echo $t1; ?>
                        </th>
                        <th class="text-center">
                        <?php echo $t2; ?>
                        </th>
                        <th class="text-center">
                        <?php echo $t3; ?>
                        </th>
                        <th class="text-center">
                        <?php echo $t4; ?>
                        </th>
                        <th></th>
                      </tr>
                    </tfoot>
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