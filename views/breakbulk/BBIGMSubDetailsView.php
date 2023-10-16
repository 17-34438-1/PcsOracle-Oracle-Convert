<script type="text/javascript">
</script>

<?php 
$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
$this->TM=$type;

?>
<?php 
		 echo form_open('breakbulk/BBIGMController/BBIGMSubDetailsSerch');
				$Stylepadding = 'style="padding: 12px 20px;"';
				if(!empty($error_message))
				{
					$Stylepadding = 'style="padding:25px 20px;"';
				}	
				if(isset($captcha_image)){
					$Stylepadding = 'style="padding:62px 20px 93px;"';
				}
 ?>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>
    <section class="panel">
                <div class="panel-body">
    <div class="table-responsive">
		<div>
		<input type="hidden" name="myflag" value="53">
            <input type="hidden" name="TM" value="<?php print($this->TM); ?>">
            <span><b>Search By :</b></span>
            <select name="lbl_search" onFocus="gsLabelObj(lbl_org_id17,'#61BCEF','white')"
                onBlur="gsLabelObj(lbl_org_id17,'','')">
                <option value="1">B/L No</option>
                <option value="2">Line No</option>
                <option value="3">Rotation No</option>
            </select>
            <input type="text" name="txt_serch" value="" maxlength="50" style="width:150px" required>
            <?php $arrt2 = array('name'=>'btn_serch2','id'=>'submit2','value'=>'GO!','class'=>'login_button'); echo form_submit($arrt2);?> 
		</div>
        <table class="table table-bordered table-hover table-striped mb-none" cellspacing="0" id="datatable-default">
            <thead>
                <tr>
                    <th>Shipping Agent Name</th>
                    <th>MLO&nbsp;Code</th>
                    <th>Line&nbsp;No.</th>
                    <th>B/L Number</th>
                    <th>Number/Quantity</th>
                    <th>Kind of Package</th>
                    <th>Marks&nbsp;&&nbsp;Number</th>
                    <th>Description&nbsp;Of&nbsp;Goods</th>
                    <th>Gross Weight</th>
                    <th>Net Weight</th>
                    <th>IGM Submission Date</th>
                    <th>Consignee&nbsp;and&nbsp;Notify&nbsp;Party</th>
                    <!-- <th>Bill&nbsp;Of&nbsp;Entry&nbsp;Number</th> -->
                    <th>Bill&nbsp;Of&nbsp;Entry&nbsp;Date</th>
                    <!-- <th>Truck&nbsp;Entry</th> -->
                    <th>Delivered</th>
                    <th>Discharged</th>
                    <th>C&nbsp;&&nbsp;F&nbsp;Agent&nbsp;Name</th>
                    <th>Remarks</th>
                    <th>AIR BLock Status</th>
                    <th>Delivery Block Status</th>
                    <th>Intelligence Block Status</th>
                    <th>IMCO</th>
                    <th>UN</th>
                    <th>Supplementary</th>
                    <th>Delivery Order</th>
                </tr>
            </thead>
            <?php
     if($igmMasterList) {
			$len=count($igmMasterList);
            for($i=0;$i<$len;$i++){
                $igmId=$igmMasterList[$i]['id'];
		?>
                <tr>
                    <td>
                        <?php print('AIN NO: '.$igmMasterList[$i]['AIN_No'].'<br>'.$igmMasterList[$i]['Organization_Name']); ?>
                    </td>
                    <td><?php print($igmMasterList[$i]['mlocode']); ?></td>
                    <td><?php print($igmMasterList[$i]['Line_No']); ?></td>
                    <td><?php print($igmMasterList[$i]['BL_No']); ?></td>
                    <td><?php print($igmMasterList[$i]['Pack_Number']); ?></td>

                    <td><?php print($igmMasterList[$i]['Pack_Description']); ?></td>
                    <td><?php print($igmMasterList[$i]['Pack_Marks_Number']); ?></td>
                    <td><?php print($igmMasterList[$i]['Description_of_Goods']); ?></td>
                    <!--<td><?php print($igmMasterList[$i]['Date_of_Entry_of_Goods']); ?></td>-->
                    <td>
                        <?php print($igmMasterList[$i]['weight']); ?>&nbsp;<?php print($igmMasterList[$i]['weight_unit']); ?>
                    </td>
                    <td>
                        <?php print($igmMasterList[$i]['net_weight']); ?>&nbsp;<?php print($igmMasterList[$i]['net_weight_unit']); ?>
                    </td>


                    <td><?php print($igmMasterList[$i]['final_submit_date']); ?></td>

                    <?php 
					//load container detail
						//print("select cnt.id as id,cnt.cont_number as cont_number,cnt.cont_size as cont_size,cnt.cont_iso_type as cont_iso_type,cnt.cont_weight as cont_weight,cnt.cont_seal_number as cont_seal_number,cnt.cont_description as cont_description from igm_detail_container cnt where cnt.igm_detail_id=$row->id");
						$id=$igmMasterList[$i]['id'];
					?>
                    <td>
                        <table width="100%">
                            <tr>
                                <th>Consignee</th>
                            </tr>
                            <tr>
                                <td><?php print($igmMasterList[$i]['ConsigneeDesc']); ?></td>
                            </tr>
                            <tr>
                                <th>Notify Party</th>
                            </tr>
                            <tr>
                                <td><?php print($igmMasterList[$i]['NotifyDesc']); ?></td>
                            </tr>
                        </table>

                    </td>
                    <!-- <td  align="center"></td> -->
                    <td><?php print($igmMasterList[$i]['Bill_of_Entry_Date']); ?></td>
                    <!-- <td></td> -->
                    <td><?php print($igmMasterList[$i]['No_of_Pack_Delivered']); ?></td>
                    <td><?php print($igmMasterList[$i]['No_of_Pack_Discharged']); ?></td>
                    <td> </td>
                    <td><?php print($igmMasterList[$i]['Remarks']); ?></td>
                    <td><?php print($igmMasterList[$i]['AFR']); ?></td>
                    <td><?php print($igmMasterList[$i]['delivery_block_stat']); ?></td>
                    <td><?php print($igmMasterList[$i]['int_block']); ?></td>
                    <td><?php print($igmMasterList[$i]['imco']); ?></td>
                    <td><?php print($igmMasterList[$i]['un']); ?></td>
                    <?php
			$CODE=$igmMasterList[$i]['IGM_id'];
		?>
                    <td><a href="<?php echo site_url("breakbulk/BBIGMController/igmSupplementryView/$CODE/$igmId/$type") ?>"
                            target='upper_top'>View Supplementary</a><br>
                    <td><a href="<?php echo site_url('report/viewDeliveryOrder/'.$igmMasterList[$i]['BL_No'].'/'.str_replace("/","_",$igmMasterList[$i]['Import_Rotation_No'])) ?>"
                            target="_BLANK">View Report</td>
                </tr>
            <?php	
			}
			
		}
		?>
        </table>
    </div>
  </div>
    </div>
    </section>
</section>