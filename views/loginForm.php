<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		
		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<?php include("cssAssets.php");?>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
                        <div class="row">
                            <div class="col-md-9">
                                <h2 style="color: #0dce0f; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase"><span>My port panel login</span>  </h2></div>
                            <div class="col-md-3">
                                <h2>
                                    <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
                                </h2>
                            </div>
                        </div>
					</div>
					<div class="panel-body">
						<form action="<?php echo site_url('LoginController/') ?>" method="post">
							<div class="form-group mb-lg">
								<label>Username</label>
								<div class="input-group input-group-icon">
									<input name="username" type="text" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Password</label>
									<!--a href="pages-recover-password.html" class="pull-right">Lost Password?</a-->
								</div>
								<div class="input-group input-group-icon">
									<input name="password" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<!--div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Remember Me</label>
									</div>
								</div-->
								<div class="col-sm-4 text-right">
									<input type="submit" name="submit_login" class="btn btn-primary hidden-xs" value="Sign In">
									<!--button type="submit" name="submit_login" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button-->
								</div>
							</div>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright 2020. All rights reserved. Powered by <a href="#">Chattogram Port Authority.</a>.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<?php include("jsAssets.php");?>

	</body><!--img src="http://www.ten28.com/fref.jpg"-->
</html>