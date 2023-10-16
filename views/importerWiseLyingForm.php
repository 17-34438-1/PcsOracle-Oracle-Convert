<script type="text/javascript">

function getCriteria(srcCriteria)
	{
		
		if(srcCriteria=="importer")
		{
			document.getElementById("goodsBlock").style.display = "none";
			document.getElementById("importerBlock").style.display = "block";
			document.getElementById("goods_name").value = "";


		}
		if(srcCriteria=="goods")
		{
			document.getElementById("importerBlock").style.display = "none";
			document.getElementById("goodsBlock").style.display = "block";
			document.getElementById("importer").value = "";

		}
		
	}
	
	
    function validate()
    {
        /* if(document.goodsReportForm.item.value == "" )
        {
            alert( "Please Select Commudity!" );
            document.goodsReportForm.item.focus() ;
            return false;
        }
        else */ if( document.goodsReportForm.goodsfromdate.value == "" )
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
					<form class="form-horizontal form-bordered" name= "goodsReportForm" onsubmit="return(validate());" action="<?php echo site_url("Report/importerWiseLyingReport");?>" method="post" target="_blank">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Commodity <span class="required">*</span></span>
									<select name="item" id="item" class="">
										<option value="" label="---Select Commudity---" selected style="width:110px;">---Select Commodity---</option>
										<?php
										/* foreach($goodsList as $goods)
										{
											echo '<option value="'.$goods['id'].'">'.$goods['c_name'].'</option>';
										} */
										?>
									</select>
								</div-->
								<!--div class="input-group mb-md">
									<span class="input-group-addon span_width">Goods Name<span class="required">*</span></span>
									<input type="text" style="width:200px;"  id="goods_value"  name="goods_value" class="form-control login_input_text" />
									
								</div-->
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Category <span class="required">*</span></span>
											<select name="sCriteria" id="sCriteria" class="form-control" onchange="getCriteria(this.value)"; required>
												<option  label="--Select--" selected >--Select--</option>
												<option value="importer">Importer Wise</option>
												<option value="goods">Goods Wise</option>
											</select>									
										
								</div>
								
								
								
								
								<div id="importerBlock" style="display:none">
									<div class="input-group mb-md" >
										<span class="input-group-addon span_width">Importer Name<span class="required">*</span></span>
										<input type="text" id="importer" list="importer"  name="importer" class="form-control login_input_text" />
										<datalist id="importer">
											<?php 
												for($i=0;$i<count($rsltAllQry);$i++)
												{
												  echo '<option value="'.$rsltAllQry[$i]['Notify_name'].'">'.$rsltAllQry[$i]['Notify_name'].'</option>';
												  
												}
											?>
										</datalist>
											
									</div>
								</div>	
								
								<div id="goodsBlock" style="display:none">
									<div class="input-group mb-md" >
										<span class="input-group-addon span_width">Goods Name <span class="required">*</span></span>
										<input type="text"   id="goods_name"   name="goods_name" class="form-control login_input_text" />								
											
									</div>
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
