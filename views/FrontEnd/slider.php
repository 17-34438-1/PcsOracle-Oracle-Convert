<!--  start Slider -->
<section class="slider">
    <div class="fullwidthbanner-container">
        <div class="fullwidthbanner">
            <ul>
                <!-- THE FIRST SLIDE -->
                <!--li data-transition="flyin" data-slotamount="1" data-masterspeed="300"  data-thumb="images/thumbs/thumb1.jpg" data-delay="4000" >
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/slides/slider_1.jpg" alt="">
                </li-->
                <!-- THE SECOND SLIDE -->
                <li data-transition="3dcurtain-horizontal" data-slotamount="1" data-masterspeed="300"  data-thumb="images/thumbs/thumb2.jpg">
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/slides/sl-4.jpg" alt="">
                </li>
                <!-- THE THIRD SLIDE -->
                <li data-transition="3dcurtain-horizontal" data-slotamount="1" data-masterspeed="300"  data-thumb="images/thumbs/thumb2.jpg">
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/slides/slide7.png" alt="">
                </li>
                <!-- THE FOURTH SLIDE -->
                <li data-transition="cube" data-slotamount="1" data-masterspeed="300"  data-thumb="images/thumbs/thumb2.jpg">
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/slides/slide8.png" alt="">
                </li>
                <!-- THE FIFTH SLIDE -->
                <li data-transition="slideleft" data-slotamount="1" data-masterspeed="300"  data-thumb="images/thumbs/thumb2.jpg">
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/slides/slide1.png" alt="">
                </li>

            </ul>

            <div class="tp-bannertimer tp-bottom"></div>
        </div>
    </div>
</section><!--End slider-->

<div class="container">

    <div class="row" id="main-boxes">
    <div class="col-md-4 col-sm-4">
            <div class="box-style-1 red">
            <form method="POST" name="form1" enctype="multipart/form-data" action="<?php echo site_url("ShedBillController/truckPayForUsers") ?>"  onsubmit="return validate();">
                <div class="row">
                    <div class="col-md-12" >
                        <div class="col-md-12 input-group mb-md text-center">
                            <h4>Online Payment for Vehicle Entry</h4>
                        </div>

                        <div class="input-group mb-md" style="margin-bottom:8px;">
                            <span class="input-group-addon span_width">Phone Number : </span>
                            <input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" title="numbers only, 11 digit" class="form-control" placeholder="your phone number" minlength="11" maxlength="11"/>
                        </div>

                        <div class="input-group mb-md" style="margin-bottom:8px;">
                            <span class="input-group-addon span_width">C&F :</span>
                            <?php
                                $cf_ain_query = "SELECT CONCAT(AIN_No_New,' - ',Organization_Name) AS AIN_No_New
                                FROM organization_profiles
                                WHERE Org_Type_id = '2'";
                                $cfAin = $this->bm->dataSelectDB1($cf_ain_query);
                            ?>

                            <input class="form-control" list="cfAinList" name="cnfAin" id="cnfAin" autocomplete="off" >
                            <datalist id="cfAinList" >
                                <?php
                                for($i=0;$i<count($cfAin);$i++)
                                {
                                ?>
                                <option value="<?php echo $cfAin[$i]['AIN_No_New']; ?>">
                                <?php
                                }
                                ?>
                            </datalist>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                <input type="submit" class="btn btn-warning" name="pay" value="Sonali Pay"/>
                            </div>
                            

                            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                <input type="submit" class="btn btn-primary" name="pay" value="EkPay" disabled/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="text-center">
                                <?php if(isset($msg)){ echo $msg; }?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <div class="col-md-4 col-sm-4">
            <div class="box-style-1 red">
            <form method="POST" action="<?php echo site_url("ShedBillController/gatePassForUsers") ?>" target="_blank" onsubmit="return validate();">
                <div class="row">
                    <div class="col-md-12" >
                        <div class="col-md-12 input-group mb-md text-center">
                            <h4>Vehicle Ticket Print</h4>
                        </div>

                        <div class="input-group mb-md" style="margin-bottom:8px;">
                            <span class="input-group-addon span_width">Ticket id : </span>
                            <input type="text" name="visit_id" id="visit_id" class="form-control" placeholder="Type Your Ticket id"/>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 input-group mb-md text-center">
                                <input type="submit" class="btn btn-success btn-md" name="pass" value="Ticket Print"/>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            </div>
        </div>

        <div class="col-md-4 col-sm-4">
            <div class="box-style-2 red">
                <!--a href="<?php echo site_url('FrontEndController/ContactUs');?>" title="Plan a visit"-->
                <a href="" title="Plan a visit">
                    <img src="<?php echo ASSETS_WEB_PATH?>fimg/icon-home-visit.png" alt="">
                    <h3>About PCS</h3>
                    <p><br/><br/><br/><br/></p>
                </a>
            </div>
        </div>
    </div>
</div> <!-- end container-->
