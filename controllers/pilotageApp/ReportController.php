<?php

class ReportController extends CI_Controller {
	function __construct(){
			parent::__construct();	
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array('CI_auth', 'CI_menu'));
			$this->load->helper(array('html','form', 'url'));
			$this->load->driver('cache');
			$this->load->helper('file');
			$this->load->model('ci_auth', 'bm', TRUE);
			$this->load->library("pagination");
			
			$this->load->model('pilotageApp/PilotageModel');
			$this->load->model('pilotageApp/ReportModel');
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
        
 function index(){
		 $this->ReportController();
	}

	function Report(){
			$import_rotation=str_replace("_","/",$this->uri->segment(4));
			$getVvdGkey=$this->uri->segment(5);
			$activity_for=$this->uri->segment(6);
			$pilot_user_id=$this->uri->segment(7);
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');

			$this->load->library('m_pdf');
			$this->data['title']="Pilotage Certificate";
			$this->data['import_rotation']=$import_rotation;
			$pdfFilePath ="PilotageCertificate-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$pdf->showWatermarkText = true;	
				
		 $this->data['vlsdetails_igm_data'] = $this->PilotageModel->getVslDetailsFromIGM($import_rotation);
		 $this->data['vlsdetails_n4_data'] = $this->PilotageModel->getVesselDetailsFromN4_28($import_rotation);
			
			if($activity_for=="incoming"){
					$this->data['vsl_arrival_data'] = $this->PilotageModel->getVslArrivalData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/IncomingReportPdf',$this->data, true);	
			}else if($activity_for=="shifting"){
					$this->data['vsl_shifting_data'] = $this->PilotageModel->getVslShiftingData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/ShiftingReportPdf',$this->data, true);	
			}else if($activity_for=="outgoing"){	
					$this->data['vsl_departed_data'] = $this->PilotageModel->getVslDepartedData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/OutgoingReportPdf',$this->data, true);	
			}else if($activity_for=="cancel"){
					$this->data['vsl_cancel_data'] = $this->PilotageModel->getVslCacelData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/CancelReportPdf',$this->data, true);	
			}
			
			$stylesheet = file_get_contents('resources/styles/test.css'); // external css
			$pdf->useSubstitutions = true; // optional - just as an example
			$pdf->setAutoTopMargin = false;	
			$pdf->setAutoBottomMargin = false;		
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf				
			$this->load->view('jsAssets');					
	
	}
	function Report38(){
	
			$import_rotation=str_replace("_","/",$this->uri->segment(4));
			$getVvdGkey=$this->uri->segment(5);
			$activity_for=$this->uri->segment(6);
			$pilot_user_id=$this->uri->segment(7);
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');

			$this->load->library('m_pdf');
			$this->data['title']="Pilotage Certificate";
			$this->data['import_rotation']=$import_rotation;
			$pdfFilePath ="PilotageCertificate-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$pdf->showWatermarkText = true;	
				
		 $this->data['vlsdetails_igm_data'] = $this->PilotageModel->getVslDetailsFromIGM($import_rotation);
		 $this->data['vlsdetails_n4_data'] = $this->PilotageModel->getVesselDetailsFromN4_38($import_rotation);
			
			if($activity_for=="incoming"){
					$this->data['vsl_arrival_data'] = $this->PilotageModel->getVslArrivalData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/IncomingReportPdf',$this->data, true);	
			}else if($activity_for=="shifting"){
					$this->data['vsl_shifting_data'] = $this->PilotageModel->getVslShiftingData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/ShiftingReportPdf',$this->data, true);	
			}else if($activity_for=="outgoing"){	
					$this->data['vsl_departed_data'] = $this->PilotageModel->getVslDepartedData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/OutgoingReportPdf',$this->data, true);	
			}else if($activity_for=="cancel"){
					$this->data['vsl_cancel_data'] = $this->PilotageModel->getVslCacelData($getVvdGkey,$pilot_user_id);
					$html=$this->load->view('pilotageApp/CancelReportPdf',$this->data, true);	
			}
	
			$stylesheet = file_get_contents('resources/styles/test.css'); // external css
			$pdf->useSubstitutions = true; // optional - just as an example
			$pdf->setAutoTopMargin = false;	
			$pdf->setAutoBottomMargin = false;		
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf				
			$this->load->view('jsAssets');					
	
	}
	
