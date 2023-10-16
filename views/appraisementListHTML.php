
<script language="JavaScript">
$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
        console.log($next.length);
        if (!$next.length) {
            //$next = $('[tabIndex=1]');
			form.submit();
        }
		else
        $next.focus();
    }
});
function setEquipCharge(myVal)
{
	//alert(myVal);
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
	xmlhttp.onreadystatechange=stateChangeEquipmentValue;
	xmlhttp.open("GET","<?php echo site_url('ajaxController/getEquipmentCharge')?>?equipID="+myVal,false);
	xmlhttp.send();
}
function stateChangeEquipmentValue()
{
	//alert("ddfd");
    if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	{
		//alert(xmlhttp.responseText);			  
		var val = xmlhttp.responseText;
		var jsonData = JSON.parse(val);
		//var jval=jsonData[0].myval;
		//alert("J val:"+jval);
		var equip_charge=document.getElementById("equip_charge");
		//var selectList=document.getElementById("type"+jval);
		//removeOptions(selectList);
		//alert(xmlhttp.responseText);
		for (var i = 0; i < jsonData.length; i++) 
		{
			//alert(jsonData[i].name);
			equip_charge.value=jsonData[i].equipment_charge;
			//var option = document.createElement('option');
			//option.value = jsonData[i].block;
			//option.text = jsonData[i].block;
			//selectList.appendChild(option);
		}
    }
}
function getCnfCode(val) 
{	
	//alert(val);		
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
	xmlhttp.open("GET","<?php echo site_url('ajaxController/getCnfCode')?>?cnf_lic_no="+val,false);
	xmlhttp.send();
		  
}

function stateChangeValue()
{
	//alert("ddfd");
    if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	{
		//alert(xmlhttp.responseText);			  
		var val = xmlhttp.responseText;
		var jsonData = JSON.parse(val);
		//var jval=jsonData[0].myval;
		//alert("J val:"+jval);
		var cnfCodeTxt=document.getElementById("cnfName");
		//var selectList=document.getElementById("type"+jval);
		//removeOptions(selectList);
		//alert(xmlhttp.responseText);
		for (var i = 0; i < jsonData.length; i++) 
		{
			//alert(jsonData[i].name);
			cnfCodeTxt.value=jsonData[i].name;
			//var option = document.createElement('option');
			//option.value = jsonData[i].block;
			//option.text = jsonData[i].block;
			//selectList.appendChild(option);
		}
    }
}
function validate()
{
	if( document.myChkForm.cnfLicense.value == "" )
	{
		alert( "Please provide Cnf License!" );
		document.myChkForm.cnfLicense.focus() ;
		return false;
	}
	if( document.myChkForm.cnfName.value == "" )
	{
		alert( "Please provide Cnf Name!" );
		document.myChkForm.cnfName.focus() ;
		return false;
	}
	if( document.myChkForm.beNo.value == "" )
	{
		alert( "Please provide BE No!" );
		document.myChkForm.beNo.focus() ;
		return false;
	}
	if( document.myChkForm.beDate.value == "" )
	{
		alert( "Please provide BE Date!" );
		document.myChkForm.beDate.focus() ;
		return false;
	}

	if( document.myChkForm.used_equipment.value == "" )
	{
		alert( "Please provide Used Equipment!" );
		document.myChkForm.used_equipment.focus() ;
		return false;
	}

	if( document.myChkForm.appraise_date.value == "" )
	{
		alert( "Please provide Appraisement Date!" );
		document.myChkForm.appraise_date.focus() ;
		return false;
	}
	if( document.myChkForm.carpainter_use.value == "" )
	{
		alert( "Please provide Carpainter Use!" );
		document.myChkForm.carpainter_use.focus() ;
		return false;
	}
	if( document.myChkForm.hosting_charge.value == "" )
	{
		alert( "Please provide Hosting Charge!" );
		document.myChkForm.hosting_charge.focus() ;
		return false;
	}
	if( document.myChkForm.extra_movement.value == "" )
	{
		alert( "Please provide Extra Movement!" );
		document.myChkForm.extra_movement.focus() ;
		return false;
	}
	if( document.myChkForm.scale_for.value == "" )
	{
		alert( "Please provide Scale!" );
		document.myChkForm.scale_for.focus() ;
		return false;
	}
	if(document.myChkForm.used_equipment.value != "0")
	{
		if( document.myChkForm.hosting_charge.value == "" ||  document.myChkForm.hosting_charge.value == "0")
		{
			alert( "Please Set Hosting Charge atleast 1 !" );
			document.myChkForm.hosting_charge.focus() ;
			return false;
		}
	}
	return( true );
}
  
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/appraisementCertifyList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()" style="padding:12px 20px;">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
								<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
								<input type="text" name="ddl_bl_no" id="ddl_bl_no" class="form-control login_input_text" tabindex="1" placeholder="BL No">
							</div>												
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">								
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
							</div>													
						</div>
					</div>	
				</form>
			</div>
		</section>
	</div>
