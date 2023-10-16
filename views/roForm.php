<script>
	
	function validate()
	{
		var agent_id = document.getElementById("agent_id").value.trim();
		var roType = document.getElementById("roType").value.trim();
		var imp_rot = document.getElementById("imp_rot").value.trim();
		var bl_no = document.getElementById("bl_no").value.trim();

		/* if(!agent_id)
		{
			alert("Please Select agent..");
			document.getElementById("agent_id").focus();
			return false;
		} */

		if(!roType)
		{
			alert("Please Select R/O type...");
			document.getElementById("roType").focus();
			return false;
		}
		if(!imp_rot)
		{
			alert("Please write a rotation no...");
			document.getElementById("imp_rot").focus();
			return false;
		}
		if(!bl_no)
		{
			alert("Please write a bl no...");
			document.getElementById("bl_no").focus();
			return false;
		}
		else if(confirm("Do you want to submit this release order?") == true)
		{
			return true;
		}

		return false;
	}
	
	function getCnfData()
	{
		var cnf_ain = document.getElementById("cnf_ain").value;
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
		//var branch=document.getElementById("branch_id").value;
		var url = "<?php echo site_url('ReleaseOrderController/getAgentByCnf');?>?cnf_ain="+cnf_ain;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeAgentByCnf;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
		//clearstatcache();
		//getSupplierCountry();
		//getProductUnit();
	}
	function stateChangeAgentByCnf()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
		  var selectList=document.getElementById("agent_id");
		  removeOptions(selectList);
		  //alert(xmlhttp.responseText);
		  var val = xmlhttp.responseText;
		  var jsonData = JSON.parse(val);
		  //alert(xmlhttp.responseText);
		  
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].id;
				option.text = jsonData[i].agent_name+" ("+jsonData[i].agent_code+")";
				selectList.appendChild(option);
				//alert(jsonData[i].product_name);
			}
		}
	}
	function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			//selectbox.remove(i);
			selectbox.children[i].remove();
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
						<?php
							if(!is_null($this->session->flashdata('success'))){
								echo $this->session->flashdata('success');
							}

							if(!is_null($this->session->flashdata('error'))){
								echo $this->session->flashdata('error');
							}
						?>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" 
								action="<?php echo site_url('ReleaseOrderController/submitRO') ?>" onsubmit="return validate();">
								<div class="form-group">
									<div class="col-md-8 col-md-offset-3">	
										<?php if($org_Type_id == 2 or $org_Type_id == "2") { ?>
										<div class="row">
											<div  class="col-md-2">
												<span  style="font-weight:bold;">C&F Name :</span>
											</div>
											<div class="col-md-9">
												<?php echo $u_name; ?>
											</div>
										</div>
										<div class="row">
											<div  class="col-md-2">
												<span  style="font-weight:bold;">Address :</span>
											</div>
											<div class="col-md-9">
												<?php
													$addr = null;
													$cell = null;
													
													for($b=0;$b<count($rslt_agent);$b++)
													{
														$addr = $rslt_agent[$b]['Address_1'].$rslt_agent[$b]['Address_2'];
														$cell = $rslt_agent[$b]['Cell_No_1'];
													}
													
													echo $addr;
												?>
											</div>
										</div>
										<div class="row">
											<div  class="col-md-2">
												<span  style="font-weight:bold;">Mobile No :</span>
											</div>
											<div class="col-md-9">
												<?php 
													echo $cell; 
												?>
											</div>
										</div>
										<?php } else { ?>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">C&F AIN :</span>
											<input type="text" class="form-control" id="cnf_ain" name="cnf_ain" onblur="getCnfData()">
										</div>
										<?php } ?>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Agent / Jetty Sircar Name :</span>
											
											 
											<select name="agent_id" id="agent_id" class="form-control">
												<option value="">-- Select --</option>
												<?php 
													// if($flag==1)
													// {
												?>
												<!-- <option value="<?php //echo $rslt_sqlRelease[0]['agent_name']; ?>"  selected="true" > <?php //echo $rslt_sqlRelease[0]['agent_name'] ?></option> -->

												<?php
													// }else if($flag==0){ 
														for($i=0;$i<count($rslt_agent);$i++)
														{
												?>
													<option value="<?php echo $rslt_agent[$i]['id']; ?>"><?php echo $rslt_agent[$i]['agent_name']." (".$rslt_agent[$i]['agent_code'].")"; ?></option>
												<?php
														}
													// }  
												?>
											</select>
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">R/O type :</span>
											<select name="roType" id="roType" class="form-control">
												<option value="">-- Select --</option>
												<option value="Appraise">Appraise</option>
												<option value="Appraise cum Delivery">Appraise cum Delivery</option>
												<option value="Delivery">Delivery</option>
											</select>
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Reg No :</span>

											<?php 
											// if($flag==1)
											// {
											?> 
												<input type="text" name="imp_rot" id="imp_rot" class="form-control" <?php //if($flag==1){?> value="<?php //echo $rslt_sqlRelease[0]['imp_rot']; ?>" <?php //}?> required>
											<?php
											// }  
											// else
											// {
											?>	
											<!-- <input type="text" name="imp_rot" id="imp_rot" class="form-control" required> -->

											<?php
											// }
											?>
											
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BL No :</span>
											<?php 
											// if($flag==1)
											// {
											?> 
												<input type="text" name="bl_no" id="bl_no" class="form-control" <?php //if($flag==1){?> value="<?php //echo $rslt_sqlRelease[0]['bl_no']; ?>" <?php //}?> required>
											<?php
											// } else{
											?>	
											<!-- <input type="text" name="bl_no" id="bl_no" class="form-control" required> -->

											<?php
											// }
											?>

										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">

										<?php 
											// if($flag==1)
											// {
											?>
											<!-- <input class="mb-xs mt-xs mr-xs btn btn-primary" name="update" type="submit" value="UPDATE" > -->
											<?php 
											// } 
											// else
											// { 
											?>
											<button type="submit" name="btnSave"  id="btnSave" class="mb-xs mt-xs mr-xs btn btn-primary">Submit RO</button>
											<?php 
											// } 
											?>

										</div>													
									</div>

									<div class="row">
										<div class="col-sm-12 text-center">
											<?php echo $msg; ?>
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