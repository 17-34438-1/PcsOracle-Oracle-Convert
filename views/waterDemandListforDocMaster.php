<script>

    window.setInterval('refresh()', 60000); 	// Call a function every 10000 milliseconds (OR 10 seconds).

    // Refresh or reload page.
    function refresh() {
        window .location.reload();
    }
    
    function confirmation(){
		if(confirm("Are you sure?"))
		{
			return true;
		}
		else			
		{
			return false;
		}
		return false;
	}

    function Modalconfirmation(){
        remarks = document.getElementById('remarks').value.trim();

        if(!remarks){
            alert("Write Remarks Please..");
            return false;
        }
        else
        {
            if(confirm("Are you sure?"))
            {
                return true;
            }
            else			
            {
                return false;
            }
        }
		return false;
    }

    // function setAction(id){
    //     actionValremarks = "http://192.168.16.108:8080/pcs/index.php/Vessel/waterDemandBackward/"+id;
    //     actionValFile = "http://192.168.16.108:8080/pcs/index.php/Vessel/waterDemandFileUpload/"+id;
    //     document.getElementById('waterDemandBackward').action = actionValremarks;
    //     document.getElementById('fileUpload').action = actionValFile;
    //     document.getElementById('id').value = id;
    //     document.getElementById('fileId').value = id;
    // }

    function setAction(id){
        actionValremarks = "http://122.152.54.185/PcsOracle/index.php/Vessel/waterDemandBackward/"+id;
        actionValFile = "http://122.152.54.185/PcsOracle/index.php/Vessel/waterDemandFileUpload/"+id;
        document.getElementById('waterDemandBackward').action = actionValremarks;
        document.getElementById('fileUpload').action = actionValFile;
        document.getElementById('id').value = id;
        document.getElementById('fileId').value = id;
    }

    function setRemarks(remarks){
        document.getElementById('showRemarks').innerHTML = remarks;
    }

    function selectAllRot(state)
    {
        var totAllMeasurement = 0;
        var subTotMeasurement = 0;
        var totalRot = document.getElementById('totalCount').value;
        if(state.checked == true)
        {
            //If "All" is not checked;
            for(var p=0;p<totalRot;p++)
            {
                //document.getElementById("rotchk"+p).checked = true;
                if(document.getElementById("idchk"+p).disabled == false)
                {
                    document.getElementById("idchk"+p).checked = true;
                }
                //subTotMeasurement = parseFloat(document.getElementById("rotchk"+p).value);
                //totAllMeasurement = parseFloat(totAllMeasurement)+parseFloat(subTotMeasurement);
            }
            
        }
        else
        {
            //If "All" is not checked;
            for(var p=0;p<totalRot;p++)
            {
                //document.getElementById("rotchk"+p).checked = false;						
                document.getElementById("idchk"+p).checked = false;						
            }
            // Following line is commented because from now on measurement will not change by clicking on chkbox.....
            // document.getElementById("measurement").value = 0;
        }
    
        var numberOfChecked = 0;

        if(document.getElementById('allcheck').checked==true)
        {
            numberOfChecked = $('input:checkbox:checked').length -1;
        }
        else
        {
            numberOfChecked = $('input:checkbox:checked').length;
        }

        //alert(numberOfChecked);

        document.getElementById("item").innerHTML = numberOfChecked;

        if(numberOfChecked>0)
        {
            document.getElementById('forward').disabled = false;
        }else{
            document.getElementById('forward').disabled = true;
        }

        document.getElementById('filela').innerHTML = numberOfChecked;
        document.getElementById("filela").style.fontWeight = 'bold';
        document.getElementById('filela2').innerHTML = numberOfChecked;
        document.getElementById("filela2").style.fontWeight = 'bold';
    }

    function selectCheck(state)
    {
        var numberOfChecked = 0;
        
        if(document.getElementById('allcheck').checked==true)
        {
            var numberOfChecked = $('input:checkbox:checked').length -1;
        }
        else
        {
            var numberOfChecked = $('input:checkbox:checked').length;
        }
        //alert(numberOfChecked);
        document.getElementById("item").innerHTML = numberOfChecked;

        if(numberOfChecked>0){
            document.getElementById('forward').disabled = false;
        }else{
            document.getElementById('forward').disabled = true;
        }
        
        document.getElementById('filela').innerHTML = numberOfChecked;
        document.getElementById("filela").style.fontWeight = 'bold';
        document.getElementById('filela2').innerHTML = numberOfChecked;
        document.getElementById("filela2").style.fontWeight = 'bold';
    }

    window.onload = function()
    {

        var totAllMeasurement = 0;
        var subTotMeasurement = 0;
        var totalRot = document.getElementById('totalCount').value;
        
        for(var p=0;p<totalRot;p++)
        {
            if(document.getElementById("idchk"+p).disabled == false)
            {
                document.getElementById("idchk"+p).checked = true;
            }
        }
    
        var numberOfChecked = 0;

        if(document.getElementById('allcheck').checked==true)
        {
            numberOfChecked = $('input:checkbox:checked').length -1;
        }
        else
        {
            numberOfChecked = $('input:checkbox:checked').length;
        }

        //alert(numberOfChecked);

        document.getElementById("item").innerHTML = numberOfChecked;

        if(numberOfChecked>0)
        {
            document.getElementById('forward').disabled = false;
        }else{
            document.getElementById('forward').disabled = true;
        }

        document.getElementById('filela').innerHTML = numberOfChecked;
        document.getElementById("filela").style.fontWeight = 'bold';
        document.getElementById('filela2').innerHTML = numberOfChecked;
        document.getElementById("filela2").style.fontWeight = 'bold';
    };

    function validate()
    {
        billOp = $('#billOp').val().trim();
        if(!billOp)
        {
            alert("Please select bill operator...");
            return false;
        }
        else
        {
            return true;
        }
        return false;
    }

    function isQtyOk(ev,value,max)
    {
        if(parseInt(value) > parseInt(max)){
            ev.value = '';
            ev.nextElementSibling.style.display = 'none';
            alert('Approve quantity must be equal or less than The Demand quantity!');
        }else{
            ev.nextElementSibling.style.display = 'block';
        }
    }

    function insertAppQty(ev,id)
    {
        var apprvQty = ev.previousElementSibling.value;
        var maxQty = ev.previousElementSibling.max;

        if(parseInt(apprvQty) > parseInt(maxQty))
        {
            ev.previousElementSibling.value = '';
            ev.style.display = 'none';
            alert('Approve quantity must be equal or less than The Demand quantity!');
        }
        else
        {
            // starts here //

            if (window.XMLHttpRequest) 
            {
                xmlhttp=new XMLHttpRequest();
            } 
            else 
            {  
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) 
                {							  
                    var val = xmlhttp.responseText;
                    var jsonData = JSON.parse(val);
                    if(jsonData.status == true)
                    {
                        alert("successfully approved...");
                        document.getElementById('burgeApproval'+id).style.display='none';
                        document.getElementById('burgeFullSupplied'+id).style.display='none';
                        document.getElementById('burgeSelectOptions'+id).style.display='block';
                    }
                    else
                    {
                        ev.previousElementSibling.value = "";
                        alert("something wrong! please try again later...");
                    }
                }
            };
            
            var url = "<?php echo site_url('AjaxController/insertAppQty')?>?id="+id+"&qty="+apprvQty;
            xmlhttp.open("GET",url,false);
            xmlhttp.send();

            // ends here //
            // alert('Everything is fine!');
        }
        return false;
    }

    function insertBurge(ev,id)
    {
        var burge = ev.previousElementSibling.value;

        if(!burge)
        {
            alert("Please select a burge to assign...");
        }
        else
        {
            if (window.XMLHttpRequest) 
            {
                xmlhttp=new XMLHttpRequest();
            } 
            else 
            {  
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            xmlhttp.onreadystatechange=function()
            {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) 
                {							  
                    var val = xmlhttp.responseText;
                    var jsonData = JSON.parse(val);
                    
                    if(jsonData.status == true)
                    {
                        //ev.previousElementSibling.value = apprvQty;
                        alert("successfully assigned...");
                    }
                    else
                    {
                        ev.previousElementSibling.value = "";
                        alert("something wrong! please try again later...");
                    }
                }
            };
            
            var url = "<?php echo site_url('AjaxController/insertBurge')?>?id="+id+"&burge="+burge;
            xmlhttp.open("GET",url,false);
            xmlhttp.send();
        }

        return false;
    }

    // setInterval(function()
    // {
    //     if (window.XMLHttpRequest) 
    //     {
    //         xmlhttp=new XMLHttpRequest();
    //     } 
    //     else 
    //     {  
    //         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    //     }
        
    //     xmlhttp.onreadystatechange=function()
    //     {
    //         if (xmlhttp.readyState==4 && xmlhttp.status==200) 
    //         {							  
    //             var val = xmlhttp.responseText;
    //             var jsonData = JSON.parse(val);
    //             alert(jsonData.suppliedData.length);
    //             for(var i = 0; i<jsonData.suppliedData.length ; i++){
    //                 var water_demand_id = jsonData.suppliedData[i].water_demand_id;
    //                 var supplied_qty = jsonData.suppliedData[i].supplied_qty;
    //             }
    //         }
    //     };
        
    //     var url = "<?php //echo site_url('AjaxController/getSuppliedQty')?>";
    //     xmlhttp.open("GET",url,false);
    //     xmlhttp.send();

    // },5000);


