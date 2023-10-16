<script type="text/javascript">
	function getCriteria(srcCriteria)
	{
		//alert(srcCriteria);
		if(srcCriteria=="rnd")
		{
			document.getElementById("srotation").disabled = false;
			document.getElementById("fdate").disabled = false;
			document.getElementById("todate").disabled = false;
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").value = "";
            document.getElementById("todate").value = "";
		}
		else if(srcCriteria=="rotation")
		{
			document.getElementById("srotation").disabled = false;
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").disabled = true;
			document.getElementById("fdate").value = "";
            document.getElementById("todate").disabled = true;
            document.getElementById("todate").value = "";
		}
		else if(srcCriteria=="date")
		{
			document.getElementById("fdate").disabled = false;
            document.getElementById("todate").disabled = false;
			document.getElementById("srotation").disabled = true;
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").value = "";
            document.getElementById("todate").value = "";
		}
		else
		{
			document.getElementById("srotation").disabled = true;
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").disabled = true;
			document.getElementById("fdate").value = "";
            document.getElementById("todate").disabled = true;
            document.getElementById("todate").value = "";
		}
		
	}

	function chkBlankField()
	{
		if(document.getElementById("sCriteria").value=="rnd")
		{
			if(document.getElementById("fdate").value=="" || document.getElementById("todate").value=="" || document.getElementById("srotation").value=="")
			{
				alert("Please provide search value");
				return false;
			}
		}
		else if(document.getElementById("sCriteria").value=="rotation")
		{
			if(document.getElementById("srotation").value=="")
			{
				alert("Please provide rotation");
				return false;
			}
		}
		else if(document.getElementById("sCriteria").value=="date")
		{
			if(document.getElementById("fdate").value=="" || document.getElementById("todate").value=="")
			{
				alert("Please provide date");
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
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php //echo $msg;?>
								</div>
							</div>
							<form class="form-horizontal form-bordered" method="POST" name="fcl_cont_dlv_assignment_summary_form" 
								id="fcl_cont_dlv_assignment_summary_form" 
								action="<?php echo site_url("Report/lyingContainerListView");?>" enctype="multipart/form-data" 
								target="_blank">
								<div class="form-group">
									<!--div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search by <span class="required">*</span></span>
											<select name="sCriteria" id="sCriteria" class="form-control" 
												onchange="getCriteria(this.value)"; required>
												<option value="" label="--Select--" selected >--Select--</option>
												<option value="rotation" >Rotation</option>
												<option value="date">Date</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
											<input type="text" class="form-control" id="srotation" name="srotation" value="" disabled/>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date</span>
											<input type="date" name="fdate" id="fdate" class="form-control" 
												value="<?php date("Y-m-d"); ?>" disabled>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date </span>
											<input type="date" class="form-control" id="todate" name="todate" value="<?php date("Y-m-d"); ?>" disabled/>
										</div>
									</div-->
									<div class="row">
										<div class="col-md-offset-4 col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="lim" name="lim" value="fst" checked>
												<label for="radioExample3">First 15000</label>
											</div>
										</div>
										<div class="col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="lim" name="lim" value="lst">
												<label for="radioExample3">Last All</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-offset-4 col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="fileOptions" name="fileOptions" value="xl" checked>
												<label for="radioExample3">Excel</label>
											</div>
										</div>
										<div class="col-md-2">
											<div class="radio-custom radio-success">
												<input type="radio" id="fileOptions" name="fileOptions" value="html">
												<label for="radioExample3">HTML</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-11 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
												View
											</button>
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