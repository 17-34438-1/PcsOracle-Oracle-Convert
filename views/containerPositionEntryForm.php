<script>
	function getContainerInfo(val)
	{
		//alert(val);

			if (window.XMLHttpRequest) 
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=stateChangeContainerValue;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/getContainerInfo')?>?cont_number="+val,false);
			xmlhttp.send();
		
	}
	function stateChangeContainerValue()
	{
		//alert("ddfd");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
				  
			var val = xmlhttp.responseText;
			//alert(val);
			var jsonData = JSON.parse(val);
			//alert(jsonData);
			var cont_info_div=document.getElementById("contInfoDiv");
			var cont_size=document.getElementById("cont_size");
			var cont_height=document.getElementById("cont_height");
			var cont_status=document.getElementById("cont_status");
			var cont_rot=document.getElementById("cont_rotation");
			
			var cont_size_hd=document.getElementById("cont_size_hd");
			var cont_height_hd=document.getElementById("cont_height_hd");
			var cont_status_hd=document.getElementById("cont_status_hd");
			var cont_rotation_hd=document.getElementById("cont_rotation_hd");
		
			for (var i = 0; i < jsonData.length; i++) 
			{	
				cont_size.innerHTML=jsonData[i].cont_size;
				cont_height.innerHTML=jsonData[i].cont_height;
				cont_status.innerHTML=jsonData[i].cont_status;
				cont_rot.innerHTML=jsonData[i].ib_vyg;
				
				cont_size_hd.value=jsonData[i].cont_size;
				cont_height_hd.value=jsonData[i].cont_height;
				cont_status_hd.value=jsonData[i].cont_status;
				cont_rotation_hd.value=jsonData[i].ib_vyg;
				
			}
			cont_info_div.style.display="block";
		}
	}
	function validate()
      {
		  //alert("OK");
		if( document.myChkForm.cont_no.value == "" )
         {
            alert( "Please provide Container No!" );
            document.myChkForm.cont_no.focus() ;
            return false;
         }
		 if( document.myChkForm.cont_position.value == "" )
         {
            alert( "Please provide Container Position!" );
            document.myChkForm.cont_position.focus() ;
            return false;
         }
		  if( document.myChkForm.cont_position.value == "Container Receive" ||  document.myChkForm.cont_position.value == "Empty Container Remove" ||  document.myChkForm.cont_position.value == "On Chasis Delivery")
         {
			 if( document.myChkForm.trailer_no.value == "" ) 
			 {
				alert( "Please provide Trailer No!" );
				document.myChkForm.trailer_no.focus() ;
				return false;
			 } 
			 else
			 {
				  if( document.myChkForm.cont_size_hd.value == "" &&  document.myChkForm.cont_height_hd.value == "")
				 {
						alert( "Please Provide Correct Container No" );
						document.myChkForm.cont_position.focus() ;
						return false;
				 }
				 else{
					 return true;
				 }
			 }
		 }
		  if( document.myChkForm.cont_size_hd.value == "" &&  document.myChkForm.cont_height_hd.value == "")
         {
				alert( "Please Provide Correct Container No" );
				document.myChkForm.cont_position.focus() ;
				return false;
		 }
		 else{
			 return true;
		 }
         return( true );
      }
	 function showTrailer()
	 {
		 var trailer_tr=document.getElementById("trailer_vw");
		 var trailer_no=document.getElementById("trailer_no");
		 
		  if( document.myChkForm.cont_position.value == "Container Receive" ||  document.myChkForm.cont_position.value == "Empty Container Remove" ||  document.myChkForm.cont_position.value == "On Chasis Delivery")
         {
           trailer_tr.style.display="inline"; 
		   trailer_no.value="";
         }
		 else
		 {
			  trailer_tr.style.display="none";
			  trailer_no.value="";
		 }
	 }
 </script>
<style>
input { 
    width: 100px;
}
input:focus {
    background-color: #F3F781;
}
select:focus {
    background-color: #F3F781;
}
 table {border-collapse: collapse;}
			 .left{
					width:280px;
					float:left;										
					font-size: 10px;
					color:black;
				}
				.right{
					margin-left:300px;
					font-size: 10px;
					color:black;
				}
