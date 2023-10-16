 <script type="text/javascript">
   
	function validate()
	{
		if( document.myform.from_date.value == "" )
		{
			alert( "Please provide From Date" );
			document.myform.from_date.focus() ;
			return false;
		}
		else if( document.myform.to_date.value == "" )
		{
			alert( "Please provide To date" );
			document.myform.to_date.focus() ;
			return false;
		}
		else if( document.myform.shed.value == "" )
		{
			alert( "Please select a shed." );
			document.myform.shed.focus() ;
			return false;
		}
		return true ;
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">	

				<section class="panel">
						<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" target= "_blank" action="<?php echo site_url("LCL/shedWiseDeliveryReportView"); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="col-md-12 text-center">
								<div class="col-md-4">	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
										<input type="date" name="from_date" id="from_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
									</div>
								</div>
								<div class="col-md-4">	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
										<input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
									</div>
								</div>
							</div>
							<div class="col-md-12 text-center">

								<div class="col-md-4">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Shed <span class="required">*</span></span>
										<select name="shed" id="shed" class="form-control">
											<option value="">-- Select --</option>
											<?php
												for($i=0;$i<count($yardList);$i++){
											?>
												<option value="<?php echo $yardList[$i]['shed_yard']?>"><?php echo $yardList[$i]['shed_yard']?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-3 text-center">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>
							</div>
						</form>
						</div>
					</section>

					
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 

        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
    </div>
	
  </div>
</section>