		<?php if($_POST['options']=='html'){?>
<HTML>

<BODY>

<?php }
else if($_POST['options']=='xl'){
   // $rota=str_replace('/', '-', $rot);

    header("Content-type: application/octet-stream");
    //header("Content-Disposition: attachment; filename=Container-List-$rota-Stripping.xls;");
    header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
    header("Content-Type: application/ms-excel");
    header("Pragma: no-cache");
    header("Expires: 0");
}
//$rot=$_REQUEST['rot'];
?>
			<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										 <?php if($_POST['options']=='html'){ ?>
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<?php } ?>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											Vessel Wise Transit Ways
										</h5>
									</div>
								</div>
							</div>
							<?php 
								include('dbOracleConnection.php');
								$i=0;
								$mlo="";
								$querystr="select vvd_gkey,vsl,ib_vyg,ata,etd,berth,ship_agent,berth_op,phase,
									(SELECT LISTAGG(id, '->') AS transit_way FROM ref_point_calls 
									INNER JOIN ref_routing_point ON ref_point_calls.point_gkey=ref_routing_point.gkey
									WHERE itin_gkey=(SELECT itinereray FROM argo_visit_details WHERE gkey=vvd_gkey)) AS transit_way
									from (
									SELECT NVL(vsl_vessel_visit_details.vvd_gkey,'') AS vvd_gkey,
									vsl_vessels.name AS vsl,
									NVL(vsl_vessel_visit_details.ib_vyg,'') AS ib_vyg,
									NVL(argo_carrier_visit.ata,'') AS ata, 
									NVL(argo_visit_details.etd,'') AS etd,
									NVL((SELECT argo_quay.id FROM argo_quay 
									INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey 
									WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.gkey FETCH FIRST 1 ROW ONLY),'') AS berth,
									NVL(Y.name,'') AS ship_agent,
									NVL(vsl_vessel_visit_details.flex_string02,
									NVL(vsl_vessel_visit_details.flex_string03,'')) AS berth_op,
									NVL(argo_carrier_visit.phase,'') AS PHASE FROM vsl_vessel_visit_details 
									INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey 
									INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
									INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey 
									INNER JOIN ref_carrier_service ON ref_carrier_service.gkey=argo_visit_details.service 
									INNER JOIN ( ref_bizunit_scoped r LEFT JOIN ( ref_agent_representation X 
									LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey ) ON r.gkey=X.bzu_gkey) ON r.gkey = vsl_vessel_visit_details.bizu_gkey
									WHERE vsl_vessel_visit_details.ib_vyg='$rot'
									) a";
									
								$query=oci_parse($con_sparcsn4_oracle,$querystr);
								oci_execute($query,OCI_DEFAULT);
								
								while (($row = oci_fetch_object($query)) != false){
									$i++;
							?>
							<div class="row">
								<div class="col-md-offset-5 col-sm-6 mt-md">
									<h5 class="h5 mt-none mb-sm text-dark text-bold">
										Vessel Name: <span style="padding-right:45px;"></span>
										<?php if($row->VSL) echo $row->VSL; else echo "&nbsp;";?>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>Phase: <span style="padding-right:94px;"></span>
										<?php if($row->PHASE) echo substr($row->PHASE,2); else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>ATA: <span style="padding-right:107px;"></span>
										<?php if($row->ATA) echo $row->ATA; else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>ETD: <span style="padding-right:107px;"></span>
										<?php if($row->ETD) echo $row->ETD; else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>Berth: <span style="padding-right:95px;"></span>
										<?php if($row->BERTH) echo $row->BERTH; else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>Agent: <span style="padding-right:94px;"></span>
										<?php if($row->SHIP_AGENT) echo $row->SHIP_AGENT; else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>Berth Operator: <span style="padding-right:29px;"></span>
										<?php if($row->BERTH_OP) echo $row->BERTH_OP; else echo "&nbsp;";?> </b>
									</h5>
									<h5 class="h5 mt-none mb-sm text-dark">
										<b>Transit Ways: <span style="padding-right:45px;"></span>
										<font color="blue"><b><?php if($row->TRANSIT_WAY) echo $row->TRANSIT_WAY; else echo "&nbsp;";?> </b>
									</h5>
								</div>
							</div>
							<?php 
								} 
								oci_close($con_sparcsn4_oracle);
							?>
						</header>
						<div class="panel-body">
						
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>