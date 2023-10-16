<?php if($_POST['options']=='html'){?>
<HTML>
	<!--HEAD>
		<TITLE>Container detail by Rotation</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}    // comment section

        </style>
    </HEAD>
	-->
<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		$rota=str_replace('/', '-', $rot);
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Container-List-$rota-Stripping.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
       //$rot=$_REQUEST['rot']; 	
	?>

	
	<table align="center" width="70%" border ='1' cellpadding='0' cellspacing='0'>
	<?php 
	if($_POST['options']=='html'){?>
	<!--tr bgcolor="#273076" height="100px">
		<td align="center" valign="middle" colspan="14" >
			<h1><font color="white">Chittagong Port Authority</font></h1>
		</td>
	</tr-->
	<tr>
		<td colspan="14" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
	</tr>
	<?php } ?>
	<tr bgcolor="#ffffff" align="center" height="50px">
		<td colspan="1" align="center"><font size="3"><b><?php echo $containerStatus;?></b></font></td>
		<td colspan="6" align="center"><font size="5"><b><?php echo $title;?></b></font></td>
	</tr>
	<!--tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr-->
	
	<tr bgcolor="#A9A9A9" align="center" height="25px">
		<td style="border-width:3px;border-style: double;"><b>SL.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container</b></td>
		<td style="border-width:3px;border-style: double;"><b>Striping Date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>Slot</b></td>
		<td style="border-width:3px;border-style: double;"><b>Freight Kind</b></td>
				
	</tr>