</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="row">

		<div class="col-lg-6">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/containerPositionToDB'; ?>" target="_blank" id="myChkForm" name="myChkForm" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
								<div class="input-group mb-md">
									ENTRY INFO
								</div>	
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">CONTAINER NO <span class="required">*</span></span>
									<input type="text" name="cont_no" id="cont_no" class="form-control" onblur="return getContainerInfo(this.value)" placeholder="Container no">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Action <span class="required">*</span></span>
									<select name="cont_position" id="cont_position" class="form-control" onchange="showTrailer()">
										<option value="" label="cont_position">--Select--</option>
										<option value="Delivery Stay" label="Delivery Stay" >Delivery Stay</option>
										<option value="Delivery Cancel" label="Delivery Cancel" >Delivery Cancel</option>											
										<option value="Container Receive" label="Container Receive" >Container Receive</option>
										<option value="Empty Container Remove" label="Empty Container Remove" >Empty Container Remove</option>
										<option value="On Chasis Delivery" label="On Chasis Delivery" >On Chasis Delivery</option>
										<option value="Empty Lying YARD" label="Empty Lying YARD" >Empty Lying(YARD)</option>
										<option value="Bidder delivery Auction" label="Bidder delivery Auction" >Bidder delivery / Auction</option>
										<option value="Custom Appraise" label="Custom Appraise" >Custom Appraise</option>
										<option value="C&F Delivery" label="C&F Delivery" >C&F Delivery</option>	
										<option value="Container inventory" label="Container inventory" >Container inventory</option>
									</select>
								</div>												
							</div>

							<div class="row" id="trailer_vw" style="display:none;">
								<div class="col-sm-12 text-center">
									<span class="input-group-addon span_width">TRAILER NO <span class="required">*</span></span>
									<input type="text" name="trailer_no" id="trailer_no" class="form-control" placeholder="Trailer no">
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
								</div>													
							</div>
							
						</div>	
					</form>
				</div>
			</section>
		</div>

		<div class="col-lg-6" id="contInfoDiv" style="display:none;">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/listOfNotStrippedContainerView'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-8">
								<div class="input-group mb-md">
									CONTAINER INFO
								</div>

								<table class="table table-bordered table-responsive table-hover table-striped mb-none">
									<tr>
										<td>SIZE</td>
										<td><label id="cont_size"></label></td>					
										<input type="hidden" id="cont_size_hd" name="cont_size_hd"/>					
									</tr>
									<tr>
										<td>HEIGHT</td>
										<td><label id="cont_height"></label></td>
										<input type="hidden" id="cont_height_hd" name="cont_height_hd"/>	
									</tr>
									<tr>
										<td>STATUS</td>
										<td><label id="cont_status"></label></td>
										<input type="hidden" id="cont_status_hd" name="cont_status_hd"/>	
									</tr>
									<tr>
										<td>ROTATION</td>
										<td><label id="cont_rotation"></label></td>
										<input type="hidden" id="cont_rotation_hd" name="cont_rotation_hd"/>	
									</tr>
								</table>												
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>

	</div>

	<?php
		$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
	?>
       <div style="">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none">
			<tr  align="center" class="gridDark">
				<th>SL</th>
				<th>CONTAINER</th>
				<th>ACTION</th>
				<th>TRAILER NO</th>
				<th>ROTATION</th>
				<th>SIZE</th>
				<th>HEIGHT</th>
				<th>STATUS</th>
				<th>ACTION</th>
			</tr>
			<?php for($i=0;$i<count($rtnSearchList);$i++) {?>
			<tr  align="center" class="gridLight">
				<td><?php echo $i+1;?></td>
				<td><?php echo $rtnSearchList[$i]['cont_number'] ?></td>
				<td><?php echo $rtnSearchList[$i]['position'] ?></td>
				<td><?php echo $rtnSearchList[$i]['trailer_no'] ?></td>
				<td><?php echo $rtnSearchList[$i]['rotation'] ?></td>
				<td><?php echo $rtnSearchList[$i]['cont_size'] ?></td>
				<td><?php echo $rtnSearchList[$i]['cont_height'] ?></td>
				<td><?php echo $rtnSearchList[$i]['cont_status'] ?></td>
				<td style="padding:5px;">
				<form action="<?php echo base_url().'index.php/Report/containerPositionDelete'; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete Container Position?');">
					<input type="hidden" name="cont_move_id" value="<?php echo $rtnSearchList[$i]['id']; ?>">
					<button type="submit" class="login_button btn-primary" name="submit" value="Delete">Delete</button>
				</form>
				</td>	
			</tr>
			<?php }?>
		</table>
		</div>
</section>