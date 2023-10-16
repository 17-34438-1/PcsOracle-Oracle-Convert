<style>
 #table-scroll {
  height:600px;
  width:850px;
  overflow:auto;  
  margin-top:20px;
}
 </style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize_1">
      <div class="mainbar_1">
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/viewVesselListImportSearchList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation No: <span class="required">*</span></span>
										<input type="text" name="rot_num" id="rot_num" class="form-control" placeholder="Rotation No">
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
		</div>

       <div id="table-scroll" class="table-responsive">
			<table  class="table table-bordered table-responsive table-hover table-striped mb-none">
				<thead>
					<tr class="gridDark">
						<!--td><b>View Appraisement</b></td-->
						<td ><b>SL.</b></td>
						<td ><b>Vessel Name</b></td>							
						<td ><b>Imp Rot</b></td>
						<!--td ><b>Exp Rot</b></td-->
						<td ><b>Agent</b></td>
						<td ><b>Berth Operator</b></td>
						<td ><b>Status</b></td>
						<td ><b>ATA</b></td>
						<td ><b>ATD</b></td>
						<td ><b>Action</b></td>

					</tr>
				</thead>
				<tbody>
					<?php 
					include("dbOracleConnection.php");
					for($i=0;$i<count(@$rtnVesselList);$i++){
						$vvd_gkey="";
						$PHASE="";
						$phase_num="";
						$ata="";
						$atd="";
						$bop="";
						$mlo="";
						$vvd_gkey=$rtnVesselList[$i]['vvd_gkey'];

						$str1="SELECT SUBSTR(argo_carrier_visit.phase,3) AS PHASE,SUBSTR(argo_carrier_visit.phase,2) as phase_num,
						argo_carrier_visit.ata as ata, argo_carrier_visit.atd as atd
						FROM argo_carrier_visit WHERE argo_carrier_visit.cvcvd_gkey='$vvd_gkey'";
						$query1=oci_parse($con_sparcsn4_oracle,$str1);
						oci_execute($query1);
					    while(($row1=oci_fetch_object($query1))!=false){
							$PHASE=$row1->PHASE;
							$phase_num=$row1->PHASE_NUM;
							$ata=$row1->ATA;
							$atd=$row1->ATD;

						}
						$str2="SELECT vsl_vessel_visit_details.flex_string02 AS bop
						FROM vsl_vessel_visit_details WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_gkey'";
						$query2=oci_parse($con_sparcsn4_oracle,$str2);
						oci_execute($query2);
					    while(($row2=oci_fetch_object($query2))!=false){
							$bop=$row2->BOP;
						}
						$str3="SELECT ref_bizunit_scoped.id  AS mlo FROM vsl_vessel_visit_details
						INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey 
						WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_gkey'";

						$query3=oci_parse($con_sparcsn4_oracle,$str3);
						oci_execute($query3);
						while(($row3=oci_fetch_object($query3))!=false){
							$mlo=$row3->MLO;
                        }
						if($PHASE!='CANCELED' || $PHASE!='CLOSED' ){

						
						?>
					<tr class="gradex">
						<td style="color:red"><?php echo $i+1;?></td>
						<td style="display:none"><?php echo $rtnVesselList[$i]['vvd_gkey'];?></td>
						<td><?php echo $rtnVesselList[$i]['vsl_name'];?></td>
						<td><?php echo $rtnVesselList[$i]['vsl_visit_dtls_ib_vyg'];?></td>
						<!--td><?php echo $rtnVesselList[$i]['ob_vyg'];?></td-->						
						<td><?php echo $mlo;?></td>						
						<td><?php echo $bop;?></td>						
						<td 
							<?php if ($phase_num=='20')
									{?>style="background-color:#F6D8CE"<?php } else if($phase_num=='30'){?>style="background-color:#F78181" <?php } else if($phase_num=='40'){?>style="background-color:#FACC2E"<?php } else if($phase_num=='50'){?>style="background-color:#F5A9A9"<?php } else if($phase_num=='60'){?>style="background-color:#610B0B"<?php }?>>
							
						
						<?php echo $PHASE;?></td>
						
						<td><?php echo $ata;?></td>
						<td><?php echo $atd;?></td>
						<td>
							<form style="display:inline" action="<?php echo site_url('report/ImportContainerSummeryView/'.str_replace("/","_",$rtnVesselList[$i]['vsl_visit_dtls_ib_vyg']))?>" target="_blank" method="POST">
								<button class="btn btn-primary" id="VwBtn" type="submit">Summary</button>
							</form>
							<!--form style="display:inline margin-top:2%" action="<?php echo site_url('report/containerDischargeAppsList/'.str_replace("/","_",$rtnVesselList[$i]['vsl_visit_dtls_ib_vyg']))?>" target="_blank" method="POST">
								<input class="login_button" style="background-color:green" id="VwBtn" type="submit" value="Details"/>
							</form-->
						</td>
					</tr>
				</tbody>
					<?php } }?>
			</table>
		 </div>
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <!-- <div class="sidebar">
	   <?php include_once("mySideBar.php"); ?>
	  </div> -->
      <div class="clr"></div>
    </div>
	
  </div>
</section>