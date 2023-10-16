<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<html lang="en">
	 <?php
		//echo $_SERVER['SERVER_NAME'];
		$path= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/CopinoSample/';
		//echo $path.'C&F_Agent_USER_ID_Enlistment_Form.pdf';
		$path2= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/CopinoManual/';
		$path3= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/C&FManual/';
		$path4= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/PilotAppMenual/';
		$path5= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/TosGateControll/';
	?>
<!--<![endif]--><head>

    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <title>Chittagong Port Authority</title>
    <meta name="description" content="EDU - Educational, College and Courses Boostrap site template with Responsive Megamenu 14$">
    <meta name="author" content="Ansonika">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">

    <!-- Favicons-->
    <link rel="shortcut icon" href="<?php echo ASSETS_WEB_PATH?>fimg/cpaLogo.png" type="image/x-icon"/>
    <link rel="apple-touch-icon" type="image/x-icon" href="<?php echo ASSETS_WEB_PATH?>fimg/cpa_logo.jpg">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="<?php echo ASSETS_WEB_PATH?>fimg/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="<?php echo ASSETS_WEB_PATH?>fimg/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="<?php echo ASSETS_WEB_PATH?>fimg/apple-touch-icon-144x144-precomposed.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="<?php echo ASSETS_WEB_PATH?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ASSETS_WEB_PATH?>css/megamenu.css" rel="stylesheet">
    <link href="<?php echo ASSETS_WEB_PATH?>css/style.css" rel="stylesheet">
    <link href="<?php echo ASSETS_WEB_PATH?>css/font-awesome.css" rel="stylesheet" >
    <link rel="stylesheet" href="<?php echo ASSETS_WEB_PATH?>js/fancybox/source/jquery.fancybox.css?v=2.1.4">

    <!-- REVOLUTION BANNER CSS SETTINGS -->
    <link rel="stylesheet" href="<?php echo ASSETS_WEB_PATH?>css/fullwidth.css" media="screen" >
    <link rel="stylesheet" href="<?php echo ASSETS_WEB_PATH?>css/settings.css" media="screen" >

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Jquery -->
    <script src="<?php echo ASSETS_WEB_PATH?>js/jquery.js"></script>
    <!-- Support media queries for IE8 -->
    <script src="<?php echo ASSETS_WEB_PATH?>js/respond.min.js"></script>

    <!-- HTML5 and CSS3-in older browsers-->
    <script src="<?php echo ASSETS_WEB_PATH?>js/modernizr.custom.17475.js"></script>
    <script type="text/javascript" src="<?php echo ASSETS_JS_PATH; ?>welcome_ccha.js"></script>

    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo ASSETS_WEB_PATH?>font-awesome/css/font-awesome.css">
    <![endif]-->

    <!-- Style switcher-->
    <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo ASSETS_WEB_PATH?>css/jquery-sticklr-1.4-light-color.css" >
    <!-- Fonts-->
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/helvetica.css" title="helvetica" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/cabin.css" title="cabin" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/droid.css" title="droid" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/lato.css" title="lato" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/montserrat.css" title="montserrat" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/opensans.css" title="opensans" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/quattrocento.css" title="quattrocento" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/roboto.css" title="roboto" media="all">
    <link rel="alternate stylesheet" type="text/css" href="<?php echo ASSETS_WEB_PATH?>css/robotoslab.css" title="robotoslab" media="all">
	<style>.mapouter{position:relative;text-align:right;width:100%;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;}</style>
    
    <!-- <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-11097556-8']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    });
    </script> -->
    <script>
        $(document).ready(function(){
            $("#login").on('shown.bs.modal', function(){
                $(this).find('#username').focus();
            });
        });
    </script>
</head>

<body>
<!--[if !IE]><!--><script>if(/*@cc_on!@*/false){document.documentElement.className+=' ie10';}</script><!--<![endif]--> <!-- Border radius fixed IE10-->

