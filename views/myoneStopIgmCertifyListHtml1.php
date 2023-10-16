<meta http-equiv="content-type" content="text/html;charset=Windows-1252">
    <script type="text/javascript">
        var people, asc1 = 1,
            asc2 = 1,
            asc3 = 1;
        window.onload = function () {
            people = document.getElementById("data");
        }

        function sort_table(tbody, col, asc) {
            var rows = tbody.rows,
                rlen = rows.length,
                arr = new Array(),
                i, j, cells, clen;
            // fill the array with values from the table
            for (i = 0; i < rlen; i++) {
                cells = rows[i].cells;
                clen = cells.length;
                arr[i] = new Array();
                for (j = 0; j < clen; j++) {
                    arr[i][j] = cells[j].innerHTML;
                }
            }
            // sort the array by the specified column number (col) and order (asc)
            arr.sort(function (a, b) {
                return (a[col] == b[col]) ? 0 : ((a[col] > b[col]) ? asc : -1 * asc);
            });
            // replace existing rows with new rows created from the sorted array
            for (i = 0; i < rlen; i++) {
                rows[i].innerHTML = "<td>" + arr[i].join("</td><td>") + "</td>";
            }
        }
		
		  function getFocus() {
            document.getElementById("ddl_imp_cont_no").focus();
        }
		
	</script>
	
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/oneStopIgmCertifyList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_cont_no" id="ddl_imp_cont_no" class="form-control" autofocus placeholder="Container No">
								</div>
								<div class="input-group mb-md">
									<span class="">OR </span>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_bl_no" id="ddl_imp_bl_no" class="form-control" placeholder="BL No">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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
	
	<?php
/*****************************************************
Developed BY: Shemul Bhowmick
Sr. Software Engineer
DataSoft Systems Bangladesh Ltd
******************************************************/

$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');

?>

