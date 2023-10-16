<!doctype html>
<script>
    function validate()
    {

        if( document.myForm.fromdate.value == "" )
        {
            alert( "Please provide From Date!" );
            document.myForm.fromdate.focus() ;
            return false;
        }
        if( document.myForm.todate.value == "" )
        {
            alert( "Please provide To Date!" );
            //document.myForm.todate.focus() ;
            return false;
        }
        return( true );
    }
</script>
<style>
    label
    {
        color: black;
    }
</style>
<html class="fixed">
<head>

    <?php include("cssAssetsList.php"); ?>
</head>

<body>
<section class="body">

    <section role="main" class="content-body">

        <header class="page-header">
            <h2><?php echo $title; ?></h2>
        </header>

        <!--        <div class="row">-->
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"></h2>
                </header>
                <div class="panel-body">
                    <form name="myForm" id="myForm"  class="form-horizontal form-bordered"
                          action="<?php echo site_url("ShedBillController/shedBillSummaryRptView");?>" method="post" onsubmit="return validate()" target="_blank">
                        <div class="form-group">
                            <label class="col-md-3 control-label">From Date : </label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
                                    <input type="date" id="fromdate" name="fromdate" value="" class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><nobr> To Date: </nobr></label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
                                    <input type="date" id="todate" name="todate" value="" class="form-control" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="xl" checked>
                                    <label for="radioExample3">Excel</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="html" >
                                    <label for="radioExample3">HTML</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12" align="center">
                                <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary" value="Show">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!--        </div>-->

    </section>
    <?php
    include("jsAssetsList.php");
    ?>

</section>
</body>
</html>
