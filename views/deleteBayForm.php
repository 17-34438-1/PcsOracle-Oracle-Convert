<style>
	.myButton 
	{
		-moz-box-shadow: 0px 0px 0px 0px #f0f7fa;
		-webkit-box-shadow: 0px 0px 0px 0px #f0f7fa;
		box-shadow: 0px 0px 0px 0px #f0f7fa;
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #33bdef), color-stop(1, #019ad2));
		background:-moz-linear-gradient(top, #33bdef 5%, #019ad2 100%);
		background:-webkit-linear-gradient(top, #33bdef 5%, #019ad2 100%);
		background:-o-linear-gradient(top, #33bdef 5%, #019ad2 100%);
		background:-ms-linear-gradient(top, #33bdef 5%, #019ad2 100%);
		background:linear-gradient(to bottom, #33bdef 5%, #019ad2 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bdef', endColorstr='#019ad2',GradientType=0);
		background-color:#33bdef;
		-moz-border-radius:6px;
		-webkit-border-radius:6px;
		border-radius:6px;
		border:1px solid #057fd0;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:15px;
		font-weight:bold;
		padding:6px 24px;
		text-decoration:none;
		text-shadow:0px -1px 0px #5b6178;
	}

	.myButton:hover 
	{
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #019ad2), color-stop(1, #33bdef));
		background:-moz-linear-gradient(top, #019ad2 5%, #33bdef 100%);
		background:-webkit-linear-gradient(top, #019ad2 5%, #33bdef 100%);
		background:-o-linear-gradient(top, #019ad2 5%, #33bdef 100%);
		background:-ms-linear-gradient(top, #019ad2 5%, #33bdef 100%);
		background:linear-gradient(to bottom, #019ad2 5%, #33bdef 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#019ad2', endColorstr='#33bdef',GradientType=0);
		background-color:#019ad2;
	}
	.myButton:active 
	{
		position:relative;
		top:1px;
	}
</style>
<script>
	function rtnConrfm()
	{
		if(confirm("Do you want to delete?"))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal"  action="<?php echo site_url("report/deleteBayPerformed");?>" method="post" onsubmit="return rtnConrfm()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation :<span class="required">*</span></span>
									<input style="width:250px" type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control login_input_text" placeholder="Rotation No">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Bay No.:<span class="required">*</span></span>
									<select style="width:250px" name="sValue" class="login_input_text">
										<option>----Select----</option>
											<?php 
												for($i=1;$i<=50;$i++){
													if($i<10)
													$iVal = "0".$i;
													else
													$iVal = $i;
											?>
											<option value="<?php echo $iVal;?>"><?php echo $iVal;?></option>
											<?php 
												}
											?>
									</select>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Delete</button>
								</div>													
							</div>
						</div>																		
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