<div class="table-responsive">
<table border="0" width="100%" bgcolor="#FFFFFF">
	<TR align="center"><TD colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></TD></TR>
	<TR align="center"><TD colspan="6" ><h2><span ><?php if($containerNo!="") echo "Container No: ".$containerNo; else echo "BL No: ".$blNo ?></span> </h2></TD></TR>
	
	<TR><TD align="center">
	<div class="table-responsive">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<!--table id="example" class="display" width="100%" cellspacing="0"-->
		<thead>
			<tr class="gridDark" style="cursor: pointer;" title="Click to sort">
				<th align="center" onclick="sort_table(data, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">SL</th>
				<th align="center" onclick="sort_table(data, 1, asc2); asc2 *= -1; asc3 = 1; asc4 = 1;">Gate Out Date & Time</th>
				<th align="center" onclick="sort_table(data, 2, asc3); asc3 *= -1; asc4 = 1; asc5 = 1;">Last Move Date & Time</th>
				<th align="center" onclick="sort_table(data, 3, asc4); asc4 *= -1; asc5 = 1; asc6 = 1;">Container</th>
				<th align="center" onclick="sort_table(data, 4, asc5); asc5 *= -1; asc6 = 1; asc7 = 1;">Yard</th>
				<th align="center" onclick="sort_table(data, 5, asc6); asc6 *= -1; asc7 = 1; asc8 = 1;">Position</th>
				<th align="center" onclick="sort_table(data, 6, asc7); asc7 *= -1; asc8 = 1; asc9 = 1;">Cont. Type</th>
				<th align="center" onclick="sort_table(data, 7, asc8); asc8 *= -1; asc9 = 1; asc10 = 1;">Status</th>
				<th align="center" onclick="sort_table(data, 8, asc9); asc9 *= -1; asc1 = 1; asc11 = 1;">Discharge Time</th>
				<th align="center" onclick="sort_table(data, 9, asc10); asc10 *= -1; asc11 = 1; asc12 = 1;">Dest.</th>
				<th align="center" onclick="sort_table(data, 10, asc11); asc11 *= -1; asc12 = 1; asc13 = 1;">Offdock Name</th>
				<th align="center" onclick="sort_table(data, 11, asc12); asc12 *= -1; asc13 = 1; asc14 = 1;">Rotation</th>
				<th align="center" onclick="sort_table(data, 12, asc13); asc13 *= -1; asc14 = 1; asc15 = 1;">Vessel Name</th>
				<th align="center" onclick="sort_table(data, 13, asc14); asc14 *= -1; asc15 = 1; asc16 = 1;">Master BL</th>
				<th align="center" onclick="sort_table(data, 14, asc15); asc15 *= -1; asc16 = 1; asc17 = 1;">Sub BL</th>
				<th align="center" onclick="sort_table(data, 15, asc16); asc16 *= -1; asc17 = 1; asc18 = 1;">Seal</th>
				<th align="center" onclick="sort_table(data, 16, asc17); asc17 *= -1; asc18 = 1; asc1 = 1;">Size</th>
				<th align="center" onclick="sort_table(data, 17, asc18); asc18 *= -1; asc1 = 1; asc2 = 1;">Height</th>
			</tr>
		</thead>
		<tbody id="data">
		
		<?php
			
			include("mydbPConnection.php");
			include("dbConection.php");
			include("dbOracleConnection.php");	
			$totcontainerNo="";
			$totContQute="";
			$t20 = 0;
			$t40 = 0;
			$t45 = 0;
			
			if(count($rtnContainerList)>0)
			{
			$len=count($rtnContainerList);
            $j=0;
            for($i=0;$i<$len;$i++){
			 $id=$rtnContainerList[$i]['id'];
			// echo "select  group_concat(igm_supplimentary_detail.BL_No) as sub_bl from igm_supplimentary_detail where igm_detail_id=$id";
			 
			 $sql=mysqli_query($con_cchaportdb,"select  group_concat(igm_supplimentary_detail.BL_No) as sub_bl from igm_supplimentary_detail where igm_detail_id=$id");
			 $rtnSubBl=mysqli_fetch_object($sql);
			 $subBl=$rtnSubBl->sub_bl;
			 $subBl=str_replace(",",", ",$subBl);
			// echo $subBl;
			 $j++;
			 $containerNo1=$rtnContainerList[$i]['cont_number'];
			 $rotaionNo=$rtnContainerList[$i]['Import_Rotation_No'];
			 
			//echo $rtnContainerList[$i]['cont_number']."-".$containerNo;			
			
			
		
			/*$strYardPositon = "select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block,time_move from (
			select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,time_out as fcy_time_out,time_move 
			from inv_unit a
				inner join 
			inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
					inner join argo_carrier_visit h ON h.gkey = inv_unit_fcy_visit.actual_ib_cv
					inner join
				argo_visit_details i ON h.cvcvd_gkey = i.gkey
					inner join
				vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rotaionNo' and a.id='$containerNo1'
			) as  tmp";*/

			$strYardPositon = "select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,
			time_out as fcy_time_out,time_move 
			from inv_unit a
			inner join 
			inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
			inner join argo_carrier_visit h ON h.gkey = inv_unit_fcy_visit.actual_ib_cv
			inner join
			argo_visit_details i ON h.cvcvd_gkey = i.gkey
			inner join
			vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey 
			where ib_vyg='$rotaionNo' and a.id='$containerNo1'";
			$sqlYardPosition=oci_parse($con_sparcsn4_oracle,$strYardPositon);
			oci_execute($sqlYardPosition);
		
			
			
			$fcy_time_out = "";
			$time_move = "";
			$yard = "";
			$block = "";
			$fcy_time_in = "";
			$fcy_last_pos_slot = "";
			$fcy_position_name = "";
			while(($rtnYardPosition = oci_fetch_object($sqlYardPosition)) !=false)
			{
			

				$fcy_time_out = $rtnYardPosition->FCY_TIME_OUT;
				$time_move = $rtnYardPosition->TIME_MOVE;
				$fcy_time_in = $rtnYardPosition->FCY_TIME_IN;
				$fcy_last_pos_slot = $rtnYardPosition->FCY_LAST_POS_SLOT;
				$fcy_position_name = $rtnYardPosition->FCY_POSITION_NAME;

				$yardStr="select ctmsmis.cont_yard('$fcy_last_pos_slot') as yard";
				$yardNo="";
				$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
				$yardRes=mysqli_fetch_object($yardQuery);
				$yardNo=$yardRes->yard;
				$blockStr="select ctmsmis.cont_block('$fcy_last_pos_slot','$yardNo') as block";
				$blockNo="";
				$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
				$blockRes=mysqli_fetch_object($blockQuery);
				$blockNo=$blockRes->block;
			}
			//echo $rtnYardPosition->fcy_time_in."<hr>";
			$rot  = $rtnContainerList[$i]['Import_Rotation_No'];
			if($rtnContainerList[$i]['cont_size']==20)	
				$t20 = $t20+1;
			else if($rtnContainerList[$i]['cont_size']==40)	
				$t40 = $t40+1;
			else if($rtnContainerList[$i]['cont_size']==45)	
				$t45 = $t45+1;
		?>
			
			    <tr <?php if(strtoupper($rtnContainerList[$i]['cont_number'])==strtoupper($containerNo)) { ?> class="pinkLight" <?php } else { ?> class="gridLight" <?php } ?>>
					<td align="center"><?php echo $j; ?></td>
					<td align="center"><?php print($fcy_time_out); ?></td>
					<td align="center"><?php print($time_move); ?></td>
					<td align="center" ><?php print($rtnContainerList[$i]['cont_number']); ?></td>
					<td align="center" ><?php if($yardNo) print($yardNo.", ".$blockNo);  ?></td>
					<td align="center"><?php if($fcy_time_in=="") print($fcy_last_pos_slot."<font color='blue' size='2'><i> On_Vessel</i></font>"); else if($fcy_last_pos_slot=="" or strtoupper($fcy_last_pos_slot)=="TIP") { print($fcy_last_pos_slot." ".$fcy_position_name);} else  print($fcy_last_pos_slot); ?></td>
					<td align="center"> <?php print($rtnContainerList[$i]['cont_iso_type']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_status']); ?></td>
					<td align="center"><?php print($fcy_time_in); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['off_dock_id']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['offdock_name']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['Import_Rotation_No']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['vsl_name']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['BL_No']); ?></td>
					<td align="center" ><?php print($subBl); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_size']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_height']); ?></td>
					
				</tr>
			
		<?php	
			
			if($totcontainerNo!="")
			{
				$totcontainerNo=$totcontainerNo.", ".$containerNo1;
				$totContQute = $totContQute.",'".$containerNo1."'";
			}
			else
			{
				$totcontainerNo=$containerNo1;
				$totContQute="'".$containerNo1."'";
			}			
				
			
			}	
		//}
			
			$strDtSum = "";
			if(!is_null($rot)){
				$strDtSum = "SELECT NVL (cast( time_move as date),'') AS time_move,COUNT(a.id) AS totcont
				FROM inv_unit a
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey
				INNER JOIN argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv OR h.gkey = a.cv_gkey)
				INNER JOIN argo_visit_details i ON h.cvcvd_gkey = i.gkey
				INNER JOIN vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey 
				WHERE ib_vyg='$rot' AND a.id IN($totContQute)
				GROUP BY cast(time_move as date)";
			}
			//echo $strDtSum;
			$resDTSum = oci_parse($con_sparcsn4_oracle,$strDtSum);	
			oci_execute($resDTSum);	
		?>
		</tbody>
		<tr><td colspan="16" align="center"><?php echo "Total Container: ". @$j;?></td></tr>
		<tr><td colspan="16" align="left"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr>
		<tr>
			<td colspan="8" align="right" valign="top">
				<table>
					<tr class="gridDark"><td colspan="2" align="center">Date Wise Summary</td>
					<tr class="gridDark"><td style="padding:5px;">Date</td><td style="padding:5px;">Total Container</td>
				<?php 
					while(($rowDTSum = oci_fetch_object($resDTSum)) !=false){
				?>	
					<tr class="gridLight"><td style="padding:5px;"><?php echo $rowDTSum->TIME_MOVE;?></td><td style="padding:5px;" align="right"><?php echo $rowDTSum->TOTCONT;?></td>
				<?php 
					}
				?>	
				</table>
			</td>
			<td colspan="8" align="left" valign="top">
				<table>
					<tr class="gridDark"><td colspan="2" align="center">Size Wise Summary</td>
					<tr class="gridDark"><td style="padding:5px;">Size</td><td style="padding:5px;">Total Container</td>				
					<tr class="gridDark"><td style="padding:5px;">20'</td><td style="padding:5px;" align="right"><?php echo $t20;?></td>				
					<tr class="gridDark"><td style="padding:5px;">40'</td><td style="padding:5px;" align="right"><?php echo $t40;?></td>				
					<tr class="gridDark"><td style="padding:5px;">45'</td><td style="padding:5px;" align="right"><?php echo $t45;?></td>				
				</table>
			</td>
		</tr>
		
	</table>
	</div>
	  <a style="padding-right: 10%; color: red;" id="back2Top" onclick="getFocus()" title="Back to top" href="#"><!--&#10148;--> <b>Back to top</b></a>
	
</TD></TR>
<br/>

<?php 
	}
mysqli_close($con_sparcsn4);
mysqli_close($con_cchaportdb);
?>
</table>
</div>

</section>