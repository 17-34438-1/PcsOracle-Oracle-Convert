<?php

class BBExportReportController extends CI_Controller {
	function __construct(){
        parent::__construct();
        $this->load->library(array('session', 'form_validation'));
        $this->load->model(array('CI_auth', 'CI_menu'));
        $this->load->helper(array('html','form', 'url'));
                    $this->load->driver('cache');
                    $this->load->helper('file');
                    $this->load->model('ci_auth', 'bm', TRUE);
        $this->load->model('breakbulk/BBExportRotationWiseCargoOnboardModel');
        $this->load->model('breakbulk/BBExportRotationWiseCargoDischargeModel');
        //$this->load->library("pagination");
        header("cache-Control: no-store, no-cache, must-revalidate");
        header("cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
        
    function index(){
            $this->BBExportReportController();
    }
    
     //ASIF BB Export Rotation Wise Onboard Cargo Report 12/3/2023 PART1
    function BBExportRotationWiseCargoOnboardView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="BB Export Rotation Wise Onboard Cargo Report ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBExportRotationWiseCargoOnboard',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF BB Export Rotation Wise Onboard Cargo Report 12/3/2023 PART2
    function BBExportRotationWiseOnboardCargoReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
           
            $bb_rot_no=$this->input->post('bb_rot_no');
            $data['ROTATIONDATA'] = $this->BBExportRotationWiseCargoOnboardModel->ExportRotationWiseOnboardCargoSearch($bb_rot_no);
            $data['ROTATIONIGMDATA'] = $this->BBExportRotationWiseCargoOnboardModel->ExportRotationWiseOnboardCargoSearchIGMData($bb_rot_no);
            $data['VESSELDATA'] = $this->BBExportRotationWiseCargoOnboardModel->getVesselInfoByRotation($bb_rot_no);
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBExportRotationWiseCargoOnboardPdf',$data, true); 
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BB Export Rotation Wise Onboard Cargo Report');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{

            echo "PLEASE PROVIDE VALID ROTATION NO";
        }
    }

     //ASIF BB Export Rotation Wise Onboard Cargo Report 13/3/2023 PART1
    function BBExportRotationWiseCargoDischargeView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="BB Export Rotation Wise Cargo Discharge Report ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBExportRotationWiseCargoDischarge',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF BB Export Rotation Wise Onboard Cargo Report 13/3/2023 PART2
    function BBExportRotationWiseCargoDischargeReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
           
            $bb_rot_no=$this->input->post('bb_rot_no');
            $data['ROTATIONDATA'] = $this->BBExportRotationWiseCargoDischargeModel->ExportRotationWiseCargoDischargeSearch($bb_rot_no);
            $data['ROTATIONIGMDATA'] = $this->BBExportRotationWiseCargoDischargeModel->ExportRotationWiseCargoDischargeSearchIGMData($bb_rot_no);
            $data['VESSELDATA'] = $this->BBExportRotationWiseCargoDischargeModel->getVesselInfoByRotation($bb_rot_no);
          
           
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBExportRotationWiseCargoDischargePdf',$data, true); 
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BB Export Rotation Wise Cargo Discharge Report');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{

            echo "PLEASE PROVIDE VALID ROTATION NO";
        }
    }









}