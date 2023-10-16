<meta http-equiv="refresh" content="20" />
<style>
	#overflow
	{
		width: 100%;
		height: 1000px;
		overflow: scroll;
		border: 1px solid #ccc;
	}
</style>
<table width="100%">
	<tr>
		<td align="center"><img src="<?php echo IMG_PATH; ?>cpanew.jpg" height="100px" width="300px"  /></td>
	</tr>
</table>

<div class="content">
	<div class="content_resize_1">
		<div class="mainbar_1">
			<div class="article">
								
				<div class="clr"></div>
				<div class="img1">
				
			<table cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" >
				<form action="<?php echo site_url('report/exportContainerLoadingList');?>" method="POST" >
					<tr><td colspan="12" align="center">  <span><font color="green"  size="4" style="font-weight: bold"><nobr><?php print $tableTitle; ?></nobr></font></span></td></tr>
			

									 
					<tr>
						<td align="center" >
					

					<td><b><nobr>Rotation:</nobr></b></td>
					<td>
								<input type="text" style="width:170px" id="rotation" name="rotation" autofocus />
					</td>

					<td  align="center" width="70px">
								<input type="submit" value="Search" name="Search" class="login_button">
					</td>
					</tr>
				</form>
			</table>
				<!--div class="img1" style="overflow:auto"-->
				<!--div id="overflow"-->
					<table>
						<tr>
							<td>
								<table class="table table-bordered table-responsive table-hover table-striped mb-none">
									<!--tr class="gridDark"-->
									<tr bgcolor="#aea4a4">
										<th>Sl</th>
										<th>ID</th>
										<th>Rotation</th>
										<th>Vessel Name</th>
										<th>Freight Kind</th>
										<th>Category</th>
										<th>Size</th>
										<th>Height</th>
										<th>Position</th>
										<th>MLO</th>
										<th>Seal</th>
										<th>Weight</th>
										<th>Trailer No</th>
										<th>Port of Discharge</th>
										<th>Update By</th>
										<th>Update Time</th>
										<th>Remarks</th>
									</tr>
									<?php
									include("dbOracleConnection.php");		
									$container="";
									for($i=0;$i<count($rslt_export_container_loading_list);$i++)
									{
										$gkey="";
										$vvdGkey="";
										$gkey=$rslt_export_container_loading_list[$i]['gkey'];
										$vvdGkey=$rslt_export_container_loading_list[$i]['vvd_gkey'];
										$sqlQuery="SELECT inv_unit.id,ib_vyg AS vsl_visit_dtls_ib_vyg,
										vsl_vessels.name AS vsl_name,freight_kind,category,
										(SELECT substr(ref_equip_type.nominal_length,-2) FROM inv_unit 
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) AS siz,
										
										((SELECT substr(ref_equip_type.nominal_height,-2) FROM inv_unit 
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10) AS height,
										ref_bizunit_scoped.id AS mlo,ref_bizunit_scoped.name AS mlo_name
										From inv_unit
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
										INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
										LEFT JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey
										where inv_unit.gkey='$gkey' and inv_unit_fcy_visit.transit_state NOT IN('S60_LOADED','S70_DEPARTED','S99_RETIRED')";
										$sqlQueryRes = oci_parse($con_sparcsn4_oracle,$sqlQuery);
										oci_execute($sqlQueryRes);
										//echo $sqlQuery;
										//return;
										$result1=array();
										$numRow =oci_fetch_all($sqlQueryRes, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
										oci_free_statement($sqlQueryRes);
										$sqlQueryRes = oci_parse($con_sparcsn4_oracle,$sqlQuery);
										oci_execute($sqlQueryRes);
										$sqlQueryRow=oci_fetch_object($sqlQueryRes);
										if($numRow>0){
									?>
									<!--tr class="gridLight"-->
									<tr <?php if($rslt_export_container_loading_list[$i]['cont_status']!=$sqlQueryRow->FREIGHT_KIND){ ?> bgcolor="#93E0FE" <?php } else if($rslt_export_container_loading_list[$i]['re_status']=="1"){ ?> bgcolor="pink" <?php } else if($rslt_export_container_loading_list[$i]['re_status']=="2"){ ?> bgcolor="F8FAD7"<?php }else { if($color==0) { ?> bgcolor="#d9e6f0" <?php } else  {?>bgcolor="FFFFFF" <?php }} ?>>
										<td align="center"><?php echo $i+1; ?></td>
										<td align="center"><?php echo $sqlQueryRow->ID ?></td>
										<td align="center"><?php echo $sqlQueryRow->VSL_VISIT_DTLS_IB_VYG; ?></td>
										<td align="center"><?php echo $sqlQueryRow->VSL_NAME; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['cont_status']; ?></td>
										<td align="center"><?php echo $sqlQueryRow->CATEGORY; ?></td>
										<td align="center"><?php echo $sqlQueryRow->SIZ; ?></td>
										<td align="center"><?php echo ($sqlQueryRow->HEIGHT)/10 ; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['stowage_pos']; ?></td>
										<td align="center"><?php echo $sqlQueryRow->MLO; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['seal_no']; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['goods_and_ctr_wt_kg']; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['truck_no']; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['pod']; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['user_id']; ?></td>
										<td align="center"><?php echo $rslt_export_container_loading_list[$i]['last_update']; ?></td>
										<td align="center"><?php if($rslt_export_container_loading_list[$i]['cont_status']!=$sqlQueryRow->FREIGHT_KIND) echo "Need to load with given Freight Kind"; else if($rslt_export_container_loading_list[$i]['re_status']==0) echo "GENERAL"; else  echo "Need To Pre Advise or Something Others";?></td>
									</tr>
									<?php
										$container=$container.", ".$sqlQueryRow->ID;
										}
									}
									?>
								</table>
							</td>
						</tr>
						<!--tr>
							<td><?php echo substr($container,1); ?></td>
						</tr-->
					</table>
				</div>
				<table>
					<tr>
						<td><?php echo substr($container,1); ?></td>
					</tr>
				</table>
				<div class="clr"></div>
			</div>
		</div>
		<!--div class="sidebar">
			<?php //include_once("mySideBar.php"); ?>
		</div-->
		<div class="clr"></div>
	</div>
</div>
	