<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <?php echo $msg;?>
                            </div>
                        </div>
                        <table class="table table-stripe table-bordered" id="datatable-default">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Effective Date</th>
                                    <th>From Currency</th>
                                    <th>To Currency</th>
                                    <th>Rate</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    for($i=0;$i<count($result);$i++){
                                ?>
                                <tr>
                                    <td><?= $i+1;?></td>
                                    <td><?= $result[$i]['effective_date']; ?></td>
                                    <td><?= $result[$i]['from_currency']; ?></td>
                                    <td><?= $result[$i]['to_currency']; ?></td>
                                    <td><?= $result[$i]['rate']; ?></td>
                                    <td><?= $result[$i]['notes']; ?></td>
                                    <td>
                                        <?= form_open();?>
                                            <input type="hidden" name="gkey" value="<?= $result[$i]['gkey']; ?>">
                                            <input type="submit" name="action" value="Edit" class="btn btn-warning btn-sm">
                                        <?= form_close(); ?>
                                    </td>
                                    
                                    <td>
                                        <?php $data = array("onsubmit" => "return confirm('Are you sure?');"); ?>
                                        <?= form_open('Vessel/usdtoBdtExchangeRateList',$data);?>
                                            <input type="hidden" name="gkey" value="<?= $result[$i]['gkey']; ?>">
                                            <input type="submit" name="action" value="Delete" class="btn btn-danger btn-sm">
                                        <?= form_close(); ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>