<header style="background-color:white;">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-6 text-center company_logo" id="border-footer">
                <div class="row">
                    <div class="col-md-1">
                        <img style="margin-top: 10px" src="<?php echo  ASSETS_WEB_PATH?>fimg/cpaLogo.png" alt="Logo">
                    </div>
                    <div class="col-md-6" style="margin-top: 20px; padding: 0">
                        <a href="<?php echo site_url('Welcome/')?>">

                            <p style="font-size: 24px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
								<b>চট্টগ্রাম   বন্দর  কর্তৃপক্ষ</b>
							</p>
                            <p style="font-size: 20px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
								Chittagong Port Authority
							</p>
                            <!--p style="font-size: 16px; color: black"> চট্টগ্রাম  বন্দর কর্তৃপক্ষ......</p-->
                        </a>
                    </div>
                </div>

            </div>
            <div class="col-md-5 col-sm-6">
				<div class="col-md-12 col-sm-12">
					<h3 align="center">Port Community System<h3>
				</div>

                <div id="phone" style="color: black;" class="hidden-xs"><strong>+880-1747 81 00 37 </strong>Network department</div>
                <div id="phone" style="color: black;" class="hidden-xs"><strong>+880-1749 92 33 27 </strong>Operation department</div>
                

                <!--div id="menu-top">
                    <ul>
                        <li ><a style="color: black" href="<?php echo site_url('Welcome/')?>" title="Home">Home</a> | </li>
                        <li><a style="color: black" href="<?php echo site_url('FrontEndController/NewsEvents')?>" title="News and Events">News &amp; Events</a> | </li>
                        <li><a  style="color: black" href="<?php echo site_url('FrontEndController/ContactUs')?>" title="Contact">Contact</a></li>
                    </ul>
                </div-->
            </div><!-- End col-md-8-->
        </div><!-- End row-->
		
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<marquee>
					<!-- <font color='blue' size='5'>Payment of vehicle entry fee can be paid online.</font> -->
                    <!--font color='blue' size='4'>বিকাশ, রকেট সহ যে কোনো ডেবিট/ক্রেডিট কার্ড দিয়ে বন্দরে গাড়ি প্রবেশের ফি জমা দিয়ে ঘরে বসেই গেট পাস সংগ্রহ করুন। স্ব-শরীরে উপস্থিত হয়ে ফি প্রদান ও গেট পাস সংগ্রহ করা পরিহার করুন। করোনা থেকে বাঁচুন।</font-->
					<font color='blue' size='4'>সম্মানীত ব্যবহারকারীগণ, ডিসেম্বর ১, ২০২১ থেকে ইলেক্ট্রনিক ডেলিভারি অর্ডার (EDO) চালু হয়েছে। চালুকৃত নতুন এই মডিউলটির ব্যবহারবিধি ডাউনলোড করতে <a href="<?php echo $path3. 'User Manual of Electronic Delivery Order in TOS System-version_2.pdf'; ?>" target="_BLANK"></font><font color="red" size='5'>এখানে ক্লিক করুন</a>।</font>
				</marquee>
			</div>
		</div>
    </div><!-- End container-->
</header><!-- End Header-->

<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form name="frmmodal" action="<?php echo site_url('login/')?>" method="post" onsubmit="isvalidLoginModal()">
            <div class="modal-header">
				<div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
					<img style="margin-top: 10px;margin-left:42%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
					<span style="margin-left:30%;">Port Community System</span>
					<button style="padding-top: 5px;" type="button" class="close" data-dismiss="modal"> X </button>
				</div>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">User Id : </span>
							<input type="text" name="username" id="username" class="form-control" placeholder="Type Your User ID" autofocus>
						</div>
						<br>
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Password : </span>
							<input type="password" name="password" id="password" class="form-control" placeholder="Type Your Password">
						</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
				<div class="row">
					<div class="col-sm-6 text-right">
						<input type="submit" name="submit_login" class="btn btn-success" value="Login" >
					</div>
					<div class="col-sm-6">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
            </div>
            </form>
        </div>
    </div>
</div>

