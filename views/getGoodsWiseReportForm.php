<script language="JavaScript">
    // function validate()
    // {
		// if(document.goodsReportForm.item.value == "" )
		// {
			// alert( "Please Select Goods!" );
			// document.goodsReportForm.item.focus() ;
			// return false;
		// }
		// else if( document.goodsReportForm.goodsfromdate.value == "" )
		// {
			// alert( "Please Select From Date" );
			// document.goodsReportForm.goodsfromdate.focus() ;
			// return false;
		// }else if(document.goodsReportForm.goodstodate.value == "")
		// {
			// alert( "Please Select To Date" );
			// document.goodsReportForm.goodstodate.focus() ;
			// return false;
		// }
		// return true ;
    // }
	
	function validate()
    {
		if( document.goodsReportForm.goodsfromdate.value == "" )
		{
			alert( "Please Select From Date" );
			document.goodsReportForm.goodsfromdate.focus() ;
			return false;
		}else if(document.goodsReportForm.goodstodate.value == "")
		{
			alert( "Please Select To Date" );
			document.goodsReportForm.goodstodate.focus() ;
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
					<form class="form-horizontal form-bordered" name= "goodsReportForm" onsubmit="return(validate());" action="<?php echo site_url("Report/getGoodsWiseReport");?>" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">										
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Item:<span class="required">*</span></span>
									<select name="item" id="item" class="">
										<option value="" label="---Select Item---" selected style="width:110px;">---Select Item---</option>
										<?php
										foreach($goodsList as $goods)
										{
											echo '<option value="'.$goods['id'].'">'.$goods['c_name'].'</option>';
										}
										?>
									</select>
								</div-->
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date:<span class="required">*</span></span>
									<input type="date" style="width:200px;" id="goodsfromdate" name="goodsfromdate" class="form-control login_input_text" />
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date:<span class="required">*</span></span>
									<input type="date" style="width:200px;" id="goodstodate" name="goodstodate" class="form-control login_input_text" />
								</div>		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Yard Active:<span class="required">*</span></span>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="YES"> YES
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="NO"> NO
									</label>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Good's Name:<span class="required">*</span></span>
									<input type="text" id="searchText" name="searchText" > 
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
