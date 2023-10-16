<script>

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>
    <section class="panel">
			<header class="panel-heading">
				
			</header>
			<div class="panel-body">
			        <div class="row">
						<div class="col-sm-12 text-center">
						<?php //echo $msg;?>
						</div>													
					</div>
										
					<!--div class="row">
						<div class="col-sm-12 text-center">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/searchOnVesselList'; ?>" name="myform" onsubmit="return vesselValidate()">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
										
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
												<input type="test" name="vesselSearch" id="vesselSearch" class="form-control" placeholder="Search Value">
											</div>
											
											
										</div>

									
																					
										<div class="row">
											<div class="col-sm-12 text-center">
											
												<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
											</div>													
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
											<?php echo $msg1;?>
											</div>													
										</div>
										
									</div>
								
								</form>
						</div>													
					</div-->
					<br/>	
				
				<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center" colspan="10"><?php echo "Vessel Info" ?> </th>
						</tr>
						<tr>
                            <th class="text-center">SL NO</th>							
                            <th class="text-center">Lot Date</th>							
                            <th class="text-center">Lot ID</th>							
                            <th class="text-center">Total Vessel</th>							
                            <th class="text-center">Forward At</th>							
                            <th class="text-center">Forward By</th>							
                            <th class="text-center">Vessel Type</th>							
                            <th class="text-center">Action</th>							
						</tr>
					</thead>
					<tbody>
						<?php  
							for($i=0;$i<count($rslt_marineVslLot_NE);$i++) 
							{ 
						?>
						<tr class="gradeX">
						    <td align="center"> <?php echo $i+1; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['lot_dt']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['lot_id']; ?> </td>
                            <td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['tot_vsl']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['forward_at']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['forward_by']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['vsl_lot_type']; ?> </td>							
							<td align="center">
								<form action="<?php echo site_url("Vessel/marineVslLot_NotEntering_List")?>" method="POST" target="_blank">
									<input type="hidden" name="lotVslListId" value="<?php  echo $rslt_marineVslLot_NE[$i]['id']; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit" name="btnViewLotVsl_NE" value="View Vessel"/>
								</form>
							</td>
						</tr>
						<?php
							} 
						?>
						<!--tr class="gradeX">
						    <td align="center"> <?php echo $i+1; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['lot_dt']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['lot_id']; ?> </td>
                            <td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['tot_vsl']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['forward_at']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['forward_by']; ?> </td>
							<td align="center"> <?php echo $rslt_marineVslLot_NE[$i]['vsl_lot_type']; ?> </td>							
							<td align="center">
								<form action="<?php echo site_url("Vessel/marineVslLot_NotEntering_List")?>" method="POST">
									<input type="hidden" name="lotVslListId" value="<?php  echo $rslt_marineVslLot_NE[$i]['id']; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit" name="btnViewLotVsl_NE" value="View Vessel"/>
								</form>
							</td>
						</tr-->
					</tbody>
				</table>
				<?php // } ?>
			</div>
		</section>
<!-- end: page -->
</section>    