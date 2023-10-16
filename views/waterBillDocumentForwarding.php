<script>

    function validate()
    {
        rotation = $("#rotation").val().trim();
        if(!rotation){
            alert("Rotation not given!");
            return false;
        }
        else
        {
            return true;
        }
        return false;
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
					<?php
						if(!is_null($this->session->flashdata('success'))){
							echo $this->session->flashdata('success');
						}

						if(!is_null($this->session->flashdata('error'))){
							echo $this->session->flashdata('error');
						}
					?>

					<div class="panel-body">
                        <?php $data = array('onsubmit' => "return validate()");?>
                        <?= form_open('',$data);?>
						
							<div class="form-group" >
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">	
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation no">
									</div>												

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Supply Date <span class="required">*</span></span>
										<input type="date" name="supply_date" id="supply_date" class="form-control" placeholder="Supply Date">
									</div>												
								</div>
								<label class="col-md-3 control-label">&nbsp;</label>											
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward</button>
									</div>													
								</div>
							</div>
                        <?= form_close();?>	
						<!-- </form> -->
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>