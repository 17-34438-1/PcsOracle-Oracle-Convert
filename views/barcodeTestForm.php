<script>
	function enableCont()
	{
		if(document.myform.printType.value=="single")
		{
			//alert("gg");
			 document.getElementById("container_no2").disabled=true;
			 document.myform.container_no2.value="";
		}
		else if(document.myform.printType.value=="multiple")
		{
			//alert("ee");
			document.getElementById("container_no2").disabled=false;
		}
	}

	function validate()
    {
		  //alert("OK");
		 if( document.myform.contCat.value == "" )
         {
            alert( "Please Select Category!" );
            document.myform.contCat.focus() ;
            return false;
         }
		 else if( document.myform.printType.value == "" )
         {
            alert( "Please Select Print Type!" );
            document.myform.printType.focus() ;
            return false;
         }
		else if( document.myform.container_no.value == "" )
         {
            alert( "Please provide Container No!" );
            document.myform.container_no.focus() ;
            return false;
         }
		 else if( document.myform.truck_no.value == "" )
         {
            alert( "Please provide Truck Id!" );
            document.myform.truck_no.focus() ;
            return false;
         }
		 else if(document.myform.printType.value=="multiple")
		{
			//alert("ee");
			if(document.myform.container_no2.value == "")
			{
				alert( "Please provide Container 2!" );
				document.myform.container_no2.focus() ;
				return false;
			}
			else
			{
				return( true );
			}
			
		}
		 else{
			 return( true );
		 }
	}

 </script>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" name="myform" id="myform"
								action="<?php echo site_url('report/generateBarcodePage') ?>" onsubmit="return(validate());">
							
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Category :</span>
											<select name="contCat" id="contCat" class="form-control">
												<option value="">--SELECT--</option>
												<option value="import">IMPORT</option>
												<option value="export">EXPORT</option>
												<option value="storage">STORAGE</option>
												<option value="exportEmpty">EXPORT-EMPTY</option>
											</select> 
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Print Type :</span>
											<select name="printType" id="printType" class="form-control" onchange="enableCont()">
												<option value="single">SINGLE</option>
												<option value="multiple">DOUBLE</option>
											</select> 
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container No:</span>
											<input type="text" name="container_no" id="container_no" class="form-control">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container No 2:</span>
											<input type="text" name="container_no2" id="container_no2" class="form-control" disabled>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Id:</span>
											<input type="text" name="truck_no" id="truck_no" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										<!--input type="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success"/-->
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