<?php

class CfsModule extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->model('ci_auth', 'bm', TRUE);
			$this->load->library("pagination");
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
	function index(){
		 $this->lclAssignment();
    }
	
//Sumon Roy--Start
	
	function logout(){ 
		$query="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
			LEFT(sparcsn4.argo_carrier_visit.phase,2) AS phase_num,SUBSTR(sparcsn4.argo_carrier_visit.phase,3) AS phase_str,sparcsn4.argo_visit_details.eta,
			sparcsn4.argo_visit_details.etd,sparcsn4.argo_carrier_visit.ata,
			sparcsn4.argo_carrier_visit.atd,sparcsn4.ref_bizunit_scoped.id AS agent,sparcsn4.argo_quay.id AS berth,
			IFNULL(sparcsn4.vsl_vessel_visit_details.flex_string02,sparcsn4.vsl_vessel_visit_details.flex_string03) AS berthop
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
			LEFT JOIN sparcsn4.vsl_vessel_berthings ON sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			LEFT JOIN sparcsn4.argo_quay ON sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
			WHERE sparcsn4.argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
			ORDER BY sparcsn4.argo_carrier_visit.phase";
			//echo $data['voysNo'];
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;
			
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
		
		
	function lclAssignment()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
		    $strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			
			$data['lclAssignmentList']=$lclAssignmentList;    
			$editFlag = 0;
			$dateFlag = 0;
			$data['msg']="";
			$data['editFlag']=$editFlag;
			$data['dateFlag']=$dateFlag;
			$data['title']="LCL ASSIGNMENT ENTRY FORM...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryForm',$data);
			$this->load->view('jsAssets');
		}	
    }
	  
	  
	  
	function lclAssignmentPerform()
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
		$ipaddr = $_SERVER['REMOTE_ADDR'];				
			if($this->input->post('update'))
			{
				$lclID=$this->input->post('id');
		
				$expectDate=$this->input->post('expectDate');
				$stv=$this->input->post('stv');
				$contAtShed=$this->input->post('contAtShed');
				$cargoAtShed=$this->input->post('cargoAtShed');
				$decOfCargo=$this->input->post('decOfCargo');
				$remarks=$this->input->post('remarks');
				$igmDetailContId=$this->input->post('igmDetailContId');
				$igmDetailId=$this->input->post('igmDetailId');
								
				$strInsertEq = "UPDATE lcl_assignment_detail SET igm_cont_detail_id ='$igmDetailContId',igm_detail_id = '$igmDetailId',
				assignment_date='$expectDate',cont_loc_shed ='$contAtShed',cargo_loc_shed='$cargoAtShed',description_cargo='$decOfCargo',
				remarks='$remarks',last_update=now(),update_by='$login_id',user_ip='$ipaddr',unstuff_flag='0',berthOp='$stv'
				WHERE lcl_assignment_detail.id=$lclID";

				$stat = $this->bm->dataInsertDB1($strInsertEq);
						
				if($stat==1)
				{
					$data['msg']="LCL Assignment Updated successfully ";
					$dateFlag=1;
					$data['dateFlag']=$dateFlag;
					$exptDate=$this->input->post('expectDate');
					$data['exptDate']=$exptDate;						
				}
				else
					$data['msg']="Not Updated.";
									
				$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0";
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				
				$data['lclAssignmentList']=$lclAssignmentList;    
			
				$data['title']="LCL ASSIGNMENT ENTRY FORM...";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentEntryForm',$data);
				$this->load->view('jsAssets');
			}								
			else
			{
				$cont=$this->input->post('contNo');
				$expectDate=$this->input->post('expectDate');
				$stv=$this->input->post('stv');
				$contAtShed=$this->input->post('contAtShed');
				$cargoAtShed=$this->input->post('cargoAtShed');
				$decOfCargo=$this->input->post('decOfCargo');
				$remarks=$this->input->post('remarks');
				$igmDetailContId=$this->input->post('igmDetailContId');
				$igmDetailId=$this->input->post('igmDetailId');
				$landingTime=$this->input->post('landingTime');
									
				$strContSearch="select count(*) as rtnValue from lcl_assignment_detail
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id
				where igm_detail_container.cont_number='$cont' and unstuff_flag=0";				
			
				$contSearch= $this->bm->dataReturnDb1($strContSearch);
		
				if($contSearch!=0)
				{
					$data['msg']="Container No: ".$cont." already assigned!";
				
					$dateFlag=1;
					$data['dateFlag']=$dateFlag;
					$exptDate=$this->input->post('expectDate');
					$data['exptDate']=$exptDate;
					
					$shedFlag=1;
						
					$contShed=$this->input->post('contAtShed');
					$cargoShed=$this->input->post('cargoAtShed');
						
					$data['contShed']=$contShed;
					$data['cargoShed']=$cargoShed;
					$data['shedFlag']=$shedFlag;						
						
					$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
					igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
					igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
					lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time,if(assignment_date<=date(now()),1,0) as st
					from lcl_assignment_detail
					inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
					inner join igm_masters on igm_masters.id=igm_details.IGM_id 
					inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and cont_loc_shed='$contShed' order by assignment_date";
					$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
					
					$data['lclAssignmentList']=$lclAssignmentList;
				
					$data['title']="LCL ASSIGNMENT ENTRY FORM...";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('lclAssignmentEntryForm',$data);
					$this->load->view('jsAssets');
				}
				else
				{
					$strInsertEq = "insert into lcl_assignment_detail(igm_cont_detail_id,igm_detail_id,assignment_date,cont_loc_shed,cargo_loc_shed,description_cargo,remarks,last_update,update_by,user_ip,unstuff_flag,berthOp,landing_time)
					values($igmDetailContId,$igmDetailId, '$expectDate', '$contAtShed', '$cargoAtShed', '$decOfCargo','$remarks', now(), '$login_id','$ipaddr','0','$stv','$landingTime')";
				
					$stat = $this->bm->dataInsertDB1($strInsertEq);
			
					$shedFlag=0;		
					if($stat==1)
					{
						$data['msg']="LCL Assignment Saved successfully  ";
						$dateFlag=1;
						$data['dateFlag']=$dateFlag;
						$exptDate=$this->input->post('expectDate');
						$data['exptDate']=$exptDate;
						
						$shedFlag=1;
							
						$contShed=$this->input->post('contAtShed');
						$cargoShed=$this->input->post('cargoAtShed');
							
						$data['contShed']=$contShed;
						$data['cargoShed']=$cargoShed;
						$data['shedFlag']=$shedFlag;	
						
					}
					else
						$data['msg']="Not Saved.";
							
					$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
					igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
					igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
					lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, if(assignment_date<=date(now()),1,0) as st from lcl_assignment_detail
					inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
					inner join igm_masters on igm_masters.id=igm_details.IGM_id 
					inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and cont_loc_shed='$contShed' order by assignment_date";
					$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			
					$data['lclAssignmentList']=$lclAssignmentList;    

					$data['title']="LCL ASSIGNMENT ENTRY FORM...";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('lclAssignmentEntryForm',$data);
					$this->load->view('jsAssets');				
				}
			}
		}
	}
	 
	 
	 //syncLclAssignment________________________________
	 
	function syncLclAssignment()
	{
		$strAssignID="select lcl_assignment_detail.igm_cont_detail_id,igm_detail_container.cont_number
		from lcl_assignment_detail
		inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id
		inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id
		where unstuff_flag=0";
		$lclAssignmentIDList = $this->bm->dataSelectDb1($strAssignID);
		for($i=0;$i<count($lclAssignmentIDList);$i++) 
		{ 
			$dtlContId=$lclAssignmentIDList[$i]['igm_cont_detail_id'];
			$cont_number=$lclAssignmentIDList[$i]['cont_number'];

			$strId = "SELECT 
			(SELECT COUNT(*) FROM srv_event WHERE srv_event.applied_to_gkey=inv_unit.gkey AND event_type_gkey=30) AS rtnValue
			FROM inv_unit WHERE id='$cont_number' AND  category='IMPRT' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
			
			$rtnValue = $this->bm->dataReturn($strId);
			
			if($rtnValue>0)
			{
				$strID = "update lcl_assignment_detail SET unstuff_flag=1 where igm_cont_detail_id=$dtlContId";
				$flagUpdate = $this->bm->dataInsertDB1($strID);					
			}
		}
		
		$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
		igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
		igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
		lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
		inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
		inner join igm_masters on igm_masters.id=igm_details.IGM_id 
		inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0";
		$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);

		$data['lclAssignmentList']=$lclAssignmentList;
		$data['title']="LCL ASSIGNMENT ENTRY FORM...";

		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('lclAssignmentEntryForm',$data);
		$this->load->view('jsAssets');
	}
	
	 function lclAssignmentReportView()
		{  
		
		$login_id = $this->session->userdata('login_id');	
			//load mPDF library
		$this->load->library('M_pdf');
		$data['title']="LCL ASSIGNMENT REPORT";
		/*
		$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time
			from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id
			inner join igm_masters on igm_masters.id=igm_details.IGM_id
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id
			where unstuff_flag=0 order by cont_loc_shed";*/
		$strSelect="select distinct cont_loc_shed from lcl_assignment_detail where unstuff_flag=0 order by cont_loc_shed";
		$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
		
		//$data['lclAssignmentList']=$lclAssignmentList; 
		$data['login_id']=$login_id;
		
		$this->data['lclAssignmentList']= $lclAssignmentList;
		$html=$this->load->view('lclAssignmentReportView',$this->data,true);


			//this the the PDF filename that user will get to download
		$pdfFilePath ="mypdfName-".time()."-download.pdf";


			//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		//$pdf->autoPageBreak = true;
			//$pdf->SetHeader('|Date: {DATE j-m-Y}|');

			//$pdf->SetHeader($url . "\n\n" . 'Date {DATE j-m-Y}');
			// $pdf->setFooter('|Page {PAGENO}|');
			//generate the PDF!
			//$stylesheet = file_get_contents('pdf.css'); // external css
			//$pdf->WriteHTML($stylesheet,1);
		$stylesheet = file_get_contents('assets/stylesheets/lcl.css'); 
		$pdf->useSubstitutions = true; 
		$pdf->WriteHTML($stylesheet,1);	
		$pdf->WriteHTML($html,2);
			//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		$pdf->Output($pdfFilePath, "I");   //--------pdf view show
			//$pdf->Output($pdfFilePath, "D");  //-------pdf download					
	}
		
  
	function lclAssignmentEdit()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			if($this->input->post('lclID'))
			{
				$lclID=$this->input->post('lclID');
			}
			else
			{
				$lclID=$this->uri->segment(3);
			}

			$strSelect="select lcl_assignment_detail.id, igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and lcl_assignment_detail.id=$lclID";
			$lclAssignmentEditList = $this->bm->dataSelectDb1($strSelect);

			$data['lclAssignmentEditList']=$lclAssignmentEditList;    
			$editFlag = 1;
			$data['editFlag']=$editFlag;

			$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			$data['lclAssignmentList']=$lclAssignmentList; 

			$data['title']="LCL ASSIGNMENT ENTRY FORM...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryForm',$data);
			$this->load->view('jsAssets');
		}
	}
	 

    
			
   

	
	function lclAssignmentCancel()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		
		else
		{
			if($this->input->post('lclID'))
				{
					$lclID=$this->input->post('lclID');
				}
			else
				{
					$lclID=$this->uri->segment(3);
				}
			//echo "ID ".$lclID;
			$strSelect="DELETE FROM lcl_assignment_detail WHERE lcl_assignment_detail.id=$lclID";
			$stat = $this->bm->dataDeleteDB1($strSelect);
			
			if($stat==1)
			{
				$data['msg']="LCL Assignment Deleted";
			}
			else
				$data['msg']="Not delete.";
			
			
			$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			$data['lclAssignmentList']=$lclAssignmentList; 
			
			
			$data['title']="LCL ASSIGNMENT ENTRY FORM...";
			$this->load->view('header5');
			$this->load->view('lclAssignmentEntryForm',$data);
			$this->load->view('footer_1');
		}
		
	}
	
	
	
	//LCL Assignment Report Table____________________________________________
	
	function lclAssignmentReportTable()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		
		$section = $this->session->userdata('section');
		$login_id = $this->session->userdata('login_id');	
		$data['login_id']=$login_id;
		
		     $strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id 
			where unstuff_flag=0 and cargo_loc_shed='$section'";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			$data['lclAssignmentList']=$lclAssignmentList; 
			
			
			$data['title']="LCL ASSIGNMENT REPORT....";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentReportTableView',$data);
			$this->load->view('jsAssets');
	}
	
	
	function lclAssignmentReportTablePerform()
	{
		$login_id = $this->session->userdata('login_id');	
		$data['login_id']=$login_id;
		$search_by = $this->input->post('search_by');
	  
		if(isset($_POST['View'])) 
		{
			
			if($search_by=="rotation")
			{
				$rot=$this->input->post('search_value');
				$data['title']="LCL ASSIGNMENT REPORT FOR THE ROTATION  ".$rot;
				$data['rot']=$rot;
		 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 and igm_details.Import_Rotation_No='$rot' AND lcl_assignment_detail.sl!='0' ORDER BY cont_loc_shed ASC";
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 
	
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('jsAssets');
			}
			else if($search_by=="container")
			{
				$cont=$this->input->post('search_value');
				$data['title']="LCL ASSIGNMENT REPORT FOR THE CONTAINER ".$cont;
				$todate=$this->input->post('todate');
		 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND cont_number='$cont' AND lcl_assignment_detail.sl!='0' ORDER BY cont_loc_shed ASC";
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 
			
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('jsAssets');
			}	 
			else if($search_by=="shedNo")	 
			{
				$shedNo=$this->input->post('shedNo');
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;
		 
				$data['title']="LCL ASSIGNMENT REPORT FOR THE SHED NO: ".$shedNo." AND DATE BETWEEN ".$fromdate." AND ".$todate;
				//$todate=$this->input->post('todate');time
		 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND
				cont_loc_shed='$shedNo' AND lcl_assignment_detail.sl!='0' AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";
				
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('jsAssets');		
			}			
			else if($search_by=="dateRange")
			{
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;
				$data['title']="LCL ASSIGNMENT REPORT BETWEEN ".$fromdate." AND ".$todate;	
		 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND lcl_assignment_detail.sl!='0' AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('jsAssets');
			}		
			else  
			{
				
				$data['title']=" LCL ASSIGNMENT REPORT ";
		 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND lcl_assignment_detail.sl!='0' 
				ORDER BY cont_loc_shed ASC";
				$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('jsAssets');
				
						   
			}
		}
		elseif (isset($_POST['Print'])) 
		{
			$this->load->library('m_pdf');
			
			if($search_by=="rotation")
			{
				$rot=$this->input->post('search_value');
				$title="LCL ASSIGNMENT REPORT FOR THE ROTATION  ".$rot;
				$data['rot']=$rot;
				 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, 
				lcl_assignment_detail.last_update AS lst_updt, DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 and igm_details.Import_Rotation_No='$rot' AND lcl_assignment_detail.sl!='0' ORDER BY cont_loc_shed ASC ";
				// $lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				// $data['lclAssignmentList']=$lclAssignmentList; 

				// $this->load->view('header5');
				// $this->load->view('lclAssignmentReportTableView',$data);
				// $this->load->view('footer_1');
			}
			else if($search_by=="container")
			{
				$cont=$this->input->post('search_value');
				$title="LCL ASSIGNMENT REPORT FOR THE CONTAINER ".$cont;
				$todate=$this->input->post('todate');
				 
				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND cont_number='$cont' AND lcl_assignment_detail.sl!='0' ORDER BY cont_loc_shed ASC";
				/* $lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
				$data['lclAssignmentList']=$lclAssignmentList; 

				$this->load->view('header5');
				$this->load->view('lclAssignmentReportTableView',$data);
				$this->load->view('footer_1'); */
			}			
			else if($search_by=="shedNo")	 
			{
				$shedNo=$this->input->post('shedNo');
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;

				$title="LCL ASSIGNMENT REPORT FOR THE SHED NO: ".$shedNo." AND DATE BETWEEN ".$fromdate." AND ".$todate;
				//$todate=$this->input->post('todate');

				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time,
				lcl_assignment_detail.last_update AS lst_updt, DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND cont_loc_shed='$shedNo' AND lcl_assignment_detail.sl!='0' AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";
			}
			else if($search_by=="dateRange")
			{
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;
				$title="LCL ASSIGNMENT REPORT BETWEEN ".$fromdate." AND ".$todate;	
				$this->data['title']=$title;

				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, 
				lcl_assignment_detail.last_update AS lst_updt, DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND lcl_assignment_detail.sl!='0' AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";
			}				
			else  
			{
				$title="LCL ASSIGNMENT REPORT";

				$strSelect="select lcl_assignment_detail.id,lcl_assignment_detail.sl,igm_detail_container.cont_number,
				igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
				igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
				lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, 
				lcl_assignment_detail.last_update AS lst_updt, DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as time 
				from lcl_assignment_detail
				inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
				inner join igm_masters on igm_masters.id=igm_details.IGM_id 
				inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag!=2 AND lcl_assignment_detail.sl!='0' ORDER BY cont_loc_shed ASC";				
			}
			
			$this->data['title']=$title;
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);

			$data['login_id']=$login_id;

			$this->data['lclAssignmentList']= $lclAssignmentList;
			$html=$this->load->view('lclAssignmentReportViewbySearch',$this->data,true);

			$pdfFilePath ="lclAssignmentReport-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			$stylesheet = file_get_contents('resources/styles/lcl.css'); 
			$pdf->useSubstitutions = true; 
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);

			$pdf->Output($pdfFilePath, "I");   //--------pdf view show
		}
		else if(isset($_POST['Excel'])) 		
		{
			$strSelect = "SELECT lcl_assignment_detail.id,
			lcl_assignment_detail.sl,
			igm_detail_container.cont_number,
			igm_detail_container.cont_size,
			igm_detail_container.cont_height,
			igm_details.Import_Rotation_No,
			Vessel_Name,
			assignment_date,
			igm_details.mlocode,
			berthOp as stv,
			cont_loc_shed,
			cargo_loc_shed,
			description_cargo,
			lcl_assignment_detail.remarks,
			date(lcl_assignment_detail.landing_time)as landing_time 
			FROM lcl_assignment_detail
			INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
			INNER JOIN igm_detail_container ON igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id
			WHERE unstuff_flag!=2 
			AND lcl_assignment_detail.sl!='0'";
			
			if($search_by=="rotation")
			{
				$rot=$this->input->post('search_value');
				$data['title']="LCL ASSIGNMENT REPORT FOR THE ROTATION  ".$rot;
				$data['rot']=$rot;
		 
				$strSelect=$strSelect." AND igm_details.Import_Rotation_No='$rot' ORDER BY cont_loc_shed ASC";			
			}
			else if($search_by=="container")
			{
				$cont=$this->input->post('search_value');
				$data['title']="LCL ASSIGNMENT REPORT FOR THE CONTAINER ".$cont;
				$todate=$this->input->post('todate');
		 
				$strSelect=$strSelect." AND cont_number='$cont' ORDER BY cont_loc_shed ASC";			
			}
			else if($search_by=="shedNo")	 
			{
				$shedNo=$this->input->post('shedNo');
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;
		 
				$data['title']="LCL ASSIGNMENT REPORT FOR THE SHED NO: ".$shedNo." AND DATE BETWEEN ".$fromdate." AND ".$todate;				
				
				$strSelect=$strSelect." AND cont_loc_shed='$shedNo' AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";	
			}			
			else if($search_by=="dateRange")
			{
				$fromdate=$this->input->post('fromdate');
				$todate=$this->input->post('todate');
				$data['fromdate']=$fromdate;
				$data['todate']=$todate;
				$data['title']="LCL ASSIGNMENT REPORT BETWEEN ".$fromdate." AND ".$todate;	
		 
				$strSelect=$strSelect." AND assignment_date BETWEEN '$fromdate' AND '$todate' ORDER BY cont_loc_shed ASC";					
			}				
			else  
			{
				$data['title']=" LCL ASSIGNMENT REPORT ";
		 
				$strSelect=$strSelect." ORDER BY cont_loc_shed ASC";
			}
			// echo $strSelect;return;
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			$data['lclAssignmentList']=$lclAssignmentList; 	
			$this->load->view('lclAssignmentReportTableViewExcel',$data);
		}		// Excel end			    
	}
	
	//Sumon Roy--End-----------------------------------------------
	
	//Sourav Organizational Profile Start 
	function org_creation_form()	
	{		
		$session_id = $this->session->userdata('value');		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")		
		{			
			$this->logout();		
		}		
		else		
		{						
			$sql_org_type="SELECT id,Org_Type FROM tbl_org_types ORDER BY Org_Type ASC";
			$orgList=$this->bm->dataSelectDb1($sql_org_type);
			$data['orgList']=$orgList;
			
			$data['title']="ORGANIZATION PROFILE FORM";			
			$msg="";	
			$data['editFlag']=0;			
			$data['orgDetailList']="";
			$this->load->view('header2');			
			$this->load->view('organization_creation_form',$data);			
			$this->load->view('footer_1');			
		}	
	}
	
	function orgProfileList()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$sql_row_num="select count(*) as rtnValue from organization_profiles";
			
			//echo $sql_row_num;
			$segment_three = $this->uri->segment(3);
			
			$config = array();
			$config["base_url"] = site_url("cfsModule/orgProfileList/$segment_three");
			$config["total_rows"] = $this->bm->dataReturnDb1($sql_row_num);
			$config["per_page"] = 20;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			$limit=$config["per_page"];
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$start=$page;
			
			if($this->input->post('delete'))
			{
				$lclID=$this->input->post('lclID');
				$deleteSql="DELETE FROM organization_profiles WHERE id='$lclID'";
				$deleteStat=$this->bm->dataInsertDB1($deleteSql);
			}
			
		    // $strSelect="SELECT organization_profiles.id AS profileId,organization_profiles.Org_Type_id,Org_Type,Organization_Name,AIN_No_New,License_No,Agent_Code
			// FROM organization_profiles
			// INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id order by organization_profiles.id desc limit $start,$limit";
			
			$strSelect="SELECT organization_profiles.id AS profileId,organization_profiles.Org_Type_id,Org_Type,Organization_Name,AIN_No_New,License_No,Agent_Code
			FROM organization_profiles
			INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id order by organization_profiles.id desc";

			//echo $strSelect;
			//return;
			$profileList = $this->bm->dataSelectDb1($strSelect);
			
			$strOrgType="SELECT * FROM tbl_org_types";

			//echo $strSelect;
			//return;
			$org_type_list = $this->bm->dataSelectDb1($strOrgType);
			
			$strOrgName="SELECT distinct Organization_Name FROM organization_profiles";
			$org_name_list = $this->bm->dataSelectDb1($strOrgName);
			
			$data['profileList']=$profileList;    
			$data['org_type_list']=$org_type_list;    
			$data['org_name_list']=$org_name_list;    
			$data['start']=$start;
			$data['links'] = $this->pagination->create_links();
			
			$data['title']="Organization Profile List...";
						
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('orgProfileList',$data);
			$this->load->view('jsAssetsList');
		}	
	}
	
	function searchOrgProfile()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$searchBy=$this->input->post('search_by');
			$cond="";
			if($searchBy=="org_type")
			{
				$orgTypeId=$this->input->post('org_type');
				$cond=" where tbl_org_types.id=".$orgTypeId;
			}
			else if($searchBy=="org_name")
			{
				$orgName=$this->input->post('org_name');
				$cond=" where Organization_Name= '".$orgName."'";
			}
			else if($searchBy=="lic_no")
			{
				$lic=$this->input->post('lic_no');
				$cond=" where License_No='".$lic."'";
			}
			else if($searchBy=="aiin_no")
			{
				$lic=$this->input->post('aiin_no');
				$cond=" where AIN_No_New='".$lic."'";
			}
			else
			{
				$cond="";
			}

			
			$sql_row_num="select count(*) as rtnValue from organization_profiles";
			
			//echo $sql_row_num;
			$segment_three = $this->uri->segment(3);
			
			$config = array();
			$config["base_url"] = site_url("cfsModule/orgProfileList/$segment_three");
			$config["total_rows"] = $this->bm->dataReturnDb1($sql_row_num);
			$config["per_page"] = 20;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			$limit=$config["per_page"];
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$start=$page;
			
		    $strSelect="SELECT organization_profiles.id AS profileId,organization_profiles.Org_Type_id,Org_Type,Organization_Name,AIN_No_New,License_No,Agent_Code 
			FROM organization_profiles
			INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id".$cond. 
			" limit $start,$limit";

			//echo $strSelect;
			//return;
			$profileList = $this->bm->dataSelectDb1($strSelect);
			
			$strOrgType="SELECT * FROM tbl_org_types";

			//echo $strSelect;
			//return;
			$org_type_list = $this->bm->dataSelectDb1($strOrgType);
			
			$strOrgName="SELECT distinct Organization_Name FROM organization_profiles";
			$org_name_list = $this->bm->dataSelectDb1($strOrgName);
			
			$data['profileList']=$profileList;    
			$data['org_type_list']=$org_type_list;    
			$data['org_name_list']=$org_name_list; 
			$data['start']=$start;
			$data['links'] = $this->pagination->create_links();
			
			$data['title']="Organization Profile List...";
			$this->load->view('header5');
			$this->load->view('orgProfileList',$data);
			$this->load->view('footer_1');
		}	
	}
	
	function editOrgProfile()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
				$this->logout();
		}
		else
		{
			$lclID=$this->input->post('lclID');
			
			$selectSql="SELECT organization_profiles.id AS profileId,organization_profiles.Org_Type_id,Org_Type,Organization_Name,AIN_No_New,License_No,Agent_Code,
			License_issue_Date,Licence_Validity_Date,Address_1,Address_2,Address_3,Telephone_No_Land,Cell_No_1,Cell_No_2,Fax_No,email,URL
			FROM organization_profiles
			INNER JOIN tbl_org_types ON organization_profiles.Org_Type_id=tbl_org_types.id
			where organization_profiles.id='$lclID'";
            //echo $selectSql;
            //return;
			$orgDetailList=$this->bm->dataSelectDb1($selectSql);
			$data['orgDetailList']=$orgDetailList;
			
			$sql_org_type="SELECT id,Org_Type FROM tbl_org_types ORDER BY Org_Type ASC";
			$orgList=$this->bm->dataSelectDb1($sql_org_type);
			$data['orgList']=$orgList;
			
			$data['editFlag']=1;

			$data['title']="ORGANIZATION PROFILE FORM";	
			$data['msg']="";

			$this->load->view('header2');
			$this->load->view('organization_creation_form',$data);
			$this->load->view('footer');
		}
	}

	function org_creation_action()
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
				$this->logout();
		}
		else
		{
			$org_prof_id=$this->input->post('org_prof_id');		//hidden
			
			$orgType=$this->input->post('org_type');
			$sql_org_type_id="SELECT id as rtnValue FROM tbl_org_types WHERE Org_Type='$orgType'";

			$org_Type_id=$this->bm->dataReturnDb1($sql_org_type_id);
			//echo "hh : ".$org_Type_id;
			//return;
			
			$org_name=$this->input->post('org_name');
			$ain_no=$this->input->post('ain_no');
			
			$license_no=$this->input->post('license_no');
			$license_issue_dt=$this->input->post('license_issue_dt');
			$license_validity_dt=$this->input->post('license_validity_dt');
			
			$address_1=$this->input->post('address_1');
			$address_2=$this->input->post('address_2');
			$address_3=$this->input->post('address_3');
			
			$land_phone=$this->input->post('land_phone');
			$cell_phone_1=$this->input->post('cell_phone_1');
			$cell_phone_2=$this->input->post('cell_phone_2');
			
			$fax_no=$this->input->post('fax_no');
			$email=$this->input->post('email');
			$url=$this->input->post('url');
			
			$agent_code=$this->input->post('agent_code');
			
			$login_id = $this->session->userdata('login_id');
			
			if($org_prof_id>0)
			{
				$sql_org_create="UPDATE organization_profiles
				 SET Org_Type_id='$org_Type_id',`Organization_Name`='$org_name',`License_No`='$license_no',
				 AIN_No='$ain_no',AIN_No_New='$ain_no',
				`License_issue_Date`='$license_issue_dt',
				`Licence_Validity_Date`='$license_validity_dt',`Address_1`='$address_1',
				`Address_2`='$address_2',`Address_3`='$address_3',`Telephone_No_Land`='$land_phone',
				`Cell_No_1`='$cell_phone_1',`Cell_No_2`='$cell_phone_2',`Fax_No`='$fax_no',`email`='$email',
				`URL`='$url',`Last_Update_By_id`='$login_id',`last_update`=NOW(),`Agent_Code`='$agent_code'
				 WHERE id=$org_prof_id";
			}
			else
			{				
				$sql_org_create="INSERT INTO organization_profiles(`Org_Type_id`,`Organization_Name`,`AIN_No`,`AIN_No_New`,`License_No`,`License_issue_Date`,`Licence_Validity_Date`,`Address_1`,`Address_2`,`Address_3`,`Telephone_No_Land`,`Cell_No_1`,`Cell_No_2`,`Fax_No`,`email`,`URL`,`Last_Update_By_id`,`last_update`,`Agent_Code`)
				VALUES('$org_Type_id','$org_name','$ain_no','$ain_no','$license_no','$license_issue_dt','$license_validity_dt','$address_1','$address_2','$address_3','$land_phone','$cell_phone_1','$cell_phone_2','$fax_no','$email','$url','$login_id',NOW(),'$agent_code')";
			}
			
			//$rslt_org_create=1;
			$rslt_org_create=$this->bm->dataInsertDB1($sql_org_create);
			
			//echo $sql_org_create;
			//return;
			
			if($rslt_org_create==1)
				$msg="<font color='green'><b>Organization profile created</b></font>";
			else
				$msg="<font color='red'><b>Failed</b></font>";
			
			$sql_org_type="SELECT id,Org_Type FROM tbl_org_types ORDER BY Org_Type ASC";
			$orgList=$this->bm->dataSelectDb1($sql_org_type);
			$data['orgList']=$orgList;
			
			$data['editFlag']=0;

			$data['title']="ORGANIZATION PROFILE FORM";	
			$data['msg']=$msg;
			
			$this->load->view('header2');
			$this->load->view('organization_creation_form',$data);
			$this->load->view('footer_1');	
			
			//echo "HELLO : ".$orgType;
			//return;
		}
	}
	//Sourav Organizational Profile End
	
	//LCL Assignment Entry Form (New)
	
	
	
	
	
	function lclAssignmentEntryForm_modified()
	{
		//print_r($this->session->all_userdata());
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
						
			$strAssignID="select lcl_assignment_detail.igm_cont_detail_id,igm_detail_container.cont_number
			from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id
			where unstuff_flag=0";
			$lclAssignmentIDList = $this->bm->dataSelectDb1($strAssignID);
			for($i=0;$i<count($lclAssignmentIDList);$i++) { 
				$dtlContId=$lclAssignmentIDList[$i]['igm_cont_detail_id'];
				$cont_number=$lclAssignmentIDList[$i]['cont_number'];
				
			
				
				$strId="SELECT (SELECT COUNT(*) FROM srv_event WHERE srv_event.applied_to_gkey=inv_unit.gkey AND event_type_gkey=30) AS rtnValue
				FROM inv_unit WHERE id='$cont_number' AND  category='IMPRT' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
				$rtnValue = $this->bm->dataReturn($strId);
				
				if($rtnValue>0)
				{
					$strID = "update lcl_assignment_detail SET unstuff_flag=1 where igm_cont_detail_id=$dtlContId";
					$flagUpdate = $this->bm->dataInsertDB1($strID);					
				}
			}
			
			$data['msg']="";
			
			//$data['lclAssignmentList']=$lclAssignmentList;    
			$editFlag = 0;
			$data['editFlag']=$editFlag;
			$data['title']="LCL ASSIGNMENT ENTRY FORM...";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryForm_modified',$data);
			$this->load->view('jsAssetsList');
		}	
	}

	
	
	function lclAssignmentPerform_new()
	{
		//print_r($this->session->all_userdata());
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$stat=0;
			//return ;
			$row=$this->input->post('rowNum');
			/* echo $row;
			return; */
			for($i=0; $i< $row; $i++)
			{
				/* echo "---";
				return; */
				//slNo1 contNo1 vesselName1 rotNo1 contSize1 contHeight1 mlo1 stv1 contAtShed1 cargoAtShed1 decOfCargo1 remarks1
				//$row=$this->input->post('slNo');
				
				$expectDate=$this->input->post('expectDate');
				
				$slNo=$this->input->post('slNo'.$i);
				$contNo=$this->input->post('contNo'.$i);
				$vesselName=$this->input->post('vesselName'.$i);
				$contSize=$this->input->post('contSize'.$i);
				$contHeight=$this->input->post('contHeight'.$i);
				$mlo=$this->input->post('mlo'.$i);
				$stv=$this->input->post('stv'.$i);
				$contAtShed=$this->input->post('contAtShed'.$i);
				$cargoAtShed=$this->input->post('cargoAtShed'.$i);
				$decOfCargo=$this->input->post('decOfCargo'.$i);
				$remarks=$this->input->post('remarks'.$i);
				$igm_dtl_id=$this->input->post('igm_dtl_id'.$i);
				$igm_cont_dtl_id=$this->input->post('igm_cont_dtl_id'.$i);
				$landingTime=$this->input->post('landingTime'.$i);
				$lcl_id=$this->input->post('lcl_id'.$i);
				/* echo $igm_cont_dtl_id;
				return; */
				if($lcl_id!=null )
				{
					 $strUpdate = "UPDATE lcl_assignment_detail SET unstuff_flag='2' WHERE lcl_assignment_detail.id='$lcl_id'";					
					$stat = $this->bm->dataUpdateDB1($strUpdate);
					//echo  $strUpdate;          

				}
				if ($contNo!=null)
				{
					 $strCheck="Select count(*) as rtnValue from lcl_assignment_detail where igm_cont_detail_id='$igm_cont_dtl_id' 
					and cont_loc_shed='$contAtShed' and assignment_date='$expectDate'";
					$chkStat= $this->bm->dataReturnDb1($strCheck);

				  if($chkStat==0)	
				  {
					   $strInsertEq = "insert into lcl_assignment_detail (igm_cont_detail_id,igm_detail_id,assignment_date, sl, cont_loc_shed,cargo_loc_shed,description_cargo,remarks,
					  last_update,update_by,user_ip,unstuff_flag,berthOp,landing_time)
					  values('$igm_cont_dtl_id', '$igm_dtl_id', '$expectDate', '$slNo', '$contAtShed', '$cargoAtShed', '$decOfCargo','$remarks', now(), 
					  '$login_id', '$ipaddr','0','$stv','$landingTime')";
					 // return;
						//echo $strInsertEq ;
					  $stat = $stat+ $this->bm->dataInsertDB1($strInsertEq);					
				  }
				 /*  else{
					  $strUpdate="Update lcl_assignment_detail set assignment_date='$expectDate', sl='$slNo', cont_loc_shed='$contAtShed',
					  cargo_loc_shed='$cargoAtShed', description_cargo='$decOfCargo',remarks='$remarks' WHERE "; 
					  
					} */
				}
			}
			//return;
			if($stat>0)
				{
				$data['msg']="<font size=3 color=green>Assignment successfully done.</font>";
				}
			else
				{
				$data['msg']="<font size=3 color=red>Assignment not done.</font>";
				}
			$editFlag = 0;
			$data['editFlag']=$editFlag;
			$data['title']="LCL ASSIGNMENT ENTRY FORM...";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryForm_modified',$data);
			$this->load->view('jsAssetsList');
		}	
	}
	
	function lclAssignmentEntryEditForm()
	{
			
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['edit']=0;    
			$data['title']="LCL ASSIGNMENT LIST";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryEditForm',$data);
			$this->load->view('jsAssetsList');
		}	
	}
		
	function lclAssignmentEntryEditList()
	{
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$dt=$this->input->post('date');
			
			$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,sl,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, if(assignment_date<=date(now()),1,0) as st from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and 
			assignment_date='$dt' order by sl";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			
			$data['lclAssignmentList']=$lclAssignmentList; 
			$data['dt']=$dt; 
			$data['edit']=1;    
			$data['title']="LCL ASSIGNMENT LIST";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryEditForm',$data);
			$this->load->view('jsAssetsList');
		}	
		
	}
		
	function lclAssignmentEntryEditListAction()
	{
			
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$tbl_lenth=$this->input->post('tbl_lenth');
			$dt=$this->input->post('date');
			/*  echo $dt;
				return; */
			
			for($i=0; $i<$tbl_lenth; $i++)
			{
				$icl_id=$this->input->post('icl_id'.$i);
				$sl=$this->input->post('sl'.$i);
				if($sl==0)
				{
					$strDelete="DELETE from lcl_assignment_detail where lcl_assignment_detail.id='$icl_id'";
					$del_stat = $this->bm->dataDeleteDB1($strDelete);
				}
				else
				{
					$strUpdate="update lcl_assignment_detail set sl='$sl' where lcl_assignment_detail.id='$icl_id'";						
					$update_stat = $this->bm->dataUpdateDB1($strUpdate);
				}				
			}
			//return;
			
			$strSelect="select lcl_assignment_detail.id,igm_detail_container.cont_number,sl,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time, if(assignment_date<=date(now()),1,0) as st from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and 
			assignment_date='$dt' order by sl";
			$lclAssignmentList = $this->bm->dataSelectDb1($strSelect);
			
			$data['lclAssignmentList']=$lclAssignmentList; 
			$data['dt']=$dt; 
			$data['edit']=1;    
			$data['title']="LCL ASSIGNMENT LIST";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclAssignmentEntryEditForm',$data);
			$this->load->view('jsAssetsList');
		}	
		
	}

		//syncLclAssignment________________________________
	
}

?>