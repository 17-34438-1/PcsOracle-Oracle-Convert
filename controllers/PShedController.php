<?php

class PShedController extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->model('ci_auth', 'bm', TRUE);
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
	
	// Chemical Shed Tally Entry Starts--------------
	function pShedTallyEntryWithIgmInfoForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION(Chemical Shed)";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('pShedTallyEntryForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function logout()
	{
	
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

	function pShedTallyEntryFormWithIgmContInfo()
	{	
			
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$org_Type_id = $this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');
			if($this->input->post('rotation') && $this->input->post('cont'))
			{
				$rotation=strtoupper(trim($this->input->post('rotation')));
				$cont=trim($this->input->post('cont'));
				
				$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
				FROM lcl_assignment_detail
				INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotation' AND cont_number='$cont'";
				
				// $cntrslt=$this->bm->dataReturnDb1($cntquery);
				$cntrslt=1;//assign 1 for temporary to skip assignment 
				
				
				if($cntrslt==0)
				{
					$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
					$data['msg']="Please provide assignment for the container";
					$data['assigned']=0;
					$data['rotation']=$rotation;
					$data['cont']=$cont;
					$data['login_id']=$login_id;
					$this->load->view('pShedTallyEntryFormView',$data);
					
					//return;
					//exchange_btn_status;
				} 
			}
			else
			{
				$cont=$this->uri->segment(3);
				$rot_year=$this->uri->segment(4);
				$rot_no=$this->uri->segment(5);
				$rotation=$rot_year.'/'.$rot_no;
				
				$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
				FROM lcl_assignment_detail
				INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotation' AND cont_number='$cont'";
				
				$cntrslt=$this->bm->dataReturnDb1($cntquery);
				
				if($cntrslt==0)
				{
					$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
					$data['msg']="Please provide assignment for the container";
					$data['assigned']=0;
					$data['rotation']=$rotation;
					$data['cont']=$cont;
					$data['login_id']=$login_id;
					$this->load->view('pShedTallyEntryFormView',$data);
					
					//return;
				}
			}
			
			$chkExistShedTallyQry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rotation' and cont_number='$cont'";
			$rtnExistShedTally = $this->bm->dataSelectDb1($chkExistShedTallyQry);
			$cntExist = $rtnExistShedTally[0]['id'];								
			
			$tbl = "sup_detail";
		
			//Cont_gross_weight and cont_seal_number added
			if($cntExist<1)
			{
				$sqlContainer="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_sup_detail_container.cont_imo 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
				WHERE Import_Rotation_No='$rotation' AND cont_number='$cont'
				ORDER BY 2";
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "sup_detail";
				}
				
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
				WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_sup_detail_container.cont_number='$cont'";
			}
			else
			{
				$sqlContainerCheck="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
				FROM igm_supplimentary_detail 
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE Import_Rotation_No='$rotation' AND cont_number='$cont'
				ORDER BY 2";				

				$rtnContainerListCheck = $this->bm->dataSelectDb1($sqlContainerCheck);
				$cntCheck = count($rtnContainerListCheck);
				
				if($cntCheck==0)
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rotation' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					) tbl2
					UNION
					 SELECT * FROM (SELECT shed_tally_info.igm_sup_detail_id AS id, import_rotation AS Import_Rotation_No, BL_No, shed_tally_info.cont_number, cont_size, Cont_gross_weight, cont_seal_number, Pack_Description, Pack_Marks_Number, Pack_Number, ConsigneeDesc, NotifyDesc, igm_sup_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					 WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont'
					 AND  shed_tally_info.igm_sup_detail_id IS NOT NULL ) tbl3";
					//"AND BL_NO IS NOT NULL" (removed condition from above query for showing data of blank BL)-------------------
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_detail_container.cont_number='$cont'";
					
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "detail";
					}	
						

					/* $sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_detail_container.cont_number='$cont'"; */
				}
				else
				{
					$sqlContainer="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
					cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,
					igm_sup_detail_container.cont_imo
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
					WHERE Import_Rotation_No='$rotation' AND cont_number='$cont'
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
					shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
					NotifyDesc,igm_sup_detail_container.cont_imo FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$cont' and BL_NO is null
					)tbl2";
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "sup_detail";
					}	
					
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
					LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
					WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_sup_detail_container.cont_number='$cont'";
				}										
			}
			//echo $sqlContainer;
			
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$cnt = count($rtnContainerList);				
									
			//Cont_gross_weight and cont_seal_number added
			if($cnt==0)
			{
				$tbl = "detail";
				if($cntExist<1)
				{
					$sqlContainer = "select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					LEFT join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rotation' and cont_number='$cont'
					order by 2";
				}
				else
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rotation' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					)tbl2";
				}
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "detail";
				}
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_details 
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
				WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_detail_container.cont_number='$cont'";
			}
			
			//echo $sqlContainer;
			//return;
			
			// query for devonestop - remove later
			$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rotation' and cont_number='$cont' and exchange_done_status=1";
			
			if($org_Type_id==59)			//CPA Shed Users
			{
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rotation' AND cont_number='$cont' AND cpa_exchange_done_status=1";
			}
			else if($org_Type_id==30)		// Berth Operator
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rotation' AND cont_number='$cont' AND berth_exchange_done_status=1";
			}
			else if($org_Type_id==4)		// FF
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rotation' AND cont_number='$cont' AND ff_exchange_done_status=1";
			}
			
			$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
			$chkVal= $chkList[0]['chkVal'];
			if($chkVal>0)
			{
				//$data['update_btn_status']=0;
				$data['view_btn_status']=1;  //previously 0; 1 for exchange done; alter if necessary
				$data['save_btn_status']=0;
				$data['exchange_btn_status']=0;
				$data['msgExchange']="Exchange Done";
			}
			else
			{
				// query for devonestop - remove later
				$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rotation' and cont_number='$cont' and exchange_done_status=0";
				
				if($org_Type_id==59)			//CPA Shed Users
				{
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rotation' AND cont_number='$cont' AND cpa_exchange_done_status=0";
				}
				else if($org_Type_id==30)		// Berth Operator
				{					
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rotation' AND cont_number='$cont' AND berth_exchange_done_status=0";
				}
				else if($org_Type_id==4)		// FF
				{					
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rotation' AND cont_number='$cont' AND ff_exchange_done_status=0";
				}
		
				$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
				$chkVal= $chkList[0]['chkVal'];
				if($chkVal>0)
				{
					//$data['update_btn_status']=1;
					$data['view_btn_status']=1;
					$data['exchange_btn_status']=1;
					$data['save_btn_status']=1;
				}
				else
				{
					//$data['update_btn_status']=0;
					$data['view_btn_status']=0;
					$data['exchange_btn_status']=0;
					$data['save_btn_status']=1;
				}
			}
			$login_id = $this->session->userdata('login_id');
														
			$rslt_vesselname_seal=$this->bm->dataSelectDb1($sql_vesselname_seal);
			
			$data['rslt_vesselname_seal']=$rslt_vesselname_seal;
			
			$data['assigned']=1;		
			$data['rotation']=$rotation;
			$data['tbl']=$tbl;
			$data['cont']=$cont;
			$data['stat']="";
			$data['viewType']="search";
			$data['login_id']=$login_id;
			
			
			$data['rtnContainerList']=$rtnContainerList; //"$rtnContainerList" returns selected data for table
			$this->load->view('pShedTallyEntryFormView',$data);
		}
	}
	
	function pShedSaveTallyRcv()
		{	
			
			$imdg=$this->input->post('imdgClass');	
			$weight=$this->input->post('weight');
			$storageArea=$this->input->post('storageArea');	
			$totalPck=$this->input->post('totalPck');
			$rcv_unit=$this->input->post('rcv_unit');
			$actualmarks=$this->input->post('actualmarks');
			$remark=$this->input->post('remark');			
			$dtlId=$this->input->post('dtlId');
			$rot=$this->input->post('rot');
			$cont=$this->input->post('cont');
			$tbl=$this->input->post('tbl');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$berth_op=$this->input->post('berth_op');
			$phy_tally_no=$this->input->post('phy_tally_no');	
			$phy_tally=$this->input->post('phy_tally');
			if($phy_tally_no!="")
			{
				$physical_tally_sheet_no = $phy_tally_no;
			}
			else
			{
				$physical_tally_sheet_no = $phy_tally;
			}
			//echo $physical_tally_sheet_no;
			//return;
			
			$login_id = $this->session->userdata('login_id');
			//$login_id = "devpshed";
			$org_Type_id = $this->session->userdata('org_Type_id');
			//$section = $this->session->userdata('section');
			$shed_name = $this->input->post('shed_name');
			$date = date('dmy');
			$dtlDate = date('my');
			$igmDetailQuery="select MAX(id) as rtnValue from shed_tally_info";	
		
			$igmDetailId = $this->bm->dataReturnDb1($igmDetailQuery);
			$igmDetailId_no = $igmDetailId+1;
			$igmDetailNumber=$dtlDate."00".$igmDetailId_no;
			 
			$strChkTallySheetEntry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
			$rtnExistTallySheet = $this->bm->dataSelectDb1($strChkTallySheetEntry);
			$tallyExist = $rtnExistTallySheet[0]['id'];
			if($tallyExist>0)
			{
				$strGetTallyInformation= "select distinct tally_sheet_no,wr_date,tally_sheet_number from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
				$rtnGetTallyInformation = $this->bm->dataSelectDb1($strGetTallyInformation);
				
				$tallySheetNo=$rtnGetTallyInformation[0]['tally_sheet_no'];
				$wrDate=$rtnGetTallyInformation[0]['wr_date'];
				$tallySheetNumber=$rtnGetTallyInformation[0]['tally_sheet_number'];
				if($tbl=="sup_detail")
				{
					if($dtlId=="" || $dtlId==0)
						{
							
							$str = "insert into shed_tally_info(igm_sup_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,last_update,
							tally_sheet_no,actual_marks,total_pack,wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,
							physical_tally_sheet_no,shed_yard)
							values('$igmDetailNumber','$rot','$cont','$login_id','$ipaddr','$remark',now(),'$tallySheetNo','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit', '$berth_op',
							'$physical_tally_sheet_no','$shed_name')";
						}
					else
						{
							
							$str = "insert into shed_tally_info(igm_sup_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,
							last_update,tally_sheet_no,actual_marks,total_pack,wr_date,
							shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,physical_tally_sheet_no,shed_yard)
							values('$dtlId','$rot','$cont','$login_id','$ipaddr','$remark',now(),'$tallySheetNo','$actualmarks',
							$totalPck,'$wrDate','$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit', '$berth_op',
							'$physical_tally_sheet_no','$shed_name')";
						}
				}
				else
				{
					if($dtlId=="" || $dtlId==0)
						{
							//$maxtally_sheet_no = "";
							$str = "insert into shed_tally_info(igm_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,last_update,tally_sheet_no,actual_marks,total_pack,wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,
							physical_tally_sheet_no,shed_yard)
							values('$igmDetailNumber','$rot','$cont','$login_id','$ipaddr','$remark',now(),
							'$tallySheetNo','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber'
							,'$imdg','$weight','$rcv_unit', '$berth_op','$physical_tally_sheet_no','$shed_name')";
						}
					else
						{
							$str = "insert into shed_tally_info(igm_detail_id,import_rotation,cont_number,update_by,ip_addr,
								remarks,last_update,tally_sheet_no,actual_marks,total_pack,wr_date,
								shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,physical_tally_sheet_no,shed_yard)
								values('$dtlId','$rot','$cont','$login_id','$ipaddr','$remark',now(),
								'$tallySheetNo','$actualmarks',$totalPck,'$wrDate',
								'$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit','$berth_op', '$physical_tally_sheet_no',
								'$shed_name')";
						}
				}
				//echo $str;
				$stat = $this->bm->dataInsertDB1($str);
				if($stat==1)
				{					
					$data['stat']="<font color='red'><b>Sucessfully inserted</b></font>";
					$data['view_btn_status']=1;
					$data['exchange_btn_status']=1;
					$data['save_btn_status']=1;
				}
			}
			else
			{
				// echo "NEW DATA";	
				$tally_sheet_noQuery="select MAX(tally_sheet_no) as rtnValue from shed_tally_info";
				$tally_sheet_no = $this->bm->dataReturnDb1($tally_sheet_noQuery);
				
				$maxtally_sheet_no = $tally_sheet_no+1;
				//$igmDetailId_no = $igmDetailId+1;
				
				$size=strlen($maxtally_sheet_no);
				
				if($size==1)
				{
				//	 $tallySheetNumber="TSN"."-".$date."000".$maxtally_sheet_no;
					 $tallySheetNumber=$storageArea."-".$date."000".$maxtally_sheet_no;
					 //$igmDetailNumber=$dtlDate.$maxtally_sheet_no.$igmDetailId_no;
				}
				else if($size==2)
				{
				//	 $tallySheetNumber="TSN"."-".$date."00".$maxtally_sheet_no;
					$tallySheetNumber=$storageArea."-".$date."00".$maxtally_sheet_no;
					//$igmDetailNumber=$dtlDate.$maxtally_sheet_no.$igmDetailId_no;
				}
				else if($size==3)
				{
				//	 $tallySheetNumber="TSN"."-".$date."0".$maxtally_sheet_no;
					 $tallySheetNumber=$storageArea."-".$date."0".$maxtally_sheet_no;
					 //$igmDetailNumber=$dtlDate.$maxtally_sheet_no.$igmDetailId_no;
				}
				else 
				{
				//	 $tallySheetNumber="TSN"."-".$date."".$maxtally_sheet_no;
					$tallySheetNumber=$storageArea."-".$date."".$maxtally_sheet_no;
					//$igmDetailNumber=$dtlDate.$maxtally_sheet_no.$igmDetailId_no;
				}
				if($tbl=="sup_detail")
						{
							if($dtlId=="" || $dtlId==0)
							{
									$str = "insert into shed_tally_info(igm_sup_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,last_update,
									tally_sheet_no,actual_marks,total_pack,wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,
									physical_tally_sheet_no,shed_yard)
									values('$igmDetailNumber','$rot','$cont','$login_id','$ipaddr','$remark',now(),
									'$maxtally_sheet_no','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber',
									'$imdg','$weight','$rcv_unit','$berth_op','$physical_tally_sheet_no','$shed_name')";
							}
							else
							{
									$str = "insert into shed_tally_info(igm_sup_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,
									last_update,tally_sheet_no,actual_marks,total_pack,
									wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit, berthOp, physical_tally_sheet_no,shed_yard)
									values('$dtlId','$rot','$cont','$login_id','$ipaddr','$remark',now(),
									'$maxtally_sheet_no','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit','$berth_op','$physical_tally_sheet_no','$shed_name')";
							}
						}
						else
						{
							if($dtlId=="" || $dtlId==0)
							{
								$str = "insert into shed_tally_info(igm_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,last_update,
								tally_sheet_no,actual_marks,total_pack,wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit, berthOp,
								physical_tally_sheet_no,shed_yard)
									values('$igmDetailNumber','$rot','$cont','$login_id','$ipaddr','$remark',now(),
									'$maxtally_sheet_no','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit','$berth_op','$physical_tally_sheet_no','$shed_name')";
							}
							else
							{
								$str = "insert into shed_tally_info(igm_detail_id,import_rotation,cont_number,update_by,ip_addr,remarks,last_update,
								tally_sheet_no,actual_marks,total_pack,wr_date,shed_loc,tally_sheet_number,imdg,weight,rcv_unit,berthOp,
								physical_tally_sheet_no,shed_yard)
								values('$dtlId','$rot','$cont','$login_id','$ipaddr','$remark',now(),
								'$maxtally_sheet_no','$actualmarks',$totalPck,date(now()),'$storageArea','$tallySheetNumber','$imdg','$weight','$rcv_unit',
								'$berth_op','$physical_tally_sheet_no','$shed_name')";
							}
						}
					 //echo $str;
					// echo "<br>";
					$stat = $this->bm->dataInsertDB1($str);  //comment out to stop insertion
						if($stat==1)
						{
							$data['stat']="<font color='blue'><b>Sucessfully inserted</b></font>";
							$data['view_btn_status']=1;
							$data['exchange_btn_status']=1;
							$data['save_btn_status']=1;
						}
			}
			
			
			
			
			
			$chkExistShedTallyQry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
			$rtnExistShedTally = $this->bm->dataSelectDb1($chkExistShedTallyQry);
			
			$cntExist = $rtnExistShedTally[0]['id'];
					
			if($tbl=="sup_detail")
			{
				$tbl=="sup_detail";
				if($cntExist<1)
				{
					$sqlContainer = "select igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,
					igm_sup_detail_container.cont_imo
					from igm_supplimentary_detail 
					inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					where Import_Rotation_No='$rot' and cont_number='$cont'
					order by 2";
				}
				else
				{
					$sqlContainer = "select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,									cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_sup_detail_container.cont_imo
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
					WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
					shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
					NotifyDesc,igm_sup_detail_container.cont_imo FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
					)tbl2";
					/*$sqlContainer="SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,shed_tally_info.cont_number,
					cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
					FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail  ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					LEFT JOIN  igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					WHERE  shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont'
					ORDER BY 2";*/
				}
			}						
			else
			{
				$tbl=="detail";
				if($cntExist<1)
				{
					$sqlContainer = "select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'
					order by 2
					";
				}
				else
				{
					// echo "dtl 2 ".$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
									// from igm_details 
									// inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
									// where Import_Rotation_No='$rot' and cont_number='$cont'					
									// ) tbl1
									// union
									// select * from (SELECT shed_tally_info.igm_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
									// shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
									// NotifyDesc FROM shed_tally_info 
									// LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id 
									// LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
									// WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
									// )tbl2";
									
					$sqlContainer = "SELECT
					igm_details.id,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,
					Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					WHERE Import_Rotation_No='$rot' AND cont_number='$cont'

					UNION
		
					SELECT shed_tally_info.igm_detail_id AS id,Description_of_Goods,import_rotation AS Import_Rotation_No,BL_No, 
					shed_tally_info.cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,
					Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					FROM shed_tally_info 
					LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id 
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NULL";
				}
			}
			//echo $sqlContainer;
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);			
			$data['assigned']=1;
			$data['rotation']=$rot;
			$data['cont']=$cont;
			$data['tbl']=$tbl;
				
			$data['rtnContainerList']=$rtnContainerList;
			
			//////////Redirect to Tally
			$org_Type_id = $this->session->userdata('org_Type_id');
			$login_id = $this->session->userdata('login_id');
			if($this->input->post('rot') && $this->input->post('cont'))
			{
				$rot=trim($this->input->post('rot'));
				$cont=trim($this->input->post('cont'));
				
				$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
				FROM lcl_assignment_detail
				INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'";
				
				// $cntrslt=$this->bm->dataReturnDb1($cntquery);
				$cntrslt=1;//assign 1 for temporary to skip assignment 
				
				
				if($cntrslt==0)
				{
					$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
					$data['msg']="Please provide assignment for the container";
					$data['assigned']=0;
					$data['rotation']=$rot;
					$data['cont']=$cont;
					$data['login_id']=$login_id;
					$this->load->view('pShedTallyEntryFormView',$data);
					
					//return;
					//exchange_btn_status;
				} 
			}
			else
			{
				$cont=$this->uri->segment(3);
				$rot_year=$this->uri->segment(4);
				$rot_no=$this->uri->segment(5);
				$rot=$rot_year.'/'.$rot_no;
				
				$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
				FROM lcl_assignment_detail
				INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'";
				
				$cntrslt=$this->bm->dataReturnDb1($cntquery);
				
				if($cntrslt==0)
				{
					$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
					$data['msg']="Please provide assignment for the container";
					$data['assigned']=0;
					$data['rotation']=$rotation;
					$data['cont']=$cont;
					$data['login_id']=$login_id;
					$this->load->view('pShedTallyEntryFormView',$data);
					
					//return;
				}
			}
			
				$chkExistShedTallyQry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
			$rtnExistShedTally = $this->bm->dataSelectDb1($chkExistShedTallyQry);
			$cntExist = $rtnExistShedTally[0]['id'];								
			
			$tbl = "sup_detail";
		
			//Cont_gross_weight and cont_seal_number added
			if($cntExist<1)
			{
				$sqlContainer="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_sup_detail_container.cont_imo 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
				ORDER BY 2";
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "sup_detail";
				}
				
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
				WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_sup_detail_container.cont_number='$cont'";
			}
			else
			{
				$sqlContainerCheck="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
				FROM igm_supplimentary_detail 
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
				ORDER BY 2";				

				$rtnContainerListCheck = $this->bm->dataSelectDb1($sqlContainerCheck);
				$cntCheck = count($rtnContainerListCheck);
				
				if($cntCheck==0)
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					) tbl2
					UNION
					 SELECT * FROM (SELECT shed_tally_info.igm_sup_detail_id AS id, import_rotation AS Import_Rotation_No, BL_No, shed_tally_info.cont_number, cont_size, Cont_gross_weight, cont_seal_number, Pack_Description, Pack_Marks_Number, Pack_Number, ConsigneeDesc, NotifyDesc, igm_sup_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					 WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont'
					 AND  shed_tally_info.igm_sup_detail_id IS NOT NULL ) tbl3";
					//"AND BL_NO IS NOT NULL" (removed condition from above query for showing data of blank BL)-------------------
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_detail_container.cont_number='$cont'";
					
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "detail";
					}	
						

					/* $sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_detail_container.cont_number='$cont'"; */
				}
				else
				{
					$sqlContainer="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
					cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,
					igm_sup_detail_container.cont_imo
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
					WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
					shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
					NotifyDesc,igm_sup_detail_container.cont_imo FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
					)tbl2";
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "sup_detail";
					}	
					
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
					LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_sup_detail_container.cont_number='$cont'";
				}										
			}
			//echo $sqlContainer;
			
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$cnt = count($rtnContainerList);				
									
			//Cont_gross_weight and cont_seal_number added
			if($cnt==0)
			{
				$tbl = "detail";
				if($cntExist<1)
				{
					$sqlContainer = "select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					LEFT join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'
					order by 2";
				}
				else
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					)tbl2";
				}
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "detail";
				}
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_details 
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
				WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_detail_container.cont_number='$cont'";
			}
			
			// query for devonestop - remove later
			$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rot' and cont_number='$cont' and exchange_done_status=1";
			
			if($org_Type_id==59)			//CPA Shed Users
			{
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND cpa_exchange_done_status=1";
			}
			else if($org_Type_id==30)		// Berth Operator
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND berth_exchange_done_status=1";
			}
			else if($org_Type_id==4)		// FF
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND ff_exchange_done_status=1";
			}
			
			$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
			$chkVal= $chkList[0]['chkVal'];
			if($chkVal>0)
			{
				//$data['update_btn_status']=0;
				$data['view_btn_status']=1;  //previously 0; 1 for exchange done; alter if necessary
				$data['save_btn_status']=0;
				$data['exchange_btn_status']=0;
				$data['msgExchange']="Exchange Done";
			}
			else
			{
				// query for devonestop - remove later
				$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rot' and cont_number='$cont' and exchange_done_status=0";
				
				if($org_Type_id==59)			//CPA Shed Users
				{
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rot' AND cont_number='$cont' AND cpa_exchange_done_status=0";
				}
				else if($org_Type_id==30)		// Berth Operator
				{					
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rot' AND cont_number='$cont' AND berth_exchange_done_status=0";
				}
				else if($org_Type_id==4)		// FF
				{					
					$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
					FROM shed_tally_info 
					WHERE import_rotation='$rot' AND cont_number='$cont' AND ff_exchange_done_status=0";
				}
		
				$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
				$chkVal= $chkList[0]['chkVal'];
				if($chkVal>0)
				{
					//$data['update_btn_status']=1;
					$data['view_btn_status']=1;
					$data['exchange_btn_status']=1;
					$data['save_btn_status']=1;
				}
				else
				{
					//$data['update_btn_status']=0;
					$data['view_btn_status']=0;
					$data['exchange_btn_status']=0;
					$data['save_btn_status']=1;
				}
			}
			$login_id = $this->session->userdata('login_id');
														
			$rslt_vesselname_seal=$this->bm->dataSelectDb1($sql_vesselname_seal);
			
			$data['rslt_vesselname_seal']=$rslt_vesselname_seal;
			
			$data['assigned']=1;		
			$data['rotation']=$rot;
			$data['tbl']=$tbl;
			$data['cont']=$cont;
			
			$data['viewType']="search";
			$data['login_id']=$login_id;
			
			
			$data['rtnContainerList']=$rtnContainerList; //"$rtnContainerList" returns selected data for table
			$this->load->view('pShedTallyEntryFormView',$data);
			
		}

	function pShedDeleteTallyRcv()
	{	
		$tallyID=$this->uri->segment(3);
		$cont=$this->uri->segment(4);
		$rot=str_replace("_","/",$this->uri->segment(5));
		$tbl=$this->uri->segment(6);
		//return;
		$login_id = $this->session->userdata('login_id');
		
		$strDelQuery = "delete from shed_tally_info where id=$tallyID";
		$statDel = $this->bm->dataInsertDB1($strDelQuery);
		if($statDel==1)
		{
			$data['stat']="<font color='red'><b>Sucessfully Deleted.</b></font>";
			$data['view_btn_status']=1;
			$data['save_btn_status']=1;
			$data['exchange_btn_status']=1;
		}
		
		$chkExistShedTallyQry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
		$rtnExistShedTally = $this->bm->dataSelectDb1($chkExistShedTallyQry);
		
		$cntExist = $rtnExistShedTally[0]['id'];
				
		if($tbl=="sup_detail")
		{
			$tbl=="sup_detail";
			if($cntExist<1)
			{
				$sqlContainer = "select igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,
				igm_sup_detail_container.cont_imo 
				from igm_supplimentary_detail 
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				where Import_Rotation_No='$rot' and cont_number='$cont'
				order by 2";
			}
			else
			{
				$sqlContainer = "select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
								cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
								NotifyDesc,igm_sup_detail_container.cont_imo 
								FROM igm_supplimentary_detail 
								LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
								WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
								) tbl1
								union
								select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
								shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
								NotifyDesc,igm_sup_detail_container.cont_imo FROM shed_tally_info 
								LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
								LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
								WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
								)tbl2";
				/*$sqlContainer="SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,shed_tally_info.cont_number,
				cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
				FROM shed_tally_info 
				LEFT JOIN igm_supplimentary_detail  ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN  igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE  shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont'
				ORDER BY 2";*/
			}
		}						
		else
		{
			$tbl=="detail";
			if($cntExist<1)
			{
				$sqlContainer = "select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
				from igm_details 
				inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
				where Import_Rotation_No='$rot' and cont_number='$cont'
				order by 2
				";
			}
			else
			{
				// echo "dtl 2 ".$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
								// from igm_details 
								// inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
								// where Import_Rotation_No='$rot' and cont_number='$cont'					
								// ) tbl1
								// union
								// select * from (SELECT shed_tally_info.igm_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
								// shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
								// NotifyDesc FROM shed_tally_info 
								// LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id 
								// LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
								// WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
								// )tbl2";
								
				$sqlContainer = "SELECT
				igm_details.id,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,
				Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
				FROM igm_details 
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'

				UNION
	
				SELECT shed_tally_info.igm_detail_id AS id,Description_of_Goods,import_rotation AS Import_Rotation_No,BL_No, 
				shed_tally_info.cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,
				Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo
				FROM shed_tally_info 
				LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id 
				LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
				WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NULL";
			}
		}
	
		$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);			
		$data['assigned']=1;
		$data['rotation']=$rot;
		$data['cont']=$cont;
		$data['tbl']=$tbl;
			
		$data['rtnContainerList']=$rtnContainerList;
		
		//////////Redirect to Tally
		$org_Type_id = $this->session->userdata('org_Type_id');
		$login_id = $this->session->userdata('login_id');
		if($this->input->post('rot') && $this->input->post('cont'))
		{
			$rot=trim($this->input->post('rot'));
			$cont=trim($this->input->post('cont'));
			
			$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
			FROM lcl_assignment_detail
			INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE Import_Rotation_No='$rot' AND cont_number='$cont'";
			
			// $cntrslt=$this->bm->dataReturnDb1($cntquery);
			$cntrslt=1;//assign 1 for temporary to skip assignment 
			
			
			if($cntrslt==0)
			{
				$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
				$data['msg']="Please provide assignment for the container";
				$data['assigned']=0;
				$data['rotation']=$rot;
				$data['cont']=$cont;
				$data['login_id']=$login_id;
				//$this->load->view('pShedTallyEntryFormView',$data);
				
				//return;
				//exchange_btn_status;
			} 
		}
		else
		{
			// echo "runs";
			// echo "<br>";
			// echo $cont=$this->uri->segment(3);
			// echo "<br>";
			// echo $rot_year=$this->uri->segment(4);
			// echo "<br>";
			// echo $rot_no=$this->uri->segment(5);
			// echo "<br>";
			// echo $rot=$rot_year.'/'.$rot_no;
			
			$cntquery="SELECT COUNT(lcl_assignment_detail.igm_detail_id) AS rtnValue
			FROM lcl_assignment_detail
			INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE Import_Rotation_No='$rot' AND cont_number='$cont'";
			
			$cntrslt=$this->bm->dataReturnDb1($cntquery);
			
			if($cntrslt==0)
			{
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_detail_container.cont_number='$cont'";
				$rslt_vesselname_seal = $this->bm->dataSelectDb1($sql_vesselname_seal);
				if(count($rslt_vesselname_seal)==0)
				{
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
					LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_sup_detail_container.cont_number='$cont'";
					$rslt_vesselname_seal = $this->bm->dataSelectDb1($sql_vesselname_seal);
				}
				$data['title']="TALLY ENTRY FORM WITH IGM INFORMATION...";
				$data['msg']="Please provide assignment for the container";
				$data['assigned']=0;
				$data['rotation']=$rot;
				$data['cont']=$cont;
				$data['login_id']=$login_id;
				$data['view_btn_status']=1;
				$data['exchange_btn_status']=1;
				$data['rslt_vesselname_seal']=$rslt_vesselname_seal;
				//$this->load->view('pShedTallyEntryFormView',$data);
				
				//return;
			}
		}
		
					$chkExistShedTallyQry="select count(id) as id from shed_tally_info WHERE  import_rotation='$rot' and cont_number='$cont'";
			$rtnExistShedTally = $this->bm->dataSelectDb1($chkExistShedTallyQry);
			$cntExist = $rtnExistShedTally[0]['id'];								
			
			$tbl = "sup_detail";
		
			//Cont_gross_weight and cont_seal_number added
			if($cntExist<1)
			{
				$sqlContainer="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_sup_detail_container.cont_imo 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
				ORDER BY 2";
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "sup_detail";
				}
				
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_supplimentary_detail 
				LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
				WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_sup_detail_container.cont_number='$cont'";
			}
			else
			{
				$sqlContainerCheck="SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
				FROM igm_supplimentary_detail 
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
				ORDER BY 2";				

				$rtnContainerListCheck = $this->bm->dataSelectDb1($sqlContainerCheck);
				$cntCheck = count($rtnContainerListCheck);
				
				if($cntCheck==0)
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					) tbl2
					UNION
					 SELECT * FROM (SELECT shed_tally_info.igm_sup_detail_id AS id, import_rotation AS Import_Rotation_No, BL_No, shed_tally_info.cont_number, cont_size, Cont_gross_weight, cont_seal_number, Pack_Description, Pack_Marks_Number, Pack_Number, ConsigneeDesc, NotifyDesc, igm_sup_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					 WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont'
					 AND  shed_tally_info.igm_sup_detail_id IS NOT NULL ) tbl3";
					//"AND BL_NO IS NOT NULL" (removed condition from above query for showing data of blank BL)-------------------
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_detail_container.cont_number='$cont'";
					
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "detail";
					}	
						

					/* $sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_details 
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
					WHERE igm_masters.Import_Rotation_No= '$rotation' AND igm_detail_container.cont_number='$cont'"; */
				}
				else
				{
					$sqlContainer="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
					cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,
					igm_sup_detail_container.cont_imo
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
					WHERE Import_Rotation_No='$rot' AND cont_number='$cont'
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
					shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,
					NotifyDesc,igm_sup_detail_container.cont_imo FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE shed_tally_info.import_rotation='$rot' and shed_tally_info.cont_number='$cont' and BL_NO is null
					)tbl2";
					$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
					$cnt = count($rtnContainerList);				
					if($cnt>0)
					{
						$tbl = "sup_detail";
					}	
					
					$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
					LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id	
					WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_sup_detail_container.cont_number='$cont'";
				}										
			}
			//echo $sqlContainer;
			
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$cnt = count($rtnContainerList);				
									
			//Cont_gross_weight and cont_seal_number added
			if($cnt==0)
			{
				$tbl = "detail";
				if($cntExist<1)
				{
					$sqlContainer = "select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo 
					from igm_details 
					LEFT join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'
					order by 2";
				}
				else
				{
					$sqlContainer = "select * from (select igm_details.id,Import_Rotation_No,BL_No,cont_number,cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc,igm_detail_container.cont_imo
					from igm_details 
					inner join igm_detail_container on igm_detail_container.igm_detail_id=igm_details.id
					where Import_Rotation_No='$rot' and cont_number='$cont'					
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_detail_id AS id,
					import_rotation AS Import_Rotation_No,
					BL_No,
					shed_tally_info.cont_number,
					cont_size,
					Cont_gross_weight,
					cont_seal_number,
					Pack_Description,
					Pack_Marks_Number,
					Pack_Number,
					ConsigneeDesc,
					NotifyDesc,igm_detail_container.cont_imo FROM shed_tally_info LEFT JOIN igm_details ON shed_tally_info.igm_detail_id=igm_details.id LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id WHERE shed_tally_info.import_rotation='$rot' AND shed_tally_info.cont_number='$cont' AND BL_NO IS NOT NULL 
					)tbl2";
				}
				
				$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
				$cnt = count($rtnContainerList);				
				if($cnt>0)
				{
					$tbl = "detail";
				}
				
				$sql_vesselname_seal="SELECT Vessel_Name,cont_seal_number,cont_size 
				FROM igm_details 
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
				WHERE igm_masters.Import_Rotation_No= '$rot' AND igm_detail_container.cont_number='$cont'";
			}
		// query for devonestop - remove later
		$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rot' and cont_number='$cont' and exchange_done_status=1";
		
		if($org_Type_id==59)			//CPA Shed Users
		{
			$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
			FROM shed_tally_info 
			WHERE import_rotation='$rot' AND cont_number='$cont' AND cpa_exchange_done_status=1";
		}
		else if($org_Type_id==30)		// Berth Operator
		{					
			$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
			FROM shed_tally_info 
			WHERE import_rotation='$rot' AND cont_number='$cont' AND berth_exchange_done_status=1";
		}
		else if($org_Type_id==4)		// FF
		{					
			$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
			FROM shed_tally_info 
			WHERE import_rotation='$rot' AND cont_number='$cont' AND ff_exchange_done_status=1";
		}
		
		$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
		$chkVal= $chkList[0]['chkVal'];
		if($chkVal>0)
		{
			//$data['update_btn_status']=0;
			$data['view_btn_status']=1;  //previously 0; 1 for exchange done; alter if necessary
			$data['save_btn_status']=0;
			$data['exchange_btn_status']=0;
			$data['msgExchange']="Exchange Done";
		}
		else
		{
			// query for devonestop - remove later
			$chkExchangeDoneQuery="select count(id) as chkVal from shed_tally_info where import_rotation='$rot' and cont_number='$cont' and exchange_done_status=0";
			
			if($org_Type_id==59)			//CPA Shed Users
			{
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND cpa_exchange_done_status=0";
			}
			else if($org_Type_id==30)		// Berth Operator
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND berth_exchange_done_status=0";
			}
			else if($org_Type_id==4)		// FF
			{					
				$chkExchangeDoneQuery="SELECT COUNT(id) AS chkVal 
				FROM shed_tally_info 
				WHERE import_rotation='$rot' AND cont_number='$cont' AND ff_exchange_done_status=0";
			}
	
			$chkList = $this->bm->dataSelectDb1($chkExchangeDoneQuery);
			$chkVal= $chkList[0]['chkVal'];
			if($chkVal>0)
			{
				//$data['update_btn_status']=1;
				$data['view_btn_status']=1;
				$data['exchange_btn_status']=1;
				$data['save_btn_status']=1;
			}
			else
			{
				//$data['update_btn_status']=0;
				$data['view_btn_status']=0;
				$data['exchange_btn_status']=0;
				$data['save_btn_status']=1;
			}
		}
		$login_id = $this->session->userdata('login_id');
													
		$rslt_vesselname_seal=$this->bm->dataSelectDb1($sql_vesselname_seal);
		
		$data['rslt_vesselname_seal']=$rslt_vesselname_seal;
		
		$data['assigned']=1;		
		$data['rotation']=$rot;
		$data['tbl']=$tbl;
		$data['cont']=$cont;
		
		$data['viewType']="search";
		$data['login_id']=$login_id;
		
		
		$data['rtnContainerList']=$rtnContainerList; //"$rtnContainerList" returns selected data for table
		$this->load->view('pShedTallyEntryFormView',$data);
	}

	// Chemical Shed Tally Entry Ends----------------
	
	function dateWisePShedTallyListForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Date Wise TALLY RECEIVE REPORT (Chemical Shed)";
			$data['from_date']="";
			$data['to_date']="";
		
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('dateWisePShedtallyListForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function searchDtWisePShedTallyFormList()
	{
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		
		//$str="select * from shed_tally_info where DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date'";
			
		$str="SELECT igm_supplimentary_detail.id AS igmsupid,igm_supplimentary_detail.BL_No,shed_tally_info.*
				FROM shed_tally_info
				INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
				WHERE shed_tally_info.update_by='pshed' AND (DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date')
				GROUP BY BL_No
				ORDER BY shed_tally_info.id DESC";//GROUP BY tally_sheet_number
		$rtnTallyList = $this->bm->dataSelectDb1($str);
		$this->data['rtnTallyList']=$rtnTallyList;
		$this->data['title']="DATE WISE TALLY RECEIVE REPORT (Chemical Shed)";
		
		$this->data['from_date']=$from_date;
		$this->data['to_date']=$to_date;
		
		// $this->load->view('cssAssetsList');
		// $this->load->view('headerTop');
		// $this->load->view('sidebar');
		// $this->load->view('dateWisePShedtallyListForm',$data);
		// $this->load->view('jsAssetsList');
		
		$this->load->library('m_pdf');
		$html=$this->load->view('pShedTallyPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
		 
		$pdfFilePath ="tallyReport-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example
			
		//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
			 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	}
	
	function dateWisePShedTallyDeliveryReportForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="DATE WISE TALLY Delivery REPORT(Chemical Shed)";
			$data['from_date']="";
			$data['to_date']="";
		
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('dateWisePShedtallyDeliveryReportForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function searchDtWisePShedTallyDeliveryList()
	{
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		
		//$str="select * from shed_tally_info where DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date'";
			
		$str="SELECT igm_supplimentary_detail.id AS igmsupid,igm_supplimentary_detail.BL_No,shed_tally_info.*
				FROM shed_tally_info
				INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
				WHERE shed_tally_info.update_by='pshed' AND (DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date')
				GROUP BY BL_No
				ORDER BY shed_tally_info.id DESC";//GROUP BY tally_sheet_number
		$rtnTallyList = $this->bm->dataSelectDb1($str);
		$this->data['rtnTallyList']=$rtnTallyList;
		$this->data['title']="Date Wise TALLY LIST REPORT(Checmical Shed)";
		
		$this->data['from_date']=$from_date;
		$this->data['to_date']=$to_date;
		
		// $this->load->view('cssAssetsList');
		// $this->load->view('headerTop');
		// $this->load->view('sidebar');
		// $this->load->view('dateWisePShedtallyListForm',$data);
		// $this->load->view('jsAssetsList');
		
		$this->load->library('m_pdf');
		$html=$this->load->view('pShedTallyDeliveryPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
		 
		$pdfFilePath ="tallyReport-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example
			
		//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
			 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	}
	
	function dateWisePShedTallyBalanceReportForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			//$str="select * from shed_tally_info where DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date'";
				
			$str="SELECT igm_supplimentary_detail.id AS igmsupid,igm_supplimentary_detail.BL_No,shed_tally_info.*
					FROM shed_tally_info
					INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
					WHERE shed_tally_info.update_by='pshed'
					GROUP BY BL_No 
					ORDER BY shed_tally_info.id DESC";//GROUP BY tally_sheet_number
			$rtnTallyList = $this->bm->dataSelectDb1($str);
			$this->data['rtnTallyList']=$rtnTallyList;
			$this->data['title']="Date Wise TALLY LIST REPORT(Checmical Shed)";
						
			// $this->load->view('cssAssetsList');
			// $this->load->view('headerTop');
			// $this->load->view('sidebar');
			// $this->load->view('dateWisePShedtallyListForm',$data);
			// $this->load->view('jsAssetsList');
			
			$this->load->library('m_pdf');
			$html=$this->load->view('pShedTallyBalancePDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="tallyReport-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			//	$pdf->useSubstitutions = true; // optional - just as an example
				
			//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
	}
	
    //Chemical Shed Tally List Starts--------------
	function pShedTallyListForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$str="SELECT tally_sheet_number,import_rotation,cont_number,SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(shed_loc) AS shed_loc,loc_first,wr_date,shed_yard,SUM(total_pack) as total_pack,
			SUM(shed_tally_info.weight) AS weight
			FROM shed_tally_info
			INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			WHERE shed_tally_info.update_by='pshed'
			GROUP BY tally_sheet_number
			ORDER BY shed_tally_info.id DESC";
			
			$rtnContainerList = $this->bm->dataSelectDb1($str);
			$data['rtnContainerList']=$rtnContainerList;
			$data['title']="TALLY LIST REPORT(Chemical Shed)";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('pShedtallyListForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function pShedTallyFormList()
	{
		$ddl_imp_rot_no=$this->input->post('ddl_imp_rot_no');
		$ddl_cont_no=$this->input->post('ddl_cont_no');
				
		/* $str="SELECT tally_sheet_number,import_rotation,cont_number,igm_supplimentary_detail.BL_NO,rcv_pack,flt_pack,
			shed_loc,loc_first,wr_date,shed_yard FROM shed_tally_info
			INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			where import_rotation='$ddl_imp_rot_no' and cont_number='$ddl_cont_no'"; */
			
		$str="SELECT tally_sheet_number,import_rotation,cont_number,SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(shed_loc) AS shed_loc,loc_first,wr_date,shed_yard,SUM(total_pack) as total_pack,
		SUM(shed_tally_info.weight) AS weight   
		FROM shed_tally_info
		INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
		WHERE import_rotation='$ddl_imp_rot_no' AND cont_number='$ddl_cont_no' AND shed_tally_info.update_by='pshed'";	
				
		$rtnContainerList = $this->bm->dataSelectDb1($str);
		//echo $rtnContainerList[0]['verify_number']."  fdfdfd";
		$data['rtnContainerList']=$rtnContainerList;
		$data['title']="TALLY LIST REPORT(Chemical Shed)";
		//$data['vNum']=$rtnContainerList[0]['BL_NO'];
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('pShedtallyListForm',$data);
		$this->load->view('jsAssetsList');
		
	}
	
	function pShedTallyReportPdf()
	{ 
			//load mPDF library
		$this->load->library('m_pdf');
		//$mpdf->use_kwt = true;
		
		if($this->input->post('rotation') && $this->input->post('container'))
		{
			$rotation=$this->input->post('rotation');
			$container=$this->input->post('container');
		}
		/*else if($_GET["rotation"] && $_GET["cont"])
		{
			$rotation=$_GET["rotation"];
			$container=$_GET["cont"];
		}*/
		else if($this->uri->segment(3) != null && $this->uri->segment(4) != null)
		{
			$rotation=str_replace("_","/",$this->uri->segment(3));
			$container=str_replace("_","/",$this->uri->segment(4));
		}
		else{
			$rotation=$rot;
			$container=$cont;
		}
		
		$section = $this->session->userdata('section');
		$login_id = $this->session->userdata('login_id');
			
		$sqlinfo = "SELECT  id,(SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No= tmp.import_rotation) AS Vessel_Name,import_rotation as Import_Rotation_No,cont_number,cont_seal_number,cont_size,tally_sheet_number,rcv_pack,rcv_unit,loc_first,flt_pack,shed_loc,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
		(SUM(rcv_pack)+IFNULL(loc_first,0)) AS totPkg,actual_marks,marks_state,
		(SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack,shift_name,Notify_name,mlocode
		FROM(SELECT shed_tally_info.igm_sup_detail_id as id,rcv_unit,igm_supplimentary_detail.Line_No,shed_tally_info.import_rotation,igm_sup_detail_container.cont_number,cont_seal_number,Vessel_Name,cont_size,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number,shed_tally_info.flt_pack,shed_loc,tally_sheet_number,shift_name,
		(select Organization_Name from organization_profiles where id=igm_supplimentary_detail.Submitee_Org_Id) as Notify_name,igm_details.mlocode
		FROM  shed_tally_info
		LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id		
		WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container' 
		AND shed_tally_info.update_by='pshed') AS tmp GROUP BY id";
		
		$rtninfo = $this->bm->dataSelectDb1($sqlinfo);
		
		$loopCounter="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
					cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
					FROM igm_supplimentary_detail 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
					WHERE Import_Rotation_No='$rotation' AND cont_number='$container'
					) tbl1
					union
					select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
					shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,actual_marks,Pack_Number,ConsigneeDesc,
					NotifyDesc FROM shed_tally_info 
					LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
					LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$container' and BL_NO is null
					)tbl2";
		
		$rtnCounter = $this->bm->dataSelectDb1($loopCounter);
		
		// $sqlBerth="SELECT IFNULL(flex_string03,flex_string02) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
		// FROM sparcsn4.vsl_vessel_visit_details 
		// INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		// WHERE ib_vyg='$rotation'";
		$sqlBerth="SELECT NVL(flex_string03,flex_string02) AS berthOp,to_char(argo_carrier_visit.ata) AS ata
		FROM vsl_vessel_visit_details 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation'";
		//echo $sqlBerth;
		$rsltBerth=$this->bm->dataSelect($sqlBerth);
		
		$sqlSigQuery="select distinct signature_path_berth,signature_path_freight,signature_path_cpa from shed_tally_info 
		where import_rotation='$rotation' and cont_number='$container'";
			
		$rsltSig=$this->bm->dataSelectDb1($sqlSigQuery);
		$signature_path_berth=$rsltSig[0]['signature_path_berth'];
		$signature_path_freight=$rsltSig[0]['signature_path_freight'];
		$signature_path_cpa=$rsltSig[0]['signature_path_cpa'];
		
		$this->data['rotation']=$rotation;
		$this->data['container']=$container;
		$this->data['section']=$section;
		$this->data['rtninfo']=$rtninfo;
		$this->data['rtnCounter']=$rtnCounter;
		$this->data['rsltBerth']=$rsltBerth;
		$this->data['counter']=@$counter;
		$this->data['signature_path_berth']=$signature_path_berth;
		$this->data['signature_path_freight']=$signature_path_freight;
		$this->data['signature_path_cpa']=$signature_path_cpa;

		$html=$this->load->view('pShedTallyReportPdfOutput',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			
		$pdfFilePath ="tallyReport-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example
			
		//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
				
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	}
	
	function dateWisePShedRemovalListForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Date Wise TALLY RECEIVE REPORT (Chemical Shed)";
			$data['from_date']="";
			$data['to_date']="";
		
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('dateWisePShedRemovalListForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function searchDtWisePShedRemovalList()
	{
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		
		//$str="select * from shed_tally_info where DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date'";
			
		$str="SELECT igm_supplimentary_detail.id AS igmsupid,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_imo,shed_tally_info.*
		FROM shed_tally_info
		INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE shed_tally_info.update_by='pshed' AND (DATE(shed_tally_info.last_update) BETWEEN '$from_date' AND '$to_date')
		GROUP BY BL_No
		ORDER BY shed_tally_info.id DESC";//GROUP BY tally_sheet_number
		$rtnTallyList = $this->bm->dataSelectDb1($str);
		$this->data['rtnTallyList']=$rtnTallyList;
		$this->data['title']="DATE WISE TALLY RECEIVE REPORT (Chemical Shed)";
		
		$this->data['from_date']=$from_date;
		$this->data['to_date']=$to_date;
		
		// $this->load->view('cssAssetsList');
		// $this->load->view('headerTop');
		// $this->load->view('sidebar');
		// $this->load->view('dateWisePShedtallyListForm',$data);
		// $this->load->view('jsAssetsList');
		
		$this->load->library('m_pdf');
		$html=$this->load->view('pShedRemovalListPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			
		$pdfFilePath ="tallyReport-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example
			
		//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
				
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	}

}
?>