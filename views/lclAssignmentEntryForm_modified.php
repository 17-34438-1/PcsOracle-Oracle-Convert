<script type="text/javascript">
	var lastIval = 0;
	$('body').on('keydown', 'input, select, textarea', function(e) {
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
	});
   
   
   function validate()
   {
	   
    if(document.getElementById('subbtn').clicked == true)
	   {
		if(document.myForm.expectDate.value == "" )
		{
			alert( "Please provide Expected Date!" );
			document.myForm.expectDate.focus() ;
			return false;
		}
		else if( document.myForm.contNo.value == "" )
		{
			alert( "Please provide Container No!" );
			document.myForm.contNo.focus() ;
			return false;
		}
  
		return true ;
	   }
	   else
	   {
		   //return false;
	   }
   }
   	
	
	
   function getShed(shed)
   {  
		document.getElementById("cargoAtShed").value=shed;
		
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeShedInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getShedDtlInfo')?>?shed="+shed,false);
					
		xmlhttp.send();
		
		
   }
   
   
   
   	function stateChangeShedInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{	
			var val = xmlhttp.responseText;
			//alert(val);
			
		removeTableRow();
		
        var jsonData = JSON.parse(val);
		for (var i = 0; i < jsonData.length; i++) 
		{
			
			var st = jsonData[i].st;
			var tr = document.createElement("tr");
			if(st==1)
				tr.style.background="#CCAEDE";
			else
				tr.style.background="#E1F0FF";
			
			var td1 = document.createElement('td');
			var textl = document.createTextNode(i+1);
			td1.appendChild(textl);
			
			var td2 = document.createElement('td');
			var text2 = document.createTextNode(jsonData[i].cont_number);
			td2.appendChild(text2);
			
			var td3 = document.createElement('td');
			var text3 = document.createTextNode(jsonData[i].cont_size);
			td3.appendChild(text3);
			
			
			var td4 = document.createElement('td');
			var text4 = document.createTextNode(jsonData[i].cont_height);
			td4.appendChild(text4);
			
			var td5 = document.createElement('td');
			var text5 = document.createTextNode(jsonData[i].Import_Rotation_No);
			td5.appendChild(text5);
				
						
			var td6 = document.createElement('td');
			var text6 = document.createTextNode(jsonData[i].Vessel_Name);
			td6.appendChild(text6);
			
			var td7 = document.createElement('td');
			var text7 = document.createTextNode(jsonData[i].assignment_date);
			td7.appendChild(text7);
			
			var td8 = document.createElement('td');
			var text8 = document.createTextNode(jsonData[i].mlocode);
			td8.appendChild(text8);
			
			var td9 = document.createElement('td');
			if(jsonData[i].stv=="SAIF POWERTEC")
			{
				jsonData[i].stv="SPL";
			}	
			var text9 = document.createTextNode(jsonData[i].stv);
			td9.appendChild(text9);
			
			var td10 = document.createElement('td');
			var text10 = document.createTextNode(jsonData[i].cont_loc_shed);
			td10.appendChild(text10);
			
			var td11 = document.createElement('td');
			var text11 = document.createTextNode(jsonData[i].cargo_loc_shed);
			td11.appendChild(text11);
			
			var td12 = document.createElement('td');
			var text12 = document.createTextNode(jsonData[i].description_cargo);
			td12.appendChild(text12);
			
			var td13 = document.createElement('td');
			var text13 = document.createTextNode(jsonData[i].landing_time);
			td13.appendChild(text13);
			
			var td14 = document.createElement('td');
			var text14 = document.createTextNode(jsonData[i].remarks);
			td14.appendChild(text14);
			
			var lclid=jsonData[i].id;
			var td15 = document.createElement('td');
			var href = document.createElement("a");
			var createAText = document.createTextNode("Edit");
			href.setAttribute('href', "<?php echo site_url('cfsModule/lclAssignmentEdit');?>/"+lclid);
			href.appendChild(createAText);
			td15.appendChild(href);

			var lclid=jsonData[i].id;
			var td16 = document.createElement('td');
			var href2 = document.createElement("a");
			var createAText = document.createTextNode("Delete");
			href2.setAttribute('href', "<?php echo site_url('cfsModule/lclAssignmentCancel');?>/"+lclid);
			href2.appendChild(createAText);
			td16.appendChild(href2);

	
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
			tr.appendChild(td12);
			tr.appendChild(td13);
			tr.appendChild(td14);
			tr.appendChild(td15);
			tr.appendChild(td16);			
			
			tbl.appendChild(tr);
		}
					
		}
	}
   
   function removeTableRow()
   {
		var tbl = document.getElementById("mytbl_cont");
		var rowslenth = tbl.getElementsByTagName("tr").length;
		var rmvroLn = rowslenth-1;

		for(var i=rmvroLn;i>=1;i--)
		{
			tbl.deleteRow(i);
		}
   }
   
   function getAssignInfo(shed)
   {
	   var dt = document.getElementById("expectDate").value;
		if(dt=="" || dt==null )
		 {
			 alert("Please provide Unstuffing Date!");
			
		 }
		 else
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
			xmlhttp.onreadystatechange=stateChangeDisplayData;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getAssignmentInfo')?>?dt="+dt+"&shed="+shed,false);
					
			xmlhttp.send();
		 }
   }

	function stateChangeDisplayData()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var tbl = document.getElementById("mytbl_cont");
			removeTableRow();
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			for (var i = 0; i < jsonData.length; i++) 
			{
				//alert(i);
				var  sl = jsonData[i].sl;
				var  cont_number = jsonData[i].cont_number;
				var  Vessel_Name = jsonData[i].Vessel_Name;
				var  Import_Rotation_No = jsonData[i].Import_Rotation_No;
				var  cont_size = jsonData[i].cont_size;
				var  cont_height = jsonData[i].cont_height;
				var  mlocode = jsonData[i].mlocode;
				var  stvVal = jsonData[i].stv;
				var  cont_loc_shed = jsonData[i].cont_loc_shed;
				var  cargo_loc_shed = jsonData[i].cargo_loc_shed;
				var  description_cargo = jsonData[i].description_cargo;
				var  remarksVal = jsonData[i].remarks;
				var  igm_detail_id = jsonData[i].igm_detail_id;
				var  igm_cont_detail_id = jsonData[i].igm_cont_detail_id;
				var  landing_Time = jsonData[i].landing_time;
				var  id = jsonData[i].id;
				//alert("calling");igm_details.id AS igm_detail_id, igm_detail_container.id igm_cont_detail_id
				
				var tr = document.createElement("tr");

				var td1 = document.createElement('td');
				var slId = document.createElement("input");
				slId.type = "text";
				slId.value = sl;
				slId.name = "slNo" + i;
				slId.id = "slNo" + i;
				//slId.value =  i;
				slId.style.width = "40px";
				slId.setAttribute('onclick', 'this.select();');
				td1.appendChild(slId);
				
				var td2 = document.createElement('td');
				var contNo = document.createElement("input");
				contNo.setAttribute('type', 'text');
				contNo.setAttribute('name', 'contNo'+ i);
				contNo.setAttribute('onblur', 'getContInfo(this.value,'+i+')');				
				contNo.setAttribute('onclick', 'this.select();');
				contNo.value = cont_number;
				contNo.style.width = "100px";
			//	contNo.setAttribute('style', 'text-transform:uppercase');
				td2.appendChild(contNo);
				
				var td3 = document.createElement('td');
				var vesselName = document.createElement("input");
				vesselName.type = "text";
				vesselName.name = "vesselName" + i;
				vesselName.id = "vesselName" + i;
				vesselName.value = Vessel_Name;
				vesselName.style.width = "100px";
				vesselName.setAttribute("readonly","true");
				vesselName.setAttribute('onclick', 'this.select();');
				td3.appendChild(vesselName);
				
				var td4 = document.createElement('td');
				var rotNo = document.createElement("input");
				rotNo.type = "text";
				rotNo.name = "rotNo" + i;
				rotNo.id = "rotNo" + i;
				rotNo.value = Import_Rotation_No;
				rotNo.style.width = "70px";
				rotNo.setAttribute("readonly","true");
				rotNo.setAttribute('onclick', 'this.select();');
				td4.appendChild(rotNo);
				
				var td5 = document.createElement('td');
				var contSize = document.createElement("input");
				contSize.type = "text";
				contSize.name = "contSize" + i;
				contSize.id = "contSize" + i;
				contSize.value = cont_size;
				contSize.style.width = "40px";
				contSize.setAttribute("readonly","true");
				contSize.setAttribute('onclick', 'this.select();');
				td5.appendChild(contSize);
				
				var td6 = document.createElement('td');
				var contHeight = document.createElement("input");
				contHeight.type = "text";
				contHeight.name = "contHeight" + i;
				contHeight.id = "contHeight" + i;
				contHeight.value = cont_height;
				contHeight.style.width = "45px";
				contHeight.setAttribute("readonly","true");
				contHeight.setAttribute('onclick', 'this.select();');
				td6.appendChild(contHeight);
				
				var td7 = document.createElement('td');
				var mlo = document.createElement("input");
				mlo.type = "text";
				mlo.name = "mlo" + i;
				mlo.id = "mlo" + i;
				mlo.value = mlocode;
				mlo.style.width = "40px";
				mlo.setAttribute("readonly","true");
				mlo.setAttribute('onclick', 'this.select();');
				td7.appendChild(mlo);
				
				var td8 = document.createElement('td');
				var stv = document.createElement("input");
				stv.type = "text";
				stv.name = "stv" + i;
				stv.id = "stv" + i;
				stv.value = stvVal;
				stv.style.width = "80px";
				//stv.setAttribute("readonly","true");
				stv.setAttribute('onclick', 'this.select();');
				td8.appendChild(stv);
				
			
				
				var td9 = document.createElement('td');
				var contAtShed = document.createElement("input");
				contAtShed.type = "text";
				contAtShed.name = "contAtShed" + i;
				contAtShed.id = "contAtShed" + i;
				contAtShed.value = cont_loc_shed;
				contAtShed.style.width = "70px";
				contAtShed.setAttribute("readonly","true");
				contAtShed.setAttribute('onclick', 'this.select();');
				td9.appendChild(contAtShed);
				
				
				var td10 = document.createElement('td');
				var cargoAtShed = document.createElement("input");
				cargoAtShed.type = "text";
				cargoAtShed.name = "cargoAtShed" + i;
				cargoAtShed.id = "cargoAtShed" + i;
				cargoAtShed.value = cargo_loc_shed;
				cargoAtShed.style.width = "70px";
				cargoAtShed.setAttribute("readonly","true");
				cargoAtShed.setAttribute('onclick', 'this.select();');
				td10.appendChild(cargoAtShed);
				
				var td11 = document.createElement('td');
				var decOfCargo = document.createElement("input");
				decOfCargo.type = "text";
				decOfCargo.name = "decOfCargo" + i;
				decOfCargo.id = "decOfCargo" + i;
				decOfCargo.value = description_cargo;
				decOfCargo.style.width = "120px";
				decOfCargo.setAttribute('onclick', 'this.select();');
				td11.appendChild(decOfCargo);
				
				var td12 = document.createElement('td');
				var remarks = document.createElement("input");
				remarks.type = "text";
				remarks.name = "remarks" + i;
				remarks.id = "remarks" + i;
				remarks.value = remarksVal;
				remarks.style.width = "80px";
				remarks.setAttribute('onclick', 'this.select();');
				td12.appendChild(remarks);
				
				var td13 = document.createElement('td');
				var igm_dtl_id = document.createElement("input");
				igm_dtl_id.type = "text";
				igm_dtl_id.name = "igm_dtl_id" + i;
				igm_dtl_id.id = "igm_dtl_id" + i;
				igm_dtl_id.value = igm_detail_id;
				igm_dtl_id.style.width = "0px";
				igm_dtl_id.style.visibility="hidden";
				td13.appendChild(igm_dtl_id);
				
				var td14 = document.createElement('td');
				var igm_cont_dtl_id = document.createElement("input");
				igm_cont_dtl_id.type = "text";
				igm_cont_dtl_id.name = "igm_cont_dtl_id" + i;
				igm_cont_dtl_id.id = "igm_cont_dtl_id" + i;
				igm_cont_dtl_id.value = igm_cont_detail_id;
				igm_cont_dtl_id.style.width = "0px";
				igm_cont_dtl_id.style.visibility="hidden";
				td14.appendChild(igm_cont_dtl_id);
				
				var td15 = document.createElement('td');
				var landingTime = document.createElement("input");
				landingTime.type = "text";
				landingTime.name = "landingTime" + i;
				landingTime.id = "landingTime" + i;
				landingTime.value = landing_Time;
				landingTime.style.width = "0px";
				landingTime.style.visibility="hidden";
				td15.appendChild(landingTime);
				
				var td16 = document.createElement('td');
				var lcl_id = document.createElement("input");
				lcl_id.type = "text";
				lcl_id.name = "lcl_id" + i;
				lcl_id.id = "lcl_id" + i;
				lcl_id.style.width = "0px";
				lcl_id.value = id;
				lcl_id.style.visibility="hidden";
				td16.appendChild(lcl_id);
		
		
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
				tr.appendChild(td12);
				tr.appendChild(td13);
				tr.appendChild(td14);	
				tr.appendChild(td15);	
				tr.appendChild(td16);	
				
				tbl.appendChild(tr);
			}
			addRow()
		}
	}
	
	
	var glCont = null;
   function getContInfo(cont,ival)
	{
		lastIval = parseInt(ival);
		glCont = cont;
		if(cont!="")
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
			xmlhttp.onreadystatechange=stateChangeContInfo;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getLCLContInfo')?>?cont="+cont+"&ival="+ival,false);
					
			xmlhttp.send();
		 }
		  
		 else
		 {
			 alert("Please, Enter the correct container no.")
		 }
		
	}
			
			
	function stateChangeContInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			 // var myVslInfo=document.getElementById("myVslInfo");
			 //removeOptions(selectList);
			var val = xmlhttp.responseText;
			//alert(val);
			var strArr = val.split("|");
		    var iva=strArr[10]; 
			
			
			document.getElementById("contSize"+iva).value=strArr[1];
			document.getElementById("contHeight"+iva).value=strArr[2];
			document.getElementById("vesselName"+iva).value=strArr[3];
			document.getElementById("rotNo"+iva).value=strArr[4];
			document.getElementById("mlo"+iva).value=strArr[5];
			document.getElementById("igm_cont_dtl_id"+iva).value=strArr[6];
			document.getElementById("igm_dtl_id"+iva).value=strArr[7];
			document.getElementById("stv"+iva).value=strArr[8];
			document.getElementById("landingTime"+iva).value=strArr[9];  
			//document.getElementById("decOfCargo"+iva).value=strArr[11];  
			
			//document.getElementById("contAtShed").focus();
			var tbl = document.getElementById("mytbl_cont");
			var rowslenth = parseInt(tbl.getElementsByTagName("tr").length);
			//alert(rowslenth +" "+ lastIval)
			//alert(rowslenth +" "+ lastIval)
			var diff = rowslenth-lastIval;
			if(diff==2 && (glCont != null || glCont!=""))
			{
				addRow();
			}
			document.getElementById("decOfCargo"+iva).focus();
		}
		
		//slNo1 contNo1 vesselName1 rotNo1 contSize1 contHeight1 mlo1 stv1 contAtShed1 cargoAtShed1 decOfCargo1 remarks1
		
		
	}
	
	function setUpperCase(element,val)
	{
		var str = val.toUpperCase();
		element.value=str;
	}
	
	function addRow()
	{
		var tbl = document.getElementById("mytbl_cont");
		var rowslenth = tbl.getElementsByTagName("tr").length;
		var shed=document.getElementById("contAtShed").value;
/* 		 alert(rowslenth);
		 var last_cont=document.getElementsByName('contNo'+rowslenth).value;
		alert(last_cont); */
		//if(rowslenth+1)'contNo'+ i;
		for (var i = rowslenth-1; i < parseInt(rowslenth); i++) 
		{
			var tr = document.createElement("tr");

			var td1 = document.createElement('td');
			var slId = document.createElement("input");
			slId.type = "text";
			slId.name = "slNo" + i;
			slId.id = "slNo" + i;
			slId.value =  i+1;
			slId.style.width = "40px";
			slId.setAttribute('onclick', 'this.select();');
			td1.appendChild(slId);
			
			var td2 = document.createElement('td');
			var contNo = document.createElement("input");
			contNo.setAttribute('type', 'text');
			contNo.setAttribute('name', 'contNo'+ i);
			contNo.setAttribute('id', 'contNo'+ i);
			contNo.setAttribute('onblur', 'getContInfo(this.value,'+i+')');
			contNo.setAttribute('onkeyup', 'setUpperCase(this,this.value)');
			/*contNo.setAttribute('stule', 'width:80px;');
			contNo.type = "text";
			contNo.name = "contNo" + i;
			contNo.id = "contNo" + i;
			contNo.onBlur = "getContInfo(this.value);";*/ 
			contNo.style.width = "100px";
			td2.appendChild(contNo);
			
			var td3 = document.createElement('td');
			var vesselName = document.createElement("input");
			vesselName.type = "text";
			vesselName.name = "vesselName" + i;
			vesselName.id = "vesselName" + i;
			vesselName.style.width = "100px";
			vesselName.setAttribute("readonly","true");
			vesselName.setAttribute('onclick', 'this.select();');
			td3.appendChild(vesselName);
			
			var td4 = document.createElement('td');
			var rotNo = document.createElement("input");
			rotNo.type = "text";
			rotNo.name = "rotNo" + i;
			rotNo.id = "rotNo" + i;
			rotNo.style.width = "70px";
			rotNo.setAttribute("readonly","true");
			rotNo.setAttribute('onclick', 'this.select();');
			td4.appendChild(rotNo);
			
			var td5 = document.createElement('td');
			var contSize = document.createElement("input");
			contSize.type = "text";
			contSize.name = "contSize"+i;
			contSize.id = "contSize"+i;
			contSize.style.width = "40px";
			contSize.setAttribute("readonly","true");
			contSize.setAttribute('onclick', 'this.select();');
			td5.appendChild(contSize);
			
			var td6 = document.createElement('td');
			var contHeight = document.createElement("input");
			contHeight.type = "text";
			contHeight.name = "contHeight" + i;
			contHeight.id = "contHeight" + i;
			contHeight.style.width = "45px";
			contHeight.setAttribute("readonly","true");
			contHeight.setAttribute('onclick', 'this.select();');
			td6.appendChild(contHeight);
			
			var td7 = document.createElement('td');
			var mlo = document.createElement("input");
			mlo.type = "text";
			mlo.name = "mlo" + i;
			mlo.id = "mlo" + i;
			mlo.style.width = "40px";
			mlo.setAttribute("readonly","true");
			mlo.setAttribute('onclick', 'this.select();');
			td7.appendChild(mlo);
			
			var td8 = document.createElement('td');
			var stv = document.createElement("input");
			stv.type = "text";
			stv.name = "stv" + i;
			stv.id = "stv" + i;
			stv.style.width = "80px";
			//stv.setAttribute("readonly","true");
			stv.setAttribute('onclick', 'this.select();');
			td8.appendChild(stv);
			
			var td9 = document.createElement('td');
			var contAtShed = document.createElement("input");
			contAtShed.type = "text";
			contAtShed.name = "contAtShed" + i;
			contAtShed.id = "contAtShed" + i;
			contAtShed.style.width = "70px";
			contAtShed.value=shed;
			contAtShed.setAttribute("readonly","true");
			contAtShed.setAttribute('onclick', 'this.select();');
			td9.appendChild(contAtShed);
			
			
			var td10 = document.createElement('td');
			var cargoAtShed = document.createElement("input");
			cargoAtShed.type = "text";
			cargoAtShed.name = "cargoAtShed" + i;
			cargoAtShed.id = "cargoAtShed" + i;
			cargoAtShed.style.width = "70px";
			cargoAtShed.value=shed;
			cargoAtShed.setAttribute("readonly","true");
			cargoAtShed.setAttribute('onclick', 'this.select();');
			td10.appendChild(cargoAtShed);
			
			var td11 = document.createElement('td');
			var decOfCargo = document.createElement("input");
			decOfCargo.type = "text";
			decOfCargo.name = "decOfCargo" + i;
			decOfCargo.id = "decOfCargo" + i;
			decOfCargo.style.width = "120px";
			decOfCargo.setAttribute('onclick', 'this.select();');
			decOfCargo.setAttribute('onkeyup', 'setUpperCase(this,this.value)');
			td11.appendChild(decOfCargo);
			
			var td12 = document.createElement('td');
			var remarks = document.createElement("input");
			remarks.type = "text";
			remarks.name = "remarks" + i;
			remarks.id = "remarks" + i;
			remarks.style.width = "80px";
			remarks.setAttribute('onkeyup', 'setUpperCase(this,this.value)');
			td12.appendChild(remarks);
			
	
			var td13 = document.createElement('td');
			var igm_dtl_id = document.createElement("input");
			igm_dtl_id.type = "text";
			igm_dtl_id.name = "igm_dtl_id" + i;
			igm_dtl_id.id = "igm_dtl_id" + i;
			//igm_dtl_id.value = igm_detail_id;
			igm_dtl_id.style.width = "0px";
			igm_dtl_id.style.visibility="hidden";
			td13.appendChild(igm_dtl_id);
				
			var td14 = document.createElement('td');
			var igm_cont_dtl_id = document.createElement("input");
			igm_cont_dtl_id.type = "text";
			igm_cont_dtl_id.name = "igm_cont_dtl_id" + i;
			igm_cont_dtl_id.id = "igm_cont_dtl_id" + i;
			//igm_cont_dtl_id.value = igm_cont_detail_id;
			igm_cont_dtl_id.style.width = "0px";
			igm_cont_dtl_id.style.visibility="hidden";
			td14.appendChild(igm_cont_dtl_id); 
				
			var td15 = document.createElement('td');
			var landingTime = document.createElement("input");
			landingTime.type = "text";
			landingTime.name = "landingTime" + i;
			landingTime.id = "landingTime" + i;
			landingTime.style.width = "0px";
			landingTime.style.visibility="hidden";
			td15.appendChild(landingTime);
			
			var td16 = document.createElement('td');
			var lcl_id = document.createElement("input");
			lcl_id.type = "text";
			lcl_id.name = "lcl_id" + i;
			lcl_id.id = "lcl_id" + i;
			lcl_id.style.width = "0px";
			lcl_id.style.visibility="hidden";
			td16.appendChild(lcl_id);
				
				
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
		    tr.appendChild(td12);
		    tr.appendChild(td13);
		    tr.appendChild(td14); 
		    tr.appendChild(td15); 
		    tr.appendChild(td16); 
				
			
			tbl.appendChild(tr);
		}
		//alert(i);
		//document.myForm.rowNum.value=i;	
		//i=i-1;
		//alert(i);
		document.getElementById("rowNum").value=i.toString();;
	}

	
	  function saveValidate()
      {
        if (confirm("Do you want to save these assignment?") == true) {
		   return true ;
	} else {
		 return false;
            }		 
      }
