<script>
	function myconfirm()
	{
		if(confirm("Do you want done this EDI?"))
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
				action="<?php echo site_url('UploadExcel/edi_declaration') ?>">
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
				<header class="clearfix">
					<div class="row">
						<div class="col-sm-12 text-center mt-md mb-md">
							<div class="ib">
								<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
								<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
							</div>
						</div>
					</div>
				</header>
				<div class="table-responsive">
					<table class="table table-bordered table-responsive table-hover table-striped mb-none">
						<thead>
							<tr class="gridDark">
								<th align="center">Sl</th>
								<th align="center">EDI File</th>
								<th align="center">Stow File</th>
								<th align="center">Status</th>
								<th align="center">Declaration</th>
							</tr>
						</thead>
						<tbody>
						<?php
						//$path= 'http://'.$_SERVER['SERVER_ADDR'].'/pcs/assets/edi/';
						$path= ASSETS_PATH.'edi/';
						//$path= 'http://cpatos.gov.bd/pcs/resources/edi/';
						$path= 'http://cpatos.gov.bd/resources/edi/';
						for($i=0;$i<count($rslt_edi_list);$i++)
						{
						?>
							<tr class="gradeX">
								<td align="center"><?php echo $i+1; ?></td>

								<td align="center"><a href="<?php echo $path.$rslt_edi_list[$i]['file_name_edi'];?>" download="<?php echo $rslt_edi_list[$i]['file_name_edi']; ?>" target="_blank" /><?php echo $rslt_edi_list[$i]['file_name_edi']; ?></td>

								<td align="center"><a href="<?php echo $path.$rslt_edi_list[$i]['file_name_stow'];?>" download target="_blank" /><?php echo $rslt_edi_list[$i]['file_name_stow']; ?></td>
								<td align="center"><a href="<?php echo site_url('UploadExcel/update_edi_status/'.$rslt_edi_list[$i]['id']); ?>" class="login_button" style="text-decoration: none;" onclick="return myconfirm();">Done EDI</a></td>
								<td align="center"><a href="<?php echo site_url('UploadExcel/show_edi_declaration/'.$rslt_edi_list[$i]['id']); ?>" class="login_button" style="text-decoration: none;" target="BLANK">View</a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>

</section>