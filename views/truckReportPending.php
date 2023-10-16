<script type="text/javascript">
	setInterval(displayclock, 500);
	function displayclock(){
		var time = new Date();
		var hrs = time.getHours();
		var min = time.getMinutes();
		var sec = time.getSeconds();
		var meridiem = "AM";
		//var meridiem = "PM";
		if(hrs > 12)
		{
			hrs = hrs-12;
		}
		if(hrs => 12)
		{
			meridiem = "PM";
			//meridiem = "AM";
		}else{
			meridiem = "AM";
		}
		if(hrs == 0)
		{
			hrs = 12;
		}
		if(hrs < 10)
		{
			hrs = '0' + hrs;
		}
		if(min < 10)
		{
			min = '0' + min;
		}
		if(sec < 10)
		{
			sec = '0' + sec;
		}
		document.getElementById("clock").innerHTML = hrs + ':' + min + ':' + sec + ' ' + meridiem;
	}
</script>

<?php if($_SESSION['org_Type_id']==67 || $_SESSION['org_Type_id']==62 || $_SESSION['org_Type_id']==75)
	{ ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title; ?></h2>
	</header>
	<h4 class="mt-none"><b>Date & Time : <?php echo date("d-m-Y"); ?> <span id="clock"> </span></b></h4>
	<div class="row">
    <div class="col-md-6 col-lg-12 col-xl-6">
        <div class="row">
            <div class="col-md-12 col-lg-6 col-xl-6">
                <section class="panel panel-featured-left panel-featured-primary">
                    <div class="panel-body">
                        <div class="widget-summary">
                            <div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-default" style="background-color:white;">
                                    <i class="fa fa-truck" style="color:green;"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Paid Truck</h4>
                                    <div class="info">
                                        <strong class="amount"><a href="<?php echo site_url('Report/truckReport/paid'); ?>"><?php echo $truckPaid;?></a></strong>
                                        <span class="text-primary"></span>
                                    </div>
                                </div>
                                <div class="summary-footer">
                                    <a href="#" class="text-muted text-uppercase"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-6">
                <section class="panel panel-featured-left panel-featured-secondary">
                    <div class="panel-body">
                        <div class="widget-summary">
                            <div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-default" style="background-color:white;">
                                    <i class="fa fa-truck" style="color:red;"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Unpaid Truck</h4>
                                    <div class="info">
                                        <strong class="amount"><a href="<?php echo site_url('Report/truckReport/notpaid'); ?>"><?php echo $truckNotPaid;?></a></strong>
                                        <span class="text-primary"></span>
                                    </div>
                                </div>
                                <div class="summary-footer">
                                    <a class="text-muted text-uppercase"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php } ?>