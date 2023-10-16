<script>
	function search_type(type)
	{
		if( document.search_org_profile.search_by.value == "" )
		{
			alert( "Please provide search type!" );
			document.search_org_profile.search_by.focus() ;
			return false;
		}
		else
		{	
			if(type=="org_type")
			{
				document.getElementById("type").style.display = 'inline';
				document.getElementById("name").style.display = 'none';
				document.getElementById("lic").style.display = 'none';
				document.getElementById("aiin").style.display = 'none';
				//org_name.disabled=true;
				//org_type.disabled=false;
				//lic_no.disabled=true;
				
				document.search_org_profile.lic_no.value="";
				document.search_org_profile.aiin_no.value="";
			}
			else if(type=="org_name")
			{
				document.getElementById("type").style.display = 'none';
				document.getElementById("name").style.display = 'inline';
				document.getElementById("lic").style.display = 'none';
				document.getElementById("aiin").style.display = 'none';
				//org_name.disabled=false;
				//org_type.disabled=true;
				//lic_no.disabled=true;
				
				document.search_org_profile.lic_no.value="";
				document.search_org_profile.aiin_no.value="";
			}
			else if(type=="lic_no")
			{
				document.getElementById("type").style.display = 'none';
				document.getElementById("name").style.display = 'none';
				document.getElementById("lic").style.display = 'inline';
				document.getElementById("aiin").style.display = 'none';
				document.search_org_profile.aiin_no.value="";
				//org_name.disabled=true;
				//org_type.disabled=true;
				//lic_no.disabled=false;
				
			}
			else if(type=="aiin_no")
			{
				document.getElementById("type").style.display = 'none';
				document.getElementById("name").style.display = 'none';
				document.getElementById("lic").style.display = 'none';
				document.getElementById("aiin").style.display = 'inline';
				document.search_org_profile.lic_no.value="";
				//org_name.disabled=true;
				//org_type.disabled=true;
				//lic_no.disabled=false;
				
			}
		}
	}
	
	function validate()
	{
		if (confirm("Are you sure!! Delete this record?") == true) 
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
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php echo $title; ?></h2>
				</header>
				<div class="panel-body">															
					<!-- -->
					<!-- need to work in this form -->
					<!--form class="form-horizontal form-bordered" name="search_org_profile" id="search_bill" action="<?php echo site_url("cfsModule/searchOrgProfile");?>" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
								<div align="left" id="type" style="display:none;">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Search By :<span class="required">*</span></span>
										
										<select name="search_by" id="search_by" onchange="search_type(this.value);">
											<option value="">--Select--</option>
											<option value="org_type">Org Type</option>
											<option value="org_name">Org Name</option>
											<option value="lic_no">License No</option>
											<option value="aiin_no">Aiin No</option>
										</select>
									</div>
								</div>
								<div align="left" id="name" style="display:none;">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Organization Type :<span class="required">*</span></span>
										<select name="org_type" id="org_type" onchange="search_type(this.value);" >
											<option value="">--Select--</option>
											<?php
											for($i=0; $i<count($org_type_list); $i++)
											{ 
											?>
												<option value="<?php echo $org_type_list[$i]['id']; ?>" label="<?php echo $org_type_list[$i]['Org_Type']; ?>"><?php echo $org_type_list[$i]['Org_Type']; ?></option>
											<?php 
											} 
											?>											
																				
										</select>
									</div>												
								</div>
								<div align="left" id="lic" style="display:none;">								
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">License No :<span class="required">*</span></span>
										<input name="lic_no" id="lic_no" type="text" />
									</div>
								</div>
								<div align="left" id="aiin" style="display:none;">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Aiin No :<span class="required">*</span></span>
										<input name="aiin_no" id="aiin_no" type="text" />
									</div>
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<br>
							<div class="row">
								<div class="col-sm-12 text-center">								
									<form action="<?php echo site_url('cfsModule/org_creation_form');?>" method="POST">
										<input type="submit" value="ADD NEW" class="login_button" /> 
									</form>
								</div>													
							</div>
						</div>
					</form-->
										
					<!-- -->
					
					
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th>SL</th>
								<th>Org Type</th>
								<th>Org Name</th>
								<th>AIIN NO</th>
								<th>License</th>
								<th>Agent Code</th>
								<th>Action</th>
								<th>Action</th>						
							</tr>
						</thead>
						<tbody>
						<?php
						for($i=0;$i<count($profileList);$i++)
						{
						?>
							<tr class="gradeX">
								<td><?php echo $i+1; ?></td>
								<td><?php echo $profileList[$i]['Org_Type']?></td>
								<td><?php echo $profileList[$i]['Organization_Name']?></td>								
								<td><?php echo $profileList[$i]['AIN_No_New']?></td>								
								<td><?php echo $profileList[$i]['License_No']?></td>								
								<td><?php echo $profileList[$i]['Agent_Code']?></td>								
								<td>
									<form action="<?php echo site_url('cfsModule/editOrgProfile');?>" method="POST">
										<input type="hidden" name="lclID" value="<?php echo $profileList[$i]['profileId'];?>">							
										<input type="submit" value="Edit" name="edit" class="btn btn-primary" style="width:90%;">							
									</form> 
								</td>
								<td>
									<form action="<?php echo site_url('cfsModule/orgProfileList');?>" method="POST" onsubmit="return validate();">
										<input type="hidden" name="lclID" value="<?php echo $profileList[$i]['profileId'];?>">							
										<input type="submit" value="Delete" name="delete" class="btn btn-danger" style="width:100%;">							
									</form>  
								</td>								
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
