<style>
.badge1 {
    position: relative;
}

.badge1[data-badge]:after {
    content: attr(data-badge);
    position: absolute;
    top: -5px;
    right: -10px;
    font-size: 1.5em;
    background: red;
    color: white;
    width: 28px;
    height: 28px;
    text-align: center;
    line-height: 28px;
    border-radius: 50%;
    box-shadow: 0 0 1px #333;
}

.badge_large {
    position: relative;
}

.badge_large[data-badge]:after {
    content: attr(data-badge);
    position: absolute;
    top: -5px;
    right: -10px;
    font-size: 16px;
    background: red;
    color: white;
    width: 35px;
    height: 35px;
    text-align: center;
    line-height: 28px;
    border-radius: 50%;
    box-shadow: 0 0 1px #333;
}

.blink_me {
    animation: blinker 2s linear infinite;
    color: red;
    font-weight: bold;
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

.blink_me:hover {
    opacity: 1;
    -webkit-animation-name: none;
    /* should be set to 100% opacity as soon as the mouse enters the box; when the mouse exits the box, the original animation should resume. */
}
</style>

<script type="text/javascript">
var session ="<?php echo $this->session->userdata('Control_Panel') ?>";
if(session==91){
    var countVesselFile=0;
    function loadDoc() {
        setInterval(function(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                countVesselFile=this.responseText;
            document.getElementById("noti_number").innerHTML = countVesselFile;
            console.log(this.responseText);
            }
        };
        xhttp.open("GET", "<?php echo site_url(); ?>/breakbulk/BBVesselOperationController/BBVesselDeclarationNotiFicationUpdate", true);
        xhttp.send();

        },2000);
    }
    loadDoc();
}
</script>


<?php 
	$lid = $this->session->userdata('login_id');
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	
	$menu = $this->session->userdata("menu");
	$sub_menu = $this->session->userdata("sub_menu");
	
	include('mydbPConnection.php');
	include('dbConection.php');
