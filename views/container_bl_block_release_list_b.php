<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/Container_BL_BlockReleaseList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_im_cont_no" id="ddl_im_cont_no" class="form-control" placeholder="Container No">
								</div>
								<div class="input-group mb-md">
									<span class="">OR </span>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="ddl_im_bl_no" id="ddl_im_bl_no" class="form-control" placeholder="BL No">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
								<?php echo @$msg; ?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
			
			<?php
				if($flag == 1){
			?>

			<section class="panel">
                <div class="panel-body">
				<table class="table table-bordered table-hover">
				<h3  align="center"><?php echo $title; ?></h3>
				<h3 align="center"><span ><?php if($containerNo!="") echo "Container No: ".$containerNo; else echo "BL No: ".$blNo ?></span> </h3>
                    <table class="table table-bordered table-hover">
					
					
					<thead>
                        <tr>
                            <th>SL </th>
                            <th>Conainer No </th>
                            <th>Rotation No</th>
                            <th>BL Ref</th>
							<th>Position</th>
							<!--th>Block St</th-->
                            <th>Flag</th>
                            <th>Block Time</th>
                            <th>Release Time</th>
						</tr>
					</thead>
					
		
			<?php
			
			//include("mydbPConnection.php");
			include("dbConection.php");
			
			
			// echo $rtnBlLis->last_pos_name."<hr>";
		

		?>
		<?php
		for($i=0; $i< count($rtnBlList) ; $i++) 
		{
			$val=$rtnBlList[$i]['cont_no'];

			$sqlContainerPosition="SELECT inv_unit_fcy_visit.gkey,inv_unit_fcy_visit.last_pos_name,
			(SELECT ctmsmis.cont_yard(last_pos_name)) AS Yard_No,
			(SELECT ctmsmis.cont_block(last_pos_name,Yard_No)) AS Block_No
			FROM sparcsn4.inv_unit   
			INNER JOIN sparcsn4.inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			WHERE sparcsn4.inv_unit.id='$val' ORDER BY inv_unit_fcy_visit.gkey DESC LIMIT 1";
			// $rtnBlList = $this->bm->dataSelect($sqlContainerQuery);
			$sqlConPosition=mysqli_query($con_sparcsn4,$sqlContainerPosition);
			$rtnConList=mysqli_fetch_array($sqlConPosition);
			$yard = "";
			// var_dump($rtnBlLis);
			// return;

			// var_dump($rtnBlLis['last_pos_name']);
			// return;

			for($j=0; $j<count($rtnConList) ; $j++) 
			{
				$yard=$rtnConList['last_pos_name'];

			}
			$st=$rtnBlList[$i]['release_flag'];
			$time=$rtnBlList[$i]['time_stamp'];

		?>  
			<tr <?php if($st=="DO_NOT_RELEASE") { ?> style="background-color:#ef8068" <?php }  else { ?> style="background-color:#83d67f" <?php } ?> >
				<td><?php echo $i+1; ?></td>
				<td><?php echo $rtnBlList[$i]['cont_no']; ?></td>
				<td><?php echo $rtnBlList[$i]['rotation_no']; ?></td>
				<td><?php echo $rtnBlList[$i]['bl_ref']; ?></td>
				
				<td><?php echo $yard; ?></td>
				<!-- <td><?php echo $rtnBlNo; ?></td-->
				<td><?php echo $rtnBlList[$i]['release_flag']; ?></td> 
				<td><?php if($st=="DO_NOT_RELEASE") { echo $time; } ?></td> 
				<td><?php if($st!="DO_NOT_RELEASE") { echo $time; } ?></td> 

				 <?php /* 
				 if($rtnBlNo =="DO_NOT_RELEASE"){ ?>
					<td><?php echo $rtnBlNo; ?></td>
					<?php } 
				else { ?>
					<td><?php echo ""; ?></td>
				<?php } ?>

				<?php 
				 if($rtnBlNo =="DO_NOT_RELEASE"){ ?>
					<td><?php echo ""; ?></td>
					<?php } 
				else{ ?>
					<td><?php echo $rtnBlNo; ?></td>
				<?php }  */?>

				<!--td><?php echo $rtnBlList[$i]['time_stamp']; ?></td-->
			</tr>
		<?php
		}
		?>

		<?php 
		mysqli_close($con_sparcsn4);
		//mysqli_close($con_cchaportdb);
		?>
                    </table>
	</table>
                </div>
            </section>
			<?php
				}
			?>

		</div>
	</div>


</section>
