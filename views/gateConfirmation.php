<script language="JavaScript">
	function validate()
	{
		if(document.getElementById("dlv_date").value=="")
		{
			alert( "Please provide date!" );
			document.getElementById("dlv_date").focus();
			 event.preventDefault();
		}
		else if(document.getElementById("gate_no").value=="")
		{
			alert( "Please select gate no!" );
			document.getElementById("gate_no").focus();
			 event.preventDefault();
		}
		else if(document.getElementById("tro").value=="")
		{
			alert( "Please select truck no!" );
			document.getElementById("tro").focus();
			 event.preventDefault();
		}
		else
		{
			return true;
		}				
	}
	
	function getvrfyno(verify_num)
	{
		document.getElementById("gate_pass_no").value="";
		document.getElementById("reg_no").value="";
		document.getElementById("marks").value="";
		document.getElementById("vessel_name").value="";
		document.getElementById("des_goods").value="";
		document.getElementById("mlo_line").value="";
		document.getElementById("quantity").value="";
		document.getElementById("mlo_code").value="";
		document.getElementById("unit").value="";
		document.getElementById("ffw_line").value="";
		document.getElementById("cnf").value="";
		document.getElementById("ffw_code").value="";
		document.getElementById("be_no").value="";
		document.getElementById("importer_name").value="";
		document.getElementById("be_date").value="";
		document.getElementById("gate_no").value="";
		document.getElementById("dlv_date").value="";
		document.getElementById("tro").value="";
		document.getElementById("dlv_qty").value="";
		document.getElementById("notifyName").value="";
		document.getElementById("notifyAddress").value="";
		var r = document.getElementById("verify_num").value;
	
		document.getElementById("verify_number").value=r;
		document.getElementById("verifyNo").value=r;
		
		if(document.getElementById("verify_num").value=="")
		{
			alert("Please! Enter Verify No.");
			document.getElementById("verify_num").focus();
		}
		else
		{
			var verify_num=document.getElementById("verify_num").value.toUpperCase();
			
			getTruck(verify_num);	//call for truck no

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
			var url = "<?php echo site_url('AjaxController/getDeliveryByVerifyInfo')?>?verify_num="+verify_num;
		//	alert(url);
			xmlhttp.onreadystatechange=stateChangegetBLInfo;
			xmlhttp.open("GET",url,false);	
			
			xmlhttp.send();
		}
	}

	function stateChangegetBLInfo()
	{		//alert("stateChangegetBLInfo");	
		if (xmlhttp.readyState==4 && xmlhttp.status==200) //???
		{			
			var val = xmlhttp.responseText;
			
			var jsonData = JSON.parse(val);
			if(jsonData.length==0)
			{
				alert("No Data Found !");
			}
			for (var i = 0; i < jsonData.length; i++) 
		    {
				document.getElementById("reg_no").value=jsonData[i].Import_Rotation_No;
				document.getElementById("marks").value=jsonData[i].Pack_Marks_Number; 
				document.getElementById("vessel_name").value=jsonData[i].Vessel_Name;
				document.getElementById("des_goods").value=jsonData[i].Description_of_Goods;
				document.getElementById("quantity").value=jsonData[i].Pack_Number;
				document.getElementById("mlo_line").value=jsonData[i].mloline;
				document.getElementById("mlo_code").value=jsonData[i].mlocode;
				document.getElementById("unit").value=jsonData[i].Pack_Description;
				document.getElementById("ffw_line").value=jsonData[i].ffwline;
				document.getElementById("cnf").value=jsonData[i].cnf_name;
				document.getElementById("importer_name").value=jsonData[i].Consignee_name;
				document.getElementById("be_no").value=jsonData[i].be_no; 
				document.getElementById("be_date").value=jsonData[i].be_date; 
				document.getElementById("dlv_qty").value=jsonData[i].bal_pack; 
				document.getElementById("notifyName").value=jsonData[i].Notify_name; 
				document.getElementById("notifyAddress").value=jsonData[i].Notify_address; 
			}			
		}
	}
	
	function getTruck(val){
		//alert(val);
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
		var url = "<?php echo site_url('AjaxController/getTruck')?>?verify_num="+val;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeSection;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function stateChangeSection()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var selectList=document.getElementById("tro");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].truck_id;  //value of option in backend
				option.text = jsonData[i].truck_id;	  //text of option in frontend
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
	
	function getbalance(tro)
	{
		var verify_num=document.getElementById("verify_num").value;
	
		if (window.XMLHttpRequest) 
		{
		//	alert("ok");
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	//	var url = "<?php echo site_url('ajaxController/getbalance')?>?truck_id="+tro+"&verify_num="+verify_num;
		var url = "<?php echo site_url('AjaxController/getbalance')?>?truck_id="+tro;
		// alert(url);
		xmlhttp.onreadystatechange=stateChangeBalance;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function stateChangeBalance()
	{			
		// alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) //???
		{			
			var val = xmlhttp.responseText;
			
			
			var jsonData = JSON.parse(val);
			// alert(jsonData);
		//	console.log(jsonData);
			//alert(jsonData[0].rtndelvPack);
			//alert("alert");
			var rcv=document.getElementById("quantity").value;
		//	document.getElementById("dlv_qty").value=parseFloat(rcv)-parseFloat(jsonData[0].rtndelvPack.delv_pack);
		//	document.getElementById("gate_no").value=jsonData[0].rtndelv.gate_no;
			document.getElementById("gate_no").value=jsonData[0].gate_no;
		//	alert("alert");
			document.getElementById("troCart").value=jsonData[0].truck_id;
			document.getElementById("dlv_qty").value=jsonData[0].delv_pack;
			//alert("after select "+document.getElementById("tro").value);
		}
	}

	$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
        console.log($next.length);
        if (!$next.length) {
            //$next = $('[tabIndex=1]');
	form.submit();
        }
	else
        $next.focus();
    }
});
</script>
<style>
input {   
    width: 200px;
}

