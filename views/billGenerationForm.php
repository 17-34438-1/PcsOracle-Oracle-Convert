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
/*$('body').on('keydown', 'input, select, textarea', function(e) {
		var self = $(this)
		  , form = self.parents('form:eq(0)')
		  , focusable
		  , next
		  ;
		if (e.keyCode == 13) {
			focusable = form.find('input,select,button,textarea,submit').filter(':visible');
			next = focusable.eq(focusable.index(this)+1);
			if (next.length) {
				//next.style.backgroundColor = "red";
				next.focus();			
			} else {
				form.submit();
			}
			return false;
		}
	});*/
/*function changeToRPC(e)
{
	if (e.keyCode == 13) {
		document.getElementById('rpc').focus();
	}
}
function changeToHC(e)
{
	if (e.keyCode == 13) {
		document.getElementById('hc_charge').focus();
	}
}
function changeToScale(e)
{
	if (e.keyCode == 13) {
		document.getElementById('scale_charge').focus();
	}
}
function changeToVat(e)
{
	if (e.keyCode == 13) {
		document.getElementById('vat').focus();
	}
}
function changeToMlwf(e)
{
	if (e.keyCode == 13) {
		document.getElementById('mlwf').focus();
	}
}*/
	function validate()
	{
		var tableData = document.getElementById('mytbl');
		var numberOfRows = tableData.rows.length;
		//alert(numberOfRows);
		if( document.myChkForm.verify_num.value =="")
		{
			alert( "Please provide VERIFY Number!" );
			document.myChkForm.verify_num.focus() ;
			return false;
		}
		else if( numberOfRows <= 2 )
		{
			alert( "TABLE CANNOT BE EMPTY!" );
			document.myChkForm.scale_charge.focus() ;
			//document.myChkForm.cnfName.focus() ;
			return false;
		}

		if (confirm("Do you want to Save Bill Information?") == true) 
		{
			return true ;
		} 
		else 
		{
			return false;
		}
	}  	
	
	function getCnfCode(e,val) 
	{	
		//alert(val);		
		if (e.keyCode == 13) 
		{
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
			xmlhttp.onreadystatechange=stateChangeValue;
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getCnfCode')?>?cnf_lic_no="+val,false);
			xmlhttp.send();
		}
	}

	function stateChangeValue()
	{
		//alert("ddfd");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{				  
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			var cnfCodeTxt=document.getElementById("cnfName");
		
			for (var i = 0; i < jsonData.length; i++) 
			{				
				cnfCodeTxt.value=jsonData[i].NAME;				
			}
		}
	}
	
	function getData()
	{
		var formTypeVal=document.getElementById("formType").value;
		//alert(formTypeVal);
		if(formTypeVal=="list")
		{
			document.getElementById('verify_num').blur();
		}
		else
		{
			document.getElementById('verify_num').focus();
		}
	}
	
	/****************Working with Enter Key Event********************/
	function getBillData(val) 				// on blur 
	{	
		//if (e.keyCode == 13) {
		//alert(val);
		//document.getElementById("").style.display="inline";
		//document.getElementById("loadingImg").style.display="inline";

		var chkVerifyNum=chkExist(val); //Check Verify Number Exist In Shed Bill Master

		var vatInfo = document.getElementById('vat').value;
		var mlwf = document.getElementById('mlwf').value;
		
		document.getElementById('sendVerifyNo').value = val;
		
		if(chkVerifyNum=="2")
		{
			alert("Bill Already Generated.");
			
			//To show data if already generated starts---------------------------
			var unstfDt="";
			if (window.XMLHttpRequest) 
			{
				//document.getElementById("loadingImg").style.display="inline";
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeBillValue;
			var unstfDt = "";
			var uptoDt = "";
			var comm_dt = "";
			var rpc = "0";
			var hcCharge = "0";
			var scCharge = "0";			
			/* xmlhttp.open("GET","<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+val+"&unstfDt="+unstfDt+"&vatInfo="+vatInfo+"&mlwf="+mlwf,false); */
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+val+"&unstfDt="+unstfDt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf+"&comm_dt="+comm_dt,false);
			xmlhttp.send();
			showBillToTable(event);
			//To show data if already generated ends-----------------------------
			
			
			//document.myChkForm.verify_num.focus();
			document.myChkForm.bill_for.focus();
			//document.getElementById('save_btn').style.visibility = 'hidden';
			document.getElementById("save_btn").style.display="none";
			document.getElementById("btnViewBill").style.display="block";
			//document.getElementById("loadingImg").style.display="none";
			/*document.getElementById("loadingImg").style.display="inline";
			if (window.XMLHttpRequest) 
			{
			xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeFromShedBill;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getDataFromShedBill')?>?verify_num="+val,false);
			xmlhttp.send();*/
		}
		else if(chkVerifyNum=="1")
		{
			var unstfDt="";

			if (window.XMLHttpRequest) 
			{
				//document.getElementById("loadingImg").style.display="inline";
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeBillValue;
			var unstfDt = "";
			var uptoDt = "";
			var comm_dt = "";

			var rpc = "0";
			var hcCharge = "0";
			var scCharge = "0";			
			/* xmlhttp.open("GET","<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+val+"&unstfDt="+unstfDt+"&vatInfo="+vatInfo+"&mlwf="+mlwf,false); */
			
			
			// var url = "<?php //echo site_url('AjaxController/getBillDetails')?>?verify_num="+val+"&unstfDt="+unstfDt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf+"&comm_dt="+comm_dt;
			// alert(url);
			
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+val+"&unstfDt="+unstfDt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf+"&comm_dt="+comm_dt,false);
			xmlhttp.send();
			
			document.getElementById("save_btn").style.display="block";
			document.getElementById("btnViewBill").style.display="none";
			
		}
		else
		{
			alert("Verification Number is Invalid.");
			//document.myChkForm.verify_num.focus();
			document.myChkForm.bill_for.focus();
			document.getElementById('save_btn').style.visibility = 'hidden';
			//document.getElementById("loadingImg").style.display="none";
		}
		//}
	}		
	
	function chkExist(val)
	{
	//	alert("inside chkExist");
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
		xmlhttp.onreadystatechange=stateCheckVerifyNum;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/checkVerifyNumberExist')?>?verify_num="+val,false);
		xmlhttp.send();	
		return xmlhttp.onreadystatechange();
		//alert(xmlhttp.onreadystatechange);
	}
	
	function stateCheckVerifyNum()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			
			var val = xmlhttp.responseText;
		
			var jsonData = JSON.parse(val);
			
			var chkData=jsonData.rtnChkList;
		
			if(chkData=="0")
			{
				return "0";
			}
			else if(chkData=="1")
			{
				return "1";
			}
			else
			{
				return "2";
			}
		}
	}
	
	function addRow(val) 			// works after selecting tarrif in table 
	{
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
		xmlhttp.onreadystatechange=stateChangeBillInformation;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getBillInfo')?>?tarrif_id="+val,false);
		xmlhttp.send();
				
	}
	
	function stateChangeBillInformation()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{		  
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			
			document.getElementById("tarrif_id_tbl").value=jsonData.getBillInfo[0].tarrif_id;
			document.getElementById("bill_head_tbl").value=jsonData.getBillInfo[0].description;
			document.getElementById("option_tbl").value=jsonData.getBillInfo[0].gl_code;
			document.getElementById("rate_tbl").value=jsonData.getBillInfo[0].tarrif_rate;
		//	document.getElementById("qty_tbl").value="";				// add quantity of container here
			document.getElementById("qty_tbl").value=tot_cont_qty;				// add quantity of container here
			document.getElementById("days_tbl").value="";
			document.getElementById("wt_tbl").value="";
			document.getElementById("mlwf_tbl").value="";
			document.getElementById("vat_tbl").value="";
			document.getElementById("port_tbl").value="";
			
			//addAfterTotal();
		}
	}

	function addTableRow()				// works onclick ADD button
	{
		var  table = document.getElementById("mytbl");
		var tr = document.createElement('tr'); 
		tr.className ="gridDark";
						   
		var td1 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "tarrif_id" + table.rows.length;
		input.id = "tarrif_id" + table.rows.length;
		input.value=document.getElementById("tarrif_id_tbl").value;
		input.style.width = "300px";
		td1.appendChild(input);
						   
		var td2 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "billHead"+ table.rows.length;
		input.id = "billHead";
		input.value=document.getElementById("bill_head_tbl").value;
		input.style.width = "100px";
		td2.appendChild(input);
						   
		var td3 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "option"+ table.rows.length;
		input.id = "option";
		input.value=document.getElementById("option_tbl").value;
		input.style.width = "50px";
		td3.appendChild(input);

		var td4 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "rate"+ table.rows.length;
		input.id = "rate";
		input.value=document.getElementById("rate_tbl").value;
		input.style.width = "40px";
		td4.appendChild(input);
						   						   						   
		var td5 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "qty"+ table.rows.length;
		input.id = "qty";
		input.value=document.getElementById("qty_tbl").value;	
		input.style.width = "20px";
		td5.appendChild(input);

		var td6 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "qday"+ table.rows.length;
		input.id = "qday";
		input.value=document.getElementById("days_tbl").value;
		//input.onkeypress=function(event){return getBillRowTotal(event,this.value,table.rows.length)};
		input.style.width = "20px";
		td6.appendChild(input);
						   
		var td7 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "weight"+ table.rows.length;
		input.id = "weight";
		input.value=document.getElementById("wt_tbl").value;
		input.style.width = "40px";
		td7.appendChild(input);

		var td8 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "mlwf"+ table.rows.length;
		input.id = "mlwf";
		input.value=document.getElementById("mlwf_tbl").value;
		input.style.width = "40px";
		td8.appendChild(input);

		var td9 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "vatTK"+ table.rows.length;
		input.id = "vatTK";
		input.value=document.getElementById("vat_tbl").value;
		input.style.width = "40px";
		td9.appendChild(input);
						   
		var td10 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "amt"+ table.rows.length;
		input.id = "amt";
		input.value=document.getElementById("port_tbl").value;
		input.style.width = "40px";
		td10.appendChild(input);
						   
		var td11 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "button";
		//input.name = "mlwf";
		input.style.background="#CCAEDE";
		//input.onclick=deleteRow(this);
		input.onclick=function(event){deleteRow(this)};
		input.value="DELETE";
		input.style.width = "40px";
		td11.appendChild(input);
						   
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);
		tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);
		tr.appendChild(td10);
		tr.appendChild(td11);

		table.appendChild(tr);
	
		document.getElementById("total_port").value=parseFloat(document.getElementById("total_port").value) + parseFloat(document.getElementById("port_tbl").value);
		document.getElementById("total_vat").value=parseFloat(document.getElementById("total_vat").value) + parseFloat(document.getElementById("vat_tbl").value);
		document.getElementById("total_mlwf").value=parseFloat(document.getElementById("total_mlwf").value) + parseFloat(document.getElementById("mlwf_tbl").value);
		
		document.getElementById("grand_total").value=parseFloat(document.getElementById("total_port").value) + parseFloat(document.getElementById("total_vat").value) + parseFloat(document.getElementById("mlwf_tbl").value);
		
		var  table = document.getElementById("mytbl");
		document.getElementById("tbl_total_row").value=table.rows.length;			   
	}	
	
	function addEquipmentInfo(ename,echarge,glcode,tariffid,weight,mlwfAmt,vatAmt,portAmt,contStatus)				
	{
		var  table = document.getElementById("mytbl");
		var tr = document.createElement('tr'); 
		tr.className ="gridDark";
						   
		var td1 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "tarrif_id_val";
		input.id = "tarrif_id_val";
		
		input.value=tariffid;
		input.style.width = "300px";
		td1.appendChild(input);
						   
		var td2 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "billHeadVal";
		input.id = "billHeadVal";
		input.value=ename;
		input.style.width = "100px";
		td2.appendChild(input);
						   
		var td3 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "optionVal";
		input.id = "optionVal";
		input.value=glcode;
		input.style.width = "50px";
		td3.appendChild(input);

		var td4 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "rateVal";
		input.id = "rateVal";
		input.value=echarge;
		input.style.width = "40px";
		td4.appendChild(input);
						   						   						   
		var td5 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "qtyVal";
		input.id = "qtyVal";
		//input.value=document.getElementById("qty_tbl").value;	
		input.value=1;	
		input.style.width = "20px";
		td5.appendChild(input);

		var td6 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "qdayVal";
		input.id = "qdayVal";
		//input.value=document.getElementById("days_tbl").value;
		input.value=1;
		//input.onkeypress=function(event){return getBillRowTotal(event,this.value,table.rows.length)};
		input.style.width = "20px";
		td6.appendChild(input);
						   
		var td7 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "weightVal";
		input.id = "weightVal";
		input.value=weight;
		input.style.width = "40px";
		td7.appendChild(input);

		var td8 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "mlwf_val";
		input.id = "mlwf_val";
		input.value=mlwfAmt;
		input.style.width = "40px";
		td8.appendChild(input);

		var td9 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "vatTKVal";
		input.id = "vatTKVal";
		input.value=vatAmt;
		input.style.width = "40px";
		td9.appendChild(input);
						   
		var td10 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "text";
		input.name = "amtVal";
		input.id = "amtVal";
		input.value=portAmt;
		input.style.width = "40px";
		td10.appendChild(input);
						   
		var td11 = document.createElement('td');
		//td1.colSpan="2";
		var input = document.createElement("input");
		input.type = "button";
		//input.name = "mlwf";
		input.style.background="#CCAEDE";
		//input.onclick=deleteRow(this);
		input.onclick=function(event){deleteRow(this)};
		input.value="DELETE";
		input.style.width = "80px";
		input.style.textAlign = "center";
		//input.style.borderRadius = "25px";
		td11.appendChild(input);
						   
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td4);
		tr.appendChild(td5);
		tr.appendChild(td6);
		tr.appendChild(td7);
		tr.appendChild(td8);
		tr.appendChild(td9);
		tr.appendChild(td10);
		tr.appendChild(td11);

		table.appendChild(tr);
		document.getElementById("total_port").value=parseFloat(document.getElementById("total_port").value) + parseFloat(document.getElementById("amtVal").value);
		document.getElementById("total_vat").value=parseFloat(document.getElementById("total_vat").value) + parseFloat(document.getElementById("vatTKVal").value);
		document.getElementById("total_mlwf").value=parseFloat(document.getElementById("total_mlwf").value) + parseFloat(document.getElementById("mlwf_val").value);
		
		document.getElementById("grand_total").value=parseFloat(document.getElementById("total_port").value) + parseFloat(document.getElementById("total_vat").value) + parseFloat(document.getElementById("total_mlwf").value);
		
		var  table = document.getElementById("mytbl");
		document.getElementById("tbl_total_row").value=table.rows.length;			   
	}

	var tot_cont_qty=0;		// 2020-04-08 - global variable
	
	
	function stateChangeBillValue()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var val = xmlhttp.responseText;
		
			console.log(val);
			var jsonData = JSON.parse(val);
			
			console.log(jsonData);
			if(jsonData.rtnBillList.length<1)
			{
				alert("Verification Number Not Valid.");
				document.getElementById('verify_num').focus();
			}
			else
			{
				document.getElementById('save_btn').style.visibility = 'visible';
				var bill_no=document.getElementById("bill_no");
				var one_stop_point=document.getElementById("one_stop_point");
				var rotation_no=document.getElementById("rotation_no");
				var arr_dt=document.getElementById("arr_dt");
				var comm_dt=document.getElementById("comm_dt");
				var wr_dt=document.getElementById("wr_dt");
				var ado_no=document.getElementById("ado_no");
				var ado_dt=document.getElementById("ado_dt");
				var ado_upto=document.getElementById("ado_upto");
				var ex_rate=document.getElementById("ex_rate");
				var bill_for=document.getElementById("bill_for");
				var unstfDt=document.getElementById("unstfDt");
				var wr_upto_dt=document.getElementById("wr_upto_dt");
				var be_no=document.getElementById("be_no");
				var be_dt=document.getElementById("be_dt");
				var less=document.getElementById("less");
				var part_bl=document.getElementById("part_bl");
				//document.myChkForm.scale_charge.focus() ;
				/**********Container Data*****************/
				var twnty_gen_eight_five=document.getElementById("twnty_gen_eight_five");
				var twnty_gen_nine_five=document.getElementById("twnty_gen_nine_five");
				var twnty_open_eight_five=document.getElementById("twnty_open_eight_five");
				var twnty_open_nine_five=document.getElementById("twnty_open_nine_five");
				var twnty_flat_eight_five=document.getElementById("twnty_flat_eight_five");
				var twnty_flat_nine_five=document.getElementById("twnty_flat_nine_five");
				
				var forty_gen_eight_five=document.getElementById("forty_gen_eight_five");
				var forty_gen_nine_five=document.getElementById("forty_gen_nine_five");
				var forty_open_eight_five=document.getElementById("forty_open_eight_five");
				var forty_open_nine_five=document.getElementById("forty_open_nine_five");
				var forty_flat_eight_five=document.getElementById("forty_flat_eight_five");
				var forty_flat_nine_five=document.getElementById("forty_flat_nine_five");
		
				var forty_five_gen_eight_five=document.getElementById("forty_five_gen_eight_five");
				var forty_five_gen_nine_five=document.getElementById("forty_five_gen_nine_five");
				var forty_five_open_eight_five=document.getElementById("forty_five_open_eight_five");
				var forty_five_open_nine_five=document.getElementById("forty_five_open_nine_five");
				var forty_five_flat_eight_five=document.getElementById("forty_five_flat_eight_five");
				var forty_five_flat_nine_five=document.getElementById("forty_five_flat_nine_five");
		
		
				/***********Container Data****************/
				var cont_qty=document.getElementById("cont_qty");
				var cont_wht=document.getElementById("cont_wht");
				
				var qty_in_shed=document.getElementById("qty_in_shed");
				var wht_in_shed=document.getElementById("wht_in_shed");
				var qty_lock=document.getElementById("qty_lock");
				var wht_lock=document.getElementById("wht_lock");
				var qty_out_shed=document.getElementById("qty_out_shed");
				var wht_out_shed=document.getElementById("wht_out_shed");
				var cnfCode=document.getElementById("cnfCode");
				var cnfName=document.getElementById("cnfName");
				
				var importerCode=document.getElementById("impo_reg_no");
				var importerName=document.getElementById("impo_reg_name");
				
				var rpc=document.getElementById("rpc");
				var hc_charge=document.getElementById("hc_charge");
				var hc_rcv_unit_txt=document.getElementById("hc_rcv_unit");
				var hc_rcv_amount=document.getElementById("hc_rcv_amount");
				var scale_charge=document.getElementById("scale_charge");
				var ext_mov_twnty=document.getElementById("ext_mov_twnty");
				var ext_mov_forty=document.getElementById("ext_mov_forty");
				
				var total_port=document.getElementById("total_port");
				var total_vat=document.getElementById("total_vat");
				var total_mlwf=document.getElementById("total_mlwf");
				
				
				 twnty_gen_eight_five.value="";
				 twnty_gen_nine_five.value="";
				 twnty_open_eight_five.value="";
				 twnty_open_nine_five.value="";
				 twnty_flat_eight_five.value="";
				 twnty_flat_nine_five.value="";
				
				 forty_gen_eight_five.value="";
				 forty_gen_nine_five.value="";
				 forty_open_eight_five.value="";
				 forty_open_nine_five.value="";
				 forty_flat_eight_five.value="";
				 forty_flat_nine_five.value="";
		
				 forty_five_gen_eight_five.value="";
				 forty_five_gen_nine_five.value="";
				 forty_five_open_eight_five.value="";
				 forty_five_open_nine_five.value="";
				 forty_five_flat_eight_five.value="";
				 forty_five_flat_nine_five.value="";
				
				for (var i = 0; i < jsonData.rtnBillList.length; i++) 
				{															
					if(jsonData.rtnBillList[i].cont_type.search("general") != -1 )
					{
						// CONTAINER SIZE
						if(jsonData.rtnBillList[i].cont_size.search("20") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{						
								twnty_gen_eight_five.value = Number(twnty_gen_eight_five.value) + 1;							
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								twnty_gen_nine_five.value  = Number(twnty_gen_nine_five.value) + 1;							
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("40") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_gen_eight_five.value  = Number(forty_gen_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								//alert(jsonData.rtnBillList[i].cont_height);
								//alert(document.getElementById("forty_gen_nine_five").value);
								forty_gen_nine_five.value = Number(forty_gen_nine_five.value) + 1;
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("45") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_five_gen_eight_five.value = Number(forty_five_gen_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								forty_five_gen_nine_five.value  = Number(forty_five_gen_nine_five.value) + 1;
							}
						}						
						//alert("Match : "+jsonData.rtnBillList[i].cont_type);
					}
					else if (jsonData.rtnBillList[i].cont_type.search("flatrack") != -1 )
					{				
						// CONTAINER SIZE
						if(jsonData.rtnBillList[i].cont_size.search("20") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								twnty_flat_eight_five.value = Number(twnty_flat_eight_five.value) + 1;
								//ext_mov_twnty.value=jsonData.appraisalData[0].extra_movement;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								twnty_flat_nine_five.value = Number(twnty_flat_nine_five.value) + 1;
								//ext_mov_twnty.value=jsonData.appraisalData[0].extra_movement;
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("40") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_flat_eight_five.value = Number(forty_flat_eight_five.value) + 1;
								//ext_mov_forty.value=jsonData.appraisalData[0].extra_movement;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								forty_flat_nine_five.value = Number(forty_flat_nine_five.value) + 1;
								//ext_mov_forty.value=jsonData.appraisalData[0].extra_movement;
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("45") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_five_flat_eight_five.value = Number(forty_five_flat_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								forty_five_flat_nine_five.value = Number(forty_five_flat_nine_five.value) + 1;
							}
						}
					}
					else if (jsonData.rtnBillList[i].cont_type.search("open") != -1 )
					{
						if(jsonData.rtnBillList[i].cont_size.search("20") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								twnty_open_eight_five.value = Number(twnty_open_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								twnty_open_nine_five.value = Number(twnty_open_nine_five.value) + 1;
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("40") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_open_eight_five.value = Number(forty_open_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								forty_open_nine_five.value = Number(forty_open_nine_five.value) + 1;
							}
						}
						else if(jsonData.rtnBillList[i].cont_size.search("45") != -1)
						{
							// CONTAINER HEIGHT
							if(jsonData.rtnBillList[i].cont_height.search("8") != -1)
							{
								forty_five_open_eight_five.value = Number(forty_five_open_eight_five.value) + 1;
							}
							else if(jsonData.rtnBillList[i].cont_height.search("9") != -1)
							{
								forty_five_open_nine_five.value = Number(forty_five_open_nine_five.value) + 1;
							}
						}
					}

					bill_no.value="";
					rotation_no.value=jsonData.rtnBillList[i].import_rotation;
					arr_dt.value=jsonData.arraivalDateValue;
					comm_dt.value=jsonData.rtnBillList[i].comm_landing_date;
				
					if(jsonData.cont_status=="LCL")
						hc_rcv_unit_txt.value=jsonData.rtnBillList[i].rcv_unit;
					else if(jsonData.cont_status=="FCL")
						hc_rcv_unit_txt.value=jsonData.total_container;			// this part is ok as we need only total_container of bill for hosting_charge
					
					hc_rcv_amount.value=jsonData.rtnBillList[i].equipment;
					console.log("RCV : "+jsonData.rtnBillList[i].rcv_unit);
					wr_dt.value=jsonData.rtnBillList[i].wr_date;
					ado_no.value=jsonData.rtnBillList[i].do_no;
					ado_dt.value=jsonData.rtnBillList[i].do_date;
					ado_upto.value=jsonData.rtnBillList[i].valid_up_to_date;
					ex_rate.value=jsonData.getExRateValue;
					bill_for.value="";
					one_stop_point.value=jsonData.sectionValue;
					//console.log(jsonData.sectionValue);
					unstfDt.value=jsonData.rtnBillList[i].wr_date;
					//wr_upto_dt.value=jsonData.rtnBillList[i].wr_upto_date;
					wr_upto_dt.value=jsonData.rtnBillList[i].valid_up_to_date;
					be_no.value=jsonData.rtnBillList[i].be_no;
					be_dt.value=jsonData.rtnBillList[i].be_date;
					less.value="";
					part_bl.value="";
					cont_qty.value=jsonData.rtnBillList[i].Pack_Number;
					cont_wht.value=jsonData.rtnBillList[i].cont_weight;
					if(jsonData.rtnBillList[i].cont_status=="FCL")
					{
						// //qty_in_shed.value="ff";
						document.getElementById("qty_in_shed").disabled=true;
						document.getElementById("wht_in_shed").disabled=true;
						document.getElementById("qty_lock").disabled=true;
						document.getElementById("wht_lock").disabled=true;
						document.getElementById("qty_out_shed").disabled=true;			// 2020-04-12
						document.getElementById("wht_out_shed").disabled=true;			//	"
						
						document.getElementById("qty_in_shed").value="";				//	"
						document.getElementById("wht_in_shed").value="";				//	"
						document.getElementById("qty_lock").value="";					//	"
						document.getElementById("wht_lock").value="";					//	"
						document.getElementById("qty_out_shed").value="";				//	"
						document.getElementById("wht_out_shed").value="";				//	"
						
						// var lclFieldset=document.getElementById("lcl_fieldset");
						// lclFieldset.style.display=none;
					}
					else	// 2020-04-12
					{
						qty_in_shed.value=jsonData.rtnBillList[i].rcv_pack;
						qty_lock.value=jsonData.rtnBillList[i].loc_first;
					}
					
					cnfCode.value=jsonData.rtnBillList[i].cnf_lic_no;
					cnfName.value=jsonData.rtnBillList[i].cnf_name;
				
					importerCode.value=jsonData.rtnBillList[i].Consignee_code;
					importerName.value=jsonData.rtnBillList[i].Consignee_name;
					
					vessel_name.value=jsonData.rtnBillList[i].Vessel_Name;
					bl_no.value=jsonData.rtnBillList[i].BL_No;
					container_size.value=jsonData.rtnBillList[i].cont_size;
					container_height.value=jsonData.rtnBillList[i].cont_height;
					totalqty=jsonData.rtnBillList[i].total_pack;
				
					if(totalqty!=cont_qty.value)
					{
						if(jsonData.rtnBillList[i].cont_status=="FCL")		//2020-04-12
						{
							wht_in_shed="";
							wht_lock="";
						}
						else
						{
							wht_in_shed.value= (cont_wht.value/totalqty)*qty_in_shed.value;
							wht_lock.value= (cont_wht.value/totalqty)*qty_lock.value;
						}
						
						// 2020-04-12 - previous
						// wht_in_shed.value= (cont_wht.value/totalqty)*qty_in_shed.value;
						// wht_lock.value= (cont_wht.value/totalqty)*qty_lock.value;
					}
					else
					{
						wht_in_shed.value= (cont_wht.value/cont_qty.value)*qty_in_shed.value;
						wht_lock.value= (cont_wht.value/cont_qty.value)*qty_lock.value;
					}
					//sendVerifyNo.value=jsonData.rtnBillList[i].verify_number;
								
					var  table = document.getElementById("mytbl");
					removeTableElement(table);								
					
					if(jsonData.appraisalData!=null)
					{
						//alert("jsonData.appraisalData.length---");
						for (var j = 0; j < jsonData.appraisalData.length; j++) 
						{					
							rpc.value=jsonData.appraisalData[j].carpainter_use;
							hc_charge.value=jsonData.appraisalData[j].hosting_charge;
							//hc_charge.value=parseFloat(jsonData.appraisalData[j].hosting_charge)+Math.ceil((parseFloat(jsonData.rtnBillList[i].cont_weight)/1000));
							scale_charge.value=jsonData.appraisalData[j].scale_for;
							if(jsonData.rtnBillList[i].cont_size.search("20") != -1)
							{
								ext_mov_twnty.value=jsonData.appraisalData[j].extra_movement;
							}
							else
							{
								ext_mov_forty.value=jsonData.appraisalData[j].extra_movement;
							}
						}
					}
					else
					{
						rpc.value=0;
						hc_charge.value=0;
						scale_charge.value=0;
						
						ext_mov_twnty.value=0;
						ext_mov_forty.value=0;
						
					}
					
				}			// jsonData.rtnBillList.length loop ends
				getGrossTotal();
				//document.myChkForm.scale_charge.focus() ;
				//document.getElementById("loadingImg").style.display="none";
				//document.getElementById('wr_upto_dt').focus();
				document.getElementById('bill_for').focus();
				//alert(jsonData.rtnBillList[0].equipment_name);
				
			}
		}
	}

	function getGrossTotal()
	{
		var total_port=document.getElementById("total_port");
		var total_vat=document.getElementById("total_vat");
		var total_mlwf=document.getElementById("total_mlwf");

		var grand_total=document.getElementById("grand_total");
		//grand_total.value=(parseFloat(total_port.value)+parseFloat(total_vat.value)+parseFloat(total_mlwf.value)).toFixed(2);
		grand_total.value=parseFloat(Math.round(parseFloat(total_port.value)+parseFloat(total_vat.value)+parseFloat(total_mlwf.value))).toFixed(2);

		var  table = document.getElementById("mytbl");
		document.getElementById("tbl_total_row").value=table.rows.length;
	}		
	
	function removeTableElement(table)
	{
		var tblLen = table.rows.length;
		//alert(tblLen);
		for(var i=tblLen;i>2;i--)
		{
			table.deleteRow(i-1);
		}    
	}
	
	function deleteRow(row)
	{
		//alert(row);
		var i=row.parentNode.parentNode.rowIndex;
		
		var rowPortAmountValue= document.getElementById('mytbl').rows[i].cells[9].children[0].value;
		var rowVatAmountValue= document.getElementById('mytbl').rows[i].cells[8].children[0].value;
		var rowMlwfAmountValue= document.getElementById('mytbl').rows[i].cells[7].children[0].value;
		//console.log("Value : "+document.getElementById('mytbl').rows[i].cells[2].children[0].value);
		document.getElementById("total_port").value=parseFloat(parseFloat(document.getElementById("total_port").value) - parseFloat(rowPortAmountValue)).toFixed(2);
		document.getElementById("total_vat").value=parseFloat(parseFloat(document.getElementById("total_vat").value) - parseFloat(rowVatAmountValue)).toFixed(2);
		document.getElementById("total_mlwf").value=parseFloat(parseFloat(document.getElementById("total_mlwf").value) - parseFloat(rowMlwfAmountValue)).toFixed(2);
		
		document.getElementById("grand_total").value=parseFloat(parseFloat(document.getElementById("grand_total").value) - (parseFloat(rowPortAmountValue) + parseFloat(rowVatAmountValue) + parseFloat(rowMlwfAmountValue))).toFixed(2);
		
		document.getElementById('mytbl').deleteRow(i);
		//console.log("hello"+i);		
	}
	
	function addBillToTable(e)
	{
		// alert("Changed");
		var verify_num = document.getElementById('verify_num').value;
		var unstfDt = "";
		var uptoDt = "";

		var rpc = "0";
		var hcCharge = "0";
		var scCharge = "0";
		var start_dt="";
		var vatInfo = document.getElementById('vat').value;
		var mlwf = document.getElementById('mlwf').value;
		unstfDt= document.getElementById('unstfDt').value;
		var bill_for= document.getElementById('bill_for').value;
		uptoDt= document.getElementById('wr_upto_dt').value;
		var comm_dt= document.getElementById('comm_dt').value;
		// ---- Sumon--------
		if(bill_for=='LCL CONTAINER')
		{
			start_dt=unstfDt;
		}
		else
		{
			start_dt=comm_dt;
		}
		
		rpc= document.getElementById('rpc').value;
		hcCharge= document.getElementById('hc_charge').value;
		scCharge= document.getElementById('scale_charge').value;

		//console.log(hcCharge+"--"+scCharge+"--"+rpc);

		if(verify_num=="")
		{
			//document.getElementById("loadingImg").style.display="none";
			alert("Please Input Verification Number.");			
			document.getElementById('verify_num').focus();
		}
		else if( bill_for!="FCL CONTAINER" && unstfDt=="")
		{
			//document.getElementById("loadingImg").style.display="none";
			alert("Please Input Unstuffing Date.");

			document.getElementById('unstfDt').focus();
		}
		else if(uptoDt=="")
		{
			//document.getElementById("loadingImg").style.display="none";
			alert("Please Input Up to Date.");			
			document.getElementById('wr_upto_dt').focus();
		}
		else
		{
			//alert(unstfDt);
			if (confirm("Do you want to Generate Bill?") == true) 
			{
				//document.getElementById("loadingImg").style.display="inline";
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
				xmlhttp.onreadystatechange=stateChangeAddBillDetails;				
				var url="<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+verify_num+"&unstfDt="+start_dt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf+"&comm_dt="+comm_dt;
				// alert(url);
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			} 
			else 
			{
				//document.getElementById("loadingImg").style.display="none";
			}
		}
	}
 
	function showBillToTable(e)
	{
		// alert("Changed");
		var verify_num = document.getElementById('verify_num').value;
		var unstfDt = "";
		var uptoDt = "";
		var comm_dt = "";

		var rpc = "0";
		var hcCharge = "0";
		var scCharge = "0";
		var vatInfo = document.getElementById('vat').value;
		var mlwf = document.getElementById('mlwf').value;
		unstfDt= document.getElementById('unstfDt').value;
		uptoDt= document.getElementById('wr_upto_dt').value;
		comm_dt= document.getElementById('comm_dt').value;
		rpc= document.getElementById('rpc').value;
		hcCharge= document.getElementById('hc_charge').value;
		scCharge= document.getElementById('scale_charge').value;

		console.log(hcCharge+"--"+scCharge+"--"+rpc);

		if(verify_num=="")
		{
			//document.getElementById("loadingImg").style.display="none";
			alert("Please Input Verification Number.");			
			document.getElementById('verify_num').focus();
		}
		else if(uptoDt=="")
		{
			//document.getElementById("loadingImg").style.display="none";
			alert("Please Input Up to Date.");			
			document.getElementById('wr_upto_dt').focus();
		}
		else
		{
			//alert(unstfDt);
			
				//document.getElementById("loadingImg").style.display="inline";
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
				xmlhttp.onreadystatechange=stateChangeAddBillDetails;
				// xmlhttp.open("GET","<?php echo site_url('ajaxController/getBillDetails')?>?verify_num="+verify_num+"&unstfDt="+unstfDt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf,false);
				
				var url="<?php echo site_url('AjaxController/getBillDetails')?>?verify_num="+verify_num+"&unstfDt="+unstfDt+"&uptoDt="+uptoDt+"&rpc="+rpc+"&hcCharge="+hcCharge+"&scCharge="+scCharge+"&vatInfo="+vatInfo+"&mlwf="+mlwf+"&comm_dt="+comm_dt;
				// alert(url);
				xmlhttp.open("GET",url,false);
				xmlhttp.send();
			
		}
	}
 
	function stateChangeAddBillDetails()		// works on tab index 
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			//alert(xmlhttp.responseText);			  
			var val = xmlhttp.responseText;

			var jsonData = JSON.parse(val);
			console.log(jsonData);
			if(jsonData.chargeList.length<1)
			{
				alert("Verification Number is invalid");
				//document.getElementById("loadingImg").style.display="none";
				document.getElementById('verify_num').focus();
			}
			else
			{
				var vatChecked = document.getElementById('vat').value;				
				var contStatus=jsonData.cont_status;
				if(contStatus=="LCL")
				{
					//alert("LCL inside");
					/**********************ADD BILL TO TABLE START*******************************/
					var  table = document.getElementById("mytbl");

					var  hc_charge = document.getElementById('hc_charge').value;
					var  hc_rcv_unit = document.getElementById('hc_rcv_unit').value;
					var  hc_rcv_amount = document.getElementById('hc_rcv_amount').value;
					var  rpc_charge = document.getElementById('rpc').value;
					var  scale_charge = document.getElementById('scale_charge').value;

					var  wht_in_shed = document.getElementById('wht_in_shed').value;
					var  wht_lock = document.getElementById('wht_lock').value;

					var portSum=0;
					var vatSum=0;
					var mlwfSum=0;
					removeTableElement(table);
					
					for (var k = 0; k < jsonData.chargeList.length; k++) 
					{
						if(jsonData.chargeList[k].qday>0)
						{
							var tr = document.createElement('tr'); 
							tr.className ="gridDark";
				
							var td1 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "tarrif_id" + k;
							input.value=jsonData.chargeList[k].tarrif_id;
							input.style.width = "300px";
							td1.appendChild(input);

							var td2 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "billHead" + k;
							input.value=jsonData.chargeList[k].description;
							input.style.width = "100px";
							td2.appendChild(input);

							var td3 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "option" + k;
							input.value=jsonData.chargeList[k].gl_code;
							input.style.width = "50px";
							td3.appendChild(input);

							var td4 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "rate" + k;
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="" || (hc_rcv_unit=="Pallet" && hc_rcv_unit=="Carton"))
							{
								input.value=hc_rcv_amount;
							}
							else
							{
								input.value=jsonData.chargeList[k].tarrif_rate;
							}
							input.style.width = "40px";
							td4.appendChild(input);

							var td5 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "qty" + k;

							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="" || (hc_rcv_unit=="Pallet" && hc_rcv_unit=="Carton"))
							{
								input.value=Math.ceil(hc_charge);		
								//alert("IF : "+hc_charge);
							}
							else
							{
								if(jsonData.chargeList[k].gl_code=="206031" || jsonData.chargeList[k].gl_code=="206033" || jsonData.chargeList[k].gl_code=="206035")
								{
									input.value=Math.ceil(wht_in_shed /1000);
								}
								else
								{
									if(jsonData.chargeList[k].gl_code=="206037" || jsonData.chargeList[k].gl_code=="206039" || jsonData.chargeList[k].gl_code=="206041")
									{
										input.value=Math.ceil(wht_lock /1000);
									}
									else
									{
										if(jsonData.chargeList[k].gl_code=="204002N" && (scale_charge!="" || scale_charge!="0"))
										{
											input.value=scale_charge;
										}
										else
										{
											if(jsonData.chargeList[k].gl_code=="309000" && (rpc_charge!="" || rpc_charge!="0"))
											{
												input.value=rpc_charge;
											}
											else
											{
												input.value=jsonData.chargeList[k].Qty;
											}
										}
									}
								}
							}

							input.style.width = "20px";
							td5.appendChild(input);

							var td6 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "qday" + k;
							if(jsonData.chargeList[k].gl_code=="501005" || jsonData.chargeList[k].gl_code=="502000N" || jsonData.chargeList[k].gl_code=="503000N")
							{
								input.value=0;
							}
							else
							{
								input.value=jsonData.chargeList[k].qday;
							}
							input.style.width = "20px";
							td6.appendChild(input);

							var td7 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "weight" + k;
							input.value=jsonData.chargeList[k].cont_weight;
							input.style.width = "40px";
							td7.appendChild(input);

							var td8 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "mlwf"+k;
							input.id = "mlwfAmount" + k;
							//input.value=jsonData.chargeList[k].totMlwf;
							// comment by both awal and sourav for coming mlwf from ajax controller
							if(jsonData.chargeList[k].gl_code=="501005"  || jsonData.chargeList[k].gl_code=="502000N")
							{
								alert("ok");
								input.value=parseFloat(jsonData.chargeList[k].amt*0.04).toFixed(2);
							}
							else
							{
								input.value="0";
							}

							// input.value="0";
							input.style.width = "40px";
							td8.appendChild(input);

							var td9 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "vatTK"+k;
							input.id = "vatAmount"+k;
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="")
							{
								input.value=(hc_charge * hc_rcv_amount)*0.15;
							}
							else
							{
								if(jsonData.chargeList[k].gl_code=="204002N" && scale_charge!="")
								{
									input.value=(scale_charge * jsonData.chargeList[k].tarrif_rate)*0.15;
								}
								else
								{
									if(jsonData.chargeList[k].gl_code=="309000" && rpc_charge!="")
									{
										input.value=(rpc_charge * jsonData.chargeList[k].tarrif_rate)*0.15;
									}
									else
									{
										input.value=jsonData.chargeList[k].vatTK;
									}
								}
							}
							input.style.width = "40px";
							td9.appendChild(input);

							var td10 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "amt"+k;
							input.id = "portAmount"+k;
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="")
							{
							input.value=hc_charge * hc_rcv_amount;
							}
							else
							{
								if(jsonData.chargeList[k].gl_code=="204002N" && scale_charge!="")
								{
									input.value=scale_charge * jsonData.chargeList[k].tarrif_rate;
								}
								else
								{
									if(jsonData.chargeList[k].gl_code=="309000" && rpc_charge!="")
									{
										input.value=rpc_charge * jsonData.chargeList[k].tarrif_rate;
									}
									else
									{
										input.value=jsonData.chargeList[k].amt;
									}
								}
							}
							input.style.width = "40px";
							td10.appendChild(input);

							var td11 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "button";
							input.style.background="#CCAEDE";
							input.name = "mlwf";
							input.onclick=function(event){deleteRow(this)};
							input.value="DELETE";
							input.style.width = "80px";
							input.style.textAlign = "center";
							td11.appendChild(input);

							tr.appendChild(td1);
							tr.appendChild(td2);
							tr.appendChild(td3);
							tr.appendChild(td4);
							tr.appendChild(td5);
							tr.appendChild(td6);
							tr.appendChild(td7);
							tr.appendChild(td8);
							tr.appendChild(td9);
							tr.appendChild(td10);
							tr.appendChild(td11);

							table.appendChild(tr);
							//var amount="amt"+k;
							portSum += parseFloat(document.getElementById("portAmount"+k).value); 
							vatSum += parseFloat(document.getElementById("vatAmount"+k).value); 
							mlwfSum += parseFloat(document.getElementById("mlwfAmount"+k).value); 
						}
					}		// for loop
					
					document.getElementById("total_port").value=parseFloat(portSum).toFixed(2);
					document.getElementById("total_vat").value=parseFloat(vatSum).toFixed(2);
					document.getElementById("total_mlwf").value=parseFloat(mlwfSum).toFixed(2);

					document.getElementById("grand_total").value= parseFloat(portSum + vatSum + mlwfSum).toFixed(2);
					//document.getElementById("loadingImg").style.display="none";
					document.getElementById("tbl_total_row").value=table.rows.length-2;

					//var hidden_val = document.getElementById('forBill').getAttribute('data-value');
					//document.getElementById('type_id').value = hidden_val;
					var ename = "";
					var echarge = "";
					var glcode = "";
					var tariffid = "";
					var weight = "";
					var mlwfAmt = "";
					var vatAmt = "";
					var portAmt = "";
					for (var x = 0; x < jsonData.rtnBillList.length; x++) 
					{
						if(jsonData.rtnBillList[x].equipment_charge != null)
						{
							ename = jsonData.rtnBillList[x].equipment_name;
							echarge = jsonData.rtnBillList[x].equipment_charge;
							glcode = jsonData.rtnBillList[x].glcode;
							tariffid = jsonData.rtnBillList[x].tariffid;
							if(glcode=="501005" || glcode=="502000N")
							{
								mlwfAmt=parseFloat(jsonData.rtnBillList[x].tamt*0.04).toFixed(2);
							}
							else
							{
								mlwfAmt="0";
							}		
							vatAmt=echarge*0.15;	
							if(echarge == null)
							{
								portAmt="0";
							}
							else
							{
								portAmt = echarge;
							}
						}
					}
					
					
					for (var y = 0; y < jsonData.chargeList.length; y++) 
					{
						weight = jsonData.chargeList[y].cont_weight;
					}
					
					if(tariffid!="" && echarge!="")
					{
						addEquipmentInfo(ename,echarge,glcode,tariffid,weight,mlwfAmt,vatAmt,portAmt,contStatus);
					}					/**********************ADD BILL TO TABLE END*******************************/
				}		// LCL block end
				else if(contStatus=="FCL")		// ####################	FCL ####################
				{
				//	alert("in FCL");
					
					/**********************ADD BILL TO TABLE START*******************************/
					var  table = document.getElementById("mytbl");

					var  hc_charge = document.getElementById('hc_charge').value;
					var  hc_rcv_unit = document.getElementById('hc_rcv_unit').value;
					var  hc_rcv_amount = document.getElementById('hc_rcv_amount').value;
					var  rpc_charge = document.getElementById('rpc').value;
					var  scale_charge = document.getElementById('scale_charge').value;

					var  wht_in_shed = document.getElementById('wht_in_shed').value;
					var  wht_lock = document.getElementById('wht_lock').value;

					var portSum=0;
					var vatSum=0;
					var mlwfSum=0;
					removeTableElement(table);
					
					for (var k = 0; k < jsonData.chargeList.length; k++) 
					{
					//	alert("k : "+k);		// start from this alert for correct value of qty
						if(jsonData.chargeList[k].qday>0)
						{
							var tr = document.createElement('tr'); 
							tr.className ="gridDark";
				
							var td1 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "tarrif_id" + k;
							input.value=jsonData.chargeList[k].tarrif_id;
							input.style.width = "300px";
							td1.appendChild(input);

							var td2 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "billHead" + k;
							input.value=jsonData.chargeList[k].description;
							input.style.width = "100px";
							td2.appendChild(input);

							var td3 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "option" + k;
							input.value=jsonData.chargeList[k].gl_code;
							input.style.width = "50px";
							td3.appendChild(input);

							var td4 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "rate" + k;
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="" || (hc_rcv_unit=="Pallet" && hc_rcv_unit=="Carton"))
							{
								//alert(" -- hc_rcv_amount -- "+hc_rcv_amount);
								input.value=jsonData.chargeList[k].tarrif_rate;
							}
							else
							{ 
							//	alert(" -- tarrif_rate -- "+jsonData.chargeList[k].tarrif_rate);
								input.value=jsonData.chargeList[k].tarrif_rate;
							}
							input.style.width = "40px";
							td4.appendChild(input);

							var td5 = document.createElement('td');			// qty column
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "qty" + k;
							
							
							// ##############
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="")		// HOSTING CHARGE
							{
							//	alert("-- hosting charge -- : "+hc_charge);
								//input.value=Math.ceil(hc_charge);		
								input.value=hc_charge;		
								//alert("IF : "+hc_charge);
							}
							else
							{
								if(jsonData.chargeList[k].gl_code=="204002N" && (scale_charge!="" || scale_charge!="0"))	//WEIGHMENT_CHARGE
								{
								//	alert("-- weighment charge --"+scale_charge);
									input.value=scale_charge;
								}
								else
								{
									if(jsonData.chargeList[k].gl_code=="309000" && (rpc_charge!="" || rpc_charge!="0"))		//REPAIRING_CHARGE
									{
									//	alert("-- repairing charge --"+rpc_charge);
										input.value=rpc_charge;
									}
									else
									{
									//	alert("-- Qty -- "+jsonData.chargeList[k].Qty);
										input.value=jsonData.chargeList[k].Qty;
									}
								}
							}
							// ##############
						//	input.value=jsonData.total_container;		
							input.style.width = "20px";
							td5.appendChild(input);

							var td6 = document.createElement('td');				
							// days columns - disable for 501001,501002,501003,505001,505002,505006, 503000N,204002N,309000,null(FLT)

							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "qday" + k;
							// if(jsonData.chargeList[k].gl_code=="501001" || jsonData.chargeList[k].gl_code=="501002" || jsonData.chargeList[k].gl_code=="501003" || jsonData.chargeList[k].gl_code=="505001" || jsonData.chargeList[k].gl_code=="505002" || jsonData.chargeList[k].gl_code=="505006")
								
							if(jsonData.chargeList[k].gl_code=="501001" || jsonData.chargeList[k].gl_code=="501002" || jsonData.chargeList[k].gl_code=="501003" || jsonData.chargeList[k].gl_code=="505001" || jsonData.chargeList[k].gl_code=="505002" || jsonData.chargeList[k].gl_code=="505006" || jsonData.chargeList[k].gl_code=="503000N" || jsonData.chargeList[k].gl_code=="204002N" || jsonData.chargeList[k].gl_code=="309000" || jsonData.chargeList[k].gl_code==null)
							{
								input.value="";
								input.disabled=true;
							}
							else
							{
								input.value=jsonData.chargeList[k].qday;		// not for river dues and lift on
							}
							input.style.width = "20px";
							td6.appendChild(input);

							var td7 = document.createElement('td');			// weight column - modification done
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "weight" + k;
						//	input.value=jsonData.chargeList[k].cont_weight;
							input.style.width = "40px";
							input.disabled = true;
							td7.appendChild(input);

							var td8 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "mlwf"+k;
							input.id = "mlwfAmount" + k;
							//input.value=jsonData.chargeList[k].totMlwf;
							// comment by both awal and sourav for coming mlwf from ajax controller
							if(jsonData.chargeList[k].gl_code=="501001" || jsonData.chargeList[k].gl_code=="501002" || jsonData.chargeList[k].gl_code=="501003" || jsonData.chargeList[k].gl_code=="502000N")
							{
								input.value=parseFloat(jsonData.chargeList[k].amt*0.04).toFixed(2);
							}
							else
							{
								input.value="0";
							}
							// comment by both awal and sourav for coming mlwf from ajax controller

							// input.value="0";
							input.style.width = "40px";
							td8.appendChild(input);

							var td9 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "vatTK"+k;
							input.id = "vatAmount"+k;
							//alert(hc_charge+"___"+hc_rcv_amount);
							// ---Sumon re-arrange vatinfo for hosting, weighment and repairing charge--
							if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="")
							{
								if(vatChecked==1)
								{
									input.value=(hc_charge * jsonData.chargeList[k].tarrif_rate)*0.15;
								}
								else
								{
									input.value=0;
								}
							}
							else if(jsonData.chargeList[k].gl_code=="204002N" && scale_charge!="")
							{
								if(vatChecked==1)
								{
									input.value=(scale_charge * jsonData.chargeList[k].tarrif_rate)*0.15;
								}
								else
								{
									input.value=0;
								}
							}
							else if(jsonData.chargeList[k].gl_code=="309000" && rpc_charge!="")
							{
								if(vatChecked==1)
								{
									input.value=(rpc_charge * jsonData.chargeList[k].tarrif_rate)*0.15;
								}
								else
								{
									input.value=0;
								}
							}
							else
							{ 
								
								input.value=jsonData.chargeList[k].vatTK;
								
							}
							input.style.width = "40px";
							td9.appendChild(input);

							var td10 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "text";
							input.name = "amt"+k;
							input.id = "portAmount"+k;
							 if(jsonData.chargeList[k].gl_code=="503000N" && hc_charge!="")
							{
							input.value=hc_charge * jsonData.chargeList[k].tarrif_rate;
							}
							else
							{ 
								if(jsonData.chargeList[k].gl_code=="204002N" && scale_charge!="")
								{
									input.value=scale_charge * jsonData.chargeList[k].tarrif_rate;
								}
								else
								{
									if(jsonData.chargeList[k].gl_code=="309000" && rpc_charge!="")
									{
										input.value=rpc_charge * jsonData.chargeList[k].tarrif_rate;
									}
									else
									{
										input.value=jsonData.chargeList[k].amt;
									}
								}
							}
							input.style.width = "40px";
							td10.appendChild(input);

							var td11 = document.createElement('td');
							//td1.colSpan="2";
							var input = document.createElement("input");
							input.type = "button";
							input.style.background="#CCAEDE";
							input.name = "mlwf";
							input.onclick=function(event){deleteRow(this)};
							input.value="DELETE";
							input.style.width = "40px";
							td11.appendChild(input);

							tr.appendChild(td1);
							tr.appendChild(td2);
							tr.appendChild(td3);
							tr.appendChild(td4);
							tr.appendChild(td5);
							tr.appendChild(td6);
							tr.appendChild(td7);
							tr.appendChild(td8);
							tr.appendChild(td9);
							tr.appendChild(td10);
							tr.appendChild(td11);

							table.appendChild(tr);
							//var amount="amt"+k;
							portSum += parseFloat(document.getElementById("portAmount"+k).value); 
							vatSum += parseFloat(document.getElementById("vatAmount"+k).value); 
							mlwfSum += parseFloat(document.getElementById("mlwfAmount"+k).value); 
						}
					}		// for loop
					
					document.getElementById("total_port").value=parseFloat(portSum).toFixed(2);
					document.getElementById("total_vat").value=parseFloat(vatSum).toFixed(2);
					document.getElementById("total_mlwf").value=parseFloat(mlwfSum).toFixed(2);

					document.getElementById("grand_total").value= parseFloat(portSum + vatSum + mlwfSum).toFixed(2);
					//document.getElementById("loadingImg").style.display="none";
					document.getElementById("tbl_total_row").value=table.rows.length-2;
				}				// FCL block end												
			}
		}
	}
	
	function billForEvent(val)
	{
		if(val=='FCL CONTAINER')
		{
			document.getElementById("unstfDt").readOnly = true;
		}
		else
		{
			document.getElementById("unstfDt").readOnly = false;
		}
		
	}
 

