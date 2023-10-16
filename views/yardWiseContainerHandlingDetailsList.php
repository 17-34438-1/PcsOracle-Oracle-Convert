<?php
	/*select * from (select inv_unit.id,
	(select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
	inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
	inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
	where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
	(select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
	inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
	inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
	where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
	sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
	(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
	(SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
	inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
	inv_unit_fcy_visit.time_in,inv_unit.seal_nbr1 as seal,inv_unit.goods_and_ctr_wt_kg
	from sparcsn4.inv_unit 
	inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
	inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
	where sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' and sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' ) as tmp
	where Yard_No= 'GCB'*/
	
	//include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	include("mydbPConnectionctmsmis.php");
	$sql_cond="";
	if($yard_no!="")
	{
		if($block=="ALL")
		{
			$sql_cond="Yard_No= '$yard_no'";
		}
		else
		{
			$sql_cond="Yard_No='$yard_no' and Block_No='$block'";
		}
	}
		
			
	if($org_Type_id==1)
	{
		// Previous
		// $sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
			// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
			// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
			// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey,
			// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			// inv_unit_fcy_visit.time_in

			// from sparcsn4.inv_unit 
			// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
			// where sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' and sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' 
			// and sparcsn4.inv_unit.category='IMPRT' and sparcsn4.ref_bizunit_scoped.id='$login_id') as tmp
			// where ".$sql_cond;
			
		// New
		$sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr, 
			(select substr(ref_equip_type.nominal_length,-2) 
			from inv_unit 
			inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey 
			inner join ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			fetch FIRST 1 rows ONLY) as siz, 
			(select substr(ref_equip_type.nominal_height,-2) 
			from inv_unit
			inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey 
			inner join ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch FIRST 1 rows ONLY)/10 as height, ref_bizunit_scoped.id as MLO,
			inv_unit.category,inv_unit.freight_kind,inv_unit_fcy_visit.last_pos_name,
			inv_unit_fcy_visit.last_pos_slot, inv_unit_fcy_visit.time_in from inv_unit 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			inner join ref_bizunit_scoped on inv_unit.line_op=ref_bizunit_scoped.gkey 
			where inv_unit_fcy_visit.transit_state='S40_YARD' and inv_unit_fcy_visit.visit_state='1ACTIVE'
			and inv_unit.category='IMPRT' and sparcsn4.ref_bizunit_scoped.id='$login_id'
			) tmp";
	}
	else if($org_Type_id==57) // Shipping Agent
	{
		// Previous-MySql
		// $sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
			// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
			// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
			// (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
			// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			// inv_unit_fcy_visit.time_in

			// from sparcsn4.inv_unit 
			// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			// inner join sparcsn4.ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
			// where sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' and sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' 
			// and sparcsn4.inv_unit.category='IMPRT' and sparcsn4.ref_bizunit_scoped.id in (SELECT r.id FROM sparcsn4.ref_bizunit_scoped r       
			// LEFT JOIN ( sparcsn4.ref_agent_representation X       
			// LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey 
			// WHERE Y.id = '$login_id')) as tmp
			// where ".$sql_cond;
			
			// New-Oracle
			$sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
				(select substr(ref_equip_type.nominal_length,-2) from inv_unit
				inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
				inner join  ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
				where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) as siz,
				(select substr(ref_equip_type.nominal_height,-2) from inv_unit
				inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
				inner join  ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
				where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10 as height,
				ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
				inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
				inv_unit_fcy_visit.time_in

				from inv_unit 
				inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				inner join ref_bizunit_scoped on inv_unit.line_op=ref_bizunit_scoped.gkey
				where inv_unit_fcy_visit.transit_state='S40_YARD' and inv_unit_fcy_visit.visit_state='1ACTIVE' 
				and inv_unit.category='IMPRT' and ref_bizunit_scoped.id in (SELECT r.id FROM ref_bizunit_scoped r       
				LEFT JOIN ( ref_agent_representation X       
				LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey 
				WHERE Y.id = '$login_id')) tmp";
	}
	else
	{
		// Previous-MySql
		// $sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
			// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
			// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
			// (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
			// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			// inv_unit_fcy_visit.time_in

			// from sparcsn4.inv_unit 
			// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
			// where sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' and sparcsn4.inv_unit_fcy_visit.visit_state='1ACTIVE' 
			// and sparcsn4.inv_unit.category='IMPRT') as tmp
			// where ".$sql_cond;
			
		// New-Oracle
		$sql = "select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			(select substr(ref_equip_type.nominal_length,-2) from inv_unit
			inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
			inner join  ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) as siz,
			(select substr(ref_equip_type.nominal_height,-2) from inv_unit
			inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
			inner join  ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10 as height,
			ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			inv_unit_fcy_visit.time_in

			from inv_unit 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			inner join ref_bizunit_scoped on inv_unit.line_op=ref_bizunit_scoped.gkey
			where inv_unit_fcy_visit.transit_state='S40_YARD' and inv_unit_fcy_visit.visit_state='1ACTIVE' 
			and inv_unit.category='IMPRT') tmp";
	}
	$sqlRslt=oci_parse($con_sparcsn4_oracle,$sql);								
