<script type="text/javascript">
    function validate()
    {
        if(document.goodsReportForm.item.value == "" )
        {
            alert( "Please Select Commudity!" );
            document.goodsReportForm.item.focus() ;
            return false;
        }
        else if( document.goodsReportForm.goodsfromdate.value == "" )
        {
            alert( "Please Select From Date" );
            document.goodsReportForm.goodsfromdate.focus() ;
            return false;
        }else if(document.goodsReportForm.goodstodate.value == "")
        {
            alert( "Please Select To Date" );
            document.goodsReportForm.goodstodate.focus() ;
            return false;
        }else if(document.goodsReportForm.options.value == "")
        {
            alert( "Please Select SUMMARY/DETAILS" );
//            document.goodsReportForm.options.focus() ;
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
					<form class="form-horizontal form-bordered" name= "goodsReportForm" onsubmit="return(validate());" action="<?php echo site_url("report/itemWiseSummaryDetailsFormAction");?>" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Commudity:<span class="required">*</span></span>
									<select name="item" id="item" class="">
										<option value="" label="---Select Commudity---" selected style="width:110px;">---Select Commudity---</option>
										<?php
										foreach($goodsList as $goods)
										{
											echo '<option value="'.$goods['id'].'">'.$goods['c_name'].'</option>';
										}
										?>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Importer Name:<span class="required">*</span></span>
									<input type="text" style="width:250px;"  id="search_value"  name="search_value" list="forBill" class="form-control login_input_text" />
									<datalist id="forBill">
										<?php
										include_once("mydbPConnection.php");
										
										$strrcvAllQry = "SELECT DISTINCT  igm_details.Notify_name FROM igm_details 
												WHERE Notify_name!='' AND Notify_name IS NOT NULL ORDER BY Notify_name ASC ";
										$resrcvAllT = mysqli_query($con_cchaportdb,$strrcvAllQry);
										
										while($rowrcvAllQry=mysqli_fetch_object($resrcvAllT))
										{
											echo '<option value="'.$rowrcvAllQry->Notify_name.'">'.$rowrcvAllQry->Notify_name.'</option>';
										}
										?>
									</datalist>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date:<span class="required">*</span></span>
									<input type="date" style="width:200px;" id="goodsfromdate" name="goodsfromdate" class="form-control login_input_text"/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date:<span class="required">*</span></span>
									<input type="date" style="width:200px;" id="goodstodate" name="goodstodate" class="form-control login_input_text"/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Group By:<span class="required">*</span></span>
									<select name="group_by" id="group_by" class="" >
										<option value="" label="---Select---" selected style="width:110px;">---Select-------</option>
										<option value="rotation" label="Rotation" >Rotation</option>
										<option value="importer" label="Importer" >Importer</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="summary"> SUMMARY
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="details"> DETAILS
									</label>
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
