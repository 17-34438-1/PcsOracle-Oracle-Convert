<script type="text/javascript">
	function chkBlankField()
	{
		if(document.getElementById("reportType").value=="" )
		{
			alert("Please select report type");
			return false
		}
		else if(document.getElementById("removalFromDate").value=="" )
		{
			alert("Please fill from date");
			return false
		}
		else if(document.getElementById("removalFromTime").value=="" )
		{
			alert("Please fill from time");
			return false
		}
		else if(document.getElementById("removalToDate").value=="" )
		{
			alert("Please fill to date");
			return false
		}
		else if(document.getElementById("removalToTime").value=="" )
		{
			alert("Please fill to time");
			return false
		}
		else
		{
			return true;
		}
	}	
</script>
<style>
	tr
	{
		background-color:#E1F0FF;
	}
</style>
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
					<form class="form-horizontal form-bordered" name="last24HrsCPAToOffdockRemovalForm" id="last24HrsCPAToOffdockRemovalForm" action="<?php echo site_url("report/last24HrsCPAToOffdockRemovalReport"); ?>" method="POST" onsubmit="return chkBlankField();" enctype="multipart/form-data" target="_blank">
						<div class="form-group">
							<div class="table-responsive">
							<!--table class="table mb-none" style="border:solid 1px #ccc;" width="20px" align="center"-->
								<table class="table mb-none" style="border:solid 1px #ccc;width:70%" align="center">
									<tr>
										<td align="center" colspan="6"><b><?php echo $title; ?></b></td>
									</tr>	
									<tr>
										<td align="right"><label><font color='red'><b>*</b></font>Report Type</label></td>
										<td align="center">:</td>
										<td colspan="4" > 
											<select name="reportType" id="reportType" class="form-control">
												<option value="">---Select Type---</option>
												<option value="all">All</option>
												<option value="remark">With Remarks</option>
											</select>
										</td>							
									</tr>

									<tr>
										<td align="right" class="gridDark"><label><font color='red'><b>*</b></font>Group By</label></td>
										<td align="center" class="gridDark">:</td>
										<td class="gridDark" colspan="4" > 
											<select name="groupBy" id="groupBy" class="form-control">
												<option value="">------Select Group------</option>
												<option value="vessel">Vessel</option>
												<option value="offdock">Offdock</option>
											</select>
										</td>							
									</tr>

									<tr>
										<td align="right"><label><font color='red'><b>*</b></font>From Date</label></td>
										<td align="center">:</td>
										<td> 
											<input type="date" style="width:150px;" id="removalFromDate" name="removalFromDate" value="<?php date("Y-m-d"); ?>"/>
										</td>
										<td> 
											<font color='red'><b>*</b></font>From Time
										</td>
										<td align="center">:</td>
										<td>
											<input type="text" style="width:150px;" id="removalFromTime" name="removalFromTime" value=""/><font color='red'><b> 00:00:00</b></font>
										</td>
										<script>
											$(function() {
												$( "#removalFromDate" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												});
											});
										</script>								
									</tr>
									<tr>
										<td align="right"><label><font color='red'><b>*</b></font>To Date</label></td>
										<td align="center">:</td>
										<td> 
											<input type="date" style="width:150px;" id="removalToDate" name="removalToDate" value="<?php date("Y-m-d"); ?>"/>
										</td>
										<td> 
											<font color='red'><b>*</b></font>From Time
										</td>
										<td align="center">:</td>
										<td>
											<input type="text" style="width:150px;" id="removalToTime" name="removalToTime" value=""/><font color='red'><b> 00:00:00</b></font>
										</td>
										<script>
											$(function() {
												$( "#removalToDate" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												});
											});
										</script>								
									</tr>																	
									<tr>	
										<td colspan="6" align="center">											
											<label class="checkbox-inline">
												<input type="radio" id="fileOptions" name="fileOptions" value="html"> HTML
											</label>
											<label class="checkbox-inline">
												<input type="radio" id="fileOptions" name="fileOptions" value="pdf"> PDF
											</label>
										</td>
									</tr>
									<tr>
										<td colspan="6" align="center">											
											<label class="checkbox-inline">
												<input type="radio" name="sumOrDtlOpt" id="sumOrDtlOpt" value="summary"> Summary
											</label>
											<label class="checkbox-inline">
												<input type="radio" name="sumOrDtlOpt" id="sumOrDtlOpt" value="detail"> Detail
											</label>
										</td>
									</tr>
									<tr>
										<td colspan="6" align="center" width="70px">
											<button type="submit" name="submit_login" id="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Submit</button>
										</td>
									</tr>				
								</table>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
