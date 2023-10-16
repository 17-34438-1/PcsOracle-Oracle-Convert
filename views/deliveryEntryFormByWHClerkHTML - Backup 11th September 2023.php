<script language="JavaScript">

	
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
	}); */
	
	$(document).on('keypress', 'input,select', function (e) 
	{
		if (e.which == 13) 
		{
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


	function getBlInfo(blNo)  
	{   
		var rotNo=document.getElementById("rotNo").value;
		
		if(blNo=="" || blNo==" " || rotNo=="" || rotNo==" ")
		{
			if(rotNo=="" || rotNo==" ")
			{
				alert("Reg No Field is blank");
				document.getElementById("rotNo").focus();
				return false;
			}			
			if(blNo=="" || blNo==" ")
			{
				alert("BL Field is blank");
				document.getElementById("rotNo").focus();
				return false;
			}
		}
		else
		{		
		
			document.getElementById("verifyNo").value="";
			document.getElementById("oneStopPoint").value="";
			document.getElementById("doQuantity").value="";
			document.getElementById("doUnit").value="";

			document.getElementById("marks").value="";
			document.getElementById("mloCode").value="";
			
			document.getElementById("doNo").value="";
			document.getElementById("doDate").value="";
			document.getElementById("validUpToDate").value="";			
		
			// paperFileDate  exitNoteNum   date  truckNum cusOrderNo cusOrderDate
			document.getElementById("description").value="";
			
			document.getElementById("importerName").value="";
			document.getElementById("grossWeight").value="";
			
			document.getElementById("mloLine").value="";
			document.getElementById("forwarderLine").value="";			
			
			document.getElementById("billOfEntryNo").value="";
			document.getElementById("billOfEntryDate").value="";
			
			document.getElementById("shedTallyInfoID").value=""; 
			document.getElementById("netWeight").value="";
			document.getElementById("doIssuedBy").value="";
			document.getElementById("cnf_lic").value="";
			document.getElementById("cnfName").value="";  
			document.getElementById("paperFileDate").value="";  
			document.getElementById("exitNoteNum").value="";  
			document.getElementById("date").value="";  
			document.getElementById("truckNum").value="";  
			document.getElementById("cusOrderNo").value="";  
			document.getElementById("cusOrderDate").value="";  
			//alert("q");
			document.getElementById("invoiceAmount").value="";
			
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
			//alert(blNo+"&rotNo="+rotNo);
			rotNo=rotNo.replace("/","_");
			xmlhttp.onreadystatechange=stateChangegetBLInfo;
			var url="<?php echo site_url('AjaxController/getDeliveryByBLInfo')?>?blNo="+blNo+"&rotNo="+rotNo;
			// alert(url);
			xmlhttp.open("GET",url,false);	
			
			xmlhttp.send();	 
		}
	}


	function stateChangegetBLInfo()
	{			
		// if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		// {			
			// var val = xmlhttp.responseText;
			
			// var jsonData = JSON.parse(val);
			
			// var seaNo=jsonData.seaInfo[0].reg_no;
		// //	alert(seaNo);
			// getSeaInfo(seaNo);
		// }
		//alert(xmlhttp.responseText);
		
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			var val = xmlhttp.responseText;
			// alert(xmlhttp.responseText);
			var jsonData = JSON.parse(val);
			// alert(jsonData);
			// if(jsonData.msg!='')
			// {
			// 	alert(jsonData.msg);
			// } 

			if(jsonData.exchnStatus!='yes')
			{
				if(jsonData.igmContList[0].cont_status=='LCL')
				{
					alert(jsonData.exchnStatus);
				}
			} 
			//alert(jsonData.deliveryList[0].description);
			// alert(jsonData);
			
			//DO AND RO START
			
			var imageLoc = jsonData.imageCount[0].do_image_loc;
			var imgNumber = jsonData.imageCount[0].rtnValue;
			//alert(imgNumber);
			if(imgNumber>0){
				//document.getElementById("doImage").disabled = false;
				//document.getElementById("doPdf").disabled = false;
				//document.getElementById("doImage").style.visibility="visible";
				//document.getElementById("doPdf").style.visibility="visible";
				$('#doImage').removeAttr('disabled');
				$('#doPdf').removeAttr('disabled');
				var imgpath="Report/releaseorderpdf/";
				var ropath="ReleaseOrderController/releaseOrderViewTos/";
				var pathpdf="igmViewController/DeliveryOrderPDFShow/";
				// var pathpdf="ShedBillController/shedDOPDF/";
				var bl = jsonData.imageCount[0].bl_no;
				bl = bl.replaceAll("/", "_");
				var rot = jsonData.imageCount[0].imp_rot;
				rot = rot.replace("/", "_");
				var id = jsonData.imageCount[0].id;
				var bl_type = jsonData.doData[0].bl_type;
				var sumitted_by = jsonData.doData[0].sumitted_by;
				var verifyNo = jsonData.rtnContainerList[0].verify_number;
				//var imagePath = "<?php //echo site_url('"+imgpath+bl+"/"+rot+"')?>";
				// var imagePath = "<?php //echo site_url('"+imgpath+bl+"/"+rot+"')?>";
				var imagePath = "<?php echo site_url('"+ropath+bl+"/"+rot+"/"+verifyNo+"')?>";
				//var imagePath = "../../assets/do_image/"+imageLoc;
				//alert(imagePath);
				var pdfPath = "<?php echo site_url('"+pathpdf+bl+"/"+rot+"/"+id+"/"+bl_type+"/"+sumitted_by+"')?>";
				document.getElementById("doImage").href=imagePath;
				document.getElementById("doPdf").href=pdfPath;
			}else{
				//document.getElementById("doImage").disabled = true;
				//document.getElementById("doPdf").disabled = true;
				//document.getElementById("doImage").style.visibility="hidden";
				//document.getElementById("doPdf").style.visibility="hidden";
				$('#doImage').attr('disabled','disabled');
				$('#doPdf').attr('disabled','disabled');
			}
			
			//DO AND RO END

			//Approve Button  Start 
			// alert("length");
			// alert(jsonData.approveRslt.length);
			if(jsonData.approveRslt.length>0)
			{
				var edoId = jsonData.approveRslt[0].id;
				var approveSt = jsonData.approveRslt[0].check_st;

				if(approveSt == 0)
				{
					$('#approve').removeAttr('disabled');
					document.getElementById("approve").innerHTML="Approve";
					document.getElementById("edoid").value=edoId;
				}
				else if(approveSt == 1)
				{
					$('#approve').attr('disabled','disabled');
					document.getElementById("approve").innerHTML="Approved!";
					document.getElementById("edoid").value="";
				}
				else
				{
					$('#approve').attr('disabled','disabled');
					document.getElementById("approve").innerHTML="Approve";
					document.getElementById("edoid").value="";
				}
			}
			// alert("ok 123");
			// alert(xmlhttp.responseText);
			// alert("ok 456");
			//Approve Button  End
			
			// console.log(jsonData);
			 $('#myTable').find('tbody').empty();
			for (var i = 0; i < jsonData.igmContList.length; i++) 
			{  
				$('#feedback').append('<tr><td align="center" class="read" style="width:120px;">' 
				+ jsonData.igmContList[i].cont_number + '</td><td align="center" class="read" style="width:90px;">' 
				+ jsonData.igmContList[i].cont_size + '</td><td align="center" class="read" style="width:60px;">' 
				+ jsonData.igmContList[i].cont_height +'</td><td align="center" class="read" style="width:90px;">' 
				+ jsonData.igmContList[i].cont_status +'</td></tr>');				
			} 
			
			var cnf_lic_no = "";
			var cnfDate = "";
			for(di=0;di < jsonData.deliveryList.length; di++)
			{
				document.getElementById("exitNoteNum").value=jsonData.deliveryList[di].exit_note_number;
				// alert("1");
				document.getElementById("paperFileDate").value=jsonData.deliveryList[di].paper_file_date;
				// alert("2");
				document.getElementById("cusOrderNo").value=jsonData.deliveryList[di].cus_rel_odr_no; 
				// alert("3");
				document.getElementById("cusOrderDate").value=jsonData.deliveryList[di].cus_rel_odr_date;
				document.getElementById("billOfEntryDate").value=jsonData.deliveryList[di].be_date;
				document.getElementById("billOfEntryNo").value=jsonData.deliveryList[di].be_no;
				cnf_lic_no = jsonData.deliveryList[0].cnf_lic_no;
				cnfDate = jsonData.deliveryList[0].cnfDate;
			}
			
			
			//alert(jsonData.seaInfo.length)
			if(jsonData.seaInfo.length!=0 )
		 	{
				document.getElementById("seaNumber").value=jsonData.seaInfo[0].reg_no;
				document.getElementById("billOfEntryNo").value=jsonData.seaInfo[0].reg_no;
				document.getElementById("billOfEntryDate").value=jsonData.seaInfo[0].reg_date;
				document.getElementById("invoiceAmount").value=jsonData.seaInfo[0].total_value;
				// alert("Total "+jsonData.seaInfo[0].total_value);
				document.getElementById("office_code").value=jsonData.seaInfo[0].office_code;
				document.getElementById("c_nubmber").value=jsonData.seaInfo[0].reg_no;
				document.getElementById("xml_date").value=jsonData.seaInfo[0].reg_date; 
				document.getElementById("cusOrderNo").value=jsonData.seaInfo[0].recp_no; 
				document.getElementById("cusOrderDate").value=jsonData.seaInfo[0].recp_date;  
				
				document.getElementById("cnfName").value=jsonData.seaInfo[0].cnf_name;						
				document.getElementById("cnf_lic").value=jsonData.seaInfo[0].cnf_lic;						
				document.getElementById("exitNoteNum").value=jsonData.seaInfo[0].exit_note_number; 
				
				if(jsonData.seaInfo[0].cnf_lic != cnf_lic_no)	
				{
					// alert(jsonData.seaInfo[0].cnf_lic);
					// alert(jsonData.deliveryList[0].cnf_lic_no);
					alert('EDO C&F not matched with Bill of Entry C&F.');
				}
				
			}
			else
			{
				alert('EDO C&F not found in Bill of Entry Information.');
			}
			
			
		
			
			//alert(jsonData.deliveryList[0].description); 
			// alert("cont_igm "+jsonData.cont_igm);
			// alert("bl ");
			
			// alert("bl "+jsonData.deliveryList[0].blNo);
			// alert(jsonData.deliveryList[0].cnf_lic_no);

			document.getElementById("cnf_lic").value=jsonData.cnfData[0].cnf_lic_no;						
			document.getElementById("cnfName").value=jsonData.cnfData[0].cnf_name;						
			document.getElementById("date").value=cnfDate;						

			document.getElementById("blNo").value=jsonData.deliveryList[0].blNo;						//2019-08-01 - intakhab
			document.getElementById("rotNo").value=jsonData.deliveryList[0].rotNo;						//			"
			document.getElementById("importerName").value=jsonData.deliveryList[0].Notify_name;			//			"
			document.getElementById("grossWeight").value=jsonData.deliveryList[0].Cont_gross_weight;	//			"
			document.getElementById("netWeight").value=jsonData.deliveryList[0].cont_weight; 			//			"
			document.getElementById("doQuantity").value=jsonData.deliveryList[0].Pack_Number;			//			"
			document.getElementById("doUnit").value=jsonData.deliveryList[0].Pack_Description;			//			"
			document.getElementById("marks").value=jsonData.deliveryList[0].Pack_Marks_Number;			//			"
			document.getElementById("mloLine").value=jsonData.deliveryList[0].master_BL_No;				//			"
			document.getElementById("mloCode").value=jsonData.deliveryList[0].mlocode;					//			"
			document.getElementById("forwarderLine").value=jsonData.deliveryList[0].blNo;				//			"
			document.getElementById("doIssuedBy").value=jsonData.deliveryList[0].Organization_Name; 	//			"
			document.getElementById("oneStopPoint").value=jsonData.deliveryList[0].one_stop_point;		//			"
			document.getElementById("doNo").value=jsonData.deliveryList[0].do_no;						//			"
			document.getElementById("doDate").value=jsonData.deliveryList[0].do_date;					//			"verify_number
			document.getElementById("validUpToDate").value=jsonData.deliveryList[0].valid_up_to_date;	//			"
			// alert(jsonData.deliveryList[0].description);
			document.getElementById("description").value=jsonData.deliveryList[0].description;			//			"
		
		//	document.getElementById("date").value=jsonData.seaInfo[0].paper_file_date;			//			"
			
			document.getElementById("shedTallyInfoID").value=jsonData.deliveryList[0].id;
		//	document.getElementById("paperFileDate").value=jsonData.seaInfo[0].paper_file_date;
		
			document.getElementById("truckNum").value=jsonData.deliveryList[0].no_of_truck;  

			document.getElementById("verifyNo").value=jsonData.deliveryList[0].verify_number;
			if(jsonData.deliveryList[0].verify_number!=null)
			{			
				document.getElementById('btn').style.display="none";
				document.getElementById('vmsg').style.display="block";
				alert("Already Verified.")	
			}
									
			for (var i = 0; i < jsonData.commLandDate.length; i++) 
			{
				//alert(jsonData.commLandDate[i].rtnValue);
				document.getElementById("commLandDate").value=jsonData.commLandDate[i].RTNVALUE;

			}					
		}
	}
	
	/* Get Info By B/E Number */
	function getSeaInfo(seaNo)  
	{
		if(seaNo=="" || seaNo==" ")
		{
			if(seaNo=="" || seaNo==" ")
			{
				alert("C NUMBER Can not be blank");				
			}
		}
		else
		{
			document.getElementById("rotNo").value=""; 
			document.getElementById("blNo").value=""; 
			document.getElementById("verifyNo").value="";
			document.getElementById("oneStopPoint").value="";
			document.getElementById("doQuantity").value="";
			document.getElementById("doUnit").value="";

			document.getElementById("marks").value="";
			document.getElementById("mloCode").value="";
			
			document.getElementById("doNo").value="";
			document.getElementById("doDate").value="";
			document.getElementById("validUpToDate").value="";
		
			// paperFileDate  exitNoteNum   date  truckNum cusOrderNo cusOrderDate
			document.getElementById("description").value="";
			
			document.getElementById("importerName").value="";
			document.getElementById("grossWeight").value="";
			
			document.getElementById("billOfEntryNo").value="";
			document.getElementById("billOfEntryDate").value="";
			
			document.getElementById("forwarderLine").value="";
			document.getElementById("mloLine").value="";

			document.getElementById("shedTallyInfoID").value=""; 
			document.getElementById("netWeight").value="";
			document.getElementById("doIssuedBy").value="";
			document.getElementById("cnf_lic").value="";
			document.getElementById("cnfName").value="";  
			document.getElementById("paperFileDate").value="";  
			document.getElementById("exitNoteNum").value="";  
			document.getElementById("date").value="";  
			document.getElementById("truckNum").value="";  
			document.getElementById("cusOrderNo").value="";  
			document.getElementById("cusOrderDate").value="";  			
			
			document.getElementById("date").value="";		
			
			document.getElementById("invoiceAmount").value="";

			$('#doImage').attr('disabled','disabled');
			$('#doPdf').attr('disabled','disabled');
			
			if (window.XMLHttpRequest) 
			{				
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  				
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=stateChangegetSEAInfo;
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getDeliveryBySeaInfo')?>?seaNo="+seaNo,false);	
		
			xmlhttp.send();	 
		}
	}


	function stateChangegetSEAInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			//alert(xmlhttp.responseText);
			
			var val = xmlhttp.responseText;			
			
			var jsonData = JSON.parse(val);
			// alert(jsonData.imageCount[0].do_image_loc);
			
			//DO AND RO START
			
			var imageLoc = jsonData.imageCount[0].do_image_loc;
			var imgNumber = jsonData.imageCount[0].rtnValue;
			//alert(imgNumber);
			
			if(imgNumber>0){
				//document.getElementById("doImage").disabled = false;
				//document.getElementById("doPdf").disabled = false;
				//document.getElementById("doImage").style.visibility="visible";
				//document.getElementById("doPdf").style.visibility="visible";
				$('#doImage').removeAttr('disabled');
				$('#doPdf').removeAttr('disabled');
				var imgpath="ReleaseOrderController/releaseorderpdf/";
				var ropath="ReleaseOrderController/releaseOrderViewTos/";
				var pathpdf="igmViewController/DeliveryOrderPDFShow/";
				var bl = jsonData.imageCount[0].bl_no;
				bl = bl.replaceAll("/", "_");
				var rot = jsonData.imageCount[0].imp_rot;
				rot = rot.replace("/", "_");
				var id = jsonData.imageCount[0].id;
				// var imagePath = "<?php //echo site_url('"+imgpath+bl+"/"+rot+"')?>";
				var imagePath = "<?php echo site_url('"+ropath+bl+"/"+rot+"')?>";
				//var imagePath = "../../assets/do_image/"+imageLoc;
				//alert(imagePath);
				var pdfPath = "<?php echo site_url('"+pathpdf+bl+"/"+rot+"/"+id+"')?>";
				document.getElementById("doImage").href=imagePath;
				document.getElementById("doPdf").href=pdfPath;
			}else{
				//document.getElementById("doImage").disabled = true;
				//document.getElementById("doPdf").disabled = true;
				//document.getElementById("doImage").style.visibility="hidden";
				//document.getElementById("doPdf").style.visibility="hidden";
				$('#doImage').attr('disabled','disabled');
				$('#doPdf').attr('disabled','disabled');
			}
			
			//DO AND RO END

			console.log(jsonData);
			$('#myTable').find('tbody').empty();
			for (var i = 0; i < jsonData.igmContList.length; i++) 
			{  
				$('#feedback').append('<tr><td align="center" class="read" style="width:120px;">' 
				+ jsonData.igmContList[i].cont_number + '</td><td align="center" class="read" style="width:90px;">' 
				+ jsonData.igmContList[i].cont_size + '</td><td align="center" class="read" style="width:60px;">' 
				+ jsonData.igmContList[i].cont_height +'</td><td align="center" class="read" style="width:90px;">' 
				+ jsonData.igmContList[i].cont_status +'</td></tr>');								
			}
		
			document.getElementById("seaNumber").value=jsonData.seaInfo[0].reg_no;
			document.getElementById("billOfEntryNo").value=jsonData.seaInfo[0].reg_no;
			document.getElementById("billOfEntryDate").value=jsonData.seaInfo[0].reg_date;
			document.getElementById("invoiceAmount").value=jsonData.seaInfo[0].total_value;
		
			document.getElementById("office_code").value=jsonData.seaInfo[0].office_code;
			document.getElementById("c_nubmber").value=jsonData.seaInfo[0].reg_no;
			document.getElementById("xml_date").value=jsonData.seaInfo[0].reg_date; 
			document.getElementById("cusOrderNo").value=jsonData.seaInfo[0].recp_no; 
			document.getElementById("cusOrderDate").value=jsonData.seaInfo[0].recp_date; 
			
			document.getElementById("cnfName").value=jsonData.seaInfo[0].cnf_name;						//2019-07-31 - intakhab 
			document.getElementById("cnf_lic").value=jsonData.seaInfo[0].cnf_lic;						//			"
			document.getElementById("exitNoteNum").value=jsonData.seaInfo[0].exit_note_number;			//			"
			// alert(jsonData.deliveryList[0].description);
			
			// check why info is not available for BE No. -- 2022-06-20
			// alert("ok 12");
			// alert(xmlhttp.responseText);
			// alert(jsonData.deliveryList[0].blNo);
			// alert("ok 22");
			document.getElementById("blNo").value=jsonData.deliveryList[0].blNo;						//2019-08-01 - intakhab
			document.getElementById("rotNo").value=jsonData.deliveryList[0].rotNo;						//			"
			document.getElementById("importerName").value=jsonData.deliveryList[0].Notify_name;			//			"
			document.getElementById("grossWeight").value=jsonData.deliveryList[0].Cont_gross_weight;	//			"
			document.getElementById("netWeight").value=jsonData.deliveryList[0].cont_weight; 			//			"
			document.getElementById("doQuantity").value=jsonData.deliveryList[0].Pack_Number;			//			"
			document.getElementById("doUnit").value=jsonData.deliveryList[0].Pack_Description;			//			"
			document.getElementById("marks").value=jsonData.deliveryList[0].Pack_Marks_Number;			//			"
			document.getElementById("mloLine").value=jsonData.deliveryList[0].master_BL_No;				//			"
			document.getElementById("mloCode").value=jsonData.deliveryList[0].mlocode;					//			"
			document.getElementById("forwarderLine").value=jsonData.deliveryList[0].blNo;				//			"
			document.getElementById("doIssuedBy").value=jsonData.deliveryList[0].Organization_Name; 	//			"
			document.getElementById("oneStopPoint").value=jsonData.deliveryList[0].one_stop_point;		//			"
			document.getElementById("doNo").value=jsonData.deliveryList[0].do_no;						//			"
			document.getElementById("doDate").value=jsonData.deliveryList[0].do_date;					//			"
			document.getElementById("validUpToDate").value=jsonData.deliveryList[0].valid_up_to_date;	//			"
			document.getElementById("description").value=jsonData.deliveryList[0].description;			//			"
		
			document.getElementById("date").value=jsonData.seaInfo[0].paper_file_date;			//			"
			
			document.getElementById("shedTallyInfoID").value=jsonData.deliveryList[0].id;
			document.getElementById("paperFileDate").value=jsonData.seaInfo[0].paper_file_date;
		
			document.getElementById("truckNum").value=jsonData.deliveryList[0].no_of_truck;  
		
			document.getElementById("verifyNo").value=jsonData.deliveryList[0].verify_number;
			//alert(jsonData.deliveryList[0].verify_number);
			if(jsonData.deliveryList[0].verify_number!=null)
			{			
				document.getElementById('btn').style.display="none";
				document.getElementById('vmsg').style.display="block";
				alert("Already Verified.")	
			}
									
			for (var i = 0; i < jsonData.commLandDate.length; i++) 
			{
				//alert(jsonData.commLandDate[i].rtnValue);
				document.getElementById("commLandDate").value=jsonData.commLandDate[i].RTNVALUE;

			}	
			
		}
	}
	
	/* Get Info By B/E Number */
	
	
	
	function displayModal()
	{
		var modal = document.getElementById('myModal');					
		modal.style.display = "block";
	}
	
	function packageDisplayModal()
	{
		var packModal = document.getElementById('packageModel');					
		packModal.style.display = "block";
	}
		
	function marksDisplayModal()
	{
		var marModel = document.getElementById('marksModel');					
		marModel.style.display = "block";
	}	
		
		
	function removeTableElement(table)
	{
		var tblLen = table.rows.length;
		//alert(tblLen);
		for(var i=tblLen;i>0;i--)
		{
			table.deleteRow(i-1);
		}				
	}
		
	function getcnfName(cnf_lic_no)
	{	
	    //alert(cnf_lic_no);
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
		xmlhttp.onreadystatechange=stateChangegetCNFInfo;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/getCnfCode')?>?cnf_lic_no="+cnf_lic_no,false);
				
		xmlhttp.send();
	  
    }

	function stateChangegetCNFInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
			var val = xmlhttp.responseText;	
			//alert(val);
            var jsonData = JSON.parse(val);	
	        for (var i = 0; i < jsonData.length; i++) 
		    {			
			 document.getElementById("cnfName").value=jsonData[i].NAME;
			}
		}
    }

	
	
	function validate()
	{
		checkInfo();

		// if(document.getElementById('approve').innerHTML == "Approve")
		// {
		// 	alert( "Please Approve this" );
		// 	return false;
		// }
		// else 
		if( document.myForm.oneStopPoint.value == "" )
		{
			alert( "One Stop point not assigned." );
			document.myForm.oneStopPoint.focus() ;
			return false;
		}
		if( document.myForm.doNo.value == "" )
		{
			alert( "Please! Provide DO no.." );
			document.myForm.doNo.focus() ;
			return false;
		}
		else if( document.myForm.doDate.value == "" ) 
		{
			alert( "Please! Provide DO Date.." );
			document.myForm.doDate.focus() ;
			return false;
		}
		/* else if( document.myForm.validUpToDate.value == "" )
		{
			alert( "Please! Provide Valid Upto Date.." );
			document.myForm.validUpToDate.focus() ;
			return false;
		} */
		
		else if( document.myForm.cnf_lic.value == "" )
		{
			alert( "Please! Provide CNF licence code.." );
			document.myForm.cnf_lic.focus() ;
			return false;
		}

		else if( document.myForm.cnfName.value == "" )
		{
			alert( "Please! Provide CNF Name.." );
			document.myForm.cnfName.focus() ;
			return false;
		}
		
		
		else if( document.myForm.paperFileDate.value == "" )
		{
			alert( "Please! Provide Papaer file Date.." );
			document.myForm.paperFileDate.focus() ;
			return false;
		}
				
		// else if( document.myForm.exitNoteNum.value == "" )
		// {
			// alert( "Please! Provide Exit note No.." );
			// document.myForm.exitNoteNum.focus() ;
			// return false;
		// }
			
					
		else if( document.myForm.date.value == "" )
		{
			alert( "Please! Provide date of exit.." );
			document.myForm.date.focus() ;
			return false;
		}
		// else if( document.myForm.truckNum.value == "" )			// 2020-12-21
		// {
			// alert( "Please! Provide number of Truck.." );
			// document.myForm.truckNum.focus() ;
			// return false;
		// }
		else if( document.myForm.cusOrderNo.value == "" )
		{
			alert( "Please! Provide Custome order no.." );
			document.myForm.cusOrderNo.focus() ;
			return false;
		}
		else if( document.myForm.cusOrderDate.value == "" )
		{
			alert( "Please! Provide Custom Order Date." );
			document.myForm.cusOrderDate.focus() ;
			return false;
		}
		else if(confirm('Are you sure?'))
		{
			return true;
		}
		// else
		// {
			return false;
		// }
	}
	
	
	function validateform()
	{  
		if( document.btnForm.office_code.value == "" )
		{
			alert( "Please provide Correct Bill of Entry Number." );
			return false;
		}
		else if( document.btnForm.c_nubmber.value == "" ) 
		{
			alert( "Please provide Correct Bill of Entry Number." );
			return false;
		}
		else if( document.btnForm.xml_date.value == "" ) 
		{
			alert( "Please provide Correct Bill of Entry Number." );
			return false;
		}
		else
			return true;
		
	}
	

	function closeMsgBox()
	{
		//alert("OK");
		document.getElementById('myModal').style.display = "none";
	}

	function closeMsgBox2()
	{
		document.getElementById('packageModel').style.display = "none";
	}	
		
	function closeMsgBox3()
	{
		document.getElementById('marksModel').style.display = "none";
	}

	function checkInfo()
	{
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
		var cnf_lic=document.getElementById("cnf_lic").value;
		var cnfName=document.getElementById("cnfName").value;
		var rotNo=document.getElementById("rotNo").value;
		var blNo=document.getElementById("blNo").value;

		var url = "<?php echo site_url('AjaxController/chkCnfInfo');?>?cnf_lic="+cnf_lic+"&cnfName="+cnfName+"&rotNo="+rotNo+"&blNo="+blNo;
		//alert(url);
		xmlhttp.onreadystatechange=stateChangeChkInfo;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}
	
	function stateChangeChkInfo()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			alert(jsonData.contAlertMsg);
			alert(jsonData.alertMsg);
			return false;
			//alert(xmlhttp.responseText);
			//alert($data);