</script>
<style>
input {
   
    width: 100px;
  
}
input:focus {
    background-color: #F3F781;
}
select:focus {
    background-color: #F3F781;
}
 table {border-collapse: collapse;}
			 .left{
					width:230px;
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
			/* The Modal (background) */
				.modal {
					display: none; /* Hidden by default */
					position: fixed; /* Stay in place */
					z-index: 1; /* Sit on top */
					padding-top: 100px; /* Location of the box */
					left: 0;
					top: 0;
					width: 100%; /* Full width */
					height: 100%; /* Full height */
					overflow: auto; /* Enable scroll if needed */
					background-color: rgb(0,0,0); /* Fallback color */
					background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
				}

				/* Modal Content */
				.modal-content {
					position: relative;
					background-color: #fefefe;
					margin: auto;
					padding: 0;
					border: 1px solid #888;
					width: 80%;
					box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
					-webkit-animation-name: animatetop;
					-webkit-animation-duration: 0.4s;
					animation-name: animatetop;
					animation-duration: 0.4s
				}

				/* Add Animation */
				@-webkit-keyframes animatetop {
					from {top:-300px; opacity:0} 
					to {top:0; opacity:1}
				}

				@keyframes animatetop {
					from {top:-300px; opacity:0}
					to {top:0; opacity:1}
				}

				/* The Close Button */
				.close {
					color: white;
					float: right;
					font-size: 28px;
					font-weight: bold;
				}

				.close:hover,
				.close:focus {
					color: #000;
					text-decoration: none;
					cursor: pointer;
				}

				.modal-header {
					padding: 2px 16px;
					background-color: #5858FA;
					color: white;
					align:center;
				}

				.modal-body {padding: 2px 16px;}

				.modal-footer {
					padding: 2px 16px;
					background-color: #5858FA;
					color: white;
				}

				/* th,td{
					height:20px;
				} */

				input[type=text],input[type=date],input[type=search]{
					height:20px;
					padding:0px;
				} 


</style>
<body onload="getData();">
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form id="myChkForm" name="myChkForm" action="<?php echo site_url('ShedBillController/billGenerationFormToDB');?>" method="post" onkeypress="return event.keyCode != 13" onsubmit="return validate()">
						<input type="hidden" id="formType"  name="formType" value="<?php echo $formType; ?>" />
						<table class="table" width="100%" align="center" cellspacing="0" cellpadding="0">
							<tr>
								<td>	 
									<table align="center" border="0" width="700px">	
										<tr align="left">
											<td style="color:black;">VERIFY NO :<em>&nbsp;</em></td>
											<td>
												<input type="text" class="form-control" style="width:120px;" tabindex="1" id="verify_num"  name="verify_num" value="<?php if($formType=="list") echo $verify_number; else echo "";?>" onblur="getBillData(this.value);" autofocus/>
												<input type="hidden" id="tbl_total_row"  name="tbl_total_row"/>
							
												<input type="hidden" id="vessel_name"  name="vessel_name"/>
												<input type="hidden" id="bl_no"  name="bl_no"/>
												<input type="hidden" id="container_size"  name="container_size"/>
												<input type="hidden" id="container_height"  name="container_height"/>
											</td>
											<td style="color:black;">DA BILL NO :<em>&nbsp;</em></td>
											<td>
												<input type="text" class="form-control" style="width:120px;" id="da_bill_no"  name="da_bill_no"/>
											</td>
											<td style="color:black;">BILL DATE :<em>&nbsp;</em></td>
											<td>
												<input type="date" class="form-control" style="width:130px; height:30px;" id="bill_date" value="<?php echo date("Y-m-d"); ?>" name="bill_date"/>
											</td>
										</tr>
										<tr>
											<!--td colspan="9"><div id="loadingImg" style="display:none;" ><img src="<?php echo IMG_PATH?>ajax-loader.gif" width="400" height="200" style="position:absolute;"/><span style="padding-bottom:5px;"><font color="red" size="4">Processing. Please wait untill process data......</font></span></div></td>
										</tr-->
									</table>
									
									<br><br>
									
									<div class="left">
										<fieldset>
											<legend><font size="1" color="blue"><u>IGM INFO:</u></font></legend>
											<table height="310px">
												<tr>
													<td> BILL NO</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px;height:30px;" id="bill_no"  name="bill_no"/></td>
												</tr>
												<tr>
													<td> REG. NO</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="rotation_no"  name="rotation_no"/></td>
												</tr>
												<tr>
													<td> ARRAIVAL DT</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="arr_dt"  name="arr_dt"/>
													</td>
												</tr>
												<tr>
													<td> COMML DT</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="comm_dt"  name="comm_dt"/>
													</td>
												</tr>
												<tr>
													<td> W/R ST DT</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="wr_dt"  name="wr_dt"/>
													</td>
												</tr>
												<tr>
													<td> A.D.O NO</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="ado_no"  name="ado_no"/></td>
												</tr>
												<tr>
													<td> A.D.O DATE</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="ado_dt"  name="ado_dt"/>
													</td>
												</tr>
												<tr>
													<td> A.D.O UPTO</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="ado_upto"  name="ado_upto"/>
													</td>
												</tr>
												<tr>
													<td> ONE STOP PT</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="one_stop_point" name="one_stop_point"/></td>
												</tr>
											</table>
										</fieldset>
									</div>
									
									<div class="middle">
										<fieldset>
											<legend><font size="1" color="blue"><u>OTHER INFO:</u></font></legend>
											<table height="280px">
												<tr>
													<td> EXCH RATE</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="ex_rate"  name="ex_rate"/></td>
												</tr>
												<tr>
													
												</tr>
												<tr>
													<td> BILL FOR</td>
													<td>:</td>
													<td><input tabIndex="2" type="text" class="form-control" style="width:130px; height:30px;" id="bill_for"  name="bill_for" list="forBill" onblur="billForEvent(this.value)" />
																		
														   
															<datalist id="forBill">
																<?php 
																for($i=0;$i<count($getRcvAllQry);$i++)
																{
																  echo '<option value="'.$getRcvAllQry[$i]['billFor'].'">'.$getRcvAllQry[$i]['billFor'].'</option>';
																  
																  	//echo '<option value="'.$rowrcvAllQry->billFor.'">'.$rowrcvAllQry->billFor.'</option>';
																}
																?>
															</datalist>
																				
															
														  <!--option value="LCL CONTAINER">
														  <option value="LCL TRANSHIPMENT">
														  <option value="CAR NEW">
														  <option value="CAR OLD">
														  <option value="FCL CONTAINER">
														  <option value="DEPO CONTAINER">
														  <option value="SUSPECT CONTAINER">
														  <option value="FCL TRANSHIPMENT"-->
														</datalist>
														<!--input type="text" name="type_id" id="type_id"-->
													</td>
												</tr>
												<tr>
													<td> UNSTF DT</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="unstfDt"  name="unstfDt" tabindex="4" /> <!--onkeypress="return addBillToTable(event,this.value)"-->
													</td>
												</tr>
												<tr>
													<td> W/R BILL UP TO</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="wr_upto_dt"  name="wr_upto_dt" tabindex="3"/>
													</td>
												</tr>
												<tr>
													<td> BE NO</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="be_no"  name="be_no"/></td>
												</tr>
												<tr>
													<td> BE DATE</td>
													<td>:</td>
													<td><input type="date" class="form-control" style="width:130px; height:30px;" id="be_dt"  name="be_dt"/>
													</td>
												</tr>
												<tr>
													<td> LESS (% WAVE)</td>
													<td>:</td>
													<td><input class="form-control" style="width : 60px; height:30px;" type="text" id="less"  name="less"/></td>
												</tr>
												<tr>
													<td> PART BL</td>
													<td>:</td>
													<td><input type="text" class="form-control" style="width:130px; height:30px;" id="part_bl"  name="part_bl"/></td>
												</tr>
											</table>
										</fieldset>
									</div>
									
									<div class="right">
										<fieldset>
											<legend><font size="1" color="blue"><u>CONTAINER:</u></font></legend>
											<div>
												<div class="left_container_fieldSet">				
													<table height="106px">
														<tr>
															<td></td>
															<td colspan="2"><u>GENERAL</u></td>
															<td colspan="2"><u>OPEN TOP</u></td>
															<td colspan="2"><u>FLAT TRACK</u></td>
														</tr>
														<tr>
															<td></td>
															<td><u>8.5</u></td>
															<td><u>9.5</u></td>
															<td><u>8.5</u></td>
															<td><u>9.5</u></td>
															<td><u>8.5</u></td>
															<td><u>9.5</u></td>
														</tr>
														<tr>
															<td>20</td>
															<td><input type="text" id="twnty_gen_eight_five" class="form-control" name="twnty_gen_eight_five" style="width:20px" ></td>
															<td><input id="twnty_gen_nine_five" class="form-control" name="twnty_gen_nine_five"style="width : 20px" type="text"></td>
															<td><input id="twnty_open_eight_five" class="form-control" name="twnty_open_eight_five"style="width : 20px" type="text"></td>
															<td><input id="twnty_open_nine_five" class="form-control" name="twnty_open_nine_five"style="width : 20px" type="text"></td>
															<td><input id="twnty_flat_eight_five" class="form-control" name="twnty_flat_eight_five"style="width : 20px" type="text"></td>
															<td><input id="twnty_flat_nine_five" class="form-control" name="twnty_flat_nine_five" style="width : 20px" type="text"></td>
														</tr>
														<tr>
															<td>40</td>
															<td><input id="forty_gen_eight_five" class="form-control" name="forty_gen_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_gen_nine_five" class="form-control" name="forty_gen_nine_five" style="width : 20px" type="text"></td>
															<td><input id="forty_open_eight_five" class="form-control" name="forty_open_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_open_nine_five" class="form-control" name="forty_open_nine_five" style="width : 20px" type="text"></td>
															<td><input id="forty_flat_eight_five" class="form-control" name="forty_flat_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_flat_nine_five" class="form-control" name="forty_flat_nine_five" style="width : 20px" type="text"></td>
														</tr>
														<tr>
															<td>45</td>
															<td><input id="forty_five_gen_eight_five" class="form-control" name="forty_five_gen_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_five_gen_nine_five" class="form-control" name="forty_five_gen_nine_five" style="width : 20px" type="text"></td>
															<td><input id="forty_five_open_eight_five" class="form-control" name="forty_five_open_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_five_open_nine_five" class="form-control" name="forty_five_open_nine_five" style="width : 20px" type="text"></td>
															<td><input id="forty_five_flat_eight_five" class="form-control" name="forty_five_flat_eight_five" style="width : 20px" type="text"></td>
															<td><input id="forty_five_flat_nine_five" class="form-control" name="forty_five_flat_nine_five" style="width : 20px" type="text"></td>
														</tr>
													</table>
													<br>
													<table height="50px;">
														<tr>
															<td>EXT MOV 20 : </td>
															<td><input id="ext_mov_twnty" class="form-control" name="ext_mov_twnty" style="width : 80px" type="text"></td>
														</tr>
														<tr>
															<td>EXT MOV 40 : </td>
															<td><input id="ext_mov_forty" class="form-control" name="ext_mov_forty" style="width : 80px" type="text"></td>
														</tr>
													</table>
												</div>
												<div class="right_container_fieldSet">
													<table align="center" width="170px">
														<tr>
															<td>QTY</td>
															<td>:</td>
															<td><input id="cont_qty" class="form-control" name="cont_qty" style="width : 90px" type="text"></td>
															<td>WEIGHT</td>
															<td>:</td>
															<td><input id="cont_wht" class="form-control" name="cont_wht" style="width : 90px" type="text"></td>
														</tr>
													</table>
													<fieldset id="lcl_fieldset">
														<legend>LCL</legend>
														<table height="86px" width="150px">
															<tr>
																<td></td>
																<td></td>
																<td>QTY</td>
																<td>WEIGHT</td>
															</tr>
															<tr>
																<td>IN SHED</td>
																<td>:</td>
																<td><input id="qty_in_shed" class="form-control"name="qty_in_shed" style="width : 40px" type="text"></td>
																<td><input id="wht_in_shed" class="form-control" name="wht_in_shed"style="width : 40px" type="text"></td>
															</tr>
															<tr>
																<td>LOCK FAST</td>
																<td>:</td>
																<td><input id="qty_lock" class="form-control" name="qty_lock" style="width : 40px" type="text"></td>
																<td><input id="wht_lock" class="form-control" name="wht_lock" style="width : 40px" type="text"></td>
															</tr>
															<tr>
																<td>OUT SHED</td>
																<td>:</td>
																<td><input id="qty_out_shed" class="form-control" name="qty_out_shed" style="width : 40px" type="text"></td>
																<td><input id="wht_out_shed" class="form-control" name="wht_out_shed" style="width : 40px" type="text"></td>
															</tr>
														</table>
													</fieldset>
													<br>
													<table align="center" height="40px">
														<tr align="">
															<td>Remarks : </td>
															<td><TextArea id="remarks" class="form-control" rows="1" name="remarks" style="width : 160px"></TextArea></td>
														</tr>
													</table>
												</div>

												<table height="46px" width="400px">
													<tr>
														<td>C&F CODE & NAME </td>
														<td><input id="cnfCode" name="cnfCode" class="form-control" style="width : 50px" type="text" onkeypress="return getCnfCode(event,this.value)"></td>
														<td><input id="cnfName" class="form-control" name="cnfName"style="width : 220px" type="text"></td>						
													</tr>
													<tr>
														<td>IMPO.REG NO.& NAME </td>
														<td><input id="impo_reg_no" name="impo_reg_no" class="form-control" style="width : 50px" type="text"></td>
														<td><input id="impo_reg_name" class="form-control" name="impo_reg_name" style="width : 220px" type="text"></td>	
													</tr>						
												</table>

											</div>
										</fieldset>				
									</div>
									
									</br>
									<table style="border:1px solid #ddd;" width="100%" height="45px;" align="center" cellspacing="0" cellpadding="0">						
										<tr align="left" >
											<td style="color:black;">RPC:</td>
											<td >
												<input type="text" class="form-control" style="width:150px;" id="rpc"  name="rpc" tabindex="5"/>   
											</td>
											<td style="color:black;">HC CHARGE:</td>
											<td>
												<input type="text" class="form-control" style="width:150px" id="hc_charge"  name="hc_charge" tabindex="6"/>
												<input type="hidden" id="hc_rcv_unit"  name="hc_rcv_unit" />
												<input type="hidden" id="hc_rcv_amount"  name="hc_rcv_amount" />
											</td>
											<td style="color:black;">SCALE CHARGE:</td> <!-- WEIGHMENT CHARGE -->
											<td>
												<input type="text" class="form-control" style="width:150px" id="scale_charge"  name="scale_charge" tabindex="7"/> <!--onkeypress="return addBillToTable(event,this.value)"-->
											</td>
											<td style="color:black;">VAT:</td>
											<td>
												<select class="form-control" name="vat" id="vat" onkeypress="changeToMlwf(event)" tabindex="8">
													<option value="1">Yes</option> 
													<option value="0">No</option> 
												</select>
											</td>
											<td style="color:black;">MLWF :<em>&nbsp;</em></td>
											<td>
												<select class="form-control" name="mlwf" id="mlwf" onblur="return addBillToTable(event)" tabindex="9">
													<option value="1">Yes</option> 
													<option value="0">No</option> 
												</select>
											</td>
										</tr>
									</table>
									
									<br>
									
									<div>
										<div class="left_tbl_div" style="overflow:auto;width:650px;">
											<table id="mytbl" class="mytbl table table-bordered" >
												<thead>
													<tr>
														<td align="center"><b>CHOICE</b></td>
														<td align="center"><b>BILL HEAD</b></td>
														<td align="center"><b>OPTION</b></td>
														<td align="center"><b>RATE</b></td>
														<td align="center"><b>QTY</b></td>											
														<td align="center"><b>DAYS</b></td>
														<td align="center"><b>WT</b></td>
														<td align="center"><b>MLWF(TK)</b></td>
														<td align="center"><b>VAT(TK)</b></td>
														<td align="center"><b>PORT(TK)</b></td>
														<td align="center"><b>ACTION</b></td>
													</tr>
												</thead>
												<tbody>
													<tr>							
														<td>
															<input style="white-space:pre-wrap;background-color:#FF9;width: 300px;" class="form-control" type="search" list="tarrif" placeholder="Pick a Tarrif ID" name="bill_tarrif" oninput="return addRow(this.value)">							
															<datalist id="tarrif">
																<?php 

																for($i=0;$i<count($getBillTarrif);$i++)
																{
																  echo '<option value="'.$getBillTarrif[$i]['id'].'">'.$getBillTarrif[$i]['id'].'('.$getBillTarrif[$i]['amount'].')'.'</option>';
																}
																?>
															</datalist>
															
															<!--select class="" style="width : 100px" onchange="return addRow(this.value)">
																<?php 

																for($i=0;$i<count($getBillTarrif);$i++)
																{
																  echo '<option value="'.$getBillTarrif[$i]['id'].'">'.$getBillTarrif[$i]['id'].'</option>';
																}
																?>
															</select-->
														</td>
														<td><input type="text" class="form-control" id="bill_head_tbl" style="width:100px"name="bill_head_tbl"/></td>
														<td style="display:none;"><input type="hidden" id="tarrif_id_tbl" style="width : 150px" name="tarrif_id_tbl"/></td>
														<td><input type="text" class="form-control" id="option_tbl" style="width : 50px" name="option_tbl"/></td>
														<td><input type="text" class="form-control" id="rate_tbl" style="width : 40px" name="rate_tbl"/></td>
														<td><input type="text" class="form-control" id="qty_tbl" style="width : 20px" name="qty_tbl"/></td>
														<td><input type="text" class="form-control" id="days_tbl" style="width : 30px" name="days_tbl"/></td>
														<script>
															$('#days_tbl').keyup(function() {												
															var tot_amount= parseFloat($("#days_tbl").val())* parseFloat($("#qty_tbl").val())*parseFloat($("#rate_tbl").val());
															var tot_vat= (parseFloat($("#days_tbl").val())* parseFloat($("#qty_tbl").val())*parseFloat($("#rate_tbl").val()))*0.15;
																$("#port_tbl").val(tot_amount);
																$("#vat_tbl").val(tot_vat);
																});	
														</script>
														<td><input type="text" class="form-control" id="wt_tbl" style="width : 40px" name="wt_tbl"/></td>
														<td><input type="text" class="form-control" id="mlwf_tbl" style="width : 60px" name="mlwf_tbl"/></td>
														<td><input type="text" class="form-control" id="vat_tbl" style="width : 40px" name="vat_tbl"/></td>
														<td><input type="text" class="form-control" id="port_tbl" style="width : 60px" name="port_tbl"/></td>
														<td><input type="button" class="btn btn-success" style="width:80px;height:20px;padding:0px;" value="ADD" class="login_button" onclick="addTableRow()"/></td>
													</tr>
												</tbody>
											</table>					
										</div>
													
										<div class="right_tbl_div" align="right" style="width:250px;">
											<table height="120px;">
												<tr>
													<td>TOTAL PORT</td>
													<td>:</td>
													<td><input type="text" class="form-control" id="total_port" style="width : 100px" name="total_port" value=0 /></td>
												</tr>
												<tr>
													<td>TOTAL VAT</td>
													<td>:</td>
													<td><input type="text" class="form-control" id="total_vat" style="width : 100px" name="total_vat" value=0 /></td>
												</tr>
												<tr>
													<td>TOTAL MLWF</td>
													<td>:</td>
													<td><input type="text" class="form-control" id="total_mlwf" style="width : 100px" name="total_mlwf" value=0 /></td>
												</tr>
												<tr>
													<td>LESS AMOUNT PORT</td>
													<td>:</td>
													<td><input type="text" class="form-control" id="less_amt_port" style="width : 100px" name="less_amt_port" /></td>
												</tr>
												<tr>
													<td>LESS AMOUNT VAT</td>
													<td>:</td>
													<td><input type="text" class="form-control" id="less_amt_vat" style="width : 100px"name="less_amt_vat"/></td>
												</tr>
											</table>
											</br>
											<table height="50px">
												<td>G.TOTAL</td>
												<td>:</td>
												<td><input type="text" class="form-control" id="grand_total" style="width : 100px" name="grand_total"/></td>
											</table>
										</div>
									</div>
								
						<div align="center">
							<table border="0">
								<tr>
									<td><input tabindex="10" id="save_btn" class="login_button" type="submit" style="width : 100px" value="SAVE"/></td>
					</form>
					<td id="btnViewBill" style="display:none;">
						<form action="<?php echo site_url('ShedBillController/getShedBillPdf');?>" method="post" target="_blank">
							<input type="hidden" id="sendVerifyNo" name="sendVerifyNo">									
							<input type="submit" value="VIEW BILL"  class="login_button" style="width:100px;">							
						</form> 							
					</td>
					<td><input  id="preViewBtn" onclick="GetBillToModal()" class="login_button" type="button" style="width : 100px" value="PENAL BILL"/></td>
							<!--td>
								<form action="<?php echo site_url('ShedBillController/getShedBillPdf')?>" name="myChkForm" target="_blank" method="POST" onsubmit="return validate();">						
									<input type="hidden"  id="sendVerifyNo" name="sendVerifyNo" value="<?php echo $verify_num;?>">									
									<input type="submit" value="VIEW BILL"  class="login_button" style="width:100%;">							
								</form> 							
							</td-->
							<td><input class="login_button" type="button" style="width : 100px" value="MANUAL BILL"/></td>
							<td><input class="login_button" type="button" style="width : 100px" value="ROUGH BILL"/></td>
							<td><input class="login_button" type="button" style="width : 100px" value="UNDERCHARGE"/></td>
							<td><input class="login_button" type="button" style="width : 100px" value="DELETE BILL"/></td>
							<td><a href="<?php echo site_url('ShedBillController/billGenerationForm') ?>">
								<input class="login_button" type="button" style="width : 100px" value="CLEAR"/></a>
							</td>
						</tr>
						<tr>
							<!-- <td colspan="8"><iframe name="msgbar" id="msgbar" width="800" height="50" frameborder="0"></iframe></td> -->
							<td colspan="8">
								<?php if(isset($msg)){ echo $msg;}?>
							</td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
