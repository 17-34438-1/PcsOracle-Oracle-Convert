
<script>

function consigneeInfoChange()
{		
	if (window.XMLHttpRequest) 
	{
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var consigneeCode = document.getElementById("consignee_code").value;
	var bl_igm_type = document.getElementById("bl_igm_type").value;
	// alert(bl_igm_type);
	
		
	
	var url="<?php echo site_url('AjaxController/consignee_info_change')?>?consignee_code="+consigneeCode+"&bl_igm_type="+bl_igm_type+"";
	// alert(url);
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangeConsignee;
	xmlhttp.send();

	
}


function stateChangeConsignee()
{	

if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			// alert(val);
			// alert(jsonData.notify_name);
			// alert(jsonData.notify_address);
			// alert(jsonData.notifierCount);
			
			if(jsonData.notifierCount==0)
			{
				alert("Invalid Notifier Info");
				var consignee_address_pre_text = document.getElementById("consignee_address_pre_text").value;
				document.getElementById('consignee_address').value=consignee_address_pre_text;
			}
			else{
			var consignee_name = document.getElementById('consignee_name');
			var consignee_address = document.getElementById('consignee_address');
		
		
			
			consignee_name.value = "";
			consignee_address.value = "";
			
			consignee_name.value = jsonData.consignee_name;				
			consignee_address.value = jsonData.consignee_address;
			
		
			
			}
						
		}
	
}

function notifyInfoChange()
{		
	if (window.XMLHttpRequest) 
	{
		xmlhttp=new XMLHttpRequest();
	} 
	else 
	{  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	var notifyCode = document.getElementById("notify_code").value;
	var bl_igm_type = document.getElementById("bl_igm_type").value;
	// alert(bl_igm_type);
	
		
	
	var url="<?php echo site_url('AjaxController/notify_info_change')?>?Notify_code="+notifyCode+"&bl_igm_type="+bl_igm_type+"";
	// alert(url);
	xmlhttp.open("GET",url,false);
	xmlhttp.onreadystatechange=stateChangeNotifier;
	xmlhttp.send();

	
}

function stateChangeNotifier()
{	

        if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			var val = xmlhttp.responseText;
		    var jsonData = JSON.parse(val);	
			// alert(val);
			// alert(jsonData.notify_name);
			// alert(jsonData.notify_address);
			// alert(jsonData.notifierCount);
			
			if(jsonData.notifierCount==0)
			{
				alert("Invalid Notifier Info");
				var notify_code_pre_text = document.getElementById("notify_code_pre_text").value;
				document.getElementById('notify_code').value=notify_code_pre_text;
			}
			else{
				var Notify_name = document.getElementById('Notify_name');
				var Notify_address = document.getElementById('Notify_address');
				var notify_desc = document.getElementById('notify_desc');
				
				Notify_name.value = "";
				Notify_address.value = "";
				notify_desc.value = "";
				
				Notify_name.value = jsonData.notify_name;				
				Notify_address.value = jsonData.notify_address;
				notify_desc.value = jsonData.notify_description;
			}
						
		}
	
}

function changeDgStatus()
{
	var dg_check = document.getElementById("dg_check");
	if(dg_check.checked == true)
	{
		document.getElementById("dg_status").disabled = false;
	}
	else
	{
		document.getElementById("dg_status").disabled = true;
	}
}

function changeImcoStatus()
{
	var imco_check = document.getElementById("imco_check");
	if(imco_check.checked == true)
	{
		document.getElementById("imco").disabled = false;
	}
	else
	{
		document.getElementById("imco").disabled = true;
	}
}

function changeUnStatus()
{
	var un_check = document.getElementById("un_check");
	if(un_check.checked == true)
	{
		document.getElementById("un").disabled = false;
	}
	else
	{
		document.getElementById("un").disabled = true;
	}
}
</script>



