 <?php if(substr($_SERVER['REMOTE_ADDR'],0,7)=="192.168" or substr($_SERVER['REMOTE_ADDR'],0,4)=="10.1") { ?>
 <script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagentlocal.js"> </script>
 <?php } else { ?>
 <script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>getagent.js"> </script>
 <?php } ?>

 <script>$

	function myShowMLO(imp_rotation)
	{ 
	imp_rotation = document.getElementById('txt_login').value;
	//alert(imp_rotation);
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	{
	alert ("Your browser does not support AJAX!");
	return;
	} 
	// var url=BASE_URL+"getmlocodeigm";
	// url=url+"?imp_rotation="+imp_rotation;
	var url = "<?php echo site_url('Report/getmlocodeigm')?>?imp_rotation="+imp_rotation;
	//alert(url);
	//url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChangedUdName;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	}

	function stateChangedUdName() 
	{ 
	if (xmlHttp.readyState==4)
	{ 
	//alert(xmlHttp.responseText);
	document.getElementById("mlocode").innerHTML=xmlHttp.responseText;

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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/myLCLManifestView'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Import Rotation No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" placeholder="Import Rotation No" onblur= "myShowMLO(this);">
								</div>

								<div class="input-group mb-md">
									<span class="input-group-addon span_width" id="lbl_line">MLO Code <span class="required">*</span></span>
									<div id="mlocode"></div>
								</div>												
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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