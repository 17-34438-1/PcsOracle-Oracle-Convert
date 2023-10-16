 <script type="text/javascript">
   
   function validate()
   {
    if( document.myForm.fromdate.value == "" )
      {
       alert( "Please provide fromdate!" );
       document.myForm.fromdate.focus() ;
       return false;
      }
    
    if( document.myForm.todate.value == "" )
    {
     alert( "Please provide todate!" );
     document.myForm.todate.focus() ;
     return false;
    }
    return true ;
   }
   
   
   function changeTextBox(v)
    {
		var search_value = document.getElementById("search_value");
		var fromdate = document.getElementById("fromdate");
		var todate = document.getElementById("todate");
		if(v=="dateRange")
		{
			search_value.disabled=true;
			fromdate.disabled=false;
			todate.disabled=false;
		
		}	
		else if(v=="")
		{
			search_value.disabled=true;
			fromdate.disabled=true;
			todate.disabled=true;
		}
		else 
		{
			search_value.disabled=false;
			fromdate.disabled=true;
			todate.disabled=true;		
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
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/contDetailInfoByRotWithXlsDownloadPerform') ?>"
							target="_blank">
							
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">														
											<span class="input-group-addon span_width">Search by</span>
											<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);" required>
												<option value="" label="search_by" selected>---Select---</option>
												<option value="rotation" label="Rotation" >Rotation</option>
												<option value="container" label="Container" >Container</option>
												<option value="dateRange" label="DateRange" >Date Range</option>
											</select>														
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search value :</span>
											<input type="text" name="search_value" id="search_value" class="form-control" placeholder="" disabled>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date:</span>
											<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php echo date("yy/m/d"); ?>" disabled>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date:</span>
											<input type="date" name="todate" id="todate" class="form-control" value="<?php echo date("yy/m/d"); ?>" disabled>
										</div>
										<div class="col-md-offset-2 col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="options" name="options" value="xl">
												<label for="radioExample3">Excel</label>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-3">
											<div class="radio-custom radio-success">
												<input type="radio" id="options" name="options" value="html">
												<label for="radioExample3">HTML</label>
											</div>
										</div>
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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
		<!-- end: page -->
	</section>
	</div>