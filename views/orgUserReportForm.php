<script type="text/javascript">

function changeTextBox(v)
{
	   var ain_no = document.getElementById("ain_no");
		//var fromdate = document.getElementById("fromdate");
		if(v=="single")
		{
			ain_no.disabled=false;
		}
		else 
		{
			ain_no.value=null;
			ain_no.disabled=true;		
		}	
}
</script>
<style>
     #table-scroll {
	  height:500px;
	  width: 1000px;
	  overflow:auto;  
	  margin-top:0px;
      }
</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
  
  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/orgUserReportView'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Organization Type<span class="required">*</span></span>
									<select name="org_type" id="org_type" class="form-control">
										<option value="" selected style="width:110px;">----Select----</option>
										<?php for($i=0; $i<count($orgTypeList); $i++) { ?>
										<option value="<?php echo $orgTypeList[$i]['id']; ?>"><?php echo $orgTypeList[$i]['Org_Type']; ?></option>	
										<?php } ?>	
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Category<span class="required">*</span></span>
									<select name="org_cat" id="org_cat" class="form-control" onchange="changeTextBox(this.value);">
											<option value="single">Single</option>	
											<option value="all">All</option>	
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" id="lbl_line">AIN No<span class="required">*</span></span>
									<input type="text" name="ain_no" id="ain_no" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>												
							</div>											

							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" id="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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