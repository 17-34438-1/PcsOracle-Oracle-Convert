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
                                <span>Dashboard</span>
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
                                            class="fa fa-m