</script> 


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
                        <form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("CfsModule/lclAssignmentPerform_new");?>" method="post">
							<div class="form-group">
                                <input type="hidden" id="igmDetailContId" name="igmDetailContId" value=""> 
                                <input type="hidden" id="igmDetailId" name="igmDetailId" value=""> 
                                <input type="hidden" id="id" name="id" value="<?php if($editFlag==1) echo $id; else "";?>">
                                
                                <div class="row">
									<div class="col-sm-12 text-center">
                                        <?php echo $msg; ?>
									</div>
								</div>

								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Expt. Date of Unstuffing: <span class="required">*</span></span>
                                        <input type="date"  align="left" class="form-control" id="expectDate" name="expectDate" value="<?php echo date('Y-m-d', strtotime(' +1 day'))?>"  />
                                        
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Shed: <span class="required">*</span></span>
                                        <select name="contAtShed" id="contAtShed" class="form-control" value="" onchange="getAssignInfo(this.value)";>  
                                            <option value="">--Select---</option>
                                            <option value="CFS/NCT">CFS/NCT</option> 
                                            <option value="CFS/CCT">CFS/CCT</option> 
                                            <option value="13 Shed">13 Shed</option> 
                                            <option value="12 Shed">12 Shed</option> 
                                            <option value="9 Shed">9 Shed</option> 
                                            <option value="8 Shed">8 Shed</option> 
                                            <option value="7 Shed">7 Shed</option> 
                                            <option value="6 Shed">6 Shed</option> 
                                            <option value="5 Shed">5 Shed</option> 
											<option value="4 Shed">4 Shed</option> 
                                            <option value="N Shed">N Shed</option> 
                                            <option value="D Shed">D Shed</option> 
                                            <option value="P Shed">P Shed</option> 	
                                            <option value="F Shed">F Shed</option> 	
                                            <option value="CFS/OFS">CFS/OFS</option> 	
                                        </select>
									</div>												
								</div>

                                <div class="col-md-12">

                                    <table  class="table table-bordered table-hover table-striped" align="center"  id="mytbl_cont" width="100%">
                                        <tr>
                                            <th align="center" width="3%">SL</td>
                                            <th style="border-collapse: collapse;" width="10%" align="center">Cont No</th>
                                            <th style="border-collapse: collapse;" width="18%" align="center">Vessel Name</th>
                                            <th style="border-collapse: collapse;" width="8%" align="center">Imp Reg No</th>
                                            <th style="border-collapse: collapse;" width="4%" align="center">Size</th>
                                            <th style="border-collapse: collapse;" width="4%" align="center">Height</th>
                                            <th style="border-collapse: collapse;" width="4%" align="center">MLO</th>
                                            <th style="border-collapse: collapse;" width="11%" align="center">STEV</th>
                                            <th style="border-collapse: collapse;" width="8%" align="center">Cont at Shed</th>
                                            <th style="border-collapse: collapse;" width="8%" align="center">Cargo at shed</th>
                                            <th style="border-collapse: collapse;" width="15%" align="center">Desc Cargo</th>
                                            <th style="border-collapse: collapse;" width="5%" align="center">Remarks</th>
                                        </tr>
                                        
                                    </table>

                                </div>

								<div class="row">
									<div class="col-sm-12 text-center">
										<input type="hidden" id="rowNum" name="rowNum" >
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success" onclick="return saveValidate();">Save</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
                                        <?php //echo $msg; ?>
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