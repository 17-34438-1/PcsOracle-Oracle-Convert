<body>
<section class="body">
	<!-- start: header -->
	<header class="header">
				<div class="logo-container">
					<a href="<?php echo site_url('Welcome/') ?>" class="logo" style="width:50%;">
						<nobr>
							<img src="<?php echo  ASSETS_WEB_PATH?>fimg/logocpa.png" height="35" alt="JSOFT Admin" /> 
							<span style="font-size:22px;text-decoration:none;color:#000;">Port Community System</span>
						</nobr>
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
					<!--form action="pages-search-results.html" class="search nav-form">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form-->
			
					<span class="separator"></span>
					<?php 
						$org_type_id = $this->session->userdata('org_Type_id');
						
						if($org_type_id == 1 || $org_type_id == 2 || $org_type_id == 4 || $org_type_id == 62){
					?>
					<?php
			
			include("mydbPConnection.php");
			$login_id = $this->session->userdata("login_id");
			$org_Type_id = $this->session->userdata("org_Type_id");
			$org_id = $this->session->userdata("org_id");
			$countResult=0;
			$i=0;
			$times ="";
			$splitTime ="";
			$hour = "";
			$minute ="";
			$actionTime='';
			$i=0;

			$resultAllQuery = "SELECT *,TIMEDIFF(NOW(),generate_time) AS action_time FROM (
				SELECT edo_notification.id,edo_notification.`org_notify_by`,edo_notification.generate_time,organization_profiles.`Organization_Name`,edo_notification.`notification_st`,
				
				
				
			(CASE
				WHEN notification_st='1' THEN CONCAT(organization_profiles.`Organization_Name`, ' has submitted an EDO application for BL- ', edo_application_by_cf.`bl`)
				WHEN notification_st='2' THEN CONCAT(organization_profiles.`Organization_Name`, ' has forwarded EDO for BL- ', edo_application_by_cf.`bl`)
				WHEN notification_st='3' THEN CONCAT(organization_profiles.`Organization_Name`, ' has uploaded EDO for BL- ', edo_application_by_cf.`bl`)
				WHEN notification_st='4' THEN CONCAT(organization_profiles.`Organization_Name`, ' has approved for BL- ', edo_application_by_cf.`bl`) 
			END) AS notification_msg
			FROM edo_notification 
			INNER JOIN users ON `edo_notification`.`org_notify_by`=users.`login_id`
			INNER JOIN `organization_profiles` ON users.`org_id`=`organization_profiles`.`id`
			INNER JOIN `edo_application_by_cf` ON `edo_notification`.`application_id`=`edo_application_by_cf`.`id`
			WHERE seen_st='0' AND org_notified='$org_id'
			UNION
			SELECT edo_notification.id,edo_notification.`org_notify_by`,edo_notification.generate_time,organization_profiles.`Organization_Name`,edo_notification.`notification_st`,
			(CASE
			WHEN notification_st='1' THEN CONCAT(organization_profiles.`Organization_Name`, ' has submitted an EDO application for BL- ', edo_application_by_cf.`bl`)
	        WHEN notification_st='2'   AND seen_st='0' THEN CONCAT(organization_profiles.`Organization_Name`, 'BL- ', edo_application_by_cf.`bl`, ' is pending for upload. ')
	        WHEN notification_st='3' THEN CONCAT(organization_profiles.`Organization_Name`, ' EDO approval is pending for BL- ', edo_application_by_cf.`bl`)
	         
			END) AS notification_msg
			FROM edo_notification 
			INNER JOIN users ON `edo_notification`.`org_notify_by`=users.`login_id`
			INNER JOIN `organization_profiles` ON users.`org_id`=`organization_profiles`.`id`
			INNER JOIN `edo_application_by_cf` ON `edo_notification`.`application_id`=`edo_application_by_cf`.`id`
			WHERE life_st='0' AND org_notified='$org_id'
			
			) AS tmp  
			WHERE notification_msg IS NOT NULL
			ORDER BY action_time";

			$queryresult = mysqli_query($con_cchaportdb,$resultAllQuery);
			$countResult=mysqli_num_rows($queryresult);
			?>
			
		
					<ul class="notifications">
				
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="<?php if($countResult==0){echo "";}else {echo "badge";}?>"><?php if($countResult==0){echo "";}else{echo $countResult;}?></span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="<?php if($countResult==0){echo "";}else {echo "pull-right label label-default";}?>">
									<?php if($countResult==0){echo "No ";}else{echo $countResult;}?>
									</span>
									Notification
								</div>
			
								<div class="content">
									<ul>
										 

									<?php while(($row = mysqli_fetch_array($queryresult)) && $i<5){
										
										
										 
										?>
										<li style="padding: 3px;">
											<a href="<?php echo site_url('ShedBillController/viewNotification')?>?flag=<?php echo $row['id'];?>"  class="<?php if(  $countResult==0 ){echo "clrearfix";}else  { echo "alert-success";} ?>" >
												<div class="image">
											
													<!-- class="fa fa-paper-plane bg-danger"></i-->
												</div>
												<span class="title"></span>
												
												<span class="message" style="color: #000011;"><?php echo $row["notification_msg"];?></span>
												<span class="message pull-right" >
													<?php
													$times = $row['action_time'];
													$splitTime = explode(":",$times);
													 $hour =  $splitTime[0];
													 $minute =$splitTime[1];
													 if($hour==00){
													
													echo $actionTime= $minute ." "."m". " ". "ago";
												}
													else{
														echo $actionTime= $hour." " ."h"." ".$minute ." "."m". " ". "ago";;

													} 
													
													
													?>
													
												
												</span>
											</a>
										</li>
										
										
										
										<?php $i++;}?>
										
									
				
										
									</ul>
			
									<hr />
			
									<div class="text-right">
										<a href="<?php echo site_url('ShedBillController/ViewAllNotification') ?>" class="view-more" style="color: #000011;">View All</a>
									</div>
								</div>
							</div>
						</li>
					</ul>

					<?php 
						}
					?>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<!--figure class="profile-picture">
								<img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure-->
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@JSOFT.com">
								<span class="name">
									<?php echo $this->session->userdata("User_Name");?>
								</span>
								<span class="name">
									<?php echo $this->session->userdata("login_id");?>
								</span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="<?php echo site_url('Login/logout') ?>"><i class="fa fa-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
	</header>
