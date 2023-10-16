<script type="text/javascript">
	function delete_product()
	{
		if (confirm("Do you want to detete this entry?") == true)
		{
			return true ;
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
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
									
			<div class="panel-body" align="center">
			<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				
	
				<div style="height:600px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<tr>
							<td style="font-size:20px" align="center">Sl.</td>
							<td style="font-size:20px" align="center">Company Name</td>
							<td style="font-size:20px" align="center">Created By</td>
							<td style="font-size:20px" align="center">Edit</td>
							<td style="font-size:20px" align="center">Delete</td>												
						</tr>
						<?php
						$org_Type_id = $this->session->userdata('org_Type_id');
						for($i=0;$i<count($rslt_product_user_list);$i++)
						{
							$id=$rslt_product_user_list[$i]['id'];
							$company_name=$rslt_product_user_list[$i]['company_name'];
							$created_by=$rslt_product_user_list[$i]['created_by'];
						?>
						<tr>
							<td class="gridLight" align="center"><?php echo $i+1; ?></td>
							<td class="gridLight" align="center"><?php echo $company_name; ?></td>
							<td class="gridLight" align="center"><?php echo $created_by; ?></td>
							<td class="gridLight" align="center">
								<form id="user_edit_form" name="user_edit_form" method="post" action="<?php echo site_url("Report/user_edit_form"); ?>">
									<input id="user_id" name="user_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="edit_btn" name="edit_btn" type="submit" value="Edit" class="mb-xs mt-xs mr-xs btn btn-success" <?php if($org_Type_id == 84){ echo "disabled";}?> />
								</form>
							</td>
							<td class="gridLight" align="center">
								<form id="user_delete_form" name="user_delete_form" method="post" action="<?php echo site_url("Report/user_delete_form"); ?>" onsubmit="return(delete_product());">
									<input id="user_id" name="user_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="delete_btn" name="delete_btn" type="submit" value="Delete" class="mb-xs mt-xs mr-xs btn btn-danger" disabled <?php //if($org_Type_id == 84){ echo "disabled";}?> />
								</form>
							</td>
						</tr>
						<?php
						}
						?>
				</table>
			</div> <hr><!-- .widget-content -->
		</div> <!-- /widget -->	
				</div>
			</section>
	
		</div>
	</div>	
<!-- end: page -->
</section>
</div>