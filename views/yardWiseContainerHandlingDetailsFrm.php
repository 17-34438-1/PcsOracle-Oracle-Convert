 
 <script type="text/javascript">
  $(function() {
    $("#equipment").change(function() {
        if ($(this).val() == "All") {
			//console.log(false);
            $("#srcVal").attr("disabled", "disabled");
			$("#srcVal").val("");
        }
        else {
            //console.log(true);
            $("#srcVal").removeAttr("disabled");
        }
    });
});

  function getBlock(yard)
	{		
	
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeYardInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getBlockCpa')?>?yard="+yard,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeYardInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		    //alert(val);
			
			var selectList=document.getElementById("block");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].block;  //value of option in backend
				option.text = jsonData[i].block;	  //text of option in frontend
				selectList.appendChild(option);
			}										
		}
	}
		 function removeOptions(selectbox)
		{
			var i;
			for(i=selectbox.options.length-1;i>=1;i--)
			{
				selectbox.remove(i);
			}
		}
	
 </script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
  <div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/yardWiseContainerHandlingDetailsView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TERMINAL: <span class="required">*</span></span>
									<select name="yard_no" id="yard_no" class="form-control" onchange="getBlock(this.value)"; required>
										<option value="" label="yard_no" selected>---Select---</option>
										<option value="CCT" label="CCT">CCT</option>
										<option value="NCT" label="NCT">NCT</option>
										<option value="GCB" label="GCB">GCB</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BLOCK: <span class="required">*</span></span>
									<select name="block" id="block" class="form-control">
										<option value="ALL">---Select---</option>
									</select>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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