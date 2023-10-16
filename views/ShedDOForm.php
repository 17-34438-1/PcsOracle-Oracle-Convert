<style>
    th, td{
        padding:5px;
    }
</style>
<?php include("mydbPConnection.php"); ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
    <div class="content">	
		<section class="panel">
			<div class="panel-body">
				<?php if($frmType=="search" or $frmType=="inserted" or $frmType=="deleted") { ?>
					<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("EDOController/shedDOUpload") ?>" target="successMsg">
						<!--onsubmit="return chkMeasurement()"-->
						<input type="hidden" id="contSt" name="contSt" value="<?php echo $contSt;?>">
						<input type="hidden" id="containerCount" name="containerCount" value="<?php echo count($contList);?>">
						<div class="table-responsive">
						<table border="1" style="border-collapse:collapse;margin-bottom:20px;" align="center" width="100%">
							<tr>
								<th colspan="4" valign="center" align="center" style="font-size:20px;"><b>DELIVERY ORDER</b></th>
								<td colspan="2" valign="center"><strong>BL No:</strong> <?php echo $blno;?></td>
								<!--td colspan="1" valign="center"><strong>Status:</strong> <?php echo @$contList[0]['cont_status'];?></td-->
							</tr>
							<tr>
								<td rowspan="5" colspan="3" valign="top">			
									<strong>Notify Party(Complete Name & Address)</strong>
									<p>
										<?php echo $Notify_name;?><br/>
										<?php echo $Notify_address;?>
									</p>
								</td>
								<td>
									<b>Vessel</b><br/><?php echo $Vessel_Name;?>
								</td>
								<td>
									<b>Voyage No</b><br/><?php echo $Voy_No;?>
								</td>
								<td>
									<b>Print Date</b><br/><?php echo date("Y-m-d H:i:s"); ?>
								</td>
							</tr>
							<tr>
								<td>
									<b>Place of Receipt</b><br/>CHATTOGRAM PORT AUTHORITY
								</td>
								<td colspan="2" rowspan="4" valign="top">
									<strong>Other Numbering Identification</strong><br/>
									<p>
										<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Reg No:</b></font> 
											<?php echo $rotNo;?><br/>
									</p>
									<p>
										<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>BE No:</b></font>
										<input type="text" name="billOfEntryNo" id="billOfEntryNo"
											style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" 
												value="<?php if($edit=="edit" or $edit=="extendValidity"){echo $Bill_of_Entry_No_Val;}else {echo $beNo;}?>" 
												pattern="[0-9 _,]*" title="Only Numbers and Comma" required>
										 <?php //echo $Bill_of_Entry_No;?><br/>
									</p>
									<p>
										<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>BE Date:</b></font>
										<input type="date" name="billOfEntryDate" id="billOfEntryDate"
											style="text-align:center; border:1px solid blue;width:150px;height:30px;" autocomplete="off" 
												value="<?php if($edit=="edit" or $edit=="extendValidity"){echo $BE_Dt_Val;} else {echo $beDate;}?>" required>
										 <?php //echo $Bill_of_Entry_No;?><br/>
									</p>
									<p>
										<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Office Code:</b></font>
										<input type="text" name="officeCode" id="officeCode"
											style="text-align:center; border:1px solid blue;width:150px;height:30px;" autocomplete="off" 
												value="<?php if($edit=="edit" or $edit=="extendValidity"){echo $office_Code_Val;}else {echo $ofcCode;}?>" required>
										 <?php //echo $Bill_of_Entry_No;?><br/>
									</p>
									<p>
										<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Remarks:</b></font>
										<textarea type="text" name="remarks" id="remarks"
											style="text-align:center; border:1px solid blue;width:180px;height:35px;"  
												 ><?php if($edit=="edit" or $edit=="extendValidity"){ echo $remarks;}?> </textarea>
										 <?php //echo $Bill_of_Entry_No;?><br/>
									</p>
									<p>
										<font style="margin-left:4px;margin-right:4px;color:#000000;"><b>Date:</b></font> <?php echo $Submission_Date;?><br/>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<b>Port of Loading</b><br/>
									<?php echo $port_of_origin." ".$Port_of_Shipment;?>
								</td>
							</tr>
							<tr>
								<td>
									<b>Port of Discharge</b><br/>
									CHATTOGRAM,BANGLADESH, Bangladesh
								</td>
							</tr>
							<tr>
								<td>
									<b>Place of Delivery</b><br/>
									<?php echo $Port_of_Destination; ?>
								</td>
							</tr>
							<tr>
								<td colspan="4" valign="top">
									<strong>Consignee(Complete Name & Address)</strong>
									<p>
										<?php echo $Consignee_name;?><br>
										<?php echo $Consignee_address;?>
									</p>
								</td>
								<td colspan="2" valign="top">
									<strong>Shipper/Exporter(Complete Name & Address)</strong>
									<p>
												
									</p>
								</td>
							</tr>
							<tr>
								<th valign="top" class="text-center">Quantity</th>
								<th valign="top" class="text-center"><nobr>Kind of Packages</nobr></th>
								<th valign="top" class="text-center">Description of Goods</th>
								<th valign="top" class="text-center">Marks and Numbers</th>
								<th valign="top" class="text-center">Gross Weight</th>
								<th valign="top" class="text-center">Measurement</th>
							</tr>
							<tr>
								<td align="center"><?php echo $igm_pack_number;?></td>
								<td align="center"><?php echo $Pack_Description;?></td>
								<td valign="top" align="center"><?php echo substr($Description_of_Goods,0,100);?></td>
								<td align="center"><?php echo substr($Pack_Marks_Number,0,100);?></td>
								<td align="center">
									<p>
										<nobr><input type="text" name="deliveredWeight" id="deliveredWeight" value="<?php echo $weight;?>" 
											style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" readonly required>
										<input type="text" value="<?php echo $weight_unit;?>" 
											style="text-align:center; border:1px solid blue;width:50px;" autocomplete="off" readonly required></nobr>
									</p>
								</td>
								<td align="center">
									<p>
										<input type="text" name="measurement" id="measurement"
											style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" 
												value="<?php if($edit == "edit"){echo $measurementVal;} else { echo $Volume_in_cubic_meters; }?>" required 
												<?php if($igm_type!="BB") { ?> readonly <?php } ?>>
									</p>
								</td>
							</tr>
							<!--tr>
								<td colspan="4" valign="top">
									<strong>Kind of Packages,Description of Goods ,Marks and Numbers, Container No/Seal No.</strong>
									<p>
										<?php echo $Description_of_Goods;?><br/>
									</p>
									<p>
										<?php echo $Pack_Description;?><br/>
									</p>
									<p>
										<?php echo $Pack_Marks_Number;?><br/>
									</p>
								</td>
								<td valign="top" align="center">
									<b>Gross Weight</b>									
									<p>
										<nobr><input type="text" name="deliveredWeight" id="deliveredWeight" value="<?php echo $weight;?>" 
											style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" readonly required>
										<input type="text" value="<?php echo $weight_unit;?>" 
											style="text-align:center; border:1px solid blue;width:50px;" autocomplete="off" readonly required></nobr>
									</p>
								</td>
								<td valign="top" align="center">
									<b>Measurement</b>
									<p>
										<input type="text" name="measurement" id="measurement"
											style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" 
												value="<?php if($edit == "edit"){echo $measurementVal;} else { echo $Volume_in_cubic_meters; }?>" required 
												<?php if($igm_type!="BB") { ?> readonly <?php } ?>>
									</p>
								</td>
							</tr-->
							<tr>
								<td colspan="4" valign="top" align="left">
									<p>
										<b>Delivered to C&F Agent: </b><!--onblur="getcnf(this.value)"-->
										<input type="text" name="cnflic" id="cnflic"
											style="text-align:center; border:0px solid blue;width:100px;" 
												value="<?php if($edit == "edit"){echo $cnf_lic_no;} else { echo $cnfLicenseNo;} ?>" 
													onblur="getcnf(this.value)"><br><br>
										<b>C&F Name:  </b>
										<?php
										$cfNm = "";
										for($cf=0;$cf<count($cnf_name);$cf++)
										{
											$cfNm = $cnf_name[$cf]['name'];
										}
										?>
										<input type="text" name="cnfname" id="cnfname" value="<?php echo $cnfName; ?>"
											style="text-align:center; border:0px solid blue;width:350px;font-weight:bold;" readonly required>
									</p>
								</td>									
								<td colspan="2" align="center">
									<b>Valid upto: </b>
									<input type="date" name="valid_upto" id="valid_upto" style="width:150px;height:30px;text-align:center;"
										value="<?php if($edit == "edit"){
											if($type_of_bl=="HB" and $igm_type=="GM") 
												{
													echo $validUptodtVal; 
												}
											else 
												{
													if($requested_valid_dt!="" and $requested_valid_dt!=$validUptodtVal) {echo $requested_valid_dt;} else {echo $validUptodtVal;}
												}
											 
											} else {
												if($type_of_bl=="HB" and $igm_type=="GM") {echo $valid_dt_mlo;} else { if($requested_valid_dt!=""){echo $requested_valid_dt;} }
												} ?>" 
										
										max="<?php if($edit == "edit"){if($requested_valid_dt!=""){echo $requested_valid_dt;} else {echo $valid_dt_mlo;}  } else {echo $valid_dt_mlo;}?>" 
												
										<?php 
											if(($type_of_bl=="MB" and $igm_type=="GM") or ($igm_type=="BB")){
												if($contSt=="LCL"){
													echo " ";
												} else {
													echo " ";
												}
											} 
											else if(($type_of_bl=="HB" and $igm_type=="GM")){
												if($contSt=="LCL"){
													echo " ";
												} else {
													echo "";
												}
											}
											?>
											
										<?php if($type_of_bl=="HB" and $igm_type=="GM")
											{ 												
												echo "readonly";
											} 
										?>
									>
								</td>
								<!--td colspan="2" align="center">
									<input type="file" name="dofile" id="dofile" style="width:200px;"-->
										<!--?php if($edit=="edit") { echo " ";} else { echo "required";}?-->
								<!--/td-->
							</tr>
							<tr>
								<td colspan="6">
									<table border="1" style="border-collapse:collapse;" align="center" width="100%">
										<tr>
											<th class="text-center"><input type="checkbox" onclick="selectAllContainer(this);"> All</th>
											<th class="text-center" style="display:none;">Cont Id</th>
											<th class="text-center">Container No</th>
											<th class="text-center">Seal No</th>
											<th class="text-center">Size/Type/Height/Status/Location</th>
											<th class="text-center">Weight</th>
											<th class="text-center">Quantity</th>
										</tr>
										<?php 
											for($i=0;$i<count($contList);$i++) { 
											$cnt = 0;
											$sId = "";
											$sId = $contList[$i]['cId'];
											if($edit == "edit")
											{												
												$strCount = "SELECT COUNT(*) AS countContainer FROM do_upload_wise_container 
														WHERE shed_mlo_do_info_id='$editId' AND cont_igm_id='$sId'";
												$resCount = mysqli_query($con_cchaportdb,$strCount);
												while($rowContContainer = mysqli_fetch_object($resCount)){
													$cnt = $rowContContainer->countContainer;
												}
											}
											else if($edit == "extendValidity")
											{
												$strCount = "SELECT COUNT(*) AS countContainer FROM edo_applied_validity_date 
																WHERE edo_id='$edo_id' AND cont_igm_id='$sId'";
												$resCount = mysqli_query($con_cchaportdb,$strCount);
												while($rowContContainer = mysqli_fetch_object($resCount)){
													$cnt = $rowContContainer->countContainer;
												}
											}
											else
											{
												if(($type_of_bl=="MB" and $igm_type=="GM" and $cnf_vldty_appr_st=="1")){
													$strCount = "SELECT COUNT(*) AS countContainer FROM edo_applied_validity_date 
																	WHERE edo_id='$edo_id' AND cont_igm_id='$sId'";
													$resCount = mysqli_query($con_cchaportdb,$strCount);
													while($rowContContainer = mysqli_fetch_object($resCount)){
														$cnt = $rowContContainer->countContainer;
													}
												} else if(($type_of_bl=="HB" and $igm_type=="GM")) {
													$strCount = "SELECT COUNT(*) AS countContainer FROM edo_applied_validity_date 
																	WHERE edo_id='$edo_id' AND cont_igm_id='$sId'";
													$resCount = mysqli_query($con_cchaportdb,$strCount);
													while($rowContContainer = mysqli_fetch_object($resCount)){
														$cnt = $rowContContainer->countContainer;
													}
												}
											}
										?>
										<tr align="center">
											<td>
												<input type="checkbox" id="contchk<?php echo $i;?>" value="<?php echo $contList[$i]['Cont_gross_weight']; ?>" 
													<?php if($edit=="edit" or $edit=="extendValidity") { ?> 
														<?php if($cnt>0) { ?> 
															checked 
													<?php } } else { ?> 
															<?php if(($type_of_bl=="MB" and $igm_type=="GM" and $cnf_vldty_appr_st=="1")) { ?>
																<?php if($cnt>0) { ?> checked <?php } ?>
															<?php } else { ?> 
																checked 
													<?php } }?> 
													onclick="setTotGrosValue(this,this.value,<?php echo $i;?>)">
											</td>
											<td style="display:none;">
												<input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>" 
													value="<?php echo $contList[$i]['cId']; ?>"
												<?php if($edit=="edit" or $edit=="extendValidity") { ?>
													<?php if($cnt>0) { ?> checked <?php } ?>
												<?php  } else { ?> 
													<?php if(($type_of_bl=="MB" and $igm_type=="GM" and $cnf_vldty_appr_st=="1")) { ?>
														<?php if($cnt>0) { ?> checked <?php } ?>
													<?php  } else { ?>
														checked 
													<?php } ?> 
												<?php } ?> 
											</td>
											<td><?php echo $contList[$i]['cont_number'];?></td>
											<td><?php echo $contList[$i]['cont_seal_number'];?></td>												
											<td>
												<?php 
													echo $contList[$i]['cont_size']."/".$contList[$i]['cont_type']."/".$contList[$i]['cont_height']."/".
														$contList[$i]['cont_status']."/".$contList[$i]['cont_location_code'];
												?>
											</td>
											<td><?php echo $contList[$i]['Cont_gross_weight'];?></td>
											<td><?php echo $contList[$i]['cont_number_packaages'];?></td>
										</tr>
										<?php } ?>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="6" align="center">
									<p>
										<input type="hidden" name="igm_dtl" id="igm_dtl" value="<?php echo $dtl_id;?>">
										<input type="hidden" name="blno" id="blno" value="<?php echo $blno;?>">
										<input type="hidden" name="rotno" id="rotno" value="<?php echo $rotNo;?>">
										<input type="hidden" name="beno" id="beno" 
											value="<?php if($edit=="edit"){echo $Bill_of_Entry_No_Val;}else {echo $Bill_of_Entry_No;}?>">
										<input type="hidden" name="grossQty" id="grossQty" value="<?php echo $weight;?>">
										<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id;?>">
										<!-- <input type="hidden" name="CODE" id="CODE" value="<?php echo $CODE;?>"> -->
										<!-- <input type="hidden" name="type_of_Igm" id="type_of_Igm" value="<?php echo $type_of_Igm;?>"> -->
										<input type="hidden" name="type_of_bl" id="type_of_bl" value="<?php echo $type_of_bl;?>">
										<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type;?>">
										<?php if($edit == "edit") { ?>
											<input type="hidden" name="editId" id="editId" value="<?php echo $editId;?>">
											<input type="hidden" name="update" id="update" value="update">
											<button type="submit" class="btn btn-primary" style="margin-top:10px;">Update</button>
										<?php } else if($edit=="extendValidity") { ?>
											<input type="hidden" name="editId" id="editId" value="<?php echo $editId;?>">
											<input type="hidden" name="extend" id="extend" value="extend">
											<button type="submit" class="btn btn-primary" style="margin-top:10px;">Update</button>
										<?php } else { ?>
											<?php if((isset($logo_pic)) or ($logo_pic != "")) { ?>
												<button type="submit" class="btn btn-primary" style="margin-top:10px;">
													Submit
												</button>
											<?php } else { ?>
												<font color="red"><strong>Please Update Your Organization Profile to Select a Logo.</strong></font>
											<?php } ?>
										<?php } ?>
										<a class="btn btn-primary" style="margin-top:10px;margin-left:10px;" href="<?php echo site_url('EDOController/pendingEDOapplication') ?>">
											BACK TO LIST
										</a>
									</p>
									</br>
									<iframe id="successMsg" name="successMsg" height="50" width="600" style="border:0"></iframe>
								</td>
							</tr>
						</table>
						</div>
					</form>
				<?php } //} ?>
			</div>
		</section> 
		<script>
			function setTotGrosValue(ele,val,ival)
			{				
				var pval = parseFloat(document.getElementById("measurement").value);
				var nval = 0;
				//alert(ele);
				if(ele.checked == true)
				{
					nval = pval+parseFloat(val);
					document.getElementById("idchk"+ival).checked = true;
				}
				else
				{
					nval = pval-parseFloat(val);
					document.getElementById("idchk"+ival).checked = false;
				}	
				// Following line is commented because from now on measurement will not change by clicking on chkbox.....
				// document.getElementById("measurement").value=nval;
			}
			function chkMeasurement()
			{
				var mval = parseFloat(document.getElementById("measurement").value);
				//alert(mval);
				if(mval==0)
				{
					alert("Sorry! Measurement can not be 0");
					return false;
					event.preventDefault();
				}
			}
			
			function selectAllContainer(state)
			{
				var totAllMeasurement = 0;
				var subTotMeasurement = 0;
				var totalContainer = document.getElementById('containerCount').value;
				if(state.checked == true)
				{
					//If "All" is not checked;
					for(var p=0;p<totalContainer;p++)
					{
						document.getElementById("contchk"+p).checked = true;
						document.getElementById("idchk"+p).checked = true;
						subTotMeasurement = parseFloat(document.getElementById("contchk"+p).value);
						totAllMeasurement = parseFloat(totAllMeasurement)+parseFloat(subTotMeasurement);
					}
					// Following line is commented because from now on measurement will not change by clicking on chkbox.....
					// document.getElementById("measurement").value=totAllMeasurement;
				}
				else
				{
					//If "All" is not checked;
					for(var p=0;p<totalContainer;p++)
					{
						document.getElementById("contchk"+p).checked = false;						
						document.getElementById("idchk"+p).checked = false;						
					}
					// Following line is commented because from now on measurement will not change by clicking on chkbox.....
					// document.getElementById("measurement").value = 0;
				}
			}
			
			function getcnf(bb_cnf_lic_no)
			{
				//alert(bb_cnf_lic_no)

				if(bb_cnf_lic_no=="")
				{
					alert("Please enter C&F License no.");			
					document.getElementById('cnflic').focus();
				}
				else
				{
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
						xmlhttp.onreadystatechange=stateChangeCNFName;
						
						var url="<?php echo site_url('ajaxController/getCNFName')?>?bb_cnf_lic_no="+bb_cnf_lic_no;
						// alert(url);
						xmlhttp.open("GET",url,false);
						xmlhttp.send();					
				}
			}
			
			function stateChangeCNFName()
			{
				//alert(xmlhttp.responseText);
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{
					//var selectList=document.getElementById("dept");
					//removeOptions(selectList);
					//alert(xmlhttp.responseText);
					var val = xmlhttp.responseText;
					var jsonData = JSON.parse(val);
					//alert(xmlhttp.responseText);
					var cnf_name = ""; 
					for (var i = 0; i < jsonData.length; i++) 
					{
						cnf_name = jsonData[i].name;
					}
					document.getElementById("cnfname").value=cnf_name;
				}
			}
			
		</script>
    </div>		 
</section>