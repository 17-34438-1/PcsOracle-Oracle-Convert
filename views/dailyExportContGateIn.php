<script type="text/javascript">
	// function chkBlankField()
	// {
		// if(document.getElementById("sampleText").value=="" )
		// {
			// alert("Please fill sampleText");
			// return false
		// }
		// if(document.getElementById("sampleDate").value=="" )
		// {
			// alert("Please fill sampleDate");
			// return false
		// }
		// if(document.getElementById("sampleAjax").value=="" )
		// {
			// alert("Please fill sampleAjax");
			// return false
		// }
		// if(document.getElementById("sampleAjaxReturn").value=="" )
		// {
			// alert("Please fill sampleAjaxReturn");
			// return false
		// }
		// else
		// {
			// return true;
		// }
	// }
	
	// function getAjaxValue()
	// {
		// var sampleAjax=document.getElementById("sampleAjax").value;
		
		// if (window.XMLHttpRequest) 
		// {
		  // xmlhttp=new XMLHttpRequest();
		// } 
		// else 
		// {  
			// xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		// }	
	
		// var url = "<?php echo site_url('ajaxController/getAjaxValue')?>?sampleAjax="+sampleAjax;
	// //	alert(url);
		// xmlhttp.onreadystatechange=stateAjaxValue;
		// xmlhttp.open("GET",url,false);
					
		// xmlhttp.send();
	// }
	
	// function stateAjaxValue()
	// {			
		// if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		// {			
			// var val = xmlhttp.responseText;	
			// var jsonData = JSON.parse(val);
			
			// document.sampleForm.sampleAjaxReturn.value=jsonData[0].name;													
		// }
	// }
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">					
					<form class="form-horizontal form-bordered" name="sampleForm" id="sampleForm" action="<?php echo site_url("report/dailyExportContGateInAction"); ?>" method="POST" onsubmit="return chkBlankField();" enctype="multipart/form-data" target="_blank">		
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div align="center"  >
									<b><?php echo $title; ?></b>
								</div>
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
									<input type="text" id="gateInRotation" name="gateInRotation" class="form-control login_input_text" />
								</div>																	
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Submit</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
