<?php include_once("inc/header.php"); 
$active_page = "compute";
?>
<style>
  #compute-btns{
  width: 100%;
  max-width: 100%;
  min-width: 100%;
  margin-bottom: 20px;
  
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
                <H4>B.Sc. (Hons) Computer Science </H4>
              </div>
              <div class="card-body">
              <div class="accordion">
                    <button type="button" class="btn btn-info" id="compute-btns" type="button" data-toggle="collapse" data-target="#l1" aria-expanded="false" aria-controls="l1">
                        100 Level (First Semester)  
                    </button>

                    <button type="button" class="btn btn-info" id="compute-btns" type="button" data-toggle="collapse" data-target="#l1" aria-expanded="false" aria-controls="l1">
                        100 Level (Second Semester)  
                    </button>
                    <!--<div class="card">
                      <div class="card-body">
                        <div class="form-group">
                              Did You Register For Summer? <br> Yes <input type="radio" name="summer" class="form-control">  No  <input type="radio" name="summer" class="form-control">
                        </div>
                      </div>
                    </div>-->
                    <div  class="collapse" id="l1">
                              <div class="table-responsive">
                                <form method="post">
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
                                      <tr>
                                        <td class="text-center">
                                          GEDS 001
                                        </td>
                                        <td class="text-center">
                                          Citizenship Orientation
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="score[]" id="" class="form-control" placeholder="Enter Score [0 - 100]">
                                        </td>
                                      </tr>
                                      
                                      <tr>
                                        <td colspan="3">
                                        <input type="submit" value="Submit" class="btn btn-white">
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
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
  </div>
<?php include_once("inc/footer.php"); ?>