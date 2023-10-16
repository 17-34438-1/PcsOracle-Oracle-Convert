
<script language="JavaScript">
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
		var cnfCodeTxt=document.getElementById("strCnfCode");
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
	if( document.myChkForm.strCnfLicense.value == "" )
	{
		alert( "Please provide C&F License No!" );
		document.myChkForm.strCnfLicense.focus() ;
		return false;
	}

	if( document.myChkForm.strCnfCode.value == "" )
	{
		alert( "Please provide C&F Name!" );
		document.myChkForm.strCnfCode.focus() ;
		return false;
	}
	if( document.myChkForm.strAgentDo.value == "" )
	{
		alert( "Please provide Agent DO!" );
		document.myChkForm.strAgentDo.focus() ;
		return false;
	}
	if( document.myChkForm.strDoDate.value == "" )
	{
		alert( "Please provide DO Date!" );
		document.myChkForm.strDoDate.focus() ;
		return false;
	}
	if( document.myChkForm.strBEno.value == "" )
	{
		alert( "Please provide BE No!" );
		document.myChkForm.strBEno.focus() ;
		return false;
	}
	if( document.myChkForm.strBEdate.value == "" )
	{
		alert( "Please provide BE Date!" );
		document.myChkForm.strBEdate.focus() ;
		return false;
	}
	if( document.myChkForm.strWRdate.value == "" )
	{
		alert( "Please provide WR Date!" );
		document.myChkForm.strWRdate.focus() ;
		return false;
	}
	if( document.myChkForm.strTonUpdt.value == "" )
	{
		alert( "Please provide Tonnage Value!" );
		document.myChkForm.strTonUpdt.focus() ;
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
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/lclAssignmentCertifyList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()" style="padding:12px 20px;">
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
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>
		</section>
	</div>
</div>

<?php 
if($unstuff_flag>0)
{
?>
<div style="width:100%; height:500px; overflow-y:auto;">
	<table border="0"  width="100%" style="border-color: white" align="center">	
		<TR>
			<TD align="center">	
				<div class="panel-body">
					<div class="table-responsive">
						<!--table border=1 cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6"-->
						<table class="table table-bordered mb-none">
						<?php
					
						//	include('Frontend/mydbPConnection.php');
							include('mydbPConnection.php');
							$totcontainerNo="";
							if($rtnContainerList) {
							$len=count($rtnContainerList);
							
							$i = 0;

							$containerNo1=$rtnContainerList[$i]['cont_number'];
							$rotaionNo=$rtnContainerList[$i]['Import_Rotation_No'];

						//	include("Frontend/dbConection.php");
							include("dbConection.php");
					
							$sqlYardPosition=mysqli_query($con_sparcsn4,"select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block from (
							select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,time_out as fcy_time_out from inv_unit a
							inner join 
							inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
							inner join
							argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv or h.gkey = a.cv_gkey)
							inner join
							argo_visit_details i ON h.cvcvd_gkey = i.gkey
							inner join
							vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rotaionNo' and a.id='$containerNo1'
							) as  tmp"
							);
								
							$rtnYardPosition=mysqli_fetch_object($sqlYardPosition);			
						?>
						
							<tr>
								<th>Discharge Time</th>
								<td align="center"><b>:</b></td>
								<td align="center"><?php print($rtnYardPosition->fcy_time_out); ?></td>
								<!--th width="100px">Container</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_number']);  ?></td-->
								<th>Receive Pack</th>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['rcv_pack']); ?></td>
							</tr>
							<tr align="center">
								<th>Marks & Number</th>
								<td align="center"><b>:</b></td>
								<td align="center"><?php echo str_replace(',',', ',$rtnContainerList[$i]['Pack_Marks_Number']); ?></td>
								
								<td width="150px"><b>Consignee Description</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['ConsigneeDesc']);  ?></td>
							</tr>
							<tr align="center">	
								<td><b>Yard / Shed</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['shed_yard']);  ?></td>
								
								<td><b>Position</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['shed_loc']);  ?></td>				
							</tr>
							<tr align="center">
								<td><b>Status</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['cont_status']); ?></td>
								
								<td><b>Discharge Time</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnYardPosition->fcy_time_in); ?></td>
							</tr>
							<tr align="center">					
								<td><b>Offdock Name</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['offdock_name']); ?></td>
								
								<td><b>Rotation</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['Import_Rotation_No']); ?></td>
								<!--th>Seal</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td-->
							</tr>
							<tr align="center">
								<td><b>Notify Description</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['NotifyDesc']); ?></td>
								
								<td><b>Cont. Type</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['cont_iso_type']); ?></td>
							</tr>
							<tr align="center">
								<td><b>Dest.</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[$i]['off_dock_id']); ?></td>
								
								<td colspan="3"></td>
							</tr>

							<!--tr class="gridLight">
								<th>Size</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_size']); ?></td>
								<th>Height</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_height']); ?></td>
							</tr>
							<tr class="gridLight">
								<th width="100px">Container</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_number']);  ?></td>
								<th>Seal</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td>
							</tr-->
						<?php	
							if($totcontainerNo!="")
								$totcontainerNo=$totcontainerNo.", ".$containerNo1;
							else
								$totcontainerNo=$containerNo1;

						//	mysql_close($con_sparcsn4);
						?>

				<!--tr><td colspan="16" align="center"><?php echo "Total Container: ". $j;?></td></tr-->
				<!--<tr><td colspan="16" align="left"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr>-->
					</table>
				</div>
			</div>
			<br/>

			<!--table border="0" cellspacing="2" cellpadding="1" bdcolor="#ffffff" width=60%" style="margin-right:180px"-->
			<table border="1" cellspacing="2" cellpadding="1"  width="60%" bgcolor="#2AB1D6" style="margin-right:180px">
				<tr align="center">
					<td><b>Container</b></td>
					<td><b>Seal</b></td>
					<td><b>Size</b></td>
					<td><b>Height</b></td>
				</tr>

				<?php
				for($i=0;$i<$len;$i++)
				{
				?>
				<tr class="gridLight">
					<td align="center"><?php print($rtnContainerList[$i]['cont_number']);  ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_size']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_height']); ?></td>
				</tr>
				<?php 
				} 
				?>
			</table>

	<br/>
	<form action="<?php echo site_url('Report/lclAssignmentVerify');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
		<input type="hidden" value="<?php echo  $verify_id; ?>" name="verify_id" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $verify_num; ?>" name="verify_num" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $ddl_imp_rot_no; ?>" name="verify_rot" style="width:200px;"/>
		<input type="hidden" value="<?php echo  $ddl_bl_no; ?>" name="verify_bl" style="width:200px;"/>
		
		<input type="hidden" value="<?php echo  $contStatus; ?>" name="contStatus" style="width:200px;"/>
		<table border=1 cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6">
			
			<tr>
				<td align="center"><b>C&f License</b></td>
				<td align="center"><b>:</b></td>
				<td><input type="text" id="strCnfLicense" value="<?php echo  $rtnContainerList[0]['cnf_lic_no']; ?>" name="strCnfLicense" onblur="getCnfCode(this.value)" style="width:200px;"/></td>
				
				<td align="center"><b>C&f Name</b></td>
				<td align="center"><b>:</b></td>
				<td><input type="text" id="strCnfCode" value="<?php echo $rtnContainerList[0]['cnf_name'];?>" name="strCnfCode" style="width:200px;"/></td>
			</tr>
			<tr>
				<td align="center"><b>Agent DO</b></td>
				<td align="center"><b>:</b></td>
				<td><input type="text"value="<?php echo  $rtnContainerList[0]['agent_do']; ?>" name="strAgentDo" style="width:200px;"/></td>
				
				<td align="center"><b>DO Date</b></td>
				<td align="center"><b>:</b></td>
				<td>
					<input type="date" id="strDoDate" value="<?php echo  $rtnContainerList[0]['do_date']; ?>" name="strDoDate" style="width:200px;"/>
					<script>
						$(function() {
						 $( "#strDoDate" ).datepicker({
						  changeMonth: true,
						  changeYear: true,
						  dateFormat: 'yy-mm-dd', // iso format
						 });
						 });
					</script>
				</td>
			</tr>
			<tr>
				<td align="center"><b>BE No</b></td>
				<td align="center"><b>:</b></td>
				<td><input type="text" value="<?php echo  $rtnContainerList[0]['be_no']?>" name="strBEno" style="width:200px;"/></td>
				
				<td align="center"><b>BE Date</b></td>
				<td align="center"><b>:</b></td>
				<td>
					<input type="date" id="strBEdate" value="<?php echo  $rtnContainerList[0]['be_date']; ?>" name="strBEdate" style="width:200px;"/>
					<script>
						$(function() {
						 $( "#strBEdate" ).datepicker({
						  changeMonth: true,
						  changeYear: true,
						  dateFormat: 'yy-mm-dd', // iso format
						 });
						 });
					</script>
				</td>										
			</tr>
			<tr class="gridDark">				
				<td align="center"><b>W/R UP TO DATE</b></td>
				<td align="center"><b>:</b></td>
				<td>
					<input type="date" id="strWRdate" value="<?php echo  $rtnContainerList[0]['wr_upto_date']; ?>" name="strWRdate" style="width:200px;"/>
					<script>
						$(function() {
						 $( "#strWRdate" ).datepicker({
						  changeMonth: true,
						  changeYear: true,
						  dateFormat: 'yy-mm-dd', // iso format
						 });
						 });
					</script>
				</td>
				
				<td align="center"><b>Tonnage Update</b></td>
				<td align="center"><b>:</b></td>
				<td><input type="text" value="<?php echo  $rtnContainerList[0]['update_ton']; ?>" name="strTonUpdt" style="width:200px;"/></td>
			</tr>
			<?php 
				if ($verify_num >0)
				{
					?>
					<tr align="center">
						<!--td colspan="6" align="center"><input type="submit" class="login_button" value="CERTIFY"/></td-->
						<td colspan="6" align="center"><button type="submit" name="btn_certify" class="mb-xs mt-xs mr-xs btn btn-success">CERTIFY</button></td>
					</tr>
			<?php
				}
				else{
					?>
					<tr align="center">
						<!--td colspan="6" align="center"><input type="submit" class="login_button" value="VERIFY"/></td-->
						<td colspan="6" align="center"><button type="submit" name="btn_verify" class="mb-xs mt-xs mr-xs btn btn-success">VERIFY</button></td>
					</tr>
					
			<?php 
				}
			?>
			
		</table>
	</form>
	</div>
<?php //}
			
			
			}?>
		
	
</TD></TR>
<br/>
<?php 
	// mysql_close($con_cchaportdb);?>
</table>
</div>
<?php 
}
?>