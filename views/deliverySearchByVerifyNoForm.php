<script language="JavaScript">

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

  function getVerifyInfo(verifyNo)
     { 
	    //document.getElementById("rotNo").value="";
	    //document.getElementById("verifyNo").value="";
		document.getElementById("regNo").value="";
		document.getElementById("mloBLno").value="";   
	    document.getElementById("fBLno").value="";      		
	    document.getElementById("vslNam").value=""; 
	    document.getElementById("unStuffDate").value=""; 
	    document.getElementById("unTallyShitNo").value=""; 	
		document.getElementById("unRecvQty").value="";    
		document.getElementById("unRecvPKQty").value="";   
		document.getElementById("marksNo").value="";
		document.getElementById("description").value="";
		document.getElementById("quantity").value="";
		document.getElementById("pkUnit").value="";
		document.getElementById("grossWeight").value=""
		document.getElementById("netWeight").value="";
		document.getElementById("importerName").value="";
		document.getElementById("apprDate").value="";
		document.getElementById("cfAgentCode").value="";
		document.getElementById("cfAgentName").value="";
		document.getElementById("customBillofEntryNo").value="";
		document.getElementById("customBillofEntryDate").value="";
		document.getElementById("contNo").value="";
		document.getElementById("contSize").value="";
		document.getElementById("contHeight").value="";
		document.getElementById("contType").value="";
        document.getElementById("status").value="";
		document.getElementById("unstuffShedNo").value="";
		document.getElementById("cargoRecLoc").value="";
		document.getElementById("verifyNo1").value="";
		document.getElementById("verifyDate").value="";
		document.getElementById("invoiceValue").value="";
		document.getElementById("custRealiseOrderNum").value="";
		document.getElementById("custRealiseOrderDate").value="";
		document.getElementById("exitNoteNum").value="";
		document.getElementById("exitNoteDate").value="";
		document.getElementById("billNumber").value="";
		document.getElementById("billDate").value="";
		document.getElementById("bankCPno").value="";
		document.getElementById("bankCPdate").value="";
		document.getElementById("portBillStatus").value="";
		document.getElementById("truckNum").value="";
		document.getElementById("assignClerk").value="";
	

		//var rotNo=document.getElementById("rotNo").value;
		
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
		//alert(rotNo);
		xmlhttp.onreadystatechange=stateChangegetVerifyInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getVerifyInfo')?>?verifyNo="+verifyNo,false);	
       	
		xmlhttp.send(); 

   }


	function stateChangegetVerifyInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			var val = xmlhttp.responseText;
		    //alert(val);		
            //var val = xmlhttp.responseText;
			//var strArr = val.split("|");
		    //alert(val);
			
		 var jsonData = JSON.parse(val);
		 for (var i = 0; i < jsonData.length; i++) 
		    {
				document.getElementById("regNo").value=jsonData[i].import_rotation;
				document.getElementById("mloBLno").value=jsonData[i].master_BL_No;  
				document.getElementById("fBLno").value=jsonData[i].BL_No;		
				document.getElementById("vslNam").value=jsonData[i].Vessel_Name;
				document.getElementById("unStuffDate").value=jsonData[i].wr_date;
				document.getElementById("unTallyShitNo").value=jsonData[i].tally_sheet_number;	
				document.getElementById("unRecvQty").value=jsonData[i].un_rcv_qty;    
				document.getElementById("unRecvPKQty").value=jsonData[i].Pack_Description;   
				document.getElementById("marksNo").value=jsonData[i].Pack_Marks_Number;
				document.getElementById("description").value=jsonData[i].Description_of_Goods;
				document.getElementById("quantity").value=jsonData[i].Pack_Number;
				document.getElementById("pkUnit").value=jsonData[i].Pack_Description; 
				document.getElementById("grossWeight").value=jsonData[i].Cont_gross_weight;
				document.getElementById("netWeight").value=jsonData[i].cont_weight;
				var importer = jsonData[i].Notify_name +" "+jsonData[i].Notify_address ; 
				document.getElementById("importerName").value=importer;
				document.getElementById("importerNam").value=jsonData[i].Notify_name;         //for hidden field
				document.getElementById("importerAddress").value=jsonData[i].Notify_address;  //for hidden field
				document.getElementById("apprDate").value=jsonData[i].appraise_date;
				document.getElementById("cfAgentCode").value=jsonData[i].cnf_lic_no;
				document.getElementById("cfAgentName").value=jsonData[i].cnf_name;
				document.getElementById("customBillofEntryNo").value=jsonData[i].be_no;
				document.getElementById("customBillofEntryDate").value=jsonData[i].be_date;
				document.getElementById("contNo").value=jsonData[i].cont_number;
				document.getElementById("contSize").value=jsonData[i].cont_size;
				document.getElementById("contHeight").value=jsonData[i].cont_height;
				document.getElementById("contType").value=jsonData[i].cont_type;
				document.getElementById("status").value=jsonData[i].cont_status;
				document.getElementById("unstuffShedNo").value=jsonData[i].shed_loc;
				document.getElementById("cargoRecLoc").value=jsonData[i].shed_yard;
				
                document.getElementById("verifyNo1").value=jsonData[i].verify_number;
				
				//document.getElementById("verifyNo1").value=jsonData[i].verify_number;
				document.getElementById("verifyDate").value=jsonData[i].verifyDate;
				document.getElementById("invoiceValue").value=jsonData[i].grand_total;
				document.getElementById("custRealiseOrderNum").value=jsonData[i].cus_rel_odr_no;
				document.getElementById("custRealiseOrderDate").value=jsonData[i].cus_rel_odr_date;
				document.getElementById("exitNoteNum").value=jsonData[i].exit_note_number;
				document.getElementById("exitNoteDate").value=jsonData[i].date;
				document.getElementById("billNumber").value=jsonData[i].bill_no;
				document.getElementById("billDate").value=jsonData[i].bill_date;
				document.getElementById("portBillStatus").value=jsonData[i].bill_rcv_stat;
				document.getElementById("shedTallyId").value=jsonData[i].shed_tally_id;
				document.getElementById("truckNum").value=jsonData[i].no_of_truck;
				if(jsonData[i].bill_rcv_stat=="Not Paid" )	
				   {
					    //alert("Not ok");
					    $("#portBillStatus").removeClass("read2");
					    $("#portBillStatus").removeClass("read");
						$('#portBillStatus').addClass('read3');
                   
					//document.getElementById("portBillStatus").classList.remove('read2');
				  }
				  
				  else if(jsonData[i].bill_rcv_stat=="Paid")
					{
						  //alert("ok");
					    $('#portBillStatus').removeClass('read');
						$('#portBillStatus').removeClass('read3');
						$('#portBillStatus').addClass('read2');
                      
						//document.getElementById("portBillStatus").classList.add('read2');
					}
					else
					{
						$('#portBillStatus').removeClass('read3');
						$('#portBillStatus').removeClass('read2');
						$('#portBillStatus').addClass('read');
					}
				
				var cp_no = jsonData[i].cp_no;
				var cp_year = jsonData[i].cp_year;
				var cp_bank_code = jsonData[i].cp_bank_code;
				var cp_unit = jsonData[i].cp_unit;
				var cpLen = cp_no.length;
				var finaCPno = "";
				//alert(cpLen);
				if(cpLen==1)
					finaCPno = cp_bank_code+cp_unit+"/"+cp_year+"-000"+cp_no;
				else if(cpLen==2)
					finaCPno = cp_bank_code+cp_unit+"/"+cp_year+"-00"+cp_no;
				else if(cpLen==3)
					finaCPno = cp_bank_code+cp_unit+"/"+cp_year+"-0"+cp_no;
				else
					finaCPno = cp_bank_code+cp_unit+"/"+cp_year+"-"+cp_no;
				
				document.getElementById("bankCPno").value=finaCPno;
				document.getElementById("bankCPdate").value=jsonData[i].bank_cp_date;
				
				
				//var clerkAssign=(jsonData[i].clerk_assign.trim());
				if(jsonData[i].clerk_assign!=null)
				{ 
					alert("Already! A clerk is assigned.");
					//getClerk();
					/* $("#asClerk").show();
					$("#clerkName").show();
					document.getElementById("clerkName").value =jsonData[i].clerk_assign;*/
					var selectList=document.getElementById("assignClerk");
					removeOptions(selectList);
					var option = document.createElement('option');
					option.value = jsonData[i].clerk_assign;  //value of option in backend
					option.text = jsonData[i].clerk_assign;	  //text of option in frontend
					option.selected = true;
					selectList.appendChild(option);
				    $("#save").attr("disabled",true);
				}
				else
				  {		
					getClerk();
					//$("#asClerk").show();
					
					$("#save").removeAttr("disabled");
					
                  }
			}			
		}
	}
	
	
	//clerk asign
		function getClerk(){
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
		var url = "<?php echo site_url('ajaxController/getClerk')?>?";
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
			var selectList=document.getElementById("assignClerk");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].u_name;  //value of option in backend
				option.text = jsonData[i].u_name;	  //text of option in frontend
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
     if( document.myForm.assignClerk.value == "" )
			{
			 alert( "Please Select a Clerk!" );
			 document.myForm.assignClerk.focus() ;
			 return false;
			}
		else
			return true;
	}
	
