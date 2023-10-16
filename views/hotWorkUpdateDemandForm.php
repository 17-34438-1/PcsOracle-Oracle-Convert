<!--<script>

    function validate()
    {
        rotation = $("#rotation").val().trim();
        service_date = $("#service_date").val().trim();
        firemanHRS = $("#firemanHRS").val().trim();
        if(!rotation)
		{
            alert("Rotation not given!");
            return false;
        }
		else if(!service_date)
		{
            alert("Service date not given!");
            return false;
        }
		else if(!firemanHRS)
		{
            alert("Fireman work hour not given!");
            return false;
        }
        else
        {
           if(confirm("Please, once again carefully check. These information will be saved in N4 also."))
			{
				return true;
			}
			else			
			{
				return false;
			}
			return false
		}
        return false;
    }
	
</script>-->

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
                            <?php //echo //$frmType;//echo $msg;?>
                        </div>
                    </div>
                    <div class="row ">
                        <form class="form-horizontal form-bordered  " id="myform" method="POST" action="<?php echo site_url('Vessel/updateHotWorkDemandExtended') ?>" onsubmit="return validate('Are you sure?')">

                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-6">
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Rotation :</span>
										<input type="text" name="rotation" id="rotation" class="form-control" 
												placeholder="Rotation" 
												value="<?php if($frmType=="edit") echo $rotation; else echo "";?>"
												<?php if($frmType=="edit") echo "readonly"; ?>
										>
                                        <!--input type="text" name="rotation" id="rotation" class="form-control" placeholder="Rotation" required-->
                                    </div>
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Service Date :</span>
										<input type="date" name="service_date" id="service_date" class="form-control" 
												placeholder="Service Date" 
												value="<?php if($frmType=="edit") echo $service_date; else echo "";?>" required>
                                        <!--input type="date" name="service_date" id="service_date" placeholder="Service Date" class="form-control" required-->
                                    </div>	
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Fireman (HRS) :</span>
                                        <input type="number" name="firemanHRS" id="firemanHRS" placeholder="Fireman(HRS)" value="<?php if($frmType=="edit") echo $firemanHRS; else "";  ?>" class="form-control">
                                    </div>	
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Fire Pump (HRS) :</span>
                                        <input type="number" name="firepumpHRS" id="firepumpHRS" placeholder="Fire Pump (HRS)" value="<?php if($frmType=="edit") echo $firepumpHRS; else "";  ?>"; class="form-control">
                                    </div>	
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Fire Officer (HRS) :</span>
                                        <input type="number" name="fireofficerHRS" id="fireofficerHRS" placeholder="Fire Officer (HRS)" value="<?php if($frmType=="edit") echo $fireofficerHRS; else "";  ?>" class="form-control">
                                    </div>
									<div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Fire Engine (HRS) :</span>
                                        <input type="number" name="fireEngineHRS" id="fireEngineHRS" placeholder="Fire Engine (HRS)" value="<?php if($frmType=="edit") echo $fireEngineHRS; else "";  ?>"  class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" name="submit_login" id="submit"
                                            class="mb-xs mt-xs mr-xs btn btn-primary">Save</button>
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