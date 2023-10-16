
<style>
	.captcha
	{
		width:50%;
		background: yellow;
		text-align: center;
		font-size: 24px;
		font-weight:700;
	}
</style>
<div class="container" id="main-boxes">
<div class="row">
	<aside  class="col-md-4 col-sm-4">

		<!--p><a  href="all-courses.html" title="All courses"><img src="<?php echo  ASSETS_WEB_PATH?>fimg/banner.jpg" alt="Banner" class="img-rounded img-responsive" ></a></p-->

		<div class="box-style-1 ribbon borders">
            <div class="feat" style="padding-left:5px;">
                <div class="feat">
                    <i class="icon-group icon-3x"></i>
                    <h3>Login</h3>
                </div>
                <div id="login_container" style="width:100%;padding:5px;">
					<!--form name="frm2" action="" onsubmit="return isvalidLogin();" style="padding:25px 20px;"-->
						<?php 
							//$body="";
							//echo form_open('auths/login'); 
							$attributes = array('name' => 'frm2');
							echo form_open(base_url().'index.php/Login/',array('name' =>'frm2','onsubmit' => 'return isvalidLogin();'));
							$Stylepadding = 'style="padding: 12px 20px;"';
							if(!empty($error_message))
							{
								$Stylepadding = 'style="padding:25px 20px;"';
							}	
							if(isset($captcha_image)){
								$Stylepadding = 'style="padding:62px 20px 93px;"';
							}
						?>
                        <table border="0" width="250px">
							<tr>
								<td colspan="4">
									<?php if(@$body!="") echo @$body; ?>
								</td>
							</tr>
                            <tr>
                                <td align="left" colspan="2"><label for="user_name" style="padding-bottom:10px; padding-right:5px;">User Name:<em>&nbsp;</em></label></td>
                            
                                <td align="left" colspan="2" class="borderTop">
                                    <!--input type="text" name="username" id="txt_login" class="login_input_text"-->
									<?php 
										$attribute = array('name'=>'username','id'=>'txt_login','class'=>'login_input_text');
										echo form_input($attribute,set_value('username'));
									?>
                                </td>
                            </tr>					
                            <tr>
                                <td align="left" colspan="2"><label for="password" style="padding-bottom:10px; padding-right:5px;" >Password:<em>&nbsp;</em></label></td>
                            
                                <td align="left" colspan="2" class="borderTop">
                                    <!--input type="password" name="password" id="txt_password" class="login_inpt_text"-->
									<?php $attribute = array('name'=>'password','id'=>'txt_password','class'=>'login_input_text');
										echo form_password($attribute);?>
									<?php //echo form_error('txt_password'); ?>
                                </td>
                            </tr>
                                    
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <!--input type="submit" name="submit_login" id="submit" value="Login" class="button_medium"-->
									<?php $arrt = array('name'=>'submit_login','id'=>'submit','value'=>'Login','class'=>'button_medium'); echo form_submit($arrt);?>
                                </td>
                            </tr>					
                        </table>
						</form>
			    </div>
            </div>
            <?php
				$rand=rand(9999,1000);
			?>
            <hr class="double" style="border-top: 2px solid #cedee7;">
            <div class="feat" style="padding-left:5px;">
                <div class="feat">
                    <i class="icon-film icon-3x"></i>
                    <h3>Container Location</h3>
                </div>
            
                <div style="color:#000;" align="center" id="login_container">
					<?php
						$attributes = array('name' => 'frm2');
						echo form_open(base_url().'index.php/Report/mySearchContainerLocation',array('name' =>'frm3','target' => '_blank','onsubmit' => 'return isValidation();'));
						$Stylepadding = 'style="padding: 12px 20px;"';
						?>
						<!--a href='".site_url('Report/containerHandlingView')."' target='_blank'>Container Handling today</a-->
					
							<table>
								<tr>
									<td>
										<label for="user_name" style="padding-bottom:10px; padding-right:5px;">Container No.:</label>
									</td>
									<td>
										<?php $attribute = array('name'=>'containerLocation','id'=>'containerLocation','class'=>'login_input_text');
										echo form_input($attribute,set_value('containerLocation'));
										?>
									</td>
									
								</tr>
								<tr>
									<td>
										<label for="captcha" style="padding-bottom:10px; padding-right:5px;">Captcha</label>
									</td>
									<td>
										<input type="text" name="captcha" placeholder="Enter Captcha" required data-parsely-trigger="keyup">
										<input type="hidden" name="captcha_rand" value="<?php echo $rand; ?>">
									</td>
									
								</tr>
								<tr>
									<td>
										<label for="captcha" style="padding-bottom:10px; padding-right:5px;">Captcha Code</label>
									</td>
									<td class="captcha">
										<?php echo  $rand; ?>
									</td>
									
								</tr>
								<tr>
									<td colspan="2">
										<?php  echo @$captchaMsg; ?>
									</td>
								</tr>
								
								<tr>
									<td>&nbsp;</td>
									<td><?php $arrt = array('name'=>'submit_login','id'=>'submit','value'=>'Search','class'=>'button_medium'); echo form_submit($arrt);?></td>
								</tr>
							</table>	
						</form>
				</div>
			</div>
            
            <hr class="double" style="border-top: 2px solid #cedee7;">
            
            <div class="feat" style="padding-left:5px;">
                <div class="feat">
                    <i class="icon-book icon-3x"></i>
                    <h3>Berthing Report</h3>
                </div>
                <div style="color:#000;padding:5px;" align="center" id="login_container">
					<form name= "myForm" onsubmit="return(validate());" action="<?php echo site_url("Report/berthReportView");?>" target="_blank" method="post">
						<table>
							<tr align="center" valign="center">
								<td>
									<table>
											<tr>
												<th align="left"><label style="padding-bottom:10px; padding-right:5px;">
													<nobr> From Date: </nobr> </label>
												</th>
												<td>
													<input type="date" id="fromdate" name="fromdate" value="" class="form-control"/>
												</td>
											</tr>
											<tr>
												<th align="left"><label style="padding-bottom:10px; padding-right:5px;">
													<nobr> To Date: </nobr> </label>
												</th>
												<td>
													<input type="date" id="todate" name="todate" value="" class="form-control" />
												</td>
											</tr>		
											<tr>
												<td>&nbsp;</td>
												<td>
													<input type="submit" name="View" value="View" class="button_medium"/>
												</td>
											</tr>								
										</tr>
								  </table>
								</td>
							</tr>
						</table>
					</form>
				</div>
            </div>
            
            <hr class="double" style="border-top: 2px solid #cedee7;">
            
            <div class="feat last" style="padding-left:5px;">
                <div class="feat last">
                    <i class="icon-laptop icon-3x"></i>
                    <h3>Important Link</h3>
                </div>
              
                <div style="width:100%;text-align:center;padding:5px;" id="login_container">
                    <table>
					<?php
						//echo site_url("report/containerHandlingView");
						//echo base_url("report/containerHandlingView");
						
					?>
                        <tr>
							<td align="center">
								<a href="<?php echo site_url("Report/containerHandlingView");?>" target="_blank">
									Yardwise Equipment Booking Report Today
								</a>
							</td>
						</tr>
						<tr>
							<!--td align="center">
								<?php $formPath= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/applicationForm/'; ?>
								<a target="_blank" href="<?php echo $formPath. 'userForm.pdf'; ?>">
									Application Form for TOS User ID
								</a>
							</td-->
						</tr>
                    </table>
			    </div>
            </div>
        </div>
    </aside>
	<section class="col-md-8 col-sm-8">
		<div class="col-right">
			<div style="overflow:scroll;margin-bottom:5px;"class="widget responsive borders widget-table"><!-- overflow:scroll; -->
				<div class="widget-header">
					<h3 align="center">Vessel Information</h3>
				</div> <!-- .widget-header -->
				<div style="height:600px;" class="widget-content">
					<table class="table table-responsive table-bordered table-striped table-hover" id="datatable-default">
						<thead>
							<tr class="gridDark">
								<th>Vessel Name</th>
								<th>Rotation</th>
								<th>Phase</th>
								<th>ETA</th>
								<th>ATA</th>
								<th>Berth</th>
								<th>Operator</th>
								<th>Agent</th>
								<th>ETD</th>
								<th>ATD</th>
							</tr>
						</thead>
						<tbody>
							<?php
							for($i=0;$i<count($rtnVesselList);$i++){
							?>
							<tr class="gridLight">
								<td><?php echo $rtnVesselList[$i]['NAME']?></td>
								<td><?php echo $rtnVesselList[$i]['IB_VYG']?></td>
								<td><?php echo $rtnVesselList[$i]['PHASE_STR']?></td>
								<td><nobr><?php echo $rtnVesselList[$i]['ETA']?></nobr></td>
								<td><nobr><?php echo $rtnVesselList[$i]['ATA']?></nobr></td>
								<td><?php echo $rtnVesselList[$i]['BERTH']?></td>
								<td><?php echo $rtnVesselList[$i]['BERTHOP']?></td>
								<td><?php echo $rtnVesselList[$i]['AGENT']?></td>
								<td><nobr><?php echo $rtnVesselList[$i]['ETD']?></nobr></td>
								<td><nobr><?php echo $rtnVesselList[$i]['ATD']?></nobr></td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div><!-- .widget-content -->
			</div> <!-- /widget -->	

			<!-- 
			<h2 style="margin-top:5px;">Upcoming courses</h2>
			<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei. Saepe tantas ocurreret duo ea, has facilisi vulputate an. Priaeque iuvaret nominati et, ad mea clita numquam. Maluisset dissentiunt et per, dico liber erroribus vis te. Dolor consul graecis nec ut, scripta eruditi scriptorem et nam.</p>
			<hr>
			<div class="strip-lessons">
				<div class="row">
					<div class="col-md-3 col-xs-6">
						<div class="box-style-one borders"><img src="<?php echo ASSETS_WEB_PATH?>fimg/lessons.png" alt=""><h5>Intro</h5></div>
					</div>
					<div class="clearfix visible-xs-block"></div>
					<div class="col-md-9">
						<h4>Autem possim his</h4>
						<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, <strong>mea ea ullum epicurei</strong>. Saepe tantas ocurreret duo ea, has facilisi vulputate an. Pri aeque iuvaret nominati et, ad mea clita numquam. </p>
						<ul class="data-lessons">
							<li><i class="icon-time"></i>Duration: 3 hours</li>
							<li><i class="icon-film"></i><a class="fancybox-media" href="http://www.youtube.com/watch?v=pgk-719mTxM">Play video</a></li>
							<li><i class="icon-cloud-download"></i><a href="#">Donwload prospect</a></li>
						</ul>
					</div>
				</div>
			</div>
			
			
			<div class="strip-lessons">
			<div class="row">
				<div class="col-md-3 col-xs-6">
					<div class="box-style-one borders"><img src="<?php echo ASSETS_WEB_PATH?>fimg/teacher.jpg" alt="" class="picture img-responsive"><h5>Lesson one</h5></div>
				</div>
				<div class="clearfix visible-xs-block"></div>
				<div class="col-md-9">
					<h4>Putant mandamus</h4>
					<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, <strong>mea ea ullum epicurei</strong>. Saepe tantas ocurreret duo ea, has facilisi vulputate an. Pri aeque iuvaret nominati et, ad mea clita numquam. </p>
					<ul class="data-lessons">
						<li><i class="icon-time"></i>Duration: 3 hours</li>
						<li><i class="icon-film"></i><a class="fancybox-media" href="http://www.youtube.com/watch?v=pgk-719mTxM">Play video</a></li>
						<li><i class="icon-cloud-download"></i><a href="#">Donwload prospect</a></li>
					</ul>
				</div>
			</div>
			</div>
			
			 <div class="strip-lessons">
			<div class="row">
				<div class="col-md-3 col-xs-6">
					<div class="box-style-one borders"><img src="<?php echo ASSETS_WEB_PATH?>fimg/teacher-2.jpg" alt="" class="picture img-responsive"><h5>Lesson two</h5></div>
				</div>
				<div class="clearfix visible-xs-block"></div>
				<div class="col-md-9">
					<h4>Quodsi nominavi</h4>
					<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, <strong>mea ea ullum epicurei</strong>. Saepe tantas ocurreret duo ea, has facilisi vulputate an. Pri aeque iuvaret nominati et, ad mea clita numquam. </p>
					<ul class="data-lessons">
						<li><i class="icon-time"></i>Duration: 3 hours</li>
						<li><i class="icon-film"></i><a class="fancybox-media" href="http://www.youtube.com/watch?v=pgk-719mTxM">Play video</a></li>
						<li><i class="icon-cloud-download"></i><a href="#">Donwload prospect</a></li>
					</ul>
				</div>
			</div>
			</div>
			
			<div class="strip-lessons">
			<div class="row">
				<div class="col-md-3 col-xs-6">
					<div class="box-style-one borders"><img src="<?php echo ASSETS_WEB_PATH?>fimg/teacher-3.jpg" alt="" class="picture img-responsive"><h5>Lesson three</h5></div>
				</div>
				<div class="clearfix visible-xs-block"></div>
				<div class="col-md-9">
					<h4>Saepe tantas</h4>
					<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, <strong>mea ea ullum epicurei</strong>. Saepe tantas ocurreret duo ea, has facilisi vulputate an. Pri aeque iuvaret nominati et, ad mea clita numquam. </p>
					<ul class="data-lessons">
						<li><i class="icon-time"></i>Duration: 3 hours</li>
						<li><i class="icon-film"></i><a class="fancybox-media" href="http://www.youtube.com/watch?v=pgk-719mTxM">Play video</a></li>
						<li><i class="icon-cloud-download"></i><a href="#">Donwload prospect</a></li>
					</ul>
				</div>
			</div>
			</div>
			
			<p class="text-center"><a href="contact.html" class="button_large">View all courses </a></p>
			-->

		</div>
	</section>
  </div>
   </div><!-- end container-->
  
<!--script>

	function isValidation(){
		Cont = document.getElementById('containerLocation').value;
		if(Cont == ""){
			alert("Please Enter Container No");
			return false;
		}else{
			return true;
		}
	}
</script-->
