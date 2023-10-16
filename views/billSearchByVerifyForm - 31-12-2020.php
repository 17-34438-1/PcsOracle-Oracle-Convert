<script>

	function validation()
	{
		if( document.myForm.verifyNo.value == "" )
		{
			alert( "Please! provide verify no" );
			document.myForm.verifyNo.focus() ;
			return false;
		}
		return true;
	}
	
	
	function validate()
	{
	
		var truckNum=document.deliverForm.numTruc.value;
		var truckNum=document.deliverForm.numTruc.value;
		var deliverd_truck=$("#deliverd_truck").val();

		//alert(deliverd_truck);
		 
		for( var i=(deliverd_truck+1); i<(deliverd_truck+2); i++)
		{
			// alert(m+i);
			// alert("ccc  "+document.deliverForm.truck+i.value);
		  //<?php for($i=1; $i<=$rtnVerifyReport[0]['no_of_truck'];$i++) {  ?>

			var tr=$("#truck<?php echo $i;?>").val();
			var pk=$("#pkQty<?php echo $i;?>").val();
			var gate=$("#gateNo<?php echo $i;?>").val();
			//var tr =document.deliverForm.truck+i.value;
			//alert(tr);
			 
			if( tr == "" )
			{  
				alert( "Please! provide truck "+<?php echo $i;?>+ " no" );
				document.deliverForm.truck<?php echo $i;?>.focus() ;
				return false;
			}
			else if(pk=="")
			{
				alert( "Please! provide Package Quantity on the "+tr+" " );
				document.deliverForm.pkQty<?php echo $i;?>.focus() ;
				return false;
			}
			else if(gate=="")
			{
				alert( "Please! Select Gate no for the Truck no:  "+tr+" " );
				document.deliverForm.gateNo<?php echo $i;?>.focus() ;
				return false;
			}
			 
		  //<?php } ?>
			return true;		
		}
	}
	
	
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
<div class="content">
    <div class="content_resize">
		<div class="mainbar">
			<div class="article">
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/ShedBillController/bilSearchByVerifyNumber'; ?>" name="myform" onsubmit="return(validation());" style="padding:12px 20px;">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Verify No: <span class="required">*</span></span>
										<input type="text" name="verifyNo" id="verifyNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Verify No">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="report" id="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-success login_button">Search</button>
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
        
				<div class="clr"></div>
			</div>
			
	<?php if($search==1){?>		
	<div class="img">
	<form name= "deliverForm"  onsubmit="return(validate());" action="<?php echo site_url('ShedBillController/deliver');?>"  method="POST" onload="" >
	
		<input type="hidden" style="width:140px;" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
		<input type="hidden" style="width:140px;" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>"> 
		<input type="hidden" style="width:140px;" id="verifyNo" name="verifyNo" value="<?php echo $rtnVerifyReport[0]['verify_number']?>"> 
		<div class="col-md-8"> 
			<table class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left"  id="mytbl" style="margin-left:20px;">
				<tr class="gridDark">
					<td><nobr>Vessel Name</nobr></td>	
					<td>:</td>	
					<td><nobr><?php echo $rtnVerifyReport[0]['vessel_name']?></nobr></td>
					<td>Rotation</td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['import_rotation']?></td>			
					<td>B/L No</td>	
					<td>:</td>	
					<td><nobr><?php echo $rtnVerifyReport[0]['bl_no']?></nobr></td>
				</tr>	
				<tr class="gridDark">
					<td><nobr>IGM Quantity</nobr></td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['Pack_Number']?></td>
					<td>IGM Unit</td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['Pack_Description']?></td>
					<td>Container</td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['cont_number']?></td>
				</tr>	
				<tr class="gridDark">
					<td><nobr>Recieved Quantity</nobr></td>	
					<td>:</td>	
					<td><?php echo @$rtnVerifyReport[0]['rcv_pack']?></td>
					<td>Recieved Unit</td>	
					<td>:</td>	
					<td><?php echo @$rtnVerifyReport[0]['rcv_unit']?></td>
					<td><nobr>Payment Status</nobr></td>	
					<td>:</td>	
					<td><nobr><?php if($rtnVerifyReport[0]['paid_status']=='Paid')
						{?>
						<font color='light green'><?php echo $rtnVerifyReport[0]['paid_status']?></font>
						<?php } if( $rtnVerifyReport[0]['paid_status']=='Not Paid')
						{?>
						<font color='red'><?php echo $rtnVerifyReport[0]['paid_status']?></font>
						<?php }?>
					</nobr></td>
				</tr>
				<tr class="gridDark"> 	 
					<td>Goods Description</td>	
					<td>:</td>	
					<td colspan="7" style="font-size:11px"><?php echo $rtnVerifyReport[0]['Description_of_Goods']?></td>	
				</tr>
				<tr class="gridDark"> 
					<td>Verify No</td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['verify_number']?></td>	
					<td><nobr>Number of Truck</nobr></td>	
					<td>:</td>				
					<td colspan="4"><?php echo $rtnVerifyReport[0]['no_of_truck'];?></td>	
					<input type="hidden" style="" id="numTruc" name="numTruc"  value="<?php echo $rtnVerifyReport[0]['no_of_truck']?>" readonly>
					<!--td><input type="hidden" style="" name="numTruc" id="numTruc" onload="addDynamicRow(this.value=""></td-->		
				</tr>
			</table >
		</div>
		<?php if($tblFlag==1) {?>
				   <!--DO Flag-->
		   <?php  if($doShowFlag==1) {?>
		<div class="col-md-6">
			<table class="table table-bordered table-responsive table-hover table-striped mb-none" cellspacing="2" cellpadding="2" align="left"  id="mytbl" style="margin-left:20px;">
				<tr class="gridDark">
					<th>Serial</th>
					<th>:</th>
					<th><nobr>Truck ID</nobr></th>
					<th><nobr>Package Qty</nobr></th>
					<th><nobr>Gate</nobr></th>
				<tr>
					<?php for($i=0; $i<count($doInfo);$i++) {  ?>
				<tr style="background-color:#cfd8d8">
					<th><nobr>Truck <?php echo $i+1;?></nobr></th>
					<td>:</td>
				
					<td align ="center" style="width:200px" ><nobr><?php echo $doInfo [$i]['truck_id'];?></nobr></td>
					<td align ="center" style="width:240px;"><nobr><?php echo $doInfo [$i]['delv_pack'];?></nobr></td>
					<td align ="center" style="width:200px;"><nobr><?php echo $doInfo [$i]['gate_no'];?></nobr></td>
				</tr>
					 <?php }?>
					
					<!--<?php if($dlv_btn_status==1){?>			
					<?php for($i=($deliverd_truck+1); $i<($deliverd_truck+2);$i++) {
						if(($rtnVerifyReport[0]['no_of_truck'])>=($deliverd_truck+1)){
					?>
				<tr style="background-color:#cfd8d8">
					<th><nobr>Truck <?php echo $i;?></nobr></th>
					<td>:</td>
					<td><input style="width:100px;" type="text" id="truck<?php echo $i;?>"  name="truck<?php echo $i;?>"></td>
					<td><input style="width:140px;" type="text" id="pkQty<?php echo $i;?>" name="pkQty<?php echo $i;?>"></td>
					<td>
						<select name="gateNo<?php echo $i;?>" id="gateNo<?php echo $i;?>" value="" >  
								<option value="">--Select----</option>
								<option value="CCT-1">CCT-1</option> 
								<option value="CCT-2">CCT-2</option> 
								<option value="CCT-2">CCT-3</option> 
								<option value="NCT">NCT</option> 
								<option value="Gate-5">Gate-5</option> 
								<option value="Gate-4">Gate-4</option> 
								<option value="CPAR">CPAR</option> 
											
						</select>	
					</td>
					
				</tr>
				<tr><td><input type="hidden" name="deliverd_truck" id="deliverd_truck" value="<?php echo $deliverd_truck;?>" readonly></td></tr>
					<?php } } }?> -->
			</table>
	   <?php }?>
	   <?php }?>
		</div>
		<div class="col-md-7"> 
			<table class="table table-responsive" style="margin-left:70px;">
				<tr>
					<td  align="left">
						<?php $bl= str_replace("/","_",$rtnVerifyReport[0]['bl_no']);?>
						<button type="button" class="btn btn-primary">	
						<a href="<?php echo site_url('report/deliveryEntryFormByWHClerk/'.$bl.'/'.$rtnVerifyReport[0]['import_rotation']).'/doForm';?>" class="login_button" style="text-decoration: none;padding:4px;font-size:12px; color:white;" target="_blank">VERIFY info</a>
						</button>
					</td>
					<td>
						<button type="button" class="btn btn-primary">
						<a href="<?php echo site_url('ShedBillController/getShedBillPdf/'.$rtnVerifyReport[0]['verify_number']);?>" class="login_button"  style="text-decoration: none;padding:4px;font-size:12px;color:white;" target="_blank" ><nobr>BILL info</nobr></a>
						</button>
					</td>
					<td>
						<?php if($dlv_btn_status==1){
								if($rtnVerifyReport[0]['paid_status']=="Paid"){?>			
									<input type="hidden" name="sendVerifyNo" value="numTruc">						
									<button type="button" value="DELIVER" name="deliver" class="btn btn_primary login_button">	
						<?php }}?>            
					</td>
				</tr>
			</table>
		</div>
	 <table align="center">				
	   <tr align="center">
           <?php if(@$msgFlag==1){?>
			<td><font color="green">
			<nobr><?php echo $msg;  ?></nobr>
			</font>
			</td>
		   <?php } else if(@$msgFlag==2){?>
			<td><font color="red">
			<nobr><?php echo $msg;  ?></nobr>
			</font>
			</td>
		   <?php }?>
	   </tr>
	 </table>
	</form>
	</div>
	<?php }?>	
      
      </div>
      <!-- <div class="sidebar">
    <?php include_once("mySideBar.php"); ?>
   </div> -->
      <div class="clr"></div>
    </div>
 <?php echo form_close()?>
  </div>
</section>