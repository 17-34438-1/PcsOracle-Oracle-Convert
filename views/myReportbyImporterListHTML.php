<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title ;?></h2>
        <div class="right-wrapper pull-right">
        </div>
    </header>

    <section class="panel">
         <div class="panel-body">
            <form class="form-horizontal form-bordered" method="POST" 
                action="<?php echo site_url('Report/IgmReportImporterListSearch') ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php  ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width"><font color='red'><b>*</b></font> NOTIFY NAME :</span>
                            <input type="text" id="Notify_name" name="Notify_name" tabindex="1" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button type="submit" name="btnSave" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
                        </div>													
                    </div>
                </div>	
            </form>
        </div>
    </section>

    <section class="panel">
     
        <div class="panel-body">
       
            <table class="table table-bordered table-responsive table-hover table-striped mb-none"
                id="datatable-default">
                <thead>
                    <tr>
                        <th class="text-center">Sl</th>
                    
                        <th class="text-center">NOTIFY CODE</th>
                        <th class="text-center">NOTIFY NAME</th>
                        <th class="text-center">NOTIFY DESC</th>
                        <th class="text-center">NOTIFY ADDRESS</th>
                        
                    </tr>
                </thead>

              
                <tbody>
                    <?php 
					for($i=0;$i<count($rtnVesselList);$i++) {
										?>
                    <tr class="gradeX">
                        <td align="center"><?php echo $i+1;?></td>
                       
                        <td align="center"><?php echo $rtnVesselList[$i]['Notify_code'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['Notify_name'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['NotifyDesc'];?></td>
                        <td align="center"><?php echo $rtnVesselList[$i]['Notify_address'];?></td>
                     
                    </tr>
                    <?php } ?>
                </tbody>
                <TR><TD><p><?php echo @$links; ?></p></TD></TR>

            </table>
        </div>
    </section>
  
</section>
</div>



