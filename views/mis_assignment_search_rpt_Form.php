<script type="text/javascript">

function changeTextBox(v)
{
	    var search_value = document.getElementById("search_value");
		var fromdate = document.getElementById("fromdate");
		if(v=="date")
		{
			search_value.value=null;
			search_value.disabled=true;
			fromdate.disabled=false;		
		}
		else if(v=="bl_no")
		{
			fromdate.value=null;
			search_value.disabled=false;
			fromdate.disabled=true;	
		}
		else if(v=="user")
		{
			fromdate.value=null;
			search_value.value=null;
			search_value.disabled=false;
			fromdate.disabled=false;	
		}	
		else if(v=="all")
		{
			fromdate.value=null;
			search_value.value=null;
			search_value.disabled=true;
			fromdate.disabled=true;	
		}			
		else if(v=="")
		{
			search_value.value=null;
			fromdate.value=null;
			search_value.disabled=true;
			fromdate.disabled=true;
		}
		else 
		{
			fromdate.value=null;
			search_value.disabled=false;
			fromdate.disabled=true;			
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/mis_assignment_entry_rpt'; ?>" target="_blank" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
									<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
										<option value="" label="search_by" selected style="width:110px;">---Select-------</option>
										<option value="all" label="All" >All</option>
										<option value="date" label="date" >Date</option>
										<option value="bl_no" label="bl_no" >Bl No</option>
										<option value="user" label="user" >User</option>														
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search value <span class="required">*</span></span>
									<input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search Value">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width" id="lbl_line">Search Date <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>												
							</div>
												
							<!-- <div class="col-md-offset-4 col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="xl" checked>
									<label for="radioExample3">Excel</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="radio-custom radio-success">
									<input type="radio" id="options" name="options" value="html" >
									<label for="radioExample3">HTML</label>
								</div>
							</div> -->

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
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