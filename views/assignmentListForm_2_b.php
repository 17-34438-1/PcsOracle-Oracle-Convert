 <style>
 #table-scroll {
  height:350px;
  overflow:auto;  
  margin-top:20px;
}
 </style>
<script>

	var slno="";
	
	function exchangeDone(sl) 
	{
		var answer = confirm("Are you want to Exchange Done?");
		slno=sl;
		// alert(sl);
		
		
		if (answer) 
		{
			var rotation = document.getElementById ("rotTdId_"+slno).innerHTML;
			var container = document.getElementById ("contTdId_"+slno).innerHTML;
		
			// alert(rotation);
			// alert(container);
			
			// return false;
			
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
			xmlhttp.open("GET","<?php echo site_url('ajaxController/ExchangeDoneStatusChange')?>?rotation="+rotation+"&container="+container,false);
			xmlhttp.send();
		}
		else 
		{
			
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
				// deleteLastrow();
				// $("#exBtn").remove();
				// $("#tblInner tbody tr").find("td:eq(9)").remove();
				// //document.getElementById("vwBtn").style.width = "300px"; 
				// document.getElementById('excngeData').innerHTML = 
				// "<table><tr><td>Exchange Done.</td><td><a class='button' href='#popup1'  onclick='txttransfer()'><font color='white'>Upload Signature</font></a></td></tr></table>";
				// document.getElementById("btnView").style.visibility = 'block';
				
				document.getElementById("exBtn_"+slno).disabled = true; 
				document.getElementById("exBtn_"+slno).value = "Confirmed"; 
				
			}
			else
			{
				alert("Exchange Not Done.");
			}
		}
	}
	
	function chkTrkQty(id)
	{		
		if(document.getElementById('truck_qty_'+id).value=="" || document.getElementById('truck_qty_'+id).value==null || document.getElementById('truck_qty_'+id).value==0)
		{
			alert("Please add truck quantity");
			return false;		
		}
		else
		{
			return true;
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignmentListForm_2'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
								<input type="hidden" id="search" name="search" value="search">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search By : <span class="required">*</span></span>
											<select class="form-control" id="searchBy" name="searchBy">
												<option value="">--Select--</option>
												<option value="rotation">Rotation</option>													
												<option value="cont">Container</option>
												<option value="bl">BL No</option>
												<option value="beNo">B/E No</option>
												<option value="verificationNo">Verification No</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Value : <span class="required">*</span></span>
											<input type="text" name="searchVal" id="searchVal" class="form-control" placeholder="value">
											
										</div>												
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php if(isset($updateMsg)){echo $updateMsg;} ?>
										</div>
									</div>
								</div>	
							</form>
						</div>
					</section>
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 
         
		  <?php echo form_close()?>

          <div class="clr"></div>
        </div>
       <div id="table-scroll" class="panel-body table-responsive">
			<table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<?php
					if($msg != "")
					{
					?>
					<tr>
						<td colspan="13" align="center"><?php echo $msg; ?></td>
					</tr>
					<?php
					}
					?>
					<tr class="gridDark" align="center">												
						<td align="center"><b>SL</b></td>
						<td align="center"><b>Container</b></td>
						<td align="center"><b>Size</b></td>
						<td align="center"><b>Height</b></td>	
						<td align="center"><b>Status</b></td>	
                        <td align="center"><b>Rotation</b></td>
						<td align="center"><b>BL</b></td>
						<td align="center"><b>Assignment Type</b></td>
						<td align="center"><b>Assignment Date</b></td>
                        <!--th>B/E</th>
                        <th>Verify No</th>
                        <th>DO Form</th-->
						<?php
						if($org_Type_id==2)
						{
						?>
                        <!--td align="center"><b>Truck Qty</b></td>
                        <td align="center"><b>Action</b></td-->
                        <td align="center"><b>Truck Detail</b></td>
						<?php
						} 
						else if($org_Type_id==62)
						{
							?>
							<td align="center"><b>Keep Down</b></td>
						<?php }
						?>
					</tr>
					<?php 
					
					include('mydbPConnection.php');
					$blNo="";
					$beNo="";
					$verifyNo="";
					$truckQty=0;
					
					for($i=0;$i<count($rslt_assignmentList);$i++)
					{
						$contstatus=$rslt_assignmentList[$i]['cont_status'];

						// bl - start
						$sql_blNo="SELECT BL_no,Bill_of_Entry_No 
						FROM igm_details 
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
						WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
						
						$rslt_blNo=mysqli_query($con_cchaportdb,$sql_blNo);
						$beNo="";
						while($row_blNo=mysqli_fetch_object($rslt_blNo))
						{
							$blNo=$row_blNo->BL_no;
							$beNo=$row_blNo->Bill_of_Entry_No;
						}
						// bl - end
						
						// be info - start						
						
						$sql_beInfo = "SELECT office_code,reg_no,reg_date
						FROM sad_info						
						WHERE reg_no='$beNo'";
						$rslt_beInfo=mysqli_query($con_cchaportdb,$sql_beInfo);
						
						$office_code="";							
						$reg_date = "";

						while($row_beInfo=mysqli_fetch_object($rslt_beInfo))
						{
							$office_code=$row_beInfo->office_code;							
							$reg_date=$row_beInfo->reg_date;
						}						
						// be info - end
						
						// truck - start
						if($contstatus=="FCL")
						{
							// $sql_verifyNo = "SELECT verify_number,no_of_truck FROM verify_info_fcl WHERE be_no='$beNo'";		
							
							// $rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
							
							// while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
							// {
								// $verifyNo = $row_verifyNo->verify_number;
								// $truckQty = $row_verifyNo->no_of_truck;								
							// }
							
							$sql_igmDtlContId = "SELECT igm_detail_container.id
							FROM igm_details
							INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
							WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
						// echo "<br>";	
						// echo "<br>";	
							
							$rslt_igmDtlContId = mysqli_query($con_cchaportdb,$sql_igmDtlContId);
							
							$igmDtlContId = "";
							while($row_igmDtlContId = mysqli_fetch_object($rslt_igmDtlContId))
							{
								$igmDtlContId = $row_igmDtlContId->id;
							}
							
							$sql_qtyTruck = "SELECT no_of_truck FROM verify_info_fcl WHERE igm_detail_cont_id='$igmDtlContId'";
							$rslt_qtyTruck = mysqli_query($con_cchaportdb,$sql_qtyTruck);
							// echo "<br>";
							// echo "<br>";
							$truckQty = "";
							while($row_qtyTruck = mysqli_fetch_object($rslt_qtyTruck))
							{
								$truckQty = $row_qtyTruck->no_of_truck;
							}

							
						}
						else if($contstatus=="LCL")
						{
							$sql_verifyNo = "SELECT shed_tally_info.id,shed_tally_info.verify_number,verify_other_data.shed_tally_id,verify_other_data.no_of_truck
							FROM verify_other_data
							INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id  
							WHERE import_rotation='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."' 
							LIMIT 1";
							
							$rslt_verifyNo = mysqli_query($con_cchaportdb,$sql_verifyNo);
							
							while($row_verifyNo = mysqli_fetch_object($rslt_verifyNo))
							{
								$verifyNo = $row_verifyNo->verify_number;
								$truckQty = $row_verifyNo->no_of_truck;
							}
						}
						// truck - end
						
						// do - start
						// $sql_chkBE = "SELECT COUNT(Bill_of_Entry_No) AS cnt
						// FROM igm_details
						// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						// WHERE Import_Rotation_No='".$rslt_assignmentList[$i]['rot_no']."' AND cont_number='".$rslt_assignmentList[$i]['cont_no']."'";
						// $rslt_chkBE = mysql_query($sql_chkBE);
						
						// while($row_chkBE = mysql_fetch_object($rslt_chkBE))
						// {
							// $cntBE = $row_chkBE->cnt;
						// }
						
						$sql_doImg = "SELECT do_image_loc FROM do_image WHERE be_no = '$beNo'";
						$rslt_doImg = mysqli_query($con_cchaportdb,$sql_doImg);
						$do_image_loc="";
						while($row_doImg = mysqli_fetch_object($rslt_doImg))
						{
							$do_image_loc = $row_doImg->do_image_loc;
						}
						// do - end
					?>
					<tr class="gridLight" align="center">
						<!--td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td>
						<td id="contTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['cont_number'];?></td>						
						<td id="rotTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
						<td><?php echo $rtnContainerList[$i]['rcv_pack'];?></td>
						<td><?php echo $rtnContainerList[$i]['flt_pack'];?></td>
						<td><?php echo $rtnContainerList[$i]['loc_first'];?></td>
						<td><?php echo $rtnContainerList[$i]['shed_loc'];?></td>
						<td><?php echo $rtnContainerList[$i]['shed_yard'];?></td>
						<td><?php echo $rtnContainerList[$i]['wr_date'];?></td-->
						
						
						<td><?php echo $i+1; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['cont_no']; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['size']; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['height']; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['cont_status']; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['rot_no']; ?></td>
						<td><?php echo $blNo; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['mfdch_value']; ?></td>
						<td><?php echo $rslt_assignmentList[$i]['assignmentDate']; ?></td>
						<!--td>
							<a href="<?php echo site_url('Report/xml_conversion_action/1/'.$office_code.'/'.$beNo.'/'.$reg_date); ?>" target="_blank"><?php echo $beNo; ?></a>
						</td>
						<td>
							<a href="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber/'.$verifyNo); ?>" target="_blank"><?php echo $verifyNo; ?></a>							
						</td>
						<td>
							<?php
							if($do_image_loc!="")
							{	// show uploaded document
							?>
							<a href="<?php echo BASE_PATH."resources/do_image/".$do_image_loc; ?>" class="login_button" target="_blank">DO</a>
							<?php
							}
							?>
						</td-->
						<?php
						if($org_Type_id == 2)
						{
						?>
								<!--input type="hidden" name="verifyNumber" id="verifyNumber" value="<?php echo $verifyNo;?>"/-->								
						<!--form action="<?php echo site_url("Report/editTruckQty");?>" method="post" onsubmit="return chkTrkQty(<?php echo $i+1; ?>)">
							<input type="hidden" name="trk_id" id="trk_id" value="<?php echo $i+1; ?>" />
							<td>
								<input style="width:30px;padding:0px;" type="text" class="form-control" name="truck_qty_<?php echo $i+1; ?>" id="truck_qty_<?php echo $i+1; ?>" value="<?php echo $truckQty;?>" />
							</td>						
							<td>
								<input type="hidden" name="rot_no" id="rot_no" value="<?php echo $rslt_assignmentList[$i]['rot_no'];?>" />
								<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $rslt_assignmentList[$i]['cont_no'];?>" />
								<input type="hidden" name="beNo" id="beNo" value="<?php echo $beNo;?>" />
								<?php
									if($truckQty>0){
								?>
										<input type="submit" name="saveQty" id="saveQty" class="btn btn-xs btn-success" value="Update" />
								<?php		
									}else{
								?>
										<input type="submit" name="saveQty" id="saveQty" class="btn btn-xs btn-success" value="Save" />
								<?php		
									}
								?>
								
							</td>
						</form-->
						<td>
								<!--input type="hidden" name="verifyNo" id="verifyNo" value="<?php echo $verifyNo; ?>" /-->
							<form name="truckEntryForm" id="truckEntryForm" method="post" action="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber'); ?>">								
								<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rslt_assignmentList[$i]['rot_no']; ?>" />
								<input type="hidden" name="contNo" id="contNo" value="<?php echo $rslt_assignmentList[$i]['cont_no']; ?>" />
								<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_assignmentList[$i]['cont_status']; ?>" />
								<input style="width:100px" type="submit" name="truckEntry" id="truckEntry" value="Truck Entry" 
									class="btn btn-xs btn-primary" />
							</form>						
						</td>
						<?php
						}
						else if($org_Type_id==62)
						{
						?>
						<td>
							<?php
								include('mydbPConnection.php');
								$thisRot = $rslt_assignmentList[$i]['rot_no'];								
								$thisCont = $rslt_assignmentList[$i]['cont_no'];								
								$keepDownSt = "";
								$chkKeepDownSt="SELECT verify_info_fcl.keepdown_st 
								FROM igm_detail_container 
								LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
								LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
								LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
								LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
								LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE igm_details.Import_Rotation_No='$thisRot' AND igm_detail_container.cont_number='$thisCont'";
								
								$resKeepDownSt=mysqli_query($con_cchaportdb,$chkKeepDownSt);								
								while($getKeepDownSt=mysqli_fetch_object($resKeepDownSt))
								{
									$keepDownSt=$getKeepDownSt->keepdown_st;
								}
								if($keepDownSt!=1){								
							?>
							<form method="post" action="<?php echo site_url('ShedBillController/updateKeepDownStatus'); ?>" >
								<input type="hidden" name="changeState" id="changeState" value="changeState" />
								<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rslt_assignmentList[$i]['rot_no']; ?>" />
								<input type="hidden" name="contNo" id="contNo" value="<?php echo $rslt_assignmentList[$i]['cont_no']; ?>" />
								<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_assignmentList[$i]['cont_status']; ?>" />								
								<input type="submit" name="keepDown" id="keepDown" value="KeepDown" 
									class="btn btn-xs btn-primary"/>
							</form>
								<?php } else { ?>
									<h6 class="h6 mt-none mb-sm" style="color:red;"><b>Keep Down Done !</b></h6>
								<?php } ?>
						</td>
						<?php
						}
						?>
					</tr>
					<?php }?>
				</table>
		 </div>
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <!-- <div class="sidebar">
	   <?php //include_once("mySideBar.php"); ?>
	  </div> -->
      <div class="clr"></div>
    </div>
	
  </div>
</section>