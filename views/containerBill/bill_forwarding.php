
<style>
label
{
	color: black;
}
</style>
<html class="fixed">
	<head>
		<?php
		include("cssAssetsList.php");
		?>
	</head>
	<body>
		<section class="body">
			<?php include("headerTop.php"); ?>

			<div class="inner-wrapper">
				<?php include("contentMenu.php"); ?>
				<!-- start: sidebar -->

				<?php include dirname(__FILE__).'/../sidebar.php'; ?>

				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo site_url('menu_controller/index'); ?>">
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
									<form class="form-horizontal form-bordered" method="post" name="bill_forwarding_form" id="bill_forwarding_form" action="<?php echo site_url("ContainerBill/bill_forwarding_view"); ?>" target="_blank" >
										<div class="form-group">											
											<div class="col-md-12" align="center">
												<label class="col-md-12" for="inputDefault"><?php echo $msg; ?></label>
												<!--label class="col-md-12" for="inputDefault" align="right"><font color="black"><b><a href="<?php echo site_url('ContainerBill/container_bill_List'); ?>" target="_blank">View Bill List</a></b></font></label-->
											</div>
										</div>
										<!--div class="form-group">											
											<div class="col-md-12" align="right">
												<label class="col-md-12" for="inputDefault"><font color="black"><b><a href="<?php echo site_url('ContainerBill/container_bill_List'); ?>" target="_blank">View Bill List</a></b></font></label>
											</div>
										</div-->
										<!--div class="form-group" >
											<label class="col-md-12 control-label" for="inputDefault" align="right">View Bill List</label>
										</div-->
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">Bill Type</label>
											<div class="col-md-6">
												<select class="form-control mb-md" name="bill_type" name="bill_type" id="bill_type" onchange="change_state()">
													<option value="">--Select Bill Type--</option>
													<?php
													for($i=0;$i<count($rslt_bill_type);$i++)
													{
													?>
													<option value="<?php echo $rslt_bill_type[$i]['id']; ?>"><?php echo $rslt_bill_type[$i]['billtype']; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">From Date</label>
											<div class="col-md-6">
												<input type="date" class="form-control" id="fromDate" name="fromDate" >
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label" for="inputDefault">To Date</label>
											<div class="col-md-6">
												<input type="date" class="form-control" id="toDate" name="toDate" >
												<!--script>
													 $(function() {
													 $( "#toDate" ).datepicker({
													  changeMonth: true,
													  changeYear: true,
													  dateFormat: 'yy-mm-dd', // iso format
													 });
													 });
													</script-->
											</div>
										</div>
									
										<div class="form-group">
											<div class="col-md-12" align="center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary">View</button>
											</div>
										</div>										
									</form>
								</div>
							</section>
						</div>
					</div>

					<!-- start: page -->
					<div class="row">
						
						
					</div>
					<!-- end: page -->
				</section>
			</div>
		</section>

		<?php
		include("jsAssets.php");
		?>
	</body>
</html>