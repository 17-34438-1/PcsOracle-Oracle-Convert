
<div align="center" style="font-size:18px">
		<img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg">
	</div>
<table width="100%" cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="50px">
		<td colspan="14" align="center" style="border:none;"><font size="4"><b>POSITION WISE PRE ADVICE CONTAINER</b></font></td>
		
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
	



	$query=mysqli_query($con_sparcsn4,"SELECT * FROM (
	SELECT gkey,cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,last_update,vvd_gkey
	FROM ctmsmis.mis_exp_unit_preadv_req WHERE cont_id='$container_no' ORDER BY last_update DESC LIMIT 1
	)AS tmp");	
	

	//echo $positon;
	$i=0;
	$j=0;	
	//$transit_state="";
	while($row=mysqli_fetch_object($query)){
		
		 $VVD_GKEY="";
		 $CONT_ID="";
		 $vsl_name="";
	     $VVD_GKEY=$row->vvd_gkey;
		 $CONT_ID=$row->cont_id;
		 $last_pos_name="";

	    $query1="select vsl_vessels.name from vsl_vessel_visit_details
		inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		where vsl_vessel_visit_details.vvd_gkey='$VVD_GKEY'";

		$query1Res = oci_parse($con_sparcsn4_oracle, $query1);
        oci_execute($query1Res);
		
		while(($rtnRes1=oci_fetch_object($query1Res))!=false){
			 $vsl_name=$rtnRes1->NAME;
		}
		

		 $query2="select last_pos_name from inv_unit_fcy_visit
		 inner join inv_unit on inv_unit.gkey=inv_unit_fcy_visit.unit_gkey
		 where inv_unit.category='EXPRT' and  inv_unit.id='$CONT_ID' order by inv_unit.gkey desc  fetch first 1 rows only";
		 $query2Res = oci_parse($con_sparcsn4_oracle, $query2);
		 oci_execute($query2Res);
		 while(($rtnRes2=oci_fetch_object($query2Res))!=false){
			  $last_pos_name=$rtnRes2->LAST_POS_NAME;
		 }

		 $query3="select inv_unit_fcy_visit.transit_state from inv_unit_fcy_visit
		 inner join inv_unit on inv_unit.gkey=inv_unit_fcy_visit.unit_gkey
		 where inv_unit.category='EXPRT' and  inv_unit.id='$CONT_ID' order by inv_unit.gkey desc  fetch first 1 rows only";
		 $query3Res = oci_parse($con_sparcsn4_oracle, $query3);
		 oci_execute($query3Res);
		 while(($rtnRes3=oci_fetch_object($query3Res))!=false){
			 $transit_state=$rtnRes3->TRANSIT_STATE;
	     }

	
				
		
		$strTrans="select inv_unit_fcy_visit.transit_state from inv_unit 
		inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit.id='$row->cont_id' order by inv_unit_fcy_visit.gkey";
		$resTrans=oci_parse($con_sparcsn4_oracle,$strTrans);
		oci_execute($resTrans);

		while(($rowTrans=oci_fetch_object($resTrans))!=false){
			$Trans=$rowTrans->TRANSIT_STATE;
		}

		$i++;
?>

<?php if($transit_state=='S20_INBOUND'){
	
?>
<tr align="center">
		<td><?php echo $i;?></td>
		<td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
		<td><?php if($vsl_name) echo $vsl_name; else echo "&nbsp;";?></td>
		<td><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_height) echo $row->cont_height; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
		<td><?php $transs =$Trans; echo $str2 = substr($transs, 4);?></td>
		<td><?php if($last_pos_name) echo $last_pos_name; else echo "&nbsp;";?></td> 
</tr>

<?Php }?>

		<?php 
		oci_free_statement($resTrans);
		oci_free_statement($query2Res);
		oci_free_statement($query3Res);
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
 mysqli_close($con_sparcsn4);
 oci_close($con_sparcsn4_oracle);
 ?>


</table>
