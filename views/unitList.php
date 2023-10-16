<script type="text/javascript">
	function validate()
	{
		if( document.myForm.rotation.value == "" )
		{
			alert( "Please provide Reg No!" );
			document.myForm.rotation.focus() ;
			return false;
		}
		
		return true ;
	}
	function del_validate()
	{
		if (confirm("Do you want to detete this entry?") == true)
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
  width:60%;
  align:center;
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
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/ShedBillController/unitListSearch'; ?>" id="myform" name="myform">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Reg No  <span class="required">*</span></span>
											<input type="text" name="rotation" id="rotation" class="form-control" placeholder="Reg No">
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

							<hr/>

							<table  class="table table-bordered table-responsive table-hover table-striped mb-none" style="width:60%;" align="center">
								<thead>
									<tr class="gridDark" align="center">
										<td><b>Serial</b></td>
										<td><b>Reg No</b></td>
										<td ><b>Unit</b></td>						
										<td ><b>Action</b></td>
										<td ><b>Action</b></td>			
									</tr>
								</thead>
									<?php for($i=0;$i<count($rslt_list);$i++){?>
									<tr class="gridLight" align="center">

										<td><?php echo $i+1;?></td>
										<td><?php echo $rslt_list[$i]['rotation'];?></td>
										<td><?php echo $rslt_list[$i]['unit_no'];?></td>
										<td class="gridLight" align="center">
											<form action="<?php echo site_url("ShedBillController/unitListEdit");?>" method="post">
												<input type="hidden" name="rt_no" value="<?php echo $rslt_list[$i]['rotation']?>" />
												<input type="submit" name="edit" value="Edit" class="btn btn-primary" />
											</form>
										</td>
										<td class="gridLight" align="center">
											<form onsubmit="return del_validate()" action="<?php echo site_url("ShedBillController/unitListDelete");?>" method="post">
												<input type="hidden" name="rot" value="<?php echo $rslt_list[$i]['rotation']?>" />
												<input type="hidden" name="unit" value="<?php echo $rslt_list[$i]['unit_no']?>" />
												<input type="submit" name="delete" value="Delete" class="btn btn-danger" />
											</form>
										</td>
									
									</tr>

									<?php } ?>
								</table>

						</div>
					</section>
				</div>
			</div>

		</div>
		 
		</div>
		
       	<!--div id="table-scroll"-->
			
		 <!--/div-->
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>

      <div class="clr"></div>
	</div>
</section>