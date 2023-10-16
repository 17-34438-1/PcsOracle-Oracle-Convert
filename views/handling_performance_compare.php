<script type="text/javascript">
	function chk_confirm()
	{
		var discharge_actual=document.getElementById("discharge_actual").value;
		var discharge_ctms=document.getElementById("discharge_ctms").value;
		var loading_actual=document.getElementById("loading_actual").value;
		var loading_ctms=document.getElementById("loading_ctms").value;
		
		document.getElementById("discharge_actual_entry").value=discharge_actual;
		document.getElementById("discharge_ctms_entry").value=discharge_ctms;
		document.getElementById("loading_actual_entry").value=loading_actual;
		document.getElementById("loading_ctms_entry").value=loading_ctms;
		
		if (confirm("Do you want to save ?") == true) 
		{
			return true ;
		} 
		else 
		{
			return false;
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form name="search_user" onsubmit="return(chk_user());" action="<?php echo site_url("report/handling_performance_compare_search");?>" method="post" class="form-horizontal form-bordered">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>
									<input type="date" style="width:200px;" id="perform_search_date" name="perform_search_date" class="form-control login_input_text"/>
								</div>																		
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
						</div>
					</form>
					<br>
					<?php
					if($flag==1)
					{
					?>
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<tr align="center">
							<td rowspan="2"><b>Sl</b></th>
							<td rowspan="2"><b>Vessel</b></th>
							<td rowspan="2"><b>Rotation</b></th>
							<td rowspan="2"><b>Bertd</b></td>
							<td rowspan="2"><b>Agent</b></td>
							<td colspan="2"><b>Discharge</b></td>
							<td colspan="2"><b>Loading</b></td>
							<td rowspan="2"><b>Action</b></td>
						</tr>
						<tr align="center">
							<td><b>Actual</b></td>
							<td><b>CTMS</b></td>
							<td><b>Actual</b></td>
							<td><b>CTMS</b></td>
						</tr>
						<?php
						$dis_actual="";
						$dis_ctms="";
						$load_actual="";
						$load_ctms="";
						
						$btn_disable="";
						include('mydbPConnectionn4.php');
						for($i=0;$i<count($rslt_performance_search);$i++)
						{
							//--
							$id="";
							$dis_actual="";
							$dis_ctms="";
							$load_actual="";
							$load_ctms="";

							$tmp_ata=$perform_search_date;
							$tmp_vvd_gkey=$rslt_performance_search[$i]['cvcvd_gkey'];
							
							$sql_mismatch="SELECT IFNULL(id,0) AS id,discharge_actual,discharge_ctms,loading_actual,loading_ctms 
							FROM ctmsmis.handlingperformancecompare 
							WHERE ata_dt='$tmp_ata' AND vvd_gkey='$tmp_vvd_gkey'
							ORDER BY entry_dt DESC LIMIT 1";
							
							$rslt_mismatch=mysqli_query($con_sparcsn4,$sql_mismatch);
							
							while($row_mismatch=mysqli_fetch_object($rslt_mismatch))
							{
								$id=$row_mismatch->id;
								$dis_actual=$row_mismatch->discharge_actual;
								$dis_ctms=$row_mismatch->discharge_ctms;
								$load_actual=$row_mismatch->loading_actual;
								$load_ctms=$row_mismatch->loading_ctms;
							}
																				
							// if($dis_actual!="" or $dis_ctms!="" or $load_actual!="" or $load_ctms!="")
								// $btn_disable=1;
							//--
						?>
						<tr>
							<form name="actual_ctms_entry_form" id="actual_ctms_entry_form" action="<?php echo site_url("report/actual_ctms_entry_action")?>" method="post">
								<input type="hidden" id="tbl_id" name="tbl_id" value="<?php echo $id; ?>" />
								<input type="hidden" id="vvd_gkey_entry" name="vvd_gkey_entry" value="<?php echo $rslt_performance_search[$i]['cvcvd_gkey']; ?>" />
								<input type="hidden" id="ata_dt_entry" name="ata_dt_entry" value="<?php echo $rslt_performance_search[$i]['ata_dt']; ?>" />
								<input type="hidden" id="perform_search_date_entry" name="perform_search_date_entry" value="<?php echo $perform_search_date; ?>" />
								
								<td align="center"><?php echo $i+1; ?></td>
								<td align="center">
									<input style="width:70px" type="text" id="vsl_name_entry" name="vsl_name_entry" value="<?php echo $rslt_performance_search[$i]['name']; ?>" />
								</td>
								<td align="center">
									<input style="width:70px" type="text" id="rot_entry" name="rot_entry" value="<?php echo $rslt_performance_search[$i]['ib_vyg']; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="berth_entry" name="berth_entry" value="<?php echo $rslt_performance_search[$i]['berth']; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="agent_entry" name="agent_entry" value="<?php echo $rslt_performance_search[$i]['agent']; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="discharge_actual_entry" name="discharge_actual_entry" value="<?php echo $dis_actual; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="discharge_ctms_entry" name="discharge_ctms_entry" value="<?php echo $dis_ctms; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="loading_actual_entry" name="loading_actual_entry" value="<?php echo $load_actual; ?>" />
								</td>
								<td align="center">
									<input style="width:50px" type="text" id="loading_ctms_entry" name="loading_ctms_entry" value="<?php echo $load_ctms; ?>"  />
								</td>
								<td align="center">									
									<button type="submit" id="save_entry" name="save_entry" class="mb-xs mt-xs mr-xs btn btn-info" onclick="return chk_confirm();">Save</button>
								</td>
							</form>
						</tr>
						<?php
						}
						?>
						<form name="add_new_form" id="add_new_form" action="<?php echo site_url("report/add_new_form_action")?>" method="post">
							<input type="hidden" id="perform_search_date_entry_new" name="perform_search_date_entry_new" value="<?php echo $perform_search_date; ?>" />
							<tr>
								<td align="center"><?php echo $i+1; ?></td>
								<td align="center"><input style="width:70px" type="text" name="vsl_name_new" id="vsl_name_new" /></td>
								<td align="center"><input style="width:70px" type="text" name="rot_new" id="rot_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="berth_new" id="berth_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="agent_new" id="agent_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="dis_act_new" id="dis_act_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="dis_ctms_new" id="dis_ctms_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="load_act_new" id="load_act_new" /></td>
								<td align="center"><input style="width:50px" type="text" name="load_ctms_new" id="load_ctms_new" /></td>
								<td align="center">								
									<button type="submit" id="add_new" name="add_new" class="mb-xs mt-xs mr-xs btn btn-info">Add</button>
								</td>
							</tr>
						</form>
					</table>
					<?php
					}
					?>
				</div>
			</section>
		</div>
	</div>
</section>
