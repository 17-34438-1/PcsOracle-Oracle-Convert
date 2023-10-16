<?php

class BBDashBoardController extends CI_Controller {
	function __construct(){
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array('CI_auth', 'CI_menu'));
			$this->load->helper(array('html','form', 'url'));
			$this->load->driver('cache');
			$this->load->helper('file');
			$this->load->model('breakbulk/SearchBLModel');
			
            
		

			//$this->load->library("pagination");
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
        
 function index(){
		 $this->BBDashBoardController();
 }

 function search_keyword(){
	$session_id = $this->session->userdata('value');
    $LoginStat = $this->session->userdata('LoginStat');
    if($LoginStat!="yes"){
		$this->logout();
	}else{
		$keyword=$this->input->post('blLocation');
		$data['blData']=$this->SearchBLModel->searchBL(trim($keyword));
        //  echo json_encode($data);
		//  return;
		$data['blDataByState']=$this->SearchBLModel->searchBLBYState(trim($keyword));
		$data['blDataByYard']=$this->SearchBLModel->searchBLBYShedYard(trim($keyword));
			$data['check']= -1;
		$data['title']="Cargo Location Search Form";
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('breakbulk/cargoLocationSearchForm',$data);
		$this->load->view('jsAssetsList');
	}
 }

 function bbCargoLocationSearchForm(){
    $session_id = $this->session->userdata('value');
    $LoginStat = $this->session->userdata('LoginStat');
    if($LoginStat!="yes"){
     $this->logout();
    }
    else{  
	$data['title']="Cargo Location Search Form";
	$data['check']= -2;
     $this->load->view('cssAssets');
     $this->load->view('headerTop');
     $this->load->view('sidebar');
     $this->load->view('breakbulk/cargoLocationSearchForm',$data);
     //$this->load->view('ShippingAgentPanel/updateShedFrm',$data);
     $this->load->view('jsAssets');
    }	
   }
}
?>