//			data = xmlhttp.responseText;
		}
	}	

	function setApprovestst(){
		var uploadId = document.getElementById("edoid").value;

		if(confirm("Do you want to Approve ?"))
	  	{
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			

			xmlhttp.onreadystatechange=function(){		 
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{							
					var val = xmlhttp.responseText;
					var jsonData = JSON.parse(val);				
					
					if(jsonData.strUpdateStat==true)
					{
						alert("Approved Successfully!");
						$('#approve').attr('disabled','disabled');
						document.getElementById("approve").innerHTML="Approved";
						document.getElementById("edoid").value="";
					}
					else
					{
						alert("Updated Failed!");
						$('#approve').attr('disabled','disabled');
						document.getElementById("approve").innerHTML="Approve"; 
					}			
				}
			};
				
			var url = "<?php echo site_url('AjaxController/changeChkState')?>?uploadId="+uploadId;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
	  }
	  else
	  {
		  return false;
	  }
	}

	</script>

	<?php
		if($doFormFlag == 2){
	?>
		<script type="text/javascript">
			var blNo = '<?php echo $blNo;?>';
			window.onload = function() {
				getBlInfo(blNo);
			};
		</script>
	<?php
		}
	?>

<style>
.read{ background-color : #F7EEC0; }
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 9999; /* Sit on top */
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
    width: 35%;
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
    color: black;
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
    background-color: #fff;
    color: #000;
	
}

