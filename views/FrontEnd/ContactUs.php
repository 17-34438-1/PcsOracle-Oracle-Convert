<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h1>Contacts</h1>
            <ul class="breadcrumb">
                <li><a href="index.html">Home</a><span class="divider">/</span></li>
                <li class="active">Contact</li>
            </ul>
        </div>
        <!-- =========================Start Col left section ============================= -->
        <aside  class="col-md-4 col-sm-4">
            <div class="col-left">
                <h3>Address</h3>
                <ul>
                    <li><i class="icon-home"></i> Address Line</li>
					<li><i class="icon-adn"></i> Address Line</li>
					<li><i class="icon-location-arrow"></i> Address Line</li>
					<li><i class="icon-phone"></i> Telephone: + 00 (0) 0000 0000</li>
					<li><i class="icon-phone-sign"></i> 00000-000000, 01000 000000</li>
					<li><i class="icon-envelope"></i> Email: <a href="#">myportpanel@myportpanel.com</a></li>
                </ul>
                <hr>
				<div class="mapouter">
					<div class="gmap_canvas"><iframe  id="gmap_canvas" src="https://maps.google.com/maps?q==&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div><style>.mapouter{position:relative;text-align:right;}.gmap_canvas {overflow:hidden;background:none!important;}</style></div>
                <!--<iframe height="250" src="https://maps.google.it/maps?f=q&amp;source=s_q&amp;0.714353,-74.005973&amp;sspn=0.868126,2.422485&amp;ie=UTF8&amp;hq=&amp;hnear=New+York,+Stati+Uniti&amp;t=m&amp;z=10&amp;iwloc=A&amp;ll=40.714353,-74.005973&amp;output=embed" style="border:0;">
                </iframe>
                <br/>
                <small><a href="https://maps.google.it/maps?f=q&amp;source=embmp;hnear=New+York,+Stati+Uniti&amp;t=m&amp;z=10&amp;iwloc=A&amp;ll=40.714353,-74.005973" style="text-align:left">View bigger</a></small>-->
                <hr>
                <p>Get directions writing your start point.</p>
                <form action="http://maps.google.com/maps" method="get" target="_blank">
                    <input type="text" name="saddr"  placeholder="Enter your location" class="form-control" />
                    <input type="hidden" name="daddr" value="New York, NY 11430" /> <!-- Write here your end point -->
                    <input type="submit" value="Get directions" class=" button_medium" />
                </form>
            </div>
            <!--p>
                <a href="#" title="All courses"><img src="<?php echo ASSETS_WEB_PATH ?>fimg/banner.jpg" alt="Banner" class="img-rounded img-responsive"></a>
            </p-->
        </aside>

        <!-- =========================Start Col right section ============================= -->
        <section class="col-md-8 col-sm-8">
            <div class="col-right">
                <p class="lead">
                    An utinam reprimique duo, <strong>putant mandamus cu qui</strong>. Priaeque iuvaret nominati et, ad mea clita numquam. Maluisset dissentiunt et per, dico liber erroribus vis te. Dolor consul graecis nec ut, scripta eruditi scriptorem et nam.
                </p>
                <hr>
                <h4>General Enquire or Apply</h4>

                <div id="message-contact"></div>
                <form method="post" action="assets/contact.php" id="contactform">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name <span class="required">* </span></label>
                            <input type="text" class="form-control ie7-margin" id="name_contact">
                        </div>
                        <div class="col-md-6">
                            <label>Last name <span class="required">* </span></label>
                            <input type="text" class="form-control ie7-margin" id="lastname_contact">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Email <span class="required">* </span></label>
                            <input type="email" id="email_contact" class="form-control ie7-margin">
                        </div>
                        <div class="col-md-6">
                            <label>Phone <span class="required">* </span></label>
                            <input type="text" id="phone_contact" class="form-control ie7-margin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Select a department</label>
                            <select id="subject_contact" class="form-control">
                                <option value="Administration">Planning</option>
                                <option value="Admissions">Operations</option>
                                <option value="Courses">Terminal Management</option>
                                <option value="Apply">CTMS</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Message <span class="required">*</span></label>
                            <textarea rows="5" id="message_contact" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label><span class="required">*</span> Are you human? 3 + 1 =</label>
                            <input type="text" id="verify_contact" class="form-control">
                        </div>
                        <div class="button-align col-md-3">
                            <input type="submit" id="submit-contact" value="Submit" class=" button_medium">
                        </div>
                    </div>
                    <hr>
                </form>

                <h4>Plan a visit</h4>
                <div id="message-visit"></div>
                <form method="post" action="assets/visit.php" id="visit">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name <span class="required">* </span></label>
                            <input type="text" class="form-control ie7-margin" id="name_visit">
                        </div>
                        <div class="col-md-6">
                            <label>Last name <span class="required">* </span></label>
                            <input type="text" class="form-control ie7-margin" id="lastname_visit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Email <span class="required">* </span></label>
                            <input type="email" id="email_visit" class="form-control ie7-margin">
                        </div>
                        <div class="col-md-6">
                            <label>Phone <span class="required">* </span></label>
                            <input type="text" id="phone_visit" class="form-control ie7-margin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="datetimepicker" class="input-append" style="position:relative;">
                                <label>Select a date <span class="required">* </span></label>
                                <input type="text" class=" dateinput form-control" id="date_visit">
                                <span class="add-on" style="position:absolute; top:34px; right:5px; cursor:pointer"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><span class="required">*</span> Are you human? 3 + 1 =</label>
                            <input type="text" id="verify_visit" class="form-control">
                        </div>
                    </div>
                    <!-- end row-->
                    <input type="submit" id="submit-visit" value="Submit" class=" button_medium">
                </form>

            </div><!-- end col right-->
        </section>
    </div><!-- end row-->
</div><!-- end container-->
