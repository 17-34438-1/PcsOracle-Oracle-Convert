<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/yardWiseImportTotalReportPerform'; ?>" target="_blank" id="myform" name="myform">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Date <span class="required">*</span></span>
                                    <input type="date" name="date" id="date" class="form-control" value="<?php date("Y-m-d"); ?>">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
                                    <select name="yard_no" id="yard_no" class="form-control">
                                        <option value="" label="yard_no" selected>Select</option>
                                        <option value="GCB" label="GCB">GCB</option>
                                        <option value="CCT" label="CCT">CCT</option>
                                        <option value="NCT" label="NCT">NCT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" id="submit" name="View" class="mb-xs mt-xs mr-xs btn btn-success login_button">View</button>
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
  