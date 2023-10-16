 
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
		function validate()
      {
		  //alert("test"+document.myform.fromdate.value);
		    if( document.myform.assDt.value == "" )
         {
            alert( "Please provide Assignment Date!" );
            document.myform.assDt.focus() ;
            return false;
         }
		   if( document.myform.fromdate.value == "" )
         {
            alert( "Please provide From Date!" );
            document.myform.fromdate.focus() ;
            return false;
         }
		  if( document.myform.fromTime.value == "" )
         {
            alert( "Please provide From Time!" );
            document.myform.fromTime.focus() ;
            return false;
         }
		  if( document.myform.todate.value == "" )
         {
            alert( "Please provide To Date!" );
            document.myform.todate.focus() ;
            return false;
         }
		  if( document.myform.toTime.value == "" )
         {
            alert( "Please provide To Time!" );
            document.myform.toTime.focus() ;
            return false;
         }
		 return( true );
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/yardWiseContainerDeliveryView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
								<div class="input-group mb-md">
								<span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
									<input type="date" name="assDt" id="assDt" class="form-control" value="<?php date("Y-m-d"); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Terminal <span class="required">*</span></span>
									<select name="yard_no" id="yard_no" class="form-control" onchange="getBlock(this.value)">
										<option value="" label="yard_no" selected style="width:130px;">Select</option>
										<option value="CCT" label="CCT" >CCT</option>
										<option value="NCT" label="NCT" >NCT</option>
										<option value="GCB" label="GCB">GCB</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Block <span class="required">*</span></span>
									<select name="block" id="block" class="form-control">
										<option value="ALL">---Select---</option>																						
									</select>
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>">

									<span class="input-group-addon span_width">From Time <span class="required">*</span></span>
									<input type="text" name="fromTime" id="fromTime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>">

									<span class="input-group-addon span_width">To Time <span class="required">*</span></span>
									<input type="text" name="toTime" id="toTime" class="form-control" value="" placeholder="(HH:MM)(24 hrs)"> 
								</div>												
							</div>
																		
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" id="submit" name="show" value="html" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
									
									<button type="submit" id="submit" name="show" value="xl" class="mb-xs mt-xs mr-xs btn btn-success login_button">Excel</button>
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