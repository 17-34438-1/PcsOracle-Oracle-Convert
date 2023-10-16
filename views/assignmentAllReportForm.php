 <script>
	// MIS start
	function validate2()
	{
		if(document.misassignment.date.value == "")
		{
			alert( "Please provide date!" );
			document.misassignment.date.focus() ;
			return false;
		}
		else if(document.misassignment.terminal.value == "")
		{
			alert( "Please provide terminal!" );
			document.misassignment.terminal.focus() ;
			return false;
		}
	}
	
	function changeterminal(terminal)
	{
		if( document.misassignment.date.value == "" )
		{
			alert( "Please provide date then select!" );
			document.misassignment.date.focus() ;
			return false;
		}
		else
		{	
			if(terminal=="")
			{
				yard.disabled=true;
				assigntype.disabled=true;
			}
			else if(terminal=="CCT" || terminal=="NCT")
			{
				yard.disabled=true;
				assigntype.disabled=false;
				getAssignment();
			}
			else if(terminal=="GCB")
			{
				yard.disabled=false;
				assigntype.disabled=false;				
				getAssignment();
				getBlock2();
			}
		}
	}
	
	function getAssignment()
	{
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var assignDt = document.misassignment.date.value;
		var terminal=document.getElementById("terminal").value;
		var url = "<?php echo site_url('AjaxController/getAssignmentType')?>?terminal="+terminal+"&assignDt="+assignDt;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeAssignment;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeAssignment()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			//alert(val);
			var selectList=document.getElementById("assigntype");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
		//	alert(jsonData[0].mfdch_value);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mfdch_value;  //value of option in backend
				option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	function getBlock2()
	{
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var terminal=document.getElementById("terminal").value;
		xmlhttp.onreadystatechange=stateChangeLoadBlock;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/loadBlock')?>?terminal="+terminal,false);
					
		xmlhttp.send();
	}
	
	function stateChangeLoadBlock()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			
			var selectList=document.getElementById("yard");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].Block_No;  //value of option in backend
				option.text = jsonData[i].Block_No;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	function onblockchange()
	{
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var assignDt = document.misassignment.date.value;
	//	var terminal=document.getElementById("terminal").value;
		var terminal=document.misassignment.terminal.value;
	//	var yard=document.getElementById("yard").value;
		var yard=document.misassignment.yard.value;
		var url = "<?php echo site_url('AjaxController/onblockchange')?>?terminal="+terminal+"&assignDt="+assignDt+"&yard="+yard;
	
		xmlhttp.onreadystatechange=stateChangeBlock;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeBlock()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			//alert(val);
			var selectList=document.getElementById("assigntype");
			removeOptions2(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mfdch_value;  //value of option in backend
				option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	function removeOptions2(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			selectbox.remove(i);
		}
	}
	//MIS end
	
	
    function getBlock(yard)
	{		
		// alert(yard);
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeYardInfo;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getBlock')?>?yard="+yard,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeYardInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		    // alert(val);
			
			var selectList=document.getElementById("block");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			alert(jsonData.length);
			for (var i = 0; i < jsonData.length; i++) 
			{
				// alert(jsonData[i].block);
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
 
 /*
  function blockList() {
   
    $.getJSON('<?php echo site_url('AjaxController/getBlock')?>?yard='+yard, {yardName:$('#yard_no').val()}, function(data) {

        var select = $('#block');
        var options = select.prop('options');
        $('option', select).remove();

        $.each(data, function(index, array) {
            options[options.length] = new Option(array['variety']);
        });

    });

}

$(document).ready(function() {
	
	blockList();
	$('#yard_no').change(function() {
		blockList();
	});
	
});
 
 
 */
 
	function rtnFuncAss(val)
		{
			//alert(val);
			if(val=="assign1")
			{
				document.getElementById("fromdate1").disabled=false;
				document.getElementById("todate1").disabled=true;
				document.getElementById("todate1").disabled=true;
				document.getElementById("yard_no1").disabled=false;
				document.getElementById("container1").disabled=true;
			}		
			else if(val=="deli1")
			{
				document.getElementById("fromdate1").disabled=false;
				document.getElementById("todate1").disabled=false;
				document.getElementById("yard_no1").disabled=false;
				document.getElementById("container1").disabled=true;
			}
			else if(val=="cont1")
			{
				document.getElementById("fromdate1").disabled=true;
				document.getElementById("todate1").disabled=true;
				document.getElementById("yard_no1").disabled=true;
				document.getElementById("container1").disabled=false;
			}
			else
			{
				document.getElementById("fromdate1").disabled=false;
				document.getElementById("todate1").disabled=false;
				document.getElementById("yard_no1").disabled=true;
				document.getElementById("container1").disabled=true;
			}
		}
 
 
	function rtnFunc(val)
	{
		// alert(val);
		if(val=="assign")
		{
			// alert(val);
			document.getElementById("fromdate").disabled=false;
			document.getElementById("todate").disabled=true;
			document.getElementById("yard_no").disabled=false;
			document.getElementById("block").disabled=false;
			document.getElementById("container").disabled=true;
		}		
		else if(val=="deli")
		{
			document.getElementById("fromdate").disabled=false;
			document.getElementById("todate").disabled=false;
			document.getElementById("yard_no").disabled=false;
			document.getElementById("block").disabled=false;
			document.getElementById("container").disabled=true;

		}
		else if(val=="cont")
		{
			document.getElementById("fromdate").disabled=true;
			document.getElementById("todate").disabled=true;
			document.getElementById("yard_no").disabled=true;
			document.getElementById("block").disabled=true;
			document.getElementById("container").disabled=false;		
		}
		else
		{
			document.getElementById("fromdate").disabled=false;
			document.getElementById("todate").disabled=false;
			document.getElementById("yard_no").disabled=true;
			document.getElementById("block").disabled=true;			
			document.getElementById("container").disabled=true;
		}
	}
	
	function validation()
	{
		if( document.cont_search.assignment.value == "" )
		{
			alert( "Please provide assignment date!" );
			document.cont_search.assignment.focus() ;
			return false;
		}
				
		if( document.cont_search.ddl_imp_cont_no.value == "" )
		{
			alert( "Please provide container!" );
			document.cont_search.ddl_imp_cont_no.focus() ;
			return false;
		}
		return true ;
	}
	
	//Head Delivery Register Report start
	function updatetable(regblock)
	{
	//	alert(regblock);
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var date=document.getElementById("regdate").value;
		
		var url = "<?php echo site_url('AjaxController/updatetable')?>?date="+date+"&regblock="+regblock;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeTable;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeTable()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			
			var selectList=document.getElementById("regassigntype");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mfdch_value;  //value of option in backend
				option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	//----------------
	function chkblock()
	{
		if(document.appraisementRegister.regterminal.value == "")
		{
			alert( "Please provide terminal!" );
			document.appraisementRegister.regterminal.focus() ;
			return false;
		}
		else if(document.appraisementRegister.regterminal.value=="GCB" && document.appraisementRegister.regblock.value == "")
		{
			alert( "Please provide block!" );
			document.appraisementRegister.regblock.focus() ;
			return false;
		}
		return true;
	}
	
	function fetchterminal(regterminal)
	{
		if(regterminal=="")
		{
			regblock.disabled=true;
			regassigntype.disabled=true;
		}
		else if(regterminal=="CCT" || regterminal=="NCT")
		{
			regblock.disabled=true;
			regassigntype.disabled=false;
			getAssignment2();
		}
		else if(regterminal=="GCB")
		{
			regblock.disabled=false;
			regassigntype.disabled=false;				
			getAssignment2();
			getBlock3();
		}
	}
	
	function getAssignment2()
	{
	//	alert(terminal);
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var assignDt = document.appraisementRegister.regdate.value;
		var terminal=document.appraisementRegister.regterminal.value;
	
		var url = "<?php echo site_url('AjaxController/getAssignmentType')?>?terminal="+terminal+"&assignDt="+assignDt;
	//	alert(url);
		xmlhttp.onreadystatechange=stateChangeAssignment2;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeAssignment2()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			//alert(val);
			var selectList=document.getElementById("regassigntype");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mfdch_value;  //value of option in backend
				option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	function getBlock3()
	{
	//	alert("ok");
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		var terminal=document.appraisementRegister.regterminal.value;
	//	alert(terminal);
		xmlhttp.onreadystatechange=stateChangeLoadBlock3;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/loadBlock')?>?terminal="+terminal,false);
					
		xmlhttp.send();
	}
	
	function stateChangeLoadBlock3()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			
			var selectList=document.getElementById("regblock");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].Block_No;  //value of option in backend
				option.text = jsonData[i].Block_No;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	
	function onblockchange2()
	{
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var assignDt = document.appraisementRegister.regdate.value;
		var terminal=document.appraisementRegister.regterminal.value;
		var yard=document.appraisementRegister.regblock.value;
		
		var url = "<?php echo site_url('AjaxController/onblockchange')?>?terminal="+terminal+"&assignDt="+assignDt+"&yard="+yard;
	//	alert(url);
		xmlhttp.onreadystatechange=stateChangeBlock2;
		xmlhttp.open("GET",url,false);
					
		xmlhttp.send();
	}
	
	function stateChangeBlock2()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			//alert(val);
			var selectList=document.getElementById("regassigntype");
			removeOptions(selectList);
			
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mfdch_value;  //value of option in backend
				option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
	//Head Delivery Register Report end
  </script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		
		<!--  Links Start -->
		
		<div class="col-lg-6">
			
		</div>


		<div class="col-lg-6 mb-md">
			<?php $path= 'http://'.$_SERVER['SERVER_ADDR'].'/pcs/assets/download/';?>

			<label class="col-md-1 control-label">&nbsp;</label>
			<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success col-md-5"><a target="_blank" style="color:white; text-decoration:none;" href="<?php echo $path.'How_to_download_after_delivery_all_Assignment_converted.pdf';?>">Delivery All Assignment</a></button>
			<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success col-md-5"><a target="_blank" style="color:white;text-decoration:none;" href="<?php echo $path.'How_to_update_Appriasment_in_CTMS_system.pdf';?>">Update Appriasment</a></button>
		</div>

		<!--  Links End -->

		<!-- 1st Row Start  -->

		<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignmentAllReportFormView'; ?>" target="_blank" id="myform">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">	
								<div class="input-group mb-md">
									ASSIGNMENT/DELIVERY EMPTY DETAILS
									<input type="hidden" name="submit" id="submit" value="2">
								</div>
								<div class="input-group mb-md">
									<div class="col-md-offset-3 col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="assign1" onclick="rtnFuncAss(this.value)" checked>
											<label for="radioExample3">Assignment</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="assigne1" onclick="rtnFuncAss(this.value)">
											<label for="radioExample3">Assignment(E)</label>
										</div>
									</div>
									<div class="col-md-offset-3 col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="deli1" onclick="rtnFuncAss(this.value)">
											<label for="radioExample3">Delivery</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="cont1" onclick="rtnFuncAss(this.value)">
											<label for="radioExample3">Container</label>
										</div>
									</div>
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="fromdate1" id="fromdate1" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="todate1" id="todate1" class="form-control" value="<?php echo date("Y-m-d"); ?>" disabled>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
									<select name="yard_no1" id="yard_no1" class="form-control" > <!--onchange="getBlock(this.value)" -->
										<option value="all">yard_no</option>
										<option value="CCT">CCT</option>
										<option value="NCT">NCT</option>
										<option value="GCB">GCB</option>
										<option value="SCY">SCY</option>
										<option value="OFY2">OFY2</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="container1" id="container1" class="form-control" placeholder="Container No" disabled>
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="pdf" >
									<label for="radioExample3">PDF</label>
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
									<?php 
										if(isset($msg1)){ echo $msg1;}
									?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>

		<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignmentAllReportFormView'; ?>" target="_blank" id="myform">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg2)){ echo $msg2;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-9">	
								<div class="input-group mb-md">
									YARDWISE ASSIGNMENT/DELIVERY EMPTY DETAILS
									<input type="hidden" name="submit" id="submit" value="1">
								</div>
								<div class="input-group mb-md">
									<div class="col-md-offset-3 col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="assign" onclick="rtnFunc(this.value)" checked>
											<label for="radioExample3">Assignment</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="assigne" onclick="rtnFunc(this.value)">
											<label for="radioExample3">Assignment(E)</label>
										</div>
									</div>
									<div class="col-md-offset-3 col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="deli" onclick="rtnFunc(this.value)">
											<label for="radioExample3">Delivery</label>
										</div>
									</div>
									<div class="col-md-4">
										<div class="radio-custom radio-success">
											<input type="radio" id="options1" name="options1" value="cont" onclick="rtnFunc(this.value)">
											<label for="radioExample3">Container</label>
										</div>
									</div>
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="todate" id="todate" class="form-control" value="<?php echo date("Y-m-d"); ?>" disabled>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
									<select name="yard_no" id="yard_no" onchange="getBlock(this.value)" class="form-control">
											<option value="">yard_no</option>
											<option value="CCT">CCT</option>
											<option value="NCT">NCT</option>
											<option value="GCB">GCB</option>
											<option value="SCY">SCY</option>
											<option value="OFY2">OFY2</option>
										</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Block <span class="required">*</span></span>
									<select name="block" id="block" class="form-control">
										<option value="">--Select--</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="container" id="container" class="form-control" placeholder="Container No">
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
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

		<!-- 1st Row End  -->
		
		<!-- 2nd Row Start  -->

		<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/appraisementRegisterPerform'; ?>" target="_blank" id="appraisementRegister" name="appraisementRegister" onsubmit="return chkblock();">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg4)){ echo $msg4;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">	
								<div class="input-group mb-md">
									HEAD DELIVERY REGISTER REPORT
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>
									<input type="date" name="regdate" id="regdate" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Terminal <span class="required">*</span></span>
									<select name="regterminal" id="regterminal" onchange="fetchterminal(this.value);" class="form-control">
										<option value="">--Select--</option>
										<option value="CCT">CCT</option>
										<option value="NCT">NCT</option>
										<option value="GCB">GCB</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Block <span class="required">*</span></span>
									<select name="regblock" id="regblock" class="form-control" disabled>
										<option value="ALLBLOCK">--All Block--</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Assignment Type <span class="required">*</span></span>
									<select name="regassigntype" id="regassigntype" class="form-control" disabled>
										<option value="ALLASSIGN">--All Assignment--</option>
									</select>
								</div>												
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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

	  	<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/misAssignmentPerform'; ?>" target="_blank" id="misassignment" name="misassignment" onsubmit="return(validate2());">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg5)){ echo $msg5;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">	
								<div class="input-group mb-md">
									MIS ASSIGNMENT
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width"> Date <span class="required">*</span></span>
									<input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Terminal <span class="required">*</span></span>
									<select name="terminal" id="terminal" onchange="changeterminal(this.value);" class="form-control">
											<option value="">--Select--</option>
											<option value="CCT">CCT</option>
											<option value="NCT">NCT</option>
											<option value="GCB">GCB</option>
											<option value="SCY">SCY</option>
											<option value="OFY">OFY</option>
										</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard/Block <span class="required">*</span></span>
									<select name="yard" id="yard" class="form-control" disabled>
										<option value="ALLBLOCK">--All Block--</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Assignment Type <span class="required">*</span></span>
									<select name="assigntype" id="assigntype" class="form-control" disabled>
										<option value="ALLASSIGN">--All Assignment--</option>
									</select>
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="option" name="option" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="option" name="option" value="pdf" >
									<label for="radioExample3">PDF</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
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

		<!-- 2nd Row End  -->

		<!-- 3rd Row Start  -->

	  	<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/containerSearchResult'; ?>" target="_blank" id="cont_search" name="cont_search" onsubmit="return validation()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg6)){ echo $msg6;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">	
								<div class="input-group mb-md">
									CONTAINER SEARCH
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
									<input type="date" name="assignment" id="assignment" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_cont_no" id="ddl_imp_cont_no" class="form-control" placeholder="Container No">
								</div>												
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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

	  	<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/appraiseReSlotLocList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg7)){ echo $msg7;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">	
								<div class="input-group mb-md">
									APPRAISE RE-SLOT LOCATION
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Date <span class="required">*</span></span>
									<input type="date" name="fromdate3" id="fromdate3" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Containers <span class="required">*</span></span>
									<textarea name="contList" id="contList" class="form-control" placeholder="Containers"></textarea>
								</div>												
							</div>

							<div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="pdf" >
									<label for="radioExample3">PDF</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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
				
		<!-- 3rd Row End  -->

		<!-- 4th Row Start  -->

	 	 <div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/assignmentAllReportFormView'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg3)){ echo $msg3;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-9">	
								<div class="input-group mb-md">
									ASSIGNMENT/DELIVERY EMPTY SUMMARY REPORT
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
									<input type="date" name="fromdate3" id="fromdate3" class="form-control" 
										value="<?php echo date("Y-m-d"); ?>" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
									<select name="yard3" id="yard3" class="form-control" required>
										<option value="" label="yard" selected style="width:130px;">Select</option>
										<option value="CCT" label="CCT" >CCT</option>
										<option value="NCT" label="NCT" >NCT</option>
										<option value="GCB" label="GCB">GCB</option>
										<option value="SCY" label="SCY">SCY</option>
										<option value="OFY2" label="OFY2">OFY2</option>
									</select>
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options3" name="options3" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options3" name="options3" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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

		<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/listOfNotStrippedContainerView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php 
										if(isset($msg8)){ echo $msg8;}
									?>
								</div>
							</div>

							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-9">
								<div class="input-group mb-md">
									LIST OF NOT STRIPPED ASSIGNMENT DELIVERY CONTAINERS
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Date <span class="required">*</span></span>
									<input type="date" name="strDt" id="strDt" class="form-control" value="<?php echo date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
									<select name="yard_no1" id="yard_no1" class="form-control">
										<option value="" label="yard_no" selected style="width:130px;">Select</option>
											<option value="CCT" label="CCT">CCT</option>
											<option value="NCT" label="NCT">NCT</option>
											<option value="GCB" label="GCB">GCB</option>
											<option value="SCY" label="SCY">SCY</option>
											<option value="OFY2" label="OFY2">OFY2</option>
									</select>
								</div>												
							</div>

							<div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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

		<!-- 4th Row End  -->

	</div>

</section>
