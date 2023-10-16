<?php

class BBImportReportController extends CI_Controller {
	function __construct(){
        parent::__construct();
        $this->load->library(array('session', 'form_validation'));
        $this->load->model(array('CI_auth', 'CI_menu'));
        $this->load->helper(array('html','form', 'url'));
                    $this->load->driver('cache');
                    $this->load->helper('file');
                    $this->load->model('ci_auth', 'bm', TRUE);
        $this->load->model('breakbulk/BBImportRotationWiseCargoOnboardModel');
        $this->load->model('breakbulk/BBImportRotationWiseCargoDischargeModel');
        //$this->load->library("pagination");
        header("cache-Control: no-store, no-cache, must-revalidate");
        header("cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
        
    function index(){
            $this->BBImportReportController();
    }
    
     //ASIF BB Import Rotation Wise Onboard Cargo Report 12/3/2023 PART1
    function BBImportRotationWiseCargoOnboardView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="BB Import Rotation Wise Onboard Cargo Report ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBImportRotationWiseCargoOnboard',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF BB Import Rotation Wise Onboard Cargo Report 12/3/2023 PART2
    function BBImportRotationWiseOnboardCargoReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
           
            $bb_rot_no=$this->input->post('bb_rot_no');
            $data['ROTATIONDATA'] = $this->BBImportRotationWiseCargoOnboardModel->importRotationWiseOnboardCargoSearch($bb_rot_no);
            $data['ROTATIONIGMDATA'] = $this->BBImportRotationWiseCargoOnboardModel->importRotationWiseOnboardCargoSearchIGMData($bb_rot_no);
            //$data['VESSELDATA'] = $this->BBImportRotationWiseCargoOnboardModel->getVesselInfoByRotation($bb_rot_no);
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBImportRotationWiseCargoOnboardPdf',$data, true); 
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BB Import Rotation Wise Onboard Cargo Report');
            $pdf->SetTitle = true;
            $pdf->useSubstitutions = true; 
            $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
            $pdf->WriteHTML($html,2);
            $pdf->Output($pdfFilePath, "I");
        }else{

            echo "PLEASE PROVIDE VALID ROTATION NO";
        }
    }

     //ASIF BB Import Rotation Wise Onboard Cargo Report 13/3/2023 PART1
    function BBImportRotationWiseCargoDischargeView(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="BB Import Rotation Wise Cargo Discharge Report ";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBImportRotationWiseCargoDischarge',$data);
            $this->load->view('jsAssets');
        }
    }
     //ASIF BB Import Rotation Wise Onboard Cargo Report 13/3/2023 PART2
    function BBImportRotationWiseCargoDischargeReportPdf(){
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
           
            $bb_rot_no=$this->input->post('bb_rot_no');
            $data['ROTATIONDATA'] = $this->BBImportRotationWiseCargoDischargeModel->importRotationWiseCargoDischargeSearch($bb_rot_no);
            $data['ROTATIONIGMDATA'] = $this->BBImportRotationWiseCargoDischargeModel->importRotationWiseCargoDischargeSearchIGMData($bb_rot_no);
         //   $data['VESSELDATA'] = $this->BBImportRotationWiseCargoDischargeModel->getVesselInfoByRotation($bb_rot_no);
          
           
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBImportRotationWiseCargoDischargePdf',$data, true); 
            $pdfFilePath =$bb_rot_no."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('BB Import Rotation Wise Cargo Discharge Report');
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