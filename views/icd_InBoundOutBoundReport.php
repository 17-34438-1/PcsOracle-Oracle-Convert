<script language="JavaScript">
    function validate()
    {
        if(document.container_search.be_entry_date.value == "" )
        {
            alert( "Please Select Date!" );
            document.container_search.be_entry_date.focus() ;
            return false;
        }
        return true ;
    }
</script>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo "ICD Inbound Outbound Report";?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="post" 
								action="<?php echo site_url("Report/icdInBoundOutBoundReportAction") ; ?>" 
								id="container_search" name="container_search" target="_blank"> 
									<div class="form-group">
									<div>
										<div class="col-sm-offset-3 col-md-6">	
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Select Date: </span>
												<input type="date" id="be_entry_date" name="be_entry_date" class="form-control" />
											</div>
										</div>
										</div>
										<div class="row">
										<div class="col-md-offset-4 col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="options" name="options" value="in">
											<label for="radioExample3">In Bound</label>
										</div>
										</div>
										<div class="col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="options" name="options" value="out">
												<label for="radioExample3">Out Bound</label>
											</div>
										</div>
										
										</div>	
										<div class="row">
											<div class="col-sm-11 text-center">
												<button type="submit" name="View" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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
						
						
						
						
					<!-- end: page -->
				</section>
			</div>