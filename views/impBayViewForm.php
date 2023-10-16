<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/uploadExcel/impBayViewPerformed'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Vessel <span class="required">*</span></span>
									<select name="vvdGkey" id="txt_login" class="form-control">
                                        <option>----Select Vessel----</option>
										<?php 
											for($i=0; $i<count($vessel); $i++)
											{  ?> 
											<option value="<?php echo $vessel[$i]['VVD_GKEY']; ?>" ><?php echo $vessel[$i]['VSL']; ?></option>
										<?php } ?>
                                    </select>
								</div>										
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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