?>
<div class="inner-wrapper">
    <aside id="sidebar-left" class="sidebar-left">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <strong>


                    <?php
						echo $this->session->userdata('org_type')." Panel";
					?>
                </strong>
            </div>
            <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html"
                data-fire-event="sidebar-left-toggle">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>

        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li class="<?php if($sub_menu=="Dashboard"){?>nav-active<?php }?>">
                            <a href="<?php echo site_url('FrontEndController/Dashboard') ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>Dashboard </span>
                            </a>
                        </li>
                        <?php 						
						
						if(($this->session->userdata('Control_Panel')==57 && $this->session->userdata('org_Type_id')==57) 
								or ($this->session->userdata('org_Type_id')==10))
						{?>
                        <li>
                            <a><span>SHIPPING AGENT PANEL</span></a>

                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization List
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM General Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedDeliveryOrderInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>SHED DELIVERY ORDER INFO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedDeliveryOrderList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Shed Delivery Order List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY FORM</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/officeCodeUpdaterForm/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>B/E Entry Form
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>STUFFING REPORT</span></a>
                            <ul class="nav nav-children ">
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing <br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/sh_agent_assignment_Form') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment Details
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <li><a href="<?php echo site_url('Report/myPortCodeList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Port Code List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVessel') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Agent Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myOffdocWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVesselInfo') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Transit Ways <br /><span
                                            style="padding-right:30px;"></span> Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWisePreadviceloadedContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span> Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Loaded Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge and <br /><span
                                            style="padding-right:30px;"></span> Balance Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/doReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery Order Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContainerGateInForm') ?>"><i
                                            class="fa fa-mail-forward (alias)">
                                        </i>Export Container Gate <br /><span style="padding-right:30px;"></span> in
                                        List
                                    </a>
                                </li>
                                <!-- From MLO Panel --->
                                <li>
                                    <a href="<?php echo site_url('Report/dischargeListForMLO') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Discharge List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying <br /><span
                                            style="padding-right:30px;"></span> Container Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export <br>
                                        <span style="padding-right:30px;"></span>APPS Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesselBillList/1') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListImportStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Import<br>
                                        <span style="padding-right:30px;"></span>APPS Loading Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li>
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DISPUTE BILL</span></a>
							<ul class="nav nav-children">
								
								<li>
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
									</a>
								</li>							
							</ul>
						</li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">
                                <?php
									//include("dbConection.php");
									$str = "SELECT COUNT(DISTINCT rotation) AS cnt
									FROM ctmsmis.mis_exp_unit_preadv_req
									WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
									$result = mysqli_query($con_sparcsn4,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;
									//echo $badge;
								?>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation
                                        <span class="badge1" data-badge="<?php echo $badge;?>"
                                            style="padding-left:20px;"></span>
                                    </a>
                                </li>
								<li><a href="<?php echo site_url('UploadExcel/upload_copern_copino_form') ?>"><i  class="fa fa-mail-forward (alias)"></i> Upload for COPARN & COPINO</a></li>
                                <li><a href="<?php echo site_url('UploadExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO</a></li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/ediDownloadSample') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Download Excel Sample <br /><span
                                            style="padding-right:30px;"></span> for EDI Download
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/EDISearch'."/"."EDISearch") ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDI Converter
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ConvertedEDISearch') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Converted Vessel List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- same list as acc and bill operator -->
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>BILL</span></a>
							<ul class="nav nav-children">
								
								<li>
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
									</a>
								</li>							
							</ul>
						</li-->

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">

                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php } ?>
						
						
					<?php if($this->session->userdata('org_Type_id')==103)
						{ ?> 
						<ul class="nav nav-main">
	
							<li class="nav-parent">
								<a>
									<i class="fa fa-copy" aria-hidden="true"></i>
									<span>Billing</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="pages-signup.html">Billing Summary</a>
									</li>
									<li>
										<a href="<?php echo site_url('ContainerBill/new_bill_generation'); ?>">New Bill Generation</a>
									</li>
									<li>
										<a href="<?php echo site_url('ContainerBill/container_bill_List'); ?>">Billing List</a>
									</li>
									<li>
										<a href="pages-lock-screen.html">Rotation Wise View</a>
									</li>
									<li>
										<a href="pages-user-profile.html">Update VAT Info</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-copy" aria-hidden="true"></i>
									<span>Update Bill Info</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo site_url('Menu_Controller/update_bill_date'); ?>">Update Bill Date</a>
									</li>
									<li>
										<a  href="<?php echo site_url('Menu_Controller/update_bill_number'); ?>">Update Bill Number</a>
									</li>
									<li>
										<a href="<?php echo site_url('Menu_Controller/update_bill_mlo_agent'); ?>">Update MLO & Agent Info</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-copy" aria-hidden="true"></i>
									<span>Remarks</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo site_url('Menu_Controller/update_remarks'); ?>">Update Remarks</a>
									</li>
								</ul>
							</li>
							<li class="nav-parent">
								<a>
									<i class="fa fa-copy" aria-hidden="true"></i>
									<span>Forwarding</span>
								</a>
								<ul class="nav nav-children">
									<li>
										<a href="<?php echo site_url('Menu_Controller/bill_forwarding'); ?>" >Bill Forwarding</a>
									</li>
								</ul>
							</li>
						</ul>
						<?php } ?>

                        <!-- CPA SHED PANEL START -->

                        <?php 
						if($this->session->userdata('Control_Panel')==59 && $this->session->userdata('org_Type_id')==59)
						{
						?>
                        <li class="nav-active">
                            <a href="<?php echo site_url('FrontEndController/Dashboard') ?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span>CPA SHED PANEL</span>
                            </a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class='nav-active'><a href='#'><span>CPA SHED PANEL</span></a></li> 
						<li class="fa fa-home"><a><span>CPA SHED PANEL</span></a></li> -->
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT
                                    VIEW</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL Assignment Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/tallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Tally Sheet Entry
                                    </a>
                                </li>


                                <?php if($lid!="shed13" and $lid!="shed12" and $lid!="shed9" and $lid!="shed8" and $lid!="shed7" and $lid!="shed6" and $lid!="nctcfs" and $lid!="cctcfs") { ?>
                                <li>
                                    <a href="<?php echo site_url('Report/appraisementCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Appraisement Section
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/appraisementCertifySectionEdit') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Edit Appraisement
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('cfsModule/lclAssignmentEntryForm_modified') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL ASSIGNMENT ENTRY <br />
                                        <span style="padding-right:30px;"></span>FORM (NEW)
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SHED OPERATION</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/emergencyTruckList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Emergency Truck List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DISPUTE LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/tra') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TRAFFIC CONFIRMATION <br>
                                        <span style="padding-right:30px;"></span>PROCESS
                                    </a>
                                </li>
                                <!-- <li>
									<a href="<?php echo site_url('ShedBillController/confirmationProcessForm/cf') ?>">
										<i class="fa fa-mail-forward (alias)"></i>C&F Confirmation process
									</a>
								</li> -->
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessFormForCf') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CONFIRMATION for C&F
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ASSIGNMENT LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CART TICKET LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
							<ul class="nav nav-children">									
								<li>
									<a href="<?php echo site_url('Report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Manifest</a>
								</li>
							</ul>	
						</li-->
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>OTHERS</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/doEntrySearchForm') ?>">DELIVERY ORDER(DO) ENTRY</a></li>
								<li><a href="<?php echo site_url('CfsModule') ?>">LCL ASSIGNMENT ENTRY FORM</a></li-->
                                <li>
                                    <a href="<?php echo site_url('igmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM General Information<br />
                                    </a>
                                </li>
                                <?php 
									if($lid!="shed13" and $lid!="shed12" and $lid!="shed9" and $lid!="shed8" and $lid!="shed7" and $lid!="shed6" and $lid!="nctcfs" and $lid!="cctcfs") { 
								?>

                                <li><a href="<?php echo site_url('ShedBillController/billSearchByVerifyForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Delivery Order(DO) Entry</a></li>
                                <li><a href="<?php echo site_url('Report/deliveryEntryFormByWHClerk') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Document Process (Verify)</a></li>
                                <li><a href="<?php echo site_url('Report/deliverySearchByVerifyNo') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>W.H/LOCKFAST Entry</a></li>
                                <?php } ?>

                                <li><a href="<?php echo site_url('ShedBillController/tallyReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Tally Report</a></li>

                                <?php 
									if($lid!="shed13" and $lid!="shed12" and $lid!="shed9" and $lid!="shed8" and $lid!="shed7" and $lid!="shed6" and $lid!="nctcfs" and $lid!="cctcfs") { 
								?>
                                <li>
                                    <a href="<?php echo site_url('Report/cargoReceiveDeliveryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cargo Receiving & Delivery <br>
                                        <span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <?php } ?>

                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>LABOUR
                                    MODULE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/labourInfoEntryLasher') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Labour Info Entry<br />
                                        <span style="padding-right:30px;"></span>for CCT Lasher
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/labourInfoEntryLasherList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Labour Info List<br />
                                        <span style="padding-right:30px;"></span>for CCT Lasher
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/gangEntryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gang Entry Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/labourCategoryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Labour Category
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/equipmentAssignToLabourForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Assign to Labour</a></li>
                                <li><a href="<?php echo site_url('Report/gangAssignToVesselForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gang Assign to Vessel</a></li>
                                <li><a href="<?php echo site_url('Report/labourAssignToGangForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Labour Assign to Gang</a></li>
                                <!--li><a href="<?php echo site_url('ShedBillController') ?>">SHED BILL</a></li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL TALLY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/tallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TALLY LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <?php 
									if($lid!="shed13" and $lid!="shed12" and $lid!="shed9" and $lid!="shed8" and $lid!="shed7" and $lid!="shed6" and $lid!="nctcfs" and $lid!="cctcfs") { 
								?>
                                <li><a href="<?php echo site_url('Report/verificationListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Verification List</a></li>
                                <li><a href="<?php echo site_url('Report/appraisementListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Apprisement List</a></li>
                                <?php } ?>

                                <li><a href="<?php echo site_url('Report/tallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Tally List</a></li>
                                <li><a href="<?php echo site_url('Report/lcldeliveryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Delivery Indent Entry</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/lcldeliveryAssignment') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Delivery Assignment</a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/dateWiseTallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Date Wise Tally List</a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/shedWiseLyingTallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"> </i>Shed wise Lying Tally List</a></li>

                                <?php 
									if($lid!="shed13" and $lid!="shed12" and $lid!="shed9" and $lid!="shed8" and $lid!="shed7" and $lid!="shed6" and $lid!="nctcfs" and $lid!="cctcfs") { 
								?>
                                <li><a href="<?php echo site_url('Report/wirehouseReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Warehouse Report (Import) </a></li>
                                <li><a href="<?php echo site_url('Report/wirehouseReportFormDatewise') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Warehouse Report (Date Wise) </a>
                                </li>
                                <li><a href="<?php echo site_url('GateController/gateReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('LCL/shedWiseDeliveryReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Shed Head Delivery</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>




                        <?php if($lid=='DCEE' or $lid=='TM' or $lid=='ATM' or $lid=='abdullashibli' or $lid=='AENSAJID' or $lid=='elecmasihulasif') { ?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span> C&F MODULE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TRUCK ENTRY
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/truckEntranceApplicationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PRINT GATE PASS</a></li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <!-- <li>
								<a href="<?php echo site_url('ShedBillController/confirmationProcessForm/cf') ?>">
									<i class="fa fa-mail-forward (alias)"></i>C&F CONFIRMATION <br/>
										<span style="padding-right:30px;"></span>PROCESS
								</a>
							</li> -->
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessFormForCf') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CONFIRMATION for C&F
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DISPUTE LIST
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/truckReport/gateIn') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TRUCK TO GATE IN</a></li>
                                <li><a href="<?php echo site_url('Report/truckReport/insidePort') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>WORKING INSIDE THE PORT</a></li>
                                <li><a href="<?php echo site_url('Report/truckReport/gateOut') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TRUCK GATE OUT</a></li>


                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>MLO MODULE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/dischargeListForMLO') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying<br>
                                        <span style="padding-right:30px;"></span>Container Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
                                        <span style="padding-right:30px;"></span>apps Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListImportStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Import<br>
                                        <span style="padding-right:30px;"></span>Apps Loading Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE MODULE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('GateController/gateOut') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Entry</a></li>
                                <li><a href="<?php echo site_url('GateController/gateConfirmation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Confirmation</a></li>

                                <li><a href="<?php echo site_url('GateController/gateReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Report</a></li>
                                <li><a href="<?php echo site_url('GateController/gateRegisterReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Register Report</a></li>
                                <li><a href="<?php echo site_url('GateController/containerRegisterInRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Inward & Outward Container
                                        Register</a></li>
                                <li><a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward Container
                                        Register</a></li>
                                <li><a href="<?php echo site_url('GateController/containerRegisterInRegister_ocr')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Inward & Outward OCR Container
                                        Register</a></li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br>
                                        <span style="padding-right:30px;"></span>Empty Details
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>

                                <li><a href="<?php echo site_url('Report/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary List</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li><a href="<?php echo site_url('Report/auctionContainers') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Auction Handover Report</a></li>

                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Import Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesslWiseRefeerContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myWaterSupplyInVesselsReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Supply in Vessels</a></li>
                                <li><a href="<?php echo site_url('Report/myContainerHistoryReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container History Report</a></li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>

                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>
                                <li><a href="<?php echo site_url('Report/pangoanDischargeForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Discharge</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">

                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>


                        <?php } ?>

                        <?php 
						} 
						?>

                        <!-- CPA SHED PANEL END -->

                        <!-- CPA PANEL start -->
                        <?php
						if($this->session->userdata('Control_Panel')==12 and $this->session->userdata('section')==9)
						{
						?>
                        <li class="fa fa-home"><a><span>CPA PANEL</span></a></li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM Break Bulk
                                        Information</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/updateManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>">Convert COPINO</a></li-->
                                <li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert EXPORT-COMMODITY</a></li>
                                <!-- <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>"><i class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li> -->
                                <li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
                                <li><a href="<?php echo site_url('Report/convertIgmCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify Section</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Routing Points</a></li>
                                <li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myIGMFFReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break<br>
                                        <span style="padding-right:30px;"></span> Bulk Reports
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>

                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span> Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise <br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/uploadIcdExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Upload</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertIcdFileForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Converter</a></li>
                            </ul>
                        </li>
                        <?php
						}
						?>

                        <?php
						if($this->session->userdata('Control_Panel')!=22 && $this->session->userdata('Control_Panel')==12 && $this->session->userdata('section')!=19 && $this->session->userdata('section')!=9 && $this->session->userdata('login_id')!="cpaops" && $this->session->userdata('Control_Panel')!=28 and $this->session->userdata('Control_Panel')!=57 && $this->session->userdata('Control_Panel')!=61)
						{ 
						?>
                        <li class="fa fa-home"><a><span>CPA PANEL</span></a></li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
						if($this->session->userdata('section')!='scy')
						{	
						?>

                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM Break Bulk<br>
                                        <span style="padding-right:30px;"></span> Information
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneralForBBshed/BB') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM BB Information</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/updateManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>">Convert COPINO</a></li-->
                                <li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert Export-Commodity</a></li>
                                <!-- <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>">Check The IGM</a></li> -->
                                <li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
                                <li><a href="<?php echo site_url('Report/convertIgmCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify Section</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li><a href="<?php echo site_url('Report/igmInfoByBl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Info by BL Number(s)</a></li>
                                <li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Routing Points</a></li>
                                <li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>


                                <li><a href="<?php echo site_url('Report/qgcContForwardedListForCPA') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>QGC Conainer Forwarding</a></li>


                                <?php
								if($lid=="sazam" or $lid=="tipai" or $lid=="popy" or $lid=="Shepu" or $lid=="shopna" or $lid=="cparaselrana2018" or $lid=="cpakamruzzaman2018"  or $lid=="cpatipu2018"  or $lid=="cpakamar2018" or $lid=="cpaziauddin" or $lid=="cpaabsaruddin2018" or $ipaddr="192.168.16.50") 
								{
								?>
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel') ?>">EXCEL UPLOAD FOR COPINO</a></li-->
                                <?php						
									$str = "SELECT COUNT(DISTINCT rotation) AS cnt
									FROM ctmsmis.mis_exp_unit_preadv_req
									WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
									$result = mysqli_query($con_sparcsn4,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;		
								?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation</a></li>
                                <?php
									$str = "select count(id) as cnt from edi_stow_info where file_status=0";
									
									$result = mysqli_query($con_cchaportdb,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;
									//echo "TEST : ".$badge;
								?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's EDI Declaration</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
								<li>
									<a href="<?php echo site_url('UploadExcel/copern_copino_list') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Download Coparn Copino 
									</a>
								</li>
                                <?php  
								if($lid=="sazam"){ ?>
                                <li><a href="<?php echo site_url('UploadExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO</a></li>
                                <?php } ?>
                                <?php
								}
								?>
                            </ul>
                        </li>
                        <?php
						$login_idIgm = $this->session->userdata('login_id');
						if($login_idIgm=="23534")
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM AMENDMENT</span></a>
                            <ul class="nav nav-children">




							<li><a href="<?php echo site_url('IgmViewController/igmAmendmentForm') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Amendment Form (New)</a></li>


                                <li><a href="<?php echo site_url('GateController/igmCorrection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Amendment</a></li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/lateBLsubmission') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL submission<br>
                                        <span style="padding-right:30px;"></span>form (Suplimentary) </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/igmContainerSubmissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL Container submission<br>
                                        <span style="padding-right:30px;"></span>form (Suplimentary) </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/lateBLsubmissionMaster') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL submission<br>
                                        <span style="padding-right:30px;"></span>form (Master) </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('IgmViewController/lateBLContainerSubmissionMaster') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL Container <br>
                                        <span style="padding-right:30px;"></span>Submission(Master)</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('EDOController/changeCNFForEDO') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Change C&F for EDO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status
                                        <br><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <?php
						}
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>VESSEL LIST WITH EXPORT<br>
                                        <span style="padding-right:30px;"></span> APPS LOADING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <span class="blink_me">
                                            <i class="fa fa-mail-forward (alias)"></i>Comments by Shipping <br>
                                            <span style="padding-right:30px;"></span>Section on Export Vessel
                                        </span>
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/workDone24hrsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hrs Container <br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Loading Export apps
                                    </a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPAN4') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Final Loading Export</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignment_sheet_for_pangaon') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Sheet for Outward<br>
                                        <span style="padding-right:30px;"></span>PANGAON ICT Container
                                    </a>
                                </li>
                                <?php  
								if($lid=="porikkhit") 
								{ 
								?>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement List</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel/last24hrsStatementList') ?>">LAST 24 STATEMENT LIST</a></li-->
                                <li><a href="<?php echo site_url('Report/stuffingPermissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form</a></li>
                                <?php
								}
								?>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br>
                                        <span style="padding-right:30px;"></span>Empty Details
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Special Assignment
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Termianl wise special <br>
                                        <span style="padding-right:30px;"></span>assignment</a>
                                </li>

                                <li><a href="<?php echo site_url('Report/assignment_summary') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Assignment Summary</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying<br>
                                        <span style="padding-right:30px;"></span>Container Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
                                        <span style="padding-right:30px;"></span>Receive Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerDeliveryRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
                                        <span style="padding-right:30px;"></span>Delivery Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/containerPositionEntryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Position Entry</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/blockWiseRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge Report<br>
                                        <span style="padding-right:30px;"></span>by apps
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Removal Container</a></li>
                                <li><a href="<?php echo site_url('Report/countryWiseImportReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Country Wise Import Report</a></li>
                                <li><a href="<?php echo site_url('Report/yearWiseImportReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Year Wise Import Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingImportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Discharging Import
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/pangoanDischargeForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Discharge</a></li>
                                <li><a href="<?php echo site_url('Report/removal_list_form/overflow') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Removal List OF Overflow Yard</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/removal_list_form/all') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>List of CTMS Assignment</a></li>
                                <li><a href="<?php echo site_url('Report/yardWiseImportTotalReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Yard Wise Assignment <br> <span
                                            style="padding-right:30px;"></span>Summary</a></li>

                                <li><a href="<?php echo site_url('Report/auctionContainers') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Auction Handover Report</a></li>

                                <li>
                                    <a href="<?php echo site_url('Report/contatOuterAnchorageForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Details at<br><span
                                            style="padding-right:30px;"></span>Outer Anchorage</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i
                                            class="fa fa-mail-forward (alias)">
                                        </i>Container / BL Block Release <br>
                                        <span style="padding-right:30px;"></span>List
                                    </a>
                                </li>

                                <!-- <li>
                                    <a href="<?php echo site_url('Report/blWiseAucCancel') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>BL Wise Auction Cancel Form<br>
                                        <span style="padding-right:30px;"></span>
                                    </a>
                                </li> -->
                                <?php
								// if($lid=="shamima" or $lid=="msarafat" or $lid=="jenifar" or $lid=="asraf" or $lid=="devcpa")
								// {
									// $str = "SELECT COUNT(*) AS tot
									// FROM 
									// (SELECT unit_gkey AS u_gkey,
									// (SELECT id FROM inv_unit WHERE gkey=a.unit_gkey) AS Cont,
									// (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size
									// FROM sparcsn4.inv_unit_equip	
									// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey		
									// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey		
									// WHERE sparcsn4.inv_unit_equip.unit_gkey=u_gkey LIMIT 1) AS size,
									// (SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) AS height
									// FROM sparcsn4.inv_unit_equip	
									// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey		
									// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey		
									// WHERE sparcsn4.inv_unit_equip.unit_gkey=u_gkey LIMIT 1) AS height,flex_date01,
									// (SELECT (SELECT new_value FROM srv_event_field_changes WHERE event_gkey=srv_event.gkey AND metafield_id='ufvFlexDate01' ORDER BY gkey DESC LIMIT 1) AS assignment_Date
									// FROM srv_event
									// INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
									// WHERE event_type_gkey=4 AND DATE(created)=CURDATE() AND applied_to_gkey=a.unit_gkey 
									// ORDER BY srv_event_field_changes.event_gkey DESC LIMIT 1) AS ass_dt
									// FROM (SELECT unit_gkey,flex_date01 FROM inv_unit_fcy_visit WHERE DATE(flex_date01)=CURDATE()) AS a) AS b WHERE ass_dt IS NOT NULL";
									// $result = mysqli_query($con_sparcsn4,$str);
									// $row = mysqli_fetch_object($result);
									// $badge = $row->tot;
								?>
                                <!--li class="badge_large" font-size="3px" data-badge="<?php echo $badge;?>"><a href="<?php echo site_url('report/special_assignment') ?>" target="_blank">SPECIAL ASSIGNMENT</a></li-->
                                <?php
								// }
								?>
                            </ul>
                        </li>
                        <?php
						if($lid=="zakir")
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status
                                        <br><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
                                        <span style="padding-right:30px;"></span>Generation List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>RELEASE ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roSubmitForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order Submission
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/releaseOrderViewForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order View
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php 
						} 
						?>

                        <?php
						if($lid=="sazam" or $lid=="tipai" or $lid=="popy" or $lid=="Shepu" or $lid=="shopna" or $lid=="cparaselrana2018" or $lid=="cpakamruzzaman2018"  or $lid=="cpatipu2018"  or $lid=="cpakamar2018" or $lid=="cpaziauddin" or $lid=="cpaabsaruddin2018" or $ipaddr="192.168.16.50")
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    AMENDMENT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('GateController/changeContStatusForm')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Change Container Status</a></li>
                            </ul>
                        </li>
                        <?php
						}
						?>

                        <?php
						if($lid=="devcpa")
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>LCL
                                    ASSIGNMENT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Entry Form</a></li>
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a></li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL TALLY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/tallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Tally Sheet Entry
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/tallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TALLY LIST</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL ASSIGNMENT</span></a>
                            <ul class="nav nav-children">
                                <?php if($lid=="k.ahmed" or $lid=="m.abusayem" or $lid=="16096") { ?>
                                <li>
                                    <a href="<?php echo site_url('CfsModule/lclAssignmentEntryForm_modified')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Entry Form</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myBirthIGMList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View Berth Operator Report</a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a>
                                </li>
                            </ul>
                        </li>
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>QGC/MHC </span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Vessel/qgcContForwardNewForm') ?>" ><i class="fa fa-mail-forward (alias)"></i>QGC/MHC Handled Forwarding</a></li>
							</ul>
						</li>

                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break <br><span
                                            style="padding-right:30px;"></span>Bulk Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Manifest</a></li>

                                <li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Manifest</a></li>
                                <li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Manifest</a></li>

                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li><a href="<?php echo site_url('Report/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary <br>
                                        <span style="padding-right:30px;"></span>List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <li><a href="<?php echo site_url('Report/depotLadenContFormTooffdock') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container <br><span
                                            style="padding-right:30px;"></span>(Original) </a></li>

                                <li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>

                                <li><a href="<?php echo site_url('Report/valuableItemByRotation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Valuable Item By Rotation</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
                                        <span style="padding-right:30px;"></span>Generation List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CART</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Import Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesslWiseRefeerContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myWaterSupplyInVesselsReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Supply in Vessels</a></li>
                                <li><a href="<?php echo site_url('Report/myContainerHistoryReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container History Report</a></li>
                            </ul>
                        </li>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>MIS
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('MisReport/A23_1Form') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Performance Container Vessels<br>
                                        <span style="padding-right:30px;"></span>Last 24hrs (A23.1)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsContInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information <br>
                                        <span style="padding-right:30px;"></span>(Garments Item) by Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/searchGarmentsItemByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information<br>
                                        <span style="padding-right:30px;"></span>by Item & Rotation
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24HrPositionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hours Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/last45DayslyingReportLink') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 45 Days Lying<br>
                                        <span style="padding-right:30px;"></span>Food Items Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrsCPAToOffdockRemovalForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours<br>
                                        <span style="padding-right:30px;"></span>CPA TO OFFDOCK removal
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/icdInBoundOutBoundReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ICD<br>
                                        <span style="padding-right:30px;"></span>In Bound Out Bound Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/pangaonInBoundOutBoundReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pangaon<br>
                                        <span style="padding-right:30px;"></span>In Bound Out Bound Report
                                    </a>
                                </li>
                                <!--li>
									<a href="<?php echo site_url('Report/dgContainerByRotation') ?>" ><i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('Report/valuableItemByRotation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Valuable Item By Rotation</a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/readObpcForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Excel for<br>
                                        <span style="padding-right:30px;"></span>OBPC & RL
                                    </a>
                                </li>
								<li>
									<a href="<?php echo site_url('UploadExcel/copern_copino_list') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Download Coparn Copino 
									</a>
								</li>
                            </ul>
                        </li>

                        <li class='nav-parent'><a><i class="fa fa-list"
                            aria-hidden="true"></i><span>EQUIPMENT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myReportHHTReCord') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        YARD Wise Assigned <br /><span style="padding-right:30px;"></span>Equipment List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/monthlyYardWiseContainerHandling') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        Yard Wise Total <br /><span style="padding-right:30px;"></span> Container Handling
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/blockWiseEquipmentList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        Container Handling Equipment <br /><span style="padding-right:30px;"></span>
                                        Assign
                                    </a>
                                </li>

                                <!--li><a href="<?php echo site_url('UploadExcel/equipmentDemandList') ?>">Container Handling Equipment Demand</a></li-->

                                <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>Equipment Demand</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('UploadExcel/equipmentHandlingDemandForm') ?>">
                                                <i class="fa fa-mail-forward (alias)"></i>
                                                Container Handling<br/><span style="padding-right:30px;"></span>
                                                Equipment Demand
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Report/cnfEquipmentDemandViewForm') ?>">
                                                <i class="fa fa-mail-forward (alias)"></i>Cnf Equipment Demand
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('UploadExcel/updateEquipmentList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Update Equipment Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dateWiseEqipAssignForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Block Wise Equipment <br /><span
                                            style="padding-right:30px;"></span> Booking Lists
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerHandlingRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Container Handling Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerHandlingRptMonthlyForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Monthly Container Handling
                                        <br /><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/plannedRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Container Job Done Vesselwise
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/equipmentEntryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Entry Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/mis_equipment_cur_stat_rpt') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Current Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/equipmentUnstuffing') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/equipmentUnstuffingList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/cargoHandlingEquipmentPositionEntry') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br /><span
                                            style="padding-right:30px;"></span>Position
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/cargoHandlingEquipmentRemarks') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br /><span
                                            style="padding-right:30px;"></span>Remarks
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dailyEquipmentBookingOpPosition') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Daily Equipment Booking<br /><span
                                            style="padding-right:30px;"></span> Operator Position
                                    </a>
                                </li>
								
								 <li>
                                    <a href="<?php echo site_url('Report/dailyEquipmentBookingOpPositionCCTorNCT') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Daily Equipment Booking<br /><span
                                            style="padding-right:30px;"></span> Operator Position CCT/NCT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrContHandlingForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Cont.<br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/blockWiseEquipmentHandlingReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>BlockWise Equipment Handling
                                        <br /><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li class='has-sub'><a href='#'><span>HEAD DELIVERY</span></a>
							<ul>
								<li><a href="<?php echo site_url('Report/head_delivery') ?>">HEAD DELIVERY DETAIL ENTRY</a></li>
								<li><a href="<?php echo site_url('Report/headDeliveryForm') ?>">HEAD DELIVERY REPORT</a></li>
							</ul>
						</li-->
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_bill_entry') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry Report</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_be_report') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Date Wise Bill of Entry Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/head_delivery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Search & Truck Entry</a>
                                </li>
                            </ul>
                        </li>
                        <?php
						}	// scy
						?>
                        <?php
						if($this->session->userdata('section')=='scy')
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>SCY CONTAINER
                                    OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery Empty Details
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/containerOperationYardLyingList') ?>"
                                        target="_blank"><i class="fa fa-mail-forward (alias)"></i>SCY Yard Lying
                                        Container</a></li>
                                <li><a href="<?php echo site_url('Report/containerPositionEntryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Operation</a></li>
                                <li><a href="<?php echo site_url('Report/containerOperationReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Operation Report</a></li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <?php
						if(strtoupper($lid)=="NORIN" || strtoupper($lid)=="UHABIBA" || strtoupper($lid)=="SHAMMI")
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>ICD</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/uploadIcdExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Upload</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertIcdFileForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Converter</a></li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <?php
						if(strtoupper($lid)=="GANICPA" || $lid=="mdsalim15")
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>GATE
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward<br>
                                        <span style="padding-right:30px;"></span>Container Register
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <?php
						// if($lid!="CPACS1")
						if($lid=="popy")
						{
						?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>GATE
                                    REPORT</span></a>
                            <!--ul class="nav nav-children">
								<li><a href="<?php echo site_url('Login/myPasswordChange') ?>"><i class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
							</ul-->
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/offhireSummaryAndDetails') ?>"
                                        target="_blank"><i class="fa fa-mail-forward (alias)"></i>Offhire Summary and
                                        Details</a></li>
                                <li>
                                    <a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward<br>
                                        <span style="padding-right:30px;"></span>Container Register
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php
						}	// end
						?>
                        <!-- CPA PANEL end -->
                        <!-- Break Bulk  (BB) CPA Panel start ASIF AND ANIT 26/2/23-->
                        <?php
						if($this->session->userdata('Control_Panel')==91 && $this->session->userdata('org_Type_id')==91){ ?>                         
                        <li>
                            <a><span>BREAK BULK PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a
                                        href="<?php echo site_url('breakbulk/BBDashBoardController/bbCargoLocationSearchForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Cargo Location
                                        Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER<br>
                                    <span style="padding-right:30px;"></span>(EDO)</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Pending EDO
                                        List<br>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Approved EDO
                                        List<br>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>EDO
                                        Verification Report<br>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry
                                        Upload<br>
                                        <span style="padding-right:30px;"></span>Status Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('breakbulk/BBIGMController/BBIgmView/BB') ?>"
                                        ><i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View Vessel
                                        Declaration & <br>
                                        <span style="padding-right:30px;"></span>Stowage Plan Option
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('breakbulk/BBIGMController/BBIGMSubALLDetails')?>" ><i class="fa fa-mail-forward (alias)"></i> View IGM Sub
                                        Details <br>
                                    </a>
                                </li>
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Download Break
                                        Bulk <br>
                                        <span style="padding-right:30px;"></span>Prevised Template (Excel file)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a
                                        href="<?php echo site_url('breakbulk/BBReportController/CargoVesselDischargeSummaryView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Discharge Summary</a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/CargoVesselSingleBLWiseIGMView') ?>"><i class="fa fa-mail-forward (alias)"></i>Single BL Wise IGM </a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/BBIGMCheckView') ?>"><i class="fa fa-mail-forward (alias)"></i>BB IGM Check</a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/CargoManifestReportView') ?>"><i class="fa fa-mail-forward (alias)"></i>Cargo Manifest Full Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li> 
                                    <div> 
                                        <a href="<?php echo site_url('breakbulk/BBVesselOperationController/BBVesselDeclarationListView/BB') ?>" onclick="getsession()">
                                            <i class="fa fa-mail-forward (alias)"></i>View Vessel Declaration  
                                                <span class="badge bg-secondary"><i id="noti_number">0</i></span> 
                                        </a>
                                    </div>
                                </li> 

                                <li> 
                                    <div> 
                                   
                                        <a href="<?php echo site_url('breakbulk/BBVesselOperationController/BerthWiseReport') ?>">
                                            <i class="fa fa-mail-forward (alias)"></i>Berth Wise Vessel report 
                                        </a>
                                    </div>
                                </li> 
                                    
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Import</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseCargoOnboardView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Onboard Cargo Report</a>
                                        </li>
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseCargoDischargeView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Cargo Discharge</a></li>
                                        <li><a href="<?php echo site_url('breakbulk/BBReportController/PerformanceReportView') ?>"><i class="fa fa-mail-forward (alias)"></i>24 Hours <br><span
                                                    style="padding-right:30px;"></span>Performance Report</a></li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Export</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Cargo Export <br><span
                                                    style="padding-right:30px;"></span>Loading list</a></li>
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Rotaion wise <br><span
                                                    style="padding-right:30px;"></span>Cargo Export List </a></li>

                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}?>
                        <!-- Break Bulk (BB) CPA Panel end ASIF AND ANIT 26/2/23-->
                        <!-- Break Bulk  (BB) Shipping Agent Panel start ASIF AND ANIT 26/2/23 -->
                        <?php
						if($this->session->userdata('Control_Panel')==93 && $this->session->userdata('org_Type_id')==93)
						{
						?>
                        <li>
                            <a><span>BREAK BULK PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a
                                        href="<?php echo site_url('breakbulk/BBDashBoardController/bbCargoLocationSearchForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Cargo Location
                                        Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('breakbulk/BBIGMController/BBIgmView/BB') ?>"
                                        ><i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View Vessel
                                        Declaration & <br>
                                        <span style="padding-right:30px;"></span>Stowage Plan Option
                                    </a>
                                </li> -->
                                <!-- <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i> View IGM Sub
                                        Details <br>
                                    </a>
                                </li> -->
                                <li>
                                    <a href="" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Cargo Pre-advise Export
                                        <br>
                                        <span style="padding-right:30px;"></span>Template Upload
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a
                                        href="<?php echo site_url('breakbulk/BBReportController/CargoVesselDischargeSummaryView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Discharge Summary</a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/CargoVesselSingleBLWiseIGMView') ?>"><i class="fa fa-mail-forward (alias)"></i>Single BL Wise IGM </a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/BBIGMCheckView') ?>"><i class="fa fa-mail-forward (alias)"></i>BB IGM Check</a></li>
                                <li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li> 
                                    <div> 
                                        <a href="">
                                            <i class="fa fa-mail-forward (alias)"></i>Report </span> 
                                        </a>
                                    </div>
                                </li>   
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <!-- <li><a
                                        href="<?php echo site_url('breakbulk/BBReportController/CargoVesselDischargeSummaryView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Discharge Summary</a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/CargoVesselSingleBLWiseIGMView') ?>"><i class="fa fa-mail-forward (alias)"></i>Single BL Wise IGM </a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/BBIGMCheckView') ?>"><i class="fa fa-mail-forward (alias)"></i>BB IGM Check</a></li> -->
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Import</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseOnboardCargoView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Onboard Cargo Report</a>
                                        </li>
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseCargoDischargeView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Cargo Discharge</a></li>
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>24 Hours <br><span
                                                    style="padding-right:30px;"></span>Performance Report</a></li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Export</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Cargo Export <br><span
                                                    style="padding-right:30px;"></span>Loading list</a></li>
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Rotaion wise <br><span
                                                    style="padding-right:30px;"></span>Cargo Export List </a></li>

                                    </ul>
                                </li>
                                <!-- <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Vessel Information </a></li> -->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Break Bulk  (BB) Shipping Agent Panel end -->
                        <!-- Break Bulk  (BB)  Berth Operator Panel start-->
                        <?php
						if($this->session->userdata('Control_Panel')==95 && $this->session->userdata('org_Type_id')==95){
						?>
                        <li>
                            <a><span>BREAK BULK PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a
                                        href="<?php echo site_url('breakbulk/BBDashBoardController/bbCargoLocationSearchForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Cargo Location
                                        Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('breakbulk/BBIGMController/BBIgmView/BB') ?>"><i class="fa fa-mail-forward (alias)"></i>View IGM
                                        Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORT</span></a>
                            <ul class="nav nav-children">
                                <!-- <li><a
                                        href="<?php echo site_url('breakbulk/BBReportController/CargoVesselDischargeSummaryView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Discharge Summary</a></li>
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/CargoVesselSingleBLWiseIGMView') ?>"><i class="fa fa-mail-forward (alias)"></i>Single BL Wise IGM </a></li> -->
                                <li><a href="<?php echo site_url('breakbulk/BBReportController/BBIGMCheckView') ?>"><i class="fa fa-mail-forward (alias)"></i>BB IGM Check</a></li>
                                <li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL OPERATION</span></a>
                            <ul class="nav nav-children">
                                <!-- <li> 
                                    <div> 
                                        <a href="<?php echo site_url('breakbulk/BBVesselOperationController/BBVesselDeclarationListView/BB') ?>">
                                            <i class="fa fa-mail-forward (alias)"></i>View Vessel Declaration  
                                                <span class="badge bg-secondary">4</span> 
                                        </a>
                                    </div>
                                </li>  -->

                                <li> 
                                    <div> 
                                        <a href="<?php echo site_url('breakbulk/BBVesselOperationController/BerthWiseReport') ?>">
                                            <i class="fa fa-mail-forward (alias)"></i>Berth Wise Vessel report 
                                        </a>
                                    </div>
                                </li> 
                                    
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Import</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseOnboardCargoView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Onboard Cargo Report</a>
                                        </li>
                                        <li><a href="<?php echo site_url('breakbulk/BBImportReportController/BBImportRotationWiseCargoDischargeView') ?>"><i class="fa fa-mail-forward (alias)"></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Cargo Discharge</a></li>
                                        <!-- <li><a href=""><i class="fa fa-mail-forward (alias)"></i>24 Hours <br><span
                                                    style="padding-right:30px;"></span>Performance Report</a></li> -->
                                        <li><a href="<?php echo site_url('breakbulk/BBReportController/TallySheetForImportReportPdf') ?>"><i class="fa fa-mail-forward (alias)"></i>Tally Sheet <br><span
                                                    style="padding-right:30px;"></span>For Import </a></li>
                                                    <li><a href=""><i class="fa fa-mail-forward (alias)"></i>RL <br><span
                                                    style="padding-right:30px;"></span>For Import Cargo</a></li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>Export</span></a>
                                    <ul class="nav nav-children">
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Cargo Export <br><span
                                                    style="padding-right:30px;"></span>Loading list</a></li>
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Rotaion wise <br><span
                                                    style="padding-right:30px;"></span>Cargo Export List </a></li>
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Tally Sheet <br><span
                                                    style="padding-right:30px;"></span>For Export </a></li>

                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Break Bulk  (BB)  Berth Operator Panel end -->
                        <!--  Marin Department Panel Satrt-->
                        <?php
						if($this->session->userdata('Control_Panel')==101 && $this->session->userdata('org_Type_id')==101){
						?>
                        <li>
                            <a><span> Marin PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                        <li><a href="<?php echo site_url('pilotageApp/ReportController/PilotWiseReportView') ?>"><i class="fa fa-mail-forward (alias)"></i>Pilot Wise Report
                                                </a>
                                        </li>
                                        <li><a href="<?php echo site_url('pilotageApp/ReportController/DateWiseVSLHandledView') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Handled List
                                                </a>
                                        </li>
                                        <!-- <li><a href=""></i>Import Rotation wise
                                                <br><span style="padding-right:30px;"></span>Cargo Discharge</a></li>
                                       
                                        <li><a href=""><i class="fa fa-mail-forward (alias)"></i>Tally Sheet <br><span
                                                    style="padding-right:30px;"></span>For Import </a></li>
                                                    <li><a href=""><i class="fa fa-mail-forward (alias)"></i>RL <br><span
                                                    style="padding-right:30px;"></span>For Import Cargo</a></li> -->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!--  Marin Department Panel End-->
                        <!--Bank Panel Start-->
                        <?php
						if($this->session->userdata('Control_Panel')==13 && $this->session->userdata('org_Type_id')==13)
						{
						?>
                        <li class="fa fa-home"><a><span>BANK PANEL</span></a></li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>Bill List</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Consignee Bill List</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a
                                        href="<?php echo site_url('ShedBillController/shedBillHeadWiseSummaryRptForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Head Wise Summary Report</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/shedBillSummaryRptForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Summary Report </a></li>
                            </ul>
                        </li>
                        <?php 
						} 
						?>
                        <!--Bank Panel END-->

                        <!-- Berth Panel Start -->
                        <?php 
						if($this->session->userdata('Control_Panel')==30 && $this->session->userdata('Control_Panel')!=22 and $this->session->userdata('Control_Panel')!=57 ) 
						{
						?>
                        <li>
                            <a><span>BERTH / TERMINAL OPERATOR PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
						if($lid =='devberth' or $lid =='SAIFBO')
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SPECIAL REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/reportSpecial') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
						}
						if($lid =='SHAHIN' or $lid =='IBRAHIM2'or $lid =='devberth' )
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SHAHIN SPECIAL REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/updateVslForExpCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Update Vessel for<br>
                                        <span style="padding-right:30px;"></span>Export Containers
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Loading Export apps
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myEquipmentHandlingHistoryTimewise') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Equipment Handling<br>
                                        <span style="padding-right:30px;"></span>Performance(RTG) Timewise
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myEquipmentHandlingHistory') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Handling <br><span
                                            style="padding-right:30px;"></span>Performance(RTG)</a>
                                </li>
                            </ul>
                        </li>
                        <?php
						}
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL TALLY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/tallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Tally Sheet Entry
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/tallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TALLY LIST</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM Break Bulk
                                        <br /><span style="padding-right:30px;"></span>Information</a></li>
                                <?php
								if($lid =='devberth' or $lid =='SAIFBO')
								{
								?>
                                <li><a href="<?php echo site_url('IgmViewController/updateManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>
                                <?php										
										$str = "select count(id) as cnt from edi_stow_info where file_status=0";
										
										$result = mysqli_query($con_cchaportdb,$str);
										$row = mysqli_fetch_object($result);
										$badge = $row->cnt;
										//echo "TEST : ".$badge;
									?>
                                <?php
								}
								?>

                                <?php										
									$str = "select count(id) as cnt from edi_stow_info where file_status=0";
									
									$result = mysqli_query($con_cchaportdb,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;
									//echo "TEST : ".$badge;
								?>
                                <li class="badge1" data-badge="<?php echo $badge;?>">
                                    <a href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Today's EDI Declaration
                                    </a>
                                </li>

                                <li><a href="<?php echo site_url('Report/qgcContForwardForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>QGC Conainer Forwarding</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/containerEventHistory') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Event History</a></li>
                                <li><a href="<?php echo site_url('Report/myBirthIGMList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View Berth Operator Report</a></li>
                                <li><a href="<?php echo site_url('Report/operatorWiseEquipmentHandling') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Operator's RTG Handling <br /><span
                                            style="padding-right:30px;"></span>Performance</a></li>
                                <li><a href="<?php echo site_url('Report/operatorWiseEquipmentHandlingQGC') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Operator's QGC Handling <br /><span
                                            style="padding-right:30px;"></span>Performance</a></li>
                                <li><a href="<?php echo site_url('Report/operatorWiseSCHandling') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Operator's SC Handling <br /><span
                                            style="padding-right:30px;"></span>Performance</a></li>
                                <li><a href="<?php echo site_url('Report/myEquipmentHandlingHistory') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Handling <br /><span
                                            style="padding-right:30px;"></span>Performance(RTG)</a></li>
                                <li><a href="<?php echo site_url('Report/myEquipmentLoginLogout') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Handling (RTG <br /><span
                                            style="padding-right:30px;"></span>LOGIN/LOGOUT)</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myImportContainerLoadingReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Container Discharge <br /><span
                                            style="padding-right:30px;"></span>Details (Excel Last 24 Hours)
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('Report/myImportContainerDischargeReportlast24hours') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Container Discharge <br /><span
                                            style="padding-right:30px;"></span>Report Summary(Last 24h)
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report/myImportContainerDischargeReport') ?>">IMPORT CONTAINER DISCHARGE REPORT( BALANCE AND DISCHARGE)</a></li-->

                                <li>
                                    <a href="<?php echo site_url('Report/myImportSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Import<br>
                                        <span style="padding-right:30px;"></span>Summary(Berth Operator)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery <br /><span
                                            style="padding-right:30px;"></span>Empty Details
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Container Discharge<br>
                                        <span style="padding-right:30px;"></span>and Balance Report
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report/myRequstEmtyContainerReport') ?>">ASSAIGNMENT/DELIVERY EMPTY DETAILS</a></li>
								<li><a href="<?php echo site_url('Report/yardWiseEmtyContainerReport') ?>">YARD WISE ASSAIGNMENT/DELIVERY EMPTY DETAILS</a></li>
								<li><a href="<?php echo site_url('Report/myRequstAssignmentEmtyContainerReport') ?>">ASSAIGNMENT/DELIVERY EMPTY SUMMARY </a></li-->

                                <li>
                                    <a href="<?php echo site_url('Report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li><a href="<?php echo site_url('Report/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary <br /><span
                                            style="padding-right:30px;"></span>List</a></li>
                                <li><a href="<?php echo site_url('Report/myBlockedContainerAllView') ?>"
                                        target="_blank"><i class="fa fa-mail-forward (alias)"></i>Blocked Container
                                        Report</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/impBayView') ?>"><span class="blink_me"><i
                                                class="fa fa-mail-forward (alias)"></i>Import Container
                                            Bay<br>View</span></a></li>
                                <li><a href="<?php echo site_url('Report/containerDischargeAppsForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Discharge (apps)</a></li>
                                <li><a href="<?php echo site_url('Report/removal_list_form/overflow') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Removal List of Overflow Yard</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/removal_list_form_lcl/overflow') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Removal List of Overflow
                                        Yard<br><span style="padding-right:30px;"></span>LCL</a></li>
                            </ul>
                        </li>
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DG INFORMATION</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('report/dgDischargeSummary') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
										<span style="padding-right:30px;"></span>Summary List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('report/dgDischargeList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
										<span style="padding-right:30px;"></span>List by Rotation
									</a>
								</li>
							</ul>
						</li-->
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HANDLING REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/workDone24hrsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hrs. Container<br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/exportEquipmentHandlingHistory') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Equipment Handling <br><span
                                            style="padding-right:30px;"></span>Performance(RTG)
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/viewVesselListStatus') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Vessel List With Status</a></li>
                                <li><a href="<?php echo site_url('Report/loadedContainerList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Loaded Container <br /><span
                                            style="padding-right:30px;"></span>List </a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWisePreadviceloadedContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Loaded Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dateAndRoationWisePreAdviseCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Date and Rotation<br>
                                        <span style="padding-right:30px;"></span>Wise Pre-Advised Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/destinationloadedContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Destination Wise MLO<br>
                                        <span style="padding-right:30px;"></span>Loaded Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ExportContainerTobeLoadingReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container to<br>
                                        <span style="padding-right:30px;"></span>be Loaded List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export Container Loading(Excel)</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportExcelUploadSampleForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Excel Upload<br>
                                        <span style="padding-right:30px;"></span>Sample (With Loaded Data)
                                    </a>
                                </li>
								<!--this url commented by Awal-->
                                <li><a href="<?php echo site_url('UploadExcel/exportExcelUpload') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Upload Export Container<br /><span
                                            style="padding-right:30px;"></span>(Excel File)</a>
								</li>
                                <li><a href="<?php echo site_url('Report/myExportExcelUploadSample') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Excel Uploaded <br /><span
                                            style="padding-right:30px;"></span>Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Comments by Shipping Section<br>
                                        <span style="padding-right:30px;"></span>on Export Vessel
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/loadedFreightKindContainerList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List <br /><span
                                            style="padding-right:30px;"></span>(Load & Empty)</a></li>
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
                                <li><a
                                        href="<?php echo site_url('Report/berthOperatorExportContainerHandlingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Rotation Wise Export Container</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportContainerNotFoundReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export Container not Found
                                        <br /><span style="padding-right:30px;"></span>Report</a></li>
                                <li><a href="<?php echo site_url('Report/myRotationWiseContInfoForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Rotation Wise Container <br /><span
                                            style="padding-right:30px;"></span>Information</a></li>
                                <li><a href="<?php echo site_url('Report/myExportContainerBlockReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export Container Block Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/exportCopinoView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export COPINO</a></li>
                                <?php $path= 'http://'.$_SERVER['SERVER_ADDR'].'/pcs/resources/download/';?>

                                <!--li><a href="<?php echo site_url('UploadExcel') ?>">EXCEL UPLOAD FOR COPINO</a></li-->
                                <li><a href="<?php echo site_url('UploadExcel/bayView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export Cnotainer Bay View</a></li>
                                <li><a href="<?php echo site_url('Report/blankBayForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Export Blank Bay View</a></li>
                                <?php 
									$appQry="SELECT  app_name,version_code,REPLACE(version_name,'.','_') as version_name FROM ctmsmis.mis_exp_app_version ORDER BY id desc limit 1";
									$rtnAppQry= mysqli_query($con_sparcsn4,$appQry);
									$row=mysqli_fetch_object($rtnAppQry);
								?>
                                <!--li><a href="<?php echo $path.'export_loading_app.apk';?>"><font color="blue" size="2"><b>EXPORT LOADING APP</b></font></a></li-->
                                <li><a href="<?php echo $path.$row->app_name."V".$row->version_name.".apk";?>">
                                        <font color="blue" size="2"><i class="fa fa-mail-forward (alias)"></i><b>Export
                                                Loading app</b></font>
                                    </a></li>

                                <li><a href="<?php echo site_url('Report/podListView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>POD List</a></li>
                                <li><a href="<?php echo site_url('Report/isoCodeListView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ISO Code List</a></li>
                                <li><a href="<?php echo site_url('Report/yardListView') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Yard List</a></li>
                            </ul>
                        </li>
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>QGC/MHC </span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Vessel/holdShiftingContainerForm') ?>" ><i class="fa fa-mail-forward (alias)"></i>Hold Shifting Container Form</a></li>
								<li><a href="<?php echo site_url('Vessel/qgcContForwardNewForm') ?>" ><i class="fa fa-mail-forward (alias)"></i>QGC/MHC Handled Report <br><span style="padding-right:25px;"></span> & Forwarding to CPA Billing</a></li>
							</ul>
						</li>		

                        <?php
						if($lid=="devberth" or $lid=="saif")
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PANGAON</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/pangoanContUpload') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for PANGAON</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertPanContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert PANGAON Containers</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <?php
						if($lid=="devberth" or $lid=="MHCLBOO")
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>MH CHOWDHURY REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/contDetailInfoByRotWithXlsDownloadForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Details by Rotation<br />
                                        <span style="padding-right:30px;"></span> HTML and Excel View
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/contListAllByRotationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container List (All)</a></li>
                                <li><a href="<?php echo site_url('Report/contListDischargeByRotationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container List (Discharge)</a></li>
                                <li><a href="<?php echo site_url('Report/contListAssignmentByRotationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container List (Assignment)</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/contListOffDockByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container List <br />
                                        <span style="padding-right:30px;"></span>(Offdock Delivery)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/contListReferByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container List (REEFER)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/contListEmptyGateOutByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container List(Empty Gate Out)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/contListStripingByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container List (Stripping)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContListAllByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container (All)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li class='has-sub'><a href='#'><span>MH CHOWDHURY EXPORT</span></a>
							<ul>
								<li><a href="<?php echo site_url('Report/exportContListDownload') ?>">CONTAINER EXPORT DOWNLOAD</a></li>
								<li><a href="<?php echo site_url('Report/exportContListAllByRotationForm') ?>">CONTAINER EXPORT (ALL)</a></li>
							</ul>
						</li-->
                        <?php
						}
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Berth Panel End -->

                        <!-- C&F Panel Start -->
                        <?php if($this->session->userdata('Control_Panel')==2 && $this->session->userdata('org_Type_id')==2 ) 
						{
						?>
                        <!-- <li>
							<a><span>C&F PANEL</span></a>						
						</li> -->

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY FORM</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/officeCodeUpdaterForm') ?>">B/E Entry Form</a></li-->
                                <!--li><a href="<?php echo site_url('Report/doReportForm') ?>">Delivery Order Report</a></li>
								<li><a href="<?php echo site_url('Report/onestopCertifySection') ?>">Location \ Certify</a></li-->
                                <!-- <li><a href="<?php echo site_url('ShedBillController/billSearchByVerifyForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Delivery Ooder(DO) Entry</a></li>
								<li><a href="<?php echo site_url('Report/jettySarkarEntryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Jetty Sarkar Entry Form</a></li>	
								<li><a href="<?php echo site_url('Report/jettySarkarListForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Jetty Sarkar List</a></li>	
								<li><a href="<?php echo site_url('Report/truck_entry_form') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Assign for Delivery</a></li>
								<li><a href="<?php echo site_url('Report/billOfEntryInfoForm') ?>"><i class="fa fa-mail-forward (alias)"></i>B/L Entry Information Form</a></li> -->
                                <!-- <li>
									<a href="<?php echo site_url('Report/cnfCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Assignment Request Form
									</a>
								</li> -->
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentRequestList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Request List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentRequestSearchByDateForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Request Search <br>
                                        <span style="padding-right:30px;"></span>By Date
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/assignmentEquipmentForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment & Equipment Form
                                    </a>
                                </li>

                                <!-- <li>
									<a href="<?php echo site_url('igmViewController/shedDeliveryOrderInfoForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>SHED DELIVERY ORDER INFO
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('igmViewController/shedDeliveryOrderList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Shed Delivery Order List
									</a>
								</li> -->


                            </ul>
                        </li>
                        <!-- Start For VCMS -->
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/tallyListForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Tally List</a></li>
								<li><a href="<?php echo site_url('Report/certificationFormHtml') ?>"><i class="fa fa-mail-forward (alias)"></i>Unstuffing Information</a></li>
								<li><a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>"><i class="fa fa-mail-forward (alias)"></i>Assignment/Certify</a></li>
						end for vcms -->
                        <!--li><a href="<?php echo site_url('Report/shedStockBalanceForm') ?>">VIEW CERTIFICATION</a></li-->
                        <!--li><a href="<?php echo site_url('Report/doReportForm') ?>">Delivery Order Report</a></li>
								<li><a href="<?php echo site_url('Report/onestopCertifySection') ?>">Location \ Certify</a></li-->
                        <!-- start for vcms
								<li><a href="<?php echo site_url('Report/verificationListForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Verification List</a></li>
								<li><a href="<?php echo site_url('Report/cnfCertifySection') ?>"><i class="fa fa-mail-forward (alias)"></i>Assignment Request Form</a></li>								
								<li>
									<a href="<?php echo site_url('igmViewController/shedDeliveryOrderInfoForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>SHED DELIVERY ORDER INFO
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('igmViewController/shedDeliveryOrderList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Shed Delivery Order List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/tokenDemandRequestForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Token Demand Request
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/dataSheetForConsigneeBillPDF') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i>Data Sheet for Consignee Bill<br>
										<span style="padding-right:30px;"></span>(PDF)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/xml_conversion') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a>
								</li>
							</ul>
						</li>
						end for vcms -->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Container Location
                                        Search
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>

                                <?php			
									$login_idUcnt = $this->session->userdata('login_id');
									$strucnt = "SELECT COUNT(*) AS ucnt FROM loc_certi_usr WHERE user_id='$login_idUcnt' AND active_st=1";									
									$resultucnt = mysqli_query($con_cchaportdb,$strucnt);
									$rowucnt = mysqli_fetch_object($resultucnt);
									$ucnt = $rowucnt->ucnt;
									//echo "TEST : ".$badge;
									if($ucnt>0)
									{
								?>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <?php
									}
								?>
                            </ul>
                        </li>
                        <?php			
							$login_idKSM = $this->session->userdata('login_id');
							if($login_idKSM=="301113652CF"){
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>B/L INFORMATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/downloadBL') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Download B/L Informatin
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php			
							}
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELETRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/applicationForEDObyrotationBL') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Application
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CERTIFY SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Assignment/Certify</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>RELEASE ORDER</span></a>
							
							
							
							
                            <ul class="nav nav-children">
                                <?php
								if($this->session->userdata('login_id') == "devcf" or $this->session->userdata('login_id') == "testcf")
								{
								?>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roSubmitForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order Submission
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/releaseOrderViewForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order View
                                    </a>
                                </li>
								
								
								
								
								
                                <?php
								}
								else
								{
								?>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order Submission
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order List
                                    </a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('Report/cnfEquipmentDemandViewForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cnf Equipment Demand
                                    </a>
                                </li>
                                <?php
								}
								?>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE PASS GENERATION</span></a>
                            <ul class="nav nav-children">
                                <!-- start for vcms
								<li><a href="<?php echo site_url('Report/cf_agent_assignment_Form') ?>"><i class="fa fa-mail-forward (alias)"></i>ALL ASSIGNMENT DETAILS</a></li>
						end for vcms -->
                                <!--li><a href="<?php echo site_url('Report/truck_entry_list') ?>">ASSIGNED TRUCK LIST</a></li-->
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TRUCK ENTRY FCL
                                    </a>
                                </li>
                                <!-- <li>
									<a href="<?php echo site_url('ShedBillController/cnfTruckEntryLCL') ?>">
										<i class="fa fa-mail-forward (alias)"></i>TRUCK ENTRY LCL									
									</a>
								</li>  -->
                                <li><a href="<?php echo site_url('Report/lcldeliveryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL DELIVERY LIST</a></li>

                                <li>
                                    <a href="<?php echo site_url('LCL/lcl_ntsdeliveryList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL DELIVERY LIST(nts)
                                    </a>
                                </li>

                                <li><a href="<?php echo site_url('ShedBillController/truckEntranceApplicationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PRINT GATE PASS</a></li>
                                <!--li><a href="<?php echo site_url('Report/assignmentListForm_2') ?>"><i class="fa fa-mail-forward (alias)"></i>ASSIGNMENT LIST (GATE PASS)</a></li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>YARD OPERATION</span></a>
                            <ul class="nav nav-children">
                                <!--li>
									<a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i class="fa fa-mail-forward (alias)"></i>LOADING PROCESS</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/cf') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>C&F CONFIRMATION <br />
                                        <span style="padding-right:30px;"></span>PROCESS
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DISPUTE LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class='nav-parent'>
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/truckReport/gateIn') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TRUCK TO GATE IN</a></li>
                                <li><a href="<?php echo site_url('Report/truckReport/insidePort') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>WORKING INSIDE THE PORT</a></li>
                                <li><a href="<?php echo site_url('Report/truckReport/gateOut') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>TRUCK GATE OUT</a></li>
                            </ul>
                        </li>

                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>BILL SECTION</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Consignee Bill List</a></li>		
							</ul>
						</li-->

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                                <!-- <li><a href="<?php echo site_url('sendEmailController/sendMail') ?>"><i class="fa fa-mail-forward (alias)"></i>Send Mail</a></li> -->
                            </ul>
                        </li>
                        <!--li class='has-sub'><a href='#'><span>ENTRY FORM</span></a>
							<ul>
								<li><a href="<?php echo site_url('Report/truck_entry_form') ?>">TRUCK ENTRY FORM</a></li>
							</ul>
						</li-->
                        <?php 
						}	
						?>
                        <!-- C&F Panel end -->

                        <!-- Gate Panel start -->
                        <?php 
						if($this->session->userdata('Control_Panel')==61 && $this->session->userdata('org_Type_id')==61)
						{
						?>
                        <li>
                            <a><span>GATE USER PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE INSPECTOR</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('GateController/gateOut') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Entry</a></li>
                                <li><a href="<?php echo site_url('GateController/gateConfirmation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Confirmation</a></li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('GateController/gateReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Report</a></li>
                                <li><a href="<?php echo site_url('GateController/gateRegisterReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Register Report</a></li>
                                <li><a href="<?php echo site_url('GateController/containerRegisterInRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Inward & Outward Container
                                        Register</a></li>
                                <li><a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward Container
                                        Register</a></li>
                                <li><a href="<?php echo site_url('GateController/containerRegisterInRegister_ocr')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Inward & Outward OCR Container
                                        Register</a></li>

                            </ul>
                        </li>
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>GATE SECURITY</span></a>
							<ul class="nav nav-children">
								<!--
									<li><a href="<?php echo site_url('Report/wirehouseReportForm') ?>">WAREHOUSE REPORT (IMPORT) </a></li>
									<li><a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">LCL Assignment \ Certify</a></li>
								>				
							</ul>
						</li-->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Gate Panel end -->


                        <?php 
						// if($this->session->userdata('Control_Panel')==12 and $this->session->userdata('section')==9 ) 
						// {
						?>
                        <!--li>
							<a><span>CPA PANEL</span></a>
							
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>-------</span></a>
							<ul class="nav nav-children">
								<li>
									
								</li>
							</ul>
						</li-->
                        <?php 
						//}	
						?>



                        <?php if($this->session->userdata('Control_Panel')==3 && $this->session->userdata('org_Type_id')==3)
						{
						?>
                        <li>
                            <a><span>CUSTOM PANEL</span></a>

                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CUSTOM REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vessel_wise_discharge_loading') ?>"
                                        target="_blank"><i class="fa fa-mail-forward (alias)"></i>Vessel Wise Discharge,
                                        Loading</a></li>
                                <li><a href="<?php echo site_url('Report/yard_wise_delivery_and_receive') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Yard Wise Delivery & Receiving</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/EquipmentWiseDeliveryReceving') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Delivery & Receiving</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT LIST</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/cf_agent_assignment_Form') ?>"><i class="fa fa-mail-forward (alias)"></i>ALL ASSIGNMENT DETAILS</a></li-->
                                <!--li><a href="<?php echo site_url('Report/truck_entry_list') ?>">ASSIGNED TRUCK LIST</a></li-->
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ASSIGNMENT LIST
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('ShedBillController/truckEntranceApplicationForm') ?>"><i class="fa fa-mail-forward (alias)"></i>TRUCK DETAILS</a></li-->
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- ICD Panel Start -->
                        <?php 
						if($this->session->userdata('Control_Panel')==69 && $this->session->userdata('org_Type_id')==69)
						{
						?>
                        <li>
                            <a><span>ICD APP PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/cirForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Interchange Receipt</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/date_wise_icd_report') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Date Wise ICD Report</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- ICD Panel End -->

                        <?php 	
						if($this->session->userdata('Control_Panel')==1 && $this->session->userdata('org_Type_id')==1)
						{
						?>
                        <li>
                            <a><span>MLO PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/orgLogoUploadForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization Logo Upload
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <!-- <li>
									<a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
									</a>
								</li> -->

                                <li>
                                    <a href="<?php echo site_url('EDOController/pendingEDOapplication') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING APPLICATION FOR <br /><span
                                            style="padding-right:30px;"></span>EDO
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>UPLOADED EDO LIST
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationforEdoWithoutcnf') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>EDO Upload without C&F <br><span
                                            style="padding-right:30px;"></span>Application</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoContainerReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO CONTAINER REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>STUFFING REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/offDockContSearchForm') ?>">PRE ADVISED CONTAINER SEARCH </a></li-->
                                <!--li><a href="<?php echo site_url('ShedBillController/shedBillHeadWiseSummaryRptForm') ?>">HEAD WISE SUMMARY REPORT</a></li-->
                                <li><a href="<?php echo site_url('Report/dischargeListForMLO') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying<br>
                                        <span style="padding-right:30px;"></span>Container Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
                                        <span style="padding-right:30px;"></span>apps Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListImportStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Import<br>
                                        <span style="padding-right:30px;"></span>Apps Loading Report
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('ShedBillController/shedBillSummaryRptForm') ?>">SUMMARY REPORT </a></li-->
                                <!--li><a href="<?php echo site_url('Report/myRequstEmtyContainerReport1') ?>">Test Pdf </a></li-->
                                <!--li><a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">LAST 24 HOURS STUFFING CONTAINER LIST</a></li-->
                                <?php	
								// $strContBill = "";
								// if($lid=="devmlo")
									// $strContBill = "select count(draft_id) as cnt from ctmsmis.mis_billing where date(billing_date) = date(now())";
								// else
									// $strContBill = "SELECT COUNT(draft_id) AS cnt FROM ctmsmis.mis_billing WHERE DATE(billing_date) = DATE(NOW()) AND mlo_code='$lid'";
								
								// $resultContBill = mysqli_query($con_sparcsn4,$strContBill);
								// $rowContBill = mysqli_fetch_object($resultContBill);
								// $badgeContBill = $rowContBill->cnt;		
							?>
                                <!--li class="badge1" data-badge="<?php echo $badgeContBill;?>"><a href="<?php echo site_url('Bill/containerBillList/1') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Bill List</a></li-->
                                <!--li><a href="<?php echo site_url('Bill/containerBillListVersion/1') ?>">CONTAINER BILL LIST (VERSION)</a></li-->
                            </ul>
                        </li>

                        <!-- From Agent Panel - start -->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM General Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedDeliveryOrderInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>SHED DELIVERY ORDER INFO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedDeliveryOrderList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Shed Delivery Order List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY FORM</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/officeCodeUpdaterForm/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>B/E Entry Form
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/sh_agent_assignment_Form') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment Details
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <li><a href="<?php echo site_url('Report/myPortCodeList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Port Code List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVessel') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Agent Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myOffdocWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVesselInfo') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Transit Ways <br /><span
                                            style="padding-right:30px;"></span> Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWisePreadviceloadedContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span> Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Loaded Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge and <br /><span
                                            style="padding-right:30px;"></span> Balance Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/doReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery Order Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContainerGateInForm') ?>"><i
                                            class="fa fa-mail-forward (alias)">
                                        </i>Export Container Gate <br /><span style="padding-right:30px;"></span> in
                                        List
                                    </a>
                                </li>
                                <!-- From MLO Panel --->
                                <li>
                                    <a href="<?php echo site_url('Report/vesselBillList/1') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">
                                <?php
									//include("dbConection.php");
									$str = "SELECT COUNT(DISTINCT rotation) AS cnt
									FROM ctmsmis.mis_exp_unit_preadv_req
									WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
									$result = mysqli_query($con_sparcsn4,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;
									//echo $badge;
								?>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation
                                        <span class="badge1" data-badge="<?php echo $badge;?>"
                                            style="padding-left:20px;"></span>
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('UploadExcel') ?>"><i class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO</a></li> 
								<li><a href="<?php echo site_url('UploadExcel/upload_copern_copino_form') ?>"><i  class="fa fa-mail-forward (alias)"></i> Upload for COPARN & COPINO</a></li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/ediDownloadSample') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Download Excel Sample <br /><span
                                            style="padding-right:30px;"></span> for EDI Download
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/EDISearch'."/"."EDISearch") ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDI Converter
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ConvertedEDISearch') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Converted Vessel List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- From Agent Panel - end -->

                        <li class="nav-parent <?php if($menu=="bill"){ ?> nav-expanded nav-active <?php } ?>">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>BILL</span></a>
                            <ul class="nav nav-children">
                                <?php
								// $agentCode = $this->session->userdata('agentCode');
								// $sql_vslBillCnt = "";
								// if($lid=="devmlo")
								// {
									// $sql_vslBillCnt = "SELECT COUNT(*) AS cnt
									// FROM ctmsmis.mis_vsl_billing_detail_test
									// WHERE DATE(billing_date)=DATE(NOW())";
								// }
								// else
								// {
									// $sql_vslBillCnt = "SELECT COUNT(*) AS cnt
									// FROM ctmsmis.mis_vsl_billing_detail_test
									// WHERE DATE(billing_date)=DATE(NOW()) AND agent_code='$agentCode'";									
								// }
								// $resultVslBill = mysqli_query($con_sparcsn4,$sql_vslBillCnt);
								// $rowVslBill = mysqli_fetch_object($resultVslBill);
								// $badgeVslBill = $rowVslBill->cnt;	
								?>
                                <!--li class="badge1" data-badge="<?php echo $badgeVslBill;?>"-->
                                <li>
                                    <a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
                                    </a>
                                </li>
                                <?php	
									// $strContBill = "";
									// if($lid=="devmlo")
									// {
										// $strContBill = "SELECT COUNT(draft_id) AS cnt
										// FROM ctmsmis.mis_billing
										// WHERE DATE(billing_date) = DATE(NOW())";
									// }
									// else
									// {
										// $strContBill = "SELECT COUNT(draft_id) AS cnt
										// FROM ctmsmis.mis_billing
										// WHERE DATE(billing_date) = DATE(NOW()) AND mlo_code='$lid'";
									// }
									// $resultContBill = mysqli_query($con_sparcsn4,$strContBill);
									// $rowContBill = mysqli_fetch_object($resultContBill);
									// $badgeContBill = $rowContBill->cnt;		
								?>
                                <!--li class="badge1" data-badge="<?php echo $badgeContBill;?>"-->
                                <li>
                                    <a href="<?php echo site_url('Bill/containerBillList/1') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Bill List</a>
                                </li>
                                <li class="<?php if($sub_menu=="waterDemandForm"){?>nav-active<?php }?>">
                                    <a href="<?php echo site_url('Vessel/waterDemandForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Demand Form</a>
                                </li>
                                <li class="<?php if($sub_menu=="waterDemandList"){?>nav-active<?php }?>">
                                    <a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
                                </li>

                                <!--li class="<?php if($sub_menu=="hotWorkDemandForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/hotWorkDemandForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Hot Work Demand Form</a>
								</li-->

                                <!--li class="<?php if($sub_menu=="hotWorkDemandListForMlo"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/hotWorkDemandListForMlo') ?>"><i class="fa fa-mail-forward (alias)"></i>Hot Work Demand List</a>
								</li-->

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>




                        <?php 
						if($this->session->userdata('Control_Panel')==62 && $this->session->userdata('org_Type_id')==62)
						{
						?>
                        <li>
                            <a><span>ONE STOP PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>1. LCL ASSIGNMENT SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>1.1 LCL Assignment Entry Form</a>
                                </li>
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>1.2 LCL Assignment Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.3 ASSIGNMENT LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lcldeliveryList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.4 LCL Delivery Indent Entry
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-parent'>
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>1.1 IGM INFORMATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('igmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK">View IGM General Information</a></li>
                                <!--li><a href="<?php echo site_url('igmViewController/viewIgmGeneral/BB') ?>" target="_BLANK">View IGM Break Bulk Information</a></li-->
                                <li><a href="<?php echo site_url('igmViewController/viewIgmGeneralForBBshed/BB') ?>"
                                        target="_BLANK">View IGM BB Information</a></li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedDeliveryOrderInfoForm') ?>">
                                        SHED DELIVERY ORDER INFO
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/shedDeliveryOrderList') ?>">Shed
                                        Delivery Order List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>2. UNSTUFFING POINT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>2.1 LCL Assignment Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/tallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>2.2 Tally Entry With<br>
                                        <span style="padding-right:30px;"></span>IGM Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/pShedTallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>2.4 Tally Entry With IGM<br>
                                        <span style="padding-right:30px;"></span>Information(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/pShedTallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Tally List(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/dateWisePShedTallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Date Wise Tally Receive<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('PShedController/dateWisePShedTallyDeliveryReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Date Wise Tally Delivery<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/dateWisePShedTallyBalanceReportForm') ?>"
                                        target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Tally Balance<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/dateWisePShedRemovalListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Removal List(Chemical Shed)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELETRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>3. COMMUNITY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/certificationFormHtml') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>3.1 Unstuffing Information</a></li>
                                <li><a href="<?php echo site_url('Report/verificationListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>3.2 Verification List</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>4. CERTIFY SECTION</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/certificationFormHtml') ?>">VIEW CERTIFICATION</a></li-->
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                    <iclass="fa fa-mail-forward (alias)"></i>4.1 Assignment/Certify</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/certifyList') ?>">
                                    <iclass="fa fa-mail-forward (alias)"></i>4.2 Certify List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>5. APPRAISEMENT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/appraisementCertifySection') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>5.1 Appraisement Section</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/appraisementList') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>5.2 Appraisement List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/appraisementCertifySectionEdit') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>5.3 Edit Appraisement</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>6. MANIFEST SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('ReleaseOrderController/unitSetUpdate') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>6.1 UNIT ASSIGN</a></li>
                                <li><a href="<?php echo site_url('ReleaseOrderController/unitList') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>6.2 ASSIGNED UNIT LIST</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/deliveryEntryFormByWHClerk') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>6.3 Document Process (Verify)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/verifyList') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>6.4 Verify List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>7. BILL SECTION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/verifyNoList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Verified List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/billGenerationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill Generation</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Consignee Bill List</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/billTariff') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill Tarrif</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/billTariffRate') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill Tarrif Rate</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/billTariffList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Tarrif List</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/rateList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Rate List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/tokenDemandRequestForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Token Demand Request
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dataSheetForConsigneeBillPDF') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Data Sheet for Consignee Bill<br>
                                        <span style="padding-right:30px;"></span>(PDF)
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/dltRcvRqstList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Remove Receive Request</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>8. BANK SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('ShedBillController/shedBillListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>8.1 Consignee Bill List</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>9. COUNTER SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('ReleaseOrderController/releaseOrderForm') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>9.1 Release Order</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/shedDeliveryOrder') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>9.2 Shed Delivery Order</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/cartTicketForm') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>9.3 Cart Ticket</a>
                                </li>
                                <li><a href="<?php echo site_url('ReleaseOrderController/roListCounter') ?>">
                                    <i class="fa fa-mail-forward (alias)"></i>9.4 Release Order List (Counter)</a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report_R/releaseOrderPDF_static') ?>" target="_blank"><i class="fa fa-mail-forward (alias)"></i>Release Order Static</a></li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>10. SHED/YARD DELIVERY
                                    SECTION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/deliverySearchByVerifyNo') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>10.1 W.H/LOCKFAST Entry</a></li>
                                <!--li><a href="<?php echo site_url('Report/xml_conversion') ?>">BILL OF ENTRY LIST</a></li>
								<li><a href="<?php echo site_url('Report/date_wise_be_report') ?>">DATE WISE Bill of Entry REPORT</a></li-->
                                <li><a href="<?php echo site_url('Report/head_delivery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>10.2 Head Delivery</a></li>
                                <li><a href="<?php echo site_url('ShedBillController/billSearchByVerifyForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>10.3 Delivery Order(DO)Entry</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>11. GATE REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/cartTicketForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>11.1 Cart Ticket</a></li>
                                <li><a href="<?php echo site_url('GateController/gateOut') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>11.2 Gate Entry</a></li>
                                <li><a href="<?php echo site_url('GateController/gateConfirmation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>11.3 Gate Confirmation</a></li>
                                <li>
                                    <a href="<?php echo site_url('GateController/gateReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>11.4 Gate Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('GateController/gateRegisterReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>11.5 Gate Register Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/tokenDemandRequestForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Token Demand Request
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dataSheetForConsigneeBillPDF') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Data Sheet for Consignee Bill<br>
                                        <span style="padding-right:30px;"></span>(PDF)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('GateController/datewiseTruckEntryReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Date Wise Online Truck and <br>
                                        <span style="padding-right:30px;"></span>Assignment Entry Report
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>12. YARD OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/emergencyTruckList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Emergency Truck List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loading Process</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DISPUTE LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/tra') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TRAFFIC CONFIRMATION <br>
                                        <span style="padding-right:30px;"></span>PROCESS
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ASSIGNMENT LIST
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>13. CART</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Cart Ticket List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>14. PRO MODULE</span></a>
                            <ul class="nav nav-children">
                                <!-- <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySectionTos') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment/Certify (TOS)
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order List (TOS)
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="<?php //echo site_url('ReleaseOrderController/roListNts') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order List
                                    </a>
                                </li> -->
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/deleteROAllData') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delete R/O All data
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/equipListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Equipment List Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/equipDemandList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Equipment Demand List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/equipDemandSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Equipment Demand Summary
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php 
						}	
						?>

                        <!-- Pilot Panel Start -->
                        <?php if($this->session->userdata('Control_Panel')==64 && $this->session->userdata('org_Type_id')==64)
						{
						?>
                        <li>
                            <a><span>PILOT PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CERTIFICATE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vesselListForPilotage') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Certificate</a></li>
                                <li><a href="<?php echo site_url('Report/vesselListForPangaon') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Certificate for PANGAON</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/outerAnchorageForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Movement Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Pilot Panel End -->

                	<!-- Marine Panel Start -->
						<?php if( $this->session->userdata('org_Type_id')==83)
						{
						?>
						<li>
							<a><span>Marine PANEL</span></a>							
						</li>
						<?php
						if($this->session->userdata('login_id')=="agent1")
						{
						?>						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Vessel/vesselsNotEnteringAgentForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Agent Entry </a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/noEntryAgentList') ?>"><i class="fa fa-mail-forward (alias)"></i>Agent List</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselsNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Entry</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/noEntryVesselList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel List</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/visitedVesselsNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Visit Entry </a>
								</li>								
								<li>
									<a href="<?php echo site_url('Vessel/outerVesselVistInfoList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Visit List </a>
								</li>
								<li>									
									<a href="<?php echo site_url('Vessel/outerAnchorageVslReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>All Incoming & Departure Vessel in CPA</a>
								</li>
							</ul>
						</li>
						<?php
						}
						else if($this->session->userdata('login_id') == "mhossain" || $this->session->userdata('login_id') == "023683")
						{
						?>
						<li class="nav-parent <?php if($menu=="VESSEL"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL</span></a>
							<ul class="nav nav-children">
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>CONTAINER VESSEL</span></a>
                                    <ul class="nav nav-children">
                                        <li class="<?php if($sub_menu=="vesselForwardingbyMarineForm"){?>nav-active<?php }?>">
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward</a>
                                        </li>	
                                        <li class="<?php if($sub_menu=="forwardedVslHistoryN4"){?>nav-active<?php }?>">
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryN4') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</a>
                                        </li>	
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingStatementForm') ?>">
                                                <i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding </br><span style="padding-right:30px;"></span> Statement 
                                            </a>
                                        </li>
                                        <!--li>
                                            <a href="<?php echo site_url('Vessel/marineVslLot_N4') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessels Batch List</a>
                                        </li-->
                                    </ul>
                                </li>
							</ul>
						</li>
						<?php
						}
						else
						{
						?>
						<li class="nav-parent <?php if($menu=="VESSEL"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL</span></a>
							<ul class="nav nav-children">
								<li class="<?php if($sub_menu=="vesselsNotEnteringAgentForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/vesselsNotEnteringAgentForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Agent Entry 
									</a>
								</li>
								<li class="<?php if($sub_menu=="noEntryAgentList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/noEntryAgentList') ?>"><i class="fa fa-mail-forward (alias)"></i>Agent List</a>
								</li>
								<li class="<?php if($sub_menu=="vesselsNotEntering"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/vesselsNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Entry</a>
								</li>
								<li class="<?php if($sub_menu=="noEntryVesselList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/noEntryVesselList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel List</a>
								</li>
								<li class="<?php if($sub_menu=="visitedVesselsNotEntering"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/visitedVesselsNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Visit Entry </a>
								</li>								
								<li class="<?php if($sub_menu=="outerVesselVistInfoList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/outerVesselVistInfoList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Visit List<br><span style="padding-right:30px;"></span>(Pending Approval) </a>
								</li>
                                 <li class="<?php if($sub_menu=="outerVesselVistInfoApprovedList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/outerVesselVistInfoApprovedList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Visit List<br><span style="padding-right:30px;"></span>(Approved) </a>
								 </li>								
								
								<!--li>
									<a href="<?php echo site_url('Vessel/marineVslLot_NotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessels (Not Entering) Batch List</a>
								</li-->
								<li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>NOT ENTERING</span></a>
                                    <ul class="nav nav-children">
                                        <li class="<?php if($sub_menu=="vslNotEnteringForwardList"){?>nav-active<?php }?>">
                                            <a href="<?php echo site_url('Vessel/vslNotEnteringForwardList') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Not Entering</a>
                                        </li>	
                                        <li class="<?php if($sub_menu=="forwardedVslHistoryNotEntering"){?>nav-active<?php }?>">
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Not Entering)</a>
                                        </li>
                                        
                                        <?php
                                            if($this->session->userdata('login_id') == '23026'){
                                        ?>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Not Entering)</a>
                                        </li>
                                        <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
								
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>BHATIARY</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineforVatiaryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Bhatiary</a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryBhatiary') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Bhatiary)</a>
                                        </li>

                                        <?php
                                            if($this->session->userdata('login_id') == '23026'){
                                        ?>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Vatiary') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Bhatiary)</a>
                                        </li>

                                        <?php
                                            }
                                        ?>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>KUTUBDIA</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineforKutubdiaForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward </br><span style="padding-right:30px;"></span>Kutubdia</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryKutubdia') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</br><span style="padding-right:30px;"></span> (Kutubdia)</a>
                                        </li>

                                        <?php
                                            if($this->session->userdata('login_id') == '23026'){
                                        ?>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Kutubdia') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Kutubdia)</a>
                                        </li>
                                        <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
								
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>BEACHING</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vslBeachedForwardForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Beaching</a>
                                        </li>	
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryBeached') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Beaching)</a>
                                        </li>

                                        <?php
                                            if($this->session->userdata('login_id') == '23026'){
                                        ?>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListBeached') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Beaching)</a>
                                        </li>

                                        <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
								
                                <?php
                                    if($this->session->userdata('login_id') == '23026'){
                                ?>
                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>CANCELLATION</span></a>
                                    <ul class="nav nav-children">
                                        

                                        <li>
                                            <a href="<?php echo site_url('Vessel/vslCancelationForwardForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Cancellation</a>
                                        </li>	
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryCancelation') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Cancellation)</a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListCancelation') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Cancellation)</a>
                                        </li>

                                    </ul>
                                </li>

                                <?php
                                    }
                                ?>

								
								<li class="<?php if($sub_menu=="outerAnchorageVslReportForm"){?>nav-active<?php }?>">									
									<a href="<?php echo site_url('Vessel/outerAnchorageVslReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>All Incoming & Departure <br><span style="padding-right:30px;"></span>Vessel in CPA</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingStatementForNotEnteringForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Statement </br>
										<span style="padding-right:30px;"></span>(Not Entering)
									</a>
								</li>
								
								<li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>TUG HIRING</span></a>
                                    <ul class="nav nav-children">
                                        <li>
											<a href="<?php echo site_url('Vessel/tugHiringForm') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Tug Hiring Form
											</a>
										</li>	
										 <li>
											<a href="<?php echo site_url('Vessel/tugHiringList') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Tug Hiring List
											</a>
										</li>
                                        <li>
											<a href="<?php echo site_url('Vessel/tugHiringListForForwardingToHM') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Check & Forward </br>
												<span style="padding-right:30px;"></span>(Tug Hiring)
											</a>
										</li>
										<li>
											<a href="<?php echo site_url('Vessel/SelectTugHiringListForForwarding') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Check & Forward to </br>
												<span style="padding-right:30px;"></span>Accounts (Tug Hiring)
											</a>
										</li>
										<li>
											<a href="<?php echo site_url('Vessel/forwardedTugHiringLetterList') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Tug Hiring Letter
											</a>
										</li>
                                    </ul>
                                </li>
								
								
								
								<!-- <li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMarineforVatiaryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward Bhatiary</a>
								</li> -->
								
							</ul>
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>HOLIDAY</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/holidayDeclarationForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Holiday Declaration
									</a>
								</li>	
								<li>
									<a href="<?php echo site_url('Report/holidayList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Holiday list
									</a>
								</li>
							</ul>
						</li>
						<?php
						}
						?>

						<li>
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DISPUTE BILL</span></a>
							<ul class="nav nav-children">
								
								<li>
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
									</a>
								</li>							
							</ul>
						</li>

						<li class="nav-parent <?php if($menu=="Bill"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>WATER SUPPLY AND FORWARD</span></a>
							<ul class="nav nav-children">

                                <?php
                                    if($this->session->userdata('login_id') == '000326')
                                    {
                                ?>

                                <li class="<?php if($sub_menu=="waterDemandListforDocMaster"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/waterDemandListforDocMaster') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
								</li>

                                <?php
                                    }else{
                                ?>

								<li class="<?php if($sub_menu=="waterDemandListforDocMaster"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/waterDemandListforDocMaster') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
								</li>

                                <?php
                                    }
                                ?>
                                
								<li class="<?php if($sub_menu=="waterBillDocumentForwarding"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/waterBillDocumentForwarding') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Bill Document <br>
									<span style="padding-right:30px;"></span>Forwarding</a>
								</li>							
							</ul>
						</li>
						
						<li class="nav-parent <?php if($menu=="accountSetting"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
							<ul class="nav nav-children">
								<li class="<?php if($sub_menu=="verify_cell_number"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Login/verify_cell_number') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Password Change
									</a>
								</li>
								<li class="<?php if($sub_menu=="two_step_verify_cell_number"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Two Step Verification
									</a>
								</li>
							</ul>
						</li>
						
						<?php 
						}	
						?>	
						<!-- Manine Panel End -->	

  	                <!-- Master Panel Start -->
                    <?php if( $this->session->userdata('org_Type_id')==81)
                        {
                        ?>
						<li>
							<a><span>HARBAOUR MASTER PANEL</span></a>							
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL</span></a>
							<ul class="nav nav-children">

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>CONTAINER VESSEL</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding</a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryN4') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>NOT ENTERING</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vslNotEnteringForwardList') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward<br><span style="padding-right:30px;"></span>Not Entering</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Not Entering)</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Not Entering)</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>BHATIARY</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineforVatiaryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward </br><span style="padding-right:30px;"></span>Bhatiary</a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryBhatiary') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</br><span style="padding-right:30px;"></span> (Bhatiary)</a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Vatiary') ?>">
                                                <i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Bhatiary)
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>KUTUBDIA</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMarineforKutubdiaForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward </br><span style="padding-right:30px;"></span>Kutubdia</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryKutubdia') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</br><span style="padding-right:30px;"></span> (Kutubdia)</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Kutubdia') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Kutubdia)</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>BEACHING</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vslBeachedForwardForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Beaching</a>
                                        </li>	
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryBeached') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Beaching)</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListBeached') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Beaching)</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>CANCELLATION</span></a>
                                    <ul class="nav nav-children">
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vslCancelationForwardForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Cancellation</a>
                                        </li>	
                                        <li>
                                            <a href="<?php echo site_url('Vessel/forwardedVslHistoryCancelation') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Cancellation)</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListCancelation') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Cancellation)</a>
                                        </li>
                                    </ul>
                                </li>
								

								<!--li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMarineByAppForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding by Marine </br><span style="padding-right:30px;"></span>(Apps)</a>
								</li-->	
								<!--li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyLetterListApps') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter(Apps)</a>
								</li-->
								
								
								<!--li>
									<a href="<?php echo site_url('Vessel/outerAnchorageForwarding') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Not Entering (Old)</a>
								</li-->	

									
									

								<li>
									<a href="<?php echo site_url('Vessel/forwardedVslSummary') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel Summary</a>
								</li>	

								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingStatementForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Statement 
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingStatementForNotEnteringForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Statement </br>
										<span style="padding-right:30px;"></span>(Not Entering)
									</a>
								</li>
								
							</ul>
						</li>	

						<li class="nav-parent <?php if($menu=="bill"){ ?> nav-expanded nav-active <?php } ?>">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DISPUTE BILL</span></a>
							<ul class="nav nav-children">
								
								<li class="<?php if($sub_menu=="vesselBillListAcc/a"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
									</a>
								</li>							
							</ul>
						</li>

						<li class="nav-parent <?php if($menu=="Bill"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>BILL</span></a>
							<ul class="nav nav-children">
								<li class="<?php if($sub_menu=="waterDemandList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
								</li>								
							</ul>
						</li>
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
							<ul class="nav nav-children">
							<li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
							<li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
							</ul>
						</li>
						
						<?php 
						}	
						?>	
						<!-- Master Panel End -->

                        						<!-- Accontant Panel Start -->
						<!-- For Accountant (Previous Sr Accountant) and Bill Operator (Previous Accountant) -->
						
						<?php if( $this->session->userdata('org_Type_id')==82)
						{
						?>
						<li>
							<a><span>CPA ACCOUNTANT PANEL</span></a>							
						</li>
						<!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL</span></a>
							<ul class="nav nav-children">
								
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Not Entering)</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMarineByAppForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding by Marine </br><span style="padding-right:30px;"></span>(Apps)</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/forwardedVslHistoryN4') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History</a>
								</li>
								
								
								<li>
									<a href="<?php echo site_url('Vessel/vslNotEnteringForwardList') ?>"><i class="fa fa-mail-forward (alias)"></i>Check And Forward <br><span style="padding-right:30px;"></span>Not Entering</a>
								</li>	
								<li>
									<a href="<?php echo site_url('Vessel/forwardedVslHistoryNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Forwarded Vessel History <br><span style="padding-right:30px;"></span>(Not Entering)</a>
								</li>	
							</ul>
						</li-->
						
						<li class="nav-parent <?php if($menu=="bill"){ ?> nav-expanded nav-active <?php } ?>" >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>BILL</span></a>
							<ul class="nav nav-children">
								<!--li class="<?php if($sub_menu=="usdtoBdtExchangeRateform"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/usdtoBdtExchangeRateform') ?>">
										<i class="fa fa-mail-forward (alias)"></i>USD to BDT Exchange</br><span style="padding-right:30px;"></span> Rate
									</a>
								</li-->
								<li class="<?php if($sub_menu=="usdtoBdtExchangeRateList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/usdtoBdtExchangeRateList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Exchange Rate List</br>
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListNotEntering') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Not Entering)</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Vatiary') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Bhatiary)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterList/Kutubdia') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Kutubdia)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListBeached') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Beaching)</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/vesselForwardingbyMasterListCancelation') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding Letter </br><span style="padding-right:30px;"></span> Record (Cancellation)</a>
								</li>
								<li class="<?php if($sub_menu=="vesselForwardinghistory"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/vesselForwardinghistory') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Forwarding History
									</a>
								</li>
								
								<li class="<?php if($sub_menu=="vesselBillListAcc/p"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/p') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Bill List (Pending)</a>
								</li>	
								<li class="<?php if($sub_menu=="vesselBillListAcc/a"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Bill List (Approved)</a>
								</li>

								<li class="<?php if($sub_menu=="waterDemandList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Document List</a>
								</li>

								<li class="<?php if($sub_menu=="bankStatementForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/bankStatementForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Bank Statement</a>
								</li>
								<li class="<?php if($sub_menu=="billwiseStatementForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/billwiseStatementForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Billwise Statement
									</a>
								</li>
								<li class="<?php if($sub_menu=="periodicStatementForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/periodicStatementForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Periodic Statement
									</a>
								</li>
								<li class="<?php if($sub_menu=="monthlyStatementForm"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/monthlyStatementForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Monthly Statement
									</a>
								</li>	

								<li class="nav-parent">
                                    <a><i class="fa fa-list" aria-hidden="true"></i><span>TUG HIRING</span></a>
                                    <ul class="nav nav-children">
										<li>
											<a href="<?php echo site_url('Vessel/forwardedTugHiringLetterList') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Tug Hiring Letter
											</a>
										</li>
                                    </ul>
                                </li>
								
							</ul>
						</li>
						
                        <li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>HOT WORK SERVICE </span></a>
							<ul class="nav nav-children">
							<?php if( $this->session->userdata('section')=="acc")
							{
							?>
								<li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandListForAccount') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandApproveListForAccount') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service Approved<br>
										<span style="padding-right:30px;"></span> List
									</a>
								</li>
							<?php } else if($this->session->userdata('section')=="billop"){  ?>	
								<li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandApproveListForBillOperator') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service Approved<br>
										<span style="padding-right:30px;"></span> List
									</a>
								</li>
							<?php }else if($this->session->userdata('section')=="dcfo"){ ?>
								<li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandListForAccount') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service List
									</a>
								</li>
							<?php } ?>	
							
							</ul>		
					    </li>

						<li class="nav-parent <?php if($menu=="dispute_bill"){ ?> nav-expanded nav-active <?php } ?>">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DISPUTE BILL</span></a>
							<ul class="nav nav-children">
								
								<li class="<?php if($sub_menu=="vesselBillListAcc/a/dis"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('VesselBill/vesselBillListAcc/a/dis') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
									</a>
								</li>							
							</ul>
						</li>
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
							<ul class="nav nav-children">
							<li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
							<li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
							</ul>
						</li>
						
						<?php 
						}	
						?>	
						<!-- Accontant Panel End -->	

                        <!-- ONE STOP DOCUMENTATION Panel Start -->
                        <?php if($this->session->userdata('org_Type_id')==80)
						{
						?>
                        <li>
                            <a><span>ONE STOP DOCUMENTATION</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
                                        <span style="padding-right:30px;"></span>Generation List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>BILL OF ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                                <!--li><a href="<?php echo site_url('Report/date_wise_bill_entry') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry Report</a></li>
								<li><a href="<?php echo site_url('Report/date_wise_be_report') ?>"><i class="fa fa-mail-forward (alias)"></i>Date Wise Bill of Entry Report</a></li>
								<li><a href="<?php echo site_url('Report/head_delivery') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Search & Truck Entry</a></li>
								<li><a href="<?php echo site_url('Report/be_error_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry Error List</a></li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- ONE STOP DOCUMENTATION Panel End -->

                        <!-- Security Panel Start -->
                        <?php 
						if($this->session->userdata('Control_Panel')==67&& $this->session->userdata('org_Type_id')==67)
						{
						?>
                        <li>
                            <a><span>SECURITY PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
						<li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverListForYardUnit') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover
                                    </a>
                                </li>                          
                            </ul>
                        </li>

                        <?php
							if($lid=="gate3counter" or $lid=="gate5counter" or $lid=="gate1counter" or $lid=="gate2counter" or $lid=="cparcounter" or $lid=="cct2counter" or $lid=="nct1counter" or $lid=="nct2counter" or $lid=="nct3counter" or $lid=="ofycounter" or $lid=="cctcfscounter" or $lid=="shed13counter" or $lid=="shed12counter" or $lid=="shed09counter" or $lid=="yardcounter" or $lid=="ncygatecounter")
							{
							?>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/fclTruckEntryformforSecurity') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Truck Entry for FCL
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/lclTruckEntryformforSecurity') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Truck Entry for LCL
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span
                                            style="padding-right:30px;"></span>Empty Details</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Termianl wise special <br>
                                        <span style="padding-right:30px;"></span>assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/monthlyAssignmentReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Monthly Assignment Report</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/yearlyAssignmentReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Yearly Assignment Report</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TOS GATE CONTROL </span></a>
                            <ul class="nav nav-children">
                                <?php
											$login_id = $this->session->userdata('login_id');
											if($login_id == 'gate5counter' || $login_id == 'gate3counter' || $login_id == 'yardcounter'  || $login_id == "gate1counter" || $login_id == "gate2counter" || $login_id == "cparcounter" || $login_id == "cct2counter" || $login_id == "nct1counter" || $login_id == "nct2counter" || $login_id == "nct3counter" || $login_id=="ofycounter" || $login_id == "cctcfscounter" || $login_id == "shed13counter" || $login_id == "Shed12counter" || $login_id == "Shed09counter"){
										?>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Payment Collection
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Payment Collection For LCL
                                    </a>
                                </li>
                                <?php
											}
										?>
                                <li>
                                    <a href="<?php echo site_url('GateController/gateCollectionReportForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Collection Report
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateInProcessForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate in Process
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/gateInProcessForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate in Process for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Generation
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateOutProcessForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate out Process
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/replaceTruckGateOutForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Replace Truck Gate out
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateInOutReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Truck Gate in out Report
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('GateController/DlvRegSearchForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DELIVERY REGISTER In GATE
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                             
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverListForYardUnit') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>
                    
                            </ul>
                        </li>




                        <?php
							}
							else if($lid=="ncyasi" or $lid=="ofyasi" or $lid=="cctasi" or $lid=="nctasi" or $lid=="unit1asi" or $lid=="unit2asi" or $lid=="unit3asi" or $lid=="unit4asi" or $lid=="unit5asi")
							{
							?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CART</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>YARD OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a
                                        href="<?php echo site_url('ShedBillController/additionalTruckPermissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Additional Truck Permission</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/emergencyTruckList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Emergency Truck List</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loading Process</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Dispute List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/sec') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Security Confirmation process
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Confirmation
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
							}
							else if($lid=="gatesergeant")
							{
							?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TOS GATE CONTROL</span></a>
                            <ul class="nav nav-children">
                                <?php
											$login_id = $this->session->userdata('login_id');
											if($login_id == 'gate5counter' || $login_id == 'gate3counter' || $login_id == 'yardcounter' || $login_id == 'cctcfscounter' || $login_id == "shed13counter" || $login_id == "Shed12counter" || $login_id == "Shed09counter"){
										?>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Payment Collection
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></iPayment Collection </a>
                                </li>
                                <?php
											}
										?>

                                <li>
                                    <a href="<?php echo site_url('GateController/gateCollectionReportForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Collection Report
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateInProcessForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate in Process
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/gateInProcessForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate in Process for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Generation
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateOutProcessForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate out Process
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/replaceTruckGateOutForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Replace Truck Gate out
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateInOutReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Truck Gate in out Report
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('GateController/DlvRegSearchForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DELIVERY REGISTER In GATE
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CART</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php							
							}
							else
							{
							?>
                        <?php
								if($lid=="pass" or $lid=="ds" or $lid=="ddops" or $lid=="soops" or $lid=="opincharge" or $lid=="devsecurity"  or $lid=="dirsecurity" )
								{
								?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM INFORMATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('igmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK">View IGM General Information</a></li>
                                <li><a href="<?php echo site_url('Report/lyingContainerListform') ?>">IGM Info for Lying
                                        Container</a></li>
                            </ul>
                        </li>
                        <!--li class="nav-parent">
										<a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT</span></a>
										<ul class="nav nav-children">
											<li>
												<a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
													<i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br>
													<span style="padding-right:30px;"></span>Empty Details
												</a>
											</li>
											<?php
											$str = "SELECT COUNT(*) AS tot
											FROM 
											(SELECT unit_gkey AS u_gkey,
											(SELECT id FROM inv_unit WHERE gkey=a.unit_gkey) AS Cont,
											(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size
											FROM sparcsn4.inv_unit_equip	
											INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey		
											INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey		
											WHERE sparcsn4.inv_unit_equip.unit_gkey=u_gkey LIMIT 1) AS size,
											(SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) AS height
											FROM sparcsn4.inv_unit_equip	
											INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey		
											INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey		
											WHERE sparcsn4.inv_unit_equip.unit_gkey=u_gkey LIMIT 1) AS height,flex_date01,
											(SELECT (SELECT new_value FROM srv_event_field_changes WHERE event_gkey=srv_event.gkey AND metafield_id='ufvFlexDate01' ORDER BY gkey DESC LIMIT 1) AS assignment_Date
											FROM srv_event
											INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
											WHERE event_type_gkey=4 AND DATE(created)=CURDATE() AND applied_to_gkey=a.unit_gkey 
											ORDER BY srv_event_field_changes.event_gkey DESC LIMIT 1) AS ass_dt
											FROM (SELECT unit_gkey,flex_date01 FROM inv_unit_fcy_visit WHERE DATE(flex_date01)=CURDATE()) AS a) AS b WHERE ass_dt IS NOT NULL";

											/* $result = mysqli_query($con_sparcsn4,$str);
											$row = mysqli_fetch_object($result);
											$badge = $row->tot; */
											?>
											<!--li class="badge_large" font-size="3px" data-badge="<?php echo $badge;?>"><i class="fa fa-mail-forward (alias)"></i><a href="<?php echo site_url('Report/special_assignmentForSecurity') ?>" target="_blank">Special Assignment</a></li>
											<li-->
                        <!--li>
												<a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
											</li>
										</ul>
									</li-->
                        <?php
								}
								?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li>
                                    <a href="<?php echo site_url('report/myIGMFFReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Reports
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
                                <!--li><a href="<?php echo site_url('report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Menifest</a></li-->
                                <li><a href="<?php echo site_url('report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
                                <li><a href="<?php echo site_url('report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
                                <li><a href="<?php echo site_url('report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <li>
                                    <a href="<?php echo site_url('report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li>
                                    <a href="<?php echo site_url('report/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary<br>
                                        <span style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                            </ul>
                        </li>

                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span
                                            style="padding-right:30px;"></span>Empty Details</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/monthlyAssignmentReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Monthly Assignment Report</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yearlyAssignmentReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Yearly Assignment Report</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/cont_wise_truck') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Wise Truck Entry</a></li>
                                <li><a href="<?php echo site_url('Report/containerBarcodeSearch') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Search(Barcode
                                        Generator)</a></li>
                                <li><a href="<?php echo site_url('Report/containerListForSecurity') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container & Truck Entry List</a></li>
                                <li><a href="<?php echo site_url('Report/contDlvStatusCheck') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Delivery Status Check</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
									<a><i class="fa fa-list" aria-hidden="true"></i><span>HOT WORK SERVICE </span></a>
									<ul class="nav nav-children">
										<li>
											<a href="<?php echo site_url('Vessel/hotWorkDemandListForAccount') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Hot Work Service List
											</a>
										</li>
										<!-- <li>
											<a href="<?php //echo site_url('Vessel/hotWorkDemandApproveListForAccount') ?>">
												<i class="fa fa-mail-forward (alias)"></i>Hot Work Demand Approved<br>
												<span style="padding-right:30px;"></span> List
											</a>
										</li> -->
									
									</ul>		
						</li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                                <li><a href="<?php echo site_url('Report/ocr_container_info') ?>" target="_blank"><i
                                            class="fa fa-mail-forward (alias)"></i>OCR Container List</a></li>
                                <!--li><a href="<?php echo site_url('Report/date_wise_be_report') ?>">DATE WISE Bill of Entry REPORT</a></li>
										<li><a href="<?php echo site_url('Report/head_delivery') ?>">CONTAINER SEARCH & TRUCK ENTRY</a></li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>BREAK BULK SHED</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/bbTruckEntryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Truck Demand List</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>OCR</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/ocr_report_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>OCR Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE REPORT</span></a>
                            <ul class="nav nav-children">
                                <!--li>
											<a href="<?php echo site_url('GateController/containerRegisterInRegister_ocr')?>">
												<i class="fa fa-mail-forward (alias)"></i>Inward & Outward OCR<br>
												<span style="padding-right:30px;"></span>Container Register
											</a>
										</li-->
                                <li>
                                    <a href="<?php echo site_url('GateController/containerRegisterInRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Inward & Outward Container <br />
                                        Register</a>
                                </li>
                                <li><a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward <br />
                                        Container Register</a></li>
                                <li>
                                    <a href="<?php echo site_url('GateController/feesRegistrtaionForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Fees Registration
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('GateController/datewiseTruckEntryForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DateWise Truck Entry Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE INSPECTOR</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('GateController/gateOut') ?>"><i class="fa fa-mail-forward (alias)"></i>Gate Entry</a></li-->
                                <li><a href="<?php echo site_url('GateController/gateConfirmation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate Confirmation</a></li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TOS GATE CONTROL</span></a>
                            <ul class="nav nav-children">
                                <?php
											$login_id = $this->session->userdata('login_id');
											if($login_id == "gate5counter" || $login_id == "gate3counter" || $login_id == "yardcounter" || $login_id == "cctcfscounter" || $login_id == "shed13counter" || $login_id == "Shed12counter" || $login_id == "Shed09counter"){
										?>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Payment Collection
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/paymentCollection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Payment Collection For LCL
                                    </a>
                                </li>
                                <?php
											}
										?>

                                <li>
                                    <a href="<?php echo site_url('GateController/gateCollectionReportForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Collection Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/gateInProcessForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate in Process</a></li>

                                <li>
                                    <a href="<?php echo site_url('LCL/gateInProcessForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Gate in Process for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Generation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gateOutProcessForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate out Process
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/replaceTruckGateOutForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Replace Truck Gate out
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('ShedBillController/gateInOutReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Truck Gate in out Report</a></li>
                                <li class="nav-parent">

                                <li><a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Cart Ticket List</a></li>

                                <li>
                                    <a href="<?php echo site_url('GateController/DlvRegSearchForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DELIVERY REGISTER In GATE
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>YARD OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/emergencyTruckList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Emergency Truck List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loading Process</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Dispute List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/sec') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Security Confirmation process
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li class="nav-parent">
									<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
									<ul class="nav nav-children">
										<li><a href="<?php echo site_url('Login/myPasswordChange') ?>"><i class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
									</ul>
								</li-->
                        <?php
							}
							?>
                        <!-- account setting is common for all user  -->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Security Panel End -->


                        <!-- Chairman Panel start -->
                        <?php 
						if($this->session->userdata('Control_Panel')==65&& $this->session->userdata('org_Type_id')==65)
						{
						?>
                        <li>
                            <a><span>CHAIRMAN PANEL</span></a>

                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EQUIPMENT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myReportHHTReCord') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Assigned<br>
                                        <span style="padding-right:30px;"></span>Equipment List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/monthlyYardWiseContainerHandling') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>YARD WISE TOTAL<br>
                                        <span style="padding-right:30px;"></span>CONTAINER HANDLING
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report/monthlyYardWiseContainerHandling') ?>">YARD WISE TOTAL CONTAINER HANDLING</a></li-->
                                <li><a href="<?php echo site_url('Report/containerHandlingRptForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Handling Report</a></li>
                                <li><a href="<?php echo site_url('Report/containerHandlingRptMonthlyForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Monthly Container Handling Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/plannedRptForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Job Done VesselWise</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <span class="blink_me"><i class="fa fa-mail-forward (alias)"></i>Comments by
                                            Shipping Section<br>
                                            <span style="padding-right:30px;"></span>on Export Vessel</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vslDtlForChairmanView') ?>" target="_blank"><i
                                            class="fa fa-mail-forward (alias)"></i>List of Vessel</a></li>
                                <li><a href="<?php echo site_url('Report/gateDtlForChairmanView') ?>" target="_blank"><i
                                            class="fa fa-mail-forward (alias)"></i>List of Gate</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Chairman Panel end -->

                        <!-- Network Panel start -->
                        <?php
						if($this->session->userdata('Control_Panel')==66 && $this->session->userdata('org_Type_id')==66){ ?>
                        <li>
                            <a><span>NETWORK PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if($lid=="faisal"){ ?>
                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM
                                    OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY FORM</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/product_type_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Type</a></li>
                                <li><a href="<?php echo site_url('Report/location_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location</a></li>
                                <li><a href="<?php echo site_url('Report/location_detail_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location Detail Form</a></li>
                                <li><a href="<?php echo site_url('Report/product_user_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product User</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductEntryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Entry Form</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductReceive') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Received Form</a></li>
                                <li><a href="<?php echo site_url('Report/product_dlv_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Delivery Form</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY LIST</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/product_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Type List</a></li>
                                <li><a href="<?php echo site_url('Report/location_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location List</a></li>
                                <li><a href="<?php echo site_url('Report/location_details_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location Details List</a></li>
                                <li><a href="<?php echo site_url('Report/product_user_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List</a></li>
                                <li><a href="<?php echo site_url('Report/workstationList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductEntryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductReceiveList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Received List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductDeliveryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Delivery List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/workstationReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
                                <li><a href="<?php echo site_url('Report/product_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>OTHERS</span></a>
                            <ul class="nav nav-children">
                                <?php 
									$path= 'http://'.$_SERVER['SERVER_ADDR'].'/pcs/resources/download/';
									$appQry="SELECT app_name,version_code,REPLACE(version_name,'.','_') as version_name FROM ctmsmis.mis_exp_app_version ORDER BY id desc limit 1";
									$rtnAppQry= mysqli_query($con_sparcsn4,$appQry);
									$row=mysqli_fetch_object($rtnAppQry);
								?>
                                <li>
                                    <a href="<?php echo $path.$row->app_name."V".$row->version_name.".apk";?>">
                                        <font color="blue" size="2">
                                            <i class="fa fa-mail-forward (alias)"></i><b>Export Loading app</b>
                                        </font>
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/showProcessList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>System Status
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/slaveProcess/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Slave Process</a></li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <?php  
								if($lid=="nadmin")
								{
								?>
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                                <li>
                                    <a href="<?php echo site_url('Login/changePassForClient') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Password Change for<br>
                                        <span style="padding-right:30px;"></span>Network Panel User
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report/userList') ?>"><i class="fa fa-mail-forward (alias)"></i>User List</a></li-->
                                <li><a href="<?php echo site_url('Report/userList/active') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List (Active)</a></li>
                                <li><a href="<?php echo site_url('Report/userList/inactive') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List (Inactive)</a></li>
                                <li><a href="<?php echo site_url('Report/usrCreationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Create User</a></li>
                                <?php
								}
								?>
                            </ul>
                        </li>
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT LOADING APP</span></a>
							<ul class="nav nav-children">
								<?php 
									$path= 'http://'.$_SERVER['SERVER_ADDR'].'/myportpanel/resources/download/';
									//include("dbConection.php");
									$appQry="SELECT  app_name,version_code,REPLACE(version_name,'.','_') as version_name FROM ctmsmis.mis_exp_app_version ORDER BY id desc limit 1";
									$rtnAppQry= mysql_query($appQry,$con_sparcsn4);
									$row=mysql_fetch_object($rtnAppQry);
								?>
								<li><a href="<?php echo $path.$row->app_name."V".$row->version_name.".apk";?>"><font color="blue" size="2"><b>EXPORT LOADING APP</b></font></a></li>
							</ul>
						</li-->
                        <?php 
						}	
						?>
                        <!-- Network Panel end -->

                        <!--Billing Start-->
                        <?php 		
						if($this->session->userdata('section')==19 && $this->session->userdata('org_Type_id')==5)
						{
						?>
                        <li>
                            <a><span>BILLING PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM Break Bulk<br>
                                        <span style="padding-right:30px;"></span>Information
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('IgmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/updateManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>">Convert COPINO</a></li-->
                                <li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert Export-Commodity</a></li>
                                <!-- <li><a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>">Check The IGM</a></li> -->
                                <li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
                                <li><a href="<?php echo site_url('Report/convertIgmCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify Section</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Routing Points</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>
                                <?php  
								if($lid=="sazam" or $lid=="mdibrahim" or $lid=="popy" or $lid=="Shepu" or $lid=="tipai" or $lid=="shopna" or $lid=="norin"  or $lid=="anikcpa" or $lid=="IBRAHIM")
								{
								?>
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <?php						
									$str = "SELECT COUNT(DISTINCT rotation) AS cnt
									FROM ctmsmis.mis_exp_unit_preadv_req
									WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
									$result = mysqli_query($con_sparcsn4,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;		
								?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation</a></li>
                                <?php									
									$str = "select count(id) as cnt from edi_stow_info where file_status=0";
									
									$result = mysqli_query($con_cchaportdb,$str);
									$row = mysqli_fetch_object($result);
									$badge = $row->cnt;
									//echo "TEST : ".$badge;
								?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>">Today's EDI
                                        Declaration</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
                                <?php
								}
								?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break Bulk
                                        Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
                                <!--li><a href="<?php echo site_url('Report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Menifest</a></li-->
                                <li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <!--li>
										<a href="<?php echo site_url('	Report/myDGContainer') ?>">
											<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
										</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li><a href="<?php echo site_url('Report/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary List</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/OffDockContainerList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise Container
                                        List </a></li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Menifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('	Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Import Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesslWiseRefeerContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myWaterSupplyInVesselsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Water Supply in Vessels
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myContainerHistoryReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container History Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ICD</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/icdInboundOutboundContainerReport')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ICD Incoming Outcoming<br>
                                        <span style="padding-right:30px;"></span>Container Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/icdContainerReportByRotation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Container by Rotation</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
                                        <span style="padding-right:30px;"></span>apps Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <span class="blink_me"><i class="fa fa-mail-forward (alias)"></i>Comments by
                                            Shipping Section<br>
                                            <span style="padding-right:30px;"></span>on Export Vessel</span>
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li><a href="<?php echo site_url('Report/workDone24hrsForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 hrs. Container Handling
                                        Report</a></li>
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final Loading<br>
                                        <span style="padding-right:30px;"></span>Export APPS
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPAN4') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Loading Export
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignment_sheet_for_pangaon') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Sheet for Outward<br>
                                        <span style="padding-right:30px;"></span>PANGAON ICT Container
                                    </a>
                                </li>
                                <?php  
								if($lid=="porikkhit") 
								{ 
								?>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement List</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement</a></li>
                                <!--li><a href="<?php echo site_url('UploadExcel/last24hrsStatementList') ?>">LAST 24 STATEMENT LIST</a></li-->
                                <li><a href="<?php echo site_url('Report/stuffingPermissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form</a></li>
                                <?php 
								} 
								?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery Empty<br>
                                        <span style="padding-right:30px;"></span>Details
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying<br>
                                        <span style="padding-right:30px;"></span>Container Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
                                        <span style="padding-right:30px;"></span>Receive Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerDeliveryRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
                                        <span style="padding-right:30px;"></span>Delivery Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/containerPositionEntryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Position Entry</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/blockWiseRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge Report<br>
                                        <span style="padding-right:30px;"></span>by apps
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Removal Container</a></li>
                                <li><a href="<?php echo site_url('Report/countryWiseImportReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Country Wise Import Report</a></li>
                                <li><a href="<?php echo site_url('Report/yearWiseImportReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Year Wise Import Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingImportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Discharging Import
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/removal_list_form/overflow') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Removal List OF Overflow Yard
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/removal_list_form/all') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>List of CTMS Assignment</a></li>
                            </ul>
                        </li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>QGC/MHC </span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Vessel/qgcContForwardNewForm') ?>" ><i class="fa fa-mail-forward (alias)"></i>QGC/MHC Handled Forwarding</a></li>
								<!--li><a href="<?php echo site_url('Vessel/stateOfContainerHandledByQGC') ?>" ><i class="fa fa-mail-forward (alias)"></i>QGC/MHC Handled Statement</a></li-->
							</ul>
						</li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GATE REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/offhireSummaryAndDetails') ?>"
                                        target="_blank"><i class="fa fa-mail-forward (alias)"></i>Offhire Summary and
                                        Details</a></li>
                                <li>
                                    <a href="<?php echo site_url('GateController/gateWiseContainerRegister')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gatewise Inward & Outward<br>
                                        <span style="padding-right:30px;"></span>Container Register
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PANGOAN REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>
                                <li><a href="<?php echo site_url('Report/pangoanDischargeForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Discharge</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!--Billing  END-->

                        <!-- Break Bulk Panel start -->
                        <?php
						if($this->session->userdata('Control_Panel')==70 && $this->session->userdata('org_Type_id')==70)
						{
						?>
                        <li>
                            <a><span>BREAK BULK PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <!--li>
									<a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
									</a>
								</li-->
                            </ul>
                        </li>
                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ENTRY & LIST</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/bbTruckEntryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Truck Demand Form</a></li>
								<li><a href="<?php echo site_url('Report/bbTruckEntryList') ?>"><i class="fa fa-mail-forward (alias)"></i>Truck Demand List</a></li>
							</ul>
						</li-->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('igmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Break Bulk Panel end -->

                        <!-- NSI Panel start -->
                        <?php
						if($this->session->userdata('Control_Panel')==71 && $this->session->userdata('org_Type_id')==71)
						{
						?>
                        <li>
                            <a><span>NSI PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/containerReportDailyForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Daily Container Report</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTINGS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- NSI Panel end -->

                        <!-- ADMIN PANEL start -->
                        <?php
						if($this->session->userdata('Control_Panel')==28)
						{
						?>
                        <li>
                            <a><span>ADMIN PANEL.</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('EDOController/changeCNFForEDO') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Change C&F for EDO
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status
                                        <br><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
                            <ul class="nav nav-children">
                                <?php if($this->session->userdata('org_Type_id')==28) { ?>
                                <li>
                                    <a href="<?php echo site_url('Login/OrgProfileForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization Profile Entry
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization List
                                    </a>
                                </li>


                            </ul>
                        </li>
						<li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>RELEASE ORDER</span></a>
							
                            <ul class="nav nav-children">
                                <?php
								if($this->session->userdata('login_id') == "devcf" or $this->session->userdata('login_id') == "testcf")
								{
								?>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roSubmitForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order Submission
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/releaseOrderViewForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order View
                                    </a>
                                </li>
								
                                <?php
								}
								else
								{
								?>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order Submission
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ReleaseOrderController/roList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Release Order List
                                    </a>
                                </li>
                                <?php
								}
								?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckEntryByAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FCL Truck Entry
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/lcltruckEntryByAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL Truck Entry
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/changeTruckInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Change Truck Entry Info
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Generation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/gateWiseTruckEntryByScanningForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Wise Truck Entry
                                        <br><span style="padding-right:30px;"></span>(Scanning)
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL LAYOUT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vslLayout') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>New Vessel Layout</a></li>
                                <li><a href="<?php echo site_url('Report/deleteWrongBay') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Delete Wrong Bay</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/updateVslForExpCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Update Vessel for Export<br>
                                        <span style="padding-right:30px;"></span>Containers
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/updateVisitForPctCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Update Visit for PANGAON<br>
                                        <span style="padding-right:30px;"></span>Containers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('GateController/igmCorrection') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Correction</a></li-->
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>
                                <!--li>
									<a href="<?php echo site_url('IgmViewController/lateBLsubmission') ?>"><i class="fa fa-mail-forward (alias)"></i>Late BL submission form </a>
								</li>
								<li>
									<a href="<?php echo site_url('IgmViewController/igmContainerSubmissionForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Late BL Container submission form </a>
								</li-->
                                <!-- dont by other -->
                                <li>
                                    <a href="<?php echo site_url('igmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>

                                </li>
                                <li><a href="<?php echo site_url('igmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('igmViewController/checkTheIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
                                <li><a href="<?php echo site_url('igmViewController/updateManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>

                                <li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert EXPORT-COMMODITY</a></li>


                                <li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/convertIgmCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify<br>
                                        <span style="padding-right:30px;"></span>Section
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Routing Points</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>
                                <li><a href="<?php echo site_url('Report/deleteIGMInfo') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Delete IGM Information</a></li>
                                <!-- not necessary, as igm amendment and late submission is ok -->
                                <!--
								<li><a href="<?php echo site_url('IgmViewController/igmInfoProcessForm') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Information Entry</a></li>
								<li><a href="<?php echo site_url('IgmViewController/igm_sup_dtl_entry_form') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Entry</a></li>
								-->
                                <li>
                                    <a href="<?php echo site_url('Report/offDockReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Destination Change<br>
                                        <span style="padding-right:30px;"></span>to Offdock
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM AMENDMENT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('GateController/igmCorrection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Amendment</a></li>

                                <li>
                                    <a href="<?php echo site_url('IgmViewController/lateBLsubmissionMaster') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL submission<br>
                                        <span style="padding-right:30px;"></span>form (Master) </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('IgmViewController/lateBLContainerSubmissionMaster') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL Container <br>
                                        <span style="padding-right:30px;"></span>Submission(Master)</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('IgmViewController/lateBLsubmission') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL submission<br>
                                        <span style="padding-right:30px;"></span>form (Suplimentary) </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('IgmViewController/igmContainerSubmissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Late BL Container submission<br>
                                        <span style="padding-right:30px;"></span>form (Suplimentary) </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myIGMFFReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Reports
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
                                <!--li><a href="<?php echo site_url('Report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Menifest</a></li-->
                                <li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <!--li>
										<a href="<?php echo site_url('Report/myDGContainer') ?>">
											<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
										</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary<br>
                                        <span style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <!--li><a href="<?php echo site_url('Report/dgContainerByRotation') ?>" ><i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation</a></li-->
                                <li><a href="<?php echo site_url('Report/valuableItemByRotation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Valuable Item By Rotation</a></li>
                            </ul>
                        </li>


                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
                                        <span style="padding-right:30px;"></span>APPS Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <span class="blink_me"><i class="fa fa-mail-forward (alias)"></i>Comments by
                                            Shipping<br>
                                            <span style="padding-right:30px;"></span>Section on Export Vessel</span>
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/workDone24hrsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 hrs. Container<br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final Loading<br>
                                        <span style="padding-right:30px;"></span>Export APPS
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPAN4') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Loading Export
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignment_sheet_for_pangaon') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Sheet for Outward<br>
                                        <span style="padding-right:30px;"></span>PANGAON ICT Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement List</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement</a></li>

                                <li><a href="<?php echo site_url('Report/stuffingPermissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('Report/dlvFromOffdock') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery From Offdock
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span
                                            style="padding-right:30px;"></span>Empty Details</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Termianl wise special <br>
                                        <span style="padding-right:30px;"></span>assignment</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/searchIGMByContainer') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Search IGM Container</a></li>
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myoffDocEntryForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Information <br />
                                        <span style="padding-right:30px;"></span> Entry Form
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesselEventHistory') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Event History
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerEventHistory') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Event History
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/showProcessList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>System Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerDischargeAppsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Discharge(APPS)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerSearchByRotationAppsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Search for APPS
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Removal Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Statement List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/barcodeTestForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Print Barcode
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Statement
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingPermissionForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form
                                    </a>
                                </li>

                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li>
                                    <a href="<?php echo BASE_PATH.'assets/goods_vessel_tariff/CPA-Tariff-ilovepdf-compressed.pdf' ?>"
                                        target="blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Tariff on Goods, Vessel etc.
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/vesselBillList/1') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Vessel Bill List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/checkVatStatusForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>VAT Status
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/slaveProcess/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Slave Process</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContainerLoadingList') ?>"
                                        target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container Loading List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportContainerNotFoundReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container <br>
                                        <span style="padding-right:30px;"></span>NOT FOUND Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offhireSummaryAndDetails') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offhire Summary & Details</a></li>
                                <li>
                                    <a href="<?php echo site_url('bill/containerBillListVersion/1') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Bill<br>
                                        <span style="padding-right:30px;"></span>List (Version)
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>
                                <li><a href="<?php echo site_url('Report/pilot_vsl_entry_rpt') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Pilot Vessel Entry Report</a></li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedWiseLyingTallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"> </i>Shed wise Lying Tally List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container / BL Block Release <br>
                                        <span style="padding-right:30px;"></span>List</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/ladenExportContainerForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Laden Export Container</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/importDischargeReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/outerAnchorageForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Movement Report
                                    </a>
                                </li>


                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
                                        <span style="padding-right:30px;"></span>Generation List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Auction/auctionUnitChangesForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Unit Change
                                    </a>
                                </li>
                                <!--li>
									<a href="<?php echo site_url('Auction/pendingRLGenerationListLCL') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
										<span style="padding-right:30px;"></span>Generation List (LCL)
									</a>
								</li>
								
								<li>
									<a href="<?php echo site_url('Auction/AuctionHandOverReportFormLCL') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover Form(LCL)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Auction/AuctionHandOverReportListLCL') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover List(LCL)
									</a>
								</li-->
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Import Container
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>MIS REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('misReport/A23_1Form') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Performance Container<br>
                                        <span style="padding-right:30px;"></span>Vessels Last 24hrs (A23.1)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsContInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information<br>
                                        <span style="padding-right:30px;"></span>(Garments Item) by Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/searchGarmentsItemByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information by<br>
                                        <span style="padding-right:30px;"></span>Item & Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrPositionForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Position
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dailyExportContGateIn') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Daily Export Container<br>
                                        <span style="padding-right:30px;"></span>Gate In
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last45DayslyingReportLink') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 45 Days Lying<br>
                                        <span style="padding-right:30px;"></span>Food Items Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrsCPAToOffdockRemovalForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours CPA to<br>
                                        <span style="padding-right:30px;"></span>OFFDOCK removal
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsItemLyingAndDeliveryInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Garments Item Lying and<br>
                                        <span style="padding-right:30px;"></span>Delivery Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsItemLyingAndDeliveryInfoFormNew') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Garments Item Lying and<br>
                                        <span style="padding-right:30px;"></span>Delivery Information (New)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SCY OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/rotationWiseContainerPosition') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Rotation Wise Container Position
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/containerOperationReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Operation Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PILOTAGE CERTIFICATE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vesselListForPilotage') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Certificate</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">
                                <!-- done by other -->
                                <?php
								include("dbConection.php");
								$str = "SELECT COUNT(DISTINCT rotation) AS cnt
								FROM ctmsmis.mis_exp_unit_preadv_req
								WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
								$result = mysqli_query($con_sparcsn4,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
							?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation</a></li>

                                <?php
								include("mydbPConnection.php");
								$str = "select count(id) as cnt from edi_stow_info where file_status=0";
								
								$result = mysqli_query($con_cchaportdb,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
								//echo "TEST : ".$badge;
							?>
                                <?php 
								
								include("dbConection.php");
							?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's EDI Declaration</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
                                <li><a href="<?php echo site_url('UploadExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO..</a></li>
								<li>
									<a href="<?php echo site_url('UploadExcel/copern_copino_list') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Download Coparn Copino 
									</a>
								</li>
                                <!-- done by other -->
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/stuffingContExcel') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Excel for Last 24<br>
                                        <span style="padding-right:30px;"></span>Hours Stuffing Container</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/exportExcelUploadForAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Export Container<br>
                                        <span style="padding-right:30px;"></span>(Excel File)</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/last24hrPerformancePdfForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hour Performance<br>
                                        <span style="padding-right:30px;"></span>PDF File Upload
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/handling_performance_compare') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Handling Performance Compare
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/readObpcForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Excel for<br>
                                        <span style="padding-right:30px;"></span>OBPC & RL
                                    </a>
								
                                </li>
								
								
                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>NOTICE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/noticeUploadForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Notice Upload Form</a></li>
                                <li><a href="<?php echo site_url('Report/noticeUploadList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Notice Upload List</a></li>
                            </ul>
                        </li>

                        <!-- done by other -->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Entry Form</a></li>
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a></li>
                                <li>
                                    <a href="<?php echo site_url('LCL/shedWiseDeliveryReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Shed Head Delivery</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SMS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/smsRptForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Datewise SMS Status</a></li>
                                <li><a href="<?php echo site_url('Report/smsBalanceEntryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>SMS Balance Upload</a></li>
                                <!-- <li><a href="<?php echo site_url('Report/smsRptTypeForm') ?>"><i class="fa fa-mail-forward (alias)"></i>SMS Report</a></li> -->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>DOWNLOAD</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myBillSummaryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill Summary</a></li>
											
								<!--li>
									<a href="<?php echo site_url('Report/exportLoadingSNXForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Export Loading SNX
									</a>
								</li-->
								<li>
									<a href="<?php echo site_url('Report/rotationListForExportLoadingSNX') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Export Loading SNX
									</a>
								</li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PANGAON</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/pangoanContUpload') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for PANGAON</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertPanContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert PANGAON Containers</a></li>
											
											<!--Nadim Start-->
								<li><a href="<?php echo site_url('Report/pangaonEdiConverterForm') ?>"><i
								class="fa fa-mail-forward (alias)"></i>PANGAON Edi Converter</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ICD</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/uploadIcdExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Upload</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertIcdFileForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Converter</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_bill_entry') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry Report</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_be_report') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Date Wise Bill of Entry Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/head_delivery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Search & Truck Entry</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/be_error_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry Error List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GOODS REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/goodsWiseReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Wise Report</a></li>
                                <li><a href="<?php echo site_url('Report/itemGoodsWiseReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Report Active Yard</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/itemWiseSummaryDetailsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Item Wise Summary & Details<br>
                                        <span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/itemWiseLyingSummaryDetailsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Item Wise Lying Summary and <br>
                                        <span style="padding-right:30px;"></span>Details Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/importerWiseLyingReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Importer Wise Lying Report
                                    </a>
                                </li> 
								<li>
                                    <a href="<?php echo site_url('Report/importerWiseIGMreportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Importer Wise IGM Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/specificItemsGoodsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Specific Items Goods Report<br>
                                        <span style="padding-right:30px;"></span>(Statement of Containerized Cargo)
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SHED BILL PANEL</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/shedBillUrls') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>SHED BILL URLs
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-parent'><a><i class="fa fa-list"
                                    aria-hidden="true"></i><span>EQUIPMENT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myReportHHTReCord') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        YARD Wise Assigned <br /><span style="padding-right:30px;"></span>Equipment List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/monthlyYardWiseContainerHandling') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        Yard Wise Total <br /><span style="padding-right:30px;"></span> Container
                                        Handling
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/blockWiseEquipmentList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        Container Handling Equipment <br /><span style="padding-right:30px;"></span>
                                        Assign
                                    </a>
                                </li>

                                <!--li><a href="<?php echo site_url('UploadExcel/equipmentDemandList') ?>">Container Handling Equipment Demand</a></li-->
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/equipmentHandlingDemandForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>
                                        Container Handling Equipment <br /><span style="padding-right:30px;"></span>
                                        Demand
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/updateEquipmentList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Update Equipment Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dateWiseEqipAssignForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Block Wise Equipment <br /><span
                                            style="padding-right:30px;"></span> Booking Lists
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerHandlingRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Container Handling Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerHandlingRptMonthlyForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Monthly Container Handling
                                        <br /><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/plannedRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Container Job Done Vesselwise
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/equipmentEntryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Entry Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/mis_equipment_cur_stat_rpt') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Current Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/mis_equipment_indent_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Indent Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/equipmentUnstuffing') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('misReport/equipmentUnstuffingList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/cargoHandlingEquipmentPositionEntry') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br /><span
                                            style="padding-right:30px;"></span>Position
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/cargoHandlingEquipmentRemarks') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br /><span
                                            style="padding-right:30px;"></span>Remarks
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dailyEquipmentBookingOpPosition') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i> Daily Equipment Booking<br /><span
                                            style="padding-right:30px;"></span> Operator Position
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrContHandlingForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Cont.<br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/blockWiseEquipmentHandlingReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>BlockWise Equipment Handling
                                        <br /><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>

                            </ul>
                        </li>



                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('ShedBillController/truckEntryByDSForm') ?>"><i class="fa fa-mail-forward (alias)"></i>TRUCK ENTRY BY CTMS</a></li-->
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                                <li><a href="<?php echo site_url('Login/changePassForClient') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change for Client</a></li>
                                <?php  
								if($this->session->userdata('Control_Panel')==28)
								{
								?>
                                <li><a href="<?php echo site_url('Report/goods_report') ?>" target="_blank"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Report</a></li>
                                <li><a href="<?php echo site_url('Report/goods_report_new') ?>" target="_blank"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Report New</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/goods_report_rice') ?>" target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Goods Report Rice
                                    </a>
                                </li>
                                <!--li><a href="<?php echo site_url('Report/userList') ?>"><i class="fa fa-mail-forward (alias)"></i>User List</a></li-->
                                <li><a href="<?php echo site_url('Report/userList/active') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List (Active)</a></li>
                                <li><a href="<?php echo site_url('Report/userList/inactive') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List (Inactive)</a></li>
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization Profile List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/orgUserReportForm')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Organization User Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/organizationTypeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Organization Type List</a></li>
                                <li><a href="<?php echo site_url('Report/usrCreationForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Create User</a></li>
                                <li>
                                    <a href="<?php echo site_url('menuDesignController/sectionDetailsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Section Details Form
                                    </a>
                                </li>

                                <?php 
								}
								?>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- ADMIN PANEL end -->
                        <!--FREIGHT Panel Start-->
                        <?php if($this->session->userdata('Control_Panel')==4 && $this->session->userdata('org_Type_id')==4)
						{ 
						?>
                        <li>
                            <a><span>FREIGHT PANEL</span></a>

                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Login/organizationProfileList/0')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Organization List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span> LCL Assignment</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL Assignment Report
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>STUFFING REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours <br /><span
                                            style="padding-right:30px;"></span> Stuffing Container List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">


                                <li>
                                    <a href="<?php echo site_url('EDOController/pendingEDOapplication') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING APPLICATION FOR <br /><span
                                            style="padding-right:30px;"></span>EDO
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>UPLOADED EDO LIST
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('EDOController/tokenDistributionList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TOKEN COLLECTION LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationforEdoWithoutcnf') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>EDO Upload without C&F <br><span
                                            style="padding-right:30px;"></span>Application</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoContainerReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO CONTAINER REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span> LCL Location Certify</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('Report/certificationFormHtml') ?>">VIEW CERTIFICATION</a></li-->
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Location/Certify
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li><a href="<?php echo site_url('Report/myPortCodeList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Port Code List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVessel') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Wise Pre-Advised Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Agent Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myOffdocWiseContainerStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span>Container Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myAgentWiseVesselInfo') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Transit Ways <br /><span
                                            style="padding-right:30px;"></span> Information
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWisePreadviceloadedContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Pre-Advised <br /><span
                                            style="padding-right:30px;"></span> Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Loaded Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Import Discharge and <br /><span
                                            style="padding-right:30px;"></span> Balance Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/doReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery Order Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContainerGateInForm') ?>"><i
                                            class="fa fa-mail-forward (alias)">
                                        </i>Export Container Gate <br /><span style="padding-right:30px;"></span> in
                                        List
                                    </a>
                                </li>
                                <!-- From MLO Panel --->
                                <li>
                                    <a href="<?php echo site_url('Report/dischargeListForMLO') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Discharge List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Current Yard Lying <br /><span
                                            style="padding-right:30px;"></span> Contianer Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export <br /><span
                                            style="padding-right:30px;"></span> apps Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesselBillList/1') ?>" target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Bill List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL TALLY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/tallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TALLY LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						} 
						?>
                        <!--FREIGHT Panel END-->

                        <!-- P Shed Panel start -->
                        <?php
						if($this->session->userdata('Control_Panel')==72 && $this->session->userdata('org_Type_id')==72)
						{
						?>
                        <li>
                            <a><span>P SHED</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--li>
							<a href="<?php echo site_url('Report/pShedTallyEntryWithIgmInfoForm') ?>" >
								<i class="fa fa-mail-forward (alias)"></i>2.3 Tally Entry With<br>
								<span style="padding-right:30px;"></span>IGM Information(P Shed)
							</a>
						</li-->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TALLY ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('PShedController/pShedTallyEntryWithIgmInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.1 Tally Entry With IGM<br>
                                        <span style="padding-right:30px;"></span>Information(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/pShedTallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.2 Tally List(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/dateWisePShedTallyListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.3 Date Wise Tally Receive<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('PShedController/dateWisePShedTallyDeliveryReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>1.4 Date Wise Tally Delivery<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('PShedController/dateWisePShedTallyBalanceReportForm') ?>"
                                        target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>1.5 Tally Balance<br>
                                        <span style="padding-right:30px;"></span>(Chemical Shed)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- P Shed Panel end -->

                        <!-- Freight Forwarder Associaion -->
                        <?php 
						if($this->session->userdata('Control_Panel')==72 && $this->session->userdata('org_Type_id')==73)
						{
						?>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELETRONIC DELIVERY ORDER</span></a>
                            <ul class="nav nav-children">
                                <!-- <li>
									<a href="<?php echo site_url('EDOController/applicationForEDObyrotationBL') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Application
									</a>
								</li> 
								<?php if($lid!="baffa") { ?>
								<li>
									<a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>APPLICATION FOR EDO LIST
										
									</a>
								</li>
								<?php } ?>-->
                                <li>
                                    <a href="<?php echo site_url('EDOController/tokenDistributionForm/NULL') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TOKEN DISTRIBUTION
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/tokenDistributionList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TOKEN DISTRIBUTION LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/dateWiseTokenDist') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DATE WISE TOKEN <br>
                                        <span style="padding-right:30px;"></span>DISTRIBUTION LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/organizationWiseTokenReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ORGANIZATION WISE TOKEN <br>
                                        <span style="padding-right:30px;"></span>REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL ASSIGNMENT SECTION</span></a>
                            <ul class="nav nav-children">
                                <!--li>
									<a href="<?php echo site_url('CfsModule') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>1.1 LCL Assignment Entry Form
									</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('CfsModule/lclAssignmentReportTable') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL Assignment Report
                                    </a>
                                </li>
                                <!--li>
									<a href="<?php echo site_url('Report/assignmentListForm_2') ?>">
										<i class="fa fa-mail-forward (alias)"></i>1.3 ASSIGNMENT LIST
									</a>
								</li-->
                                <!--li>
									<a href="<?php echo site_url('Report/lcldeliveryList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>1.4 LCL Delivery Indent Entry
									</a>
								</li-->
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- Freight Forwarder Associaion -->

                        <!-- CPA Administration -->
                        <?php if($this->session->userdata('org_Type_id')==74) { ?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/slotWiseLyingContainer') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Slot Wise Lying Container </a></li>
                                <li><a href="<?php echo site_url('Report/operatorWiseEquipmentHandling') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Operator's RTG Handling
                                        Performance</a></li>
                                <li><a href="<?php echo site_url('Report/operatorWiseSCHandling') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Operator's SC Handling
                                        Performance</a></li>
                                <li><a href="<?php echo site_url('Report/myEquipmentHandlingHistory') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Equipment Handling
                                        Performance(RTG)</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DELV.ORDER
                                    REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status
                                        <br><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span
                                            style="padding-right:30px;"></span>Empty Details</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Termianl wise special <br>
                                        <span style="padding-right:30px;"></span>assignment</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/smsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment SMS Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/outerAnchorageForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Movement Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container / BL Block Release <br>
                                        <span style="padding-right:30px;"></span>List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/gateWiseTruckEntryByScanningForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Wise Truck Entry
                                        <br><span style="padding-right:30px;"></span>(Scanning)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HARDWARE REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/workstationReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
                                <li><a href="<?php echo site_url('Report/product_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php }	?>
                        <!-- CPA Administration -->

                        <?php 
						if($this->session->userdata('Control_Panel')==22 && $this->session->userdata('org_Type_id')==6)
						{ 
						?>
                        <li>
                            <a><span>OFFDOCK PANEL</span></a>
                        </li>

                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Container Location
                                        Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/dlvFromOffdock') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery From Offdock
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dlvFromOffdockReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery From Offdock Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myBlockedContainerView') ?>" target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Blocked Container Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offDockContListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>POSITION WISE PRE ADVISED <br>
                                        <span style="padding-right:30px;"></span>CONTAINER
                                        LIST</a></li>
                                <li><a href="<?php echo site_url('Report/offDockContSearchForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PRE ADVISED CONTAINER <br>
                                        <span style="padding-right:30px;"></span>SEARCH </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/dateWiseoffDockContListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>DATE WISE PRE ADVISED <br>
                                        <span style="padding-right:30px;"></span>CONTAINER
                                        LIST</a></li>
                                <li><a href="<?php echo site_url('Report/preAdvisedOffDockContByRotForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ROTATION WISE PRE-ADVISED<br>
                                        <span style="padding-right:30px;"></span>CONTAINERS</a></li>

                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>OFFDOCK INFORMATION</a></li>

                                <li><a href="<?php echo site_url('Report/offdocdepotLadenCont') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>LADEN CONTAINER</a></li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOCATION \ CERTIFY</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LAST 24 STATEMENT</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatementList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LAST 24 STATEMENT LIST</a></li>
                                <li><a href="<?php echo site_url('Report/barcodeTestForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PRINT BARCODE</a></li>
                                <li><a href="<?php echo site_url('Report/stuffingContainerListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LAST 24 HOURS STUFFING <br>
                                        <span style="padding-right:30px;"></span>CONTAINER
                                        LIST</a></li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('UploadExcel/uploadBlockedContainerForm') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>EXCEL UPLOAD
                                        BLOCKED<br>
                                        <span style="padding-right:30px;"></span>CONTAINER
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/stuffingContExcel') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>UPLOAD EXCEL FOR
                                        LAST 24<br>
                                        <span style="padding-right:30px;"></span>HOURS STUFFING CONTAINER
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-book" aria-hidden="true"></i><span>MANUAL</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo BASE_PATH.'resources/OffdockManual/CTMS Off-Dock User Manual.pdf' ?>"
                                        target="blank">
                                        <i class="fa fa-mail-forward (alias)"></i>OFF-DOCK MANUAL
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_PATH.'resources/OffdockManual/iso_code.xls' ?>"
                                        target="blank">
                                        <i class="fa fa-mail-forward (alias)"></i>ISO CODE LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_PATH.'resources/OffdockManual/mlo_code.xls' ?>"
                                        target="blank">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO CODE LIST
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li class="nav-parent">
                            <a><i class="fa fa-user" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}
						?>
                        <!-- CPA Administration -->

                        <!-- CPA Yard -->
                        <?php 
						if($this->session->userdata('Control_Panel')==75 && $this->session->userdata('org_Type_id')==75)
						{ 
						?>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Container Location
                                        Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>YARD OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/emergencyTruckList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Emergency Truck List</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loading Process</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/dusputeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Dispute List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessForm/tra') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>TRAFFIC CONFIRMATION <br>
                                        <span style="padding-right:30px;"></span>PROCESS
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/confirmationProcessFormForCf') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CONFIRMATION for C&F
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ASSIGNMENT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br>
                                        <span style="padding-right:30px;"></span>Empty Details
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CART</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/headDeliveryRegister') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>HEAD DELIVERY REGISTER <br>
                                        <span style="padding-right:30px;"></span>REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}
						?>
                        <!-- CPA Yard -->

                        <!-- Head Delivery Register -->
                        <?php 
						if($this->session->userdata('Control_Panel')==76 && $this->session->userdata('org_Type_id')==76)
						{ 
						?>
                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='fa fa-mail-forward (alias)' aria-hidden="true"></i>Container Location
                                        Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>YARD OPERATION</span></a>
                            <ul class="nav nav-children">

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckByContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loading Process</a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('LCL/loadingFormLcl') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LOADING PROCESS for LCL</a>
                                </li>

                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}
						?>
                        
                        <!-- Head Delivery Register -->

                        <!--  CPAOPS Starts -->

                        <?php 
						if($this->session->userdata('Control_Panel')==12 && $this->session->userdata('org_Type_id')==5 && $this->session->userdata('login_id')== 'cpaops')
						{ 
						?>
                        <li class="nav-parent">

                        <li class="nav-parent">
                            <a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO
                                    LOCATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
                                        <i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container
                                        Location Search
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <?php if($this->session->userdata('login_id')!= 'cpaops'){?>
                        <li>
                            <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>" target="_BLANK">
                                <i class="fa fa-mail-forward (alias)"></i>View IGM General Information
                            </a>

                        </li>

                        <li>
                            <a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>" target="_BLANK"><i
                                    class="fa fa-mail-forward (alias)">
                                </i>View IGM Break Bulk Information
                            </a>
                        </li>
                        <?php }?>
                        <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                    class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                        <li>
                            <a href="<?php echo site_url('IgmViewController/downloadBL') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Download B/L Informatin
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('IgmViewController/myIGMContainer') ?>" target="_BLANK"><i
                                    class="fa fa-mail-forward (alias)">
                                </i>IGM Container List
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('IgmViewController/checkTheIGM') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Check The IGM
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('IgmViewController/viewIGM') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>View IGM
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Report/IgmReportbyDescription') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Report by Description of Goods
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Report/IgmReportbyImporter') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Report by Importer Name
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Report/IgmReportImporterList') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Importer List
                            </a>
                        </li>



                        <li>
                            <a href="<?php echo site_url('Report/IgmReportbyContainer') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Report by Container
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo site_url('Report/IgmReportbyBL') ?>">
                                <i class="fa fa-mail-forward (alias)"></i>Report by BL No
                            </a>
                        </li>

                        <?php 
						}
						?>

                        <!--  CPAOPS Ends -->

                        <!-- Auction Module Starts -->
                        <?php 
						// if($this->session->userdata('Control_Panel')==77 && $this->session->userdata('org_Type_id')==77)
						// {
						?>
                        <!--li class="nav-parent">
							<a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO LOCATION</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
										<i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container Location Search
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
									</a>
								</li>
							</ul>		
						</li>						
						<li class="nav-parent">
							<a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>IGM OPERATION</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i>View IGM General Information
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('IgmViewController/viewIgmGeneral/BB') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i>View IGM Break Bulk Information
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Location/Certify
									</a>
								</li>
							</ul>		
						</li>
						<li class="nav-parent">
							<a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>IGM Reports</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
								<li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
								<li>
									<a href="<?php echo site_url('Report/dischargeListForMLO') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Discharge List 
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
										<span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/') ?>"><i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary List</a></li>
							</ul>		
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span> Auction Report </span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
										<span style="padding-right:30px;"></span>Generation List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover List
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Login/myPasswordChange') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Password Change
									</a>
								</li>
							</ul>
						</li-->
                        <?php 
						// }	
						?>
                        <!-- Auction Module Ends -->

                        <!-- Driver Module Starts - Truck Entry Panel  -->

                        <?php 
						if($this->session->userdata('org_Type_id')==79)
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/driverDashboard') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Truck Entry (Driver)
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/gatePassForDriverForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PRINT GATE PASS
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/contWiseGatePassPrintForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CONTAINER WISE GATE PASS <br>
                                        <span style="padding-right:30px;"></span>PRINT
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/contWiseUnpaidTruckForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>CONTAINER WISE UNPAID<br>
                                        <span style="padding-right:30px;"></span>TRUCK LIST
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- Driver Module Ends  -->

                        <!-- CPA Monitor Panel-->
                        <?php
						if($this->session->userdata('Control_Panel')==84)
						{
						?>
                        <li>
                            <a><span>ADMIN PANEL</span></a>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DLV.ORDER REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Details)</a>
                                    </a>
                                </li>
                                <!--li>
									<a href="<?php echo site_url('EDOController/organizationWiseEDOSummaryForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
									</a>
								</li-->
                                <li>
                                    <a
                                        href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date & Organization Wise
                                        </br><span style="padding-right:30px;"></span> Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>EDO Verification Report
                                        <br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status
                                        <br><span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('EDOController/organizationWiseTokenReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>ORGANIZATION WISE TOKEN <br>
                                        <span style="padding-right:30px;"></span>REPORT
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Login/OrgProfileForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Organization Profile Entry
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Login/organizationProfileList/0')?>">
										<i class="fa fa-mail-forward (alias)"></i>Organization List
									</a>
								</li>
								
							</ul>
						</li-->

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/truckEntryByAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FCL Truck Entry
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/lcltruckEntryByAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>LCL Truck Entry
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/changeTruckInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Change Truck Entry Info
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/gateWiseTruckEntryByScanningForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Gate Wise Truck Entry
                                        <br><span style="padding-right:30px;"></span>(Scanning)
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- <li class="nav-parent">
							<a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO LOCATION</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/mySearchContainerLocationForm') ?>">
										<i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container Location Search
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/lclAssignmentCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
									</a>
								</li>
							</ul>
						</li> -->

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL LAYOUT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vslLayout') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>New Vessel Layout</a></li>
                                <li><a href="<?php echo site_url('Report/deleteWrongBay') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Delete Wrong Bay</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/updateVslForExpCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Update Vessel for Export<br>
                                        <span style="padding-right:30px;"></span>Containers
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/updateVisitForPctCont') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Update Visit for PANGAON<br>
                                        <span style="padding-right:30px;"></span>Containers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General
                                        Information</a></li>

                                <!-- dont by other -->
                                <li>
                                    <a href="<?php echo site_url('igmViewController/viewIgmGeneral/BB') ?>"
                                        target="_BLANK">
                                        <i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Information
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('igmViewController/myIGMContainer') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a>
                                </li>
                                <li><a href="<?php echo site_url('igmViewController/checkTheIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
                                <li><a href="<?php echo site_url('igmViewController/updateManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>

                                <li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert EXPORT-COMMODITY</a></li>
                                <!--li><a href="<?php echo site_url('igmViewController/checkTheIGM') ?>"><i class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li-->

                                <li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/convertIgmCertifySection') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify<br>
                                        <span style="padding-right:30px;"></span>Section
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
                                <li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>"
                                        target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Routing Points</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>
                                <li><a href="<?php echo site_url('Report/deleteIGMInfo') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Delete IGM Information</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/igmInfoProcessForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Information Entry</a></li>
                                <li><a href="<?php echo site_url('IgmViewController/igm_sup_dtl_entry_form') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Entry</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/offDockReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Destination Change<br>
                                        <span style="padding-right:30px;"></span>to Offdock
                                    </a>
                                </li>

                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
                                <li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myIGMFFReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break<br>
                                        <span style="padding-right:30px;"></span>Bulk Reports
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
                                <!--li><a href="<?php echo site_url('Report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Menifest</a></li-->
                                <li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
                                <li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
                                <!--li>
										<a href="<?php echo site_url('	Report/myDGContainer') ?>">
											<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
										</a>
								</li-->
                                <li>
                                    <a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
                                        <span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary<br>
                                        <span style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/OffDockContainerList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <!--li><a href="<?php echo site_url('Report/dgContainerByRotation') ?>" ><i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation</a></li-->
                                <li><a href="<?php echo site_url('Report/valuableItemByRotation') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Valuable Item By Rotation</a></li>
                            </ul>
                        </li>


                        <li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG
                                    REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myDGManifest') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Manifest
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dg_report') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
                                        <span style="padding-right:30px;"></span>DELIVERY REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span
                                            style="padding-right:30px;"></span>List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dgContainerByRotation') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myDGContainer') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeSummary') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>Summary List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('report/dgDischargeList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
                                        <span style="padding-right:30px;"></span>List by Rotation
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
                                        <span style="padding-right:30px;"></span>APPS Loading Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
                                        <span class="blink_me"><i class="fa fa-mail-forward (alias)"></i>Comments by
                                            Shipping<br>
                                            <span style="padding-right:30px;"></span>Section on Export Vessel</span>
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/workDone24hrsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 hrs. Container<br>
                                        <span style="padding-right:30px;"></span>Handling Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final Loading<br>
                                        <span style="padding-right:30px;"></span>Export APPS
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPAN4') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
                                        <span style="padding-right:30px;"></span>Loading Export
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignment_sheet_for_pangaon') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment Sheet for Outward<br>
                                        <span style="padding-right:30px;"></span>PANGAON ICT Container
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement List</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Statement</a></li>

                                <li><a href="<?php echo site_url('Report/stuffingPermissionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
                            <ul class="nav nav-children">


                                <li>
                                    <a href="<?php echo site_url('Report/dlvFromOffdock') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery From Offdock
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span
                                            style="padding-right:30px;"></span>Empty Details</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Terminal wise special <br>
                                        <span style="padding-right:30px;"></span>assignment</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/searchIGMByContainer') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Search IGM Container</a></li>
                                <li><a href="<?php echo site_url('Report/myoffDociew') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/myoffDocEntryForm')?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Offdock Information <br />
                                        <span style="padding-right:30px;"></span> Entry Form
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
                                <li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/vesselEventHistory') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Event History
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerEventHistory') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Event History
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/showProcessList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>System Status
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerDischargeAppsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Discharge(APPS)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/containerSearchByRotationAppsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Search for APPS
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offdock Removal Position</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
                                        <span style="padding-right:30px;"></span>Container List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Statement List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/barcodeTestForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Print Barcode
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Statement
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/stuffingPermissionForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form
                                    </a>
                                </li>

                                <li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
                                <li>
                                    <a href="<?php echo BASE_PATH.'assets/goods_vessel_tariff/CPA-Tariff-ilovepdf-compressed.pdf' ?>"
                                        target="blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Tariff on Goods, Vessel etc.
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/vesselBillList/1') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Vessel Bill List</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/checkVatStatusForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>VAT Status
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/slaveProcess/') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Slave Process</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/exportContainerLoadingList') ?>"
                                        target="_blank">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container Loading List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/myExportContainerNotFoundReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Export Container <br>
                                        <span style="padding-right:30px;"></span>NOT FOUND Report
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/offhireSummaryAndDetails') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Offhire Summary & Details</a></li>
                                <li>
                                    <a href="<?php echo site_url('bill/containerBillListVersion/1') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Bill<br>
                                        <span style="padding-right:30px;"></span>List (Version)
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>
                                <li><a href="<?php echo site_url('Report/pilot_vsl_entry_rpt') ?>" target="_BLANK"><i
                                            class="fa fa-mail-forward (alias)"></i>Pilot Vessel Entry Report</a></li>

                                <li>
                                    <a href="<?php echo site_url('ShedBillController/shedWiseLyingTallyListForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"> </i>Shed wise Lying Tally List
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container / BL Block Release <br>
                                        <span style="padding-right:30px;"></span>List</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/smsReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Assignment SMS Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/outerAnchorageForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Vessel Movement Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HARDWARE REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/workstationReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
                                <li><a href="<?php echo site_url('Report/product_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>INVENTORY LIST</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/product_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Type List</a></li>
                                <li><a href="<?php echo site_url('Report/location_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location List</a></li>
                                <li><a href="<?php echo site_url('Report/location_details_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Location Details List</a></li>
                                <li><a href="<?php echo site_url('Report/product_user_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>User List</a></li>
                                <li><a href="<?php echo site_url('Report/workstationList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductEntryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductReceiveList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Received List</a></li>
                                <li><a href="<?php echo site_url('Report/networkProductDeliveryList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Delivery List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>INVENTORY REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/workstationReport') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
                                <li><a href="<?php echo site_url('Report/product_report_search') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
                                        <span style="padding-right:30px;"></span>Generation List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Auction Handover List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER REPORTS</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
                                        <span style="padding-right:30px;"></span>Import Container
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>MIS REPORT</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('misReport/A23_1Form') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Performance Container<br>
                                        <span style="padding-right:30px;"></span>Vessels Last 24hrs (A23.1)
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsContInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information<br>
                                        <span style="padding-right:30px;"></span>(Garments Item) by Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/searchGarmentsItemByRotationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Container Information by<br>
                                        <span style="padding-right:30px;"></span>Item & Rotation
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrPositionForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Position
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/dailyExportContGateIn') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Daily Export Container<br>
                                        <span style="padding-right:30px;"></span>Gate In
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last45DayslyingReportLink') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 45 Days Lying<br>
                                        <span style="padding-right:30px;"></span>Food Items Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/last24HrsCPAToOffdockRemovalForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hours CPA to<br>
                                        <span style="padding-right:30px;"></span>OFFDOCK removal
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/garmentsItemLyingAndDeliveryInfoForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Garments Item Lying and<br>
                                        <span style="padding-right:30px;"></span>Delivery Information
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SCY OPERATION</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/rotationWiseContainerPosition') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Rotation Wise Container Position
                                    </a>
                                </li>
                                <li><a href="<?php echo site_url('Report/containerOperationReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Operation Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PILOTAGE CERTIFICATE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/vesselListForPilotage') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Certificate</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
                            <ul class="nav nav-children">
                                <!-- done by other -->
                                <?php
								include("dbConection.php");
								$str = "SELECT COUNT(DISTINCT rotation) AS cnt
								FROM ctmsmis.mis_exp_unit_preadv_req
								WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
								$result = mysqli_query($con_sparcsn4,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
							?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation</a></li>

                                <?php
								include("mydbPConnection.php");
								$str = "select count(id) as cnt from edi_stow_info where file_status=0";
								
								$result = mysqli_query($con_cchaportdb,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
								//echo "TEST : ".$badge;
							?>
                                <?php 
								
								include("dbConection.php");
							?>
                                <li class="badge1" data-badge="<?php echo $badge;?>"><a
                                        href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Today's EDI Declaration</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
                                <li><a href="<?php echo site_url('UploadExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO</a>
								</li>
                                <!-- done by other -->
								
								<!-- nadim change start -->
								<li>
									<a href="<?php echo site_url('UploadExcel/copern_copino_list') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Coparn Copino Declaration List
									</a>
								</li>
								<!-- nadim change end -->
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/stuffingContExcel') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Excel for Last 24<br>
                                        <span style="padding-right:30px;"></span>Hours Stuffing Container</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/exportExcelUploadForAdmin') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Export Container<br>
                                        <span style="padding-right:30px;"></span>(Excel File)</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/last24hrPerformancePdfForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Last 24 Hour Performance<br>
                                        <span style="padding-right:30px;"></span>PDF File Upload
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/handling_performance_compare') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Handling Performance Compare
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('UploadExcel/readObpcForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Upload Excel for<br>
                                        <span style="padding-right:30px;"></span>OBPC & RL
                                    </a>
                                </li>
								
								
                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>NOTICE</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/noticeUploadForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Notice Upload Form</a></li>
                                <li><a href="<?php echo site_url('Report/noticeUploadList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Notice Upload List</a></li>
                            </ul>
                        </li>

                        <!-- done by other -->
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>LCL ASSIGNMENT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('CfsModule')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Entry Form</a></li>
                                <li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable')?>"><i
                                            class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a></li>
                            </ul>
                        </li>


                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>DOWNLOAD</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/myBillSummaryForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill Summary</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>PANGAON</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/pangoanContUpload') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Excel Upload for PANGAON</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertPanContForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Convert PANGAON Containers</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ICD</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('UploadExcel/uploadIcdExcel') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Upload</a></li>
                                <li><a href="<?php echo site_url('UploadExcel/convertIcdFileForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>ICD Excel File Converter</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_bill_entry') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry Report</a></li>
                                <li><a href="<?php echo site_url('Report/date_wise_be_report') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Date Wise Bill of Entry Report</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/head_delivery') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Container Search & Truck Entry</a>
                                </li>
                                <li><a href="<?php echo site_url('Report/be_error_list') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Bill of Entry Error List</a></li>
                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>GOODS REPORT</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Report/goodsWiseReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Wise Report</a></li>
                                <li><a href="<?php echo site_url('Report/itemGoodsWiseReportForm') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Goods Report Active Yard</a></li>
                                <li>
                                    <a href="<?php echo site_url('Report/itemWiseSummaryDetailsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Item Wise Summary & Details<br>
                                        <span style="padding-right:30px;"></span>Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('Report/itemWiseLyingSummaryDetailsForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Item Wise Lying Summary and <br>
                                        <span style="padding-right:30px;"></span>Details Report
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>SHED BILL PANEL</span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('Report/shedBillUrls') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>SHED BILL URLs
                                    </a>
                                </li>
                            </ul>
                        </li>





                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <!--li><a href="<?php echo site_url('ShedBillController/truckEntryByDSForm') ?>"><i class="fa fa-mail-forward (alias)"></i>TRUCK ENTRY BY CTMS</a></li-->
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>

                            </ul>
                        </li>
                        <?php 
						}	
						?>
                        <!-- CPA Monitor Panel Ends-->

                        <!-- Gate Sergeant Module Starts  -->

                        <?php 
						if($this->session->userdata('org_Type_id') == 85)
						{
						?>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>CART TICKET & CHALLAN </span></a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('ShedBillController/chalanGenerationForm') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Challan Generation
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- Gate Sergeant Module Ends  -->


                        <!-- Fire Module Starts  -->

                        <?php 
						if($this->session->userdata('org_Type_id') == 87)
						{
						?>
                      <li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>HOT WORK SERVICE </span></a>
							<ul class="nav nav-children">
								<!--li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Demand List
									</a>
								</li-->
								<!--li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandApproveList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Demand Approved<br>
										<span style="padding-right:30px;"></span> List
									</a>
								</li-->
								
								<!--li>
									<a href="<?php echo site_url('Vessel/fireHotWorkDemandForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service Form
									</a>
								</li-->
								<li>
                                <a href="<?php echo site_url('Vessel/fireWorkServiceFormExtended') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service Form <br>
									<span style="padding-right:30px;"></span>Extended
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Vessel/hotWorkDemandApproveList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Hot Work Service List
										
									</a>
								</li>
							
							</ul>		
						</li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- Fire Module Ends  -->

                        <!-- Exen Module Starts  -->

                        <?php 
						if($this->session->userdata('org_Type_id') == 88)
						{
						?>
                        <li class="nav-parent <?php if($menu=="Bill"){ ?> nav-expanded nav-active <?php } ?>">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>WATER SUPPLY AND FORWARD</span></a>
                            <ul class="nav nav-children">
                                <li class="<?php if($sub_menu=="waterDemandList"){?>nav-active<?php }?>">
                                    <a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- Exen Module Ends  -->

                        <!-- Sub Assistant Engineer Module Starts  -->

                        <?php 
						if($this->session->userdata('org_Type_id') == 89)
						{
						?>
                        <li class="nav-parent <?php if($menu=="Bill"){ ?> nav-expanded nav-active <?php } ?>">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>WATER SUPPLY AND FORWARD</span></a>
                            <ul class="nav nav-children">
                                <li class="<?php if($sub_menu=="waterDemandList"){?>nav-active<?php }?>">
                                    <a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Demand List</a>
                                </li>
                                <li class="<?php if($sub_menu=="waterBillDocumentForwarding"){?>nav-active<?php }?>">
                                    <a href="<?php echo site_url('Vessel/waterBillDocumentForwarding') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Water Bill Document <br>
                                        <span style="padding-right:30px;"></span>Forwarding</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                            <a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
                            <ul class="nav nav-children">
                                <li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
                                <li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i
                                            class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
                            </ul>
                        </li>
                        <?php 
						}	
						?>

                        <!-- Sub Assistant Engineer Module Ends  -->

                        <!-- Electrical Engineer Module Starts  -->
						
						<?php 
						if($this->session->userdata('org_Type_id') == 78)
						{
							$login_id = $this->session->userdata('login_id');
							$section_query = "SELECT section_value FROM users 
								INNER JOIN tbl_org_section ON tbl_org_section.gkey = users.section
								WHERE login_id = '$login_id'";
							$section_result = $this->bm->dataSelectDB1($section_query);

							$eng_section = null;
							if(count($section_result)>0)
							{
								$eng_section = $section_result[0]['section_value'];
							}
						?>
						<li class="nav-parent " >
							<a><i class="fa fa-list" aria-hidden="true"></i><span>WATER SUPPLY AND FORWARD</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Vessel/waterDemandList') ?>"><i class="fa fa-mail-forward (alias)"></i>
									<?php
										$allowedSub = array('SAECCT','SAEGCB','SAENCT');
										$srEng = array('AENG','SRSAE','EXENCT','DCEE');
										if(in_array($eng_section,$allowedSub)){
									?>
									Water Demand List
									<?php
										}else if(in_array($eng_section,$srEng)){
									?>
									Water Supply Document <br><span style="padding-right:33px;"></span>Forwarding
									<?php
										}
									?>
									</a>
								</li>
								<?php
									$allowed = array('SAECCT','SAEGCB','SAENCT');
									if(in_array($eng_section,$allowed)){
								?>	
								<li>
									<a href="<?php echo site_url('Vessel/waterSpplyByLineForwarding') ?>"><i class="fa fa-mail-forward (alias)"></i>Water Supply Document <br><span style="padding-right:30px;"></span>Forwarding</a>
								</li>
								<?php
									}
								?>						
							</ul>
						</li>	
						<li class="nav-parent <?php if($menu=="shoreCrane"){ ?> nav-expanded nav-active <?php } ?>">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>SHORE CRANE</span></a>
							<ul class="nav nav-children">								
								<li class="<?php if($sub_menu=="shoreCraneDemandList"){?>nav-active<?php }?>">
									<a href="<?php echo site_url('Vessel/shoreCraneDemandList') ?>">
										<i class='fa fa-mail-forward (alias)'></i>Shore Crane Demand List
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ELECTRONIC DLV.ORDER REPORT</span></a>
								<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('EDOController/applicationForEDOList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>PENDING EDO LIST
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('EDOController/ApprovedForEDOList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>APPROVED EDO LIST
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('EDOController/edoVerificationReportFrom') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Details)</a>
									</a>
								</li>
								<!--li>
									<a href="<?php echo site_url('EDOController/organizationWiseEDOSummaryForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
									</a>
								</li-->
								<li>
									<a href="<?php echo site_url('EDOController/edoDateAndOrganizationWiseSummaryForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Date & Organization Wise </br><span style="padding-right:30px;"></span> Summary)</a>
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('EDOController/edoVerificationDateWiseReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Date Wise Summary)</a>
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('EDOController/edoVerificationOrgWiseReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>EDO Verification Report 
										<br><span style="padding-right:30px;"></span>(Organization Wise Summary)</a>
									</a>
								</li>	
								<li>
									<a href="<?php echo site_url('ShedBillController/beUploadStsReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Bill of Entry Upload Status 
										<br><span style="padding-right:30px;"></span>Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('EDOController/organizationWiseTokenReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>ORGANIZATION WISE TOKEN <br>
										<span style="padding-right:30px;"></span>REPORT
									</a>
								</li>
							</ul>
						</li>

						<?php
							if($login_id == 'MEZBAH'){
						?>

						<li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IMPORT REPORTS</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/assignmentAllReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br>
										<span style="padding-right:30px;"></span>Empty Details
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Special Assignment
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Termianl wise special <br>
									<span style="padding-right:30px;"></span>assignment</a>
								</li>
								
								<li><a href="<?php echo site_url('Report/assignment_summary') ?>"><i class="fa fa-mail-forward (alias)"></i>Assignment Summary</a></li>
								<li>
									<a href="<?php echo site_url('Report/yardWiseContainerHandlingDetailsRptForm') ?>">
									<i class="fa fa-mail-forward (alias)"></i>Current Yard Lying<br>
										<span style="padding-right:30px;"></span>Container Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/yardWiseContainerHandlingRptForm') ?>">
									<i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
										<span style="padding-right:30px;"></span>Receive Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/yardWiseContainerDeliveryRptForm') ?>">
									<i class="fa fa-mail-forward (alias)"></i>Yard Wise Container<br>
										<span style="padding-right:30px;"></span>Delivery Report
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/containerPositionEntryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Position Entry</a></li>
								<li>
									<a href="<?php echo site_url('Report/blockWiseRotation') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Import Discharge Report<br>
										<span style="padding-right:30px;"></span>by apps
									</a>
								</li>	
								<li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Offdock Removal Container</a></li>
								<li><a href="<?php echo site_url('Report/countryWiseImportReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Country Wise Import Report</a></li>
								<li><a href="<?php echo site_url('Report/yearWiseImportReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Year Wise Import Report</a></li>
								<li>
									<a href="<?php echo site_url('Report/mloWiseFinalDischargingImportFormForCPA') ?>">
										<i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
										<span style="padding-right:30px;"></span>Discharging Import
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/pangoanDischargeForm') ?>"><i class="fa fa-mail-forward (alias)"></i>PANGAON Discharge</a></li>
								<li><a href="<?php echo site_url('Report/removal_list_form/overflow') ?>"><i class="fa fa-mail-forward (alias)"></i>Removal List OF Overflow Yard</a></li>
								<li><a href="<?php echo site_url('Report/removal_list_form/all') ?>"><i class="fa fa-mail-forward (alias)"></i>List of CTMS Assignment</a></li>
								<li><a href="<?php echo site_url('Report/yardWiseImportTotalReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Yard Wise Assignment <br> <span style="padding-right:30px;"></span>Summary</a></li>
								
								<li><a href="<?php echo site_url('Report/auctionContainers') ?>"><i class="fa fa-mail-forward (alias)"></i>Auction Handover Report</a></li>

								<li>
									<a href="<?php echo site_url('Report/contatOuterAnchorageForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Details at<br><span style="padding-right:30px;"></span>Outer Anchorage</a>
								</li>

								<li>
									<a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i class="fa fa-mail-forward (alias)">
										</i>Container / BL Block Release <br>
										<span style="padding-right:30px;"></span>List
									</a>
								</li>
							</ul>
						</li>

						<li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>IGM AMENDMENT</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('GateController/changeContStatusForm')?>"><i class="fa fa-mail-forward (alias)"></i>Change Container Status</a></li>
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>LCL TALLY</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/tallyEntryWithIgmInfoForm') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Tally Sheet Entry
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('report/tallyListForm') ?>"><i class="fa fa-mail-forward (alias)"></i>TALLY LIST</a>
								</li>
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>CART</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('ShedBillController/vcmsCartTicketList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Cart Ticket List
									</a>
								</li>
							</ul>
						</li>

						<li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>EQUIPMENT</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/myReportHHTReCord') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i>
										YARD Wise Assigned <br/><span style="padding-right:30px;"></span>Equipment List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/monthlyYardWiseContainerHandling') ?>">
										<i class="fa fa-mail-forward (alias)"></i>
										Yard Wise Total <br/><span style="padding-right:30px;"></span> Container Handling
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/blockWiseEquipmentList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>
										Container Handling Equipment <br/><span style="padding-right:30px;"></span> Assign
									</a>
								</li>
								
								<!--li><a href="<?php echo site_url('UploadExcel/equipmentDemandList') ?>">Container Handling Equipment Demand</a></li--> 
								<li>
									<a href="<?php echo site_url('UploadExcel/equipmentHandlingDemandForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>
										Container Handling Equipment <br/><span style="padding-right:30px;"></span> Demand
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/updateEquipmentList') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Update Equipment Information
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/dateWiseEqipAssignForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Block Wise Equipment <br/><span style="padding-right:30px;"></span> Booking Lists
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/containerHandlingRptForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Container Handling Report
									</a>
								</li>								
								<li>
									<a href="<?php echo site_url('Report/containerHandlingRptMonthlyForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Monthly Container Handling <br/><span style="padding-right:30px;"></span>Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/plannedRptForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Container Job Done Vesselwise
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/equipmentEntryForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Entry Form
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/mis_equipment_cur_stat_rpt') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Current Status
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('misReport/mis_equipment_indent') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Indent
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Indent List
									</a>
								</li>				
								<li>
									<a href="<?php echo site_url('misReport/mis_equipment_indent_report') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Indent Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('misReport/equipmentUnstuffing') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('misReport/equipmentUnstuffingList') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Equipment Unstuffing List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/cargoHandlingEquipmentPositionEntry') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br/><span style="padding-right:30px;"></span>Position
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/cargoHandlingEquipmentRemarks') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Cargo Handling Equipment <br/><span style="padding-right:30px;"></span>Remarks
									</a>
								</li>		
								<li>
									<a href="<?php echo site_url('Report/dailyEquipmentBookingOpPosition') ?>">
										<i class="fa fa-mail-forward (alias)"></i> Daily Equipment Booking<br/><span style="padding-right:30px;"></span>  Operator Position
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/last24HrContHandlingForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Cont.<br>
										<span style="padding-right:30px;"></span>Handling Report
									</a>
								</li>	
								<li>
									<a href="<?php echo site_url('Report/blockWiseEquipmentHandlingReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>BlockWise Equipment Handling <br/><span style="padding-right:30px;"></span>Report
									</a>
								</li>																				
							</ul>
						</li>

						<?php
							}
						?>
					
						<!--li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ORGANIZATION PROFILE</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php //echo site_url('Login/OrgProfileForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Organization Profile Entry
									</a>
								</li>
								<li>
									<a href="<?php //echo site_url('Login/organizationProfileList/0')?>">
										<i class="fa fa-mail-forward (alias)"></i>Organization List
									</a>
								</li>
								
							</ul>
						</li-->

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>TRUCK ENTRY</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('ShedBillController/truckEntryByAdmin') ?>">
										<i class="fa fa-mail-forward (alias)"></i>FCL Truck Entry
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('ShedBillController/lcltruckEntryByAdmin') ?>">
										<i class="fa fa-mail-forward (alias)"></i>LCL Truck Entry
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('ShedBillController/changeTruckInfoForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Change Truck Entry Info
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/gateWiseTruckEntryByScanningForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Gate Wise Truck Entry 
										<br><span style="padding-right:30px;"></span>(Scanning)
									</a>
								</li>
							</ul>
						</li>

						<!-- <li class="nav-parent">
							<a><i class="glyphicon glyphicon-search" aria-hidden="true"></i><span>CONTAINER / CARGO LOCATION</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php //echo site_url('Report/mySearchContainerLocationForm') ?>">
										<i class='glyphicon glyphicon-arrow-right' aria-hidden="true"></i>Container Location Search
									</a>
								</li>
								<li>
									<a href="<?php //echo site_url('Report/lclAssignmentCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container(LCL) Location Search
									</a>
								</li>
							</ul>
						</li> -->
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>VESSEL LAYOUT</span></a>
							<ul class="nav nav-children">							
								<li><a href="<?php echo site_url('Report/vslLayout') ?>"><i class="fa fa-mail-forward (alias)"></i>New Vessel Layout</a></li>
								<li><a href="<?php echo site_url('Report/deleteWrongBay') ?>"><i class="fa fa-mail-forward (alias)"></i>Delete Wrong Bay</a></li>
								<li>
									<a href="<?php echo site_url('Report/updateVslForExpCont') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Update Vessel for Export<br>
										<span style="padding-right:30px;"></span>Containers
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/updateVisitForPctCont') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Update Visit for PANGAON<br>
										<span style="padding-right:30px;"></span>Containers
									</a>
								</li>
							</ul>						
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>IGM OPERATION</span></a>
							<ul class="nav nav-children">							
								<li><a href="<?php echo site_url('IgmViewController/viewIgmGeneral/GM') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>View IGM General Information</a></li>
								
								<!-- dont by other -->
								<li>
									<a href="<?php echo site_url('igmViewController/viewIgmGeneral/BB') ?>" target="_BLANK">
										<i class="fa fa-mail-forward (alias)"></i>View IGM Break<br>
										<span style="padding-right:30px;"></span>Bulk Information
									</a>
								</li>
								<li><a href="<?php echo site_url('igmViewController/myIGMContainer') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>IGM Container List</a></li>
								<li><a href="<?php echo site_url('igmViewController/checkTheIGM') ?>"><i class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li>
								<li><a href="<?php echo site_url('igmViewController/updateManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>Convert IGM</a></li>
								
								<li><a href="<?php echo site_url('Report/myExportCommodityForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Convert EXPORT-COMMODITY</a></li>
								<!--li><a href="<?php echo site_url('igmViewController/checkTheIGM') ?>"><i class="fa fa-mail-forward (alias)"></i>Check The IGM</a></li-->
								
								<li><a href="<?php echo site_url('IgmViewController/viewIGM') ?>"><i class="fa fa-mail-forward (alias)"></i>View IGM</a></li>
								<li>
									<a href="<?php echo site_url('Report/convertIgmCertifySection') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Convert Igm to Certify<br>
										<span style="padding-right:30px;"></span>Section
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/onestopCertifySection') ?>"><i class="fa fa-mail-forward (alias)"></i>Location \ Certify</a></li>
								<li><a href="<?php echo site_url('Report/myRountingPointCodeList') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Routing Points</a></li>
								<li><a href="<?php echo site_url('Report/myViewBreakBulkList') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Break Bulk IGM Info</a></li>
								<li><a href="<?php echo site_url('Report/deleteIGMInfo') ?>"><i class="fa fa-mail-forward (alias)"></i>Delete IGM Information</a></li>
								<li><a href="<?php echo site_url('IgmViewController/igmInfoProcessForm') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Information Entry</a></li>
								<li><a href="<?php echo site_url('IgmViewController/igm_sup_dtl_entry_form') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Entry</a></li>
								<li>
									<a href="<?php echo site_url('Report/offDockReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>IGM Destination Change<br>
										<span style="padding-right:30px;"></span>to Offdock
									</a>
								</li>
								
							</ul>
						</li>
											
						
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>IGM REPORTS</span></a>
							<ul class="nav nav-children">							
								<li><a href="<?php echo site_url('Report/myIGMReport') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Reports</a></li>
								<li><a href="<?php echo site_url('Report/myIGMBBReport') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Reports Break Bulk</a></li>
								<li><a href="<?php echo site_url('Report/myIGMFFReport') ?>"><i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Reports</a></li>
								<li>
									<a href="<?php echo site_url('Report/myIGMFFReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>IGM Supplementary Break<br>
										<span style="padding-right:30px;"></span>Bulk Reports
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/myICDManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>ICD Menifest</a></li>
								<!--li><a href="<?php echo site_url('Report/myDGManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>DG Menifest</a></li-->
								<li><a href="<?php echo site_url('Report/myLCLManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>LCL Menifest</a></li>
								<li><a href="<?php echo site_url('Report/myFCLManifest') ?>"><i class="fa fa-mail-forward (alias)"></i>FCL Menifest</a></li>
								<li><a href="<?php echo site_url('Report/myDischargeList') ?>"><i class="fa fa-mail-forward (alias)"></i>Discharge List</a></li>
								<!--li>
										<a href="<?php echo site_url('	Report/myDGContainer') ?>">
											<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
										</a>
								</li-->
								<li>
									<a href="<?php echo site_url('Report/RequestForPreAdviceReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Request for Pre-Advised<br>
										<span style="padding-right:30px;"></span>Container List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/ImportContainerSummery') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Summary Of Import Container<br>
										<span style="padding-right:30px;"></span>(MLO,Size,Height) Wise
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/mloDischargeSummery') ?>"><i class="fa fa-mail-forward (alias)"></i>MLO Discharge Summary List</a></li>
								<li>
									<a href="<?php echo site_url('Report/') ?>">
										<i class="fa fa-mail-forward (alias)"></i>FEEDER Discharge Summary<br>
										<span style="padding-right:30px;"></span>List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/OffDockContainerList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Offdock Destination Wise<br>
										<span style="padding-right:30px;"></span>Container List 
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
								<!--li><a href="<?php echo site_url('Report/dgContainerByRotation') ?>" ><i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation</a></li-->	
								<li><a href="<?php echo site_url('Report/valuableItemByRotation') ?>" ><i class="fa fa-mail-forward (alias)"></i>Valuable Item By Rotation</a></li>
							</ul>
						</li>
						
						
						<li class='nav-parent'><a><i class="fa fa-list" aria-hidden="true"></i><span>DG REPORTS</span></a>
							<ul class="nav nav-children">									
								<li>
									<a href="<?php echo site_url('Report/myDGManifest') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG Manifest
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('report/dg_report') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER <br>
										<span style="padding-right:30px;"></span>DELIVERY REPORT
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/yardWiseLyingDGContListForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Yard Wise Lying DG Container <br><span style="padding-right:30px;"></span>List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/dgContainerByRotation') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>DG Container By Rotation
									</a>
								</li>	
								<li>
									<a href="<?php echo site_url('Report/myDGContainer') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG CONTAINER LYING REPORT
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('report/dgDischargeSummary') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
										<span style="padding-right:30px;"></span>Summary List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('report/dgDischargeList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>DG Containers Discharge<br>
										<span style="padding-right:30px;"></span>List by Rotation
									</a>
								</li>
							</ul>	
						</li>
						
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>EXPORT REPORTS</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/viewVesselListStatus') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel List With Export<br>
										<span style="padding-right:30px;"></span>APPS Loading Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/commentsSearchForVessel') ?>">
										<span class="blink_me"><i class="fa fa-mail-forward (alias)"></i>Comments by Shipping<br>
										<span style="padding-right:30px;"></span>Section on Export Vessel</span>
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
								<li>
									<a href="<?php echo site_url('Report/workDone24hrsForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 hrs. Container<br>
										<span style="padding-right:30px;"></span>Handling Report
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/myExportImExSummery') ?>"><i class="fa fa-mail-forward (alias)"></i>MLO Wise Export Summary</a></li>
								<li>
									<a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPA') ?>">
										<i class="fa fa-mail-forward (alias)"></i>MLO Wise Final Loading<br>
										<span style="padding-right:30px;"></span>Export APPS
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/mloWiseFinalDischargingExportFormForCPAN4') ?>">
										<i class="fa fa-mail-forward (alias)"></i>MLO Wise Final<br>
										<span style="padding-right:30px;"></span>Loading Export
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/pangoanLoadingExportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>PANGAON Loading Export</a></li>		
								<li>
									<a href="<?php echo site_url('Report/assignment_sheet_for_pangaon') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Assignment Sheet for Outward<br>
										<span style="padding-right:30px;"></span>PANGAON ICT Container
									</a>
								</li>					
								<li>
									<a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
										<span style="padding-right:30px;"></span>Container List
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Last 24 Statement List</a></li>		
								<li><a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>"><i class="fa fa-mail-forward (alias)"></i>Last 24 Statement</a></li>
								
								<li><a href="<?php echo site_url('Report/stuffingPermissionForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form</a></li>
								</ul>
						</li>
												
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>REPORT</span></a>
							<ul class="nav nav-children">
							   
						 
								<li>
									<a href="<?php echo site_url('Report/dlvFromOffdock') ?>">
                                        <i class="fa fa-mail-forward (alias)"></i>Delivery From Offdock
                                    </a>
                                </li>
								<li>
									<a href="<?php echo site_url('Report/assignmentAllReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>All Assignment/Delivery<br><span style="padding-right:30px;"></span>Empty Details</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/special_assignmentForSecurityForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Special Assignment</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/terminalWiseSpecialAssignmentForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Terminal wise special <br>
									<span style="padding-right:30px;"></span>assignment</a>
								</li>
								<li><a href="<?php echo site_url('Report/searchIGMByContainer') ?>"><i class="fa fa-mail-forward (alias)"></i>Search IGM Container</a></li>
								<li><a href="<?php echo site_url('Report/myoffDociew') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Offdock Information</a></li>
								<li>
									<a href="<?php echo site_url('Report/myoffDocEntryForm')?>">
										<i class="fa fa-mail-forward (alias)"></i>Offdock Information <br/>
										<span style="padding-right:30px;"></span> Entry Form
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/commodityInfoView') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Commodity Information</a></li>
								<li><a href="<?php echo site_url('Report/myExportContainerLoadingReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Loaded Container List</a></li>
								<li>
									<a href="<?php echo site_url('Report/vesselEventHistory') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Event History
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/containerEventHistory') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Event History
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/depotLadenContForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Depot LADEN Container</a></li>
								<li>
									<a href="<?php echo site_url('Report/showProcessList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>System Status
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/containerDischargeAppsForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Discharge(APPS)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/containerSearchByRotationAppsForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Search for APPS
									</a>
								</li>							
								<li><a href="<?php echo site_url('Report/offDockRemovalPositionForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Offdock Removal Position</a></li>																
								<li>
									<a href="<?php echo site_url('Report/stuffingContainerListForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Stuffing<br>
										<span style="padding-right:30px;"></span>Container List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/last24hrsOffDockStatementoforAdminForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Statement List
									</a>
								</li>		
								<li>
									<a href="<?php echo site_url('Report/barcodeTestForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Print Barcode
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/last24hrsStatements') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Statement
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/stuffingPermissionForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Stuffing Permission Form
									</a>
								</li>
								
								<li><a href="<?php echo site_url('Report/last24HoursERForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Last 24 Hour's EIR Position</a></li>
								<li>
									<a href="<?php echo BASE_PATH.'assets/goods_vessel_tariff/CPA-Tariff-ilovepdf-compressed.pdf' ?>" target="blank">
										<i class="fa fa-mail-forward (alias)"></i>Tariff on Goods, Vessel etc.
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/vesselBillList/1') ?>"><i class="fa fa-mail-forward (alias)"></i>Vessel Bill List</a></li>
								<li>
									<a href="<?php echo site_url('Report/checkVatStatusForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>VAT Status
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/slaveProcess/') ?>"><i class="fa fa-mail-forward (alias)"></i>Slave Process</a></li>
								<li>
									<a href="<?php echo site_url('Report/exportContainerLoadingList') ?>" target="_blank" >
										<i class="fa fa-mail-forward (alias)"></i>Export Container Loading List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/myExportContainerNotFoundReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Export Container <br>
										<span style="padding-right:30px;"></span>NOT FOUND Report
									</a>
								</li>
								<li><a href="<?php echo site_url('Report/offhireSummaryAndDetails') ?>" ><i class="fa fa-mail-forward (alias)"></i>Offhire Summary & Details</a></li>
								<li>
									<a href="<?php echo site_url('bill/containerBillListVersion/1') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Bill<br>
										<span style="padding-right:30px;"></span>List (Version)
									</a>
								</li>								
								<li><a href="<?php echo site_url('Report/mis_assignment_report_search') ?>"><i class="fa fa-mail-forward (alias)"></i>MIS Assignment Report</a></li>
								<li><a href="<?php echo site_url('Report/pilot_vsl_entry_rpt') ?>" target="_BLANK"><i class="fa fa-mail-forward (alias)"></i>Pilot Vessel Entry Report</a></li>
								
								<li>
									<a href="<?php echo site_url('ShedBillController/shedWiseLyingTallyListForm') ?>"><i class="fa fa-mail-forward (alias)"> 	</i>Shed wise Lying Tally List
									</a>
								</li>

								<li>
									<a href="<?php echo site_url('Report/Container_bl_block_release_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Container / BL Block Release <br>
									<span style="padding-right:30px;"></span>List</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/smsReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Assignment SMS Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/outerAnchorageForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Vessel Movement Report
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>HARDWARE REPORTS</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/workstationReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
								<li><a href="<?php echo site_url('Report/product_report_search') ?>"><i class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>INVENTORY LIST</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/product_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Product Type List</a></li>
								<li><a href="<?php echo site_url('Report/location_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Location List</a></li>
								<li><a href="<?php echo site_url('Report/location_details_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Location Details List</a></li>
								<li><a href="<?php echo site_url('Report/product_user_list') ?>"><i class="fa fa-mail-forward (alias)"></i>User List</a></li>
								<li><a href="<?php echo site_url('Report/workstationList') ?>"><i class="fa fa-mail-forward (alias)"></i>Workstation List</a></li>
								<li><a href="<?php echo site_url('Report/networkProductEntryList') ?>"><i class="fa fa-mail-forward (alias)"></i>Product List</a></li>
								<li><a href="<?php echo site_url('Report/networkProductReceiveList') ?>"><i class="fa fa-mail-forward (alias)"></i>Product Received List</a></li>
								<li><a href="<?php echo site_url('Report/networkProductDeliveryList') ?>"><i class="fa fa-mail-forward (alias)"></i>Product Delivery List</a></li>
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>INVENTORY REPORTS</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/workstationReport') ?>"><i class="fa fa-mail-forward (alias)"></i>Workstation Report</a></li>
								<li><a href="<?php echo site_url('Report/product_report_search') ?>"><i class="fa fa-mail-forward (alias)"></i>Product Report</a></li>
							</ul>
						</li>

						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>AUCTION HANDOVER</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/pendingRLGenerationList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Pending Auction RL <br>
										<span style="padding-right:30px;"></span>Generation List
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/AuctionHandOverReportForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover Form
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/AuctionHandOverReportList') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Auction Handover List
									</a>
								</li>
							</ul>
						</li>
											
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>REEFER REPORTS</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/myRefferImportContainerDischargeReport') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Yard Wise Reefer<br>
										<span style="padding-right:30px;"></span>Import Container
									</a>
								</li>					
							</ul>
						</li>
												
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>MIS REPORT</span></a>
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('misReport/A23_1Form') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Performance Container<br>
										<span style="padding-right:30px;"></span>Vessels Last 24hrs (A23.1)
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/garmentsContInfoForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Information<br>
										<span style="padding-right:30px;"></span>(Garments Item) by Rotation
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/searchGarmentsItemByRotationForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Container Information by<br>
										<span style="padding-right:30px;"></span>Item & Rotation
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/last24HrPositionForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hours Position
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/dailyExportContGateIn') ?>" >
										<i class="fa fa-mail-forward (alias)"></i>Daily Export Container<br>
										<span style="padding-right:30px;"></span>Gate In
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/last45DayslyingReportLink') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 45 Days Lying<br>
										<span style="padding-right:30px;"></span>Food Items Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/last24HrsCPAToOffdockRemovalForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hours CPA to<br>
										<span style="padding-right:30px;"></span>OFFDOCK removal
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/garmentsItemLyingAndDeliveryInfoForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Garments Item Lying and<br>
										<span style="padding-right:30px;"></span>Delivery Information
									</a>
								</li>						
							</ul>
						</li>
												
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>SCY OPERATION</span></a>
							<ul class="nav nav-children">
							   <li>
									<a href="<?php echo site_url('Report/rotationWiseContainerPosition') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Rotation Wise Container Position
									</a>
								</li>
							   <li><a href="<?php echo site_url('Report/containerOperationReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Operation Report</a></li>
							</ul>
						</li>
											
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>PILOTAGE CERTIFICATE</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/vesselListForPilotage') ?>"><i class="fa fa-mail-forward (alias)"></i>Certificate</a></li>
							</ul>
						</li>			   					   
			   						
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>UPLOAD</span></a>
							<ul class="nav nav-children">
								<!-- done by other -->
							<?php
								include("dbConection.php");
								$str = "SELECT COUNT(DISTINCT rotation) AS cnt
								FROM ctmsmis.mis_exp_unit_preadv_req
								WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
								$result = mysqli_query($con_sparcsn4,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
							?>
								<li class="badge1" data-badge="<?php echo $badge;?>"><a href="<?php echo site_url('UploadExcel/preAdvisedRotList') ?>"><i class="fa fa-mail-forward (alias)"></i>Today's Pre-Advised Rotation</a></li>
							
							<?php
								include("mydbPConnection.php");
								$str = "select count(id) as cnt from edi_stow_info where file_status=0";
								
								$result = mysqli_query($con_cchaportdb,$str);
								$row = mysqli_fetch_object($result);
								$badge = $row->cnt;
								//echo "TEST : ".$badge;
							?>
							<?php 
								
								include("dbConection.php");
							?>
								<li class="badge1" data-badge="<?php echo $badge;?>"><a href="<?php echo site_url('UploadExcel/todays_edi_declaration') ?>"><i class="fa fa-mail-forward (alias)"></i>Today's EDI Declaration</a></li>
								<li><a href="<?php echo site_url('UploadExcel/convertCopino') ?>"><i class="fa fa-mail-forward (alias)"></i>Convert COPINO</a></li>
								<li><a href="<?php echo site_url('UploadExcel') ?>"><i class="fa fa-mail-forward (alias)"></i>Excel Upload for COPINO</a></li>
								<!-- done by other -->
								<li>
									<a href="<?php echo site_url('UploadExcel/stuffingContExcel') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Upload Excel for Last 24<br>
										<span style="padding-right:30px;"></span>Hours Stuffing Container</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/exportExcelUploadForAdmin') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Upload Export Container<br>
										<span style="padding-right:30px;"></span>(Excel File)</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/last24hrPerformancePdfForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Last 24 Hour Performance<br>
										<span style="padding-right:30px;"></span>PDF File Upload
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/handling_performance_compare') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Handling Performance Compare
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('UploadExcel/readObpcForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Upload Excel for<br>
										<span style="padding-right:30px;"></span>OBPC & RL
									</a>
								</li>
							</ul>
						</li>
			   
						
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>NOTICE</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/noticeUploadForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Notice Upload Form</a></li>
								<li><a href="<?php echo site_url('Report/noticeUploadList') ?>"><i class="fa fa-mail-forward (alias)"></i>Notice Upload List</a></li>
							</ul>
						</li>
			   						
						<!-- done by other -->
						<li class="nav-parent">			
							<a><i class="fa fa-list" aria-hidden="true"></i><span>LCL ASSIGNMENT</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('CfsModule')?>"><i class="fa fa-mail-forward (alias)"></i>LCL Assignment Entry Form</a></li>
								<li><a href="<?php echo site_url('CfsModule/lclAssignmentReportTable')?>"><i class="fa fa-mail-forward (alias)"></i>LCL Assignment Report</a></li>
							</ul>
						</li>
						
					
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>DOWNLOAD</span></a>
							<ul class="nav nav-children">							
								<li><a href="<?php echo site_url('Report/myBillSummaryForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill Summary</a></li>
							</ul>						
						</li>			

						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>PANGAON</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('UploadExcel/pangoanContUpload') ?>"><i class="fa fa-mail-forward (alias)"></i>Excel Upload for PANGAON</a></li>
								<li><a href="<?php echo site_url('UploadExcel/convertPanContForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Convert PANGAON Containers</a></li>
							</ul>
						</li>
						
						<li class="nav-parent">							
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ICD</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('UploadExcel/uploadIcdExcel') ?>"><i class="fa fa-mail-forward (alias)"></i>ICD Excel File Upload</a></li>
								<li><a href="<?php echo site_url('UploadExcel/convertIcdFileForm') ?>"><i class="fa fa-mail-forward (alias)"></i>ICD Excel File Converter</a></li>
							</ul>
						</li>
				
						<li class="nav-parent">	
							<a><i class="fa fa-list" aria-hidden="true"></i><span>HEAD DELIVERY</span></a>
							<ul class="nav nav-children">							
								<li><a href="<?php echo site_url('Report/xml_conversion') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry List</a></li>
								<li><a href="<?php echo site_url('Report/date_wise_bill_entry') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry Report</a></li>
								<li><a href="<?php echo site_url('Report/date_wise_be_report') ?>"><i class="fa fa-mail-forward (alias)"></i>Date Wise Bill of Entry Report</a></li>
								<li><a href="<?php echo site_url('Report/head_delivery') ?>"><i class="fa fa-mail-forward (alias)"></i>Container Search & Truck Entry</a></li>
								<li><a href="<?php echo site_url('Report/be_error_list') ?>"><i class="fa fa-mail-forward (alias)"></i>Bill of Entry Error List</a></li>
							</ul>
						</li>	
						
						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>GOODS REPORT</span></a>							
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Report/goodsWiseReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Goods Wise Report</a></li>
								<li><a href="<?php echo site_url('Report/itemGoodsWiseReportForm') ?>"><i class="fa fa-mail-forward (alias)"></i>Goods Report Active Yard</a></li>
								<li>
									<a href="<?php echo site_url('Report/itemWiseSummaryDetailsForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Item Wise Summary & Details<br>
										<span style="padding-right:30px;"></span>Report
									</a>
								</li>
								<li>
									<a href="<?php echo site_url('Report/itemWiseLyingSummaryDetailsForm') ?>">
										<i class="fa fa-mail-forward (alias)"></i>Item Wise Lying Summary and <br>
										<span style="padding-right:30px;"></span>Details Report
									</a>
								</li>	
								
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>SHED BILL PANEL</span></a>							
							<ul class="nav nav-children">
								<li>
									<a href="<?php echo site_url('Report/shedBillUrls') ?>">
										<i class="fa fa-mail-forward (alias)"></i>SHED BILL URLs
									</a>
								</li>				
							</ul>
						</li>

						<li class="nav-parent">
							<a><i class="fa fa-list" aria-hidden="true"></i><span>ACCOUNT SETTING</span></a>
							<ul class="nav nav-children">
								<li><a href="<?php echo site_url('Login/verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Password Change</a></li>
								<li><a href="<?php echo site_url('Login/two_step_verify_cell_number') ?>"><i class="fa fa-mail-forward (alias)"></i>Two Step Verification</a></li>
							</ul>
						</li>						
						<?php 
						}	
						?>

						<!-- Electrical Engineer Module Ends  -->

                    </ul>
                </nav>
            </div>

        </div>

    </aside>
    <?php mysqli_close($con_cchaportdb); ?>
    <?php mysqli_close($con_sparcsn4); ?>