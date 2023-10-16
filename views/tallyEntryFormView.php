<html>
	<head>
		<title>Chittagong Port Authority</title>
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>/oldcss/calender.jquery-ui.css">
		<link rel="stylesheet" href="<?php echo CSS_PATH; ?>oldcss/popUp.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>oldjs/jquery-1.6.0.min.js"></script>
		<script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>oldjs/calender.jquery-ui.min.js"></script>
		<style type="text/css">
		  #overlay {
			  background: rgba(0,0,0,0.4);
			  width: 100%;
			  height: 100%;
			  min-height: 100%;
			  position: absolute;
			  top: 0;
			  left: 0;
			  z-index: 5;
			}
			.button {
				display: block;
				width: 100%;
				height: 5%;
				background: #4E9CAF;
				padding: 10px;
				text-align: center;
				border-radius: 5px;
				color: white;
				font-weight: bold;
				text-decoration: none;
			}
		</style>
		<script>		
			function exchangeDone() {
				var answer = confirm("Are you want to Exchange Done?")
					if (answer) {
						var rotation=document.getElementById("rot").value;
						var container=document.getElementById("cont").value;
						//alert(container);
						//console.log(rotation+"--"+container);
						//alert(rotation+"--"+container);
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
							xmlhttp.open("GET","<?php echo site_url('AjaxController/ExchangeDoneStatusChange')?>?rotation="+rotation+"&container="+container,false);
							xmlhttp.send();
					}
					else {
						
					}
				//alert("K");		
				
				 
			}
			function stateChangeValue()
				{
					//alert("stateChangeValue");
					if (xmlhttp.readyState==4 && xmlhttp.status==200) 
					{
							  
						var val = xmlhttp.responseText;
						var jsonData = JSON.parse(val);
						//alert(val);
						//alert(jsonData);
						//alert(jsonData.stat);
						//var cnfCodeTxt=document.getElementById("cnfName");
						if(jsonData.stat[0]=="1")
						{
							//document.getElementById("btnTr").style.visibility = 'hidden';
							//document.getElementById("btnTr").innerHTML = "";
							//document.getElementById("btnTr2").innerHTML = "";
							
							//document.getElementById("btnTr1").colSpan="4";

							alert("Exchange Done.");
							deleteLastrow();
							$("#exBtn").remove();
							$("#tblInner tbody tr").find("td:eq(9)").remove();
							//document.getElementById("vwBtn").style.width = "300px"; 
							// document.getElementById('excngeData').innerHTML = "<table><tr><td>Exchange Done.</td><td><a class='button' href='#popup1'  onclick='txttransfer()'><font color='white'>Upload Signature</font></a></td></tr></table>";
							
							document.getElementById('excngeData').innerHTML = "<table><tr><td>Exchange Done.</td></tr></table>";
							document.getElementById("btnView").style.visibility = 'block';
						}
						else
						{
							alert("Exchange Not Done.");
						}
					}
				}
			function validateField()
			{
				var answer = confirm("Are you want to delete tally?");
				if (answer) {
							return true;
							}
							else {
								return false;
							}		
			}
		function txttransfer()
		{
			//alert("TEST");
			var div = document.getElementById('popup1');
			div.style.display = 'block';
			
			var rotNumber="";
			var containerNumber="";
			var userTransfer="";
			
			
			rotNumber="<b>"+document.getElementById("rotNumTransfer").value+"</b>";
			containerNumber="<b>"+document.getElementById("contNumTransfer").value+"</b>";
			userTransfer="<b>"+document.getElementById("userTransfer").value+"</b>";
			
			
			//alert("kdf : "+containerNumber);
			
			document.getElementById('rotNum').innerHTML=rotNumber;
			document.getElementById('contNum').innerHTML=containerNumber;
			document.getElementById('userName').innerHTML=userTransfer;
			
			
			document.getElementById("rotNumber").value=document.getElementById("rotNumTransfer").value;
			document.getElementById("contNumber").value=document.getElementById("contNumTransfer").value;
			document.getElementById("user").value=document.getElementById("userTransfer").value;
	}
	function deleteRow(row)
	{
		var i=row.parentNode.parentNode.rowIndex;
		document.getElementById('myTbl').deleteRow(i);
	}
	function deleteLastrow() 
	{
		var table = document.getElementById('myTbl');
		var rowCount = table.rows.length;
		table.deleteRow(rowCount -1);
	}
	
	function berthOpPut()
	{
		//alert("ok")
		//alert(document.getElementById('shed_name').value);
		var prev_phy_tally = document.getElementById('phyTallySheetNumber').value;
		var wrDt = document.getElementById('wrDate').value;
		//alert(prev_phy_tally);
		var totCnt = document.getElementById('cnt').value;
		//document.getElementById('phy_tally').value=prev_phy_tally;
		for(var p=0;p<=totCnt;p++)
		{
			//alert("ok")
			document.getElementById('berth_op'+p).value=document.getElementById('berthOp').value;
			document.getElementById('shed_name'+p).value=document.getElementById('shed_no').value;

			document.getElementById('phy_tally_no'+p).value=prev_phy_tally;
			document.getElementById('unstuffingDate'+p).value=wrDt;
		} 

	}
	function setPhyTally(val)
	{	//alert("setPhyTally");
		//document.getElementById('phy_tally').value=val;
		var totCnt = document.getElementById('cnt').value;
		for(var p=0;p<=totCnt;p++)
		{
			document.getElementById('phy_tally_no'+p).value=val;
		}
		
		
	}
	function validate()
	{
		var wrDt = document.getElementById('wrDate').value;	
		if(wrDt == "")
		{
			alert( "Please select unstuffing date." );
			return false;
		}		
		else if( document.getElementsByName('shed_name')[0].value == "")
		{
			alert( "Please select shed." );
			document.getElementsByName('shed_name')[0].focus() ;
			return false;
		}	
		else
		{
			return true;
		} 
		
	}
	
	

	</script>
	</head>
	<body>
	<!--form action="<?php echo site_url('report/saveTallyRcv');?>" method="post" target="blank" onsubmit=""-->
	<!--form action="<?php echo site_url('report/updateTallyInfo');?>" method="post" onsubmit="return validateField()"-->
		<table width="100%" cellpadding="0">
			<tr bgcolor="#273076" height="100px">
				<td align="center" valign="middle">
					<h1><font color="white">Chittagong Port Authority</font></h1>
				</td>
			</tr>
			<tr bgcolor="#2E9AFE">
				<td align="left" valign="middle" style="padding-top:10px;padding-left:20px;">
					<?php
							include_once("dbConection.php");
							include("dbOracleConnection.php");
							//$strBerth = "select IFNULL(flex_string02, flex_string03) as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotation'";
							$strBerth="select NVL(flex_string02, flex_string03) as rtnValue from vsl_vessel_visit_details where ib_vyg='$rotation'";
							 $berthOp = $this->bm->dataReturn($strBerth);
						
							

						/*	$strBerthList = "SELECT DISTINCT flex_string02  FROM sparcsn4.vsl_vessel_visit_details WHERE  flex_string02 NOT LIKE '%BASHER AHMED%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%'
							 AND flex_string02 NOT LIKE '%BASHIR AHMED' AND flex_string02 NOT LIKE '%FAZLISONS LIMITED%'
							 ORDER BY flex_string02 DESC LIMIT 7";*/
							 $strBerthList="SELECT DISTINCT flex_string02  FROM vsl_vessel_visit_details WHERE  flex_string02 NOT LIKE '%BASHER AHMED%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%'
							 AND flex_string02 not LIKE '%BASHIR AHMED' AND flex_string02 NOT LIKE '%FAZLISONS LIMITED%'
							 ORDER BY flex_string02 DESC fetch first 7 rows only";
							$berthOpList = $this->bm->dataSelect($strBerthList);
						
							 
							 
						
							include_once("mydbPConnection.php");
							
							$strYardName = "SELECT DISTINCT shed_yard AS rtnValue FROM shed_tally_info WHERE shed_tally_info.import_rotation='$rotation' 
							AND shed_tally_info.cont_number='$cont' AND shed_yard IS NOT NULL  ORDER BY shed_tally_info.id DESC LIMIT 1";
							@$yardName = $this->bm->dataReturnDb1($strYardName);
							
							//$rot_number = $rtnContainerList[$i]['Import_Rotation_No'];
							//$con_number = $rtnContainerList[$i]['cont_number'];
							$strDt = "";							
							$strDt = "select distinct wr_date,tally_sheet_number,tally_sheet_no,shed_loc, shed_yard from shed_tally_info where import_rotation='$rotation' and cont_number='$cont'";
							//echo "Tst : ".$strDt;
							$resDt = mysqli_query($con_cchaportdb,$strDt);
							$numrowDt = mysqli_num_rows($resDt);
							$rowrcv = mysqli_fetch_object($resDt);
							
							//for manual unstuffing date starts----------
								$strUnstuffingDt = "";							
								$lastUnstuffingDt = "";							
								$strUnstuffingDt = "SELECT id,wr_date FROM shed_tally_info 
													WHERE import_rotation='$rotation' AND cont_number='$cont'
													ORDER BY id DESC LIMIT 1";								
								$resUnstuffingDt = mysqli_query($con_cchaportdb,$strUnstuffingDt);
								$numRowUnstuffingDt = mysqli_num_rows($resUnstuffingDt);
								while($rowUnstuffingDt = mysqli_fetch_object($resUnstuffingDt))
								{
									$lastUnstuffingDt = $rowUnstuffingDt->wr_date;
								}
							//for manual unstuffing date ends------------
							
							$strPhyTally = "SELECT id,physical_tally_sheet_no FROM shed_tally_info 
										WHERE import_rotation='$rotation' AND cont_number='$cont'
										ORDER BY id DESC LIMIT 1";
							$resPhyTally = mysqli_query($con_cchaportdb,$strPhyTally);
							$rowPhyTally = mysqli_fetch_object($resPhyTally);
							if($numrowDt!=0)
							{
								$physicalTallySheetNumber = $rowPhyTally->physical_tally_sheet_no;
							}
							
							$shedYard=$this->session->userdata('section');
					?>																						
						<script>
							$(function() {
							 $( "#wrDate" ).datepicker({
							  changeMonth: true,
							  changeYear: true,
							  dateFormat: 'yy-mm-dd', // iso format
							 });
							});
						</script>
					<h3><font color="white">Tally Entry Form for<?php echo " Rotation: <font color='#1C0204'>".$rotation."</font> and Container: <font color='#1C0204'>".strtoupper($cont)."</font>";?></font>			
					<font color="red"><b>Unstuffing Date</b></font> :  
					<input size="10" type="text" id="wrDate" name="wrDate" 
						value="<?php if($numRowUnstuffingDt==0) echo ""; else echo $lastUnstuffingDt;?>" />
					<input size="10" type="hidden" id="maxTallyNo" name="maxTallyNo" value="<?php if($numrowDt==0) echo ""; else echo $rowrcv->tally_sheet_no;?>" />
					<input  type="hidden" id="rotNumTransfer" name="rotNumTransfer" value="<?php echo $rotation;?>" />
					<input  type="hidden" id="contNumTransfer" name="contNumTransfer" value="<?php echo $cont;?>" />
					<input  type="hidden" id="userTransfer" name="userTransfer" value="<?php echo $login_id;?>" />
					
					<font color="green"><b>Tally Sheet Number</b></font> : <input class="" size="15" type="text" id="tallySheetNumber" name="tallySheetNumber" value="<?php if($numrowDt==0) echo ""; else echo $rowrcv->tally_sheet_number;?>" readonly="true"/>
					<br>
					<font color='white'>Vessel Name:</font> <?php echo $rslt_vesselname_seal[0]['Vessel_Name']?>
					<font color='white'>Seal No:</font> <?php echo $rslt_vesselname_seal[0]['cont_seal_number']?>
					<font color='white'>Size:</font> <?php echo $rslt_vesselname_seal[0]['cont_size']?>
					<!--font color='white'>Shed/Yard:</font> <?php //echo $shedYard; ?> -->
					<font color='white'>Berth Operator:</font>
					<select  id="berthOp" name="berthOp" >					
							<option value="<?php echo @$berthOp; ?>" selected="true"><?php echo @$berthOp; ?></option>
	         
								<?php for($i=0; $i<count($berthOpList); $i++){ ?>
                               <option value="<?php echo $berthOpList[$i]['FLEX_STRING02']; ?>"><?php echo $berthOpList[$i]['FLEX_STRING02']; ?></option>
                                               <?php } ?>
					</select>
					<font color='white'>Shed:</font>
					 <select name="shed_no" id="shed_no";>  
						<option value="">--Select---</option>
						<option value="CFS/NCT" <?php if($yardName=='CFS/NCT'){ ?> selected <?php } ?>>CFS/NCT</option> 
						<option value="CFS/CCT" <?php if($yardName=='CFS/CCT'){ ?> selected <?php } ?>>CFS/CCT</option> 
						<option value="13 Shed" <?php if($yardName=='13 Shed'){ ?> selected <?php } ?>>13 Shed</option> 
						<option value="12 Shed" <?php if($yardName=='12 Shed'){ ?> selected <?php } ?>>12 Shed</option> 
						<option value="9 Shed" <?php if($yardName=='9 Shed'){ ?> selected <?php } ?>>9 Shed</option> 
						<option value="8 Shed" <?php if($yardName=='8 Shed'){ ?> selected <?php } ?>>8 Shed</option> 
						<option value="7 Shed" <?php if($yardName=='7 Shed'){ ?> selected <?php } ?>>7 Shed</option> 
						<option value="6 Shed" <?php if($yardName=='6 Shed'){ ?> selected <?php } ?>>6 Shed</option> 
						<option value="5 Shed" <?php if($yardName=='5 Shed'){ ?> selected <?php } ?>>5 Shed</option> 
						<option value="4 Shed" <?php if($yardName=='4 Shed'){ ?> selected <?php } ?>>4 Shed</option> 
						<option value="N Shed" <?php if($yardName=='N Shed'){ ?> selected <?php } ?>>N Shed</option> 
						<option value="D Shed" <?php if($yardName=='D Shed'){ ?> selected <?php } ?>>D Shed</option> 
						<option value="P Shed" <?php if($yardName=='P Shed'){ ?> selected <?php } ?>>P Shed</option> 	
					</select>
					<br/>
					<font color="green"><b>Physical Tally Sheet Number</b></font> : <input class="" size="15" type="text" 
					id="phyTallySheetNumber" name="phyTallySheetNumber" 
					value="<?php if($numrowDt==0) echo ""; else echo $rowPhyTally->physical_tally_sheet_no;?>" onblur="setPhyTally(this.value);"/>
					</h3>
				</td>
			</tr>
			<tr>
				<td align="left" valign="middle">
					<h3><?php echo $stat;?></h3>
				</td>
			</tr>
			<tr>
				<td>
					
					<table id="myTbl" width="100%" border="0" align="center" cellspacing="1" cellpadding="1">
						
						<tr bgcolor="#58ACFA">
							<td align="center">SL.</td>
							<!--td rowspan="2">Import Rotation</td>
							<td rowspan="2">Master BL</td-->
							<td align="center">BL No.</td>
							
							<td align="center">Marks & Number</td>
							
							<!--td rowspan="2">Consignee Description</td-->
							
							
							<td align="center">G.Weight</td>
							<td align="center">Pkg Unit</td>
							<td align="center"><nobr>Pkg Qty</nobr></td>
							<td align="center">Input Fields</td>
							<!--td style="display:none" rowspan="2">Unstuff Date</td-->
							<!--td align="center" colspan="5">Pack Rcv</td-->
							
							<!--td>Location in Shed</td-->
							<!--td rowspan="2">Bay No</td-->
							<!--td>Loc First</td-->
							<!--td rowspan="2">Tally Sheet Number</td-->
							<!--td rowspan="2">Physical<br>Marks</td-->
							<!--td rowspan="2">Shift Name</td>
							<td rowspan="2">Physical Marks</td>
							<td rowspan="2">Remarks</td-->
							<!--td rowspan="2">Seal Number</td>
							<td rowspan="2">Description<br>of Goods</td>
							<td rowspan="2">Cont Size</td>
							<td rowspan="2">Notify Description</td-->
							<!--td rowspan="2">Remarks</td-->
							<!--td rowspan="2">Action</td>
							<td rowspan="2">Report</td-->
						</tr>
						<!--tr bgcolor="#58ACFA">
										<td>IWH</td>
										<td>Loc Fast</td>
										<td>Ship Survey</td>
										<td>Total</td>
										<td>Rcv Unit</td>
						</tr-->
						
							<?php
								include_once("mydbPConnection.php");
								for($i=0; $i< count($rtnContainerList);$i++) { 
								//echo "After Loop : ".count($rtnContainerList)+1;
									?>
								
									<tr bgcolor="#A9D0F5">
										<td>
											<?php echo $i+1;?>
										</td>
											
										<!--td>
											<?php echo "<font size='2'>".$rtnContainerList[$i]['Import_Rotation_No']."</font>"?>
										</td-->
										<!--td>
											<?php echo "<font size='2'>".$rtnContainerList[$i]['master_BL_No']."</font>"?>
										</td-->
										<td>
											<?php 
												if(isset($rtnContainerList[$i]['BL_No']))
												{
													echo "<font size='2'>".$rtnContainerList[$i]['BL_No']."</font>";
												}
												else
												{
													echo "";
												}
											?>
											<?php //echo "<font size='2'>".$rtnContainerList[$i]['BL_No']."</font>"?>
										</td>				
										
										<td width="100">
											<?php
											if(isset($rtnContainerList[$i]['id']))
											{
												$strMarks = "select distinct(actual_marks) as actual_marks from shed_tally_info 
															where igm_sup_detail_id='".$rtnContainerList[$i]['id']."' 
															AND shed_tally_info.import_rotation='$rotation' 
															AND shed_tally_info.cont_number='$cont'
															union 
															select distinct(actual_marks) as actual_marks from shed_tally_info 
															where igm_detail_id='".$rtnContainerList[$i]['id']."'
															AND shed_tally_info.import_rotation='$rotation' 
															AND shed_tally_info.cont_number='$cont'";
												$resMarks = mysqli_query($con_cchaportdb,$strMarks);
												$rowMarks = mysqli_fetch_object($resMarks);
												//echo "".$strMarks;
												if(isset($rowMarks->actual_marks) && @$rowMarks->actual_marks!=""  )
												{
													echo $rowMarks->actual_marks;
												}
												else
												{
													echo "<font size='2'>".substr($rtnContainerList[$i]['Pack_Marks_Number'], 0, 50)."</font>";
												}
											}
											else
											{
												echo "";
											}											
											//if($rowMarks->actual_marks!="" or $rowMarks->actual_marks!=null) echo $rowMarks->actual_marks; else echo "<font size='2'>".substr($rtnContainerList[$i]['Pack_Marks_Number'], 0, 50)."</font>";
										?>
											
											<?php 
												//echo "<font size='2'>".substr($rtnContainerList[$i]['Pack_Marks_Number'], 0, 20)."</font>"
											?>
											
										</td>
										
										<!--td width="200">
											<?php echo "<font size='2'>".$rtnContainerList[$i]['ConsigneeDesc']."</font>"?>
										</td-->
										
										
										<td>											
											<?php 
												if(isset($rtnContainerList[$i]['Cont_gross_weight']))
												{
													echo $rtnContainerList[$i]['Cont_gross_weight'];
												}
												else
												{
													echo "";
												}
											?>
										</td>
										<td>
											<?php 
												if(isset($rtnContainerList[$i]['Pack_Description']))
												{
													echo $rtnContainerList[$i]['Pack_Description'];
												}
												else
												{
													echo "";
												}
												
											?>
										</td>
										<td>
											<?php 
												if(isset($rtnContainerList[$i]['Pack_Number']))
												{
													echo $rtnContainerList[$i]['Pack_Number'];
												}
												else
												{
													echo "";
												}
												
											?>
										</td>
															
										<td>
											<table id="tblInner" border="0" cellpadding="1" cellspacing="2" bgcolor="#fff" style="width:100%;">
												<tr align="center" bgcolor="#7fb3d5 ">
													<td  colspan="2"><nobr>Goods Condition</nobr></td>
													<td  colspan="2"><nobr>Ship Survey</nobr></td>
													<td  rowspan="2">Total</td>
													<td rowspan="2"><nobr>Rcv Unit</nobr></td>
													<td rowspan="2">Bay No</td>
													<td rowspan="2"><nobr>Shift Name</nobr></td>
													<td width=150 rowspan="2">Physical Marks</td>
													<td width=150 rowspan="2">Remarks</td>
													<?php if($save_btn_status=="1") { ?><td class="actionTd" rowspan="2">Action</td><?php } ?>
													<td align="center" colspan="7"><nobr></nobr></td>
													
												</tr>
												<tr align="center" bgcolor="#7fb3d5 ">
													<td>IWH</td>
													<td>L/Fast</td>
													<td>IWH</td>
													<td>L/Fast</td>
													<!--td><nobr>Ship Survey</nobr></td-->
													
													
												</tr>
												<!--For loop start -->
												<?php
												if(isset($rtnContainerList[$i]['id']))
												{
													$supDtlId = $rtnContainerList[$i]['id'];
												}
												
												$strrcv = "";
												$numrowrcvAll = 0;
												$numrowfltAll = 0;
												/* if($tbl=="sup_detail") */
													 $strrcv = "select * from shed_tally_info where igm_sup_detail_id='$supDtlId' AND 
													 shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont'
													union 
													select * from shed_tally_info where igm_detail_id='$supDtlId'
													AND shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont'";
													$resrcv = mysqli_query($con_cchaportdb,$strrcv);
												while($rowrcv = mysqli_fetch_object($resrcv))
												{
												?>
												<tr align="center" bgcolor="#a9cce3">
													<td><?php echo $rowrcv->rcv_pack;?></td>
													<td><?php echo $rowrcv->loc_first;?></td>
													<td><?php echo $rowrcv->flt_pack;?></td>
													<td><?php echo $rowrcv->flt_pack_loc;?></td>
													<td><?php echo $rowrcv->total_pack;?></td>
													<td><?php echo $rowrcv->rcv_unit;?></td>
													<td><?php echo $rowrcv->shed_loc;?></td>
													<td><?php echo $rowrcv->shift_name;?></td>
													<td width=150><font size="1"><?php echo substr($rowrcv->actual_marks, 0, 20);?></font></td>
													<td width=150><font size="1"><?php echo substr($rowrcv->remarks, 0, 20);?></font></td>
													<?php if($rowrcv->exchange_done_status!="1") {?><td class="actionTd"><a href="<?php echo site_url('report/deleteTallyRcv/'.$rowrcv->id."/".$rowrcv->cont_number."/".str_replace("/","_",$rowrcv->import_rotation)."/".$tbl);?>" onclick="return confirm('Are you sure you want to delete Tally?');">Delete</a></td><?php }?>
												</tr>
												<?php } ?>
												<!--While loop end-->
												<tr align="center" bgcolor="#a9cce3">
					<form name="myForm"  onsubmit="return validate();" action="<?php echo site_url('Report/saveTallyRcv');?>" method="post">
													<td>
														<input type="hidden" name="berth_op" id="berth_op<?php echo $i;?>">
														<input type="hidden" name="shed_name" id="shed_name<?php echo $i;?>" >
														<input class="chkValidation" size="5" type="text" id="rcv<?php echo $i;?>" name="rcv" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#rcv<?php echo $i;?>').keyup(function() {
																//alert("jfkg");
																
																var tot= parseFloat($("#rcv<?php echo $i;?>").val())+parseFloat($("#conLocFast<?php echo $i;?>").val());
																$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#rcv<?php echo $i;?>').blur(function() {
																//alert("jfkg");
																
																var tot= parseFloat($("#rcv<?php echo $i;?>").val())+parseFloat($("#conLocFast<?php echo $i;?>").val());
																$("#totalPck<?php echo $i;?>").val(tot);
															});	
														</script>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="conLocFast<?php echo $i;?>" name="conLocFast" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#conLocFast<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#conLocFast<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
														<td>
														<input class="chkValidation" size="5" type="text" id="flt<?php echo $i;?>" name="flt" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#flt<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#flt<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#flt<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#flt<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="flt_pack_loc<?php echo $i;?>" name="flt_pack_loc" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#flt_pack_loc<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#flt_pack_loc<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#flt<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#flt_pack_loc<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#flt_pack_loc<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val()) + parseFloat($("#flt<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
													<!--td>
														<input class="chkValidation" size="5" type="text" id="flt" name="flt" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="flt_pack_loc" name="flt_pack_loc" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
													</td-->
													<td>
														<input class="chkValidation" size="5" type="text" id="totalPck<?php echo $i;?>" name="totalPck" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" readonly="true"/>													
													</td>
													<td>
														<input class="chkValidation" type="search" size="5" 
														value="<?php if(isset($RcvUnit)){echo $RcvUnit;} else {echo "";};?>" list="rcvUnit" placeholder="Pick Unit" name="rcv_unit">
														<datalist id="rcvUnit" >
														<?php				
															$supDtlId = $rtnContainerList[$i]['id'];
															$strrcvAll = "";
																$strrcvAllQry = "select Pack_Unit as Pack_Description from igm_pack_unit";
																
																$resrcvAllT = mysqli_query($con_cchaportdb,$strrcvAllQry);
																		
																while($rowrcvAllQry=mysqli_fetch_object($resrcvAllT))
																{
																	echo '<option selected="selected" value="'.$rowrcvAllQry->Pack_Description.'">'.$rowrcvAllQry->Pack_Description.'</option>';													
																}
															echo $strAllRcv."<br>";
														?>
														</datalist>
													</td>
													<td>
														<input size="5" type="text" value="" name="contAtShed" onkeyup="this.value = this.value.toUpperCase();"/>
													</td>
													<td>
														<select name="shiftname">												
															<option value="day">Day</option>
															<option value="night">Night</option>
														</select> 
													</td>
													<td>
														<textarea cols="13" rows="2" name="actualmarks" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
													</td>
													<td>
														<textarea cols="13" rows="2" name="remark" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
														<!--input type="text" name="remark<?php echo $i; ?>" value="<?php if($rowremarks==0) echo ""; else echo $remarks; ?>"/-->
													</td>
													<?php if($save_btn_status=="1") {?><td>
														<input  style="display:inline" id="SaveBtn" type="submit" value="Save" onclick="berthOpPut()"/>							
														<!--a href="<?php echo site_url('Report/saveTallyRcv/'.$rtnContainerList[$i]['id']);?>">Save</a-->								
													</td><?php }?>
												</tr>
												<input type="hidden" 
													value="<?php if(isset($rtnContainerList[$i]['id'])){ echo $rtnContainerList[$i]['id'];}?>"  name="dtlId" style="width:80px">
												<input type="hidden" value="<?php echo $rotation?>" id="rot"  name="rot" style="width:80px">
												<input type="hidden" value="<?php echo $cont?>"  id="cont" name="cont" style="width:80px"> 
												<input type="hidden" value="<?php echo $tbl?>"  name="tbl" style="width:80px">
												<input type="hidden" value="<?php echo count($rtnContainerList);?>" 
													name="cnt" id="cnt" style="width:80px">
												<input type="hidden" name="phy_tally_no" id="phy_tally_no<?php echo $i;?>" style="width:80px">
												<input type="hidden" name="unstuffingDate" id="unstuffingDate<?php echo $i;?>" style="width:80px">
											</form>
											</table>
										</td>										
										
									</tr>
									<?php } ?>
									
									<tr bgcolor="#A9D0F5">
										<td>
											<?php echo $i+1;?>
										</td>											
										<td></td>	
										<td width="100"></td>										
										<td></td>
										<td></td>
										<td></td>
															
										<td>
											<table id="tblInner" border="0" cellpadding="1" cellspacing="2" bgcolor="#fff" style="width:100%;">
												<tr align="center" bgcolor="#7fb3d5 ">
													<td  colspan="2"><nobr>Good Condition</nobr></td>
													<td  colspan="2"><nobr>Ship Survey</nobr></td>
													<td  rowspan="2">Total</td>
													<td rowspan="2"><nobr>Rcv Unit</nobr></td>
													<td rowspan="2">Bay No</td>
													<td rowspan="2"><nobr>Shift Name</nobr></td>
													<td width=150 rowspan="2">Physical Marks</td>
													<td width=150 rowspan="2">Remarks</td>
													<?php if($save_btn_status=="1") { ?><td class="actionTd" rowspan="2">Action</td><?php } ?>
													<td align="center" colspan="7"><nobr></nobr></td>
													
												</tr>
												<tr align="center" bgcolor="#7fb3d5 ">
													<td>IWH</td>
													<td>L/Fast</td>
													<td>IWH</td>
													<td>L/Fast</td>
													<!--td><nobr>Ship Survey</nobr></td-->
													
													
												</tr>
												<!--For loop start -->
												<?php
												if(isset($rtnContainerList[$i]['id']))
												{
													$supDtlId = $rtnContainerList[$i]['id'];
												}
												
												$strrcv = "";
												$numrowrcvAll = 0;
												$numrowfltAll = 0;
												if($tbl=="sup_detail")
													$strrcv = "select * from shed_tally_info where igm_sup_detail_id='$supDtlId'
													AND shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont'";
												else
													$strrcv = "select * from shed_tally_info where igm_detail_id='$supDtlId'  AND shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont'";
													
													$resrcv = mysqli_query($con_cchaportdb,$strrcv);
												while($rowrcv = mysqli_fetch_object($resrcv))
												{
												?>
												<!--Blank BL-->
												<!--tr align="center" bgcolor="#a9cce3">
													<td><?php echo $rowrcv->rcv_pack;?></td>
													<td><?php echo $rowrcv->loc_first;?></td>
													<td><?php echo $rowrcv->flt_pack;?></td>
													<td><?php echo $rowrcv->flt_pack_loc;?></td>
													<td><?php echo $rowrcv->total_pack;?></td>
													<td><?php echo $rowrcv->rcv_unit;?></td>
													<td><?php echo $rowrcv->shed_loc;?></td>
													<td><?php echo $rowrcv->shift_name;?></td>
													<td width=150><font size="1"><?php echo substr($rowrcv->actual_marks, 0, 20);?></font></td>
													<td width=150><font size="1"><?php echo substr($rowrcv->remarks, 0, 20);?></font></td>
													<?php if($rowrcv->exchange_done_status!="1") {?><td class="actionTd"><a href="<?php echo site_url('report/deleteTallyRcv/'.$rowrcv->id."/".$rowrcv->cont_number."/".str_replace("/","_",$rowrcv->import_rotation)."/".$tbl);?>" onclick="return confirm('Are you sure you want to delete Tally?');">Delete</a></td><?php }?>
												</tr-->
												<?php } ?>
												<!--While loop end-->
												<tr align="center" bgcolor="#a9cce3">
					<form name="myForm"  onsubmit="return validate();" action="<?php echo site_url('Report/saveTallyRcv');?>" method="post">
													<td>
														<input type="hidden" name="berth_op" id="berth_op<?php echo $i;?>">
														<input type="hidden" name="shed_name" id="shed_name<?php echo $i;?>" >
														<input class="chkValidation" size="5" type="text" id="rcv<?php echo $i;?>" name="rcv" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#rcv<?php echo $i;?>').keyup(function() {
																//alert("jfkg");
																
																var tot= parseFloat($("#rcv<?php echo $i;?>").val())+parseFloat($("#conLocFast<?php echo $i;?>").val());
																$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#rcv<?php echo $i;?>').blur(function() {
																//alert("jfkg");
																
																var tot= parseFloat($("#rcv<?php echo $i;?>").val())+parseFloat($("#conLocFast<?php echo $i;?>").val());
																$("#totalPck<?php echo $i;?>").val(tot);
															});	
														</script>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="conLocFast<?php echo $i;?>" name="conLocFast" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#conLocFast<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#conLocFast<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
														<td>
														<input class="chkValidation" size="5" type="text" id="flt<?php echo $i;?>" name="flt" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#flt<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#flt<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#flt<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#flt<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="flt_pack_loc<?php echo $i;?>" name="flt_pack_loc" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
														<script>
															$('#flt_pack_loc<?php echo $i;?>').keyup(function() {
															
															var tot= parseFloat($("#flt_pack_loc<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val())+ parseFloat($("#flt<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															$('#flt_pack_loc<?php echo $i;?>').blur(function() {
															
															var tot= parseFloat($("#flt_pack_loc<?php echo $i;?>").val())+ parseFloat($("#rcv<?php echo $i;?>").val()) + parseFloat($("#conLocFast<?php echo $i;?>").val()) + parseFloat($("#flt<?php echo $i;?>").val());
															$("#totalPck<?php echo $i;?>").val(tot);
															});	
															
														</script>
													</td>
													<!--td>
														<input class="chkValidation" size="5" type="text" id="flt" name="flt" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="flt_pack_loc" name="flt_pack_loc" value="<?php if($numrowfltAll==0) echo '0.0'; else echo '0.0';?>" onfocus="if(this.value=='0.0') this.value=''" onblur="if(this.value=='') this.value='0.0'"/>
													</td-->
													<td>
														<input class="chkValidation" size="5" type="text" id="totalPck<?php echo $i;?>" name="totalPck" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>" readonly="true"/>													
													</td>
													<td>
														<input class="chkValidation" type="search" size="5" 
														value="<?php if(isset($RcvUnit)){echo $RcvUnit;} else {echo "";};?>" list="rcvUnit" placeholder="Pick Unit" name="rcv_unit">
														<datalist id="rcvUnit" >
														<?php				
															$supDtlId = $rtnContainerList[$i]['id'];
															$strrcvAll = "";
																$strrcvAllQry = "select Pack_Unit as Pack_Description from igm_pack_unit";
																
																$resrcvAllT = mysqli_query($con_cchaportdb,$strrcvAllQry);
																		
																while($rowrcvAllQry=mysqli_fetch_object($resrcvAllT))
																{
																	echo '<option selected="selected" value="'.$rowrcvAllQry->Pack_Description.'">'.$rowrcvAllQry->Pack_Description.'</option>';													
																}
															echo $strAllRcv."<br>";
														?>
														</datalist>
													</td>
													<td>
														<input size="5" type="text" value="" name="contAtShed" onkeyup="this.value = this.value.toUpperCase();"/>
													</td>
													<td>
														<select name="shiftname">												
															<option value="day">Day</option>
															<option value="night">Night</option>
														</select> 
													</td>
													<td>
														<textarea cols="13" rows="2" name="actualmarks" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
													</td>
													<td>
														<textarea cols="13" rows="2" name="remark" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
														<!--input type="text" name="remark<?php echo $i; ?>" value="<?php if($rowremarks==0) echo ""; else echo $remarks; ?>"/-->
													</td>
													<?php if($save_btn_status=="1") {?><td>
														<input  style="display:inline" id="SaveBtn" type="submit" value="Save" onclick="berthOpPut()"/>							
														<!--a href="<?php echo site_url('Report/saveTallyRcv/'.$rtnContainerList[$i]['id']);?>">Save</a-->								
													</td><?php }?>
												</tr>
												<input type="hidden" 
													value="<?php if(isset($rtnContainerList[$i]['id'])){ echo $rtnContainerList[$i]['id'];}?>"  name="dtlId" style="width:80px">
												<input type="hidden" value="<?php echo $rotation?>" id="rot"  name="rot" style="width:80px">
												<input type="hidden" value="<?php echo $cont?>"  id="cont" name="cont" style="width:80px"> 
												<input type="hidden" value="<?php echo $tbl?>"  name="tbl" style="width:80px">
												<input type="hidden" value="<?php echo count($rtnContainerList);?>" 
													name="cnt" id="cnt" style="width:80px">
												<input type="hidden" name="phy_tally_no" id="phy_tally_no<?php echo $i;?>" style="width:80px">
												<input type="hidden" name="unstuffingDate" id="unstuffingDate<?php echo $i;?>" style="width:80px">
											</form>
											</table>
										</td>										
										
									</tr>
									
									
									
						
					</table>
				</td>
			</tr>
			<tr bgcolor="#2E9AFE">
				<td align="center">
					<table>
						<tr>
							
							<!--td id="btnTr">
								<input type="hidden" value="<?php echo count($rtnContainerList)+$equal;?>"  name="tblRow" style="width:80px">
								<?php if($save_btn_status=="1")
								{?>
									<input  style="display:inline" id="SaveBtn" type="submit" value="Save"/>	
								<?php } ?>
								<?php if($update_btn_status=="1")
								{?>
									<input style="display:inline" id="UpdateBtn" type="submit" value="Update"/>	
								<?php } ?>
								</form>								
							</td-->							
							<td>
								<table>
									<tr>
										<td id="btnTr1">
											<?php if($view_btn_status=="1")
											{?>
												<form style="display:inline" action="<?php echo site_url('ShedBillController/tallyReportPdf/'.str_replace("/","_",$rotation).'/'.str_replace("/","_",$cont))?>" target="_blank" method="POST">
													<input id="container_size" name="container_size" type="hidden" 
														value="<?php echo $rslt_vesselname_seal[0]['cont_size']?>"/>
													<input id="seal_no" name="seal_no" type="hidden" 
														value="<?php echo $rslt_vesselname_seal[0]['cont_seal_number']?>"/>
													<input id="VwBtn" type="submit" value="View"/>
												</form>
												<!--form style="display:inline" action="<?php echo site_url('ShedBillController/tallyReportPdf/'.str_replace("/","_",$rotation).'/'.str_replace("/","_",$cont))?>" target="_blank" method="POST">
													<input id="VwBtn" type="submit" value="View"/>
												</form-->
											<?php }?>
										</td>
										<td id="excngeData">
										</td>
									</tr>
								</table>
								<?php if($view_btn_status=="1")
								{?>
									<!--form style="display:inline" action="<?php echo site_url('ShedBillController/tallyReportPdf/'.str_replace("/","_",$rotation).'/'.str_replace("/","_",$cont))?>" target="_blank" method="POST">
										<input id="VwBtn" type="submit" value="View"/>
									</form-->
									<!--form style="display:inline" action="<?php echo site_url('ShedBillController/tallyReportPdf/'.str_replace("/","_",$rotation).'/'.str_replace("/","_",$cont))?>" target="_blank" method="POST">
										<input id="VwBtn" type="submit" value="View"/>
									</form-->
								<?php }?>
							</td>
							<td id="btnTr2"> 
								<?php if($exchange_btn_status=="1")
								{?>
									<input id="exBtn"  type="button" id="ExBtn" value="Exchange Done" onclick="exchangeDone()"/>									
								<?php }
								else{?>
									
									<?php
									echo $msgExchange;?>
									
								<?php }?>
							</td>
							<!--<?php //if($view_btn_status=="1" && $exchange_btn_status=="0") { ?>
								<td id="btnTr3">
								<div>
									<a class="button" href="#popup1"  onclick="txttransfer()"><font color="white">Upload Signature</font></a>
								</div>
								</td>
								
								<?php// }?>-->
								
								
								
							<!--td>
								<?php 
								//if($view_btn_status=="1" && $exchange_btn_status=="0")
								//{
									?>
								<div >
									<a class="button" href="#popup1"  onclick="txttransfer()"><font color="white">Upload Signature</font></a>
								</div>
								<?php 
								//}
								?>
							</td-->
							<!--td>
								<input  type="button" id="addRow" value="Add Row" onclick="addRowToTbl()"/>	
							</td-->
						</tr>
						<tr id="btnSig" align="right">
							
						</tr>
					
					</table>
				</td>
				
			</tr>
		</table>
		<div id="popup1" class="overlay">
		<div class="popup">
			
			<?php  
			//	include("popUpSignature.php") ;
				//include("popUpSignatureNew.php") ;
			?>
			
			</div>
			
		</div>	
	</body>
</html>
