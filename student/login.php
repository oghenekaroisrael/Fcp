<?php 
// Include database class
include_once '../inc/db.php';
include_once("inc/header.php"); 

?>

<body class="">
  <div class="wrapper">
    <div class="main-panel">
      <div class="content">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Student Login</h5>
                <div id="get_result"></div>	
              </div>
              <div class="card-body">
                <form id="login">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Matric Number</label>
                        <input type="text" class="form-control" required autocomplete="off" placeholder="19/0123" name="matNumber" id="matNum"/>
                      </div>
                    </div>
                  </div>
                  <input type="submit" class="btn btn-fill btn-primary" value="Verify">
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
	var a=jQuery.noConflict();
	a(function () {
		a('#login').on('submit', function (e) {
			e.preventDefault();
      var matNumb = a("#matNum").val();
			if(matNumb === ""){
				
				a('#get_result').html("<div class='alert alert-danger'>Matric Number must not be empty</div>").show();
			} else{   
				a.ajax({
					type: 'POST',
          url: '../func/verify.php',
					data: a('#login').serialize() + '&ins=verify',
					dataType: "JSON",
					success: function(response)
					{
						if (response.value === 'emptyMatNumb') {
							console.log(response);
							jQuery('#get_result').html("<div class='alert alert-danger'>Username must not be empty</div>").show();
						}else if (response.value === 'Login') {
							console.log(response);
							jQuery('#get_result').html("<div class='alert alert-success'>Redirecting you</div>").show();
							window.location = response.page;
						}else {
							jQuery('#get_result').html(response.value2).show();
							console.log(response);
						}
					}
				});
			}

		});
	});
</script>