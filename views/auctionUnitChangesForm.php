<script>
	function chkDate()
		{
			var fdate = document.getElementById("from_date").value;
			var tdate = document.getElementById("to_date").value;
			if(fdate==tdate)
			{
				return true;
			}
			else if(fdate < tdate)
			{
				return true;
			}
			else if(fdate > tdate)
			{
				alert("Wrong combination of date !");
				return false;
			}
		}
		
		
	function getUnitInfo(rot)
	{
			//setTimeout(document.getElementById("contNo").focus(),0);	
	      //alert("Find : "+document.getElementById("contNo").value);
		 if(rot=="" )
		 {
			alert("Please provide Rotation No!");
			
		 }
		 else
		 {
			if (window.XMLHttpRequest) 
			{
			 // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
			// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeUnitInfo;
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getUnitInfo')?>?rot="+rot,false);
					
			xmlhttp.send();
		 }
		
	}
			
			
	function stateChangeUnitInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		
		    //alert(val);
			
			document.getElementById("prev_unit").value=val;
			
			//document.getElementById("contAtShed").focus();
			
		}
	}

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<!-- start: Table -->
	<div class="row">
		<div class="col-lg-12">	
			<section class="panel">
				
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST"
						action="<?php echo site_url('Auction/auctionUnitChanges') ?>" onsubmit="return chkDate();">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php  if(isset($msg)) {echo $msg;} ?>
								</div>
							</div>
							<div class="col-md-6 col-md-offset-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
									<input type="text" name="rotation" id="rotation" class="form-control" value="" autofocus onblur="getUnitInfo(this.value);" required>
								</div>		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" >Prev. Unit </span>
									<input type="text" name="prev_unit" id="prev_unit" class="form-control" readonly>
								</div>
							
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">New Unit <span class="required">*</span></span>
									<select class="form-control" name="unit" id="unit" required>
										<option value="">--Select--</option>
											<option value="U1">U1</option>
											<option value="U2">U2</option>
											<option value="U3">U3</option>
											<option value="U4">U4</option>
											<option value="U5">U5</option>
											<option value="U6">U6</option>
											<option value="U7">U7</option>
											<option value="U8">U8</option>
											<option value="U9">U9</option>
											<option value="U10">U10</option>
											<option value="U11">U11</option>
											<option value="U12">U12</option>
									</select>
								</div>								
							</div>						
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>

		</div>
	</div>	
	<!-- end: Table -->
</section>
</div>