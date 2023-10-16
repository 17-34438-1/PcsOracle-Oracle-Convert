<script>
	function getff(ain_no)
		{
			//alert(ain_no)
			if(ain_no=="")
			{
				alert("Please enter FF AIN No.");			
				document.getElementById('cnflic').focus();
			}
			else
			{
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
					xmlhttp.onreadystatechange=stateChangeFFName;
					
					var url="<?php echo site_url('ajaxController/getFFName')?>?ain_no="+ain_no;
					// alert(url);
					xmlhttp.open("GET",url,false);
					xmlhttp.send();					
			}
		}
		
	function stateChangeFFName()
		{
			//alert(xmlhttp.responseText);
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
				//var selectList=document.getElementById("dept");
				//removeOptions(selectList);
				//alert(xmlhttp.responseText);
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);
				//alert(xmlhttp.responseText);
				var ff_name = ""; 
				for (var i = 0; i < jsonData.length; i++) 
				{
					ff_name = jsonData[i].Organization_Name;
				}
				if(ff_name=="")
				{
					alert("Sorry! Organization doesn't exist!");
					document.getElementById("ffLicNo").value="";
					document.getElementById("ffName").value="";
					document.getElementById("tokenQty").value="";
				}
				else
				{
					document.getElementById("ffName").value=ff_name;
				}
			}
		}
	function validate()
		{
			if (confirm("Do you want to continue the transition ?") == true)
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
								action="<?php echo site_url('EDOController/tokenDistributionEntry') ?>" onsubmit="return validate();">
								<input type="hidden" class="form-control" id="frmType" name="frmType" value="<?php echo $frmType; ?>">
								<input type="hidden" class="form-control" id="infoID" name="infoID"
									value="<?php if($frmType=="edit"){ echo $editInfo[0]['GangInformationID'];} else{echo "";} ?>">
								<div class="form-group">									
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">AIN No :</span>
											<input type="text" name="ffLicNo" id="ffLicNo" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $editInfo[0]['ffLicNo'];} else{echo "";} ?>"
													onblur="getff(this.value)" required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">FF Name :</span>
											<input type="text" name="ffName" id="ffName" class="form-control" 
												value="<?php if($frmType=="edit"){ echo $editInfo[0]['ffName'];} else{echo "";} ?>" required readonly>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Number of Token :</span>
											<input type="number" name="tokenQty" id="tokenQty" class="form-control" min="1"
												value="<?php if($frmType=="edit"){ echo $editInfo[0]['tokenQty'];} else{echo "";} ?>" required>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<?php if($msg != "NULL") {echo $msg; } ?>
										</div>
									</div>
								</div>	
							</form>
							<!--hr/>					
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL No</th>
										<th class="text-center">Gang ID</th>
										<th class="text-center">Gang Name</th>
										<th class="text-center">Required Labour</th>	
										<th class="text-center">Remarks</th>	
										<th class="text-center">Action</th>	
										<th class="text-center">Action</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnGangList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $rtnGangList[$i]['GangID']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['GangName']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['RequiredLabor']?></td>
										<td align="center"><?php echo $rtnGangList[$i]['Remarks']?></td>
										<td align="center">
											<form action="<?php echo site_url('report/GangUpdate');?>" method="POST">
												<input type="hidden" name="editID" id="editID" value="<?php echo $rtnGangList[$i]['GangInformationID'];?>">
												<button type="submit" name="btnEdit" id="btnEdit" class="mb-xs mt-xs mr-xs btn btn-success">Edit</button>
											</form>
										</td>
										<td align="center">
											<form action="<?php echo site_url('report/GangDelete');?>" method="POST" onsubmit="return validate();">
												<input type="hidden" name="deleteID" id="deleteID" value="<?php echo $rtnGangList[$i]['GangInformationID'];?>">
												<button type="submit" name="btnDelete" id="btnDelete" class="mb-xs mt-xs mr-xs btn btn-danger">Delete</button>
											</form>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table-->
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>