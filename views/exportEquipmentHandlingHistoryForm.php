<script>
	function enabletime(time)
	{
		if(time=="Day" || time=="Night")
		{
			
			fromtime.disabled=true;
			totime.disabled=true;
			todate.disabled=true;
			
			document.myform.fromtime.value="";
			document.myform.totime.value="";
			document.myform.todate.value="";
		}
		else if(time=="timewise")
		{
			fromtime.disabled=false;
			totime.disabled=false;
			todate.disabled=false;
		}
	}
</script>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/exportEquipmentHandlingHistoryView"); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Shift <span class="required">*</span></span>
                                    <select name="shift" id="shift" onchange="enabletime(this.value);" class="form-control" required>
                                        <option value="">shift</option>
                                        <option value="Day">Day</option>
										<option value="Night">Night</option>
										<option value="timewise">TimeWise</option>
                                    </select>
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">

									<span class="input-group-addon span_width">From Time <span class="required">*</span></span>
									<input type="text" name="fromtime" id="fromtime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)" disabled>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled>

									<span class="input-group-addon span_width">To Time <span class="required">*</span></span>
									<input type="text" name="totime" id="totime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)" disabled> 
								</div>												
							</div>
							
							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div>
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>	

</section>