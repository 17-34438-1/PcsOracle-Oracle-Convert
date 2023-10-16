<script>

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

    function supplyModalconfirmation(){
        supplyDate = document.getElementById('supplyDate').value.trim();

        if(!supplyDate){
            alert("Write Supply date Please..");
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
        actionValremarks = "http://cpatos.gov.bd/pcs/index.php/Vessel/waterDemandBackward/"+id;
        actionValFile = "http://cpatos.gov.bd/pcs/index.php/Vessel/waterDemandFileUpload/"+id;
        document.getElementById('waterDemandBackward').action = actionValremarks;
        document.getElementById('fileUpload').action = actionValFile;
        document.getElementById('id').value = id;
        document.getElementById('fileId').value = id;
    } 
	
	function setSupplyDateAction(id){
       // actionValremarks = "http://cpatos.gov.bd/pcs/index.php/Vessel/waterDemandBackward/"+id;
       // actionValFile = "http://cpatos.gov.bd/pcs/index.php/Vessel/waterDemandFileUpload/"+id;
       // document.getElementById('waterDemandBackward').action = actionValremarks;
       // document.getElementById('fileUpload').action = actionValFile;
        document.getElementById('supply_id').value = id;
       // document.getElementById('fileId').value = id;
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
                                        <th class="text-center" rowspan="2" id="vertical_center">Vessel name</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Berth</th>
                                        <!-- <th class="text-center">Supply Type</th>	 -->
                                        <th class="text-center" colspan="2" id="vertical_center">Demand</th>
                                        <th class="text-center" colspan="2" id="vertical_center">Forward</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">Supply Status</th>
                                        <th class="text-center" rowspan="2" id="vertical_center">File</th>
										
                                        <?php

                                            if($org_Type_id == 78 || $org_Type_id == 81 || ($org_Type_id == 82 && ($section == "billop" || $section == "dcfo")) || $org_Type_id == 83){
                                        ?>								
                                        <th class="text-center" rowspan="2" id="vertical_center">Action</th>									
                                        <?php
                                            }
                                        ?>
										
										<!--Nadim Start -->
										<?php if($org_Type_id==78 AND ($section == 10 || $section == 14 || $section == 15)){?>
										
										<th class="text-center" rowspan="2" id="vertical_center">Update.</th>
										<?php }?>
										<!--Nadim end  -->
                                        
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
                                        <th class="text-center" id="vertical_center">By</th>
                                        <!-- <th class="text-center" id="vertical_center">Date</th> -->
                                        <th class="text-center" id="vertical_center">Status</th>
                                        <th class="text-center" id="vertical_center" width="30%">At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $tbl = "";
										$rot="";
										$eng_aprv_st="";
										$marineAprvSt="";
										$supplyType="";
                                        for($i=0;count($result)>$i;$i++)
                                        {
                                            $id = $result[$i]['id'];
                                            $qty = $result[$i]['demand_qty']." ".$result[$i]['demand_unit'];
                                            $rotation = $result[$i]['rotation_no'];
                                            $demand_by = $result[$i]['demand_by'];
											$supplyType = $result[$i]['supply_type'];
											$eng_aprv_st=$result[$i]['eng_aprv_st'];
											$marineAprvSt=$result[$i]['marine_aprv_st'];
										    $supply_date=$result[$i]['supply_date'];
											
										  
											//echo "supply_type:" .$result[$i]['supply_type']."  ".$result[$i]['eng_aprv_st']; 
											
										    //$rotation_no = $result[$i]['rotation_no'];
											 $rotation_no = str_replace('/','_',$result[$i]['rotation_no']);
											//echo "<br>";
											

                                            $berthVal = strtoupper(substr($result[$i]['berth'],0,1));
											
                                            

                                            $userNameQuery = "SELECT u_name FROM users WHERE login_id = '$demand_by'";
                                            $userNameResult = $this->bm->dataSelectDB1($userNameQuery);
                                            $u_name = "";
                                            if(count($userNameResult)>0){
                                                $u_name = $userNameResult[0]['u_name'];
                                            }

                                            $tbl .= "<tr>";
                                            $sl = $i+1;

                                            if($org_Type_id == 82 && $section == "acc")
                                            {
                                            $tbl.="<td align='center'><input type='checkbox' name='idchk[]' id='idchk{$i}' onclick='selectCheck(this);' value='{$result[$i]['id']}'";

                                            // if($result[$i]['marine_aprv_st'] == 0){
                                            //     if($result[$i]['dcee_aprv_st'] == 0  || $result[$i]['acc_aprv_st'] == 1){
                                            //         $tbl.="disabled";
                                            //         //$st = 1;
                                            //     }
                                            // }
                                            // else 
                                            // {
                                            //     if($result[$i]['demand_aprv_st'] == 0 || $result[$i]['acc_aprv_st'] == 1){
                                            //         $tbl.="disabled";
                                            //         //$st = 1;
                                            //     }
                                            // }

                                            if(($result[$i]['supply_type']=='shore' && $result[$i]['xen_aprv_st'] == 0)  || $result[$i]['acc_aprv_st'] == 1)
                                            {
                                                $tbl.="disabled";
                                            }

                                            $tbl.="></td>";
                                            }

                                            $tbl.="<td align='center'> {$sl} </td>";
                                            $tbl.="<td align='center'> {$rotation} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['vessel_name']} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['berth']} </td>";
                                            // $tbl.="<td align='center'> {$supplyType} </td>";
                                            $tbl.="<td align='center'> {$qty} </td>";
                                            // $tbl.="<td align='center'> {$result[$i]['demand_date']} </td>";
                                            $tbl.="<td align='center'> {$u_name} </td>";

                                            // Forward Status

                                            //$forward_time = "";

                                            if($result[$i]['supply_type']=='burge' && $result[$i]['marine_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Marine needs to forward </td>";
                                            }
                                            else if($result[$i]['marine_aprv_st']==1 && $result[$i]['demand_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Marine forwarded to Harbour Master </td>";
                                                //$forward_time = $result[$i]['marine_aprv_at'];
                                            }
											else if($result[$i]['demand_aprv_st']==1 && $result[$i]['acc_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Forwarded to Accounts </td>";
                                                //$forward_time = $result[$i]['marine_aprv_at'];
                                            }
                                            else if($result[$i]['supply_type']=='shore' && $result[$i]['eng_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Sub Asst. Engineer needs to forward </td>";
                                            }
                                            else if($result[$i]['eng_aprv_st']==1 && $berthVal== 'G' && $result[$i]['sr_sub_eng_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Sub Asst. Engineer forwarded to Sr. Sub Asst. Engineer </td>";
                                                //$forward_time = $result[$i]['eng_aprv_at'];
                                            }
                                            else if($result[$i]['eng_aprv_st']==1 && $berthVal == 'G' && $result[$i]['sr_sub_eng_aprv_st']==1 && $result[$i]['asst_eng_aprv_st']==0)
                                            {
                                                if($result[$i]['asst_eng_dispute_st']==1)
                                                {
                                                    $tbl.="<td align='center' class='text-danger'> Asst. Engineer backwarded to Sub Asst. Engineer </br></br> <button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#showRemarksModal' onclick='setRemarks("."\"".$result[$i]['asst_eng_dispute_remarks']."\"".")'>Remarks</button> </td>";
                                                    //$forward_time = $result[$i]['asst_eng_dispute_at'];
                                                }
                                                else
                                                {
                                                    $tbl.="<td align='center'> Sr. Sub Asst. Engineer forwarded to Asst. Engineer </td>";
                                                    //$forward_time = $result[$i]['sr_sub_eng_aprv_at'];
                                                }
                                                
                                            }
                                            else if($result[$i]['eng_aprv_st']==1 && $berthVal != 'G' && $result[$i]['asst_eng_aprv_st']==0)
                                            {
                                                if($result[$i]['asst_eng_dispute_st']==1)
                                                {
                                                    $tbl.="<td align='center' class='text-danger'> Asst. Engineer backwarded to Sub Asst. Engineer </br></br> <button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#showRemarksModal' onclick='setRemarks("."\"".$result[$i]['asst_eng_dispute_remarks']."\"".")'>Remarks</button> </td>";
                                                    //$forward_time = $result[$i]['asst_eng_dispute_at'];
                                                }
                                                else
                                                {
                                                    $tbl.="<td align='center'> Sub Asst. Engineer forwarded to Asst. Engineer </td>";
                                                    //$forward_time = $result[$i]['eng_aprv_at'];
                                                }
                                            }
                                            else if($result[$i]['asst_eng_aprv_st']==1 && $result[$i]['xen_aprv_st']==0)
                                            {
                                                $tbl.="<td align='center'> Asst. Engineer forwarded to EXEN </td>";
                                                //$forward_time = $result[$i]['asst_eng_aprv_at'];
                                            }
                                            // else if($result[$i]['xen_aprv_st']==1 && $result[$i]['dcee_aprv_st']==0)
                                            // {
                                            //     $tbl.="<td align='center'> EXEN forwarded to Deputy Chief Engineer (Electrical)</td>";
                                            //     //$forward_time = $result[$i]['asst_eng_aprv_at'];
                                            // }
                                            // else if($result[$i]['acc_aprv_st']==0 && (($result[$i]['eng_aprv_st']==1 && $result[$i]['xen_aprv_st']==1) || ($result[$i]['marine_aprv_st']==1 && $result[$i]['demand_aprv_st']==1)))
                                            // else if($result[$i]['dcfo_aprv_st']==0 && (($result[$i]['xen_aprv_st']==1 && $result[$i]['dcee_aprv_st']==1) || ($result[$i]['marine_aprv_st']==1 && $result[$i]['demand_aprv_st']==1)))
                                            // {
                                            //     $tbl.="<td align='center'> Forwarded to Deputy Chief Finance </td>";
                                            // }
                                            else if($result[$i]['xen_aprv_st']==1 && $result[$i]['acc_aprv_st']==0) // dcfo_aprv_st
                                            {
                                                $tbl.="<td align='center'> Forwarded to Accounts </td>";
                                            }
                                            else if($result[$i]['acc_aprv_st']==1 && $result[$i]['bill_op_bill_st']==0)
                                            {
                                                $tbl.="<td align='center'> Forwarded to Bill Operator </td>";
                                            }
                                            else if($result[$i]['bill_op_bill_st']==1)
                                            {
                                                $tbl.="<td align='center'> Bill Generated </td>";
                                            }

                                            // Forwarded At
                                            
                                            $forward_at = "";
                                            $demand_at = $result[$i]['demand_at'];

                                            $forward_at.="Demand at : <b>".$demand_at."</b><br/>";

                                            if($result[$i]['supply_type'] == 'shore')
                                            {
                                                $sae_forward = $result[$i]['eng_aprv_at'];
                                                $srsae_forward = $result[$i]['sr_sub_eng_aprv_at'];
                                                $ae_forward = $result[$i]['asst_eng_aprv_at'];
                                                $ae_backward = $result[$i]['asst_eng_dispute_at'];
                                                $exen_forward = $result[$i]['xen_aprv_at'];
                                                $dcee_forward = $result[$i]['dcee_aprv_at'];
                                                
                                                $forward_at.="Sub. asst. Engr. forwarded at : <b>".$sae_forward."</b><br/>";
                                                if($berthVal == 'G'){
                                                    $forward_at.="Sr. Sub. asst. Engr. forwarded at : <b>".$srsae_forward."</b><br/>";
                                                }

                                                if($result[$i]['asst_eng_dispute_st'] == 1){
                                                    $forward_at.="Asst. Engr. Backwarded at : <b>".$ae_backward."</b><br/>";
                                                }else{
                                                    $forward_at.="Asst. Engr. forwarded at : <b>".$ae_forward."</b><br/>";
                                                }

                                                $forward_at.="EXEN forwarded at : <b>".$exen_forward."</b><br/>";
                                                // $forward_at.="DCEE forwarded at : <b>".$dcee_forward."</b><br/>";
                                            }
                                            else if($result[$i]['supply_type'] == 'burge')
                                            {
                                                $marine_forward = $result[$i]['marine_aprv_at'];
                                                $master_forward = $result[$i]['demand_aprv_at'];

                                                $forward_at.="Marine forwarded at : <b>".$marine_forward."</b><br/>";
                                                $forward_at.="HM forwarded at : <b>".$master_forward."</b><br/>";
                                            }


                                            // $forward_at.="DCFO forwarded at : <b>".$result[$i]['dcfo_aprv_at']."</b><br/>";
                                            $forward_at.="A/C forwarded at : <b>".$result[$i]['acc_aprv_at']."</b><br/>";
                                            $forward_at.="Bill Generated at : <b>".$result[$i]['bill_op_bill_at']."</b><br/>";

                                            $tbl.="<td align='left'> 
                                                    {$forward_at}
                                                    </td>";

                                            // Supply Status 

                                            $supplyQuery = "";
                                            if($supplyType == "shore"){
                                               /*  $supplyQuery = "SELECT COUNT(*) AS rtnValue,sparcsn4.srv_event.placed_time FROM sparcsn4.vsl_vessel_visit_details 
                                                INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
                                                WHERE ib_vyg='$rotation' AND sparcsn4.srv_event.event_type_gkey=169";    */

												$supplyQuery = "SELECT COUNT(*) AS rtnValue, supply_date as  placed_time  FROM ctmsmis.water_demand_info 
																WHERE  water_demand_info.id='$id'"; 
                                            }
                                            else if($supplyType == "burge")
                                            {
                                                /* $supplyQuery = "SELECT COUNT(*) AS rtnValue,sparcsn4.srv_event.placed_time FROM sparcsn4.vsl_vessel_visit_details 
                                                INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
                                                WHERE ib_vyg='$rotation' AND sparcsn4.srv_event.event_type_gkey IN(168,459,461,463)"; */
												 $supplyQuery = "SELECT COUNT(*) AS rtnValue, supply_date as  placed_time  FROM ctmsmis.water_demand_info 
																WHERE  water_demand_info.id='$id'"; 
                                            }

                                            $supplyResult = $this->bm->dataSelectDb2($supplyQuery);

                                            $supplyStatus = null;
                                            $placedTime = null;
                                            
                                            if(count($supplyResult)>0){
                                                $supplyStatus = $supplyResult[0]["rtnValue"];
                                                $placedTime = $supplyResult[0]["placed_time"];
                                            }

                                            if($placedTime != ""){
                                                $tbl.="<td align='center'> <font color='green'>Supplied at {$placedTime}</font> </td>";
                                            }else{
                                               // $tbl.="<td align='center'> <font color='red'>Not supplied</font> </td>";
												  $tbl.="<td align='center'><button style='margin-top:5px;' type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#supplyDateModal' onclick='setSupplyDateAction(".$id.")' >SupplyDate</button></td>";
                                            }
											
/* 											if ($org_Type_id == 83){
												 $tbl.="<td align='center'>";
											  $tbl.="<button style='margin-top:5px;' type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#fileUploadModal' onclick='setAction(".$id.")' {$disabled}>Upload</button>";
											 $tbl."</td>";
											} */

                                            // Document upload

                                            $tbl.="<td align='center'>";

                                            if(($eng_section == "SAECCT" || $eng_section == "SAENCT" || $eng_section == "SAEGCB" || $org_Type_id == 83))
                                            {
                                                $disabled = "";
                                                if($result[$i]["asst_eng_aprv_st"] == 1 || $result[$i]["demand_aprv_st"] == 1){
                                                    $disabled = "disabled";
                                                }
                                                $tbl.="<button style='margin-top:5px;' type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#fileUploadModal' onclick='setAction(".$id.")' {$disabled}>Upload</button>";
                                            }

                                            if($result[$i]['docPath'] != NULL)
                                            {
                                                $docPath = "/pcs/resources/waterBill/".$result[$i]['docPath'];
                                                $tbl.="<div style='margin-top:5px;'><a href='{$docPath}' target='_blank' class='btn btn-xs btn-success'>View</a><div>";
                                            }
                                            // else
                                            // {
                                            //     if(!($eng_section == "SAECCT" || $eng_section == "SAENCT" || $eng_section == "SAEGCB"))
                                            //     {
                                            //         $tbl.="<p class='text-danger'><b>Not uploaded</b></p>";
                                            //     }
                                            // }
                                            
                                            $tbl."</td>";
											
											//$tbl.="<td align='center'> jjjj </td>";
											
											


                                            
                                            if($org_Type_id == 78 || $org_Type_id == 81 || ($org_Type_id == 82 && ($section == "billop" || $section == "dcfo")) || $org_Type_id == 83)
                                            {
                                                if($supplyStatus == 1)
                                                {
                                                    if($org_Type_id == 83 && $result[$i]['supply_type']=='burge' && $result[$i]['marine_aprv_st']==0)
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    else if($org_Type_id == 81 && $result[$i]['supply_type']=='burge' && $result[$i]['demand_aprv_st']==0 && $result[$i]['marine_aprv_st']==1)
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    else if($org_Type_id == 78 && ($eng_section == "SAECCT" || $eng_section == "SAENCT" || $eng_section == "SAEGCB") && $result[$i]['supply_type']=='shore' && ($result[$i]['eng_aprv_st']==0 || ($result[$i]['eng_aprv_st']==1 || $result[$i]['sr_sub_eng_aprv_st']==1) && $result[$i]['asst_eng_dispute_st']==1))
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    else if($org_Type_id == 78 && $berthVal == 'G' && $eng_section == "SRSAE" && $result[$i]['supply_type']=='shore' && $result[$i]['eng_aprv_st']==1 && ($result[$i]['sr_sub_eng_aprv_st']==0 || ($result[$i]['sr_sub_eng_aprv_st']==1 && $result[$i]['asst_eng_dispute_st']==1)))
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    else if($org_Type_id == 78 && $berthVal == 'G' && $eng_section == "AENG" && $result[$i]['supply_type']=='shore' && $result[$i]['sr_sub_eng_aprv_st']==1 && $result[$i]['asst_eng_aprv_st']==0 && $result[$i]['asst_eng_dispute_st']==0)
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> <button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#remarksModal' onclick='setAction(".$id.")'>Back</button></td>";
                                                    }
                                                    else if($org_Type_id == 78 && $berthVal != 'G' && $eng_section == "AENG" && $result[$i]['supply_type']=='shore' && $result[$i]['eng_aprv_st']==1 && $result[$i]['asst_eng_aprv_st']==0 && $result[$i]['asst_eng_dispute_st']==0)
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> <button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#remarksModal' onclick='setAction(".$id.")'>Back</button></td>";
                                                    }
                                                    else if($org_Type_id == 78 && $eng_section == "EXENCT" && $result[$i]['supply_type']=='shore' && $result[$i]['asst_eng_aprv_st']==1 && $result[$i]['xen_aprv_st']==0)
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    // else if($org_Type_id == 78 && $eng_section == "DCEE" && $result[$i]['supply_type']=='shore' && $result[$i]['xen_aprv_st']==1 && $result[$i]['dcee_aprv_st']==0)
                                                    // {
                                                    //     $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    // }
                                                    // else if($org_Type_id == 82 && $section == "dcfo" && $result[$i]['dcfo_aprv_st']==0 && (($result[$i]['supply_type']=='shore' && $result[$i]['dcee_aprv_st']==1) || ($result[$i]['supply_type']=='burge' && $result[$i]['demand_aprv_st']==1)))
                                                    // {
                                                    //     $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    // }
                                                    else if($org_Type_id == 82 && $section == "acc" && $result[$i]['dcfo_aprv_st']==1 && $result[$i]['acc_aprv_st']==0 )
                                                    {
                                                        $tbl.="<td align='center'> <a href='".site_url('Vessel/waterDemandForward/'.$id)."' onclick='return confirmation()' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Forward </a> </td>";
                                                    }
                                                    else 
                                                    if($org_Type_id == 82 && $section == "billop" && $result[$i]['acc_aprv_st']==1)
                                                    {
                                                        include("dbOracleConnection.php");																		
                                                        $sql_chkOaDate = "SELECT off_port_arr
                                                        FROM vsl_vessel_visit_details
                                                        WHERE vsl_vessel_visit_details.ib_vyg='".$result[$i]['rotation_no']."' 
                                                        AND off_port_arr IS NOT NULL AND off_port_arr!=''";
                                                        
                                                        $rslt_chkOaDate = oci_parse($con_sparcsn4_oracle,$sql_chkOaDate);
                                                        oci_execute($rslt_chkOaDate);
                                                        $results=array();
                                                        $chkOaDate =oci_fetch_all($rslt_chkOaDate, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                                                        oci_free_statement($rslt_chkOaDate);
                                                        $rslt_chkOaDate = oci_parse($con_sparcsn4_oracle,$sql_chkOaDate);
                                                        oci_execute($rslt_chkOaDate);
                                                        
                                                        
                                                        if($chkOaDate == 0)
                                                        {
                                                            // $tbl.="<td align='center'> <p class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'> No Outer Anchorage Date </p> </td>";
                                                            $tbl.="<td align='center'> <font color='red'><b>No Outer Anchorage Date</b></font> </td>";
                                                        }
                                                        else
                                                        {
                                                            if($result[$i]['bill_op_bill_st']==1)
                                                            {
                                                                $tbl.="<td align='center'> <font color='green'><b>Bill Generated</b></font> </td>";
                                                            }
                                                            else
                                                            {														
                                                                $oaDate = "";
                                                                while(($row_chkOaDate = oci_fetch_object($rslt_chkOaDate))!=false)
                                                                {
                                                                    $oaDate = $row_chkOaDate->OFF_PORT_ARR;
                                                                }
                                                            
                                                                $sql_dollarRate = "SELECT rate
                                                                FROM billing.bil_currency_exchange_rates
                                                                WHERE DATE(effective_date)=DATE('$oaDate')";
                                                                // echo $sql_dollarRate;
                                                                $rslt_dollarRate = mysqli_query($con_sparcsn4,$sql_dollarRate);
                                                                $isExist = mysqli_num_rows($rslt_dollarRate);

                                                                if($isExist>0)
                                                                {
                                                                    $tbl.="<td align='center'>
                                                                        <a class='btn btn-xs btn-primary' href='".site_url('VesselBill/Generate_WaterSupply_Bill/'.str_replace('/','_',$result[$i]['rotation_no']))."' onclick='return confirMsg();' style='color:white'>Generate Bill</a>
                                                                    </td>";
                                                                }
                                                                else
                                                                {
                                                                    $tbl.="<td align='center'>
                                                                        <a class='btn btn-xs btn-danger' href='".site_url('Vessel/usdtoBdtExchangeRateform/')."' 
                                                                        style='color:white'>
                                                                            <u>Rate Setting</u>
                                                                        </a>
                                                                    </td>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $tbl.="<td align='center'> <p class='mb-xs mt-xs mr-xs btn btn-xs btn-primary' disabled> Forward </p> </td>";
                                                    }
                                                }
                                                else
                                                {
                                                    $tbl.="<td align='center'> <p class='mb-xs mt-xs mr-xs btn btn-xs btn-primary'> Event not created </p> </td>";
                                                }
                                            }
											
											
											//nadim start
											
											if($org_Type_id==78 AND ($section == 10 || $section == 14 || $section == 15))
											{
												  
                                                if($berthVal == 'G' && ($result[$i]['asst_eng_aprv_st']==1 || ($placedTime == "" || is_null($placedTime))))
                                                {
                                                    $tbl.="<td align='center'> <a  style='background-color:Violet;' class='mb-xs mt-xs mr-xs btn btn-xs' disabled> Update</a> </td>";
                                                }
                                                else if(($berthVal=='N' || $berthVal=='C') && ($result[$i]['asst_eng_aprv_st']==1 || ($placedTime == "" || is_null($placedTime))))
                                                {
                                                    $tbl.="<td align='center'> <a style='background-color:Violet;'  class='mb-xs mt-xs mr-xs btn btn-xs' disabled> Update</a> </td>";
                                                }
                                                else
                                                {
                                                    $tbl.="<td align='center'> <a href='".site_url('Vessel/updateWaterDemand/'.$id)."'  class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'> Update</a> </td>";
                                                }
											}
                                            
											/*else
                                            {
												$tbl.="<td align='center'> <a href='".site_url('Vessel/updateWaterDemand/'.$id)."'  class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'> Update </a> </td>";
											}*/
											//nadim end
											

                                            if($org_Type_id == 82 && $section == "billop")
                                            {
                                                // $tbl.="<td align='center'> <a href='".site_url('Vessel/waterBill/'.$id)."' target='_blank' class='mb-xs mt-xs mr-xs btn btn-xs btn-warning'> Generate Bill </a> </td>";
                                            }

                                            $tbl .= "</tr>";
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


        <!-- Supply Date Modal -->
        <div class="modal fade" id="supplyDateModal" tabindex="-1" role="dialog" aria-labelledby="supplyDateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplyDateModalLabel">Supply Date</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form class="form-horizontal form-bordered" method="POST" name="supplyDateModal" id="supplyDateModal" action="<?php echo site_url('Vessel/supplyDateUpdate/').$id; ?>">
                        <div class="modal-body">
                            <div class="input-group mb-md">
                                <span class="input-group-addon span_width">Water Supply Date <span class="required">*</span></span>
                                <input type="date" name="supplyDate" id="supplyDate" class="form-control">
                                <input type="hidden" name="supply_id" id="supply_id" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="mb-xs mt-xs mr-xs btn btn-danger" onclick="return supplyModalconfirmation()">Submit</button>
                        </div>
                    </form>
                </div>
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