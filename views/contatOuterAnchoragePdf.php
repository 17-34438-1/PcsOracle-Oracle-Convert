<html>
    <head>
        
    </head>
    <body>
        <table>
            <tr>
                <th align="center" >
                    <h2 align="center"><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
                    <h3 align="center">Number of ICD, LCL, PANGAON, GINGER, GARLIC, ONION & REEFER Container of the Vessel at Outer Anchorage (<?php echo $date; ?>)</h3>
                </th>
            </tr>
        </table>

        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2" align="center">SL.</th>
                    <th rowspan="2" align="center">VESSEL</th>
                    <th colspan="4" align="center">ICD</th>
                    <th colspan="4" align="center">LCL</th>
                    <th colspan="4" align="center">PANGAON</th>
                    <th colspan="4" align="center">GINGER</th>
                    <th colspan="4" align="center">GARLIC</th>
                    <th colspan="4" align="center">ONION</th>
                    <th colspan="4" align="center">REEFER</th>
                    <th colspan="4" align="center">IMDG</th>
                    <th colspan="4" align="center">OFFDOCK</th>
                </tr>

                <tr>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                    <th align="center">20</th>
                    <th align="center">40</th>
                    <th align="center">BOX</th>
                    <th align="center">TEUs</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    include("dbConection.php");
					include("dbOracleConnection.php");

                    
					$vsl_query="SELECT vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
					SUBSTR(argo_carrier_visit.phase,3) AS phase_str,argo_visit_details.eta,
					argo_visit_details.etd,argo_carrier_visit.ata
					FROM argo_carrier_visit
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey	
					WHERE cast(TO_DATE('$date','YYYY-MM-DD')as date) BETWEEN cast(ata as date) AND cast(atd as date)
					ORDER BY argo_carrier_visit.phase";

                    $totalIcd20 = 0;
                    $totalIcd40 = 0;
                    $totalIcdBox = 0;
                    $totalIcdTeus = 0;

                    $totallcl20 = 0;
                    $totallcl40 = 0;
                    $totallclBox = 0;
                    $totallclTeus = 0;

                    $totalpgn20 = 0;
                    $totalpgn40 = 0;
                    $totalpgnBox = 0;
                    $totalpgnTeus = 0;

                    $totalgngr20 = 0;
                    $totalgngr40 = 0;
                    $totalgngrBox = 0;
                    $totalgngrTeus = 0;

                    $totalgrlc20 = 0;
                    $totalgrlc40 = 0;
                    $totalgrlcBox = 0;
                    $totalgrlcTeus = 0;

                    $totalonion20 = 0;
                    $totalonion40 = 0;
                    $totalonionBox = 0;
                    $totalonionTeus = 0;

                    $totalrfr20 = 0;
                    $totalrfr40 = 0;
                    $totalrfrBox = 0;
                    $totalrfrTeus = 0;

                    $totalimdg20 = 0;
                    $totalimdg40 = 0;
                    $totalimdgBox = 0;
                    $totalimdgTeus = 0;

                    $totaloffdock20 = 0;
                    $totaloffdock40 = 0;
                    $totaloffdockBox = 0;
                    $totaloffdockTeus = 0;

                    $rotation = "";
                    $vsl_name = "";
                    
			
					$vsl_rslt=oci_parse($con_sparcsn4_oracle,$vsl_query);
					oci_execute($vsl_rslt);
					
                    $i=1;
                   
					while(($vsl=oci_fetch_object($vsl_rslt))!=false)
                    {
                        $vsl_name = $vsl->NAME;
                        $rotation = $vsl->IB_VYG;
                ?>

                <tr>
                    <td align="center"><?php echo $i; ?></td>
                    <td align="center"><?php echo $vsl_name;?></td>
                    
                    <?php
                        //ICD starts 
                    
                        include("mydbPConnection.php");

                        $icd_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND  igm_sup_detail_container.off_dock_id='2592'
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.off_dock_id='2592') AS tmp";

                        $icd20 = 0;
                        $icd40 = 0;
                        $icdBox = 0;
                        $icdTeus = 0;
                        $icd_rslt=mysqli_query($con_cchaportdb,$icd_query);
                        while($icd_row=mysqli_fetch_object($icd_rslt))
                        {
                            $icd20 = $icd_row->ft_20;
                            $totalIcd20+=$icd20;
                            $icd40 = $icd_row->ft_40;
                            $totalIcd40+=$icd40;
                            $icdBox = $icd_row->box;
                            $totalIcdBox+=$icdBox;
                            $icdTeus = $icd_row->teus;
                            $totalIcdTeus+=$icdTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $icd20; ?></td>
                    <td align="center"><?php echo $icd40; ?></td>
                    <td align="center"><?php echo $icdBox; ?></td>
                    <td align="center"><?php echo $icdTeus; ?></td>

                    <?php
                        //LCL starts 
                        
                        include("mydbPConnection.php");

                        $lcl_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND  igm_sup_detail_container.cont_status='LCL'
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.cont_status='LCL') AS tmp";

                        $lcl20 = 0;
                        $lcl40 = 0;
                        $lclBox = 0;
                        $lclTeus = 0;
                        $lcl_rslt=mysqli_query($con_cchaportdb,$lcl_query);
                        while($lcl_row=mysqli_fetch_object($lcl_rslt))
                        {
                            $lcl20 = $lcl_row->ft_20;
                            $totallcl20+=$lcl20;
                            $lcl40 = $lcl_row->ft_40;
                            $totallcl40+=$lcl40;
                            $lclBox = $lcl_row->box;
                            $totallclBox+=$lclBox;
                            $lclTeus = $lcl_row->teus;
                            $totallclTeus+=$lclTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $lcl20; ?></td>
                    <td align="center"><?php echo $lcl40; ?></td>
                    <td align="center"><?php echo $lclBox; ?></td>
                    <td align="center"><?php echo $lclTeus; ?></td>
                    
                    <?php
                        //PANGAON starts 
                        
                        include("mydbPConnection.php");

                        $pgn_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND  igm_sup_detail_container.off_dock_id='5235'
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.off_dock_id='5235') AS tmp";

                        $pgn20 = 0;
                        $pgn40 = 0;
                        $pgnBox = 0;
                        $pgnTeus = 0;
                        $pgn_rslt=mysqli_query($con_cchaportdb,$pgn_query);
                        while($pgn_row=mysqli_fetch_object($pgn_rslt))
                        {
                            $pgn20 = $pgn_row->ft_20;
                            $totalpgn20+=$pgn20;
                            $pgn40 = $pgn_row->ft_40;
                            $totalpgn40+=$pgn40;
                            $pgnBox = $pgn_row->box;
                            $totalpgnBox+=$pgnBox;
                            $pgnTeus = $pgn_row->teus;
                            $totalpgnTeus+=$pgnTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $pgn20; ?></td>
                    <td align="center"><?php echo $pgn40; ?></td>
                    <td align="center"><?php echo $pgnBox; ?></td>
                    <td align="center"><?php echo $pgnTeus; ?></td>
                    
                    <?php
                        //Ginger starts 
                        
                        include("mydbPConnection.php");

                        $gngr_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'
                        AND 
                        (igm_supplimentary_detail.Description_of_Goods LIKE '%Ginger%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%ginger%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%GINGER%')
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND  
                        (igm_details.Description_of_Goods LIKE '%Ginger%'
                        OR igm_details.Description_of_Goods LIKE '%ginger%'
                        OR igm_details.Description_of_Goods LIKE '%GINGER%')) AS tmp";

                        $gngr20 = 0;
                        $gngr40 = 0;
                        $gngrBox = 0;
                        $gngrTeus = 0;
                        $gngr_rslt=mysqli_query($con_cchaportdb,$gngr_query);
                        while($gngr_row=mysqli_fetch_object($gngr_rslt))
                        {
                            $gngr20 = $gngr_row->ft_20;
                            $totalgngr20+=$gngr20;
                            $gngr40 = $gngr_row->ft_40;
                            $totalgngr40+=$gngr40;
                            $gngrBox = $gngr_row->box;
                            $totalgngrBox+=$gngrBox;
                            $gngrTeus = $gngr_row->teus;
                            $totalgngrTeus+=$gngrTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $gngr20; ?></td>
                    <td align="center"><?php echo $gngr40; ?></td>
                    <td align="center"><?php echo $gngrBox; ?></td>
                    <td align="center"><?php echo $gngrTeus; ?></td>
                    
                    <?php
                        //Garlic starts 
                        
                        include("mydbPConnection.php");

                        $grlc_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'
                        AND 
                        (igm_supplimentary_detail.Description_of_Goods LIKE '%Garlic%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%garlic%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%GARLIC%')
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND  
                        (igm_details.Description_of_Goods LIKE '%Garlic%'
                        OR igm_details.Description_of_Goods LIKE '%garlic%'
                        OR igm_details.Description_of_Goods LIKE '%GARLIC%')) AS tmp";

                        $grlc20 = 0;
                        $grlc40 = 0;
                        $grlcBox = 0;
                        $grlcTeus = 0;
                        $grlc_rslt=mysqli_query($con_cchaportdb,$grlc_query);
                        while($grlc_row=mysqli_fetch_object($grlc_rslt))
                        {
                            $grlc20 = $grlc_row->ft_20;
                            $totalgrlc20+=$grlc20;
                            $grlc40 = $grlc_row->ft_40;
                            $totalgrlc40+=$grlc40;
                            $grlcBox = $grlc_row->box;
                            $totalgrlcBox+=$grlcBox;
                            $grlcTeus = $grlc_row->teus;
                            $totalgrlcTeus+=$grlcTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $grlc20; ?></td>
                    <td align="center"><?php echo $grlc40; ?></td>
                    <td align="center"><?php echo $grlcBox; ?></td>
                    <td align="center"><?php echo $grlcTeus; ?></td>
                    
                    <?php
                        //Onion starts 
                        
                        include("mydbPConnection.php");

                        $onion_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'
                        AND  (igm_supplimentary_detail.Description_of_Goods LIKE '%Onion%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%onion%'
                        OR igm_supplimentary_detail.Description_of_Goods LIKE '%ONION%')
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, 
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND  
                        (igm_details.Description_of_Goods LIKE '%Onion%'
                        OR igm_details.Description_of_Goods LIKE '%onion%'
                        OR igm_details.Description_of_Goods LIKE '%ONION%')) AS tmp";

                        $onion20 = 0;
                        $onion40 = 0;
                        $onionBox = 0;
                        $onionTeus = 0;
                        $onion_rslt=mysqli_query($con_cchaportdb,$onion_query);
                        while($onion_row=mysqli_fetch_object($onion_rslt))
                        {
                            $onion20 = $onion_row->ft_20;
                            $totalonion20+=$onion20;
                            $onion40 = $onion_row->ft_40;
                            $totalonion40+=$onion40;
                            $onionBox = $onion_row->box;
                            $totalonionBox+=$onionBox;
                            $onionTeus = $onion_row->teus;
                            $totalonionTeus+=$onionTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $onion20; ?></td>
                    <td align="center"><?php echo $onion40; ?></td>
                    <td align="center"><?php echo $onionBox; ?></td>
                    <td align="center"><?php echo $onionTeus; ?></td>



                    <?php
                        //REEFER starts 
                    
                        include("dbConection.php");
						include("dbOracleConnection.php");

                     

					    $rfr_query="SELECT  SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, ((SUM(ft_40)*2) +SUM(ft_20)) AS teus, COUNT(*) AS box 
						FROM (
						SELECT inv_unit.id,
						vsl_vessels.name AS vsl_name,
						vsl_vessel_visit_details.ib_vyg AS vsl_visit_dtls_ib_vyg,
						(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = 20 THEN 1 ELSE 0 END) AS ft_20,
						(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)> 20 THEN 1 ELSE 0 END) AS ft_40,
						SUBSTR(ref_equip_type.nominal_length,-2) AS siz,
						SUBSTR(ref_equip_type.nominal_height,-2)/10 AS height
						FROM inv_unit 
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
						INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
						INNER JOIN argo_carrier_visit ON  argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
						INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
						WHERE ref_equip_type.iso_group IN ('RE','RS','RT') 
						AND vsl_vessel_visit_details.ib_vyg='$rotation'
						)  tmp";

                         $rfr_rslt=oci_parse($con_sparcsn4_oracle,$rfr_query);
                         oci_execute($rfr_rslt);

                        $i=1;

                       

                        $rfr20 = 0;
                        $rfr40 = 0;
                        $rfrBox = 0;
                        $rfrTeus = 0;

                        
					  while(($rfr_row=oci_fetch_object($rfr_rslt))!=false)
                       
                        {
                            $rfr20 = $rfr_row->FT_20;
                            $totalrfr20+=$rfr20;
                            $rfr40 = $rfr_row->FT_40;
                            $totalrfr40+=$rfr40;
                            $rfrBox = $rfr_row->BOX;
                            $totalrfrBox+=$rfrBox;
                            $rfrTeus = $rfr_row->TEUS;
                            $totalrfrTeus+=$rfrTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $rfr20; ?></td>
                    <td align="center"><?php echo $rfr40; ?></td>
                    <td align="center"><?php echo $rfrBox; ?></td>
                    <td align="center"><?php echo $rfrTeus; ?></td>
                    
                    <?php
                        //IMDG starts 
                        
                        include("mydbPConnection.php");

                        $imdg_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number, igm_sup_detail_container.cont_imo,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'
                        AND  (igm_sup_detail_container.cont_imo IS NOT NULL AND igm_sup_detail_container.cont_imo!='')
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, igm_detail_container.cont_imo,
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND  (igm_detail_container.cont_imo IS NOT NULL AND igm_detail_container.cont_imo!='') ) AS tmp";

                        $imdg20 = 0;
                        $imdg40 = 0;
                        $imdgBox = 0;
                        $imdgTeus = 0;
                        $imdg_rslt=mysqli_query($con_cchaportdb,$imdg_query);
                        while($imdg_row=mysqli_fetch_object($imdg_rslt))
                        {
                            $imdg20 = $imdg_row->ft_20;
                            $totalimdg20+=$imdg20;
                            $imdg40 = $imdg_row->ft_40;
                            $totalimdg40+=$imdg40;
                            $imdgBox = $imdg_row->box;
                            $totalimdgBox+=$imdgBox;
                            $imdgTeus = $imdg_row->teus;
                            $totalimdgTeus+=$imdgTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $imdg20; ?></td>
                    <td align="center"><?php echo $imdg40; ?></td>
                    <td align="center"><?php echo $imdgBox; ?></td>
                    <td align="center"><?php echo $imdgTeus; ?></td>

                    <?php
                        //OffDock starts 
                        
                        include("mydbPConnection.php");

                        $offdock_query = "SELECT SUM(ft_20) AS ft_20, SUM(ft_40) AS ft_40, (SUM(ft_40)*2+SUM(ft_20)) AS teus, COUNT(*) AS box 
                        FROM (
                        
                        SELECT igm_sup_detail_container.cont_number, igm_sup_detail_container.cont_imo, off_dock_id,
                        
                        (CASE WHEN igm_sup_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_sup_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        
                        FROM igm_sup_detail_container
                        INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
                        WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'
                        AND   igm_sup_detail_container.off_dock_id NOT IN ('2591', '2592', '5235')
                        
                        UNION 
                        
                        SELECT igm_detail_container.cont_number, igm_detail_container.cont_imo, off_dock_id,
                        
                        (CASE WHEN igm_detail_container.cont_size = 20 THEN 1 ELSE 0 END) AS ft_20,
                        (CASE WHEN igm_detail_container.cont_size > 20 THEN 1 ELSE 0 END) AS ft_40
                        FROM igm_detail_container
                        INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
                        WHERE igm_details.Import_Rotation_No='$rotation' AND  igm_detail_container.off_dock_id NOT IN ('2591', '2592', '5235')  ) AS tmp";

                        $offdock20 = 0;
                        $offdock40 = 0;
                        $offdockBox = 0;
                        $offdockTeus = 0;
                        $offdock_rslt=mysqli_query($con_cchaportdb,$offdock_query);
                        while($offdock_row=mysqli_fetch_object($offdock_rslt))
                        {
                            $offdock20 = $offdock_row->ft_20;
                            $totaloffdock20+=$offdock20;
                            $offdock40 = $offdock_row->ft_40;
                            $totaloffdock40+=$offdock40;
                            $offdockBox = $offdock_row->box;
                            $totaloffdockBox+=$offdockBox;
                            $offdockTeus = $offdock_row->teus;
                            $totaloffdockTeus+=$offdockTeus;
                        }
                    
                    ?>

                    <td align="center"><?php echo $offdock20;?></td>
                    <td align="center"><?php echo $offdock40;?></td>
                    <td align="center"><?php echo $offdockBox;?></td>
                    <td align="center"><?php echo $offdockTeus;?></td>

                </tr>

                <?php
                    $i++;
                    }
                ?>

                <tr>
                    <th align="center" colspan="2">Total</th>
                    <th align="center"><?php echo $totalIcd20; ?></th>
                    <th align="center"><?php echo $totalIcd40; ?></th>
                    <th align="center"><?php echo $totalIcdBox; ?></th>
                    <th align="center"><?php echo $totalIcdTeus; ?></th>
                    <th align="center"><?php echo $totallcl20; ?></th>
                    <th align="center"><?php echo $totallcl40; ?></th>
                    <th align="center"><?php echo $totallclBox; ?></th>
                    <th align="center"><?php echo $totallclTeus; ?></th>
                    <th align="center"><?php echo $totalpgn20; ?></th>
                    <th align="center"><?php echo $totalpgn40; ?></th>
                    <th align="center"><?php echo $totalpgnBox; ?></th>
                    <th align="center"><?php echo $totalpgnTeus; ?></th>
                    <th align="center"><?php echo $totalgngr20; ?></th>
                    <th align="center"><?php echo $totalgngr40; ?></th>
                    <th align="center"><?php echo $totalgngrBox; ?></th>
                    <th align="center"><?php echo $totalgngrTeus;?></th>
                    <th align="center"><?php echo $totalgrlc20; ?></th>
                    <th align="center"><?php echo $totalgrlc40; ?></th>
                    <th align="center"><?php echo $totalgrlcBox; ?></th>
                    <th align="center"><?php echo $totalgrlcTeus; ?></th>
                    <th align="center"><?php echo $totalonion20; ?></th>
                    <th align="center"><?php echo $totalonion40; ?></th>
                    <th align="center"><?php echo $totalonionBox; ?></th>
                    <th align="center"><?php echo $totalonionTeus; ?></th>
                    <th align="center"><?php echo $totalrfr20; ?></th>
                    <th align="center"><?php echo $totalrfr40; ?></th>
                    <th align="center"><?php echo $totalrfrBox; ?></th>
                    <th align="center"><?php echo $totalrfrTeus; ?></th>
                    <th align="center"><?php echo $totalimdg20; ?></th>
                    <th align="center"><?php echo $totalimdg40; ?></th>
                    <th align="center"><?php echo $totalimdgBox; ?></th>
                    <th align="center"><?php echo $totalimdgTeus; ?></th>
                    <th align="center"><?php echo $totaloffdock20; ?></th>
                    <th align="center"><?php echo $totaloffdock40; ?></th>
                    <th align="center"><?php echo $totaloffdockBox; ?></th>
                    <th align="center"><?php echo $totaloffdockTeus; ?></th>
                </tr>
            </tbody>
        
        </table>
    <?php
		
		oci_close($con_sparcsn4_oracle);
		mysqli_close($con_cchaportdb);
    ?>
    <body>
</html>
	

	
		


