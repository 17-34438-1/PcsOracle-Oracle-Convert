
<script language="JavaScript">

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
<div class="content">
    <div class="content_resize_1">
      <div class="mainbar_1">
        <div class="article">						
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/certificationFormViewList'; ?>" id="myform" name="myform" onsubmit="return validate()">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
											<input type="text" name="ddl_imp_rot_no" id="txt_login" class="form-control" placeholder="Rotation No">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
											<input type="text" name="ddl_bl_no" id="txt_login" class="form-control" placeholder="BL No">
										</div>												
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<font color=""><b>
												<?php if(@$verify_number>0 or @$verify_num>0)
													{ echo "<font color='green'><b>VERIFY NUMBER IS ".$verify_num."</b></font>";} 			 
												else 
													{ echo $msg;}?>
												</b>
											</font>
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
Developed BY: Sourav Chakraborty
Software Developer
DataSoft Systems Bangladesh Ltd
******************************************************/

$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');

?>
<?php 
// if(@$unstuff_flag>0)			// uncomment if necessary	- 2021-01-09
if($unstuff_flag>=0)
{
?>
<div class="table-responsive" style="width:100%; height:500px; overflow-y:auto;">
<table border="0"  width="100%" bgcolor="#FFFFFF" align="center" 
	class="table table-bordered table-responsive table-hover table-striped mb-none">
	<!--<TR align="center"><TD colspan="6" ><h2><span ><?php echo $title; ?></span> </h2></TD></TR>-->
	
	
	<TR><TD align="center">
	
	
		<?php
			
			include("mydbPConnection.php");
			$totcontainerNo="";
			if($rtnContainerList) {
			$len=count($rtnContainerList);
			//echo "Length : ".$len;
            $j=0;
            for($i=0;$i<$len;$i++){?>
				
			<table border=0 cellspacing="2" cellpadding="1" bdcolor="#ffffff" 
				class="table table-bordered table-responsive table-striped mb-none">
				<?php
			 $id=@$rtnContainerList[$i]['igm_detail_id'];
			 $containerNo1=@$rtnContainerList[$i]['cont_number'];
			 $rotaionNo=@$rtnContainerList[$i]['Import_Rotation_No'];
			 $igm_supDtl_id=@$rtnContainerList[$i]['igm_sup_dtl_id'];
			 //$sql=mysql_query("select  group_concat(igm_supplimentary_detail.BL_No) as sub_bl from igm_supplimentary_detail where igm_detail_id=$id");
			 //$rtnSubBl=mysql_fetch_object($sql);						 
			 $j++;
			 //$sql=mysqli_query($con_cchaportdb,"select rcv_pack,marks_state,shift_name from shed_tally_info where igm_sup_detail_id=$igm_supDtl_id");
			 //$rtnData=mysqli_fetch_assoc($sql);	
			 
			//echo $rtnContainerList[$i]['cont_number']."-".$containerNo1;
			//echo $rtnContainerList[$i]['Import_Rotation_No']."-".$rotaionNo;
			
			include("dbConection.php");
			include("dbOracleConnection.php");
			
	

			/*$sqlYardPosition=mysqli_query($con_sparcsn4,"SELECT fcy_time_in,fcy_time_out FROM (
			SELECT time_in AS fcy_time_in,time_out AS fcy_time_out FROM inv_unit a
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey
			INNER JOIN argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv OR h.gkey = a.cv_gkey)
			INNER JOIN argo_visit_details i ON h.cvcvd_gkey = i.gkey
			INNER JOIN vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey WHERE ib_vyg='$rotaionNo' AND a.id='$containerNo1'
			) AS  tmp");*/

			$query1="SELECT fcy_time_in,fcy_time_out FROM (
				SELECT time_in AS fcy_time_in,time_out AS fcy_time_out FROM inv_unit a
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=a.gkey
				INNER JOIN argo_carrier_visit h ON (h.gkey = a.declrd_ib_cv OR h.gkey = a.cv_gkey)
				INNER JOIN argo_visit_details i ON h.cvcvd_gkey = i.gkey
				INNER JOIN vsl_vessel_visit_details ww ON ww.vvd_gkey = i.gkey
				WHERE ib_vyg='$rotaionNo' AND a.id='$containerNo1'
				)  tmp";

             $sqlYardPosition = oci_parse($con_sparcsn4_oracle, $query1);
             oci_execute($sqlYardPosition);
			
	

			// $rtnYardPosition=mysqli_fetch_assoc($sqlYardPosition);
			$rtnYardPosition = oci_fetch_object($sqlYardPosition);
		
			
		?>
		
		
					<tr class="gridLight">
						<th width="100px">Discharge Time</th><th>:</th><td><?php print($rtnYardPosition['FCY_TIME_OUT']); ?></td>
						<th width="100px">Vessel Name</th><th>:</th><td><?php print($rtnContainerList[$i]['Vessel_Name']); ?></td>
						<th>Rotation</th><th>:</th><td><?php print($rtnContainerList[$i]['Import_Rotation_No']); ?></td>
						
					</tr>
					<tr class="gridLight">
						<th width="100px">Container</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_number']);  ?></td>
						<th width="100px">Cont.Size</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_size']);  ?></td>
						<th width="100px">Cont.Height</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_height']);  ?></td>
					</tr>
					<tr class="gridLight">
						<!--th>Cont. Type</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_iso_type']); ?></td-->
						<th>BL No</th><th>:</th><td><?php print($rtnContainerList[$i]['BL_No']); ?></td>
						<th>Yard / Shed</th><th>:</th><td><?php print($rtnContainerList[$i]['shed_yard']);  ?></td>
						<th width="100px">Unstuffing Date</th><th>:</th><td><?php print($rtnContainerList[$i]['wr_date']);  ?></td>
					</tr>
					<tr class="gridLight">
						<th>Marks & Number</th><th>:</th><td><?php echo str_replace(',',', ',$rtnContainerList[$i]['Pack_Marks_Number']); ?></td>
						<th width="150px">Description of Goods</th><th>:</th><td><?php print($rtnContainerList[$i]['Description_of_Goods']);  ?></td>
						<th>Importer</th><th>:</th><td><?php print($rtnContainerList[$i]['Notify_name']); ?></td>
					</tr>
					<tr class="gridLight">	
						<th width="100px">Receive Pack</th><th>:</th><td><?php print($rtnContainerList[$i]['rcv_pack']); ?></td>
						<th width="100px">Pack Unit</th><th>:</th><td><?php print($rtnContainerList[$i]['rcv_unit']); ?></td>
					</tr>
					<tr align="center">
						<!-- <th colspan=4 >
							<form class="col-sm-12 text-center" action="<?php echo site_url('report/certificationListPdf/'.str_replace("/","_",$rtnContainerList[$i]['BL_No']).'/'.str_replace("/","_",$rtnContainerList[$i]['Import_Rotation_No']))?>" target="_blank" method="POST">						
								<button type="submit" class="btn btn-primary">PDF View</button>
							</form> 
						</th>
						<th colspan=5 >
							<form class="col-sm-12 text-center" action="<?php echo site_url('report/releaseOrderFormView')?>" target="_blank" 
							method="POST">			
								<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verify_num; ?>" />
								<button type="submit"class="btn btn-primary" >Release Order</button>
							</form> 
						</th> -->

						<th colspan="9">
							<div class="col-md-4">
								<form class="col-sm-12 text-center" action="<?php echo site_url('Report/certificationListPdf/'.str_replace("/","_",$rtnContainerList[$i]['BL_No']).'/'.str_replace("/","_",$rtnContainerList[$i]['Import_Rotation_No']))?>" target="_blank" method="POST">						
									<button type="submit" class="btn btn-primary" >PDF View</button>					
								</form>
							</div>

							<div class="col-md-4">
							<form class="col-sm-12 text-center" action="<?php echo site_url('Report/releaseOrderFormView')?>" target="_blank" 
							method="POST">			
								<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $ddl_imp_rot_no; ?>" />
								<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $ddl_bl_no; ?>" />
								<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verify_num; ?>" />
								<button type="submit"class="btn btn-primary" >Release Order</button>
							</form> 
							</div>

							<div class="col-md-4">
								<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#beUpload">B/E Upload</button>
							</div>
						</th>

					</tr>
					<!--tr class="gridLight">
						<th>Status</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_status']); ?></td>
						<th>Discharge Time</th><th>:</th><td><?php print($rtnYardPosition->FCY_TIME_IN); ?></td>
						<th>Dest.</th><th>:</th><td><?php print($rtnContainerList[$i]['off_dock_id']); ?></td>

					</tr>
					<tr class="gridLight">
					
						<th>Offdock Name</th><th>:</th><td><?php print($rtnContainerList[$i]['offdock_name']); ?></td>
						
						<th>Seal</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_seal_number']); ?></td>
					</tr>
					<tr class="gridLight">
						<th>Size</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_size']); ?></td>
						<th>Height</th><th>:</th><td><?php print($rtnContainerList[$i]['cont_height']); ?></td>

					</tr-->
		<?php	
			
			if($totcontainerNo!="")
				$totcontainerNo=$totcontainerNo.", ".$containerNo1;
			else
				$totcontainerNo=$containerNo1;
				
				mysqli_close($con_sparcsn4);
			
			
			
			

		?>
		<!--tr><td colspan="16" align="center"><?php echo "Total Container: ". $j;?></td></tr-->
		<!--<tr><td colspan="16" align="left"><?php if($totcontainerNo) echo  $totcontainerNo; else echo "&nbsp;"; ?></td></tr>-->
	</table>
	<br/>
	<!--form action="<?php echo site_url('report/lclAssignmentVerify');?>" method="POST" name="myChkForm" onsubmit="return(validate());">
		<input type="hidden"value="<?php echo  $verify_id?>" name="verify_id" style="width:200px;"/>
		<input type="hidden"value="<?php echo  $verify_num?>" name="verify_num" style="width:200px;"/>
		<input type="hidden"value="<?php echo  $ddl_imp_rot_no?>" name="verify_rot" style="width:200px;"/>
		<input type="hidden"value="<?php echo  $ddl_bl_no?>" name="verify_bl" style="width:200px;"/>
		<table border=0 cellspacing="2" cellpadding="1"  width="80%" bgcolor="#2AB1D6">
			
			<tr class="gridDark">
				<th>C&f License</th><th>:</th><td><input type="text" id="strCnfLicense" value="<?php echo  $rtnContainerList[$i]['cnf_lic_no']?>" name="strCnfLicense" onblur="getCnfCode(this.value)" style="width:200px;"/></td>
				<th>C&f Name</th><th>:</th><td><input type="text" id="strCnfCode" value="<?php echo $cnf_name;?>" name="strCnfCode" style="width:200px;"/></td>
			</tr>
			<tr class="gridLight" >
				<th>Agent DO</th><th>:</th><td ><input type="text"value="<?php echo  $rtnContainerList[$i]['agent_do']?>" name="strAgentDo" style="width:200px;"/></td>
				<th>DO Date</th><th>:</th>
					<td>
						<input type="text" id="strDoDate" value="<?php echo  $rtnContainerList[$i]['do_date']?>" name="strDoDate" style="width:200px;"/>
						<script>
							$(function() {
							 $( "#strDoDate" ).datepicker({
							  changeMonth: true,
							  changeYear: true,
							  dateFormat: 'yy-mm-dd', // iso format
							 });
							 });
						</script>
					</td>

			</tr>
			<tr class="gridDark">
				<th>BE No</th><th>:</th><td><input type="text" value="<?php echo  $rtnContainerList[$i]['be_no']?>" name="strBEno" style="width:200px;"/></td>
				<th>BE Date</th><th>:</th>
					<td>
						<input type="text" id="strBEdate" value="<?php echo  $rtnContainerList[$i]['be_date']?>" name="strBEdate" style="width:200px;"/>
						<script>
							$(function() {
							 $( "#strBEdate" ).datepicker({
							  changeMonth: true,
							  changeYear: true,
							  dateFormat: 'yy-mm-dd', // iso format
							 });
							 });
						</script>
					</td>
										
			</tr>
			<tr class="gridDark">
				
				<th>W/R UP TO DATE</th><th>:</th>
					<td>
						<input type="text" id="strWRdate" value="<?php echo  $rtnContainerList[$i]['wr_upto_date']?>" name="strWRdate" style="width:200px;"/>
						<script>
							$(function() {
							 $( "#strWRdate" ).datepicker({
							  changeMonth: true,
							  changeYear: true,
							  dateFormat: 'yy-mm-dd', // iso format
							 });
							 });
						</script>
					</td>
					<th>Tonnage Update</th><th>:</th><td><input type="text" value="<?php echo  $rtnContainerList[$i]['update_ton']?>" name="strTonUpdt" style="width:200px;"/></td>					
			</tr>
			<?php 
				if ($verify_num >0)
				{
					?>
					<tr style="background:#FFF"  align="center">
						<td colspan="6" align="center"><input type="submit" class="login_button" value="UPDATE"/></td>
					</tr>
			<?php
				}
				else{
					?>
					<tr style="background:#FFF"  align="center">
						<td colspan="6" align="center"><input type="submit" class="login_button" value="VERIFY"/></td>
					</tr>
					
			<?php 
				}
			?>
			
		</table>
	</form-->
	</table> <?php }
	}?>

	</TD></TR>
	<br/>
	<?php 
	mysqli_close($con_cchaportdb);
	oci_free_statement($sqlYardPosition);
	oci_close($con_sparcsn4_oracle);
	?>

</div>
<?php 
}
			else{
				echo "";
				
			}
?>

		 <!--</div>-->
		 </div>
         
		  </form>
          <div class="clr"></div>
        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <div class="clr"></div>
    </div>

	<!--   Modal Start   -->
	<div class="modal fade" id="beUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">BE Upload</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form action="<?php echo site_url('report/beFileUpload')?>" target="_blank" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="file" name="beFile" id="beFile"/>
				</div>
				<div class="modal-footer">
					<input type="submit" name="save" id="save" class="btn btn-success" value="Save">
				</div>
			</form>

			</div>
		</div>
	</div>
	<!--   Modal End   -->
	
  </div>
</section>
