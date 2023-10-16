
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
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header>
									<div class="form-group" align="center"><b>GATE OUT BY VERIFICATION </b></div>
									<div class="panel-body" align="center">
									    <form name= "myForm" action="<?php echo site_url("GateController/gateOutView");?>" method="post">

					
									<div class="form-group">
										<label class="col-md-3 control-label">&nbsp;</label>
										<div class="col-md-6">		
											<div class="input-group mb-md">
												<span class="input-group-addon span_width" style="width:150px;">Verify No: <span class="required">*</span></span>
													<input type="text" style="width:130px;" id="verifyNo" name="verifyNo" value=""/>		
													</div>
												</div>
											
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
														<!--input type="submit" value="Save" name="save" class="login_button"-->
	
													</div>													
												</div>
												</form>
												<div class="row">
													<div class="col-sm-12 text-center">
														
													</div>
												</div>
											</div>	
											</br>
											
				 <form name= "myForm" action="<?php echo site_url("GateController/chalan");?>" method="post" target="_blank">
   
						<input type="hidden" style="width:140px;" id="verifyNo" name="verifyNo" value="<?php echo $verifyNo; ?>"> 
						   
							<table cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" >
								   <?php if($tableFlag==1){?>
							<tr><td colspan="12" align="center"> <h4><span><nobr><b><?php echo $tableTitle; ?></b></nobr></span> </h4></td></tr>
								<tr class="gridDark" style="height:35px;" >
							</table >	
							<table cellspacing="1" cellpadding="1" align="center" border="1">	
								<font size="15">
									<th>SL</th>
									<th><nobr>Rotation No</nobr></th>
									<th>Cont No</th>
									<th><nobr>Rec.Pack<nobr></th>
									<th><nobr>flt pack<nobr></th>
									<th><nobr>Shed Lock<nobr></th>
									<th><nobr>Lock1st<nobr></th>
									<th><nobr>PackNo.</nobr></th>
									<th><nobr>Pack Dr.</nobr></th>
									<th><nobr>Weight</nobr></th>
									<th><nobr>Notify Name</nobr></th>
									<th><nobr>Consignee</nobr></th>

									</font>
								</tr>
								
							  <?php
							   
								for($i=0;$i<count($verifyStatusList);$i++) { 				
								?>
								  <tr class="gridLight">
								  <td>
								   <?php echo $i+1;?>
								  </td>
								  <td align="center">
								   <?php echo $verifyStatusList[$i]['import_rotation']?>
								  </td>
								  <td align="center">
								   <?php echo $verifyStatusList[$i]['cont_number']?>
								  </td>
								  <td align="center">
								   <?php echo $verifyStatusList[$i]['rcv_pack']?>
								  </td>
								  <td align="center">
								   <?php echo $verifyStatusList[$i]['flt_pack']?>
								  </td> 
								   <td align="center">
								   <?php echo $verifyStatusList[$i]['shed_loc']?>
								  </td> 
								 <td align="center">
								   <?php echo $verifyStatusList[$i]['loc_first']?>
								 </td>   
								 <td align="center">
								   <?php echo $verifyStatusList[$i]['Pack_Number']?>
								 </td>   

								 <td align="center">
								   <?php echo $verifyStatusList[$i]['Pack_Description']?>
								 </td>   

								 <td align="center">
								   <?php echo $verifyStatusList[$i]['weight']?>
								 </td>   

								 <td align="center">
								   <?php echo $verifyStatusList[$i]['Notify_name']?>
								 </td>   

								 <td align="center">
								   <?php echo $verifyStatusList[$i]['Consignee_name']?>
								 </td>     
								 
								 </tr>
								 <tr>
									<input type="hidden" style="width:140px;" id="notifyName" name="notifyName" value="<?php echo $verifyStatusList[$i]['Notify_name'] ?>"> 
									<input type="hidden" style="width:140px;" id="notifyAddress" name="notifyAddress" value="<?php echo $verifyStatusList[$i]['Notify_address'] ?>"> 
								 </tr>
								 
								<tr>
								 <td colspan="15" align="center">
								  <input type="submit" value="INVOICE" class="mb-xs mt-xs mr-xs btn btn-success"/>   
								  
								</td>
							  </tr>
								 
								 <?php } ?>	 
								 <?php } ?>
								</table>
								<?php if($tableFlag==1) {?>
								<table align="center"><tr><th><font size= "2"; color="green">Truck Wise Goods Details</font></th><tr></table>
								<table  align="center" width=80% border="1" style="font-size:12px" > 
										<thead style="">
											<tr >		
												<th align="center" ><nobr>TRUCK NO</nobr></th>
												<th align="center" >DESCRIPTION OF GOODS</th>
												<th align="center" >QUANTITY</th>
												<th align="center" >REMARKS</th>						
											</tr>
										</thead>
										<tbody>
										 <?php       
										for($i=0;$i<count($result3);$i++) { 
										 ?>
										 <tr class="" > 
										  
										  <td align="center">
										   <?php echo $result3[$i]['truck_id']?>
										  </td>
										  <td align="left">
										   <?php echo $goodsDes?>
										  </td>
										  <td align="center">
										   <?php echo $result3[$i]['delv_pack']?>
										  </td>
										  <td align="center">
										   <?php echo $result3[$i]['remarks']?>
										  </td>
										 

										</tr>
										 <?php
										}
									   ?>
									</tbody>
								</table>
								<?php }?>
								
								
								
							  </form>
									</div>
								</section>
						
							</div>
						</div>	
					<!-- end: page -->
				</section>
			</div>