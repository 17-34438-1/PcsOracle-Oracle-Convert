<script>
	function changeTextBox(val) 
	{ 
		document.getElementById("rot_no").value="";
		document.getElementById("bl_no").value="";
		document.getElementById("cont_no").value="";

		if(val=="bl") {		
			document.getElementById("rotationDiv").style.display="block";
			document.getElementById("blDiv").style.display="block";
			document.getElementById("contDiv").style.display="none";
			document.getElementById("submitBtnDiv").style.display="block";
		} else if(val=="container" )
		{		
			document.getElementById("rotationDiv").style.display="block";
			document.getElementById("blDiv").style.display="block";
			document.getElementById("contDiv").style.display="block";
			document.getElementById("submitBtnDiv").style.display="block";
		} else {
			
			document.getElementById("rotationDiv").style.display="none";
			document.getElementById("blDiv").style.display="none";
			document.getElementById("contDiv").style.display="none";
			document.getElementById("submitBtnDiv").style.display="none";			
		}		
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
							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">	
								<form  method="POST" action="<?php echo site_url("IgmViewController/amendment_form"); ?>"  >
								
									<div class="form-group">
										<label class="col-md-4 control-label">Change Type :</label>
										<div class="col-md-8">
											<select name="bl_selected" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
												<option value="">--Select--</option>
												
												<option value="bl">BL</option>
												<option value="container">Container</option>
												<?php foreach($select_container as $key=>$cl)  { ?> 
												<option value="<?php echo $key;?>"><?php echo $cl; ?></option>
												<?php }?> 
												
											</select>
										</div>
									</div>
									<div class="form-group" id="rotationDiv" style="display:none">
									   <label class="col-md-4 control-label">Rotation :</label>
										<div class="col-md-8">
										  <input type="text" id="rot_no" name="rot_no"  class="form-control" />
										</div>
									</div>
									<div class="form-group" id="blDiv"  style="display:none">
										<label class="col-md-4 control-label">BL No :</label>
										<div class="col-md-8">
										  <input type="text" id="bl_no" name="bl_no" class="form-control" onblur="fetchData();"/>
										</div>
									</div>									
									<div class="form-group" id="contDiv"  style="display:none" >
										<label class="col-md-4 control-label">Container No :</label>
										<div class="col-md-8">
										  <input type="text" id="cont_no" name="cont_no" class="form-control"/>
										</div>
									</div>											
									<div class="row" id="submitBtnDiv" style="display:none">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">
												Next
											</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>													
									</div>
								</form>
							</div>	
				
						
					</div>
			    </section>
			</div>
		</div>

	<!-- end: page -->
</section>
</div>


