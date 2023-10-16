
<script type="text/javascript">
    function validate()
	{
		if(document.stuffingContainerMloSearchForm.search_by.value == "cont_no")
		{
			if(document.stuffingContainerMloSearchForm.cont_no.value == "")
			{
				alert( "Please provide a container no!" );
				document.stuffingContainerMloSearchForm.cont_no.focus() ;
				return false;
			}
			if(document.stuffingContainerMloSearchForm.stuffing_date_mlo.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerMloSearchForm.stuffing_date_mlo.focus() ;
				return false;
			}
		}
		
		else if(document.stuffingContainerMloSearchForm.search_by.value == "offdock")
		{
			if(document.stuffingContainerMloSearchForm.offdock.value == "")
			{
				alert( "Please provide an offdock!" );
				document.stuffingContainerMloSearchForm.offdock.focus() ;
				return false;
			}
			if(document.stuffingContainerMloSearchForm.stuffing_date_mlo.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerMloSearchForm.stuffing_date_mlo.focus() ;
				return false;
			}
		}
	}
	
	function search_criteria(search_by)
	{
//		alert("ok");
		if(document.stuffingContainerMloSearchForm.search_by.value == "")
		{
			alert( "Please provide a value!" );
			document.stuffingContainerMloSearchForm.search_by.focus() ;
			return false;
		}
		else
		{
			if(search_by == "cont_no")
			{
				stuffing_date_mlo.disabled=false;
				cont_no.disabled=false;
				offdock.disabled=true;
				document.stuffingContainerMloSearchForm.offdock.value="";
				document.stuffingContainerMloSearchForm.stuffing_date_mlo.value="";
			}
			else if(search_by == "offdock")
			{
				stuffing_date_mlo.disabled=false;
				cont_no.disabled=true;
				offdock.disabled=false;
				document.stuffingContainerMloSearchForm.offdock.value="";
				document.stuffingContainerMloSearchForm.stuffing_date_mlo.value="";
			}
		}
	}
	function mydate1()
		{
			//alert("dsds");
			//document.getElementById("stuffing_date_mlo").value="";
		d=new Date(document.getElementById("stuffing_date_mlo").value);
		//document.getElementById("stuffing_date_mlo").value="";
		
		$stuffing_date_mlo = document.getElementById("stuffing_date_mlo").value;
		$dateRep = str_replace('/', '-', $stuffing_date_mlo);
		$admissionDate = date('Y-m-d', strtotime($dateRep));
		
		document.getElementById("stuffing_date_mlo").value=$admissionDate;
		//alert(d);
		//dt=d.getDate();
		//mn=d.getMonth();
		//mn++;
		//yy=d.getFullYear();
		
		//newDate = d.setDate(15);
		//newMonth = d.setMonth(11);
		//newYear = d.setFullYear(2020);
		//document.getElementById("stuffing_date_mlo").innerHTML = newYear+"/"+newMonth+"/"+newDate;
		//alert(yy+"-"+mn+"-"+dt);
		//document.getElementById("stuffing_date_mlo").value="10/10/2020";		
		//document.getElementById("stuffing_date_mlo").value=dt+"/"+mn+"/"+yy;
		//document.getElementById("ndt").hidden=false;
		//document.getElementById("dt").hidden=true;
		}
</script> 
 <?php 
 $org_Type_id = $this->session->userdata('org_Type_id');
 $login_id = $this->session->userdata('login_id');

 ?>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							</h2>								
						</header>
						<div class="panel-body">
							<form class="form-horizontal form-bordered" name= "stuffingContainerMloSearchForm" 
							id="stuffingContainerMloSearchForm" 
							onsubmit="return(validate());" method="POST" action="<?php echo site_url('report/stuffingContainerListPerform') ?>"
							target="_blank">
							<input type="hidden" id="login_id_mlo" name="login_id_mlo" value="<?php echo $login_id; ?>" />
							
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<?php if($org_Type_id==57) { ?>
											<div class="input-group mb-md">														
												<span class="input-group-addon span_width">MLO</span>
												<select name="ship_mlo" id="ship_mlo" class="form-control">
													<option value="ALL">--Select--</option>
													<?php
														include(APPPATH.'views/FrontEnd/mydbPConnectionn4.php');
														include(APPPATH.'views/dbOracleConnection.php');
														//$con_sparcsn4=mysqli_connect("10.1.1.21", "sparcsn4", "sparcsn4","sparcsn4");
														//include("mydbPConnectionn4.php");
														$sql_mlo_list="SELECT r.id FROM ref_bizunit_scoped r       
														LEFT JOIN ( ref_agent_representation X       
														LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey 
														WHERE Y.id = '$login_id'";
														$rslt_mlo_Res=oci_parse($con_sparcsn4_oracle,$sql_mlo_list);
														oci_execute($rslt_mlo_Res);
														while(($mlo_list=oci_fetch_object($rslt_mlo_Res)) != false)
														{ 
													?>
														<option value="<?php echo $mlo_list->ID; ?>"><?php echo $mlo_list->ID; ?></option>
													<?php
													}
													?>
												</select>													
											</div>
										<?php } ?>
											<div class="input-group mb-md">														
												<span class="input-group-addon span_width">Search by</span>
												<select name="search_by" id="search_by" class="form-control" onchange="search_criteria(this.value);" required>
													<option value="">--Select--</option>
													<option value="offdock">Offdock</option>
													<option value="cont_no">Container</option>
												</select>														
											</div>
											<div class="input-group mb-md">														
												<span class="input-group-addon span_width">Offdock</span>
												<select name="offdock" id="offdock" class="form-control" disabled>
													<option value="ALL">--Select--</option>
													<?php
														include(APPPATH.'views/FrontEnd/mydbPConnectionn4.php');
														//$con_sparcsn4=mysqli_connect("10.1.1.21", "sparcsn4", "sparcsn4","sparcsn4");
														//include("mydbPConnectionn4.php");
														$sql_offdock_list="select code,name from ctmsmis.offdoc";
														$rslt_offdock_list=mysqli_query($con_sparcsn4,$sql_offdock_list);
														while($offdock_list=mysqli_fetch_object($rslt_offdock_list))
														{ 
													?>
														<option value="<?php echo $offdock_list->code; ?>"><?php echo $offdock_list->name; ?></option>
													<?php
													}
													?>
												</select>													
											</div>
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Container</span>
												<input type="text" name="cont_no" id="cont_no" class="form-control" placeholder="Container No" value="" disabled>
											</div>
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Date</span>
												<input type="date" name="stuffing_date_mlo" id="stuffing_date_mlo" class="form-control" value="<?php echo date("yy/m/d"); ?>">
											</div>
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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
		<!-- end: page -->
	</section>
	</div>