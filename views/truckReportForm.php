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
        </section>

        <?php
            if(count($rslt_truckReport)>0)
            {
        ?>
        <section class="panel">
        <div class="panel-body table-responsive">
		<div class="row">
			<div class="col-md-12">
				<?php echo $msg;?>
			</div>
		</div>
                <table class="table table-bordered table-striped table-hover">	
                    <tr class="gridDark" align="center">
                    <?php
                        $colspan = 0;
                        if($uriSegment=="insidePort") 
                        {
                            $colspan = 9;
                        }
                        else if($uriSegment=="gateOut" || $uriSegment=="total") 
                        {
                            $colspan = 10;
                        }
                        else if($uriSegment=="notpaid") 
                        {
                            $colspan = 7;
                        }
                        else
                        {
                            $colspan = 8;
                        }
                    ?>
                        
                        <th colspan="<?php echo $colspan; ?>"><?php echo $title." of ".$truckDate; ?></th>
                        
                        
                    </tr>
                    <tr class="gridDark" align="center">
                        <th>Sl.</th>
                        <th>Visit ID</th>
                        <th>Truck No</th>
                        <th>Driver</th>
                        <th>Helper</th>
                        <th>C&F </th>
                        <th>Container</th>

                        <?php if($uriSegment != "notpaid" ){ ?>
                            <th>Payment Colleted By</th>
                        <?php } ?>

                        <?php if($uriSegment == "insidePort" || $uriSegment == "gateOut" || $uriSegment=="total"){ ?>
                            <th>Gate In Time</th>
                        <?php } ?>

                        <?php if($uriSegment=="gateOut" || $uriSegment=="total"){ ?>
                            <th>Gate Out Time</th>
                        <?php } ?>

                    </tr>
                    <?php
                    for($i=0;$i<count($rslt_truckReport);$i++)
                    {
                    ?>
                    <tr class="gridLight" align="center">
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo $rslt_truckReport[$i]['trucVisitId']; ?></td>
                        <td><?php echo $rslt_truckReport[$i]['truck_id']; ?></td>
                        <td><?php echo $rslt_truckReport[$i]['driver_name']; ?></td>
                        <td><?php echo $rslt_truckReport[$i]['assistant_name']; ?></td>

                        <td><?php echo $rslt_truckReport[$i]['cnf_name']; ?></td>
                        <td><?php echo $rslt_truckReport[$i]['cont_no']; ?></td>

                        <?php if($uriSegment!="notpaid" ){ ?>
                            <td><?php if($rslt_truckReport[$i]['paid_status']==1){echo $rslt_truckReport[$i]['paid_collect_by'];} ?></td>
                        <?php } ?>

                        <?php if($uriSegment=="insidePort" || $uriSegment=="gateOut" || $uriSegment=="total") { ?>
                            <td><?php echo $rslt_truckReport[$i]['gate_in_time']; ?></td>
                        <?php
                            } 
                        ?>

                        <?php if($uriSegment=="gateOut" || $uriSegment=="total") { ?>
                            <td><?php echo $rslt_truckReport[$i]['gate_out_time']; ?></td>
                        <?php
                            } 
                        ?>

                    </tr>
                    <?php
                    }
                    ?>
                    <tr class="gridDark">
                    <th colspan="<?php echo $colspan; ?>"><b><?php echo "Total No of Truck : ".count($rslt_truckReport); ?></b></th>

                    </tr>
                    <form name="truckReportDownloadForm" id="truckReportDownloadForm" method="post" action="<?php echo site_url('report/truckReport'); ?>" target="_blank">
                        <input type="hidden" name="reportDownload" id="reportDownload" value="download" />
                        <input type="hidden" name="truckDate" id="truckDate" value="<?php echo $truckDate; ?>" />
                        <input type="hidden" name="uriSegment" id="uriSegment" value="<?php echo $uriSegment; ?>" />
                        <tr>								
                            <td class="gridDark" colspan="<?php echo $colspan; ?>" align="center">
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
                            <td class="gridLight" colspan="<?php echo $colspan;?>" align="center">
                                <input type="submit" name="downloadReport" id="downloadReport" value="Download" class="btn btn-primary" />
                            </td>
                        </tr>
                    </form>						
                </table>					
            </div>
        </section>
        <?php
            }
        ?>
    </div>
</section>
</div>