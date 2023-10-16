<script>
	function chkBlank()
	{
		if(document.getElementById('imp_rot').value=="")
		{
			alert("Fill the Rotation No");
			return false;
		}
		else if(document.getElementById('bl_no').value=="")
		{
			alert("Fill the BL No");
			return false;
		}
		else
		{
			return true;
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
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/releaseOrderView') ?>" onsubmit="return chkBlank()" target="_blank">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<b><?php echo $msg; ?></b>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Reg No :</span>
											<input type="text" name="imp_rot" id="imp_rot" class="form-control" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BL No :</span>
											<input type="text" name="bl_no" id="bl_no" class="form-control" onblur="chkDataForRO()" required>
										</div>
									</div>									
									<div id="btnSubmitRO" class="row">
										<div class="col-sm-12 text-center">											
											<button type="submit" name="btnSave" id="btnSave" class="mb-xs mt-xs mr-xs btn btn-primary">View RO</button>
										</div>													
									</div>
									<!--div id="resMsg" class="row" style="display:none">
										<div class="col-sm-12 text-center">											
											<span id="msgText"></span>
										</div>													
									</div-->
								</div>	
							</form>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>