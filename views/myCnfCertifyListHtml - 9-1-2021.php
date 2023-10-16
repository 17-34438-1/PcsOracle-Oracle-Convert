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
            document.getElementById("txt_login").focus();
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/cnfCertifyList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php if(isset($msg)){echo $msg;} ?>
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_imp_cont_no" id="ddl_imp_cont_no" class="form-control" placeholder="Container No">
								</div>
								<div class="input-group mb-md">
									<span class=""> OR </span>
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

<div class="panel-body table-responsive">
<table border="0" width="100%" bgcolor="#FFFFFF">
	<TR align="center"><TD colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></TD></TR>
	<TR align="center"><TD colspan="6" ><h2><span ><?php if($containerNo!="") echo "Container No: ".$containerNo; else echo "BL No: ".$blNo ?></span> </h2></TD></TR>
	
	<TR><TD align="center">
	<div class="panel-body table-responsive">
	<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/cnfCertifyInsert'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none" width="100%">
		<!--table id="example" class="display" width="100%" cellspacing="0"-->
		<thead>
			<tr class="gridDark" style="cursor: pointer;" title="Click to sort">
				<th align="center" onclick="sort_table(data, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">SL</th>
				<th align="center" onclick="sort_table(data, 3, asc4); asc4 *= -1; asc5 = 1; asc6 = 1;">Container</th>
				<th align="center" onclick="sort_table(data, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">Assignment Type</th>
				<th align="center" onclick="sort_table(data, 4, asc5); asc5 *= -1; asc6 = 1; asc7 = 1;">Yard</th>
				<th align="center" onclick="sort_table(data, 5, asc6); asc6 *= -1; asc7 = 1; asc8 = 1;">Position</th>
				<th align="center" onclick="sort_table(data, 6, asc7); asc7 *= -1; asc8 = 1; asc9 = 1;">Size</th>
				<th align="center" onclick="sort_table(data, 7, asc8); asc8 *= -1; asc9 = 1; asc10 = 1;">Height</th>
				<th align="center" onclick="sort_table(data, 8, asc9); asc9 *= -1; asc1 = 1; asc11 = 1;">Discharge Time</th>
				<th align="center" onclick="sort_table(data, 9, asc10); asc10 *= -1; asc11 = 1; asc12 = 1;">Dest.</th>
				<th align="center" onclick="sort_table(data, 11, asc12); asc12 *= -1; asc13 = 1; asc14 = 1;">Rotation</th>
				<th align="center" onclick="sort_table(data, 12, asc13); asc13 *= -1; asc14 = 1; asc15 = 1;">Vessel Name</th>
				<th align="center" onclick="sort_table(data, 13, asc14); asc14 *= -1; asc15 = 1; asc16 = 1;">Master BL</th>
			</tr>
		</thead>
		<tbody id="data">
		
		<?php
			
			include("mydbPConnection.php");
			include("dbConection.php");
			$totcontainerNo="";
			$totContQute="";
			$t20 = 0;
			$t40 = 0;
			$t45 = 0;
			if($rtnContainerList) {
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
			
			
			/*$sqlYardPosition=mysql_query("select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select cont_block(fcy_last_pos_slot,yard)) as block from (
			select fcy_time_in,fcy_last_pos_slot,fcy_position_name,(select cont_yard(fcy_last_pos_slot)) as yard,fcy_time_out from mis_inv_unit where id='$containerNo1' and vsl_visit_dtls_ib_vyg='$rotaionNo'
			) as  tmp",$conn);*/
			$strYardPositon = "select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block,time_move from (
			select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,time_out as fcy_time_out,time_move 
			from inv_unit a
				inner join 
			inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
					inner join argo_carrier_visit h ON h.gkey = inv_unit_fcy_visit.actual_ib_cv
					inner join
				argo_visit_details i ON h.cvcvd_gkey = i.gkey
					inner join
				vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rotaionNo' and a.id='$containerNo1'
			) as  tmp";
			$sqlYardPosition=mysqli_query($con_sparcsn4,$strYardPositon);
			
			/*echo "select fcy_time_in,fcy_last_pos_slot,fcy_position_name,yard,fcy_time_out,(select ctmsmis.cont_block(fcy_last_pos_slot,yard)) as block from (
select time_in as fcy_time_in,last_pos_slot as fcy_last_pos_slot,last_pos_name as fcy_position_name,ctmsmis.cont_yard(last_pos_slot) as yard,time_out as fcy_time_out from inv_unit a
	inner join 
inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=a.gkey
        inner join
 argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv or h.gkey = a.cv_gkey)
        inner join
    argo_visit_details i ON h.cvcvd_gkey = i.gkey
        inner join
    vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey where ib_vyg='$rotaionNo' and a.id='$containerNo1'
) as  tmp"."<hr>";*/
			
			$rtnYardPosition=mysqli_fetch_object($sqlYardPosition);
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
					<td align="center" ><?php print($rtnContainerList[$i]['cont_number']); ?><input type="hidden" id="cont<?php echo $i; ?>" name="cont<?php echo $i; ?>" value="<?php echo $rtnContainerList[$i]['cont_number']; ?>"/></td>

					<?php
						$cont = $rtnContainerList[$i]['cont_number'];
						$rotation = $rtnContainerList[$i]['Import_Rotation_No'];
						$AssigntypeQuery=mysqli_query($con_cchaportdb,"SELECT mfdch_value FROM assignment_request_data WHERE cont_number='$cont' AND rotation='$rotation'");
						$assignTypeByCont=mysqli_fetch_object($AssigntypeQuery);
						if($assignTypeByCont != null){
							$type = $assignTypeByCont->mfdch_value;
							
							$gkeyQuery=mysqli_query($con_sparcsn4,"SELECT gkey FROM sparcsn4.config_metafield_lov WHERE mfdch_value='$type'");
							$gkeyValue = mysqli_fetch_object($gkeyQuery);
							$gkey = $gkeyValue->gkey;
						}
					?>

					<td align="center">
						<select class="form-control" id="assignType<?php echo $i; ?>" name="assignType<?php echo $i; ?>">
							<?php
								for($k=0;$k<COUNT($assignType);$k++){
							?>
								<option value="<?php echo $assignType[$k]['gkey']; ?>" <?php if($assignTypeByCont != null){if($gkey == $assignType[$k]['gkey']){echo "selected";}} ?>>
									<?php 
										// if($assignTypeByCont != null){
										// 	echo $type;
										// }else{
											echo $assignType[$k]['mfdch_value'];
										// } 
									?>
								</option>
							<?php
								}
							?>
						</select>
					</td>
					<td align="center" ><?php if($rtnYardPosition->yard) print($rtnYardPosition->yard.", ".$rtnYardPosition->block);  ?></td>
					<td align="center"><?php if($rtnYardPosition->fcy_time_in=="") print($rtnYardPosition->fcy_last_pos_slot."<font color='blue' size='2'><i> On_Vessel</i></font>"); else if($rtnYardPosition->fcy_last_pos_slot=="" or strtoupper($rtnYardPosition->fcy_last_pos_slot)=="TIP") { print($rtnYardPosition->fcy_last_pos_slot." ".$rtnYardPosition->fcy_position_name);} else  print($rtnYardPosition->fcy_last_pos_slot); ?></td>
					<td align="center"> <?php print($rtnContainerList[$i]['cont_size']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['cont_height']); ?></td>
					<td align="center"><?php print($rtnYardPosition->fcy_time_in); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['off_dock_id']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['Import_Rotation_No']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['vsl_name']); ?></td>
					<td align="center"><?php print($rtnContainerList[$i]['BL_No']); ?></td>
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
				
			
			}	}
			
			$strDtSum = "SELECT IFNULL(DATE(time_move),'') AS time_move,COUNT(a.id) AS totcont
			FROM inv_unit a
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey
			INNER JOIN argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv OR h.gkey = a.cv_gkey)
			INNER JOIN argo_visit_details i ON h.cvcvd_gkey = i.gkey
			INNER JOIN vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey WHERE ib_vyg='$rot' AND a.id IN($totContQute)
			GROUP BY DATE(time_move)";
			//echo $strDtSum;
			$resDTSum = mysqli_query($con_sparcsn4,$strDtSum);			
		?>
		</tbody>
		<!-- <tr><td colspan="16" align="center"><?php echo "Total Container: ". $j;?></td></tr> -->
		<!-- <tr><td colspan="16" align="left"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr> -->
		<!-- <tr>
			<td colspan="8" align="right" valign="top">
				<table>
					<tr class="gridDark"><td colspan="2" align="center">Date Wise Summary</td>
					<tr class="gridDark"><td style="padding:5px;">Date</td><td style="padding:5px;">Total Container</td>
				<?php 
					while($rowDTSum = mysqli_fetch_object($resDTSum)){
				?>	
					<tr class="gridLight"><td style="padding:5px;"><?php echo $rowDTSum->time_move;?></td><td style="padding:5px;" align="right"><?php echo $rowDTSum->totcont;?></td>
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
		</tr> -->
		
		
	</table>
	</div>
	
</TD>
</TR>
<tr>
	<td align="center" colspan="12">
		<input type="hidden" name="total" id="total" value="<?php echo $i;?>"/>
		<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rtnContainerList[0]['Import_Rotation_No']; ?>"/>
		<input type="hidden" name="blNo" id="blNo" value="<?php echo $rtnContainerList[0]['BL_No']; ?>"/>
		<input type="hidden" name="ignDetailId" id="ignDetailId" value="<?php echo $rtnContainerList[0]['id']; ?>"/>
		<?php
			if($assignTypeByCont != null){
		?>
				<input type="submit" name="save" id="update" value="Update" class="btn btn-success">
		<?php
			}else{
		?>
				<input type="submit" name="save" id="save" value="Save" class="btn btn-success">
		<?php
			}
		?>
	</td>
</tr>
</form>
<br/>

<?php 
mysqli_close($con_sparcsn4);
mysqli_close($con_cchaportdb);
?>
</table>
</div>

</section>