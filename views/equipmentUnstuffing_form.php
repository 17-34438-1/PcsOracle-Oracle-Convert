 <script>
 	$(document).on('keypress', 'input,select,textarea', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
        console.log($next.length);
        if (!$next.length) {
            //$next = $('[tabIndex=1]');
   form.submit();
        }
  else
        $next.focus();
    }
   });


 function getVsl(rot_no)
	{		
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getVslName')?>?rot_no="+rot_no,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			document.getElementById("vsl_name").value="";								
			document.getElementById("vsl_name").value=jsonData[0].vsl_name;								
		}
	}
	
	
 function validate()
      {
		 
		  if( document.myChkForm.berth_op.value == "" )
		 {
			alert( "Please provide No Of Container!" );
			document.myChkForm.berth_op.focus() ;
			return false;
		 }
		 
		 if( document.myChkForm.rot_no.value == "" )
		 {
			alert( "Please provide Rotation No.!" );
			document.myChkForm.rot_no.focus() ;
			return false;
		 }

		  else
		  {
			return true;
		  }
		  //alert(document.myChkForm.used_equipment.value);
		
      }
 </script>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title; ?></h2>
					
						<div class="right-wrapper pull-right">
						
						</div>
					</header>

					<!-- start: page -->
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('misReport/equipmentUnstuffingList') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-list"></i> GO TO LIST
										</button>
									</a>									
								</h2>								
							</header>
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" 
									action="<?php echo site_url('misReport/equipmentUnstuffing_entry') ?>" name="myChkForm" onsubmit="return(validate());">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">UNSTUFFING DATE</span>
												<input type="date" name="un_dt" id="un_dt" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['un_dt'] ?>" <?php } ?> tabindex="1">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">BERTH OPERARTOR</span>
												<select name="berth_op" id="berth_op" class="form-control" tabindex="12">
													<?php if($editFlag==1){?> 
														<option value="<?php echo $indent_details[0]['berth_op']; ?>" label="<?php echo $indent_details[0]['berth_op']; ?>"><?php echo $indent_details[0]['berth_op']; ?></option>
													<?php }  ?> 
														<option value="" style="width:130px;">---Select---</option>
													<?php	for($i=0; $i<count($bertg_opList); $i++){ ?>
														<option value="<?php echo $bertg_opList[$i]['BERTHOP']; ?>" label="<?php echo $bertg_opList[$i]['BERTHOP']; ?>"><?php echo $bertg_opList[$i]['BERTHOP']; ?></option>
													<?php }  ?>			  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">ROTATION NO.</span>
												<input type="text" name="rot_no" id="rot_no" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['rotation'] ?>" <?php } ?> onblur="getVsl(this.value);" tabindex="2">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">VESSEL NAME.</span>
												<input type="text" name="vsl_name" id="vsl_name" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['vsl_name'] ?>" <?php } ?>>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">UPPER NO.</span>
												<input type="text" name="upr_no" id="upr_no" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['up_no'] ?>" <?php } ?> tabindex="3">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">SHED NO</span>
												<input type="text" name="shed_no" id="shed_no" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['shed_no'] ?>" <?php } ?> tabindex="4">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">BUSKAR</span>
												<input type="text" name="buskar" id="buskar" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['buskar'] ?>" <?php } ?> tabindex="5">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">LONG TROLLY</span>
												<input type="text" name="long_trolly" id="long_trolly" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['long_trolly'] ?>" <?php } ?> tabindex="6">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">FLT</span>
												<div class="row">
													<div class="col-md-6">
														<!--span class="input-group-addon span_width">FLT</span-->
														<select name="equip_flt" id="equip_flt" class="form-control" tabindex="7">
															<option value="" selected style="width:130px;">---Select---</option>
															
															<!--option value="3T" label="3T" <?php if($indent_details[0]['flt_3t']>0) echo 'selected="selected"'; ?>>3T</option>
															<option value="5T" label="5T" <?php if($indent_details[0]['flt_5t']>0) echo 'selected="selected"'; ?>>5T</option>
															<option value="10T" label="10T" <?php if($indent_details[0]['flt_10t']>0) echo 'selected="selected"'; ?>>10T</option>
															<option value="20T" label="20T" <?php if($indent_details[0]['flt_20t']>0) echo 'selected="selected"'; ?>>20T</option--> 
															
															<option value="3T" label="3T" <?php if($editFlag==1) if($indent_details[0]['flt_3t']>0) echo 'selected="selected"'; ?>>3T</option>
															<option value="5T" label="5T" <?php if($editFlag==1)  if($indent_details[0]['flt_5t']>0) echo 'selected="selected"'; ?>>5T</option>
															<option value="10T" label="10T" <?php if($editFlag==1) if($indent_details[0]['flt_10t']>0) echo 'selected="selected"'; ?>>10T</option>
															<option value="20T" label="20T" <?php if($editFlag==1) if($indent_details[0]['flt_20t']>0) echo 'selected="selected"'; ?>>20T</option> 
														</select> 
													</div>
													<div class="col-md-6">
														<input type="text" class="form-control" name="no_of_flt" id="no_of_flt" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_flt'] ?>" <?php } ?> tabindex="8"/>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">MC</span>
												<div class="row">
													<div class="col-md-6">
														<select name="equip_mc" id="equip_mc" class="form-control" tabindex="9">
															<option value="" selected style="width:130px;">---Select---</option>
															<!--option value="10T" label="10T" <?php if($indent_details[0]['mc_10t']>0) echo 'selected="selected"'; ?> >10T</option>
															<option value="20T" label="20T" <?php if($indent_details[0]['mc_20t']>0) echo 'selected="selected"'; ?>>20T</option>
															<option value="30T" label="30T" <?php if($indent_details[0]['mc_30t']>0) echo 'selected="selected"'; ?>>30T</option>
															<option value="50T" label="50T" <?php if($indent_details[0]['mc_50t']>0) echo 'selected="selected"'; ?>>50T</option--> 
															
															<option value="10T" label="10T" <?php if($editFlag==1) if($indent_details[0]['mc_10t']>0) echo 'selected="selected"'; ?> >10T</option>
															<option value="20T" label="20T" <?php if($editFlag==1) if($indent_details[0]['mc_20t']>0) echo 'selected="selected"'; ?>>20T</option>
															<option value="30T" label="30T" <?php if($editFlag==1) if($indent_details[0]['mc_30t']>0) echo 'selected="selected"'; ?>>30T</option>
															<option value="50T" label="50T" <?php if($editFlag==1) if($indent_details[0]['mc_50t']>0) echo 'selected="selected"'; ?>>50T</option> 
														</select> 
													</div>
													<div class="col-md-6">
														<input type="text" class="form-control" name="no_of_mc" id="no_of_mc" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_mc'] ?>" <?php } ?> tabindex="10"/>
													</div>
												</div>
											</div>
										</div>
										
										
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<input type="hidden" class="form-control" name="unstuffid" id="unstuffid" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['id']; }?>"/>
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<?php if($editFlag==1){?>
											 <input class="mb-xs mt-xs mr-xs btn btn-primary"  name="update" type="submit"  value="UPDATE" tabindex="11"> 
											<?php } else{?>
											<input class="mb-xs mt-xs mr-xs btn btn-success"  name="save" type="submit"  value="SAVE" tabindex="11">
											<?php } ?> 
										</div>													
									</div>
									<div class="form-group">
									</div>
								</form>
							</div>
						</section>
						
						
						
						
					<!-- end: page -->
				</section>
			</div>