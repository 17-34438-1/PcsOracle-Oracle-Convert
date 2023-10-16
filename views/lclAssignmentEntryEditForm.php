<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/CfsModule/lclAssignmentEntryEditList'; ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Assignment Date: <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" placeholder="Assignment Date" value="<?php if($edit=="1"){ echo $dt;} else echo DATE('Y-m-d'); ?>">
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

                    <?php if($edit==1) { ?>	
			
                        <form onsubmit="return(validation());" action="<?php echo site_url("CfsModule/lclAssignmentEntryEditListAction");?>"  method="post">
                        <?php  $len=count($lclAssignmentList)?>
                        <input type="hidden" class="read" style="width:20px;"  id="tbl_lenth" name="tbl_lenth" value="<?php echo $len; ?>" >

                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <th>SL</th>
                                <th>Cont No</th>
                                <th>Size</th>
                                <th>Height</th>
                                <th>Rotation</th>
                                <th><nobr>Assign Date</nobr></th>
                                <th><nobr>Cont.at</nobr></th>
                                <th><nobr>Cargo at</nobr></th>
                                <th>Remarks</th>
                            </tr>

                            <?php
				   
                                for($i=0;$i<count($lclAssignmentList);$i++) { 
                                
                                ?>
                                <tr>
                                    <td>
                                        <input type="text" class="read" style="width:30px;"  id="sl<?php echo $i; ?>" name="sl<?php echo $i; ?>" value="<?php echo $lclAssignmentList[$i]['sl']?>" > 
                                        <input type="hidden" class="read" style="width:20px;"  id="icl_id<?php echo $i; ?>" name="icl_id<?php echo $i; ?>" value="<?php echo $lclAssignmentList[$i]['id']?>" > 
                                    </td>
                
                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['cont_number']?>
                                    </td>

                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['cont_size']?>
                                    </td>

                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['cont_height']?>
                                    </td>

                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['Import_Rotation_No']?>
                                    </td> 
                
                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['assignment_date']?>
                                    </td>   		  
                    
                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['cont_loc_shed']?>
                                    </td> 

                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['cargo_loc_shed']?>
                                    </td> 

                                    <td align="center">
                                        <?php echo $lclAssignmentList[$i]['remarks']?>
                                    </td>
                                
                                </tr>
                            <?php
                                }
                            ?>
                            
                            <tr>
                                <td colspan="9" align="center">
                                    <input type="submit" value="Update" class="btn btn-success"/> </nobr>
                                </td>
                            </tr>

                        </table>
                    </form>
                <?php } ?>

					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>