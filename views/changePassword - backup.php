<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="" />
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<script>
			function validate(){
				$pass = document.getElementById("new_password").value.trim();
				$confirmPass = document.getElementById("confirm_password").value;

				if($pass == null || $pass == "" || $confirmPass == null || $confirmPass == ""){
					alert("Please Fill all the fields!");
					return false;
				}
			}
		</script>

		<?php include("cssAssets.php");?>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="panel panel-sign">
					<div class="panel-body">
						<form action="<?php echo site_url('login/passwordChangeUpdateForm') ?>" method="post" onsubmit="return validate();">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $title;?>
								</div>
							</div>
							<div class="form-group mb-lg">
								<label>Username</label>
								<div class="input-group input-group-icon">
									<input name="user_name" id="user_name" type="text" value="<?php echo $username;?>" class="form-control input-lg" readonly />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>
							<!-- <div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Current Password</label>
								</div>
								<div class="input-group input-group-icon">
									<input name="old_password" id="old_password" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div> -->
							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">New Password</label>
									<!--a href="pages-recover-password.html" class="pull-right">Lost Password?</a-->
								</div>
								<div class="input-group input-group-icon">
									<input name="new_password" id="new_password" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>
							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Confirm Password</label>
									<!--a href="pages-recover-password.html" class="pull-right">Lost Password?</a-->
								</div>
								<div class="input-group input-group-icon">
									<input type="password" name="confirm_password" id="confirm_password" class="form-control input-lg" onkeyup="checkPass(); return false;"/>
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
								<div class="text-center">
								<span id="confirmMessage"></span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-offset-4 col-sm-4 text-center">
									<input type="submit" name="submit_login" class="btn btn-primary hidden-xs" value="Update">
									<input type="submit" name="submit_login" class="btn btn-primary btn-block btn-lg visible-xs mt-lg" value="Update">
								</div>
							</div>

							<div class="row">
								<div class="text-center">
									<font size="2" color="red">N.B. After Changing Password, Please login again.</font>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<?php include("jsAssets.php");?>

	</body>
</html>
<script type="text/javascript">
	function checkPass()
		{
			//Store the password field objects into variables ...
			var user_name = document.getElementById('user_name').value;
			var old_password = document.getElementById('old_password').value;
			if(old_password==""){
				alert("Please enter your current password.");
			}
			var new_password = document.getElementById('new_password');
			var confirm_password = document.getElementById('confirm_password');
			//Store the Confimation Message Object ...
			var message = document.getElementById('confirmMessage');
			//Set the colors we will be using ...
			var goodColor = "#66cc66";
			var badColor = "#ff6666";
			//Compare the values in the password field 
			//and the confirmation field
			if(new_password.value == confirm_password.value){
				//The passwords match. 
				//Set the color to the good color and inform
				//the user that they have entered the correct password 
				confirm_password.style.backgroundColor = goodColor;
				message.style.color = goodColor;
				message.innerHTML = "Passwords Match!"
			}else{
				//The passwords do not match.
				//Set the color to the bad color and
				//notify the user.
				confirm_password.style.backgroundColor = badColor;
				message.style.color = badColor;
				message.innerHTML = "Passwords Not Match!"
			}
		}  
</script>