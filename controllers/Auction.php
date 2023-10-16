<?php

class Auction extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->helper('file');
			$this->load->model('ci_auth', 'bm', TRUE);
			$this->load->library("pagination");
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
			
	}
	
	 function index(){
		 
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
			

			$this->load->view('cssVesselList');
			$this->load->view('jsVesselList');
			$this->load->view('FrontEnd/header');
			$this->load->view('FrontEnd/slider');
			$this->load->view('FrontEnd/index',$data);
			$this->load->view('FrontEnd/footer');

			$this->db->cache_delete_all();
        }
		
		
		
// Auction Handover  -- Start
		
		function pendingRLGenerationList()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title']="Pending Auction RL Generation List";
				$data['msg'] = "";
				
				$query = "SELECT * FROM(
				SELECT sparcsn4.inv_unit.gkey,inv_unit.id,inv_unit.freight_kind,
				sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
				sparcsn4.ref_bizunit_scoped.id AS agent,
				TIMESTAMPDIFF(DAY,sparcsn4.inv_unit_fcy_visit.time_in, NOW()) AS lying_days,
				sparcsn4.vsl_vessel_visit_details.ib_vyg AS rot_no,sparcsn4.vsl_vessels.name AS v_name,
				RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
				RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height,
				sparcsn4.ref_equip_type.id AS TYPE,
				DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				
				(SELECT DATE(time_discharge_complete)
				FROM sparcsn4.vsl_vessel_visit_details
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 LIMIT 1) AS cl_dt,
		
				sparcsn4.inv_unit_fcy_visit.last_pos_slot,
				inv_unit_fcy_visit.flex_string04 AS rl_no,inv_unit_fcy_visit.flex_string05 AS rl_date,
				inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
				inv_unit_fcy_visit.time_in, inv_unit_fcy_visit.time_out,'' AS remarks
				FROM sparcsn4.inv_unit
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
				INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				WHERE transit_state='S40_YARD' AND sparcsn4.inv_unit.category='IMPRT' 
				AND DATE(argo_visit_details.time_discharge_complete) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
				GROUP BY sparcsn4.vsl_vessel_visit_details.ib_vyg ORDER BY argo_visit_details.time_discharge_complete DESC
				) AS tbl WHERE lying_days>=30";
				$result = $this->bm->dataSelect($query);
				
				$data['result']=$result;
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('PendingRLGenerationList',$data);
				$this->load->view('jsAssetsList');
			}
		}

		function AuctionHandOverReportForm()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{ 
				$data['title']="Auction Handover";
				$data['msg'] = "";
				$data['flag'] = 0;
				$data['save_flag'] = 0;
				$data['result'] = null;

				//for print
				$this->data['title']="Auction Handover";
				$this->data['msg'] = "";
				$this->data['flag'] = 0;
				$this->data['save_flag'] = 0;
				$this->data['result'] = null;

				$action = $this->input->post('action');
				//$save_action = $this->input->post('action');
				
				$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
				$year_format = $dt->format('Y');
				
				$containerArray=array();
				
				if($action == "save"){
					//If save button is clicked...
					$data['save_flag'] = 1;
					$unit=$this->input->post('unit');
					$rotation_no=$this->input->post('rotation');
					$cntResult=$this->input->post('cntResult');
					$arrival_date=$this->input->post('arrival_date');
					$cl_date=$this->input->post('cl_date');
					$vessel_name=$this->input->post('vessel_name');
					
					$year = substr($rotation_no,2,2);
					
					$rl_beginning = $unit.$year;
					$rl_no = "";
					
					$totRl=0;
					$totRlwithBL=0;
					
					$login_id = $this->session->userdata('login_id');
					$ipaddr = $_SERVER['REMOTE_ADDR'];					
									
					for($i=1;$i<=$cntResult;$i++)
					{
						$cntContainers = $this->input->post("cntContainers".$i);
						$bl_no = $this->input->post("blNo".$i);
						$pack_Marks_Number = $this->input->post("pack_Marks_Number".$i);
						$description_of_Goods = $this->input->post("description_of_Goods".$i);
						$notify_name = $this->input->post("notify_name".$i);
						$Pack_Number = $this->input->post("Pack_Number".$i);
						$agent = $this->input->post("agent".$i);
						$remarks = $this->input->post("remarks".$i);
						for($c=0;$c<$cntContainers;$c++){
							$inv_unit_gkey = $this->input->post("inv_unit_gkey".$i.$c);
							$cont = $this->input->post("cont".$i.$c);
							$size = $this->input->post("size".$i.$c);
							$height = $this->input->post("height".$i.$c);
							$cont_status = $this->input->post("cont_status".$i.$c);
							$last_pos_slot = $this->input->post("last_pos_slot".$i.$c);
							$obpc_number = $this->input->post("obpc_number".$i.$c);
							$obpc_date = $this->input->post("obpc_date".$i.$c);
							$weight = $this->input->post("weight".$i.$c);
							$type = $this->input->post("type".$i.$c);
							$removal_status = $this->input->post("removal_status".$i.$c);
							if($removal_status=="0")
							{
								$strCntRl = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rl_no LIKE '%$rl_beginning%'";						
								$totRl=$this->bm->dataReturnDb1($strCntRl);
								if($totRl==0)
								{
									// No RL for the combination of 'year of rotation' and 'unit', so this will be the first RL for this unit-year combination...
									$rl_no = $rl_beginning."000001";
								}
								else
								{
									$strCntRlwithBL = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no'";
									$totRlwithBL=$this->bm->dataReturnDb1($strCntRlwithBL);	
									if($totRlwithBL==0)
									{
										//No RL for the given BL...
										$str_new_rl = "SELECT MAX(RIGHT(rl_no,6))+1 AS rtnValue FROM auction_handover 
												WHERE rl_no LIKE '%$rl_beginning%' ORDER BY id DESC LIMIT 1";
										$new_rl=$this->bm->dataReturnDb1($str_new_rl);
										$rl_no = $rl_beginning.str_pad((string)$new_rl, "6", "0", STR_PAD_LEFT); 
									}
									else
									{
										//There is already some/one RL for the given BL...
										$str_new_rl = "SELECT rl_no AS rtnValue FROM auction_handover 
												WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no' ORDER BY id DESC LIMIT 1";
										$new_rl=$this->bm->dataReturnDb1($str_new_rl);
										$rl_no = $new_rl; 
									}
								}
														
								$str = "insert into auction_handover(rotation_no,vessel_name,arrival_date,cl_date,bl_no,pack_Marks_Number,description_of_Goods,
									notify_name,cont,agent,weight,quantity,
									size,height,type,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,
									remarks,inv_unit_gkey,entered_by,entry_ip,entry_time)
									values('$rotation_no','$vessel_name','$arrival_date','$cl_date','$bl_no','$pack_Marks_Number','$description_of_Goods','$notify_name',
									'$cont','$agent','$weight','$Pack_Number',
									'$size','$height','$type','$cont_status','$unit','$rl_no',now(),
									'$obpc_number','$obpc_date','$last_pos_slot','$remarks','$inv_unit_gkey','$login_id',
									'$ipaddr',now())";			
								$stat = $this->bm->dataInsertDB1($str);
								$data['msg'] = "<font color='blue'><strong>Data Saved Succesfully.</strong></font>";							
							}
						}
					}
				}

				if($action == "Search" or $action == "save" or $action == "print" )
				{
					//Resend with list...
					$data['flag'] = 1;
					$rotation = $this->input->post('rotation');
					$data['rotation'] = $rotation; //...2021/3202....2021/844

					// for print
					$this->data['flag'] = 1;
					$this->data['rotation'] = $rotation;
					
					$strSearch = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rotation_no='$rotation'";
					$rtnValue=$this->bm->dataReturnDb1($strSearch);
					
					if($rtnValue=="0")
					{
						// for print
						$this->data['save_flag'] = 0;

						$data['save_flag'] = 0;
						$arrival_date = "";
						$cl_date = "";
						$v_name = "";
						$agent = "";
						$query = "SELECT * FROM (
							SELECT sparcsn4.inv_unit.gkey,inv_unit.id,inv_unit.freight_kind,
								sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
								sparcsn4.ref_bizunit_scoped.id AS agent,
								TIMESTAMPDIFF(DAY,sparcsn4.inv_unit_fcy_visit.time_in, NOW()) AS lying_days,
								sparcsn4.vsl_vessel_visit_details.ib_vyg AS rot_no,sparcsn4.vsl_vessels.name AS v_name,
								RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
								RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height,
								sparcsn4.ref_equip_type.id AS TYPE,
								DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
								
							(SELECT DATE(time_discharge_complete)
							FROM sparcsn4.vsl_vessel_visit_details
							INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
							WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 LIMIT 1) AS cl_dt,
						
								sparcsn4.inv_unit_fcy_visit.last_pos_slot,
								inv_unit_fcy_visit.flex_string04 AS rl_no,inv_unit_fcy_visit.flex_string05 AS rl_date,
								inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
								inv_unit_fcy_visit.time_in, inv_unit_fcy_visit.time_out,'' AS remarks
								FROM sparcsn4.inv_unit
								INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
								INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
								INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
								INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
								INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
								INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
								INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
								INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
								INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
								WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit.category='IMPRT' 
								AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation'
								) AS tbl WHERE lying_days>=30";

						$result = $this->bm->dataSelect($query);
						$conts='';
						
						for($x=0;$x<count($result);$x++)
						{
							$arrival_date=$result[$x]['ata'];	
							$cl_date=$result[$x]['cl_dt'];	
							$v_name=$result[$x]['v_name'];
							$agent=$result[$x]['agent'];
							$containers = "'".$result[$x]['id']."'";
							array_push($containerArray,$containers);							
						}
						//print_r($containerArray);
						$allCont = implode(",",$containerArray);
						//echo $conts;
						//return;
						$data['arrival_date'] = $arrival_date;
						$data['cl_date'] = $cl_date;
						$data['v_name'] = $v_name;
						$data['agent'] = $agent;
						$data['conts'] = $conts;
						$data['allCont'] = $allCont;

						// for print
						$this->data['arrival_date'] = $arrival_date;
						$this->data['cl_date'] = $cl_date;
						$this->data['v_name'] = $v_name;
						$this->data['agent'] = $agent;
						$this->data['conts'] = $conts;
						$this->data['allCont'] = $allCont;
					}
					else
					{
						// for print
						$this->data['save_flag'] = 1;
						
						$data['save_flag'] = 1;
						$unit = "";
						$arrival_date = "";
						$cl_date = "";
						$v_name = "";
						$agent = "";
						// $query = "SELECT distinct bl_no, rotation_no,vessel_name,arrival_date,cl_date,pack_Marks_Number,
								// description_of_Goods,notify_name,cont as id,agent,weight,quantity,size,
								// height,type,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,remarks,
								// inv_unit_gkey as gkey,entered_by,entry_ip,entry_time
								// FROM auction_handover 
								// WHERE rotation_no='$rotation' GROUP BY bl_no";
						
					/* 	$query = "SELECT distinct bl_no, rotation_no,vessel_name,arrival_date,cl_date,pack_Marks_Number,
								description_of_Goods,notify_name,cont as id,agent,weight,quantity,size,
								height,type,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,remarks,
								inv_unit_gkey as gkey,entered_by,entry_ip,entry_time
								FROM auction_handover 
								WHERE rotation_no='$rotation' 
								GROUP BY bl_no ORDER BY rl_no"; */
						$query = "SELECT * ,
								( CASE
								 WHEN house_bl IS NULL THEN igm_notify
								 WHEN house_bl IS NOT NULL THEN igm_sup_notify	
								 END) AS Notify_name_,
								 ( CASE
								 WHEN house_bl IS NULL THEN igm_notify_addr
								 WHEN house_bl IS NOT NULL THEN igm_sup_notify_addr	
								 END) AS Notify_sup_name_

								 FROM ( SELECT DISTINCT auction_handover.bl_no, rotation_no,vessel_name,arrival_date,cl_date, auction_handover.pack_Marks_Number,
								auction_handover.description_of_Goods, igm_details.notify_name,cont AS id,agent, auction_handover.weight,quantity,size,
								height,TYPE,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,auction_handover.remarks,
								inv_unit_gkey AS gkey,entered_by,entry_ip,entry_time,
								igm_details.Notify_name AS igm_notify, 
								igm_details.Notify_address AS igm_notify_addr, 
								igm_supplimentary_detail.Notify_name AS igm_sup_notify, 
								igm_supplimentary_detail.Notify_address AS igm_sup_notify_addr, 

								IFNULL(igm_details.mlocode, '') AS  mlocode,

								(SELECT organization_profiles.Organization_Name FROM  organization_profiles
								WHERE organization_profiles.id = igm_supplimentary_detail.Submitee_Org_Id LIMIT 1) AS ff_name,
								(SELECT organization_profiles.Address_1 FROM  organization_profiles
								WHERE organization_profiles.id = igm_supplimentary_detail.Submitee_Org_Id LIMIT 1) AS ff_addr,
								igm_supplimentary_detail.BL_No AS house_bl
								FROM auction_handover 
								LEFT JOIN igm_details ON igm_details.BL_No=auction_handover.bl_no AND igm_details.Import_Rotation_No=auction_handover.rotation_no
								LEFT JOIN igm_supplimentary_detail ON  igm_supplimentary_detail.master_BL_No=auction_handover.bl_no AND igm_supplimentary_detail.Import_Rotation_No=auction_handover.rotation_no
								WHERE rotation_no='$rotation' GROUP BY bl_no ORDER BY rl_no ASC
								) AS tmp  ";	
								// echo $query;return;
						$result = $this->bm->dataSelectDb1($query);
						
						for($x=0;$x<count($result);$x++)
							{
								$unit=$result[$x]['unit'];	
								$arrival_date=$result[$x]['arrival_date'];	
								$cl_date=$result[$x]['cl_date'];	
								$v_name=$result[$x]['vessel_name'];	
								$agent=$result[$x]['agent'];
							}
						//print_r($containerArray);
						$queryCont = "SELECT cont FROM auction_handover WHERE rotation_no='$rotation'";
						$resultCont = $this->bm->dataSelectDb1($queryCont);
						for($y=0;$y<count($resultCont);$y++)
						{	
							$containers = "'".$resultCont[$y]['cont']."'";
							array_push($containerArray,$containers);
						}						
						$allCont = implode(",",$containerArray);
						
						$data['unit'] = $unit;
						$data['arrival_date'] = $arrival_date;
						$data['cl_date'] = $cl_date;
						$data['v_name'] = $v_name;
						$data['agent'] = $agent;
						$data['allCont'] = $allCont;

						// for print
						$this->data['unit'] = $unit;
						$this->data['arrival_date'] = $arrival_date;
						$this->data['cl_date'] = $cl_date;
						$this->data['v_name'] = $v_name;
						$this->data['agent'] = $agent;
						$this->data['allCont'] = $allCont;
					}
					$queryBL = "SELECT DISTINCT igm_details.BL_No
						FROM cchaportdb.igm_details
						INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
						WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' AND 
						cchaportdb.igm_detail_container.cont_number IN ($allCont)";
					$resultBL = $this->bm->dataSelectDb1($queryBL);
					
					$cntResult = count($resultBL);
					$data['result']=$result;
					$data['cntResult']=$cntResult;
					$data['resultBL']=$resultBL;

					// for print
					$this->data['result']=$result;
					$this->data['cntResult']=$cntResult;
					$this->data['resultBL']=$resultBL;
				}

				if($action == "print")
				{
					$this->load->library('m_pdf');	
					$html=$this->load->view('AuctionHandOverReportPrint',$this->data, true); 
					$pdfFilePath ="Auction Handover Report-".time()."-download.pdf";
					$pdf = $this->m_pdf->load();
					$pdf = new mPDF('c', 'A4-L');
					$pdf->useSubstitutions = true; 
					$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
					$pdf->WriteHTML($html,2);
					$pdf->Output($pdfFilePath, "I");

					//$this->load->view('AuctionHandOverReportPrint',$data);
				}
				else
				{
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');				
					$this->load->view('AuctionHandOverReportForm',$data);
					$this->load->view('jsAssets');
				}				
			}
		}
		
		function AuctionHandOverReportList()
		{
			$session_id = $this->session->userdata('value');			
			$LoginStat = $this->session->userdata('LoginStat');		
			if($LoginStat!="yes")
			{
				$this->logout();				
			}
			else
			{							
				$data['title']="AUCTION HANDOVER LIST";
				if($this->input->post("search")=="0")
				{
					$unit = $this->input->post("unit");
					$from_date = $this->input->post("from_date");
					$to_date = $this->input->post("to_date");
					$search = $this->input->post("search");
					$data['unit']=$unit;
					$data['from_date']=$from_date;
					$data['to_date']=$to_date;
					$data['search']=1;
					$sql = "SELECT DISTINCT(rotation_no),arrival_date,cl_date,unit 
							FROM auction_handover 
							WHERE unit='$unit' AND (DATE(entry_time) BETWEEN '$from_date' AND '$to_date')
							ORDER BY id DESC";	
				}
				else
				{
					$data['search']=0;							
					$sql = "SELECT DISTINCT(rotation_no),arrival_date,cl_date,unit FROM auction_handover ORDER BY id DESC";
				}
				$auction_handover_List = $this->bm->dataSelectDb1($sql);
				$data['auction_handover_List']=$auction_handover_List;
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('AuctionHandoverList',$data);
				$this->load->view('jsAssetsList');
				
			}
		}
		// Auction Handover  -- End
		// Auction Notice  -- Start	
		
		function AuctionHandNoticeGeneration()
		{
			$session_id = $this->session->userdata('value');			
			$LoginStat = $this->session->userdata('LoginStat');		
			if($LoginStat!="yes")
			{
				$this->logout();				
			}
			else
			{	
				$this->load->library('m_pdf');

				$rotation = $this->input->post("rotation");
				$arrival_dt = $this->input->post("arrival_dt");
				$cl_date = $this->input->post("cl_dt");
				
				$query = "SELECT * FROM(
				SELECT sparcsn4.inv_unit.gkey,inv_unit.id,inv_unit.freight_kind,
				sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
				sparcsn4.ref_bizunit_scoped.id AS agent,
				TIMESTAMPDIFF(DAY,sparcsn4.inv_unit_fcy_visit.time_in, NOW()) AS lying_days,
				sparcsn4.vsl_vessel_visit_details.ib_vyg AS rot_no,sparcsn4.vsl_vessels.name AS v_name,
				RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
				RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height,
				sparcsn4.ref_equip_type.id AS TYPE,
				DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				
				(SELECT DATE(time_discharge_complete)
				FROM sparcsn4.vsl_vessel_visit_details
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 LIMIT 1) AS cl_dt,
		
				sparcsn4.inv_unit_fcy_visit.last_pos_slot,
				inv_unit_fcy_visit.flex_string04 AS rl_no,inv_unit_fcy_visit.flex_string05 AS rl_date,
				inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
				inv_unit_fcy_visit.time_in, inv_unit_fcy_visit.time_out,'' AS remarks
				FROM sparcsn4.inv_unit
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
				INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit.category='IMPRT' 
						AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation'
				) AS tbl WHERE lying_days>=30";
						$result = $this->bm->dataSelect($query);
						$conts='';
						$containerArray=array();
						for($x=0;$x<count($result);$x++)
						{
							$v_name = $result[$x]['v_name'];
							//$conts = "'".implode("','", $result[$x]['id'])."'";
							$containers = "'".$result[$x]['id']."'";
							array_push($containerArray,$containers);
						}
						$allCont = implode(",",$containerArray);
						
						$igm_sql ="SELECT DISTINCT igm_details.BL_No, igm_details.Notify_name, igm_details.Notify_address  
									FROM cchaportdb.igm_details
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
									WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' AND 
									cchaportdb.igm_detail_container.cont_number IN ($allCont)";
						$notice_List = $this->bm->dataSelectDb1($igm_sql);

						
				
				/* $sql = "SELECT  auction_handover.bl_no, auction_handover.notify_name, auction_handover.vessel_name, igm_details.Notify_address
				FROM auction_handover 
				LEFT JOIN igm_details ON auction_handover.bl_no=igm_details.BL_No AND auction_handover.rotation_no=igm_details.Import_Rotation_No
				WHERE auction_handover.rotation_no='$rotation' ORDER BY rl_no ASC";	
			
				$notice_List = $this->bm->dataSelectDb1($sql); */
				
				$this->data['v_name']=$v_name;
				$this->data['notice_List']=$notice_List;
				$this->data['rotation']=$rotation;
				$this->data['arrival_dt']=$arrival_dt;
				$this->data['cl_date']=$cl_date;
				
				
				$html=$this->load->view('AuctionHandNotice',$this->data,true); 			 
				$pdfFilePath ="AuctionHandNotice-".time()."-download.pdf";



				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('resources/styles/lcl.css'); 
				
				
				$pdf->useSubstitutions = true; 
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I");   
				
			}
		}
		// Auction Handover  -- End

		
		
		
		
		// Pending Auction RL List -  LCL
		
		function pendingRLGenerationListLCL()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title']="Pending Auction RL Generation List (LCL)";
				$data['msg'] = "";
				
