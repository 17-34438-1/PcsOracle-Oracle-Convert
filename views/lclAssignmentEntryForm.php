<script type="text/javascript">
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
			
		var tbl = document.getElementById("mytbl");
		var rowslenth = tbl.getElementsByTagName("tr").length;
		var rmvroLn = rowslenth-1;

		for(var i=rmvroLn;i>=1;i--)
		{
			tbl.deleteRow(i);
		}
		
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
   
   
   


   function getContInfo(cont)
	{
			//setTimeout(document.getElementById("contNo").focus(),0);	
	      //alert("Find : "+document.getElementById("contNo").value);
		 if(cont=="" )
		 {
			alert("Please provide Container No!");
			document.getElementById("contSize").value="";
			document.getElementById("contHeight").value="";
			document.getElementById("vesselName").value="";
			document.getElementById("rotNo").value="";
			document.getElementById("mlo").value="";
			document.getElementById("igmDetailContId").value="";
			document.getElementById("igmDetailId").value="";
			document.getElementById("stv").value="";
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
			xmlhttp.onreadystatechange=stateChangeContInfo;
			xmlhttp.open("GET","<?php echo site_url('AjaxController/getLCLContInfo')?>?cont="+cont,false);
					
			xmlhttp.send();
		 }
		
	}
			
			
	function stateChangeContInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			 // var myVslInfo=document.getElementById("myVslInfo");
			 //removeOptions(selectList);
			var val = xmlhttp.responseText;
			var strArr = val.split("|");
		    //alert(val);
			
			document.getElementById("contSize").value=strArr[1];
			document.getElementById("contHeight").value=strArr[2];
			document.getElementById("vesselName").value=strArr[3];
			document.getElementById("rotNo").value=strArr[4];
			document.getElementById("mlo").value=strArr[5];
			document.getElementById("igmDetailContId").value=strArr[6];
			document.getElementById("igmDetailId").value=strArr[7];
			document.getElementById("stv").value=strArr[8];
			document.getElementById("landingTime").value=strArr[9];
			
			//document.getElementById("contAtShed").focus();
			
		}
	}

</script> 
<style>
	input:focus
	{
		background-color:#A9E2F3;
	}

	select:focus
	{
		background-color:#A9E2F3;
	}
	
	 #table-scroll {
	  height:500px;
	  overflow:auto;  
	  margin-top:0px;
      }

