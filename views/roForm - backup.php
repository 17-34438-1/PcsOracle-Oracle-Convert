<script>
	function getVerifyNo()
		{
			var rotNo = document.getElementById("imp_rot").value;
			var blNo = document.getElementById("bl_no").value;
			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
			// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			//alert(blNo+"&rotNo="+rotNo);
			rotNo=rotNo.replace("/","_");
			xmlhttp.onreadystatechange=stateChangegetBLInfo;
			var url="<?php echo site_url('AjaxController/getDeliveryByBLInfo')?>?blNo="+blNo+"&rotNo="+rotNo;
			// alert(url);
			xmlhttp.open("GET",url,false);	
			
			xmlhttp.send();	 
		}

	function stateChangegetBLInfo()
	{				
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			var val = xmlhttp.responseText;
			//alert(xmlhttp.responseText);
			var jsonData = JSON.parse(val);
				
			document.getElementById("verify_number").value = jsonData.rtnContainerList[0].verify_number;				
		}
	}

	// function chkAssignment()
	// {
	// 	var rotNo = document.getElementById("imp_rot").value;
	// 	var blNo = document.getElementById("bl_no").value;

	// 	if (window.XMLHttpRequest) 
	// 	{
	// 		// code for IE7+, Firefox, Chrome, Opera, Safari
	// 		xmlhttp=new XMLHttpRequest();
	// 	} 
	// 	else 
	// 	{  
	// 	// code for IE6, IE5
	// 		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	// 	}
		
	// 	rotNo=rotNo.replace("/","_");
	// 	//alert(blNo+"&rotNo="+rotNo);

	// 	xmlhttp.onreadystatechange=function()
	// 	{				
	// 		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
	// 		{			
	// 			var val = xmlhttp.responseText;
	// 			//alert(xmlhttp.responseText);
	// 			var jsonData = JSON.parse(val);
	// 			var chkAssignment = jsonData.chkAssignment[0];
	// 			//alert(chkAssignment);
	// 			if(chkAssignment == 1){
	// 				document.getElementById("btnSave").disabled = false;
	// 			}else{
	// 				alert("Apply for assignment before view release order!");
	// 				document.getElementById("btnSave").disabled = true;
	// 			}
	// 		}
	// 	}
	// 	var url="<?php echo site_url('AjaxController/chkAssignmentInfo')?>?blNo="+blNo+"&rotNo="+rotNo;
	// 	// alert(url);
	// 	xmlhttp.open("GET",url,false);	
		
	// 	xmlhttp.send();	
	// }

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
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a>
							</h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" 
								action="<?php echo site_url('report/releaseorderpdf') ?>" onsubmit="return getVerifyNo()" target="_blank">
								<!--input type="hidden" name="verify_number" id="verify_number" class="form-control" -->
								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-3">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Reg No :</span>
											<input type="text" name="imp_rot" id="imp_rot" class="form-control" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BL No :</span>
											<input type="text" name="bl_no" id="bl_no" class="form-control" onblur="chkAssignment()" required>
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<!-- <button type="submit" name="btnSave" id="btnSave" class="mb-xs mt-xs mr-xs btn btn-primary" disabled>Show Release Order</button> -->
											<button type="submit" name="btnSave" id="btnSave" class="mb-xs mt-xs mr-xs btn btn-primary">Submit RO</button>
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