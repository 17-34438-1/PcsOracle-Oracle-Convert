<script>
function getMloName(rotation)
	{		
	//alert("OK : "+rotation);
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeMloName;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getMloByRotation')?>?rotation="+rotation,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeMloName()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		   // alert(val);
			
			var selectList=document.getElementById("mloName");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(jsonData);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].mlocode;  //value of option in backend
				option.text = jsonData[i].mlocode;	  //text of option in frontend
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
		
function validate()
      {
		  //alert("OK");
		if( document.myform.er_date.value == "" )
         {
            alert( "Please provide Rotation Number!" );
            document.myform.er_date.focus() ;
            return false;
         }
		 else{
			 return( true );
		 }
	  }
</script>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<!--header class="panel-heading">
							<h2 class="panel-title" align="right">
								<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
									<button style="margin-left: 35%" class="btn btn-primary btn-sm">
										<i class="fa fa-list"></i>
									</button>
								</a-->
							<!--/h2>								
						</header-->
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" name="myform" id="myform" target="_blank"
								action="<?php echo site_url('report/vatStatusList') ?>" onsubmit="return validate()">
								<div class="form-group">
									<div class="col-md-offset-3 col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No :</span>
											<input type="text" name="rotation_no" id="rotation_no" onblur="getMloName(this.value);" class="form-control">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Select MLO :</span>
											<select name="mloName" id="mloName" class="form-control">
												<option value="">--ALL MLO--</option>
											</select> 
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									</div>
									<div class="col-md-3">
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
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
										<!--input type="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success"/-->
									</div>													
								</div>
								<!--div class="form-group">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
									</div>													
								</div-->
							</form>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>