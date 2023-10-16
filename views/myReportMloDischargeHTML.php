
<script>

function OrgNameChange(str){
	str = document.getElementById('org_name').value;
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var url = "<?php echo site_url('Report/getMloCodeOrg')?>?q="+str;
		

	
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangedMlo;
	xmlhttp.send();

}

function stateChangedMlo() 
{ 
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{ 
			
		var mlo_code = document.getElementById("mlo_code");
		
			removeOptions(mlo_code);
		
	
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
		
			if(jsonData.mlo_rslt.length > 0){
				var alloption = document.createElement('option');
				alloption.text = "ALL";
				alloption.value = "all";
				mlo_code.appendChild(alloption);
			}
			
			
			for(i=0;i<jsonData.mlo_rslt.length;i++)
			{
				var option = document.createElement('option');
				
				// option.value = jsonData.mlo_rslt[i].id;
				
				option.text = jsonData.mlo_rslt[i].mlocode;
				if(option.text!=""){
				mlo_code.appendChild(option);
				}

			}
		
	}
}

function myshowAgent(str)
{
	
	str = document.getElementById('txt_login').value;
	
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var url = "<?php echo site_url('Report/getAgentOrg')?>?q="+str;
	//alert(url);



	// alert(url);
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangedAgent;
	xmlhttp.send();
}




function stateChangedAgent() 
{ 

	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{ 
			
		var org_name = document.getElementById("org_name");
		//alert(org_name);
		var org_id = document.getElementById("org_id");
		 removeOptions(org_name);
		
		
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);

			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			
				
			for(i=0;i<jsonData.org_rslt.length;i++)
			{
				var option = document.createElement('option');
				
				option.value = jsonData.org_rslt[i].id;
				option.text = jsonData.org_rslt[i].Organization_Name;
				org_name.appendChild(option);

			}
	}
}

function removeOptions(org_name)
{
	//alert(org_name);
	var i;
	for(i=org_name.options.length-1;i>=1;i--)
	{
		org_name.remove(i);
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
					<form class="form-horizontal form-bordered" method="POST" 
					action="<?php echo base_url().'index.php/Report/mloDischargeSummeryView'; ?>" 
					target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" placeholder="Rotation No" onblur="myshowAgent();">
								</div>
								
								
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization Name <span class="required">*</span></span>
									<select name="txt_line" id="org_name" maxlength="50" onchange="OrgNameChange(this.value)" class="form-control">
										<option value="all">All</option>																																
									</select>

								</div>
								
						
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MLO Code <span class="required">*</span></span>
									
										<select name="ddl_Org_id" id="mlo_code" maxlength="50" class="form-control">
										<option value="">Select</option>
								
									</select>
									
								</div>												
							</div>
												
							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									
									<button type="submit" id="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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