<script>
	function myconfirm()
	{
		if(confirm("Do you want done this File?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
  </script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
<section class="panel">
        <!--header class="panel-heading">
			<h2 class="panel-title" align="right">
				<a href="<?php echo site_url('POSController/LiftingEntryForm') ?>">
					<button style="margin-left: 35%" class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i>
					</button>
				</a>									
			</h2>								
		</header-->
		<div class="panel-body">
			<form class="form-horizontal form-bordered" method="POST" 
				action="<?php echo site_url('UploadExcel/coparn_rot_search') ?>">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php //echo $msg; ?>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-3">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width"><font color='red'><b>*</b></font> ROTATION NO :</span>
							<input type="text" id="rot_no" name="rot_no" tabindex="1" class="form-control" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
						</div>													
					</div>
				</div>	
			</form>
		</div>
	</section>

	<section class="panel">
		<div class="panel-body">
			<div class="invoice">
				
				<div class="table-responsive">
					<table class="table table-bordered table-responsive table-hover table-striped mb-none">
						<thead>
							<tr class="gridDark">
								<th align="center" class="text-center">Sl</th>
								<th align="center" class="text-center">Rotation</th>
								<th align="center" class="text-center">Coparn File</th>
								<th align="center" class="text-center">Copino File </th>
								<th align="center" class="text-center">Declaration</th>
								
							</tr>
						</thead>
						<tbody>
						<?php

						
						//$path= 'http://'.$_SERVER['SERVER_ADDR'].'/pcs/assets/edi/';
						
						$path= 'http://cpatos.gov.bd/assets/uploadfile/';
						
						
						
						
						for($i=0;$i<count($rslt_copern_list);$i++)
						{
						?>

							<tr class="gradeX">
								<td align="center"><?php echo $i+1; ?></td>
								<td align="center"><?php echo $rslt_copern_list[$i]['rotation']; ?></td>
								<td align="center"><a href="<?php echo $path.$rslt_copern_list[$i]['file_coparn'];?>" download target="_blank" /><?php echo $rslt_copern_list[$i]['file_coparn']; ?></td>
								<td align="center"><a href="<?php echo $path.$rslt_copern_list[$i]['file_copino'];?>" download target="_blank" /><?php echo $rslt_copern_list[$i]['file_copino']; ?></td>
								<?php if($rslt_copern_list[$i]['n4_operation_st']==1){?>
								<td align="center"><input class="btn btn-secondary  disabled" tabindex="-1" type="button" role="button" aria-disabled="true" style="width : 100px" value="Done"  onclick="return DoneAllready();"/> </a></td> 
								
								<?php }else {?>
									<td align="center"><a href="<?php echo site_url('UploadExcel/update_cop_status/'.$rslt_copern_list[$i]['id']); ?>" ><input class="login_button btn btn-primary" type="button" style="width : 100px" value="Done" #box  onclick="return myconfirm();"/> </a></td>
								<?php } ?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>

</section>