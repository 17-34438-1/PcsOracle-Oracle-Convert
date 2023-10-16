<script type="text/javascript">
	function validate()
	{
		if( document.myForm.container.value == "" )
		{
			alert( "Please provide Container No!" );
			document.myForm.container.focus() ;
			return false;
		}
		return true ;
	}
</script>
 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title; ?></h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>

	<!-- start: page -->
		<section class="panel">
			<header class="panel-heading">
				<!--h2 class="panel-title" align="right">
					<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
						<button style="margin-left: 35%" class="btn btn-primary btn-sm">
							<i class="fa fa-list"></i> GO TO INDENT LIST
						</button>
					</a>									
				</h2-->
			</header>
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" onsubmit="return(validate());"
					action="<?php echo site_url('report/searchIGMByContainerPerform') ?>" target="_self">
					<div class="form-group">
						
						<div class="col-md-offset-3 col-md-6">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Container</span>
								<input type="text" name="container" id="container" class="form-control">
							</div>
						</div>
						
						<div class="col-sm-12 text-center">
							<button class="mb-xs mt-xs mr-xs btn btn-success" type="submit">Search</button>
						</div>													
					</div>
					<div class="form-group">
					</div>
				</form>
				<?php if($flag==1) { ?>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center">Sl</th>
							<th class="text-center">Import Rotation No</th>
							<th class="text-center">Container No</th>
							<th class="text-center">Size</th>	
							<th class="text-center">Height</th>	
							<th class="text-center">MLO Code</th>
						</tr>
					</thead>
					<tbody>
						<?php
							//  loc_id, location_name, owner_id, full_name, type_id, short_name, prod_user_id, 
							//   company_name, prod_serial, prod_ip, prod_deck_id, prod_rcv_date, prod_rcv_by
							for($i=0;$i<count($rslt_container_search);$i++) { 				
						?>
						<tr class="gradeX">
							<td align="center"> <?php echo $i+1;?> </td>
							<td align="center"> <?php echo $rslt_container_search[$i]['Import_Rotation_No']?> </td>
							<td align="center"> <?php echo $rslt_container_search[$i]['cont_number']?> </td>
							<td align="center"> <?php echo $rslt_container_search[$i]['cont_size']?> </td>
							<td align="center"> <?php echo $rslt_container_search[$i]['cont_height']?> </td>
							<td align="center"> <?php echo $rslt_container_search[$i]['mlocode']?> </td>
						</tr>
						<?php } ?>
						<!--tr class="gradeX">
							<td align="center" colspan="17">
								<?php echo $links?>
							</td>
						</tr-->
					</tbody>
				</table>
				<?php } ?>
			</div>
		</section>
	<!-- end: page -->
</section>
</div>