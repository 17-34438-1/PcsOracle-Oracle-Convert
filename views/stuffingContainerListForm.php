
<script type="text/javascript">
    function validate()
	{
		if(document.stuffingContainerSearchForm.search_by.value == "cont_no")
		{
			if(document.stuffingContainerSearchForm.cont_no.value == "")
			{
				alert( "Please provide a container no!" );
				document.stuffingContainerSearchForm.cont_no.focus() ;
				return false;
			}
			if(document.stuffingContainerSearchForm.stuffing_date.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerSearchForm.stuffing_date.focus() ;
				return false;
			}
		}
		
		else if(document.stuffingContainerSearchForm.search_by.value == "offdock")
		{
			if(document.stuffingContainerSearchForm.offdock.value == "")
			{
				alert( "Please provide an offdock!" );
				document.stuffingContainerSearchForm.offdock.focus() ;
				return false;
			}
			if(document.stuffingContainerSearchForm.stuffing_date.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerSearchForm.stuffing_date.focus() ;
				return false;
			}
		}
	}
	
	function search_criteria(search_by)
	{
//		alert("ok");
		if(document.stuffingContainerSearchForm.search_by.value == "")
		{
			alert( "Please provide a value!" );
			document.stuffingContainerSearchForm.search_by.focus() ;
			return false;
		}
		else
		{
			if(search_by == "cont_no")
			{
				stuffing_date.disabled=false;
				cont_no.disabled=false;
				offdock.disabled=true;
				document.stuffingContainerSearchForm.offdock.value="";
				document.stuffingContainerSearchForm.stuffing_date.value="";
			}
			else if(search_by == "offdock")
			{
				stuffing_date.disabled=false;
				cont_no.disabled=true;
				offdock.disabled=false;
				document.stuffingContainerSearchForm.offdock.value="";
				document.stuffingContainerSearchForm.stuffing_date.value="";
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
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" name= "stuffingContainerSearchForm" id="stuffingContainerSearchForm" 
							onsubmit="return(validate());" method="POST" action="<?php echo site_url('report/stuffingContainerListPerform') ?>"
							target="_blank">
							
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">														
											<span class="input-group-addon span_width">Search by</span>
											<select name="search_by" id="search_by" class="form-control" onchange="search_criteria(this.value);" required>
												<option value="">--Select--</option>
												<option value="offdock">Offdock</option>
												<option value="cont_no">Container</option>
											</select>														
										</div>
										<div class="input-group mb-md">														
											<span class="input-group-addon span_width">Offdock</span>
											<select name="offdock" id="offdock" class="form-control" disabled>
												<option value="">--Select--</option>
												<?php
													include('FrontEnd/mydbPConnectionn4.php');
													$sql_offdock_list="select code,name from ctmsmis.offdoc";
													$rslt_offdock_list=mysqli_query($con_sparcsn4,$sql_offdock_list);
													while($offdock_list=mysqli_fetch_object($rslt_offdock_list))
													{ 
												?>
													<option value="<?php echo $offdock_list->code; ?>"><?php echo $offdock_list->name; ?></option>
												<?php
												}
												?>
											</select>													
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container</span>
											<input type="text" name="cont_no" id="cont_no" class="form-control" placeholder="Container No" disabled>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Stuffing Date</span>
											<input type="date" name="stuffing_date" id="stuffing_date" class="form-control" value="<?php echo date("yy/m/d"); ?>" disabled>
										</div>
										<div class="col-md-offset-3 col-md-2" style="display:none;">
											<div class="radio-custom radio-success">
												<input type="radio" id="option" name="option" value="pdf">
												<label for="radioExample3">PDF</label>
											</div>
										</div>
										<div class="col-md-3" style="display:none;">
											<div class="radio-custom radio-success">
												<input type="radio" id="option" name="option" value="html" checked>
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