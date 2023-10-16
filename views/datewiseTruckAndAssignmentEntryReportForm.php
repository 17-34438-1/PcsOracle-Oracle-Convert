<script>
	function chkDate()
		{
			var fdate = document.getElementById("from_date").value;
			var tdate = document.getElementById("to_date").value;
			if(fdate==tdate)
			{
				return true;
			}
			else if(fdate < tdate)
			{
				return true;
			}
			else if(fdate > tdate)
			{
				alert("Wrong combination of date !");
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
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" target="_blank"
								action="<?php echo site_url('GateController/truckAndAssignmentEntryReport') ?>" onsubmit="return chkDate();">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date</span>
											<input type="date" name="from_date" id="from_date" class="form-control" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date</span>
											<input type="date" name="to_date" id="to_date" class="form-control" required>
										</div>
									</div>
									
									<!--div class="col-md-offset-4 col-md-3">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="excel">
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="pdf" checked>
											<label for="radioExample3">PDF</label>
										</div>
									</div-->
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
									
							</form>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>