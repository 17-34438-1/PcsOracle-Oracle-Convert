<script>
	function validate()
	{
		if(document.getElementById('house_bl_no').value=="" || document.getElementById('house_bl_no').value==null)
		{
			alert('House BL not found.');
			return false;
		}
		else if(document.getElementById('igmDetailId').value=="" || document.getElementById('igmDetailId').value==null)
		{
			alert('Wrong IGM info.');
			return false;
		}
		else
		{
			return true;
		}
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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("igmViewController/fetchContainerInfo");?>" id="myform" name="myform" onsubmit="return validate()">
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
													<label class="col-md-4 control-label">Master BL No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id=blNo" name="blNo" class="form-control"
														value="<?php echo  $result[0]['BL_No'];?>" />
													</div>
											</div>
											<div class="form-group" id="hb_bl"  >
													<label class="col-md-4 control-label"> House BL No :</label>
													<!--span class="input-group-addon span_width">Search Value : </span-->
													<div class="col-md-8">
														<input type="text" id="hb_bl_no" name="blResults" class="form-control" 
														value="<?php echo $houseBlNo;?>"/>
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
													<button type="submit" name="bl_save" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
												</div>													
											</div>
											
							        </div>
							</div>	
						</form>
					</div>
			    </section>
			</div>
		</div>

		<div class="row" id="bl_form">
			<div class="col-md-12">						
				<section class="panel">
				<div class="form-group" align="center"><h3><b>Container Information </b><h3></div>
				</section>
		 </div>

		 <div class="row">
	<?php include("mydbPConnection.php"); ?>
	<div class="col-lg-12">					
		<div class="panel-body">
			<section class="panel">
			<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("igmViewController/SaveContainerInfo");?>"  id="myform" name="myform" onsubmit="return(validate());">
			    <input type="hidden" id="rotation" name="rotation"  class="form-control" 
			    value="<?php echo  $result[0]['Import_Rotation_No'];?>" />
				<input type="hidden" id=blNo" name="blNo" class="form-control"
				value="<?php echo  $result[0]['BL_No'];?>" />
				<input type="hidden" id="containerNo" name="containerNo" class="form-control" 
				value="<?php echo  $result[0]['cont_number'];?>"/>
				    
				<div class="form-group" >
				<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md" >
							<span class="input-group-addon span_width">House Bl No<span class="required">*</span></span>
							<input type="hidden" name="house_bl_no" id="house_bl_no" class="form-control" autofocus  tabindex="2" value="<?php echo  $houseBlNo;?>" placeholder="Container No">
						</div>
				</div>
				<div class="col-md-6"  style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Igm Detail Id <span class="required">*</span></span>
							<input type="hidden" name="igmDetailId" id="igmDetailId" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['igm_detail_id'];?>" placeholder=">Igm Detail Id">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Number <span class="required">*</span></span>
							<input type="text" name="containerNumber" id="containerNumber" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_number'];?>" placeholder="Container No">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Size <span class="required">*</span></span>
							<!--input type="text" name="containerSize" id="containerSize" class="form-control" autofocus  tabindex="2" value="<?php //echo  $result[0]['cont_size'];?>" placeholder="Container Size"-->
							<select name="containerSize" id="containerSize" class="form-control" placeholder="Container Size">
												        <!--option value="<?php //echo  $result[0]['cont_size'];?>"><?php // echo  $result[0]['cont_size'];?></option-->

														<option value="20" <?php if ($result[0]['cont_size']==20){echo "selected";}?>>20</option>
														<option value="40" <?php if ($result[0]['cont_size']==40){echo "selected";}?>>40 </option>
														<option value="45" <?php if ($result[0]['cont_size']==45){echo "selected";}?>>45 </option>
													
				           </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Gross Weight <span class="required">*</span></span>
							<input type="text" name="containerGrossWeight" id="containerGrossWeight" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_gross_weight'];?>" placeholder=">Container Gross Weight">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Weight <span class="required">*</span></span>
							<input type="text" name="containerWeight" id="containerWeight" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_weight'];?>" placeholder="Container Weight">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Seal Number <span class="required">*</span></span>
							<input type="text" name="containerSealNumber" id="containerSealNumber" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_seal_number'];?>" placeholder="Container Seal Number">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Status <span class="required">*</span></span>
							<!--input type="text" name="containerStatus" id="containerStatus" class="form-control" autofocus  tabindex="2" value="<?php // echo  $result[0]['cont_status'];?>" placeholder="Container Status" -->
							<select name="containerStatus" id="containerStatus" class="form-control" placeholder="Container Status">
												        <!-- option value="<?php //echo  $result[0]['cont_status'];?>"><?php //echo  $result[0]['cont_status'];?></option-->

														<option value="FCL" <?php if ($result[0]['cont_status']=='FCL'){echo "selected";}?>>FCL</option>
														<option value="LCL"<?php if ($result[0]['cont_status']=='LCL'){echo "selected";}?>>LCL</option>
														<option value="FCL/PART" <?php if ($result[0]['cont_status']=='FCL/PART'){echo "selected";}?>>FCL/PART</option>
													
				           </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Height <span class="required">*</span></span>
							<!--input type="text" name="containerHeight" id="containerHeight" class="form-control" autofocus  tabindex="2" value="<?php //echo  $result[0]['cont_height'];?>" placeholder=">Container Height"-->
							<select name="containerHeight" id="containerHeight" class="form-control" placeholder="Container  Height">
												        <!--option value="<?php //echo  $result[0]['cont_height'];?>"><?php // echo  $result[0]['cont_height'];?></option-->
														
														<option value="8.6" <?php if ($result[0]['cont_height']==8.6){echo "selected";}?>>8.6</option>
														<option value="9.6" <?php if ($result[0]['cont_height']==9.6){echo "selected";}?>>9.6 </option>
														
													
				           </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Iso Type <span class="required">*</span></span>
							<input type="text" name="containerIsoType" id="containerIsoType" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_iso_type'];?>" placeholder="Container Iso Type">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Type <span class="required">*</span></span>
							<input type="text" name="containerType" id="containerType" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_type'];?>" placeholder="Container Type">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Vat <span class="required">*</span></span>
							<input type="text" name="containerVat" id="containerVat" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_vat'];?>" placeholder=">Container Vat ">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Commudity Code <span class="required">*</span></span>
							<input type="text" name="commudityCode" id="commudityCode" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['commudity_code'];?>" placeholder="Commudity Code">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Delivery Status <span class="required">*</span></span>
							<input type="text" name="deliveryStatus" id="deliveryStatus" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['Delivery_Status'];?>" placeholder="Delivery Status">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Delivery Status Date <span class="required">*</span></span>
							<input type="text" name="deliveryStatusDate" id="deliveryStatusDate" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['Delivery_Status_date'];?>" placeholder="Delivery Status Date">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Discharged Status <span class="required">*</span></span>
							<input type="text" name="dischargedStatus" id="dischargedStatus" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['Discharged_Status'];?>" placeholder="Discharged Status ">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Discharged Status Date <span class="required">*</span></span>
							<input type="text" name="dischargedStatusDate" id="dischargedStatusDate" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['Discharged_Status_date'];?>" placeholder="Discharged Status Date">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Login Id<span class="required">*</span></span>
							<input type="text" name="loginId" id="loginId" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['login_id'];?>" placeholder="Login Id">
						</div>
					</div>
					<div class="col-md-6"  style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Last Update<span class="required">*</span></span>
							<input type="text" name="lastUpdate" id="lastUpdate" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['last_update'];?>" placeholder="Last Update">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Org Id<span class="required">*</span></span>
							<input type="text" name="orgId" id="orgId" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['org_id'];?>" placeholder="Org Id">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Org Name<span class="required">*</span></span>
							<input type="text" name="orgName" id="orgName" class="form-control" autofocus  tabindex="2"  placeholder="Org Name" 
							value="<?php
							$orgId=$result[0]['org_id'];
							$query="SELECT organization_profiles.Organization_Name FROM users
							INNER JOIN  organization_profiles ON users.org_id=organization_profiles.id
							WHERE users.org_id='$orgId'";
							$queryResult = mysqli_query($con_cchaportdb,$query);
							$countRes=mysqli_num_rows($queryResult);
							while($row = mysqli_fetch_array($queryResult)){
                                 echo  $row['Organization_Name'];}
							?>" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Off Dock Id<span class="required">*</span></span>
							<!--input type="text" name="offDockId" id="offDockId" class="form-control" autofocus  tabindex="2" value="<?php //echo  $result[0]['off_dock_id'];?>" placeholder=">Off Dock Id"-->
							<select name="offDockId" id="offDockId" class="form-control" placeholder="Off Dock Id">
							                        <?php 
													$offDockQuery="SELECT id,Organization_Name FROM organization_profiles WHERE Org_Type_id='6' OR id='2591'";
													$offDockQueryResult = mysqli_query($con_cchaportdb,$offDockQuery);
													$countoffDockQueryResult=mysqli_num_rows($offDockQueryResult);
													while($row = mysqli_fetch_array($offDockQueryResult)) { ?>

													     <option value="<?php echo  $row['id'];?>" <?php if($result[0]['off_dock_id']== $row['id']){echo "selected";}?> >
														 <?php echo  $row['Organization_Name'];?></option>
														
													<?php } ?>
														
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Imo<span class="required">*</span></span>
							<input type="text" name="containerImo" id="containerImo" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_imo'];?>" placeholder="Container Imo">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Un<span class="required">*</span></span>
							<input type="text" name="containerUn" id="containerUn" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_un'];?>" placeholder="Container Un">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Received Off Dock Icd Status<span class="required">*</span></span>
							<input type="text" name="receivedOffDockIcdStatus" id="receivedOffDockIcdStatus" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['received_offdock_icd_Status'];?>" placeholder="Received Off Dock Icd Status">
						</div>
					</div>
					<div class="col-md-6" style="display:none;">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Port Status<span class="required">*</span></span>
							<input type="text" name="portStatus" id="portStatus" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['Port_Status'];?>" placeholder="Port Status">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Number Packages<span class="required">*</span></span>
							<input type="text" name="containerNumberPackages" id="containerNumberPackages" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_number_packaages'];?>" placeholder="Container Number Packages">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Container Location Code<span class="required">*</span></span>
							<input type="text" name="containerLocationCode" id="containerLocationCode" class="form-control" autofocus  tabindex="2" value="<?php echo  $result[0]['cont_location_code'];?>" placeholder="Container Location Code">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">
								Save
							</button>
						</div>													
					</div>
					
					
					
					
				</div>	
			</form>
		</section>
	</div>
	</div>
</div>		
	

	
        



		
		
		
	<!-- end: page -->
</section>
</div>