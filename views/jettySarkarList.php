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
									<header class="panel-heading">
										<h2 class="panel-title" align="right">
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header>
									<div class="panel-body" align="center">
			<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				<div class="widget-header">
					<h3 align="center">Jetty Sarkar List</h3>
				</div> <!-- .widget-header -->
				<div style="height:600px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<thead>
							<tr class="gridDark">
								<th>SL</th>
								<th>JS NAME</th>
								<th>LICENSE NO</th>
								<!--th>CONTACT NO</th>
								<th>ADRRESS</th-->
								<th>PICTURE</th>
								<th>SIGNATURE</th>
								<th>LICENSE</th>
								<th>GATE PASS</th>
								<th>ACTION</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
							  <?php							   
								for($i=0;$i<count($js);$i++) { 					
								?>
							<tr class="gridLight">
							      
								  <td align="center" > <?php echo $i+1;?>  </td>						 
								  <td align="center"><?php echo $js[$i]['js_name']?></td>
								  <td align="center"><?php echo $js[$i]['js_lic_no']?></td>
								  <!--td align="center"><?php echo $js[$i]['cell_no']?> </td>
								  <td align="center"><?php echo $js[$i]['adress']?></td-->	
								<td align="center"><?php   
									$picture=str_replace('-','',$js[$i]['js_img_path']);
									$signature=str_replace('-','',$js[$i]['signature_path']);
									$license_copy=str_replace('-','',$js[$i]['lic_copy_path']);
									$gate_pass=str_replace('-','',$js[$i]['gate_pass_path']);
									
									?>
								   <img align="middle"  width="100px" height="40px" src="<?php echo JTY_SIG_PATH.''.$picture; ?>">
								</td>	
								<td align="center"><img align="middle"  width="100px" height="40px" src="<?php echo JTY_SIG_PATH.''.$signature; ?>"></td>	
								<td align="center"> <img align="middle"  width="100px" height="40px" src="<?php echo JTY_SIG_PATH.''.$license_copy; ?>"></td>	
								<td align="center"><img align="middle"  width="100px" height="40px" src="<?php echo JTY_SIG_PATH.''.$gate_pass; ?>"></td>	
								<td align="center">
								   <form action="<?php echo site_url('Report/jettySarkarEntryFormEdit');?>" method="POST">
										<input type="hidden" name="jsId" value="<?php echo $js[$i]['id'];?>">		
										<input type="hidden" name="editFlag" value="1">		
										<!--input type="hidden" name="updateFlag" value="<?php echo $updateFlag;?>"-->	
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>									
										
										<!--input type="submit" value="Edit" name="start" class="login_button" style="width:100%;"-->							
									</form>
								</td> 
							   <td align="center">
							   <form action="<?php echo site_url('Report/jettySarkarEntryDelete');?>" method="POST" onsubmit="return(delete_jty_sarkar());">
									<input type="hidden" name="jsId" value="<?php echo $js[$i]['id'];?>">	
									<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Delete</button>									
									<!--input type="submit" value="Delete" name="Delete" class="login_button" style="width:100%;"-->							
								</form>
							  </td> 
								  
								 </tr>
								 <?php
								}
							   ?>
						</tbody>
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