input:focus {
    background-color: #F3F781;
}

select:focus {
    background-color: #F3F781;
}

 table {border-collapse: collapse;}
			 .left{
					width:800px;
					float:left;										
					font-size: 10px;
					color:black;
				}
				.middle{
					margin-left:0px;
					width:230px;
					float:left;
					height:100%;
					font-size: 10px;
					color:black;
				}
				.right{
					margin-left:20px;
					font-size: 10px;
					color:black;
				}
				.left_container_fieldSet{
					
					float:left;
					height:100%;
					font-size: 10px;
					color:black;
				}
				.right_container_fieldSet{
					margin-left:10px;
					font-size: 10px;
					color:black;
				}
				.left_tbl_div{
					float:left;
					height:100%;
					font-size: 10px;
					color:black;
				}
				.right_tbl_div{
					float:right;
					font-size: 10px;
					color:black;
				}
	.ui-datepicker {
				   background: #DF7A3C;
				   border: 1px solid #555;
				   color: black;
				 }
				
				.input_field
				{
					width:200px;
					background-color : #F7EEC0;
				}

		</style>
<section role="main" class="content-body" style="margin-top:0px;">
			<header class="page-header">
				<h2><?php echo $title;?></h2>
			
				<div class="right-wrapper pull-right">
					
				</div>
			</header>
			<section class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $msg;?>
								</div>
							</div>
							
							<div class="invoice">
								<form name="myForm" id="myForm" onsubmit="return validate();" action="<?php echo site_url("GateController/gateConfirmationPerform");?>" method="post"  enctype="multipart/form-data">
								<header class="clearfix">
									<div class="row">
										<div class="col-sm-offset-1 col-sm-4 mt-md">
											<h5 class="h5 mt-none mb-sm text-dark text-bold">
												VERIFY NO. <input type="text" id="verify_num" name="verify_num" autofocus 
															style="height:20px;border-radius:5px;" value="<?php echo $verify_num;?>" 
															onblur="return getvrfyno(this.value)" tabindex="1"/>
											</h5>
										</div>
										<div class="col-sm-5 mt-md">
											<h5 class="h5 mt-none mb-sm text-dark text-bold">
												GATE PASS NO:  <input type="text" id="gate_pass_no" name="gate_pass_no" 
																style="height:20px;border-radius:5px;">
											</h5>
										</div>
									</div>
								</header>
								<div class="row">
									<div class="col-md-offset-5 col-sm-3 mt-md">
										<h5 class="h5 mt-none mb-sm text-dark"><b><u>INFORMATION</u></b></h5>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-5 mt-md" align="right">
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>REGISTRATION NO : </b>
											<input class="input_field" type="text" id="reg_no" name="reg_no" 
											style="height:20px;border-radius:5px;" value="<?php echo $reg_no;?>" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>VESSEL NAME : </b>
											<input class="input_field" type="text" id="vessel_name" name="vessel_name" 
											value="<?php echo $vessel_name;?>" style="height:20px;border-radius:5px;" readonly />
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>MLO LINE :</b>
											<input class="input_field" type="text" id="mlo_line" name="mlo_line" 
											value="<?php echo $mlo_line;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>MLO CODE :</b>
											<input class="input_field" type="text" id="mlo_code" name="mlo_code" value="<?php echo $mlo_code;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>FFW LINE :</b>
											<input class="input_field" type="text" id="ffw_line"  name="ffw_line" value="<?php echo $ffw_code;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>FFW CODE :</b>
											<input class="input_field" type="text" id="ffw_code"  name="ffw_code" 
											style="height:20px;border-radius:5px;" readonly>
										</h6>									
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>IMPORTER NAME :</b>
											<input class="input_field" type="text" id="importer_name"  name="importer_name" 
											value="<?php echo $importer_name;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>									
									</div>
									<div class="col-sm-5 mt-md" align="right">
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>MARKS : </b>
											<input class="input_field" type="text" id="marks" name="marks" 
											style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>DESCRIPTION OF GOODS :  </b>
											<input class="input_field" type="text" id="des_goods" name="des_goods" value="<?php echo $des_goods;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>QUANTITY :</b>
											<input class="input_field" type="text" id="quantity" name="quantity" value="<?php echo $quantity;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>UNIT :</b>
											<input class="input_field" type="text" id="unit" name="unit" value="<?php echo $unit;?>" 
											style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>C&F AGENT :</b>
											<input class="input_field" type="text" id="cnf" name="cnf" value="<?php echo $cnf;?>" 
											style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>BE NO :</b>
											<input class="input_field" type="text" id="be_no"  name="be_no" value="<?php echo $be_no;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>BE DATE :</b>
											<input class="input_field" type="text" id="be_date"  name="be_date" value="<?php echo $be_date;?>" style="height:20px;border-radius:5px;" readonly>
										</h6>										
									</div>
								</div>
								<div class="row">
									<div class="col-md-offset-5 col-sm-3 mt-md">
										<h5 class="h5 mt-none mb-sm text-dark"><b><u>ENTRY PART</u></b></h5>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-5 mt-md" align="right">
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>GATE NO :</b>
											<input class="input" type="text" id="gate_no" name="gate_no" 
											style="height:20px;border-radius:5px;">
										</h6>									
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>TRUCK :</b>
											<select id="tro" name="tro" onchange="getbalance(this.value)" tabindex="2" 
												style="height:40px;width:200px;">
												<option value=""><b>--Select--</b></option>
											</select>
										</h6>									
									</div>
									<div class="col-sm-5 mt-md" align="right">
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>DLV DT :</b>
											<input class="input" type="date" id="dlv_date" name="dlv_date" tabindex="3" 
											style="height:40px;border-radius:5px;">
										</h6>
										<h6 class="h6 mt-none mb-sm text-dark">
											<b>DLV BALANCE QTY :</b>
											<input type="text" id="dlv_qty" name="dlv_qty" style="height:20px;border-radius:5px;" readonly>
										</h6>										
									</div>
								</div>								
								<div class="col-md-offset-3 col-md-1">
									<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success" style="width:100px;">
										Save
									</button>
								</div>
								</form>
								<div class="col-md-offset-1 col-md-1 text-center">
									<form action="<?php echo site_url('Report/cartTicketPdf');?>" method="POST" target="_blank">
										<input type="hidden" name="verify_number" id="verify_number" >
										<!--input class="mb-xs mt-xs mr-xs btn btn-primary" type="submit" style="width : 100px" value="View" name="btnCartView"/-->
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary" style="width:100px;" 
											name="btnCartView">
											View
										</button>
									</form>
								</div>
								<div class="col-md-offset-1 col-md-1" >
									<form action="<?php echo site_url('GateController/chalan');?>" method="POST" target="_blank">
										<input type="hidden" name="stat" id="stat" value=1>
										<input type="hidden" name="verifyNo" id="verifyNo" >
										<input type="hidden" name="troCart" id="troCart" value="">
										<input type="hidden" name="notifyName" id="notifyName" value="">
										<input type="hidden" name="notifyAddress" id="notifyAddress" value="">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-warning" name="btnCartView">
											Invoice/Challan
										</button>
									</form>
								</div>
							</div>
						</div>
					</section>
		</section>
				<!--section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
							
						</div>
					</header>


				<div class="row">
				<div class="col-lg-12">	
					
					<section class="panel">
				<div class="panel-body" align="center">
				<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("GateController/gateConfirmationPerform");?>" method="post"  enctype="multipart/form-data">
					<div class="form-group">
					<table style="border:solid 1px #ccc;" width="100%" align="center" cellspacing="0" cellpadding="0">
						<tr>
							<td style="color:black;">VERIFY NO :<em>&nbsp;</em></td>
							<td>
								<input type="text" id="verify_num" name="verify_num" autofocus value="<?php echo $verify_num;?>" 
									onblur="return getvrfyno(this.value)" tabindex="1"/>
							</td>
							
							<td style="color:black;">GATE PASS NO:<em>&nbsp;</em></td>
							<td align="right">
								<input type="text" id="gate_pass_no" name="gate_pass_no">
							</td>
						</tr>
					</table>
					</div>
			
				<fieldset>
					
				<label class="col-md-3 control-label">&nbsp;</label>
					<div class="col-md-12">		
					<legend>INFORMATION</legend>
						<table style="border:solid 1px #ccc;" width="100%" align="center" cellspacing="0" cellpadding="0">
							<tr>
								<td align="right"> REGISTRATION NO</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="reg_no" name="reg_no" value="<?php echo $reg_no;?>" readonly></td>
								<td align="right"> MARKS</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="marks" name="marks" readonly></td>
							</tr>
							<tr>
								<td align="right"> VESSEL NAME</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="vessel_name" name="vessel_name" value="<?php echo $vessel_name;?>" readonly /></td>
								<td align="right"> DESCRIPTION OF GOODS</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="des_goods" name="des_goods" value="<?php echo $des_goods;?>" readonly></td>
							</tr>
							<tr>
								<td align="right"> MLO LINE</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="mlo_line" name="mlo_line" value="<?php echo $mlo_line;?>" readonly></td>
								<td align="right"> QUANTITY</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="quantity" name="quantity" value="<?php echo $quantity;?>" readonly></td>
							</tr>
							<tr>
								<td align="right"> MLO CODE</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="mlo_code" name="mlo_code" value="<?php echo $mlo_code;?>" readonly></td>
								<td align="right"> UNIT</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="unit" name="unit" value="<?php echo $unit;?>" readonly></td>
							</tr>
							<tr>
								<td align="right"> FFW LINE</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="ffw_line"  name="ffw_line" value="<?php echo $ffw_code;?>" readonly></td>
								<td align="right">C&F AGENT</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="cnf"  name="cnf" value="<?php echo $cnf;?>" readonly></td>
							</tr>
							<tr>
								<td align="right"> FFW CODE</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="ffw_code"  name="ffw_code" readonly></td>
								<td align="right"> BE NO</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="be_no"  name="be_no" value="<?php echo $be_no;?>" readonly></td>
							</tr>
							<tr>
								<td align="right"> IMPORTER NAME</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="importer_name"  name="importer_name" value="<?php echo $importer_name;?>" readonly></td>
								<td align="right"> BE DATE</td>
								<td>:</td>
								<td><input class="input_field" type="text" id="be_date"  name="be_date" value="<?php echo $be_date;?>" readonly></td>
							</tr>
						</table>
			</fieldset>
			<fieldset>
				<legend>Entry Part</legend>
				<table style="border:solid 1px #ccc;" width="800px" align="center" cellspacing="0" cellpadding="0">
					<tr>
						<td  align="right"> GATE NO</td>
						<td>:</td>
						<td><input class="input" type="text" id="gate_no" name="gate_no"></td>
						<td  align="right"> DLV DT</td>
						<td>:</td>
						<td><input class="input" type="date" id="dlv_date" name="dlv_date" tabindex="3" >
						</td>
					</tr>
					<tr>
						<td align="right"> TRO/TRU#</td>
						<td>:</td>
						<td> <select id="tro" name="tro" onchange="getbalance(this.value)" tabindex="2" >
								<option value="">--Select--</option>
							</select> </td>
						<td  align="right"> DLV BALANCE QTY</td>
						<td>:</td>
						<td><input type="text" id="dlv_qty" name="dlv_qty" readonly></td>
					</tr>
				</table>
			</fieldset>	
			<table>
				<tr>
					<td><input class="login_button" type="submit" style="width : 100px" value="Save"/>
					</form>
					<td>
						<form action="<?php echo site_url('Report/cartTicketPdf');?>" method="POST" target="_blank">
							<input type="hidden" name="verify_number" id="verify_number" >
							<input class="login_button" type="submit" style="width : 100px" value="View" name="btnCartView"/>
						</form>
					</td>
					<td>
						<form action="<?php echo site_url('GateController/chalan');?>" method="POST" target="_blank">
							<input type="hidden" name="stat" id="stat" value=1>
							<input type="hidden" name="verifyNo" id="verifyNo" >
							<input type="hidden" name="troCart" id="troCart" value="">
							<input type="hidden" name="notifyName" id="notifyName" value="">
							<input type="hidden" name="notifyAddress" id="notifyAddress" value="">
							<input class="login_button" type="submit" style="width : 100px" value="Invoice/Challan" name="btnCartView"/>
						</form>
					</td>
				</tr>
				<tr>  <td align="center" colspan="3"> <?php echo $msg?></td>
				</tr>
			</table>
								
							</div>
																					
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
						
									
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
				</section-->
			</div>