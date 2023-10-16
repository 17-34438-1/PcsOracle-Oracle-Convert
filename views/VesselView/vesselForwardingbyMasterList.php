<script>
	function chkConfirm()
	{
		if (confirm("Do you want to delete ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}
	}
</script>
<?php $Control_Panel = $this->session->userdata('Control_Panel'); ?>
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
							<table class="table table-bordered mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">#Sl</th>
										<th class="text-center">File Date</th>
										<th class="text-center">File No</th>
										<th class="text-center">File Sub</th>
										<th class="text-center">Piloting Num</th>
										<th class="text-center">Letter</th>	
									</tr>
								</thead>
								<tbody>
									<?php 
										for($i=0;$i<count($letterList);$i++){
									?>
									<tr>
										<td align="center" style="height:5%;"><?php echo $i+1;?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_dt'];?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_no'];?></td>
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['file_sub'];?></td>										
										<td align="center" style="height:5%;"><?php echo $letterList[$i]['no_vsl'];?></td>
										<td align="center" style="height:5%;">
											<form action="<?php echo site_url("Vessel/vesselForwardingLetter");?>" method="post" target="_blank">
												<input type="hidden" name="letter_id" id="letter_id" value="<?php echo $letterList[$i]['id'];?>">
												<input type="hidden" name="file_dt" id="file_dt" value="<?php echo $letterList[$i]['file_dt'];?>">
												<input type="hidden" name="file_sub" id="file_sub" value="<?php echo $letterList[$i]['file_sub'];?>">
												<input type="hidden" name="file_no" id="file_no" value="<?php echo $letterList[$i]['file_no'];?>">
												<input type="hidden" name="no_vsl" id="no_vsl" value="<?php echo $letterList[$i]['no_vsl'];?>">
												<input type="submit" value="View Letter" class="btn btn-sm btn-primary" style="height:2%;">
											</form>
										</td>
										
									</tr>
									<?php } ?>
		
								</tbody>
							</table>
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: Table -->
	</section>
	</div>