<script>
	function confirmBillGeneration(){
		if(confirm("Do you want to generate Firework bill ?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
	<?php include('dbConection.php'); 
	include("dbOracleConnection.php");
	include("dbOracleBillingConnection.php");

	?>
	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr class="gridDark">
                                <th class="text-center">#Sl</th>
                                <th class="text-center">Rotation no</th>									
                                <th class="text-center">Vessel name</th>	
                                <th class="text-center">Service Date</th>								
                                <th class="text-center">Start time</th>								
                                <th class="text-center">Start date</th>	
                                <th class="text-center">Action</th>										
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tbl = "";
                                
                                for($i=0;count($result)>$i;$i++)
                                {

                            ?>  <tr>  
                                    <td align='center'> <?php echo $i+1; ?> </td>
                                    <td align='center'> <?php echo $result[$i]['rotation']; ?> </td>
                                    <td align='center'> <?php echo $result[$i]['vessel_name']; ?> </td>
                                    <td align='center'> <?php echo$result[$i]['service_date']; ?> </td>
                                    <td align='center'> <?php echo$result[$i]['start_time']; ?> </td>
                                    <td align='center'> <?php echo $result[$i]['start_date']; ?> </td>
                                    <td align='center'> 
										<?php
										if($result[$i]['bill_op_bill_st']==1)
										{
										?>
											<font color='green'><b>Bill Generated</b></font>
										<?php
										}
										else
										{										
											$sql_chkOaDate = "SELECT off_port_arr
											FROM vsl_vessel_visit_details
											WHERE ib_vyg='".$result[$i]['rotation']."' AND  off_port_arr is not null";
											$rslt_chkOaDate = oci_parse($con_sparcsn4_oracle,$sql_chkOaDate);
											oci_execute($rslt_chkOaDate);
											
											$oaDate = "";
											while(($row_chkOaDate = oci_fetch_object($rslt_chkOaDate))!=false)
											{
												$oaDate = $row_chkOaDate->OFF_PORT_ARR;
											}
																				
											if($oaDate == "" or $oaDate == null)
											{
										?>
												<font color='red'><b>Outer Anchorage Date not found</b></font>
										<?php
											}
											else											
											{
												// check dollar date
												$sql_dollarRate = "SELECT COUNT(rate) as countRate FROM bil_currency_exchange_rates
												WHERE effective_date=to_date('$effectiveDate','yyyy-mm-dd')";
												// echo $sql_dollarRate;
												$rslt_dollarRate = oci_parse($con_billing_oracle,$sql_dollarRate);
												oci_execute($rslt_dollarRate);
												$result=array();
												$$isExist =oci_fetch_all($rslt_dollarRate, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);
												oci_free_statement($rslt_dollarRate);
												
												if($isExist == 0)
												{
										?>
													<a class="btn btn-danger" href="<?php echo site_url('Vessel/usdtoBdtExchangeRateform/')?>" 
													style="color:white">
														<u>Rate Setting</u>
													</a>
										<?php
												}
												else
												{
										?>
													<a href="<?php echo site_url("VesselBill/Generate_Fireman_Bill/".str_replace("/","_",$result[$i]['rotation']))?>" class="btn btn-primary" 
													onclick="return confirmBillGeneration();">
														Bill Generate
													</a>										
										<?php
												}
											}
										}
										?>
                                    </td>
                                </tr>    


                            <?php } ?>
                        </tbody>
                    </table>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>