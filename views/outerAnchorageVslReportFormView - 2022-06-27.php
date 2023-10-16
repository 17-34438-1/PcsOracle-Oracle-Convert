<script>
	

	function validate(){
		/* if(document.myform.searchType.value=="select"){
			alert("Select a Report Type");
			return false;
		}
		else */ if(document.myform.formDate.value==""){
			alert("Form Date is Empty");
			return false;
		}
		else if(document.myform.toDate.value==""){
			alert("To Date is Empty");
			return false;
		}
		else{
			return true;
		}
	}

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title; ?> </h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/outerAnchorageVslReport'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
                            <div class="input-group mb-md">
									<span class="input-group-addon span_width">Report Type <span class="required">*</span></span>
                                    <select name="searchType" id="searchType" class="form-control" onchange="enableDate(this.value);" required>
									<option value="all">ALL</option>
                                        <option value="arrival">Arrival Date</option>
                                        <option value="departure">Departure Date</option>
		                            </select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel Type<span class="required">*</span></span>								
									<select name="vslType" id="vslType" class="form-control">
										<option value="">--Select--</option>
										<?php
										for($i=0;$i<count($rslt_vslType);$i++)
										{
										?>
										<option value="<?php echo $rslt_vslType[$i]['vsl_type']; ?>" ><?php echo $rslt_vslType[$i]['vsl_type']; ?></option>
										<?php
										}
										?>
										
									</select>
									
								</div>
                                
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Form Date <span class="required">*</span></span>
                                    <input type="date" name="formDate" id="formDate" class="form-control" value="<?php date("Y-m-d"); ?>" >
                                </div>
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">To Date <span class="required">*</span></span>
                                    <input type="date" name="toDate" id="toDate" class="form-control" value="<?php date("Y-m-d"); ?>" >
                                </div>
                                <!--div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Departure Date <span class="required">*</span></span>
                                    <input type="date" name="departureDate" id="departureDate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled>
                                </div-->
                                
                            </div>
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-12" align="center">
								<label class="checkbox-inline">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl"> Excel
								</label>
								<label class="checkbox-inline">
									<input type="radio" id="fileOptions" name="fileOptions" value="pdf"> PDF
								</label>
								<label class="checkbox-inline">
									<input type="radio" id="fileOptions" name="fileOptions" value="html"> HTML
								</label>
							</div>
                            <br/><br/>                            
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
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

</section>