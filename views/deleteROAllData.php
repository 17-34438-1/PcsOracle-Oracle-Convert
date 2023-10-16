<script>
	function validate()
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
			if(confirm('Are you sure?') == true){
                return true;
            }
            else
            {
                return false;
            }
		}

        return false;
	}		
</script>
<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		</header>
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<?php
							if(!is_null($this->session->flashdata('success'))){
								echo $this->session->flashdata('success');
							}

							if(!is_null($this->session->flashdata('error'))){
								echo $this->session->flashdata('error');
							}
						?>

						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('ReleaseOrderController/deleteROAllData') ?>" onsubmit="return validate()">
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
											<button type="submit" name="deletero" id="btnSave" class="mb-xs mt-xs mr-xs btn btn-danger">Delete RO</button>
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