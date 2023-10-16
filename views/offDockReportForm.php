<script>
	function validate()
	{
        if( document.myForm.rot_no.value == "" )
        {
            alert( "Please! Rotation No." );
            document.myForm.rot_no.focus() ;
            return false;
	    }
        else if( document.myForm.offdock_id.value == "" )
        {
            alert( "Please! Select Offdock Destination." );
            document.myForm.offdock_id.focus() ;
            return false;
		}
       
        else
            return true;
    }  
</script>
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
					<form class="form-horizontal form-bordered" name= "myForm" onsubmit="return validate();" action="<?php echo site_url("report/offDockReportAction");?>" method="post" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<font color="black"><b><?php echo $msg; ?></b></font>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="rot_no" id="rot_no" class="form-control login_input_text">
								</div>
								<?php
								include("dbConection.php");
								
								$query=mysqli_query($con_sparcsn4,"select * from ctmsmis.offdoc where id not in('2592','2645','2647','5231','5232','5233','5235','5236','5237')");
								$i=0;
								?>				
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">OffDock <span class="required">*</span></span>
									<select name="offdock_id" id="offdock_id">
										<option value="">---Select---</option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php 
										while($row=mysqli_fetch_object($query))
										{
											$i++;
										?>
										<option value="<?php if($row->id) echo $row->id;?>"><?php if($row->id) echo $row->name;?></option> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php	
										}
										?>
									</select>
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="destinationStat" name="destinationStat" value="part"> Partly
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="destinationStat" name="destinationStat" value="full"> Fully
									</label>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Containers <span class="required">*</span></span>
									<textarea id="containers" name="containers" class="form-control" rows="3"></textarea>
								</div>
								<div class="input-group mb-md">
									<font color="red"><b>If Partly, Please put the Containers seperated by comma(,). After the last conntainer don't put comma(,).</b></font>
								</div>

								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="fileOptions" name="fileOptions" value="xl"> Excel
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="fileOptions" name="fileOptions" value="html"> HTML
									</label>
								</div>
								
							</div>
																			
							<div class="row">
								<div class="col-sm-4 text-center">								
									<!--button type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success">Show Data</button-->
									<input type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success" value="Show Data">
								</div>
								<div class="col-sm-4 text-center">								
									<!--button type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success">Update Destination</button-->
									<input type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success" value="Update Destination">
								</div>
								<div class="col-sm-4 text-center">								
									<!--button type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success">Manual Update</button-->
									<input type="submit" name="btnAction" id="btnAction" class="mb-xs mt-xs mr-xs btn btn-success" value="Manual Update">
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
