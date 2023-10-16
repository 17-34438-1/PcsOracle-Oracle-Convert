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
								action="<?php echo site_url('report/isoCodeListViewSearchList') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">ISO CODE</span>
											<input type="text" name="rot_num" id="rot_num" class="form-control" value="<?php if($listType=="search"){echo $isoType;} else{echo "";}?>" required>
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
										<th class="text-center">SL</th>
										<th class="text-center">ISO CODE</th>
										<th class="text-center">SIZE</th>
                                        <th class="text-center">HEIGHT</th>
										<th class="text-center">TYPE</th>	
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rtnVesselList);$i++){ ?>
									<tr class="gradeX">
										<td align="center" style="color:red"><?php echo $i+1;?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['cont_iso_type'];?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['cont_size'];?></td>
										<td align="center"><?php echo $rtnVesselList[$i]['cont_height'];?></td>
                                        <td align="center"><?php echo $rtnVesselList[$i]['cont_type'];?></td>
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