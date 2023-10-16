<script language="JavaScript">

function getMlo(val) 
{	
	//var serch_by = document.getElementById('serch_by').value;
	//document.getElementById('serch_value').value="";
	//alert(val);
	var strRot = val.replace("/", "_");
	//alert(strRot)
			
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
	xmlhttp.onreadystatechange=stateChangeValue;
	xmlhttp.open("GET","<?php echo site_url('AjaxController/getMlo')?>?rot="+strRot,false);
	xmlhttp.send();
		  
}

function stateChangeValue()
{
	//alert("ddfd");
    if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	{
      var selectList=document.getElementById("serch_by");
	  removeOptions(selectList);
	  //alert(xmlhttp.responseText);
	  var val = xmlhttp.responseText;
	  var jsonData = JSON.parse(val);
	  //alert(xmlhttp.responseText);
		for (var i = 0; i < jsonData.length; i++) 
		{
			var option = document.createElement('option');
			option.value = jsonData[i].cont_mlo;
			option.text = jsonData[i].cont_mlo;
			selectList.appendChild(option);
		}
    }
}
  
function removeOptions(selectbox)
{
    var i;
    for(i=selectbox.options.length-1;i>=1;i--)
    {
        selectbox.remove(i);
    }
}


</script>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/preAdvisedOffDockContByRotReport"); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" onblur="getMlo(this.value)">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">MLO <span class="required">*</span></span>
										<select name="serch_by" id="serch_by" class="form-control" required>
											<option value="all" selected style="width:130px;">All</option>
										</select>
									</div>												
								</div>

								<div class="col-md-offset-4 col-md-2">
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
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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
	<!-- end: page -->
</section>
</div>