<script>
function cpaForward(){
	if (confirm("Do you want to forward?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
}

function searchValidate(){
	if(document.getElementById("searchType").value==""){
        alert("Please select any search type");
		return false;
    }
	else{
		return true;
	}
	
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/searchQgcContForwardedListForCPA'; ?>"  id="myform" name="myform"  onsubmit="return searchValidate()" >
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Type <span class="required">*</span></span>
                                    <select name="searchType" id="searchType" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="rotation">Rotation</option>
										<!--option value="date">Date</option-->
										
                                    </select>
								</div>	
                                <div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
                                    <input type="text" name="searchValue" id="searchValue" class="form-control" >
                                    
								</div>
									
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="show" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
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


            <section class="panel">
                <div class="panel-body">
                <div class="row">
						<div class="col-sm-12 text-center">
						<?php echo $msg;?>
						</div>													
				</div>
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr class="gridDark">
                                <th class="text-center">Sl.NO</th>
                                <th class="text-center">Rotation</th>
                                <th class="text-center">Vessel Name</th>
                                <th class="text-center">Forward Info</th>
                                <!--th class="text-center">Forward At</th-->
                                <th class="text-center">Action</th>
                                <th class="text-center">Action</th>
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
                                <td align="center"><b>Forward by : </b><?php echo $rslt_qgcFwdList[$i]['forward_by']; ?><br>
                                                   &nbsp; &nbsp; &nbsp;<b>Forward At : </b><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?>
                                </td>
                                <!--td align="center"><?php echo $rslt_qgcFwdList[$i]['forward_at']; ?></td-->
                                <td align="center">
                                    <form action="<?php echo site_url("Report/qqcContForwardReportForCPA")?>"
                                        method="POST"  target="_BLANK">
                                        <input type="hidden" name="impRot"
                                            value="<?php  echo $rslt_qgcFwdList[$i]['rotation']; ?>" />
                                        <input class="btn btn-xs btn-primary" type="submit" name="view" value="View" />
                                    </form>
                                </td>
                                <?php
                                 if($rslt_qgcFwdList[$i]['traffic_forward_st']==0){
                                 ?>
                                <td align="center">
                                   
                                    <form action="<?php echo site_url("Report/qgcContForwardForAccount")?>"
                                        method="POST" onsubmit="return cpaForward()">
                                        <input type="hidden" name="forwardId"
                                            value="<?php  echo $rslt_qgcFwdList[$i]['id']; ?>" />
                                            <input type="hidden" name="rotation"
                                            value="<?php  echo $rslt_qgcFwdList[$i]['rotation']; ?>" />
                                            <input type="hidden" name="vsl_name"
                                            value="<?php  echo $rslt_qgcFwdList[$i]['vsl_name']; ?>" />
                                        <input class="btn btn-xs btn-info" name="forward" type="submit"
                                            value="Forward" />
                                    </form>   
                                </td>
                                <?php } else { ?>
                                    <td align="center"><b>Forwarded</b></td>
                                <?php } ?>    

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