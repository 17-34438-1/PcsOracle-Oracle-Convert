<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
 <div class="content">
    <div class="content_resize panel-body">		
        <table  class="table table-bordered table-responsive table-hover table-striped mb-none">
            <tr>
                <th align="center">SL</th>
                <th align="center">Import Rotation</th>
                <th align="center">BL No</th>
                <th align="center">BE No</th>
                <th align="center">DO Image</th>
                <th align="center">Action</th>
                <th align="center">Action</th>
            </tr>

            <?php for($i=0;$i<count($ShedDOList);$i++) { ?>
                <tr>
                    <td>
                        <?php echo $i+1; ?>
                    </td>
    
                    <td align="center">
                        <?php echo $ShedDOList[$i]['imp_rot'];?>
                    </td>

                    <td align="center">
                        <?php echo $ShedDOList[$i]['bl_no'];?>
                    </td>
                    
                    <td align="center">
                        <?php echo $ShedDOList[$i]['be_no'];?>
                    </td>
                    
                    <td align="center">
                        <?php if($ShedDOList[$i]['do_image_loc']){ ?>
                            <a href="../../assets/do_image/<?php echo $ShedDOList[$i]['do_image_loc']; ?>" target="_BLANK">View DO</a>
                        <?php
                            }else{
                                echo "NOT FOUND";
                            }
                        ?>
                    </td> 

                    <td align="center">
                        <form style="display:inline" action="<?php echo site_url('ShedBillController/shedDeliveryOrderInfoData')?>" method="POST">
                            <input type="hidden" name="editFlag" value="editFlag"/>
                            <input type="hidden" name="id" value="<?php echo $ShedDOList[$i]['id']; ?>"/>
                            <input type="hidden" name="rotNo" value="<?php echo $ShedDOList[$i]['imp_rot']; ?>"/>
                            <input type="hidden" name="blno" value="<?php echo $ShedDOList[$i]['bl_no']; ?>"/>
                            <input class="btn btn-xs btn-primary" type="submit" value="Edit"/>
                        </form>
                    </td>   		  

                    <td align="center">
                        <form style="display:inline" action="<?php echo site_url('ShedBillController/shedDeliveryOrderInfoPDF')?>" target="_blank" method="POST">
                            <input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $ShedDOList[$i]['id']; ?>"/>
                            <input type="hidden" name="rotNo" id="rotNo" value="<?php echo $ShedDOList[$i]['imp_rot']; ?>"/>
                            <input type="hidden" name="blno" id="blno" value="<?php echo $ShedDOList[$i]['bl_no']; ?>"/>
                            <input class="btn btn-xs btn-primary" type="submit" value="PDF"/>
                        </form>
                    </td> 
                </tr>
            <?php } ?>
        </table>
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <div class="clr"></div>
	</div>
</section>
</div>