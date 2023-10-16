<script>
    function deleteOuterVessel(){
	if (confirm("Do you want to detete this entry?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
}
  function vesselValidate(){
	if(document.getElementById("vesselSearch").value==""){
        alert("Search Value is Empty");
		return false;
    }
	else{
		return true;
	}
}

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
					<div class="col-sm-12 text-right">
					<form action="<?php echo site_url("Vessel/backToForm")?>" method="POST">
						<button type="submit" id="submit" name="backtoForm" class="mb-xs mt-xs mr-xs btn btn-success login_button">Back To Form</button>
					</form>   
					</div>													
				</div-->
				<div class="row">
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
				</div>
				<br/>	
		
			<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
				<thead>
					<tr>
						<th class="text-center" colspan="10"><?php echo "Vessel Info" ?> </th>
					</tr>
					<tr>
						<th class="text-center">SL NO</th>
						<th class="text-center">Vessel Name</th>
						<th class="text-center">Radio Call Sign</th>
						<th class="text-center">GRT</th>
						<th class="text-center">NRT</th>
						<th class="text-center">Flag</th>
						<th class="text-center">Vessel Type</th>
						<th class="text-center">Agent Name</th>	
						<th class="text-center">Action</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php  
						
						for($i=0;$i<count($vslInfoData);$i++)
						{	
					?>
					<tr class="gradeX">
						<td align="center"> <?php echo $i+1; ?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['vsl_name'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['radio_call_sign'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['grt'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['nrt'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['flag'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['vsl_type'];?> </td>
						<td align="center"> <?php echo $vslInfoData[$i]['agent_name'];?> </td>
						<td align="center">
							<form action="<?php echo site_url("Vessel/editVesselInfo")?>" method="POST">
									<input type="hidden" name="editVessel" value="<?php  echo $vslInfoData[$i]['id']; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit"  name="edit" value="EDIT"/>
							</form>
						</td>  
						<td align="center">
							<?php
								if($vslInfoData[$i]['forward_done'] == 1){
							?>
								<input class="btn btn-xs btn-danger" name="delete" type="submit" value="DELETE" disabled/>
							<?php
								}else{
							?>
								<form action="<?php echo site_url("Vessel/deleteVesselInfo")?>" method="POST" onsubmit="return deleteOuterVessel()">
									<input type="hidden" name="deleteId" value="<?php  echo $vslInfoData[$i]['id']; ?>"/>
									<input class="btn btn-xs btn-danger" name="delete" type="submit" value="DELETE"/>
								</form>
							<?php
								}
							?>
						</td>  
						
					</tr>
					<?php  } ?>
				
				</tbody>
			</table>
			<?php // } ?>
		</div>
	</section>
<!-- end: page -->
</section>    