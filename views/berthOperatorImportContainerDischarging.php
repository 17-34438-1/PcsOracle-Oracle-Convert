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
					<form class="form-horizontal form-bordered" id = "myform" method="POST" action="<?php echo site_url('Report/myRefferImportContainerDischargeReportView'); ?>" target = "_blank">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="options1" name="options1" value="dis" checked> Discharge
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options1" name="options1" value="deli"> Delivery
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options1" name="options1" value="con"> Connection
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options1" name="options1" value="assign"> Assignment
									</label>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date:<span class="required">*</span></span>
									<input type="date" style="width:300px;" id="fromdate" name="fromdate" class="form-control login_input_text" />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date:<span class="required">*</span></span>
									<input type="date" style="width:300px;" id="todate" name="todate" class="form-control login_input_text" />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Name:<span class="required">*</span></span>
									<select name="yard_no" id="yard_no" style="width:300px;" class="form-control" required>
										<option value="" label="yard_no" selected>Select</option>
											<option value="CCT" label="CCT" >CCT</option>
											<option value="NCT" label="NCT" >NCT</option>
											<option value="GCB" label="GCB">GCB</option>
											<option value="OFY" label="OFY">OFY</option>
											<option value="All" label="ALL">ALL</option>
									</select>
								</div>	
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="optionsC" name="optionsC" value="1" checked> Disconnection 1
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="optionsC" name="optionsC" value="2"> Disconnection 2
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="optionsC" name="optionsC" value="3"> Disconnection 3
									</label>
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="xl"> Excel
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="html" checked> HTML
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="pdf"> PDF
									</label>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									<button type="submit" name="submit_forwarding" id="submit_forwarding" class="mb-xs mt-xs mr-xs btn btn-success">Forwarding</button>
									<a class="mb-xs mt-xs mr-xs btn btn-success" href="<?php echo base_url().'index.php/Report/myRefferImportContainerSync'; ?>"  class="login_button" name="Sync" style="padding:6px 10px;" target="_blank">Sync</a>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">								
									<p><font size=3 color=green><?php echo $msg; ?></font></p>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
