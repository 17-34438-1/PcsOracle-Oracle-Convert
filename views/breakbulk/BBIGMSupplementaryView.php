<?php
/*****************************************************
Developed BY: Anit Kumar NAth
Senior Software Engineer
DataSoft Systems Bangladesh Ltd
******************************************************/
$rotation=$igmMasterList[0]['Import_Rotation_No'];
?>
<script type="text/javascript">
function validate_required(field, alerttxt) {
    with(field) {
        if (value == null || value == "") {
            alert(alerttxt);
            return false;
        } else {
            return true
        }
    }
}

function validate_form(thisform) {
    with(thisform) {
        if (validate_required(txt_str_Search, "Please Type Your Text to Search") == false) {
            return false;
        }
    }
}
</script>

<div class="table-responsive">
    <div>
        <span><b>VESSEL NAME:</b></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>IMPORT ROTATION NO :</b> <b><?php echo $rotation;?></b>

    </div>

    <table class="table table-bordered table-hover table-striped mb-none" cellspacing="0" id="datatable-default">
        <thead>
            <tr>
                <th>Shipping Agent Name</th>
                <th>Line No.</th>
                <th>HBL</th>
                <th>Number/Quantity</th>
                <th>Kind of Package</th>
                <th>Gross Weight</th>
                <th>Net Weight</th>
                <th>Marks & Number</th>
                <th>Description Of Goods</th>
                <th>Submission Date</th>
                <th>Consignee and Notify Party</th>
                <th>Bill Of Entry Number</th>
                <th>Bill Of Entry Date</th>
                <th>Delivered</th>
                <th>Discharged</th>
                <th>C& F Agent Name</th>
                <th>Remarks</th>
                <th>Supplementary</th>
                <th>Navy Comments</th>
            </tr>
        </thead>
        <?php
			if($igmMasterList) {
			$len=count($igmMasterList);
            for($i=0;$i<$len;$i++){
				$igm_id=$igmMasterList[$i]['id'];
		      ?>
        <?php
				$myline1=explode("*",$igmMasterList[$i]['Line_No']);
				$mycnt1=count($myline1);
				$mlinedata1=$myline1[$mycnt1-1];
				$mybl2=explode("*",$igmMasterList[$i]['BL_No']);
				$mycnt2=count($mybl2);
				$mbldata1=$mybl2[$mycnt2-1];
				?>
        <?php
					$AIN_No = "";
					if($igmMasterList[$i]['AIN_No_New']==" " or $igmMasterList[$i]['AIN_No_New']=="" or $igmMasterList[$i]['AIN_No_New']==NULL){
						$AIN_No = $igmMasterList[$i]['AIN_No'];
					} 
					else{
						$AIN_No = $igmMasterList[$i]['AIN_No_New'];
					}
					?>
        <tr>
            <!--td valign="top"><?php print('AIN NO: '.$igmMasterList[$i]['AIN_No'].'<br>'.$igmMasterList[$i]['Agent_Name']); ?></td-->
            <td><?php print('AIN NO: '.$AIN_No.'<br>'.$igmMasterList[$i]['Agent_Name']); ?></td>
            <td><?php print($igmMasterList[$i]['Line_No']); ?></td>
            <td><?php print($mbldata1); ?></td>
            <td><?php print($igmMasterList[$i]['Pack_Number']); ?></td>
            <td><?php print($igmMasterList[$i]['Pack_Description']); ?></td>
            <td><?php print($igmMasterList[$i]['weight'].' '.$igmMasterList[$i]['weight_unit']); ?></td>
            <td><?php print($igmMasterList[$i]['net_weight'].' '.$igmMasterList[$i]['net_weight_unit']); ?></td>
            <td><?php print($igmMasterList[$i]['Pack_Marks_Number']); ?></td>
            <td><?php print($igmMasterList[$i]['Description_of_Goods']); ?></td>
            <!--<td><?php print($igmMasterList[$i]['Date_of_Entry_of_Goods']); ?></td>-->
            <td><?php print($igmMasterList[$i]['Submission_Date']); ?></td>
            <td>
                <table width="100%">
                    <tr>
                        <th align="left">Consignee</th>
                    </tr>

                    <tr>
                        <td><?php print($igmMasterList[$i]['ConsigneeDesc']); ?></td>
                    </tr>
                    <tr>
                        <th align="left">Notify Party</th>
                    </tr>
                    <tr>
                        <td><?php print($igmMasterList[$i]['NotifyDesc']); ?></td>
                    </tr>
                </table>
            </td>
            <td><a href='Forms/myBillEntryImportReportHTML.php?reg=<?php print($igmMasterList[$i]['Bill_of_Entry_No']);?>&date=<?php print($igmMasterList[$i]['Bill_of_Entry_Date']);?>&code=<?php print($igmMasterList[$i]['office_code']);?>'
                    target="aboutblank"><?php print($igmMasterList[$i]['Bill_of_Entry_No']);?></a></td>
            <td><?php print($igmMasterList[$i]['Bill_of_Entry_Date']); ?></td>
            <td><?php print($igmMasterList[$i]['No_of_Pack_Delivered']); ?></td>
            <td><?php print($igmMasterList[$i]['No_of_Pack_Discharged']); ?></td>
            <td></td>
            <td><?php print($igmMasterList[$i]['Remarks']); ?></td>
            <td><a href='home.php?myflag=40&CODE=<?php print($row->id); ?>&MCODE=<?php print($row->igm_master_id); ?>&SubCODE=<?php print($row->igm_detail_id); ?>&SSubCODE=<?php print($row->id); ?>&ImpRot=<?php print($row->Import_Rotation_No); ?>&MLine=<?php print($row->master_Line_No); ?>&MBL=<?php print($row->master_BL_No); ?>&MSLine=<?php print($row->Line_No); ?>&MSBL=<?php print($row->BL_No); ?>&TM=<?php print($this->TM); ?>&SFlag=1'
                    target='upper_top'>View Supplementary</a></td>
            <td></td>
        </tr>
        <?php  }}?>
    </table>
</div>