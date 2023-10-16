<script>
	function visibility_off()
	{
		rotation.disabled=true;
		container_id.disabled=true;
		rotation_type.disabled=true;
		date.disabled=true;
		mlo.disabled=true;
		containers.disabled=true;
	}
	
	function change_state()
	{
		var bill_type=document.bill_generation_form.bill_type.value;
		
		if(bill_type=="")
		{
			visibility_off();
		}
		else if(bill_type == 2 || bill_type == 3 || bill_type == 27 || 	bill_type == 67 || bill_type == 108 || bill_type == 112 || bill_type == 120 || bill_type == 128)
		{
			visibility_off();
			rotation.disabled=false;
		}
		else if(bill_type == 51 || bill_type == 59 ||bill_type == 63 || bill_type == 47 || bill_type == 116 || bill_type == 124 || bill_type == 135)
		{
			visibility_off();
			rotation.disabled=false;
			mlo.disabled=false;
			containers.disabled=false;
		}
		else if(bill_type==11 || bill_type == 22 )
		{
			visibility_off();		
			date.disabled=false;
		}
		else if(bill_type==18)
		{
			visibility_off();
			mlo.disabled=false;
		}
		else if(bill_type==29)
		{
			visibility_off();
			rotation.disabled=false;
			rotation_type.disabled=false;
			mlo.disabled=false;
		}
		else if(bill_type==75)
		{
			visibility_off();
			container_id.disabled=false;
		}
	}
</script>
<style>
label
{
	color: black;
}
</style>
<html class="fixed">
	<head>
		<?php include("cssAssets.php"); ?>
	</head>
	<body>
		<section class="body">
			<?php include("headerTop.php"); ?>

			<div class="inner-wrapper">
				<?php include dirname(__FILE__).'/../sidebar.php'; ?>

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo site_url('controllerName/functionName'); ?>">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Dashboard&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
							</ol>
							<!--a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a-->
						</div>
					</header>

					<div class="row">
						<div class="col-lg-12">
							<section class="panel">
								<header class="panel-heading">
									<h2 class="panel-title"></h2>
								</header>
								<div class="panel-body">
									<form class="form-horizontal form-bordered" method="post" name="bill_generation_form" 
										id="bill_generation_form" action="<?php echo site_url("ContainerBill/bill_generation_action"); ?>">
										<div class="form-group">											
											<div class="col-md-12" align="center">
												<label class="col-md-12" for="inputDefault"><?php echo $msg; ?></label>
												<!--label class="col-md-12" for="inputDefault" align="right"><font color="black"><b><a href="<?php echo site_url('Menu_Controller/container_bill_List'); ?>" target="_blank">View Bill List</a></b></font></label-->
											</div>
										</div>
										<!--div class="form-group">											
											<div class="col-md-12" align="right">
												<label class="col-md-12" for="inputDefault"><font color="black"><b><a href="<?php echo site_url('Menu_Controller/container_bill_List'); ?>" target="_blank">View Bill List</a></b></font></label>
											</div>
										</div-->
										<!--div class="form-group" >
											<label class="col-md-12 control-label" for="inputDefault" align="right">View Bill List</label>
										</div-->
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">Bill Type</label>
											<div class="col-md-6">
												<select class="form-control mb-md" name="bill_type" id="bill_type" 
													onchange="change_state()" required>
													<option value="">--Select Bill Type--</option>
													<?php
													for($i=0;$i<count($rslt_bill_type);$i++)
													{
													?>
													<option value="<?php echo $rslt_bill_type[$i]['id']; ?>">
														<?php echo $rslt_bill_type[$i]['billtype']; ?>
													</option>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">Rotation</label>
											<div class="col-md-6">
												<input type="text" class="form-control" id="rotation" name="rotation" disabled >
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">Container ID</label>
											<div class="col-md-6">
												<input type="text" class="form-control" id="container_id" name="container_id" disabled >
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputSuccess">Rotation Type</label>
											<div class="col-md-6">
												<div class="radio">
													<label>
														<input type="radio" name="rotation_type" id="rotation_type" value="import" 
															checked="" disabled >
														Import
													</label>													
												</div>
												<div class="radio">
													<label>
														<input type="radio" name="rotation_type" id="rotation_type" value="export" disabled>
														Export
													</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">Date</label>
											<div class="col-md-6">
												<input type="date" class="form-control" id="date" name="date" disabled>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">MLO</label>
											<div class="col-md-6">
												<select class="form-control mb-md" name="mlo" id="mlo" disabled>
													<option value="">--Select MLO Name--</option>
													<?php for($i=0;$i<count($rslt_mlo_data);$i++) { ?>
													<option value="<?php echo $rslt_mlo_data[$i]['ID']?>">
														<?php echo $rslt_mlo_data[$i]['ID']?>
													</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="textareaDefault">Containers</label>
											<div class="col-md-6">
												<textarea class="form-control" rows="3" id="containers" name="containers" disabled></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-12" align="center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">Generate</button>
											</div>
										</div>										
									</form>
								</div>
							</section>
						</div>
					</div>

					<!-- start: page -->
					<div class="row"></div>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php include("jsAssets.php"); ?>
	</body>
</html>