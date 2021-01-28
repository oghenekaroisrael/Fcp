<?php 
// Include database class
include_once '../inc/db.php';
include_once("inc/header.php"); ?>

<body class="">
  <div class="wrapper">
    <div class="main-panel">
      <div class="content">
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Admin Login</h5>                
                <div id="get_result"></div>	
              </div>
              <div class="card-body">
                <form id="login">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" id="username" required autocomplete="off" placeholder="username" name="username">
                      </div>

                      <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="pass" required autocomplete="off" placeholder="password" name="password">
                      </div>
                      <input type="submit" class="btn btn-fill btn-primary" value="Login"/>
                    </div>
                  </div>
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
	a(function () {
		a('#login').on('submit', function (e) {
			e.preventDefault();
			 
			var username = a("#username").val();
			var password = a("#pass").val();
			if(username === ""){
				
				a('#get_result').html("<div class='alert alert-danger'>Username must not be empty</div>").show();
			} else if (password === ""){
				
				a('#get_result').html("<div class='alert alert-danger'>Password must not be empty</div>").show();
			} else{
				a.ajax({
					type: 'post',
					url: '../func/verify.php',
					data: a('#login').serialize() + '&ins=login',
					dataType: "json",
					 success: function(response)
					{
						if (response.value === 'emptyUsername') {
							console.log(response);
							jQuery('#get_result').html("<div class='alert alert-danger'>Username must not be empty</div>").show();
						} else if (response.value === 'emptyPass') {
							console.log(response);
							jQuery('#get_result').html("<div class='alert alert-danger'>Password must not be empty</div>").show();
						} else if (response.value === 'no') {
							console.log(response);
							jQuery('#get_result').html("<div class='alert alert-danger'>Username does not exist</div>").show();
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