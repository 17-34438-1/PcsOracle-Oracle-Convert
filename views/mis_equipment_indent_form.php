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

   
 function getCnfName(val)
	{		
	
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeCnfInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getCnfCode')?>?cnf_lic_no="+val,false);
					
		xmlhttp.send();
	}
	
	function stateChangeCnfInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			console.log(val);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			var cnfCodeTxt=document.getElementById("cnf_name");
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				cnfCodeTxt.value=jsonData[i].name;
			}										
		}
	}
 /*function getBlock()
	{		
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeYardInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getIndentYard')?>",false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeYardInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		    //alert(val);
			
			var selectList=document.getElementById("yard");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].id;  //value of option in backend
				option.text = jsonData[i].yard_name;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}*/
	
	
	function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			selectbox.remove(i);
		}
	}
 /*window.onload = function() {
	getBlock();
};*/
 function validate()
      {
		  if(confirm("Do you really want to do this?"))
		  {
			 if( document.myChkForm.cnf_lic_no.value == "" )
			 {
				alert( "Please provide Cnf License!" );
				document.myChkForm.cnf_lic_no.focus() ;
				return false;
			 }
			
			 if( document.myChkForm.no_of_cont.value == "" )
			 {
				alert( "Please provide No Of Container!" );
				document.myChkForm.no_of_cont.focus() ;
				return false;
			 }
			 if( document.myChkForm.yard.value == "" && document.myChkForm.shed.value == "")
			 {
				alert( "Please select Yard/Shed!" );
				document.myChkForm.yard.focus() ;
				return false;
			 }
			
			 return true ;
		  }
		  else
		  {
			return false;
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
									<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-list"></i> GO TO INDENT LIST
										</button>
									</a>									
								</h2>								
							</header>
							<div class="panel-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('misReport/mis_equipment_indent_entry') ?>">
									<div class="form-group">
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">INDENT DATE</span>
												<input type="date" name="indent_dt" id="indent_dt" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['indent_date'] ?>" <?php } ?> tabindex="1">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">CNF CODE</span>
												<input type="text" name="cnf_lic_no" id="cnf_lic_no" class="form-control" onblur="getCnfName(this.value)" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['cnf_code'] ?>" <?php } ?> tabindex="2">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">CNF NAME</span>
												<input type="text" name="cnf_name" id="cnf_name" class="form-control" readonly="true" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['cnf_name'] ?>" <?php } ?>>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">NO OF CONT.</span>
												<input type="text" name="no_of_cont" id="no_of_cont" class="form-control" placeholder="no * size" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_container'] ?>" <?php } ?> tabindex="3">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">DESCRIPTION</span>
												<textarea id="description" rows="3" class="form-control" name="description" tabindex="3" >
													<?php if($editFlag==1){?> <?php echo $indent_details[0]['goods_description']; ?> <?php } ?>
												</textarea>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">TOT WEIGHT</span>
												<input type="text" name="tot_weight" id="tot_weight" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['total_weight'] ?>" <?php } ?> tabindex="4">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">MAX WEIGHT(PKG)</span>
												<input type="text" name="max_weight" id="max_weight" class="form-control" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['max_weight_pkg'] ?>" <?php } ?> tabindex="5">
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">RRC</span>
												<div class="row">
													<div class="col-md-6">
														<select name="equip_rrc" id="quip_rrc" class="form-control" tabindex="6">
															<option value="">--Select--</option>
															<option value="RRC" label="RRC" <?php if($editFlag==1) if($indent_details[0]['equip_rrc']>0) echo 'selected="selected"'; ?>>RRC</option>  
														</select> 
													</div>
													<div class="col-md-6">
														<input type="text" class="form-control" name="no_of_rrc" id="no_of_rrc" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_rrc'] ?>" <?php } ?>  tabindex="7"/>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">FLT</span>
												<div class="row">
													<div class="col-md-6">
														<!--span class="input-group-addon span_width">FLT</span-->
														<select name="equip_flt" id="equip_flt" class="form-control" tabindex="8">
															<option value="">--Select--</option>
															<option value="3T" label="3T" <?php if($indent_details[0]['equip_flt_3t']>0) echo 'selected="selected"'; ?>>3T</option>
															<option value="5T" label="5T" <?php if($indent_details[0]['equip_flt_5t']>0) echo 'selected="selected"'; ?>>5T</option>
															<option value="10T" label="10T" <?php if($indent_details[0]['equip_flt_10t']>0) echo 'selected="selected"'; ?>>10T</option>
															<option value="20T" label="20T" <?php if($indent_details[0]['equip_flt_20t']>0) echo 'selected="selected"'; ?>>20T</option>  
														</select> 
													</div>
													<div class="col-md-6">
														<input type="text" class="form-control" name="no_of_flt" id="no_of_flt" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_flt'] ?>" <?php } ?> tabindex="9"/>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">MC</span>
												<div class="row">
													<div class="col-md-6">
														<!--span class="input-group-addon span_width">FLT</span-->
														<select name="equip_mc" id="equip_mc" class="form-control" tabindex="10">
															<option value="">--Select--</option>
															<option value="10T" label="10T" <?php if($indent_details[0]['equip_mc_10t']>0) echo 'selected="selected"'; ?> >10T</option>
															<option value="20T" label="20T" <?php if($indent_details[0]['equip_mc_20t']>0) echo 'selected="selected"'; ?>>20T</option>
															<option value="30T" label="30T" <?php if($indent_details[0]['equip_mc_30t']>0) echo 'selected="selected"'; ?>>30T</option>
															<option value="50T" label="50T" <?php if($indent_details[0]['equip_mc_40t']>0) echo 'selected="selected"'; ?>>50T</option> 
														</select> 
													</div>
													<div class="col-md-6">
														<input type="text" class="form-control" name="no_of_mc" id="no_of_mc" <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['no_of_mc'] ?>" <?php } ?> tabindex="11"/>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">YARD</span>
												<select name="yard" id="yard" class="form-control" tabindex="12">
													<option value="">--Select--</option>                                                
													<?php for($i=0; $i<count($rtnBlockList); $i++){ ?>
														<option value="<?php echo $rtnBlockList[$i]['id']; ?>" 
														label="<?php echo $rtnBlockList[$i]['yard_name']; ?>" <?php if($rtnBlockList[$i]['id']==$indent_details[0]['indent_yard_id']) echo 'selected="selected"'; ?>><?php echo $rtnBlockList[$i]['yard_name']; ?></option>
													<?php } ?>			  
												</select> 
											</div>
										</div>
										<div class="col-md-offset-3 col-md-6">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">SHED</span>
												<select name="shed" id="shed" class="form-control" tabindex="13">
													<option value="">--Select--</option>                                                
													<?php for($i=0; $i<count($rtnShedList); $i++){ ?>
														<option value="<?php echo $rtnShedList[$i]['id']; ?>" 
														label="<?php echo $rtnShedList[$i]['yard_name']; ?>" <?php if($rtnShedList[$i]['id']==$indent_details[0]['indent_yard_id']) echo 'selected="selected"'; ?>><?php echo $rtnShedList[$i]['yard_name']; ?></option>
													<?php } ?>			  
												</select> 
											</div>
										</div>
										<div class="col-sm-12 text-center">
											<?php if($editFlag==1){?>
											 <input class="mb-xs mt-xs mr-xs btn btn-primary"  name="update" type="submit"  value="UPDATE" > 
											<?php } else{?>
											<input class="mb-xs mt-xs mr-xs btn btn-success"  name="save" type="submit"  value="SAVE" >
											<?php } ?> 
											<input class="form-control" type="hidden"  id="indentid" name="indentid"  <?php if($editFlag==1){?> value="<?php echo $indent_details[0]['id']; }?>" />
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