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
    function agentValidate(){
	if(document.getElementById("agentSearch").value==""){
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
    <!-- start: page -->
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
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/searchOnAgentList'; ?>" name="myform" onsubmit="return agentValidate()">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
										
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Agent Name <span class="required">*</span></span>
												<input type="text" name="agentSearch" id="agentSearch" class="form-control" placeholder="Search Value" >
											</div>
											
											
											
										</div>

									
																					
										<div class="row">
											<div class="col-sm-12 text-center">
											
												<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
											</div>													
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
											<?php echo $msg;?>
											</div>													
										</div>
										
									</div>
								
								</form>
						</div>													
					</div>
					<br/>	
			
				<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default" style="overflow-y:auto;">
					<thead>
						<tr>
							<th class="text-center" colspan="10"><?php echo "Agent Info" ?> </th>
						</tr>
						<tr>
                            <th class="text-center">SL NO</th>
							<th class="text-center">Agent Code</th>
							<th class="text-center">Agent Name</th>
							<th class="text-center">Alias Id</th>
							<th class="text-center">Contact Name</th>
							<th class="text-center">Contact Address</th>
							<!--th class="text-center">Contact City</th-->
							<th class="text-center">Contact Email</th>	
							<!--th class="text-center">Contact Country</th-->	
                            <th class="text-center">Phone Number</th>
							<th class="text-center">Action</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php  for($i=0;$i<count($agentInfoData);$i++) { ?>
						<tr class="gradeX">
						    <td align="center"> <?php echo $i+1; ?> </td>
							<td align="center"> <?php echo $agentInfoData[$i]['agent_code']?> </td>
							<td align="center"> <?php echo $agentInfoData[$i]['agent_name']?> </td>
							<td align="center"> <?php echo $agentInfoData[$i]['alias_id']?> </td>
                            <td align="center"> <?php echo $agentInfoData[$i]['contact_name']?> </td>
							<td align="center"> <?php echo $agentInfoData[$i]['contact_address']?> </td>
							<!--td align="center"> <?php echo $agentInfoData[$i]['contact_city']?> </td-->
							<td align="center"> <?php echo $agentInfoData[$i]['contact_email']?> </td>
							<!--td align="center"> <?php echo $agentInfoData[$i]['contact_country']?> </td-->
							<td align="center"> <?php echo $agentInfoData[$i]['contact_phone']?> </td>
							<td align="center">
							  <form action="<?php echo site_url("Vessel/editAgentInfo")?>" method="POST">
							           <input type="hidden" name="editAgent" value="<?php  echo $agentInfoData[$i]['id']; ?>"/>
							           <input class="btn btn-xs btn-primary" type="submit"  name="edit" value="EDIT"/>
							  </form>
							</td>  
							<td align="center">
							  <form action="<?php echo site_url("Vessel/deleteAgentInfo")?>" method="POST" onsubmit="return deleteOuterVessel()">
							           <input type="hidden" name="deleteId" value="<?php  echo $agentInfoData[$i]['id']; ?>"/>
							          <input class="btn btn-xs btn-danger" name="delete" type="submit" value="DELETE"/>
							  </form>
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