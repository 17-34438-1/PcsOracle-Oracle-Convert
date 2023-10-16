<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
				<div align="center"><span><?php echo @$msg; ?></span></div>
					<table  class="table table-bordered table-responsive table-hover table-striped mb-none">
						<tr><td>	 
							<table  class="table table-responsive table-hover table-striped mb-none">	
								<thead>
								<tr><td align="center"><b><font size="4"><nobr>Slave process of DB-21</nobr></font></b></td></tr>
									<tr bgcolor="#85DDEA">
										<th>Slave IO Running</th>
										<th>Slave SQL Running</th>						
										<th>Seconds Behind Master</th>
										<th>Slave IO State</th>
										<th>Master Host</th>
										<th>Relay Log Pos</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								for($i=0;$i<count($processList);$i++) { ?>
								
								<tr <?php if($processList[$i]['Slave_IO_Running']=="No" || $processList[$i]['Slave_SQL_Running']=="No") { echo "style='background-color:red;color:white;'"; }  else { echo "style='background-color:green;color:white;'"; }?> >				
									<td align="center"><?php echo $processList[$i]['Slave_IO_Running']; ?></td>					
									<td align="center"><?php echo $processList[$i]['Slave_SQL_Running'];  ?></td>		
									<td align="center"><?php echo $processList[$i]['Seconds_Behind_Master']; ?></td>
									<td align="center"><?php echo $processList[$i]['Slave_IO_State'];  ?></td>
									<td align="center"><?php echo $processList[$i]['Master_Host']; ?></td>
									<td align="center"><?php echo $processList[$i]['Relay_Log_Pos']; ?></td>
								</tr>
								<?php } ?>
								</tbody>
								<tr><td>&nbsp;&nbsp;</td></tr>
								<tr><td align="center"><b><font size="4"><nobr>Slave process of DB-22</nobr></font></b></td></tr>
								
								<thead>
									<tr bgcolor="#85DDEA">
										<th>Slave IO Running</th>
										<th>Slave SQL Running</th>						
										<th>Seconds Behind Master</th>
										<th>Slave IO State</th>
										<th>Master Host</th>
										<th>Relay Log Pos</th>
									</tr>
								</thead>
								<tbody>
								<?php 
								for($i=0;$i<count($processList22);$i++) { ?>
								
								<tr <?php if($processList22[$i]['Slave_IO_Running']=="No" or $processList22[$i]['Slave_SQL_Running']=="No") { echo "style='background-color:red;color:white;'"; }  else { echo "style='background-color:green;color:white;'"; }?>>				
									<td align="center"><?php echo $processList22[$i]['Slave_IO_Running']; ?></td>					
									<td align="center"><?php echo $processList22[$i]['Slave_SQL_Running'];  ?></td>		
									<td align="center"><?php echo $processList22[$i]['Seconds_Behind_Master']; ?></td>
									<td align="center"><?php echo $processList22[$i]['Slave_IO_State'];  ?></td>
									<td align="center"><?php echo $processList22[$i]['Master_Host']; ?></td>
									<td align="center"><?php echo $processList22[$i]['Relay_Log_Pos']; ?></td>
								</tr>
						<?php } ?>
								</tbody>
								
						</table>
					</td>
				</tr>
			</table>
			<div>
				<?php
					if(@$mystatus==2)
					{
						echo $body;
					}
				?>
			</div>
				</div>
			</section>
		</div>
	</div>

</section>