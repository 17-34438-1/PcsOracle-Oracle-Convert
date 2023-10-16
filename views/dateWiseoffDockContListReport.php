<?php
	if($type == "xl"){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=DateWise_Offdock_Container_List_Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");	
	}	
?>
<?php
	if($type == "html"){
?>
<div align="center" style="font-size:18px">
	<img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg">
</div>
<?php
	}
?>

<table width="100%"  cellpadding='0' cellspacing='0'>
		<tr bgcolor="#ffffff" align="center" height="50px">
			<td colspan="14" align="center"  style="border:none;"><font size="4"><b>DATE WISE PRE ADVICE CONTAINER LIST</b></font></td>
		</tr>
	<tr bgcolor="#ffffff" align="center" height="50px">
		
		<td colspan="15" align="center"  style="border:none;"><font color="blue"><b>FROM DATE: <?php echo $fromdate;?>  TODATE: <?php echo $todate;?></font></b>
			</td>
	</tr>
<table/>	
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>	
	<tr bgcolor="#A9A9A9" align="center" height="25px">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>
		<td style="border-width:3px;border-style: double;"><b>Cont State</b></td>
		<td style="border-width:3px;border-style: double;"><b>Position</b></td>
		
	</tr>

<?php
	include("dbConection.php");
	include("dbOracleConnection.php");
	$of = Offdock($login_id);
// 	$query=mysqli_query($con_sparcsn4,"select * from (
// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,
// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
// from ctmsmis.mis_exp_unit_preadv_req where transOp='$of' and date(last_update) BETWEEN '$fromdate' AND '$todate'
//  )as tmp");

	$query=mysqli_query($con_sparcsn4,"	SELECT * FROM (
		SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,vvd_gkey
		
		FROM ctmsmis.mis_exp_unit_preadv_req WHERE transOp='$of' AND DATE(last_update) BETWEEN '$fromdate' AND '$todate'
		 )AS tmp");

	//echo $positon;
	$i=0;
	$j=0;	
	//$transit_state="";
	$vvd_gkey=0;
	while($row=mysqli_fetch_object($query)){
	$i++;
	
	$vvd_gkey=$row->vvd_gkey;

				
		// $strTrans = "select substring(sparcsn4.inv_unit_fcy_visit.transit_state,5) as transit_state,last_pos_name from sparcsn4.inv_unit 
		// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		// where sparcsn4.inv_unit.id='$row->cont_id' order by sparcsn4.inv_unit_fcy_visit.gkey";


		$vessel_name = "SELECT vsl_vessels.name FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		WHERE vsl_vessel_visit_details.vvd_gkey='$row->vvd_gkey'";
       $resSql1 = oci_parse($con_sparcsn4_oracle, $vessel_name);
	   oci_execute($resSql1);
	   $vsl_name = "";
	   	while(($row1=oci_fetch_object($resSql1)) !=false)
		{
			$vsl_name = $row1->NAME;
		
		}

		$strTrans = "select substr(inv_unit_fcy_visit.transit_state,5)  as transit_state,last_pos_name,inv_unit.id from inv_unit 
		inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit.id='$row->cont_id' order by inv_unit_fcy_visit.gkey    ";
		

		$resTrans= oci_parse($con_sparcsn4_oracle, $strTrans);
		oci_execute($resSql2);
		$Trans="";
		$LastPosition="";
		while(($rowTrans=oci_fetch_object($resTrans)) !=false)
		 {
			$Trans=$rowTrans->TRANSIT_STATE;
			$LastPosition=$rowTrans->LAST_POS_NAME;
		 
		 }


		// $resTrans = mysqli_query($con_sparcsn4,$strTrans);
		// $Trans="";
		// $LastPosition="";
		
		// while($rowTrans = mysqli_fetch_object($resTrans))
		// {
		// 	$Trans=$rowTrans->transit_state;
		// 	$LastPosition=$rowTrans->last_pos_name;
		// }			
				
		
		
?>
<tr align="center">
		<td><?php echo $i;?></td>
		<td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
		<td><?php  echo $vsl_name;?></td>
		<td><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_height) echo $row->cont_height; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
		<td><?php if($Trans) echo  $Trans; else echo "&nbsp;";?></td>
		<td><?php if($LastPosition) echo  $LastPosition; else echo "&nbsp;";?></td>
		
</tr>

		<?php } ?>
		
<?php
		function Offdock($login_id)
			{
				if($login_id=='gclt')
				{
					return "3328";
				}
				elseif($login_id=='saplw')
				{
					return "3450";
				}
				elseif($login_id=='ebil')
				{
					return "2594";
				}
				elseif($login_id=='cctcl')
				{
					return "2595";
				}
				elseif($login_id=='ktlt')
				{
					return "2596";
				}
				elseif($login_id=='qnsc')
				{
					return "2597";
				}
				elseif($login_id=='ocl')
				{
					return "2598";
				}
				elseif($login_id=='vlsl')
				{
					return "2599";
				}
				elseif($login_id=='shml')
				{
					return "2600";
				}
				elseif($login_id=='iqen')
				{
					return "2601";
				}
				elseif($login_id=='iltd')
				{
					return "2620";
				}
				
				elseif($login_id=='plcl')
				{
					return "2643";
				}
				elseif($login_id=='shpm')
				{
					return "2646";
				}
				elseif($login_id=='hsat')
				{
					return "3697";
				}
				elseif($login_id=='ellt')
				{
					return "3709";
				}
				elseif($login_id=='bmcd')
				{
					return "3725";
				}
				elseif($login_id=='nclt')
				{
					return "4013";
				}
				elseif($login_id=='kdsl')
				{
					return "2624";
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
