
<script language="JavaScript">
    function validate()
    {
        if(document.itemGoodsReportForm.item.value == "" )
        {
            alert( "Please Select Goods!" );
            document.itemGoodsReportForm.item.focus() ;
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
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal form-bordered" name="itemGoodsReportForm" onsubmit="return(validate());" action="<?php echo site_url("Report/getItemGoodsWiseReport");?>" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Item:<span class="required">*</span></span>
									<select name="item" id="item" class="form-control">
										<option value="" label="---Select Item---" selected style="width:110px;">---Select Item---</option>
										<?php
										foreach($itemList as $goods)
										{
											echo '<option value="'.$goods['id'].'">'.$goods['c_name'].'</option>';
										}
										?>
									</select>
								</div>								
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