?>		
	<!-- start: page -->
	<section class="panel">
		<div class="panel-body">
			<div class="invoice">
				<header class="clearfix">
					<div class="row">
						<div class="col-sm-12 text-center mt-md mb-md">
							<div class="ib">
								<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
								<h4 class="h4 mt-none mb-sm text-dark text-bold">Chittagong Port Authority</h4>
								<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">CURRENT YARD LYING CONTAINER REPOR</h5>
								<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
									<?php 
										$strTitle = "";
										$strTitle2 = "";
										$strTitle3 = "";
										$strTitle = "SEARCH FOR TERMINAL : ".$yard_no." AND BLOCK : ".$block;
										echo $strTitle;
									?>
								</h5>
							</div>
						</div>
					</div>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr class="gridDark">
								<th class="text-center">Sl</th>
								<th class="text-center">CONTAINER</th>									
								<th class="text-center">SEAL NUMBER</th>									
								<th class="text-center">SIZE</th>									
								<th class="text-center">HEIGHT</th>									
								<th class="text-center">WEIGHT</th>									
								<th class="text-center">MLO</th>									
								<th class="text-center">CATEGORY</th>									
								<th class="text-center">FRIEGHT KIND</th>
								<?php if($block=="ALL") {?>	
									<th class="text-center">BLOCK</th>
								<?php }?>
								<th class="text-center">LAST POSITION</th>
								<th class="text-center">TIME IN</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$eq = "";
								$i=0;
								$last_pos_slot = "";
								oci_execute($sqlRslt,OCI_DEFAULT);
								while (($row = oci_fetch_object($sqlRslt)) != false)						
								{
									$i=$i+1;
									$last_pos_slot = $row->LAST_POS_SLOT;
									$yard_no_val = "";
									$block_no_val = "";
									
									$queryYard = "SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
									$resultYard = mysqli_query($con_ctmsmis,$queryYard);
									while($resYard = mysqli_fetch_object($resultYard)){
										$yard_no_val = $resYard->Yard_No;										
									}
									
									$queryBlock = "SELECT ctmsmis.cont_block('$last_pos_slot','$yard_no_val') AS Block_No";
									$resultBlock = mysqli_query($con_ctmsmis,$queryBlock);
									while($resBlock = mysqli_fetch_object($resultBlock)){
										$block_no_val = $resBlock->Block_No;										
									}
									
									$status = 0;									
									if($block=="ALL")
									{
										if($yard_no_val==$yard_no){
											$status++;
										}											
									}
									else
									{
										if($yard_no_val==$yard_no and $block_no_val==$block){
											$status++;
										}
									}
									if($status > 0){
							?>
								<tr class="gradeX">									
									<td align="center"> <?php echo $i;?> </td>
									<td align="center"><?php if($row->ID) echo($row->ID); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->SEAL_NBR) echo($row->SEAL_NBR); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->SIZ) echo($row->SIZ); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->HEIGHT) echo($row->HEIGHT); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->WEIGHT) echo($row->WEIGHT); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->MLO) echo($row->MLO); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->CATEGORY) echo($row->CATEGORY); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->FREIGHT_KIND) echo($row->FREIGHT_KIND); else echo("&nbsp;");?></td>
									
									<?php if($block=="ALL") { ?>

									<td align="center"> <?php echo $block_no_val; ?> </td>
									
									<?php } ?>
									
									<td align="center"><?php if($row->LAST_POS_NAME) echo($row->LAST_POS_NAME); else echo("&nbsp;");?></td>
									<td align="center"><?php if($row->TIME_IN) echo($row->TIME_IN); else echo("&nbsp;");?></td>
								</tr>
								<?php } } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
	<!-- end: page -->
</div>

