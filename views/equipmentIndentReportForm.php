<script type="text/javascript">  
	function validate()
	{
		if(document.location_form.location_name.value == "")
		{
			alert("Please provide location name!");
			document.location_form.location_name.focus();
			return false;
		}
		return true ;
	}
</script> 
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-list"></i> GO TO INDENT LIST
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" 
									action="<?php echo site_url('misReport/mis_equipment_indent_report_view') ?>" target="_blank">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">INDENT DATE</span>
												<input type="date" name="indent_date" id="indent_date" class="form-control" >
											</div>
										</div>
										
										<div class="col-sm-12 text-center">
											 <input class="mb-xs mt-xs mr-xs btn btn-primary" type="submit"  value="Search" >
										</div>													
									</div>
									<div class="form-group">
									</div>
								</form>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>