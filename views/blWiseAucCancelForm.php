<script>
function chkBlankField() {
    if (document.blWiseAucCanForm.rot_no.value == "") {
        alert("Please provide workplace in English");
        return false;
    } else if (document.blWiseAucCanForm.bl_no.value == "") {
        alert("Please provide workplace in Bengali");
        return false;
    } else {
        if (confirm("Do you want to Cancel this Auction?"))
            return true;
        else
            return false;
    }

    return false;
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
                    <form class="form-horizontal form-bordered" method="POST"
                        action="<?php echo base_url().'index.php/Report/blWiseAucCancelAction'; ?>"
                        id="blWiseAucCanForm" name="blWiseAucCanForm" onsubmit="return chkBlankField()">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Rotation No <span
                                            class="required">*</span></span>
                                    <input type="text" id="rot_no" name="rot_no" id="txt_login" class="form-control"
                                        placeholder="Rotation No">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">BL No <span
                                            class="required">*</span></span>
                                    <input type="text" id="bl_no" name="bl_no" id="txt_login" class="form-control"
                                        placeholder="BL No">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" id="submit" name="report"
                                        class="mb-xs mt-xs mr-xs btn btn-success">Auction Cancel</button>
                                </div>
                                <div class="col-sm-12 text-center">
                                    <h4><?php echo $msg; ?></h4>
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