<script>
function validate()
    {
		var rotation = document.getElementById("rotation").value;
		var prev_blNo = document.getElementById("prev_blNo").value;
		var cur_blNo = document.getElementById("cur_blNo").value;
		var containerNo = document.getElementById("containerNo").value;
	//	var editType = document.getElementById("editType").value;

		if(rotation == "")
		{
			alert("Please provide rotation!");
			return false;
		}
		else if(prev_blNo == "" )
		{
			alert("Please provide previous BL!");
			return false;
		}
		else if(cur_blNo == "" )
		{
			alert("Please Provide current BL!");
			return false;
		}
		else if(containerNo == "" )
		{
			alert("Please select container!");
			return false;
		}
	}

 
</script>
<div>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
			    <section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("igmViewController/UpdateMasterContainerInfo");?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								    <div class="col-md-6">
											<div class="form-group" id="rotation">
													<label class="col-md-4 control-label">Rotation :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="rotation" name="rotation"  class="form-control" 
														value="<?php echo  $result[0]['Import_Rotation_No'];?>" />
													</div>
											</div>
											<div class="form-group" id="bl_no">
													<label class="col-md-4 control-label">Previous BL No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="prev_blNo" name="prev_blNo" class="form-control"
														value="<?php echo  $result[0]['BL_No'];?>" />
													</div>
											</div>
											<div class="form-group" id="bl_no">
													<label class="col-md-4 control-label">Current BL No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="cur_blNo" name="cur_blNo" class="form-control"
														value="<?php echo  $result[0]['BL_No'];?>" />
													</div>
											</div>
											
											<div class="form-group" id="container_no"  >
													<label class="col-md-4 control-label">Container No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="containerNo" name="containerNo" class="form-control" 
														value="<?php echo  $result[0]['cont_number'];?>"/>
													</div>
											</div>
											
											<div class="row"  id="buttonSearch"  >
												<div class="col-md-9 text-right">
													<button type="submit" name="bl_save" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
												</div>													
											</div>
											
							        </div>
							</div>	
						</form>
					</div>
			    </section>
			</div>
		</div>
	<!-- end: page -->
</section>
</div>