<script>
//  function loadBLData(e,val){
//    if (e.keyCode == 13) 
// 		{
//             //alert(val); 
// 			if (window.XMLHttpRequest) 
// 			{
// 				// code for IE7+, Firefox, Chrome, Opera, Safari
// 				xmlhttp=new XMLHttpRequest();
// 			} 
// 			else 
// 			{  
// 				// code for IE6, IE5
// 				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
// 			}
// 			//xmlhttp.onreadystatechange=stateChangeValue;
//             xmlhttp.onreadystatechange = function() {
//            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
//                 //alert(xmlhttp.responseText); 
//             }
//            }
// 			xmlhttp.open("GET","<?php echo site_url('breakbulk/BBAjaxController/searchBL')?>?bl_no="+val,false);
// 			xmlhttp.send();
// 		}

// function stateChangeValue()
// 	{
// 		//alert("ddfd");
// 		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
// 		{				  
// 			var val = xmlhttp.responseText;
//            // alert(val);
// 			var jsonData = JSON.parse(val);
			
// 			var cnfCodeTxt=document.getElementById("blLocation");
		
// 			for (var i = 0; i < jsonData.length; i++) 
// 			{				
// 				cnfCodeTxt.value=jsonData[i].NAME;				
// 			}
// 		}
// 	}

//  }
</script>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
        <div class="right-wrapper pull-right">
        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal form-bordered" method="POST"
                        action="<?php echo site_url('breakbulk/BBDashBoardController/search_keyword') ?>">
                        <input type="hidden" name="" value="">
                        <input type="hidden" name="" value="">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">BL No <span
                                            class="required">*</span></span>
                                    <input type="text" name="blLocation" id="blLocation" class="form-control"
                                        placeholder="BL No" onkeypress="return loadBLData(event,this.value)">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" name="submit"
                                        class="mb-xs mt-xs mr-xs btn btn-success" >Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <?php 
            


            if($check == -1) { ?>
            <section class="panel">
                <header class="panel-heading">
                    <div class="row">
                        <div class="col-md-3">
                            <span><b>BL NO :</b></span><span> <b><?php echo $blData[0]['BL'];?></b></span>
                        </div>
                        <div class="col-md-2">
                            <span><b>QUANTITY :</b></span><span> <b><?php echo $blData[0]['TOTAL_QTY'];?></b></span>
                        </div>
                        <div class="col-md-2">
                            <span><b>WEIGHT :</b></span><span> <b><?php echo $blData[0]['TOTAL_WAIGHT'];?></b></span>
                        </div>
                        <div class="col-md-3">
                            <span><b>I/B :</b></span><span> <b><?php echo $blData[0]['IBACTUALVISIT'];?></b></span>
                        </div>
                        <div class="col-md-2">
                            <span><b>Agent Code :</b></span><span> <b><?php echo $blData[0]['AGENT_CODE'];?></b></span>
                        </div>
                    </div>
                   
                </header>
            </section>
            <section class="panel">
                <div class="panel-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width:100px">CATEGORY</th>
                                <th></th>
                                <th class="text-center">QUANTITY</th>
                                <th></th>
                                <th class="text-center">WEIGHT</th>
                                <th></th>
                                <th class="text-center">POSITION</th>
                            </tr>
                        </thead>
                       <?php 
                       $qty=0;
                       $qty1=0;
                       $wt1=0;
                       $wt=0;
                       $i=0;
                       $state="";
                       $state1="";
                       $position="";
                       $countDt=0;
                       foreach($blDataByState as $data){ 
                        
                              if($data['TSTATE'] != 'YARD'){
                                if($data['TSTATE'] == 'INBOUND'){
                                    $qty=$data['LOT_QTY'];
                                    $wt=$data['LOT_WEIGHT'];
                                    $state=$data['TSTATE'];
                                    $position="ON VESSEL";
                                    $arrayData[$i]['qty']=$qty;
                                    $arrayData[$i]['wt']=$wt;
                                    $arrayData[$i]['state']=$state;
                                    $arrayData[$i]['position']=$position;
                                }else{
                                    
                                    $qty1=$qty1+$data['LOT_QTY'];
                                    $wt1=$wt1+$data['LOT_WEIGHT'];
                                    $state1=$data['TSTATE'];
                                }
                             }else{
                                $position=$blDataByYard[0]['YARD'];
                                $qty=$data['LOT_QTY'];
                                $wt=$data['LOT_WEIGHT'];
                                $state=$data['TSTATE'];
                                $arrayData[$i]['qty']=$qty;
                                $arrayData[$i]['wt']=$wt;
                                $arrayData[$i]['state']=$state;
                                $arrayData[$i]['position']=$position;
                             }
                             $i++;

                         } 
                         
                         $countDt=count($arrayData);
                         if($qty1!=0){
                            $arrayData[$countDt+1]['qty']=$qty1;
                            $arrayData[$countDt+1]['wt']=$wt1; 
                            $arrayData[$countDt+1]['state']="DEPARTED";
                            $arrayData[$countDt+1]['position']="GATE OUT";
                         }

                     ?>    
                        <?php foreach($arrayData as $data){ ?> 
                        <tbody>
                            <tr>
                                <th style="width:100px"><?php  echo $data['state']?></th>
                                <td><?php echo $blData[0]['CATEGORY'];?></td>
                                <td></td>
                                <td class="text-center"><?php  echo $data['qty']?></td>
                                <td></td>
                                <td class="text-center"><?php  echo $data['wt']?></td>
                                <td></td>
                                <td class="text-center"><?php  echo $data['position']?></td>
                            </tr>
                            
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
            </section>
        <?php }  ?>
        </div>
    </div>
    <!-- end: page -->
</section>
</div>