 <script type="text/javascript">
/*   $(function() {
    $("#equipment").change(function() {
        if ($(this).val() == "All") {
			//console.log(false);
           $("#srcVal").attr("disabled", "disabled");
			//document.getElementById("srcVal").disabled = true;
        }
        else {
            //console.log(true);
            $("#srcVal").removeAttr("disabled");
        }
    });
}); */
function displayValue(val){
	//alert(val);
	if(val=="All" || val=="")
	{
		//alert("d");
		document.getElementById("srcVal").disabled = true;
	}
	else
	{
		//alert("e");
		document.getElementById("srcVal").disabled = false;
	}
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
									<a href="<?php echo site_url('Controller/EntryForm') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
							<div class="panel-body">
									<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/containerHandlingMonthlyView') ?>" target="_blank">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Search Value:</span>
												<input type="text" name="search_value" id="srcVal" class="form-control" >
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From Date:</span>
												<input type="date" name="from_date" id="from_date" class="form-control" >
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To Date:</span>
												<input type="date" name="to_date" id="to_date" class="form-control" >
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<button type="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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