<script>
 $(document).ready(function(){
	search_value.value="";
	search_value.disabled=true;
 });
 function changeTextBox(v)
    {
		var search_value = document.getElementById("search_value");
		var fromdate = document.getElementById("fromdate");
		var todate = document.getElementById("todate");
		var fromTime = document.getElementById("fromTime");
		var toTime = document.getElementById("toTime");
		if(v=="yard")
		{
			//search_value.value="";
			fromdate.value="";
			todate.value="";
			fromTime.value="";
			toTime.value="";
			
			search_value.disabled=false;
			fromdate.disabled=false;
			todate.disabled=false;
			fromTime.disabled=true;
			toTime.disabled=true;
			
			if (window.XMLHttpRequest) 
			{
			  xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeYardInfo;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getAllYard')?>",false);
						
			xmlhttp.send();
		
		}	
		else if(v=="dateRange")
		{
			search_value.value="";
			
			fromdate.value="";
			todate.value="";
			fromTime.value="";
			toTime.value="";
			
			fromdate.disabled=false;
			todate.disabled=false;
			fromTime.disabled=true;
			toTime.disabled=true;
			search_value.disabled=true;
		}
		else if(v=="dateTime")
		{
			search_value.value="";
			
			fromdate.value="";
			todate.value="";
			fromTime.value="";
			toTime.value="";
			
			fromdate.disabled=false;
			todate.disabled=false;
			fromTime.disabled=false;
			toTime.disabled=false;
			search_value.disabled=true;	
		}
		else{
			search_value.value="";
			fromdate.value="";
			todate.value="";
			fromTime.value="";
			toTime.value="";
			search_value.disabled=true;
			fromdate.disabled=true;
			todate.disabled=true;
			fromTime.disabled=true;
			toTime.disabled=true;
			
		}
		
    }
	function stateChangeYardInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			var val = xmlhttp.responseText;
			
		    //alert(val);
			
			var selectList=document.getElementById("search_value");
			removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(xmlhttp.responseText);
			for (var i = 0; i < jsonData.length; i++) 
			{
				var option = document.createElement('option');
				option.value = jsonData[i].current_position;  //value of option in backend
				option.text = jsonData[i].current_position;	  //text of option in frontend
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
		if( document.myform.ddl_imp_rot_no.value == "" )
         {
            alert( "Please provide Rotation!" );
            document.myform.ddl_imp_rot_no.focus() ;
            return false;
         }
		 if( document.myform.search_by.value == "" )
         {
            alert( "Please provide Search By!" );
            document.myform.search_by.focus() ;
            return false;
         }
		  if( document.myform.search_by.value == "yard" )
         {
             if( document.myform.search_value.value == "" )
			 {
				alert( "Please provide Search Value!" );
				document.myform.search_value.focus() ;
				return false;
			 }
			 else
			 {
				return( true ); 
			 }
         }
		  if( document.myform.search_by.value == "dateRange" )
         {
             if( document.myform.fromdate.value == "" || document.myform.todate.value == "")
			 {
				alert( "Please provide Search Date!" );
				//document.myform.search_by.focus() ;
				return false;
			 }
			 else
			 {
				return( true ); 
			 }
         }
		  if( document.myform.search_by.value == "dateTime" )
         {
             if( document.myform.fromdate.value == "" || document.myform.todate.value == "" || document.myform.fromTime.value == "" || document.myform.toTime.value == "")
			 {
				alert( "Please provide Search Date & Time!" );
				//document.myform.search_by.focus() ;
				return false;
			 }
			 else
			 {
				return( true ); 
			 }
         }
		
         return( true );
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
				
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" name="myform" id="myform"
								action="<?php echo site_url('report/containerDischargeAppsList') ?>" target="_blank">
							
								<div class="form-group">
									<div class="col-md-offset-2 col-md-8">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Import Rotation No:</span>
											<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search By :</span>
											<!--span class="input-group-addon span_width">FLT</span-->
											<select name="search_by" id="search_by" class="form-control" onchange="changeTextBox(this.value);">
												<option value="all" label="search_by" selected>--Select--</option>
												<option value="yard" label="Yard" >YARD</option>
												<option value="dateRange" label="DateRange" >DATE</option>												
												<option value="dateTime" label="DateTime" >DATETIME</option>
											</select> 
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Search Yard :</span>
											<!--span class="input-group-addon span_width">FLT</span-->
											<select name="search_value" id="search_value" class="form-control" disabled>
												<option>--Select--</option>
											</select>
										</div>
									</div>
									<div class="col-md-offset-2 col-md-4" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From Date</span>
												<input type="date" name="fromdate" id="fromdate" class="form-control" value="">
											</div>
										</div>
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To Date</span>
												<input type="date" name="todate" id="todate" class="form-control" value="">
											</div>
										</div>
									</div>
									<div class="col-md-4" style="text-align: center">
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">From (24 Hrs)</span>
												<input type="text" name="fromTime" id="fromTime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'>
											</div>
										</div>
										<div class="col-md-12">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">To (24 Hrs)</span>
												<input type="text" name="toTime" id="toTime" data-plugin-timepicker class="form-control" data-plugin-options='{ "showMeridian": false }'>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									</div>
									<div class="col-md-3">
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
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-center">
										<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
									</div>													
								</div>
							</form>
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">Sl</th>
										<th class="text-center">Container</th>
										<th class="text-center">Rotation</th>
										<th class="text-center">MLO</th>	
										<th class="text-center">ISO</th>	
										<th class="text-center">Status</th>
										<th class="text-center">Disch Time</th>
										<th class="text-center">Yard Pos</th>
										<th class="text-center">Destination</th>
										<th class="text-center">User Id</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$lenth = count($rtnAllList);
										for($i=0;$i<$lenth;$i++) {				
									?>
									<tr class="gradeX">
										<td align="center"> <?php echo $i+1;?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['id']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['rotation']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['mlo']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['iso']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['freight_kind']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['last_update']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['current_position']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['destination']?> </td>
										<td align="center"> <?php echo $rtnAllList[$i]['user_id']?> </td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>