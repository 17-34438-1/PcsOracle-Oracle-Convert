<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 company_logo" id="brand-footer">

                <a href="<?php echo site_url('Welcome/')?>">
                    <img src="<?php echo  ASSETS_WEB_PATH?>fimg/cpaLogo.png" alt="Logo">
                    <p style="margin-top:10px ;font-size: 22px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
						Chittagong Port Authority
					</p>
                    <p style="font-size: 18px; color: black">   চট্টগ্রাম  বন্দর কর্তৃপক্ষ   </p>
                </a>


				<h4 style="padding-top: 50px">
					Powered By © <br/>
				</h4>
				<a href="<?php echo site_url('Welcome/')?>">
                    <img width="100px" height="50px" src="<?php echo  ASSETS_WEB_PATH ?>fimg/datasoft_logo.gif" alt="">
                    <!--p style="margin-top:10px ;font-size: 26px; color: black; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase"> DataSoft Systems Bangladesh Ltd</p-->
                    
                </a>
				<!--a href="#" target="_blank"><img width="60px" src="<?php echo  ASSETS_WEB_PATH ?>fimg/datasoft_logo.gif" alt=""> DataSoft Systems Bangladesh Ltd.</a-->
            </div>
            <div class="col-md-4 col-sm-4" id="contacts-footer">
                <h4>Contacts</h4>
                <ul>
                    <li><i class="icon-home"></i>Address Line,</li>
                    <li><i class="icon-adn"></i> Address Line</li>
                    <li><i class="icon-location-arrow"></i> Address Line</li>
                    <li><i class="icon-phone-sign"></i> 01000-000000, 01000 123456</li>
                    <li><i class="icon-envelope"></i> Email: <a href="#">myportpanel@gmail.com</a></li>
                </ul>
                <hr>
                <h4>Newsletter</h4>
                <p>Donec adipiscing, quam non faucibus luctus, mi arcu blandit diam. Dolor consul graecis nec ut, scripta eruditi scriptorem et nam.</p>

                <div id="message-newsletter"></div>
                <form method="post"  action="assets/newsletter.php" name="newsletter" id="newsletter" class="form-inline">
                    <input name="email_newsletter" id="email_newsletter"  type="email" value="" placeholder="Your Email" class="form-control" >
                    <button  id="submit-newsletter" class="button_medium add-bottom-20" style="top:2px; position:relative" > Subscribe</button>
                </form>
            </div>
            <div class="col-md-4 col-sm-4" id="quick-links">
                <h4>Quick links</h4>
               <ul>
                    <li><a href="#" >Career</a></li>
                    <li><a href="#" >Job Circular</a></li>
                    <li><a href="#" >All Notices</a></li>
                    <li><a href="#" >Schedule</a></li>
                    <li><a href="#" >Link Link</a></li>
                </ul>
                <hr>
                <ul>
                    <li><a href="#" >Programs &amp; Feature</a></li>
                    <li><a href="#" >Important Links</a></li>
                    <li><a href="#" >One Stop Panel</a></li>
                    <li><a href="#" >My Port Panel</a></li>
                    <li><a href="#" >CPA</a></li>
                    <li><a href="#" >Departments and Programs</a></li>
                </ul>
            </div>

        </div>
    </div>
</footer><!-- End footer-->

<div id="toTop">Back to Top</div>

<!-- MEGAMENU -->
<script src="<?php echo ASSETS_WEB_PATH?>js/jquery.easing.js"></script>
<script src="<?php echo ASSETS_WEB_PATH?>js/megamenu.js"></script>

<!-- OTHER JS -->
<script src="<?php echo ASSETS_WEB_PATH?>js/bootstrap.js"></script>
<script src="<?php echo ASSETS_WEB_PATH?>js/functions.js"></script>
<script src="<?php echo ASSETS_WEB_PATH?>validate.js"></script>

<!-- FANCYBOX -->
<script src="<?php echo ASSETS_WEB_PATH?>js/fancybox/source/jquery.fancybox.pack.js?v=2.1.4" type="text/javascript"></script>
<script src="<?php echo ASSETS_WEB_PATH?>js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5" type="text/javascript"></script>
<script src="<?php echo ASSETS_WEB_PATH?>js/fancy_func.js" type="text/javascript"></script>

<!-- REVOLUTION SLIDER -->
<script src="<?php echo ASSETS_WEB_PATH?>js/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo ASSETS_WEB_PATH?>js/jquery.themepunch.revolution.min.js"></script>
<script src="<?php echo ASSETS_WEB_PATH?>js/revolutio-slider-func.js"></script>

<!-- STYLE SWITCHER -->
<script type="text/javascript" src="<?php echo ASSETS_WEB_PATH?>js/jquery-sticklr-1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#example-1').sticklr({
            animate         : true,
            showOn		    : 'hover'
        });
    });
</script>
<script type="text/javascript" src="<?php echo ASSETS_WEB_PATH?>js/fswit.js"></script>
<ul id="example-1" class="sticklr">
    <li><a href="#"  ><img src="<?php echo ASSETS_WEB_PATH?>fimg/lessons_s.png" alt=""></a>
        <ul>
            <!-- <li class="sticklr-title"><a href="http://themeforest.net/user/Ansonika/portfolio?ref=ansonika">Quick links</a></li> -->
            <li><a href="<?php echo site_url('FrontEndController/AllCourse')?>" title="All courses">All courses</a></li>
            <li><a href="<?php echo site_url('FrontEndController/CourseDetl')?>" title="Course detail">Course detail</a></li>
            <li><a href="<?php echo site_url('FrontEndController/Staff')?>" title="Meet the team">Meet the team</a></li>
            <li><a href="<?php echo site_url('FrontEndController/ContactUs')?>" title="Apply for a course">Apply for a course</a></li>
            <li><a href="<?php echo site_url('FrontEndController/')?>" title="About">About </a></li>
            <li><a href="<?php echo site_url('FrontEndController/NewsEvents')?>" title="News">News</a></li>
            <li><a href="<?php echo site_url('FrontEndController/ContactUs')?>" title="Contact">Ask for more information</a></li>
        </ul>
    </li>
    <li><a href="#" class="icon-purchase"><img src="<?php echo ASSETS_WEB_PATH?>fimg/FF.png" alt=""></a>
        <ul>
            <li class="sticklr-title"><a href="#">Social Link </a></li>
            <li><a href="https://www.facebook.com/" title="Course detail">Facebook</a></li>
            <li><a href="https://twitter.com/" title="Course detail">Twitter</a></li>
        </ul>
    </li>
</ul>

</body>
</html>