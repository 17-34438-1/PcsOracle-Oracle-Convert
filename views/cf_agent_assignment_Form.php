
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
									<!-- <div class="form-group" align="center"><b>ASSIGNMENT/DELIVERY EMPTY DETAILS </b></div> -->
									<div class="panel-body" align="center">
									<?php 
										  $attributes = array('target' => '_blank', 'id' => 'myform');
										  
										  echo form_open(base_url().'index.php/Report/cf_agent_assignment_ReportView',$attributes);
											$Stylepadding = 'style="padding: 12px 20px;"';
												if(!empty($error_message))
												{
													$Stylepadding = 'style="padding:25px 20px;"';
												}	
												if(isset($captcha_image)){
													$Stylepadding = 'style="padding:62px 20px 93px;"';
										}?>
					
											<div class="form-group">
												<label class="col-md-3 control-label">&nbsp;</label>
												<div class="col-md-6">		
													<div class="input-group mb-md">
														<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
															<input type="date" style="width:250px;" id="fromdate" class="form-control" name="fromdate" value="<?php date("Y-m-d"); ?>"/>
													</div>
												
													
												</div>
												<div class="col-md-12">		
													<div class="input-group mb-md">
													<table>
															<tr>
															
															<td align="left">
															
																
														
																<label for="Excel" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">Excel</label>
															<?php 	$data = array(
																			'name'        => 'options',
																			'id'          => 'options',
																			'value'       => 'xl',
																			'checked'     => FALSE,
																			'style'       => 'width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;',
																			);
																echo form_radio($data); ?>
															</td>
															<td align="left">
																<label for="HTML" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">HTML</label>
																<?php 	$data = array(
																			'name'        => 'options',
																			'id'          => 'options',
																			'value'       => 'html',
																			'checked'     => TRUE,
																			'style'       => 'width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;',
																			);
																echo form_radio($data); ?>
																
															</td>
													
														</tr>
														</table>
													</div>
												
													
												</div>
																								
												<div class="row">
													<div class="col-sm-12 text-center">
														<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
														<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
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