<section role="main" class="content-body">
    <header class="page-header">

        <div class="right-wrapper pull-right">

        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">

                <div class="panel-body">

                    <div class="form-group" align="center">
                        <h3><b>Make Change For BL</b><h3>
                    </div>
                    <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
							action="<?php echo site_url("IgmViewController/bl_amendment_update_value"); ?>">
						<input type="hidden" id="bl_igm_id" name="bl_id" value="<?php echo $bl_id;?>"/>
						<input type="hidden" id="bl_igm_type" name="bl_igm_type" value="<?php echo $igmType;?>"/>
						<input type="hidden" id="bl_selected_value" name="bl_selected_value" value="<?php echo $bl_selected ;?>" />
						<input type="hidden" id="rotation" name="rotation" value="<?php echo $rotation;?>"/>
                        <div class="form-group">
                            <div class="col-md-6">
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Rotation
                                    </span>
                                    <input type="text" name="rot_no" id="rot_no" value="<?php echo $rotation;?>"
                                        class="form-control" readonly/>
                                
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        BL NO
                                    </span>
                                    <input type="text" name="BL_No" id="BL_No" value="<?php echo $bl_no;?>"
                                        class="form-control" />
                                    
                                    <input type="hidden" name="BL_No_pre_text" id="BL_No_pre_text" class="form-control"
                                        value="<?php echo $bl_no;?>"    />
                                
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon">
                                        Pack Number
                                    </span>
                                    <input type="text" name="pack_Number" id="pack_Number"
                                        value="<?php echo $pack_Number;?>" class="form-control" />
                                    <input type="hidden" name="Pack_Number_pre_text" id="Pack_Number_pre_text" class="form-control"
                                        value="<?php echo $pack_Number;?>" />
                                  
                                </div>

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Pack Marks Number
                                    </span>
                                    <input type="text" name="pack_Marks_Number" id="pack_Marks_Number"
                                        value="<?php echo $pack_Marks_Number;?>" class="form-control" />
                                    <input type="hidden" name="Pack_Marks_Number_pre_text" id="Pack_Marks_Number_pre_text" class="form-control"
                                        value="<?php echo $pack_Marks_Number;?>" />
                                 
                                </div>

								

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Weight
                                    </span>
                                    <input type="text" name="weight" id="weight"
                                        value="<?php echo $weight;?>" class="form-control" />
                                    <input type="hidden" name="weight_pre_text" id="weight_pre_text" class="form-control"
                                        value="<?php echo $weight;?>" />
                                </div>

						

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Exporter Address
                                    </span>
                                    <textarea name="exporter_address" id="exporter_address" rows="3"
                                        class="form-control"
                                        required><?php  echo $exporter_address; ?></textarea>


                                    <input type="hidden" name="Exporter_address_pre_text" id="Exporter_address_pre_text" class="form-control"
                                        value="<?php echo $exporter_address;?>" />
                                  
                                </div>

							
                               
								
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Consignee Code
                                    </span>
                                    <input type="text" name="consignee_code" id="consignee_code"
                                        value="<?php echo $consignee_code;?>" class="form-control"/> <!--onblur="consigneeInfoChange();"-->
                                    <input type="hidden" name="Consignee_code_pre_text" id="Consignee_code_pre_text" class="form-control"
                                        value="<?php echo $consignee_code;?>" />
                                  
                                </div>
								
								
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Consignee Name
                                    </span>
                                    <input type="text" name="consignee_name" id="consignee_name"
                                        value="<?php echo $Consignee_name;?>" class="form-control" />
								
									<input type="hidden" name="consignee_name_pre_text" id="consignee_name_pre_text"
                                        value="<?php echo $Consignee_name;?>" class="form-control" />
                                </div>

								
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Consignee Address
                                    </span>

                                    <textarea name="consignee_address" id="consignee_address" rows="3"
                                        class="form-control"
                                        required ><?php  echo $consignee_address; ?></textarea>
                                    <input type="hidden" name="consignee_address_pre_text" id="consignee_address_pre_text" class="form-control"
                                        value="<?php echo $consignee_address;?>" />
                                  

                                </div>
							
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Type of Igm
                                    </span>
                                    <input type="text" name="type_of_igm" id="type_of_igm"
                                        value="<?php echo $type_of_igm;?>" class="form-control" />


                                    <input type="hidden" name="type_of_igm_pre_text" id="type_of_igm_pre_text" class="form-control"
                                        value="<?php echo $type_of_igm;?>" />
                                    
                                </div>

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        PF State
                                    </span>
								
                                    <input type="text" name="PFstatus" id="PFstatus"
                                        value="<?php echo $PFstatus;?>" class="form-control" />
										
										<input type="hidden" name="PFstatus_pre_text" id="PFstatus_pre_text"
                                        value="<?php echo $PFstatus;?>" class="form-control" />
                                </div>
								
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Submitee Id (AIN)
                                    </span>
                                    <input type="text" name="submiteeId" id="submiteeId"
                                        value="<?php echo $Submitee_Id;?>" class="form-control" />

										
										<input type="hidden" name="Submitee_Id_pre_text" id="Submitee_Id_pre_text"
                                        value="<?php echo $Submitee_Id;?>" class="form-control" />
                                </div>
								
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
										<input class="form-check-input" type="checkbox" name="dg_check" id="dg_check" 
										<?php if($DG_status != null) echo "checked"; ?> onclick="changeDgStatus()">
                                        DG Status
                                    </span>
                                    <input type="text" name="dg_status" id="dg_status" value="<?php echo $DG_status;?>" 
										class="form-control" <?php if($DG_status == null) echo "disabled"; ?>/>
									<input type="hidden" name="dg_status_pre_text" id="dg_status_pre_text" value="<?php echo $DG_status;?>" 
										class="form-control" />
                                </div>
								<?php if($igmType == "dtl") { ?>
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
										<input class="form-check-input" type="checkbox" name="imco_check" id="imco_check" 
										<?php if($imco != null) echo "checked"; ?> onclick="changeImcoStatus()">
                                        IMCO
                                    </span>
                                    <input type="text" name="imco" id="imco" value="<?php echo $imco;?>" 
										class="form-control" <?php if($imco == null) echo "disabled"; ?>/>
									<input type="hidden" name="imco_pre_text" id="imco_pre_text" value="<?php echo $imco;?>" 
										class="form-control" />
                                </div>
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
										<input class="form-check-input" type="checkbox" name="un_check" id="un_check" 
										<?php if($un != null) echo "checked"; ?> onclick="changeUnStatus()">
                                        UN
                                    </span>
                                    <input type="text" name="un" id="un" value="<?php echo $un;?>" 
										class="form-control" <?php if($un == null) echo "disabled"; ?>/>
									<input type="hidden" name="un_pre_text" id="un_pre_text" value="<?php echo $un;?>" 
										class="form-control" />
                                </div>
								<?php } ?>

								
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Line No
                                    </span>

                                    <input type="text" name="line_No" id="line_No"
                                        value="<?php echo $line_No;?>" class="form-control" />
                                    <input type="hidden" name="Line_No_pre_text" id="Line_No_pre_text" class="form-control"
                                        value="<?php echo $line_No;?>" />
                                 
                                </div>

								
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Pack Description
                                    </span>
                                    <textarea name="pack_Description" id="pack_Description" rows="3"
                                        class="form-control"
                                        required><?php  echo $pack_Description; ?></textarea>

                                    <input type="hidden" name="pack_Description_pre_text" id="pack_Description_pre_text" class="form-control"
                                        value="<?php echo $pack_Description;?>" />
                                  

                                </div>

							

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Description of Goods
                                    </span>
                                    <textarea name="description_goods" id="description_goods" rows="3"
                                        class="form-control"
                                        required><?php  echo $description_of_goods; ?></textarea>

                                    <input type="hidden" name="description_goods_pre_text" id="description_goods_pre_text" class="form-control"
                                        value="<?php echo $description_of_goods;?>" />
                                </div>

								

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Exporter Name
                                    </span>
                                    <input type="text" name="exporter_name" id="exporter_id"
                                        value="<?php echo $exporter_name;?>" class="form-control" />

                                    <input type="hidden" name="exporter_name_pre_text" id="exporter_name_pre_text" class="form-control"
                                        value="<?php echo $exporter_name;?>" />
                                  
                                </div>

							
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Notify Code
                                    </span>
                                    <input type="text" name="notify_code" id="notify_code"
                                       class="form-control" value="<?php echo $notify_code;?>"
									/> <!--onblur="notifyInfoChange();"-->

                                    <input type="hidden" name="notify_code_pre_text" id="notify_code_pre_text" class="form-control"
                                        value="<?php echo $notify_code;?>" />
                                  
                                </div>

								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Notify Name
                                    </span>
                                    <input type="text" name="notify_name" id="Notify_name"
                                      class="form-control"  value="<?php echo $notify_name;?>" />
                                    <input type="hidden" name="Notify_name_pre_text" id="Notify_name_pre_text" class="form-control"
                                        value="<?php echo $notify_name;?>" />
                                 
                                </div>
								
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Notify Address
                                    </span>
                                    <textarea name="notify_address" id="Notify_address" rows="3" class="form-control"
                                        required ><?php  echo $notify_address;?></textarea>
                                    
                                    <input type="hidden" name="Notify_address_pre_text" id="Notify_address_pre_text" class="form-control"
                                        value="<?php echo $notify_address;?>" />
                                  
                                </div>
								
								<div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Notify Description
                                    </span>
                                    <textarea name="notify_desc" id="notify_desc" rows="3" class="form-control"
                                        required ><?php  echo $notifyDesc;?></textarea>
                                    
                                    <input type="hidden" name="Notify_desc_pre_text" id="Notify_desc_pre_text" class="form-control"
                                        value="<?php echo $notifyDesc;?>" />
                                  
                                </div>

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Volume In Cubic Meters
                                    </span>
                                    <input type="text" name="volumeInCubicMeters" id="volumeInCubicMeters"
                                        value="<?php echo $volume_in_cubic_meters;?>"
                                        class="form-control" />

										<input type="hidden" name="volumeInCubicMeters_pre_text" id="volumeInCubicMeters_pre_text"
                                        value="<?php echo $volume_in_cubic_meters;?>" class="form-control" />

                                </div>

							

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">
                                        Port of Origin
                                    </span>

                                    <input type="text" name="portOfOrigin" id="portOfOrigin"
                                        value="<?php echo $port_of_origin;?>" class="form-control" />

										<input type="hidden" name="port_of_origin_pre_text" id="port_of_origin_pre_text"
                                        value="<?php echo $port_of_origin;?>" class="form-control" />

                                </div>
							
                                
							



                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" name="submit_login" id="submit"
                                        class="mb-xs mt-xs mr-xs btn btn-success">Save
                                    </button>
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
    <!-- end: page -->
</section>