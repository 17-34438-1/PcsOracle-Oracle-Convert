<script>	
	function validate() 
	{
		if(document.getElementById('visit_id').value!="" && document.getElementById('collected_id').value!="" && document.getElementById('current_paid_collected_by').value!="")
		{
			if(confirm('Do you really want to submit the form?')) 
			{
				return true;
			}
			else
			{						
				return false;
			}
		}
		else
		{
			alert("Please fill all info");
			return false;
		}		
	}

	function getCollectedBy()
	{		
		if (window.XMLHttpRequest) 
		{
		  	xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		var visit_id = document.getElementById('visit_id').value;

		var url = "<?php echo site_url('AjaxController/getCollectedBy')?>?visit_id="+visit_id;	    

		xmlhttp.open("GET",url,false);
		xmlhttp.onreadystatechange=stateChangeAssignment;			
		xmlhttp.send();
	}

	function stateChangeAssignment()
	{			
		if(xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
		 	var val = xmlhttp.responseText;
			
		    var jsonData = JSON.parse(val);
			
			if(jsonData.resMsg=="valid")
			{				
				document.getElementById('collected_id').value=jsonData.collectedBy;
			}
			else
			{
				alert(jsonData.resMsg);
				document.getElementById('collected_id').value="";
			}
			
			return false;			
		}
	}

</script>

<style>
	
     #table-scroll {
	  height:500px;
	  overflow:auto;  
	  margin-top:0px;
      }
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
								
				<form method="POST" action="<?php echo site_url("ShedBillController/changeTruckInfo") ?>" onsubmit="return validate();">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
						
							<div class="col-md-6">		
								
									<div class="input-group mb-md">
									
									<span class="input-group-addon span_width" class="form-control">Visit Id:</span>
									
										<input  type="number" id="visit_id" name="visit_id" onblur="getCollectedBy()" class="form-control" placeholder="Visit Id">
									</div>
								<div class="input-group mb-md">
								
								<span class="input-group-addon span_width" class="form-control">Collected By:</span>
									
									<input  type="text" id="collected_id" name="prev_paid_collected_by" class="form-control" placeholder="Collected By" readonly>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" class="form-control">Change To:</span>
									
									<select  name="current_paid_collected_by" id="current_paid_collected_by" class="form-control">		
										<option value="">---Select---</option>
										<option value="gate3counter">gate3counter</option>
										<option value="gate5counter">gate5counter</option>
										<option value="gate2counter">gate2counter</option>
										<option value="ofycounter">ofycounter</option>
										<option value="nct3counter">nct3counter</option>
										<option value="gate1counter">gate1counter</option>
									</select>
								</div>
						
								<div class="row">
									<div class="col-sm-12 text-center">
									<input type="submit" class="btn btn-success" name="btnadd" value="Save" />
									</div>
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
			

	        </section>
			
		</div>
		
	</div>

	
</section>


