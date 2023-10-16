<script>
    function changeType(val)
    {
        if(val == 'rotation')
        {
            document.getElementById('rotationDiv').style="display:static";
            document.getElementById('gateDiv').style="display:none";
            document.getElementById('fromDiv').style="display:none";
            document.getElementById('toDiv').style="display:none";
        }
        else if(val == 'date')
        {
            document.getElementById('rotationDiv').style="display:none";
            document.getElementById('gateDiv').style="display:none";
            document.getElementById('fromDiv').style="display:static";
            document.getElementById('toDiv').style="display:static";
        }
        else if(val == 'gate')
        {
            document.getElementById('rotationDiv').style="display:none";
            document.getElementById('gateDiv').style="display:static";
            document.getElementById('fromDiv').style="display:static";
            document.getElementById('toDiv').style="display:static";
        }
        else
        {
            document.getElementById('rotationDiv').style="display:static";
            document.getElementById('gateDiv').style="display:none";
            document.getElementById('fromDiv').style="display:none";
            document.getElementById('toDiv').style="display:none";
            document.getElementById('show').style="disabled:disabled";
        }
    }
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
    <div class="row">
        <div class="col-lg-12">						
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('ShedBillController/ladenExportContainer'); ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>

                            <div class="col-md-6">		
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Search By <span class="required">*</span></span>
                                    <select name="searchBy" id="searchBy" class="form-control" onchange="changeType(this.value)">
                                        <option value="rotation">Rotation</option>
                                        <option value="date">Date</option>
                                        <option value="gate">Gate</option>
                                    </select>
                                </div>	
                                
                                <div class="input-group mb-md" id="rotationDiv">
                                    <span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
                                    <input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation No">
                                </div>

                                <div class="input-group mb-md" id="gateDiv" style="display:none;">
                                    <span class="input-group-addon span_width">Gate <span class="required">*</span></span>
                                    <select name="gate" id="gate" class="form-control">
                                        <!-- <option value="select">select</option> -->
                                        <?php for($i=0; $i<count($gateList); $i++){ ?>
                                            <option value="<?php echo $gateList[$i]['GKEY'];?>"><?php echo $gateList[$i]['ID'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="input-group mb-md" id="fromDiv" style="display:none;">
                                    <span class="input-group-addon span_width"> From </span>
                                    <input type="date" name="fromDate" id="fromDate" class="form-control" value="<?php echo date('Y-m-d');?>">
                                </div>
                            
                                <div class="input-group mb-md" id="toDiv" style="display:none;">
                                    <span class="input-group-addon span_width"> To </span>
                                    <input type="date" name="toDate" id="toDate" class="form-control" value="<?php echo date('Y-m-d');?>">
                                </div>
                                
                            </div>

                            <div class="col-md-offset-4 col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="xl">
									<label for="radioExample3">Excel</label>
								</div>
							</div>

							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="html" checked>
									<label for="radioExample3">HTML</label>
								</div>
							</div>

							<div class="col-md-2">
								<div class="radio-custom radio-success">
									<input type="radio" id="fileOptions" name="fileOptions" value="pdf">
									<label for="radioExample3">PDF</label>
								</div>
							</div>
                                                                            
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" name="show" id="show" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
                                </div>													
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <?php echo $msg;?>
                                </div>
                            </div>
                        </div>	
                    </form>
                </div>
            </section>
        </div>
    </div>	
	<!-- end: page -->
</section>
</div>