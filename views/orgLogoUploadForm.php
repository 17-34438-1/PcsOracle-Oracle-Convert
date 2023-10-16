<script type="text/javascript">
	function logoValidation()
	{
		if(document.getElementById('mloCode').value=="")
		{
			alert("Please select mlo.");
			return false;
		}
		else if(document.getElementById('mloLogo').value=="")
		{
			alert("Please upload a logo.");
			return false;
		}
		else
		{
			if(confirm("Do you really want to submit the form?"))
			{
				return true;				
			}
			else
			{
				return false;
			}
		}
	}
	
	function chkConfirm()
	{
		if(confirm("Do you want to delete ?") == true)
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
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" name="orgLogoUploadForm" id="orgLogoUploadForm" action="<?php echo site_url("Report/orgLogoUploadAction"); ?>" method="POST" onsubmit="return logoValidation();" enctype="multipart/form-data">
						
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg;?>
								</div>
																								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MLO Code <span class="required">*</span></span>
									<select name="mloCode" id="mloCode" class="form-control">
										<option value="">--Select--</option>
										<?php
										for($i=0;$i<count($rslt_mloCodeList);$i++)
										{
										?>
										<option value="<?php echo $rslt_mloCodeList[$i]['mlocode']; ?>"><?php echo $rslt_mloCodeList[$i]['mlocode']; ?></option>
										<?php
										}
										?>									
									</select>
								</div>
																								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">MLO Logo <span class="required">*</span></span>
									<input class="form-control login_input_text" type="file" name="mloLogo" id="mloLogo" />
								</div>
								
								<div class="col-sm-12 text-center">								
									<button type="submit" name="btnUploadLogo" id="btnUploadLogo" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>
								</div>									
								
							</div>																		
						</div>
					</form>
					
					
				</div>
				
			</section>
			<table class="table table-bordered mb-none" id="datatable-default">
				<thead>
					<tr>
						<th class="text-center">Sl</th>
						<th class="text-center">MLO Code</th>
						<th class="text-center">MLO Logo</th>																
						<th class="text-center">Action</th>				
					</tr>
				</thead>
				<tbody>
					<?php
					for($i=0;$i<count($rslt_logoList);$i++)
					{
					?>
						<tr>
							<td align="center"><?php echo $i+1; ?></td>
							<td align="center"><?php echo $rslt_logoList[$i]['mlo_code']; ?></td>
							<td align="center"><img align="middle" width="80px" height="40px" src="<?php echo ASSETS_PATH?>mloLogo/<?php echo $rslt_logoList[$i]['logo_path']; ?>"></td>							
							<td align="center">
								<form action="<?php echo site_url("Report/orgLogoDeletedForm");?>" method="post" 
									onsubmit="return chkConfirm();">
									<input type="hidden" name="logoId" id="logoId" value="<?php echo $rslt_logoList[$i]['id'];?>">
									<input type="submit" value="Delete" class="btn btn-sm btn-danger" style="height:2%;">
								
								</form>
							</td>	
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>
