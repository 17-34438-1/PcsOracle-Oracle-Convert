<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
	<section class="panel panel-primary" id="panel-1" data-portlet-item="">
		<header class="panel-heading portlet-handler">
			<h2 class="panel-title">Truck Entry Options</h2>
		</header>
		<div class="panel-body">
			<div class="row" id="main-boxes">
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php echo $msg; ?>
					</div>
				</div>
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="box-style-2 green">
								<form method="post" action="<?php echo site_url('ShedBillController/driverYardDelivery'); ?>">
									<button type="submit" name="btnYardDelivery" class="btn btn-success btn-lg col-md-9">Yard Delivery</button>
								</form>
							</div>
						</div>
						
						<div class="col-md-6 col-sm-6">
							<div class="box-style-2 orange">
								<form method="post" action="<?php echo site_url('ShedBillController/driverShedDelivery'); ?>">
									<button type="submit" name="btnShedDelivery" class="btn btn-warning btn-lg col-md-9">Shed Delivery</button>
								</form>
							</div>
						</div>	
					</div>	

					<div class="row">
						<div class="col-md-12 col-sm-12">
							&nbsp;
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="box-style-2 green">
								<form method="post" action="<?php echo site_url('ShedBillController/driverOcdDelivery'); ?>">
									<button type="submit" name="btnYardDelivery" class="btn btn-info btn-lg col-md-9" >OCD</button>
								</form>
							</div>
						</div>
							
					</div>
				</div>		           
			</div>		
		</div>

	</section>
	<!-- end: page -->
</section>
</div>