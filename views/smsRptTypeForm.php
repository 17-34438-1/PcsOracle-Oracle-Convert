<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title; ?></h2>

        <div class="right-wrapper pull-right">

        </div>
    </header>

    <!-- start: page -->
    <section class="panel">
        <!--header class="panel-heading">
				<h2 class="panel-title" align="right">
					<a href="<?php echo site_url('Controller/EntryForm') ?>">
						<button style="margin-left: 35%" class="btn btn-primary btn-sm">
							<i class="fa fa-plus"></i>
						</button>
					</a>									
				</h2>								
			</header-->
        <div class="panel-body">
            <form class="form-horizontal form-bordered" method="POST" id="myform"
                action="<?php echo site_url('report/smsRptTypeView') ?>" target="_blank">
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Form Date:</span>
                            <input type="date" name="fromdate" id="fromdate" class="form-control">
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">To Date:</span>
                            <input type="date" name="todate" id="todate" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-offset-4 col-md-4">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">SMS Type <span
                                    class="required">*</span></span>
                            <select name="type" id="type" class="form-control">
                                <option value="" selected="selected">--SELECT--</option>
                                <option value="All">All</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Keepdown">Keep Down</option>
                                <option value="GateIn">Gate In</option>
                                <option value="GateOut">Gate Out</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-sm-12 text-center">
                        <button type="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
                    </div>
                </div>
                <div class="form-group">
                </div>
            </form>
        </div>
    </section>
    <!-- end: page -->
</section>
</div>