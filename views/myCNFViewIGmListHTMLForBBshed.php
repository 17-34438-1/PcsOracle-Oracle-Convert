<script language="JavaScript">

    function validate()
      {
		   if( document.myform.rotation.value == "" )
         {
            alert( "Please provide Rotation Number!" );
            document.myform.rotation.focus() ;
            return false;
         }
		  if( document.myform.cont.value == "" )
         {
            alert( "Please provide Container Number!" );
            document.myform.cont.focus() ;
            return false;
         }
		 return true;
	  }

// function getMlo(val) 
// {	
// 	//var serch_by = document.getElementById('serch_by').value;
// 	//document.getElementById('serch_value').value="";
// 	//alert(val);
// 	var strRot = val.replace("/", "_");
// 	//alert(strRot)
			
// 	if (window.XMLHttpRequest) 
// 	{
// 		// code for IE7+, Firefox, Chrome, Opera, Safari
// 		xmlhttp=new XMLHttpRequest();
// 	} 
// 	else 
// 	{  
// 		// code for IE6, IE5
// 		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
// 	}
// 	xmlhttp.onreadystatechange=stateChangeValue;
// 	xmlhttp.open("GET","<?php echo site_url('AjaxController/getMlo')?>?rot="+strRot,false);
// 	xmlhttp.send();
		  
// }

// function stateChangeValue()
// {
// 	//alert("ddfd");
//     if (xmlhttp.readyState==4 && xmlhttp.status==200) 
// 	{
//       var selectList=document.getElementById("serch_by");
// 	  removeOptions(selectList);
// 	  //alert(xmlhttp.responseText);
// 	  var val = xmlhttp.responseText;
// 	  var jsonData = JSON.parse(val);
// 	  //alert(xmlhttp.responseText);
// 		for (var i = 0; i < jsonData.length; i++) 
// 		{
// 			var option = document.createElement('option');
// 			option.value = jsonData[i].cont_mlo;
// 			option.text = jsonData[i].cont_mlo;
// 			selectList.appendChild(option);
// 		}
//     }
// }
  
