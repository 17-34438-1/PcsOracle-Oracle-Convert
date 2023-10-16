
<script language="JavaScript">
function changeField() 
{	
	var serch_by = document.getElementById('serch_by').value;
	
	if(serch_by=="equip" || serch_by=="euser")
	{
		//alert(serch_by);
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
		xmlhttp.open("GET","<?php echo site_url('AjaxController/logEquipName')?>?serch_by="+serch_by,false);
		xmlhttp.send();
		
	}
	else
	{
		var selectList=document.getElementById("serch_value");
	    removeOptions(selectList);
		// document.getElementById('combo').style.display="none";
	}	  
}

function stateChangeValue()
{
	
    if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	{
		//alert('ddd');
      var selectList=document.getElementById("serch_value");
	  removeOptions(selectList);
	  //alert(selectList);
	  var val = xmlhttp.responseText;
	//  alert(val);
	  var jsonData = JSON.parse(val);
	 // alert(jsonData.length);
		for (var i = 0; i < jsonData.length; i++) 
		{
			var option = document.createElement('option');
			option.value = jsonData[i].id;
			option.text = jsonData[i].id;
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

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/myEquipmentLoginLogoutView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
                                
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">From Date <span class="required">*</span></span>
                                    <input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">
                                </div>

                                <div class="input-group mb-md">
									<span class="input-group-addon span_width">Shift <span class="required">*</span></span>
                                    <select name="shift" id="shift" class="form-control" required>
                                        <option value="">shift</option>
                                        <option value="Day">Day</option>
										<option value="Night">Night</option>
                                    </select>
								</div>

                                <div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Criteria <span class="required">*</span></span>
                                    <select name="serch_by" id="serch_by" onchange="changeField();" class="form-control">
                                                <option value="all" selected style="width:110px;">ALL</option>
												<option value="equip">Equip Name</option>
												<option value="euser">User Name</option>
                                    </select>
								</div>

								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
                                    <div id="combo">
                                        <select name="serch_value" id="serch_value" class="form-control">
                                            <option value="" selected style="width:110px;">Select</option>
                                        </select>	
                                    </div>
								</div>												
							</div>

                            <br/><br/>

                            <div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div>
                            <div class="col-md-3">
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