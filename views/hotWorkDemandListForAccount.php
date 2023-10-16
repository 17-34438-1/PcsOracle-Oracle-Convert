<script>
	function selectAllRot(state)
		{
			var numberOfChecked = 0;
			var totalRow = document.getElementById('total').value;
			if(state.checked == true)
			{
				//If "All" is checked;
				for(var p=0;p<totalRow;p++)
				{
					document.getElementById("idchk"+p).checked = true;
					numberOfChecked = numberOfChecked+1;
				}				
			}
			else
			{
				//If "All" is unchecked;
				for(var p=0;p<totalRow;p++)
				{				
					document.getElementById("idchk"+p).checked = false;		
				}
				numberOfChecked = 0;
			}

			if(numberOfChecked>0){
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
			}	
		}
    function selectCheck(state,sl)
		{
			var numberOfChecked = 0;
			if(document.getElementById('checkAll').checked==true)
			{
				numberOfChecked = $('input:checkbox:checked').length -1;
			}
			else
			{
				numberOfChecked = $('input:checkbox:checked').length;
			}

			if(numberOfChecked>0){
				document.getElementById('forward').disabled = false;
			}else{
				document.getElementById('forward').disabled = true;
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
				<!--header class="panel-heading">
					<h2 class="panel-title" align="right">
						<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
							<button style="margin-left: 35%" class="btn btn-primary btn-sm">
								<i class="fa fa-list"></i>
							</button>
						</a>
					</h2>								
				</header-->
				<div class="panel-body table-responsive">	
					<h6 class="panel-title" align="center">
						<b><?php echo $msg;?></b>
					</h6>
					<form action="<?php echo site_url('Vessel/hotWorkDemandListFowardByAccount')?>" method="POST">	
						<?php if($section=='acc') { ?>
							<div class="col-md-6 col-md-offset-3">	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Bill Operator <span class="required">*</span></span>
									<select class="form-control" name="billOp" id="billOp" required>
										<option value="">--Select--</option>
										<?php for($i=0; $i<count($billOpListRslt); $i++) { ?>
											<option value="<?php echo $billOpListRslt[$i]['login_id']; ?>">
												<?php echo $billOpListRslt[$i]['u_name']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
						<?php } ?>
						<table class="table table-responsive table-bordered table-hover table-striped mb-none">
							<?php
								$login_id = $this->session->userdata('login_id');
								$section = $this->session->userdata('section');
							?>
							<thead>
								<tr class="gridDark">
									<?php if($login_id == "dirsecurity" || $section == "acc"){ ?>
									<th class="text-center"><font size='2'>All</font>
										<input type="hidden" name="total" id="total" value="<?php echo count($result);?>"></input>
										<input type="checkbox" name="checkAll" id="checkAll" onclick="selectAllRot(this);"></input>
									</th>
									<?php } ?>
									<th class="text-center">Rotation</th>
									<th class="text-center">Vessel name</th>
									<th class="text-center">Service Date</th>
									<th class="text-center">Unit</th>
									<!--th class="text-center">Start date</th-->
								</tr>
							</thead>
							<tbody>
								<?php for($i=0;$i<count($result);$i++){ ?>
									<tr>
										<?php if($login_id == "dirsecurity" || $section == "acc"){ ?>
										<td align="center">
											<input type="checkbox" name="idchk[]" id="idchk<?php echo $i;?>" 
											value="<?php echo $result[$i]['id'];?>" onclick="selectCheck(this,<?php echo $i;?>);">
										</td>
										<?php } ?>
										<td align="center"><?php echo $result[$i]['rotation'];  ?></td>
										<td align="center"><?php echo $result[$i]['vessel_name'];  ?></td>
										<td align="center"><?php echo $result[$i]['service_date'];  ?></td>
										<td align="center"><?php echo $result[$i]['start_time'];  ?></td>
										<!--td align="center"><?php echo $result[$i]['start_date'];  ?></td-->
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="row" align="center">
							<?php if($login_id == "dirsecurity" || $section == "acc"){ ?>
							<button type="submit" name="apply" id="forward" class="mb-none mt-sm mr-xs btn btn-primary" 
								<?php if(count($result)<=0){ echo "disabled";} ?> disabled>
								Forward
						   </button>
						   <?php }  ?>
						</div>
					</form>
				</div>						
			</section>
		</div>
	</div>	
	<!-- end: page -->
</section>
</div>