<?php 
	// if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1") 
	if(substr($_SERVER['SERVER_NAME'],0,7)=="192.168" or substr($_SERVER['SERVER_NAME'],0,4)=="10.1") 
	{ 
		?>
		<script>//alert("11");</script>
		<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagentlocal.js"> </script>
		<?php 
	} 
	else 
	{ 
	//	if($_SERVER['SERVER_NAME']=="115.127.51.199")
		if($_SERVER['SERVER_NAME']=="122.152.54.179")
		{
			?>
			<script>//alert("22");</script>
			<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagent.js"> </script>
			<?php 
		}
		else
		{			
			?>
			<script>//alert("33");</script>
			<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagentBrac.js"> </script>
			<?php
		}
	} 
?>

<script>
	function validate(){
		var rot = document.getElementById('txt_login').value;
		if(rot == ''){
			alert("Please give a roation!");
			return false;
		}
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/myBirthIGMListView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
                                
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
                                    <input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" onblur="myShowMLO(this);" value="">
                                </div>

								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MLO Code <span class="required">*</span></span>
                                    <div id="mlocode"></div>
								</div>												
							</div>
							
							<div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="CStatus" value="Both" checked>
									<label for="radioExample3">BOTH</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="CStatus" value="FCL">
									<label for="radioExample3">FCL</label>
								</div>
							</div>
                            <div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="CStatus" value="LCL">
									<label for="radioExample3">LCL</label>
								</div>
							</div>

                            <br/><br/>

                            <div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div>
                            <div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div>
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>	

</section>