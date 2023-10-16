<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title">RELEASE ORDER FORM</h2>
				</header>
				<div class="panel-body" align="center">
					<form class="form-inline" action="<?php echo site_url('report/releaseorderpdf'); ?>" method="post" target="_blank">
						<div class="form-group">
							<label class="sr-only" for="exampleInputUsername2">Verification No:</label>
							<input type="text" class="form-control" name="verify_number" id="verify_number" placeholder="Verification No">
						</div>
						&nbsp;&nbsp;
						<div class="radio">
							<label>
								<input type="radio" name="options" id="options" value="RlsOrder" checked="TRUE">
								Release Order
							</label>
						</div>
						&nbsp;
						<div class="radio">
							<label>
								<input type="radio" name="options" id="options" value="Bill" checked="FALSE">
								BILL
							</label>
						</div>
						&nbsp;&nbsp;
						<button type="submit" class="btn btn-primary">Search</button>
						<!--button type="reset" class="btn btn-default">Cancel</button-->
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
