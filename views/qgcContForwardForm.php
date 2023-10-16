<script>
	function subm(f,newtarget)
	{
		document.myform.target = newtarget ;
		f.submit();
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
				 <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php echo $msg;?>
                        </div>
                    </div>
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/qgcContForward'); ?>" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							
							
							<!--div class="col-md-8">                                
                                <div class="input-group mb-md">
                                    <?php echo $msg; ?>
                                </div>									
							</div-->
							
							<div class="col-md-8">                                
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
                                    <input style="width:400px" type="text" name="impRot" id="impRot" class="form-control" value="">
                                </div>									
							</div>
							
							<div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<!--input type="radio" id="btnStatus" name="btnStatus" value="show" checked>
									<label for="radioExample3">Show</label-->
									<button type="submit" id="submit" name="submit" value="show" onclick="subm(this.form,'_blank');"  class=" btn btn-success login_button">View</button>
								</div>
							</div>
							
                            <div class="col-md-2">
								<div class="radio-custom radio-success">
									<!--input type="radio" id="btnStatus" name="btnStatus" value="confirm">
									<label for="radioExample3">Confirm</label-->
									<button type="submit" id="submit" name="submit" value="confirm" onclick="subm(this.form,'_self');" class=" btn btn-success login_button">Forward</button>
								</div>
							</div>

                            <br/><br/>

                            <!--div class="col-md-offset-3 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div-->
                            <!--div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div-->
																		
							<!--div class="row">
								<div class="col-sm-12 text-center">
									
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
								</div>													
							</div-->
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
				
			<section class="panel">
				<div class="panel-body">
				    
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr class="gridDark">
								<th class="text-center">Sl.NO</th>
								<th class="text-center">Rotation</th>
                                <th class="text-center">Vessel Name</th>								
								<th class="text-center">Forward Info</th>									
								<!--th class="text-center">Forward At</th-->									
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i=0;$i<count($rslt_qgcFwdList);$i++) 
								{
							?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1; ?></td>
										<td align="center"><?php echo $rslt_qgcFwdList[$i]['rotation']; ?></td>
										<td align="center"><?php echo $rslt_qgcFwdList[$i]['vsl_name']; ?></td>
										<!--td align="center"><?php echo $rslt_qgcFwdList[$i]['forward_by']; ?></td-->
										<!--td align="center"><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?></td-->
                                        <td align="center"><b>Forward by :
											</b><?php echo $rslt_qgcFwdList[$i]['forward_by']; ?><br>
											&nbsp; &nbsp; &nbsp;<b>Forward At :
                                    </b><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?>
                                        </td>										
									</tr>
							<?php 
								} 
							?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>	

</section>