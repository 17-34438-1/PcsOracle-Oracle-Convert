<script type="text/javascript">
	function delete_jty_sarkar()
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
				
			<table>              
				<tr>
				 <form action="<?php echo site_url('Report/product_list');?>" method="POST">	
					<td align="center" >
					<label style="font-size:20px"><nobr><b>Product Type:</b></nobr><em>&nbsp;</em></label></td>
					<td>
						<select  id="product_type" name="product_type"  value="" style="font-size:15px" >
								 <option value="">--Select--</option>
								<?php
								for($i=0; $i<count($product_type_list); $i++){ ?>
									<option value="<?php echo $product_type_list[$i]['short_name']; ?>"><?php echo $product_type_list[$i]['short_name']; ?></option>
							   <?php } ?>
						</select>
					</td>  
						<td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td  align="left">
							<input type="submit" value="View" name="search" class="mb-xs mt-xs mr-xs btn btn-success">	   
						</td>
					</form>
				 </tr>
			</table>
		 <br/>
				<div style="height:600px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<tr>
							<td style="font-size:20px" align="center">Sl.</td>
							<td style="font-size:20px" align="center">Name</td>
							<td style="font-size:20px" align="center">Description</td>
							<td style="font-size:20px" align="center">Action</td>
							<td style="font-size:20px" align="center">Action</td>
						</tr>
						<?php
						$org_Type_id = $this->session->userdata('org_Type_id');
						for($i=0;$i<count($rslt_product_list);$i++)
						{
							$id=$rslt_product_list[$i]['id'];
							$short_name=$rslt_product_list[$i]['short_name'];
							$product_desc=$rslt_product_list[$i]['product_desc'];
							$created_by=$rslt_product_list[$i]['created_by'];
						?>
						<tr>
							<td class="gridLight" align="center"><?php echo $i+1; ?></td>
							<td class="gridLight" align="center"><?php echo $short_name; ?></td>
							<td class="gridLight" align="center"><?php echo $product_desc; ?></td>
							<!--td class="gridLight" align="center"><?php echo $created_by; ?></td-->
							<td class="gridLight" align="center">
								<form id="product_edit_form" name="product_edit_form" method="post" action="<?php echo site_url("Report/product_edit_form"); ?>">
									<input id="product_id" name="product_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="edit_btn" name="edit_btn" type="submit" value="Edit" class="mb-xs mt-xs mr-xs btn btn-success" <?php if($org_Type_id == 84){ echo "disabled";}?> />
								</form>
							</td>
							<td class="gridLight" align="center">
								<form id="product_delete_form" name="product_delete_form" method="post" action="<?php echo site_url("Report/product_delete_form"); ?>" onsubmit="return(delete_product());">
									<input id="product_id" name="product_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="delete_btn" name="delete_btn" type="submit" value="Delete" class="mb-xs mt-xs mr-xs btn btn-danger" <?php if($org_Type_id == 84){ echo "disabled";}?> />
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