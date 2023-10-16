<?php

class BBVesselOperationController extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
     $this->load->library(array('session', 'form_validation'));
     $this->load->model(array('CI_auth', 'CI_menu'));
     $this->load->helper(array('html','form', 'url'));
     $this->load->driver('cache');
     $this->load->library("pagination");
     $this->load->model('ci_auth', 'bm', TRUE);
     $this->load->model('breakbulk/BBIGMModel');
     $this->load->model('breakbulk/BBIGMUploadModel');
     $this->load->model('breakbulk/BBVesselOperationModel');
     
     header("cache-Control: no-store, no-cache, must-revalidate");
     header("cache-Control: post-check=0, pre-check=0", false);
     header("Pragma: no-cache");
     header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
        
	function index(){
		   $this->BBVesselOperationController();
	}

 function BBVesselDeclarationListView(){
     $user = $this->session->userdata('login_id');
     $user=str_replace(" ","",$user);
     $session_id = $this->session->userdata('value');
     $LoginStat = $this->session->userdata('LoginStat');
     if($LoginStat!="yes"){
      $this->logout();
     }
     else{
      $type_of_Igm = $this->uri->segment(4);
      $this->load->model('ci_auth', 'bm', TRUE);
      /*********** Pagination**************/
      $config = array();
      $config["base_url"] = site_url("breakbulk/BBVesselOperationController/BBVesselDeclarationListView");
      $config["total_rows"] = $this->bm->record_count();
      $config["per_page"] = 5;
      $config["uri_segment"] = 4;
      $this->pagination->initialize($config);
      $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
      /***********Pagination***************/
      //echo $type_of_Igm;
      //$igmMasterList = $this->bm->myListForm($type_of_Igm,$config["per_page"], $page); ///ai khane error khachche
      //BBIGMList
      $data['igmMasterList']=$this->BBVesselOperationModel->BBIGMVesselDeclarationList();
      $data['title']="View Vessel Declaration Detail($type_of_Igm)";
      $data['type']=$type_of_Igm;
      $data["links"] = $this->pagination->create_links();
      
      $this->load->view('cssAssetsList');
               $this->load->view('headerTop');
               $this->load->view('sidebar');
      $this->load->view('breakbulk/BBVesselDecListView',$data);
      $this->load->view('jsAssetsList');
     }
}


 function BBVesselDeclarationViewUpdate(){
     $user = $this->session->userdata('login_id');
     $igm_id=$_POST['igm_id'];
     $update=$this->BBVesselOperationModel->VesselDeclarationViewUpdate($igm_id,$user);
     echo $update;
 }

 function BBVesselDeclarationNotiFicationUpdate(){
    $count_file=$this->BBVesselOperationModel->CountVesselDeclarationUploadFile();
    echo $count_file;
    
}

function GetBBVesselDeclarationViewList(){
    $data=$this->BBVesselOperationModel->BBIGMVesselDeclarationList();
    echo json_encode($data);
}

//ASIF 14/3/23
 function BerthWiseReport(){
     $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
        }
        else{
            $data['title']="Berth Wise Vessel Operation Report";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('breakbulk/BBVesselOperationBerthWiseReport',$data);
            $this->load->view('jsAssets');
        }
}
   //ASIF  14/3/23
    function BerthWiseReportPdf(){

        echo "ASIF";
        $session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
        if($LoginStat!="yes"){
            $this->logout();
            
        }

        if($this->input->post()){
            $fromdate=$this->input->post('fromdate');
            $todate=$this->input->post('todate');
            $data['VESSELDATA'] = $this->BBVesselOperationModel->searchBerthWiseVesselWithDateRange($fromdate,$todate);
            $data['VESSELIGMDATA'] = $this->BBVesselOperationModel->searchBerthWiseVesselWithDateRangeRotaionIGM($fromdate,$todate);
            $data['FROM_DATE'] =  $fromdate;
            $data['TO_DATE'] =  $todate;
          
            $this->load->library('m_pdf');
            $html=$this->load->view('breakbulk/BBVesselOperationBerthWiseReportPdf',$data, true); 
            $pdfFilePath = $fromdate."_".date("Y-m-d H:i:s");
            $pdf = $this->m_pdf->load();
            $pdf->SetTitle('Berth Wise Vessel Operation Report');
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