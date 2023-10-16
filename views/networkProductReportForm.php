<script>
	function changeTextBox(val)
	{
		//alert(val);
		var conboDiv = document.getElementById("conboDiv");
		var inputDiv = document.getElementById("inputDiv");
		if(val=="serial" || val=="product" || val=="ip_addr")
		{
			inputDiv.style.display="inline";
			conboDiv.style.display="none";
		}
		else
		{
			inputDiv.style.display="none";
			conboDiv.style.display="inline";
			if(val=="product_sts"){
				setProductSts();
			}else{
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
				var url = "<?php echo site_url('ajaxController/getComboValForNetworkList');?>?colName="+val;
				//alert(url);
				xmlhttp.onreadystatechange=stateChangeSearchComboVal;
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			}
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
				option.text = jsonData[i].detl;
				selectList.appendChild(option);
			}
		}
	}

	function setProductSts(){
		var select = document.getElementById("searchVal");
		var length = select.options.length;
		for(i = length-1;i>=1;i--){
			select.options[i].remove();
		}

		var select = document.getElementById("searchVal");
		var value= ["Active","Damaged","In Repair"];
		for(var i = 0;i<=2;i++){
			var opt = document.createElement('option');
			opt.value = value[i];
			opt.innerHTML = value[i];
			select.appendChild(opt);
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

    // function validate()
    // {
        // if (confirm("Are you sure!! Delete this record?") == true) 
		// {
		   // return true ;
		// } 
		// else 
		// {
			// return false;
        // }		 
    // }
      
	// function checked()
	// {
		// if (confirm("Are you sure! you checked this record!!") == true) 
		// {
			// return true ;
		// } 
		// else 
		// {
			// return false;
		// } 
	// }
      
 </script>
	
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
						<div class="right-wrapper pull-right"></div>
					</header>


		<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/product_report_pdf'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
									<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
											<option value="serial" label="Serial No" selected style="width:110px;">Serial No</option>
											<option value="category" label="Product Category">Product Category</option>
											<option value="product" label="Product Name">Product Name</option>
											<option value="location" label="Location">Location</option>
											<!--<option value="serial" label="Serial No">Serial No</option>-->
											<option value="user" label="Updated By">Updated By</option>
											<option value="ip_addr" label="IP Address">IP Address</option>
											<option value="monitor" label="All Monitor">All Monitor</option>
											<option value="product_sts" label="Product Status">Product Status</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Value:  <span class="required">*</span></span>										
									<div id="conboDiv" style="display:none;" >
										<select name="searchVal" id="searchVal" class="form-control">
											<option value="">---select---</option>
										</select>
									</div>
									<div id="inputDiv" style="" >
										<input type="text" class="form-control" id="searchInput" name="searchInput" autofocus />
									</div>
								</div><br/>										
							</div>

							<div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="pdf" >
									<label for="radioExample3">PDF</label>
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
								</div>													
							</div>
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

	</section>