<script language="JavaScript">
	function changeTextBox(val)
	{
		var div_office_code = document.getElementById("div_office_code");
		var div_c_number = document.getElementById("div_c_number");
		var div_c_date = document.getElementById("div_c_date");
		var div_entry_date = document.getElementById("div_entry_date");
		var div_cont_no = document.getElementById("div_cont_no");
		
		if(val=="office_code")
		{
			div_office_code.style.display="inline";
			div_c_number.style.display="none";
			div_c_date.style.display="none";
			div_entry_date.style.display="none";
			div_cont_no.style.display="none";
		}
		else if(val=="c_number")
		{
			div_office_code.style.display="none";
			div_c_number.style.display="inline";
			div_c_date.style.display="none";
			div_entry_date.style.display="none";
			div_cont_no.style.display="none";
		}
		else if(val=="c_date")
		{
			/* document.getElementById('div_office_code').style.display="none";
			document.getElementById('div_c_number').style.display="none";
			document.getElementById('div_c_date').style.display="inline";
			document.getElementById('div_entry_date').style.display="none";
			document.getElementById('div_cont_no').style.display="none"; */
			
			div_office_code.style.display="none";
			div_c_number.style.display="none";
			div_c_date.style.display="inline";
			div_entry_date.style.display="none";
			div_cont_no.style.display="none";
		}
		else if(val=="entry_date")
		{
			div_office_code.style.display="none";
			div_c_number.style.display="none";
			div_c_date.style.display="none";
			div_entry_date.style.display="inline";
			div_cont_no.style.display="none";
		}
		else if(val=="cont_no")
		{
			div_office_code.style.display="none";
			div_c_number.style.display="none";
			div_c_date.style.display="none";
			div_entry_date.style.display="none";
			div_cont_no.style.display="inline";
		}
	}
	
	function validate()
	{
		var search_by=document.search_by_form.search_by.value;
		
		if(search_by == "")
		{
			alert("Provide a search value");
			return false;
		}		
	}
</script>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('POSController/LiftingEntryForm') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="post" action="<?php echo site_url("report/search_be_list");?>" 
								id="search_by_form" name="search_by_form" onsubmit="return validate();">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-6">		
											<div class="form-group">
												<label class="col-md-4 control-label">Search Value</label>
												<div class="col-md-8">
													<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
														<option value="">---Select---</option>
														<option value="office_code">Office Code</option>
														<option value="c_number">BE Number</option>
														<option value="c_date">BE Date</option>
														<option value="entry_date">Entry Date</option>
														<option value="cont_no">Container No</option>
													</select>
												</div>
											</div>
											<div class="form-group" id="div_office_code" style="">
												<label class="col-md-4 control-label">Search Value :</label>
												<!--span class="input-group-addon span_width">Search Value : </span-->
												<div class="col-md-8">
												<input type="text" id="search_office_code" name="search_office_code" class="form-control" />
												</div>
											</div>
											<div class="form-group" id="div_c_number" style="display:none;">
												<label class="col-md-4 control-label">Search Value :</label>
												<!--span class="input-group-addon span_width">Search Value : </span-->
												<div class="col-md-8">
												<input type="text" id="search_c_number" name="search_c_number" class="form-control" />
												</div>
											</div>
											<div class="form-group" id="div_c_date" style="display:none;">
												<label class="col-md-4 control-label">Search Value :</label>
												<!--span class="input-group-addon span_width">Search Value : </span-->
												<div class="col-md-8">
												<input type="date" id="search_c_date" name="search_c_date" class="form-control" />
												</div>
											</div>
											<div class="form-group" id="div_entry_date" style="display:none;">
												<label class="col-md-4 control-label">Search Value :</label>
												<!--span class="input-group-addon span_width">Search Value : </span-->
												<div class="col-md-8">
												<input type="date" id="search_entry_date" name="search_entry_date" class="form-control" />
												</div>
											</div>
											<div class="form-group" id="div_cont_no" style="display:none;">
												<label class="col-md-4 control-label">Search Value :</label>
												<!--span class="input-group-addon span_width">Search Value : </span-->
												<div class="col-md-8">
												<input type="text" id="search_cont_no" name="search_cont_no" class="form-control" />
												</div>
											</div>
										</div>
																						
										<div class="row">
											<div class="col-sm-12 text-center">
												<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
												<button type="submit" name="View" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
											</div>													
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
											</div>
										</div>
									</div>	
								</form>
								<?php if(count($rslt_list_of_be)!=0) { ?>
								<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr class="gridDark">
											<th class="text-center" colspan="8">Bill of Entry List</th>	
										</tr>
										<tr class="gridDark">
											<th class="text-center">Sl</th>
											<th class="text-center">Office Code</th>
											<th class="text-center">C Number</th>
											<th class="text-center">BE Date</th>	
											<th class="text-center">Exit Note Number</th>	
											<th class="text-center">Entry Date</th>	
											<th class="text-center">Total Container</th>	
											<th class="text-center">Action</th>	
										</tr>
									</thead>
									<tbody>
										<?php
											include('FrontEnd/mydbPConnection.php');
											$start = "";
											$j=$start;
											for($i=0;$i<count($rslt_list_of_be);$i++)
											{
													$j++;
													$reg_no=$rslt_list_of_be[$i]['reg_no'];
													$reg_date=$rslt_list_of_be[$i]['reg_date'];		
													
													$sql_tot_cont="SELECT COUNT(*) AS tot_cont 
													FROM sad_container
													INNER JOIN sad_info ON sad_info.id=sad_container.sad_id
													WHERE reg_no='$reg_no' and reg_date='$reg_date'";
													
													$rslt_tot_cont=mysqli_query($con_cchaportdb,$sql_tot_cont);
													
													$row_tot_cont=mysqli_fetch_object($rslt_tot_cont);
													$tot_cont=$row_tot_cont->tot_cont;
										?>
										<!--tr class="gradeX" style="background-color:blue;"-->
										<tr <?php if($rslt_list_of_be[$i]['place_dec']==""){?>style="background-color:#FB4444;color:#FCFBFB;"<?php } else {?>class="gridLight"<?php } ?>">
											<td align="center"><?php echo $j; ?></td>
											<td align="center"><?php echo $rslt_list_of_be[$i]['office_code']; ?></td>
											<td align="center"><?php echo $rslt_list_of_be[$i]['reg_no']; ?></td>
											<td align="center"><?php echo $rslt_list_of_be[$i]['reg_date']; ?></td>
											<td align="center"><?php echo $rslt_list_of_be[$i]['place_dec']; ?></td>
											<td align="center"><?php echo $rslt_list_of_be[$i]['entry_dt']; ?></td>
											<td align="center"><?php echo $tot_cont; ?></td>
											<td align="center">
												<form action="<?php echo site_url("report/xml_conversion_action");?>"  method="post" target="_blank">
													<input type="hidden" name="office_code" id="office_code" value="<?php echo $rslt_list_of_be[$i]['office_code']; ?>" />
													<input type="hidden" name="c_nubmber" id="c_nubmber" value="<?php echo $rslt_list_of_be[$i]['reg_no']; ?>" />
													<input type="hidden" name="xml_date" id="xml_date" value="<?php echo $rslt_list_of_be[$i]['reg_date']; ?>" />
													<input type="submit" name="view" id="view" value="View" class="btn btn-primary btn-sm" />
												</form>
											</td>
										</tr>
								<?php } }?>
									</tbody>
								</table>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>