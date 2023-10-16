<?php

class gateController extends CI_Controller {
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
        
        function index(){
		 $this->gate();
        }
		
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
		
		
		function gateOut()
		{
		    $session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title']="GATE OUT...";
				//echo $data['title'];
				$data['tableFlag']=0;
				$data['verifyNo']=null;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateOutView',$data);
				$this->load->view('jsAssets');
			}	
		}
		
		
		
		function gateOutView()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
			$verifyNo=$this->input->post('verifyNo');
			//echo $rotNo;	
				$tableFlag=1;
			
				$strSelect="select import_rotation,cont_number,rcv_pack,flt_pack,shed_loc,if(loc_first=1,'Yes','No') as loc_first,Pack_Number,Pack_Description,weight,Notify_name,
				Notify_address, Consignee_name from shed_tally_info inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id 
				where verify_number='$verifyNo'";
							
				$stat = $this->bm->dataSelectDb1($strSelect);
				
				if($stat>=1)
				{
					$verifyStatusList=$stat;
				}
				else
				{
				$strSelect="select import_rotation,cont_number,rcv_pack,flt_pack,shed_loc,if(loc_first=1,'Yes','No') as loc_first,Pack_Number,Pack_Description,weight,Notify_name,
				Notify_address, Consignee_name from shed_tally_info inner join igm_details on igm_details.id=shed_tally_info.igm_detail_id
				where verify_number='$verifyNo'"; 
				$stat = $this->bm->dataSelectDb1($strSelect);
				$verifyStatusList=$stat;
				
				}
					
					
				// For chalan view by verify number---------
				$sql_contStatus="SELECT cont_status AS rtnValue
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				WHERE verify_number='$verifyNo'
				GROUP BY igm_sup_detail_container.id

				UNION

				SELECT cont_status AS rtnValue
				FROM  igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				inner join verify_info_fcl on verify_info_fcl.igm_detail_id=igm_details.id
				WHERE verify_number='$verifyNo'
				GROUP BY igm_detail_container.id";
				$contStatus=$this->bm->dataReturnDb1($sql_contStatus);
				
				if($contStatus=="LCL")
				{
					$goodsDesStr="select Description_of_Goods
					from igm_supplimentary_detail
					inner join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
					where shed_tally_info.verify_number='$verifyNo'";
				}
				else if($contStatus=="FCL")
				{
					$goodsDesStr="select Description_of_Goods
					from igm_details
					inner join verify_info_fcl on verify_info_fcl.igm_detail_id=igm_details.id
					where verify_number='$verifyNo'";
				}
				
				$result2 = $this->bm->dataSelectDb1($goodsDesStr);
				$goodsDes= $result2[0]['Description_of_Goods'];
				$data['goodsDes']=$goodsDes;
				$DesStr="select truck_id, delv_pack, remarks from do_information where verify_no ='$verifyNo'";
				$result3 = $this->bm->dataSelectDb1($DesStr);
				
				$data['result3']=$result3;
				$data['tableFlag']=1;
				// For chalan view by verify number---------END	 
				
				$data['verifyStatusList']=$verifyStatusList; 
				$data['tableFlag']=$tableFlag;
				$data['title']="GATE OUT...";
				$data['verifyNo']=	$verifyNo;	
				$data['tableTitle']="<font color=green>GATE OUT FOR VERIFY NO:</font> <font color=blue size=4><b>".$verifyNo."</b></font>";
				
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('gateOutView',$data);
			$this->load->view('jsAssets');
		
			}
		
		}
		
		
		function chalan()
		{			
		    $verifyNo=$this->input->post('verifyNo');
			$notifyName=$this->input->post('notifyName');
			$notifyAddress=$this->input->post('notifyAddress');
			$stat=$this->input->post('stat');	
				
		    $chalanStr1="SELECT cnf_lic_no FROM verify_other_data 
						INNER JOIN shed_tally_info ON shed_tally_info.id = verify_other_data.shed_tally_id 
						WHERE UCASE(shed_tally_info.verify_number)=UCASE('$verifyNo')";								
			$result1 = $this->bm->dataSelectDb1($chalanStr1);
			$cntResult1 = count($result1);
			if($cntResult1!=0)
			{
				$CNFLicenceNo=$result1[0]['cnf_lic_no'];
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1
						 FROM inv_unit 
						 INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
						 LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
						 WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
				$CNFresult = $this->bm->dataSelect($CNFStr1);
				$cnfName=$CNFresult[0]['NAME'];
				$cnfAddress1=$CNFresult[0]['ADDRESS_LINE1'];
				
				$this->data['cnfName']=$cnfName;   // CNF Name
				$this->data['cnfAddress1']=$cnfAddress1;  // CNF Address
			}
			else
			{
				$sql_cnfInfo = "SELECT dec_code,dec_name
				FROM sad_info
				INNER JOIN verify_info_fcl ON verify_info_fcl.be_no=sad_info.reg_no
				WHERE  UCASE(verify_number)=UCASE('$verifyNo')";
				$rslt_cnfInfo = $this->bm->dataSelectDb1($sql_cnfInfo);
				
				$lic_p1 = substr($rslt_cnfInfo[0]['dec_code'],5);	
				$lic_p2 = substr($rslt_cnfInfo[0]['dec_code'],3,2);
				$CNFLicenceNo = $lic_p1."/".$lic_p2;
				$CNFLicenceNo = ltrim($CNFLicenceNo, '0');
				
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1
						 FROM inv_unit 
						 INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
						 LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
						 WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
						 
				$CNFresult = $this->bm->dataSelect($CNFStr1);
				$cnfName=$CNFresult[0]['NAME'];
				$cnfAddress1=$CNFresult[0]['ADDRESS_LINE1'];
				
				$this->data['cnfName']=$cnfName;   // CNF Name
				$this->data['cnfAddress1']=$cnfAddress1;  // CNF Address
			}
					 
		   $goodsDesStr="SELECT Description_of_Goods 
						FROM igm_supplimentary_detail 
						INNER JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE UCASE(shed_tally_info.verify_number)=UCASE('$verifyNo')
						
						UNION 

						SELECT Description_of_Goods 
						FROM igm_details 
						INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
						WHERE UCASE(verify_info_fcl.verify_number)=UCASE('$verifyNo')";
			$result2 = $this->bm->dataSelectDb1($goodsDesStr);
			$goodsDes= $result2[0]['Description_of_Goods'];
				 
			//--
			if($stat==1)
			{
				$DesStr="SELECT id,truck_id, delv_pack, remarks FROM cchaportdb.do_information 
					WHERE verify_no =UCASE('$verifyNo') AND delv_status=1
					union 
					SELECT id, truck_id,delv_pack, '' as remarks FROM cchaportdb.do_truck_details_entry WHERE verify_number=UCASE('$verifyNo')";
				$count=2;
			}				
			else
			{
				$DesStr="select id, truck_id, delv_pack, remarks from cchaportdb.do_information where verify_no = UCASE('$verifyNo')
						UNION 
						SELECT id, truck_id,delv_pack, '' as remarks FROM cchaportdb.do_truck_details_entry WHERE verify_number=UCASE('$verifyNo')";
				$count=1;
			}
			$result3 = $this->bm->dataSelectDb1($DesStr);
			
			$login_id = $this->session->userdata('login_id');
				  
			$this->data['result3']=$result3;
			
			$this->data['verifyNo']=$verifyNo;	 
			
			$this->data['notifyName']=$notifyName;
		    $this->data['notifyAddress']=$notifyAddress;
			$this->data['goodsDes']=$goodsDes; // Goods description
			$this->data['count']=$count; 
			
			$this->load->library('m_pdf');
			$html=$this->load->view('chalanView',$this->data, true);			 
			$pdfFilePath ="chalanView-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
		
		//Gate Confirmation start
		function gateConfirmation()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['msg']="";
				$data['title']="Gate Confirmation";
				$data['verify_num']="";           
				$data['reg_no']="";
				$data['vessel_name']="";
				$data['des_goods']="";
				$data['mlo_line']="";
				$data['quantity']="";
				$data['mlo_code']="";
				$data['unit']="";
				$data['importer_name']="";
				$data['ffw_code']="";
				$data['cnf']="";
				$data['be_no']="";
				$data['be_no']="";
				$data['be_date']="";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateConfirmation',$data);
				$this->load->view('jsAssets');		 
			}	
		}
		
		function gateConfirmationPerform()
	{	
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$verify_num=$this->input->post('verify_num');
			$tro=$this->input->post('tro');
			$truckupdate="UPDATE do_information SET delv_status='1' WHERE truck_id='$tro' AND verify_no='$verify_num'";			
			$statechange = $this->bm->dataInsertDB1($truckupdate);
			if($statechange==1)
			{
				$data['msg'] ="<font color='green'><b>Successfully Inserted.</b></font>";
				$data['verify_num'] =$verify_num;
			}
			
			//no empty truck start
		//	$query = "SELECT truck_id FROM do_information WHERE verify_no='$verify_num' AND delv_status='0'";
			$query = "SELECT COUNT(truck_id) AS rtnValue
			FROM do_information
			WHERE verify_no='$verify_num' AND delv_status='0'";
	   
			$rtntrkno = $this->bm->dataReturnDb1($query);
			//$rtntrkno = 1; //Temporary use only
			if($rtntrkno==0)
			{
				//write pdf start
				//$this->load->library('m_pdf');
				//$mpdf->use_kwt = true;
				
				//////--------cartticket starts------------------------------------------------
				$login_id = $this->session->userdata('login_id');
				
				$sqlTruckNumber="select verify_no,truck_id,gate_no,delv_pack
				from do_information
				where verify_no='$verify_num'";
				
				$rtnTruckNumber = $this->bm->dataSelectDb1($sqlTruckNumber);
			
				$this->data['rtnTruckNumber']=$rtnTruckNumber;		
				$this->data['verifyNo']=$verify_num;
				
				$this->load->library('m_pdf');
				$html=$this->load->view('pdfWriteTest',$this->data, true);
				$pdfFilePath =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/pdfsend/".$verify_num."_cartTicket.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css');
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);					 
				$pdf->Output($pdfFilePath, "F");
				
				// $this->load->library('m_pdf');				
				// $pdfFilePath =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/pdfsend/".$verify_num."_cartTicket.pdf";
				// $pdf = $this->m_pdf->load();
				// $pdf->allow_charset_conversion = true;
				// $pdf->charset_in = 'iso-8859-4';
				// $stylesheet = file_get_contents('assets/stylesheets/test.css');			
				// $pdf->WriteHTML($stylesheet,1);
				// $pdf->WriteHTML($html,2);				
				// $pdf->Output($pdfFilePath, "D"); // To save Pdf; to show and download use "I" and "D" respectively
				//////--------cartticket ends-----------------------------------------
				
				/////--------bill start-----------------------------------------------
				$strBankPaymentInfo = "select shed_bill_master.bill_no,bill_rcv_stat,cp_bank_code,concat(cp_bank_code,cp_unit,'/',right(cp_year,2),'-',concat(if(length(cp_no)=1,'000',if(length(cp_no)=2,'00',if(length(cp_no)=3,'0',''))),cp_no)) as cp_no
				from shed_bill_master 
				inner join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
				where verify_no='$verify_num'";
		
				$rtnBankPaymentInfo = $this->bm->dataSelectDb1($strBankPaymentInfo);
				$rcvstat=$rtnBankPaymentInfo[0]['bill_rcv_stat'];
				$cpnoview=$rtnBankPaymentInfo[0]['cp_no'];
				$cpbankcode=$rtnBankPaymentInfo[0]['cp_bank_code'];
				$shedbill=$rtnBankPaymentInfo[0]['bill_no'];
		
				if($cpbankcode=="OB")
					$cpbankname="ONE BANK LIMITED";
		
				$str="select bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,
				cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,
				be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height
				from shed_bill_master 
				where verify_no='$verify_num'";
				
				$rtnContainerList = $this->bm->dataSelectDb1($str);
				$unit_no=$rtnContainerList[0]['unit_no'];
				$cpa_vat_reg_no=$rtnContainerList[0]['cpa_vat_reg_no'];
				$ex_rate=$rtnContainerList[0]['ex_rate'];
		
				$qry="select verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK
				from shed_bill_details
				where verify_no='$verify_num'";
				
				$chargeList = $this->bm->dataSelectDb1($qry); 
			
				$qry_sum="select SUM(amt) as amt from shed_bill_details where verify_no='$verify_num'";
				
				$sumAll = $this->bm->dataSelectDb1($qry_sum);
				$tot_sum=$sumAll[0]['amt'];
			
				$qry_qday="select IFNULL(SUM(qday),0) as qday 
				from shed_bill_details 
				where verify_no='$verify_num' AND gl_code not in('501005','502000N','503000N')";
				
				$qdayAll = $this->bm->dataSelectDb1($qry_qday);
				$tot_qday=$qdayAll[0]['qday'];
			
				$sqlrcvdate="SELECT recv_by,DATE(recv_time) AS recv_time 
				FROM bank_bill_recv 
				WHERE bill_no='$shedbill'";
				$rtnrcvdate = $this->bm->dataSelectDb1($sqlrcvdate);
		
				$recv_by=$rtnrcvdate[0]['recv_by'];
				$recv_time=$rtnrcvdate[0]['recv_time'];
		
				$this->data['rtnContainerList']=$rtnContainerList;
				$this->data['chargeList']=$chargeList;

				$this->data['title']="Shed Bill";
				$this->data['verify_number']=$verify_num;
				$this->data['rcvstat']=$rcvstat;
				$this->data['cpnoview']=$cpnoview;
				$this->data['cpbankname']=$cpbankname;
				$this->data['recv_time']=$recv_time;
				$this->data['recv_by']=$recv_by;
				$this->data['tot_sum']=$tot_sum;
				$this->data['tot_qday']=$tot_qday;
				$this->data['bill_print_times']=1;  //for email
		 
				$this->data['unit_no']=$unit_no;
				$this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
				$this->data['ex_rate']=$ex_rate;
				
				$this->load->library('m_pdf');
				$html=$this->load->view('shedBillPdfOutput',$this->data, true);
				$pdfFilePathBill =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/pdfsend/".$verify_num."_bill.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css');
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);					 
				$pdf->Output($pdfFilePathBill, "F");
				
				// $this->load->library('m_pdf');
				// $pdfFilePath_Bill =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/pdfsend/".$verify_num."_bill.pdf";
				// $pdf = $this->m_pdf->load();
				// $pdf->allow_charset_conversion = true;
				// $pdf->charset_in = 'iso-8859-4';
				// $stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css				
				// $pdf->WriteHTML($stylesheet,1);
				// $pdf->WriteHTML($html,2);			 
				// $pdf->Output($pdfFilePath_Bill, "F");
						
		
				// $html=$this->load->view('shedBillPdfOutput',$this->data, true); 		 
				// $pdfFilePath_Bill =$_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/pdfsend/".$verify_num."_bill.pdf";
				// $pdf = $this->m_pdf->load();
				// $pdf->SetWatermarkText('CPA CTMS');
				// $pdf->showWatermarkText = true;	
				// $stylesheet = file_get_contents('resources/styles/shedBill.css');
				// $pdf->useSubstitutions = true;				
				// $pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');				
				// $pdf->WriteHTML($stylesheet,1);
				// $pdf->WriteHTML($html,2);				 
				// $pdf->Output($pdfFilePath_Bill, "F");
				/////--------bill end-------------------------------------------------------------------
					
				////--------release order start---------------------------------------------------------
				$strBill="select igm_supplimentary_detail.id,IFNULL(sum(rcv_pack+loc_first),0) as rcv_pack,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) as verify_number,IFNULL(shed_tally_info.id,0) as verify_id,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,igm_sup_detail_container.cont_height,bank_bill_recv.bill_no,bank_bill_recv.cp_no,RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,date(bank_bill_recv.recv_time) as cp_date,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.Line_No,total_port,concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as master_bill_no,shed_bill_master.bill_date,VoyNo,verify_other_data.exit_note_number
				from  igm_supplimentary_detail
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
				left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
				left join shed_bill_master on shed_bill_master.verify_no=shed_tally_info.verify_number
				left join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
				left join vessels_berth_detail on shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
				where shed_tally_info.verify_number='$verify_num' limit 1";
				
				$rtnContainerList = $this->bm->dataSelectDb1($strBill);
		
				$this->data['rtnContainerList']=$rtnContainerList;
		
				$strBillRcvInfo="select description,gl_code 
					from shed_bill_details 
					inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
					where shed_bill_master.verify_no='$verify_num'";
									
				$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
				$this->data['rtnBillRcvInfo']=$rtnBillRcvInfo;
				
				$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,
				cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,
				be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat, 
				if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status 
				from shed_bill_master where verify_no='$verify_num'"; 
		
				$rtnBillList = $this->bm->dataSelectDb1($str);
		
				$unit_no=$rtnBillList[0]['unit_no'];
				$cpa_vat_reg_no=$rtnBillList[0]['cpa_vat_reg_no'];
				$ex_rate=$rtnBillList[0]['ex_rate'];
				$bill_rcv_stat=$rtnBillList[0]['bill_rcv_stat'];
			
				$this->data['rtnBillList']=$rtnBillList;
	
				$this->data['title']="Shed Bill";
				$this->data['verify_number']=$verify_num;
				$this->data['tot_sum']=$tot_sum;
			
						 
				$this->data['unit_no']=$unit_no;
				$this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
				$this->data['ex_rate']=$ex_rate;
				$this->data['bill_rcv_stat']=$bill_rcv_stat;
				$this->data['cpnoview']=$cpnoview;
			
				$this->data['recv_time']=$recv_time;
				$this->data['recv_by']=$recv_by;
				$this->data['billPrepareBy']=$billPrepareBy;
				$this->data['bill_print_times']=4;
				$this->data['login_id']=$login_id;
							
				$this->data['verifyNo']=$verify_num;		
				
				$this->load->library('m_pdf');
				$html=$this->load->view('releaseOrderFormViewPDF',$this->data, true);
				$pdfFilePathReleaseOrder =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/pdfsend/".$verify_num."_releaseorder.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css');
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);					 
				$pdf->Output($pdfFilePathReleaseOrder, "F");
				
				
				// $html=$this->load->view('releaseOrderFormViewPDF',$this->data, true);		 
				// $pdfFilePath_releaseorder =$_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/pdfsend/".$verify_num."_releaseorder.pdf";
				// $pdf = $this->m_pdf->load();
				// $pdf->SetWatermarkText('CPA CTMS');
				// $pdf->showWatermarkText = true;	
				// $stylesheet = file_get_contents('resources/styles/releaseorder.css'); // external css						
				// $pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');						
				// $pdf->WriteHTML($stylesheet,1);
				// $pdf->WriteHTML($html,2);						 
				// $pdf->Output($pdfFilePath_releaseorder, "F"); // For Show Pdf
					//--------release order end
					
				//write pdf end
				//sending mail start----------------------------
				//include_once("SendEmailController.php");
				//require_once('mailer/class.smtp.php');
				
				//$subject="Shedbill";
				//$body="Please check the attached files.";
			//	$emailClient="intakhab.alam@datasoft-bd.com";
			//	$emailClient="intakhab.chy@gmail.com";
			//	$emailClient="shahjahan@datasoft-bd.com";
			//	$emailClient="shahscjp@gmail.com";
			
			
				//$emailClient="intakhab.chy@gmail.com";				
				//$sendEmailController =new sendEmailController();				
				//$sendEmail=$sendEmailController->sendEmail($subject,$body,$emailClient,$pdfFilePath_cartTicket,$pdfFilePath_Bill,$pdfFilePath_releaseorder);

				//sending mail end
				
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateConfirmation',$data);
				$this->load->view('jsAssets');
				return;
			}
			
			//no empty truck end
			
				//query from ajax
				$str_reload="SELECT * FROM (SELECT igm_supplimentary_detail.id,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,IFNULL(shed_tally_info.verify_number,0) AS verify_number,verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,igm_details.Consignee_name,igm_supplimentary_detail.Pack_Description,igm_details.BL_No AS mloline,igm_supplimentary_detail.BL_No AS ffwline,(SELECT mlocode FROM igm_details 
				INNER JOIN igm_supplimentary_detail sdtl ON sdtl.igm_detail_id=igm_details.id
				WHERE sdtl.id=igm_supplimentary_detail.id) AS mlocode,igm_supplimentary_detail.Pack_Number,(SELECT igm_supplimentary_detail.Pack_Number-IFNULL((SELECT SUM(delv_pack) AS delv_pack FROM do_information WHERE verify_no='$verify_num' AND delv_status=1),0)) AS bal_pack,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address
				FROM  igm_supplimentary_detail
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
				LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id 
				LEFT JOIN do_information ON do_information.verify_no=shed_tally_info.verify_number
				WHERE shed_tally_info.verify_number='$verify_num') AS tbl ORDER BY id ASC LIMIT 1";
				
				$rslt_reload = $this->bm->dataSelectDb1($str_reload);
				if(count($rslt_reload)>0)
				{
					$reg_no=$rslt_reload[0]['Import_Rotation_No'];
					$vessel_name=$rslt_reload[0]['Vessel_Name'];
					$marks=$rslt_reload[0]['Pack_Marks_Number'];
					$des_goods=$rslt_reload[0]['Description_of_Goods'];
					$quantity=$rslt_reload[0]['Pack_Number'];
					$mlo_line=$rslt_reload[0]['mloline'];
					$mlo_code=$rslt_reload[0]['mlocode'];
					$ffw_line=$rslt_reload[0]['ffwline'];
					$unit=$rslt_reload[0]['Pack_Description'];
					$cnf=$rslt_reload[0]['cnf_name'];
					$importer_name=$rslt_reload[0]['Consignee_name'];
					$be_no=$rslt_reload[0]['be_no'];
					$be_date=$rslt_reload[0]['be_date'];
					$dlv_qty=$rslt_reload[0]['bal_pack'];		//if $dlv_qty==0 then send mail  
					$notifyName=$rslt_reload[0]['Notify_name'];
					$notifyAddress=$rslt_reload[0]['Notify_address'];
					
					$data['vessel_name'] =$vessel_name;
					$data['reg_no'] =$reg_no;
					$data['marks'] =$marks;
					$data['des_goods'] =$des_goods;
					$data['quantity'] =$quantity;
					$data['mlo_line'] =$mlo_line;
					$data['mlo_code'] =$mlo_code;
					$data['ffw_line'] =$ffw_line;
					$data['ffw_code'] ="";
					$data['unit'] =$unit;
					$data['cnf'] =$cnf;
					$data['importer_name'] =$importer_name;
					$data['be_no'] =$be_no;
					$data['be_date'] =$be_date;
					$data['dlv_qty'] =$dlv_qty;  
					$data['notifyName'] =$notifyName;
					$data['notifyAddress'] =$notifyAddress;
				}
				else
				{
					$data['vessel_name'] ="";
					$data['reg_no'] ="";
					$data['marks'] ="";
					$data['des_goods'] ="";
					$data['quantity'] ="";
					$data['mlo_line'] ="";
					$data['mlo_code'] ="";
					$data['ffw_line'] ="";
					$data['ffw_code'] ="";
					$data['unit'] ="";
					$data['cnf'] ="";
					$data['importer_name'] ="";
					$data['be_no'] ="";
					$data['be_date'] ="";
					$data['dlv_qty'] ="";  
					$data['notifyName'] ="";
					$data['notifyAddress'] ="";
				}
				
				$data['title']="Gate Confirmation";
							
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateConfirmation',$data);
				$this->load->view('jsAssets');
			
			
		}
	}
		//Gate Confirmation end
		
	
	//Gate Report Start
	
	function gateReportForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$data['msg']="";
			$data['title']="GATE REPORT SECTION...";
			$data['verify_num']=null;
			$data['verify_number']=null;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('gateReportForm',$data);
			$this->load->view('jsAssets');
		}	
	}

	function gateReportViewPdf()
	{
		$login_id = $this->session->userdata('login_id');	
		$data['login_id']=$login_id;
		$search_by = $this->input->post('search_by');
		/* echo "TEST ".$search_by;
		
		$this->load->library('M_pdf');
		$mpdf->use_kwt = true; */

		if($search_by=="vNum")
			{
				$vNum=$this->input->post('search_value');
				$strSelect="select do_information.verify_no,truck_id,delv_pack,manifest_qty,vessel_name,shed_loc,cnf_agent,
							shed_bill_master.be_no,do_information.import_rotation,date(do_information.last_update)as dt,exit_note_number, Pack_Marks_Number,
							(IFNULL(manifest_qty,0)-IFNULL(delv_pack,0)) as balance,do_information.remarks,verify_other_data.date,signature_path
							from do_information
							left join shed_bill_master on do_information.verify_no=shed_bill_master.verify_no
							left join shed_tally_info on do_information.verify_no=shed_tally_info.verify_number
							left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
							left join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
							left join cnf_signature_data on verify_other_data.cnf_lic_no=cnf_signature_data.cnf_license_no
							where do_information.verify_no='$vNum' and do_information.delv_status=1";
				//$this->data['verify_number']=$vNum;	
				$data['verify_number']=$vNum;	
			}
			else if ($search_by=="dateRange")
			{
				 $fromdate=$this->input->post('fromdate');
				 $todate=$this->input->post('todate');
				 $strSelect="select do_information.verify_no,truck_id,delv_pack,manifest_qty,vessel_name,shed_loc,cnf_agent,
							shed_bill_master.be_no,do_information.import_rotation,date(do_information.last_update)as dt,exit_note_number, Pack_Marks_Number,
							(IFNULL(manifest_qty,0)-IFNULL(delv_pack,0)) as balance,do_information.remarks,verify_other_data.date,signature_path
							from do_information
							left join shed_bill_master on do_information.verify_no=shed_bill_master.verify_no
							left join shed_tally_info on do_information.verify_no=shed_tally_info.verify_number
							left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
							left join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
							left join cnf_signature_data on verify_other_data.cnf_lic_no=cnf_signature_data.cnf_license_no
							where date(do_information.last_update) between '$fromdate' and '$todate' and do_information.delv_status=1";
			//return;				
			$data['verify_number']=null;	
			$data['fromdate']=$fromdate;
			$data['todate']=$todate;
			/* $this->data['fromdate']=$fromdate;
			$this->data['todate']=$todate; */
							
			}
			$rtnGateReportList = $this->bm->dataSelectDb1($strSelect);
			$data['rtnGateReportList']=$rtnGateReportList;
			//$this->data['rtnGateReportList']=$rtnGateReportList;
			 
			 $strSignatureQuery="select image_path from users where login_id='$login_id'";
			 $strSignature = $this->bm->dataSelectDb1($strSignatureQuery);
			 $signaturePath=$strSignature[0]['image_path'];
			 
			
			$data['signaturePath']=$signaturePath;
			//$this->data['signaturePath']=$signaturePath;
			
			$this->load->view('GateReportPdfOutput',$data);
	/* 		//$html=$this->load->view('GateReportPdfOutput',$this->data, true);
			//this the the PDF filename that user will get to download
			$pdfFilePath ="gateReport-".time()."-download.pdf";

			
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = true;	
			//$pdf->mirrorMargins = 1;
			//generate the PDF!
			//$stylesheet = file_get_contents('assets/css/main.css');
			//$mpdf->WriteHTML($stylesheet,1);
			//$stylesheet = file_get_contents('resources/styles/test.css'); // external css
			$pdf->AddPage('L', // L - landscape, P - portrait
				'', '', '', '',
				30, // margin_left
				30, // margin right
				30, // margin top
				30, // margin bottom
				18, // margin header
				12); // margin footer
			$pdf->useSubstitutions = true; // optional - just as an example
			//$pdf->SetHeader($url . "\n\n" . 'Date {DATE j-m-Y}');  // optional - just as an example
			//echo "SheetAdd : ".$stylesheet;
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
			//$footerHtml='<pagefooter name="MyFooter1" content-left="{DATE j-m-Y}" content-center="{PAGENO}/{nbpg}" content-right="My document" footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;" />';
			//$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			//$pdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
			//offer it to user via browser download! (The PDF won't be saved on your server HDD)
			//$pdf->Output($pdfFilePath, "D"); /// For Direct Download 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf */
		
	 }
	 
     //Gate Report End  
	 
	
	 function gateRegisterReportForm(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			$query = "SELECT DISTINCT gkey, id FROM road_gates WHERE life_cycle_state='ACT'";
			$gateList = $this->bm->dataSelect($query);
				
			$data['gateList']=$gateList;
			$data['title']="Gate Register Report Form";
			//echo $data['title'];
			//echo $data['title'];
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('containerRegisterReportForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function containerRegisterReportView()
	{
		//$this->load->library('m_pdf');
		//$mpdf->use_kwt = true;
		//$mpdf->simpleTables = true;
		$date=$this->input->post('date');
		//$gate=$this->input->post('gate');

		 $str = "SELECT cont_no, IFNULL((igm_detail_container.cont_size),igm_sup_detail_container.cont_size) AS cont_size,
		IFNULL((igm_detail_container.cont_height),igm_sup_detail_container.cont_height) AS cont_height ,truck_id,delv_pack,gate_no, gate_in_status,gate_in_time,gate_out_status,gate_out_time
		FROM do_truck_details_entry 
		LEFT JOIN igm_detail_container ON igm_detail_container.cont_number=do_truck_details_entry.cont_no
		LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.cont_number=do_truck_details_entry.cont_no
		WHERE DATE(do_truck_details_entry.last_update)='$date'";
		//return;
	
		$result = $this->bm->dataSelectDb1($str);	  

		// $gateStr1="SELECT DISTINCT id FROM sparcsn4.road_gates WHERE gkey=$gate";
		// $gateResult = $this->bm->dataSelect($gateStr1);
		$data['result']=$result;			
		//$this->data['result']=$result;
		//$this->data['gateResult']=$gateResult;
		$data['date']=$date;	 

		//echo $cnfName."</br>";

		$this->load->view('containerRegisterReportView',$data); 
		
	}
		
		
	function containerRegisterInRegister()
	{
		///echo "i am in";
		//return;
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			$query = "SELECT DISTINCT gkey, id FROM road_gates WHERE life_cycle_state='ACT'";
			//$query = "SELECT DISTINCT gkey, id FROM road_gates WHERE life_cycle_state='ACT' fetch first 1 rows only";
			$gateList = $this->bm->dataSelect($query);
				
			$data['gateList']=$gateList;
			$data['title']="INWARD & OUTWARD CONTAINER REGISTER";
			//echo $data['title'];
			//echo $data['title'];
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('containerRegisterInRegister',$data);
			$this->load->view('jsAssets');
		}	
	}
		
		/*Sumon Roy
		  Software Developer
		  Note: For gathering more data rows have to change parameter simple tables in mpdf.  
		*/
	/*function containerRegisterInRegisterView()
	{
		$this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;
		$date=$this->input->post('date');
		$gate=$this->input->post('gate');
		

		
		$str="SELECT sparcsn4.road_truck_visit_details.truck_license_nbr,sparcsn4.road_truck_transactions.ctr_id,sparcsn4.road_truck_transactions.stage_id,
				sparcsn4.road_truck_transactions.ctr_freight_kind,RIGHT(sparcsn4.road_truck_transactions.eqo_eq_length,2) AS size,sparcsn4.road_truck_transactions.nbr,
				sparcsn4.road_truck_transactions.line_id
				FROM sparcsn4.road_truck_visit_details
				INNER JOIN sparcsn4.road_truck_transactions ON sparcsn4.road_truck_transactions.truck_visit_gkey=sparcsn4.road_truck_visit_details.tvdtls_gkey
				WHERE DATE(sparcsn4.road_truck_visit_details.created)='$date' AND sparcsn4.road_truck_visit_details.gate_gkey=$gate";
		
	
		$result = $this->bm->dataSelect($str);	  

		$gateStr1="SELECT DISTINCT id FROM sparcsn4.road_gates WHERE gkey=$gate";
		$gateResult = $this->bm->dataSelect($gateStr1);
						
		$this->data['result']=$result;
		$this->data['gateResult']=$gateResult;
		$this->data['date']=$date;	 

		//echo $cnfName."</br>";

		$html=$this->load->view('containerRegisterInRegisterView',$this->data, true); 
		$pdfFilePath ="mypdfName-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
		//$stylesheet = file_get_contents('resources/styles/test.css'); 
		//$pdf->useSubstitutions = true; 				
		$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
	
		//$pdf->WriteHTML($stylesheet,1);
		$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
		//$pdf->WriteHTML($content,2);
		$pdf->WriteHTML($html,2);
			
		$pdf->Output($pdfFilePath, "I");	 					
	}*/
	
	
	function containerRegisterInRegisterView()
	{
		$this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$mpdf->simpleTables = true;
		$date=$this->input->post('date');
		$gate=$this->input->post('gate');
		

		
		
			    $str="SELECT road_truck_visit_details.truck_license_nbr,road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
				road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,
				road_truck_transactions.line_id
				FROM road_truck_visit_details
				INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey 
				WHERE cast(road_truck_visit_details.created as date)=to_date('$date' ,'YYYY-MM-DD') AND road_truck_visit_details.gate_gkey=$gate";
			
		       $result = $this->bm->dataSelect($str);	 
	
		$gateStr1="SELECT DISTINCT id FROM road_gates WHERE gkey=$gate";
		$gateResult = $this->bm->dataSelect($gateStr1);
						
		$this->data['result']=$result;
		$this->data['gateResult']=$gateResult;
		$this->data['date']=$date;	 

		//echo $cnfName."</br>";

		$html=$this->load->view('containerRegisterInRegisterView',$this->data, true); 
		$pdfFilePath ="mypdfName-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
		//$stylesheet = file_get_contents('resources/styles/test.css'); 
		//$pdf->useSubstitutions = true; 				
		$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
	
		//$pdf->WriteHTML($stylesheet,1);
		$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
		//$pdf->WriteHTML($content,2);
		$pdf->WriteHTML($html,2);
			
		$pdf->Output($pdfFilePath, "I");	 					
	}
		
		
	function gateWiseContainerRegisterView()
	{
			$fileType=$this->input->post('fileOptions');
			$registerType=$this->input->post('registerType');
			$date=$this->input->post('date');
			$gate=$this->input->post('gate');
	
			if($registerType=="inward" and $gate=="all")
			{  

				
				
				$str="SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey, argo_carrier_visit.id AS truck,
				road_truck_visit_details.truck_license_nbr,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
				road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,
				road_truck_visit_details.created
				FROM road_truck_visit_details
				INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
				INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
				INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				WHERE road_truck_visit_details.created BETWEEN to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')+1 AND stage_id='In Gate' AND inv_unit.category='EXPRT'
				";
				$result = $this->bm->dataSelect($str);
				
				$data['title']="INWARD EXPORT  CONTAINER REGISTER";	
				
				$data['gate']="ALL";
				$data['gate_type']="inward";
			}
			else if($registerType=="inward" and $gate!="all")
			{  
				
				$str="SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey, argo_carrier_visit.id AS truck,
				road_truck_visit_details.truck_license_nbr,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
				road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,
				road_truck_visit_details.created
				FROM road_truck_visit_details
				INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
				INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
				INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				WHERE road_truck_visit_details.created BETWEEN  to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')+1 AND stage_id='In Gate' AND inv_unit.category='EXPRT'and road_truck_visit_details.gate_gkey=$gate";
				
				$data['title']="INWARD EXPORT  CONTAINER REGISTER";	
				
				$result = $this->bm->dataSelect($str);
				$data['gate']=$result[0]['ID'];
				$data['gate_type']="inward";
			}		
			else if($registerType=="outward" and $gate=="all")
			{  
				

				
				$str="SELECT * FROM (
					SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey,argo_carrier_visit.id AS truck,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
					road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,road_truck_visit_details.created,road_truck_transactions.handled
					FROM road_truck_visit_details
					INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
					INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
					INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
					WHERE road_truck_visit_details.created BETWEEN  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS')+2 AND stage_id='Out Gate'
					AND inv_goods.destination!=2591 AND inv_unit.category ='IMPRT'
					  
					UNION ALL
					
					SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey,argo_carrier_visit.id AS truck,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
					road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,road_truck_visit_details.created,road_truck_transactions.handled
					FROM road_truck_visit_details
					INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
					INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
					INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					WHERE road_truck_visit_details.created BETWEEN  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS')+2 AND stage_id='Out Gate'
					AND inv_unit.category ='STRGE'
					)  tbl WHERE handled BETWEEN to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')+1 ORDER BY truck,handled";
				$data['title']="OUTWARD CONTAINER REGISTER";	
				$result = $this->bm->dataSelect($str);
				$data['gate']="ALL";
				$data['gate_type']="outward";
			}	
			else if($registerType=="outward" and $gate!="all")
			{  
				
			
				
				$str="SELECT * FROM (
					SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey,argo_carrier_visit.id AS truck,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
					road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,road_truck_visit_details.created,road_truck_transactions.handled
					FROM road_truck_visit_details
					INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
					INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
					INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
					
					WHERE road_truck_visit_details.created BETWEEN  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS')+2 AND stage_id='Out Gate'
					AND inv_goods.destination!=2591 AND inv_unit.category ='IMPRT' and road_truck_visit_details.gate_gkey=$gate
						
					UNION ALL
					
					SELECT inv_unit.gkey,road_gates.id, road_truck_visit_details.gate_gkey,argo_carrier_visit.id AS truck,inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
					road_truck_transactions.ctr_freight_kind,SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,road_truck_transactions.nbr,road_truck_visit_details.created,road_truck_transactions.handled
					FROM road_truck_visit_details
					INNER JOIN road_truck_transactions ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
					INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey
					INNER JOIN inv_unit ON road_truck_transactions.unit_gkey=inv_unit.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					
					WHERE road_truck_visit_details.created BETWEEN  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  to_date(CONCAT('$date','00:00:00'),'YYYY-MM-DD HH24-MI-SS')+2 AND stage_id='Out Gate'
					AND inv_unit.category ='STRGE'  and road_truck_visit_details.gate_gkey=$gate
					)  tbl WHERE handled BETWEEN to_date(CONCAT('$date','08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND  to_date(CONCAT('$date','07:59:59'),'YYYY-MM-DD HH24-MI-SS')+1 ORDER BY truck,handled";

				$data['title']="OUTWARD CONTAINER REGISTER";	
				$result = $this->bm->dataSelect($str);
				$data['gate']=$result[0]['ID'];
				$data['gate_type']="outward";
			}		
				
	
				$data['result']=$result;			
				$data['date']=$date;	
				$data['fileType']=$fileType;	
				$this->load->view('gateWiseContainerRegisterView',$data);

		}
			
				
	function gateWiseContainerRegister()
	{
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			$query = "SELECT DISTINCT gkey, id FROM road_gates WHERE life_cycle_state='ACT'";
			$gateList = $this->bm->dataSelect($query);
				
			$data['gateList']=$gateList;
			$data['title']="GATE WISE INWARD OUTWARD CONTAINER REGISTER REPORT";
			//echo $data['title'];

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('gateWiseContainerRegister',$data);
			$this->load->view('jsAssets');
		}	
		
	}
		
		
	
			   
			   

		function containerRegisterInRegister_ocr()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
	
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				
				$query = "SELECT DISTINCT gkey, id FROM sparcsn4.road_gates WHERE life_cycle_state='ACT'";
				$gateList = $this->bm->dataSelect($query);
					
				$data['gateList']=$gateList;
				$data['title']="INWARD & OUTWARD CONTAINER OCR REGISTER";
				//echo $data['title'];

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('containerRegisterInRegister_ocr',$data);
				$this->load->view('jsAssets');
			}	
		}
		

		function containerRegisterInRegisterView_ocr()
		{			
			$this->load->library('M_pdf');
		    $mpdf->use_kwt = true;
			$mpdf->simpleTables = true;
		    $date=$this->input->post('date');
			$gate=$this->input->post('gate');
			

			
		    /*$str="SELECT sparcsn4.road_truck_visit_details.truck_license_nbr,sparcsn4.road_truck_transactions.ctr_id,sparcsn4.road_truck_transactions.stage_id,
					sparcsn4.road_truck_transactions.ctr_freight_kind,RIGHT(sparcsn4.road_truck_transactions.eqo_eq_length,2) AS size,sparcsn4.road_truck_transactions.nbr,
					sparcsn4.road_truck_transactions.line_id
					FROM sparcsn4.road_truck_visit_details
					INNER JOIN sparcsn4.road_truck_transactions ON sparcsn4.road_truck_transactions.truck_visit_gkey=sparcsn4.road_truck_visit_details.tvdtls_gkey
					WHERE DATE(sparcsn4.road_truck_visit_details.created)='$date' AND sparcsn4.road_truck_visit_details.gate_gkey=$gate";
			
		
			$result = $this->bm->dataSelect($str);	*/  

			$gateStr1="SELECT DISTINCT id FROM sparcsn4.road_gates WHERE gkey=$gate";
			$gateResult = $this->bm->dataSelect($gateStr1);
            $add_date = date('Y-m-d', strtotime($date. ' + 1 days'));
			//$this->data['result']=$result;
			$this->data['gateResult']=$gateResult;
			$this->data['date']=$date;
            $this->data['add_date']=$add_date;
            $this->data['gate']=$gate;

			//echo $cnfName."</br>";

			$html=$this->load->view('containerRegisterInRegisterView_ocr',$this->data, true); 
		    $pdfFilePath ="mypdfName-".time()."-download.pdf";

		    $pdf = $this->m_pdf->load();
			//$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = true;	
			//$stylesheet = file_get_contents(CSS_PATH.'style.css'); // external css
			//$stylesheet = file_get_contents('resources/styles/test.css'); 
			//$pdf->useSubstitutions = true; 				
			$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
		
			//$pdf->WriteHTML($stylesheet,1);
			$pdf = new mPDF('utf-8', 'A4-L');  //have tried several of the formats
			//$pdf->WriteHTML($content,2);
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");	 					
		}
	//Fees Registration Starts------------------------------
		function feesRegistrtaionForm()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
	
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{					
				$data['title']="VEHICLE ENTRANCE FEES";
				$msg = "";
				$data['msg']=$msg;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('feesRegistrationForm',$data);
				$this->load->view('jsAssets');
			}	
		}
		function feesRegistrtaionEntry()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');	
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title']="VEHICLE ENTRANCE FEES";
				$msg = "";
				$veh_type = $this->input->post('vehicle_type');
				$veh_charge = $this->input->post('fee_amount');
				$vat = 15;
				$vat_amount = $this->input->post('vat_amount');				
				$tot_amount = $this->input->post('total_amount');
				
				$login_id = $this->session->userdata('login_id');
				$user_ip=$_SERVER['REMOTE_ADDR'];
				
				$query = "SELECT COUNT(*) AS rtnValue FROM vehicle_fees_reg WHERE veh_type='$veh_type'";
				$rtntrkno = $this->bm->dataSelectDb1($query);
				$rsltCnt = $rtntrkno[0]['rtnValue'];
				if($rsltCnt > 0)
				{
					$msg = "<font color='red'><b>Sorry! Data for this vehicle type is already stored</b></font>";
				}
				else
				{
					$insertSql="INSERT INTO cchaportdb.vehicle_fees_reg(veh_type,veh_charge,vat,tot_amount,vat_amount,login_id,updated_time) VALUES('$veh_type',$veh_charge,'$vat','$tot_amount','$vat_amount','$login_id',NOW())";
					$insertStat=$this->bm->dataInsertDB1($insertSql);
					$msg = "<font color='blue'><b>Successfully Inserted</b></font>";
				}
				
				$data['msg'] = $msg;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('feesRegistrationForm',$data);
				$this->load->view('jsAssets');
			}	
		}
	//Fees Registration Ends--------------------------------
	
	


	
	function chalanVCMS()
	{			
		$action = $this->input->post('submit');
		$CNFLicenceNo = $this->input->post('cnf_lic_no');
		$visitId = $this->input->post('visitId');

		if($action == 'single')
		{
			

			$CNFStr1="SELECT DISTINCT(ref_bizunit_scoped.name) AS NAME, address_line1, ref_bizunit_scoped.sms_number
			FROM ref_bizunit_scoped 
			WHERE ref_bizunit_scoped.id = '$CNFLicenceNo' 
			AND  (ref_bizunit_scoped.address_line1 IS NOT NULL OR ref_bizunit_scoped.sms_number IS NOT NULL)"; 

			$CNFresult = $this->bm->dataSelect($CNFStr1);
			
			$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
			Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
			verify_info_fcl.igm_detail_cont_id,
			verify_info_fcl.igm_detail_id,SUBSTR(igm_details.Description_of_Goods, 1, 100) AS Description_of_Goods,
			IFNULL(igm_supplimentary_detail.Notify_name,igm_details.Notify_name) AS Notify_name,
			IFNULL(igm_supplimentary_detail.Notify_address, igm_details.Notify_address) AS Notify_address
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
			INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id

			INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
			WHERE do_truck_details_entry.id='$visitId'";

			$resQuery = $this->bm->dataSelectDb1($queryStr);

			if(count($resQuery) == 0){
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				SUBSTR(Description_of_Goods, 1, 100) AS Description_of_Goods,
				Notify_name,Notify_address
				FROM do_truck_details_entry
				INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
				INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
				WHERE do_truck_details_entry.id='$visitId'";
				$resQuery = $this->bm->dataSelectDb1($queryStr);
			}

			if(count($resQuery) == 0){
				$queryStr="SELECT lcl_dlv_assignment.igm_sup_dtl_id,do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				SUBSTR(Description_of_Goods, 1, 100) AS Description_of_Goods,
				Notify_name,Notify_address
				FROM do_truck_details_entry
				INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
				INNER JOIN igm_details ON igm_details.id=lcl_dlv_assignment.igm_sup_dtl_id
				INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
				WHERE do_truck_details_entry.id='$visitId'";
				$resQuery = $this->bm->dataSelectDb1($queryStr);
			}
				
			$login_id = $this->session->userdata('login_id');
			
			$this->data['CNFresult']=$CNFresult;
			$this->data['resQuery']=$resQuery;
			$this->data['visitId']=$visitId;
			
			$this->load->library('m_pdf');
			$html=$this->load->view('chalanViewVCMS',$this->data, true);			 
			$pdfFilePath ="chalanViewVCMS-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");

		}
		else if($action == 'all')
		{
         
			$cnfId=$this->input->post('cnfId');
			$queryVisitId = "SELECT id FROM do_truck_details_entry WHERE update_by = '$cnfId' AND yard_security_chk_st = '1' AND cnf_chk_st = '1' AND traffic_chk_st = '1' AND DATE(last_update) = DATE(NOW())"; 
			$resVisitId = $this->bm->dataSelectDb1($queryVisitId);
		

			
			$this->data['CNFLicenceNo']=$CNFLicenceNo;
			$this->data['resVisitId']=$resVisitId;
			
			$this->load->library('m_pdf');
			$html=$this->load->view('allChalanViewVCMS',$this->data, true);			 
			$pdfFilePath ="allChalanViewVCMS-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			// $pdf->allow_charset_conversion = true;
			// $pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
		

	}

	//Gate Collection Report   - Start

	function gateCollectionReportForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Gate collection report Form";
			$data['msg']="";
			$data['flag'] = 0;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('gateCollectionReportForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateCollectionReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			
			$collectBy = $this->input->post("collectBy");
			$slot = $this->input->post("slot");
			$payMethod = $this->input->post("payMethod");
			
			$Cond = "";
			if($collectBy!=null){
				$Cond = "AND paid_collect_by='$collectBy'";
			}
			$Cond2="";
			if($payMethod!=null){
				$Cond2 = " AND paid_method='$payMethod'";
			}

			if($slot != null){
				if($slot == 'slot 1'){
					$fromDate =  $fromDate." 08:00:00";
					$toDate = $toDate." 15:59:59";
				}else if($slot == 'slot 2'){
					$fromDate =  $fromDate." 16:00:00";
					$toDate = $toDate." 23:59:59";
				}else if($slot == 'slot 3'){
					$fromDate =  $fromDate." 00:00:00";
					$toDate = $toDate." 07:59:59";
				}
			}else{
				$fromDate =  $fromDate." 00:00:00";
				$toDate = $toDate." 23:59:59";
			}

			// $query = "SELECT * FROM do_truck_details_entry WHERE paid_status = '1' AND paid_collect_dt BETWEEN '$fromDate' AND '$toDate'".$Cond;

			$query = "SELECT id,truck_id,import_rotation,cont_no,update_by,paid_method,paid_amt,paid_collect_dt,paid_collect_by FROM do_truck_details_entry WHERE paid_status = '1' AND paid_collect_dt BETWEEN '$fromDate' AND '$toDate'".$Cond." ".$Cond2."
			UNION
			SELECT visit_id as id,truck_id,import_rotation,cont_no,replace_by AS update_by,paid_method,paid_amt,paid_collect_dt,paid_collect_by FROM vcms_replace_truck_log WHERE paid_collect_dt BETWEEN '$fromDate' AND '$toDate'".$Cond." ".$Cond2;


			$rslt = $this->bm->dataSelectDb1($query);

			$this->data['fromDate'] = $fromDate;
			$this->data['toDate'] = $toDate;
			$this->data['collectBy'] = $collectBy;
			$this->data['slot'] = $slot;
			$this->data['rslt'] = $rslt;
		
			// $this->load->view('cssAssetsList');
			// $this->load->view('headerTop');
			// $this->load->view('sidebar');
			// $this->load->view('gateCollectionReport',$data);
			// $this->load->view('jsAssetsList');

			$this->load->library('m_pdf');
			$html=$this->load->view('gateCollectionReport',$this->data, true);			 
			$pdfFilePath ="chalanView-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			//$pdf->allow_charset_conversion = true;
			//$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css');				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I");
		}
	}

	//Gate Collection Report   -- End
	
	function DlvRegSearchForm()                              
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="DELIVERY REGISTER";
			$msg = "";
			$data['msg']=$msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('DlvRegSearchForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function DlvRegSearchPDF()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{			
			$fromDate=$this->input->post('fromDate');
			$toDate=$this->input->post('toDate');							 
			$login_id = $this->session->userdata('login_id');
			
			$queryStr="SELECT truck_id,verify_info_fcl_id AS aid,igm_details.Import_Rotation_No,
			igm_details.Pack_Marks_Number as manifestMarks,igm_masters.Vessel_Name as nameofship,cont_no, 
			actual_delv_pack as manifestQty, users.u_name as cnfName
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
			INNER JOIN igm_detail_container ON igm_detail_container.id=verify_info_fcl.igm_detail_cont_id
			INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id 
			INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
			LEFT JOIN users ON users.login_id=do_truck_details_entry.cnf_chk_by
			WHERE gate_out_status=1  AND DATE(gate_out_time) BETWEEN '$fromDate' AND '$toDate'
			UNION 
			SELECT truck_id, verify_other_data_id AS aid, igm_supplimentary_detail.Import_Rotation_No,
			igm_supplimentary_detail.Pack_Marks_Number as manifestMarks,igm_masters.Vessel_Name as nameofship,cont_no,
			actual_delv_pack as manifestQty, users.u_name as cnfName
			FROM do_truck_details_entry
			INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id=do_truck_details_entry.verify_other_data_id
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			INNER JOIN igm_masters ON  igm_masters.id=igm_supplimentary_detail.igm_master_id
			LEFT JOIN users ON users.login_id=do_truck_details_entry.cnf_chk_by
			WHERE gate_out_status=1  AND DATE(gate_out_time) BETWEEN '$fromDate' AND '$toDate'";
			$resQuery = $this->bm->dataSelectDb1($queryStr);
			
			$this->data['fromDate']=$fromDate;
			$this->data['toDate']=$toDate;
			$this->data['resQuery']=$resQuery;
			
			$this->load->library('m_pdf');
			$html=$this->load->view('deliveryRegisterPDF',$this->data, true);			 
			$pdfFilePath ="DlvRegPDF-".time()."-download.pdf";
			$pdf = $this->m_pdf->load();
			$stylesheet = file_get_contents('assets/stylesheets/test.css');			
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);				 
			$pdf->Output($pdfFilePath, "I"); //Show PDF
		}
	}

	//Date Wise Truck Entry Report  -- Start

	function datewiseTruckEntryForm(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Date Wise Truck Entry Report Form";
			$data['msg']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('datewiseTruckEntryForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function datewiseTruckEntryReport(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{

			$fromDate = $this->input->post("fromDate");
			$toDate = $this->input->post("toDate");
			$options = $this->input->post("options");

			$data['fromDate'] = $fromDate;
			$data['toDate'] = $toDate;

			if($options == 'summary')
			{
				$querySummary = "SELECT entryDt,
				(SELECT COUNT(DISTINCT cont_no) FROM do_truck_details_entry dtde WHERE DATE(dtde.last_update)=tbl.entryDt) AS cont,
				(SELECT COUNT(truck_id) FROM do_truck_details_entry dtde WHERE DATE(dtde.last_update)=tbl.entryDt) AS truck,
				(SELECT COUNT(DISTINCT cnf_lic_no) FROM do_truck_details_entry dtde 
				INNER JOIN verify_info_fcl ON verify_info_fcl.id=dtde.verify_info_fcl_id
				WHERE DATE(dtde.last_update)=tbl.entryDt) AS cnf
				FROM (
				SELECT DISTINCT DATE(last_update) AS entryDt
				FROM do_truck_details_entry 
				WHERE DATE(last_update) BETWEEN '$fromDate' AND '$toDate'
				) AS tbl";

				$summaryRslt = $this->bm->dataSelectDb1($querySummary);

				$data['summaryRslt'] = $summaryRslt;
				$data['title']="Date Wise Truck Entry Report Summary";

				$this->load->view("DateWiseTruckEntrySummary",$data);
			}
			else if($options == 'details')
			{
				$queryDetails = "SELECT DATE(last_update) AS entryDt,cont_no,truck_id,
				verify_info_fcl.cnf_lic_no,
				(SELECT Organization_Name FROM organization_profiles WHERE License_No=cnf_lic_no AND Org_Type_id=2 LIMIT 1) AS cf_name
				FROM do_truck_details_entry 
				INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
				WHERE DATE(last_update) BETWEEN '$fromDate' AND '$toDate'";
				$detailsRslt = $this->bm->dataSelectDb1($queryDetails);
				$data['detailsRslt'] = $detailsRslt;
				$data['title']="Date Wise Truck Entry Report Details";
				$this->load->view("DateWiseTruckEntryDetails",$data);
			}
		}
	}
	
	// Date Wise Trucl Entry Report  -- End
	
	function datewiseTruckEntryReportForm(){
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Date Wise Truck & Assignment Entry Report Form";
			$data['msg']="";
			$data['frmType']="";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('datewiseTruckAndAssignmentEntryReportForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function truckAndAssignmentEntryReport()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{		
			$from_date=$this->input->post('from_date');
			$to_date=$this->input->post('to_date');
			
			$data['from_date']=$from_date;
			$data['to_date']=$to_date;
			
			$this->load->view('onlineTruckAndAssignmentSummaryPDF',$data);
		}
	}
	
	//igm correction
	function igmCorrection()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			//cont_number,cont_size,cont_height,Cont_gross_weight,cont_seal_number,cont_status,cont_number_packaages
			//BL_No,Line_No,Pack_Number,Pack_Description,Pack_Marks_Number,Description_of_Goods,weight As  manifest_gross_weight
			
				/*
			$data['select_bl'] =array(
				"BL_No"=>"BL NO", 
				"Line_No"=>"Line No", 
				"Pack_Number"=>"Pack Number",
				"Pack_Description"=>"Pack Description",
				"Pack_Marks_Number"=>"Pack Mark Number",
				"Description_of_Goods"=>"Description Of Goods",
				"weight"=>"Weight",
				"ConsigneeDesc"=>"Consignee Description",
				"NotifyDesc"=>"Notify Description",
				"Exporter_name"=>"Exporter Name",
				"Exporter_address"=>"Exporter Address",
				"Notify_code"=>"Notify Code",
				"Notify_name"=>"Notify Name",
				"Notify_address"=>"Notify Address",
				"Consignee_code"=>"Consignee Code",
				"Consignee_name"=>"Consignee Name",
				"Consignee_address"=>"Consignee Address",
				"Volume_in_cubic_meters"=>"Volume In Cubic Meters",
				"place_of_unloading"=>"Place of Unloading",
				"port_of_origin"=>"Port of Origin",
				"type_of_igm"=>"Type of IGM",
				"Submitee_Id"=>"Submitee Id",
				"PFstatus"=>"PFstatus"
				);
				*/
			$data['select_bl'] =array(
				"BL_No"=>"BL NO", 
				"Line_No"=>"Line No", 
			
				"Pack_Info"=>"Pack Info",
				
				"Description_of_Goods"=>"Description Of Goods",
				"weight"=>"Weight",
				
				"Exporter_Info"=>"Exporter Info",
				
				"Notify_Info"=>"Notify Info",
				
				"Consignee_Info"=>"Consignee Info",
				
				"Volume_in_cubic_meters"=>"Volume In Cubic Meters",
				"place_of_unloading"=>"Place of Unloading",
				"port_of_origin"=>"Port of Origin",
				"type_of_igm"=>"Type of IGM",
				"Submitee_Id"=>"Submitee Id",
				"PFstatus"=>"PFstatus"
				);
			$data['select_container'] =array(
				"cont_number"=>"Container Number",				
				"cont_size"=>"Container Size", 
				"cont_height"=>"Container Height", 				
				"cont_weight"=>"Container Weight", 			
				"Cont_gross_weight"=>"Container Gross Weight", 
				"cont_seal_number"=>"Container Seal Number",
				"cont_status"=>"Container Status",				
				"cont_iso_type"=>"Container ISO Type",
				"cont_type"=>"Container Type",
				"cont_vat"=>"Container Vat",				
				"cont_number_packaages"=>"Cotainer Number Packages",
				"off_dock_id"=>"Destination"
				);

			$data['title']="IGM Correction ";
			$data['msg']="";
			$data['flag'] = 0;
			//echo json_encode($data);
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('igm_correction_view',$data);
			$this->load->view('jsAssets');
		}
	}
	
	public function get_bl_or_container_data()
	{
		$msg="";
		if($this->input->post("c_save"))
		{		
			$change_type="Container";
			$login_id = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];	
			$c_id=$this->input->post("c_id");
	        $c_igm=$this->input->post("c_igm_type");
			$c_selected=$this->input->post("c_selected");
			$c_pre_text=$this->input->post("c_pre_text");
			$c_new_text=$this->input->post("c_new_text");
			$date_time = date("Y-m-d H:i:s");
			
		
			//echo $c_id. " " ."$c_igm ". " " . "$c_selected". "  ". "$c_pre_text" . " "  . "$c_new_text"." "."$ipaddress"." "."$login_id"." ".$ipaddress;
			//id,change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip
			$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,
			entry_by,entry_at,entry_ip)
			VALUES ('$change_type','$c_id','$c_igm','$c_selected','$c_pre_text','$c_new_text','$login_id','$date_time','$ipaddress')";
			// echo $sql;
			// return;
			$logFlag = $this->bm->dataInsertDB1($sql);
			// return;
		
			$rotation_number = "";
			$bl_number = "";
		
			if($logFlag == 1)
			{
				if($c_igm=="dtl")
				{
					 $sql="UPDATE igm_detail_container
					SET $c_selected  = '$c_new_text'
					WHERE id=$c_id";	
					// echo "1.1. ".$sql;return;
					$flag = $this->bm->dataUpdateDB1($sql);
					
					if($flag==1){
						$msg = "<font color='green'>Container Amendment successful.</font>";
						
						//change in EDO starts...
						if($c_selected=="cont_status"){
							$sql_rot_bl = "SELECT igm_details.Import_Rotation_No,igm_details.BL_No,igm_detail_container.cont_number
										FROM igm_detail_container 
										INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
										WHERE igm_detail_container.id='$c_id'";
							$rslt_rot_bl = $this->bm->dataSelectDB1($sql_rot_bl);
							for($i=0; $i<count($rslt_rot_bl); $i++){	   
								$rotation_number=$rslt_rot_bl[$i]['Import_Rotation_No'];
								$bl_number=$rslt_rot_bl[$i]['BL_No'];
							}
							
							$sql_edo_application_id = "SELECT id FROM edo_application_by_cf WHERE rotation='$rotation_number' AND bl='$bl_number'";
							$rslt_edo_application_id = $this->bm->dataSelectDB1($sql_edo_application_id);
							
							if(count($rslt_edo_application_id)>0){
								$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET cont_status='$c_new_text' 
															WHERE edo_application_by_cf.rotation='$rotation_number' AND bl='$bl_number'";
								$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
							}
						}					
						//change in EDO ends...
						
					} else {
						$msg = "<font color='red'>Try again please.</font>";
					}						
				}
				else
				{
					$sql="UPDATE igm_sup_detail_container
					SET $c_selected  = '$c_new_text'
					WHERE id=$c_id";	
					 //echo "1.2. ".$sql;return;
					$flag = $this->bm->dataUpdateDB1($sql);
					
					if($flag==1){
						$msg = "<font color='green'>Container Amendment successful.</font>";
						//change in EDO starts...
						if($c_selected=="cont_status"){
							$sql_rot_bl = "SELECT igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number
											FROM igm_sup_detail_container
											INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
											WHERE igm_sup_detail_container.id='$c_id'";
							$rslt_rot_bl = $this->bm->dataSelectDB1($sql_rot_bl);
							for($i=0; $i<count($rslt_rot_bl); $i++){	   
								$rotation_number=$rslt_rot_bl[$i]['Import_Rotation_No'];
								$bl_number=$rslt_rot_bl[$i]['BL_No'];
							}
							
							$sql_edo_application_id = "SELECT id FROM edo_application_by_cf WHERE rotation='$rotation_number' AND bl='$bl_number'";
							$rslt_edo_application_id = $this->bm->dataSelectDB1($sql_edo_application_id);
							
							if(count($rslt_edo_application_id)>0){
								$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET cont_status='$c_new_text' 
															WHERE edo_application_by_cf.rotation='$rotation_number' AND bl='$bl_number'";
								$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
							}
						}					
						//change in EDO ends...
					} else {
						$msg = "<font color='red'>Try again please.</font>";
					}
						
				}
			}
		}
		else if($this->input->post("bl_save"))
		{			
			$change_type="BL";
			$login_id = $this->session->userdata('login_id');
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			$bl_id=$this->input->post("bl_id");
			$bl_igm=$this->input->post("bl_igm_type");
			$bl_selected=$this->input->post("bl_selected");
			$bl_pre_textarea=$this->input->post("bl_pre_textarea");
			$bl_new_textarea=$this->input->post("bl_new_textarea");
			$bl_pre_text=$this->input->post("bl_pre_text");
			$bl_new_text=$this->input->post("bl_new_text");
			$date_time = date("Y-m-d H:i:s");
			
			$groupFlag = $this->input->post('groupFlag');
				// return;		// start from here
			if($groupFlag=="Pack_Info")
			{
				$prevPackNumber = $this->input->post('prevPackNumber');
				$newPackNumber = $this->input->post('newPackNumber');
				$prevPackDesc = $this->input->post('prevPackDesc');
				$newPackDesc = $this->input->post('newPackDesc');
				$prevPackMarksNumber = $this->input->post('prevPackMarksNumber');
				$newPackMarksNumber = $this->input->post('newPackMarksNumber');
				
				// Pack Number
				$flag_1 = 0;
				
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Pack_Number','$prevPackNumber','$newPackNumber','$login_id',NOW(),'$ipaddress')";
				
				$logFlag_1 = $this->bm->dataInsertDB1($sql);								
				
				if($logFlag_1 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Pack_Number='$newPackNumber'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Pack_Number='$newPackNumber'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_1==1)
						$msg = $msg."Pack Number,";
				}
				
				// Pack Description
				$flag_2 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Pack_Description','$prevPackDesc','$newPackDesc','$login_id',NOW(),'$ipaddress')";				
				$logFlag_2 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_2 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Pack_Description='$newPackDesc'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Pack_Description='$newPackDesc'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_2==1)
						$msg = $msg."Pack Description,";
				}
				
				// Pack Marks Number
				$flag_3 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Pack_Marks_Number','$prevPackMarksNumber','$newPackMarksNumber','$login_id',NOW(),'$ipaddress')";
				$logFlag_3 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_3 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Pack_Marks_Number='$newPackMarksNumber'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Pack_Marks_Number='$newPackMarksNumber'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_3==1)
						$msg = $msg."Pack Marks Number";
				}
				
				$msg = "<font color='green'>".$msg." updated successfully</font>";								
			}			// Pack Info ends
			else if($groupFlag=="Exporter_Info")
			{
				$prevExporterName = $this->input->post('prevExporterName');
				$newExporterName = $this->input->post('newExporterName');
				$prevExporterAddress = $this->input->post('prevExporterAddress');
				$newExporterAddress = $this->input->post('newExporterAddress');
				
				// Exporter Name
				$flag_1 = 0;
				
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Exporter_name','$prevExporterName','$newExporterName','$login_id',NOW(),'$ipaddress')";
				$logFlag_1 = $this->bm->dataInsertDB1($sql);								
				
				if($logFlag_1 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Exporter_name='$newExporterName'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Exporter_name='$newExporterName'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_1==1)
						$msg = $msg."Exporter name,";
				}
				
				// Exporter Address
				$flag_2 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Exporter_address','$prevExporterAddress','$newExporterAddress','$login_id',NOW(),'$ipaddress')";
				$logFlag_2 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_2 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Exporter_address='$newExporterAddress'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Exporter_address='$newExporterAddress'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_2==1)
						$msg = $msg."Exporter address";
				}
				
				$msg = "<font color='green'>".$msg." updated successfully</font>";		
			}		// Exporter_Info ends
			else if($groupFlag=="Notify_Info")
			{
				$prevNotifyCode = $this->input->post('prevNotifyCode');
				$newNotifyCode = $this->input->post('newNotifyCode');
				
				$prevNotifyName = $this->input->post('prevNotifyName');
				$newNotifyName = $this->input->post('newNotifyName');
				
				$prevNotifyAddress = $this->input->post('prevNotifyAddress');
				$newNotifyAddress = $this->input->post('newNotifyAddress');
				
				$prevNotifyDesc = $this->input->post('prevNotifyDesc');
				$newNotifyDesc = $this->input->post('newNotifyDesc');
				
				// Notify Code
				$flag_1 = 0;
				
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Notify_code','$prevNotifyCode','$newNotifyCode','$login_id',NOW(),'$ipaddress')";
				$logFlag_1 = $this->bm->dataInsertDB1($sql);								
				
				if($logFlag_1 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Notify_code='$newNotifyCode'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Notify_code='$newNotifyCode'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_1==1)
						$msg = $msg."Notify code, ";
				}
				
				// Notify name
				$flag_2 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Notify_name','$prevNotifyName','$newNotifyName','$login_id',NOW(),'$ipaddress')";
				$logFlag_2 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_2 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Notify_name='$newNotifyName'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Notify_name='$newNotifyName'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_2==1)
						$msg = $msg."Notify name, ";
				}
				
				// Notify address
				$flag_3 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Notify_address','$prevNotifyAddress','$newNotifyAddress','$login_id',NOW(),'$ipaddress')";
				$logFlag_3 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_3 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Notify_address='$newNotifyAddress'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Notify_address='$newNotifyAddress'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_3==1)
						$msg = $msg."Notify Address, ";
				}
				
				// Notify description
				$flag_4 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','NotifyDesc','$prevNotifyDesc','$newNotifyDesc','$login_id',NOW(),'$ipaddress')";
				$logFlag_4 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_4 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET NotifyDesc='$newNotifyDesc'
						WHERE id='$bl_id'";	
						$flag_4 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET NotifyDesc='$newNotifyDesc'
						WHERE id='$bl_id'";	
						$flag_4 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_4==1)
						$msg = $msg."Notify Description ";
				}
				
				$msg = "<font color='green'>".$msg." updated successfully</font>";		
			}		// Notify_Info ends
			else if($groupFlag=="Consignee_Info")
			{
				$prevConsigneeCode = $this->input->post('prevConsigneeCode');
				$newConsigneeCode = $this->input->post('newConsigneeCode');
				
				$prevConsigneeName = $this->input->post('prevConsigneeName');
				$newConsigneeName = $this->input->post('newConsigneeName');
				
				$prevConsigneeAddress = $this->input->post('prevConsigneeAddress');
				$newConsigneeAddress = $this->input->post('newConsigneeAddress');
				
				$prevConsigneeDesc = $this->input->post('prevConsigneeDesc');
				$newConsigneeDesc = $this->input->post('newConsigneeDesc');
				
				// Consignee Code
				$flag_1 = 0;
				
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Consignee_code','$prevConsigneeCode','$newConsigneeCode','$login_id',NOW(),'$ipaddress')";
				$logFlag_1 = $this->bm->dataInsertDB1($sql);								
				
				if($logFlag_1 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Consignee_code='$newConsigneeCode'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Consignee_code='$newConsigneeCode'
						WHERE id='$bl_id'";	
						$flag_1 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_1==1)
						$msg = $msg."Consignee code, ";
				}
				
				// Consignee name
				$flag_2 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Consignee_name','$prevConsigneeName','$newConsigneeName','$login_id',NOW(),'$ipaddress')";
				$logFlag_2 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_2 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Consignee_name='$newConsigneeName'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Consignee_name='$newConsigneeName'
						WHERE id='$bl_id'";	
						$flag_2 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_2==1)
						$msg = $msg."Consignee name, ";
				}
				
				// Consignee address
				$flag_3 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','Consignee_address','$prevConsigneeAddress','$newConsigneeAddress','$login_id',NOW(),'$ipaddress')";
				$logFlag_3 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_3 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET Consignee_address='$newConsigneeAddress'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET Consignee_address='$newConsigneeAddress'
						WHERE id='$bl_id'";	
						$flag_3 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_3==1)
						$msg = $msg."Consignee Address, ";
				}
				
				// Consignee description
				$flag_4 = 0;
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','ConsigneeDesc','$prevConsigneeDesc','$newConsigneeDesc','$login_id',NOW(),'$ipaddress')";
				$logFlag_4 = $this->bm->dataInsertDB1($sql);
				
				if($logFlag_4 == 1)
				{				
					if($bl_igm=="dtl")
					{
						$sql="UPDATE igm_details
						SET ConsigneeDesc='$newConsigneeDesc'
						WHERE id='$bl_id'";	
						$flag_4 = $this->bm->dataUpdateDB1($sql);
					}
					else
					{						
						$sql="UPDATE igm_supplimentary_detail
						SET ConsigneeDesc='$newConsigneeDesc'
						WHERE id='$bl_id'";	
						$flag_4 = $this->bm->dataUpdateDB1($sql);
					}
					
					if($flag_4==1)
						$msg = $msg."Consignee Description ";
				}
				
				$msg = "<font color='green'>".$msg." updated successfully</font>";		
			}		// Consignee_Info ends
			else
			{
				//echo $bl_id. " " ."$bl_igm ". " " . "$bl_selected". "  ". "$bl_pre_text" . " "  . "$bl_new_text". " " . "$bl_pre_textarea"   . "$bl_new_textarea";
			 
				$Submitee_Org_Id = "";
				if($bl_selected == "Submitee_Id")
				{
					$sql_ain="SELECT id AS rtnValue
					FROM organization_profiles
					WHERE (AIN_No='$bl_new_text' OR AIN_No_New='$bl_new_text') AND Org_Type_id='4'";
					$Submitee_Org_Id=$this->bm->dataReturnDb1($sql_ain);
					
					$bl_new_text = $bl_new_text.'ff';
					
					// change in EDO for BL starts...
						
						$rotation_number = "";
						$bl_number = "";
						if($bl_igm=="sup")
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
							$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET ff_org_id='$Submitee_Org_Id' 
														WHERE edo_application_by_cf.rotation='$rotation_number' AND bl='$bl_number'";
							$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
						} 						
						
					// change in EDO for BL ends...
				}
			
				$sql="INSERT INTO igm_correction_log(change_type,ref_tbl_id,igm_type,change_field,prior_value,new_value,
				desc_marks_prior_value,desc_marks_new_value,entry_by,entry_at,entry_ip)
				VALUES ('$change_type','$bl_id','$bl_igm','$bl_selected','$bl_pre_text','$bl_new_text','$bl_pre_textarea','$bl_new_textarea','$login_id','$date_time','$ipaddress')";
				// echo $sql;return;
				$logFlag = $this->bm->dataInsertDB1($sql);
				
				if($logFlag == 1)
				{				
					if($bl_igm=="dtl")
					{
						if( $bl_selected =="Pack_Description"|| $bl_selected  =="Description_of_Goods"|| $bl_selected  =="ConsigneeDesc"|| $bl_selected  =="NotifyDesc"|| $bl_selected  =="Exporter_address"|| $bl_selected  =="Notify_address"|| $bl_selected  =="Consignee_address")
						{					
							$newValue = $bl_new_textarea;
						}
						else
						{				
							$newValue = $bl_new_text;
						}	
						
						$sql="UPDATE igm_details
						SET $bl_selected  = '$newValue'
						WHERE id=$bl_id";					
						
						$flag = $this->bm->dataUpdateDB1($sql);
						
						if($bl_selected == "Submitee_Id")
						{
							$sql_updateSubOrgid_dtl = "UPDATE igm_details
												SET igm_details.Submitee_Org_Id='$Submitee_Org_Id'
												WHERE id = '$bl_id'";
							$this->bm->dataUpdateDB1($sql_updateSubOrgid_dtl);
						}
						
						if($flag==1)
							$msg = "<font color='green'>BL Amendment successful.</font>";
						else
							$msg = "<font color='red'>Try again please.</font>";
					}
					else		// sup
					{
						if( $bl_selected =="Pack_Description"|| $bl_selected  =="Description_of_Goods"|| $bl_selected  =="ConsigneeDesc"|| $bl_selected  =="NotifyDesc"|| $bl_selected  =="Exporter_address"|| $bl_selected  =="Notify_address"|| $bl_selected  =="Consignee_address")
						{
							$newValue = $bl_new_textarea;				
						}
						else
						{
							$newValue = $bl_new_text;						
						}
						
						$sql="UPDATE  igm_supplimentary_detail
						SET $bl_selected  = '$newValue'
						WHERE id=$bl_id";	
						// echo "2.2. ".$sql;return;
						$flag = $this->bm->dataUpdateDB1($sql);
						
						//------ 2022-02-23 --------
						if($bl_selected == "Submitee_Id")
						{
							$sql_updateSubOrgid_sup = "UPDATE igm_supplimentary_detail
												SET igm_supplimentary_detail.Submitee_Org_Id='$Submitee_Org_Id'
												WHERE id = '$bl_id'";
							$this->bm->dataUpdateDB1($sql_updateSubOrgid_sup);
						}
						//------ / 2022-02-23 --------
						
						if($flag==1)
							$msg = "<font color='green'>BL Amendment successful.</font>";
						else
							$msg = "<font color='red'>Try again please.</font>";
					}
				}
			}
		}	

		$data['title']="IGM Correction ";
		$data['msg']=$msg;
		$data['flag'] = 0;
		//echo json_encode($data);

		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('igm_correction_view',$data);
		$this->load->view('jsAssets');		
	}
	
	function getIgmTable($tblFlag)
	{
		if($tblFlag == "dtl")
		{
			//return "igm_details";
			return "igm_details_copy";
		}
		else if($tblFlag == "supDtl")
		{
			// return "igm_supplimentary_detail";
			return "igm_supplimentary_detail_copy";
		}
		else if($tblFlag == "corLog")
		{
			// return "igm_correction_log";
			return "igm_correction_log_copy";
		}
		
	}
	
	//-- Status change for communication - start
	function changeContStatusForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Change Container Status";
			$data['msg']="";			
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('changeContStatusForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function changeContStatus()
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
			
			$data['title']="Change Container Status";
			
			$impRot = $this->input->post('impRot');
			$blNo = $this->input->post('blNo');
			$contNo = $this->input->post('contNo');
			$prevContStatus = $this->input->post('prevContStatus');
			$contStatus = $this->input->post('contStatus');
			
			$igmType = $this->input->post('igmType');
			$igmContId = $this->input->post('igmContId');
			
			$msg = "Blank";
			$edo_application_id = "";
			
			if($igmType == "" or $igmType == " " or $igmType == null)
			{
				$msg = "<font color='red'>Invalid Data.</font>";
			}
			else if($igmContId == "" or $igmContId == " " or $igmContId == null)
			{
				$msg = "<font color='red'>Invalid Data.</font>";
			}
			else if($impRot == "" or $blNo == "" or $contNo == "" or $contStatus == "")
			{
				$msg = "<font color='red'>Fill the required fields.</font>";
			}
			else	// got all data
			{
				if($igmType == "dtl")		// check if for dtl or supDtl
				{
					$sql_contStatus = "SELECT igm_detail_container.id,cont_status
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_details.Import_Rotation_No='$impRot' AND BL_No='$blNo' AND cont_number='$contNo'";
					$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);
					
					if(count($rslt_contStatus)>0)	// check again, if the data is valid
					{
						$sql_insertLog = "INSERT INTO igm_correction_log(ref_tbl_id,change_type,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
						VALUES('$igmContId','Container','$igmType','cont_status','$prevContStatus','$contStatus','$login_id',NOW(),'$ipaddr')";
						
						if($this->bm->dataInsertDB1($sql_insertLog))
						{
							$sql_updateDtlContStatus = "UPDATE igm_detail_container
											SET igm_detail_container.cont_status='$contStatus'
											WHERE igm_detail_container.id='$igmContId'";
							
							if($this->bm->dataUpdateDB1($sql_updateDtlContStatus))
							{
								$msg = "<font color='green'>Update successful.</font>";
								
								$sql_edo_application_id = "SELECT id FROM edo_application_by_cf WHERE rotation='$impRot' AND bl='$blNo'";
								$rslt_edo_application_id = $this->bm->dataSelectDB1($sql_edo_application_id);
								
								if(count($rslt_edo_application_id)>0){
									
									$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET cont_status='$contStatus' 
																WHERE rotation='$impRot' AND bl='$blNo'";
									$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
								}
								
							}
							else
							{
								$msg = "<font color='red'>Dtl Update failed.</font>";
							}
						}
						else
						{
							$msg = "<font color='red'>Dtl Log entry failed.</font>";
						}						
					}
					else
					{
						$msg = "<font color='red'>Invalid Dtl data submitted...</font>";
					}
				}
				else if($igmType == "supDtl")
				{
					// same as dtl
					$sql_contStatus = "SELECT igm_sup_detail_container.id,cont_status
					FROM igm_supplimentary_detail
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$impRot' AND BL_No='$blNo' AND cont_number='$contNo'";
					// echo $sql_contStatus;return;
					$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);
					
					if(count($rslt_contStatus)>0)	// check again, if the data is valid
					{
						$sql_insertLog = "INSERT INTO igm_correction_log(ref_tbl_id,change_type,igm_type,change_field,prior_value,new_value,entry_by,entry_at,entry_ip)
						VALUES('$igmContId','Container','$igmType','cont_status','$prevContStatus','$contStatus','$login_id',NOW(),'$ipaddr')";
						
						if($this->bm->dataInsertDB1($sql_insertLog))
						{
							$sql_updateSupDtlContStatus = "UPDATE igm_sup_detail_container
							SET igm_sup_detail_container.cont_status='$contStatus'
							WHERE igm_sup_detail_container.id='$igmContId'";
							if($this->bm->dataUpdateDB1($sql_updateSupDtlContStatus))
							{
								$msg = "<font color='green'>Update successful.</font>";
								
								$sql_edo_application_id = "SELECT id FROM edo_application_by_cf WHERE rotation='$impRot' AND bl='$blNo'";
								$rslt_edo_application_id = $this->bm->dataSelectDB1($sql_edo_application_id);
								
								if(count($rslt_edo_application_id)>0){
									$sql_updateEDOContStatus = "UPDATE edo_application_by_cf SET cont_status='$contStatus' 
																WHERE rotation='$impRot' AND bl='$blNo'";
									$res_updateEDOContStatus = $this->bm->dataUpdateDB1($sql_updateEDOContStatus);
								}
							}
							else
							{
								$msg = "<font color='red'>Sup Dtl Update failed.</font>";
							}
						}
						else
						{
							$msg = "<font color='red'>Sup Dtl Log entry failed.</font>";
						}						
					}
					else
					{
						$msg = "<font color='red'>Invalid Sup Dtl data submitted...</font>";
					}
				}
				else
				{
					$msg = "<font color='red'>Invalid IGM Type...</font>";
				}
			}
						
			$data['msg'] = $msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('changeContStatusForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	//-- Status change for communication - end
	
   //finish igm correction 
      
}
?>