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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('report/smsBalanceEntryFormPerform'); ?>" >
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">TOTAL BUY QTY <span class="required">*</span></span>
									<input type="number" style="width:180px;"  id="buy_sms" name="buy_sms" class="form-control login_input_text">
									
									<button type="submit" name="save" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
								</div>
								<div class="input-group mb-md">
									<?php echo $msg; ?> 
								</div>								
							</div>																			
						</div>
					</form>
					<br>
					<!--table cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" width="600px"-->
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<tr class="gradeX" align="center">
							<font size="15">
								<td><b>SL</td>
								<td><b><nobr>Previous Buying Qty <nobr><b></td>
								<td><b><nobr>Send Qty <nobr><b></td>
								<td><b><nobr>Balance Qty <nobr><b></td>
								<td><b><nobr>Previous Buy Date<nobr><b></td>        
							</font>
						</tr>
							
						<?php
						for($i=0;$i<count($result);$i++) 
						{ 				
						?>
						<tr class="gradeX" align="center">
							<td>
								<?php echo $i+1;?>
							</td>						 
							<td>
								<?php echo $result[$i]['buy_sms']; ?>
							</td> 
							<td>
								<?php echo $result[$i]['send_sms']; ?>
							</td> 
							<td>
								<?php echo $result[$i]['buy_sms'] - $result[$i]['send_sms']; ?>
							</td>   
							<td>
								<?php echo $result[$i]['date_sms']; ?>
							</td>      
						</tr>
						<?php 
						}
						?>	
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