/* 				$query = "SELECT * FROM( SELECT * FROM (
						SELECT shed_tally_info.id, shed_tally_info.igm_detail_id AS ig_id, igm_masters.Vessel_Name, 
						BL_No, import_rotation AS Import_Rotation_No, shed_tally_info.cont_number,
						cont_size, igm_detail_container.cont_status, 
						Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,

						TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
						FROM shed_tally_info
						INNER JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
						INNER JOIN  igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
						WHERE  DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
						AND shed_tally_info.igm_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1


						UNION

						SELECT shed_tally_info.id,  shed_tally_info.igm_sup_detail_id AS ig_id, igm_masters.Vessel_Name, 
						igm_supplimentary_detail.BL_No,import_rotation AS Import_Rotation_No,
						shed_tally_info.cont_number, 
						cont_size, igm_sup_detail_container.cont_status, 
						igm_supplimentary_detail.Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
						TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
						FROM shed_tally_info 
						INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
						INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
						WHERE DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
						AND shed_tally_info.igm_sup_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1 
						) tbl1 ) AS tmp WHERE lying_days> 30 ORDER BY id ASC "; */
						
				$query="SELECT DISTINCT Import_Rotation_No, Vessel_Name FROM( SELECT * FROM (
						SELECT import_rotation AS Import_Rotation_No, igm_masters.Vessel_Name, 

						TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
						FROM shed_tally_info
						INNER JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
						INNER JOIN  igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
						WHERE  DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
						AND shed_tally_info.igm_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1


						UNION

						SELECT 
						import_rotation AS Import_Rotation_No, igm_masters.Vessel_Name, 
						TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
						FROM shed_tally_info 
						INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
						INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
						WHERE DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
						AND shed_tally_info.igm_sup_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1 
						) tbl1 WHERE lying_days> 30 ) AS tmp  ORDER BY Import_Rotation_No DESC";		
				$result = $this->bm->dataSelectDb1($query);
				
				$data['result']=$result;
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('PendingRLGenerationList_LCL',$data);
				$this->load->view('jsAssetsList');
			}
		}
		
		
		function AuctionHandOverReportFormLCL()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{ 
				$data['title']="Auction Handover (LCL)";
				$data['msg'] = "";
				$data['flag'] = 0;
				$data['save_flag'] = 0;
				$data['result'] = null;

				//for print
				$this->data['title']="Auction Handover (LCL)";
				$this->data['msg'] = "";
				$this->data['flag'] = 0;
				$this->data['save_flag'] = 0;
				$this->data['result'] = null;

				$action = $this->input->post('action');
				//$save_action = $this->input->post('action');
				
				$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
				$year_format = $dt->format('Y');
				
				$containerArray=array();
				
				if($action == "save"){
					//If save button is clicked...
					$data['save_flag'] = 1;
					$unit=$this->input->post('unit');
					$rotation_no=$this->input->post('rotation');
					$cntResult=$this->input->post('cntResult');
					$arrival_date=$this->input->post('arrival_date');
					$cl_date=$this->input->post('cl_date');
					$vessel_name=$this->input->post('vessel_name');
					
					$year = substr($rotation_no,2,2);
					
					$rl_beginning = $unit.$year;
					$rl_no = "";
					
					$totRl=0;
					$totRlwithBL=0;

					$login_id = $this->session->userdata('login_id');
					$ipaddr = $_SERVER['REMOTE_ADDR'];					
									
					for($i=1;$i<=$cntResult;$i++)
					{
						$cntContainers = $this->input->post("cntContainers".$i);
						$bl_no = $this->input->post("blNo".$i);
						$house_bl = $this->input->post("house_bl".$i);
						$pack_Marks_Number = $this->input->post("pack_Marks_Number".$i);
						$description_of_Goods = $this->input->post("description_of_Goods".$i);
						$notify_name = $this->input->post("notify_name".$i);
						$Pack_Number = $this->input->post("Pack_Number".$i);
						$Pack_Description = $this->input->post("Pack_Description".$i);
						$agent = $this->input->post("agent".$i);
						$remarks = $this->input->post("remarks".$i);
						for($c=0;$c<$cntContainers;$c++){
							$inv_unit_gkey = $this->input->post("inv_unit_gkey".$i.$c);
							$cont = $this->input->post("cont".$i.$c);
							$size = $this->input->post("size".$i.$c);
							$height = $this->input->post("height".$i.$c);
							$cont_status = $this->input->post("cont_status".$i.$c);
							$last_pos_slot = $this->input->post("last_pos_slot".$i.$c);
							$obpc_number = $this->input->post("obpc_number".$i.$c);
							$obpc_date = $this->input->post("obpc_date".$i.$c);
							$weight = $this->input->post("weight".$i.$c);
							$type = $this->input->post("type".$i.$c);
							$removal_status = $this->input->post("removal_status".$i.$c);
							if($removal_status=="0")
							{
								$strCntRl = "SELECT COUNT(*) AS rtnValue FROM auction_handover_lcl WHERE rl_no LIKE '%$rl_beginning%'";						
								$totRl=$this->bm->dataReturnDb1($strCntRl);
								if($totRl==0)
								{
									// No RL for the combination of 'year of rotation' and 'unit', so this will be the first RL for this unit-year combination...
									$rl_no = $rl_beginning."000001";
								}
								else
								{
									$strCntRlwithBL = "SELECT COUNT(*) AS rtnValue FROM auction_handover_lcl WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no'";
									$totRlwithBL=$this->bm->dataReturnDb1($strCntRlwithBL);	
									if($totRlwithBL==0)
									{
										//No RL for the given BL...
										$str_new_rl = "SELECT MAX(RIGHT(rl_no,6))+1 AS rtnValue FROM auction_handover_lcl 
												WHERE rl_no LIKE '%$rl_beginning%' ORDER BY id DESC LIMIT 1";
										$new_rl=$this->bm->dataReturnDb1($str_new_rl);
										$rl_no = $rl_beginning.str_pad((string)$new_rl, "6", "0", STR_PAD_LEFT); 
									}
									else
									{
										//There is already some/one RL for the given BL...
										$str_new_rl = "SELECT rl_no AS rtnValue FROM auction_handover_lcl 
												WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no' ORDER BY id DESC LIMIT 1";
										$new_rl=$this->bm->dataReturnDb1($str_new_rl);
										$rl_no = $new_rl; 
									}
								}
														
								$str = "insert into auction_handover_lcl(rotation_no,vessel_name,arrival_date,cl_date,bl_no,pack_Marks_Number,description_of_Goods,
									notify_name,cont,agent,weight,quantity, pack_description,
									size,height,type,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,
									remarks,inv_unit_gkey,entered_by,entry_ip,entry_time, house_bl)
									values('$rotation_no','$vessel_name','$arrival_date','$cl_date','$bl_no','$pack_Marks_Number','$description_of_Goods','$notify_name',
									'$cont','$agent','$weight','$Pack_Number', '$Pack_Description',
									'$size','$height','$type','$cont_status','$unit','$rl_no',now(),
									'$obpc_number','$obpc_date','$last_pos_slot','$remarks','$inv_unit_gkey','$login_id',
									'$ipaddr',now(),'$house_bl')";			
								$stat = $this->bm->dataInsertDB1($str);
								$data['msg'] = "<font color='blue'><strong>Data Saved Succesfully.</strong></font>";							
							}
						}
					}
				} 

				if($action == "Search"  or $action == "print" )
				{
					//Resend with list...
					$data['flag'] = 1;
					$rotation = $this->input->post('rotation');
					//$bl_no = $this->input->post('bl_no');
					$data['rotation'] = $rotation; //...2021/3202....2021/844

					// for print
					$this->data['flag'] = 1;
					$this->data['rotation'] = $rotation;
					
					//$strSearch = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rotation_no='$rotation' AND bl_no='$bl_no'";
					$strSearch = "SELECT COUNT(*) AS rtnValue FROM auction_handover_lcl WHERE rotation_no='$rotation'";
					$rtnValue=$this->bm->dataReturnDb1($strSearch);
					
					if($rtnValue=="0")
					{
						// for print
						$this->data['save_flag'] = 0;

						$data['save_flag'] = 0;
						$arrival_date = "";
						$cl_date = "";
						$v_name = "";
						$agent = "";
						$query = "SELECT * FROM( SELECT * FROM (SELECT shed_tally_info.id, shed_tally_info.igm_detail_id AS ig_id, igm_masters.Vessel_Name, 
								BL_No, import_rotation AS Import_Rotation_No, shed_tally_info.cont_number,
								cont_size, igm_detail_container.cont_status, 
								Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info
								INNER JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
								INNER JOIN  igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE  DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1


								UNION

								SELECT shed_tally_info.id,  shed_tally_info.igm_sup_detail_id AS ig_id, igm_masters.Vessel_Name, 
								igm_supplimentary_detail.BL_No,import_rotation AS Import_Rotation_No,
								shed_tally_info.cont_number, 
								cont_size, igm_sup_detail_container.cont_status, 
								igm_supplimentary_detail.Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info 
								INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_sup_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1 
								)tbl1 WHERE Import_Rotation_No='$rotation' ) AS tmp WHERE lying_days> 30 ORDER BY id ASC ";

						$result = $this->bm->dataSelectDb1($query);
						$conts='';
						
						for($x=0;$x<count($result);$x++)
						{
							//$arrival_date=$result[$x]['ata'];	
							//$rot=$result[$x]['Import_Rotation_No'];	
							$v_name=$result[$x]['Vessel_Name'];
							$containers = "'".$result[$x]['cont_number']."'";
							array_push($containerArray,$containers);
							
						}
						
						$berth_query = "SELECT DATE(time_discharge_complete) AS cl_date, sparcsn4.ref_bizunit_scoped.id AS agent,
									DATE(sparcsn4.argo_carrier_visit.ata) AS ata
									FROM sparcsn4.vsl_vessel_visit_details 
									INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey 
									INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
									INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
									WHERE ib_vyg='$rotation' LIMIT 1";
							
						$bertRslt=$this->bm->dataSelect($berth_query);
						
						$cl_date=$bertRslt[0]['cl_date'];
						$agent=$bertRslt[0]['agent'];
						$arrival_date=$bertRslt[0]['ata'];
						
						
						//print_r($containerArray);
						$allCont = implode(",",$containerArray);
						//echo $conts;
						//return;
						$data['arrival_date'] = $arrival_date;
						$data['cl_date'] = $cl_date;
						$data['v_name'] = $v_name;
						$data['agent'] = $agent;
						$data['conts'] = $conts;
						$data['allCont'] = $allCont;

						// for print
						$this->data['arrival_date'] = $arrival_date;
						$this->data['cl_date'] = $cl_date;
						$this->data['v_name'] = $v_name;
						$this->data['agent'] = $agent;
						$this->data['conts'] = $conts;
						$this->data['allCont'] = $allCont;
					}
					else
					{
						
						//echo "1";
						//return;
						// for print
						$this->data['save_flag'] = 1;
						
						$data['save_flag'] = 1;
						$unit = "";
						$arrival_date = "";
						$cl_date = "";
						$v_name = "";
						$agent = "";
						$query = "SELECT * ,
								( CASE
								 WHEN hous_bl IS NULL THEN igm_notify
								 WHEN hous_bl IS NOT NULL THEN igm_sup_notify	
								 END) AS Notify_name_,
								 ( CASE
								 WHEN hous_bl IS NULL THEN igm_notify_addr
								 WHEN hous_bl IS NOT NULL THEN igm_sup_notify_addr	
								 END) AS Notify_sup_name_

								 FROM ( SELECT DISTINCT IFNULL (auction_handover_lcl.house_bl, auction_handover_lcl.bl_no) AS bl_no, 
								 auction_handover_lcl.house_bl  AS hous_bl,  auction_handover_lcl.bl_no as master_bl,
								 rotation_no,vessel_name,arrival_date,cl_date,
								auction_handover_lcl.description_of_Goods, igm_details.notify_name,cont AS id,agent,size,
								height,TYPE,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,auction_handover_lcl.remarks,
								auction_handover_lcl.pack_Marks_Number,
								auction_handover_lcl.quantity,
								auction_handover_lcl.weight,
								auction_handover_lcl.pack_description,
								inv_unit_gkey AS gkey,entered_by,entry_ip,entry_time,
								igm_details.Notify_name AS igm_notify, 
								igm_details.Notify_address AS igm_notify_addr, 
								igm_supplimentary_detail.Notify_name AS igm_sup_notify, 
								igm_supplimentary_detail.Notify_address AS igm_sup_notify_addr, 

								IFNULL(igm_details.mlocode, '') AS  mlocode,

								(SELECT organization_profiles.Organization_Name FROM  organization_profiles
								WHERE organization_profiles.id = igm_supplimentary_detail.Submitee_Org_Id LIMIT 1) AS ff_name,
								(SELECT organization_profiles.Address_1 FROM  organization_profiles
								WHERE organization_profiles.id = igm_supplimentary_detail.Submitee_Org_Id LIMIT 1) AS ff_addr,
								igm_supplimentary_detail.BL_No AS house_bl
								FROM auction_handover_lcl 
								LEFT JOIN igm_details ON igm_details.BL_No=auction_handover_lcl.bl_no AND igm_details.Import_Rotation_No=auction_handover_lcl.rotation_no
								LEFT JOIN igm_supplimentary_detail ON  igm_supplimentary_detail.BL_No=auction_handover_lcl.house_bl AND igm_supplimentary_detail.Import_Rotation_No=auction_handover_lcl.rotation_no
								WHERE rotation_no='$rotation'  AND cont_status='LCL'
								GROUP BY bl_no ORDER BY rl_no ASC
								) AS tmp ";
						//echo $query; return;
					/* 	$query = "SELECT distinct bl_no, rotation_no,vessel_name,arrival_date,cl_date,pack_Marks_Number,
								description_of_Goods,notify_name,cont as id,agent,weight,quantity,size,
								height,type,cont_status,unit,rl_no,rl_date,obpc_number,obpc_date,last_pos_slot,remarks,
								inv_unit_gkey as gkey,entered_by,entry_ip,entry_time
								FROM auction_handover 
								WHERE rotation_no='$rotation' 
								GROUP BY bl_no ORDER BY rl_no"; */
							/* $query = "SELECT * FROM( SELECT * FROM (SELECT shed_tally_info.id, shed_tally_info.igm_detail_id AS ig_id, igm_masters.Vessel_Name, 
								BL_No, import_rotation AS Import_Rotation_No, shed_tally_info.cont_number,
								cont_size, igm_detail_container.cont_status, 
								Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info
								INNER JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
								INNER JOIN  igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE  DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1


								UNION

								SELECT shed_tally_info.id,  shed_tally_info.igm_sup_detail_id AS ig_id, igm_masters.Vessel_Name, 
								igm_supplimentary_detail.BL_No,import_rotation AS Import_Rotation_No,
								shed_tally_info.cont_number, 
								cont_size, igm_sup_detail_container.cont_status, 
								igm_supplimentary_detail.Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info 
								INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_sup_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1 
								)tbl1 WHERE Import_Rotation_No='$rotation' ) AS tmp WHERE lying_days> 30 ORDER BY id ASC ";	 */
								// echo $query;return;
						$result = $this->bm->dataSelectDb1($query);
						
						for($x=0;$x<count($result);$x++)
							{
								$unit=$result[$x]['unit'];	
								$arrival_date=$result[$x]['arrival_date'];	
								$cl_date=$result[$x]['cl_date'];	
								$v_name=$result[$x]['vessel_name'];	
								$agent=$result[$x]['agent'];
							}
						//print_r($containerArray);
						$queryCont = "SELECT cont FROM auction_handover_lcl WHERE rotation_no='$rotation'";
						$resultCont = $this->bm->dataSelectDb1($queryCont);
						for($y=0;$y<count($resultCont);$y++)
							{	
								$containers = "'".$resultCont[$y]['cont']."'";
								array_push($containerArray,$containers);
							}						
						$allCont = implode(",",$containerArray);
						
						$data['unit'] = $unit;
						$data['arrival_date'] = $arrival_date;
						$data['cl_date'] = $cl_date;
						$data['v_name'] = $v_name;
						$data['agent'] = $agent;
						$data['allCont'] = $allCont;

						// for print
						$this->data['unit'] = $unit;
						$this->data['arrival_date'] = $arrival_date;
						$this->data['cl_date'] = $cl_date;
						$this->data['v_name'] = $v_name;
						$this->data['agent'] = $agent;
						$this->data['allCont'] = $allCont;
					}
					$queryBL = "SELECT DISTINCT igm_details.BL_No
						FROM cchaportdb.igm_details
						INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
						INNER JOIN shed_tally_info ON igm_details.id=shed_tally_info.igm_detail_id
						WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' AND  shed_tally_info.delivery_status!=1 
						AND cchaportdb.igm_detail_container.cont_number IN ($allCont)
						UNION 
						SELECT DISTINCT igm_supplimentary_detail.BL_No
						FROM cchaportdb.igm_supplimentary_detail
						INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
						INNER JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE cchaportdb.igm_supplimentary_detail.Import_Rotation_No='$rotation' AND shed_tally_info.delivery_status!=1
						AND cchaportdb.igm_sup_detail_container.cont_number IN ($allCont)";
					$resultBL = $this->bm->dataSelectDb1($queryBL);
					
					$cntResult = count($resultBL);
					$data['result']=$result;
					$data['cntResult']=$cntResult;
					$data['resultBL']=$resultBL;

					// for print
					$this->data['result']=$result;
					$this->data['cntResult']=$cntResult;
					$this->data['resultBL']=$resultBL;
				}

				if($action == "print")
				{
					$this->load->library('m_pdf');	
					$html=$this->load->view('AuctionHandOverReportPrintLCL',$this->data, true); 
					$pdfFilePath ="Auction Handover Report-".time()."-download.pdf";
					$pdf = $this->m_pdf->load();
					$pdf = new mPDF('c', 'A4-L');
					$pdf->useSubstitutions = true; 
					$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
					$pdf->WriteHTML($html,2);
					$pdf->Output($pdfFilePath, "I");

					//$this->load->view('AuctionHandOverReportPrint',$data);
				}
				else
				{
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');				
					$this->load->view('AuctionHandOverReportFormLCL',$data);
					$this->load->view('jsAssets');
				}				
			}
		}
		
		
		
				// Auction Notice lcl -- Start	
		
		function AuctionHandNoticeGenerationLCL()
		{
			$session_id = $this->session->userdata('value');			
			$LoginStat = $this->session->userdata('LoginStat');		
			if($LoginStat!="yes")
			{
				$this->logout();				
			}
			else
			{	
				$this->load->library('m_pdf');

				$rotation = $this->input->post("rotation");
				$cl_date = $this->input->post("cl_dt");
				$arriv_dt = $this->input->post("arriv_dt");
				
				/* $query = "SELECT * FROM (
				SELECT sparcsn4.inv_unit.gkey,inv_unit.id,inv_unit.freight_kind,
				sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
				sparcsn4.ref_bizunit_scoped.id AS agent,
				TIMESTAMPDIFF(DAY,sparcsn4.inv_unit_fcy_visit.time_in, NOW()) AS lying_days,
				sparcsn4.vsl_vessel_visit_details.ib_vyg AS rot_no,sparcsn4.vsl_vessels.name AS v_name,
				RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
				RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height,
				sparcsn4.ref_equip_type.id AS TYPE,
				DATE(sparcsn4.argo_carrier_visit.ata) AS ata,
				
				(SELECT DATE(time_discharge_complete)
				FROM sparcsn4.vsl_vessel_visit_details
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 LIMIT 1) AS cl_dt,
		
				sparcsn4.inv_unit_fcy_visit.last_pos_slot,
				inv_unit_fcy_visit.flex_string04 AS rl_no,inv_unit_fcy_visit.flex_string05 AS rl_date,
				inv_unit_fcy_visit.flex_string07 AS obpc_number,inv_unit_fcy_visit.flex_string08 AS obpc_date,
				inv_unit_fcy_visit.time_in, inv_unit_fcy_visit.time_out,'' AS remarks
				FROM sparcsn4.inv_unit
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
				INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
				INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
				INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
				INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
				INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
				WHERE sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD' AND sparcsn4.inv_unit.category='IMPRT' 
				AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotation'
				) AS tbl WHERE lying_days>=30"; */
				
				$query="SELECT * FROM( SELECT * FROM (SELECT shed_tally_info.id, shed_tally_info.igm_detail_id AS ig_id, igm_masters.Vessel_Name, 
								BL_No, import_rotation AS Import_Rotation_No, shed_tally_info.cont_number,
								cont_size, igm_detail_container.cont_status, 
								Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info
								INNER JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
								INNER JOIN  igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE  DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1


								UNION

								SELECT shed_tally_info.id,  shed_tally_info.igm_sup_detail_id AS ig_id, igm_masters.Vessel_Name, 
								igm_supplimentary_detail.BL_No,import_rotation AS Import_Rotation_No,
								shed_tally_info.cont_number, 
								cont_size, igm_sup_detail_container.cont_status, 
								igm_supplimentary_detail.Pack_Number, shed_tally_info.last_update, shed_tally_info.update_by, Cont_gross_weight,
								TIMESTAMPDIFF(DAY, shed_tally_info.last_update, NOW()) AS lying_days
								FROM shed_tally_info 
								INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
								INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
								INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
								WHERE DATE(shed_tally_info.last_update) BETWEEN CURDATE() - INTERVAL 40 DAY AND DATE(NOW())
								AND shed_tally_info.igm_sup_detail_id IS NOT NULL AND shed_tally_info.delivery_status!=1 
								)tbl1 WHERE Import_Rotation_No='$rotation' ) AS tmp WHERE lying_days> 30 ORDER BY id ASC ";
						$result = $this->bm->dataSelectDb1($query);
						$conts='';
						$containerArray=array();
						$blArray=array();
						for($x=0;$x<count($result);$x++)
						{
							$v_name = $result[$x]['Vessel_Name'];
							//$conts = "'".implode("','", $result[$x]['id'])."'";
							$containers = "'".$result[$x]['cont_number']."'";
							$bls = "'".$result[$x]['BL_No']."'";
							array_push($containerArray,$containers);
							array_push($blArray,$bls);
						}
						$allCont = implode(",",$containerArray);
						$allbl = implode(",",$blArray);
						
