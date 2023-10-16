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
                    <h2 class="panel-title" align="right">

                    </h2>
                </header>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php echo $msg;?>
                        </div>
                    </div>
                    <div class="row ">
                        <form class="form-horizontal form-bordered  " id="myform" method="POST"
                            action="<?php echo site_url('Vessel/saveHotWorkDemandForFire') ?>">
                            <input type="hidden" name="ddl_imp_rot_no" id="txt_login" class="form-control"
                                placeholder="">
                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-6">
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Rotation :</span>
                                        <input type="text" name="rotation" id="txt_login" class="form-control"
                                            placeholder="" required>
                                    </div>
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Service Date :</span>
                                        <input type="date" name="service_date" id="service_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" name="submit_login" id="submit"
                                            class="mb-xs mt-xs mr-xs btn btn-primary">Forward</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </section>

        </div>
    </div>
    <!-- end: page -->
</section>
</div>