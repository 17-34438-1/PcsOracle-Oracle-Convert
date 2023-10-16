<script type="text/javascript">
    function doSum()
    {
        var impCont = document.getElementById('impCont').value;
        var expCont = document.getElementById('expCont').value;
        var emptyCont = document.getElementById('emptyCont').value;
		
        var sum = parseInt(impCont) + parseInt(expCont)+parseInt(emptyCont);
        //alert(sum);
		document.getElementById('total').value = sum;
    }
	
	
	function validate()
	{
	   if (confirm("Are you Confirm, You have filled all information?") == true) {
			   return true ;
			} else {
				return false;
			}
	}		
</script>
<style>
	tr
	{
		background-color:#E1F0FF;
	}
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">					
					<form class="form-horizontal form-bordered" name="myForm" onsubmit="return(validate());" 
						action="<?php echo site_url("uploadExcel/last24hrsOffDocStatement"); ?>" method="POST" enctype="multipart/form-data" target="_blank">
						<div class="form-group">
							<div class="table-responsive">
							<!--table class="table mb-none" style="border:solid 1px #ccc;" width="20px" align="center"-->
							<table align="center"  bgcolor="#A9E2F3" width="70%"  cellspacing="0" cellpadding="0">
								<?php
								//	if($ctime<=10 and $ctime>=9)
								//$diff=$rslt_time[0]['diff'];
								if($ctime==7 )
								{ 
								?>
								<tr>
									<td colspan="3" style="font-size:20px;color:red;">
											<marquee hspace="1"><b> Last 24 hrs statement saving process will be closed after <?php echo $diff; ?> hh:mmss for today.</b></marquee>
									</td>
								</tr>	
								<?php
								}
								else if($ctime>=8 and $msgFlag==1)
								{ 
								?>
								<tr>
									<td colspan="3" style="font-size:20px;color:red;">
	
											<marquee hspace="1"><b> Last 24 hrs statement saving process will be closed after <?php echo $diff; ?> hh:mmss for today.</b></marquee>
									</td>
								</tr>
								<?php } 
									else 
								{ 
								?>
								<tr>
									<td colspan="3" style="font-size:20px;color:red;">
	
											<marquee hspace="1"><b>Last 24 hrs statement saving process closed today.</b></marquee>
									</td>
								</tr>
								<?php } ?>
							
							
								<tr style="height:35px";>
									<td align="center" colspan="3" style="border: 1px solid black; color:black"><b><h2 style="color:black">Last 24hrs Statement</h2></b></td>
								</tr>
								<tr>
									<th align="center" colspan="2" style="border: 1px solid black; height:22px; color:black"><b>Date </b></th>
										<td style="border: 1px solid black"> 
											<input type="date" style="height:31px;width:167px;" id="date" name="date" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['stmt_date'] ; } ?>"/>
											</td>
								
										
									
								</tr>
									
								<tr>
								    <th align="center" colspan="2" width="70%"  style="border: 1px solid black; color:black">Name of Off-Dock</th>
									<td style="border: 1px solid black;"><input type="text" style="height:22px;" id="offDock" name="offDock" value="<?php echo $offDock; ?>" readonly></td>
								</tr>	
								<tr>
								    <th align="center" colspan="2" style="border: 1px solid black; color:black"> Capacity(TEUs)</th>
									<td style="border: 1px solid black;"><input type="text" style="height:22px;" id="capacity " name="capacity" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['capacity'] ; } ?>"></td>
								</tr>
								<tr>
								    <th align="center" colspan="2" style="border: 1px solid black; color:black"> Import Container Lying (TEUs) </th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="impCont" name="impCont"  onkeyup="doSum()" value="<?php if($editFlag==1){ echo $offDockEditList[0]['imp_lying'] ; } else echo 0;?>" onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"></td>
								</tr>
								<tr>
								    <th align="center" colspan="2" style="border: 1px solid black; color:black"> Export Container Lying (TEUs) </th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="expCont" name="expCont" onkeyup="doSum()"  value="<?php if($editFlag==1){echo $offDockEditList[0]['exp_lying'] ; } else echo 0; ?>" onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"></td>
								</tr>
								<tr>
								    <th align="center" colspan="2"  style="border: 1px solid black; color:black"> Empty Container Lying(TEUs)</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="emptyCont" name="emptyCont" onkeyup="doSum()" value="<?php if($editFlag==1){ echo $offDockEditList[0]['mty_lying'] ; } else echo 0; ?>" onfocus="if(this.value=='0') this.value=''" onblur="if(this.value=='') this.value='0'"> </td>
								</tr>
								<tr>
								    <th align="center" colspan="2" style="border: 1px solid black; color:black"> Total(TEUs)</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="total" name="total"  value="<?php if($editFlag==1){ echo $offDockEditList[0]['total_teus'] ; } ?>"> </td>
								</tr>
								<tr>
									<th align="center" colspan="2" style="border: 1px solid black; color:black"> Last 24hrs Export stuffed(TEUs)</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="last24stuff" name="last24stuff" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['last_24hrs'] ; } ?>"> </td>
								</tr>
								<tr>
								    <th align="center" rowspan="2"  style="border: 1px solid black; color:black"> PORT TO DEPORT(TEUS)</th>
									<th align="center" style="border: 1px solid black; color:black"> LADEN</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id=" p2dLaden" name="p2dLaden" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['port_to_depo_laden'] ; } ?>"> </td>
								</tr>
								<tr>
									<th align="center" style="border: 1px solid black; color:black"> EMPTY</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="p2dEmpty" name="p2dEmpty" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['port_to_depo_mty'] ; } ?>"> </td>
								</tr>
								<tr>
									<th align="center" rowspan="2" style="border: 1px solid black; color:black"> DEPORT TO PORT(TEUS)</th>					
									<th align="center" style="border: 1px solid black; color:black"> LADEN</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="d2pLaden" name="d2pLaden" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['depo_to_port_laden'] ; } ?>"> </td>
								</tr>
								<tr>
									<th align="center" style="border: 1px solid black; color:black"> EMPTY</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="d2pEmpty" name="d2pEmpty" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['depo_to_port_mty'] ; } ?>"> </td>
								</tr>
								<tr>
								    <th align="center" colspan="2" style="border: 1px solid black; color:black"> Remarks</th>
									<td style="border: 1px solid black"><input type="text" style="height:22px;" id="remarks" name="remarks" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['remarks'] ; } ?>"> </td>

								</tr>
								<tr><td><input type="hidden" name="akey" id="akey" <?php if($editFlag==1){ ?> value="<?php echo $offDockEditList[0]['akey'] ; } ?>">	</td></tr>
								<!-- Here, $editFlag=0, that's why $offDockEditList[0]['akey'] was generating an error-->
								<!--tr><td><input type="hidden" name="akey" id="akey" value="<?php echo $offDockEditList[0]['akey'];?>">	</td></tr-->
								<tr>
								
									<td colspan="3" align="center">
									<?php
										include("FrontEnd/mydbPConnection.php"); 
										$str = "SELECT TIMEDIFF(CONCAT(DATE(Now()),'$time'),NOW()) AS diff";
										$query=mysqli_query($con_cchaportdb,$str);
										$row=mysqli_fetch_object($query);
										$diff=$row->diff;	
									?>
										<?php if($updateFlag!=1) {?>				
											<input type="submit" value="Save" name="save" class="login_button" <?php if( strtotime($diff) < strtotime(' 00:00:00')){ ?>  class="login_button" style="background-color:red" disabled  <?php } ?>>
										<?php } else {?> 		
											<input type="submit" value="Update" name="update" class="login_button">				
										<?php }?>	
									</td>
									<!--td colspan="3" align="center"><?php $arrt = array('name'=>'submit_login','id'=>'submit','value'=>'Save','class'=>'login_button'); echo form_submit($arrt);?></td-->
								</tr>
								<tr>
									<td colspan="3" align="center"><?php echo $msg;?></td>
								</tr>
							</table>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
</section>
