<script type="text/javascript">
	function checkPass()
	{
		//alert("ok");
		var uPass = document.getElementsByName("uPass")[0].value;
		var cPass = document.getElementsByName("cPass")[0].value;
		//alert(uPass+"="+cPass);
		if(uPass!=cPass)
		{
			document.getElementById("no").style.display="inline";
			document.getElementById("yes").style.display="none";
		}
		else
		{
			document.getElementById("yes").style.display="inline";
			document.getElementById("no").style.display="none";
		}
	}
	
	function myFormValidation()
	{
		var orgType = document.getElementById("orgType").value;
		var orgName = document.getElementById("orgName").value;
		var uId = document.getElementById("uId").value;
		var uPass = document.getElementById("uPass").value;
		var cPass = document.getElementById("cPass").value;
		var cPhone = document.getElementById("cPhone").value;
		var email = document.getElementById("email").value;
		//alert(orgType);
		if(orgType=="" || orgType==" ")
		{
			alert("Select a organization type.");
			document.getElementById("orgType").style.background="#F6CECE";
			document.getElementById("orgType").focus();
			return false;
		}
		else if(orgName=="" || orgName==" ")
		{
			alert("Type a organization name.");
			document.getElementById("orgName").style.background="#F6CECE";
			document.getElementById("orgName").focus();
			return false;
		}
		else if(uId=="" || uId==" ")
		{
			alert("Type a user id.");
			document.getElementById("uId").style.background="#F6CECE";
			document.getElementById("uId").focus();
			return false;
		}
		else if(uPass=="" || uPass==" ")
		{
			alert("Type a password.");
			document.getElementById("uPass").style.background="#F6CECE";
			document.getElementById("uPass").focus();
			return false;
		}
		else if(cPass=="" || cPass==" ")
		{
			alert("Type a confirm password.");
			document.getElementById("cPass").style.background="#F6CECE";
			document.getElementById("cPass").focus();
			return false;
		}
		else if(cPhone=="" || cPhone==" ")
		{
			alert("Type a phone number.");
			document.getElementById("cPhone").style.background="#F6CECE";
			document.getElementById("cPhone").focus();
			return false;
		}
		else if(email=="" || email==" ")
		{
			alert("Type a email number.");
			document.getElementById("email").style.background="#F6CECE";
			document.getElementById("email").focus();
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function user_type(userType)
	{
		if(userType=="organization")
		{
			image.disabled=false;
			document.getElementById("div_uname").style.display = "none";
		}
		else if(userType=="cnf")
		{
			image.disabled=false;
			document.getElementById("div_uname").style.display = "none";
		}

		else
		{
			document.getElementById("div_uname").style.display = "block";
			image.disabled=true;
		}
	}
	
	function allow_license()
	{
		var orgType = document.getElementById("orgType").value;
	
		if(orgType==2)
			license_no.disabled=false;
		else
			license_no.disabled=true;
	}
	
	function get_cnf_info()
	{
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		var license_no = document.getElementById("license_no").value;
		
		var url = "<?php echo site_url('AjaxController/get_cnf_info')?>?license_no="+license_no;
		
		xmlhttp.onreadystatechange=stateChangeCnfInfo;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeCnfInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			document.getElementById("orgName").value=jsonData[0].u_name;
			document.getElementById("address1").value=jsonData[0].Address_1;
			document.getElementById("address2").value=jsonData[0].Address_2;
			
			var land_phone=jsonData[0].Telephone_No_Land;
			var ind_of_land_phone=land_phone.indexOf(",");
			
			if(ind_of_land_phone>-1)
			{
				land_phone = land_phone.substring(0, ind_of_land_phone);
				document.getElementById("lPhone").value=land_phone;
			}
			else
			{
				document.getElementById("lPhone").value=jsonData[0].Telephone_No_Land;		
			}
			
			var cell_phone=jsonData[0].Cell_No_1;
			var ind_of_cell_phone=cell_phone.indexOf(",");
			
			if(ind_of_cell_phone>-1)
			{
				cell_phone = cell_phone.substring(0, ind_of_cell_phone);
				document.getElementById("cPhone").value=cell_phone;
			}
			else
			{
				document.getElementById("cPhone").value=jsonData[0].Cell_No_1;		
			}
			
			document.getElementById("email").value=jsonData[0].email;										
		}
	}
	$(document).on('change', 'input', function(){
		var optionslist = $('datalist')[0].options;
		var value = $(this).val();
		var orgType = document.getElementById("orgType").value;
		for (var x=0;x<optionslist.length;x++){
		   if (optionslist[x].value === value) {
			   
			
			
			if(orgType=="" || orgType==" ")
			{
				alert("Select a organization type.");
				//document.getElementById("orgType").style.background="#F6CECE";
				//document.getElementById("orgType").focus();
				return false;
			}
			else
			{
				if (window.XMLHttpRequest) 
				{
				  xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				
				  //Alert here value
				var url = "<?php echo site_url('AjaxController/get_org_info')?>?org_type="+orgType+"&org_name="+value;
				
				xmlhttp.onreadystatechange=stateChangeOrgInfo;
				xmlhttp.open("GET",url,false);
							
				xmlhttp.send();
			}
			  //alert(value);
			  //break;
		   }
		}
	});
	function stateChangeOrgInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//console.log("L : "+jsonData.length);
			document.getElementById("address1").value=jsonData[0].Address_1;
			document.getElementById("address2").value=jsonData[0].Address_2;
			document.getElementById("cPhone").value=jsonData[0].Cell_No_1;
			document.getElementById("email").value=jsonData[0].email;
			document.getElementById("lPhone").value=jsonData[0].Cell_No_2;
			
											
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
					<form class="form-horizontal form-bordered" name="userCreation" id="userCreation" action="<?php echo site_url("Report/userCreation"); ?>" method="POST" onsubmit="return myFormValidation();" enctype="multipart/form-data">
					<input type="hidden" name="orgId_edit" id="orgId_edit" 
						value="<?php if(isset($rslt_user_data[0]['org_id'])) echo $rslt_user_data[0]['org_id']; ?>">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg;?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization Type <span class="required">*</span></span>
									<select name="orgType" id="orgType" class="form-control" onchange="allow_license()" required>
										<option value="">Select</option>						
										<?php 
										if($org_Type_id=='66')
										{ 
										?>
										<option value="<?php echo $org_Type_id ?>" >Network</option>
										<?php
										}
										else 
										{			
											for($t=0;$t<count($orgList);$t++) 
											{		
										?>								
					
										<option value="<?php echo $orgList[$t]['id']; ?>" 
											<?php  
												if(isset($rslt_user_data[0]['org_Type_id']))
												{
													if($orgList[$t]['id']==$rslt_user_data[0]['org_Type_id']) 
														echo 'selected="selected"';
												}
												 ?>>
											<?php echo $orgList[$t]['Org_Type']?>
										</option>
										<?php
											}
										}
										?>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">License No.</span>
									<input class="form-control login_input_text" id="license_no" name="license_no" type="text" 
									 onblur="get_cnf_info()" disabled/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">User Type</span>
									<select name="userType" id="userType" class="form-control" onchange="user_type(this.value);" >
										<option value="">--Select--</option>
										<option value="organization">Organization</option>
										<option value="cnf">CNF</option>
										<option value="single">Single</option>
									</select>
								</div>
							
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization Name <span class="required">*</span></span>
									<input class="form-control login_input_text" list="org_names" name="orgName" id="orgName" value="<?php if(isset($rslt_user_data[0]['u_name'])) echo $rslt_user_data[0]['u_name']; ?>" required />
									<datalist id="org_names">
									<?php
									include('mydbPConnection.php');
									$sql_org_name="SELECT id,Organization_Name FROM organization_profiles";
									
									$rslt_org_name=mysqli_query($con_cchaportdb,$sql_org_name);
									
									while($row_org_name=mysqli_fetch_object($rslt_org_name))
									{
									?>
										<option value="<?php echo $row_org_name->Organization_Name; ?>" id="<?php echo $row_org_name->id; ?>" label="<?php echo $row_org_name->Organization_Name; ?>">
										</option>
									<?php
									}
									?>											
									</datalist>									
								</div>
								
								<div id="div_uname"  style="display:none;">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">User Name <span class="required">*</span></span>
										<input class="form-control login_input_text" type="text" name="u_name" id="u_name" >
									</div>
								
								</div>
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">User Id <span class="required">*</span></span>
									<input class="form-control login_input_text" type="text" name="uId" id="uId" 
									value="<?php if(isset($rslt_user_data[0]['login_id'])) echo $rslt_user_data[0]['login_id'];?>" 
									<?php if(isset($rslt_user_data[0]['login_id'])) echo "readonly" ;?>
									required>
								</div>
								<?php 
								if($creation==1)
								{
								?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Password <span class="required">*</span></span>
									<input class="form-control login_input_text" type="password" name="uPass" id="uPass" required>
									<input type="hidden" name="create" id="create" value=1 />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Confirm Password <span class="required">*</span></span>
									<input type="password" class="form-control" name="cPass" id="cPass"  onkeyup="checkPass()" required>
									<span style=" display: none;" id="no"><font color="red" size="5">&#10008;</font></span><span style=" display: none;" id="yes"><font color="green" size="5">&#10004;</font></span>
								</div>
								<?php 
								}
								?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Address 1</span>
									<textarea name="address1" class="form-control" id="address1"><?php if(isset($rslt_user_data[0]['Address_1'])) echo $rslt_user_data[0]['Address_1'];?></textarea>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Address 2</span>
									<textarea name="address2" class="form-control" id="address2"><?php if(isset($rslt_user_data[0]['Address_2'])) echo $rslt_user_data[0]['Address_2'];?></textarea>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Land Phone</span>
									<input type="text" class="form-control" name="lPhone" id="lPhone" value="<?php if(isset($rslt_user_data[0]['Telephone_No_Land'])) echo $rslt_user_data[0]['Telephone_No_Land'];?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Cell Phone <span class="required">*</span></span>
									<input type="text" class="form-control" name="cPhone" id="cPhone" value="<?php if(isset($rslt_user_data[0]['Cell_No_1'])) echo $rslt_user_data[0]['Cell_No_1'];?>" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Email <span class="required">*</span></span>
									<input type="text" class="form-control" name="email" id="email" value="<?php if(isset($rslt_user_data[0]['email'])) echo $rslt_user_data[0]['email'];?>" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Section</span>
									<select name="section" id="section" class="form-control">
										<option value="">--Select--</option>
									<?php
									$sql_section="select id,full_name from users_section_detail";
									$rslt_section=mysqli_query($con_cchaportdb,$sql_section);
									while($row_section=mysqli_fetch_object($rslt_section))
									{
									?>
										<option value="<?php echo $row_section->id;?>" 
											<?php
												if(isset($rslt_user_data[0]['section']))
												{
													if($row_section->id==$rslt_user_data[0]['section']) 
														echo 'selected="selected"'; 
												}
											?>><?php echo $row_section->full_name;?></option>
									<?php
									}
									?>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Image</span>
									<input name="image" class="form-control" id="image" type="file" />
								</div>
								<?php
								if(isset($rslt_user_data[0]['login_id']))
								{									
								?>
										<div class="col-sm-12 text-center">								
											<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
										</div>
										
								<?php
								}
								else
								{
								?>
									<div class="col-sm-12 text-center">								
										<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Create</button>
									</div>
									<!--div class="col-sm-12 text-center">								
										<button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>
									</div-->
								<?php
								}						
								?>
							</div>																		
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
