<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title ;?></h2>
        <div class="right-wrapper pull-right">
        </div>
    </header>
    <!-- start: page -->
	<section class="panel">
        <!--header class="panel-heading">
			<h2 class="panel-title" align="right">
				<a href="<?php echo site_url('/') ?>">
					<button style="margin-left: 35%" class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i>
					</button>
				</a>									
			</h2>								
		</header-->
		<div class="panel-body">
			<form class="form-horizontal form-bordered" method="POST" 
				action="<?php echo site_url('Report/viewVesselListStatus') ?>">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php //echo $msg; ?>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-3">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width"><font color='red'><b>*</b></font> ROTATION NO :</span>
							<input type="text" id="rot_no" name="rot_no" tabindex="1" class="form-control" required>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
						</div>													
					</div>
				</div>	
			</form>
		</div>
	</section>
    <section class="panel">
        <!--header class="panel-heading">
								<h2 class="panel-title" align="right">
									<a href="<?php echo site_url('POSController/LiftingEntryForm') ?>">
										<button style="margin-left: 35%" class="btn btn-primary btn-sm">
											<i class="fa fa-plus"></i>
										</button>
									</a>									
								</h2>								
							</header-->
        <div class="panel-body">
       
            <table class="table table-bordered table-responsive table-hover table-striped mb-none"
                id="datatable-default">
                <thead>
                    <tr>
                        <th class="text-center">Sl</th>
                        <th class="text-center" style="display:none">vvd_gkey</th>
                        <th class="text-center">Vessel Name</th>
                        <th class="text-center">Imp Rot</th>
                        <th class="text-center">Exp Rot</th>
                        <th class="text-center">Agent</th>
                        <th class="text-center">Berth Operator</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">ETA</th>
                        <th class="text-center">ETD</th>
                        <th class="text-center">ATA</th>
                        <th class="text-center">ATD</th>
                        <th class="text-center">Action</th>
                        <?php if($this->session->userdata('Control_Panel')==28) {?>
                        <th class="text-center">Comments</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                        <?php } ?>
                    </tr>
                </thead>

                
                <tbody>
                    <?php 
					for($i=0;$i<count($rtnVesselList);$i++) {
										?>
                    <tr class="gradeX">
                        <td align="center"><?php echo $i+1;?></td>
                        <td align="center" style="display:none"><?php echo $rtnVesselList[$i]['VVD_GKEY'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['NAME'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['IB_VYG'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['OB_VYG'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['AGENT'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['BERTHOP'];?></td>
                        <td align="center" <?php if ($rtnVesselList[$i]['PHASE_NUM']=='20'){ ?> style="background-color:#F6D8CE"
                            <?php } else if($rtnVesselList[$i]['PHASE_NUM']=='30'){?>style="background-color:#F78181"
                            <?php } else if($rtnVesselList[$i]['PHASE_NUM']=='40'){?>style="background-color:#FACC2E"
                            <?php } else if($rtnVesselList[$i]['PHASE_NUM']=='50'){?>style="background-color:#F5A9A9"
                            <?php } else if($rtnVesselList[$i]['PHASE_NUM']=='60'){?>style="background-color:#610B0B"
                            <?php }?>>
                            <?php echo $rtnVesselList[$i]['PHASE_STR'];?>
                        </td>
                        <td align="center"><?php echo $rtnVesselList[$i]['ETA'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['ETD'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['ATA'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['ATD'];?></td>
                        <td align="center">
                            <form style="display:inline"
                                action="<?php echo site_url('Report/myExportImExSummeryView/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']))?>"
                                target="_blank" method="POST">
                                <input class="mb-xs mt-xs mr-xs btn btn-primary" id="VwBtn" type="submit"
                                    value="Summary" />
                            </form>

                            <form style="display:inline margin-top:2%"
                                action="<?php echo site_url('Report/myExportExcelUploadSampleReportView/'.str_replace("/","_",$rtnVesselList[$i]['IB_VYG']))?>"
                                target="_blank" method="POST">
                                <input class="mb-xs mt-xs mr-xs btn btn-success" style="background-color:green"
                                    id="VwBtn" type="submit" value="Details" />
                            </form>
                        </td>
                        <?php if($this->session->userdata('Control_Panel')==28) { ?>
                        <td>
                            <form style="display:inline"
                                action="<?php echo site_url('Report/vesselListWithStatusEntry')?>" method="POST">
                                <?php 
														include_once("mydbPConnectionn4.php");
														$vvd_gkey = $rtnVesselList[$i]['VVD_GKEY'];
														$strGkey = "select pre_comments from ctmsmis.mis_exp_vvd where vvd_gkey='$vvd_gkey'";
														
														$resComment = mysqli_query($con_sparcsn4,$strGkey);
														$rowComment=mysqli_fetch_object($resComment);
														$RcvComment = @$rowComment->pre_comments;
														//echo "test : ".$resComment;
														?>
                                <input style="width:100px;" id="remarks" type="text" name="remarks"  value="<?php echo $RcvComment;?>" />
								<input style="width:100px;" id="rot_num" type="hidden" name="rot_num" value="<?php echo $rtnVesselList[$i]['IB_VYG'];?>" />
                                <input id="vvd_gkey" type="hidden" name="vvd_gkey" value="<?php echo $rtnVesselList[$i]['VVD_GKEY'];?>" />
                        </td>
                        <td>
                            <?php 
														include_once("mydbPConnectionn4.php");
														$vvd_gkey = $rtnVesselList[$i]['VVD_GKEY'];
														$strGkey = "select comments from ctmsmis.mis_exp_vvd where vvd_gkey='$vvd_gkey'";
														
														$resComment = mysqli_query($con_sparcsn4,$strGkey);
														$rowComment=mysqli_fetch_object($resComment);
														$RcvComment = @$rowComment->comments;
														//echo "test : ".$resComment;
														echo $RcvComment;
														?>
                        </td>
                        <td>
                            <input class="login_button" id="SaveBtn" type="submit" value="Save" />
                            </form>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- end: page -->
</section>
</div>