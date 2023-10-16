


<script Language="JavaScript">
 function ClickStart(val) 
{
	var strtQuery="";
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
	
	if(val="0")
	{
		alert("Start");
		xmlhttp.open("POST","<?php echo site_url('ajaxController/getBlockList')?>?yard="+val+"&jval="+jval,false);
		xmlhttp.send();
	}
	else{
		alert("End");
		xmlhttp.open("POST","<?php echo site_url('ajaxController/getBlockList')?>?yard="+val+"&jval="+jval,false);
	    xmlhttp.send();
	}
	
}
function getShiftName(shift,jval)
{
	//alert("ShiftName : "+shift+jval);
	var sval = "shift"+jval;
	var shiftBox = document.getElementById(sval);
	//alert(shiftBox);
	shiftBox.value = shift;
	
	//alert(shiftBox.value);
}
 </script>
<?php
	include("FrontEnd/dbConection.php");
?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<header class="panel-heading">
								<h6 class="panel-title" align="center">
									<?php echo $msg; ?>									
								</h6>								
							</header>
							<div class="panel-body">
								<!--div class="row">
									<div class="col-sm-12 text-center mt-md mb-md">
										<div class="ib">
											<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
											<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										</div>
									</div>
								</div-->
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/blockWiseEquipmentList') ?>">
									<div class="form-group">
										<label class="col-md-3 control-label">&nbsp;</label>
										<div class="col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Equipment</span>
												<input type="text" name="search" id="search" class="form-control" placeholder="Equipment" value="">
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
										</div>													
									</div>
								</form>
								<table class="table table-bordered table-hover table-striped mb-none"  id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Serial No</th>
											<th class="text-center">Block</th>
											<th class="text-center">Equipment</th>
											<th class="text-center">Shift</th>	
											<th class="text-center">Action</th>	
											<th class="text-center">Action</th>	
										</tr>
									</thead>
									<tbody>
										<?php 
											$j=$start;
											for($i=0;$i<count($equipmentList);$i++) { 
											$j++;
											$equip = $equipmentList[$i]['EQUIPEMENT'];
											/* include('FrontEnd/dbConection.php');
											$i=0;
											$mlo="";
											$querystr="select * from ctmsmis.offdoc";
											$query=mysqli_query($con_sparcsn4,$querystr);
											while($row=mysqli_fetch_object($query)){
											$i++; */
										?>
										<tr class="gradeX">
											<td align="center"><?php  echo $j; ?></td>
											<td align="center">
												<?php if($equipmentList[$i]['BLOCK']) echo $equipmentList[$i]['BLOCK']; else echo "&nsp;"; ?>
											</td>
											<td align="center">
												<?php if($equipmentList[$i]['EQUIPEMENT']) echo $equipmentList[$i]['EQUIPEMENT']; else echo "&nbsp;"; ?>
											</td>
											<td align="center">
												<?php 
													$str1 = "select shift from ctmsmis.mis_equip_detail
													inner join ctmsmis.mis_equip_assign_detail on ctmsmis.mis_equip_assign_detail.equip_detail_id=ctmsmis.mis_equip_detail.id
													where equipment='$equip' and start_state=1 and end_state=0 and work_out_state=0 and date(start_work_time)=date(now()) 
													order by ctmsmis.mis_equip_assign_detail.id desc limit 1";
													//echo "Query : ".$str1;
													$res1 = mysqli_query($con_sparcsn4,$str1);
													$row1 = mysqli_fetch_object($res1); 
													$num_rows = mysqli_num_rows($res1);	
													$sftVal = isset($row1->shift) ? $row1->shift : "";
												?>
												<select name="shift" id="shift" class="form-control" onchange="getShiftName(this.value,<?php echo $j;?>)">											
													  <option value="">--Select--</option>
													  <option value="A" <?php if($sftVal=='A'){?> selected <?php } ?> >A</option>
													  <option value="B" <?php if($sftVal=='B'){?> selected <?php } ?> >B</option>
													  <option value="C" <?php if($sftVal=='C'){?> selected <?php } ?> >C</option>					  
												</select> 
											</td>
											<td align="center">
												<?php
													$str = "select ctmsmis.mis_equip_assign_detail.id,ctmsmis.mis_equip_assign_detail.start_state,ctmsmis.mis_equip_assign_detail.end_state,ctmsmis.mis_equip_assign_detail.work_out_state from ctmsmis.mis_equip_detail
													inner join ctmsmis.mis_equip_assign_detail on ctmsmis.mis_equip_assign_detail.equip_detail_id=ctmsmis.mis_equip_detail.id
													where ctmsmis.mis_equip_detail.equipment='$equip' and date(start_work_time) = date(now()) order by ctmsmis.mis_equip_assign_detail.id desc limit 1";
													$res = mysqli_query($con_sparcsn4,$str);
													$row = mysqli_fetch_object($res);
													
													$start_state = isset($row->start_state) ? $row->start_state : "";
													$end_state=isset($row->end_state) ? $row->end_state : "";
													$work_out_state=isset($row->work_out_state) ? $row->work_out_state : "";
													$id=isset($row->id) ? $row->id : "";
													
													if(($start_state==0 and $end_state==0) or ($start_state==1 and $work_out_state==1) or ($start_state==1 and $end_state==1))
													{
												?>
												<form action="<?php echo site_url('uploadExcel/equipmentStartWorkoutPerform');?>" method="POST">
													<input type="hidden" name="block" value="<?php echo $equipmentList[$i]['BLOCK'];?>">
													<input type="hidden" name="equipment" value="<?php echo $equipmentList[$i]['EQUIPEMENT'];?>">
													<input type="hidden" id="shift<?php echo $j;?>" name="shift<?php echo $j;?>" value="">
													<input type="hidden" id="jval" name="jval" value="<?php echo $j;?>">
													<input type="hidden" name="btnState" value="start">
													<input type="submit" value="Start" name="start" class="mb-xs mt-xs mr-xs btn btn-success">							
												</form>
												<?php
													}
													else
													{
												?>
												<form action="<?php echo site_url('uploadExcel/equipmentStartWorkoutPerform');?>" method="POST">
													<input type="hidden" name="block" value="<?php echo $equipmentList[$i]['BLOCK'];?>">
													<input type="hidden" name="equipment" value="<?php echo $equipmentList[$i]['EQUIPEMENT'];?>">							
													<input type="hidden" name="btnState" value="end">
													<input type="hidden" name="detailID" value="<?php echo $id;?>">
													<input type="submit" value="End" name="end" class="mb-xs mt-xs mr-xs btn btn-danger">							
												</form>
												<?php
													}
												?>
											</td>
											<td align="center">
												<form action="<?php echo site_url('uploadExcel/equipmentStartWorkoutPerform');?>" method="POST">
													<input type="hidden" name="block" value="<?php echo $equipmentList[$i]['BLOCK'];?>">							
													<input type="hidden" name="equipment" value="<?php echo $equipmentList[$i]['EQUIPEMENT'];?>">
													
													<input type="hidden" name="btnState" value="workout">
													<?php
													if($start_state==0)
													{
													?>
													<input type="submit" value="Work Out" name="workout" class="mb-xs mt-xs mr-xs btn btn-success" disabled style="background-color:gray;">	
													<?php
													}
													else if($start_state==1 && $work_out_state==1)
													{
													?>
													<input type="submit" value="Work Out" name="workout" class="mb-xs mt-xs mr-xs btn btn-success" disabled style="background-color:gray;">	
													<?php
													}
													else if($start_state==1 && $end_state==1)
													{
													?>
													<input type="submit" value="Work Out" name="workout" class="mb-xs mt-xs mr-xs btn btn-success" disabled style="background-color:gray;">	
													<?php
													}
													else if($start_state==1 && $end_state==0 && $work_out_state==0)
													{
													?>
														<input type="submit" value="Work Out" name="workout" class="mb-xs mt-xs mr-xs btn btn-success">
														<input type="hidden" name="detailID" value="<?php echo $id;?>">
													<?php
													}
													?>
												</form> 
											</td>
										</tr>
										
										<?php } ?>
										
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>




