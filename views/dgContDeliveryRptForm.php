<script type="text/javascript">
	function getCriteria(srcCriteria)
	{
		//alert(srcCriteria);
		if(srcCriteria=="rotation")
		{
			document.getElementById("srotation").disabled = false;
			document.getElementById("fdate").disabled = true;
			document.getElementById("ldate").disabled = true;
			
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").value = "";
			document.getElementById("ldate").value = "";
		}
		else if(srcCriteria=="date")
		{
			document.getElementById("fdate").disabled = false;
			document.getElementById("ldate").disabled = false;
			document.getElementById("srotation").disabled = true;
			
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").value = "";
			document.getElementById("ldate").value = "";
		}
		else
		{
			document.getElementById("srotation").disabled = true;
			document.getElementById("fdate").disabled = true;
			document.getElementById("ldate").disabled = true;
			
			document.getElementById("srotation").value = "";
			document.getElementById("fdate").value = "";
			document.getElementById("ldate").value = "";
		}
		
		// if(document.getElementById("assignment_date").value=="" )
		// {
			// alert("Please fill assignment_date");
			// return false
		// }
		// else
		// {
			// return true;
		// }
	}
	function chkBlankField()
	{
		var searchCriteria = document.getElementById("sCriteria").value;
		if(searchCriteria=="rotation")
		{
			if(document.getElementById("srotation").value=="")
			{
				alert("Please fill rotation field !");
				return false;
			}
		}
		else if(searchCriteria=="date")
		{
			if(document.getElementById("fdate").value=="" || document.getElementById("ldate").value=="")
			{
				alert("Please fill both dates !");
				return false;
			}
		}
		// if(document.getElementById("fdate").value=="" && document.getElementById("ldate").value=="" && document.getElementById("srotation").value=="")
		// {
			// alert("Please provide search value");
			// return false
		// }
		// else
		// {
			// return true;
		// }
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
							<form class="form-horizontal form-bordered" method="POST" name="dg_cont_delivery_form" 
								id="dg_cont_delivery_form" onsubmit="return chkBlankField();"
								action="<?php echo site_url("Report/dgContDelivery_action");?>" enctype="multipart/form-data" 
								target="_blank">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search by <span class="required">*</span></span>
											<select name="sCriteria" id="sCriteria" class="form-control" 
												onchange="getCriteria(this.value)" required>
												<option value="" label="--Select--" selected>--Select--</option>
													<!--option value="rnd">Rotation & Date</option-->
													<option value="rotation">Rotation</option>
													<option value="date">Date</option>
											</select>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
											<input type="text" class="form-control" id="srotation" name="srotation" value="" disabled/>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Date</span>
											<input type="date" class="form-control" id="fdate" name="fdate" disabled/>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">To Date </span>
											<input type="date" class="form-control" id="ldate" name="ldate" disabled/>
										</div>
									</div>
									<div class="col-md-offset-4 col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="fileOptions" name="fileOptions" value="xl">
											<label for="radioExample3">Excel</label>
										</div>
									</div>
									<div class="col-md-2">
										<div class="radio-custom radio-success">
											<input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
											<label for="radioExample3">HTML</label>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
												Show
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