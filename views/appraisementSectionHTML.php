
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
		// if( document.myChkForm.cnfLicense.value == "" )
        // {
        //     alert( "Please provide Cnf License!" );
        //     document.myChkForm.cnfLicense.focus() ;
        //     return false;
        // }
		// if( document.myChkForm.cnfName.value == "" )
        // {
        //     alert( "Please provide Cnf Name!" );
        //     document.myChkForm.cnfName.focus() ;
        //     return false;
        // }
		// if( document.myChkForm.beNo.value == "" )
        // {
        //     alert( "Please provide BE No!" );
        //     document.myChkForm.beNo.focus() ;
        //     return false;
        // }
		// if( document.myChkForm.beDate.value == "" )
        // {
        //     alert( "Please provide BE Date!" );
        //     document.myChkForm.beDate.focus() ;
        //     return false;
        // }
      
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

		if (confirm('Are you sure?')) 
		{
			return true;
		}
		else
		{
			return false;
		}

		return false;
        // return( true );
      }
  
  function validateInitial()
{
	if( document.myform.ddl_imp_rot_no.value == "" )
	{
		alert( "Please provide Reg No!" );
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
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/ReleaseOrderController/appraisementCertifyList'; ?>" id="myform" name="myform" onsubmit="return validateInitial()" style="padding:12px 20px;">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Reg No <span class="required">*</span></span>
								<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control login_input_text" autofocus= "autofocus" placeholder="Reg No">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
								<input type="text" name="ddl_bl_no" id="txt_login" class="form-control login_input_text" tabindex="1" placeholder="BL No">
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
$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
if(@$unstuff_flag>0)
			{
				if($contStatus!="" or $contStatus!=null)
				{
				?>
				
<!--div style="width:100%; height:500px; overflow-y:auto;"-->
<div class="panel-body">
<div style="width:100%;">
<table class="table table-bordered table-striped table-hover"  width="100%" bgcolor="#FFFFFF" align="center">
	<!--<TR align="center"><TD colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></TD></TR>-->
	
	
	<TR><TD align="center">
	
		<!--table border=1 cellspacing="2" cellpadding="1" bdcolor="#ffffff"-->
		<!-- <table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1" bgcolor="#2AB1D6"> -->

		<?php
			
			//include("mydbPConnection.php");
			$totcontainerNo="";
			if($rtnContainerList) {
			$len=count($rtnContainerList);
			//echo "Length : ".$len;
            $j=0;
			$i = 0;
            // for($i=0;$i<$len;$i++)
			// {
				
			
			//echo $rtnYardPosition->fcy_time_in."<hr>";
			
		?>
		<?php if($appraiseFlag==0) { ?>
			<table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1" bgcolor="#2AB1D6">
			<tr class="gridLight">
				<th width="100px" style="background-color:#006bff45;">Vessel Name</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Vessel_Name']) ?></td>
				<th width="100px" style="background-color:#006bff45;">Rotation</th><th>:</th><td><?php print($rtnContainerList[$i]['Import_Rotation_No']);  ?></td>
				<th width="100px" style="background-color:#006bff45;">BL No</th><th>:</th><td><?php print($rtnContainerList[$i]['BL_No']);  ?></td>
				
				
			</tr >
			<!--tr class="gridLight">
				<th width="100px">Container</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_number']); ?></td>
				<th width="100px">Size</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_size']); ?></td>
				<th width="100px">Status</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_status']); ?></td>
			</tr-->
			<tr class="gridLight">	
				<!--th width="100px">C&F Name</th><th>:</th><td><?php print($rtnContainerList[$i]['cnf_name']); ?></td-->
				<th style="background-color:#006bff45;" >Marks</th><th>:</th><td><?php echo str_replace(',',', ',$rtnContainerList[$i]['Pack_Marks_Number']); ?></td>
				<th style="background-color:#006bff45;">Quantity</th><th>:</th><td><?php echo ($rtnContainerList[$i]['Pack_Number']);  ?></td>
				<th width="150px" style="background-color:#006bff45;">Goods Desc.</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
				
				
			</tr>
			<!--tr class="gridLight">
				<th width="150px">Good's Description</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
				<th>BE No</th><th>:</th><td><?php echo ($rtnContainerList[$i]['be_no']);  ?></td>
				<th>BE Date</th><th>:</th><td><?php echo ($rtnContainerList[$i]['be_date']);  ?></td>
			</tr-->
			<tr class="gridLight">
				<th style="background-color:#006bff45;">Importer Name</th><th>:</th><td><?php print($rtnContainerList[$i]['Notify_name']); ?></td>
				<th style="background-color:#006bff45;">Pack Description</th><th>:</th><td><?php echo($rtnContainerList[$i]['Pack_Description']);  ?></td>
				<th style="background-color:#006bff45;">Yard/Shed</th><th>:</th><td><?php echo @$rtnContainerList[$i]['shed_yard']; ?></td>
			</tr>
			<?php if($contStatus=="LCL") { ?>
			<tr class="gridLight">
				
				<th style="background-color:#006bff45;">Bay Position</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['shed_loc']).",";} ?></td>
				<th style="background-color:#006bff45;">Rcv Pack</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['rcvTally']).",";}  ?></td>
				<th style="background-color:#006bff45;">Rcv Unit</th><th>:</th><td><?php $lenUnit = count($rtnUnitList); for($j=0;$j<$lenUnit;$j++) { echo($rtnUnitList[$j]['rcv_unit']).",";}?></td>
			</tr>
			<?php } ?>
			<tr class="gridLight">
				<th style="background-color:#006bff45;">Gross Weight</th><th>:</th><td><?php echo($rtnContainerList[$i]['Cont_gross_weight']); ?></td>
				<th colspan=6></th>

			</tr>
				
		
	</table>
	<br/>
	
	<!--table border="0" cellspacing="2" cellpadding="1" bdcolor="#ffffff" width=60%" style="margin-right:180px"-->
	<table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1"  width="60%" bgcolor="#2AB1D6" style="margin-right:180px">
		<tr class="gridLight" style="background-color:#006bff45;">
			<th>Container</th>
			<th>Seal</th>
			<th>Size</th>
			<th>Height</th>
		</tr>

		<?php
		for($k=0;$k<count($rtnContainerList);$k++)
		{
		?>
		<tr class="gridLight" >
			<td><?php print($rtnContainerList[$k]['cont_number']);  ?></td>
			<td><?php print($rtnContainerList[$k]['cont_seal_number']); ?></td>
			<td><?php print($rtnContainerList[$k]['cont_size']); ?></td>
			<td><?php print($rtnContainerList[$k]['cont_height']); ?></td>
		</tr>
		<?php 
		} 
		?>
	</table>
	<br/>
	<form action="<?php echo site_url('ReleaseOrderController/appraisementVerify');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
		<input type="hidden" value="<?php echo $verify_id?>" name="verify_id" style="width:200px;"/>
		<input type="hidden" value="<?php echo $type; ?>" name="type" />
		<input type="hidden" value="<?php echo $id; ?>" name="id" />
		<!-- <input type="hidden" value="<?php //echo  $verify_num?>" name="verify_num" style="width:200px;"/> -->
		<input type="hidden" value="<?php echo  $ddl_imp_rot_no?>" name="ddl_imp_rot_no" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $ddl_bl_no?>" name="ddl_bl_no" style="width:200px;"/>
		<!--input type="hidden" id="login_id" name="login_id" value="<?php //echo $login_id?>"-->
		<!--input type="hidden" id="userip" name="userip" value="<?php //echo $userip?>"-->
		
		<table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6">
			<tr align="center"><td colspan="6"><font color="black"><b>Comments</b></font></td></tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">C&F License</th>
				<th>:</th>
				<td>
					<input type="text" class="form-control" value="<?php echo @$rtnContainerList[0]['cnf_lic_no']; ?>" id="cnfLicense" 
					name="cnfLicense" style="width:200px;" onblur= "getCnfCode(this.value)"/>
				</td>
				<th style="background-color:#006bff45;">C&F Name</th>
				<th>:</th>
				<td>
					<input type="text" class="form-control" value="<?php echo @$rtnContainerList[0]['cnf_name']; ?>" id="cnfName" id="cnfName" 
					name="cnfName" style="width:200px;"/>
				</td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">BE No</th>
				<th>:</th>
				<td>
					<input type="text" class="form-control" value="<?php echo @$rtnContainerList[$i]['be_no']; ?>" id="beNo" name="beNo" 
					style="width:200px;"/>
				</td>
				<th style="background-color:#006bff45;">Be Date</th>
				<th>:</th>
				<td>
					<input type="date" class="form-control" value="<?php if( @$rtnContainerList[0]['be_date'] == "" || @$rtnContainerList[0]['be_date'] == "0000-00-00") echo date("Y-m-d"); else echo @$rtnContainerList[$i]['be_date'];?>" id="beDate" name="beDate" style="width:200px;"/>
					
				</td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Used Equipment</th>
				<th>:</th>
				<td>
					<select name="used_equipment" class="form-control" id="used_equipment" style="width:200px;" onchange="setEquipCharge(this.value)">  
						<option value="0">--------Select---------</option>
						<?php 
							for($l=0;$l<count($getUsedEquipment);$l++)
							{
						?>
							<option value="<?php echo $getUsedEquipment[$l]['equipment_id']; ?>" <?php if($getUsedEquipment[$l]['equipment_id'] == $used_equipment){ echo "selected";} ?> ><?php echo $getUsedEquipment[$l]['equipment_name']; ?></option>
						<?php
							}
						?>	
					</select>
					<input type="hidden" id="equip_charge" name="equip_charge" />
				</td>
				<th style="background-color:#006bff45;">Appraisement Date</th>
				<th>:</th>
				<td>
					<input type="date" class="form-control" value="<?php if($appraise_date == "" || $appraise_date == "0000-00-00") echo date("Y-m-d"); else echo $appraise_date; ?>" id="appraise_date" name="appraise_date" style="width:200px;"/>
				</td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Carpainter Use</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $carpainter_use; ?>" id="carpainter_use" name="carpainter_use" style="width:200px;"/></td>
				<th style="background-color:#006bff45;">Hosting Charge</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $hosting_charge; ?>" id="hosting_charge" name="hosting_charge" style="width:200px;"/></td>
			</tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Extra Movement</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $extra_movement; ?>" id="extra_movement" name="extra_movement" style="width:200px;"/></td>
				<th style="background-color:#006bff45;">Scale For</th><th>:</th><td ><input type="text" class="form-control" value="<?php echo $scale_for; ?>" id="scale_for" name="scale_for" style="width:200px;"/></td>
			</tr>
			<tr align="center"><td colspan="6"><font color="black"><b>Customs Appraiser Info</b></font></td></tr>
			<tr class="gridLight" >
				<th style="background-color:#006bff45;">Customs Appraiser Mobile No</th><th>:</th><td ><input type="text" class="form-control" value="<?php if(isset($rtnContainerList)){ echo $rtnContainerList[$i]['custom_appraiser_mobile'];}?>" id="appraiser_mobile" name="appraiser_mobile" 
				style="width:200px;"/></td>
				<th style="background-color:#006bff45;">Customs Appraiser Name</th><th>:</th><td ><input type="text" class="form-control" value="<?php if(isset($rtnContainerList)){ echo $rtnContainerList[$i]['custom_appraiser'];}?>" id="appraiser_name" name="appraiser_name" 
				style="width:200px;"/></td>
			</tr>
			<tr align="center"><td colspan="6"><font color="black"><b>Jetty Sircar Info</b></font></td></tr>
			<tr class="gridLight">
				<td colspan="6">
					<table class="table table-bordered table-striped table-hover">
						<tr class="gridLight" style="border:1px solid blue;">
							<th style="background-color:#006bff45;">Jetty Sircar Lic/No</th>
							<th>:</th>

							<td>
								<input type="text" class="form-control" id="jetty_sarkar_lic" name="jetty_sarkar_lic" value="<?php if(isset($rtnContainerList)){ echo $rtnContainerList[$i]['jetty_sirkar_lic'];}?>"/>
							</td>

							<th style="background-color:#006bff45;">Jetty Sircar Name</th>
							<th>:</th>
							<td>
								<input type="text" class="form-control" id="jetty_sarkar_name" name="jetty_sarkar_name" value="<?php if(isset($rtnContainerList)){ echo $rtnContainerList[$i]['jetty_sirkar_name'];}?>"/>
							</td>

							<th style="background-color:#006bff45;">Jetty Sircar Mobile</th>
							<th>:</th>
							<td>
								<input type="text" class="form-control" id="jetty_sarkar_mob" name="jetty_sarkar_mob" value="<?php if(isset($rtnContainerList)){ echo $rtnContainerList[$i]['jetty_sirkar_mobile'];}?>"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr style="background:#FFF"  align="center">
					<?php if(isset($rtnContainerList)){
					?>
						<td colspan="6" align="center"><input type="submit" class="btn btn-success" value="UPDATE"/></td>
					<?php }else{ ?>
						<td colspan="6" align="center"><input type="submit" class="btn btn-success" value="SAVE"/></td>
					<?php } ?>
				<?php } else { ?>
					<font color="green"><b>Appraisement Successfully Done.</b></font></br> <font color="red"><b>Rotation : <?php echo  $ddl_imp_rot_no?> and BL NO : <?php echo  $ddl_bl_no?></b></font>
					<!--td colspan="6" align="center"><input type="submit" class="login_button" value="UPDATE"/></td-->
				<?php } ?>
			</tr>
			
		</table>
	</form>
	<?php 
		//}	
	}
	?>
		
	
	</TD></TR>
	<br/>
	</table>
	</div>
	</div>
	<?php 
		}
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
      <!-- <div class="sidebar" >
	   <?php include_once("mySideBar.php"); ?>
	  </div> -->
      <div class="clr"></div>
    </div>
	
  </div>
</section>