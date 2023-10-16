<script>
function subm(f, newtarget) {
    document.myform.target = newtarget;
    f.submit();
	//var submitValue = document.getElementById('submit').value;
	//document.getElementById('submit').value = submitValue;
}


function validateForward(generateSt)
	{
		//alert(generateSt);
		var sectionId = document.getElementById('sectionId').value;
		//alert(billing_statement_generate_st);
		//alert(sectionId);
		if (sectionId=='19')
		{
			if(generateSt=='1')
			{
				if (confirm("Do you want to forward to shipping section?") == true)
					{
						return true ;
					}
				else
					{
						return false;
					}
			}
			else
			{
				alert("Please, generate the statement.");
				return false;
			}
		}
		if (sectionId=='1')
		{
			if (confirm("Do you want to forward to Accounts section?") == true)
				{
					return true ;
				}
			else
				{
					return false;
				}
		}
	}
	
	
	
 	function validate()
	{
		//alert("ok");
		var impRot = document.getElementById('impRot').value.trim();
		//var submitVal = document.getElementById('submit').value;
		
			if(impRot!='')
			{
				/* if (confirm("Rotation: "+impRot+"; MLO wise handling statement forward to Bill section, confirm?") == true)
					{ */
						return true ;
					/* }
				else
					{
						return false;
					} */
				
			}
			else
			{
				alert("Please, Enter valid rotation number");
				return false;
				
			}
				
	
	} 
	
	