<nav>
    <div class="megamenu_container">
        <a id="megamenu-button-mobile" href="#">Menu</a><!-- Menu button responsive-->

        <!-- Begin Mega Menu Container -->
        <ul class="megamenu">
            <!-- Begin Mega Menu -->
            <li><a href="<?php echo site_url('Welcome/')?>" class="nodrop-down"> <i class="icon-home"> </i> Home</a></li>
            <li><a href="http://cpatos.gov.bd/" class="nodrop-down"> <i class="icon-home"> </i>TOS Home</a></li>
            <!-- <li><a href="javascript:void(0)" class="drop-down">CTMS <i class="icon-angle-down" style="margin-left: 1%"> </i></a> -->
                <!-- Begin Item -->
                <!-- <div class="drop-down-container">
                    <div class="row">
                        <div class="col-md-7">
                            <iframe height="300" src="http://www.youtube.com/embed/pgk-719mTxM?wmode=transparent"></iframe>
                        </div>
                        <div class="col-md-5">
							<ul class="list-menu">
                                <li><a href="#" title="All courses"><h4>About</h4></a></li>
                                <li><a href="#" title="All courses"><h4>Objective</h4></a></li>
                                <li><a href="#" title="All courses"><h4>Contact Info</h4></a></li>
                                <li><a href="#" title="All courses"><h4>Management</h4></a></li>
                                <li><a href="#" title="All courses"><h4>Office Staff</h4></a></li>
							</ul>
                        </div>
                    </div>
				</div> -->
            </li><!-- End Item -->

			<li class="drop-normal"><a href="javascript:void(0)" class="drop-down">Administration  <i class="icon-angle-down" style="margin-left: 1%"> </i></a>
				<!-- <div class="drop-down-container normal">
					<ul>
						<li><a href="about-us.html" title="About">Administration 1 </a></li>
						<li><a href="about-us.html" title="About">Administration 2</a></li>
						<li><a href="about-us.html" title="About">Administration 3 </a></li>
						<li><a href="about-us.html" title="About">Administration 4</a></li>
					</ul>
				</div> -->
			</li>

			<li><a href="javascript:void(0)" class="drop-down">About <i class="icon-angle-down" style="margin-left: 1%"></i></a>
				<!-- Begin Item -->
				<div class="drop-down-container">

					<div class="row">

						<div class="col-md-3">
							<h4> </h4>
							<ul class="list-menu">
								<li><a href="<?php echo site_url('FrontEndController')?>" title="All courses"><h3>About CPA</h3></a></li><hr>
								<li><a href="#" title="Course detail">Message from Chairman</a></li>
								<li><a href="#" title="Course detail">Message from Vice Chairman</a></li>
								<li><a href="#" title="Course detail">Message from Director </a></li>
								<li><a href="#" title="Course detail">Message from Assistant Director </a></li>
							</ul>
						</div>

						<div class="col-md-9">
							<ul class="tabs">
								<li><a class="active" href="#section-1">Management</a></li>
								<li><a href="#section-2">History</a></li>
							</ul>

							<ul class="tabs-content">

								<li class="active" id="section-1">
									<div class="row">

										<div class="col-md-4">
											<p><img src="<?php echo ASSETS_WEB_PATH ?>fimg/chairman.jpg" class="img-rounded shadow img-responsive" alt=""></p>
											<h5>Rear Admiral M. Shahjahan, NPP, NDC, PSC, BN <em>Chairman</em></h5>
											<p><br/><br/><br/><br/></p>
											<p><a href="#" class="button_red_small" title="staff">Read more</a></p>
										</div>

										<!--div class="col-md-4">
											<p><img src="<?php echo ASSETS_WEB_PATH ?>fimg/teacher-small-2.jpg" class="img-rounded shadow img-responsive" alt=""></p>
											<h5>Mr.John Doe <em>Vice Chairman</em></h5>
											<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
											<p><a href="staff.html" class="button_red_small" title="staff">Read more</a></p>
										</div>

										<div class="col-md-4">
											<p><img src="<?php echo ASSETS_WEB_PATH ?>fimg/teacher-small-3.jpg" class="img-rounded shadow img-responsive" alt=""></p>
											<h5>Mr.John Doe <em>Director </em></h5>
											<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
											<p><a href="staff.html" class="button_red_small" title="staff">Read more</a></p>
										</div-->

									</div><!-- End row -->
								</li>

								<li id="section-2">
									<!--p class="lead ">An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei. An utinam reprimique duo, putant mandamus cu qui.</p-->
									<hr>

									<!--div class="row">

										<div class="col-md-6">
											<h5>History</h5>
											<p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei. An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
										</div>

										<div class="col-md-6">
											<h5>Mission</h5>
											<p>An utinam reprimique duo, putant mandamus cu qui. <strong>Autem possim his cu</strong>, quodsi nominavi fabellas ut sit, mea ea ullum epicurei. An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
										</div>

									</div--><!-- End row -->
								</li>

							</ul><!-- End tabs-->
						</div><!-- End col-md-9 -->

					</div><!-- End row -->
				</div><!-- End Item Container -->
			</li><!-- End Item -->

            <!--li><a href="#" class="drop-down">Facilities <i class="icon-angle-down" style="margin-left: 1%"></i></a>
                <!-- Begin Item -->
             <!--   <div class="drop-down-container" id="icon-menu">
                    <div class="row">
                        <div class="col-md-3 "><a href="#" title="About "></a></div>  <!--i class="icon-building icon-3x"></i>Terminal Management -->
                    <!--    <div class="col-md-3"><a href="#" title="Academics"></a></div>  <!--i class="icon-flag icon-3x"></i>Transportation -->
                    <!--    <div class="col-md-3"><a href="#" title="All courses"></a></div>  <!--i class="icon-list icon-3x"></i>Laboratory -->
                   <!--     <div class="col-md-3"><a href="#" title="Staff page"></a></div>  <!--i class="icon-group icon-3x"></i>Facilities -->
                     <!--   <div class="col-md-3 "><a href="#" title="Contact"></a></div> <!--i class="icon-envelope icon-3x"></i>Debate Clubs -->
                     <!--   <div class="col-md-3"><a href="#" title="Plan a visit"></a></div>  <!--i class="icon-eye-open icon-3x"></i>Sports Team -->
                    <!--   <div class="col-md-3"><a href="#" title="News"></a></div> <!--i class="icon-physician icon-3x"></i>Medical -->
                    <!--    <div class="col-md-3"><a href="#" title="Blog"></a></div> <!--i class="icon-comments-alt icon-3x"></i>Vocational Course  -->
                    <!--    <div class="col-md-3"><a href="#" title="Single Post"></a></div> <!--i class="icon-comments icon-3x"></i>Spoken English  -->
                   <!--     <div class="col-md-3"><a href="#" title="calendar"></a></div> <!--i class=" icon-calendar icon-3x"></i>Career -->
                  <!--  </div><!-- End row -->
               <!-- </div>  --><!-- End Item Container -->
            <!--/li--><!-- End Item -->

            <li><a href="javascript:void(0)" class="drop-down">Contacts <i class="icon-angle-down" style="margin-left: 1%"></i></a>
                <!-- Begin Item -->
                <div class="drop-down-container">

                    <div class="row">

                        <div class="col-md-6">
							<div class="mapouter">
								<div class="gmap_canvas">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.1780656140922!2d91.79957881444822!3d22.309104448213898!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acdf250bff95bf%3A0x253978de79dca90e!2z4Kaa4Kaf4KeN4Kaf4KaX4KeN4Kaw4Ka-4KauIOCmrOCmqOCnjeCmpuCmsA!5e0!3m2!1sbn!2sbd!4v1610352423840!5m2!1sbn!2sbd" width="100%" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
									</iframe>
								</div>
							</div>
                        </div>

                        <div class="col-md-6">
                            <h4>Address</h4>
                            <ul>
                                <li><i class="icon-home"></i> Address : Chittagong Port Authority</li>
                                <li><i class="icon-adn"></i> Operation: +880-1749923327</li>
                                <li><i class="icon-location-arrow"></i> Network: +880-1747810037</li>
                                <!--li><i class="icon-envelope"></i> Email: <a href="#">care.ctms@datasoft-bd.com</a></li-->
                                <li><i class="icon-envelope"></i> Email: <a href="#">helpdesk@cpatos.gov.bd</a></li>
                            </ul>
                            <br>
                            <hr>

                            <!-- <div class="row">

                                <div class="col-md-6">
                                    <h5>Questions?</h5>
                                    <p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
                                    <p><a href="#" class="button_red_small">Read more</a></p>
                                </div>

                                <div class="col-md-6">
                                    <h5>Apply now</h5>
                                    <p>An utinam reprimique duo, putant mandamus cu qui. Autem possim his cu, quodsi nominavi fabellas ut sit, mea ea ullum epicurei.</p>
                                    <p><a href="#" class="button_red_small" title="Contact">Contact us</a></p>
                                </div>

                            </div>End row -->
                        </div><!-- End col-md-6 -->
                    </div><!-- End row-->
                </div><!-- End Item Container -->
            </li><!-- End Item -->

			<!--li><a href="#" class="nodrop-down">Apps </a></li-->
			<li class="drop-normal"><a href="javascript:void(0)" class="drop-down">Apps  <i class="icon-angle-down" style="margin-left: 1%"> </i></a>
				<div class="drop-down-container normal">
					<ul>
						<?php                           
                            $pathTOS= 'http://'.$_SERVER['SERVER_NAME'].'/Apps/CPATOS/';                            
                        ?>
						<li class="active"><a href="<?php echo $pathTOS.'TOS_20_07_22.apk';?>"><span>TOS App</span></a></li>
						<?php                           
                            $pathTOSOld= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/resources/tos_old/';                            
                        ?>
						<li class="active"><a href="<?php echo $pathTOSOld.'TOS_OLD.apk';?>"><span>TOS Old App</span></a></li>
						<?php                           
                            $pathTOSCamera= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/resources/cameraApp/';                            
                        ?>
						<li class="active"><a href="<?php echo $pathTOSCamera.'Camera.apk';?>"><span>Camera App</span></a></li>
						<?php                           
                            //$smartEnterprise= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/resources/smartEnterprise/';                            
                        ?>
						<!-- <li class="active"><a href="<?php //echo $smartEnterprise.'SmartEnterprise.apk';?>"><span>Smart Enterprise</span></a></li> -->
						<?php                           
                          //  $pathPilotApp= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/resources/apk/CPAPilot/';                            
                        ?>
						<!--<li class="active"><a href="<?php //echo $pathPilotApp.'CPAPilot.apk';?>"><span>Pilot App</span></a></li> -->
						
						<!--<li class="active"><a href="<?php //echo $pathTOS.'TOS_23_12_21.apk';?>"><span>Pilot App</span></a></li>
						<li class="active"><a href="<?php //echo $pathTOS.'TOS_23_12_21.apk';?>"><span>C&F TOS GATE PASS APP</span></a></li>  -->
					</ul>
				</div>
			</li>
			
			<li><a href="javascript:void(0)" class="drop-down">Notice & Documents <i class="icon-angle-down" style="margin-left: 1%"></i></a>
				<!-- Begin Item -->
				<div class="drop-down-container">

					<div class="row">

						<div class="col-md-6">
							<h4> </h4>
							<ul class="list-menu">
								<li><a href="." title="Notice"><h3 style="color:white;"><i class="icon-building"></i>Notice</h3></a></li><hr>
								<li>
									<?php $noticePath= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/notices/'; ?>
									<a target="_blank" href="<?php echo $noticePath. 'vesselVisitInfoPublish.pdf'; ?>" title="Notice">
										<font style="color:white;"><i class="icon-arrow-right"></i>Vessel Visit Information তথ্যাদি CTMS এর অধীন TOS এ অন্তর্ভুক্তিকরণ প্রসঙ্গে। </font>
									</a>
								</li>
							</ul>
						</div>

						<div class="col-md-6">
							<h4> </h4>
							<ul class="list-menu">
								<li><a href="." title="Documents"><h3 style="color:white;"><i class="icon-book"></i>Documents</h3></a></li><hr>
								<li class="active" >
									<a target="_blank" href="<?php echo $path3. 'User Manual of Electronic Delivery Order in TOS System-version_2.pdf'; ?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>EDO Manual</font></span>
									</a>
								</li>
								

								<!--li class="active"><a href="<?php echo site_url("Report/containerHandlingView");?>" target="_blank"><span>Equipment Booking Report</span></a></li-->
								<?php $formPath= 'http://'.$_SERVER['SERVER_NAME'].'/pcs/assets/applicationForm/'; ?>
								<li class="active">
									<a target="_blank" href="<?php echo $formPath. 'userForm.pdf'; ?>">
										<font style="color:white;"><i class="icon-arrow-right"></i>TOS User ID Application Form (PDF)</font>
									</a>
								</li>
								<li class="active">
									<a target="_blank" href="<?php echo $formPath. 'userForm.doc'; ?>">
										<font style="color:white;"><i class="icon-arrow-right"></i>TOS User ID Application Form (Word)</font>
									</a>
								</li>
								
								<li class="active" >
									<a target="_blank" href="<?php echo $path5. 'TOS_Gate_Controll_Process.pdf'; ?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>TOS Gate Control Process</font></span>
									</a>
								</li>
								
								<li class="active">
									<a href="<?php echo $path.'copino_sample.csv';?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>Copino Sample</font></span>
									</a>
								</li>								
								<li class="active">
									<a href="<?php echo $path.'coparn_sample.csv';?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>Coparn Sample</font></span>
									</a>
								</li>
								
								<li class="active" >
									<a target="_blank" href="<?php echo $path2. 'CopinoManual.pdf'; ?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>Copino Manual</font></span>
									</a>
								</li>
								<!-- <li><a href="<?php echo $path.'CopinoManual.pdf';?>" target="_BLANK"><span>Download PDF</span></a></li>-->

								<li class="active" >
									<a target="_blank" href="<?php echo $path3. 'C&FManual.pdf'; ?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>C&F User Manual</font></span>
									</a>
								</li>
								<li class="active" >
									<a target="_blank" href="<?php echo $path4. 'pilot_app_V001.pdf'; ?>">
										<span><font style="color:white;"><i class="icon-arrow-right"></i>Pilot App Manual</font></span>
									</a>
								</li>
								
								<!--Not working link -->
								<li class="active" >
									<a href="#"><span><font style="color:white;"><i class="icon-arrow-right"></i>Break Bulk User Manual</font></span></a>
								</li>
								<li class="active" >
									<a href="#"><span><font style="color:white;"><i class="icon-arrow-right"></i>Port User Manual</font></span></a>
								</li>

								<li class="active" >
									<a href="<?php echo site_url('ShedBillController/truckEntryForUsers')?>"><span><font style="color:white;"><i class="icon-arrow-right"></i>Entry</font></span></a>
								</li>
								<li class="active" >
									<a href="<?php echo site_url('ShedBillController/gatePassForUsersForm')?>"><span><font style="color:white;"><i class="icon-arrow-right"></i>Print Gate Pass</font></span></a>
								</li>
							</ul>
						</div>

					</div><!-- End row -->
				</div><!-- End Item Container -->
			</li><!-- End Item -->
			
			
            <!-- <a href="<?php echo site_url('FrontEndController/Gallery')?>" class="nodrop-down">Gallery </a> -->

			<li data-toggle="modal" data-target="#login"><a class="nodrop-down ">Login</a></li>

            <li>
                <a class="nodrop-down " href="<?php echo site_url('Login/UserSignUp')?>"  style="text-decoration: none;color:white;">SIGNUP</a>
            </li>

        </ul><!-- End Mega Menu -->
    </div>
</nav><!-- /navbar -->
