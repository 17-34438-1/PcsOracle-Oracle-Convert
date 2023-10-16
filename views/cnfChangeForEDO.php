<script>
	function confirmation()
		{
			var edo_number = document.getElementById("edo_number").value;
			var current_cnf = document.getElementById("current_cnf").value;
			var correct_cnf = document.getElementById("correct_cnf").value;
			if(edo_number==""){
				alert("Please provide EDO Number.");
				return false;
			} else if(current_cnf==""){
				alert("Present C&F cannot be blank or empty.");
				return false;
			} else if(correct_cnf==""){
				alert("Please provide new C&F AIN.");
				return false;
			} else {
				if (confirm("Do You Want to Change C&F AIN Number ?") == true)
					{
						return true ;
					}
				else
					{
						return false;
					}
			}
			
			
		}
		
	function getCNFInfo(edo_number)
	{	
		document.getElementById("current_cnf").value = "";
		if(edo_number=="")
		{
			document.getElementById("current_cnf").value = "";
			alert("Please provide EDO Number");
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
			
			
			xmlhttp.onreadystatechange=function(){		 
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);				
				
				//alert(xmlhttp.responseText);
				if(jsonData.msgFlag==0)
				{
					alert("Sorry! Invalid EDO Number");
					return false;
				}
				else
				{
					document.getElementById("current_cnf").value = jsonData.orgName + " ("+jsonData.ain+")";
				}			
			}
		};
			
			var url = "<?php echo site_url('AjaxController/getCNFAIN')?>?edo_number="+edo_number;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
		
	}
	
	function getCorrectCnfName(correct_cnf_ain)
	{	
		document.getElementById("correct_cnf_name").value = "";
		if(correct_cnf_ain=="")
		{
			document.getElementById("correct_cnf_name").value = "";
			alert("Please provide Correct C&F Name");
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
			
			
			xmlhttp.onreadystatechange=function(){		 
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);				
				
				//alert(xmlhttp.responseText);
				if(jsonData.ainValidityFlag==0)
				{
					alert("Sorry! Invalid AIN Number");
					document.getElementById("correct_cnf").value = "";
					return false;
				}
				else
				{
					document.getElementById("correct_cnf_name").value = jsonData.cnf_name;
				}			
			}
		};
			
			var url = "<?php echo site_url('AjaxController/getCorrectCnfInfo')?>?correct_cnf_ain="+correct_cnf_ain;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
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
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" 
								action="<?php echo site_url('EDOController/changeCNFForEDO') ?>" onsubmit="return confirmation();">
								<input type="hidden" class="form-control" id="frmType" name="frmType" value="<?php echo $frmType; ?>">
								<input type="hidden" class="form-control" id="save" name="save" value="save">
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">EDO Number :</span>
											<input type="text" name="edo_number" id="edo_number" class="form-control" 
												onblur="return getCNFInfo(this.value);"  required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Present C&F (AIN) :</span>
											<input type="text" name="current_cnf" id="current_cnf" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $ediCNFinfo[0]['current_cnf'];} else{echo "";} ?>" readonly required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Should be C&F (AIN) :</span>
											<input type="text" name="correct_cnf" id="correct_cnf" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $ediCNFinfo[0]['correct_cnf'];} else{echo "";} ?>" 
												onblur="return getCorrectCnfName(this.value);" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Should be C&F (Name) :</span>
											<input type="text" name="correct_cnf_name" id="correct_cnf_name" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $ediCNFinfo[0]['correct_cnf_name'];} else{echo "";} ?>" readonly required>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Change</button>
										</div>													
									</div>
								</div>	
							</form>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>