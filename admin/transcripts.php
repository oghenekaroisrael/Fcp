<?php 
ob_start();
session_start();
include_once("inc/header.php"); 
$active_page = "transcripts";
// Include database class
include_once '../inc/db.php';
if(!isset($_SESSION['userSession'])){
  header("Location: ../index");
  exit;
}elseif (isset($_SESSION['userSession'])){
  $uid = $_SESSION['userSession'];
  $deptID = Database::getInstance()->get_name_from_id("department_id","users","uid",$uid);
  $dpt = ucwords(Database::getInstance()->get_name_from_id("name","departments","department_id",$deptID));
  if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['level']) && !empty($_POST['level'])) {
    $studData = Database::getInstance()->select_from_where_or_ord3("students","level",$_POST['level'],"lastName",$_POST['name'],"matNO","ASC");
  }else{
    $studData = Database::getInstance()->select_transcripts();
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
                <h2 class="card-title">Transcript Search</h2>
                <H4>Department: <?php echo $dpt; ?> </H4>
              </div>
              <div class="card-body">
                  <div class="container">
                    <h4>Search Parameters</h4>
                  <form action="" method="POST">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" name="name" class="form-control">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Level</label>
                            <input type="text" name="level" class="form-control">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <input type="submit" value="Search" class="btn btn-info"  style="margin-top: 25px;">
                        </div>
                      </div>
                  </form>
                  </div>
                <div class="table-responsive">
                  <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th class="text-center">
                          Fullname
                        </th>
                        <th class="text-center">
                          Level
                        </th>
                        <th class="text-center">
                          Matric Number
                        </th>
                        <th class="text-center">
                          Action
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        foreach ($studData as $row) {
                          ?>
                          <tr>
                            <td class="text-center">
                              <?php echo ucwords($row['lastName']." ".$row['firstName']." ".$row['middleName']); ?>
                            </td>
                            <td class="text-center">
                              <?php echo $row['level']; ?>
                            </td>
                            <td class="text-center">
                            <?php echo $row['matNO']; ?>
                            </td>
                            <td class="text-center">
                                <a href="transcript.php?id=<?php echo $row['matNO']; ?>"><i class="fas fa-eye text-primary"></i></a>
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