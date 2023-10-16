<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/stuffingContExcelPerform'); ?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<?php
								if($flag==1)
								{
									$path=BASE_PATH.'resources/';									
								?>
								<div class="input-group mb-md">									
									<a href="<?php echo $path.'sampleExcelStuffingContainer.xls';?>"><span>Download Sample Excel</span></a>
								</div>
								<?php
								}
								else
								{
								?>								
								<div class="input-group mb-md">		
									<font color="blue" size="2"><b><?php echo $msg; ?><br></b> </font>
								</div>									
								<?php
								}
								?>
								<?php
								$org_Type_id=$this->session->userdata('org_Type_id');
								if($org_Type_id==28)
								{
								?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Offdock <span class="required">*</span></span>
									<select name="offdock" id="offdock ">
										<option value="">--Select--</option>
										<?php
										include("mydbPConnectionn4.php");
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
								<?php
								}
								?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Browse Excel File <span class="required">*</span></span>
									<input type="file" name="file" class="form-control login_input_text"/>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-success" <?php if($ctime>=$upperLimit and $org_Type_id!=28 and $diff == null) { ?>disabled <?php } ?>>Upload</button>
								</div>													
							</div>
							<?php
							//	if($ctime<=10 and $ctime>=9)
							//	if($ctime==11)
								if($ctime==$lowerLimit and $org_Type_id!=28 and $diff!=null)
								{ 
								?>
								<div style="font-size:20px;color:red;">
									<marquee hspace="1"><b>Upload facility will be closed after <?php echo $diff; ?> minutes for today.</b></marquee>
								</div>
								<?php
								}
							//	else if($ctime>=10 and $cmin==15)	
							//	else if($ctime>=12)
								else if($ctime>=$upperLimit and $org_Type_id!=28)
								{ 
								?>
									<div style="font-size:20px;color:red;">
										<marquee hspace="1"><b>Upload facility is closed for today.</b></marquee>
									</div>
								<?php
								}
								?>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
