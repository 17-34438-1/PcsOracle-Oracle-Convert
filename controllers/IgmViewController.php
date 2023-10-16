<?php

class igmViewController extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->library("pagination");
			$this->load->model('ci_auth', 'bm', TRUE);
			
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
        
	function index(){
		$this->viewIgmGeneral();
	}
	function myPanelView(){
		if($this->session->userdata('login_id'))
		{
			$this->load->view('header');
			$this->load->view('panelView');
			$this->load->view('footer');
		}
		else
		{
		
			$this->load->view('header');
			$this->load->view('welcomeview_1',$data);
			$this->load->view('footer');
		}
		
	}
		



	
	//view Vessel GM
	function viewIgmGeneral()
	{
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$type_of_Igm = $this->uri->segment(3);
			$this->load->model('ci_auth', 'bm', TRUE);
		
			/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("igmViewController/viewIgmGeneral/".$type_of_Igm);
			$config["total_rows"] = $this->bm->record_count();
			$config["per_page"] = 5;			
			$config["uri_segment"] = 4;
		
			$this->pagination->initialize($config);
			echo $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
			/***********Pagination***************/
				
			$igmMasterList = $this->bm->myListForm($type_of_Igm,$config["per_page"], $page); ///ai khane error khachche
			//print_r($igmMasterList);
			//die();
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="View Vessel Declaration Detail($type_of_Igm)...";
			$data['type']=$type_of_Igm;
			$data["links"] = $this->pagination->create_links();
			$this->load->view('header1',$datahome);
			$this->load->view('myCNFViewIGmListHTML',$data);
			$this->load->view('footer2');
		}
	}

	//Search Vessel
	function myListSearch(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$SearchCriteria=$this->input->post('SearchCriteria');
			$Searchdata=$this->input->post('Searchdata');
			$type=$this->input->post('type');
			//echo $type."<hr>";
			
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearch($type,$SearchCriteria, $Searchdata); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="View Vessel Declaration Detail($type)...";
			$data['type']=$type;
			
			$this->load->view('header1',$datahome);
			$this->load->view('myCNFViewIGmListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//view Igm GM
	function myListForm(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$CODE = $this->uri->segment(3);
			$type_of_Igm = $this->uri->segment(4);
			$this->load->model('ci_auth', 'bm', TRUE);
		
			/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("igmViewController/myListForm/$CODE/$type_of_Igm");
			$config["total_rows"] = $this->bm->record_count();
			$config["per_page"] = 5;
			$config["uri_segment"] = 5;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		
			/***********Pagination***************/
			//echo $CODE."_".$type_of_Igm;
			$igmMasterList = $this->bm->myListFormIGM($CODE,$type_of_Igm,$config["per_page"], $page); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($type_of_Igm)...";
			$data['type']=$type_of_Igm;
			$data['CODE']=$CODE;
			$data['user']=$user;
			$data["links"] = $this->pagination->create_links();
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}

	//Search by Port................

	function myListSearchforPort(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('txt_CODE2');
			$TM=$this->input->post('txt_TM2');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$org_type_id=$this->input->post('txt_Org_Id_for_search2');
		
			
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearchforPort($CODE,$TM, $txt_ROTATION,$org_type_id); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//Search by Line/BL...............
	function myListSearchLineBL(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('MCODE');
			$TM=$this->input->post('TM');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$SearchId=$this->input->post('lbl_search');
			$SearchData=$this->input->post('txt_serch');
		
			//echo $SearchId."<hr>";
			$this->load->model('ci_auth', 'bm', TRUE);
			//ECHO $CODE.'_'.$TM.'_'.$txt_ROTATION.'_'.$SearchId.'_'.$SearchData.'_'.$_SESSION['Control_Panel'];
			$igmMasterList = $this->bm->myListSearchLineBL($CODE,$TM, $txt_ROTATION,$SearchId,$SearchData); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//Search by Importer..............
	function myListSearchImporter(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('MCODE');
			$TM=$this->input->post('TM');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$SearchId=$this->input->post('lbl_search');
			$SearchData=$this->input->post('txt_serch');
		
			//echo $SearchId."<hr>";
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearchImporter($CODE,$TM, $txt_ROTATION,$SearchId,$SearchData); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			$data['user']=$user;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//Search by MLO Code...............
	function myListSearchMLO(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('txt_CODE');
			$TM=$this->input->post('txt_TM');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$org_type_id=$this->input->post('txt_Org_Id_for_search123');
			//$SearchData=$this->input->post('txt_serch');
		
			//echo $SearchId."<hr>";
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearchMLO($CODE,$TM, $txt_ROTATION,$org_type_id); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}

	//Search by MLO Name...............
	function myListSearchByMLO(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('txt_CODE');
			$TM=$this->input->post('txt_TM');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$org_type_id=$this->input->post('txt_Org_Id_for_search123');
			//$SearchData=$this->input->post('txt_serch');
		
			//echo $SearchId."<hr>";
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearchByMLO($CODE,$TM, $txt_ROTATION,$org_type_id); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//Search by SAF Name...............
	function myListSearchBySAF(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$CODE=$this->input->post('txt_CODE');
			$TM=$this->input->post('txt_TM');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$org_type_id=$this->input->post('txt_Org_Id_for_search122');
			//$SearchData=$this->input->post('txt_serch');
		
			//echo $SearchId."<hr>";
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->bm->myListSearchBySAF($CODE,$TM, $txt_ROTATION,$org_type_id); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="LIST OF IGM SUB DETAILS ($TM)...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myCNFIGMSubListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}

	/************************************************* View Img Search End *********************************************/
	/*************************************************Start Supplementary**********************************************/
		
	//view Igm GM
	function myListFormSEasy(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			
			$MCODE = $this->uri->segment(3);
			$TM = $this->uri->segment(4);
			$CType = $this->uri->segment(5);
			//echo $MCODE."=".$TM."=".$CType;
			
			$this->load->model('ci_auth', 'bm', TRUE);
		
			/*********** Pagination**************/
			
			$config = array();
			//$config["base_url"] = site_url("igmViewController/myListForm/$CODE/$type_of_Igm");     //1-6-2020
			$config["base_url"] = site_url("igmViewController/myListForm");
			$config["total_rows"] = $this->bm->record_count();
			$config["per_page"] = 5;
			$config["uri_segment"] = 6;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
		
			/***********Pagination***************/
				
			$igmMasterList = $this->bm->myListFormSEasy($MCODE,$TM,$CType,$config["per_page"], $page); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="VIEW IGM SUPPLEMENTARY DETAIL...";
			$data['type']=$TM;
			$data['MCODE']=$MCODE;
			$data['CType']=$CType;
			$data["links"] = $this->pagination->create_links();
			$this->load->view('header3',$datahome);
			$this->load->view('myIGMFFSupplListHTMLEasy',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//view Supp Igm GM Search By Line/BL
	function myListSearchFFByLineBL(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			
			$CType=$this->input->post('CType');
			$MCODE=$this->input->post('MCODE');
			$master_id=$this->input->post('rot_search');
			$TM=$this->input->post('TM');
			$SFlag=$this->input->post('txt_SFlag');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$SearchId=$this->input->post('lbl_search');
			$SearchData=$this->input->post('txt_serch');
			$this->load->model('ci_auth', 'bm', TRUE);
				
			$igmMasterList = $this->bm->myListSearchFFByLineBL($CType,$MCODE,$master_id,$TM,$SearchId,$SearchData,$SFlag,$txt_ROTATION); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="VIEW IGM SUPPLEMENTARY DETAIL...";
			$data['type']=$TM;
			$data['MCODE']=$MCODE;
			$data['CType']=$CType;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myIGMFFSupplListHTMLEasy',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//view Supp Igm GM Search By FF Agent
	function myListSearchFFByFF(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			
			$CType=$this->input->post('CType');
			$MCODE=$this->input->post('txt_ccc');
			$master_id=$this->input->post('rot_search');
			$TM=$this->input->post('txt_TMccc2');
			$SFlag=$this->input->post('txt_SFlag');
			$txt_ROTATION=$this->input->post('txt_ROTATION');
			$SearchId=$this->input->post('txt_Org_Id_for_submittedFF');
			//$SearchData=$this->input->post('txt_serch');
			$this->load->model('ci_auth', 'bm', TRUE);
				
			$igmMasterList = $this->bm->myListSearchFFByFF($MCODE,$TM,$SearchId,$txt_ROTATION); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="VIEW IGM SUPPLEMENTARY DETAIL...";
			$data['type']=$TM;
			$data['MCODE']=$MCODE;
			$data['CType']=$CType;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myIGMFFSupplListHTMLEasy',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
		
	//view Supp Igm GM ...................
	function myListFormS(){
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$CODE = $this->uri->segment(3);
			$SubCODE = $this->uri->segment(4);
			$TM = $this->uri->segment(5);
			//$SearchData=$this->input->post('txt_serch');
			$this->load->model('ci_auth', 'bm', TRUE);
				
			$igmMasterList = $this->bm->myListFormS($CODE,$SubCODE,$TM); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			
			$data['title']="VIEW IGM SUPPLEMENTARY DETAIL...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			$data['SubCODE']=$SubCODE;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myIGMSupplListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}

	//view Supp Igm GM Search By Port
	function myListSearchFFByPort(){
		//print_r($this->session->all_userdata());
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			
			$CODE=$this->input->post('txt_CODE');
			$SubCode=$this->input->post('txt_SubCode');
			$TM=$this->input->post('txt_TM');
			$org_type_id=$this->input->post('txt_Org_Id_for_search2');
			
			$this->load->model('ci_auth', 'bm', TRUE);
				
			$igmMasterList = $this->bm->myListSearchFFByPort($CODE,$SubCode,$TM,$org_type_id); ///ai khane error khachche
			$datahome['igmMasterList']=$igmMasterList;
			
			$data['title']="VIEW IGM SUPPLEMENTARY DETAIL...";
			$data['type']=$TM;
			$data['CODE']=$CODE;
			$data['SubCode']=$SubCode;
			
			$this->load->view('header3',$datahome);
			$this->load->view('myIGMSupplListHTML',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
		}
	}
	/*********************************************End IGM Supplementary ********************************************************/
	//view Igm Container HTML..........................................

	function myIGMContainer(){
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="IGM Container List...";
			$this->load->view('header4');
			$this->load->view('myIGMDetailsContainerListHTML',$data);
			//$this->load->view('footer');
		}
	}
		
	//view Igm Container List...............................................
	function myIGMContainerList()
	{
	
		//$type_of_Igm = $this->uri->segment(3);
		$Searchdata=$this->input->post('Searchdata');
		$SearchCriteria2=$this->input->post('SearchCriteria2');
		//echo $SearchCriteria2;
		$this->load->model('ci_auth', 'bm', TRUE);
		$igmContainerList = $this->bm->myIgmContainerListSearch($Searchdata);
	
		$datahome['igmContainerList']=$igmContainerList;
		//$data['igmContainerList']=$igmContainerList;
	
		$data['title']="IGM Container List...";
		$data['rotation']=$Searchdata;
		$data['SearchCriteria2']=$SearchCriteria2;
		$this->load->view('header4',$datahome);
		$this->load->view('myIGMDetailsContainerListHTML',$data);
		$this->load->view('footer2');
	
	
	
	}
		
	//view Check the IGM HTML..........................................
	function checkTheIGM(){
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="Check The Document...";
			$data['login_id']=$login_id;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myCustomDocumentcheckHTML',$data);
			$this->load->view('jsAssets');
		}
	}
		
	//view Check the IGM HTML..........................................
	function viewIGM(){
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="Check The Document...";
			$data['login_id']=$login_id;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myViewIgmCheckList',$data);
			$this->load->view('jsAssets');
		}
	}
		
	//view Check the IGM HTML..........................................
	function viewIGMList()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$impno=$this->input->post('impno');
		$lineno=$this->input->post('lineno');
		$igm=$this->input->post('options');
		$impno1=$this->input->post('impno1');
		$blno=$this->input->post('blno');
		$data['impno']=$impno;
		$data['lineno']=$lineno;
		$data['igm']=$igm;
		$data['impno1']=$impno1;
		$data['blno']=$blno;
		
		//echo $impno."=rot=".$lineno."=Line=".$igm."=igm=".$impno1."=rot2=".$blno."=bol=";
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="Check The Document...";
			$this->load->view('header3');
			$this->load->view('myCustomAirIgmHTML',$data);
			$this->load->view('footer2');
		}
	}
		
		
	//view Check the IGM List...............................................
	function checkTheIGMList()
	{
		//$type_of_Igm = $this->uri->segment(3);
		$txt_imp_rot=$this->input->post('txt_imp_rot');
		$txt_line=$this->input->post('txt_line');
		$txt_imp_rot1=$this->input->post('txt_imp_rot1');
		$txt_bl=$this->input->post('txt_bl');
							
		$data['title']="Check The Document...";
		$data['txt_imp_rot']=$txt_imp_rot;
		$data['txt_line']=$txt_line;
		$data['txt_imp_rot1']=$txt_imp_rot1;
		$data['txt_bl']=$txt_bl;
		
		$this->load->view('header3');
		$this->load->view('myCustomDocumentcheckList',$data);
		//$this->load->view('footer');	
	}
		
	//UpDate Manifest....
	function updateManifest(){
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$data['title']="CONVERT IGM...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myUpdateManifest',$data);
			$this->load->view('jsAssets');
		}	
	}
		
	//UpDate Manifest....
	/* function updateManifestList(){
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();				
		}
		else
		{
			$ddl_imp_rot_no=$this->input->post('ddl_imp_rot_no');
			$data['title']="UPDATE MANIFEST...";
			//$this->load->view('header2');
			$this->load->model('ci_auth', 'bm', TRUE);
			$igmContainerList = $this->bm->updateManifestList($ddl_imp_rot_no);
			//echo 
			$strChkEdi = "select count(*) as rtnValue from edi_stow_info where ucase(file_name_edi)=ucase(concat(replace('$ddl_imp_rot_no','/','_'),'.edi'))";
			$ediSt = $this->bm->dataReturnDb1($strChkEdi);
			
			$strCntryCode = "select sparcsn4.vsl_vessels.country_code as rtnValue
			from sparcsn4.vsl_vessel_visit_details
			inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			where ib_vyg='$ddl_imp_rot_no'";
			$CntryCode = $this->bm->dataReturn($strCntryCode);
			
			if(count($igmContainerList)==0)
			{
				$data['title']="CONVERT IGM...";
				$data['msg']="<font color='red' size='2'><b>You can not convert IGM before getting IGM from Customs ASYCODA WORLD...</b></font>";
				$this->load->view('header2',$igmContainerList);
				$this->load->view('myUpdateManifest',$data);
				$this->load->view('footer');
			}
			// else if($ediSt==0 and $CntryCode!="BD")		//changed on 8 May 2019
			// {
				// $data['title']="CONVERT IGM...";
				// $data['msg']="<font color='red' size='2'><b>EDI for Rotation <strong>$ddl_imp_rot_no</strong> not uploaded through <strong>myportpanel</strong>...</b></font>";
				// $this->load->view('header2',$igmContainerList);
				// $this->load->view('myUpdateManifest',$data);
				// $this->load->view('footer');
			// }
			else
			{
				// for checking export loading complete start awal
				$strChkBO = "SELECT IFNULL(IFNULL(flex_string03,flex_string02),'NB') AS rtnValue
				FROM sparcsn4.vsl_vessel_visit_details WHERE ib_vyg='$ddl_imp_rot_no'";
				//$bo = $this->bm->dataReturn($strChkBO);
				$bo = "";
				if($bo=="NB")
				{
					$data['title']="CONVERT IGM...";
					$data['msg']="<font color='red' size='2'><b>Please assign berth operator name for rotaiton ".$ddl_imp_rot_no."...</b></font>";
					$this->load->view('header2',$igmContainerList);
					$this->load->view('myUpdateManifest',$data);
					$this->load->view('footer');
				}
				else
				{
					$strBOpOrg = "select id as rtnValue from organization_profiles where Organization_Name='$bo'";
					$BOpOrg = $this->bm->dataReturnDb1($strBOpOrg);
					
					$strBlockRotCount = "select count(*) as rtnValue from ctmsmis.mis_exp_vvd
					where brth_org_id='$BOpOrg' and ucase(comments) !='OK'";
					//$cnt = $this->bm->dataReturn($strBlockRotCount);
					$cnt = 0;
					
					if($cnt>0)
					{
						$strBlockRotList = "select ib_vyg as rtnValue from ctmsmis.mis_exp_vvd
						inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=ctmsmis.mis_exp_vvd.vvd_gkey
						where brth_org_id='$org_id' and ucase(comments) !='OK'";
						$BlockRotList = $this->bm->dataSelect($strBlockRotList);
						$strBRot = "";
						for($brl=0;$brl<count($strBlockRotList);$brl++)
						{
							$strBRot .= $strBlockRotList[$brl]['rtnValue'].", ";
						}
						$data['title']="CONVERT IGM...";
						$data['msg']="<font color='red' size='2'><b>IGM ".$ddl_imp_rot_no." is not convertable because berth operator of this IGM is ".$bo." and his previous export rotation ".$strBRot." is not complete...</b></font>";
						$this->load->view('header2',$igmContainerList);
						$this->load->view('myUpdateManifest',$data);
						$this->load->view('footer');
					}
					else
					{
						for($i=0;$i<count($igmContainerList);$i++)
						{
							//echo "shemul3";
							$imp=$igmContainerList[$i]['Import_Rotation_No'];
							$Total_number_of_containers=$igmContainerList[$i]['Total_number_of_containers'];
							$Total_number_of_bols=$igmContainerList[$i]['Total_number_of_bols'];
							$Total_number_of_containers=str_replace(" ","",$Total_number_of_containers);
							if($Total_number_of_containers==0)
							{
								$data['title']="CONVERT IGM...";
								$data['msg']="<font color='red' size='2'><b>This is Break Bulk(BB) IGM. You can not convert this IGM</b></font>";
								$this->load->view('header2',$igmContainerList);
								$this->load->view('myUpdateManifest',$data);
								$this->load->view('footer');
							}
							if($Total_number_of_bols>0)
							{
								//echo "shemul2";
								$igmList = $this->bm->updateManifestList1($ddl_imp_rot_no);
								//echo count($igmList);
								if(count($igmList)==0)
								{
									//echo "shemul4";
									$data['title']="CONVERT IGM...";
									$data['msg']="<font color='red' size='2'><b>Only General segment is available from ASYCODA WORLD.<br> You can not convert this IGM before getting BL Segment..</b></font>";
									$this->load->view('header2',$igmContainerList);
									$this->load->view('myUpdateManifest',$data);
									$this->load->view('footer');
								}
								else
								{
									$this->load->view('myUpdateManifestList',$data);
								}
							}						
						}
					}
				}
			}
			
			
			
			
			//$this->load->view('myUpdateManifestList',$data);
			
			///$data['a']=$a;
			//$this->load->view('myUpdateManifest',$data);
			
			//$data['myUpdateManifestList'] = $this->load->view('myUpdateManifestList', $ddl_imp_rot_no, TRUE);
			//$this->load->view ('myUpdateManifest', $data);
			//$this->load->view('footer');
		}	
	} */
		
		
	function updateManifestList()
	{
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();				
		}
		else
		{
			$ddl_imp_rot_no=$this->input->post('ddl_imp_rot_no');
			$data['title']="UPDATE MANIFEST...";
			//$this->load->view('header2');
			$this->load->model('ci_auth', 'bm', TRUE);
			$igmContainerList = $this->bm->updateManifestList($ddl_imp_rot_no);
			//echo count($igmContainerList);

			$data['igmContainerList']= $igmContainerList;
			//echo 
			$strChkEdi = "select count(*) as rtnValue from edi_stow_info where ucase(file_name_edi)=ucase(concat(replace('$ddl_imp_rot_no','/','_'),'.edi'))";
							// echo $strChkEdi;
			$ediSt = $this->bm->dataReturnDb1($strChkEdi);

			// $strCntryCode = "select sparcsn4.vsl_vessels.country_code as rtnValue
			// from sparcsn4.vsl_vessel_visit_details
			// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			// where ib_vyg='$ddl_imp_rot_no'";

			//mysq and oracle both ok 
			include("dbOracleConnection.php");
					
			
			$strCntryCodeQry = "SELECT vsl_vessels.country_code AS rtnValue
			FROM vsl_vessel_visit_details
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			WHERE ib_vyg='$ddl_imp_rot_no'";
			$strCntryCode = oci_parse($con_sparcsn4_oracle, $strCntryCodeQry);
			oci_execute($strCntryCode);
			$CntryCode ="";
			if(($strCntryCode_row= oci_fetch_object($strCntryCode)) != false){
				$CntryCode = $strCntryCode_row->RTNVALUE;
			}




			// echo $strCntryCode;
			//$CntryCode = $this->bm->dataReturn($strCntryCode);

			if(count($igmContainerList)==0)
			{
				$data['title']="CONVERT IGM...";
				$data['msg']="<font color='red' size='2'><b>You can not convert IGM before getting IGM from Customs ASYCODA WORLD...</b></font>";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myUpdateManifest',$data);
				$this->load->view('jsAssets');
			}
			else if($ediSt==0 and $CntryCode!="BD")
			{
				$data['title']="CONVERT IGM...";
				$data['msg']="<font color='red' size='2'><b>EDI for Rotation <strong>$ddl_imp_rot_no</strong> not uploaded through <strong>PCS</strong>...</b></font>";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('myUpdateManifest',$data);
				$this->load->view('jsAssets');
			}
			else
			{
				// for checking export loading complete start awal
				//in MYSQL IFNULL -----IN ORACLE NVL Changed by Asif
				$strChkBOQuery = "SELECT NVL(NVL(flex_string03,flex_string02),'NB') AS rtnValue FROM vsl_vessel_visit_details WHERE ib_vyg='$ddl_imp_rot_no'";
				$strChkBOQueryRes = oci_parse($con_sparcsn4_oracle, $strChkBOQuery);
				oci_execute($strChkBOQueryRes);
				$bo ="";
				if(($strChkBOQueryRes_row= oci_fetch_object($strChkBOQueryRes)) != false){
					//$strBRot .= $BlockRotList[$brl]['rtnValue'].", ";
					$bo = $strChkBOQueryRes_row->RTNVALUE;
				}


				
				
				
				//$bo = $this->bm->dataReturn($strChkBO);
				//return;
				$bo = "";
				if($bo=="NB")
				{
					$data['title']="CONVERT IGM...";
					$data['msg']="<font color='red' size='2'><b>Please assign berth operator name for rotaton ".$ddl_imp_rot_no."...</b></font>";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('myUpdateManifest',$data);
					$this->load->view('jsAssets');
				}
				else
				{
					// $strBOpOrg = "select id as rtnValue from organization_profiles where Organization_Name='$bo'";
					$strBOpOrg = "select id as rtnValue from organization_profiles where Organization_Name='$bo'";
					$BOpOrg = $this->bm->dataReturnDb1($strBOpOrg);
					
					$strBlockRotCount = "select count(*) as rtnValue from ctmsmis.mis_exp_vvd
					where brth_org_id='$BOpOrg' and ucase(comments) !='OK'";
					//$cnt = $this->bm->dataReturn($strBlockRotCount);
					$cnt = 0;
					//echo $strBlockRotCount;
					//return;SAIF POWERTEC
					if($cnt>5 and $bo=='SAIF POWERTEC' )
					{
						// $strBlockRotList = "select ib_vyg as rtnValue from ctmsmis.mis_exp_vvd
						// inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=ctmsmis.mis_exp_vvd.vvd_gkey
						// where brth_org_id='$org_id' and ucase(comments) !='OK'";
						
						$strBlockRotList = "SELECT vvd_gkey FROM ctmsmis.mis_exp_vvd WHERE brth_org_id='$org_id' AND UCASE(comments) !='OK'";
						$BlockRotList = $this->bm->dataSelect($strBlockRotList);
						$strBRot = "";
						
						
						for($brl=0;$brl<count($BlockRotList);$brl++)
						{
							
							$get_rotation_using_gkey_query = "select ib_vyg as rtnValue FROM vsl_vessel_visit_details where vsl_vessel_visit_details.vvd_gkey ='$BlockRotList[$brl]['vvd_gkey']'";
							$get_rotation_rslt = oci_parse($con_sparcsn4_oracle, $get_rotation_using_gkey_query);
							oci_execute($get_rotation_rslt);
							if(($rotation_row= oci_fetch_object($get_rotation_rslt)) != false){
								//$strBRot .= $BlockRotList[$brl]['rtnValue'].", ";
								$strBRot .= $rotation_row->rtnValue.", ";
							}
							
							
							
						}
						$data['title']="CONVERT IGM...";
						$data['msg']="<font color='red' size='2'><b>IGM ".$ddl_imp_rot_no." is not convertable because berth operator of this IGM is ".$bo." and his previous export rotation ".$strBRot." is not complete...</b></font>";
						$this->load->view('cssAssets');
						$this->load->view('headerTop');
						$this->load->view('sidebar');
						$this->load->view('myUpdateManifest',$data);
						$this->load->view('jsAssets');
					}
					else if($cnt>0 and $bo!='SAIF POWERTEC' )
					{
						/* $strBlockRotList = "select ib_vyg as rtnValue from ctmsmis.mis_exp_vvd
						inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=ctmsmis.mis_exp_vvd.vvd_gkey
						where brth_org_id='$org_id' and ucase(comments) !='OK'";
						$BlockRotList = $this->bm->dataSelect($strBlockRotList);
						$strBRot = "";
						for($brl=0;$brl<count($BlockRotList);$brl++)
						{
							$strBRot .= $BlockRotList[$brl]['rtnValue'].", ";
						} */
						
						$strBlockRotList = "SELECT vvd_gkey FROM ctmsmis.mis_exp_vvd WHERE brth_org_id='$org_id' AND UCASE(comments) !='OK'";
						$BlockRotList = $this->bm->dataSelect($strBlockRotList);
						$strBRot = "";
						
							
						for($brl=0;$brl<count($BlockRotList);$brl++)
						{
							
							$get_rotation_using_gkey_query = "select ib_vyg as rtnValue FROM vsl_vessel_visit_details where vsl_vessel_visit_details.vvd_gkey ='$BlockRotList[$brl]['vvd_gkey']'";
							$get_rotation_rslt = oci_parse($con_sparcsn4_oracle, $get_rotation_using_gkey_query);
							oci_execute($get_rotation_rslt);
							if(($rotation_row= oci_fetch_object($get_rotation_rslt)) != false){
								//$strBRot .= $BlockRotList[$brl]['rtnValue'].", ";
								$strBRot .= $rotation_row->rtnValue.", ";
							}
							
							
							
						}
						
						
						$data['title']="CONVERT IGM...";
						$data['msg']="<font color='red' size='2'><b>IGM ".$ddl_imp_rot_no." is not convertable because berth operator of this IGM is ".$bo." and his previous export rotation ".$strBRot." is not complete...</b></font>";
						$this->load->view('cssAssets');
						$this->load->view('headerTop');
						$this->load->view('sidebar');
						$this->load->view('myUpdateManifest',$data);
						$this->load->view('jsAssets');
					}
					else
					{
						for($i=0;$i<count($igmContainerList);$i++)
						{
							//echo "shemul3";
							$imp=$igmContainerList[$i]['Import_Rotation_No'];
							$Total_number_of_containers=$igmContainerList[$i]['Total_number_of_containers'];
							$Total_number_of_bols=$igmContainerList[$i]['Total_number_of_bols'];
							$Total_number_of_containers=str_replace(" ","",$Total_number_of_containers);
							if($Total_number_of_containers==0)
							{
								$data['title']="CONVERT IGM...";
								$data['msg']="<font color='red' size='2'><b>This is Break Bulk(BB) IGM. You can not convert this IGM</b></font>";
								$this->load->view('cssAssets');
								$this->load->view('headerTop');
								$this->load->view('sidebar');
								$this->load->view('myUpdateManifest',$data);
								$this->load->view('jsAssets');
							}
							if($Total_number_of_bols>0)
							{
								//echo "shemul2";
								$igmList = $this->bm->updateManifestList1($ddl_imp_rot_no);
								//echo count($igmList);
								if(count($igmList)==0)
								{
									//echo "shemul4";
									$data['title']="CONVERT IGM...";
									$data['msg']="<font color='red' size='2'><b>Only General segment is available from ASYCODA WORLD.<br> You can not convert this IGM before getting BL Segment..</b></font>";
									$this->load->view('cssAssets');
									$this->load->view('headerTop');
									$this->load->view('sidebar');
									$this->load->view('myUpdateManifest',$data);
									$this->load->view('jsAssets');
								}
								else
								{
									//if($bo=='SAIF POWERTEC' or strtoupper($login_id)=="SAIFBO") 
									//{
										//$this->load->view('myUpdateManifestListSaifError',$data);//this page is for 'sa' awal 'if' 'tims' error
									//}
									//else
									//{
										// $this->load->view('cssAssets');
										// $this->load->view('headerTop');
										// $this->load->view('sidebar');
										// $this->load->view('myUpdateManifest',$data);
										// $this->load->view('jsAssets');
										$this->load->view('myUpdateManifestList',$data);
									//}
								}
							}						
						}
					}
				}
			}
			
			//$this->load->view('myUpdateManifestList',$data);
			
			///$data['a']=$a;
			//$this->load->view('myUpdateManifest',$data);
			
			//$data['myUpdateManifestList'] = $this->load->view('myUpdateManifestList', $ddl_imp_rot_no, TRUE);
			//$this->load->view ('myUpdateManifest', $data);
			//$this->load->view('footer');
		}	
	}
		
		
		
	//view Delivery Dash Board HTML..........................................
	function myDBDelivery(){
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="Delivery Dash Board Import...";
			$this->load->view('header2');
			$this->load->view('myDBDeliveryGoodsHTML',$data);
			$this->load->view('footer');
		}
	}
		
	//view Delivery Dash Board List...............................................
	function myDBDeliveryList()
	{
	
		//$type_of_Igm = $this->uri->segment(3);
		$txt_imp_rot=$this->input->post('txt_imp_rot');
		$txt_line=$this->input->post('txt_line');
		$txt_imp_rot1=$this->input->post('txt_imp_rot1');
		$txt_bl=$this->input->post('txt_bl');			        
		$data['title']="Delivery Dash Board Import...";
		$data['txt_imp_rot']=$txt_imp_rot;
		$data['txt_line']=$txt_line;
		$data['txt_imp_rot1']=$txt_imp_rot1;
		$data['txt_bl']=$txt_bl;
		
		$this->load->view('header2');
		$this->load->view('myCustomDocumentcheckList',$data);
		$this->load->view('footer');
	
	}

	function viewImporterList()
	{
		$this->load->model('ci_auth', 'bm', TRUE);
		$session_id = $this->session->userdata('value');
		$type = $this->uri->segment(3);
		$search=$this->input->post('search');
		
		/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("igmViewController/viewImporterList/$type");
			$config["total_rows"] = $this->bm->record_count_igm();
			$config["per_page"] = 20;
			$config["uri_segment"] = 4;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			
			$limit=$config["per_page"];
			$start=$page;
		
			/***********Pagination***************/
		
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			
			if($type=="search"){
				$sql="select distinct Notify_code,ifnull(Notify_name,NotifyDesc) as notify_name,Notify_address from igm_details where upper(ifnull(notify_name,NotifyDesc)) like upper('%$search%')order by id desc limit $start,$limit";
			}
			else
				$sql="select distinct Notify_code,ifnull(Notify_name,NotifyDesc) as notify_name,Notify_address from igm_details order by id desc limit $start,$limit";
			//echo $sql;
			
			$igmImporterList = $this->bm->dataSelectDb1($sql);
			$data['igmImporterList']=$igmImporterList;
			
			$data["links"] = $this->pagination->create_links();
			$data['title']="Importer List...";
			$this->load->view('header2');
			$this->load->view('myViewImporterList',$data);
			$this->load->view('footer');
		}
	}
		
	function viewExporterList()
	{
		$this->load->model('ci_auth', 'bm', TRUE);
		$session_id = $this->session->userdata('value');
		$type = $this->uri->segment(3);
		$search=$this->input->post('search');
		
		/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("igmViewController/viewExporterList/$type");
			$config["total_rows"] = $this->bm->record_count_igm();
			$config["per_page"] = 20;
			$config["uri_segment"] = 4;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			
			$limit=$config["per_page"];
			$start=$page;
		
			/***********Pagination***************/
		
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			if($type=="search"){
				$sql="select distinct Exporter_name,Exporter_address from igm_details  where Exporter_name like '%$search%' order by id desc limit $start,$limit";
			}
			else
				$sql="select distinct Exporter_name,Exporter_address from igm_details order by id desc limit $start,$limit";
			//echo $sql;
			
			$igmImporterList = $this->bm->dataSelectDb1($sql);
			$data['igmImporterList']=$igmImporterList;
			
			$data["links"] = $this->pagination->create_links();
			$data['title']="Exporter List...";
			$this->load->view('header2');
			$this->load->view('myViewExporterList',$data);
			$this->load->view('footer');
		}
	}
		
		
	function logout()
	{
		
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";

		$this->session->sess_destroy();
		$this->cache->clean();
		//redirect(base_url(),$data);
		// $this->load->view('header');
		// $this->load->view('welcomeview_1', $data);
		// $this->load->view('footer');

		$this->load->view('cssVesselList');
		$this->load->view('jsVesselList');
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/slider');
		$this->load->view('FrontEnd/index',$data);
		$this->load->view('FrontEnd/footer');

		$this->db->cache_delete_all();
	}
		
	// IGM INFORMATION ENTRY START	
	
	function igmInfoProcessForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$data['title']="IGM INFORMATION PROCESS FORM...";
			$data['msg']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igmInfoProcessForm',$data);
			$this->load->view('jsAssets');
			
		}
	}
	
	
	function igmInfoProcess()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$IGM_id=$this->input->post('IGM_id');
			$rotation_no=$this->input->post('rotation_no');
			$line_no=$this->input->post('line_no');			
			$bl_no=$this->input->post('bl_no');			
			$pck_num=$this->input->post('pck_num');
			$pck_desc=$this->input->post('pck_desc');
			$pck_marks_num=$this->input->post('pck_marks_num');
			$goods_desc=$this->input->post('goods_desc');
			$weight=$this->input->post('weight');
			$remarks=$this->input->post('remarks');
			$cons_desc=$this->input->post('cons_desc');
			$not_desc=$this->input->post('not_desc');
			//$sub_id=$this->input->post('sub_id');
			//$sub_dt=$this->input->post('sub_dt');
			$mlo_code=$this->input->post('mlo_code');
			$exp_name=$this->input->post('exp_name');
			$exp_addr=$this->input->post('exp_addr');
			$not_code=$this->input->post('not_code');
			$not_name=$this->input->post('not_name');
			$not_addr=$this->input->post('not_addr');
			$cons_code=$this->input->post('cons_code');
			$cons_name=$this->input->post('cons_name');
			$cons_addr=$this->input->post('cons_addr');
			$dg_stat=$this->input->post('dg_stat');
			$unload_code=$this->input->post('unload_code');
			$origine_code=$this->input->post('origine_code');
			$comment=$this->input->post('comment');
			$user = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			//$login_id = $this->session->userdata('login_id');
			if($IGM_id>0)
			{
				//---------
				$sql_igmDtl_prev_data="SELECT * FROM igm_details WHERE IGM_id='$IGM_id' AND Import_Rotation_No='$rotation_no' AND BL_No='$bl_no'";
				$rslt_igmDtl_prev_data=$this->bm->dataSelectDb1($sql_igmDtl_prev_data);
				
				$IGM_id_old=$rslt_igmDtl_prev_data[0]['IGM_id'];
				$rotation_no_old=$rslt_igmDtl_prev_data[0]['Import_Rotation_No'];
				$line_no_old=$rslt_igmDtl_prev_data[0]['Line_No'];
				$bl_no_old=$rslt_igmDtl_prev_data[0]['BL_No'];
				$pck_num_old=$rslt_igmDtl_prev_data[0]['Pack_Number'];
				$pck_desc_old=$rslt_igmDtl_prev_data[0]['Pack_Description'];
				$pck_marks_num_old=$rslt_igmDtl_prev_data[0]['Pack_Marks_Number'];
				$goods_desc_old=$rslt_igmDtl_prev_data[0]['Description_of_Goods'];
				$weight_old=$rslt_igmDtl_prev_data[0]['weight'];
				$remarks_old=$rslt_igmDtl_prev_data[0]['Remarks'];
				$cons_desc_old=$rslt_igmDtl_prev_data[0]['ConsigneeDesc'];
				$not_desc_old=$rslt_igmDtl_prev_data[0]['NotifyDesc'];
				$mlo_code_old=$rslt_igmDtl_prev_data[0]['mlocode'];
				$exp_name_old=$rslt_igmDtl_prev_data[0]['Exporter_name'];
				$exp_addr_old=$rslt_igmDtl_prev_data[0]['Exporter_address'];
				$not_code_old=$rslt_igmDtl_prev_data[0]['Notify_code'];
				$not_name_old=$rslt_igmDtl_prev_data[0]['Notify_name'];
				$not_addr_old=$rslt_igmDtl_prev_data[0]['Notify_address'];
				$cons_code_old=$rslt_igmDtl_prev_data[0]['Consignee_code'];
				$cons_name_old=$rslt_igmDtl_prev_data[0]['Consignee_name'];
				$cons_addr_old=$rslt_igmDtl_prev_data[0]['Consignee_address'];
				$dg_stat_old=$rslt_igmDtl_prev_data[0]['DG_status'];
				$unload_code_old=$rslt_igmDtl_prev_data[0]['place_of_unloading'];
				$origine_code_old=$rslt_igmDtl_prev_data[0]['port_of_origin'];
				
				$insertLogQry="INSERT INTO igm_log_dtl (IGM_id,Import_Rotation_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,Remarks,ConsigneeDesc,NotifyDesc,mlocode,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,DG_status,place_of_unloading,port_of_origin,log_entry_by,log_entry_date,ip_addr,log_entry_status,log_comment) 
				VALUES ('$IGM_id_old','$rotation_no_old','$line_no_old','$bl_no_old','$pck_num_old','$pck_desc_old','$pck_marks_num_old','$goods_desc_old','$weight_old','$remarks_old','$cons_desc_old','$not_desc_old','$mlo_code_old','$exp_name_old','$exp_addr_old','$not_name_old','$not_name_old','$not_addr_old','$cons_code_old','$cons_name_old','$cons_addr_old','$dg_stat_old','$unload_code_old','$origine_code_old','$user',NOW(),$ipaddr,'update','$comment')";
				
				//	$this->bm->dataInsertDB1($insertLogQry);
				//---------
				$insertIgmDtlQry="update igm_details set Line_No='$line_no',BL_No='$bl_no',Pack_Number='$pck_num',Pack_Description='$pck_desc',
							Pack_Marks_Number='$pck_marks_num',Description_of_Goods='$goods_desc',
							weight='$weight',Remarks='$remarks',ConsigneeDesc='$cons_desc',NotifyDesc='$not_desc',mlocode='$mlo_code',
							Exporter_name='$exp_name',Exporter_address='$exp_addr',Notify_code='$not_code',Notify_name='$not_name',Notify_address='$not_addr',
							Consignee_code='$cons_code',Consignee_name='$cons_name',Consignee_address='$cons_addr',DG_status='$dg_stat',
							place_of_unloading='$unload_code',port_of_origin='$origine_code' 
							where IGM_id='$IGM_id' and Import_Rotation_No='$rotation_no' and BL_No='$bl_no'";
							
				
			}
			else
			{
				$insertIgmDtlQry="insert into igm_details (IGM_id,Import_Rotation_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,
							weight,Remarks,ConsigneeDesc,NotifyDesc,mlocode,
							Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,DG_status,
							place_of_unloading,port_of_origin) values ('$IGM_id','$rotation_no','$line_no','$bl_no','$pck_num','$pck_desc','$pck_marks_num','$goods_desc','$weight',
							'$remarks','$cons_desc','$not_desc','$mlo_code','$exp_name','$exp_addr','$not_code','$not_name','$not_addr','$cons_code','$cons_name','$cons_addr',
							'$dg_stat','$unload_code','$origine_code')";
				$insertLogQry="INSERT INTO igm_log_dtl (IGM_id,Import_Rotation_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,Remarks,ConsigneeDesc,NotifyDesc,mlocode,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,DG_status,place_of_unloading,port_of_origin,log_entry_by,log_entry_date,ip_addr,log_entry_status,log_comment) 
				VALUES ('$IGM_id','$rotation_no','$line_no','$bl_no','$pck_num','$pck_desc','$pck_marks_num','$goods_desc','$weight','$remarks','$cons_desc','$not_desc','$mlo_code','$exp_name','$exp_addr','$not_code','$not_name','$not_addr','$cons_code','$cons_name','$cons_addr','$dg_stat','$unload_code','$origine_code','$user',NOW(),$ipaddr,'insert','$comment')";
				//	$this->bm->dataInsertDB1($insertLogQry);
			}
			//echo $insertIgmDtlQry;
			//return;
			//$statIgmDtl = $this->bm->dataInsertDB1($insertIgmDtlQry);  
			$statIgmDtl = 1;  
			if($statIgmDtl==1)
			{
				$data['msg']="IGM INFORMATION INSERTED SUCCESSFULLY FOR ROTATION  -> <b>".$rotation_no."</b>";
				// $insertLogQry="INSERT INTO cchaportdb.igm_log_manual_entry (Import_Rotation_No,BL_No,igm_table,entry_by,entry_date,ip_addr,entry_status,comment)
				// VALUES ('$rotation_no','$bl_no','igm_dtl','$user',now(),'$ipaddr','insert','$comment')";
				//echo $insertLogQry;
			
			}
			else
			{
				$data['msg']="IGM INFORMATION NOT INSERTED -> <b>".$rotation_no."</b>";
			}
			
			$data['title']="IGM INFORMATION PROCESS FORM...";		
			$this->load->view('header2');
			$this->load->view('igmInfoProcessForm',$data);
			$this->load->view('footer');
			
		}
	}
		// IGM INFORMATION ENTRY END
		
	//igm sup_detail entry - start
	function igm_sup_dtl_entry_form()				
	{		
		$login_id = $this->session->userdata('login_id');
		$data['title']="IGM Supplementary Entry Form...";
		$data['msg']="";
		$data['search_flag']=0;		
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('igm_sup_dtl_entry_form',$data);
		$this->load->view('jsAssets');
	}
	
	function igm_detail_search()					
	{		
		$login_id = $this->session->userdata('login_id');
		$data['title']="IGM Supplementary Entry Form...";
		
		$search_flag=1;
		$rot_igmDtl=$this->input->post('rot_igmDtl');
		$bl_igmDtl=$this->input->post('bl_igmDtl');
		
		$sql_search_igmDtl="SELECT * FROM igm_details 
		WHERE Import_Rotation_No='$rot_igmDtl' AND BL_No='$bl_igmDtl'";
		$rslt_search_igmDtl = $this->bm->dataSelectDb1($sql_search_igmDtl);
		
		$sql_search_igmSupDtl="SELECT id AS igmSupDtl_id,BL_No,Description_of_Goods,Exporter_name,Notify_name,Consignee_name 
		FROM igm_supplimentary_detail 
		WHERE Import_Rotation_No='$rot_igmDtl' AND master_BL_No='$bl_igmDtl'";
		$rslt_search_igmSupDtl = $this->bm->dataSelectDb1($sql_search_igmSupDtl);
					
		$data['search_flag']=$search_flag;			
		$data['rslt_search_igmDtl']=$rslt_search_igmDtl;			
		$data['rslt_search_igmSupDtl']=$rslt_search_igmSupDtl;					
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('igm_sup_dtl_entry_form',$data);
		$this->load->view('jsAssets');
	}
	
	function igmSupDtl_edit_form()
	{
		$login_id = $this->session->userdata('login_id');
		$data['title']="IGM Supplementary Entry Form...";
		$flag=$this->input->post('flag');
		$igmSupDtl_id="";
		$msg="";
		
		if($flag=="edit")
		{
			$igmSupDtl_id=$this->input->post('igmSupDtl_id');
			
			$sql_select_igmSupDtl="SELECT igm_master_id,igm_detail_id,Import_Rotation_No,master_Line_No,master_BL_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,ConsigneeDesc,NotifyDesc,Submitee_Id,Submission_Date,Submitee_Org_Id,user_action,last_update,type_of_IGM,final_submit,weight_unit,file_clearence_date,PFstatusdt,final_submit_date,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,port_of_origin
			FROM igm_supplimentary_detail WHERE id='$igmSupDtl_id'";
			
			$rslt_select_igmSupDtl=$this->bm->dataSelectDb1($sql_select_igmSupDtl);
			$data['igmSupDtl_id']=$igmSupDtl_id;
			$data['rslt_select_igmSupDtl']=$rslt_select_igmSupDtl;
		}	
		else if($flag=="insert")
		{
			$Import_Rotation_No=$this->input->post('Import_Rotation_No');
			$master_bl_no=$this->input->post('master_bl_no');
			
			$data['Import_Rotation_No']=$Import_Rotation_No;
			$data['master_bl_no']=$master_bl_no;
		}
		
		$data['flag']=$flag;		
				
		$this->load->view('header2');
		$this->load->view('igmSupDtl_edit_form',$data);
		$this->load->view('footer');
	}
	
	function igmSupDtl_edit_form_action()
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		$flag_state=$this->input->post('flag_state');			
		$igmSupDtl_id=$this->input->post('igmSupDtl_id');		// hidden start - for log, not to update
		$igm_master_id=$this->input->post('igm_master_id');
		$igm_detail_id=$this->input->post('igm_detail_id');
		$Import_Rotation_No=$this->input->post('Import_Rotation_No');
		$master_line_no=$this->input->post('master_line_no');		
		$master_bl_no=$this->input->post('master_bl_no');		// hidden end - for log, not to update
		
		$line_no=$this->input->post('line_no');
		$bl_no=$this->input->post('bl_no');
		$pack_number=$this->input->post('pack_number');
		$pack_description=$this->input->post('pack_description');
		$pack_marks_number=$this->input->post('pack_marks_number');
		$description_of_goods=$this->input->post('description_of_goods');
		$weight=$this->input->post('weight');
		$consignee_desc=$this->input->post('consignee_desc');
		$notify_desc=$this->input->post('notify_desc');
		$submitee_id=trim($this->input->post('submitee_id'));
		$submission_date=$this->input->post('submission_date');
		$submitee_org_id=$this->input->post('submitee_org_id');
		$user_action=$this->input->post('user_action');
		$type_of_IGM=$this->input->post('type_of_IGM');
		$final_submit=$this->input->post('final_submit');
		$weight_unit=$this->input->post('weight_unit');
		$file_clearence_date=$this->input->post('file_clearence_date');
		$pfstatusdt=$this->input->post('pfstatusdt');
		$final_submit_date=$this->input->post('final_submit_date');
		$exporter_name=$this->input->post('exporter_name');
		$exporter_address=$this->input->post('exporter_address');
		$notify_code=$this->input->post('notify_code');
		$notify_name=$this->input->post('notify_name');
		$notify_address=$this->input->post('notify_address');		
		$consignee_code=$this->input->post('consignee_code');
		$consignee_name=$this->input->post('consignee_name');
		$consignee_address=$this->input->post('consignee_address');
		$volume_in_cubic_meters=$this->input->post('volume_in_cubic_meters');
		$port_of_origin=$this->input->post('port_of_origin');
		$comment=$this->input->post('comment');
		
		if($flag_state=="edit")
		{
			//-----------
			$sql_igmSupDtl_prev_data="SELECT * FROM igm_supplimentary_detail WHERE id='$igmSupDtl_id'";
			$rslt_igmSupDtl_prev_data=$this->bm->dataSelectDb1($sql_igmSupDtl_prev_data);
			
			$igm_master_id_old=$rslt_igmSupDtl_prev_data[0]['igm_master_id'];
			$igm_detail_id_old=$rslt_igmSupDtl_prev_data[0]['igm_detail_id'];
			$Import_Rotation_No_old=$rslt_igmSupDtl_prev_data[0]['Import_Rotation_No'];
			$master_line_no_old=$rslt_igmSupDtl_prev_data[0]['master_Line_No'];
			$master_bl_no_old=$rslt_igmSupDtl_prev_data[0]['master_BL_No'];
			$line_no_old=$rslt_igmSupDtl_prev_data[0]['Line_No'];
			$bl_no_old=$rslt_igmSupDtl_prev_data[0]['BL_No'];
			$pack_number_old=$rslt_igmSupDtl_prev_data[0]['Pack_Number'];
			
			$pack_description_old=$rslt_igmSupDtl_prev_data[0]['Pack_Description'];
			$pack_marks_number_old=$rslt_igmSupDtl_prev_data[0]['Pack_Marks_Number'];
			$description_of_goods_old=$rslt_igmSupDtl_prev_data[0]['Description_of_Goods'];
			$weight_old=$rslt_igmSupDtl_prev_data[0]['weight'];
			$consignee_desc_old=$rslt_igmSupDtl_prev_data[0]['ConsigneeDesc'];
			$notify_desc_old=$rslt_igmSupDtl_prev_data[0]['NotifyDesc'];
			$submitee_id_old=$rslt_igmSupDtl_prev_data[0]['Submitee_Id'];
			$submission_date_old=$rslt_igmSupDtl_prev_data[0]['Submission_Date'];
			$submitee_org_id_old=$rslt_igmSupDtl_prev_data[0]['Submitee_Org_Id'];
			$user_action_old=$rslt_igmSupDtl_prev_data[0]['user_action'];
			$last_update=$rslt_igmSupDtl_prev_data[0]['last_update'];
			$type_of_IGM_old=$rslt_igmSupDtl_prev_data[0]['type_of_igm'];
			$final_submit_old=$rslt_igmSupDtl_prev_data[0]['final_submit'];
			$weight_unit_old=$rslt_igmSupDtl_prev_data[0]['weight_unit'];
			$file_clearence_date_old=$rslt_igmSupDtl_prev_data[0]['file_clearence_date'];
			$pfstatusdt_old=$rslt_igmSupDtl_prev_data[0]['PFstatusdt'];
			$final_submit_date_old=$rslt_igmSupDtl_prev_data[0]['final_submit_date'];
			$exporter_name_old=$rslt_igmSupDtl_prev_data[0]['Exporter_name'];
			$exporter_address_old=$rslt_igmSupDtl_prev_data[0]['Exporter_address'];
			$notify_code_old=$rslt_igmSupDtl_prev_data[0]['Notify_code'];
			$notify_name_old=$rslt_igmSupDtl_prev_data[0]['Notify_name'];
			$notify_address_old=$rslt_igmSupDtl_prev_data[0]['Notify_address'];		
			$consignee_code_old=$rslt_igmSupDtl_prev_data[0]['Consignee_code'];
			$consignee_name_old=$rslt_igmSupDtl_prev_data[0]['Consignee_name'];
			$consignee_address_old=$rslt_igmSupDtl_prev_data[0]['Consignee_address'];
			$volume_in_cubic_meters_old=$rslt_igmSupDtl_prev_data[0]['Volume_in_cubic_meters'];
			$port_of_origin_old=$rslt_igmSupDtl_prev_data[0]['port_of_origin'];
			
			$sql_log_entry="INSERT INTO igm_log_sup_dtl(igm_master_id,igm_detail_id,Import_Rotation_No,master_Line_No,master_BL_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,ConsigneeDesc,NotifyDesc,Submitee_Id,Submission_Date,Submitee_Org_Id,user_action,last_update,type_of_IGM,final_submit,weight_unit,file_clearence_date,PFstatusdt,final_submit_date,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,port_of_origin,log_entry_by,log_entry_date,ip_addr,log_entry_status,log_comment) 
			VALUES('$igm_master_id_old','$igm_detail_id_old','$Import_Rotation_No_old','$master_line_no_old','$master_bl_no_old','$line_no_old','$bl_no_old','$pack_number_old','$pack_description_old','$pack_marks_number_old','$description_of_goods_old','$weight_old','$consignee_desc_old','$notify_desc_old','$submitee_id_old','$submission_date_old','$submitee_org_id_old','$user_action_old','$last_update','$type_of_IGM_old','$final_submit_old','$weight_unit_old','$file_clearence_date_old','$pfstatusdt_old','$final_submit_date_old','$exporter_name_old','$exporter_address_old','$notify_code_old','$notify_name_old','$notify_address_old','$consignee_code_old','$consignee_name_old','$consignee_address_old','$volume_in_cubic_meters_old','$port_of_origin_old','$login_id',NOW(),'$ipaddr','update','$comment')";
			
				$statIgmDtl_log = $this->bm->dataInsertDB1($sql_log_entry);  
			//-----------
			$sql_edit_igmSupDtl="UPDATE igm_supplimentary_detail 
			SET Line_No='$line_no',BL_No='$bl_no',Pack_Number='$pack_number',Pack_Description='$pack_description',Pack_Marks_Number='$pack_marks_number',Description_of_Goods='$description_of_goods',weight='$weight',ConsigneeDesc='$consignee_desc',NotifyDesc='$notify_desc',Submitee_Id='$submitee_id',Submission_Date='$submission_date',Submitee_Org_Id='$submitee_org_id',user_action='$user_action',last_update=NOW(),type_of_igm='$type_of_IGM',final_submit='$final_submit',weight_unit='$weight_unit',file_clearence_date='$file_clearence_date',PFstatusdt='$pfstatusdt',final_submit_date='$final_submit_date',Exporter_name='$exporter_name',Exporter_address='$exporter_address',Notify_code='$notify_code',Notify_name='$notify_name',Notify_address='$notify_address',Consignee_code='$consignee_code',Consignee_name='$consignee_name',Consignee_address='$consignee_address',Volume_in_cubic_meters='$volume_in_cubic_meters',port_of_origin='$port_of_origin'
			WHERE id='$igmSupDtl_id'";
			
			
		}
		else if($flag_state=="insert")
		{
			$sql_igm_master_id="SELECT id AS rtnValue FROM igm_masters WHERE Import_Rotation_No='$Import_Rotation_No'";
			$igm_master_id=$this->bm->dataReturnDb1($sql_igm_master_id);
			
			$sql_igm_dtl_id_line="SELECT id AS igm_detail_id,Line_No AS master_Line_no 
			FROM igm_details 
			WHERE Import_Rotation_No='$Import_Rotation_No' AND BL_No='$master_bl_no'";
			$rslt_igm_dtl_id_line=$this->bm->dataSelectDb1($sql_igm_dtl_id_line);
			$igm_detail_id=$rslt_igm_dtl_id_line[0]['igm_detail_id'];
			$master_line_no=$rslt_igm_dtl_id_line[0]['master_Line_no'];			
			
			$sql_edit_igmSupDtl="INSERT INTO igm_supplimentary_detail(igm_master_id,igm_detail_id,Import_Rotation_No,master_Line_No,master_BL_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,ConsigneeDesc,NotifyDesc,Submitee_Id,Submission_Date,Submitee_Org_Id,user_action,last_update,type_of_IGM,final_submit,weight_unit,file_clearence_date,PFstatusdt,final_submit_date,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,port_of_origin) 
			VALUES('$igm_master_id','$igm_detail_id','$Import_Rotation_No','$master_line_no','$master_bl_no','$line_no','$bl_no','$pack_number','$pack_description','$pack_marks_number','$description_of_goods','$weight','$file_clearence_date','$pfstatusdt','$final_submit_date','$consignee_desc','$notify_desc','$submitee_id','$submission_date','$submitee_org_id','$user_action',NOW(),'$type_of_IGM','$final_submit','$weight_unit','$exporter_name','$exporter_address','$notify_code','$notify_name','$notify_address','$consignee_code','$consignee_name','$consignee_address','$volume_in_cubic_meters','$port_of_origin')";
			
			$sql_log_entry="INSERT INTO igm_log_sup_dtl(igm_master_id,igm_detail_id,Import_Rotation_No,master_Line_No,master_BL_No,Line_No,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight,ConsigneeDesc,NotifyDesc,Submitee_Id,Submission_Date,Submitee_Org_Id,user_action,last_update,type_of_IGM,final_submit,weight_unit,file_clearence_date,PFstatusdt,final_submit_date,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,Consignee_code,Consignee_name,Consignee_address,Volume_in_cubic_meters,port_of_origin,log_entry_by,log_entry_date,ip_addr,log_entry_status,log_comment) 
			VALUES('$igm_master_id','$igm_detail_id','$Import_Rotation_No','$master_line_no','$master_bl_no','$line_no','$bl_no','$pack_number','$pack_description','$pack_marks_number','$description_of_goods','$weight','$file_clearence_date','$pfstatusdt','$final_submit_date','$consignee_desc','$notify_desc','$submitee_id','$submission_date','$submitee_org_id','$user_action',NOW(),'$type_of_IGM','$final_submit','$weight_unit','$exporter_name','$exporter_address','$notify_code','$notify_name','$notify_address','$consignee_code','$consignee_name','$consignee_address','$volume_in_cubic_meters','$port_of_origin','$login_id',NOW(),'$ipaddr','insert','$comment')";
			
				$statIgmDtl_log = $this->bm->dataInsertDB1($sql_log_entry);  
		}
		
		$statIgmDtl = $this->bm->dataInsertDB1($sql_edit_igmSupDtl);  
	
	//	$statIgmDtl = 1;  
		if($statIgmDtl==1)
		{
			$data['msg']="IGM SUPPLEMENTARY INFORMATION INSERTED SUCCESSFULLY";
		}
		else
		{
			$data['msg']="IGM SUPPLEMENTARY INFORMATION NOT INSERTED";
		}
		$this->load->view('header2');
		$this->load->view('igmSupDtl_edit_form',$data);
		$this->load->view('footer');
	}
	//igm sup_detail entry - end
	
	function DeliveryOrderBBImgShow($y,$thisbl,$thisrot)
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$thisrot=str_replace('_','/',$thisrot);
			// echo $thisrot;
			// return;
			// $blno=$this->input->post('blno');
			// $rotno=$this->input->post('rotno');
			$queryLastDOImg = "SELECT do_image_loc FROM do_info_image WHERE bl_no='$thisbl' AND imp_rot='$thisrot' ORDER BY id DESC LIMIT $y,1";
			$lastDOImg=$this->bm->dataSelectDb1($queryLastDOImg);
			$data['lastDOImg']=$lastDOImg;
			
			$this->load->view('DOImg',$data);
		}
	}	

	// function DeliveryOrderImgShow($y,$thisbl,$thisrot)
	// {
	// 	$user = $this->session->userdata('login_id');
	// 	$user=str_replace(" ","",$user);
	// 	$session_id = $this->session->userdata('value');
	// 	//print($session_id."<hr>");
		
	// 	if($session_id!=$this->session->userdata('session_id'))
	// 	{
	// 		$this->logout();
	// 	}
	// 	else
	// 	{
	// 		$thisrot=str_replace('_','/',$thisrot);
	// 		// echo $thisrot;
	// 		// return;
	// 		// $blno=$this->input->post('blno');
	// 		// $rotno=$this->input->post('rotno');
	// 		$queryLastDOImg = "SELECT do_image_loc FROM shed_mlo_do_info WHERE bl_no='$thisbl' AND imp_rot='$thisrot' ORDER BY id DESC LIMIT $y,1";
	// 		$lastDOImg=$this->bm->dataSelectDb1($queryLastDOImg);
	// 		$data['lastDOImg']=$lastDOImg;
			
	// 		$this->load->view('DOImg',$data);
	// 	}
	// }

	function DeliveryOrderBBPDFShow($thisbl,$thisrot,$doImgInfoId)
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			// $blno=$this->input->post('blno');
			// $rotno=$this->input->post('rotno');
			// $data['blno']=$blno;
			// $data['rotno']=$rotno;	
			$thisrot=str_replace('_','/',$thisrot);
			$msg = "";
			$data['msg']=$msg;	
			
			$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
					No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,Consignee_name,Consignee_address,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE vsl_dec_type='BB' AND igm_details.Import_Rotation_No='$thisrot' AND BL_No='$thisbl' ORDER BY file_clearence_date DESC";
			$doInfo=$this->bm->dataSelectDb1($query);
			$this->data['doInfo']=$doInfo;
			
			$queryRemainingQty = "SELECT gross_quantity,IFNULL(SUM(delv_quantity),0) AS total_delivered,
								(gross_quantity-IFNULL(SUM(delv_quantity),0)) AS remaining
								FROM do_info_image 
								WHERE do_info_image.imp_rot='$thisrot' AND do_info_image.bl_no='$thisbl'";
			$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
			$this->data['remainingQty']=$remainingQty;
			
			$queryDeliveredQty = "SELECT delv_quantity,measurement FROM do_info_image WHERE id='$doImgInfoId'";
			$deliveredQty=$this->bm->dataSelectDb1($queryDeliveredQty);
			$this->data['deliveredQty']=$deliveredQty;
			
			$queryTruckDtls = "SELECT truck_id,delv_pack FROM  do_bb_truck_details_entry WHERE do_info_image_id='$doImgInfoId'";
			$truckDtls=$this->bm->dataSelectDb1($queryTruckDtls);
			$this->data['truckDtls']=$truckDtls;
			
			//$this->load->view('DeliveryOrderBBView',$data);
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			$html=$this->load->view('DeliveryOrderBBPDF',$this->data, true); 

			$pdfFilePath ="DeliveryOrderBBPDF-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = true;

			//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
			//$stylesheet = file_get_contents('resources/styles/test.css'); 
			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}');

			//$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}

	function DeliveryOrderPDFShow($thisbl,$thisrot,$doImgInfoId)
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			$thisrot=str_replace('_','/',$thisrot);
			$msg = "";
			$data['msg']=$msg;	
			
			$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
					No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
					Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo, reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_details.Import_Rotation_No='$thisrot' AND BL_No='$thisbl' ORDER BY file_clearence_date DESC";
			$doInfo=$this->bm->dataSelectDb1($query);
			
			if(count($doInfo) == 0){
				$query = "SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
				igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,
				igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,
				igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
				igm_supplimentary_detail.Description_of_Goods,
				igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,	igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
				igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
				igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
				igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
				igm_masters.imo AS imo,reg_no,dec_code
				FROM igm_masters
				INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
				LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
				LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
				LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
				LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$thisrot' AND igm_supplimentary_detail.BL_No='$thisbl' ORDER BY file_clearence_date DESC";
				$doInfo=$this->bm->dataSelectDb1($query);
			}
			
			$this->data['doInfo']=$doInfo;
			
			$queryRemainingQty = "SELECT gross_quantity,IFNULL(SUM(delv_quantity),0) AS total_delivered,
								(gross_quantity-IFNULL(SUM(delv_quantity),0)) AS remaining
								FROM shed_mlo_do_info 
								WHERE shed_mlo_do_info.imp_rot='$thisrot' AND shed_mlo_do_info.bl_no='$thisbl'";
			$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
			$this->data['remainingQty']=$remainingQty;
			
			$queryDeliveredQty = "SELECT delv_quantity,measurement FROM shed_mlo_do_info WHERE id='$doImgInfoId'";
			$deliveredQty=$this->bm->dataSelectDb1($queryDeliveredQty);
			$this->data['deliveredQty']=$deliveredQty;
			
			$queryTruckDtls = "SELECT truck_id,delv_pack FROM  do_bb_truck_details_entry WHERE do_info_image_id='$doImgInfoId'";
			$truckDtls=$this->bm->dataSelectDb1($queryTruckDtls);
			$this->data['truckDtls']=$truckDtls;
			
			//$this->load->view('DeliveryOrderBBView',$data);
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			$html=$this->load->view('DeliveryOrderPDF',$this->data, true); 

			$pdfFilePath ="DeliveryOrderPDF-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = true;

			//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
			//$stylesheet = file_get_contents('resources/styles/test.css'); 
			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}');

			//$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}

	

	function myListSearchExecuteQuery($type,$SearchCriteria, $Searchdata) 
	{
        try {
			
			//echo $SearchCriteria;
			if($SearchCriteria=="VName")
			{
	
				$query ="
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Vessel_Name Like '%$Searchdata%' order by 1 desc";

			}

			If($SearchCriteria=="port")
			{
				$query = "
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,
				vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,
				igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,
				igms.file_clearence_date,(select org.Organization_Name from organization_profiles org 
				where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,
				igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date 
				from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_dec_type='$type' and igms.Port_of_Shipment 
				Like '%$Searchdata%' order by 1 desc";


			}
			If($SearchCriteria=="Voy")
			{
				$query ="
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Voy_No Like '%$Searchdata%' order by 1 desc";
			}
			If($SearchCriteria=="Import")
			{

				if($type=='GM' or  $type=='TS' )
				{	///separating GM and TS from BB
					$query ="Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
					igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
					left join vessels_berth_detail vas on igms.id=vas.igm_id 
					where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='GM' and igms.Import_Rotation_No Like '$Searchdata' order by 1 desc";
					
				}
				else
				{
					$query = "Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
					igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
					left join vessels_berth_detail vas on igms.id=vas.igm_id 
					where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Import_Rotation_No Like '$Searchdata' order by 1 desc";

				}
	
			}

			If($SearchCriteria=="Export")
			{
				$query = "
				Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' and igms.Export_Rotation_No Like '%$Searchdata%' order by 1 desc";
					//print($str);
	
			}
			If($SearchCriteria=="All")
			{
				$query = "Select igms.id,igms.Import_Rotation_No,igms.Export_Rotation_No,igms.Sailed_Year,igms.Sailed_Date,vas.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igms.Vessel_Id,igms.Vessel_Name,igms.Voy_No,igms.Net_Tonnage,
				igms.Name_of_Master,igms.Port_of_Shipment,igms.Port_of_Destination,igms.custom_approved,igms.file_clearence_date,igms.file_clearence_logintime,(select org.Organization_Name from organization_profiles org where org.id=igms.Submitee_Org_Id) as org_name,igms.Submitee_Org_Type as Submitee_Org_Type,igms.S_Org_License_Number as S_Org_License_Number,igms.Submission_Date as Submission_Date from igm_masters igms  
				left join vessels_berth_detail vas on igms.id=vas.igm_id 
				where igms.delivery_status=0 and igms.vsl_final_submit=1 and igms.vsl_dec_type='$type' order by 1 desc limit 500";


			}
			//echo $query;
			$data=$this->bm->dataSelectDb1($query);
			//print "SQL Query: ".$this->db->last_query();
/* 						$rslt_select_igmSupDtl=$this->bm->dataSelectDb1($sql_select_igmSupDtl);
			$data['igmSupDtl_id']=$igmSupDtl_id; */
			return $data;

        } catch (Exception $ex) {
            return FALSE;
        }
    }

	//view Vessel for Break Bulk shed
	function viewIgmGeneralForBBshed()
	{
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		//print($session_id."<hr>");
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$type_of_Igm = $this->uri->segment(3);
			$this->load->model('ci_auth', 'bm', TRUE);
		
			/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("IgmViewController/viewIgmGeneralForBBshed/$type_of_Igm");
			$config["total_rows"] = $this->bm->record_count();
			$config["per_page"] = 5;
			$config["uri_segment"] = 4;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
			/***********Pagination***************/
				
			$igmMasterList = $this->bm->myListFormBB($type_of_Igm,$config["per_page"], $page); ///ai khane error khachche
			$data['igmMasterList']=$igmMasterList;
		   
			$data['title']="View Vessel Declaration Detail($type_of_Igm)...";
			$data['type']=$type_of_Igm;
			$data["links"] = $this->pagination->create_links();
			$data['flag'] = "list";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myCNFViewIGmListHTMLForBBshed',$data);
			$this->load->view('jsAssets');
			
		}
	}

	//Search Vessel
	function myListSearchBB()
	{
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$SearchCriteria=$this->input->post('SearchCriteria');
			$Searchdata=$this->input->post('Searchdata');
			$type=$this->input->post('type');
			//echo $type."<hr>";
			
			$this->load->model('ci_auth', 'bm', TRUE);
			
			$igmMasterList = $this->myListSearchExecuteQuery($type,$SearchCriteria, $Searchdata); ///ai khane error khachche
		//	PRINT_R($igmMasterList);
			$data['igmMasterList']=$igmMasterList;
		   
			$data['title']="View Vessel Declaration Detail($type)...";
			$data['type']=$type;
			$data['flag'] = "src";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myCNFViewIGmListHTMLForBBshed',$data);
			$this->load->view('jsAssets');
			
		}
	}

	function myListFormBB()
	{
		//print_r($this->session->all_userdata());
		
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		//print($session_id."<hr>");
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$CODE = $this->uri->segment(3);
			$type_of_Igm = $this->uri->segment(4);
			$this->load->model('ci_auth', 'bm', TRUE);
		
			/*********** Pagination**************/
			
			$config = array();
			$config["base_url"] = site_url("IgmViewController/myListFormBB/$CODE/$type_of_Igm");
			$config["total_rows"] = $this->bm->record_count();
			$config["per_page"] = 5;
			$config["uri_segment"] = 5;
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		
			/***********Pagination***************/
				
			$igmMasterList = $this->bm->myListFormIGM($CODE,$type_of_Igm,$config["per_page"], $page); ///ai khane error khachche
			$data['igmMasterList']=$igmMasterList;
		   
			$data['title']="LIST OF IGM SUB DETAILS ($type_of_Igm)...";
			$data['type']=$type_of_Igm;
			$data['CODE']=$CODE;
			$data['user']=$user;
			$data['myedit'] = "";
			$data["links"] = $this->pagination->create_links();
			$this->load->view('header3');
			//$this->load->view('myCNFIGMSubListHTMLBBshed',$data);
			$this->load->view('myCNFIGMSubListHTMLBB',$data);
			//$this->load->view('myCNFViewIGmListHTML', array_merge($data, $datahome));
			$this->load->view('footer2');
			
		}
	}
	//late BL submission
    function lateBLsubmission()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="IGM BL info Submission Form (Supplementary)";
			$data['msg'] = "";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmission',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function lateBLsubmissionPerform()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="IGM BL info Submission Form (Supplementary)";
			$rotation=$this->input->post('rotation');
			$bl_no=$this->input->post('BL_No');
			 $query = "SELECT id,IGM_id,Import_Rotation_No,Line_No,BL_No,Pack_Number,Pack_Description,
				Pack_Marks_Number,Description_of_Goods,Date_of_Entry_of_Goods,weight,Bill_of_Entry_No,
				Bill_of_Entry_Date,office_code,No_of_Pack_Delivered,No_of_Pack_Discharged,Remarks,ConsigneeDesc,
				NotifyDesc,BillOfEntryPostedStatus,BillOfEntryPostedStatusDate,Delivery_Status,Delivery_Status_date,
				Submitee_Id,Submitee_Org_Type,S_Org_License_Number,Submission_Date,Submitee_Org_Id,
				Last_Update_By_id,user_action,last_update,igm_verson,delivery_discharge_org_id,delivery_discharge_date,
				RegistrationDate,Registration_Year,type_of_igm,final_submit,classified_comments,remarks_shortage_excess,
				dv_ds_marks,weight_unit,net_weight,navy_comments,net_weight_unit,mlocode,imco,un,extra_remarks,AFR,
				delivery_block_stat,int_block,technical_remarks,BE_Status,R_No,R_Date,amendment_appoved,
				auction_status,file_clearence_date,PFstatus,PFstatusdt,final_submit_date,VERSION,action_clear,
				action_clear_comments,action_clear_date,epz_code,Exporter_name,Exporter_address,Notify_code,
				Notify_name,Notify_address,Consignee_code,Consignee_name,
				Consignee_address,Volume_in_cubic_meters,Freight_value,DG_status,place_of_unloading,port_of_origin 
				FROM igm_details 
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl_no'";
			//return;
			$igm_data=$this->bm->dataSelectDb1($query);
			$data['igm_data'] = $igm_data;
			if(count($igm_data)>0)
			{
				$data['flag'] = 1;
				$data['msg'] = "";
				
			}
			else{
				$data['flag'] = 0;
				$data['msg'] = "<font color=red>This BL not found in this rotation.</font>";
			}
			
			
			
			$data['editFlag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmission',$data);
			$this->load->view('jsAssets');
		}
	}
	function lateBLsubmissionAction()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$igm_master_id=$this->input->post('igm_master_id');
			$igm_detail_id=$this->input->post('igm_detail_id');
			$Import_Rotation_No=$this->input->post('Import_Rotation_No');
			$Line_No=$this->input->post('Line_No');
			$master_Line_No=$this->input->post('master_Line_No');
			$master_BL_No=$this->input->post('master_BL_No');
			$BL_No=$this->input->post('BL_No');
			$Pack_Number=$this->input->post('Pack_Number');
			$Pack_Description=$this->input->post('Pack_Description');
	
			$Pack_Marks_Number=$this->input->post('Pack_Marks_Number');
			$Pack_Marks_Number=str_replace("'"," ",$Pack_Marks_Number);
			
			$Description_of_Goods=$this->input->post('Description_of_Goods');
			$Description_of_Goods=str_replace("'"," ",$Description_of_Goods);
			
			$Date_of_Entry_of_Goods=$this->input->post('Date_of_Entry_of_Goods');
			$weight=$this->input->post('weight');
			$Bill_of_Entry_No=$this->input->post('Bill_of_Entry_No');
			$Bill_of_Entry_Date=$this->input->post('Bill_of_Entry_Date');
			$Remarks=$this->input->post('Remarks');
			$ConsigneeDesc=$this->input->post('ConsigneeDesc');
			$NotifyDesc=$this->input->post('NotifyDesc');
			$Submitee_Id=trim($this->input->post('Submitee_Id'));					// FF AIN 
			//$Submitee_Org_Id=$this->input->post('Submitee_Org_Id');
			$Submission_Date=$this->input->post('Submission_Date');
			//$type_of_igm=$this->input->post('type_of_igm');
			$weight_unit=$this->input->post('weight_unit');
			//$AFR=$this->input->post('AFR');
			//$delivery_block_stat=$this->input->post('delivery_block_stat');
			//$int_block=$this->input->post('int_block');
			//$PFstatus=$this->input->post('PFstatus');
			//$PFstatusdt=$this->input->post('PFstatusdt');
			$Exporter_address=$this->input->post('Exporter_address');
			$Notify_code=$this->input->post('Notify_code');
			$Notify_name=$this->input->post('Notify_name');
			$Notify_address=$this->input->post('Notify_address');
			$Consignee_code=$this->input->post('Consignee_code');
			$Consignee_name=$this->input->post('Consignee_name');
			$Consignee_address=$this->input->post('Consignee_address');
			$Volume_in_cubic_meters=$this->input->post('Volume_in_cubic_meters');
			$DG_status=$this->input->post('DG_status');
			$port_of_origin=$this->input->post('port_of_origin');
			$Submitee_Org_Id="";
			$submitedBy = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$chk_st=0;
			// $chk_str="SELECT COUNT(id) AS rtnValue FROM igm_supplimentary_detail_copy WHERE Import_Rotation_No='$Import_Rotation_No' AND  BL_No='$BL_No'";
			$chk_str="SELECT COUNT(id) AS rtnValue FROM igm_supplimentary_detail WHERE Import_Rotation_No='$Import_Rotation_No' AND  BL_No='$BL_No'";
			//return;
			$chk_st=$this->bm->dataReturnDb1($chk_str);
			
			$sql_chkFFAIN = "SELECT COUNT(*) AS rtnValue
			FROM organization_profiles
			WHERE AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id'";
			$chkFFAIN = $this->bm->dataReturnDb1($sql_chkFFAIN);
			
			if($chk_st>0)
			{
				$data['msg']="<font size='4' color='red'>The BL with the rotation already exist.</font>";
			}
			else if($chkFFAIN==0 or $Submitee_Id == "" or $Submitee_Id == null)
			{
				$data['msg']="<font size='4' color='red'>FF AIN is not valid.</font>";
			}
			else
			{
				$sql_ain="SELECT id AS rtnValue
				FROM organization_profiles
				WHERE (AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id') AND Org_Type_id='4'";
				$Submitee_Org_Id=$this->bm->dataReturnDb1($sql_ain);
				
				$Submitee_Id = $Submitee_Id.'ff';
						
				// $sqlInsert="INSERT INTO igm_supplimentary_detail_copy(igm_master_id, igm_detail_id, Import_Rotation_No, Line_No, master_Line_No, master_BL_No,
				// BL_No, Pack_Number, Pack_Description, Pack_Marks_Number, Description_of_Goods, Date_of_Entry_of_Goods, weight, Bill_of_Entry_No,
				// Bill_of_Entry_Date, Remarks,ConsigneeDesc, NotifyDesc, Submitee_Id, Submitee_Org_Id, Submission_Date, weight_unit,
				// Exporter_address, Notify_code, Notify_name, Notify_address, Consignee_code,
				// Consignee_name, Consignee_address, Volume_in_cubic_meters, DG_status, port_of_origin,late_submit_flag)
				// VALUES ('$igm_master_id', '$igm_detail_id', '$Import_Rotation_No', '$Line_No', '$master_Line_No', '$master_BL_No',
				// '$BL_No', '$Pack_Number', '$Pack_Description', '$Pack_Marks_Number', '$Description_of_Goods', '$Date_of_Entry_of_Goods', '$weight', '$Bill_of_Entry_No',
				// '$Bill_of_Entry_Date', '$Remarks', '$ConsigneeDesc', '$NotifyDesc', '$Submitee_Id', '$Submitee_Org_Id', '$Submission_Date',  '$weight_unit',
				// '$Exporter_address', '$Notify_code', '$Notify_name', '$Notify_address', '$Consignee_code',
				// '$Consignee_name', '$Consignee_address', '$Volume_in_cubic_meters', '$DG_status', '$port_of_origin','1')";
				
				// $sqlInsert="INSERT INTO igm_supplimentary_detail(igm_master_id, igm_detail_id, Import_Rotation_No, Line_No, master_Line_No, master_BL_No,
				// BL_No, Pack_Number, Pack_Description, Pack_Marks_Number, Description_of_Goods, Date_of_Entry_of_Goods, weight, Bill_of_Entry_No,
				// Bill_of_Entry_Date, Remarks,ConsigneeDesc, NotifyDesc, Submitee_Id, Submitee_Org_Id, Submission_Date, weight_unit,
				// Exporter_address, Notify_code, Notify_name, Notify_address, Consignee_code,
				// Consignee_name, Consignee_address, Volume_in_cubic_meters, DG_status, port_of_origin,late_submit_flag,late_submit_by,late_submit_ip, late_submit_dt)
				// VALUES ('$igm_master_id', '$igm_detail_id', '$Import_Rotation_No', '$Line_No', '$master_Line_No', '$master_BL_No',
				// '$BL_No', '$Pack_Number', '$Pack_Description', '$Pack_Marks_Number', '$Description_of_Goods', '$Date_of_Entry_of_Goods', '$weight', '$Bill_of_Entry_No',
				// '$Bill_of_Entry_Date', '$Remarks', '$ConsigneeDesc', '$NotifyDesc', '$Submitee_Id', '$Submitee_Org_Id', '$Submission_Date',  '$weight_unit',
				// '$Exporter_address', '$Notify_code', '$Notify_name', '$Notify_address', '$Consignee_code',
				// '$Consignee_name', '$Consignee_address', '$Volume_in_cubic_meters', '$DG_status', '$port_of_origin','1','$submitedBy','$ipaddress',NOW())";
				
				// with final_submit=1
				$sqlInsert="INSERT INTO igm_supplimentary_detail(igm_master_id, igm_detail_id, Import_Rotation_No, Line_No, master_Line_No, master_BL_No,
				BL_No, Pack_Number, Pack_Description, Pack_Marks_Number, Description_of_Goods, Date_of_Entry_of_Goods, weight, Bill_of_Entry_No,
				Bill_of_Entry_Date, Remarks,ConsigneeDesc, NotifyDesc, Submitee_Id, Submitee_Org_Id, Submission_Date, weight_unit,
				Exporter_address, Notify_code, Notify_name, Notify_address, Consignee_code,
				Consignee_name, Consignee_address, Volume_in_cubic_meters, DG_status, port_of_origin,late_submit_flag,late_submit_by,late_submit_ip, late_submit_dt,final_submit)
				VALUES ('$igm_master_id', '$igm_detail_id', '$Import_Rotation_No', '$Line_No', '$master_Line_No', '$master_BL_No',
				'$BL_No', '$Pack_Number', '$Pack_Description', '$Pack_Marks_Number', '$Description_of_Goods', '$Date_of_Entry_of_Goods', '$weight', '$Bill_of_Entry_No',
				'$Bill_of_Entry_Date', '$Remarks', '$ConsigneeDesc', '$NotifyDesc', '$Submitee_Id', '$Submitee_Org_Id', '$Submission_Date',  '$weight_unit',
				'$Exporter_address', '$Notify_code', '$Notify_name', '$Notify_address', '$Consignee_code',
				'$Consignee_name', '$Consignee_address', '$Volume_in_cubic_meters', '$DG_status', '$port_of_origin','1','$submitedBy','$ipaddress',NOW(),'1')";
				
				$insertStat = $this->bm->dataInsertDB1($sqlInsert);
				if($insertStat>0)
				{
					$data['msg']="<font size='4' color='green'>Operation successfully done.</font>";
				}
				else
				{
					$data['msg']="<font size='4' color='red'>Sorry! try again later.</font>";
				}
			}
			
			$data['title']="IGM info Submission Form";
			$data['flag'] = "";
			$data['editFlag'] = 0;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmission',$data);
			$this->load->view('jsAssets');
			
		}
		
	}
	
	// late bl submission finish
	
	
	
	//late BL submission Master
	function lateBLsubmissionMaster()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="IGM BL info Submission Form (Master)";
			$data['msg'] = "";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmissionMaster',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function lateBLsubmissionMasterPerform()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			// final_submit,mlocode,file_clearence_date,PFstatus,PFstatusdt,custom_check,final_submit_date,Exporter_name,place_of_unloading

			$data['title']="IGM BL info Submission Form (Master)";
			$rotation=$this->input->post('rotation');
			$bl_no=$this->input->post('BL_No');
			 $query = "SELECT id,IGM_id,Import_Rotation_No,Line_No,BL_No,Pack_Number,Pack_Description,
				Pack_Marks_Number,Description_of_Goods,Date_of_Entry_of_Goods,weight,Bill_of_Entry_No,
				Bill_of_Entry_Date,office_code,No_of_Pack_Delivered,No_of_Pack_Discharged,Remarks,ConsigneeDesc,
				NotifyDesc,BillOfEntryPostedStatus,BillOfEntryPostedStatusDate,Delivery_Status,Delivery_Status_date,
				Submitee_Id,Submitee_Org_Type,S_Org_License_Number,Submission_Date,Submitee_Org_Id,
				Last_Update_By_id,user_action,last_update,igm_verson,delivery_discharge_org_id,delivery_discharge_date,
				RegistrationDate,Registration_Year,type_of_igm,final_submit,classified_comments,remarks_shortage_excess,
				dv_ds_marks,weight_unit,net_weight,navy_comments,net_weight_unit,mlocode,imco,un,extra_remarks,AFR,
				delivery_block_stat,int_block,technical_remarks,BE_Status,R_No,R_Date,amendment_appoved,
				auction_status,file_clearence_date,PFstatus,PFstatusdt,custom_check,final_submit_date,VERSION,action_clear,
				action_clear_comments,action_clear_date,epz_code,Exporter_name,Exporter_address,Notify_code,
				Notify_name,Notify_address,Consignee_code,Consignee_name,
				Consignee_address,Volume_in_cubic_meters,Freight_value,DG_status,place_of_unloading,port_of_origin 
				FROM igm_details 
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl_no'";
			//return;
			$igm_data=$this->bm->dataSelectDb1($query);
			$data['igm_data'] = $igm_data;
			if(count($igm_data)>0)
			{
				$data['flag'] = 1;
				$data['msg'] = "";
				
			}
			else{
				$data['flag'] = 0;
				$data['msg'] = "<font color=red>This BL not found in this rotation.</font>";
			}
			
			
			
			$data['editFlag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmissionMaster',$data);
			$this->load->view('jsAssets');
		}
	}
	
	
	function lateBLsubmissionMasterAction()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			//final_submit,mlocode,file_clearence_date, custom_check,final_submit_date,Exporter_name,place_of_unloading
			
			$igm_master_id=$this->input->post('igm_master_id');
			$igm_detail_id=$this->input->post('igm_detail_id');
			$Import_Rotation_No=$this->input->post('Import_Rotation_No');
			$Line_No=$this->input->post('Line_No');
			//$master_Line_No=$this->input->post('master_Line_No');
			$previous_BL_No=$this->input->post('previous_BL_No');
			$new_BL_No=$this->input->post('new_BL_No');
			$Pack_Number=$this->input->post('Pack_Number');
			$Pack_Description=$this->input->post('Pack_Description');
			$Pack_Marks_Number=$this->input->post('Pack_Marks_Number');
			$Description_of_Goods=$this->input->post('Description_of_Goods');
			$Date_of_Entry_of_Goods=$this->input->post('Date_of_Entry_of_Goods');
			$weight=$this->input->post('weight');
			$Bill_of_Entry_No=$this->input->post('Bill_of_Entry_No');
			$Bill_of_Entry_Date=$this->input->post('Bill_of_Entry_Date');
			$Remarks=$this->input->post('Remarks');
			$ConsigneeDesc=$this->input->post('ConsigneeDesc');
			$NotifyDesc=$this->input->post('NotifyDesc');
			$Submitee_Id=trim($this->input->post('Submitee_Id'));					// FF AIN 
			//$Submitee_Org_Id=$this->input->post('Submitee_Org_Id');
			$Submission_Date=$this->input->post('Submission_Date');
			//$type_of_igm=$this->input->post('type_of_igm');
			$weight_unit=$this->input->post('weight_unit');
			$AFR=$this->input->post('AFR');
			//$delivery_block_stat=$this->input->post('delivery_block_stat');
			//$int_block=$this->input->post('int_block');
			$final_submit_date=$this->input->post('final_submit_date');
			$place_of_unloading=$this->input->post('place_of_unloading');
			$final_submit=$this->input->post('final_submit');
			$mlocode=$this->input->post('mlocode');
			$file_clearence_date=$this->input->post('file_clearence_date');
			$custom_check=$this->input->post('custom_check');
			$PFstatus=$this->input->post('PFstatus');
			$PFstatusdt=$this->input->post('PFstatusdt');
			$Exporter_name=$this->input->post('Exporter_name');
			$Exporter_address=$this->input->post('Exporter_address');
			$Notify_code=$this->input->post('Notify_code');
			$Notify_name=$this->input->post('Notify_name');
			$Notify_address=$this->input->post('Notify_address');
			$Consignee_code=$this->input->post('Consignee_code');
			$Consignee_name=$this->input->post('Consignee_name');
			$Consignee_address=$this->input->post('Consignee_address');
			$Volume_in_cubic_meters=$this->input->post('Volume_in_cubic_meters');
			$DG_status=$this->input->post('DG_status');
			$port_of_origin=$this->input->post('port_of_origin');
			$Submitee_Org_Id="";
			$submitedBy = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$chk_st=0;
			// $chk_str="SELECT COUNT(id) AS rtnValue FROM igm_supplimentary_detail_copy WHERE Import_Rotation_No='$Import_Rotation_No' AND  BL_No='$BL_No'";
			$chk_str="SELECT COUNT(id) AS rtnValue FROM igm_details WHERE Import_Rotation_No='$Import_Rotation_No' AND  BL_No='$new_BL_No'";
			//return;
			$chk_st=$this->bm->dataReturnDb1($chk_str);
			
			$sql_chkFFAIN = "SELECT COUNT(*) AS rtnValue
			FROM organization_profiles
			WHERE AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id'";
			$chkFFAIN = $this->bm->dataReturnDb1($sql_chkFFAIN);
			
			if($chk_st>0)
			{
				$data['msg']="<font size='4' color='red'>The BL with the rotation already exist.</font>";
			}
			else if($chkFFAIN==0 or $Submitee_Id == "" or $Submitee_Id == null)
			{
				$data['msg']="<font size='4' color='red'>FF AIN is not valid.</font>";
			}
			else
			{
				$sql_ain="SELECT id AS rtnValue
				FROM organization_profiles
				WHERE (AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id') AND Org_Type_id='1'";
				$Submitee_Org_Id=$this->bm->dataReturnDb1($sql_ain);
				
				$Submitee_Id = $Submitee_Id.'m';						
				$sqlInsert="INSERT INTO igm_details(IGM_id, Import_Rotation_No, Line_No, BL_No, Pack_Number, Pack_Description, Pack_Marks_Number, Description_of_Goods, Date_of_Entry_of_Goods, weight, Bill_of_Entry_No,
				Bill_of_Entry_Date, Remarks,ConsigneeDesc, NotifyDesc, Submitee_Id, Submitee_Org_Id, Submission_Date, weight_unit, PFstatus, PFstatusdt,
				AFR, place_of_unloading, final_submit_date, mlocode, file_clearence_date, custom_check,				
				Exporter_name, Exporter_address, Notify_code, Notify_name, Notify_address, Consignee_code,
				Consignee_name, Consignee_address, Volume_in_cubic_meters, DG_status, port_of_origin, prev_BL, late_submit_flag, late_submit_by, late_submit_ip, late_submit_dt)
				VALUES ('$igm_master_id',  '$Import_Rotation_No', '$Line_No', '$new_BL_No',
				'$Pack_Number', '$Pack_Description', '$Pack_Marks_Number', '$Description_of_Goods', '$Date_of_Entry_of_Goods', '$weight', '$Bill_of_Entry_No',
				'$Bill_of_Entry_Date', '$Remarks', '$ConsigneeDesc', '$NotifyDesc', '$Submitee_Id', '$Submitee_Org_Id', '$Submission_Date',  '$weight_unit', 
				'$PFstatus', '$PFstatusdt', '$AFR', '$place_of_unloading', '$final_submit_date', '$mlocode', '$file_clearence_date','$custom_check',
				'$Exporter_name', '$Exporter_address', '$Notify_code', '$Notify_name', '$Notify_address', '$Consignee_code',
				'$Consignee_name', '$Consignee_address', '$Volume_in_cubic_meters', '$DG_status', '$port_of_origin','$previous_BL_No','1','$submitedBy','$ipaddress',NOW())";
				
				$insertStat = $this->bm->dataInsertDB1($sqlInsert);
				if($insertStat>0)
				{
					$data['msg']="<font size='4' color='green'>Operation successfully done.</font>";
				}
				else
				{
					$data['msg']="<font size='4' color='red'>Sorry! try again later.</font>";
				}
			}
			
			$data['title']="IGM Master info Submission Form";
			$data['flag'] = "";
			$data['editFlag'] = 0;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLsubmissionMaster',$data);
			$this->load->view('jsAssets');
			
		}
	}
	
	
	function lateBLContainerSubmissionMaster()
    {
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		    
            $data['title']="IGM Container Info Submission Form (Master)";
			$data['msg']="";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLContainerSubmissionMasterForm',$data);
			$this->load->view('jsAssets');
		}
    }	
	
	