// function removeOptions(selectbox)
// {
//     var i;
//     for(i=selectbox.options.length-1;i>=1;i--)
//     {
//         selectbox.remove(i);
//     }
// }


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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("IgmViewController/myListSearchBB") ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search <span class="required">*</span></span>
										<select class="form-control" name="SearchCriteria" id="SearchCriteria">
											<option value="VName">Vessel Name</option>
											<option value="Voy">Voyage No</option>
											<option value="Import">Import Rot</option>
											<option value="port">Port of Shipment</option>
											<option value="All">All</option> 
										</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Value <span class="required">*</span></span>
									<input type="text" name="Searchdata" id="SearchID" class="form-control" placeholder="Value">
									<input type="hidden" name="type" value="<?php echo $type; ?>">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>	
					</form>
					
					<hr/>
					
					<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<tr class="gridDark">
							<th align="center">Shipping Agent</th>
							<th align="center"> Rotation No</th>
							<th align="center">Expected Date Of Arrival</th>
							<th align="center">Actual Berth Date</th>
							<th align="center">Vessel Name</th>
							<th align="center">Voy No</th>
							<th align="center">Net Tonnage</th>
							<th align="center">Name of Master</th>
							<th align="center">Port of Depart</th>
							<th align="center">Port of Destination</th>
							<th align="center">Submission Date</th>
							<th align="center">View IGM</th>
	
							<?php
							//
							if($_SESSION['Control_Panel']==7 or $_SESSION['Control_Panel']==6 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==13 or $_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==44 or $_SESSION['Control_Panel']==57 or $_SESSION['Control_Panel']==58 or $_SESSION['Control_Panel']==64)
							{
							?>
							<!--td>Action (Export)</td-->
							<?php
							}
							?>

							<?php

							if($_SESSION['Control_Panel']==10)
							{
							// Custom
							print("<td >Accessibility</td><td >Accessibility</td><td >Update Vessel Information</td>");
							}
							?>

						</tr>

						
						<?php
						if($igmMasterList) {
						$len=count($igmMasterList);
						
						include("mydbPConnection.php");
						include("mydbPConnectionn4.php");
						include("dbOracleConnection.php");
						//$path= "http://".$_SERVER['SERVER_ADDR']."/myportpanel/resources/edi/";
						//$path= BASE_PATH."assets/edi/";
										//$_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/edi/
						for($i=0;$i<$len;$i++)
						{
							$id=$igmMasterList[$i]['id'];
							$myrot=$igmMasterList[$i]['Import_Rotation_No'];
							
							$str_edi_file_name="SELECT file_name_edi,file_name_stow FROM edi_stow_info WHERE igm_masters_id='$id'";
							$rslt_edi_file_name=mysqli_query($con_cchaportdb,$str_edi_file_name);
							$row_edi_file_name=mysqli_fetch_object($rslt_edi_file_name);
							
							if($row_edi_file_name!=null){
								$edi_file_name=$row_edi_file_name->file_name_edi;
								$stow_file_name=$row_edi_file_name->file_name_stow;
							}
							
							$nmrwFile = mysqli_num_rows($rslt_edi_file_name);
							
							//---
							$strChkEdi = "select count(*) as rtnValue from edi_stow_info where ucase(file_name_edi)=ucase(concat(replace('$myrot','/','_'),'.edi'))";
							$ediSt = $this->bm->dataReturnDb1($strChkEdi);
							
							$strCntryCode = "select vsl_vessels.country_code as rtnValue
							from vsl_vessel_visit_details
							inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
							where ib_vyg='$myrot'";
							$CntryCode = $this->bm->dataReturn($strCntryCode);
							//---
							
							$strVvd = "SELECT vvd_gkey FROM vsl_vessel_visit_details WHERE ib_vyg='$myrot'";
							
							$resVvd = oci_parse($con_sparcsn4_oracle, $strVvd);
							oci_execute($resVvd);
						   
							$vvdGkey="";
							while (($row = oci_fetch_object($resVvd)) != false)
						
							{
							   $vvdGkey=$row->VVD_GKEY;
							
							}
							
							
							
							// $resVvd = mysqli_query($con_sparcsn4,$strVvd);
							// $rowVvd = mysqli_fetch_object($resVvd);
							
							// if($rowVvd!=null){
							// 	$vvdGkey = $rowVvd->vvd_gkey;
							// }

							// $nmrwVvd = mysqli_num_rows($resVvd);
							
							
							$myrot=$igmMasterList[$i]['Import_Rotation_No'];
							
							include("dbConection.php");
							// $strActBrtDt = "SELECT sparcsn4.argo_carrier_visit.ata as rtnValue  FROM sparcsn4.argo_carrier_visit
							// 			INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							// 			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
							// 			WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$myrot'";
							$strActBrtDt = "SELECT argo_carrier_visit.ata as rtnValue  FROM argo_carrier_visit
							INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
							WHERE vsl_vessel_visit_details.ib_vyg='$myrot'";
										
									//echo $strActBrtDt;	
							$rotNo = $this->bm->dataReturn($strActBrtDt);
							
					?>
							<tr class="gridLight">
								<!--td><?php echo $igmMasterList[$i]['Submitee_Org_Type']; ?></td-->
								<td align="center"><?php print($igmMasterList[$i]['org_name']); ?></td>
								<!--td><?php print($igmMasterList[$i]['S_Org_License_Number']); ?></td-->
								<td align="center"><?php print($igmMasterList[$i]['Import_Rotation_No']); ?></td>
								<!--td><?php print($igmMasterList[$i]['Export_Rotation_No']); ?></td-->
								<!--td><?php print($igmMasterList[$i]['Sailed_Year']); ?></td-->
								<!--td><?php print($igmMasterList[$i]['Sailed_Date']); ?></td-->
								<td align="center"><?php print($igmMasterList[$i]['ETA_Date']); ?></td>
								<td align="center"><?php print($rotNo); ?></td>
								<!--td><?php print($igmMasterList[$i]['file_clearence_date']); ?></td-->
								<td align="center"><?php print($igmMasterList[$i]['Vessel_Name']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Voy_No']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Net_Tonnage']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Name_of_Master']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Port_of_Shipment']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Port_of_Destination']); ?></td>
								<td align="center"><?php print($igmMasterList[$i]['Submission_Date']); ?></td>
								
				
								<td align="center">
								
								<?php
								/* if($nmrwVvd>0){
								?>
								<a href="<?php echo site_url('UploadExcel/impBayViewPerformed') ?>?vvdGkey=<?php echo $vvdGkey;?>" target="_BLANK" class="blink_me">Import Vessel Layout</a><br><hr>
								<?php
								}else{
								?>
								<span class="blink_me">Vessel Visit Not Created</span><br><hr>
								<?php
								} */
								
								/* if($_SESSION['Control_Panel']==11)
								{
									if($row->file_clearence_date<"2013-09-03" or $row->file_clearence_date=="" )
									{
								?>
								<a href="home.php?myflag=106&CODE=<?php print($row->id); ?>&TM=<?php print($this->VD); ?>" >View IGM Sub Detail<a/><br><hr>
								<!--<a href='home.php?myflag=6194&CODE=<?php print($row->id);?>&SUBCODE=<?php print($row->Import_Rotation_No);?>&TM=<?php print($this->VD); ?>' style="color:blue">Upload IGM Sub(GM) from Excel File</a><br><hr>-->
								<?php }} else { */ ?>
								<!--a href="<?php echo site_url("IgmViewController/myListForm/$id/$type") ?>" target="_BLANK">View IGM Sub Detail<a/-->
								<a href="<?php echo site_url("IgmViewController/myListFormBB/$id/$type") ?>"  class="btn btn-primary" name="View" style="padding:4px;"
								target="_blank"><font size="2" color="#fff">View</font></a>
								<?php // } ?>
								<?php 
								/* if($_SESSION['Control_Panel']==7 or $_SESSION['Control_Panel']==6 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==13 or $_SESSION['Control_Panel']==15 or $_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==44 or $_SESSION['Control_Panel']==58)
								{
								//for egm general manifest
							
								if($type=='BB'){?>
								<!--for BB-->					
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BTS") ?>" target="upper_top">View Break Bulk TS</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BMT") ?>" target="upper_top">View Break Bulk EMPTY</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BNIL") ?>" target="upper_top">View Break Bulk NIL</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BAMS") ?>" target="upper_top">Break Bulk Arms,Ammunition and Explosiv(Container)</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BPS") ?>" target="upper_top">View Break Bulk Provision And Store Supply(Container)</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BROB") ?>" target="upper_top">View Break Bulk ROB IGM Detail</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/BRC") ?>" target="upper_top">View Break Bulk IGM Retention Cargo(Container)</a><br><hr>					
								<?php }} else print("&nbsp;");
								//FF
								if($_SESSION['Control_Panel']==11 or $_SESSION['Control_Panel']==57)
								{
									if($row->file_clearence_date<"2013-09-03")
									{
								?>
								<a href="home.php?myflag=46&MCODE=<?php print($row->id); ?>&TM=<?php print($this->VD); ?>&CType=C" target="upper_top">View IGM Supplementary Detail<a/><br><hr>
								<?php
									}
								}
								if($_SESSION['Control_Panel']==57 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==28)
								{
									if($_SESSION['Control_Panel']==57 or $_SESSION['Control_Panel']==28)
									{
								?>
									<a href="<?php echo site_url('uploadExcel/ediUpload/'.$id) ?>">EDI Upload</a><br><hr>
									<?php		
									}
									if($nmrwFile>0)
									{
										if($ediSt==0 and $CntryCode!="BD")
										{
											echo "<font color='red'>EDI not uploaded through myportpanel</font><br>";
										}
										else
										{
									?>
										<a href="<?php echo $path.$edi_file_name;?>" download="<?php echo $edi_file_name?>">EDI Download</a><br><hr>
										<a href="<?php echo $path.$stow_file_name;?>" download="<?php echo $stow_file_name?>">Stow Plan Download</a><br><hr>
								<?php
										}	
									}	
								}
								
								if($_SESSION['Control_Panel']==11)
								{	
									if($row->file_clearence_date<"2013-09-03"){
								?>
								<a href="home.php?myflag=137&MCODE=<?php print($row->id); ?>&TM=<?php print($this->VD); ?>&CType=C">View & Delete IGM Supplementary Detail</a><br><hr>
									<?php
						
									}
								} */
								?>
								
								
								
								<?php
								/* if($_SESSION['Control_Panel']==7 or $_SESSION['Control_Panel']==6 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==13 or $_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==44 or $_SESSION['Control_Panel']==58)
								{
								//for egm general manifest
								if($type=='GM'){
								?>
								<!--<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=ROB" target="upper_top">View IGM ROB Detail<a/><br><hr>
								<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=TS" target="upper_top">View IGM TS Detail<a/><br><hr>
								<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=MT" target="upper_top">View IGM EMPTY Detail<a/><br><hr>
								<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=ROB" target="upper_top">View IGM ROB Detail<a/><br><hr>
								<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=TS" target="upper_top">View IGM TS Detail<a/><br><hr>
								<a href="home.php?myflag=106&CODE=<?php //print($row->id); ?>&TM=MT" target="upper_top">View IGM EMPTY Detail<a/><br><hr>-->
									
									<a href="<?php echo site_url("igmViewController/myListForm/$id/TS") ?>" target="upper_top">View TS IGM Detail</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/MT") ?>" target="upper_top">View EMPTY IGM Detail</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/NIL") ?>" target="upper_top">View NIL IGM Detail</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/AMS") ?>" target="upper_top">Arms,Ammunition and Explosiv(Container)</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/PS") ?>" target="upper_top">Provision And Store Supply(Container)</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/ROB") ?>" target="upper_top">View ROB IGM Detail</a><br><hr>
									<a href="<?php echo site_url("igmViewController/myListForm/$id/RC") ?>" target="upper_top">View IGM Retention Cargo(Container)</a><br><hr>
									
								<?php
								}
								} */
								?>
								

								<?php
								//Offdock
								if($_SESSION['Control_Panel']==13)
								{
								?>
									<a href="home.php?myflag=8115&key=rcv&CODE=<?php print($row->id);?>&TM=<?php print($this->VD);?>"><font color="blue">Upload Container (Recieved)</font></a><hr>
									<a href="home.php?myflag=8116&key=del&CODE=<?php print($row->id);?>&TM=<?php print($this->VD);?>"><font color="blue">Upload Container (Delivery)</font></a><hr>
								<?php
								}
								?>
								<?php
								//Port
								if($_SESSION['Control_Panel']==12 and $type=="GM")
								{
								?>
									<!--<a href="home.php?myflag=35&CODE=<?php print($row->id); ?>&TM=GM" target="upper_top">View IGM Classified<a/><br><hr>-->
								<?php
								}
								?>

								<?php
								if($_SESSION['Control_Panel']==12 and $type=="BB")
								{
								?>
									<!--<a href="home.php?myflag=35&CODE=<?php print($row->id); ?>&TM=BB" target="upper_top">View IGM Classified<a/><br><hr>-->
								<?php
								}
								?>
								
								</td>
								
								
								<?php
								if($_SESSION['Control_Panel']==7 or $_SESSION['Control_Panel']==6 or $_SESSION['Control_Panel']==12 or $_SESSION['Control_Panel']==13 or $_SESSION['Control_Panel']==10 or $_SESSION['Control_Panel']==44 or $_SESSION['Control_Panel']==58 or $_SESSION['Control_Panel']==64)
								{
								//for egm general manifest
								if($type=='GM'){
								
								?>
								<!--td>
									<a href="home.php?myflag=247&CODE=<?php print($row->id); ?>&TM=GM" target="upper_top">View EGM Sub Detail<a/><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=TS' target="upper_top">View TS EGM Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=MT' target="upper_top">View EMPTY EGM Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=NIL' target="upper_top">View NIL EGM Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=AMS' target="upper_top">Arms,Ammunition and Explosiv(Container)</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=PS' target="upper_top">Provision And Store Supply(Container)</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=ROB' target="upper_top">View ROB EGM Detail</a><br><hr>
									<a href='home.php?myflag=295&CODE=<?php print($row->id);?>&TM=RC' target="upper_top">View EGM Retention Cargo(Container)</a><br><hr>
								</td-->
								<?php
								//for egm break bulk
								}	else if($type=='BB'){
								
								?>
								<!--td>
									<a href="home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BB" target="upper_top">View EGM Break Bulk Sub Detail<a/><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BTS' target="upper_top">View TS EGM Break Bulk Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BMT' target="upper_top">View EMPTY EGM Break Bulk Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BNIL' target="upper_top">View NIL EGM Break Bulk Detail</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BAMS' target="upper_top">Arms,Ammunition and Explosiv(Container)</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BPS' target="upper_top">Provision And Store Supply(Container)</a><br><hr>
									<a href='home.php?myflag=247&CODE=<?php print($row->id);?>&TM=BROB' target="upper_top">View ROB EGM Break Bulk Detail</a><br><hr>
									<a href='home.php?myflag=295&CODE=<?php print($row->id);?>&TM=BRC' target="upper_top">View EGM Break Bulk Retention Cargo(Container)</a><br><hr>
								</td-->
								<?php
								}
								}
								?>
								
								
						<?php
					
							if($_SESSION['Control_Panel']==10)
							{
							// Custom
								if(($row->Import_Rotation_No=='')&&($row->custom_approved==0))
								{
									print("<td><a href='home.php?myflag=413&master_id=$row->id&TM=$this->VD'>Assign Import Rotation Number</a><hr><a href='home.php?myflag=2008&CODE=$row->id&VD=$this->VD'>Port Clearance</a><hr><a href='home.php?myflag=447&master_id=$row->id&TM=$this->VD'>Approve The Given Information</a>");	
								if(($_SESSION['user_role_id']==3) && ($row->file_clearence_date==''))
									{	
										print("<hr><a href='home.php?myflag=2007&CODE=$row->id&VD=$this->VD'>Final Entry</a>");
									}
								print("</td>");
								}
								else if(($row->Import_Rotation_No=='')&&($row->custom_approved==1))
									{
									print("<td><a href='home.php?myflag=413&master_id=$row->id&TM=$this->VD'>Assign Import Rotation Number</a><hr><a href='home.php?myflag=2008&CODE=$row->id&VD=$this->VD'>Port Clearance</a><hr><a href='home.php?myflag=448&master_id=$row->id&TM=$this->VD'>Reject The Given Information</a>");
									if(($_SESSION['user_role_id']==3) && ($row->file_clearence_date==''))
									{	
										print("<hr><a href='home.php?myflag=2007&CODE=$row->id&VD=$this->VD'>Final Entry</a>");
									}
								print("</td>");
									
									
									
									}
								else if(($row->Import_Rotation_No)&&($row->custom_approved==0))
									{
									print("<td><a href='home.php?myflag=414&master_id=$row->id&TM=$this->VD'>Update Import Rotation Number</a><hr><a href='home.php?myflag=2008&CODE=$row->id&VD=$this->VD'>Port Clearance</a><hr><a href='home.php?myflag=447&master_id=$row->id&TM=$this->VD'>Approve The Given Information</a>");
									if(($_SESSION['user_role_id']==3) && ($row->file_clearence_date==''))
									{	
										print("<hr><a href='home.php?myflag=2007&CODE=$row->id&VD=$this->VD'>Final Entry</a>");
									}
								print("</td>");
									
									}
								else if(($row->Import_Rotation_No)&&($row->custom_approved==1))
									{
									print("<td><a href='home.php?myflag=414&master_id=$row->id&TM=$this->VD'>Update Import Rotation Number</a><hr><a href='home.php?myflag=2008&CODE=$row->id&VD=$this->VD'>Port Clearance</a><hr><a href='home.php?myflag=448&master_id=$row->id&TM=$this->VD'>Reject The Given Information</a>");
									if(($_SESSION['user_role_id']==3) && ($row->file_clearence_date==''))
									{	
										print("<hr><a href='home.php?myflag=2007&CODE=$row->id&VD=$this->VD'>Final Entry</a>");
									}
								print("</td>");
									}
								
								if($row->Export_Rotation_No=='')
									print("<td><a href='home.php?myflag=436&master_id=$row->id&TM=$this->VD'>Assign Export Rotation Number</a></td>");	
								else
									print("<td><a href='home.php?myflag=437&master_id=$row->id&TM=$this->VD'>Update Export Rotation Number</a></td>");			
								
						?>		
						<td align="center"><a href='home.php?myflag=202&Edit=yes&CODE=<?php print($row->id);?>&VD=<?php print($this->VD); ?>' style='color:blue'>Update</a></td>
						<?php }?>
					
				
						
					
							</tr>
					<?php	
						
						}	}
			//print($_SESSION['Control_Panel']."ghghjghgjhhljhjlhj<hr>"); return;
					if($flag == "list"){
					?>

					<tr><td colspan="12" align="center"><p><?php echo $links; ?></p></td></tr>
					
					<?php } ?>

					</table>
					</div>
				</div>
			</section>
		</div>
	</div>	
	<!-- end: page -->
</section>