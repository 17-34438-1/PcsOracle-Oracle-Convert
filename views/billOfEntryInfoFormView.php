<?php include('cssAssets.php') ?>
<?php include('headerTop.php') ?>
<?php include('sidebar.php') ?>

<script>
function getBlInfo(blNo)  
   {        
		if(blNo=="" || blNo==" ")
		{
			alert("BL Field is blank");
			//document.getElementById("rotNo").focus();
			return false;
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
			//alert(rotNo);
			//alert(blNo);
			xmlhttp.onreadystatechange=stateChangegetBLInfo;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getBLInfoForCnF')?>?blNo="+blNo,false);				
			xmlhttp.send();	 
		}

   }


    function stateChangegetBLInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{			
                var val = xmlhttp.responseText;
		   // alert(val);		
                //var val = xmlhttp.responseText;
                //var strArr = val.split("|");
		// alert(val);
			
		var jsonData = JSON.parse(val);
		// alert(jsonData[i].Import_Rotation_No);
                
                if(jsonData.blInfo1[0].Bill_of_Entry_No!=='' || jsonData.blInfo1[0].Bill_of_Entry_No!== ' ')
                    {
                         document.getElementById("be_no").value=jsonData.blInfo1[0].Bill_of_Entry_No;
                    }
                if(jsonData.blInfo1[0].Bill_of_Entry_Date!=='' || jsonData.blInfo1[0].Bill_of_Entry_Date!== ' ')
                    {
                         document.getElementById("be_date").value=jsonData.blInfo1[0].Bill_of_Entry_Date;
                    }
                 if(jsonData.blInfo1[0].jetty_sirkar_lic!=='' || jsonData.blInfo1[0].jetty_sirkar_lic!== ' ')
                    {
                         document.getElementById("jetty_sarkar").value=jsonData.blInfo1[0].jetty_sirkar_lic;
                    }
                 if(jsonData.blInfo1[0].office_code!=='' || jsonData.blInfo1[0].office_code!== ' ')
                    {
                         document.getElementById("office_code").value=jsonData.blInfo1[0].office_code;
                    }
                
                
                
		var tbl = document.getElementById('myTable');
                $("#myTable tr").remove(); 
		  
//		var tableRows = tbl.getElementsByTagName("tr");
//                        
//		var rmvroLn = tableRows.length;
//
//		for(var i=rmvroLn;i>=0;i--)
//		{
//			//tbl.deleteRow(i);
//                        tbl.removeChild(tableRows[i]);
//		}
        //    alert(jsonData[0].blInfo1.Import_Rotation_No)
//                         	
                var jsonData = JSON.parse(val);
		for (var i = 0; i < jsonData.blInfo1.length; i++) 
		{
			var tr1 = document.createElement("tr");
			var tr2 = document.createElement("tr");
			var tr3 = document.createElement("tr");
			var tr4 = document.createElement("tr");
			//var tr1 = document.createElement("tr");
			
			
			
			var td1 = document.createElement('td');
			var text1 = document.createTextNode(jsonData.blInfo1[i].Import_Rotation_No);
			//alert(text1);
			td1.appendChild(text1);
			
			var td2 = document.createElement('td');
			var createAText1 = document.createTextNode("Rotation:   ");
			//var textRot = "Rotation";
			//alert(text1);
			td2.appendChild(createAText1);
			

			var td3 = document.createElement('td');
			var text2 = document.createTextNode(jsonData.blInfo1[i].Pack_Number);
			td3.appendChild(text2);
			
			var td4 = document.createElement('td');
			var createAText2 = document.createTextNode('Pack_Number:  ');
			td4.appendChild(createAText2);
			
			
			var td5 = document.createElement('td');
			var text3 = document.createTextNode(jsonData.blInfo1[i].Pack_Description);
			td5.appendChild(text3);
			
			var td6 = document.createElement('td');
			var createAText3 = document.createTextNode("Pack_Description:   ");
			td6.appendChild(createAText3);
			
			
			var td7 = document.createElement('td');
			var text4 = document.createTextNode(jsonData.blInfo1[i].Pack_Marks_Number);
			td7.appendChild(text4);
			
			var td8 = document.createElement('td');
			var createAText4 = document.createTextNode("Pack_Marks_Number:  ");
			td8.appendChild(createAText4);
			
	
                        tr1.appendChild(td2);
                        tr1.appendChild(td1);
                            //cell1.innerHTML = "NEW CELL1";

                        tr2.appendChild(td4);
                        tr2.appendChild(td3);
                        tr3.appendChild(td6);
                        tr3.appendChild(td5);
                        tr4.appendChild(td8);
                        tr4.appendChild(td7);

                            //tr.appendChild(td5);


                        tbl.appendChild(tr1);
                        tbl.appendChild(tr2);
                        tbl.appendChild(tr3);
                        tbl.appendChild(tr4);
                    
		}
                
                
                var tbl2 = document.getElementById('myTable2');
              	var rowslenth = tbl2.getElementsByTagName("tr").length;
		var rmvroLn = rowslenth-1;

		for(var j=rmvroLn;j>=1;j--)
		{
			tbl2.deleteRow(j);
		}
                
                for (var i = 0; i < jsonData.blInfo2.length; i++) 
		{
			var tr = document.createElement("tr");

			//var tr1 = document.createElement("tr");
			
                        var td0 = document.createElement('td');
			var text0 = document.createTextNode(i+1);
			td0.appendChild(text0);
			
			var td1 = document.createElement('td');
                        var text1=document.createTextNode(jsonData.blInfo2[i].cont_number);
                        //var td5 = document.createElement('td');
//                        var container = document.createElement("input");
//                        container.type = "text";
//                       // container.readonly = true;
//			container.name = "container" + i;
//                        container.id = "container" + i;
//			container.value=jsonData.blInfo2[i].cont_number;
//                        container.style.width = "160px";
			//var text5 = document.createTextNode(jsonData.blInfo2[i].cont_gross_weight);
			//td5.appendChild(container);
			//var text1 = document.createTextNode(jsonData.blInfo2[i].cont_number);
			td1.appendChild(text1);                   
                      //  document.getElementById("container"+i).readOnly = true;

    
			
			var td2 = document.createElement('td');
			var text2 = document.createTextNode(jsonData.blInfo2[i].cont_size);
			td2.appendChild(text2);
			
			
			var td3 = document.createElement('td');
			var text3 = document.createTextNode(jsonData.blInfo2[i].cont_height);
			td3.appendChild(text3);
                        
                        var td4 = document.createElement('td');
			var text4 = document.createTextNode(jsonData.blInfo2[i].cont_gross_weight);
			td4.appendChild(text4);
			
                        var td5 = document.createElement('td');
                        var trukInput = document.createElement("input");
                        trukInput.type = "text";
			trukInput.name = "truckNo" + i;
                        trukInput.id = "truckNo" + i;
			//trukInput.value=jsonData.shedBillDetailList[k].qday;
                        trukInput.style.width = "160px";
			//var text5 = document.createTextNode(jsonData.blInfo2[i].cont_gross_weight);
                       if(jsonData.blInfo2[i].truck_no!=='' || jsonData.blInfo2[i].truck_no!==' ')
                        {
                          trukInput.value=jsonData.blInfo2[i].truck_no;
                        }
			td5.appendChild(trukInput);
                        
                        
                        var td6 = document.createElement('td');
                        var contIdInput = document.createElement("input");
                        contIdInput.type = "hidden";
			contIdInput.name = "contId" + i;
                        contIdInput.id = "contId" + i;
                        contIdInput.value=jsonData.blInfo2[i].id;
			td6.appendChild(contIdInput);

	
                       // tr1.appendChild(td2);
                        tr.appendChild(td0);
                        tr.appendChild(td1);
                            //cell1.innerHTML = "NEW CELL1";

                        tr.appendChild(td2);
                        tr.appendChild(td3);
                        tr.appendChild(td4);
                        tr.appendChild(td5);
                        tr.appendChild(td6);

                        tbl2.appendChild(tr);
                    
		}
                document.getElementById("igmDetailsId").value = jsonData.blInfo2[0].igm_detail_id ;
                document.getElementById("numOfRow").value = i ;
                document.getElementById("saveBtn").disabled = false;
			
            }
	}

  </script>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
							
						</div>
					</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" align="right">
							<a href="<?php echo site_url('Controller/List') ?>">
								<button style="margin-left: 35%" class="btn btn-primary btn-sm">
									<i class="fa fa-list"></i>
								</button>
							</a>
						</h2>								
					</header>
					<div class="panel-body" align="center">
					<form name= "myForm" onsubmit="return validate();" action="<?php echo site_url("report/billOfEntryPerform");?>"  target="_blank" method="post">
						<input type="hidden" name="" value="">

						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:250px;">B/L  no. <span class="required">*</span></span>
									<input class="read" type="text" style="width:200px;"  id="bl_no" name="bl_no" value=""  onblur="getBlInfo(this.value)">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:250px;">B/E  no.<span class="required">*</span></span>
									<input class="read" type="text" style="width:200px;"  id="be_no" name="be_no" value="" >
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:250px;">B/E Date. <span class="required">*</span></span>
									<input class="read" type="date" style="width:200px;"  id="be_date" name="be_date" value="" >
                                                
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:250px;">Office Code <span class="required">*</span></span>
									<input class="read" type="text" style="width:200px;"  id="office_code" name="office_code" value="" >
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" style="width:250px;">Jetty Sarkar Name <span class="required">*</span></span>
										<select  id="jetty_sarkar" name="jetty_sarkar" style="width:200px;" value="" >
											<option value="">--Select--</option>
											<?php
											for($i=0; $i<count($jttySr_list); $i++){ ?>
											<option value="<?php echo $jttySr_list[$i]['js_lic_no']; ?>"><?php echo $jttySr_list[$i]['js_name']; ?></option>
											<?php } ?>
										</select>	 	
								</div>
								
								<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->								
										<table id="myTable" class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
												<tr><td></td></tr>

										</table>

								</div>	
								
								<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->							
									 <table id="myTable2" border="1" class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
										<tr>
											<th>SL</th>
											<th>Container No</th>
											<th>Size</th>
											<th>Height</th>
											<th>Weight</th>
											<th>Truck</th>
											<th></th>
										 </tr>    
										   
									   </table>	
								</div>	
								
								
								
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<input class="mb-xs mt-xs mr-xs btn btn-success" id="saveBtn" type="submit" value="SAVE" disabled="true"/>
										<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
										<!--input type="submit" value="Save" name="save" class="login_button"-->									
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>								
									</div>													
								</div>
									
									

							</div> <hr><!-- .widget-content -->
						</div> <!-- /widget -->	
									</div>
								</section>
						
							</div>
						</div>	
					<!-- end: page -->
				</section>
	<?php include('jsAssets.php') ?>
