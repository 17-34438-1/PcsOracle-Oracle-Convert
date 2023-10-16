<?php if($_POST['options']=='html'){?>
<HTML>

<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Vessel-List-$fromDt-TO-$toDt-Comments.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
       //$rot=$_REQUEST['rot']; 	
	?>

	
	<table align="center" width="80%" border ='0' cellpadding='0' cellspacing='0'>
	<?php 
	if($_POST['options']=='html'){?>
	<tr  height="100px">
		<td align="center" valign="middle" colspan="14" >
			<img align="middle"  src="<?php echo IMG_PATH?>cpanew.jpg">
		</td>
	</tr>
	<?php } ?>
	<tr  align="center" height="50px">
		<td colspan="14" align="center"><font size="5"><b><?php echo $title;?></b></font></td>
	</tr>

	</table>

	<div class="table-responsive">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none">
			<thead>
				<tr class="gridDark">
					<td style="border-width:3px;border-style: double;"><b>SL.</b></td>
					<td style="border-width:3px;border-style: double;"><b>Vessel Name</b></td>
					<td style="border-width:3px;border-style: double;"><b>Import Rotation</b></td>
					<td style="border-width:3px;border-style: double;"><b>Export Rotation</b></td>
					<td style="border-width:3px;border-style: double;"><b>Agent</b></td>
					<td style="border-width:3px;border-style: double;"><b>Berth Operator</b></td>
					<td style="border-width:3px;border-style: double;"><b>Status</b></td>
					<td style="border-width:3px;border-style: double;"><b>ETA</b></td>
					<td style="border-width:3px;border-style: double;"><b>ETD</b></td>
					<td style="border-width:3px;border-style: double;"><b>ATA</b></td>
					<td style="border-width:3px;border-style: double;"><b>ATD</b></td>
					<td style="border-width:3px;border-style: double;"><b>Status</b></td>
					<td style="border-width:3px;border-style: double;"><b>Status Time</b></td>
					<td style="border-width:3px;border-style: double;"><b>Comments</b></td>
					<td style="border-width:3px;border-style: double;"><b>Comments By</b></td>
					<td style="border-width:3px;border-style: double;"><b>Comments Time</b></td>
						
				</tr>
			</thead>
				<?php
					include("dbConection.php");
					include("dbOracleConnection.php");
							$str="SELECT ctmsmis.mis_exp_vvd.comments,ctmsmis.mis_exp_vvd.comments_by,ctmsmis.mis_exp_vvd.comments_time,ctmsmis.mis_exp_vvd.pre_comments,ctmsmis.mis_exp_vvd.pre_comments_time,ctmsmis.mis_exp_vvd.vvd_gkey
							FROM ctmsmis.mis_exp_vvd
							WHERE DATE(ctmsmis.mis_exp_vvd.comments_time) BETWEEN '$fromDt' and '$toDt'
							ORDER BY ctmsmis.mis_exp_vvd.comments_time DESC";

					$query=mysqli_query($con_sparcsn4,$str);					

					$i=0;
					$j=0;	
					$vvdGkey=0;
				
					while($row=mysqli_fetch_object($query)){
					$i++;

					 $vvdGkey=$row->vvd_gkey;

					 $sql1="
					
				SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
           	    SUBSTR(argo_carrier_visit.phase, 3, LENGTH( argo_carrier_visit.phase)) AS phase_num,SUBSTR(argo_carrier_visit.phase,3) AS phase_str,argo_visit_details.eta,argo_visit_details.etd,argo_carrier_visit.ata,argo_carrier_visit.atd,
                ref_bizunit_scoped.id AS agent,
                COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop
                FROM argo_carrier_visit
                INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
                INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
                INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
                INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
				WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
					";
					
				
					// WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'

					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql1);
					$row2=oci_execute($strQuery2Res);
					
					//print_r($row2->NAME);
					
					$results=array();
					$nrows = oci_fetch_all($strQuery2Res, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

					//print_r($nrows);

					oci_free_statement($strQuery2Res);
				
					$strQuery2Res=oci_parse($con_sparcsn4_oracle, $sql1);
					oci_execute($strQuery2Res);
					$rowShortName=oci_fetch_object($strQuery2Res);
					
					if($nrows>0){
						$vvdgkey=$rowShortName->VVD_GKEY;
					}
				

				?>
		<tbody>
			<tr class="gradeX">
				<td><?php echo $i?></td>
				<td><?php if($rowShortName->NAME) echo $rowShortName->NAME; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->IB_VYG) echo $rowShortName->IB_VYG; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->OB_VYG) echo $rowShortName->OB_VYG; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->AGENT) echo $rowShortName->AGENT; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->BERTHOP) echo $rowShortName->BERTHOP; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->PHASE_STR) echo $rowShortName->PHASE_STR; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->ETA) echo $rowShortName->ETA; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->ETD) echo $rowShortName->ETD; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->ATA) echo $rowShortName->ATA; else echo "&nbsp;";?></td>
				<td><?php if($rowShortName->ATD) echo $rowShortName->ATD; else echo "&nbsp;";?></td>
				<td><?php if($row->comments) echo $row->comments; else echo "&nbsp;";?></td>
				<td><?php if($row->comments_time) echo $row->comments_time; else echo "&nbsp;";?></td>
				<td><?php if($row->pre_comments) echo $row->pre_comments; else echo "&nbsp;";?></td>
				<td><?php if($row->comments_by) echo $row->comments_by; else echo "&nbsp;";?></td>
				<td><?php if($row->pre_comments_time) echo $row->pre_comments_time; else echo "&nbsp;";?></td>
					
			</tr>
		</tbody>
			<?php 

			//print_r($vvdgkey);

			}
			//print_r($vvdgkey);
			//print_r($vvdGkey);
		
			function Offdock($login_id)
				{
					if($login_id=='gclt')
					{
						return "GCL";
					}
					elseif($login_id=='saplw')
					{
						return "SAPE";
					}
					elseif($login_id=='ebil')
					{
						return "EBIL";
					}
					elseif($login_id=='cctcl')
					{
						return "CL";
					}
					elseif($login_id=='ktlt')
					{
						return "KTL";
					}
					elseif($login_id=='qnsc')
					{
						return "QNSC";
					}
					elseif($login_id=='ocl')
					{
						return "OCCL";
					}
					elseif($login_id=='vlsl')
					{
						return "VLSL";
					}
					elseif($login_id=='shml')
					{
						return "SHML";
					}
					elseif($login_id=='iqen')
					{
						return "IE";
					}
					elseif($login_id=='iltd')
					{
						return "IL";
					}
					
					elseif($login_id=='plcl')
					{
						return "PLCL";
					}
					elseif($login_id=='shpm')
					{
						return "SHPM";
					}
					elseif($login_id=='hsat')
					{
						return "HSAT";
					}
					elseif($login_id=='ellt')
					{
						return "ELL";
					}
					elseif($login_id=='bmcd')
					{
						return "BM";
					}
					elseif($login_id=='nclt')
					{
						return "NCL";
					}
					
					else
					{
						return "";
					}
					
				}
		mysqli_close($con_sparcsn4);
		oci_close($con_sparcsn4_oracle);
		?>
	</table>
</div>

<br />
<br />




<?php 
@mysqli_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
