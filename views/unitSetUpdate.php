<script type="text/javascript">
	function validate()
	{
		if( document.myForm.rotation.value == "" )
		{
			alert( "Please provide Reg No!" );
			document.myForm.rotation.focus() ;
			return false;
		}
		if( document.myForm.unit.value == "" )
		{
			alert( "Please provide Unit No!" );
			document.myForm.unit.focus() ;
			return false;
		}
		return true ;
	}
</script> 
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
    <div class="row">
        <div class="col-lg-12">						
            <section class="panel">
                <div class="panel-body">
				<table><tr><td><?php echo $msg; ?></td></tr></table>
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/ReleaseOrderController/unitSetUpdatePerform'; ?>" id="myform" name="myform" onsubmit="return(validate());" style="padding:12px 20px;">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">		
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Reg No <span class="required">*</span></span>

                                    <?php 
                                    if($value==1)
                                    {
                                    ?>

                                        <input type="text" name="rotation" id="rotation" class="form-control login_input_text" autofocus= "autofocus" value="<?php echo $rotation?>" readonly>

                                    <?php
                                    }
                                    else if($value==0)
                                    {
                                    ?>

                                        <input type="text" name="rotation" id="rotation" class="form-control login_input_text" autofocus= "autofocus" placeholder="Reg No" value="<?php if(isset($rot)){echo $rot;} ?>">

                                    <?php
                                    }
                                    ?>

                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Unit <span class="required">*</span></span>
                                    <input type="text" name="unit" id="unit" class="form-control login_input_text" tabindex="1" placeholder="Unit">
                                </div>												
                            </div>
                                                                            
                            <div class="row">
                                <div class="col-sm-12 text-center">

                                    <?php 
                                    if($value==1)
                                    {
                                    ?>
                                        <button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>    
                                    <?php
                                    }
                                    else if($value==0)
                                    {
                                    ?>
                                        <button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>    
                                    <?php
                                    }
                                    ?>

                                </div>													
                            </div>
                            
                        </div>	
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>