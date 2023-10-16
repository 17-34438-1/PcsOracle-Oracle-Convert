<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		</header>
				<div class="panel-body">
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">Sl</th>
								<th class="text-center">Rotation</th>
								<th class="text-center">BL No</th>
								<th class="text-center">C&F Name</th>
								<th class="text-center">Appraise Date</th>
								<th class="text-center">Appraise By</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                for($i=0;$i<count($verify);$i++)
                                {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i+1; ?></td>
                                    <td class="text-center"><?php echo $verify[$i]['rotation']; ?></td>
                                    <td class="text-center"><?php echo $verify[$i]['bl_no']; ?></td>
                                    <td class="text-center"><?php echo $verify[$i]['Organization_Name']; ?></td>
                                    <td class="text-center"><?php echo $verify[$i]['verify_time']; ?></td>
                                    <td class="text-center"><?php echo $verify[$i]['verify_by']; ?></td>
                                </tr>
                            <?php
                                }
                            ?>
						</tbody>
					</table>
				</div>
			</section>
	</section>
</div>