<?php
	include("dbConection.php");
			/*$str = "SELECT ib_vyg,id,time_out,
					(SELECT sparcsn4.inv_unit_fcy_visit.last_pos_name 
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					WHERE sparcsn4.inv_unit.id=tbl.id AND sparcsn4.inv_unit.category='STRGE' AND sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD') AS last_pos_name,
					(SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot 
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					WHERE sparcsn4.inv_unit.id=tbl.id AND sparcsn4.inv_unit.category='STRGE' AND sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD') AS last_pos_slot
					 FROM 
					   (
					  SELECT  sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.time_out,
					  (SELECT COUNT(*) FROM sparcsn4.srv_event WHERE applied_to_gkey=sparcsn4.inv_unit.gkey AND event_type_gkey=30) AS strip_evt
					  FROM sparcsn4.vsl_vessel_visit_details
					  INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					  INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.actual_ib_cv=sparcsn4.argo_carrier_visit.gkey
					  INNER JOIN sparcsn4.inv_unit ON sparcsn4.inv_unit.gkey=sparcsn4.inv_unit_fcy_visit.unit_gkey 
					  INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
					  inner join sparcsn4.ref_bizunit_scoped on sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
					  WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rot' 
					  AND sparcsn4.inv_unit_fcy_visit.time_out IS NOT NULL and sparcsn4.ref_bizunit_scoped.id='PIL' ORDER BY 2
						 ) AS tbl WHERE strip_evt>0";
			*/
	if($search_by=="rotation")
	{
		$str = "SELECT ib_vyg,id,time_out,freight_kind,
					(SELECT sparcsn4.inv_unit_fcy_visit.last_pos_name
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					WHERE sparcsn4.inv_unit.id=tbl.id AND sparcsn4.inv_unit.category='STRGE' AND sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD') AS last_pos_name,
					(SELECT sparcsn4.inv_unit_fcy_visit.last_pos_slot 
					FROM sparcsn4.inv_unit
					INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					WHERE sparcsn4.inv_unit.id=tbl.id AND sparcsn4.inv_unit.category='STRGE' AND sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD') AS last_pos_slot
					 FROM 
					   (
					  SELECT  sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.time_out,inv_unit.freight_kind,
					  (SELECT COUNT(*) FROM sparcsn4.srv_event WHERE applied_to_gkey=sparcsn4.inv_unit.gkey AND event_type_gkey=30) AS strip_evt
					  FROM sparcsn4.vsl_vessel_visit_details
					  INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
					  INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.actual_ib_cv=sparcsn4.argo_carrier_visit.gkey
					  INNER JOIN sparcsn4.inv_unit ON sparcsn4.inv_unit.gkey=sparcsn4.inv_unit_fcy_visit.unit_gkey 
					  INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
					  inner join sparcsn4.ref_bizunit_scoped on sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
					  WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg='$rot' 
					  AND sparcsn4.inv_unit_fcy_visit.time_out IS NOT NULL and sparcsn4.ref_bizunit_scoped.id='PIL' ORDER BY 2
						 ) AS tbl WHERE strip_evt>0";
	}
	else{
		$str="select * from(
			select sparcsn4.inv_unit.gkey,(select sparcsn4.argo_carrier_visit.id from sparcsn4.argo_carrier_visit where gkey=sparcsn4.inv_unit.declrd_ib_cv) as arcar_id,
			sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.time_in,sparcsn4.inv_unit_fcy_visit.last_pos_name,sparcsn4.inv_unit_fcy_visit.last_pos_slot 
			from sparcsn4.inv_unit 
			inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			inner join sparcsn4.ref_bizunit_scoped on sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
			where sparcsn4.inv_unit.category='STRGE' and sparcsn4.inv_unit.freight_kind='MTY'
			and date(sparcsn4.inv_unit_fcy_visit.time_in) between '$stripping_date_from' and '$stripping_date_to'
			and sparcsn4.ref_bizunit_scoped.id='PIL'
		) as tbl where arcar_id not like 'BRD%'";
	}
	
	$query=mysqli_query($con_sparcsn4,$str);					

	//echo $positon;
	$i=0;
	$j=0;	
	//$transit_state="";
	while($row=mysqli_fetch_object($query)){
	$i++;
	//echo "TT".$search_by;
	?>
	<tr align="center">

		<td><?php echo $i;?></td>
		<?php if($search_by=="dateRange")
		{
			$str_ibvyg_query="select sparcsn4.inv_unit_fcy_visit.flex_string10 as ib_vyg,sparcsn4.inv_unit.freight_kind from sparcsn4.inv_unit 
					inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
					where sparcsn4.inv_unit.id='$row->id' and sparcsn4.inv_unit.category='IMPRT' AND sparcsn4.inv_unit.gkey<'$row->gkey'
					order by sparcsn4.inv_unit.gkey desc limit 1";
			//echo $str_ibvyg_query;
					$rslt_ibv=mysqli_query($con_sparcsn4,$str_ibvyg_query);
					$rtn_ibv=mysqli_fetch_object($rslt_ibv);
					/* echo $rtn_ibv->ib_vyg;
					echo 1; */
	?>
		
		<td><?php if($rtn_ibv->ib_vyg) echo $rtn_ibv->ib_vyg; else echo "&nbsp;";?></td>
		<td><?php if($row->id) echo $row->id; else echo "&nbsp;";?></td>
		<td><?php if($row->time_in) echo $row->time_in; else echo "&nbsp;";?></td>
		<td><?php if($row->last_pos_name) echo $row->last_pos_name; else echo "&nbsp;";?></td>
		<td><?php if($row->last_pos_slot) echo $row->last_pos_slot; else echo "&nbsp;";?></td>
		<td><?php if($rtn_ibv->freight_kind) echo $rtn_ibv->freight_kind; else echo "&nbsp;";?></td>
	<?php } 
	else 
	{?>
	<td><?php if($row->ib_vyg) echo $row->ib_vyg; else echo "&nbsp;";?></td>
	<td><?php if($row->id) echo $row->id; else echo "&nbsp;";?></td>
	<td><?php if($row->time_out) echo $row->time_out; else echo "&nbsp;";?></td>
	<td><?php if($row->last_pos_name) echo $row->last_pos_name; else echo "&nbsp;";?></td>
	<td><?php if($row->last_pos_slot) echo $row->last_pos_slot; else echo "&nbsp;";?></td>
	<td><?php if($row->freight_kind) echo $row->freight_kind; else echo "&nbsp;";?></td>
	<?php }?>
</tr>

		<?php 
	
		}
		//$login_id = $this->session->userdata('login_id')
		//$login_id_trans=="";
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
// mysql_close($con_sparcsn4);
 ?>


</table>




<br />
<br />




<?php 
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
