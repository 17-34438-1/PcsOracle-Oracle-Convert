<script type="text/javascript">
	function changeTextBox(v)
	{		
		var search_value = document.getElementById("search_value");
		var impoter_level = document.getElementById("impoter_level");
		
		search_value.style.display = v == "YES" ? "block" : "none";
		impoter_level.style.display = v == "YES" ? "block" : "none";		
	}

    function validate()
    {
        if( document.goodsReportForm.options.value == "" )
        {
            alert( "Please Select Excel Or HTML Option!");
            return false;
        }
		
        return( true );
    }
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<!-- <header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header> -->
				<div class="panel-body">					
					<form class="form-horizontal form-bordered" name= "goodsReportForm"  action="<?php echo site_url("report/get45DaysLyingReportSummaryAction");?>" method="post" onsubmit="return validate()" target="_blank">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<?php echo $msg; ?>
								</div>
								<div class="input-group mb-md">
									<label class="checkbox-inline">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="xl"> Excel
									</label>
									<label class="checkbox-inline">
										<input type="radio" id="options" name="options" value="html" checked> HTML
									</label>
								</div>																
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit" id="submit" value="Details" class="mb-xs mt-xs mr-xs btn btn-success">Details</button>
								</div>
								<div class="col-sm-12 text-center">								
									<button type="submit" name="submit" id="submit" value="Summary" class="mb-xs mt-xs mr-xs btn btn-success">Summary</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
