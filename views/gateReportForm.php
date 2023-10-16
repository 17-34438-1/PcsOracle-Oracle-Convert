
<script language="JavaScript">

function changeTextBox(v)
{
	    var search_value = document.getElementById("search_value");
		var fromdate = document.getElementById("fromdate");
		var todate = document.getElementById("todate");
		if(v=="dateRange")
		{
			search_value.disabled=true;
			fromdate.disabled=false;
			todate.disabled=false;
		
		}	
		else if(v=="")
		{
			search_value.disabled=true;
			fromdate.disabled=true;
			todate.disabled=true;
		}
		else 
		{
			search_value.disabled=false;
			fromdate.disabled=true;
			todate.disabled=true;		
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
											<a href="<?php echo site_url('Controller/List') ?>">
												<button style="margin-left: 35%" class="btn btn-primary btn-sm">
													<i class="fa fa-list"></i>
												</button>
											</a>
										</h2>								
									</header-->
									 <?php 
						  $attributes = array('id' => 'myform','target'=>'_BLANK');
						  //,'target'=>'_BLANK'
						  echo form_open(base_url().'index.php/GateController/gateReportViewPdf',$attributes);
							$Stylepadding = 'style="padding: 12px 20px;"';
								if(!empty($error_message))
								{
									$Stylepadding = 'style="padding:25px 20px;"';
								}	
								if(isset($captcha_image)){
									$Stylepadding = 'style="padding:62px 20px 93px;"';
								}?>
								<!-- <div class="form-group" align="center"><b>GATE REPORT </b></div> -->
									<div class="panel-body" align="center">
									
					
				<div class="form-group">
					<label class="col-md-3 control-label">&nbsp;</label>
					<!--div class="col-md-6">		
						<div class="input-group mb-md">
							<span class="input-group-addon span_width" style="width:150px;">From Date <span class="required">*</span></span>
								<input type="date" style="width:250px;" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>"/>
						</div>
					
						
					</div-->
					<div class="col-md-12">		
						<div class="input-group mb-md">
					 <table  border="0" align="center" cellspacing="0" cellpadding="0">
	
								<tr>
								<td align="left" >
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" class="form-control">Search By :<span class="required">*</span></span>
											<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
												<option value="" label="search_by" selected style="width:110px;">---Select-------</option>
												<option value="vNum" label="Verification Number" >Verification Number</option>
												<option value="dateRange" label="Date Range" >Date Range</option>												
											</select>
									</div>	
								</div>
									<!--td align="left" >
									<label for=""><font color='red'></font>Search By :<em>&nbsp;</em></label></td>
									<td>
											<select name="search_by" id="search_by" class="" onchange="changeTextBox(this.value);">
												<option value="" label="search_by" selected style="width:110px;">---Select-------</option>
												<option value="vNum" label="Verification Number" >Verification Number</option>
												<option value="dateRange" label="Date Range" >Date Range</option>												
											</select>

									</td-->
									</td>
								</tr>
								 <tr>
									<td>
										<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" class="form-control">Verification No :<span class="required">*</span></span>
											<input type="text" class="form-control" id="search_value" name="search_value" disabled> 
										</div>	
										</div>
									</td>									
								</tr>	


								<tr >
									<td>
										<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" class="form-control">From Date:<span class="required">*</span></span>
											<input type="date" class="form-control" id="fromdate" name="fromdate" value="<?php date("Y-m-d"); ?>" disabled />

										</div>	
										</div>
										
										<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width" class="form-control">To Date:<span class="required">*</span></span>
											<input type="date" class="form-control" id="todate" name="todate" value="<?php date("Y-m-d"); ?>" disabled />

										</div>	
										</div>
								
								
										</td>
											
								</tr>

						<tr><td align="center" colspan="2"><font color=""><b>
						<?php if($verify_number>0 or $verify_num>0)
							{ echo "<font color='green'><b>VERIFY NUMBER IS ".$verify_num."</b></font>";} 			 
						  else 
							{ echo $msg;}?>
						</b></font></td></tr>
						<!--TR align="center"><TD colspan="6" ><h2><span ><?php echo "Verify No: ".$verify_num; ?></span> </h2></TD></TR-->
						</table>
							</div>
						
							
						</div>
																		
						<div class="row">
							<div class="col-sm-12 text-center">
								<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
								<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								<!--input type="submit" value="Save" name="save" class="login_button"-->

							</div>													
						</div>
						<?php echo form_close()?>
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