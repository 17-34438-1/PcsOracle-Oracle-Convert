<!doctype html>
<script>
    function changeterminal(terminal)
    {
       // alert(terminal);
        var yard=document.getElementById("search_yard");
        if(terminal=="CCT" || terminal=="NCT")
        {
            yard.disabled=true;
            //assigntype.disabled=false;
            //getAssignment();
        }
        else if(terminal=="GCB")
        {
            yard.disabled=false;

            getBlock(terminal);
        }
    }
    function getBlock(yard)
    {
        //alert(yard);
        if (window.XMLHttpRequest)
        {

            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=stateChangeYardInfo;
        xmlhttp.open("GET","<?php echo site_url('AjaxController/getBlockCpa')?>?yard="+yard,false);

        xmlhttp.send();
    }
    function stateChangeYardInfo()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {

            var val = xmlhttp.responseText;



            var selectList=document.getElementById("search_yard");

          //  removeOptions(selectList);

            var val = xmlhttp.responseText;
            var jsonData = JSON.parse(val);
            for (var i = 0; i < jsonData.length; i++)
            {
                var option = document.createElement('option');
                option.value = jsonData[i].block;  //value of option in backend
                option.text = jsonData[i].block;	  //text of option in frontend
                selectList.appendChild(option);
            }
        }
    }
    function validate()
    {
        //alert("OK");
        if( document.rcv_and_dlv.search_dt.value == "" )
        {
            alert( "Please provide Date!" );
          //  document.rcv_and_dlv.search_dt.focus() ;
            return false;
        }
        else{
            return( true );
        }
    }
</script>
<style>
    label
    {
        color: black;
    }
</style>
<html class="fixed">
<head>

    <?php include("cssAssetsList.php"); ?>
</head>

<body>
<section class="body">

    <section role="main" class="content-body">
        <header class="page-header">
            <h2><?php echo $title; ?></h2>
        </header>
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"></h2>
                </header>
                <div class="panel-body">
                    <form name="rcv_and_dlv" id="rcv_and_dlv"  class="form-horizontal form-bordered"
                          action="<?php echo site_url("report/yard_wise_delivery_and_receive_action");?>" method="post" onsubmit="return validate()" target="_blank">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputSuccess">Search Type :</label>
                            <div class="col-md-3">
                                <select class="form-control input-sm mb-md" name="search_type" id="search_type">
                                    <option value="">--SELECT--</option>
                                    <option  value="delivery">Delivery</option>
                                    <option  value="receive">Receive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Date : </label>
                            <div class="col-md-6">
                                <div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</span>
                                    <input type="date" id="search_dt" name="search_dt" value="" class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputSuccess">Search Terminal :<em>&nbsp;</em></label>
                            <div class="col-md-3">
                                <select class="form-control input-sm mb-md" name="terminal" id="terminal" onchange="changeterminal(this.value);">
                                    <option value="">--Select--</option>
                                    <option value="CCT">CCT</option>
                                    <option value="NCT">NCT</option>
                                    <option value="GCB">GCB</option>
                                    <option value="SCY">SCY</option>
                                    <option value="OFY2">OFY2</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputSuccess">Search Yard :<em>&nbsp;</em></label>
                            <div class="col-md-3">
                                <select class="form-control input-sm mb-md" name="search_yard" id="search_yard" disabled>
                                    <option  value="all">--SELECT--</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="xl" checked>
                                    <label for="radioExample3">Excel</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="html" >
                                    <label for="radioExample3">HTML</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12" align="center">
                                <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary"
                                        name="btn_search" id="btn_search">
                                    Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </section>
    <?php
    include("jsAssetsList.php");
    ?>

</section>
</body>
</html>