/* 	function fetchContainerMasterInfo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
            $rotation=$this->input->post('rotation');
		    $prev_blNo=$this->input->post('prev_blNo');
		    $cur_blNo=$this->input->post('cur_blNo');
		    $containerNo=$this->input->post('containerNo');
			//$houseBlNo=$this->input->post('blResults');
		
			$query="SELECT * FROM igm_details_copy
			INNER JOIN igm_detail_container_copy ON igm_details_copy.id=igm_detail_container_copy.igm_detail_id
			WHERE igm_details_copy.Import_Rotation_No='$rotation' AND igm_details_copy.BL_No='$prev_blNo' 
			AND igm_detail_container_copy.cont_number='$containerNo'";
		    $result = $this->bm->dataSelectDb1($query);
			$count=count($result);
			if($count > 0)
			{
				$data['title']="IGM Container Information";
				$data['msg']="";
				$data['result']=$result;
				//$data['houseBlNo']=$houseBlNo;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('igmContainerMasterDetailForm',$data);
				$this->load->view('jsAssets');
				
					
			}
			else
			{

				$data['title']="IGM Container Info Submission Form";
				$data['msg']="<font size='4' color='red'>Wrong Rotation or BL NO  or Container NO</font>";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('igmContainerSubmissionForm',$data);
				$this->load->view('jsAssets');
			}		
		}
	}
	 */
	 
	function UpdateIGMContainerDetailInfo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			$submitedBy = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$data['msg'] ="";
			$checkCount=0;            
			
            $rotation=$this->input->post('rotation');
		    $prev_blNo=$this->input->post('blNo');
		    $cur_blNo=$this->input->post('cur_blNo');
			$transfer_type=$this->input->post('transfer_type');
			
			$preBlIGMid= "";
			$strPrebBlIgm="SELECT id FROM igm_details WHERE Import_Rotation_No='$rotation' AND BL_No='$prev_blNo'";
		   	$resPrevBlIGM=$this->bm->dataSelectDb1($strPrebBlIgm);
			for($p=0; $p<count($resPrevBlIGM); $p++)
			{
				$preBlIGMid=$resPrevBlIGM[$p]['id'];
			}
			
			$newIGMid= "";
			$strIgm="SELECT id FROM igm_details WHERE Import_Rotation_No='$rotation' AND BL_No='$cur_blNo'";
		   	$resIGM=$this->bm->dataSelectDb1($strIgm);			
			for($c=0; $c<count($resIGM); $c++)
			{
				$newIGMid=$resIGM[$c]['id'];
			}
					
					
			
			if(count($resIGM) == 0){
				$data['msg']="<font size='4' color=red>Provided new container number is invalid.</font>";
			} else if(count($resPrevBlIGM) == 0){
				$data['msg']="<font size='4' color=red>Provided new container number is invalid.</font>";
			} else {
				
				if($transfer_type == "copy"){
				
					if(!empty($_POST['list'])) {
						foreach($_POST['list'] as $containerNo)
							{
								$queryContInfo="SELECT * FROM igm_detail_container WHERE cont_number='$containerNo'";
								$resContInfo = $this->bm->dataSelectDb1($queryContInfo);
								
								$cont_size = "";
								$cont_gross_weight = "";
								$cont_weight = "";
								$cont_seal_number = "";
								$cont_status = "";
								$cont_height = "";
								$cont_iso_type = "";
								$cont_type = "";
								$cont_description = "";
								$cont_vat = "";
								$commudity_code = "";
								$Delivery_Status = "";
								$Delivery_Status_date = "";
								$Discharged_Status = "";
								$Discharged_Status_date = "";
								$login_id = "";
								$user_action = "";
								$last_update = "";
								$org_id = "";
								$off_dock_id = "";
								$Delivery_login_id = "";
								$Delivery_login_date = "";
								$Delivery_org_id = "";
								$Delivery_trailor_no = "";
								$Delivery_remarks = "";
								$Delivery_to_icd_Status = "";
								$Delivery_to_icd_Status_date = "";
								$Delivery_to_icd_login_id = "";
								$Delivery_to_icd_login_id_date = "";
								$Delivery_to_icd_org_id = "";
								$Delivery_to_icd_outward_date = "";
								$Delivery_to_icd_icd_id = "";
								$Delivery_to_icd_remarks = "";
								$cont_imo = "";
								$cont_un = "";
								$received_offdock_icd_Status_date = "";
								$received_offdock_icd_Status = "";
								$received_offdock_icd_login_id = "";
								$received_offdock_icd_login_id_date = "";
								$received_offdock_icd_org_id = "";
								$received_offdock_icd_remarks = "";
								$Discharged_login_id = "";
								$Discharged_login_date = "";
								$Discharged_org_id = "";
								$Discharged_reseal = "";
								$Discharged_remarks = "";
								$Delivery_ship_to_depot_Status = "";
								$Delivery_ship_to_depot_Status_date = "";
								$Delivery_ship_to_depot_login_id = "";
								$Delivery_ship_to_depot_login_id_date = "";
								$Delivery_ship_to_depot_org_id = "";
								$Delivery_ship_to_depot_outward_date = "";
								$Delivery_ship_to_depot_offdock_id = "";
								$Delivery_ship_to_depot_remarks = "";
								$Delivery_offdock_icd_Status_date = "";
								$Delivery_offdock_icd_Status = "";
								$Delivery_offdock_icd_login_id = "";
								$Delivery_offdock_icd_login_id_date = "";
								$Delivery_offdock_icd_org_id = "";
								$Delivery_offdock_icd_remarks = "";
								$Delivery_offdock_icd_trilor_trackno = "";
								$technical_desc = "";
								$Port_Status = "";
								$perishable = "";
								$cont_number_packaages = "";
								$cont_location_code = "";
								$late_submit_flag = "";
								$late_submit_by = "";
								$late_submit_dt = "";
								$late_submit_ip = "";
								for($cont=0; $cont<count($resContInfo); $cont++)
									{
										$cont_size=$resContInfo[$cont]['cont_size'];
										$cont_gross_weight=$resContInfo[$cont]['cont_gross_weight'];
										$cont_weight=$resContInfo[$cont]['cont_weight'];
										$cont_seal_number=$resContInfo[$cont]['cont_seal_number'];
										$cont_status=$resContInfo[$cont]['cont_status'];
										$cont_height=$resContInfo[$cont]['cont_height'];
										$cont_iso_type=$resContInfo[$cont]['cont_iso_type'];
										$cont_type=$resContInfo[$cont]['cont_type'];
										$cont_description=$resContInfo[$cont]['cont_description'];
										$cont_vat=$resContInfo[$cont]['cont_vat'];
										$commudity_code=$resContInfo[$cont]['commudity_code'];
										$Delivery_Status=$resContInfo[$cont]['Delivery_Status'];
										$Delivery_Status_date=$resContInfo[$cont]['Delivery_Status_date'];
										$Discharged_Status=$resContInfo[$cont]['Discharged_Status'];
										$Discharged_Status_date=$resContInfo[$cont]['Discharged_Status_date'];
										$login_id=$resContInfo[$cont]['login_id'];
										$user_action=$resContInfo[$cont]['user_action'];
										$last_update=$resContInfo[$cont]['last_update'];
										$org_id=$resContInfo[$cont]['org_id'];
										$off_dock_id=$resContInfo[$cont]['off_dock_id'];
										$Delivery_login_id=$resContInfo[$cont]['Delivery_login_id'];
										$Delivery_login_date=$resContInfo[$cont]['Delivery_login_date'];
										$Delivery_org_id=$resContInfo[$cont]['Delivery_org_id'];
										$Delivery_trailor_no=$resContInfo[$cont]['Delivery_trailor_no'];
										$Delivery_remarks=$resContInfo[$cont]['Delivery_remarks'];
										$Delivery_to_icd_Status=$resContInfo[$cont]['Delivery_to_icd_Status'];
										$Delivery_to_icd_Status_date=$resContInfo[$cont]['Delivery_to_icd_Status_date'];
										$Delivery_to_icd_login_id=$resContInfo[$cont]['Delivery_to_icd_login_id'];
										$Delivery_to_icd_login_id_date=$resContInfo[$cont]['Delivery_to_icd_login_id_date'];
										$Delivery_to_icd_org_id=$resContInfo[$cont]['Delivery_to_icd_org_id'];
										$Delivery_to_icd_outward_date=$resContInfo[$cont]['Delivery_to_icd_outward_date'];
										$Delivery_to_icd_icd_id=$resContInfo[$cont]['Delivery_to_icd_icd_id'];
										$Delivery_to_icd_remarks=$resContInfo[$cont]['Delivery_to_icd_remarks'];
										$cont_imo=$resContInfo[$cont]['cont_imo'];
										$cont_un=$resContInfo[$cont]['cont_un'];
										$received_offdock_icd_Status_date=$resContInfo[$cont]['received_offdock_icd_Status_date'];
										$received_offdock_icd_Status=$resContInfo[$cont]['received_offdock_icd_Status'];
										$received_offdock_icd_login_id=$resContInfo[$cont]['received_offdock_icd_login_id'];
										$received_offdock_icd_login_id_date=$resContInfo[$cont]['received_offdock_icd_login_id_date'];
										$received_offdock_icd_org_id=$resContInfo[$cont]['received_offdock_icd_org_id'];
										$received_offdock_icd_remarks=$resContInfo[$cont]['received_offdock_icd_remarks'];
										$Discharged_login_id=$resContInfo[$cont]['Discharged_login_id'];
										$Discharged_login_date=$resContInfo[$cont]['Discharged_login_date'];
										$Discharged_org_id=$resContInfo[$cont]['Discharged_org_id'];
										$Discharged_reseal=$resContInfo[$cont]['Discharged_reseal'];
										$Discharged_remarks=$resContInfo[$cont]['Discharged_remarks'];
										$Delivery_ship_to_depot_Status=$resContInfo[$cont]['Delivery_ship_to_depot_Status'];
										$Delivery_ship_to_depot_Status_date=$resContInfo[$cont]['Delivery_ship_to_depot_Status_date'];
										$Delivery_ship_to_depot_login_id=$resContInfo[$cont]['Delivery_ship_to_depot_login_id'];
										$Delivery_ship_to_depot_login_id_date=$resContInfo[$cont]['Delivery_ship_to_depot_login_id_date'];
										$Delivery_ship_to_depot_org_id=$resContInfo[$cont]['Delivery_ship_to_depot_org_id'];
										$Delivery_ship_to_depot_outward_date=$resContInfo[$cont]['Delivery_ship_to_depot_outward_date'];
										$Delivery_ship_to_depot_offdock_id=$resContInfo[$cont]['Delivery_ship_to_depot_offdock_id'];
										$Delivery_ship_to_depot_remarks=$resContInfo[$cont]['Delivery_ship_to_depot_remarks'];
										$Delivery_offdock_icd_Status_date=$resContInfo[$cont]['Delivery_offdock_icd_Status_date'];
										$Delivery_offdock_icd_Status=$resContInfo[$cont]['Delivery_offdock_icd_Status'];
										$Delivery_offdock_icd_login_id=$resContInfo[$cont]['Delivery_offdock_icd_login_id'];
										$Delivery_offdock_icd_login_id_date=$resContInfo[$cont]['Delivery_offdock_icd_login_id_date'];
										$Delivery_offdock_icd_org_id=$resContInfo[$cont]['Delivery_offdock_icd_org_id'];
										$Delivery_offdock_icd_remarks=$resContInfo[$cont]['Delivery_offdock_icd_remarks'];
										$Delivery_offdock_icd_trilor_trackno=$resContInfo[$cont]['Delivery_offdock_icd_trilor_trackno'];
										$technical_desc=$resContInfo[$cont]['technical_desc'];
										$Port_Status=$resContInfo[$cont]['Port_Status'];
										$perishable=$resContInfo[$cont]['perishable'];
										$cont_number_packaages=$resContInfo[$cont]['cont_number_packaages'];
										$cont_location_code=$resContInfo[$cont]['cont_location_code'];
										$late_submit_flag=$resContInfo[$cont]['late_submit_flag'];
										$late_submit_by=$resContInfo[$cont]['late_submit_by'];
										$late_submit_dt=$resContInfo[$cont]['late_submit_dt'];
										$late_submit_ip=$resContInfo[$cont]['late_submit_ip'];
									}
								
								
								$inserContainerDtls="INSERT INTO igm_detail_container(igm_detail_id,cont_number,cont_size,cont_gross_weight,cont_weight,
									cont_seal_number,cont_status,cont_height,cont_iso_type,cont_type,cont_description,cont_vat,commudity_code,Delivery_Status,
									Delivery_Status_date,Discharged_Status,Discharged_Status_date,login_id,user_action,last_update,org_id,off_dock_id,
									Delivery_login_id,Delivery_login_date,Delivery_org_id,Delivery_trailor_no,Delivery_remarks,Delivery_to_icd_Status,
									Delivery_to_icd_Status_date,Delivery_to_icd_login_id,Delivery_to_icd_login_id_date,Delivery_to_icd_org_id,
									Delivery_to_icd_outward_date,Delivery_to_icd_icd_id,Delivery_to_icd_remarks,cont_imo,cont_un,received_offdock_icd_Status_date,
									received_offdock_icd_Status,received_offdock_icd_login_id,received_offdock_icd_login_id_date,received_offdock_icd_org_id,
									received_offdock_icd_remarks,Discharged_login_id,Discharged_login_date,Discharged_org_id,Discharged_reseal,Discharged_remarks,
									Delivery_ship_to_depot_Status,Delivery_ship_to_depot_Status_date,Delivery_ship_to_depot_login_id,
									Delivery_ship_to_depot_login_id_date,Delivery_ship_to_depot_org_id,Delivery_ship_to_depot_outward_date,
									Delivery_ship_to_depot_offdock_id,Delivery_ship_to_depot_remarks,Delivery_offdock_icd_Status_date,
									Delivery_offdock_icd_Status,Delivery_offdock_icd_login_id,Delivery_offdock_icd_login_id_date,
									Delivery_offdock_icd_org_id,Delivery_offdock_icd_remarks,Delivery_offdock_icd_trilor_trackno,
									technical_desc,Port_Status,perishable,cont_number_packaages,
									cont_location_code,late_submit_flag,late_submit_by,late_submit_dt,late_submit_ip) 
									VALUES ('$newIGMid','$containerNo','$cont_size','$cont_gross_weight','$cont_weight','$cont_seal_number','$cont_status',
									'$cont_height','$cont_iso_type','$cont_type','$cont_description','$cont_vat','$commudity_code','$Delivery_Status',
									'$Delivery_Status_date','$Discharged_Status','$Discharged_Status_date','$login_id','$user_action','$last_update',
									'$org_id','$off_dock_id','$Delivery_login_id','$Delivery_login_date','$Delivery_org_id','$Delivery_trailor_no',
									'$Delivery_remarks','$Delivery_to_icd_Status','$Delivery_to_icd_Status_date','$Delivery_to_icd_login_id',
									'$Delivery_to_icd_login_id_date','$Delivery_to_icd_org_id','$Delivery_to_icd_outward_date','$Delivery_to_icd_icd_id',
									'$Delivery_to_icd_remarks','$cont_imo','$cont_un','$received_offdock_icd_Status_date',
									'$received_offdock_icd_Status','$received_offdock_icd_login_id','$received_offdock_icd_login_id_date',
									'$received_offdock_icd_org_id','$received_offdock_icd_remarks','$Discharged_login_id','$Discharged_login_date',
									'$Discharged_org_id','$Discharged_reseal','$Discharged_remarks','$Delivery_ship_to_depot_Status',
									'$Delivery_ship_to_depot_Status_date','$Delivery_ship_to_depot_login_id','$Delivery_ship_to_depot_login_id_date',
									'$Delivery_ship_to_depot_org_id','$Delivery_ship_to_depot_outward_date','$Delivery_ship_to_depot_offdock_id',
									'$Delivery_ship_to_depot_remarks','$Delivery_offdock_icd_Status_date','$Delivery_offdock_icd_Status',
									'$Delivery_offdock_icd_login_id','$Delivery_offdock_icd_login_id_date','$Delivery_offdock_icd_org_id',
									'$Delivery_offdock_icd_remarks','$Delivery_offdock_icd_trilor_trackno','$technical_desc','$Port_Status',
									'$perishable','$cont_number_packaages','$cont_location_code',
									'$late_submit_flag','$late_submit_by','$late_submit_dt','$late_submit_ip')";
								$insertStat=$this->bm->dataInsertDB1($inserContainerDtls);
								
								$data['msg']="<font size='4' color=blue>Transferring Completed Successfully </font>";
								
								
							}
					} else {
						$data['msg']="<font size='4' color=red>Please Select a container</font>";
					}
				
				
				} else if($transfer_type == "move"){
					
					if(!empty($_POST['list'])) {
						foreach($_POST['list'] as $containerNo)
							{
								$detail_cont_id = "";
								$queryContInfo="SELECT * FROM igm_detail_container WHERE cont_number='$containerNo' AND igm_detail_id='$preBlIGMid'";
								$resContInfo = $this->bm->dataSelectDb1($queryContInfo);
								for($e=0; $e<count($resContInfo); $e++)
									{
										$detail_cont_id=$resContInfo[$e]['id'];
									}
									
								$updateIGMstr="UPDATE igm_detail_container SET igm_detail_id='$newIGMid' WHERE id='$detail_cont_id'";
								$updt=$this->bm->dataUpdateDB1($updateIGMstr);
								$updt = 1;
								if($updt>0)
									$data['msg']="<font size='4' color=blue>Container no: $containerNo adjust with BL: $cur_blNo </font>";
								else
									$data['msg']="<font size='4' color=red>Not updated</font>";
							}
					} else {
						$data['msg']="<font size='4' color=red>Please Select a container</font>";
					}
					
				} else {
					$data['msg']="<font size='4' color=red>Please Select transfer type</font>";
				}
				
			}
			
			$data['title']="IGM Container Info Submission Form (Master)";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lateBLContainerSubmissionMasterForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	// function UpdateIGMContainerDetailInfo()
	// {
		// $session_id = $this->session->userdata('value');
		// $LoginStat = $this->session->userdata('LoginStat');	
		// if($LoginStat!="yes")
		// {
			// $this->logout();
		// }
		// else
		// {
			// $submitedBy = $this->session->userdata('login_id');
			// $ipaddress = $_SERVER['REMOTE_ADDR'];
			
			// $data['msg'] ="";
			// $checkCount=0;
            // $rotation=$this->input->post('rotation');
		    // $prev_blNo=$this->input->post('blNo');
		    // $cur_blNo=$this->input->post('cur_blNo');
		    // $containerNo=$this->input->post('containerNo');
			
			// $strIgm="SELECT id as rtnValue FROM igm_details  WHERE Import_Rotation_No='$rotation' AND BL_No='$cur_blNo'";
		   	// $newIGMid=$this->bm->dataReturnDb1($strIgm);
			
		 	// $query="SELECT igm_detail_container.id, igm_detail_container.igm_detail_id FROM igm_details
			// INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			// WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$prev_blNo' 
			// AND igm_detail_container.cont_number='$containerNo'";
		    // $igmContInfo = $this->bm->dataSelectDb1($query);
			
			// $detail_cont_id="";
			// $igm_detail_id="";
			// if(count($igmContInfo)>0)
			// {
				// for($i=0; $i<count($igmContInfo); $i++)
				// {
					// $detail_cont_id=$igmContInfo[$i]['id'];
					// $igm_detail_id=$igmContInfo[$i]['igm_detail_id'];
				// }
				
			// }
			
			// $updateIGMstr="UPDATE igm_detail_container SET igm_detail_id='$newIGMid', late_submit_flag=1, late_submit_by='$submitedBy',
							// late_submit_dt=NOW(), late_submit_ip='$ipaddress'  WHERE igm_detail_container.id='$detail_cont_id'";
			// $updt=$this->bm->dataUpdateDB1($updateIGMstr);
			// if($updt>0)
				// $data['msg']="<font size='4' color=green>Container no: $containerNo adjust with BL: $cur_blNo </font>";
			// else
				// $data['msg']="<font size='4' color=red>Not updated</font>";
			
			// $data['title']="IGM Container Info Submission Form (Master)";
			// $this->load->view('cssAssets');
			// $this->load->view('headerTop');
			// $this->load->view('sidebar');
			// $this->load->view('lateBLContainerSubmissionMasterForm',$data);
			// $this->load->view('jsAssets');
		// }

	// }
	
	
	
	
	
	//igm container info late bl start
	function igmContainerSubmissionForm()
    {
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		    
            $data['title']="IGM Container Info Submission Form (Supplementary)";
			$data['msg']="";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igmContainerSubmissionForm',$data);
			$this->load->view('jsAssets');
		}
    }
	
	function fetchContainerInfo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rotation');
		    $blNo=$this->input->post('blNo');
		    $containerNo=$this->input->post('containerNo');
			$houseBlNo=$this->input->post('blResults');			
			$submitedBy = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			
			
			if(!empty($_POST['list'])) {
				foreach($_POST['list'] as $containerNo){
					
					$queryContInfo="SELECT * FROM igm_details
					INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
					WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$blNo' 
					AND igm_detail_container.cont_number='$containerNo'";
					$resContInfo = $this->bm->dataSelectDb1($queryContInfo);
					
					$igm_detail_id = "";
					$cont_number = "";
					$cont_size = "";
					$cont_gross_weight = "";
					$cont_weight = "";
					$cont_seal_number = "";
					$cont_status = "";
					$cont_height = "";
					$cont_iso_type = "";
					$cont_type = "";
					$cont_vat = "";
					$commudity_code = "";
					$Delivery_Status = "";
					$Delivery_Status_date = "";
					$Discharged_Status = "";
					$Discharged_Status_date = "";
					$login_id = "";
					$last_update = "";
					$org_id = "";
					$off_dock_id = "";
					$cont_imo = "";
					$cont_un = "";
					$received_offdock_icd_Status = "";
					$Port_Status = "";
					$cont_number_packaages = "";
					$cont_location_code = "";
					for($cont=0; $cont<count($resContInfo); $cont++)
					{
						$igm_detail_id=$resContInfo[$cont]['igm_detail_id'];
						$cont_size=$resContInfo[$cont]['cont_size'];
						$cont_gross_weight=$resContInfo[$cont]['cont_gross_weight'];
						$cont_weight=$resContInfo[$cont]['cont_weight'];
						$cont_seal_number=$resContInfo[$cont]['cont_seal_number'];
						$cont_status=$resContInfo[$cont]['cont_status'];
						$cont_height=$resContInfo[$cont]['cont_height'];
						$cont_iso_type=$resContInfo[$cont]['cont_iso_type'];
						$cont_type=$resContInfo[$cont]['cont_type'];
						$cont_vat=$resContInfo[$cont]['cont_vat'];
						$commudity_code=$resContInfo[$cont]['commudity_code'];
						$Delivery_Status=$resContInfo[$cont]['Delivery_Status'];
						$Delivery_Status_date=$resContInfo[$cont]['Delivery_Status_date'];
						$Discharged_Status=$resContInfo[$cont]['Discharged_Status'];
						$Discharged_Status_date=$resContInfo[$cont]['Discharged_Status_date'];
						$login_id=$resContInfo[$cont]['login_id'];
						$last_update=$resContInfo[$cont]['last_update'];
						$org_id=$resContInfo[$cont]['org_id'];
						$off_dock_id=$resContInfo[$cont]['off_dock_id'];
						$cont_imo=$resContInfo[$cont]['cont_imo'];
						$cont_un=$resContInfo[$cont]['cont_un'];
						$received_offdock_icd_Status=$resContInfo[$cont]['received_offdock_icd_Status'];
						$Port_Status=$resContInfo[$cont]['Port_Status'];
						$cont_number_packaages=$resContInfo[$cont]['cont_number_packaages'];
						$cont_location_code=$resContInfo[$cont]['cont_location_code'];
					}
					
					$igmSubDetailIdQuery="SELECT * FROM igm_supplimentary_detail 
									WHERE Import_Rotation_No='$rotation' AND master_BL_No ='$blNo' AND BL_No='$houseBlNo'";
					$igmSubDetailIdResult= $this->bm->dataSelectDb1($igmSubDetailIdQuery);
					$resCount=count($igmSubDetailIdResult);
					if($resCount>0)
					{
						$igm_sup_detail_id=$igmSubDetailIdResult[0]['id'];
						$checkQuery="SELECT *  FROM igm_sup_detail_container WHERE cont_number='$containerNo' AND igm_sup_detail_id='$igm_sup_detail_id'";
						$checkResult=$this->bm->dataSelectDb1($checkQuery);
						$checkCount=count($checkResult);
					}
					
					$inserQuery="INSERT INTO igm_sup_detail_container(igm_sup_detail_id,cont_number,cont_size,cont_gross_weight,cont_weight,cont_seal_number,
						cont_status,cont_height,cont_iso_type,cont_type,cont_vat,commudity_code,Delivery_Status,Delivery_Status_date,Discharged_Status,
						Discharged_Status_date,login_id,last_update,org_id,off_dock_id,cont_imo,cont_un,port_status,
						cont_number_packaages,cont_location_code,late_submit_flag,late_submit_by,late_submit_ip,late_submit_dt)
						VALUES('$igm_sup_detail_id','$containerNo','$cont_size','$cont_gross_weight','$cont_weight','$cont_seal_number','$cont_status',
						'$cont_height','$cont_iso_type','$cont_type','$cont_vat','$commudity_code','$Delivery_Status','$Delivery_Status_date',
						'$Discharged_Status','$Discharged_Status_date','$login_id',NOW(),'$org_id','$off_dock_id','$cont_imo',
						'$cont_un','$Port_Status','$cont_number_packaages','$cont_location_code','1','$submitedBy','$ipaddress',NOW())";
					$insertStat=$this->bm->dataInsertDB1($inserQuery);
					$data['msg']="<font size='4' color='green'>Operation Has Been Successfully Done.</font>";
					
										
					
				}
			} else {
				$data['msg']="<font size='4' color='red'>Please select container</font>";
			}
			
			$data['title']="IGM Container Info Submission Form";
			
			$data['houseBlNo']=$houseBlNo;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igmContainerSubmissionForm',$data);
			$this->load->view('jsAssets');
			
			// return;
			// die();
            
		
			// $query="SELECT * FROM igm_details
			// INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			// WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$blNo' 
			// AND igm_detail_container.cont_number='$containerNo'";
		    // $result = $this->bm->dataSelectDb1($query);
			// $count=count($result);
			// if($count > 0)
			// {
				// $data['title']="IGM Container Info Submission Form";
				// $data['msg']="";
				// $data['result']=$result;
				// $data['houseBlNo']=$houseBlNo;
				// $this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				// $this->load->view('igmContainerDetailForm',$data);
				// $this->load->view('jsAssets');
					
			// }
			// else
			// {
				// $data['title']="IGM Container Info Submission Form";
				// $data['msg']="<font size='4' color='red'>Wrong Rotation or BL NO  or Container NO</font>";
				// $this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				// $this->load->view('igmContainerSubmissionForm',$data);
				// $this->load->view('jsAssets');
			// }
		
		}


	}
	
	// function fetchContainerInfo()
	// {
		// $session_id = $this->session->userdata('value');
		// $LoginStat = $this->session->userdata('LoginStat');	
		// if($LoginStat!="yes")
		// {
			// $this->logout();
		// }
		// else
		// {
			
            // $rotation=$this->input->post('rotation');
		    // $blNo=$this->input->post('blNo');
		    // $containerNo=$this->input->post('containerNo');
			// $houseBlNo=$this->input->post('blResults');
		
			// $query="SELECT * FROM igm_details
			// INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			// WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$blNo' 
			// AND igm_detail_container.cont_number='$containerNo'";
		    // $result = $this->bm->dataSelectDb1($query);
			// $count=count($result);
			// if($count > 0)
			// {
				// $data['title']="IGM Container Info Submission Form";
				
				// $data['msg']="";
				// $data['result']=$result;
				// $data['houseBlNo']=$houseBlNo;
				// $this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				// $this->load->view('igmContainerDetailForm',$data);
				// $this->load->view('jsAssets');
					
			// }
			// else
			// {
				// $data['title']="IGM Container Info Submission Form";
				// $data['msg']="<font size='4' color='red'>Wrong Rotation or BL NO  or Container NO</font>";
				// $this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				// $this->load->view('igmContainerSubmissionForm',$data);
				// $this->load->view('jsAssets');
			// }
		
		// }


	// }
	
	function SaveContainerInfo()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['msg'] ="";
			$checkCount=0;
			$igm_sup_detail_id="";
            $rotation=$this->input->post('rotation');
		    $blNo=$this->input->post('blNo');
		    $containerNo=$this->input->post('containerNo');
			$igmDetailId=$this->input->post('igmDetailId');
			$containerNumber=$this->input->post('containerNumber');
			$containerSize=$this->input->post('containerSize');
			$containerGrossWeight=$this->input->post('containerGrossWeight');
			$containerWeight=$this->input->post('containerWeight');
			$containerSealNumber=$this->input->post('containerSealNumber');
			$containerStatus=$this->input->post('containerStatus');
			$containerHeight=$this->input->post('containerHeight');
			$containerIsoType=$this->input->post('containerIsoType');
			$containerType=$this->input->post('containerType');
			$containerVat=$this->input->post('containerVat');
			$commudityCode=$this->input->post('commudityCode');
			$deliveryStatus=$this->input->post('deliveryStatus');
			$deliveryStatusDate=$this->input->post('deliveryStatusDate');
			$dischargedStatus=$this->input->post('dischargedStatus');
			$dischargedStatusDate=$this->input->post('dischargedStatusDate');
			$loginId=$this->input->post('loginId');
			$lastUpdate=$this->input->post('lastUpdate');
			$orgId=$this->input->post('orgId');
			$offDockId=$this->input->post('offDockId');
			$containerImo=$this->input->post('containerImo');
			$containerUn=$this->input->post('containerUn');
			$receivedOffDockIcdStatus=$this->input->post('receivedOffDockIcdStatus');
			$portStatus=$this->input->post('portStatus');
			$containerNumberPackages=$this->input->post('containerNumberPackages');
            $containerLocationCode=$this->input->post('containerLocationCode');
			$houseBLNo=$this->input->post('house_bl_no');
			$submitedBy = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			// $igmSubDetailIdQuery="SELECT * FROM igm_supplimentary_detail_copy WHERE Import_Rotation_No='$rotation' AND master_BL_No ='$blNo' AND BL_No='$houseBLNo'";
			$igmSubDetailIdQuery="SELECT * FROM igm_supplimentary_detail
			WHERE Import_Rotation_No='$rotation' AND master_BL_No ='$blNo' AND BL_No='$houseBLNo'";
			
			$igmSubDetailIdResult= $this->bm->dataSelectDb1($igmSubDetailIdQuery);
			$resCount=count($igmSubDetailIdResult);
			if($resCount>0)
			{
				$igm_sup_detail_id=$igmSubDetailIdResult[0]['id'];
				// $checkQuery="SELECT *  FROM igm_sup_detail_container_copy WHERE cont_number='$containerNumber' AND igm_sup_detail_id='$igm_sup_detail_id'";
				$checkQuery="SELECT *  FROM igm_sup_detail_container WHERE cont_number='$containerNumber' AND igm_sup_detail_id='$igm_sup_detail_id'";
				$checkResult=$this->bm->dataSelectDb1($checkQuery);
				$checkCount=count($checkResult);
			}
			

		    
			//$resCount=count($checkResult);
			// if($checkCount>0 ||  $igm_sup_detail_id=="")
			// {
				// if($checkCount > 0)
				// {
		        // $data['msg']="<font size='4' color='red'> This data already exists,It can not be saved.</font>";
				// }
				// else if ($igm_sup_detail_id=="")
				// {
					// $data['msg']="<font size='4' color='red'> Failed,Please complete late BL submission first.</font>";
				// }

			// }
			// else
			// {
				// $inserQuery="INSERT INTO igm_sup_detail_container_copy(igm_sup_detail_id,cont_number,cont_size,cont_gross_weight,cont_weight,cont_seal_number,
				// cont_status,cont_height,cont_iso_type,cont_type,cont_vat,commudity_code,Delivery_Status,Delivery_Status_date,Discharged_Status,
				// Discharged_Status_date,login_id,last_update,org_id,off_dock_id,cont_imo,cont_un,port_status,
				// cont_number_packaages,cont_location_code,late_submit_flag,late_submit_by,late_submit_ip, late_submit_dt)
				// VALUES('$igm_sup_detail_id','$containerNumber','$containerSize','$containerGrossWeight','$containerWeight','$containerSealNumber','$containerStatus',
				// '$containerHeight','$containerIsoType','$containerType','$containerVat','$commudityCode','$deliveryStatus','$deliveryStatusDate','$dischargedStatus',
				// '$dischargedStatusDate','$loginId',NOW(),'$orgId','$offDockId','$containerImo','$containerUn','$portStatus',
				// '$containerNumberPackages','$containerLocationCode','1','$submitedBy','$ipaddress',NOW())";
				
				$inserQuery="INSERT INTO igm_sup_detail_container(igm_sup_detail_id,cont_number,cont_size,cont_gross_weight,cont_weight,cont_seal_number,
				cont_status,cont_height,cont_iso_type,cont_type,cont_vat,commudity_code,Delivery_Status,Delivery_Status_date,Discharged_Status,
				Discharged_Status_date,login_id,last_update,org_id,off_dock_id,cont_imo,cont_un,port_status,
				cont_number_packaages,cont_location_code,late_submit_flag,late_submit_by,late_submit_ip,late_submit_dt)
				VALUES('$igm_sup_detail_id','$containerNumber','$containerSize','$containerGrossWeight','$containerWeight','$containerSealNumber','$containerStatus',
				'$containerHeight','$containerIsoType','$containerType','$containerVat','$commudityCode','$deliveryStatus','$deliveryStatusDate','$dischargedStatus',
				'$dischargedStatusDate','$loginId',NOW(),'$orgId','$offDockId','$containerImo','$containerUn','$portStatus',
				'$containerNumberPackages','$containerLocationCode','1','$submitedBy','$ipaddress',NOW())";
				$insertStat=$this->bm->dataInsertDB1($inserQuery);
				$data['msg']="<font size='4' color='green'>Operation Has Been Successfully Done.</font>";
				
			// }	

			$data['title']="IGM Container Info Submission Form (Supplementary)";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igmContainerSubmissionForm',$data);
			$this->load->view('jsAssets');

		}

	}
	//igm container info late bl finish

	function downloadBL(){
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		
			$data['title']="Check The Document...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('downloadBL',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function downloadBLPerform(){
		$txt_imp_rot1=$this->input->post('txt_imp_rot1');
		$txt_bl=$this->input->post('txt_bl');
		$data["txt_imp_rot1"] = $txt_imp_rot1;
		$data["txt_bl"] = $txt_bl;
		$this->load->view('downloadBLPerform',$data);		
	}
	
	function igmAmendmentForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		$search_by =$this->input->post("search_by");

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title'] = "IGM Amendment Form";
			$msg = "";
			 
			$data['msg'] = $msg;
	
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igm_amendment_form',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function amendment_form(){
	
		$session_id = $this->session->userdata('value');	
		$rotation=$this->input->post('rot_no');
		$bl=$this->input->post('bl_no');	
		$container=$this->input->post('cont_no');	
		
		$bl_selected =$this->input->post("bl_selected");
		$igm_type =$this->input->post("igm_type");

		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			if($bl_selected=="bl") //BL Amendment
			{				
				$data['title']="Bl Amendment Form";
				$igmType = "";
				$sql_igmDtlId="SELECT id FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
				
				$igmType="sup";
				$row=count($igmDtlId);
		
				if($row==0)
				{
					$sql_igmDtlId="SELECT id FROM igm_details WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
					$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
					$igmType="dtl";					
				}
				
				if(count($igmDtlId)==0)
				{
					$data['title'] = "IGM Amendment Form";
					$msg = "<font size='3' color='red'><strong>Invalid Combination of Rotation and BL</strong></font>";
					 
					$data['msg'] = $msg;
			
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('igm_amendment_form',$data);
					$this->load->view('jsAssets');
				}
				else
				{
					if($igmType=="dtl"){
						$sql_igmDtlInfo="SELECT id,Import_Rotation_No,BL_No,Line_No,type_of_igm,description_of_goods,Pack_Number,
						Pack_Description,Pack_Marks_Number,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,
						NotifyDesc,Consignee_code,Consignee_name,Consignee_address,ConsigneeDesc,weight,Volume_in_cubic_meters,
						port_of_origin,place_of_unloading,PFstatus,Submitee_Id,DG_status,imco,un
						FROM igm_details
						WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
					}
					else{		
						$sql_igmDtlInfo="SELECT id,Import_Rotation_No,BL_No,Line_No,type_of_igm,description_of_goods,Pack_Number,
						Pack_Description,Pack_Marks_Number,Exporter_name,Exporter_address,Notify_code,Notify_name,Notify_address,
						NotifyDesc,Consignee_code,Consignee_name,Consignee_address,ConsigneeDesc,weight,Volume_in_cubic_meters,
						port_of_origin,'' AS place_of_unloading,PFstatus,Submitee_Id,DG_status,'' as imco,'' as un
						FROM igm_supplimentary_detail
						WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";					
					}	
					$igmDtlInfo = $this->bm->dataSelectDb1($sql_igmDtlInfo);
					
					$bl_id="";
					$bl_no="";
					$line_No="";
					$type_of_igm="";
					$description_of_goods="";
					$pack_Number="";
					$pack_Description="";
					$pack_Marks_Number="";
					$exporter_name="";
					$exporter_address="";
					$notify_code="";
					$notify_name="";
					$notify_address="";
					$notifyDesc="";
					$consignee_code="";
					$Consignee_name="";
					$consignee_address="";
					$consigneeDesc="";
					$weight="";
					$volume_in_cubic_meters="";
					$port_of_origin="";
					$PFstatus="";
					$Submitee_Id="";
					$place_of_unloading="";
					$DG_status="";
					$imco="";
					$un="";
						
					for($j=0;$j<count($igmDtlInfo);$j++){					
						$bl_id= $igmDtlInfo[$j]['id'];
						$bl_no= $igmDtlInfo[$j]['BL_No'];
						$rotation= $igmDtlInfo[$j]['Import_Rotation_No'];
						$line_No= $igmDtlInfo[$j]['Line_No'];
						$type_of_igm= $igmDtlInfo[$j]['type_of_igm'];
						$description_of_goods= $igmDtlInfo[$j]['description_of_goods'];
						$pack_Number= $igmDtlInfo[$j]['Pack_Number'];
						$pack_Description= $igmDtlInfo[$j]['Pack_Description'];
						$pack_Marks_Number= $igmDtlInfo[$j]['Pack_Marks_Number'];
						$exporter_name= $igmDtlInfo[$j]['Exporter_name'];
						$exporter_address= $igmDtlInfo[$j]['Exporter_address'];
						$notify_code= $igmDtlInfo[$j]['Notify_code'];
						$notify_name= $igmDtlInfo[$j]['Notify_name'];
						$notify_address= $igmDtlInfo[$j]['Notify_address'];
						$notifyDesc= $igmDtlInfo[$j]['NotifyDesc'];
						$consignee_code= $igmDtlInfo[$j]['Consignee_code'];
						$Consignee_name= $igmDtlInfo[$j]['Consignee_name'];
						$consignee_address= $igmDtlInfo[$j]['Consignee_address'];
						$consigneeDesc= $igmDtlInfo[$j]['ConsigneeDesc'];
						$weight= $igmDtlInfo[$j]['weight'];
						$volume_in_cubic_meters= $igmDtlInfo[$j]['Volume_in_cubic_meters'];
						$port_of_origin= $igmDtlInfo[$j]['port_of_origin'];
						$place_of_unloading= $igmDtlInfo[$j]['place_of_unloading'];
						$PFstatus= $igmDtlInfo[$j]['PFstatus'];
						$Submitee_Id= $igmDtlInfo[$j]['Submitee_Id'];	
						$DG_status= $igmDtlInfo[$j]['DG_status'];	
						$imco= $igmDtlInfo[$j]['imco'];	
						$un= $igmDtlInfo[$j]['un'];	
					}
					
					$data['bl_id']=$bl_id;
					$data['bl_no']=$bl_no;
					$data['rotation']=$rotation;
					$data['line_No']=$line_No;
					$data['type_of_igm']=$type_of_igm;
					$data['description_of_goods']=$description_of_goods;
					$data['pack_Number']=$pack_Number;
					$data['pack_Description']=$pack_Description;
					$data['pack_Marks_Number']=$pack_Marks_Number;
					$data['exporter_name']=$exporter_name;
					$data['exporter_address']=$exporter_address;
					$data['notify_code']=$notify_code;
					$data['notify_name']=$notify_name;
					$data['notify_address']=$notify_address;
					$data['notifyDesc']=$notifyDesc;
					$data['consignee_code']=$consignee_code;
					$data['Consignee_name']=$Consignee_name;
					$data['consignee_address']=$consignee_address;
					$data['consigneeDesc']=$consigneeDesc;
					$data['weight']=$weight;
					$data['volume_in_cubic_meters']=$volume_in_cubic_meters;
					$data['port_of_origin']=$port_of_origin;
					$data['PFstatus']=$PFstatus;
					$data['Submitee_Id']=$Submitee_Id;
					$data['place_of_unloading']=$place_of_unloading;						
					$data['DG_status']=$DG_status;						
					$data['imco']=$imco;						
					$data['un']=$un;						
					
					$data['result']=$igmDtlInfo;
					$data['igmType']=$igmType;
					$data['bl_selected']=$bl_selected;
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('bl_amendment_form',$data);
					$this->load->view('jsAssets');
				}			
			}
			else //Container Amendment
			{
				$data['title']="Container Amendment Form";
				$igmType = "";

			 	$qr="SELECT igm_sup_detail_container.id
				FROM igm_sup_detail_container
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND cont_number='$container' AND
				igm_supplimentary_detail.BL_No='$bl'";
				$igmSubDetailId = $this->bm->dataSelectDb1($qr);
				
				$row=count($igmSubDetailId);
				
				if($row==0)
				{
					$qr="SELECT igm_detail_container.id
					FROM igm_detail_container
					INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
					WHERE igm_details.Import_Rotation_No='$rotation' AND cont_number='$container' AND igm_details.BL_No='$bl' ";
					$igmSubDetailId=$this->bm->dataSelectDB1($qr);	
					$igmType="dtl";			
				} else {
					$igmType="sup";
				}

				if(count($igmSubDetailId)==0)
				{
					$data['title'] = "IGM Amendment Form";
					$msg = "<font size='3' color='red'><strong>Invalid Combination of Rotation, BL and Container</strong></font>";
					 
					$data['msg'] = $msg;
			
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('igm_amendment_form',$data);
					$this->load->view('jsAssets');
				}				
				else
				{
					if($igmType=="dtl"){
						$sql_cont_info="SELECT igm_detail_container.id AS igm_id,igm_details.Import_Rotation_No,
										igm_details.BL_No,cont_number,cont_size,cont_status,cont_height,cont_weight,cont_iso_type,
										cont_type,cont_vat,cont_number_packaages,Cont_gross_weight,cont_seal_number,off_dock_id
										FROM igm_detail_container
										INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
										WHERE igm_details.Import_Rotation_No='$rotation' AND 
										cont_number='$container' AND igm_details.BL_No='$bl'";
					}
					else{
						$sql_cont_info="SELECT igm_sup_detail_container.id AS igm_id,igm_supplimentary_detail.Import_Rotation_No,
										igm_supplimentary_detail.BL_No,cont_number,cont_size,cont_height,cont_status,cont_weight,
										cont_iso_type,cont_type,cont_vat,cont_number_packaages,Cont_gross_weight,
										cont_seal_number,off_dock_id
										FROM igm_sup_detail_container
										INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
										WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND cont_number='$container' AND
										igm_supplimentary_detail.BL_No='$bl'";			
					}						
					$cont_info = $this->bm->dataSelectDb1($sql_cont_info);
					
					$igm_id = "";
					$rot = "";
					$cont_number = "";
					$bl_number = "";
					$cont_size = "";
					$cont_height = "";
					$cont_status = "";
					$cont_weight = "";
					$cont_iso_type = "";
					$cont_type = "";
					$cont_vat = "";
					$cont_number_packaages = "";
					$cont_gross_weight = "";
					$cont_seal_number = "";
					$off_dock_id = "";
					
					for($i=0;$i<count($cont_info);$i++){
						$igm_id = $cont_info[$i]['igm_id'];
						$rot = $cont_info[$i]['Import_Rotation_No'];
						$cont_number = $cont_info[$i]['cont_number'];
						$bl_number = $cont_info[$i]['BL_No'];
						$cont_size = $cont_info[$i]['cont_size'];
						$cont_height = $cont_info[$i]['cont_height'];
						$cont_status = $cont_info[$i]['cont_status'];
						$cont_weight = $cont_info[$i]['cont_weight'];
						$cont_iso_type = $cont_info[$i]['cont_iso_type'];
						$cont_type = $cont_info[$i]['cont_type'];
						$cont_vat = $cont_info[$i]['cont_vat'];
						$cont_number_packaages = $cont_info[$i]['cont_number_packaages'];
						$cont_gross_weight = $cont_info[$i]['Cont_gross_weight'];
						$cont_seal_number = $cont_info[$i]['cont_seal_number'];
						$off_dock_id = $cont_info[$i]['off_dock_id'];
					}
					
					$data['cont_info']=$cont_info;
					
					$data['igm_id']=$igm_id;
					$data['rot']=$rot;
					$data['cont_number']=$cont_number;
					$data['bl_number']=$bl_number;
					$data['cont_size']=$cont_size;
					$data['cont_height']=$cont_height;
					$data['cont_status']=$cont_status;
					$data['cont_weight']=$cont_weight;
					$data['cont_iso_type']=$cont_iso_type;
					$data['cont_type']=$cont_type;
					$data['cont_vat']=$cont_vat;
					$data['cont_number_packaages']=$cont_number_packaages;
					$data['cont_gross_weight']=$cont_gross_weight;
					$data['cont_seal_number']=$cont_seal_number;
					$data['off_dock_id']=$off_dock_id;
					
					$data['igmType']=$igmType;
					
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('cont_amendment_form',$data);
					$this->load->view('jsAssets');
				}	
			}			
		}
	}
	
	function bl_amendment_update_value(){
		$session_id = $this->session->userdata('value');		
		$login_id = $this->session->userdata('login_id');

		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{

			$msg="";

			$changed_fields = array();
			$change_type="BL";
			$bl_selected_value=$this->input->post("bl_selected_value");
			
			$ipaddress = $_SERVER['REMOTE_ADDR'];

			$bl_id=$this->input->post("bl_id");
			$igm_type=$this->input->post("bl_igm_type");	
			$rotation=$this->input->post('rotation');

			$bl_no=$this->input->post('BL_No');	
			$BL_No_pre_text=$this->input->post('BL_No_pre_text');	
			
			$pck_num=$this->input->post('pack_Number');	
			$Pack_Number_pre_text=$this->input->post('Pack_Number_pre_text');	

			$pck_marks_num=$this->input->post('pack_Marks_Number');	
			$Pack_Marks_Number_pre_text=$this->input->post('Pack_Marks_Number_pre_text');	

			$weight=$this->input->post('weight');	
			$weight_pre_text=$this->input->post('weight_pre_text');	

			$exp_addr=$this->input->post('exporter_address');	
			$Exporter_address_pre_text=$this->input->post('Exporter_address_pre_text');	


			$not_name=$this->input->post('notify_name');
			$Notify_name_pre_text=$this->input->post('Notify_name_pre_text');

			$cons_code=$this->input->post('consignee_code');
			$Consignee_code_pre_text=$this->input->post('Consignee_code_pre_text');

			$cons_addr=$this->input->post('consignee_address');	
			$consignee_address_pre_text=$this->input->post('consignee_address_pre_text');	


			$PFstatus=$this->input->post('PFstatus');
			$PFstatus_pre_text=$this->input->post('PFstatus_pre_text');

			$Submitee_Id=$this->input->post('submiteeId');	
			$Submitee_Id_pre_text=$this->input->post('Submitee_Id_pre_text');	

			$line_no=$this->input->post('line_No');	
			$Line_No_pre_text=$this->input->post('Line_No_pre_text');	


			$pck_desc=$this->input->post('pack_Description');	
			$pack_Description_pre_text=$this->input->post('pack_Description_pre_text');	



			$goods_desc=$this->input->post('description_goods');
			$description_goods_pre_text=$this->input->post('description_goods_pre_text');
			
			
			$exp_name=$this->input->post('exporter_name');	
			$exporter_name_pre_text=$this->input->post('exporter_name_pre_text');	



			$not_code=$this->input->post('notify_code');	
			$notify_code_pre_text=$this->input->post('notify_code_pre_text');	

			$not_addr=$this->input->post('notify_address');
			$Notify_address_pre_text=$this->input->post('Notify_address_pre_text');	
			
			$notify_desc=$this->input->post('notify_desc');
			$Notify_desc_pre_text=$this->input->post('Notify_desc_pre_text');	

			
			$cons_name=$this->input->post('consignee_name');	
			$consignee_name_pre_text=$this->input->post('consignee_name_pre_text');	

		
			$Volume_in_cubic_meters=$this->input->post('volumeInCubicMeters');	
			$volumeInCubicMeters_pre_text=$this->input->post('volumeInCubicMeters_pre_text');	


			$origine_code=$this->input->post('portOfOrigin');	
			$port_of_origin_pre_text=$this->input->post('port_of_origin_pre_text');	

			$type_of_igm=$this->input->post('type_of_igm');	
			$type_of_igm_pre_text=$this->input->post('type_of_igm_pre_text');	
			
			$dg_status = "";
			$imco = "";
			$un = "";
			
			if(isset($_POST['dg_check'])) {
				$dg_status = $this->input->post('dg_status');
			} else {
				$dg_status = null;
			}
			
			$dg_status_pre_text=$this->input->post('dg_status_pre_text');	
			
			if(isset($_POST['imco_check'])) {
				$imco=$this->input->post('imco');	
			} else {
				$imco = null;
			}
			
			$imco_pre_text=$this->input->post('imco_pre_text');	
			
			if(isset($_POST['un_check'])) {
				$un=$this->input->post('un');	
			} else {
				$un = null;
			}
				
			$un_pre_text=$this->input->post('un_pre_text');	

			$date_time = date("Y-m-d H:i:s");

			if($BL_No_pre_text != $bl_no){
				array_push($changed_fields,array("change_field"=>"BL_No","prior_value"=>$BL_No_pre_text,"new_value"=>$bl_no));
			} 

			if($Pack_Number_pre_text != $pck_num){
				array_push($changed_fields,array("change_field"=>"Pack_Info","prior_value"=>$Pack_Number_pre_text ,"new_value"=>$pck_num));
			} 

			if($Pack_Marks_Number_pre_text != $pck_marks_num){
				array_push($changed_fields,array("change_field"=>"Pack_Info","prior_value"=>$Pack_Marks_Number_pre_text ,"new_value"=>$pck_marks_num));
			} 

			if($weight_pre_text != $weight){
				array_push($changed_fields,array("change_field"=>"weight","prior_value"=>$weight_pre_text,"new_value"=>$weight));
			} 
			
			if($Exporter_address_pre_text != $exp_addr){
				array_push($changed_fields,array("change_field"=>"Exporter_address","prior_value"=>$Exporter_address_pre_text,"new_value"=>$exp_addr));
			} 

			if($Notify_name_pre_text != $not_name){
				array_push($changed_fields,array("change_field"=>"Notify_name","prior_value"=>$Notify_name_pre_text,"new_value"=>$not_name));
			} 

			if($Consignee_code_pre_text != $cons_code){
				array_push($changed_fields,array("change_field"=>"Consignee_Info","prior_value"=>$Consignee_code_pre_text,"new_value"=>$cons_code));
			}			

			if($Line_No_pre_text != $line_no){
				array_push($changed_fields,array("change_field"=>"Line_No","prior_value"=>$Line_No_pre_text,"new_value"=>$line_no));
			}	

			if($PFstatus_pre_text != $PFstatus){
				array_push($changed_fields,array("change_field"=>"PFstatus","prior_value"=>$PFstatus_pre_text,"new_value"=>$PFstatus));
			}
			
			if($Submitee_Id_pre_text != $Submitee_Id){
				array_push($changed_fields,array("change_field"=>"Submitee_Id","prior_value"=>$Submitee_Id_pre_text,"new_value"=>$Submitee_Id));
			}			

			if($place_of_unloading_pre_text != $unload_code){
				array_push($changed_fields,array("change_field"=>"place_of_unloading","prior_value"=>$place_of_unloading_pre_text,"new_value"=>$unload_code));
			}

			if($pack_Description_pre_text != $pck_desc){
				array_push($changed_fields,array("change_field"=>"Pack_Description","prior_value"=>$pack_Description_pre_text,"new_value"=>$pck_desc));
			}

			if($description_goods_pre_text!= $goods_desc){
				array_push($changed_fields,array("change_field"=>"description_of_goods","prior_value"=>$description_goods_pre_text,"new_value"=>$goods_desc));
			}		

			if($exporter_name_pre_text != $exp_name){
				array_push($changed_fields,array("change_field"=>"Exporter_name","prior_value"=>$exporter_name_pre_text,"new_value"=>$exp_name));
			}		

			if($notify_code_pre_text != $not_code){
				array_push($changed_fields,array("change_field"=>"Notify_code","prior_value"=>$notify_code_pre_text,"new_value"=>$not_code));
			}
			
			if($Notify_address_pre_text != $not_addr){
				array_push($changed_fields,array("change_field"=>"Notify_address","prior_value"=>$Notify_address_pre_text,"new_value"=>$not_addr));
			}
			
			if($Notify_desc_pre_text != $notify_desc){
				array_push($changed_fields,array("change_field"=>"NotifyDesc","prior_value"=>$Notify_desc_pre_text,"new_value"=>$notify_desc));
			}			
		
			if($consignee_name_pre_text != $cons_name){
				array_push($changed_fields,array("change_field"=>"Consignee_Info","prior_value"=>$consignee_name_pre_text,"new_value"=>$cons_name));
			}
			
			if($volumeInCubicMeters_pre_text != $Volume_in_cubic_meters){
				array_push($changed_fields,array("change_field"=>"Volume_in_cubic_meters","prior_value"=>$volumeInCubicMeters_pre_text,"new_value"=>$Volume_in_cubic_meters));
			}		

			if($port_of_origin_pre_text != $origine_code){
				array_push($changed_fields,array("change_field"=>"port_of_origin","prior_value"=>$port_of_origin_pre_text,"new_value"=>$origine_code));
			}

			if($type_of_igm_pre_text != $type_of_igm){
				array_push($changed_fields,array("change_field"=>"type_of_igm","prior_value"=>$type_of_igm_pre_text,"new_value"=>$type_of_igm));
			}

			if($consignee_address_pre_text != $cons_addr){
				array_push($changed_fields,array("change_field"=>"Consignee_Info","prior_value"=>$consignee_address_pre_text,"new_value"=>$cons_addr));
			}
			
			if($dg_status_pre_text != $dg_status){
				array_push($changed_fields,array("change_field"=>"DG_status","prior_value"=>$dg_status_pre_text,"new_value"=>$dg_status));
			}
			
			if($imco_pre_text != $imco){
				array_push($changed_fields,array("change_field"=>"imco","prior_value"=>$imco_pre_text,"new_value"=>$imco));
			}
			
			if($un_pre_text != $un){
				array_push($changed_fields,array("change_field"=>"un","prior_value"=>$un_pre_text,"new_value"=>$un));
			}
			
			for($c=0;$c<count($changed_fields);$c++)
			{
				$change_field = $changed_fields[$c]['change_field'];
				$prior_value = $changed_fields[$c]['prior_value'];
				$new_value = $changed_fields[$c]['new_value'];
				
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,
				entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','$change_field','$prior_value','$new_value','$login_id','$date_time','$ipaddress')";	
				$logFlag_1 = $this->bm->dataInsertDB1($sql);
			}
			
			if($igm_type=="dtl")
			{				
				$updateIgmQry="update igm_details set Line_No='$line_no',BL_No='$bl_no',Pack_Number='$pck_num',
					Pack_Description='$pck_desc',Pack_Marks_Number='$pck_marks_num',Description_of_Goods='$goods_desc',
					weight='$weight',Exporter_name='$exp_name',Exporter_address='$exp_addr',Notify_code='$not_code',
					Notify_name='$not_name',Notify_address='$not_addr',NotifyDesc='$notify_desc',Consignee_code='$cons_code',
					Consignee_name='$cons_name',Consignee_address='$cons_addr',port_of_origin='$origine_code',
					Volume_in_cubic_meters='$Volume_in_cubic_meters',PFstatus='$PFstatus',Submitee_Id=CONCAT('$Submitee_Id','M'),
					type_of_igm='$type_of_igm',DG_status='$dg_status',imco='$imco',un='$un'
					where id='$bl_id'";		
			}
			else
			{		
				$updateIgmQry="update igm_supplimentary_detail set Line_No='$line_no',BL_No='$bl_no',Pack_Number='$pck_num',
					Pack_Description='$pck_desc',Pack_Marks_Number='$pck_marks_num',Description_of_Goods='$goods_desc',
					weight='$weight',Exporter_name='$exp_name',Exporter_address='$exp_addr',Notify_code='$not_code',Notify_name='$not_name',
					Notify_address='$not_addr',NotifyDesc='$notify_desc',Consignee_code='$cons_code',Consignee_name='$cons_name',
					Consignee_address='$cons_addr',port_of_origin='$origine_code',Volume_in_cubic_meters='$Volume_in_cubic_meters',
					PFstatus='$PFstatus',Submitee_Id=CONCAT('$Submitee_Id','FF'),type_of_igm='$type_of_igm',DG_status='$dg_status'
					where id='$bl_id'";							
			}
			
			$flag = $this->bm->dataUpdateDB1($updateIgmQry);
			if($flag==1){
				$msg = "<font color='blue'><strong>BL Amendment successful.</strong></font>";
				
				$Submitee_Org_Id = "";
				if($igm_type=="sup")
				{
					$sql_ain="SELECT id AS rtnValue FROM organization_profiles
							WHERE (AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id') AND Org_Type_id='4'";
					$Submitee_Org_Id=$this->bm->dataReturnDb1($sql_ain);
				}
				else
				{
					$sql_ain="SELECT id AS rtnValue FROM organization_profiles
							WHERE (AIN_No='$Submitee_Id' OR AIN_No_New='$Submitee_Id') AND Org_Type_id='1'";
					$Submitee_Org_Id=$this->bm->dataReturnDb1($sql_ain);
				}			
				
				$rotation_number = "";
				$bl_number = "";
				if($igm_type=="sup")
					{
						$sql_rot_bl = "SELECT Import_Rotation_No,BL_No FROM igm_supplimentary_detail WHERE id='$bl_id'";
						$rslt_rot_bl = $this->bm->dataSelectDB1($sql_rot_bl);
						for($i=0; $i<count($rslt_rot_bl); $i++){	   
							$rotation_number=$rslt_rot_bl[$i]['Import_Rotation_No'];
							$bl_number=$rslt_rot_bl[$i]['BL_No'];
						}
					}
				else 
					{
						$sql_rot_bl = "SELECT Import_Rotation_No,BL_No FROM igm_details WHERE id='$bl_id'";
						$rslt_rot_bl = $this->bm->dataSelectDB1($sql_rot_bl);
						for($i=0; $i<count($rslt_rot_bl); $i++){	   
							$rotation_number=$rslt_rot_bl[$i]['Import_Rotation_No'];
							$bl_number=$rslt_rot_bl[$i]['BL_No'];
						}
					}
				
				$sql_edo_application_id = "SELECT id FROM edo_application_by_cf WHERE rotation='$rotation_number' AND bl='$bl_number'";
				$rslt_edo_application_id = $this->bm->dataSelectDB1($sql_edo_application_id);
				
				if(count($rslt_edo_application_id)>0){
					if($igm_type=="sup")
					{
						$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET ff_org_id='$Submitee_Org_Id' 
												WHERE edo_application_by_cf.rotation='$rotation_number' AND bl='$bl_number'";
						$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
					}
					else
					{
						$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET mlo='$Submitee_Org_Id' 
												WHERE edo_application_by_cf.rotation='$rotation_number' AND bl='$bl_number'";
						$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
					}
					
				}
			}
			else{
				$msg = "<font color='red'><strong>Try again please.</strong></font>";
			}
			
			
			$data['title'] = "IGM Amendment Form";
			$data['msg'] = $msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igm_amendment_form',$data);
			$this->load->view('jsAssets');			
		}	
	}
}
?>