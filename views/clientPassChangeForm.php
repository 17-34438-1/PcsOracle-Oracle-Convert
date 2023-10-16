 <link rel="shortcut icon" href="<?php echo IMG_PATH; ?>changepassword.jpg" />
 <script type="text/javascript">
    function checkPass()
	{
		//Store the password field objects into variables ...
		var new_password = document.getElementById('new_password');
		var confirm_password = document.getElementById('confirm_password');
		//Store the Confimation Message Object ...
		var message = document.getElementById('confirmMessage');
		//Set the colors we will be using ...
		var goodColor = "#66cc66";
		var badColor = "#ff6666";
		//Compare the values in the password field 
		//and the confirmation field
		if(new_password.value == confirm_password.value){
			//The passwords match. 
			//Set the color to the good color and inform
			//the user that they have entered the correct password 
			confirm_password.style.backgroundColor = goodColor;
			message.style.color = goodColor;
			message.innerHTML = "Passwords Match!"
		}else{
			//The passwords do not match.
			//Set the color to the bad color and
			//notify the user.
			confirm_password.style.backgroundColor = badColor;
			message.style.color = badColor;
			message.innerHTML = "Passwords Not Match!"
		}
	}  

	function getUserName()
	{
		var loginId = document.getElementById('loging_id').value;
		
		if(loginId != ""){
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
			{		 			
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{							
					var val = xmlhttp.responseText;
					var jsonData = JSON.parse(val);
					
					var userName = "";
					
					if(jsonData.userName.length>0)
					{
						document.getElementById('userNameDiv').style.display = "block";
						userName = jsonData.userName[0].u_name
					}	
					else
					{
						alert("Login Id is invalid");
						document.getElementById('userNameDiv').style.display = "none";
						document.getElementById('assistantPassNo').value = "";
					}				
				
					document.getElementById('userName').value = userName;				
				}
			};
			
			var url = "<?php echo site_url('AjaxController/getuserName')?>?loginId="+loginId;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}else{
			document.getElementById('userNameDiv').style.display="none";
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
					<form action="<?php echo site_url('Login/changePassForClientPerform');?>" method="POST">

						<table style="background-color:#c3ecf9;">									
							<?php if($org_Type_id=='66'){ ?>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Login Id: <span class="required">*</span></span>
										<select name="network_logging_id" id="network_logging_id" class="form-control">
											<option value="">Select</option>
												<?php for($t=0;$t<count($loginList);$t++) {	?>					
											<option value="<?php echo $loginList[$t]['login_id']; ?>" ><?php echo $loginList[$t]['login_id' ]?></option>
													<?php } ?>
										</select><font color="red" size="4"></font>
									</div>
								</td>	
							</tr>
							<?php  } else { ?>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Login Id: <span class="required">*</span></span>
										  <input type="text" name="loging_id" style="width:250px" class="form-control" id="loging_id" value="" onblur="getUserName()">

									</div>
								</td>	
							</tr>
							<tr id="userNameDiv" style="display:none;">
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">User Name: <span class="required">*</span></span>
										<input type="text" name="userName" style="width:250px" class="form-control" id="userName" value="" readonly>
									</div>
								</td>	
							</tr>
							<?php } ?>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">New Password: <span class="required">*</span></span>
										<input type="password" name="new_password" style="width:250px"  class="form-control" id="new_password" value="" >
									</div>
								</td>	
							</tr>
							
							<tr>
								<td>
								  <div class="input-group mb-md">
								    <span class="input-group-addon span_width" style="width:170px;">Confirm Password: <span class="required">*</span></span>
									<input type="password" class="form-control" style="width:250px"  name="confirm_password" id="confirm_password" onkeyup="checkPass(); return false;">									 
									<span id="confirmMessage" class="confirmMessage"></span>
									</div>
								</td>
							</tr>
							<tr>
								<td align="center">
									<b><?php echo $ptitle; ?></b>
								</td>
							</tr>			
							<tr>
								<td colspan="3" align="center" >
								<div class="row">
									<div class="col-sm-12 text-center">
									<input type="submit" value="Update" name="Update" class="mb-xs mt-xs mr-xs btn btn-primary">
									<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
									</div>													
									</div>     
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
						
						   
					</form>			
		
					</div>
				</section>
		
			</div>
		</div>	
	<!-- end: page -->
	</section>
</div>