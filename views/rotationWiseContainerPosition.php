<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" name= "myForm" method="POST" action="<?php echo site_url('report/rotationWiseContainerPositionView'); ?>" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" id="rotNo" name="rotNo" value="" class="form-control login_input_text" />
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
						</div>
					</form>
					<br>				
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<tr align="center">
							<td><b>SL</b></td>
							<td><b>CONTAINER</b></td>
							<td><b>ACTION</b></td>
							<td><b>ROTATION</b></td>
							<td><b>SIZE</b></td>
							<td><b>HEIGHT</b></td>
							<td><b>STATUS</b></td>
							<td><b>TRAILER NO</b></td>
							<td><b>ACTION</b></td>
						</tr>
						<?php 
						for($i=0;$i<count($rtnSearchList);$i++) 
						{
						?>
						<tr align="center">
							<td><?php echo $i+1;?></td>
							<td><?php echo $rtnSearchList[$i]['cont_number'] ?></td>
							<td><?php echo $rtnSearchList[$i]['position'] ?></td>
							<td><?php echo $rtnSearchList[$i]['rotation'] ?></td>
							<td><?php echo $rtnSearchList[$i]['cont_size'] ?></td>
							<td><?php echo $rtnSearchList[$i]['cont_height'] ?></td>
							<td><?php echo $rtnSearchList[$i]['cont_status'] ?></td>
							<td><?php echo $rtnSearchList[$i]['trailer_no'] ?></td>
							<td style="padding:5px;">
								<form action="<?php echo site_url('report/containerPositionSolveUpdate') ?>" method="post" onsubmit="return confirm('Are you sure ?');">
									<input type="hidden" name="cont_move_id" value="<?php echo $rtnSearchList[$i]['id']; ?>">
									<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">Solve</button>
								</form>
							</td>	
						</tr>
						<?php 
						}
						?>
					</table>
					<table align="center" width="85%" cellpadding='0' cellspacing='0'>
					   <tr align ="left"><td><b><u>Container No:</u></b></td></tr>
						<tr>
							<td><b><?php for($i=0;$i<count($rtnSearchList);$i++) { echo $rtnSearchList[$i]['cont_number'].", ";} ?></b></td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
