<script>
	function chkBlankField()
	{
		var fromDate = document.getElementById('fromDate').value;
		var toDate = document.getElementById('toDate').value;
		
		if(fromDate=="" || toDate=="")
		{		
			alert("Please fill the form");
			return false;		
		}		
		else
			return true;
	}	
</script>
<style>
	 #table-scroll {
	  height:500px;
	  width: 1000px;
	 <!-- overflow:auto;  -->
	  margin-top:0px;
      }

</style>

<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>
	
	<div class="row">
		<div class="col-md-12">					
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/dlvFromOffdockReportAction") ?>" onsubmit="return chkBlankField();">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>		
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">From Date : <span class="required">*</span></span>
								<input type="date" name="fromDate" id="fromDate" class="form-control login_input_text" autofocus= "autofocus" placeholder="From Date" />
							</div>												
						</div>	
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">To Date : <span class="required">*</span></span>
								<input type="date" name="toDate" id="toDate" class="form-control login_input_text" autofocus= "autofocus" placeholder="To Date"  />
							</div>												
						</div>	
						<div class="row" id="applyBtn">
							<div class="col-sm-12 text-center">
								<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-primary">
									Search
								</button>
							</div>													
						</div>										
					</div>
				</form>
			</div>
		</div>
	</div>
</section>