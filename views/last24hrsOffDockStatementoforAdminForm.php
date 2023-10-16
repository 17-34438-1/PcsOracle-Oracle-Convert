<script type="text/javascript">
    function validate()
	{
		if(document.stuffingContainerSearchForm.search_by.value == "cont_no")
		{
			if(document.stuffingContainerSearchForm.cont_no.value == "")
			{
				alert( "Please provide a container no!" );
				document.stuffingContainerSearchForm.cont_no.focus() ;
				return false;
			}
			if(document.stuffingContainerSearchForm.stuffing_date.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerSearchForm.stuffing_date.focus() ;
				return false;
			}
		}
		
		else if(document.stuffingContainerSearchForm.search_by.value == "offdock")
		{
			if(document.stuffingContainerSearchForm.offdock.value == "")
			{
				alert( "Please provide an offdock!" );
				document.stuffingContainerSearchForm.offdock.focus() ;
				return false;
			}
			if(document.stuffingContainerSearchForm.stuffing_date.value == "")
			{
				alert( "Please provide stuffing date!" );
				document.stuffingContainerSearchForm.stuffing_date.focus() ;
				return false;
			}
		}
	}
	
	
</script> 

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
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							<!--/h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" name="stuffingContainerSearchForm" id="stuffingContainerSearchForm"
								action="<?php echo site_url('report/last24hrsOffDockStatementList') ?>" onsubmit="return(validate());">
							
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Offdock :</span>
											<select name="offdock" id="offdock" class="form-control" onchange="changeTextBox(this.value);">
												<option value="">--Select--</option>
												<?php
													include("FrontEnd/mydbPConnection.php");
													$sql_offdock_list="SELECT * FROM users WHERE org_Type_id=6 and id NOT BETWEEN 2637 and 3526";
													$rslt_offdock_list=mysqli_query($con_cchaportdb,$sql_offdock_list);
													while($offdock_list=mysqli_fetch_object($rslt_offdock_list))
													{
												?>
													<option value="<?php echo $offdock_list->login_id; ?>"><?php echo $offdock_list->u_name; ?></option>
												<?php
													}
												?>
											</select> 
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Statement Date</span>
											<input type="date" name="stuffing_date" id="stuffing_date" class="form-control" value="">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<!--button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button-->
										<input type="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success"/>
									</div>													
								</div>
							</form>
							<?php if($flag==1){ ?>
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center" rowspan="2">Sl</th>
										<th class="text-center" rowspan="2">Date</th>
										<th class="text-center" rowspan="2">Capacity</th>
										<th class="text-center" rowspan="2">Imp.Cont Lying</th>
										<th class="text-center" rowspan="2">Exp.Cont Lying</th>
										<th class="text-center" rowspan="2">Emty.Cont Lying</th>
										<th class="text-center" rowspan="2">Total</th>
										<th class="text-center" rowspan="2">Last 24hrs Exp. stuffed</th>
										<th class="text-center" colspan="2">Port To Deport</th>
										<th class="text-center" colspan="2">Deport To Port</th>
										<th class="text-center" rowspan="2">Remarks</th>
										<th class="text-center" rowspan="2">Print</th>
									</tr>
									<tr>
										<th class="text-center" >Laden</th>
										<th class="text-center" >Empty</th>
										<th class="text-center" >Laden</th>
										<th class="text-center" >Empty</th>
									</tr>
								</thead>
								<tbody>
									<?php
										for($i=0;$i<count($offDock);$i++) {				
									?>
									<tr class="gradeX">
										<td align="center"> <?php echo $i+1;?> </td>
										<td align="center"> <?php echo $offDock[$i]['stmt_date']?> </td>
										<td align="center"> <?php echo $offDock[$i]['capacity']?> </td>
										<td align="center"> <?php echo $offDock[$i]['imp_lying']?> </td>
										<td align="center"> <?php echo $offDock[$i]['exp_lying']?> </td>
										<td align="center"> <?php echo $offDock[$i]['mty_lying']?> </td>
										<td align="center"> <?php echo $offDock[$i]['total_teus']?> </td>
										<td align="center"> <?php echo $offDock[$i]['last_24hrs']?> </td>
										<td align="center"> <?php echo $offDock[$i]['port_to_depo_laden']?> </td>
										<td align="center"> <?php echo $offDock[$i]['port_to_depo_mty']?> </td>
										<td align="center"> <?php echo $offDock[$i]['depo_to_port_laden']?> </td>
										<td align="center"> <?php echo $offDock[$i]['depo_to_port_mty']?> </td>
										<td align="center"> <?php echo $offDock[$i]['remarks']?> </td>
										<td align="center"> 
											<form action="<?php echo site_url('uploadExcel/last24hrsOffDocStatementPdf');?>" target="_blank" method="POST">
												<input type="hidden" name="akey2" id="akey2" value="<?php echo $offDock[$i]['akey'];?>">							
												<input type="hidden" name="offdockName" id="offdockName" value="<?php echo $offDock[$i]['update_by'];?>">							
												<input type="submit" title="Print" value="Print"  class="mb-xs mt-xs mr-xs btn btn-primary">							
											</form> 
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<?php } ?>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>