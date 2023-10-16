<script>
	function validate()
	{		
		if( document.myform.searchDt.value == "" )
		{
			alert( "Please provide Date!" );
			document.myform.searchDt.focus() ;
			return false;
		}
		else
		{
			return( true );
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" id="myform" name="myform" method="POST" action="<?php echo site_url('report/containerOperationReportList'); ?>" 
					onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>									
									<input type="date" style="width:200px;" id="searchDt" name="searchDt" class="form-control login_input_text"> 
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
									<select name="cont_position" id="cont_position">
										<option value="All" label="cont_position">--Select--</option>
										<option value="Delivery Stay" label="Delivery Stay" >Delivery Stay</option>
										<option value="Delivery Cancel" label="Delivery Cancel" >Delivery Cancel</option>																							
										<option value="Container Receive" label="Container Receive" >Container Receive</option>																							
										<option value="Empty Container Remove" label="Empty Container Remove" >Empty Container Remove</option>																						
										<option value="On Chasis Delivery" label="On Chasis Delivery" >On Chasis Delivery</option>																						
										<option value="Empty Lying YARD" label="Empty Lying YARD" >Empty Lying(YARD)</option>		
										<option value="Bidder delivery Auction" label="Bidder delivery Auction" >Bidder delivery / Auction</option>																							
										<option value="Custom Appraise" label="Custom Appraise" >Custom Appraise</option>																							
										<option value="C&F Delivery" label="C&F Delivery" >C&F Delivery</option>
										<option value="Container inventory" label="Container inventory" >Container inventory</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="options" value="xl"> Excel
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" value="html"> HTML
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" value="pdf"> PDF
									</label>
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
