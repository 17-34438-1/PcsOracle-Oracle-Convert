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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/monthlyAssignmentReport'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">C&F <span class="required">*</span></span>
										<input type="text" name="cnf" id="cnf" class="form-control" list="cnfList">

                                        <?php
                                            include("dbConection.php");
                                            $cnfQuery = "SELECT DISTINCT ref_bizunit_scoped.name  AS cnf
                                            FROM sparcsn4.ref_bizunit_scoped 
                                            INNER JOIN sparcsn4.inv_goods  ON ref_bizunit_scoped.gkey = inv_goods.consignee_bzu
                                            WHERE ref_bizunit_scoped.name  IS NOT NULL";
                                            $rslt_cnfQuery = mysqli_query($con_sparcsn4,$cnfQuery);
                                        ?>

                                        <datalist id="cnfList">
                                            <?php
                                            while($rslt_cnfInfo = mysqli_fetch_object($rslt_cnfQuery))
                                            {
                                            ?>
                                            <option value="<?php echo $rslt_cnfInfo->cnf; ?>"></option>
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
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Month <span class="required">*</span></span>
										<select name="month" id="month" class="form-control">
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
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