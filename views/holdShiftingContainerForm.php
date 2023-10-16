<script language="JavaScript">
function validate()
  {
	// if( document.myform.rotation.value == "" )
	 if(document.getElementById('rotation').value=="")
	 {
		alert( "Please provide rotation number." );
		document.getElementById('rotation').focus() ;
		return false;
	 }
	 if(document.getElementById('contNo').value=="")
	 {
		alert( "Please provide container number." );
		document.getElementById('contNo').focus() ;
		return false;
	 }
	
	if( document.querySelector('input[name="igmSt"]:checked').value=='No')
		{
			if(document.getElementById('cont_iso').value=="")
			{		 
				alert( "Please provide container ISO." );
				document.getElementById('cont_iso').focus() ;
				return false;
			}
			if(document.getElementById('cont_mlo').value=="")
			{		 
				alert( "Please provide MLO code of this container." );
				document.getElementById('cont_mlo').focus() ;
				return false;
			}
			if(document.getElementById('freight_kind').value=="")
			{		 
				alert( "Please provide container freight Kind." );
				document.getElementById('freight_kind').focus() ;
				return false;
			}	
			
		} 
	 
	 return true;
  }
	  
	  
	  
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


function fetchDataForHoldShiftingContainer()
{		
	if (window.XMLHttpRequest) 
	{
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var rotation = document.getElementById("rotation").value;
	var contNo = document.getElementById("contNo").value;
	// alert(bl_igm_type);
	
		
	
	var url="<?php echo site_url('AjaxController/fetchDataShiftingContainer')?>?rotation="+rotation+"&contNo="+contNo+"";
	// alert(url);
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangeHoldShifting;
	xmlhttp.send();

	
}


function stateChangeHoldShifting()
{	

		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			
			var cont_iso = document.getElementById('cont_iso');
			var cont_mlo = document.getElementById('cont_mlo');
			var freight_kind = document.getElementById('freight_kind');
		
			
			cont_iso.value = "";
			cont_mlo.value = "";
			freight_kind.value="";

			cont_iso.value = jsonData.cont_iso_type;				
			cont_mlo.value = jsonData.mlocode;
			freight_kind.value = jsonData.cont_status;
			
		
			
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
function validateDeletion()
	{
		if (confirm("Do you want to detete this container?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}

function enableDisableField(value)
{
	if(value=="No")
	{
		document.getElementById('cont_mlo').value=null;
		document.getElementById('cont_iso').value=null;
		document.getElementById('freight_kind').value=null;
		
		document.getElementById('cont_mlo').disabled=false;
		document.getElementById('cont_iso').disabled=false;
		document.getElementById('freight_kind').disabled=false;
	}
	else
	{
		document.getElementById('cont_mlo').value=null;
		document.getElementById('cont_iso').value=null;
		document.getElementById('freight_kind').value=null;
		
		document.getElementById('cont_mlo').disabled=true;
		document.getElementById('cont_iso').disabled=true;
		document.getElementById('freight_kind').disabled=true;
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
					<form id="myform" name="myform" class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Vessel/holdShiftingContainer") ?>"  onsubmit="return validate();">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">IGM Container? <span class="required">*</span></span>
										<input type="radio" name="igmSt" id="igmSt" value="Yes" onclick="enableDisableField(this.value)" checked <?php if($editFlag==1){ if( $igm_select_st==1){?>
										checked <?php } } ?> >Yes
										<input type="radio" name="igmSt" id="igmSt" value="No" onclick="enableDisableField(this.value) "  <?php if($editFlag==1){ if( $igm_select_st==0){?>
										checked <?php } } ?> >No
									</div>	
									<div class="input-group mb-md">
										<input type="hidden" name="hold_id" id="hold_id" class="form-control" value="<?php if($editFlag==1){ echo $hold_id; } ?>">

										<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" value="<?php if($editFlag==1){ echo $rotation; } ?>" placeholder="Rotation No">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
										<input type="text" name="contNo" id="contNo" class="form-control" value="<?php if($editFlag==1){ echo $unit_no; } ?>" placeholder="Container No" onblur="fetchDataForHoldShiftingContainer();">
									</div>	
									
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Container ISO <span class="required">*</span></span>
										<input type="text" name="cont_iso" id="cont_iso" class="form-control" value="<?php //if($editFlag==1){ echo $unit_iso; } ?>" placeholder="Container ISO" disabled>
									</div>
										
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Container MLO </span>
										<input type="text" name="cont_mlo" id="cont_mlo" class="form-control" value="<?php if($editFlag==1){ echo $mlo_code; } ?>" placeholder="Container MLO" disabled>
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Freight Kind</span>
										<select class="form-control"  id="freight_kind" name="freight_kind" >
											<option value="">--Select--</option>
											<option value="EMPTY">EMPTY</option>
											<option value="FCL" >FCL</option>
											<option value="LCL">LCL</option>
										</select>	
									</div>	

									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Remarks </span>
										<textarea type="text" name="remarks" id="remarks" class="form-control"  placeholder="Remarks"><?php if($editFlag==1){ echo $remarks; } ?></textarea>
									</div>												
								</div>
																				
								<div class="row">
										<div class="col-sm-12 text-center">
											 <?php if($editFlag==1){?>
											 <input class="mb-xs mt-xs mr-xs btn btn-success"  name="update" type="submit"  value="UPDATE" > 
											 <?php } else{?>
											  <input class="mb-xs mt-xs mr-xs btn btn-success"  name="save" type="submit"  value="SAVE" > 
											 <?php } ?> 
										</div>	
								
									<!--div class="col-sm-12 text-center">
										<?php if($editFlag==1){ ?>		
										<button type="submit" name="submit_save" value="2" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
										 <?php } else { ?> 
										 <button type="submit" name="submit_save" value="1" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
										 <?php } ?>
									</div-->													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
					<div class="panel-body">
					
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">SL</th>
								<th class="text-center">ROTATION</th>
								<th class="text-center">CONTAINER</th>
								<th class="text-center">MLO</th>
								<th class="text-center">SIZE</th>
								<th class="text-center">HEIGHT</th>

								<th class="text-center">Action</th>	
								<th class="text-center">Action</th>	
							</tr>
						</thead>
						<tbody>
							<?php 								
								for($i=0;$i<count($list);$i++){ 
							?>
							<tr class="gradeX">
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $list[$i]['rotation']?></td>
								<td align="center"><?php echo $list[$i]['unit_no']?></td>
								<td align="center"><?php echo $list[$i]['mlo_code']?></td>
								<td align="center"><?php echo $list[$i]['cont_size']?></td>
								<td align="center"><?php echo $list[$i]['cont_height']?></td>								
								<td align="center">
									<form action="<?php echo site_url('Vessel/holdShiftingContainerEdit');?>" method="POST" style="display:inline-block;">
										<input type="hidden" name="hold_id" id="hold_id" 
											value="<?php echo $list[$i]['hold_id'];?>">
											<?php if($list[$i]['berth_forwared_st']==1) { ?>
										<button type="submit" class="btn btn-primary btn-xs" disabled>Edit</button>
											<?php } else { ?>
										<button type="submit" class="btn btn-primary btn-xs">Edit</button>
											<?php } ?>
									</form>
								</td>	
								<td align="center">
									<form action="<?php echo site_url('Vessel/holdShiftingContainerDelete');?>" method="POST" style="display:inline-block;" onsubmit="return validateDeletion();" >
										<input type="hidden" name="hold_id" id="hold_id" 
											value="<?php echo $list[$i]['hold_id'];?>">
											<?php if($list[$i]['berth_forwared_st']==1) { ?>
										<button type="submit" class="btn btn-danger btn-xs" disabled>Delete</button>
											<?php } else { ?>
										<button type="submit" class="btn btn-danger btn-xs">Delete</button>
											<?php } ?>
									</form>
								</td>
												
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>					
			</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