</div>


<?php 
if(@$unstuff_flag>0)
{
?>
<div class="row">
	<div class="col-lg-12">
		<table border="0" bgcolor="#FFFFFF" align="center" style="width:100%">
			<TR>
				<TD align="center">	
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered mb-none" style="width:100%">
							<?php								
							$totcontainerNo="";
							if($rtnContainerList) 
							{
								$len=count($rtnContainerList);
								//echo "Length : ".$len;
								$j=0;
								for($i=0;$i<$len;$i++)
								{
							?>
							<?php 
									if($appraiseFlag==0) 
									{		 
							?>
								<tr>
									<th>Vessel Name</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Vessel_Name']) ?></td>
									<th>Rotation</th><th>:</th><td><?php print($rtnContainerList[$i]['Import_Rotation_No']); ?></td>
									<th>Bl No</th><th>:</th><td><?php print($rtnContainerList[$i]['BL_No']); ?></td>			
								</tr>
								<tr>									
									<th>Marks</th><th>:</th><td><?php echo str_replace(',',', ',$rtnContainerList[$i]['Pack_Marks_Number']); ?></td>
									<th>Quantity</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Pack_Number']);  ?></td>
									<th>Good's Description</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
								</tr>
								<tr>
									<th>Importer Name</th><th>:</th><td><?php print($rtnContainerList[$i]['Notify_name']); ?></td>
									<th>Pack Description</th><th>:</th><td><?php echo($rtnContainerList[$i]['Pack_Description']);  ?></td>
									<th>Yard/Shed</th><th>:</th><td><?php if($rtnContainerList[$i]['shed_yard']) echo($rtnContainerList[$i]['shed_yard']); ?></td>
								</tr>
								<tr>					
									<th>Bay Position</th>
									<th>:</th>
									<td>
										<?php 
											
												$lenUnit = count($rtnUnitList); 
												if($lenUnit>0)
												{ 
													for($j=0;$j<$lenUnit;$j++) 
													{ 
														echo $rtnUnitList[$j]['shed_loc'].",";
													}
												}
																						 
											?>
									</td>
									<th>Rcv Pack</th>
									<th>:</th>
									<td>
										<?php
											$lenUnit = count($rtnUnitList);
											if($lenUnit>0)
											{
												for($j=0;$j<$lenUnit;$j++) 
												{ 
													echo($rtnUnitList[$j]['rcvTally']).",";
												}	
											}
										?></td>
									<th>Rcv Unit</th>
									<th>:</th>
									<td>
										<?php 
											$lenUnit = count($rtnUnitList); 
											if($lenUnit>0)
											{
												for($j=0;$j<$lenUnit;$j++) 
												{ 
													echo($rtnUnitList[$j]['rcv_unit']).",";
												}
											}
											
										?></td>
								</tr>
								<tr>
									<th>Gross Weight</th><th>:</th><td><?php echo($rtnContainerList[$i]['Cont_gross_weight']); ?></td>
									<th colspan=6></th>
								</tr>
							</table>
							
							<br/>
							
							<div class="table-responsive">
								<table class="table table-bordered mb-none" style="width:60%;" >
									<tr align="center">
										<td><b>Container</b></td>
										<td><b>Seal</b></td>
										<td><b>Size</b></td>
										<td><b>Height</b></td>
									</tr>

									<?php
									for($i=0;$i<count($rtnContainerList);$i++)
									{
									?>
									<tr>
										<td align="center"><?php print($rtnContainerList[$i]['cont_number']);  ?></td>
										<td align="center"><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td>
										<td align="center"><?php print($rtnContainerList[$i]['cont_size']); ?></td>
										<td align="center"><?php print($rtnContainerList[$i]['cont_height']); ?></td>
									</tr>
									<?php 
									} 
									?>
								</table>
							</div>
				
							
							<br/>
							
							<form action="<?php echo site_url('report/appraisementVerify');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
								<input type="hidden" value="<?php echo  $verify_id?>" name="verify_id" style="width:200px;"/>
								<input type="hidden" value="<?php echo  $verify_num?>" name="verify_num" style="width:200px;"/>
								<input type="hidden" value="<?php echo  $ddl_imp_rot_no?>" name="ddl_imp_rot_no" style="width:200px;"/>
								<input type="hidden" value="<?php echo  $ddl_bl_no?>" name="ddl_bl_no" style="width:200px;"/>
								<!--input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id?>"-->
								<!--input type="hidden" id="userip" name="userip" value="<?php echo $userip?>"-->
								<div class="table-responsive">
								<table class="table table-bordered mb-none" style="width:100%">
								
									<tr align="center" style="background:#FFF"><td colspan="6"><font color="black"><b>Comment's</b></font></td></tr>
									<tr class="gridLight" >
										<th>Cnf License</th><th>:</th><td ><input type="text" value="<?php echo ($rtnContainerList[0]['cnf_lic_no']); ?>" id="cnfLicense" name="cnfLicense" style="width:200px;" onblur= "getCnfCode(this.value)"/></td>
										<th>Cnf Name</th><th>:</th><td ><input type="text" value="<?php echo ($rtnContainerList[0]['cnf_name']); ?>" id="cnfName" id="cnfName" name="cnfName" style="width:200px;"/></td>
									</tr>
									<tr class="gridLight" >
										<th>BE No</th><th>:</th><td ><input type="text" value="<?php echo ($rtnContainerList[0]['be_no']); ?>" id="beNo" name="beNo" style="width:200px;"/></td>
										<th>Be Date</th><th>:</th>
										<td>
											<input type="text" value="<?php if($rtnContainerList[0]['be_date'] == "" || $rtnContainerList[0]['be_date'] == "0000-00-00") echo date("Y-m-d"); else echo $rtnContainerList[0]['be_date'];?>" id="beDate" name="beDate" style="width:200px;"/>
											<script>
												$(function() {
													$( "#beDate" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												 });
												 });
											</script>
										</td>
									</tr>
									<tr class="gridLight" >
										<th>Used Equipment</th><th>:</th><td>
										<!--input type="text" value="<?php echo $used_equipment; ?>" id="used_equipment" name="used_equipment" style="width:200px;"/-->
										<select name="used_equipment" id="used_equipment" onchange="setEquipCharge(this.value)">  
											<option value="0">--------Select---------</option>
											<?php 
												for($i=0;$i<count($getUsedEquipment);$i++)
												{
												  echo '<option value="'.$getUsedEquipment[$i]['equipment_id'].'">'.$getUsedEquipment[$i]['equipment_name'].'</option>';
												}
											?>
											<!--option value="FLT 1-5 TON">FLT 1-5 TON</option> 
											<option value="FLT 6-20 TON">FLT 6-20 TON</option> 
											<option value="FLT 21-50 TON">FLT 21-50 TON</option> 
											<option value="CRANE 1-10 TON">CRANE 1-10 TON</option> 
											<option value="CRANE ABOVE 10 TON">CRANE ABOVE 10 TON</option--> 	
										</select>
										<input type="hidden" id="equip_charge" name="equip_charge" />
										</td>
										<th>Appraisement Date</th><th>:</th>
										<td>
											<input type="text" value="<?php if($appraise_date == "" || $appraise_date == "0000-00-00") echo date("Y-m-d"); else echo $appraise_date; ?>" id="appraise_date" name="appraise_date" style="width:200px;"/>
											<script>
												$(function() {
													$( "#appraise_date" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												 });
												 });
											</script>
										</td>
									</tr>
									<tr class="gridLight" >
										<th>Carpainter Use</th><th>:</th><td ><input type="text" value="<?php echo $carpainter_use; ?>" id="carpainter_use" name="carpainter_use" style="width:200px;"/></td>
										<th>Hosting Charge</th><th>:</th><td ><input type="text" value="<?php echo $hosting_charge; ?>" id="hosting_charge" name="hosting_charge" style="width:200px;"/></td>
									</tr>
									<tr class="gridLight" >
										<th>Extra Movement</th><th>:</th><td ><input type="text" value="<?php echo $extra_movement; ?>" id="extra_movement" name="extra_movement" style="width:200px;"/></td>
										<th>Scale For</th><th>:</th><td ><input type="text" value="<?php echo $scale_for; ?>" id="scale_for" name="scale_for" style="width:200px;"/></td>
									</tr>
									<tr align="center" style="background:#FFF"><td colspan="6"><font color="black"><b>Customs Appraiser Info</b></font></td></tr>
									<tr class="gridLight" >
										<th>Customs Appraiser Name</th><th>:</th><td ><input type="text" id="appraiser_name" name="appraiser_name" 
										style="width:200px;" value="<?php echo ($rtnContainerList[0]['custom_appraiser']); ?>"/></td>
										<th>Customs Appraiser Mobile No</th><th>:</th><td ><input type="text" id="appraiser_mobile" name="appraiser_mobile" 
										style="width:200px;" value="<?php echo ($rtnContainerList[0]['custom_appraiser_mobile']); ?>"/></td>
										
									</tr>
									<tr align="center" style="background:#FFF"><td colspan="6"><font color="black"><b>Jetty Sarkar Info</b></font></td></tr>
									<tr>
										<td colspan="6">
											<table bgcolor="#2AB1D6">
												<tr>
													<th>
														Jetty Sarkar Lic/No
													</th>
													<th>
														:
													</th>
													<td>
														<input type="text" id="jetty_sarkar_lic" name="jetty_sarkar_lic" style="width:100px;padding-left:20px" value="<?php echo ($rtnContainerList[0]['jetty_sirkar_lic']); ?>"/>
													</td>
													<th style="width:110px;">
														Jetty Sarkar Name
													</th>
													<th>
														:
													</th>
													<td>
														<input type="text" id="jetty_sarkar_name" name="jetty_sarkar_name" style="width:95px;" value="<?php echo ($rtnContainerList[0]['jetty_sirkar_name']); ?>"/>
													</td>
													<th>
														Jetty Sarkar Mobile 
													</th>
													<th>
														:
													</th>
													<td>
														<input type="text" id="jetty_sarkar_mob" name="jetty_sarkar_mob" style="width:110px;" value="<?php echo ($rtnContainerList[0]['jetty_sirkar_mobile']); ?>"/>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr style="background:#FFF"  align="center">				
										<td colspan="6" align="center">
											<!--input type="submit" class="login_button" value="SAVE"/-->
											<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
										</td>
										<?php 
									} 			// if($appraiseFlag==0)
									else 
									{ 
									?>
										<font color="green"><b>Appraisement Successfully Done.</b></font></br> <font color="red"><b>Rotation : <?php echo  $ddl_imp_rot_no?> and BL NO : <?php echo  $ddl_bl_no?></b></font>
									<?php 
									}
									?>
								</table>
								</div>
							</form>						
						</div>
					</div>
							<?php 
							break;
								}		// for ends
							}		// if($rtnContainerList)
							?>
				</TD>
			</TR>
		</table>	
	</div>
</div>
<?php
}
?>