	function PilotWiseReportView(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		   if($LoginStat!="yes"){
			   $this->logout();
		   }
		   else{
			   $data['title']="Pilot Wise Report";
			   $this->load->view('cssAssets');
			   $this->load->view('headerTop');
			   $this->load->view('sidebar');
			   $this->load->view('pilotageApp/PilotWiseDateRangeView',$data);
			   $this->load->view('jsAssets');
		   }
   }


	function PilotWiseReportPdf(){
		$todate=$this->input->post('todate');
		$fromdate=$this->input->post('fromdate');
		$options = $_POST['fileOptions'];
		$data['options']=$options;
		$data['title']=$fromdate." to ".$todate." Pilot Wise Pilotage App Entry Report";
		$data['PILOTDATA'] = $this->ReportModel->getDateWisePilotEntryReport($todate,$fromdate);
		
		if($options=='pdf'){
		$this->load->library('m_pdf');
		$pdfFilePath ="PilotageWiseReport-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		$pdf->showWatermarkText = true;	
		$stylesheet = file_get_contents('resources/styles/test.css'); // external css
		$pdf->useSubstitutions = true; // optional - just as an example
		$pdf->setAutoTopMargin = false;	
		$pdf->setAutoBottomMargin = false;		
		$pdf->WriteHTML($stylesheet,1);
		$html=$this->load->view('pilotageApp/PilotWiseDateRangeReportPdf',$data, true);
		$pdf->WriteHTML($html,2);
		$pdf->Output($pdfFilePath, "I");
		}else{
			
			$this->load->view('pilotageApp/PilotWiseDateRangeReportXL',$data);
			$this->load->view('myclosebar');

		}  				

}

function DateWiseVSLHandledView(){
	$session_id = $this->session->userdata('value');
	$LoginStat = $this->session->userdata('LoginStat');
	   if($LoginStat!="yes"){
		   $this->logout();
	   }
	   else{
		   $data['title']="Date Wise Vessel Handled List";
		   $this->load->view('cssAssets');
		   $this->load->view('headerTop');
		   $this->load->view('sidebar');
		   $this->load->view('pilotageApp/DateWiseVSLHandledView',$data);
		   $this->load->view('jsAssets');
	   }
}

function DateWiseVSLHandledPdf(){
	$todate=$this->input->post('todate');
	$fromdate=$this->input->post('fromdate');
    $options = $this->input->post('fileOptions');

	$this->data['title']=$fromdate." to ".$todate." VSL Entry Report";
	$this->data['PILOTDATA'] = $this->ReportModel->getDateWiseVesselHandledReport($todate,$fromdate);
	$this->data['options']=$options;
	$html=$this->load->view('pilotageApp/DateWiseVSLHandledReportPdf',$this->data, true);
	$this->load->view('cssAssets');
	if($options=='pdf'){
	$this->load->library('m_pdf');
	$pdfFilePath ="PilotageVSLEntryReport-".time()."-download.pdf";
	$pdf = $this->m_pdf->load();
	$pdf->showWatermarkText = true;	
	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
	$pdf->useSubstitutions = true; // optional - just as an example
	$pdf->setAutoTopMargin = false;	
	$pdf->setAutoBottomMargin = false;		
	$pdf->WriteHTML($stylesheet,1);
	$pdf->WriteHTML($html,2);
	$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	$this->load->view('jsAssets');		
	}else{
		$this->load->view('pilotageApp/DateWiseVSLHandledReportXL',$this->data);
	}			
					

}
	
}