<script>
	function validate()
	{			
		if( document.igm_detail_search_form.rot_igmDtl.value == "" )
		{
			alert( "Please provide Rotation!" );
			document.igm_detail_search_form.rot_igmDtl.focus() ;
			return false;
		}
		else if( document.igm_detail_search_form.bl_igmDtl.value == "" )
		{
			alert( "Please provide BL No.!" );
			document.igm_detail_search_form.bl_igmDtl.focus() ;
			return false;
		}
		else{
			return( true );
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
					<form class="form-horizontal form-bordered" name="igm_detail_search_form" id="igm_detail_search_form" method="post" action="<?php echo site_url("igmViewController/igm_detail_search");?>" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation :<span class="required">*</span></span>
									<input type="text" name="rot_igmDtl" id="rot_igmDtl" class="form-control login_input_text">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No :<span class="required">*</span></span>
									<input type="text" name="bl_igmDtl" id="bl_igmDtl" class="form-control login_input_text">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="btn_search" id="btn_search" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
								</div>													
							</div>
						</div>
					</form>
					
					<br>
					
					<?php
					if($search_flag==1)
					{
					?>
					<table class="table table-bordered mb-none">
						<tr class="primary" align="center">
							<td colspan="6"><b>IGM Detail</b></td>
						</tr>
						<tr class="primary">
							<th>Sl</th>
							<th>Rotation</th>
							<th>BL No</th>
							<th>Pack Number</th>
							<th>Pack Description</th>
							<th>Weight</th>
						</tr>
						<?php						
						for($i=0;$i<count($rslt_search_igmDtl);$i++)
						{
						?>
						<tr class="info">
							<td align="center"><?php echo $i+1; ?></td>
							<td align="center"><?php echo $rslt_search_igmDtl[$i]['Import_Rotation_No']; ?></td>
							<td align="center"><?php echo $rslt_search_igmDtl[$i]['BL_No']; ?></td>
							<td align="center"><?php echo $rslt_search_igmDtl[$i]['Pack_Number']; ?></td>
							<td align="center"><?php echo $rslt_search_igmDtl[$i]['Pack_Description']; ?></td>
							<td align="center"><?php echo $rslt_search_igmDtl[$i]['weight']; ?></td>
						</tr>
						<?php
						}
						?>
					</table>
					<br>
					<table class="table table-bordered mb-none">
						<tr class="primary" align="center">
							<td colspan="7">IGM Supplimentary Detail</td>
						</tr>
						<tr class="primary">
							<th>Sl</th>
							<th>BL No</th>
							<th>Description of Goods</th>
							<th>Exporter Name</th>
							<th>Notify Name</th>
							<th>Consignee Name</th>
							<th>Action</th>
						</tr>
						<?php						
						for($i=0;$i<count($rslt_search_igmSupDtl);$i++)
						{
						?>
						<tr class="info">
							<td align="center"><?php echo $i+1; ?></td>
							<td align="center"><?php echo $rslt_search_igmSupDtl[$i]['BL_No']; ?></td>
							<td align="center"><?php echo $rslt_search_igmSupDtl[$i]['Description_of_Goods']; ?></td>
							<td align="center"><?php echo $rslt_search_igmSupDtl[$i]['Exporter_name']; ?></td>
							<td align="center"><?php echo $rslt_search_igmSupDtl[$i]['Notify_name']; ?></td>
							<td align="center"><?php echo $rslt_search_igmSupDtl[$i]['Consignee_name']; ?></td>
							<td align="center">
								<form name="igmSupDtl_edit_form" id="igmSupDtl_edit_form" target="_blank" action="<?php echo site_url("igmViewController/igmSupDtl_edit_form"); ?>" method="post">
									<input type="hidden" name="flag" id="flag" value="edit" />
									<input type="hidden" name="igmSupDtl_id" id="igmSupDtl_id" value="<?php echo $rslt_search_igmSupDtl[$i]['igmSupDtl_id']; ?>" />
									<input type="submit" name="edit_btn" id="edit_btn" value="EDIT" class="login_button"/>
								</form>								
							</td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td colspan="7" align="center">
								<form name="add_new_form" id="add_new_form" target="_blank" action="<?php echo site_url("igmViewController/igmSupDtl_edit_form"); ?>" method="post">
									<input type="hidden" name="flag" id="flag" value="insert" />
									<input type="hidden" name="Import_Rotation_No" id="Import_Rotation_No" value="<?php echo $rslt_search_igmDtl[0]['Import_Rotation_No']; ?>" />
									<input type="hidden" name="master_bl_no" id="master_bl_no" value="<?php echo $rslt_search_igmDtl[0]['BL_No']; ?>" />
									<input type="submit" name="btn_add_new" id="btn_add_new" value="Add New" class="mb-xs mt-xs mr-xs btn btn-success" />
								</form>
							</td>
						</tr>
					</table>
					
					<?php
					}
					?>
				</div>																	
			</section>
		</div>
	</div>	
</section>
