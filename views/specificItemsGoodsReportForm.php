<script>
    function validate(){
        var rotation = document.getElementById("rotation").value;
        if(rotation == ""){
            alert("Please Provide rotation");
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

        <div class="row">
            <div class="col-lg-12">						
                <section class="panel">
                    
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <?php //echo $msg;?>
                            </div>
                        </div>
                        <form class="form-horizontal form-bordered" method="POST" onsubmit="return validate();"
                            action="<?php echo site_url("Report/specificItemsGoodsReportView");?>" target="_blank">
                            <div class="form-group">
                                <div class="col-md-6">
                                    
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">From Date <span class="required">*</span></span>
                                        <input style="width:250px" type="date" class="form-control" id="frmDt" name="frmDt" />
                                    </div>
                                    
                                </div> 
								<div class="col-md-6">
                                  
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">To Date <span class="required">*</span></span>
                                        <input style="width:250px" type="date" class="form-control" id="toDt" name="toDt" />
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
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
                                            Show
                                        </button>
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
	</div>