/* 						ECHO $igm_sql ="SELECT DISTINCT igm_details.BL_No, igm_details.Notify_name, igm_details.Notify_address  
									FROM cchaportdb.igm_details
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id
									WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' AND 
									cchaportdb.igm_detail_container.cont_number IN ($blArray)";	 */	
						$igm_sql ="SELECT igm_detail_container.cont_number,igm_details.Description_of_Goods,igm_details.Pack_Marks_Number,igm_details.BL_No,
									igm_detail_container.cont_status,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Notify_name, igm_details.Notify_address, 
									igm_detail_container.cont_imo,igm_detail_container.cont_un, igm_details.Pack_Number, igm_details.Pack_Description,
									igm_details.Notify_name, igm_details.Notify_address									
									FROM cchaportdb.igm_details 
									INNER JOIN cchaportdb.igm_detail_container ON cchaportdb.igm_detail_container.igm_detail_id=cchaportdb.igm_details.id 
									LEFT JOIN cchaportdb.igm_supplimentary_detail ON cchaportdb.igm_details.id=cchaportdb.igm_supplimentary_detail.igm_sup_detail_id
									WHERE cchaportdb.igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No IN ($allbl)
									
									UNION
									
									SELECT igm_sup_detail_container.cont_number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Marks_Number, igm_supplimentary_detail.BL_No,
									igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,igm_supplimentary_detail.Notify_name,
									igm_supplimentary_detail.Notify_address, igm_sup_detail_container.cont_imo,igm_sup_detail_container.cont_un, igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
									igm_supplimentary_detail.Notify_name, igm_supplimentary_detail.Notify_address
									FROM cchaportdb.igm_supplimentary_detail 
									INNER JOIN cchaportdb.igm_sup_detail_container ON cchaportdb.igm_sup_detail_container.igm_sup_detail_id=cchaportdb.igm_supplimentary_detail.id
									LEFT JOIN cchaportdb.igm_details ON cchaportdb.igm_supplimentary_detail.igm_detail_id=cchaportdb.igm_details.id 
									WHERE cchaportdb.igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_supplimentary_detail.BL_No IN ($allbl)";
						$notice_List = $this->bm->dataSelectDb1($igm_sql);

						
				
				/* $sql = "SELECT  auction_handover.bl_no, auction_handover.notify_name, auction_handover.vessel_name, igm_details.Notify_address
				FROM auction_handover 
				LEFT JOIN igm_details ON auction_handover.bl_no=igm_details.BL_No AND auction_handover.rotation_no=igm_details.Import_Rotation_No
				WHERE auction_handover.rotation_no='$rotation' ORDER BY rl_no ASC";	
			
				$notice_List = $this->bm->dataSelectDb1($sql); */
				
				$this->data['v_name']=$v_name;
				$this->data['notice_List']=$notice_List;
				$this->data['rotation']=$rotation;
				$this->data['arrival_dt']=$arriv_dt;
				$this->data['cl_date']=$cl_date; 
			/* 	
				$data['v_name']=$v_name;
				$data['notice_List']=$notice_List;
				$data['rotation']=$rotation;
				$data['arrival_dt']=$arriv_dt;
				$data['cl_date']=$cl_date;  */
				
				
			//	$this->load->view('AuctionHandNoticeLCL',$data); 			 
				$html=$this->load->view('AuctionHandNoticeLCL',$this->data,true); 			 
				$pdfFilePath ="AuctionHandNotice-".time()."-download.pdf";



				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('resources/styles/lcl.css'); 
				
				
				$pdf->useSubstitutions = true; 
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I");    
				
			}
		}
		// Auction Handover  -- End
		
		
		function AuctionHandOverReportListLCL()
		{
			$session_id = $this->session->userdata('value');			
			$LoginStat = $this->session->userdata('LoginStat');		
			if($LoginStat!="yes")
			{
				$this->logout();				
			}
			else
			{	
				$data['title']="AUCTION HANDOVER LIST(LCL)";
				if($this->input->post("search")=="0")
				{
					$unit = $this->input->post("unit");
					$from_date = $this->input->post("from_date");
					$to_date = $this->input->post("to_date");
					$search = $this->input->post("search");
					$data['unit']=$unit;
					$data['from_date']=$from_date;
					$data['to_date']=$to_date;
					$data['search']=1;
					$sql = "SELECT DISTINCT(rotation_no),arrival_date,cl_date,unit 
							FROM auction_handover_lcl 
							WHERE unit='$unit' AND auction_handover_lcl.cont_status='LCL' AND (DATE(entry_time) BETWEEN '$from_date' AND '$to_date')
							ORDER BY id DESC";	
				}
				else
				{
					$data['search']=0;							
					$sql = "SELECT DISTINCT(rotation_no),arrival_date,cl_date,unit FROM auction_handover_lcl WHERE auction_handover_lcl.cont_status='LCL' ORDER BY id DESC";
				}
				$auction_handover_List = $this->bm->dataSelectDb1($sql);
				$data['auction_handover_List']=$auction_handover_List;
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('AuctionHandoverListLCL',$data);
				$this->load->view('jsAssetsList');
				
			}
		}
		
		
	function auctionUnitChangesForm()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');		
		if($LoginStat!="yes")
		{
			$this->logout();				
		}
		else
		{
			$data['title']="AUCTION UNIT CHANGE";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('auctionUnitChangesForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function auctionUnitChanges()
	{
		$session_id = $this->session->userdata('value');			
		$LoginStat = $this->session->userdata('LoginStat');		
		if($LoginStat!="yes")
		{
			$this->logout();				
		}
		else
		{
			$rotation = $this->input->post("rotation");
			$unit = $this->input->post("unit");
			
			$year = substr($rotation,2,2);

			$rl_beginning = $unit.$year;
			$rl_no = "";

			$totRl=0;
			$totRlwithBL=0;
			
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];	
			
			$strCntRl = "SELECT id, bl_no, unit FROM auction_handover WHERE rotation_no='$rotation'";						
			$totRl=$this->bm->dataSelectDb1($strCntRl);
			$stat=0;
			if(count($totRl)>0)
			{
				for($k=0;$k<count($totRl); $k++)
				{
					$handOvr_id=$totRl[$k]['id'];
					$bl_no=$totRl[$k]['bl_no'];
					$prv_unit=$totRl[$k]['unit'];
					$strRl = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rl_no LIKE '%$rl_beginning%'";						
					$rlRslt=$this->bm->dataReturnDb1($strRl);
					if($rlRslt==0)
					{
						// No RL for the combination of 'year of rotation' and 'unit', so this will be the first RL for this unit-year combination...
						$rl_no = $rl_beginning."000001";
					}
					else
					{ 
						$strCntRlwithBL = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no'";
						$totRlwithBL=$this->bm->dataReturnDb1($strCntRlwithBL);	
						if($totRlwithBL==0)
						{
							//No RL for the given BL...
							$str_new_rl = "SELECT MAX(RIGHT(rl_no,6))+1 AS rtnValue FROM auction_handover 
									WHERE rl_no LIKE '%$rl_beginning%' ORDER BY id DESC LIMIT 1";
							$new_rl=$this->bm->dataReturnDb1($str_new_rl);
							$rl_no = $rl_beginning.str_pad((string)$new_rl, "6", "0", STR_PAD_LEFT); 
						}
						else
						{
							//There is already some/one RL for the given BL...
							$str_new_rl = "SELECT rl_no AS rtnValue FROM auction_handover 
									WHERE rl_no LIKE '%$rl_beginning%' AND bl_no='$bl_no' ORDER BY id DESC LIMIT 1";
							$new_rl=$this->bm->dataReturnDb1($str_new_rl);
							$rl_no = $new_rl; 
						}
					} 
											
					$updateStr="UPDATE auction_handover SET unit='$unit', rl_no ='$rl_no' WHERE id='$handOvr_id'";	
					$stat = $this->bm->dataUpdateDB1($updateStr);
					$insertLogStr="insert into auction_unit_update_log(handovr_id, rot_no, prv_unit, cur_unit, update_by, update_time,ip_addr)
									values('$handOvr_id', '$rotation', '$prv_unit', '$unit', '$login_id', NOW(), '$ipaddr')";
					$nsert_stat = $this->bm->dataInsertDB1($insertLogStr);				
				}
				
				if($stat>0)
				{
				   $data['msg'] = "<font color='blue'><strong>Data updated succesfully.</strong></font>";
				}
				else
				{
					 $data['msg'] = "<font color='red'><strong>Data not updated.</strong></font>";
				}
			}
				
			else
			{
				 $data['msg'] = "<font color='red'><strong>Rotation No: $rotation not found in Handover List.</strong></font>";
			}
			
			
			
			$data['title']="AUCTION UNIT CHANGE";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('auctionUnitChangesForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	

}
        