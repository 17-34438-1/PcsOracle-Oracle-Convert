<style>
	.menuContainer {
		width:100%;
		color:#000;
	}
	.menuAdmin {
		width:100%;
		
		/*border: 3px solid #ab88e0;
		border-radius: 15px;*/
		background-color:#5bcdf8;
		padding:3px;
		margin:5px;
		font-family: "Calibri";
	}
	.menuContainer ul {
		padding: 0;
		margin: 0;
		list-style: none;
	}
	.menuContainer ul li {
		background-color: #bce8f9 ;
		margin-top:1px;
	}
	.menuContainer ul li a {
		/*display:block;*/
		padding: 5px 15px;
		color: #000;
		
		/*border-top: 1px solid #999;*/
		
		text-decoration: none;
		
	}
	.menuContainer ul li:first-child {
		border: none;
	}
	.menuContainer ul li:hover {
		background-color: #f2f2f2;		
	}
</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<div class="article">												
																												
                        <div class="menuContainer">		
                            <div style="float:left;width:47%;">
                                <div class="menuAdmin">1. LCL ASSIGNMENT SECTION
                                    <ul>
                                        <li>1.1<a href="<?php echo site_url('cfsModule') ?>">LCL ASSIGNMENT ENTRY FORM</a></li>
                                        <li>1.2<a href="<?php echo site_url('cfsModule/lclAssignmentEntryForm_modified') ?>">LCL ASSIGNMENT ENTRY FORM(NEW)</a></li>
                                        <li>1.3<a href="<?php echo site_url('cfsModule/lclAssignmentReportTable') ?>">LCL ASSIGNMENT REPORT</a></li>
                                        <li>1.4<a href="<?php echo site_url('cfsModule/lclAssignmentEntryEditForm')?>">LCL ASSIGNMENT ENTRY EDIT</a></li>
                                    </ul>
                                </div>
                                <div class="menuAdmin">3. COMMUNITY
                                    <ul>
                                        <li>3.1<a href="<?php echo site_url('report/certificationFormHtml') ?>">UNSTUFFING INFORMATION</a></li>
                                        <li>3.2<a href="<?php echo site_url('report/verificationListForm') ?>">VERIFICATION LIST</a></li>
                                    </ul>
                                </div>	
                                <div class="menuAdmin">5. APPRAISEMENT
                                    <ul>
                                        <li>5.1<a href="<?php echo site_url('report/appraisementCertifySection') ?>">APPRAISEMENT SECTION</a></li>
                                        <li>5.2<a href="<?php echo site_url('report/appraisementCertifySectionEdit') ?>">EDIT APPRISEMENT</a></li>
                                    </ul>
                                </div>
                                <div class="menuAdmin">7. BILL SECTION
                                    <ul>															
                                        <li>7.1<a href="<?php echo site_url('ShedBillController/billGenerationForm') ?>">BILL GENERATION</a></li>
                                        <li>7.2<a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>">SHED BILL LIST</a></li>		
                                        <li>7.3<a href="<?php echo site_url('report/billTariff') ?>">BILL TARRIF</a></li>
                                        <li>7.4<a href="<?php echo site_url('report/billTariffRate') ?>">BILL TARRIF RATE</a></li>	
                                        <li>7.5<a href="<?php echo site_url('report/billList') ?>">BILL LIST</a></li>	
                                        <li>7.6<a href="<?php echo site_url('report/rateList') ?>">RATE LIST</a></li>	
                                    </ul>
                                </div>
                                <div class="menuAdmin">11. GATE REPORT							
                                    <ul>
                                        <li>11.1<a href="<?php echo site_url('report/cartTicketForm') ?>">CART TICKET</a></li>
                                        <li>11.2<a href="<?php echo site_url('gateController/gateOut') ?>">GATE ENTRY</a></li>
                                        <li>11.3<a href="<?php echo site_url('report/gateConfirmation') ?>">GATE CONFIRMATION</a></li>
                                        <li>11.4<a href="<?php echo site_url('report/gateReportForm') ?>">GATE REPORT</a></li>
                                    </ul>						  
                                </div>
                            </div>
                            
                            <div style="float:right;width:47%;">
                                <div class="menuAdmin">2. UNSTUFFING POINT
                                    <ul>
                                        <li>2.1<a href="<?php echo site_url('cfsModule/lclAssignmentReportTable') ?>">LCL ASSIGNMENT REPORT</a></li>
                                        <li>2.2<a href="<?php echo site_url('report/tallyEntryWithIgmInfoForm') ?>">TALLY ENTRY WITH IGM INFORMATION</a></li>
                                    </ul>
                                </div>
                                <div class="menuAdmin">4. CERTIFY SECTION
                                    <ul>
                                        <li>4.1<a href="<?php echo site_url('report/lclAssignmentCertifySection') ?>">ASSIGNMENT/CERTIFY</a></li>									
                                    </ul>
                                </div>
                                <div class="menuAdmin">6. MANIFEST SECTION
                                    <ul>
                                        <li>6.1<a href="<?php echo site_url('ShedBillController/unitSetUpdate') ?>">UNIT ASSIGN</a></li>
                                        <li>6.2<a href="<?php echo site_url('ShedBillController/unitList') ?>">ASSIGNED UNIT LIST</a></li>	
                                        <li>6.3<a href="<?php echo site_url('report/deliveryEntryFormByWHClerk') ?>">DOCUMENT PROCESS (Verify)</a></li>
                                        
                                    </ul>
                                </div>
                                <div class="menuAdmin">8. BANK  SECTION
                                    <ul>
                                        <li>8.1<a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>">SHED BILL LIST</a></li>
                                    </ul>
                                </div>
                                <div class="menuAdmin">9. BILL SECTION							
                                    <ul>
                                        <li>9.1<a href="<?php echo site_url('report/releaseOrderForm') ?>">RELEASE ORDER</a></li>
                                        <li>9.2<a href="<?php echo site_url('report/shedDeliveryOrder') ?>">SHED DELIVERY ORDER</a></li>
                                        <li>9.3<a href="<?php echo site_url('report/cartTicketForm') ?>">CART TICKET</a></li>
                                    </ul>						  
                                </div>
                                <div class="menuAdmin">10. SHED/YARD DELIVERY SECTION
                                    <ul>
                                        <li>10.1<a href="<?php echo site_url('report/deliverySearchByVerifyNo') ?>">W.H/LOCKFAST ENTRY</a></li>
                                        <li>10.2<a href="<?php echo site_url('report/head_delivery') ?>">HEAD DELIVERY</a></li>	
                                        <li>10.3<a href="<?php echo site_url('ShedBillController/billSearchByVerifyForm') ?>">DELIVERY ORDER(DO) ENTRY</a></li>
                                    </ul>
                                </div>
                                <div class="menuAdmin">12. LABOUR INFO FOR LASHER
                                    <ul>
                                        <li>12.1<a href="<?php echo site_url('report/labourInfoEntryLasher') ?>">LABOUR INFO ENTRY FOR CCT LASHER</a></li>
                                        <li>12.2<a href="<?php echo site_url('report/labourInfoEntryLasherList') ?>">LABOUR INFO LIST FOR CCT LASHER</a></li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>						
					<div class="clr"></div>
				</div>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>