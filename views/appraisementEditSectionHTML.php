
<script language="JavaScript">

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
	xmlhttp.open("GET","<?php echo site_url('AjaxController/getEquipmentCharge')?>?equipID="+myVal,false);
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
	xmlhttp.open("GET","<?php echo site_url('AjaxController/getCnfCode')?>?cnf_lic_no="+val,false);
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
			cnfCodeTxt.value=jsonData[i].NAME;
			//var option = document.createElement('option');
			//option.value = jsonData[i].block;
			//option.text = jsonData[i].block;
			//selectList.appendChild(option);
		}
    }
}
function validate()
      {
		  //alert(document.myChkForm.used_equipment.value);
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
			  if( document.myChkForm.hosting_charge.value == "" ||  document.myChkForm.hosting_charge.value == 0)
			 {
				alert( "Please Set Hosting Charge atleast 1 !" );
				document.myChkForm.hosting_charge.focus() ;
				return false;
			 }
		 }
         return( true );
      }
  
  function validateInitial()
{
	if( document.myform.ddl_imp_rot_no.value == "" )
	{
		alert( "Please provide Rotation No!" );
		document.myform.ddl_imp_rot_no.focus() ;
		return false;
	}

	if( document.myform.ddl_bl_no.value == "" )
	{
		alert( "Please provide BL Number!" );
		document.myform.ddl_bl_no.focus() ;
		return false;
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
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/appraisementCertifyListEdit'; ?>" id="myform" 	name="myform" onsubmit="return validateInitial()">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
								<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
								<input type="text" name="ddl_bl_no" id="txt_login" class="form-control login_input_text" tabindex="1" placeholder="BL No">
							</div>												
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
							</div>													
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<font color="">
									<b>
										<?php 
											if(@$verify_number>0 or @$verify_num>0)
											{ 
												echo "<font color='green'><b>VERIFY NUMBER IS ".$verify_num."</b></font><br>";
											} 			 
											else 
											{ 
												echo @$msg;
											}
										?>
										<?php echo @$msgPO;?>
									</b>
								</font>
							</div>
						</div>
					</div>	
				</form>
			</div>
		</section>
	</div>
</div>

	<?php
/*****************************************************
Developed BY: Sourav Chakraborty
Software Developer
DataSoft Systems Bangladesh Ltd
******************************************************/

$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
?>





<?php 
if(@$unstuff_flag>0)
	{
?>
				
<div class="panel-body">
<table border="0"  width="100%" bgcolor="#FFFFFF" align="center">
	<!--<TR align="center"><TD colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></TD></TR>-->
	
	
	<TR><TD align="center">
	
		<table class="table table-bordered table-striped table-hover">

		<?php
			
			//include("mydbPConnection.php");
			$totcontainerNo="";
			if($rtnContainerList) {
			$len=count($rtnContainerList);
			//echo "Length : ".$len;
            $j=0;
            for($i=0;$i<$len;$i++){
				
			
			//echo $rtnYardPosition->fcy_time_in."<hr>";
			
		?>
		<?php if($appraiseFlag>0) { ?>
			<tr class="gridLight">
				<th style="background-color:#006bff45;" width="100px">Vessel Name</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Vessel_Name']) ?></td>
				<th style="background-color:#006bff45;" width="100px">Rotation</th><th>:</th><td><?php print($rtnContainerList[$i]['Import_Rotation_No']);  ?></td>
				<th style="background-color:#006bff45;" width="100px">Bl No</th><th>:</th><td><?php print($rtnContainerList[$i]['BL_No']);  ?></td>
				
				
			</tr >
			<tr class="gridLight">
				<th style="background-color:#006bff45;" width="100px">Container</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_number']); ?></td>
				<th style="background-color:#006bff45;" width="100px">Size</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_size']); ?></td>
				<th style="background-color:#006bff45;" width="100px">Status</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_status']); ?></td>
			</tr>
			<tr class="gridLight">	
				<!--th width="100px">C&F Name</th><th>:</th><td><?php print($rtnContainerList[$i]['cnf_name']); ?></td-->
				<th style="background-color:#006bff45;">Marks</th><th>:</th><td><?php echo str_replace(',',', ',$rtnContainerList[$i]['Pack_Marks_Number']); ?></td>
				<th style="background-color:#006bff45;">Quantity</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Pack_Number']);  ?></td>
				<th style="background-color:#006bff45;" width="150px">Good's Description</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
				
				
			</tr>
			<!--tr class="gridLight">
				<th width="150px">Good's Description</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
				<th>BE No</th><th>:</th><td><?php echo ($rtnContainerList[$i]['be_no']);  ?></td>
				<th>BE Date</th><th>:</th><td><?php echo ($rtnContainerList[$i]['be_date']);  ?></td>
			</tr-->
			<tr class="gridLight">
				<th style="background-color:#006bff45;">Importer Name</th><th>:</th><td><?php print($rtnContainerList[$i]['Notify_name']); ?></td>
				<th style="background-color:#006bff45;">Pack Description</th><th>:</th><td><?php echo($rtnContainerList[$i]['Pack_Description']);  ?></td>
				<th style="background-color:#006bff45;">Yard/Shed</th><th>:</th><td><?php echo($rtnContainerList[$i]['shed_yard']); ?></td>
			</tr>
			<tr class="gridLight">
				<th style="background-color:#006bff45;">Bay Position</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['shed_loc']).",";} ?></td>
				<th style="background-color:#006bff45;">Rcv Pack</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['rcvTally']).",";}  ?></td>
				<th style="background-color:#006bff45;">Rcv Unit</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['rcv_unit']).",";}?></td>
				<!--th>Bay Position</th><th>:</th><td><?php echo($rtnContainerList[$i]['shed_loc']); ?></td>
				<th>Rcv Pack</th><th>:</th><td><?php echo($rtnContainerList[$i]['rcvTally']);  ?></td>
				<th>Rcv Unit</th><th>:</th><td><?php echo($rtnContainerList[$i]['rcv_unit']); ?></td-->
			</tr>
			<tr class="gridLight">
				<th style="background-color:#006bff45;">Gross Weight</th><th>:</th><td><?php echo($rtnContainerList[$i]['Cont_gross_weight']); ?></td>
				
			</tr>
				
		
	</table>
	<br/>
	<form action="<?php echo site_url('report/appraisementVerifyEdit');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
		<input type="hidden" value="<?php echo  $verify_id?>" name="verify_id" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $verify_num?>" name="verify_num" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $ddl_imp_rot_no?>" name="ddl_imp_rot_no" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $ddl_bl_no?>" name="ddl_bl_no" style="width:200px;"/>
		<!--input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id?>"-->
		<!--input type="hidden" id="userip" name="userip" value="<?php echo $userip?>"-->
		
		<table class="table table-bordered table-striped table-hover">
			<tr align="center" style="background:#FFF"><td colspan="6"><font color="black"><b>Comment's</b></font></td></tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Cnf License</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo ($rtnContainerList[$i]['cnf_lic_no']); ?>" id="cnfLicense" name="cnfLicense"  onblur= "getCnfCode(this.value)"/></td>
				<th style="background-color:#006bff45;">Cnf Name</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo ($rtnContainerList[$i]['cnf_name']); ?>" id="cnfName" id="cnfName" name="cnfName"/></td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">BE No</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo ($rtnContainerList[$i]['be_no']); ?>" id="beNo" name="beNo"/></td>
				<th style="background-color:#006bff45;">Be Date</th><th>:</th>
					<td>
						<input type="date"  class="form-control"  value="<?php if($rtnContainerList[$i]['be_date'] == "" || $rtnContainerList[$i]['be_date'] == "0000-00-00") echo date("Y-m-d"); else echo $rtnContainerList[$i]['be_date'];?>" id="beDate" name="beDate" \/>
					</td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Used Equipment</th><th>:</th><td>
				<!--input type="text" value="<?php echo $used_equipment; ?>" id="used_equipment" name="used_equipment" style="width:200px;"/-->
				
				
				
				<select name="used_equipment" class="form-control" id="used_equipment" onchange="setEquipCharge(this.value)">  
					<option value="0">--------Select---------</option>
					<?php for($i=0;$i<count($getUsedEquipment);$i++)
						{ ?>
					<option value="<?php echo $getUsedEquipment[$i]['equipment_id'] ?>" <?php if($getUsedEquipment[$i]['equipment_id']==$used_equipment){?> selected <?php } ?>><?php echo $getUsedEquipment[$i]['equipment_name'];?></option> 
						<?php }?>	
				</select>	
				<input type="hidden" id="equip_charge" name="equip_charge" value="<?php if($equip_charge == "") echo "0.0"; else echo $equip_charge; ?>"/>
				</td>
				<th style="background-color:#006bff45;">Appraisement Date</th><th>:</th>
					<td>
						<input type="date" class="form-control" value="<?php if($appraise_date == "" || $appraise_date == "0000-00-00") echo date("Y-m-d"); else echo $appraise_date; ?>" id="appraise_date" name="appraise_date"/>
					</td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Carpainter Use</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $carpainter_use; ?>" id="carpainter_use" name="carpainter_use"/></td>
				<th style="background-color:#006bff45;">Hosting Charge</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $hosting_charge; ?>" id="hosting_charge" name="hosting_charge"/></td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Extra Movement</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $extra_movement; ?>" id="extra_movement" name="extra_movement"/></td>
				<th style="background-color:#006bff45;">Scale For</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $scale_for; ?>" id="scale_for" name="scale_for"/></td>
			</tr>
			<tr style="background:#FFF"  align="center">
				<td colspan="6" align="center"><input type="submit" class="btn btn-success" value="UPDATE"/></td>
				
					<!--td colspan="6" align="center"><input type="submit" class="login_button" value="SAVE"/></td-->
				<?php } else { ?>
					<!--font color="green"><b>Appraisement Successfully Done.</b></font></br> <font color="red"><b>Rotation : <?php echo  $ddl_imp_rot_no?> and BL NO : <?php echo  $ddl_bl_no?></b></font-->
					<font color="orange"><b>Appraisement Not Done Yet.</b></font></br> <font color="red"><b>Rotation : <?php echo  $ddl_imp_rot_no?> and BL NO : <?php echo  $ddl_bl_no?></b></font>
				<?php } ?>
			</tr>
		</table>
	</form>
<?php }
			
			
			}?>
		
	
</TD></TR>
<br/>
</table>
</div>
<?php 
}
			else{
				echo "";
				
			}
?>

		 <!--</div>-->
		 </div>
         
		  </form>
          <div class="clr"></div>
        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <div class="clr"></div>
    </div>
	
  </div>
</section>