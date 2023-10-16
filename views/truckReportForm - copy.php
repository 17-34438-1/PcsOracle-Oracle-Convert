<script type="text/javascript">
	function chkBlankField()
	{		
		if(document.getElementById("truckDate").value=="" )
		{
			alert("Please fill truckDate");
			return false
		}	
		else
		{
			return true;
		}
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/truckReport"); ?>" target="" id="myform" name="myform" onsubmit="return chkBlankField();">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Truck Date <span class="required">*</span></span>
											<input type="date" name="truckDate" id="truckDate" class="form-control" value="<?php date("Y-m-d"); ?>">
                                            <input type="hidden" name="uriSegment" id="uriSegment" value="<?php echo $uriSegment; ?>" />
										</div>												
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" id="searchTruck" name="searchTruck" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
						</div>

						<div id="table-scroll" class="panel-body table-responsive">
                        <?php
                            if(count($rslt_truckReport)>0)
                            {
                                if($uriSegment=="gateIn")
                                {
                            ?>
                            <table class="table table-bordered table-striped table-hover">	
                                <tr class="gridDark" align="center">
                                    <td colspan="5">TRUCK TO GATE IN OF <?php echo $truckDate; ?></td>
                                </tr>
                                <tr class="gridDark" align="center">
                                    <th>Sl.</th>
                                    <th>Truck No</th>
                                    <th>Driver</th>
                                    <th>C&F </th>
                                    <th>Container</th>
                                </tr>
                                <?php
                                for($i=0;$i<count($rslt_truckReport);$i++)
                                {
                                ?>
                                <tr class="gridLight" align="center">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $rslt_truckReport[$i]['truck_id']; ?></td>
                                    <td><?php echo $rslt_truckReport[$i]['driver_name']; ?></td>
                                    <td><?php echo $rslt_truckReport[$i]['cnf_name']; ?></td>
                                    <!--td><?php echo $rslt_truckReport[$i]['cont_number']; ?></td-->
                                    <td>TCNU9699764</td>
                                </tr>
                                <?php
                                }
                                ?>
                                <tr class="gridDark">
                                    <td align="left" colspan="5"><b><?php echo "Total No of Truck : ".count($rslt_truckReport); ?></b></td>
                                </tr>
                                <form name="truckReportDownloadForm" id="truckReportDownloadForm" method="post" action="<?php echo site_url('report/truckReport'); ?>" target="_blank">
                                    <input type="hidden" name="reportDownload" id="reportDownload" value="download" />
                                    <input type="hidden" name="truckDate" id="truckDate" value="<?php echo $truckDate; ?>" />
                                    <input type="hidden" name="uriSegment" id="uriSegment" value="<?php echo $uriSegment; ?>" />
                                    <tr>								
                                        <td class="gridDark" colspan="5" align="center">
                                            <table>
                                                <tr>
                                                    <td align="left">
                                                        <label for="EXCEL" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">EXCEL</label>
                                                        <input type="radio" name="fileOptions" id="fileOptions" value="xl" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" checked />
                                                    </td>
                                                    <td align="left">
                                                        <label for="PDF" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">PDF</label>
                                                        <input type="radio" name="fileOptions" id="fileOptions" value="pdf" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" />
                                                    </td>										
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>								
                                        <td class="gridLight" colspan="5" align="center">
                                            <input type="submit" name="downloadReport" id="downloadReport" value="Download" class="btn btn-primary" />
                                        </td>
                                    </tr>
                                </form>						
                            </table>					
                            <?php
                                }
                                else if($uriSegment=="insidePort")
                                {
                            ?>
                                <table class="table table-bordered table-striped table-hover">	
                                    <tr class="gridDark" align="center">
                                        <td colspan="6">WORKING INSIDE THE PORT OF <?php echo $truckDate; ?></td>
                                    </tr>
                                    <tr class="gridDark" align="center">
                                        <th>Sl.</th>
                                        <th>Truck No</th>
                                        <th>Driver</th>
                                        <th>C&F </th>
                                        <th>Container</th>
                                        <th>Gate In Time</th>
                                    </tr>
                                    <?php
                                    for($i=0;$i<count($rslt_truckReport);$i++)
                                    {
                                    ?>
                                    <tr class="gridLight" align="center">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['truck_id']; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['driver_name']; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['cnf_name']; ?></td>
                                        <!--td><?php echo $rslt_truckReport[$i]['cont_number']; ?></td-->
                                        <td>MRKU4308675</td>
                                        <td><?php echo $rslt_truckReport[$i]['gate_in_time']; ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr class="gridDark">
                                        <td align="left" colspan="6"><b><?php echo "Total No of Truck : ".count($rslt_truckReport); ?></b></td>
                                    </tr>
                                    <form name="truckReportDownloadForm" id="truckReportDownloadForm" method="post" action="<?php echo site_url('report/truckReport'); ?>" target="_blank">
                                        <input type="hidden" name="reportDownload" id="reportDownload" value="download" />
                                        <input type="hidden" name="truckDate" id="truckDate" value="<?php echo $truckDate; ?>" />
                                        <input type="hidden" name="uriSegment" id="uriSegment" value="<?php echo $uriSegment; ?>" />
                                        <tr>								
                                            <td class="gridDark" colspan="6" align="center">
                                                <table>
                                                    <tr>
                                                        <td align="left">
                                                            <label for="EXCEL" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">EXCEL</label>
                                                            <input type="radio" name="fileOptions" id="fileOptions" value="xl" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" checked />
                                                        </td>
                                                        <td align="left">
                                                            <label for="PDF" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">PDF</label>
                                                            <input type="radio" name="fileOptions" id="fileOptions" value="pdf" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" />
                                                        </td>										
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>								
                                            <td class="gridLight" colspan="6" align="center">
                                                <input type="submit" name="downloadReport" id="downloadReport" value="Download" class="btn btn-primary" />
                                            </td>
                                        </tr>
                                    </form>			
                                </table>
                            <?php		
                                }
                                else if($uriSegment=="gateOut")
                                {
                            ?>
                                <table class="table table-bordered table-striped table-hover">	
                                    <tr class="gridDark" align="center">
                                        <td colspan="7">TRUCK GATE OUT OF <?php echo $truckDate; ?></td>
                                    </tr>
                                    <tr class="gridDark" align="center">
                                        <th>Sl.</th>
                                        <th>Truck No</th>
                                        <th>Driver</th>
                                        <th>C&F </th>	
                                        <th>Container</th>
                                        <th>Gate In Time</th>								
                                        <th>Gate Out Time</th>								
                                    </tr>
                                    <?php
                                    for($i=0;$i<count($rslt_truckReport);$i++)
                                    {
                                    ?>
                                    <tr class="gridLight" align="center">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['truck_id']; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['driver_name']; ?></td>
                                        <td><?php echo $rslt_truckReport[$i]['cnf_name']; ?></td>								
                                        <!--td><?php echo $rslt_truckReport[$i]['cont_number']; ?></td-->								
                                        <td>MSKU0140100</td>								
                                        <td><?php echo $rslt_truckReport[$i]['gate_in_time']; ?></td>								
                                        <td><?php echo $rslt_truckReport[$i]['gate_out_time']; ?></td>								
                                    </tr>
                                    <?php
                                    }
                                    ?>	
                                    <tr class="gridDark">
                                        <td align="left" colspan="7"><b><?php echo "Total No of Truck : ".count($rslt_truckReport); ?></b></td>
                                    </tr>
                                    <form name="truckReportDownloadForm" id="truckReportDownloadForm" method="post" action="<?php echo site_url('report/truckReport'); ?>" target="_blank">
                                        <input type="hidden" name="reportDownload" id="reportDownload" value="download" />
                                        <input type="hidden" name="truckDate" id="truckDate" value="<?php echo $truckDate; ?>" />
                                        <input type="hidden" name="uriSegment" id="uriSegment" value="<?php echo $uriSegment; ?>" />
                                        <tr>								
                                            <td class="gridDark" colspan="7" align="center">
                                                <table>
                                                    <tr>
                                                        <td align="left">
                                                            <label for="EXCEL" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">EXCEL</label>
                                                            <input type="radio" name="fileOptions" id="fileOptions" value="xl" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" checked />
                                                        </td>
                                                        <td align="left">
                                                            <label for="PDF" style="font-size:11px;width:100%;margin:0;padding:0;text-align:left;width:75%;">PDF</label>
                                                            <input type="radio" name="fileOptions" id="fileOptions" value="pdf" style="width:2em;border:none;display:block;float:left;margin:0 5px 0 0;padding:0;" />
                                                        </td>										
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>								
                                            <td class="gridLight" colspan="7" align="center">
                                                <input type="submit" name="downloadReport" id="downloadReport" value="Download" class="btn btn-primary" />
                                            </td>
                                        </tr>
                                    </form>					
                                </table>
                            <?php
                                }
                            }
                            ?>
						</div>
					</section>
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 

        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
    </div>
	
  </div>
</section>