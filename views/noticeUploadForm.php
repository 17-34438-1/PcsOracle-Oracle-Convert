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
					<form name= "myForm" class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/noticeUploadFormPerform'); ?>" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<a href="<?php echo site_url('report/noticeUploadList') ?>">BACK TO NOTICE LIST</a>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Upload Notice <span class="required">*</span></span>
									<input class="read" type="file" style="width:200px;"  id="file" name="file">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Notice Title <span class="required">*</span></span>
									<input class="read" type="text" style="width:300px; height:20px;"  id="notice_title" name="notice_title"  <?php if($editFlag==1){ ?> value="<?php echo $rslt_notice_info[0]['title'] ?>" <?php  } ?> >
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization <span class="required">*</span></span>
									<nobr>
									<?php
										for($i = 0; $i < count($orgList); $i++) 
										{
									?>
										<input type="checkbox"  
											<?php 
												for($j = 0; $j < count(@$sel_org_list); $j++) 
												{ 
													if(@$sel_org_list[$j]['org_id'] == $orgList[$i]['id']) 
														echo "checked='checked'"; 
												} 
											?> 
											
											style="width:15px;" id="orgs" name="orgs" value="<?php echo $orgList[$i]['id'];?>"><?php  echo $orgList[$i]['Org_Type']?>
											
									<?php	
										} 
									?> 
									
									</nobr>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Comments <span class="required">*</span></span>
									<textarea id="notice_comment" name="notice_comment" style="width:300px; height: 50px" rows="6" ><?php if($editFlag==1){ ?> <?php echo $rslt_notice_info[0]['comment'] ?><?php  } ?></textarea>
								</div>
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>	
								<div class="input-group mb-md">
									<input class="read" type="hidden"  id="notice_id" name="notice_id"  <?php if($editFlag==1){?> value="<?php echo $rslt_notice_info[0]['id']; }?>" />
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">																	
									<?php 
									if($editFlag==1)
									{
									?>
									<button type="submit" name="update" class="mb-xs mt-xs mr-xs btn btn-success">UPDATE</button>
									<?php 
									} 
									else
									{
									?>
									<button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
									<?php 
									} 
									?> 
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
