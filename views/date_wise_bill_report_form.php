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
								<form class="form-horizontal form-bordered" method="post" 
								action="<?php echo site_url("report/date_wise_bill_of_entry_report_action");?>" 
								id="container_search" name="container_search" onsubmit="return(validate());" target="_blank">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">	
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Entry Date: </span>
												<input type="date" id="be_entry_date" name="be_entry_date" class="form-control" />
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
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>