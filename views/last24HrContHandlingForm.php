<script language="JavaScript">
    function validate()
    {
        if( document.myform.date.value == "" )
        {
            alert( "Please provide Date" );
            document.myform.date.focus() ;
            return false;
        }

        if( document.myform.yard.value == "" )
        {
            alert( "Please provide Yard!" );
            document.myform.yard.focus() ;
            return false;
        }
        return true;
    }


</script>


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/last24HrContHandling'; ?>"  id="myform" name="myform" target="_blank" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Date <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Yard <span class="required">*</span></span>
										<select name="yard" id="yard" class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="CCT">CCT</option>
                                            <option value="NCT">NCT</option>
                                            <option value="GCB">GCB</option>
                                        </select>
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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