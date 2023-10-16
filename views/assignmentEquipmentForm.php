<script>
    function cngFld(contType){
        if(contType == 'FCL')
        {
            document.getElementById('contNo').style.display="block";
            document.getElementById('assType').style.display=null;
            document.getElementById('rotNo').style.display="none";
            document.getElementById('blNo').style.display="none";
            document.getElementById('noOfTruck').style.display="none";
        }
        else if(contType == 'LCL')
        {
            document.getElementById('rotNo').style.display=null;
            document.getElementById('blNo').style.display=null;
            document.getElementById('noOfTruck').style.display=null;
            document.getElementById('contNo').style.display="none";
            document.getElementById('assType').style.display="none";
        }
        else
        {
            document.getElementById('rotNo').style.display="none";
            document.getElementById('blNo').style.display="none";
            document.getElementById('contNo').style.display="none";
            document.getElementById('assType').style.display="none";
            document.getElementById('noOfTruck').style.display="none";
        }
    }

    function validate(){
        var contType = document.getElementById("contType").value.trim();
        if(contType == "" || contType == null)
        {
            alert("Please select container Type!");
            return false;
        }
        else if(contType == "FCL")
        {
            var assignmentType = document.getElementById("assignmentType").value.trim();
            var cont = document.getElementById("cont").value.trim();
            if(assignmentType == "" || assignmentType == null || cont == "" || cont == null){
                alert("Please fill all the fields");
                return false;
            }
        }
        else if(contType == "LCL")
        {
            var rot = document.getElementById("rot").value.trim();
            var bl = document.getElementById("bl").value.trim();
            if(rot == "" || rot == null || bl == "" || bl == null){
                alert("Please fill all the fields");
                return false;
            }
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
                        <?php
                            if($flag == 'edit')
                            {
                                $id = "";
                                $cont_status = "";
                                $rot = "";
                                $bl = "";
                                $cont = "";
                                $noOfTruck = "";
                                $assignmentDate = "";
                                $assignId = "";
                                $assignmentType = "";

                                for($i=0;$i<count($assignmentData);$i++){
                                    $id = $assignmentData[$i]['id'];
                                    $cont_status = $assignmentData[$i]['cont_status'];
                                    $rot = $assignmentData[$i]['rotation'];
                                    $bl = $assignmentData[$i]['bl'];
                                    $cont = $assignmentData[$i]['cont_number'];
                                    $noOfTruck = $assignmentData[$i]['no_of_truck'];
                                    $assignmentDate = $assignmentData[$i]['assignmentDate'];
                                    $assignId = $assignmentData[$i]['id'];
                                    $assignmentType = $assignmentData[$i]['mfdch_value'];
                                }
                            }
                        ?>
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/assignmentEquipmentEntry"); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-2 control-label">&nbsp;</label>
								<div class="col-md-8">
                                
                                    <?php
                                        include("mydbPConnection.php");
                                        if($flag == 'edit' && $cont_status == "FCL")
                                        {
                                            $rotation = "";
                                            $bl_No = "";
                                            for($a=0;$a<count($assignmentData);$a++){
                                                $rotation =$assignmentData[$a]['rotation'];
                                                $bl_No = $assignmentData[$a]['bl'];
                                            }

                                            $edoDateQuery = "SELECT valid_upto_dt FROM shed_mlo_do_info WHERE imp_rot = '$rotation' AND bl_no = '$bl_No' LIMIT 1";
                                            $edoDateRslt = mysqli_query($con_cchaportdb,$edoDateQuery);
                                            $edoDate = "";
                                            while($row = mysqli_fetch_object($edoDateRslt)){
                                                $edoDate = $row->valid_upto_dt;
                                            }
                                    ?>
                                        <input type="hidden" name="edoDate" value="<?php echo $edoDate;?>"/>
                                    <?php
                                        }
                                    ?>		
									
                                    <div class="col-md-12" style="padding:0px;">
                                        <div class="input-group mb-md">
                                            <span class="input-group-addon span_width">Container Type<span class="required">*</span></span>
                                            <select name="contType" id="contType" class="form-control" onchange="cngFld(this.value)" <?php if($flag == 'edit'){ echo "disabled";} ?>>
                                                <option value="">Select</option>
                                                <option value="FCL" <?php if($flag == 'edit' && $cont_status == "FCL"){ echo "selected";} ?>>FCL</option>
                                                <option value="LCL" <?php if($flag == 'edit' && $cont_status == "LCL"){ echo "selected";} ?>>LCL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding:0px;<?php if($flag == 'edit' && $cont_status == "FCL"){ echo "";}else{ echo "display:none";} ?>;" id="contNo">
                                        <div class="input-group mb-md">
                                            <span class="input-group-addon span_width">Container No(s)<span class="required">*</span></span>
                                            <textarea name="cont" id="cont" class="form-control" placeholder="Write container no here" <?php if($flag == 'edit'){ echo "disabled";} ?>><?php if($flag == 'edit' && $cont_status == "FCL"){ echo $cont;}?></textarea>
                                        </div>
                                    </div>
										
                                    <div class="input-group mb-md" id="assType" style="<?php if($flag == 'edit' && $cont_status == "FCL"){ echo "";}else{ echo "display:none";} ?>;">
										<span class="input-group-addon span_width">Assignment Type <span class="required">*</span></span>
										<select name="assignmentType" id="assignmentType" class="form-control">
                                            <option value="">select assignment type</option>
                                            <?php
                                                for($i=0;count($rslt)>$i;$i++)
                                                {
                                            ?>  
                                                <option value="<?php echo $rslt[$i]['mfdch_value']; ?>" <?php if($flag == "edit"){if($assignmentType == $rslt[$i]['mfdch_value']){echo "selected";}}?>><?php echo $rslt[$i]['mfdch_desc']; ?></option>
                                            <?php        
                                                }
                                            ?>
                                        </select>
									</div>

                                    <div class="input-group mb-md" id="rotNo" style="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo "";}else{ echo "display:none";} ?>;">
										<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
										<input type="text" name="rot" id="rot" class="form-control" placeholder="rotation no" value="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo $rot;} ?>" <?php if($flag == 'edit'){ echo "readonly";} ?>>
									</div>

                                    <div class="input-group mb-md" id="blNo" style="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo "";}else{ echo "display:none";} ?>;">
										<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
										<input type="text" name="bl" id="bl" class="form-control" placeholder="bl no" value="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo $bl;} ?>" <?php if($flag == 'edit'){ echo "readonly";} ?>>
									</div>

                                    <div class="input-group mb-md" id="noOfTruck" style="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo "";}else{ echo "display:none";} ?>;">
										<span class="input-group-addon span_width">No of Truck <span class="required">*</span></span>
										<input type="text" name="noOfTruck" id="noOfTruck" class="form-control" placeholder="No of Truck" value="<?php if($flag == 'edit' && $cont_status == "LCL"){ echo $noOfTruck;} ?>">
									</div>

                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php if($flag == "edit"){ echo $assignmentDate;}else{ echo date("Y-m-d");}?>">
									</div>

								</div>
                                
                                <?php
                                    for($i=0;count($equipRslt)>$i;$i++)
                                    {
                                        if($flag == "edit"){
                                            include("mydbPConnection.php");
                                            $equipQuery = "SELECT equip_id FROM equip_for_assignment WHERE assign_id = '$assignId'";
                                            $equipments = mysqli_query($con_cchaportdb,$equipQuery);
                                            $check = "";
                                            while($equip = mysqli_fetch_object($equipments))
                                            {
                                                if($equipRslt[$i]['equipment_id'] == $equip->equip_id){ 
                                                    $check = "checked";
                                                    break;
                                                }
                                            }
                                        }
                                ?>
                                    <div class="<?php if($i == 0){ echo "col-md-offset-1";}?> <?php if($i == 4){ echo "col-md-3";}else{ echo "col-md-2";}?>">
                                        <div class="radio-custom">
                                            <input type="radio" id="option<?php echo $i;?>" name="option" value="<?php echo $equipRslt[$i]['equipment_id']; ?>" <?php if($flag == "edit"){ echo $check;} ?>>
                                            <label for="option<?php echo $i;?>"><?php echo $equipRslt[$i]['equipment_name']; ?></label>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
                                        <!-- <input type="hidden" name="rowCount" id="rowCount" value="<?php echo $i;?>"/> -->
                                        <?php
                                            if($flag == 'edit'){
                                        ?>
                                                <input type="hidden" name="contType" id="contType" value="<?php echo $cont_status;?>" />
                                                <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
                                                <button type="submit" name="save" value="update" class="mb-xs mt-xs mr-xs btn btn-success">Update</button>
                                        <?php        
                                            }else{
                                        ?>
                                                <button type="submit" name="save" value="save" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
                                        <?php
                                            }
                                        ?>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
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