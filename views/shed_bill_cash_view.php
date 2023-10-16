<!doctype html>
<script>
    function validate()
    {

        if( document.myForm.search_by.value == "" )
        {
            alert( "Please provide Search Type!" );
            document.myForm.search_by.focus() ;
            return false;
        }
        if( document.myForm.search_value.value == "" )
        {
            alert( "Please provide Search Type Value!" );
            //document.myForm.search_value.focus() ;
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
<!--html class="fixed">
<head-->

    <?php //include("cssAssetsList.php"); ?>
<!--/head>

<body-->
  <section class="body">
    <?php
    //include("headerTop.php");
    ?>

        <!-- start: sidebar -->
        <?php
        //include("contentMenu.php");
        ?>
        <!-- end: sidebar -->
<section role="main" class="content-body">
<!--    --><?php
//    include("headerTop.php");
//    ?>
            <header class="page-header">
                <h2><?php echo $title; ?></h2>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title"></h2>
                        </header>
                        <div class="panel-body">
                            <form name="myForm" id="myForm"  class="form-horizontal form-bordered"
                                  action="<?php echo site_url("ShedBillController/shedBillList");?>" method="post" onsubmit="return validate()">

								<div class="form-group">
								<label class="col-md-4 control-label" for="inputSuccess">Total Amount :</label>
                                    <div class="col-md-4">
                                        <label><b><?php echo $total_amt;?></b></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="inputSuccess">Bank Name :</label>
                                    <div class="col-md-4">
                                        <select class="form-control input-sm mb-md" name="search_by" id="search_by">
                                            <option value="" label="--Select--" selected >--Select--</option>
                                            <option value="1" label="Janata Bank Limited">Janata Bank Limited</option>
                                            <option value="2" label="Agrani Bank Limited">Agrani Bank Limited</option>
                                            <option value="3" label="One Bank Limited">One Bank Limited</option>
                                            <option value="4" label="Brack Bank Limited">Brack Bank Limited</option>
                                            <option value="5" label="NRBC Bank">NRBC Bank</option>

                                        </select>
                                    </div>

                                    <!--label class="col-md-2 control-label" for="inputDefault">Value</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm mb-md" type="text" id="search_value" name="search_value" value="">
                                    </div-->
                                </div>



                                <div class="form-group">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary" value="Submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

    <!-- <div class="row">
        <div class="col-lg-12"> -->

</section>

<?php
	//include("jsAssetsList.php");
?>

</section>
<!--/body>
</html-->
