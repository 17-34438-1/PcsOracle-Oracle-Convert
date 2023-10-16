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
                                for($i=0;$i<count($appraise);$i++)
                                {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i+1; ?></td>
                                    <td class="text-center"><?php echo $appraise[$i]['rotation']; ?></td>
                                    <td class="text-center"><?php echo $appraise[$i]['BL_NO']; ?></td>
                                    <td class="text-center"><?php echo $appraise[$i]['cnf_name']; ?></td>
                                    <td class="text-center"><?php echo $appraise[$i]['appraise_date']; ?></td>
                                    <td class="text-center"><?php echo $appraise[$i]['user_id']; ?></td>
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