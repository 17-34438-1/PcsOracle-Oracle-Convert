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
	xmlhttp.open("GET","<?php echo site_url('ajaxController/getMlo')?>?rot="+strRot,false);
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

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/wireHouseReport'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation: <span class="required">*</span></span>
									<input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container: <span class="required">*</span></span>
									<input type="text" name="cont" id="cont" class="form-control" placeholder="Container">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
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