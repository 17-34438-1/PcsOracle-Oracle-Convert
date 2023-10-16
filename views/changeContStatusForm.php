<script>
	function getContStatus()
	{
		if (window.XMLHttpRequest) 
		{
		  	xmlhttp=new XMLHttpRequest();
		} 
        else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		var impRot = document.getElementById('impRot').value;
		var blNo = document.getElementById('blNo').value;
		var contNo = document.getElementById('contNo').value;
		
		impRot = impRot.replace("/", "_");
		
		var url="<?php echo site_url('AjaxController/getContStatus')?>?impRot="+impRot+"&blNo="+blNo+"&contNo="+contNo;
		// alert(url);
		xmlhttp.open("GET",url,false);
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
				var val = xmlhttp.responseText;		   		  
				var jsonData = JSON.parse(val);					
				
				document.getElementById('igmType').value= jsonData.igmType;
				document.getElementById('igmContId').value= jsonData.igmContId;
				document.getElementById('prevContStatus').value= jsonData.contStatus;
				document.getElementById('contStatus').value= jsonData.contStatus;
			}			
		};
		xmlhttp.send();
	}
	
	function formValidation()
	{
		var impRot = document.getElementById('impRot');
		var blNo = document.getElementById('blNo');
		var contNo = document.getElementById('contNo');
		var contStatus = document.getElementById('contStatus');
		
		var igmType = document.getElementById('igmType');
		var igmContId = document.getElementById('igmContId');
		
		if(impRot.value == "") 
		{
			alert('Please fill the rotation');
			return false;
		}
		else if(blNo.value == "")
		{
			alert('Please fill the blNo');
			return false;			
		}
		else if(contNo.value == "")
		{
			alert('Please fill the contNo');
			return false;
		}
		else if(contStatus.value == "")
		{
			alert('Container status not found');
			return false;
		}
		else if(igmType.value == "" || igmContId.value == "")
		{
			alert('Invalid info');
			return false;
		}
		else
		{
			if(confirm("Do you want to submit?"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->		
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('GateController/changeContStatus'); ?>" id="contStatusChangeForm" name="contStatusChangeForm"
					onsubmit="return formValidation()">
						<div class="form-group">
							
							<input type="hidden" name="igmType" id="igmType" />
							<input type="hidden" name="igmContId" id="igmContId" />
							
							<label class="col-md-3 control-label"><?php echo $msg; ?></label>
							<div class="col-md-6">																		
								<div class="form-group" id="rotation" style="display:block">
									<label class="col-md-4 control-label">Rotation :</label>
									<div class="col-md-8">
										<input type="text" id="impRot" name="impRot"  class="form-control" />
									</div>
								</div>								
								
								<div class="form-group" id="c_container"  style="display:block" >
									<label class="col-md-4 control-label">BL No :</label>
									<div class="col-md-8">
										<input type="text" id="blNo" name="blNo" class="form-control" />
									</div>
								</div>
								
								<div class="form-group" id="c_container"  style="display:block" >
									<label class="col-md-4 control-label">Container No :</label>
									<div class="col-md-8">
										<input type="text" id="contNo" name="contNo" class="form-control" onblur="getContStatus();"/>
									</div>
								</div>
								
								<div class="form-group" id="c_container"  style="display:block" >
									<label class="col-md-4 control-label">Status :</label>
									<div class="col-md-8">
										<input type="hidden" id="prevContStatus" name="prevContStatus" class="form-control" />
										<input type="text" id="contStatus" name="contStatus" class="form-control" />
									</div>
								</div>
								
								<div class="row" style="display:block;" id="div_btnSaveStatus" >
									<div class="col-md-9 text-right">
										<input type="submit" name="btnSaveStatus" id="btnSaveStatus" class="mb-xs mt-xs mr-xs btn btn-success" value="Save" />
									</div>													
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