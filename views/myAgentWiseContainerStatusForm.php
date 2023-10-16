<script language="JavaScript">
function changeField() 
{	
	var serch_by = document.getElementById('serch_by').value;
	document.getElementById('serch_value').value="";
	/*alert(serch_by);*/
	if(serch_by=="offdoc" || serch_by=="pod")
	{
		
		document.getElementById('gen').style.display="none";
		document.getElementById('combo').style.display="inline";
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
		xmlhttp.open("GET","<?php echo site_url('ajaxController/ajaxValue')?>?serch_by="+serch_by,false);
		xmlhttp.send();
	}
	else
	{
		document.getElementById('gen').style.display="inline";
		document.getElementById('combo').style.display="none";
	}	  
}

function stateChangeValue()
{
    if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	{
      var selectList=document.getElementById("serch_combo");
	  removeOptions(selectList);
	  var val = xmlhttp.responseText;
	  var jsonData = JSON.parse(val);
	  //alert(jsonData.length);
		for (var i = 0; i < jsonData.length; i++) 
		{
			var option = document.createElement('option');
			option.value = jsonData[i].id;
			option.text = jsonData[i].name;
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
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" 
								action="<?php echo site_url('report/myAgentWiseContainerStatusReport') ?>" target="_blank">
							
								<div class="form-group">
									<label class="col-md-2 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-4 control-label">Search Value</label>
											<div class="col-md-8">
												<select name="serch_by" id="serch_by" class="form-control mb-md" onchange="changeField()" required>
													<option value="" selected>--Select--</option>
													<option value="rot">Rotation</option>
													<option value="cont">CONTAINER</option>
													<option value="mlo">MLO</option>
													<option value="offdoc">OFF-DOC</option>
													<option value="pod">POD</option>
												</select>
											</div>
										</div>
										<div class="form-group" id="gen">
											<label class="col-md-4 control-label" for="serch_value">Search Value</label>
											<div class="col-md-8">
												<input type="text" name="serch_value" id="serch_value" class="form-control">
											</div>
										</div>
										<div class="form-group" id="combo" style="display:none;">
											<label class="col-md-4 control-label">Search Value</label>
											<div class="col-md-8">
												<select class="form-control mb-md" name="serch_combo" id="serch_combo">
													<option value="" selected>Select</option>
												</select>
											</div>
										</div>
									</div>										
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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