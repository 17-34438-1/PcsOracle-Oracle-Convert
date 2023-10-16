<!--script>

    function validate()
    {
        rotation = $("#rotation").val().trim();
        supply_date = $("#supply_date").val().trim();
        supply_qty = $("#supply_qty").val().trim();
        if(!rotation)
		{
            alert("Rotation not given!");
            return false;
        }  
		else if(!supply_date)
		{
            alert("Supply date not given!");
            return false;
        }
		else if(!supply_qty)
		{
            alert("Supply quantity not given!");
            return false;
        }
        else
        {
           if(confirm("Please, once again carefully check. These information will be saved in N4 also."))
			{
				return true;
			}
			else			
			{
				return false;
			}
			return false
		}
        return false;
    }
	
</script-->

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
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
                       
                        <form class="form-horizontal form-bordered" method="POST" name="fileUpload" id="fileUpload" action="<?php echo site_url('Vessel/editWaterDemand/'.$id) ?>" enctype="multipart/form-data">
						
							<div class="form-group" >
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">	
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation no" value="<?php if($frmType2=='edit2') echo $rotation_no;else echo ''; ?>" disabled>
									</div>												

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Supply Date <span class="required">*</span></span>
										<input type="date" name="supply_date" id="supply_date" value="<?php if($frmType2=='edit2') echo $supply_date;else echo ''; ?>" class="form-control" placeholder="Supply Date" >
									</div>	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Supply Quantity(M.T) <span class="required">*</span></span>
										<input type="number" name="supply_qty" id="supply_qty" value="<?php if($frmType2=='edit2') echo $demand_qty;else echo ''; ?>" class="form-control" placeholder="Supply Quantity (M.TONNES)">
									</div>												
								</div>
								<label class="col-md-3 control-label">&nbsp;</label>											
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">save</button>
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