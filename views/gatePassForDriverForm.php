<script>
    function validate()
    {
        var visitId = document.getElementById('trucVisitId').value.trim();

		if(visitId == "" || visitId == null){
			alert("Field can not be empty!");
			return false;
		}
    }
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("ShedBillController/gatePassforDriver") ?>" target="_blank" onsubmit="return validate();">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Truck Visit No. <span class="required">*</span></span>
								<input type="number" name="trucVisitId" id="trucVisitId" class="form-control login_input_text" tabindex="1" placeholder="Truck Visit No." autofocus>
							</div>												
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success" tabindex="2">Search</button>
							</div>													
						</div>						
					</div>	
				</form>
			</div>
		</section>
	</div>
</div>
</section>
</div>