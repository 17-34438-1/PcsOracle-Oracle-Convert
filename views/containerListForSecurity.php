<script>
	function changeTextBox(val)
		{
			//alert(val);
			var conboDiv = document.getElementById("conboDiv");
			var inputDiv = document.getElementById("inputDiv");
			if(val=="Date")
			{
				inputDiv.style.display="inline";
				conboDiv.style.display="none";
			}
			else
			{
				inputDiv.style.display="none";
				conboDiv.style.display="inline";
				
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
				var url = "<?php echo site_url('ajaxController/getGateList');?>?colName="+val;
				//alert(url);
				xmlhttp.onreadystatechange=stateChangeSearchComboVal;
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
			
		}
		
	function stateChangeSearchComboVal()
		{
			//alert(xmlhttp.responseText);
           if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{
			var selectList=document.getElementById("searchVal");
			removeOptions(selectList);
				//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
				//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].id;
				option.text = jsonData[i].id;
				selectList.appendChild(option);
                                
			}
			}
		}
		
    function removeOptions(selectbox)
	{
	var i;
	for(i=selectbox.options.length-1;i>=1;i--)
            {
		selectbox.remove(i);
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
					
					<div class="form-group" align="center"><b>CONTAINER LIST SEARCH </b></div>
					<div class="panel-body" align="center">
					<form action="<?php echo site_url('Report/containerListForSecurityBySearch');?>" method="POST" target="_blank" >				
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:150px;">Search By : <span class="required">*</span></span>
										<select style="width:170px" name="search_by" id="search_by" onchange="changeTextBox(this.value);">
											<option value="" >Select</option>
											<option value="Gate" label="Gate">Gate</option>
											<option value="Date" label="Date">Date</option>
										 </select>
								</div>	
							</div>
							
							<div class="col-md-6" id="conboDiv" style="display:none;">		
								<div class="input-group mb-md"  >
									<span class="input-group-addon span_width" style="width:150px;">Search Value:<span class="required">*</span></span>
											<select name="searchVal" id="searchVal" >
											<option value="">---select---</option>
											</select>
										
								</div>	
							</div>
							
							<div class="col-md-6" id="inputDiv" style="">		
								<div class="input-group mb-md" >
									<span class="input-group-addon span_width" style="width:150px;">Search Value:<span class="required">*</span>
										<input type="date" id="searchInput" name="searchInput" value="<?php date("Y-m-d"); ?>" />										 									
								</div>	
							</div>
								


								
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
									<!--input type="submit" value="Save" name="save" class="login_button"-->

								</div>													
							</div>
							</form>
								<div class="row">
									<div class="col-sm-12 text-center">
										
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