<script language="JavaScript">
   function show_info()
	{
		var cont_no=document.container_search.cont_no.value;
		
		if(cont_no=="")
		{
			alert("No container no. Please provide one.");
			return false;
		}
	}
	
	function chk_weight()
	{
		var current_weight=parseInt(document.getElementById("quantity").value);
		var stc=document.getElementById("stc").value;
		var tot_dlv_qty=parseInt(document.getElementById("tot_dlv_qty").value);
		
		stc=stc.substr(0,stc.indexOf(' '));
		
		var gross_weight=tot_dlv_qty+current_weight;
		
		if(gross_weight > stc)
		{
			if (confirm("Total quantity is more then assignment. Continue?") == true) 
			{
				return true ;
			} 
			else 
			{
				return false;
			}
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
							<header class="panel-heading">
								<p class="panel-title" align="center">
									<?php echo $msg; ?>										
								</p>								
							</header>
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="post" 
								action="<?php echo site_url("report/head_delivery_search");?>" 
								id="container_search" name="container_search" onsubmit="return(show_info());" target="_blank">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">	
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Container No</span>
												<input type="text" id="cont_no" name="cont_no" class="form-control" />
											</div>
										</div>										
										<div class="row">
											<div class="col-sm-12 text-center">
												<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
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