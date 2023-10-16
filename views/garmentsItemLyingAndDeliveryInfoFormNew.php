<script type="text/javascript">
	function chkBlankField()
	{
		if(document.getElementById("reportType").value=="" )
		{
			alert("Please select report type");
			return false
		}
		else if(document.getElementById("garmentsFromDate").value=="" )
		{
			alert("Please fill from date");
			return false
		}
		else if(document.getElementById("garmentsToDate").value=="" )
		{
			alert("Please fill to date");
			return false
		}		
		else
		{
			return true;
		}
	}	
	
	function changeState(val)
	{			
		if(val=="all")
		{			
			document.getElementById("rotNum").readOnly=true;			
			document.getElementById("rotNum").value="";			
			document.getElementById("rotNum").style.backgroundColor = "#E7E5E5";
		}
		else if(val=="giveRot")
		{			
			document.getElementById("rotNum").readOnly=false;
			document.getElementById("rotNum").style.backgroundColor = "";			
		}
	}
</script>
<style>
	tr, td{
		padding:5px;
	}
	tr
	{
		background-color:#c3ecf9;
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
					<form class="form-horizontal form-bordered"  name="garmentsItemLyingAndDeliveryInfoForm" id="garmentsItemLyingAndDeliveryInfoForm" action="<?php echo site_url("report/garmentsItemLyingAndDeliveryInfoActionNew"); ?>" method="POST" onsubmit="return chkBlankField();" enctype="multipart/form-data" target="_blank">
						<div class="form-group">
							<div class="table-responsive">
								<table class="mb-none" align="center">	

									<tr>
										<td align="right"><label><font color='red'><b>*</b></font> From Date</label></td>
										<td align="center">:</td>
										<td> 
											<input type="date" class="form-control" style="width:350px;" id="garmentsFromDate" name="garmentsFromDate" value="<?php date("Y-m-d"); ?>"/>
										</td>								
										<script>
											$(function() {
												$( "#garmentsFromDate" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												});
											});
										</script>								
									</tr>
									<tr>
										<td align="right"><label><font color='red'><b>*</b></font> To Date</label></td>
										<td align="center">:</td>
										<td> 
											<input type="date" class="form-control" style="width:350px;" id="garmentsToDate" name="garmentsToDate" value="<?php date("Y-m-d"); ?>"/>
										</td>								
										<script>
											$(function() {
												$( "#garmentsToDate" ).datepicker({
													changeMonth: true,
													changeYear: true,
													dateFormat: 'yy-mm-dd', // iso format
												});
											});
										</script>								
									</tr>	

                                    <tr>
										<td align="right"><label><font color='red'><b>*</b></font> Lying more days</label></td>
										<td align="center">:</td>
										<td> 
											<input type="number" class="form-control" style="width:350px;" id="lyingMoreDays" name="lyingMoreDays" />
										</td>								
									</tr>	

									<!-- <tr>	
										<td colspan="3" align="center">											
											<label class="checkbox-inline">
												<input type="radio" name="lyingOrDlv" id="lyingOrDlv" value="lying"> Lying
											</label>
											<label class="checkbox-inline">
												<input type="radio" name="lyingOrDlv" id="lyingOrDlv" value="delivery"> Delivery
											</label>
										</td>								
									</tr> -->

									<tr>
										<td colspan="3" align="center">											
											<label class="checkbox-inline">
												<input type="radio" name="sumOrDtlOpt" id="sumOrDtlOpt" value="summary" checked> Summary
											</label>
											<label class="checkbox-inline">
												<input type="radio" name="sumOrDtlOpt" id="sumOrDtlOpt" value="detail"> Detail
											</label>
										</td>
									</tr>

									<tr>
										<td colspan="3" align="center">											
											<label class="checkbox-inline">
												<input type="radio" name="viewType" id="viewType" value="html"> HTML
											</label>
											<label class="checkbox-inline">
												<input type="radio" name="viewType" id="viewType" value="excel"> EXCEL
											</label>
										</td>
									</tr>

									<tr>
										<td colspan="3" align="center" width="70px">
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
