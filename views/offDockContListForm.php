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
                <header class="panel-heading">
                    
                </header>
                <div class="panel-body">
                    <form class="form-horizontal form-bordered" method="POST"
                        action="<?php echo site_url('report/offDockContListViews') ?>" target="_blank">
                        <input type="hidden" name="get" value="no">
                        
                        <div class="form-group">
                            <!-- <label class="col-lg-3 control-label">&nbsp;</label> -->
                            <div class="col-lg-offset-3 col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Search by:</span>
                                    <select name="positon" class="form-control" required>
                                        <option value="">----Select----</option>
                                        <option value="S20_INBOUND">INBOUND</option>
                                        <option value="S60_LOADED">LOADED</option>
                                        <!--option value="S70_DEPARTED">Departed</option-->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="xl" checked>
                                    <label>Excel</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="html">
                                    <label>HTML</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-11 text-center">
                                <button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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