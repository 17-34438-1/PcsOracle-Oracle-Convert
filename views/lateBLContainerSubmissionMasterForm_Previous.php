<script>
	function fetchData()
	{
		if (window.XMLHttpRequest) 
		{
		  	xmlhttp=new XMLHttpRequest();
		} 
        else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var rotation=document.getElementById("rotation").value;
		
		var bl=document.getElementById("blNo").value;
		

		if(rotation==""|| bl=="")
		{
			if(rotation=="" && bl!="")
			{
				alert("Rotation is Empty");
			}
			else if(bl=="" && rotation!=="")
			{
				alert("BL No is Empty");
			}
			else
			{
				alert("Both Rotation And BL No are Empty");
			}	
			
		}

		else
		{
			var url="<?php echo site_url('AjaxController/getIgmMasterContainer')?>?rotation="+rotation+"&bl="+bl;
           // alert(url);
			xmlhttp.open("GET",url,false);
            xmlhttp.onreadystatechange=stateChangeAssignment;
            xmlhttp.send();
		}

	}
	function stateChangeAssignment()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			//alert(jsonData);
			var result=jsonData.containers.length;
			//var countResult=jsonData.resultBl.length;
			//alert(rs);
			//alert(jsonData.containers[0].cont_number)
			if(result==0)
			{				
				document.getElementById("container").style.display="none";
				document.getElementById("buttonSearch").style.display="none";
				alert("Invalid Rotation or BL No");
			}
			else
			{
				document.getElementById("container").style.display="block";
				document.getElementById("buttonSearch").style.display="block";
				//document.getElementById("bls").style.display="block";
				for (i=0;i<=result;i++)
				{
					var optn = document.createElement("OPTION");
					optn.text = jsonData.containers[i].cont_number;
					optn.value = jsonData.containers[i].cont_number;  
					document.myform.containerNo.options.add(optn);
				
				}
				/* for (i=0;i< countResult;i++)
				{
					var optn = document.createElement("OPTION");
					optn.text = jsonData.resultBl[i].BL_No;
					optn.value = jsonData.resultBl[i].BL_No;  
					document.myform.blResults.options.add(optn);
				
				} */
			}


		}
}

	function validate()
    {
		var rotation = document.getElementById("rotation").value;
		var prev_blNo = document.getElementById("blNo").value;
		var cur_blNo = document.getElementById("cur_blNo").value;
		var containerNo = document.getElementById("containerNo").value;
		if(rotation == "")
		{
			alert("Please provide rotation!");
			document.getElementById("rotation").focus();
			return false;
		}
		else if(prev_blNo == "" )
		{
			alert("Please provide previous BL!");
			document.getElementById("blNo").focus();
			return false;
		}
		else if(cur_blNo == "" )
		{
			alert("Please Provide current BL!");
			document.getElementById("cur_blNo").focus();
			return false;
		}
		else if(containerNo == "" )
		{
			alert("Please select container!");
			document.getElementById("containerNo").focus();
			return false;
		}
		else
			return true;
	}

</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
			    <section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("igmViewController/UpdateIGMContainerDetailInfo"); ?>" id="myform" name="myform"  onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>

								    <div class="col-md-6">
											<div class="form-group" id="rotation_no">
													<label class="col-md-4 control-label">Rotation :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="rotation" name="rotation"  class="form-control" />
													</div>
											</div>
											<div class="form-group" id="bl_no">
													<label class="col-md-4 control-label">Prev. Master BL :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="blNo" name="blNo" class="form-control"  onblur="fetchData();" />
													</div>
											</div>
											<div class="form-group" id="bl_no">
													<label class="col-md-4 control-label">Current Master BL :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="cur_blNo" name="cur_blNo" class="form-control"   />
													</div>
											</div>	
											<!--div class="form-group" id="bls" style="display:none" >
													<label class="col-md-4 control-label"> House BL No:</label>
													<span class="input-group-addon span_width">Search Value : </span>
													<div class="col-md-8">
														<input type="text" id="container_data" name="containerNo" class="form-control" />
													</div>
													<div class="col-md-8">
													<select name="blResults" id="blResults" class="form-control" onchange="changeBLField(this.value);" >
												        <option value="">--Select--</option>
													
											        </select>
													</div>
											</div-->
											<div class="form-group" id="container" style="display:none" >
													<label class="col-md-4 control-label">Container No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<!--div class="col-md-8">
														<input type="text" id="container_data" name="containerNo" class="form-control" />
													</div-->
													<div class="col-md-8">
													<select name="containerNo" id="containerNo" class="form-control" onchange="changeBLField(this.value);" >
												        <option value="">--Select--</option>
													
											        </select>
													</div>
											</div>
											
											<div class="row" id="buttonSearch" style="display:none" >
												<div class="col-md-9 text-right">
													<button type="submit" name="bl_save" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
												</div>													
											</div>
											<div class="row">
												<div class="col-lg-12 text-center">
													<?php echo $msg;?>
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