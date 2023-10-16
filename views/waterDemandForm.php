<script>
    function getVslName(rotation)
    {
        if (window.XMLHttpRequest) 
        {
            xmlhttp=new XMLHttpRequest();
        } 
        else 
        {  
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        var url = "<?php echo site_url('AjaxController/getVslName')?>?rot_no="+rotation;
        
        xmlhttp.onreadystatechange = function()
        { 
		//alert(xmlhttp.readyState)
				document.getElementById('vsl_name').value = null;
                document.getElementById('vsl_imo').value = null;
                document.getElementById('rotation').value = null;
                document.getElementById('ship_agent').value = null;
                document.getElementById('agent_addr').value = null;
                document.getElementById('mobl_number').value = null;
                document.getElementById('bizu_gkey').value = null;
                document.getElementById('agent_gkey').value = null;
                document.getElementById('delv_area').value = null; 
				
            if (xmlhttp.readyState==4 && xmlhttp.status==200) 
            { 
                var val = xmlhttp.responseText;
				// alert (val);
                var jsonData = JSON.parse(val);
                var vsl_name = jsonData[0].VSL_NAME;
				//var rotation = jsonData[0].rotation;
                var vsl_imo  = jsonData[0].VSL_IMO;
                var agent_name = jsonData[0].AGENT_NAME;
                var address = jsonData[0].ADDRESS;
                var contact_num = jsonData[0].CONTACT_NUM;
                var bizu_gkey = jsonData[0].BIZU_GKEY;
                var agent_gkey = jsonData[0].AGENT_GKEY;
                document.getElementById('vsl_name').value = vsl_name;
                document.getElementById('vsl_imo').value = vsl_imo;
                document.getElementById('rotation').value = rotation;
                document.getElementById('ship_agent').value = agent_name;
                document.getElementById('agent_addr').value = address;
                document.getElementById('mobl_number').value = contact_num;
                document.getElementById('bizu_gkey').value = bizu_gkey;
                document.getElementById('agent_gkey').value = agent_gkey; 
            }
            else
            {
                document.getElementById('vsl_name').value = null;
                document.getElementById('vsl_imo').value = null;
                document.getElementById('rotation').value = null;
                document.getElementById('ship_agent').value = null;
                document.getElementById('agent_addr').value = null;
                document.getElementById('mobl_number').value = null;
                document.getElementById('bizu_gkey').value = null;
                document.getElementById('agent_gkey').value = null; 
                document.getElementById('delv_area').value = null; 
            }
        }
        
        xmlhttp.open("GET",url,false);
                    
        xmlhttp.send();
    }

    function validate()
    {
        rotation = $("#rotation").val().trim();
        vsl_name = $("#vsl_name").val().trim();
        water_demand = $("#water_demand").val().trim();
        if(!rotation || !vsl_name || !water_demand){
            alert("Rotation or water demand not given!");
            return false;
        }
        else
        {
            return true;
        }
        return false;
    }
	
	function supplyTypeChange(type)
	{
		//alert(type)
		if(type==="shore")
		{
			document.getElementById("burgeDiv").style.display="none";
		}
		else if(type==="burge")
		{
			document.getElementById("burgeDiv").style.display="block";
		}
		else
		{
			document.getElementById("burgeDiv").style.display="none";
		}
	}
	
	/* $(function(){
		alert($('#supplyType').selectedIndex)
	      if($('#supplyType').selectedIndex == 1){
				$('#burgeDiv').hide();
			}else{
				$('#burgeDiv').hide();
			}
		}); */
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
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
                        <form class="form-horizontal form-bordered" method="POST" name="fileUpload" id="fileUpload" action="<?php echo site_url('Vessel/waterDemandForm') ?>" enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-md-2 control-label">&nbsp;</label>
								<div class="col-md-8">	
									<div class="row">
										<div class="col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon" style="width:150px">Rotation<span class="required">*</span></span>
												<input type="text" name="rotation" id="rotation" class="form-control" placeholder="rotation no" onblur="getVslName(this.value)">
											</div>
										</div>
										<div class="col-md-6">											
										</div>
                                    </div>
									<!-- <br/> -->
									
									<div class="row">
									    <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon span_width" style="width:150px">Vessel Name </span>
												<input type="text" name="vsl_name" id="vsl_name" class="form-control" placeholder="vessel name" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon span_width" style="width:150px">Vessel IMO</span>
                                                <input type="text" name="vsl_imo" id="vsl_imo" class="form-control" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
									
                                  
                                    <!-- <div class="input-group mb-md">
										<span class="input-group-addon span_width">Water Demand <span class="required">*</span></span>
										<input type="text" name="water_demand" id="water_demand" class="form-control" placeholder="water demand">
									</div> -->
									
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon" style="width:150px">Demand Qty<span class="required">*</span></span>
                                                <input type="text" name="water_demand" id="water_demand" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon" >Unit<span class="required">*</span></span>
                                                <select name="unit" id="unit" class="form-control" required>
                                                    <option value="METRIC_TONNES">METRIC_TONNES</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Agent Name </span>
												<input type="text" name="ship_agent" id="ship_agent" class="form-control" placeholder="Agent Name" value="" readonly>
											</div>	
										</div>			
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Agent Address </span>
												<input type="text" name="agent_addr" id="agent_addr" class="form-control" placeholder="Agent Address" value="" readonly>
											</div>	
										</div>			
									</div>
									<div class="row">
									    <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon span_width" >Mobile</span>
												<input type="text" name="mobl_number" id="mobl_number" class="form-control" placeholder="Mobile" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
								
									<div class="row">
									    <div class="col-md-6">
                                            <div class="input-group mb-md">
											<span class="input-group-addon span_width" >Supply Type<span class="required">*</span></span>
											<select name="supplyType" id="supplyType" class="form-control"  onchange="supplyTypeChange(this.value)" required>
												<option value="" selected="true">--Select--</option>
												<option value="shore">Supply By Shore</option>
												<option value="burge">Supply By Burge</option>
											</select>
											</div>
										</div>
                                    </div>
									
								<div id="burgeDiv" style="display:none;">
										<input type="hidden" name="bizu_gkey" id="bizu_gkey" class="form-control" >
										<input type="hidden" name="agent_gkey" id="agent_gkey" class="form-control">
									<div class="row">
									    <div class="col-md-6">
                                            <div class="input-group mb-md">
                                                <span class="input-group-addon span_width" >Delivery Area <span class="required">*</span></span>
												<input type="text" name="delv_area" id="delv_area" class="form-control" placeholder="Delivery Area" value="">
                                            </div>
                                        </div>
                                        
                                    </div> 
									
									<div class="row">
										<div class="col-md-12">
										<div class="input-group mb-md">
											 <span class="input-group-addon span_width" >Upload requisition Letter<span class="required">*</span></span>
                                                <input type="file" name="file" id="file" class="form-control" >
										</div>	
										</div>			
									</div>
								</div>	
								<div class="row">
									<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:150px">Demand Date <span class="required">*</span></span>
										<input type="date" name="demand_date" id="demand_date" class="form-control" value="<?php echo date('Y-m-d');?>">
									</div>	
									</div>			
								</div>
																				
								<div class="row">
									<div class="col-md-6">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success">Demand</button>
										</div>	
									</div>											
								</div>
							</div>
                        </form>	
						<!-- </form> -->
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>