</script>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>

    <div class="row">
        <div class="col-lg-12">
		<?php if($this->session->userdata('org_Type_id')=='30') { ?>
            <section class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php echo $msg;?>
                        </div>
                    </div>
                    <form  method="POST"  action="<?php echo site_url('Vessel/qgcContForwardNew'); ?>" id="myform" name="myform"  onsubmit="return validate()">

                        <div class="form-group">
                            <label class="col-md-2 control-label">&nbsp;</label>
                            <!--div class="col-md-8">                                
                                <div class="input-group mb-md">
                                    <?php echo $msg; ?>
                                </div>									
							</div-->

                            <div class="col-md-8">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Rotation No <span
                                            class="required">*</span></span>
                                    <input style="width:400px" type="text" name="impRot" id="impRot"  class="form-control" >
                                </div>
                            </div>

                            <div class="col-md-offset-3 col-md-2 mt-4">
                                <div class="radio-custom radio-success">

                                    <button type="submit" id="submit" name="submit" value="show"
                                        onclick="subm(this.form,'_blank');"
                                        class=" btn btn-success login_button">View</button>
                                    <!--label for="radioExample3">Show</label-->
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="radio-custom radio-success">
                                    <!--input type="radio" id="btnStatus" name="btnStatus" value="confirm"-->
                                    <!--label for="radioExample3">Confirm</label-->
                                    <button type="submit" id="btnSubmit" name="submit" value="confirm"
                                        onclick="return confirm('Do you want to forward this statement to Bill section, confirm?')"
                                        class=" btn btn-success login_button">Forward</button>
                                </div>
                            </div>

                            <br /><br />

                            <!--div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div-->
                            <!--div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div-->

                            <!--div class="row">
								<div class="col-sm-12 text-center">
									
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
								</div>													
							</div-->
                            <div class="row">
                                <div class="col-sm-12 text-center">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
					<?php } ?>

            <section class="panel">
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr class="gridDark">
                                <th class="text-center">Sl.NO</th>
                                <th class="text-center">Rotation</th>
                                <th class="text-center">Vessel Name</th>
                                <th class="text-center">ForwardInfo</th>
							<?php if($org_Type_id!=30) { ?>	
                                <th class="text-center">View</th>
                                <th class="text-center">Action</th>
                                <th class="text-center">Action</th>
								
							<?php } ?>	
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
							include("dbOracleConnection.php");	
								for($i=0;$i<count($rslt_qgcFwdList);$i++) 
								{
									$rotationNo="";
									$rotationNo=$rslt_qgcFwdList[$i]['rotation'];
									$vslName="";
                                    $vslNameQuery="SELECT vsl_vessels.name
									FROM vsl_vessel_visit_details
									INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
									WHERE vsl_vessel_visit_details.ib_vyg='$rotationNo'";
									$vslNameStm=oci_parse($con_sparcsn4_oracle,$vslNameQuery);
									oci_execute($vslNameStm);
									while(($vslNameRow=oci_fetch_object($vslNameStm))!= false){
										$vslName=$vslNameRow->NAME;
									}
							?>
                            <tr class="gradeX">
                                <td align="center"><?php echo $i+1; ?></td>
                                <td align="center"><?php echo $rslt_qgcFwdList[$i]['rotation']; ?></td>
                                <td align="center"><?php echo $vslName; ?></td>
                                <!--td align="center"><?php echo $rslt_qgcFwdList[$i]['forward_by']; ?></td-->
                                <!--td align="center"><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?></td-->
                                <td align="center"><b>Forward by :
                                    </b><?php echo $rslt_qgcFwdList[$i]['forward_by']; ?><br>
                                    &nbsp; &nbsp; &nbsp;<b>Forward At :
                                    </b><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?>
                                </td>
							<?php if($org_Type_id!=30 ) { ?>	
								<td>
									<form method="POST"  action="<?php echo site_url('Vessel/qgcContForwardNew');?>"  target="_blank">
										<input type="hidden" name="impRot" id="impRot" value="<?php echo $rslt_qgcFwdList[$i]['rotation']; ?>">			
										<button type="submit"  id="submit" name="submit"  value="show" class="btn btn-primary btn-xs">Report</button>
									</form>
								</td>
								<td>
									<form  method="POST" action="<?php echo site_url("Vessel/stateOfContainerHandledByQGCreport") ?>" target="_self">
										<input type="hidden" name="rotation" id="rotation" value="<?php echo $rslt_qgcFwdList[$i]['rotation'];?>">
										<input type="hidden" name="vvd_gkey" id="vvd_gkey" value="<?php echo $rslt_qgcFwdList[$i]['vvd_gkey'];?>">
										<input type="hidden" name="vvd_gkey" id="vvd_gkey" value="<?php echo $rslt_qgcFwdList[$i]['vvd_gkey'];?>">
										<?php if ($rslt_qgcFwdList[$i]['billing_statement_generate_st'] == 0)									
										      {
												  if($section=='19') { ?>
												  
														<button type="submit"  id="submit1" name="submit1" class="btn btn-success btn-xs" value="generate">Generate_Statement</button>
										<?php } if($section=='1') { ?>
										
														<button type="submit" class="btn btn-danger btn-xs" disabled>NotGenerated yet</button>
											  <?php }
											  }
											  else { ?>
													<button type="submit" class="btn btn-success btn-xs" disabled>Generated</button>
													<!--button type="submit" class="btn btn-warning btn-xs" >View</button-->
											<?php } ?>

									</form>
									<form  method="POST" action="<?php echo site_url("Vessel/stateOfContainerHandledByQGCreport") ?>" target="_blank">
										<input type="hidden" name="rotation" id="rotation" value="<?php echo $rslt_qgcFwdList[$i]['rotation'];?>">
										<input type="hidden" name="vvd_gkey" id="vvd_gkey" value="<?php echo $rslt_qgcFwdList[$i]['vvd_gkey'];?>">
										<?php if ($rslt_qgcFwdList[$i]['billing_statement_generate_st'] == 1) { ?>
													<button type="submit" class="btn btn-warning btn-xs" >View Statement</button>
											<?php } ?>

									</form>
									
								</td>
								<td>
									<form  method="POST" action="<?php echo site_url("Vessel/stateOfContainerHandledByQGCreport") ?>" target="_self"  onsubmit="return validateForward(<?php echo $rslt_qgcFwdList[$i]['billing_statement_generate_st']; ?>);">
										<input type="hidden" name="sectionId" id="sectionId" value="<?php echo $section;?>">
										<input type="hidden" name="rotation" id="rotation" value="<?php echo $rslt_qgcFwdList[$i]['rotation'];?>">
										<input type="hidden" name="vvd_gkey" id="vvd_gkey" value="<?php echo $rslt_qgcFwdList[$i]['vvd_gkey'];?>">
										<?php if($section=='19') { if($rslt_qgcFwdList[$i]['billingSection_forwrd_st'] == 0){ ?>
										<button type="submit"  id="submit1" name="submit1" class="btn btn-primary btn-xs" value="forward">Forward</button>
										<?php } else { ?>
										<button type="submit" class="btn btn-warning btn-xs" disabled >Forwarded</button>
										<?php } } ?>
										
										<?php if($section=='1') {  if($rslt_qgcFwdList[$i]['billingSection_forwrd_st'] == 0) { ?>
														Billing sec. has not yet forwarded				
										<?php } else if ( $rslt_qgcFwdList[$i]['billingSection_forwrd_st'] == 1 && $rslt_qgcFwdList[$i]['shippingSection_forwrd_st'] == 0){ ?>
										<button type="submit"  id="submit1" name="submit1" class="btn btn-primary btn-xs" value="forward">Forward</button>
										<?php } else { ?>
										<button type="submit" class="btn btn-warning btn-xs" disabled >Forwarded</button>
										<?php }  }?>

									</form>
								</td>
								<?php } ?>	
                            </tr>
                            <?php 
								} 
							?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

</section>