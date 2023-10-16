<script>
	function changeTextBox(val)
	{         
		var rotation = document.getElementById("rotation");
		var bl_no= document.getElementById("bl_no");
		var c_bl_no= document.getElementById("c_bl_no");
		var c_rotation= document.getElementById("c_rotation");
		var c_container= document.getElementById("c_container");
		var bl_form= document.getElementById("bl_form");
		var c_form= document.getElementById("c_form");
		var bl_r= document.getElementById("r_input");
		var bl= document.getElementById("bl_input");
		var c_bl= document.getElementById("c_bl_input");
		var c_r= document.getElementById("rotation_data");
		var c= document.getElementById("container_data");
			
		if(val=="bl")
		{
			rotation.style.display="block";
			bl_no.style.display="block";
			c_bl_no.style.display="none";
			c_rotation.style.display="none";
			c_container.style.display="none";
			c_r.value="";
			c.value="";
			c_bl.value="";
			bl_r.value="";
			bl.value="";
			c_form.style.display="none";  
		}

		if(val=="container" )
		{
			c_bl_no.style.display="block";
			c_rotation.style.display="block";
			c_container.style.display="block";
			rotation.style.display="none";
			bl_no.style.display="none";
			c_r.value="";
			c.value="";
			c_bl.value="";
			bl_r.value="";
			bl_r.value="";
			bl.value="";
			bl_form.style.display="none";
		}

		if(val =="" )
		{
			rotation.style.display="none";
			c_bl_no.style.display="none";
			bl_no.style.display="none";
			bl_form.style.display="none";
			c_form.style.display="none";
			c_rotation.style.display="none";
			c_container.style.display="none";
		}
	}
	
	// select bl change - start
	function fetchData()
	{
		if (window.XMLHttpRequest) 
		{
		  	xmlhttp=new XMLHttpRequest();
		} 
        else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var rotation=document.getElementById("r_input");
		
    	var bl_no=document.getElementById("bl_input");
		var r=rotation.value;
		var b=bl_no.value;  

		if(r==""|| b=="")
		{
			if(r=="" && b!="")
			{
				alert("Rotation is Empty");
			}
			else if(b=="" && r!=="")
			{
				alert("BL No is Empty");
			}
			else
			{
				alert("Both Rotation And BL No are Empty");
			}				
		}
		else
		{
			var url="<?php echo site_url('AjaxController/blCheck')?>?rotation="+r+"&bl="+b;
            //alert(url);
			xmlhttp.open("GET",url,false);
            xmlhttp.onreadystatechange=stateChangeAssignment;
            xmlhttp.send();
		}

	}

	function stateChangeAssignment()		// sup or dtl
	{			
	//	alert("hello");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			var rs=jsonData.result.length;
	
			if(rs==0)
			{
				alert("Invalid Rotation or BL No");
				var bl = document.getElementById("bl_form");
				bl.style.display="none";
			}
			else
			{
				if(jsonData.igmType=="sup")
				{
					document.getElementById("igm_id").value=jsonData.result[0].id;
					document.getElementById("igm_type").value=jsonData.igmType;
					var bl = document.getElementById("bl_form");
					var bl_select = document.getElementById("bl_select");
					var preTextArea = document.getElementById("bl_pre_value_textarea");
		            var newTextArea = document.getElementById("bl_new_value_textarea");
		            var preText = document.getElementById("bl_pre_value_text");
		            var newText = document.getElementById("bl_new_value_text");
		            var bl_button = document.getElementById("bl_button");
			        var bl_des = document.getElementById("bl_des");
		            var bl_new_des = document.getElementById("bl_new_des");
		            var bl_pre_text = document.getElementById("bl_pre_text");
		            var bl_new_text = document.getElementById("bl_new_text");
		            bl_des.value="";
			        bl_new_des.value=""; 
		            bl_pre_text.value = "";
		            bl_new_text.value = "";
					bl_select.value="";
					preText.style.display="none";
			        newText.style.display="none";
			        preTextArea.style.display="none";
			        newTextArea.style.display="none";
					bl_button.style.display="none";
					bl.style.display="block";

				}
				else
				{
					document.getElementById("igm_id").value=jsonData.result[0].id;
					document.getElementById("igm_type").value=jsonData.igmType;
					var bl = document.getElementById("bl_form");
					var bl_select = document.getElementById("bl_select");
					var preTextArea = document.getElementById("bl_pre_value_textarea");
		            var newTextArea = document.getElementById("bl_new_value_textarea");
		            var preText = document.getElementById("bl_pre_value_text");
		            var newText = document.getElementById("bl_new_value_text");
		            var bl_button = document.getElementById("bl_button");
			        var bl_des = document.getElementById("bl_des");
		            var bl_new_des = document.getElementById("bl_new_des");
		            var bl_pre_text = document.getElementById("bl_pre_text");
		            var bl_new_text = document.getElementById("bl_new_text");
		            bl_des.value="";
			        bl_new_des.value=""; 
		            bl_pre_text.value = "";
		            bl_new_text.value = "";
					bl_select.value="";
					preText.style.display="none";
			        newText.style.display="none";
			        preTextArea.style.display="none";
			        newTextArea.style.display="none";
					bl_button.style.display="none";
					bl.style.display="block";
		             
				}
			}
		}
	}
	
	// select bl change - end
	
	// select container change - start
	function fetchContainerData()
	{		
		if (window.XMLHttpRequest) 
		{
		  	xmlhttp=new XMLHttpRequest();
		} 
        else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var c_bl_no= document.getElementById("c_bl_input");
		var rotation=document.getElementById("rotation_data");
    	var c_no=document.getElementById("container_data");
        var r=rotation.value;
		var c=c_no.value;
		var c_bl= c_bl_no.value;
		if(r==""|| c=="" || c_bl=="")
		{
			if(r=="" && c!="" && c_bl!="")
			{
				alert("Rotation is Empty");
			}
			else if(c=="" && r!=""  && c_bl!="")
			{
				alert("Container No is Empty");
			}
			else if(c_bl=="" && c!=""  && r!="")
			{
				alert("BL No is Empty");
			}
			else if(r=="" && c==""  && c_bl!="")
			{
				alert("Rotation And Container NO are Empty");
			}
			else if(r!="" && c==""  && c_bl=="")
			{
				alert("BL No And Container No are Empty");
			}
			else if(r=="" && c!=""  && c_bl=="")
			{
				alert("BL No And Container No are Empty");
			}
			else
			{
				alert(" Rotation,Container No And BL No are Empty");
			}	
		}
		else
		{
			var url="<?php echo site_url('AjaxController/containerCheck')?>?rotation="+r+"&container="+c+"&bl="+c_bl;
            //alert(url);
            xmlhttp.open("GET",url,false);
            xmlhttp.onreadystatechange=stateChangeContainer;
            xmlhttp.send();
		
		}
	}
			
	function stateChangeContainer()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		   //alert(val);
		  
			var jsonData = JSON.parse(val);	
			var rs=jsonData.result.length;
			//alert(rs);
			if(rs==0)
			{
				alert("Invalid Rotation or Container No");
				var c = document.getElementById("c_form");
		        c.style.display="none";			  
			}
			else
			{
				if(jsonData.igmType=="sup")
				{
					document.getElementById("c_igm_id").value=jsonData.result[0].id;
					document.getElementById("c_igm_type").value=jsonData.igmType;
					var c = document.getElementById("c_form");
					var c_select = document.getElementById("c_select");
					var preText = document.getElementById("c_pre_value_text");
		            var newText = document.getElementById("c_new_value_text");
		            var c_pre_text = document.getElementById("c_pre_text");
		            var c_new_text = document.getElementById("c_new_text");
					var c_button = document.getElementById("c_button");
			        preText.style.display="none";
			        newText.style.display="none";
					c_button.style.display="none";
		            c_select.value="";
					c_pre_text.value = "";
		            c_new_text.value = "";
					c.style.display="block";					 
				}
				else
				{
					document.getElementById("c_igm_id").value=jsonData.result[0].id;
					document.getElementById("c_igm_type").value=jsonData.igmType;
					var c = document.getElementById("c_form");
					var c_select = document.getElementById("c_select");
					var preText = document.getElementById("c_pre_value_text");
		            var newText = document.getElementById("c_new_value_text");
		            var c_pre_text = document.getElementById("c_pre_text");
		            var c_new_text = document.getElementById("c_new_text");
					var c_button = document.getElementById("c_button");
			        preText.style.display="none";
			        newText.style.display="none";
					c_button.style.display="none";
		            c_select.value="";
					c_pre_text.value = "";
		            c_new_text.value = "";
					c.style.display="block";
				}
			}			
		}	
	}
	
	// select container change - end
	
	// get bl prev and new value - start
	function changeBLField(val)
	{
	//	alert("get bl prev and new");
		var preTextArea = document.getElementById("bl_pre_value_textarea");
		var newTextArea = document.getElementById("bl_new_value_textarea");
		var preText = document.getElementById("bl_pre_value_text");
		var newText = document.getElementById("bl_new_value_text");
		var bl_button = document.getElementById("bl_button");
		
		if(val=="")
		{
			preTextArea.style.display="none";
			newTextArea.style.display="none";
			preText.style.display="none";
			newText.style.display="none";
			bl_button.style.display="none";

		}
		else
		{
			if (window.XMLHttpRequest) 
		    {
		  	  xmlhttp=new XMLHttpRequest();
		    } 
			else 
			{   
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			var rotation=document.getElementById("r_input");
			var bl_no=document.getElementById("bl_input");
			var igm=document.getElementById("igm_type");
			var igm_type=igm.value;
			var r=rotation.value;
			var b=bl_no.value;
			var url="<?php echo site_url('AjaxController/getSelectedDetail')?>?rotation="+r+"&bl="+b+"&value="+val+"&igm_type="+igm_type;
			// alert(url);
			xmlhttp.open("GET",url,false);
			xmlhttp.onreadystatechange=fetchDetailData;
			xmlhttp.send();  
		}
	}

	function fetchDetailData()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{						
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);
			
			/*
			if(jsonData.column=="Pack_Info")
				alert("Pack_Info");
			else
				alert("not Pack_Info");
			*/
			
			var preTextArea = document.getElementById("bl_pre_value_textarea");
		    var newTextArea = document.getElementById("bl_new_value_textarea");
		    var preText = document.getElementById("bl_pre_value_text");
		    var newText = document.getElementById("bl_new_value_text");
		    var bl_button = document.getElementById("bl_button");
			var bl_des = document.getElementById("bl_des");
		    var bl_new_des = document.getElementById("bl_new_des");
		    var bl_pre_text = document.getElementById("bl_pre_text");
		    var bl_new_text = document.getElementById("bl_new_text");
		    bl_des.value="";
			bl_new_des.value=""; 
		    bl_pre_text.value = "";
		    bl_new_text.value = "";
			
			var div_packInfo = document.getElementById('div_packInfo');
			
			var prevPackNumber = document.getElementById('prevPackNumber');
			var newPackNumber = document.getElementById('newPackNumber');
			var prevPackDesc = document.getElementById('prevPackDesc');
			var newPackDesc = document.getElementById('newPackDesc');
			var prevPackMarksNumber = document.getElementById('prevPackMarksNumber');
			var newPackMarksNumber = document.getElementById('newPackMarksNumber');
			prevPackNumber.value = "";
			newPackNumber.value = "";
			prevPackDesc.value = "";
			newPackDesc.value = "";
			prevPackMarksNumber.value = "";
			newPackMarksNumber.value = "";
			
			var div_exporterInfo = document.getElementById('div_exporterInfo');
			
			var prevExporterName = document.getElementById('prevExporterName');
			var newExporterName = document.getElementById('newExporterName');
			var prevExporterAddress = document.getElementById('prevExporterAddress');
			var newExporterAddress = document.getElementById('newExporterAddress');
			prevExporterName.value = "";
			newExporterName.value = "";
			prevExporterAddress.value = "";
			newExporterAddress.value = "";
			
			var div_notifyInfo = document.getElementById('div_notifyInfo');
			
			var prevNotifyCode = document.getElementById('prevNotifyCode');
			var newNotifyCode = document.getElementById('newNotifyCode');
			var prevNotifyName = document.getElementById('prevNotifyName');
			var newNotifyName = document.getElementById('newNotifyName');
			var prevNotifyAddress = document.getElementById('prevNotifyAddress');
			var newNotifyAddress = document.getElementById('newNotifyAddress');
			var prevNotifyDesc = document.getElementById('prevNotifyDesc');
			var newNotifyDesc = document.getElementById('newNotifyDesc');
			prevNotifyCode.value = "";
			newNotifyCode.value = "";
			prevNotifyName.value = "";
			newNotifyName.value = "";
			prevNotifyAddress.value = "";
			newNotifyAddress.value = "";
			prevNotifyDesc.value = "";
			newNotifyDesc.value = "";
			
			var div_consigneeInfo = document.getElementById('div_consigneeInfo');
			
			var prevConsigneeCode = document.getElementById('prevConsigneeCode');
			var newConsigneeCode = document.getElementById('newConsigneeCode');
			var prevConsigneeName = document.getElementById('prevConsigneeName');
			var newConsigneeName = document.getElementById('newConsigneeName');
			var prevConsigneeAddress = document.getElementById('prevConsigneeAddress');
			var newConsigneeAddress = document.getElementById('newConsigneeAddress');
			var prevConsigneeDesc = document.getElementById('prevConsigneeDesc');
			var newConsigneeDesc = document.getElementById('newConsigneeDesc');
			prevConsigneeCode.value = "";
			newConsigneeCode.value = "";
			prevConsigneeName.value = "";
			newConsigneeName.value = "";
			prevConsigneeAddress.value = "";
			newConsigneeAddress.value = "";
			prevConsigneeDesc.value = "";
			newConsigneeDesc.value = "";
			
			if(jsonData.column=="Pack_Info")
			{
				document.getElementById('groupFlag').value = "Pack_Info";
				
				div_packInfo.style.display="block";
				div_exporterInfo.style.display="none";
				div_notifyInfo.style.display="none";
				div_consigneeInfo.style.display="none";
				
				preText.style.display="none";
				newText.style.display="none";
				preTextArea.style.display="none";
				newTextArea.style.display="none";
				bl_button.style.display="block";
								
				prevPackNumber.value = jsonData.result[0].Pack_Number;	
				prevPackDesc.value = jsonData.result[0].Pack_Description;	
				prevPackMarksNumber.value = jsonData.result[0].Pack_Marks_Number;

				newPackNumber.value = prevPackNumber.value;	
				newPackDesc.value = prevPackDesc.value;	
				newPackMarksNumber.value = prevPackMarksNumber.value;				
			}
			else if(jsonData.column=="Exporter_Info")
			{
				document.getElementById('groupFlag').value = "Exporter_Info";
				
				div_packInfo.style.display="none";
				div_exporterInfo.style.display="block";
				div_notifyInfo.style.display="none";
				div_consigneeInfo.style.display="none";
				
				preText.style.display="none";
				newText.style.display="none";
				preTextArea.style.display="none";
				newTextArea.style.display="none";
				bl_button.style.display="block";
				
				prevExporterName.value = jsonData.result[0].Exporter_name;					
				prevExporterAddress.value = jsonData.result[0].Exporter_address;	
				
				newExporterName.value = prevExporterName.value;					
				newExporterAddress.value = prevExporterAddress.value;	
			}
			else if(jsonData.column=="Notify_Info")
			{
				document.getElementById('groupFlag').value = "Notify_Info";
				
				div_packInfo.style.display="none";
				div_exporterInfo.style.display="none";
				div_notifyInfo.style.display="block";
				div_consigneeInfo.style.display="none";
				
				preText.style.display="none";
				newText.style.display="none";
				preTextArea.style.display="none";
				newTextArea.style.display="none";
				bl_button.style.display="block";
				
				prevNotifyCode.value = jsonData.result[0].Notify_code;
				prevNotifyName.value = jsonData.result[0].Notify_name;
				prevNotifyAddress.value = jsonData.result[0].Notify_address;
				prevNotifyDesc.value = jsonData.result[0].NotifyDesc;
				
				newNotifyCode.value = prevNotifyCode.value;
				newNotifyName.value = prevNotifyName.value;
				newNotifyAddress.value = prevNotifyAddress.value;
				newNotifyDesc.value = prevNotifyDesc.value;
			}
			else if(jsonData.column=="Consignee_Info")
			{				
				document.getElementById('groupFlag').value = "Consignee_Info";
				
				div_packInfo.style.display="none";
				div_exporterInfo.style.display="none";
				div_notifyInfo.style.display="none";
				div_consigneeInfo.style.display="block";
				
				preText.style.display="none";
				newText.style.display="none";
				preTextArea.style.display="none";
				newTextArea.style.display="none";
				bl_button.style.display="block";
				
				prevConsigneeCode.value = jsonData.result[0].Consignee_code;				
				prevConsigneeName.value = jsonData.result[0].Consignee_name;
				prevConsigneeAddress.value = jsonData.result[0].Consignee_address;
				prevConsigneeDesc.value = jsonData.result[0].ConsigneeDesc;
				
				newConsigneeCode.value = prevConsigneeCode.value;				
				newConsigneeName.value = prevConsigneeName.value;
				newConsigneeAddress.value = prevConsigneeAddress.value;
				newConsigneeDesc.value = prevConsigneeDesc.value;
			}
			else if(jsonData.column =="Description_of_Goods")
			{					
				document.getElementById('groupFlag').value = "Description_of_Goods";		
				
				div_packInfo.style.display="none";
				div_exporterInfo.style.display="none";
				div_notifyInfo.style.display="none";
				div_consigneeInfo.style.display="none";
				
				preText.style.display="none";
				newText.style.display="none";
				preTextArea.style.display="block";
				newTextArea.style.display="block";
				bl_button.style.display="block";
				bl_des.value=jsonData.result[0].rnvalue;		 				
			}			
		    else
		    {
				document.getElementById('groupFlag').value = "Others";
				
				div_packInfo.style.display="none";
				div_exporterInfo.style.display="none";
				div_notifyInfo.style.display="none";
				div_consigneeInfo.style.display="none";
				
				preTextArea.style.display="none";
			    newTextArea.style.display="none";
			    preText.style.display="block";
			    newText.style.display="block";
			    bl_button.style.display="block";
			    bl_pre_text.value=jsonData.result[0].rnvalue;
		    }
		}		
	}
	
	// get bl prev and new value - end
	
	// get container prev and new value - start
	function changeContainerField (val)
	{		
		var preText = document.getElementById("c_pre_value_text");
		var newText = document.getElementById("c_new_value_text");
		var button = document.getElementById("c_button");
		
		if(val=="")
		{
			
			preText.style.display="none";
			newText.style.display="none";
			button.style.display="none";

		}

		else
		{
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			var rotation=document.getElementById("rotation_data");
			var c_bl_no=document.getElementById("c_bl_input");
			var container_no=document.getElementById("container_data");
			var igm=document.getElementById("c_igm_type");
			var igm_type=igm.value;
			var r=rotation.value;
			var bl=c_bl_no.value;
			var container_value=container_no.value;
			var url="<?php echo site_url('AjaxController/getSelectedContainerDetail')?>?rotation="+r+"&container="+container_value+"&value="+val+"&igm_type="+igm_type+"&bl="+bl;
		   // alert(url);
			xmlhttp.open("GET",url,false);
			xmlhttp.onreadystatechange=fetchDetailContainerData;
			xmlhttp.send();  
		}
	}
	
	function fetchDetailContainerData()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			var preText = document.getElementById("c_pre_value_text");
		    var newText = document.getElementById("c_new_value_text");
		    var button = document.getElementById("c_button");
		    var c_pre_text = document.getElementById("c_pre_text");
		    var c_new_text = document.getElementById("c_new_text");
		    c_pre_text.value = "";
		    c_new_text.value = "";
			preText.style.display="block";
			newText.style.display="block";
			button.style.display="block";
			c_pre_text.value=jsonData.result[0].rnvalue;
		}		
	}

	// get container prev and new value - end

	
	
	function blFormValidate()
	{
		var preTextArea = document.getElementById("bl_pre_value_textarea");
		var newTextArea = document.getElementById("bl_new_value_textarea");
		var preText = document.getElementById("bl_pre_value_text");
		var newText = document.getElementById("bl_new_value_text");
		var textAreaValue = document.getElementById("bl_new_des").value;
		var textValue = document.getElementById("bl_new_text").value;
		
		var groupFlag = document.getElementById("groupFlag").value;
		
		if(groupFlag == "Pack_Info")
		{
			var prevPackNumber = document.getElementById("prevPackNumber");
			var prevPackDesc = document.getElementById("prevPackDesc");
			var prevPackMarksNumber = document.getElementById("prevPackMarksNumber");

			var newPackNumber = document.getElementById("newPackNumber");
			var newPackDesc = document.getElementById("newPackDesc");
			var newPackMarksNumber = document.getElementById("newPackMarksNumber");
			
			if(newPackNumber.value=="" || newPackDesc.value == "" || newPackMarksNumber.value == "")
			{
				alert("Fill all the new value fields for Pack Info. If there is no change then fill with previous data.");
				return false;
			}
			else
			{
				return true;
			}
		}
		else if(groupFlag=="Exporter_Info")
		{
			var prevExporterName = document.getElementById("prevExporterName");
			var prevExporterAddress = document.getElementById("prevExporterAddress");
			
			var newExporterName = document.getElementById("newExporterName");
			var newExporterAddress = document.getElementById("newExporterAddress");
			
			if(newExporterName.value=="" || newExporterAddress.value == "")
			{
				alert("Fill all the new value fields for Exporter Info. If there is no change then fill with previous data.");
				return false;
			}
			else
			{
				return true;
			}
		}
		else if(groupFlag=="Notify_Info")
		{
			var prevNotifyCode = document.getElementById("prevNotifyCode");
			var prevNotifyName = document.getElementById("prevNotifyName");
			var prevNotifyAddress = document.getElementById("prevNotifyAddress");
			var prevNotifyDesc = document.getElementById("prevNotifyDesc");

			var newNotifyCode = document.getElementById("newNotifyCode");
			var newNotifyName = document.getElementById("newNotifyName");
			var newNotifyAddress = document.getElementById("newNotifyAddress");
			var newNotifyDesc = document.getElementById("newNotifyDesc");
			
			if(newNotifyCode.value=="" || newNotifyName.value == "" || newNotifyAddress.value == "" || newNotifyDesc.value == "")
			{
				alert("Fill all the new value fields for Notify Info. If there is no change then fill with previous data.");
				return false;
			}
			else
			{
				return true;
			}
		}
		else if(groupFlag=="Consignee_Info")
		{
			var prevConsigneeCode = document.getElementById("prevConsigneeCode");
			var prevConsigneeName = document.getElementById("prevConsigneeName");
			var prevConsigneeAddress = document.getElementById("prevConsigneeAddress");
			var prevConsigneeDesc = document.getElementById("prevConsigneeDesc");

			var newConsigneeCode = document.getElementById("newConsigneeCode");
			var newConsigneeName = document.getElementById("newConsigneeName");
			var newConsigneeAddress = document.getElementById("newConsigneeAddress");
			var newConsigneeDesc = document.getElementById("newConsigneeDesc");
			
			if(newConsigneeCode.value=="" || newConsigneeName.value == "" || newConsigneeAddress.value == "" || newConsigneeDesc.value == "")
			{
				alert("Fill all the new value fields for Consignee Info. If there is no change then fill with previous data.");
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			if(preTextArea.style.display=="none" && newTextArea.style.display=="none" )
			{
				if(textValue=="")
				{
					alert("New Value is Empty,Give a New Value");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				if(textAreaValue=="")
				{
					alert("New Value Is Empty,Give A New Value To Update");
					return false;
				}
				else
				{
					return true;
				}
			}
		}				
	}
	
	function ContainerFormValidate() 
	{
		var newText = document.getElementById("c_new_text").value;
		if(newText=="")
		{
			alert("New Value Is Empty,Give A New Value To Update");
			return false;
		}
		else
		{
			return true;
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
					<form class="form-horizontal form-bordered" method="POST" action="" id="myform" name="myform" >
						<div class="form-group">
							
							<label class="col-md-3 control-label"><?php echo $msg; ?></label>
							<div class="col-md-6">									
								<div class="form-group">
									<label class="col-md-4 control-label">Change Type :</label>
									<div class="col-md-8">
										<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
											<option value="">--Select--</option>
											<option value="bl">BL</option>
											<option value="container">Container</option>
											
										</select>
									</div>
								</div>		
								<div class="form-group" id="rotation" style="display:none">
								   <label class="col-md-4 control-label">Rotation :</label>
									<!--span class="input-group-addon span_width">Search Value : </span-->
									<div class="col-md-8">
									  <input type="text" id="r_input" name="r_name"  class="form-control" />
									</div>
								</div>

								<div class="form-group" id="bl_no"  style="display:none">
									<label class="col-md-4 control-label">BL No :</label>
									<!--span class="input-group-addon span_width">Search Value : </span-->
									<div class="col-md-8">
									  <input type="text" id="bl_input" name="bl_name" class="form-control" onblur="fetchData();"/>
									</div>
								</div>
								<div class="form-group" id="c_rotation"  style="display:none" >
									<label class="col-md-4 control-label">Rotation :</label>
									<!--span class="input-group-addon span_width">Search Value : </span-->
									<div class="col-md-8">
									  <input type="text" id="rotation_data" name="search_c_number" class="form-control" />
									</div>
								</div>

								<div class="form-group" id="c_bl_no"  style="display:none">
									<label class="col-md-4 control-label">BL No :</label>
									<!--span class="input-group-addon span_width">Search Value : </span-->
									<div class="col-md-8">
									  <input type="text" id="c_bl_input" name="bl_name" class="form-control"/>
									</div>
								</div>	
								
								<div class="form-group" id="c_container"  style="display:none" >
									<label class="col-md-4 control-label">Container No :</label>
									<!--span class="input-group-addon span_width">Search Value : </span-->
									<div class="col-md-8">
									  <input type="text" id="container_data" name="search_c_number" class="form-control" onblur="fetchContainerData();"/>
									</div>
								</div>
																	
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>

	<div class="row" id="bl_form" style="display:none" >
		<div class="col-lg-12">						
			<section class="panel">
			<div class="form-group" align="center"><h3><b>Make Change For BL </b><h3></div>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("GateController/get_bl_or_container_data"); ?>"  id="myform" name="myform" onsubmit="return blFormValidate();">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
										
								<div class="form-group" style="display:none" >
								<label class="col-md-4 control-label">Id :</label>									
									<div class="col-md-8">
										<input type="hidden" id="igm_id" name="bl_id" class="form-control"/>									  
									</div>
								</div>
								<div class="form-group" style="display:none"  >
								   <label class="col-md-4 control-label">Igm Type :</label>									
									<div class="col-md-8">
										<input type="hidden" id="igm_type" name="bl_igm_type" class="form-control" />
									</div>
								</div>
								<div class="form-group" >
									<label class="col-md-4 control-label">Field... :</label>									
									<div class="col-md-8">
									<select name="bl_selected" id="bl_select" class="form-control" onchange="changeBLField(this.value);" >
									<option value="">--Select--</option>
										<?php foreach($select_bl as $key=>$bl)  { ?> 
									<option value="<?php echo $key;?>"><?php echo $bl; ?></option>
										<?php }?> 
									</select>
										
									</div>
								</div>
								
								<div class="form-group" style="display:none;" id="bl_pre_value_textarea"  >
									<label class="col-md-4 control-label">Previous Value :</label>									
									<div class="col-md-8">
										<textarea name="bl_pre_textarea" id="bl_des" rows="4" cols="38" readonly></textarea>
									</div>
								</div>
								<div class="form-group" style="display:none;" id="bl_new_value_textarea">
									<label class="col-md-4 control-label">New Value :</label>									
									<div class="col-md-8">
										<textarea name="bl_new_textarea" rows="4" id="bl_new_des" cols="38"></textarea>
									</div>
								</div>
								<div class="form-group" style="display:none;" id="bl_pre_value_text" >
								   <label class="col-md-4 control-label">Previous Value  :</label>									
									<div class="col-md-8">
										<input type="text" name="bl_pre_text"  id="bl_pre_text" class="form-control" readonly />
									</div>
								</div>	
								<div class="form-group" style="display:none;" id="bl_new_value_text">
								   <label class="col-md-4 control-label">New Value  :</label>
									<div class="col-md-8">
										<input type="text" name="bl_new_text" class="form-control"  id="bl_new_text" />
									</div>
								</div>

								<!-- Pack Info - start -->
								<input type="hidden" name="groupFlag" id="groupFlag" value="" />
								<div id="div_packInfo" style="display:none">
										
									<div class="form-group" style="display:block;" id="div_newPackNumber">
										<label class="col-md-4 control-label">Pack Number :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevPackNumber"  id="prevPackNumber" class="form-control" readonly />
											<input type="text" name="newPackNumber" id="newPackNumber" class="form-control" />
										</div>
									</div>
	
									<div class="form-group" style="display:block;" id="div_newPackDesc">
										<label class="col-md-4 control-label">Pack Description :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevPackDesc"  id="prevPackDesc" class="form-control" readonly />
											<input type="text" name="newPackDesc" id="newPackDesc" class="form-control" />
										</div>
									</div>

									<div class="form-group" style="display:block;" id="div_newPackMarksNumber">
										<label class="col-md-4 control-label">Pack Marks Number :</label>
										<div class="col-md-8">											
											<input type="hidden" name="prevPackMarksNumber" id="prevPackMarksNumber" class="form-control" readonly />
											<textarea name="newPackMarksNumber" rows="4" id="newPackMarksNumber" cols="38"></textarea>
										</div>
									</div>
								</div>
								<!-- Pack Info - end -->
																
								<!-- Exporter Info - start -->
								<div id="div_exporterInfo" style="display:none">
								
									<div class="form-group" style="display:block;" id="div_newExporterName">
										<label class="col-md-4 control-label">Exporter Name :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevExporterName"  id="prevExporterName" class="form-control" readonly />
											<input type="text" name="newExporterName" id="newExporterName" class="form-control" />
										</div>
									</div>
	
									<div class="form-group" style="display:block;" id="div_newExporterAddress">
										<label class="col-md-4 control-label">Exporter Address :</label>
										<div class="col-md-8">
											<!--textarea name="prevExporterAddress" rows="4" id="prevExporterAddress" cols="38" readonly></textarea-->
											<input type="hidden" name="prevExporterAddress"  id="prevExporterAddress" class="form-control" readonly />
											<textarea name="newExporterAddress" rows="4" id="newExporterAddress" cols="38" ></textarea>
										</div>
									</div>									
								</div>
								<!-- Exporter Info - end -->
								
								<!-- Notify Info - start -->
								<div id="div_notifyInfo" style="display:none">
										
									<div class="form-group" style="display:block;" id="div_newNotifyCode">
										<label class="col-md-4 control-label">Notify Code :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevNotifyCode"  id="prevNotifyCode" class="form-control" readonly />
											<input type="text" name="newNotifyCode" id="newNotifyCode" class="form-control" />
										</div>
									</div>
									
									<div class="form-group" style="display:block;" id="div_newNotifyName">
										<label class="col-md-4 control-label">Notify Name :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevNotifyName"  id="prevNotifyName" class="form-control" readonly />
											<input type="text" name="newNotifyName" id="newNotifyName" class="form-control" />
										</div>
									</div>
									
									<div class="form-group" style="display:block;" id="div_newNotifyAddress">
										<label class="col-md-4 control-label">Notify Address :</label>
										<div class="col-md-8">											
											<input type="hidden" name="prevNotifyAddress"  id="prevNotifyAddress" class="form-control" readonly />
											<textarea name="newNotifyAddress" rows="4" id="newNotifyAddress" cols="38"></textarea>
										</div>
									</div>
									
									<div class="form-group" style="display:block;" id="div_newNotifyDesc">
										<label class="col-md-4 control-label">Notify Description :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevNotifyDesc"  id="prevNotifyDesc" class="form-control" readonly />
											<textarea name="newNotifyDesc" rows="4" id="newNotifyDesc" cols="38"></textarea>
										</div>
									</div>
								</div>	
								<!-- Notify Info - end -->
								
								<!-- Consignee Info - start -->
								<div id="div_consigneeInfo" style="display:none">
									<div class="form-group" style="display:block;" id="div_newConsigneeCode">
										<label class="col-md-4 control-label">Consignee Code :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevConsigneeCode"  id="prevConsigneeCode" class="form-control" readonly />
											<input type="text" name="newConsigneeCode" id="newConsigneeCode" class="form-control" />
										</div>
									</div>
									
									<div class="form-group" style="display:block;" id="div_newConsigneeName">
										<label class="col-md-4 control-label">Consignee Name :</label>
										<div class="col-md-8">
											<input type="hidden" name="prevConsigneeName"  id="prevConsigneeName" class="form-control" readonly />
											<input type="text" name="newConsigneeName" id="newConsigneeName" class="form-control" />
										</div>
									</div>
									
									<div class="form-group" style="display:block;" id="div_newConsigneeAddress">
										<label class="col-md-4 control-label">Consignee Address :</label>
										<div class="col-md-8">		
											<input type="hidden" name="prevConsigneeAddress"  id="prevConsigneeAddress" class="form-control" readonly />
											<textarea name="newConsigneeAddress" rows="4" id="newConsigneeAddress" cols="38"></textarea>
										</div>
									</div>
										
									<div class="form-group" style="display:block;" id="div_newConsigneeDesc">
										<label class="col-md-4 control-label">Consignee Description :</label>
										<div class="col-md-8">		
											<input type="hidden" name="prevConsigneeDesc"  id="prevConsigneeDesc" class="form-control" readonly />
											<textarea name="newConsigneeDesc" rows="4" id="newConsigneeDesc" cols="38"></textarea>
										</div>
									</div>
								</div>	
								<!-- Consignee Info - end -->
								
								<div class="row" style="display:none;" id="bl_button" >
									<div class="col-md-9 text-right">
										<input type="submit" name="bl_save" class="mb-xs mt-xs mr-xs btn btn-success" value="Save" />
									</div>													
								</div>	

							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>



	<div class="row" id="c_form" style="display:none" >
		<div class="col-lg-12">						
		  <section class="panel">
			 <div class="form-group" align="center"><h3><b>Make Change For Container </b><h3></div>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("GateController/get_bl_or_container_data"); ?>"  id="myform" name="myform" onsubmit="return ContainerFormValidate();">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
										
										<div class="form-group" style="display:none;" >
										<label class="col-md-4 control-label">Id :</label>
											<!--span class="input-group-addon span_width">Search Value : </span-->
											<div class="col-md-8">
											  <input type="hidden" id="c_igm_id" name="c_id" class="form-control"/>
											  <!--input type="text" id="c_igm_id" name="c_id" class="form-control"/-->
											</div>
										</div>
										<div class="form-group" style="display:none;" >
										   <label class="col-md-4 control-label">Igm Type :</label>
											<!--span class="input-group-addon span_width">Search Value : </span-->
											<div class="col-md-8">
											  <input type="hidden" id="c_igm_type" name="c_igm_type" class="form-control" />
											  <!--input type="text" id="c_igm_type" name="c_igm_type" class="form-control" /-->
											</div>
										</div>
										<div class="form-group" >
										   <label class="col-md-4 control-label">Field :</label>
											<!--span class="input-group-addon span_width">Search Value : </span-->
											<div class="col-md-8">
											<select name="c_selected" id="c_select" class="form-control" onchange="changeContainerField(this.value);" >
											<option value="">--Select--</option>
												<?php foreach($select_container as $key=>$cl)  { ?> 
											<option value="<?php echo $key;?>"><?php echo $cl; ?></option>
												<?php }?> 
											</select>
												
											</div>
										</div>
								
										
	
										<div class="form-group" style="display:none;" id="c_pre_value_text" >
										   <label class="col-md-4 control-label">Previous Value  :</label>
											<!--span class="input-group-addon span_width">Search Value : </span-->
											<div class="col-md-8">
											  <input type="text"  name="c_pre_text"  id="c_pre_text" class="form-control" readonly />
											</div>
										</div>	
										<div class="form-group" style="display:none;" id="c_new_value_text">
										   <label class="col-md-4 control-label">New Value  :</label>
											<!--span class="input-group-addon span_width">Search Value : </span-->
											<div class="col-md-8">
											  <input type="text"  name="c_new_text" class="form-control"  id="c_new_text" />
											</div>
										</div>	
										<div class="row" style="display:none;" id="c_button">
										  <div class="col-md-9 text-right">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<!--button  type="submit" name="c_save" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
											 <input type="submit" name="c_save" class="mb-xs mt-xs mr-xs btn btn-success" value="Save" />
										</div>													
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