.modal-body {padding: 2px 16px;}

.modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

th, td {
  padding: 4px;
}

input[type=text]{
	height:20px;
	padding:3px;
}

input[type=date]{
	height:30px;
	padding:3px;
}

</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title">DOCUMENTATION PROCESS</h2>
				</header>
				<div class="panel-body">
						
					<table border=0 align="right" >
						<tr>
							<td align="right" height>
								<form name= "btnForm" onsubmit="return validateform();" action="<?php echo site_url("ReleaseOrderController/xml_conversion_action");?>"  method="post" target="_blank">
									<input type="hidden" name="office_code" id="office_code"  /> 
									<input type="hidden" name="c_nubmber" id="c_nubmber"  />
									<input type="hidden" name="xml_date" id="xml_date"  />
									<input type="submit"  width="120px" name="view" id="view" value="View B/E" class="btn btn-primary" />
								</form>
							</td>
						</tr>
					</table>	

					<form name= "myForm" onsubmit="return validate();" action="<?php echo site_url("ReleaseOrderController/deliveryEntryForm");?>" target="successMsg" method="post">
					<ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a href="#overview" style="padding:7px;" data-toggle="tab"><b>Overview</b></a>
						</li>							
					</ul>							
					<div class="table-responsive">								
						<table align="center" width="100%">
						<caption style="padding-bottom:0px;"><font size="2" color="blue"><u> Search </u></font></caption>
							<tr>
								<th><nobr>B/E NUMBER:</nobr></th>
								<td><input style="width:100px;" class="form-control" type="text" id="seaNumber" name="seaNumber" onblur="getSeaInfo(this.value)"></td>
								<!--td><input style="width:100px;" type="text" id="seaNumber" name="seaNumber" onblur="getSeaInfo()"></td-->
								<th>REG NO:<font color='red'><b>*</b></font></th>
								<td align="center"><nobr><input style="width:100px;" class="form-control" type="text" id="rotNo" name="rotNo" <?php if($doFormFlag == 1 || $doFormFlag == 2){?> value="<?php echo $rotNo;?>" <?php }?> tabindex="1" ></nobr></td>
								<th>VERIFY NO:</th>
								<td align="center"><input style="width:100px;" class="form-control read" type="text"  id="verifyNo" name="verifyNo" readonly></td>
								<td align="center">
										<!--a href="" name="doImage" id="doImage" target="_blank" class="login_button" style="margin-left:50px;text-decoration:none;display:none;">Agent DO</a-->

										<a href="" target="_BLANK" class="btn btn-primary" id="doPdf" style="text-decoration:none;padding:8px;margin-left:10px;" disabled>
											View DO 
										</a> 
									</td>

									<td>
									&nbsp;
									</td>

									<td align="center">
										<a href="" target="_BLANK" class="btn btn-primary" id="doImage" style="text-decoration:none;padding:8px;" disabled>
											View RO 
										</a>
									</td>

									<td>
										<input type="hidden" name="edoid" id="edoid" value="">
										<a class="btn btn-success" id="approve" style="text-decoration:none;padding:8px;margin-left:10px;" onClick ="setApprovestst()" disabled>
											Approve
										</a>
									</td>
							</tr>
						</table>
					</div>												
					<!-- <br> -->
					<!-- <ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a href="#overview" style="padding:6px;" data-toggle="tab"><b>IGM Information</b></a>
						</li>							
					</ul>							 -->
					<div class="table-responsive">							
						<!--table style="border:1;width:100%"-->											
						<table width="100%"> 
						<caption style="padding-bottom:0px;"><font size="2" color="blue"><u> IGM Information </u></font></caption>
							<tr>
								<th><nobr>ONE STOP POINT:</nobr></th>
								<td><input style="width:100px;"  class="form-control read" type="text" id="oneStopPoint" name="oneStopPoint" readonly></td>
								<th><nobr> DO NO:<font color='red'><b>*</b></font></nobr></th>
								<td><nobr><input style="width:60px;" class="form-control" type="text"  id="doNo" name="doNo"  tabindex="3"></nobr></td>
								<th><nobr>DO DT:<font color='red'><b>*</b></font></nobr></th>
								<td><nobr><input style="width:132px;padding:3px;" class="form-control" type="date" id="doDate" name="doDate"  tabindex="4" value=""></nobr></td>

								<th><nobr>VALID UPTO DT:<font color='red'><b>*</b></font></nobr></th>
								<td><nobr><input style="width:132px;padding:3px;" class="form-control" type="date" id="validUpToDate" name="validUpToDate"  tabindex="5" value=""></nobr></td>				
					
							</tr>
							<!-- <tr>
								<td>&nbsp;</td>
							</tr> -->
							<tr>
								<th><nobr>BL NO:<font color='red'><b>*</b></font></nobr></th>
								<td><nobr><input style="width:150px;" class="form-control" type="text"  id="blNo" name="blNo" <?php if($doFormFlag == 1 || $doFormFlag == 2){?> value="<?php echo $blNo;?>" <?php }?> <?php if($doFormFlag == 1){ ?> autofocus <?php } ?> onblur="getBlInfo(this.value)" tabindex="2"></nobr></td>
								<!--td><nobr><input style="width:140px;" type="text"  id="blNo" name="blNo" <?php if($doFormFlag == 1 || $doFormFlag == 2){?> value="<?php echo $blNo;?>"  autofocus <?php }?> onblur="getSeaInfo()" tabindex="2"><font color='red'><b>*</b></font></nobr></td-->
								<th><nobr>MLO:</nobr></th>
								<td><input style="width:60px;" class="form-control read" type="text" id="mloCode" name="mloCode" readonly></td>
								<th><nobr>Marks:</nobr></th>
								<td colspan="3"><input type="text" class="form-control read" style="width:235px;" id="marks" name="marks" readonly></td>
								
								
								
								<input type="hidden"  id="shedTallyInfoID" name="shedTallyInfoID">

							</tr>
							<!-- <tr>
								<td>&nbsp;</td>
							</tr> -->
							<tr>
								<th><nobr>MLO LINE:</nobr></th> 
								<td><input style="width:150px;" class="form-control read" type="text" id="mloLine" name="mloLine" readonly></td>
								<th ><nobr>DESCRIPTION:</nobr></th>
								<td colspan="5"><input style="width:500px;" class="form-control read" type="text" id="description" name="description" readonly></td>
							</tr>
							<!-- <tr>
								<td>&nbsp;</td>
							</tr> -->
							<tr>
								<th><nobr>FORWARDER LINE:</nobr></th>
								<td><input style="width:150px;" class="form-control read" type="text"  id="forwarderLine" name="forwarderLine" readonly></td>
								<th><nobr>DO QUANTITY:</nobr></th>
								<td><input style="width:60px;" class="form-control read" type="text"  id="doQuantity"name="doQuantity" readonly></td>	
								<th><nobr>DO Unit:</nobr></th>
								<td><input style="width:100px;" class="form-control read" type="text" id="doUnit" name="doUnit" readonly></td>	
							</tr>
							<!-- <tr>
								<td>&nbsp;</td>
							</tr> -->
							<tr>
								<th><nobr>IMPORTER NAME:</nobr></th>
								<td colspan="1"><input style="width:150px;" class="form-control read" type="text" id="importerName" name="importerName" readonly></td>
								<th><nobr>GROSS (KG):</nobr></th>
								<td colspan="2"><nobr><input style="width:120px;"  class="form-control read" type="text"  id="grossWeight"name="grossWeight" readonly></nobr></td>

								<th> <nobr>NET WEIGHT (KG):</nobr></th>
								<td><nobr><input type="text" style="width:100px;" class="form-control read" id="netWeight" name="netWeight" readonly></nobr></td>

							</tr>
							<!-- <tr>
								<td>&nbsp;</td>
							</tr> -->
							<tr>
								<th><nobr>DO ISSUED BY:</nobr></th>
								<td colspan="7"><input style="width:762px;" class="form-control read" type="text" id="doIssuedBy" name="doIssuedBy" readonly></td>
							</tr>
						</table>
					</div>
					<!-- <br>		 -->
					<!-- <ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a href="#overview" style="padding:6px;" data-toggle="tab"><b>Container Details</b></a>
						</li>							
					</ul>							 -->
					<!-- <div class="panel-body">								 -->
					<div class="table-responsive">
						<table class="table table-bordered" id="myTable" style="margin:0px;">
						<caption style="padding-bottom:0px;"><font size="2" color="blue"><u> Container Details </u></font></caption>
							<thead>
								<tr>
									<th><nobr>&nbsp;Container No&nbsp;</nobr></th>  
									<th>&nbsp;Size&nbsp;</th>
									<th>&nbsp;Height&nbsp;</th>
									<th>&nbsp;Type&nbsp;</th>
									<th><nobr>&nbsp;MLO Status&nbsp;</nobr></th>
									<th><nobr>&nbsp;FFW Status&nbsp;</nobr></th>
									<th><nobr>&nbsp;Comm Land Date&nbsp;</nobr></th> 
									<th>&nbsp;Location&nbsp;</th>
									<th>&nbsp;Pass&nbsp;</th>
								</tr>
							</thead>
							<tbody id="feedback">
								
							</tbody>							
						</table>
					</div>
					<!-- </div> -->

					<!-- <br> -->

					<!-- <ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a href="#overview" style="padding:7px;" data-toggle="tab"><b>CNF Info</b></a>
						</li>							
					</ul> -->
					<div class="table-responsive">													
						<table width="100%">
						<caption style="padding-bottom:0px;"><font size="2" color="blue"><u> C&F Info </u></font></caption>	
							<tr> 
								<td>
									<table >
										<tr>
											<th> C & F AGENT:<font color='red'><b>*</b></font></th>
											<td> <input type="text" style="width:80px;padding:3px;" class="form-control" id="cnf_lic" name="cnf_lic"  onblur="getcnfName(this.value)" tabindex="6"></td>
											<td> <input type="text" class="form-control read"  style="width:300px;padding:3px;" id="cnfName" name="cnfName" readonly></td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th> <nobr>PAPER FILE DATE:<font color='red'><b>*</b></font></nobr></th> 
											<td colspan="2"> <input type="date" style="width:135px;padding:3px;" class="form-control" id="paperFileDate" name="paperFileDate" value="" tabindex="7"></td> 
										</tr>  
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>EXIT NOTE NUMBER:<!--font color='red'><b>*</b></font--></nobr></th>
											<td colspan="2"><input type="text" style="width:135px;" class="form-control" id="exitNoteNum" name="exitNoteNum" tabindex="8" ></</td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>DATE:</nobr></th>
											<td colspan="2"><input type="date" class="form-control" style="width:135px;"  id="date" name="date" value="" ></</td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>NUMBER OF TRUCK:<font color='red'><b>*</b></font></nobr></th>
											<td colspan="2"><input type="text" style="width:135px;" class="form-control" id="truckNum" name="truckNum"  tabindex="9"></</td>
										</tr>
									</table>
								</td>
								<td span="6">
									&nbsp;&nbsp;&nbsp;
								</td>
								<td>
									<table >
										<tr>
											<th><nobr>BILL OF ENTRY NO:</nobr></th>
											<td colspan="2"> <input type="text"  class="form-control" style="width:170px;" id="billOfEntryNo" name="billOfEntryNo" ></td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>BILL OF ENTRY DT:</nobr></th>
											<td colspan="2"> <input type="date" class="form-control" style="width:170px;" id="billOfEntryDate" name="billOfEntryDate" value="" ></td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>INVOICE AMOUNT:</nobr></th>
											<td> <input type="text" class="form-control" style="width:110px;" id="invoiceAmount" name="invoiceAmount"  ></td>
											<td> <input type="text" class="form-control" style="width:60px;" id="invoiceAmount1" name="invoiceAmount1" ></td>
										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>CUS ORDER NO:<font color='red'><b>*</b></font></nobr></th>
											<td colspan="2"><nobr><input type="text" class="form-control" style="width:170px;" id="cusOrderNo" name="cusOrderNo"  tabindex="10"></nobr></td>

										</tr>
										<!-- <tr>
											<td>&nbsp;</td>
										</tr> -->
										<tr>
											<th><nobr>CUS ORDER DATE:<font color='red'><b>*</b></font></nobr></th>
											<td colspan="2"><nobr><input type="date" class="form-control" style="width:170px;" id="cusOrderDate" name="cusOrderDate" tabindex="11" value=""></nobr></td>
										</tr>
									</table>
								</td>
							</tr>			 
						</table>				
					</div>
					<!-- <ul class="nav nav-tabs tabs-primary">
						<li class="active">
							<a href="#overview" data-toggle="tab"><b>Buttons</b></a>
						</li>							
					</ul> -->
					<!-- <div class="panel-body">														 -->
						<table align="center" id="mytbl" style="background-color: #c3ecf9;">								
							<tr>
								<td align="center">
									<div id="btn">
										<button type="submit" class="btn btn-primary" id="save" name="save" value="save" class="login_button" tabindex="12">SAVE</button> 

										<button type = "submit" class="btn btn-primary"><a href="<?php echo site_url('Report/deliveryEntryFormByWHClerk/');?>" class="login_button" style="text-decoration: none;padding:4px;font-size:12px;color:white;"><nobr>CLEAR</nobr></a></button>
									</div>
									<div id="vmsg" style="display:none;">
										<font color="red">This is already verified.</font>
										<button type = "submit" class="btn btn-primary"><a href="<?php echo site_url('Report/deliveryEntryFormByWHClerk/');?>" class="login_button" style="text-decoration: none;padding:4px;font-size:12px; color:white;"><nobr>CLEAR</nobr></a></button>
									</div>
								</td>
							</tr>
					
							<tr>
								<td>
									<iframe id="successMsg" name="successMsg" height="50" width="600" style="border:0"></iframe>
								</td>
							</tr>
						</table>
					<!-- </div> -->
					</form>
				</div>

				<!-- The Modal -->
				<div id="myModal" class="modal">
					<!-- Modal content -->
					<div class="modal-content">
					<div align="center" class="modal-header">
					  <span class="close" onclick="closeMsgBox()">&times;</span>
					  <h2><font color="red">This BL is already verified.</font></h2>
					</div>
					<div align="center"  class="modal-body">
						<div id="msgDiv"></div>
					</div>
					<!--<div class="modal-footer">
					  <h3>Modal Footer</h3>
					</div>-->
					</div>
				</div>


				<div id="packageModel" class="modal">
					<!-- Modal content -->
					<div class="modal-content">
					<div align="center" class="modal-header">
					  <span class="close" onclick="closeMsgBox2()">&times;</span>
					  <h2><font color="red">Different Unit!</font></h2>
					</div>
					<div align="center"  class="modal-body">
						<div id="msgDiv1"></div>
					</div>
					</div>
				</div>


				<div id="marksModel" class="modal">
					<!-- Modal content -->
					<div class="modal-content">
					<div align="center" class="modal-header">
					  <span class="close" onclick="closeMsgBox3()">&times;</span>
					  <h2><font color="red">This BL Received nill/wrong mark!</font></h2>
					</div>
					<div align="center"  class="modal-body">
						<div id="msgDiv2"></div>
					</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>