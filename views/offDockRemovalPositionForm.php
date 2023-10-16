<script>
function changeterminal(terminal)
	{
		//alert(terminal);
		var yard=document.getElementById("search_yard");
			if(terminal=="CCT" || terminal=="NCT")
			{
				yard.disabled=true;
				//assigntype.disabled=false;
				//getAssignment();
			}
			else if(terminal=="GCB")
			{
				yard.disabled=false;
				//assigntype.disabled=false;				
				//getAssignment();
				getBlock(terminal);
			}
		
	}
	
	function getBlock(yard)
	{		
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeYardInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getBlockCpa')?>?yard="+yard,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeYardInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		    //alert(val);
			
			var selectList=document.getElementById("search_yard");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].block;  //value of option in backend
				option.text = jsonData[i].block;	  //text of option in frontend
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
	function validate()
      {
		  //alert("OK");
		if( document.myform.er_date.value == "" )
         {
            alert( "Please provide Date!" );
            document.myform.er_date.focus() ;
            return false;
         }
		 else{
			 return( true );
		 }
	  }
 </script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="col-lg-12">						
		<section class="panel">
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/offDockRemovalPositionList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
					<div class="form-group">
						<label class="col-md-3 control-label">&nbsp;</label>
						<div class="col-md-6">	
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Date <span class="required">*</span></span>
								<input type="date" name="er_date" id="er_date" class="form-control" value="<?php date("Y-m-d"); ?>">
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Shift <span class="required">*</span></span>
								<select name="search_shift" id="search_shift" class="form-control">
									<option style="width:110px;" value="all">--SELECT--</option>
									<option style="width:110px;" value="A">A</option>
									<option style="width:110px;" value="B">B</option>
									<option style="width:110px;" value="C">C</option>
								</select>
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Search Terminal <span class="required">*</span></span>
								<select name="terminal" id="terminal" class="form-control" onchange="changeterminal(this.value);">
									<option value="">--SELECT--</option>
									<option value="CCT">CCT</option>
									<option value="NCT">NCT</option>
									<option value="GCB">GCB</option>
									<option value="SCY">SCY</option>
								</select>
							</div>
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Search Yard <span class="required">*</span></span>
								<select name="search_yard" id="search_yard" class="form-control">
									<option value="all">--SELECT--</option>
								</select>
							</div>												
						</div>

						<div class="col-md-offset-4 col-md-3">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="xl" checked>
								<label for="radioExample3">Excel</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="radio-custom radio-success">
								<input type="radio" id="options" name="options" value="html" >
								<label for="radioExample3">HTML</label>
							</div>
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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
	
</section>