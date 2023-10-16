<script>
	function chkDate()
		{
			var fdate = document.getElementById("from_date").value;
			var tdate = document.getElementById("to_date").value;
			if(fdate==tdate)
			{
				return true;
			}
			else if(fdate < tdate)
			{
				return true;
			}
			else if(fdate > tdate)
			{
				alert("Wrong combination of date !");
				return false;
			}
		}
		
			
	function getBLlist(rot_no) 
	{	
		document.getElementById("imp_rot").value=rot_no;
		let rot = rot_no.replace("/", "_");	
		//alert(rot);
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
		xmlhttp.onreadystatechange=stateCheckBLlist;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getBLlist')?>?rot_no="+rot,false);
		xmlhttp.send();	
		return xmlhttp.onreadystatechange();		
	}

	function stateCheckBLlist()
	{
		//alert("ddfd");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{				
			var selectList=document.getElementById("bL");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);			
			document.getElementById("rl_no").value ="";
			document.getElementById("rl_dt").value="";
			document.getElementById("handover_id").value="";


			for (var i = 0; i < jsonData.length; i++) 
			{
				//var  sl = jsonData[i].sl;
				var option = document.createElement('option');
				option.value = jsonData[i].bl_no;  //value of option in backend
				option.text = jsonData[i].bl_no;	  //text of option in frontend
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
	
	
	function getRLinfo(bl)
	{		
		// alert(yard);
		let rot_no=document.getElementById("imp_rot").value;
		let rot = rot_no.replace("/", "_");	
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeRLInfo;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getRLinfo')?>?bl="+bl+"&rot_no="+rot,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeRLInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			//alert(jsonData.length);
			document.getElementById("rl_no").value ="";
			document.getElementById("rl_dt").value="";
			document.getElementById("handover_id").value="";
			document.getElementById("bl_typ").value="";

			document.getElementById("rl_no").value = jsonData[0].rl_no;  
			document.getElementById("rl_dt").value = jsonData[0].rl_date;	   
			document.getElementById("handover_id").value = jsonData[0].id;	   
			document.getElementById("bl_typ").value = jsonData[0].bl_type;	   
									
		}
	}  	
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<!-- start: Table -->
	<div class="row">
		<div class="col-lg-12">	
			<section class="panel">
				
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST"
						action="<?php echo site_url('Report/AuctionHandOverListForYardUnitView') ?>" onsubmit="return chkDate();">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php  if(isset($msg)) {echo $msg;} ?>
								</div>
							</div>
							<div class="col-md-6 col-md-offset-3">
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="from_date" id="from_date" class="form-control" 
										value="" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="to_date" id="to_date" class="form-control" 
										value="" required>
								</div>
							</div>						
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>	
	<!-- end: Table -->
</section>
</div>