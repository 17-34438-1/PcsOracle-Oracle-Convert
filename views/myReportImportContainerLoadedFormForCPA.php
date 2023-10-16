
   <script>
	function rtnFunc(val)
	{
		
		if(val=="rotation")
		{
			document.getElementById("fromdate").disabled=true;
			document.getElementById("fromTime").disabled=true;
			document.getElementById("todate").disabled=true;
			document.getElementById("toTime").disabled=true;
			document.getElementById("ddl_imp_rot_no").disabled=false;
		}		
		else if(val=="date")
		{
			document.getElementById("fromdate").disabled=false;
			document.getElementById("fromTime").disabled=false;
			document.getElementById("todate").disabled=false;
			document.getElementById("toTime").disabled=false;
			document.getElementById("ddl_imp_rot_no").disabled=true;
		}
		else
		{
			document.getElementById("fromdate").disabled=true;
			document.getElementById("fromTime").disabled=true;
			document.getElementById("todate").disabled=true;
			document.getElementById("toTime").disabled=true;
			document.getElementById("ddl_imp_rot_no").disabled=false;
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
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/chkMloWiseFinalDischargingImport'; ?>" target="_blank" id="myform" name="myform">
                        <div class="form-group">
                            <label class="col-md-2 control-label">&nbsp;</label>
                            <div class="col-md-8">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
                                    <input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" placeholder="Rotation No">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">From Date <span class="required">*</span></span>
                                    <input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">

                                    <span class="input-group-addon span_width">From Time <span class="required">*</span></span>
                                    <input type="text" name="fromTime" id="fromTime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">To Date <span class="required">*</span></span>
                                    <input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>">

                                    <span class="input-group-addon span_width">To Time <span class="required">*</span></span>
                                    <input type="text" name="toTime" id="toTime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)">
                                </div>
                            </div>
                            <div class="col-md-offset-4 col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="xl">
                                    <label for="radioExample3">Excel</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="html" checked>
                                    <label for="radioExample3">HTML</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" id="submit" name="detail" class="mb-xs mt-xs mr-xs btn btn-success login_button">Detail</button>
                                    <button type="submit" id="submit" name="summary" class="mb-xs mt-xs mr-xs btn btn-success login_button">Summary</button>
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
        </div>
    </div>


</section>