</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				
					<div class="row">
						<div class="col-lg-12">						
							<section class="panel">
								<div class="panel-body">
									<?php
										if(@$editFlag==1)
										{   
											$id=$lclAssignmentEditList[0]['id'];
											$assignment_date=$lclAssignmentEditList[0]['assignment_date']; 
											$cont_number=$lclAssignmentEditList[0]['cont_number'];
											$cont_size=$lclAssignmentEditList[0]['cont_size'];
											$cont_height=$lclAssignmentEditList[0]['cont_height'];
											$Vessel_Name=$lclAssignmentEditList[0]['Vessel_Name'];
											$Import_Rotation_No=$lclAssignmentEditList[0]['Import_Rotation_No'];
											$mlocode=$lclAssignmentEditList[0]['mlocode'];
											$stv=$lclAssignmentEditList[0]['stv'];
											$cont_loc_shed=$lclAssignmentEditList[0]['cont_loc_shed'];
											$cargo_loc_shed=$lclAssignmentEditList[0]['cargo_loc_shed'];
											$description_cargo=$lclAssignmentEditList[0]['description_cargo'];
											$remarks=$lclAssignmentEditList[0]['remarks'];
											
										}		
									?>
									<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/cfsModule/lclAssignmentPerform'; ?>" target="_blank" id="myform" name="myform" onsubmit="return(validate());">
										<div class="form-group">
											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Expt. Date of Unstuffing <span class="required">*</span></span>
													<input type="text" name="expectDate" id="expectDate" class="form-control"  tabindex="1" value="<?php date("Y-m-d"); if(@$dateFlag==1)echo $exptDate; if(@$editFlag==1) echo $assignment_date; else ""; ?>" placeholder="Expt. Date of Unstuffing" readonly>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group text-center mb-md">
												<a class="mb-xs mt-xs mr-xs btn btn-info" href="<?php echo base_url().'index.php/cfsModule/syncLclAssignment'; ?>"  class="login_button" name="sync" style="padding:6px 10px;" target="_blank">
														Sync
													</a>
												</div>
											</div>

											<div class="col-md-12">
												&nbsp;
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
													<input type="text" name="contNo" id="contNo" class="form-control" autofocus onblur="getContInfo(this.value);" tabindex="2" value="<?php if(@$editFlag==1) echo $cont_number; else "";?>" placeholder="Container No">
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Size <span class="required">*</span></span>
													<input type="text" name="contSize" id="contSize" class="form-control" value="<?php if(@$editFlag==1) echo $cont_size; else "";?>" placeholder="Container size" readonly>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Height <span class="required">*</span></span>
													<input type="text" name="contHeight" id="contHeight" class="form-control" value="<?php if(@$editFlag==1) echo $cont_height; else "";?>" placeholder="Container height" readonly>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
													<input type="text" name="vesselName" id="vesselName" class="form-control" value="<?php if(@$editFlag==1) echo $Vessel_Name; else "";?>" placeholder="Vessel Name" readonly>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
													<input type="text" name="rotNo" id="rotNo" class="form-control" value="<?php if(@$editFlag==1) echo $Import_Rotation_No; else "";?>" placeholder="Rotation" readonly>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">MLO <span class="required">*</span></span>
													<input type="text" name="mlo" id="mlo" class="form-control" value="<?php if(@$editFlag==1) echo $mlocode; else "";?>" placeholder="MLO" readonly>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="input-group mb-md" style="padding:0px 0px 0px 15px;">
														<span class="input-group-addon span_width">STV <span class="required">*</span></span>
														<input type="text" name="stv" id="stv" class="form-control" value="<?php if(@$editFlag==1) echo $stv; else "";?>" placeholder="STV" readonly>
													</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Cont to be palce at Shed <span class="required">*</span></span>
														<select class="form-control" name="contAtShed" id="contAtShed" tabindex="3"  value="" onchange="getShed(this.value)";>  
															<option value="">----Select-------------</option>
															<option value="CFS/NCT" <?php if(($editFlag==1 and $cont_loc_shed=="CFS/NCT")or(@$shedFlag==1 and $contShed=="CFS/NCT")){?> selected <?php }?> >CFS/NCT</option> 
															<option value="CFS/CCT" <?php if(($editFlag==1 and $cont_loc_shed=="CFS/CCT")or(@$shedFlag==1 and $contShed=="CFS/CCT")){?> selected <?php } ?> >CFS/CCT</option> 
															<option value="13 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="13 Shed")or(@$shedFlag==1 and $contShed=="13 Shed")){?> selected <?php } ?> >13 Shed</option> 
															<option value="12 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="12 Shed")or(@$shedFlag==1 and $contShed=="12 Shed")){?> selected <?php } ?> >12 Shed</option> 
															<option value="9 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="9 Shed")or(@$shedFlag==1 and $contShed=="9 Shed")){?> selected <?php } ?> >9 Shed</option> 
															<option value="8 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="8 Shed")or(@$shedFlag==1 and $contShed=="8 Shed")){?> selected <?php } ?> >8 Shed</option> 
															<option value="7 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="7 Shed")or(@$shedFlag==1 and $contShed=="7 Shed")){?> selected <?php } ?> >7 Shed</option> 
															<option value="6 Shed" <?php if(($editFlag==1 and $cont_loc_shed=="6 Shed")or(@$shedFlag==1 and $contShed=="6 Shed")){?> selected <?php } ?> >6 Shed</option> 
															<option value="N Shed" <?php if(($editFlag==1 and $cont_loc_shed=="N Shed")or(@$shedFlag==1 and $contShed=="N Shed")){?> selected <?php } ?> >N Shed</option> 
															<option value="D Shed" <?php if(($editFlag==1 and $cont_loc_shed=="D Shed")or(@$shedFlag==1 and $contShed=="D Shed")){?> selected <?php } ?> >D Shed</option> 
															<option value="P Shed" <?php if(($editFlag==1 and $cont_loc_shed=="P Shed")or(@$shedFlag==1 and $contShed=="P Shed")){?> selected <?php } ?> >P Shed</option> 	
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Cargo to be stored at Shed <span class="required">*</span></span>
													<select class="form-control" name="cargoAtShed" id="cargoAtShed" tabindex="4"  value="">
														<option value="">----Select-------------</option>
														<option value="CFS/NCT" <?php if(($editFlag==1 and $cargo_loc_shed=="CFS/NCT")or(@$shedFlag==1 and $cargoShed=="CFS/NCT")){?> selected <?php } ?> >CFS/NCT</option> 
														<option value="CFS/CCT" <?php if(($editFlag==1 and $cargo_loc_shed=="CFS/CCT")or(@$shedFlag==1 and $cargoShed=="CFS/CCT")){?> selected <?php } ?> >CFS/CCT</option> 
														<option value="13 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="13 Shed")or(@$shedFlag==1 and $cargoShed=="13 Shed")){?> selected <?php } ?> >13 Shed</option> 
														<option value="12 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="12 Shed")or(@$shedFlag==1 and $cargoShed=="12 Shed")){?> selected <?php } ?> >12 Shed</option> 
														<option value="9 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="9 Shed")or(@$shedFlag==1 and $cargoShed=="9 Shed")){?> selected <?php } ?> >9 Shed</option> 
														<option value="8 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="8 Shed")or(@$shedFlag==1 and $cargoShed=="8 Shed")){?> selected <?php } ?> >8 Shed</option> 
														<option value="7 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="7 Shed")or(@$shedFlag==1 and $cargoShed=="7 Shed")){?> selected <?php } ?> >7 Shed</option> 
														<option value="6 Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="6 Shed")or(@$shedFlag==1 and $cargoShed=="6 Shed")){?> selected <?php } ?> >6 Shed</option> 
														<option value="N Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="N Shed")or(@$shedFlag==1 and $cargoShed=="N Shed")){?> selected <?php } ?> >N Shed</option> 
														<option value="D Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="D Shed")or(@$shedFlag==1 and $cargoShed=="D Shed")){?> selected <?php } ?> >D Shed</option> 
														<option value="P Shed" <?php if(($editFlag==1 and $cargo_loc_shed=="P Shed")or(@$shedFlag==1 and $cargoShed=="P Shed")){?> selected <?php } ?> >P Shed</option> 	
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Desc. of Cargo <span class="required">*</span></span>
													<input type="text" name="decOfCargo" id="decOfCargo" class="form-control" value="<?php if(@$editFlag==1) echo $description_cargo; else "";?>" tabindex="5" placeholder="Desc. of Cargo">
												</div>
											</div>

											<div class="col-md-6">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
													<input type="text" name="remarks" id="remarks" class="form-control" value="<?php if(@$editFlag==1) echo $remarks; else "";?>" tabindex="6" placeholder="Desc. of Cargo">
												</div>
											</div>

											<div class="col-md-12">
												&nbsp;
											</div>

											<div class="row">
												<div class="col-sm-12 text-center">
													<?php
														if(@$editFlag == 0){
															echo '<button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-primary" tabindex="7">Save</button>';
														}else{
															echo '<button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-primary" tabindex="7">Update</button>';
														}
													?>
													<a class="mb-xs mt-xs mr-xs btn btn-success" href="<?php echo base_url().'index.php/cfsModule/lclAssignmentReportView'; ?>"  class="login_button" name="print" style="padding:6px 10px;" target="_blank">
														Print
													</a>
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


					<div id="table-scroll" class="panel-body table-responsive">
						<table class="table table-bordered table-responsive table-hover table-striped mb-none">
							<tr class="gridDark" style="height:35px;" >
								<th>SL</th>
								<th>Cont No</th>
								<th>Size</th>
								<th>Height</th>
								<th>Rotation</th>
								<th><nobr>Vessel Name</nobr></th>
								<th><nobr>Assign Date</nobr></th>
								<th>MLO</th>
								<th>STV</th>
								<th><nobr>Cont.at Shed</nobr></th>
								<th><nobr>Cargo at Shed</nobr></th>
								<th><nobr>Desc. of Cargo</nobr></th>
								<th><nobr>Landing Date</nobr></th>
								<th>Remarks</th>
								<th>Action</th>
								<th>Action</th>
							</tr>

							<?php
							for($i=0;$i<count($lclAssignmentList);$i++) 
							{ 
							//	$st = $lclAssignmentList[$i]['st'];		// undefined index
							?>

							<!--tr <?php if($st==1){?>bgcolor="#CCAEDE"<?php }else{?>class="gridLight"<?php }?>-->
							<tr class="gridLight">
								<td>
									<?php echo $i+1;?>
								</td>							
								<td align="center">
									<?php echo $lclAssignmentList[$i]['cont_number']?>
								</td>
								<td align="center">
									<?php echo $lclAssignmentList[$i]['cont_size']?>
								</td>
								<td align="center">
									<?php echo $lclAssignmentList[$i]['cont_height']?>
								</td>
								<td align="center">
									<?php echo $lclAssignmentList[$i]['Import_Rotation_No']?>
								</td> 
								<td align="center">
									<?php echo $lclAssignmentList[$i]['Vessel_Name']?>
								</td> 
								<td align="center">
									<?php echo $lclAssignmentList[$i]['assignment_date']?>
								</td>   		  
								<td align="center">
									<?php echo $lclAssignmentList[$i]['mlocode']?>
								</td>          
								<td align="center">
									<?php if( $lclAssignmentList[$i]['stv']=="SAIF POWERTEC") echo "SPL"; else echo $lclAssignmentList[$i]['stv'];?>
								</td>
								<td align="center">
									<?php echo $lclAssignmentList[$i]['cont_loc_shed']?>
								</td> 
								<td align="center">
									<?php echo $lclAssignmentList[$i]['cargo_loc_shed']?>
								</td> 
								<td align="center">
									<?php echo $lclAssignmentList[$i]['description_cargo']?>
								</td> 

								<td align="center">
									<?php echo $lclAssignmentList[$i]['landing_time']?>
								</td> 

								<td align="center">
									<?php echo $lclAssignmentList[$i]['remarks']?>
								</td> 
								<td align="center">
									<div class="row">
										<div class="col-sm-12 text-center">
											<form action="<?php echo site_url('cfsModule/lclAssignmentEdit');?>" method="POST">
												<input type="hidden" name="lclID" value="<?php echo $lclAssignmentList[$i]['id'];?>">							
												<button type="submit" name="start" class="mb-xs mt-xs mr-xs btn btn-primary">Edit</button>
											</form> 
										</div>
									</div>
								</td> 
								<td align="center">
									<form action="<?php echo site_url('cfsModule/lclAssignmentCancel');?>" method="POST">
										<input type="hidden" name="lclID" value="<?php echo $lclAssignmentList[$i]['id'];?>">							
										<button type="submit" name="start" class="mb-xs mt-xs mr-xs btn btn-danger">Delete</button>
									</form> 
								</td> 
							</tr>
							<?php
							}
							?>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>