</script>
<style>
.read{ background-color : #F7EEC0; }
.read2{ background-color : #45f55f; }
.read3{ background-color : #ff5746; }
</style>
 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
			<form onsubmit="return(validate());" action="<?php echo site_url("report/deliverySearchByVerify");?>" name="myForm" method="post">
				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width" style="color:blue;">VERIFY NO : </span>
							<input type="text" name="verifyNo" id="verifyNo" class="form-control" placeholder="VERIFY NO" onblur="getVerifyInfo(this.value)" tabindex="1">
						</div>
					</div>
				</div>
			 
				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Reg/No : </span>
							<input type="text" name="regNo" id="regNo" class="form-control read" placeholder="Reg/No" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">MLO BL/No : </span>
							<input type="text" name="mloBLno" id="mloBLno" class="form-control read" placeholder="MLO BL/No" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">F BL/No : </span>
							<input type="text" name="fBLno" id="fBLno" class="form-control read" placeholder="F BL/No" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">VSL NAME : </span>
							<input type="text" name="vslNam" id="vslNam" class="form-control read" placeholder="VSL NAME" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Unstuffing Date : </span>
							<input type="text" name="unStuffDate" id="unStuffDate" class="form-control read" placeholder="VSL NAME" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Un Tally Shit No : </span>
							<input type="text" name="unTallyShitNo" id="unTallyShitNo" class="form-control read" placeholder="Un Tally Shit No" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Un Recieve Qty : </span>
							<input type="text" name="unRecvQty" id="unRecvQty" class="form-control read" placeholder="Un Recieve Qty" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Un Recieve Pk Unit : </span>
							<input type="text" name="unRecvPKQty" id="unRecvPKQty" class="form-control read" placeholder="Un Recieve Pk Unit" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Custom Bill of Entry No : </span>
							<input type="text" name="customBillofEntryNo" id="customBillofEntryNo" class="form-control read" placeholder="Custom Bill of Entry No" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Custom Bill of Entry Date : </span>
							<input type="text" name="customBillofEntryDate" id="customBillofEntryDate" class="form-control read" placeholder="Custom Bill of Entry Date" readonly>
						</div>
					</div>
		        </div>
		 
		 
		        <div class="row">
				 	<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Marks_Number : </span>
							<input type="text" name="marksNo" id="marksNo" class="form-control read" placeholder="Marks_Number" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Descrption : </span>
							<input type="text" name="description" id="description" class="form-control read" placeholder="Descrption" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Quantity : </span>
							<input type="text" name="quantity" id="quantity" class="form-control read" placeholder="Quantity" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pk Unit : </span>
							<input type="text" name="pkUnit" id="pkUnit" class="form-control read" placeholder="Pk Unit" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Net Weight : </span>
							<input type="text" name="netWeight" id="netWeight" class="form-control read" placeholder="Net Weight" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Gross Weight : </span>
							<input type="text" name="grossWeight" id="grossWeight" class="form-control read" placeholder="Gross Weight" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Appraisement Date : </span>
							<input type="text" name="apprDate" id="apprDate" class="form-control read" placeholder="Appraisement Date" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">CF Agent Code : </span>
							<input type="text" name="cfAgentCode" id="cfAgentCode" class="form-control read" placeholder="CF Agent Code" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">CF Agent Name Address : </span>
							<input type="text" name="cfAgentName" id="cfAgentName" class="form-control read" placeholder="CF Agent Name Address" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Importer Name Address : </span>
							<input type="text" name="importerName" id="importerName" class="form-control read" placeholder="Importer Name Address" readonly>
							<input type="hidden" name="importerNam" id="importerNam" class="form-control read" placeholder="Importer Name Address" readonly>
							<input type="hidden" name="importerAddress" id="importerAddress" class="form-control read" placeholder="Importer Name Address" readonly>
						</div>
					</div>
		      </div>
           
		  
			<div class="table-responsive">
			    <table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<thead>
						<tr class="gridDark">
							<th><div class="form-group">Container No</div></th>
							<th width="10%">Size</th>
							<th width="10%">Height</th>
							<th width="10%">Type</th>
							<th width="10%">Status</th>
							<th>Unstuffing Shed No</th>
							<th>Cargo Recive Location</th>
						</tr>
					</thead>
					<tbody>
						<tr class="gradeX">
							<td><input type="text" name="contNo" id="contNo" class="form-control read" placeholder="Container No" readonly></td>
							<td><input type="text" name="contSize" id="contSize" class="form-control read" placeholder="Size" readonly></td>
							<td><input type="text" name="contHeight" id="contHeight" class="form-control read" placeholder="Height" readonly></td>
							<td><input type="text" name="contType" id="contType" class="form-control read" placeholder="Type" readonly></td>
							<td><input type="text" name="status" id="status" class="form-control read" placeholder="Status" readonly></td>
							<td><input type="text" name="unstuffShedNo" id="unstuffShedNo" class="form-control read" placeholder="Unstuffing Shed No" readonly></td>
							<td><input type="text" name="cargoRecLoc" id="cargoRecLoc" class="form-control read" placeholder="Cargo Recive Location" readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
           <br/>
		   	<div class="table-responsive">
			    <table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<thead> 
						<tr class="gridDark">
							<th>Verify Number</th>
							<th>Verify Date</th>
							<th>Invoice Value</th>
							<th>Custum Realise </br> Order Number</th>
							<th>Custom Realise </br>Order Date</th>
						</tr>
					</thead>
					<tbody>
						<tr class="gradeX">
							<td><input type="text" name="verifyNo1" id="verifyNo1" class="form-control read" placeholder="Verify Number" readonly></td>
							<td><input type="text" name="verifyDate" id="verifyDate" class="form-control read" placeholder="Verify Date" readonly></td>
							<td><input type="text" name="invoiceValue" id="invoiceValue" class="form-control read" placeholder="Invoice Value" readonly></td>
							<td><input type="text" name="custRealiseOrderNum" id="custRealiseOrderNum" class="form-control read" placeholder="Order Number" readonly></td>
							<td><input type="text" name="custRealiseOrderDate" id="custRealiseOrderDate" class="form-control read" placeholder="Order Date" readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
       		<div class="table-responsive"> 
			    <table  class="table table-bordered table-responsive table-hover table-striped mb-none">
					<thead>
						<tr class="gridDark">
							<th>Exit Note Number</th>
							<th>Exit Note Date</th>
							<th>Bill Number</th>
							<th>Bill Date</th>
							<th>Bank CP/No</th>
							<th>Bank CP Date</th>
							<th>Port Bill Status</th>
						</tr>
					</thead>
					<tbody>
						<tr class="gradeX">
							<td><input type="text" name="exitNoteNum" id="exitNoteNum" class="form-control read" placeholder="Exit Note Number" readonly></td>
							<td><input type="text" name="exitNoteDate" id="exitNoteDate" class="form-control read" placeholder="Exit Note Date" readonly></td>
							<td><input type="text" name="billNumber" id="billNumber" class="form-control read" placeholder="Bill Number" readonly></td>
							<td><input type="text" name="billDate" id="billDate" class="form-control read" placeholder="Bill Date" readonly></td>
							<td><input type="text" name="bankCPno" id="bankCPno" class="form-control read" placeholder="Bank CP/No" readonly></td>
							<td><input type="text" name="bankCPdate" id="bankCPdate" class="form-control read" placeholder="Bank CP Date" readonly></td>
							<td><input type="text" name="portBillStatus" id="portBillStatus" class="form-control read" placeholder="Port Bill Status" readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
	        <div class="row">
				<div class="col-md-6">
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Assign Clerk to Delivery : </span>
						<select id="assignClerk" name="assignClerk" class="form-control" tabindex="2">
							<option value="">---Select Clerk---</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Truck Number : </span>
						<input type="text" name="truckNum" id="truckNum" class="form-control read" placeholder="Truck Number" readonly>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
				</div>													
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<?PHP echo @$msg;?>
				</div>
			</div>		
		</form>	
	</div>
	</section>	
          	<div class="clr"></div>
		</div>  
	</div>
</section>