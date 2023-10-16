<?php

class BBReportController extends CI_Controller {
	function __construct(){
            parent::__construct();
   			$this->load->library(array('session', 'form_validation'));
   			$this->load->model(array('CI_auth', 'CI_menu'));
   			$this->load->helper(array('html','form', 'url'));
			$this->load->driver('cache');
			$this->load->helper('file');
			$this->load->model('ci_auth', 'bm', TRUE);
			$this->load->model('breakbulk/BBDischargeSummaryModel');
            $this->load->model('breakbulk/BBSingleBLWiseModel');
            $this->load->model('breakbulk/BBIGMCheckModel');
            $this->load->model('breakbulk/PerformanceReportModel');
            
		

			//$this->load->library("pagination");
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
        
    function index(){
            $this->BBReportController();
    }

    //Cargo Vessel Discharge Summary View HTML PART 1
    function CargoVesselDischargeSummaryView(){
        //print_r($this->session->all_userdata());
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="Cargo Vessel Discharge Summary";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/bbIGMDischargeSummaryView',$data);
            $this->load->view('jsAssets');
        }
    }

    //Cargo Vessel Discharge Summary View HTML PART 2
    function CargoVesselDischargeSummaryReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
            $bb_imp_rot_no=$this->input->post('bb_imp_rot_no');
        }
        else if($this->uri->segment(3)){
            $bb_imp_rot_no=str_replace("_","/",$this->uri->segment(3));
        }

        $data['DSData']=$this->BBDischargeSummaryModel->searchRotation(trim($bb_imp_rot_no));
        $data['DSAgentData']=$this->BBDischargeSummaryModel->searchAgentCode(trim($bb_imp_rot_no));
        
        $this->load->library('m_pdf');
        $html=$this->load->view('breakbulk/BBDischargeSummaryReportPdf',$data, true); 
        $pdfFilePath =$bb_imp_rot_no."_".date("Y-m-d H:i:s");
        $pdf = $this->m_pdf->load();
        $pdf->SetTitle('Summary');
        $pdf->SetTitle = true;
        // $pdf->SetWatermarkText('CPA CTMS');
        // $pdf->showWatermarkText = true;  
        $pdf->useSubstitutions = true; 
        //$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}'); $Pack_Marks_Number
        //Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
        
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        $pdf->WriteHTML($html,2);
        $pdf->Output($pdfFilePath, "I");

    }

    //ASIF SINGLE BL WISE IGM REPORT 6/3/2023 PART1
    function CargoVesselSingleBLWiseIGMView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="Cargo Vessel Single BL Wise ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/bbIGMSingleBLWiseView',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF SINGLE BL WISE IGM REPORT 6/3/2023 PART2
    function CargoVesselSingleBLWiseIGMReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
            $bb_bl_no=$this->input->post('bb_bl_no');
            $data['BLDATA']=$this->BBSingleBLWiseModel->searchSingleBLWiseIGM(trim($bb_bl_no));
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/bbIGMSingleBLWisePdf',$data, true); 
            $pdfFilePath =$bb_bl_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BL Wise Single IGM Summary');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            //$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}'); $Pack_Marks_Number
            //Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
            
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{

            echo "PLEASE PROVIDE VALID BL NO";
        }
    }

     //ASIF BB IGM  CHECK REPORT 12/3/2023 PART1
    function BBIGMCheckView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="BB IGM CHECK VIEW ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBIGMCheckView',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF BB IGM  CHECK REPORT 12/3/2023 PART2
    function BBIGMCheckViewReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
           
            $bb_rot_no=$this->input->post('bb_rot_no');
            $data['ROTATIONDATA'] = $this->BBIGMCheckModel->searchRotationWiseIGM(trim($bb_rot_no));
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBIGMCheckViewPdf',$data, true); 
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BB IGM CHECK REPORT');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{

            echo "PLEASE PROVIDE VALID ROTATION NO";
        }
    }

    //Anit 29/03/2023
    function PerformanceReportView(){
            $session_id = $this->session->userdata('value');
            $LoginStat = $this->session->userdata('LoginStat');
               if($LoginStat!="yes"){
                   $this->logout();
               }
               else{
                   $data['title']="24 Hours Performance Report";
                   $this->load->view('cssAssets');
                   $this->load->view('headerTop');
                   $this->load->view('sidebar');
                   $this->load->view('breakbulk/BB24HoursPerformanceReportView',$data);
                   $this->load->view('jsAssets');
               }
       }
     
    function PerformanceReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();    
        }
        if($this->input->post()){
            $date=$this->input->post('todate');
            $data['VESSELDATA'] = $this->PerformanceReportModel->GellVesselInformationByDate($date);
            //$data['VESSELIGMDATA'] = $this->BBVesselOperationModel->searchBerthWiseVesselWithDateRangeRotaionIGM($fromdate,$todate);
            $data['DATE'] =  $date;
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BB24HoursPerformanceReportPdf',$data, true); 
            $pdfFilePath = $date."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('24 Hours Performance Report');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            // $pdf->AddPage('L', // L - landscape, P - portrait
			// 				'', '', '', '',
			// 				2, // margin_left
			// 				2, // margin right
			// 				2, // margin top
			// 				2, // margin bottom
			// 				5, // margin header
			// 				2); // margin footer

            //$pdf->AddPage('L');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{
           echo "PLEASE PROVIDE VALID DATE";
        }
    }

    //Anit 04/04/2023
    function TallySheetForImportReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();    
        }
        //echo "test";
            //$date=$this->input->post('todate');
            //$data['VESSELDATA'] = $this->PerformanceReportModel->GellVesselInformationByDate($date);
            //$data['VESSELIGMDATA'] = $this->BBVesselOperationModel->searchBerthWiseVesselWithDateRangeRotaionIGM($fromdate,$todate);
        
            $data['title'] = "TALLY SHEET FOR IMPORT CARGO";
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBTallySheetForImportCargoPdf',$data, true); 
            $pdfFilePath = "TALLY_SHEET"."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('TALLY SHEET FOR IMPORT CARGO');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
    }

    //Anit 09-04-2023
    function CargoManifestReportView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="CARGO MANIFEST FULL REPORT VIEW ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBCargoManifestFullReportView',$data);
            $this->load->view('jsAssets');
        }
    }

    //Anit 09-04-2023
    function BBCargoManifestReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }
        if($this->input->post()){
            $bb_rot_no=$this->input->post('bb_rot_no');
            $ROTATIONDATA=$this->BBIGMCheckModel->searchRotationWiseCargoManifestReport(trim($bb_rot_no));
            $data['FLAGDATA']=$this->BBIGMCheckModel->GetFlagForCargoManifestReport(trim($bb_rot_no));
            $data['PALCENAME']=$this->BBIGMCheckModel->GetPlaceNameByRotation(trim($bb_rot_no));
            $data['ROTATIONDATA'] = $ROTATIONDATA; 
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBCargoManifestFullReportPdf',$data, true); 
            // $rot= $data['ROTATIONDATA'][0]['ROTATION'];
            // $regNo= $data['ROTATIONDATA'][0]['reg_date'];
            // $vyg= $data['ROTATIONDATA'][0]['Voy_no'];
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('CRG_MANIFEST_REPORT');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html,'UTF-8','UTF-8');
            //$pdf->AddPage('L');
            //$pdf->AddPage('L','','','','',50,50,50,50,10,10);
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{
            echo "PLEASE PROVIDE VALID ROTATION NO";
        }
    }

}