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
					//alert("ddfd");
					if (xmlhttp.readyState==4 && xmlhttp.status==200) 
					{
							  
						var val = xmlhttp.responseText;
						var jsonData = JSON.parse(val);
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
							document.getElementById('excngeData').innerHTML = 
							"<table><tr><td>Exchange Done.</td><td><a class='button' href='#popup1'  onclick='txttransfer()'><font color='white'>Upload Signature</font></a></td></tr></table>";
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
			function txttransfer(){
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
	
	function setPhyTally(val)
	{
		document.getElementById('phy_tally').value=val;
		var totCnt = document.getElementById('cnt').value;
		for(var p=0;p<=totCnt;p++)
		{
			document.getElementById('phy_tally_no'+p).value=val;
		}
		
		
	}
	function setPhyTallyBeforeSave()
	{
		var prev_phy_tally = document.getElementById('phyTallySheetNumber').value;
		//alert(prev_phy_tally);
		var totCnt = document.getElementById('cnt').value;
		document.getElementById('phy_tally').value=prev_phy_tally;
		for(var p=0;p<=totCnt;p++)
		{
			document.getElementById('phy_tally_no'+p).value=prev_phy_tally;
		}
	}
	
	function berthOpPut()
	{
		var prev_phy_tally = document.getElementById('phyTallySheetNumber').value;
		//alert(prev_phy_tally);
		var totCnt = document.getElementById('cnt').value;
		for(var p=0;p<=totCnt;p++)
		{
			//alert("ok")
			document.getElementById('berth_op'+p).value=document.getElementById('berthOp').value;
			document.getElementById('shed_name'+p).value=document.getElementById('shed_no').value;

			document.getElementById('phy_tally_no'+p).value=prev_phy_tally;
		} 
	}
	
	
	function validate()
	{
		 if( document.getElementsByName('shed_name')[0].value == "" )
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
	<?php include("FrontEnd/mydbPConnection.php");?>
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
							$strBerth = "select NVL(flex_string02, flex_string03) as rtnValue from vsl_vessel_visit_details where ib_vyg='$rotation'";
							$berthOp = $this->bm->dataReturn($strBerth); 
							 
							$strBerthList = "SELECT DISTINCT flex_string02  FROM vsl_vessel_visit_details WHERE  flex_string02 NOT LIKE '%BASHER AHMED%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%' AND  flex_string02 NOT LIKE '%BASHIR AHMED & COM LTD%'
							 AND flex_string02 NOT LIKE '%BASHIR AHMED' AND flex_string02 NOT LIKE '%FAZLISONS LIMITED%'
							 ORDER BY flex_string02 DESC fetch first 7 rows only";
							 $berthOpList = $this->bm->dataSelect($strBerthList);
							
							include_once("FrontEnd/mydbPConnection.php");
							
							$strYardName = "SELECT DISTINCT shed_yard AS rtnValue FROM shed_tally_info WHERE shed_tally_info.import_rotation='$rotation' 
							AND shed_tally_info.cont_number='$cont' AND shed_yard IS NOT NULL ORDER BY shed_tally_info.id DESC LIMIT 1";
							@$yardName = $this->bm->dataReturnDb1($strYardName);
							
							//$rot_number = $rtnContainerList[$i]['Import_Rotation_No'];
							//$con_number = $rtnContainerList[$i]['cont_number'];
							$strDt = "";							
							$strDt = "select distinct wr_date,tally_sheet_number,tally_sheet_no,shed_loc,shed_yard from shed_tally_info where import_rotation='$rotation' and cont_number='$cont'";
							//echo "Tst : ".$strDt;
							$resDt = mysqli_query($con_cchaportdb,$strDt);
							$numrowDt = mysqli_num_rows($resDt);
							$rowrcv = mysqli_fetch_object($resDt);
							
							$strPhyTally = "SELECT DISTINCT id,physical_tally_sheet_no
										FROM shed_tally_info 
										WHERE import_rotation='$rotation' AND cont_number='$cont'
										ORDER BY id DESC LIMIT 1";
							$resPhyTally = mysqli_query($con_cchaportdb,$strPhyTally);
							$rowPhyTally = mysqli_fetch_object($resPhyTally);
							if($numrowDt!=0)
							{
								$physicalTallySheetNumber = $rowPhyTally->physical_tally_sheet_no;
							}
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
					<input size="10" type="text" id="wrDate" name="wrDate" value="<?php if($numrowDt==0) echo ""; else echo $rowrcv->wr_date;?>" />
					<input size="10" type="hidden" id="maxTallyNo" name="maxTallyNo" value="<?php if($numrowDt==0) echo ""; else echo $rowrcv->tally_sheet_no;?>" />
					<input  type="hidden" id="rotNumTransfer" name="rotNumTransfer" value="<?php echo $rotation;?>" />
					<input  type="hidden" id="contNumTransfer" name="contNumTransfer" value="<?php echo $cont;?>" />
					<input  type="hidden" id="userTransfer" name="userTransfer" value="<?php echo $login_id;?>" />
					
					<font color="green"><b>Tally Sheet Number</b></font> : <input class="" size="15" type="text" id="tallySheetNumber" name="tallySheetNumber" value="<?php if($numrowDt==0) echo ""; else echo $rowrcv->tally_sheet_number;?>" readonly="true"/>
					<br>
					<?php
						$vslName = "";
						$contSeal = "";
						$contSize = "";
						for($s=0;$s<count($rslt_vesselname_seal);$s++)
						{
							$vslName = $rslt_vesselname_seal[$s]['Vessel_Name'];
							$contSeal = $rslt_vesselname_seal[$s]['cont_seal_number'];
							$contSize = $rslt_vesselname_seal[$s]['cont_size'];
						}
					?>
					<font color='white'>Vessel Name:</font> <?php echo $vslName;?>
					<font color='white'>Seal No:</font> <?php echo $contSeal; ?>
					<font color='white'>Size:</font> <?php echo $contSize;?>
					<!--font color='white'>Shed/Yard:</font> <?php //echo @$rowrcv->shed_yard; ?> -->
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
						<option value="N Shed" <?php if($yardName=='N Shed'){ ?> selected <?php } ?>>N Shed</option> 
						<option value="D Shed" <?php if($yardName=='D Shed'){ ?> selected <?php } ?>>D Shed</option> 
						<option value="P Shed" <?php if($yardName=='P Shed'){ ?> selected <?php } ?>>P Shed</option> 	
					</select>
					<br>
					<font color="green"><b>Physical Tally Sheet Number</b></font> : <input class="" size="15" type="text" 
					id="phyTallySheetNumber" name="phyTallySheetNumber" 
					value="<?php if($numrowDt==0) echo ""; else echo $rowPhyTally->physical_tally_sheet_no;?>" 
					onblur="setPhyTally(this.value);"/>
					<br/></h3>
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
							<td align="center">BL No.</td>
							<td align="center">Marks & Number</td>
							<td align="center">Description of Goods</td>
							<td align="center">G.Weight</td>
							<td align="center">Pkg Unit</td>
							<td align="center"><nobr>Pkg Qty</nobr></td>
							<td align="center">Input Fields</td>
							
						</tr>						
							<?php
								include_once("FrontEnd/mydbPConnection.php");
								
								// for($i=0; $i< count($rtnContainerList)+1;$i++) 
								for($i=0; $i< count($rtnContainerList);$i++) 
								{ 
								//echo "After Loop : ".count($rtnContainerList)+1;
									?>
								
									<tr bgcolor="#A9D0F5">
										<td>
											<?php echo $i+1; echo "/".$rtnContainerList[$i]['id'];?>
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
													if(isset($rowMarks->actual_marks))
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
											?>	
										</td>
										<td>
											<?php
												if(isset($rtnContainerList[$i]['Description_of_Goods']))
												{
													echo "<font size='2'>".substr($rtnContainerList[$i]['Description_of_Goods'], 0, 50)."</font>";
												}
												else
												{
													echo "";
												}
												
											?>
										</td>
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
											<table id="tblInner" border="0" cellpadding="1" cellspacing="2" bgcolor="#fff" 
												style="width:100%;">
												<tr align="center" bgcolor="#7fb3d5 ">													
													<td>IMDG Class</td>
													<td>Quantity</td>
													<td>Unit</td>
													<td>Weight</td>
													<td>Storage Area</td>
													<td width=150>Physical Marks</td>
													<td width=150>Remarks</td>
													<?php
														if(isset($save_btn_status))
														{
															if($save_btn_status=="1") { ?><td class="actionTd">Action</td><?php }
														} 
													?>
													<td align="center" colspan="7"><nobr></nobr></td>
													
												</tr>
												
												<!--For loop start -->
												<?php
												$supDtlId = "";
												if(isset($rtnContainerList[$i]['id']))
												{
													$supDtlId = $rtnContainerList[$i]['id'];
												}
												
												$strrcv = "";
												$numrowrcvAll = 0;
												$numrowfltAll = 0;
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
													<td><?php echo $rowrcv->imdg;?></td>
													<td><?php echo $rowrcv->total_pack;?></td>
													<td><?php echo $rowrcv->rcv_unit;?></td>
													<td><?php echo $rowrcv->weight;?></td>
													<td><?php echo $rowrcv->shed_loc;?></td>
													
													<!--td><?php echo $rowrcv->rcv_unit;?></td>
													<td><?php echo $rowrcv->shed_loc;?></td>
													<td><?php echo $rowrcv->shift_name;?></td-->
													
													<td width=150><font size="1"><?php echo substr($rowrcv->actual_marks, 0, 20);?></font></td>
													<td width=150><font size="1"><?php echo substr($rowrcv->remarks, 0, 20);?></font></td>
													<?php if($rowrcv->exchange_done_status!="1") {?>
														<td class="actionTd">
														<a href="<?php echo site_url('PShedController/pShedDeleteTallyRcv/'.$rowrcv->id."/".$rowrcv->cont_number."/".str_replace("/","_",$rowrcv->import_rotation)."/".$tbl);?>" 
														onclick="return confirm('Are you sure you want to delete Tally?');">
															Delete
														</a>
														</td>
													<?php }?>
												</tr>
												<?php } ?>
												<!--While loop end-->
												<tr align="center" bgcolor="#a9cce3">
				<form name="myForm"  onsubmit="return validate();" action="<?php echo site_url('PShedController/pShedSaveTallyRcv');?>" method="post" >
				
													<td>
														<input type="hidden" name="berth_op" id="berth_op<?php echo $i;?>">
														<input type="hidden" name="shed_name" id="shed_name<?php echo $i;?>" >

														<input class="chkValidation" size="5" type="text" id="imdgClass<?php echo $i;?>" name="imdgClass" value="<?php if($numrowrcvAll==0) echo $rtnContainerList[$i]['cont_imo']; else echo $rtnContainerList[$i]['cont_imo'];?>" />													
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="totalPck<?php echo $i;?>" name="totalPck" value="<?php if($numrowrcvAll==0) echo '0'; else echo '0';?>" />
													</td>
													<td>
														<input class="chkValidation" type="search" size="5" 
														value="<?php if(isset($RcvUnit)){echo $RcvUnit;} else {echo "";};?>" list="rcvUnit" placeholder="Pick Unit" name="rcv_unit">
														<datalist id="rcvUnit" >
														<?php				
															//$supDtlId = $rtnContainerList[$i]['id'];
															//$strrcvAll = "";
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
														<input class="chkValidation" size="5" type="text" id="weight<?php echo $i;?>" name="weight" value="<?php if($numrowrcvAll==0) echo '0.0'; else echo '0.0';?>"/>		
														<input class="chkValidation" size="5" type="text" id="weightUnit<?php echo $i;?>" name="weightUnit" value="TON" readonly="true"/>	
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" 
															id="storageArea<?php echo $i;?>" name="storageArea" 
															onkeyup="this.value = this.value.toUpperCase();" 
															value="<?php if($numrowrcvAll==0) echo ''; else echo '';?>"/>	
													</td>
													
													<td>
														<textarea cols="13" rows="2" name="actualmarks" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
													</td>
													<td>
														<textarea cols="13" rows="2" name="remark" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
														<!--input type="text" name="remark<?php echo $i; ?>" value="<?php if($rowremarks==0) echo ""; else echo $remarks; ?>"/-->
													</td>
													<?php 
														if(isset($save_btn_status)) {
														if($save_btn_status=="1") {
													?>
													<td>
														<input style="display:inline" id="Save" type="submit" value="Save" onclick="berthOpPut()"/>							
														<!--a href="<?php echo site_url('report/saveTallyRcv/'.$rtnContainerList[$i]['id']);?>">Save</a-->								
													</td>
													<?php } }?>
												</tr>
												<input type="hidden" 
													value="<?php if(isset($rtnContainerList[$i]['id'])){ echo $rtnContainerList[$i]['id'];}?>"  name="dtlId" style="width:80px">
												<input type="hidden" value="<?php echo $rotation?>" id="rot" name="rot" style="width:80px">
												<input type="hidden" value="<?php echo $cont?>" id="cont" name="cont" style="width:80px"> 
												<input type="hidden" value="<?php echo $tbl?>" name="tbl" style="width:80px">
												<input type="hidden" value="<?php echo count($rtnContainerList);?>" 
													name="cnt" id="cnt" style="width:80px">
												<input type="hidden" name="phy_tally_no" id="phy_tally_no<?php echo $i;?>" style="width:80px">
												</form>
											</table>
										</td>										
										
									</tr>
									<?php
								} 
							?>
									
									
									<!-- new row - start -->
									<tr bgcolor="#A9D0F5">
										<td>
											<?php echo $i+1;?>
										</td>
											
										<td>
											<?php 
												// if(isset($rtnContainerList[$i]['BL_No']))
												// {
													// echo "<font size='2'>".$rtnContainerList[$i]['BL_No']."</font>";
												// }
												// else
												// {
													// echo "";
												// }
											?>
											
										</td>				
										
										<td width="100">
											<?php
											// if(isset($rtnContainerList[$i]['id']))
											// {
												// $strMarks = "select distinct(actual_marks) as actual_marks from shed_tally_info 
															// where igm_sup_detail_id='".$rtnContainerList[$i]['id']."'";
												// $resMarks = mysqli_query($con_cchaportdb,$strMarks);
												// $rowMarks = mysqli_fetch_object($resMarks);
												// //echo "".$strMarks;
												// if(isset($rowMarks->actual_marks))
												// {
													// echo $rowMarks->actual_marks;
												// }
												// else
												// {
													// echo "<font size='2'>".substr($rtnContainerList[$i]['Pack_Marks_Number'], 0, 50)."</font>";
												// }
											// }
											// else
											// {
												// echo "";
											// }											
											
										?>
										</td>
										<td>
											<?php
												// if(isset($rtnContainerList[$i]['Description_of_Goods']))
												// {
													// echo "<font size='2'>".substr($rtnContainerList[$i]['Description_of_Goods'], 0, 50)."</font>";
												// }
												// else
												// {
													// echo "";
												// }
												
											?>
										</td>
										<td>											
											<?php 
												// if(isset($rtnContainerList[$i]['Cont_gross_weight']))
												// {
													// echo $rtnContainerList[$i]['Cont_gross_weight'];
												// }
												// else
												// {
													// echo "";
												// }
											?>
										</td>
										<td>
											<?php 
												// if(isset($rtnContainerList[$i]['Pack_Description']))
												// {
													// echo $rtnContainerList[$i]['Pack_Description'];
												// }
												// else
												// {
													// echo "";
												// }												
											?>
										</td>
										<td>
											<?php 
												// if(isset($rtnContainerList[$i]['Pack_Number']))
												// {
													// echo $rtnContainerList[$i]['Pack_Number'];
												// }
												// else
												// {
													// echo "";
												// }												
											?>
										</td>				
										<td>
											<table id="tblInner" border="0" cellpadding="1" cellspacing="2" bgcolor="#fff" style="width:100%;">
												<tr align="center" bgcolor="#7fb3d5 ">
													
													<td>IMDG Class</td>
													<td>Quantity</td>
													<td>Unit</td>
													<td>Weight</td>
													<td>Storage Area</td>
													<td width=150>Physical Marks</td>
													<td width=150>Remarks</td>
													<?php
														if(isset($save_btn_status))
														{
															if($save_btn_status=="1") { ?><td class="actionTd">Action</td><?php }
														} 
													?>
													<td align="center" colspan="7"><nobr></nobr></td>
													
												</tr>
												
												<!--For loop start -->
												<?php if(count($rtnContainerList)==0) { ?>
												<?php													
												$strrcv = "";
												$numrowrcvAll = 0;
												$numrowfltAll = 0;												
												$stiData = "SELECT * FROM shed_tally_info 
															WHERE import_rotation='$rotation' AND cont_number='$cont'";
												
												$resstiData = mysqli_query($con_cchaportdb,$stiData);
												while($rowStiData = mysqli_fetch_object($resstiData))
												{
												?>
												<tr align="center" bgcolor="#a9cce3">													
													<td><?php echo $rowStiData->imdg;?></td>
													<td><?php echo $rowStiData->total_pack;?></td>
													<td><?php echo $rowStiData->rcv_unit;?></td>
													<td><?php echo $rowStiData->weight;?></td>
													<td><?php echo $rowStiData->shed_loc;?></td>
													
													
													<td width=150><font size="1"><?php echo substr($rowStiData->actual_marks, 0, 20);?></font></td>
													<td width=150><font size="1"><?php echo substr($rowStiData->remarks, 0, 20);?></font></td>
													<?php if($rowStiData->exchange_done_status!="1") {?>
														<td class="actionTd">
														<a href="<?php echo site_url('PShedController/pShedDeleteTallyRcv/'.$rowStiData->id."/".$rowStiData->cont_number."/".str_replace("/","_",$rowStiData->import_rotation)."/".$tbl);?>" 
														onclick="return confirm('Are you sure you want to delete Tally?');">
															Delete
														</a>
														</td>
													<?php }?>
												</tr>
												<?php } ?>
												<?php } ?>
												<!--While loop end-->
												<tr align="center" bgcolor="#a9cce3">
												<form action="<?php echo site_url('PShedController/pShedSaveTallyRcv');?>" 
													method="post" onsubmit="setPhyTallyBeforeSave()">
				
													<td>
														<input class="chkValidation" size="5" type="text" id="imdgClass<?php echo $i;?>" name="imdgClass" value="" />													
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="totalPck<?php echo $i;?>" name="totalPck" value="" />	
													</td>
													<td>
														<input class="chkValidation" type="search" size="5" 
														value="<?php if(isset($RcvUnit)){echo $RcvUnit;} else {echo "";};?>" list="rcvUnit" placeholder="Pick Unit" name="rcv_unit">
														<datalist id="rcvUnit" >
														<?php				
															//$supDtlId = $rtnContainerList[$i]['id'];
															//$strrcvAll = "";
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
														<input class="chkValidation" size="5" type="text" id="weight<?php echo $i;?>" name="weight" value=""/>		
														<input class="chkValidation" size="5" type="text" id="weightUnit<?php echo $i;?>" name="weightUnit" value="TON" readonly="true"/>	
													</td>
													<td>
														<input class="chkValidation" size="5" type="text" id="storageArea<?php echo $i;?>" name="storageArea" onkeyup="this.value = this.value.toUpperCase();" value=""/>	
													</td>
													
													<td>
														<textarea cols="13" rows="2" name="actualmarks" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
													</td>
													<td>
														<textarea cols="13" rows="2" name="remark" style="resize:none;" onkeyup="this.value = this.value.toUpperCase();"></textarea>
													</td>
													<?php 
														// if(isset($save_btn_status))
															// {
														// if($save_btn_status=="1")
															// {
													?>
													<td>
														<input style="display:inline" id="Save" type="submit" value="Save"/>
														
													</td>
													<?php
													// } 
													// }
													?>
												</tr>
												<input type="hidden" 
													value="<?php if(isset($rtnContainerList[$i]['id'])){ echo $rtnContainerList[$i]['id'];}?>"  name="dtlId" style="width:80px">
												<input type="hidden" value="<?php echo $rotation?>" id="rot" name="rot" style="width:80px">
												<input type="hidden" value="<?php echo $cont?>" id="cont" name="cont" style="width:80px"> 
												<input type="hidden" value="<?php echo $tbl?>" name="tbl" style="width:80px">
												<input type="hidden" name="phy_tally" id="phy_tally" style="width:80px">
												</form>
											</table>
										</td>	
									</tr>
									<!-- new row - end -->
						
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
												<form style="display:inline" 
													action="<?php echo site_url('PShedController/pShedTallyReportPdf/'.str_replace("/","_",$rotation).'/'.str_replace("/","_",$cont))?>" target="_blank" method="POST">
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
								<?php if($exchange_btn_status=="1") { ?>
									<input id="exBtn"  type="button" id="ExBtn" value="Exchange" onclick="exchangeDone()"/>
								<?php } else { echo "Exchange Done"; }?>
							</td>
							<!--<?php //if($view_btn_status=="1" && $exchange_btn_status=="0") {?>
								<td id="btnTr3">
								<div>
									<a class="button" href="#popup1"  onclick="txttransfer()"><font color="white">Upload Signature</font></a>
								</div>
								</td>
								
								<?php //} ?>-->
							<!--td>
								<?php if($view_btn_status=="1" && $exchange_btn_status=="0")
								{?>
								<div >
									<a class="button" href="#popup1"  onclick="txttransfer()"><font color="white">Upload Signature</font></a>
								</div>
								<?php }?>
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
				//include("popUpSignature.php") ;
				//include("popUpSignatureNew.php") ;
			?>
			
			</div>
			
		</div>	
	</body>
</html>