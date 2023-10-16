

	
	<?php
	
			$bl="";
			for($i=0; $i<count($notice_List); $i++)
			{
				$bl=$notice_List[$i]['BL_No'];
	?>
	

                <table width="80%" align="center">
				   <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <!-- <th align="right"><u>PA-325</u> /07 <br/> <span style="margin-right:30px;">CPA<span></th> -->
                        <th align="right" >
                            <table align="right">
                                <tr>
                                    <th></th>
                                    <th ><u>PA-325</u></th>
                                    <th rowspan="2"><font size="5">/07</font></th>
                                </tr>
                                <tr>
                                    <th  ></th>
                                    <th  >CPA</th>
                                </tr>
                            </table>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" align="center" style="font-size:16px;">THE CHITTAGONG PORT AUTHORITY</th>
                    </tr>
                    <tr>
                        <td>No:.............</td>
                        <td>&nbsp;</td>
                        <td align="right">Traffic Department</td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right">Dated, Chittagong</td>
                    </tr>
                    <tr>
                        <td colspan="3" >From:- Traffic Inspector <br/> YardNo. _____ <br/> Chittagong Port Authority <br/> Chittagong.</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">To, Messrs  <?php echo $notice_List[$i]['Notify_name'] ?><br/><?php echo $notice_List[$i]['Notify_address'] ?> <br/> ________________________________________</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <th colspan="3" align="center">NOTICE UNDER SECTION 25 OF THE CHITTAGONG PORT AUTHORITY (AMENDMENT) ACT.1995</th>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-left:50px;">Sub: Clearance of FCL/Empty Container <br/> Name of Vessel: &nbsp;&nbsp; 
						<?php echo $v_name;?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<br/> Rotation No: &nbsp;&nbsp;  <?php echo $rotation;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br/> 
						Arrival Date: &nbsp;&nbsp;  <?php echo $arrival_dt; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br/> 
						Common Landing Date: <?php echo $cl_date;?> </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">Dear Sir,</td>
                    </tr>
                    <tr>
                        <td colspan="3"><p><span style="padding-left:50px;">This<span> is to inform you that the under mentioned container /cargo of captioned vessel have been lying
                        uncleared A/C. This yard for the last 30 (Thirty) days or more. The same will be placed for Auction on expiry of 30
                        (thirty) days from the common landing date unless cleared earlier. In this connection no further notice will be
                        issued. 
                        </p></td>
                    </tr>
                </table>
            </tr>
            <tr>
                <table border="1" width="60%" align="center" style="border-collapse: collapse;">
                    <tr>
                        <th>SL No</th>
                        <th>Conxtainer No</th>
                        <th>BL No</th>
                        <th>Status</th>
                        <th>Marks</th>
                        <th>Remarks if Any</th>
                    </tr>
					<?php
					
					 $sql = "SELECT  igm_detail_container.cont_number, igm_details.Pack_Marks_Number, igm_details.BL_No, igm_detail_container.cont_status
							FROM igm_details
							INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
							WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No IN ('$bl')";	
							
						
					$cont_List = $this->bm->dataSelectDb1($sql);
					
					for($x=0; $x<count($cont_List); $x++)
					{
						$cont=$cont_List[$x]['cont_number'];
						$BL_No=$cont_List[$x]['BL_No'];
						$cont_status=$cont_List[$x]['cont_status'];
						$pack_Marks_Number= substr($cont_List[$x]['Pack_Marks_Number'],0,75) ;
						//$remarks=$cont_List[$x]['remarks'];
					?>
                    <tr>
						<td align="center"><?php echo $i+1; ?></td>
						<td align="center" ><?php echo $cont; ?></td>
						<td align="center" ><?php echo $BL_No; ?></td>
						<td align="center" ><?php echo $cont_status; ?></td>
						<td align="center"><?php echo $pack_Marks_Number; ?></td>
						<td align="center" ></td>
                       
                    </tr>
				<?php } ?>
                </table>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <table width="100%" align="center">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="center">You're faithfully <br/><br/><br/> Traffic Inspector <br/> Yard No ______ <br/> <b>CHITTAGONG PORT AUTHORITY</b></td>
                    </tr>
                </table>
            </tr>
            <tr>
                <table width="80%" align="center">
                    <tr>
                        <td style="padding-left:50px;">Copy to:-</td>
                    </tr>
                    <tr>
                        <td style="padding-left:50px;">1. Terminal Manager/CPA, for your kind information</td>
                    </tr>
                    <tr>
                        <td style="padding-left:50px;">2. Deputy commissioner(Auction), for your kind information & necessary action please </td>
                    </tr> 
					<tr>
                        <td style="padding-left:50px;">3. Shipping Agent, for your kind information</td>
                    </tr>
                </table>
            </tr>
            <tr>
                <table width="80%" align="center">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="center">Traffic Inspector <br/> Yard No ______ <br/> <b>CHITTAGONG PORT AUTHORITY</b></td>
                    </tr>
                </table>

		<?php if($i!=(count($notice_List)-1)) { ?>
		<pagebreak />
		<?php } ?>
		<?php
			}
		?>