</script>

<style>

    #vertical_center
    {
        text-align: center;
        vertical-align: middle;
    }

</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">

                    <?php
						if(!is_null($this->session->flashdata('success'))){
							echo $this->session->flashdata('success');
						}

						if(!is_null($this->session->flashdata('error'))){
							echo $this->session->flashdata('error');
						}

                        $org_Type_id =$this->session->userdata('org_Type_id');
                        $section = $this->session->userdata('section');
					?>

                    <?php
                        if($org_Type_id == 82 && $section == "acc"){
                    ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-1">
                                <div class="input-group mb-md">
                                    <img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">

                                </div>
                            </div>
                            <div class="col-md-9">
                                <table align="center">
                                    <tr>
                                        <td align="center"><font size=4>চট্টগ্রাম বন্দর কর্তৃপক্ষ</font>
                                                <br/> <u>নির্বাহী প্রকৌশলী (বি)/সিটি এর দপ্তর</u>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>   
                        <table width=100%>
                            <tr><td>ন;- ১৮.০৪.০০০০.৪২৬.০১১.১৮</td></tr>
                            <tr><td align="right"> তারিখ : <?php echo  date("d/m/Y"); ?></td></tr>

                            <tr>
                                <td align="left">
                                বরাবরে  <br/>
                                উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
                                চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
                                চট্টগ্রাম । <br/>
                                বিষয়: <?php echo "জাহাজে পানি সরবরাহের ভাউচার প্রেরন সংক্রান্ত।"; ?> <br/>
                                </td>
                            </tr>
                            <tr><td> <br/></td></tr>
                        
                            <br/>
                            <tr>
                                <td align="left">
                                    নির্বাহী প্রকৌশলী (বি)/সিটি/চবক এর দপ্তরের আওতাধীন উসপ্র(বি)/এনসিটি প্রশাখা হতে প্রাপ্ত জেটিস্থ জাহাজ সমুহের প্রদেয় পানি সরবরাহের তথ্যাদি নিম্নোক্ত ছকে লিপিবদ্ধ করা হলঃ-
                                </td>
                            </tr>
                        </table>
                        <form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Vessel/waterDemandForwardByAcc") ?>" <?php if($section=='acc') { ?> onsubmit="return validate()" <?php }?> >
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Bill Operator <span class="required">*</span></span>
                            <select name="billOp" id="billOp" class="form-control" style="width:300px"> 
                                <option value="">--SELECT--</option>
                                <?php for($i=0; $i<count($billOpListRslt); $i++) { ?>
                                    <option value="<?php echo $billOpListRslt[$i]['login_id']; ?>"><?php echo $billOpListRslt[$i]['u_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                    <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped mb-none" id="<?php if($org_Type_id == 82 && $section == "acc"){ echo ""; }else{ echo "datatable-default";} ?>" width="100%">
                                <input type="hidden" id="totalCount" name="totalCount" value="<?php echo count($result);?>">
                                <thead>
                                    <tr class="gridDark">
                                        <?php
                                            if($org_Type_id == 82 && $section == "acc"){
                                        ?>
                                        <th rowspan="2" align='center'>Forward All <input type='checkbox' id='allcheck' onclick='selectAllRot(this);' checked ></th>
                                        <?php
                                            }
                                        ?>
                                        <th class="text-center" rowspan="2" id="vertical_center">#Sl</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Rotation no</th>									
                                        <th class="text-center" rowspan="2" id="vertical_center">Shipping Agent</th>									
                                        <th class="text-center" rowspan="2" id="vertical_center">Vessel name</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Berth</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Delivert Area</th>
                                        <!-- <th class="text-center">Supply Type</th>	 -->
                                        <th class="text-center" colspan="2" id="vertical_center">Demand</th>
                                        <!-- <th class="text-center" colspan="2" id="vertical_center">Forward</th> -->
                                        <th class="text-center" rowspan="2" id="vertical_center">Supply Status</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Attachment</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">App. Qty (M. T.)</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Assign Burge</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Supplied Qty (M. T.)</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Due Qty (M. T.)</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Action</th>

                                        <?php

                                            if($org_Type_id == 78 || $org_Type_id == 81 || ($org_Type_id == 82 && $section == "billop") || $org_Type_id == 83){
                                        ?>	

                                        <!-- <th class="text-center" rowspan="2" id="vertical_center">Action</th>	-->

                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if($org_Type_id == 82 && $section == "billop"){
                                        ?>
                                        <!-- <th class="text-center" rowspan="2" id="vertical_center">View Bill</th> -->
                                        <?php
                                            }
                                        ?>

                                    </tr>
                                    <tr>
                                        <th class="text-center" id="vertical_center">Quantity</th>
                                        <th class="text-center" id="vertical_center">Date</th>
                                        <!-- <th class="text-center" id="vertical_center">By</th> -->
                                        <!-- <th class="text-center" id="vertical_center">Status</th>
                                        <th class="text-center" id="vertical_center" width="35%">At</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $tbl = "";
                                        for($i=0;count($result)>$i;$i++)
                                        {
                                            $id = $result[$i]['id'];
                                            $demand_qty = $result[$i]['demand_qty'];
                                            $qty = $result[$i]['demand_qty']." ".$result[$i]['demand_unit'];
                                            $rotation = $result[$i]['rotation_no'];
                                            $supplyType = $result[$i]['supply_type'];
                                            $demand_by = $result[$i]['demand_by'];
                                            $demand_date = $result[$i]['demand_date'];
                                            $berthVal = strtoupper(substr($result[$i]['berth'],0,1));
                                            $delivery_area = $result[$i]['delivery_area'];
                                            $dockMaster_aprv_qty = $result[$i]['dockMaster_aprv_qty'];
                                            $burge_name = $result[$i]['burge_name'];
                                            $suppliedQty = $result[$i]['supplied_qty'];
                                            $dueQty = $dockMaster_aprv_qty - $suppliedQty;
                                            
                                            $userNameQuery = "SELECT u_name FROM users WHERE login_id = '$demand_by'";
                                            $userNameResult = $this->bm->dataSelectDB1($userNameQuery);
                                            $u_name = "";
                                            if(count($userNameResult)>0){
                                                $u_name = $userNameResult[0]['u_name'];
                                            }

                                            // $burge_details_query = "SELECT * FROM ctmsmis.water_demand_burge_detail WHERE water_demand_id = '$id'";
                                            // $burge_data = $this->bm->dataselect($burge_details_query);


                                            // if(count($burge_data)>0)
                                            // {

                                            // }

                                            $tbl .= "<tr>";
                                            $sl = $i+1;

                                            if($org_Type_id == 82 && $section == "acc")
                                            {
                                            $tbl.="<td align='center'><input type='checkbox' name='idchk[]' id='idchk{$i}' onclick='selectCheck(this);' value='{$result[$i]['id']}'";

                                            if($result[$i]['marine_aprv_st'] == 0){
                                                if($result[$i]['dcee_aprv_st'] == 0  || $result[$i]['acc_aprv_st'] == 1){
                                                    $tbl.="disabled";
                                                    //$st = 1;
                                                }
                                            }
                                            else {
                                                if($result[$i]['demand_aprv_st'] == 0 || $result[$i]['acc_aprv_st'] == 1){
                                                    $tbl.="disabled";
                                                    //$st = 1;
                                                }
                                            }

                                            $tbl.="></td>";
                                            }

                                            $tbl.="<td align='center'> {$sl} </td>";
                                            $tbl.="<td align='center'> {$rotation} </td>";
                                            $tbl.="<td align='center'> {$u_name} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['vessel_name']} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['berth']} </td>";
                                            $tbl.="<td align='center'> {$delivery_area} </td>";
                                            // $tbl.="<td align='center'> {$supplyType} </td>";
                                            $tbl.="<td align='center'> {$qty} </td>";
                                            // $tbl.="<td align='center'> {$result[$i]['demand_date']} </td>";
                                            $tbl.="<td align='center'> {$demand_date} </td>";

                                            // Supply Status 
                                            include(APPPATH.'views/dbOracleConnection.php');

                                            $supplyQuery = "";
                                            if($supplyType == "shore"){
                                                $supplyQuery = "SELECT (SELECT COUNT(*) from vsl_vessel_visit_details WHERE ib_vyg='$rotation') AS rtnValue,srv_event.placed_time FROM vsl_vessel_visit_details 
                                                INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
                                                WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey=169 ";
                                            }
                                            else if($supplyType == "burge")
                                            {
                                                $supplyQuery = "SELECT (SELECT COUNT(*) from vsl_vessel_visit_details WHERE ib_vyg='$rotation') AS rtnValue,srv_event.placed_time FROM vsl_vessel_visit_details 
                                                INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
                                                WHERE ib_vyg='$rotation' AND srv_event.event_type_gkey IN(168,459,461,463)";
                                            }
                                            
                                            $supplyResult = $this->bm->dataSelect($supplyQuery);

                                            $supplyStatus = null;
                                            $placedTime = null;
                                            
                                            if(count($supplyResult)>0){
                                                $supplyStatus = $supplyResult[0]["RTNVALUE"];
                                                $placedTime = $supplyResult[0]["PLACED_TIME"];
                                            }

                                            $dlv_sts = "";
                                            if($supplyStatus == 1){
                                                $tbl.="<td align='center'> <font color='green'>Supplied at {$placedTime}</font> </td>";
                                                $dlv_sts = "Delivered!";
                                            }else{
                                                $tbl.="<td align='center'> <font color='#1cbad6'>Request Received</font> </td>";
                                                $dlv_sts = "Not Delivered yet!";
                                            }

                                            // Document upload

                                            $tbl.="<td align='center'>";


                                            if($result[$i]['docPath'] != NULL)
                                            {
                                                $docPath = "/resources/waterBill/".$result[$i]['docPath'];
                                                $tbl.="<div style='margin-top:5px;'><a href='{$docPath}' target='_blank' class='btn btn-xs btn-success'>View</a><div>";
                                            }
                                            
                                            $tbl."</td>";

                                            if($demand_qty == $dockMaster_aprv_qty && $dockMaster_aprv_qty == $suppliedQty){ // approve button won't be visible when demand , approved, supplied all equal
                                            $tbl .= "
                                                <td>
                                                    <input type='number' id='appQty' class='form-control input-sm' value = '{$dockMaster_aprv_qty}' max='{$demand_qty}' style='width:65px;height:25px;' onkeyup='isQtyOk(this,this.value,this.max)'>
                                                </td>
                                                ";
                                            }
                                            else
                                            {
                                                $tbl .= "
                                                <td>
                                                    <input type='number' id='appQty' class='form-control input-sm' value = '{$dockMaster_aprv_qty}' max='{$demand_qty}' style='width:65px;height:25px;' onkeyup='isQtyOk(this,this.value,this.max)'>
                                                    <input type='button' id = 'sendAppQty' onclick='insertAppQty(this,{$result[$i]['id']})' class='mb-xs mt-xs mr-xs btn btn-xs btn-primary' value='APPROVE'>
                                                </td>
                                                ";
                                            }

                                            $burgerFullSupplied = "";
                                            $burgeSelectOptions = "";
                                            $burgeApproval = "";

                                            if($dockMaster_aprv_qty>0 && $dockMaster_aprv_qty == $suppliedQty)
                                            {
                                                $burgerFullSupplied = "style='display:static'";
                                                $burgeSelectOptions = "style='display:none'";
                                                $burgeApproval = "style='display:none'";
                                            }
                                            else if($dockMaster_aprv_qty>0)
                                            {
                                                $burgerFullSupplied = "style='display:none'";
                                                $burgeSelectOptions = "style='display:static'";
                                                $burgeApproval = "style='display:none'";
                                            }
                                            else
                                            {
                                                $burgerFullSupplied = "style='display:none'";
                                                $burgeSelectOptions = "style='display:none'";
                                                $burgeApproval = "style='display:static'";
                                            }

                                            $tbl .= "<td id='burgeFullSupplied{$result[$i]['id']}' {$burgerFullSupplied}>
                                                            <input type='button' class='form-control input-sm btn-success' style='width:130px; height:25px;padding:2px;' value = 'FULL SUPPLIED'>
                                                        </td>";

                                            $tbl .= "<td id='burgeSelectOptions{$result[$i]['id']}' {$burgeSelectOptions}>
                                                    <select id='burges' class='form-control input-sm' style='width:130px; height:25px;padding:2px;'>
                                                        <option value=''>-- Select --</option>
                                                        <option value='MOSHAK'";
                                        
                                            $burge_st = "";
                                            if($burge_name == "MOSHAK"){ $burge_st = "selected";}

                                            $tbl .= "{$burge_st}>MOSHAK</option>
                                                    <option value='JHARNA'";

                                                $burge_st = "";
                                                if($burge_name == "JHARNA"){ $burge_st = "selected";}

                                            $tbl .= "{$burge_st}>JHARNA</option>
                                                    <option value='FOUARA'";

                                                $burge_st = "";
                                                if($burge_name == "FOUARA"){ $burge_st = "selected";}

                                            $tbl .= "{$burge_st}>FOUARA</option>
                                                    <option value='JALPARI'";

                                                $burge_st = "";        
                                                if($burge_name == "JALPARI"){ $burge_st = "selected";}

                                            $tbl .= "{$burge_st}>JALPARI</option>
                                                    </select>
                                                    <input type='button' id = 'sendBurge' onclick='insertBurge(this,{$result[$i]['id']})' class='mb-xs mt-xs mr-xs btn btn-xs btn-info' value='ASSIGN' style='width:130px;'>
                                                </td>";
                                            
                                            $tbl .= "<td id='burgeApproval{$result[$i]['id']}' {$burgeApproval}>
                                                    <input type='button' class='form-control input-sm btn-warning' style='width:130px; height:25px;padding:2px;' value = 'APPROVAL REQUIRED'>
                                                </td>";
                                            

                                            $tbl .= "<td>
                                                        <input type='hidden' id='suppliedQtyId' value='{$id}'>
                                                        <input type='number' id='suppliedQty' value='{$suppliedQty}' class='form-control input-sm' style='width:65px;height:25px;' readonly>
                                                    </td>";

                                            $tbl .= "<td>
                                                        <input type='number' id='dueQty' value='{$dueQty}' class='form-control input-sm' style='width:65px;height:25px;' readonly>
                                                    </td>";
                                            
                                            $tbl .= "
                                                <td>
                                                <a class='badge btn-success mb-xs mt-xs mr-xs' data-toggle='modal' data-target='#exampleModalCenter'>
                                                    <i class='fa fa-eye'></i>
                                                </a>&nbsp;
                                                <!-- <a class='badge btn-info ml-1' href='#'><i class='fa fa-edit'></i></a> -->
                                                </td>";

                                            $tbl .= "</tr>";

                                            $vessel_query = "SELECT vsl_vessels.name AS vsl_name, vsl_vessel_visit_details.ib_vyg AS rotation, vsl_vessels.lloyds_id AS vsl_imo, 
                                            ref_bizunit_scoped.name AS agent_name, CONCAT(COALESCE(ref_bizunit_scoped.address_line1,''), COALESCE(ref_bizunit_scoped.address_line2,'')) AS address,
                                            ref_bizunit_scoped.email_address, ref_bizunit_scoped.bizu_gkey, COALESCE (ref_bizunit_scoped.sms_number,ref_bizunit_scoped.telephone) AS contact_num,
                                            ref_agent_representation.agent_gkey 
                                            FROM vsl_vessels 
                                            INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
                                            LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=vsl_vessel_visit_details.bizu_gkey 
                                            LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=ref_agent_representation.agent_gkey 
                                            WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";

                                            $vsl_rslt = $this->bm->dataSelect($vessel_query);

                                            $vsl_imo = "";
                                            $agent_name = "";
                                            $email = "";
                                            $mobile = "";
                                            if(count($vsl_rslt)>0){
                                                $vsl_imo = $vsl_rslt[0]['VSL_IMO'];
                                                $agent_name = $vsl_rslt[0]['AGENT_NAME'];
                                                $email = $vsl_rslt[0]['EMAIL_ADDRESS'];
                                                $mobile = $vsl_rslt[0]['CONTACT_NUM'];
                                            }
                                        ?>

                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h4 class="modal-title" id="exampleModalLongTitle">Details</h4>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Rotation No</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$rotation;?></div>
                                                            </div>
                                                            <div class="col-md-12 well primary" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Demand Date</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$demand_date;?></div>
                                                            </div>
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Shipping Agency</div>
                                                                <div class="col-md-1 style="padding:0px;"">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$agent_name;?></div>
                                                            </div>
                                                            <div class="col-md-12 well primary" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Request By</div>
                                                                <div class="col-md-1 style="padding:0px;"">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$u_name;?></div>
                                                            </div>
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Requester Email</div>
                                                                <div class="col-md-1 style="padding:0px;"">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$email;?></div>
                                                            </div>
                                                            <div class="col-md-12 well primary" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Requester Mobile</div>
                                                                <div class="col-md-1 style="padding:0px;"">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$mobile;?></div>
                                                            </div>
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Delivery Area</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$delivery_area;?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Demand Quantity</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$demand_qty;?></div>
                                                            </div>
                                                            <div class="col-md-12 well primary" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Approved Quantity</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$dockMaster_aprv_qty;?></div>
                                                            </div>
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Vessel Name</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$result[$i]['vessel_name'];?></div>
                                                            </div>
                                                            <div class="col-md-12 well primary" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Vessel IMO</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$vsl_imo;?></div>
                                                            </div>
                                                            <div class="col-md-12 well" style="margin-bottom:0px;">
                                                                <div class="col-md-4" style="padding:0px;">Delivery Status</div>
                                                                <div class="col-md-1" style="padding:0px;">:</div>
                                                                <div class="col-md-7" style="padding:0px;"><?=$dlv_sts;?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                                </div>
                                            </div>
                                            </div>

                                        <?php

                                            }
                                            echo $tbl;
                                        ?>
                                </tbody>
                            </table>
                            <?php 
                            if($section=='acc') 
                            {
                            ?>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <!--button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Forward
                                        </button-->
                                        <p class="btn p-3 mb-2 bg-white text-dark" style="cursor:default">You have selected <label id="item" style="font-size:20px;">0</label> Item </p>
                                        <input type="submit" name="fwBtn" id="forward" value="Forward" class="mb-xs mt-xs mr-xs btn btn-success" disabled/>
                                    </div>													
                                </div>
                            <?php 
                                }
                            ?>
                            </form>
                        </div>
                    <?php
                    if($org_Type_id == 82 && $section == "acc"){
                    ?>
                    <?php if($section=='acc') { ?> 
					<table width=100%>				
						<tr>
							<td align="left">
								ইহা আপনার সদয় অবগতি ও পরবর্তী ব্যবস্থার জন্য প্রেরন করা হল।<br/>
								<u>সংযুক্ত : &nbsp;&nbsp;<label id="filela" name="filela"></label> &nbsp;টি ভাউচার।</u>
						</tr>
						<tr><td> <br/></td></tr>
						<tr>
							<td align="right">
									নির্বাহী প্রকৌশলী (বি)/সিটি (অঃ দাঃ) <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                                তারিখঃ <?php echo date("d/m/Y")."ই;";?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							</td>
                        </tr>

                        <tr>
							<td align="left">
								ন;- ১৮.০৪.০০০০.৪২৬.০১১.১৮ <br/>
								অনুলিপিঃ- <br/>
                                ১। ডেপুটি কনজারভেটর/চবক এর সদয় অবগতির জন্য। <br/>
                                ২। প্রধান অর্থ ও হিসাব রক্ষন কর্মকর্তা/চবক এর সদয় অবগতির জন্য। <br/>
                                ৩। উপ-প্রধান প্রকৌশলী (বিদ্যুৎ)/চবক এর সদয় অবগতির জন্য। <br/>
                                ৪। ডক মাস্তার/চবক এর সদয় অবগতির জন্য। <br/>
							</td>
                        </tr>
						
					</table>
					<?php } ?>
                    </div>
                    <?php } ?>
				</section>
			</div>
		</div>	

        <!-- Remarks Modal -->
        <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="remarksModalLabel">Remarks</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="form-horizontal form-bordered" method="POST" name="waterDemandBackward" id="waterDemandBackward" action="<?php echo site_url('Vessel/waterDemandBackward/').$id; ?>">
                        <div class="modal-body">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
                                <input type="text" name="remarks" id="remarks" class="form-control">
                                <input type="hidden" name="id" id="id" value="" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="return Modalconfirmation()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- File Upload Modal -->
        <div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="fileUploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileUploadModalLabel">Upload Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="form-horizontal form-bordered" method="POST" name="fileUpload" id="fileUpload" action="<?php echo site_url('Vessel/waterDemandFileUpload/').$id; ?>" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="input-group mb-md">
                                <input type="hidden" name="fileId" id="fileId" value="" class="form-control">
                                <input type="file" class="form-control" name="file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="return fileModalconfirmation()">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Show Remarks Modal -->
        <div class="modal fade" id="showRemarksModal" tabindex="-1" role="dialog" aria-labelledby="showRemarksModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showRemarksModalLabel">Remarks</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mb-md">
                            <p id="showRemarks"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

	<!-- end: page -->
</section>
</div>