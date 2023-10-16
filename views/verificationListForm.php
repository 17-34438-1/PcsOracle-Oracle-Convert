  <script>
  function validate()
   {
	if (confirm("Do you want to detete this Verification Number?") == true)
	{
		return true ;
    }
    else
	{
	   return false;
    }
   }
   	
 </script>
 <style>
 #table-scroll {
  height:420px;
  overflow:auto;  
  margin-top:20px;
}
 </style>
 <section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/verificationListFormView'; ?>" target="_blank" id="myform" name="myform">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Verify Number  <span class="required">*</span></span>
											<input type="text" name="strVerifyNum" id="strVerifyNum" class="form-control" placeholder="Verify Number ">
										</div>												
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										</div>													
									</div>
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

		</div>
		 
		</div>
		
       	<div id="table-scroll">
			<table  class="table table-bordered table-responsive table-hover table-striped mb-none">
					<tr class="gridDark" align="center">
						<td><b>View Certification</b></td>
						<td><b>Truck Add</b></td>
						<?php if($login_id!='devcf'){?>
						<td><b>Action</b></td>
						<?php } ?>
						<td ><b>Verify Number</b></td>						
						<td ><b>Rotation</b></td>
						<td ><b>Container</b></td>
						<td ><b>MBL</b></td>
						<td ><b>FBL</b></td>
						<td ><b>Size</b></td>
						<td ><b>Type</b></td>
						<td ><b>Status</b></td>
						<td ><b>Qnty</b></td>
						<td ><b>Pkgs</b></td>
						<td ><b>Importer</b></td>
						<td ><b>Gross weight</b></td>
								
					</tr>
					<?php for($i=0;$i<count($rtnContainerList);$i++){?>
					<tr class="gridLight" align="center">
					
						<td align="center"> 
							<form action="<?php echo site_url('Report/certificationFormViewList/'.str_replace("/","_",$rtnContainerList[$i]['BL_No']).'/'.str_replace("/","_",$rtnContainerList[$i]['import_rotation']))?>" method="POST">
								<button type="submit"  class="btn btn-primary login_button" >View</button>							
							</form> 
						</td>
						<td align="center"> 
							<form action="<?php echo site_url('ShedBillController/bilSearchByVerifyNumber/'.$rtnContainerList[$i]['verify_number'])?>" method="POST">
								<button type="submit" class="btn btn-primary login_button">Add Truck</button>							
							</form> 
						</td> 
					<?php if($login_id!='devcf'){?>
						<td align="center"> 
						<form  name= "delForm" onsubmit="return validate();" action="<?php echo site_url('Report/deleteVerificationNumber/'.str_replace("/","_",$rtnContainerList[$i]['verify_number']));?>" method="POST">
							<button type="submit" id="delButton" class="btn btn-primary login_button">Delete</button>
						</form>	
					    </td> 
					<?php } ?>	
						<td style="color:red"><?php echo $rtnContainerList[$i]['verify_number'];?></td>
						<td><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
						<td><?php echo $rtnContainerList[$i]['cont_number'];?></td>
						<td><?php echo $rtnContainerList[$i]['master_BL_No'];?></td>
						<td><?php echo $rtnContainerList[$i]['BL_No'];?></td>
						<td><?php echo $rtnContainerList[$i]['cont_size'];?></td>
						<td><?php echo $rtnContainerList[$i]['cont_type'];?></td>
						<td><?php echo $rtnContainerList[$i]['cont_status'];?></td>
						<td><?php echo $rtnContainerList[$i]['Pack_Number'];?></td>
						<td><?php echo $rtnContainerList[$i]['Pack_Description'];?></td>		
						<td><?php echo $rtnContainerList[$i]['Notify_name'];?></td>
						<td><?php echo $rtnContainerList[$i]['cont_weight'];?></td>
					
					</tr>
					<?php }?>
				</table>
		 </div>
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <div class="clr"></div>
	</div>
</section>