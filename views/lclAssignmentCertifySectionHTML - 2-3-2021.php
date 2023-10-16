
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
		// if( document.myChkForm.strBEno.value == "" )
		// {
			// alert( "Please provide BE No!" );
			// document.myChkForm.strBEno.focus() ;
			// return false;
		// }
		// if( document.myChkForm.strBEdate.value == "" )
		// {
			// alert( "Please provide BE Date!" );
			// document.myChkForm.strBEdate.focus() ;
			// return false;
		// }
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

	// function chkValidData()
	// {				
		// var rotNo=document.getElementById('ddl_imp_rot_no').value;
		// var blNo=document.getElementById('ddl_bl_no').value;
		
		// if (window.XMLHttpRequest) 
		// {
			// xmlhttp=new XMLHttpRequest();
		// } 
		// else 
		// {  
			// xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		// }
		
		// // xmlhttp.onreadystatechange=stateChangegeValidData;
		// xmlhttp.onreadystatechange=stateChangegeValidData;
		
		// var url="<?php echo site_url('AjaxController/chkValidData')?>?blNo="+blNo+"&rotNo="+rotNo;
		
		// xmlhttp.open("GET",url,false);	
		
		// xmlhttp.send();	 
	// }

	// function stateChangegeValidData()
	// {				
		// if (this.readyState == 4 && this.status == 200) 
		// {
			// var val = xmlhttp.responseText;
			
			// var jsonData = JSON.parse(val);
			
			// // alert("rtnvalue : "+jsonData.value);
			// if(jsonData.value=="FCL" || jsonData.value=="LCL")
			// {
				// alert("rtnvalue : "+jsonData.value);
				// return true;
			// }
			// else
			// {
				// alert("not");
				// return false;
			// }
			
		// }
		// // return false;
	// }
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/lclAssignmentCertifyList'; ?>"  id="myform" name="myform" onsubmit="return validateInitial()" style="padding:12px 20px;">
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
								<?php if($msg!="" and $msg!="rtn"){echo $msg;} ?>
							</div>													
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">								
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
if($unstuff_flag>0 or $msg=="rtn")
{
?>
<!--div style="width:100%; height:500px; overflow-y:auto;"-->
	<table border="0"  width="100%" style="border-color: white" align="center">	
		<TR>
			<TD align="center">	
				<div class="panel-body">
					<div class="table-responsive">
						<!--table border=1 cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6"-->
						<table class="table table-bordered mb-none" style="width:100%">
						<?php
							// $totcontainerNo="";
							// if($rtnContainerList) 
							// {
								
								// $len=count($rtnContainerList);
							
								// $i = 0;
								
								// $containerNo1=$rtnContainerList[$i]['cont_number'];
								// $rotaionNo=$rtnContainerList[$i]['Import_Rotation_No'];

								// include("dbConection.php");
						
								// $sql_YardPosition="select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block from (
								// select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,time_out as fcy_time_out from inv_unit a
								// inner join 
								// inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
								// inner join
								// argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv or h.gkey = a.cv_gkey)
								// inner join
								// argo_visit_details i ON h.cvcvd_gkey = i.gkey
								// inner join
								// vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rotaionNo' and a.id='$containerNo1'
								// ) as  tmp";
								// $rslt_YardPosition=mysqli_query($con_sparcsn4,$sql_YardPosition);
								
								// while($row_YardPosition=mysqli_fetch_object($rslt_YardPosition))
								// {
									// $dischargeTime = $row_YardPosition->fcy_time_in;
								// }								
						?>
						
							<tr>
								<td align="center" style="background-color:#006bff45;width:13%;"><b>Rotation</b></td>
								<td align="center" style="width:2%"><b>:</b></td>
								<td align="center" style="width:35%"><?php echo $ddl_imp_rot_no; ?></td>
								
								<td align="center" style="background-color:#006bff45;width:13%;"><b>BL No</b></td>
								<td align="center" style="width:2%"><b>:</b></td>
								<td align="center" style="width:35%"><?php echo $ddl_bl_no; ?></td>
							</tr>
							<tr>
								<!--td align="center" style="background-color:#006bff45;"><b>Discharge Time</b></th>
								<td align="center"><b>:</b></td>
								<td align="center"><?php echo $dischargeTime;; ?></td-->
								
								<td align="center" style="background-color:#006bff45;"><b>Dest.</b></td>
								<td align="center"><b>:</b></td>
								<td align="center"><?php if($contStatus!="FCL"){print($rtnContainerList[0]['off_dock_id']);} ?></td>
								
								<td align="center" style="background-color:#006bff45;"><b>Marks & Number</b></td>
								<td align="center"><b>:</b></td>
								<td align="center"><?php echo str_replace(',',', ',$rtnContainerList[0]['Pack_Marks_Number']); ?></td>
							</tr>
							<tr align="center">																
								<td style="background-color:#006bff45;"><b>Consignee Description</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['ConsigneeDesc']);  ?></td>
								
								<td style="background-color:#006bff45;"><b>Status</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['cont_status']); ?></td>
							</tr>							
							<!--tr align="center">	
								<td style="background-color:#006bff45;"><b>Yard / Shed</b></td>
								<td align="center"><b>:</b></td>
								<td><?php if($contStatus!="FCL"){print($rtnContainerList[$i]['shed_yard']);}  ?></td>
								
								<td style="background-color:#006bff45;"><b>Position</b></td>
								<td align="center"><b>:</b></td>
								<td><?php if($contStatus!="FCL"){print($rtnContainerList[$i]['shed_loc']);}  ?></td>				
							</tr-->							
							<tr align="center">					
								<td style="background-color:#006bff45;"><b>Offdock Name</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['offdock_name']); ?></td>
								
								<td style="background-color:#006bff45;"><b>Notify Description</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['NotifyDesc']); ?></td>
							</tr>
							<tr align="center">																
								<!--td style="background-color:#006bff45;"><b>Cont. Type</b></td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['cont_iso_type']); ?></td-->
								
								<td align="center" style="background-color:#006bff45;"><b>Receive Pack</td>
								<td align="center"><b>:</b></td>
								<td><?php print($rtnContainerList[0]['rcv_pack']); ?></td>
								
								<td colspan="3"></td>
							</tr>
							<?php
							// if($contStatus=="LCL")
							// {
							?>
							<!--tr align="center">								
								
								
								<td colspan="3"></td>
							</tr-->							
							<?php
							// }
							?>
							
						<?php	
							// if($totcontainerNo!="")
								// $totcontainerNo=$totcontainerNo.", ".$containerNo1;
							// else
								// $totcontainerNo=$containerNo1;

						//	mysql_close($con_sparcsn4);
						?>

				<!--tr><td colspan="16" align="center"><?php echo "Total Container: ". $j;?></td></tr-->
				<!--<tr><td colspan="16" align="left"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr>-->
						</table>
					</div>
				</div>
				<br/>

				<!--table border="0" cellspacing="2" cellpadding="1" bdcolor="#ffffff" width=60%" style="margin-right:180px"-->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1"  width="60%" bgcolor="#2AB1D6" style="margin-right:180px">
							<tr align="center">
								<td style="background-color:#006bff45;"><b>Container</b></td>
								<td style="background-color:#006bff45;"><b>Seal</b></td>
								<td style="background-color:#006bff45;"><b>Size</b></td>
								<td style="background-color:#006bff45;"><b>Height</b></td>
								<td style="background-color:#006bff45;"><b>Position</b></td>
								<td style="background-color:#006bff45;"><b>Yard/Shed</b></td>
							</tr>

							<?php
							include("dbConection.php");
				
							for($i=0;$i<count($rtnContainerList);$i++)
							{
								$contMain = $rtnContainerList[$i]['cont_number'];
								$rotMain = $rtnContainerList[$i]['Import_Rotation_No'];
								 
								/* if($contStatus=="FCL")
								{ */
								$strQuerypos="SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot AS pos,
								(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No
								FROM sparcsn4.inv_unit
								INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
								INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
								INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
								WHERE sparcsn4.inv_unit.id='$contMain' AND ib_vyg='$rotMain'";
								//return;

								$strPosForFcl = mysqli_query($con_sparcsn4,$strQuerypos);
								
								$pos="";
								$yard_No="";
								
								while($row=mysqli_fetch_object($strPosForFcl))
								{
									$pos = $row->pos;
									$yard_No = $row->Yard_No;
								}									
							?>
							<tr class="gridLight">
								<td align="center"><?php echo $rtnContainerList[$i]['cont_number'];  ?></td>
								<td align="center"><?php echo $rtnContainerList[$i]['cont_seal_number']; ?></td>
								<td align="center"><?php echo $rtnContainerList[$i]['cont_size']; ?></td>
								<td align="center"><?php echo $rtnContainerList[$i]['cont_height']; ?></td>
								<td align="center"><?php echo $pos; ?></td>
								<td align="center"><?php echo $yard_No; ?></td>
							</tr>
						<?php 								 							
							}
						?>
							<tr>
								<td colspan="6">
									<div class="row">
										<div class="col-sm-5" align="right">
											<form action="<?php echo site_url('Report/lclAssignmentCertifyList')?>" target="_blank" method="POST">		
												<input type="hidden" name="pdfView" id="pdfView" value="pdfView" />
												<input type="hidden" name="ddl_imp_rot_no" id="ddl_imp_rot_no" value="<?php echo $ddl_imp_rot_no; ?>" />
												<input type="hidden" name="ddl_bl_no" id="ddl_bl_no" value="<?php echo $ddl_bl_no; ?>" />
												<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verify_num; ?>" />
												<button type="submit" class="btn btn-primary" >PDF View</button>					
											</form>
										</div>
										
										<div class="col-sm-2" align="center">
											<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#beUpload">B/E Upload</button>
										</div>
										
										<div class="col-sm-5" align="left">
											<form action="<?php echo site_url('Report/releaseorderpdf')?>" target="_blank" method="POST">			
												<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $ddl_imp_rot_no; ?>" />
												<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $ddl_bl_no; ?>" />
												<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verify_num; ?>" />
												<button type="submit"class="btn btn-primary" >Release Order</button>
											</form> 
										</div>
									</div>
								</td>
							</tr>							
						</table>

						<br/>
	
						<?php
						if($org_Type_id==62)
						{
						?>
						<form action="<?php echo site_url('Report/lclAssignmentVerify');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
							<input type="hidden" value="<?php echo  $verify_id?>" name="verify_id" style="width:200px;"/>
							<input type="hidden" value="<?php echo  $verify_num?>" name="verify_num" style="width:200px;"/>
							<input type="hidden" value="<?php echo  $ddl_imp_rot_no?>" name="verify_rot" style="width:200px;"/>
							<input type="hidden" value="<?php echo  $ddl_bl_no?>" name="verify_bl" style="width:200px;"/>
							
							<input type="hidden" value="<?php echo  $contStatus?>" name="contStatus" style="width:200px;"/>
							<table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6">
								
								<tr>
									<td align="center" style="background-color:#006bff45;"><b>C&F License</b></td>
									<td align="center"><b>:</b></td>
									<td><input type="text" class="form-control" id="strCnfLicense" value="<?php echo  $cnf_lic_no;?>" name="strCnfLicense" onblur="getCnfCode(this.value)" style="width:200px;"/></td>
									
									<td align="center" style="background-color:#006bff45;"><b>C&F Name</b></td>
									<td align="center"><b>:</b></td>
									<td><input type="text" class="form-control" id="strCnfCode" value="<?php echo $rtnContainerList[0]['cnf_name'];?>" name="strCnfCode" style="width:200px;"/></td>
								</tr>
								<tr>
									<td align="center" style="background-color:#006bff45;"><b>Agent DO</b></td>
									<td align="center"><b>:</b></td>
									<td><input type="text" class="form-control" value="<?php echo  $rtnContainerList[0]['agent_do']?>" name="strAgentDo" style="width:200px;"/></td>
									
									<td align="center" style="background-color:#006bff45;"><b>DO Date</b></td>
									<td align="center"><b>:</b></td>
									<td>
										<input type="date" class="form-control" id="strDoDate" value="<?php echo  $rtnContainerList[0]['do_date']?>" name="strDoDate" style="width:200px;"/>
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
									<td align="center" style="background-color:#006bff45;"><b>BE No</b></td>
									<td align="center"><b>:</b></td>
									<td><input type="text" class="form-control" value="<?php echo  $rtnContainerList[0]['be_no']?>" name="strBEno" style="width:200px;"/></td>
									
									<td align="center" style="background-color:#006bff45;"><b>BE Date</b></td>
									<td align="center"><b>:</b></td>
									<td>
										<input type="date" class="form-control" id="strBEdate" value="<?php echo  $rtnContainerList[0]['be_date']?>" name="strBEdate" style="width:200px;"/>
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
									<td align="center" style="background-color:#006bff45;"><b>W/R UP TO DATE</b></td>
									<td align="center"><b>:</b></td>
									<td>
										<input type="date" class="form-control" id="strWRdate" value="<?php echo  $rtnContainerList[0]['wr_upto_date']?>" name="strWRdate" style="width:200px;"/>
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
									
									<td align="center" style="background-color:#006bff45;"><b>Tonnage Update</b></td>
									<td align="center"><b>:</b></td>
									<td><input type="text" class="form-control" value="<?php echo  $rtnContainerList[0]['update_ton']?>" name="strTonUpdt" style="width:200px;"/></td>
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
											<td colspan="6" align="center"><button type="submit" name="btn_verify" class="mb-xs mt-xs mr-xs btn btn-success">CERTIFY</button></td>
										</tr>
										
								<?php 
									}
								?>
								
							</table>
						</form>
						<?php
						}
						?>
					</div>
				</div>			
	
				<!--   Modal Start   -->
				<div class="modal fade" id="beUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">BE Upload</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						
						<form action="<?php echo site_url('Report/beFileUpload')?>" method="POST" enctype="multipart/form-data">
							<div class="modal-body">
								<input type="file" name="beFile" id="beFile"/>								
								<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $ddl_imp_rot_no; ?>" />
								<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $ddl_bl_no; ?>" />
							</div>
							<div class="modal-footer">
								<input type="submit" name="save" id="save" class="btn btn-success" value="Save">
							</div>
						</form>

						</div>
					</div>
				</div>
				<!--   Modal End   -->
		
	
			</TD>
		</TR>
<br/>
</table>
<?php 
}
?>