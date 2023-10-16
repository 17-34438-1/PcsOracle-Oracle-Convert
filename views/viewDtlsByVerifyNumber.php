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
		<?php 
			$containerNo = "";
			if($cont_status!="FCL")
				$containerNo = $rtnVerifyReport[0]['cont_number'];
			else if($cont_status=="FCL")
				$containerNo = $containerSet;
		?>
	<div class="img">
		<input type="hidden" style="width:140px;" id="contNo" name="contNo" value="<?php echo $rtnVerifyReport[0]['cont_number']?>"> 
		<input type="hidden" style="width:140px;" id="rotNo" name="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']?>"> 
		<input type="hidden" style="width:140px;" id="verifyNo" name="verifyNo" value="<?php echo $rtnVerifyReport[0]['verify_number']?>"> 
		<input type="hidden" style="width:140px;" id="fclFlagValue" name="fclFlagValue" value="<?php echo $fclFlagValue ?>">
		<div class="col-md-offset-1 col-md-8"> 
			<table class="table table-bordered table-responsive mb-none" cellspacing="2" cellpadding="2" align="center">
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
					<td>
						<?php 
							if($cont_status!="FCL")
								echo $rtnVerifyReport[0]['cont_number'];
							else if($cont_status=="FCL")
								echo $containerSet;
						?>
					</td>
				</tr>	
				<tr class="gridDark">
					<?php  if($fclFlagValue != 1){ ?>
					<td><nobr>Recieved Quantity</nobr></td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['rcv_pack']?></td>
					<td>Recieved Unit</td>	
					<td>:</td>	
					<td><?php echo $rtnVerifyReport[0]['rcv_unit']?></td>
					<?php } ?>
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
	</div>
	<?php }?>	
      
      </div>
	  <div class="col-md-offset-4 col-md-3"> 
			<table align="center">
				<tr>
					<?php if($rtnVerifyReport[0]['keepdown_st']==0) { ?>
					<td>
						<form method="post" action="<?php echo site_url('ShedBillController/updateKeepDownStatus'); ?>" >
							<input type="hidden" name="igmDtlId" id="igmDtlId" value="<?php echo $rtnVerifyReport[0]['igmDetailId']; ?>" />
							<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rtnVerifyReport[0]['import_rotation']; ?>" />
							<input type="hidden" name="contNo" id="contNo" value="<?php echo $containerNo; ?>" />
							<input type="hidden" name="igm_dtl_cont_id" id="igm_dtl_cont_id" 
								value="<?php echo $rtnVerifyReport[0]['igm_dtl_cont_id']; ?>" />							
							<button type="submit" class="btn btn-primary">
								Keep Down
							</button>
						</form>						
					</td>
					<?php } else { ?>
					<th style="color:red;background-color:white;">Keep Down Process Done !</th>
					<?php } ?>
				</tr>
			</table>
		</div>
      <div class="clr"></div>
    </div>
 <?php echo form_close()?>
  </div>
</section>