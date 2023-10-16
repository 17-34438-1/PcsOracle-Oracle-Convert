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
function searchList(searchType){
	if(searchType=="select"){
		formDate.disabled=true;
		toDate.disabled=true;
		searchValue.disabled=true;
		document.myform.formDate.value="";
		document.myform.toDate.value="";
		document.myform.searchValue.value="";

	}
	else if(searchType=="date_of_arrival" || searchType=="date_of_departure" || searchType=="entry_at"){
		formDate.disabled=false;
		toDate.disabled=false;
		searchValue.disabled=true;
		document.myform.searchValue.value="";
	}
	else{
		searchValue.disabled=false;
		formDate.disabled=true;
		toDate.disabled=true;
		document.myform.formDate.value="";
		document.myform.toDate.value="";
	}
}
function validate(){
	if(document.myform.searchType.value=="select"){
		alert("Select a Search Type");
			return false;
	}
	else {
		if(document.myform.searchType.value=="date_of_arrival" || document.myform.searchType.value=="date_of_departure" || document.myform.searchType.value=="entry_at"){
			if(document.myform.formDate.value== ""|| document.myform.toDate.value== "" ){
				if(document.myform.formDate.value==""){
					alert("From Date is Empty");
			        return false;

				}
				else{
					alert("To Date is Empty");
			        return false;

				}

			}
			else{
				return true;
			}
		}
		else{
			if(document.myform.searchValue.value==""){
				alert("Search Value is Empty");
			        return false;
			}
			else{
				return true;
			}
			

		}
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

					
					<div class="row">
						<div class="col-sm-12 text-right">
						<form action="<?php echo site_url("Vessel/backToForm")?>" method="POST">
						   <button type="submit" id="submit" name="backtoForm" class="mb-xs mt-xs mr-xs btn btn-success login_button">Back To Form</button>
						</form>   
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Vessel/searchOnAchorageVesselList'; ?>"id="myform" name="myform" onsubmit="return validate()">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
										 <div class="input-group mb-md">
												<span class="input-group-addon span_width">Search Type <span class="required">*</span></span>
												<select name="searchType" id="searchType" class="form-control" onchange="searchList(this.value);" required>
												    <option value="select">Select</option>
													<option value="imp_rot">Rotation</option>
													<option value="vsl_name">Vessel Name</option>
													<option value="date_of_arrival">Arrival Date</option>
													<option value="date_of_departure">Departure Date</option>
													<option value="beaching_agent">Beaching Agent</option>
													<option value="flag">Flag</option>
													<option value="entry_at">Entry Date</option>
													<option value="grt">Grt</option>
													<option value="nrt">Nrt</option>
												</select>

										   </div>
											
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Form Date <span class="required">*</span></span>
												<input type="date" name="formDate" id="formDate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled >
											</div>
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
												<input type="date" name="toDate" id="toDate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled >
											</div>
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
												<input type="test" name="searchValue" id="searchValue" class="form-control" placeholder="Search Value" disabled >
											</div>
											
											
										</div>

									
																					
										<div class="row">
											<div class="col-sm-12 text-center">
												<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
												<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
											</div>													
										</div>
										
									</div>
								
								</form>
						</div>													
					</div>
					<br/>	
				<?php // if($tableFlag==1){ ?>
				<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center" colspan="12"><?php // echo $tableTitle; ?> </th>
						</tr>
						<tr>
                            <th class="text-center">SL NO</th>
							<th class="text-center">ROTATION</th>
							<th class="text-center">NAME OF VESSEL</th>
							<th class="text-center">DATE OF ARRIVAL</th>
							<th class="text-center">ARRIVAL TIME</th>
							<th class="text-center">DATE OF DEPARTURE</th>	
							<th class="text-center">DEPARTURE TIME</th>	
							<th class="text-center">BEACHING AGENT</th>
							<th class="text-center">FLAG</th>
							<th class="text-center">GRT</th>
                            <th class="text-center">NRT</th>
                            <th class="text-center">REMARKS</th>
							<th class="text-center">Action</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php  for($i=0;$i<count($vslInfoData);$i++) { ?>
						<tr class="gradeX">
						    <td align="center"> <?php echo $i+1; ?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['imp_rot']?> </td>
                            <td align="center"> <?php echo $vslInfoData[$i]['vsl_name']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['date_of_arrival']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['time_of_arrival']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['date_of_departure']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['time_of_departure']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['beaching_agent']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['flag']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['grt']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['nrt']?> </td>
							<td align="center"> <?php echo $vslInfoData[$i]['remarks']?> </td>
							<td align="center">
							  <form action="<?php echo site_url("Vessel/editOuterAnchorageVsl")?>" method="POST">
							           <input type="hidden" name="editId" value="<?php  echo $vslInfoData[$i]['id']; ?>"/>
							           <input class="btn btn-xs btn-primary" type="submit"  name="edit" value="EDIT"/>
							  </form>
							</td>  
							<td align="center">
							  <form action="<?php echo site_url("Vessel/deleteOuterAnchorageVsl")?>" method="POST" onsubmit="return deleteOuterVessel()">
							           <input type="hidden" name="deleteId" value="<?php  echo $vslInfoData[$i]['id']; ?>"/>
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