<?php
	if($options == "xl"){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=PositionWise_Offdock_Container_List_Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");	
	}	
?>
<?php
	if($options == "html"){
?>
<div align="center" style="font-size:18px">
		<img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg">
	</div>
<?php
	}
?>

<table width="100%" border ='1' cellpadding='0'  cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="50px">
		<td colspan="14" align="center" style="border:none;"><font size="5"><b>POSITION WISE PRE ADVICE CONTAINER LIST</b></font></td>
		
	</tr>
		
	</tr>
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
	 $login_id=$this->session->userdata('login_id');
	 $of = Offdock($login_id);
	   
	
	   $str="SELECT * FROM (
		SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,mis_exp_unit_preadv_req.vvd_gkey,mis_exp_unit_preadv_req.gkey
		FROM ctmsmis.mis_exp_unit_preadv_req WHERE transOp='$of' AND DATE(last_update) > DATE(ADDDATE(NOW(),INTERVAL -8 DAY)) 
		)AS tmp";	
	   $query=mysqli_query($con_sparcsn4,$str);	
			

	//echo $positon;
	$i=0;
	$j=0;	
	//$transit_state="";
	while($row=mysqli_fetch_object($query)){
		 $VVD_GKEY="";
		 $gKey="";
		 $last_pos_name="";
		 $gKey=$row->gkey;
		 $VVD_GKEY=$row->vvd_gkey;
		 $str1="select vsl_vessels.name from vsl_vessel_visit_details
	     inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		 where vsl_vessel_visit_details.vvd_gkey='$VVD_GKEY'";
		
		 $strresult2 = oci_parse($con_sparcsn4_oracle, $str1);
		 oci_execute($strresult2);
		 while(($row2=oci_fetch_object($strresult2))!=false){
			$vsl_name=$row2->NAME;
		 }
		

		 $str2="select last_pos_name from inv_unit_fcy_visit where inv_unit_fcy_visit.unit_gkey='$gKey' fetch first 1 rows only";
		 $strResult3 = oci_parse($con_sparcsn4_oracle, $str2);
		 oci_execute($strResult3);
		while(($row3=oci_fetch_object($strResult3))!=false){
			$last_pos_name=$row3->LAST_POS_NAME;
		 }

		
	
		
		$strTrans="SELECT inv_unit_fcy_visit.transit_state FROM inv_unit 
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit.id='$row->cont_id' ORDER BY inv_unit_fcy_visit.gkey";
		
		
		$resTrans = oci_parse($con_sparcsn4_oracle,$strTrans);
		oci_execute($resTrans);
		$Trans="";
	
		while(($rowTrans=oci_fetch_object($resTrans))!=false)
		{
		  $Trans=$rowTrans->TRANSIT_STATE;	
		}			
				
		if($positon==$Trans)
		{
		$i++;
?>
     <tr align="center">
		<td><?php echo $i;?></td>
		<td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
		<td><?php if($vsl_name) echo  $vsl_name; else echo "&nbsp;";?></td>
		<td><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_height) echo $row->cont_height; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
		<td><?php $transs =$Trans; echo $str2 = substr($transs, 4);?></td>
		<td><?php if($last_pos_name) echo $last_pos_name; else echo "&nbsp;";?></td>
     </tr>

		<?php
		oci_free_statement($strresult2);
		oci_free_statement($strResult3);
		oci_free_statement($strTrans);
		 }
		
		}
		//$login_id = $this->session->userdata('login_id')
		//$login_id_trans=="";
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
				elseif($login_id=='ebil' or  $login_id=='S041997icd'  )
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
				else if($login_id=='nclt')
				{
					return "4013";
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
