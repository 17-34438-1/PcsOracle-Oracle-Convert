<script>
	function validate(){
		var cnf = document.getElementById("cnf").value;
		cnf = cnf.trim();
		//alert(cnf);
		if(cnf == "" || cnf == null){
			alert("Please Choose C&F!");
			return false;
		}
		//return false;
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/yearlyAssignmentReport'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">C&F <span class="required">*</span></span>
										<input type="text" name="cnf" id="cnf" class="form-control" list="cnfList">

                                        <?php
                                            include("dbConection.php");
											include("dbOracleConnection.php");
                                            $cnfQuery = "SELECT DISTINCT ref_bizunit_scoped.name  AS cnf
                                            FROM ref_bizunit_scoped 
                                            INNER JOIN inv_goods  ON ref_bizunit_scoped.gkey = inv_goods.consignee_bzu
                                            WHERE ref_bizunit_scoped.name  IS NOT NULL";




                                            $rslt_cnfQuery = oci_parse($con_sparcsn4_oracle,$cnfQuery);
                                        ?>

                                        <datalist id="cnfList">
                                            <?php

											while(($rslt_cnfInfo= oci_fetch_object($rslt_cnfQuery)) != false)
                                            {
                                            ?>
                                            <option value="<?php echo $rslt_cnfInfo->CNF; ?>"></option>
                                            <?php
                                            }
                                            ?>											
                                        </datalist>

									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Year <span class="required">*</span></span>
										<select name="year" id="year" class="form-control">
                                            <?php
												for($i=2015;$i<=date("Y");$i++){
											?>
												<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
											<?php
												}
											?>
                                        </select>
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>