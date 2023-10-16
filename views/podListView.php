<script>
    function validate(){
        var pod = document.getElementById('rot_num').value;
        if(pod == null){
            alert("Please give a  value...");
            return false;
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
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" onsubmit="return validate();"
								action="<?php echo site_url('report/podListViewSearchList') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">POD CODE</span>
											<input type="text" name="rot_num" id="rot_num" class="form-control" value="<?php if($listType=="search"){echo $podCode;} else{echo "";}?>" required>
										</div>
									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
                            
                            <hr/>
														
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL.</th>
										<th class="text-center">POD CODE</th>
										<th class="text-center">POD NAME</th>	
										<th class="text-center" style="display:none;">&nbsp;</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnVesselList);$i++){ ?>
									<tr class="gradeX">
										<td align="center" style="color:red"><?php echo $i+1;?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['PLACE_CODE'];?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['PLACE_NAME'];?></td>
										<td align="center" style="display:none;"><?php echo $rtnVesselList[$i]['ID'];?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
							
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>