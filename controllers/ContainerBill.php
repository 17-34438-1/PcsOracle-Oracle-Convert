<?php
class ContainerBill extends CI_Controller {
	function __construct()
	{
		parent::__construct();	
			$this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->helper('file');
			$this->load->model('ci_auth', 'bm', TRUE);
			$this->load->model('Container_Bill_Queries', 'cbq', TRUE);
			$this->load->library("pagination");
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
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
		
		
	function get_table_name($param)
	{
		if($param=="mis_inv_tarrif")	
			//return "ctmsmis.mis_inv_tarrif_test";
			return "ctmsmis.mis_inv_tarrif";
		if($param=="mis_billing")
			//return "ctmsmis.mis_billing_test";
			return "ctmsmis.mis_billing";
		if($param=="mis_billing_details")
			//return "ctmsmis.mis_billing_details_test";
			return "ctmsmis.mis_billing_details";
	}
		
	function container_bill_List()
	{
		
		$data['title']="Billing List";
		
	    $cont_bill_list="SELECT imp_rot,exp_rot,bill_type,mlo_code,draft_id as draft,IFNULL(created_user,'') AS created_user, 
		draft_final_status,pdf_draft_view_name,pdf_detail_view_name,DATE(billing_date) AS billing_date,br.billtype   
		FROM ".$this->get_table_name("mis_billing")." mb 
		INNER JOIN ctmsmis.billingreport br ON br.id = mb.bill_type 
		ORDER BY billing_date DESC
		LIMIT 1000";
		
		
		
		$bill_list=$this->bm->dataSelectDb2($cont_bill_list);
		
		
		$data['bill_list']=$bill_list;
		$data['msg']="";
	
		$this->load->view('containerBill/containerBillingList',$data);
		
	}
	
	
	//container bill start 
	function viewContainerBill()	
	{	
		$session_id = $this->session->userdata('value');			
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$draftNumber=$this->input->post('draftNumber');
		    $draft_view=$this->input->post('draft_view'); 
		    $printBtnValue=$this->input->post('printBtnValue');
			
			if($draft_view=='pdfPangoanDischargeInvoice')			//PCT Discharge
			{
               	//completed all with view 
			    $bill_sql="select * from (
				select mlo as payCustomerId,mlo_name as payCustomername,agent_code as customerId,agent as customerName,(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				vsl_name as ibCarrierName,rotation as ibVisitId,berth,wpn,
				IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))='RECTIFIED',CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				description as Particulars,size,height,count(description) as qty,ROUND(SUM(amt), 2) AS amt, IFNULL(ROUND(SUM(vat),2),0) AS vat,(SELECT DATE_FORMAT(billing_date,'%d-%m-%Y')  FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') as billingDate,
				DATE_FORMAT(argo_visist_dtls_eta,'%d-%m-%Y %h:%i %p') as eta,
				DATE_FORMAT(argo_visist_dtls_etd,'%d-%m-%Y %h:%i %p') as etd,
				if(currency_gkey=4861,'$','') as usd,
				'Container Discharging Bill (PCT)' as invoiceDesc,
				'DRAFT' as status,
				'' as comments,
				cast((
				CASE
					WHEN
						currency_gkey=4861
					THEN
						CAST(Tarif_rate AS DECIMAL(10,4))
					ELSE
						substring(cast(Tarif_rate as DECIMAL(10,4)),1,length(cast(Tarif_rate as DECIMAL(10,4))))
					END)as CHAR) AS rateBilled,
					
					exchangeRate,
					(select count(distinct id) from ".$this->cbq->get_table_name("mis_billing_details")." dtl
					where draftNumber='$draftNumber'
					and size=20
					) as qtytot20,
					(select count(distinct id) from ".$this->cbq->get_table_name("mis_billing_details")." dtl
					where draftNumber='$draftNumber'
					and size=40
					) as qtytot40,
					(select count(distinct id) from ".$this->cbq->get_table_name("mis_billing_details")." dtl
					where draftNumber='$draftNumber'
					and size=45
					) as qtytot45,
					vatperc,
					(SELECT created_user FROM ".$this->cbq->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
					from
					(
					select * from ".$this->cbq->get_table_name("mis_billing_details")." 
					where draftNumber='$draftNumber'
					) as tmp
					group by payCustomerId,Particulars,vatperc order by payCustomerId,Particulars asc,WPN desc
				) as tbl";
				
				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);                                                                                                       
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";	
				$print_time=$this->bm->dataSelectdb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
					
			
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_rptPangoanDischargingDraftInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="print_rptPangoanDischargingDraftInvoice-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', ''
					); // margin footer
					
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
						
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
               		
			}
			else if($draft_view=='pdfPangoanLoadingInvoice')		//PCT Loading
			{
				
				$bill_sql=" 
				SELECT * FROM (  
				SELECT mlo AS payCustomerId,id,mlo_name AS payCustomername,vsl_name,agent_code AS customerId,agent AS customerName,rotation AS ibVisitId,berth,wpn,
				IF(UCASE((SELECT remarks FROM ctmsmis.mis_billing WHERE draft_id='$draftNumber'))=UCASE('rectified'),CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				IF(description LIKE 'Load%',CONCAT(description,' (',wpn,')'),description) AS Particulars,
				size,height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,ROUND(IFNULL(SUM(vat),0),2) AS vat,
				(SELECT billing_date FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,
				billingDate AS eta,argo_visist_dtls_etd AS etd,IF(currency_gkey=4861,'$','') AS usd,
				'Container Loading Bill (PCT)' AS invoiceDesc,'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				CAST(
				( CASE WHEN currency_gkey=4861 THEN CAST(Tarif_rate AS DECIMAL(10,4)) 
				ELSE SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,
				LENGTH(CAST(Tarif_rate AS DECIMAL(10,4)))-2) END 
				)AS CHAR
				) AS rateBilled,
				(SELECT exrate FROM ctmsmis.mis_billing WHERE draft_id='$draftNumber') AS exchangeRate, 
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber' AND size=20 ) AS qtytot20, 
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber' AND size=40 ) AS qtytot40,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber' AND size=45 ) AS qtytot45,
				vatperc, (SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user,
				fcy_time_out,description,fcy_time_in,cl_date,DATE_ADD(cl_date,INTERVAL 4 DAY)clDateInt4Day,port_date,
				DATE_ADD(port_date,INTERVAL 1 DAY)AS portInt1Day,depo_date,DATE_ADD(depo_date,INTERVAL 1 DAY)depoInt1Day,
				DATE_ADD(depo_date,INTERVAL 0 DAY)depo_Int_Zero_Day

				FROM(
				SELECT *  FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND active=1 
				)tmp  GROUP BY payCustomerId,Particulars,height,vatperc ORDER BY payCustomerId,Particulars ASC,WPN DESC
				) AS tbl WHERE amt > 0";
				
				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);
				$invoiceDesc="";
				$draftNumber="";
				$created_user="";
				$billingDate="";
				$ibVisitId="";
				$payCustomername="";
				$vsl_name="";
				$customerId="";
				$eta="";
				$customerName="";
				$etd="";
				$exchangeRate="";
				$berth="";
				$qtytot20="";
				$qtytot40="";
                $qtytot45="";
				$exchangeRat="";
				for($i=0;$i<count($bill_rslt);$i++){
					$invoiceDesc=$bill_rslt[$i]['invoiceDesc'];
					$draftNumber=$bill_rslt[$i]['draftNumber'];
				    $created_user=$bill_rslt[$i]['created_user'];
					$billingDate=$bill_rslt[$i]['billingDate'];
					$payCustomerId=$bill_rslt[$i]['payCustomerId'];
					$ibVisitId=$bill_rslt[$i]['ibVisitId'];
					$payCustomername=$bill_rslt[$i]['payCustomername'];
					$vsl_name=$bill_rslt[$i]['vsl_name'];
				    $customerId=$bill_rslt[$i]['customerId'];
					$eta=$bill_rslt[$i]['eta'];
					$customerName=$bill_rslt[$i]['customerName'];
					$etd=$bill_rslt[$i]['etd'];
					$exchangeRat=$bill_rslt[$i]['exchangeRate'];
					$exchangeRate=number_format($exchangeRat,4);
				    $berth=$bill_rslt[$i]['berth'];
					$qtytot20=$bill_rslt[$i]['qtytot20'];
					$qtytot40=$bill_rslt[$i]['qtytot40'];
					$qtytot45=$bill_rslt[$i]['qtytot45'];
				}
				
				$data['invoiceDesc']=$invoiceDesc;	
				$data['draftNumber']=$draftNumber;	
				$data['created_user']=$created_user;	
				$data['billingDate']=$billingDate;	
				$data['payCustomerId']=$payCustomerId;	
				$data['ibVisitId']=$ibVisitId;	
				$data['payCustomername']=$payCustomername;	
				$data['vsl_name']=$vsl_name;	
				$data['customerId']=$customerId;	
				$data['eta']=$eta;	
				$data['customerName']=$customerName;	
				$data['etd']=$etd;	
				$data['exchangeRate']=$exchangeRate;	
				$data['berth']=$berth;	
				$data['qtytot20']=$qtytot20;	
			    $data['qtytot40']=$qtytot40;	
				$data['qtytot45']=$qtytot45;
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
				
				$print_time=$this->bm->dataSelectDb2($bill_print_time);
											
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
			    
				
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_rptPangoanLoadingDraftInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
				$pdfFilePath ="containerBill/print_rptPangoanLoadingDraftInvoice-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', ''
					); 
					
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);	
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
				
			}
			else if($draft_view=='pdfReeferInvoice')				
			{
				//view added but check again when bill generated view ok or not
				$bill_sql="SELECT 'Reefer Charges Bill' AS invoiceDesc,draftNumber AS draftNumber,
				mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,CAST((SELECT MAX(exchangeRate) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,description AS Particulars,height,size,
				IF(currency_gkey=4861,'$','') AS usd,
				CAST((
					CASE
						WHEN currency_gkey=4861
						THEN CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END)AS CHAR) AS rateBilled,COUNT(id) AS qtyUnit,SUM(storage_days)AS qty,ROUND(SUM(amt),2) AS amt,ROUND(SUM(vat),2) AS vat,(SUM(amt)+vat) AS netTotal,'' AS comments
					,'DRAFT' AS STATUS,DATE(rfr_disconnect) AS eventTo,
				(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billing_date,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' ORDER BY id

				)AS tbl GROUP BY description,size";
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";	

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);
						
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				//view missing(view add 5/16/2023)

				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_reeferbill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_reeferbill',$data);	
				}	
								
			}
			else if($draft_view=='pdfLoadingInvoice')
			{	
				$bill_sql="SELECT 'Container Loading Bill' AS invoiceDesc,draftNumber AS draftNumber,mlo AS payCustomerId,mlo_name AS payCustomerName,agent_code AS conCustomerId,agent AS conCustomerName,CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,rotation AS vslId,vsl_name AS obCarrierName,ata AS eta, atd AS etd,berth AS berth,CONCAT(description,' (',wpn,')') AS Particulars,height,size,
				IF(currency_gkey=4861,'$','') AS usd,
				CAST((
					CASE
						WHEN currency_gkey=4861
						THEN CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				)AS CHAR) AS rateBilled,COUNT(description) AS quantityBilled,SUM(amt) AS totalCharged,SUM(vat) AS totalvatamount,(SUM(amt)+SUM(vat)) AS netTotal,'' AS comments,'DRAFT' AS STATUS,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user,vatperc
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' ORDER BY id
				)AS tbl GROUP BY Particulars,vatperc

				UNION ALL

				SELECT 'Container Loading Bill' AS invoiceDesc,draftNumber AS draftNumber,mlo AS payCustomerId,mlo_name AS payCustomerName,agent_code AS conCustomerId,agent AS conCustomerName,CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,rotation AS vslId,vsl_name AS obCarrierName,ata AS eta, atd AS etd,berth AS berth,'LABOUR FUND' AS Particulars,NULL AS height,NULL AS size,
				'' AS usd,
				4.5 AS rateBilled,CONCAT(SUM(tues),' Teus') AS quantityBilled,SUM(tues)*4.5 AS totalCharged,0 AS totalvatamount,(SELECT SUM(tues)*4.5+0) AS netTotal,'' AS comments,'DRAFT' AS STATUS,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user,vatperc
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE 'Load%' ORDER BY id
				)AS tbl";
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";	

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;

				//views are missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_loadingbill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_loadingbill',$data);	
				}	
				
			
			}	
			else if($draft_view=='pdfDischargeInvoice')				//Container Discharge
			{
				
				$bill_sql="select * from (
				select mlo as payCustomerId,mlo_name as payCustomername,agent_code as customerId,agent as customerName,
				vsl_name as ibCarrierName,rotation as ibVisitId,berth,wpn,draftNumber,
				concat(description,'(',wpn,')') as Particulars,size,height,count(description) as qty,sum(amt) as amt,
				IFNULL(sum(vat),0) as vat,(select billing_date from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as billingDate,argo_visist_dtls_eta as eta ,argo_visist_dtls_etd as etd,
				if(currency_gkey=4861,'$','') as usd,
				'Container Discharging Bill' as invoiceDesc,
				'DRAFT' as status,
				'' as comments,
				cast((
				 CASE
				  WHEN
				   currency_gkey=4861
				  THEN
				   CAST(Tarif_rate AS DECIMAL(10,4))
				  ELSE
				   substring(cast(Tarif_rate as DECIMAL(10,4)),1,length(cast(Tarif_rate as DECIMAL(10,4))))

				  END
				)as CHAR) AS rateBilled,
				exchangeRate,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=20
				and fcy_time_in is not null
				) as qtytot20,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=40
				and fcy_time_in is not null
				) as qtytot40,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=45
				and fcy_time_in is not null
				) as qtytot45
				,
				vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				from
				(
				select * from ".$this->get_table_name("mis_billing_details")." 
				where draftNumber='$draftNumber'
				) as tmp
				group by payCustomerId,Particulars,vatperc order by payCustomerId,Particulars asc,WPN desc
				) as tbl

				union all

				select mlo as payCustomerId,mlo_name as payCustomername,agent_code as customerId,agent as customerName,
				vsl_name as ibCarrierName,rotation as ibVisitId,berth,wpn,draftNumber,
				'LABOUR FUND' as Particulars,size,height,concat(sum(tues),'(Teus)') as qty,sum(tues)*4.5 as amt,
				0 as vat,(select billing_date from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as billingDate,argo_visist_dtls_eta as eta ,argo_visist_dtls_etd as etd,
				'' as usd,
				'Container Discharging Bill' as invoiceDesc,
				'DRAFT' as status,
				'' as comments,
				4.5 AS rateBilled,
				exchangeRate,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=20
				and fcy_time_in is not null
				) as qtytot20,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=40
				and fcy_time_in is not null
				) as qtytot40,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=45
				and fcy_time_in is not null
				) as qtytot45
				,
				0 AS vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				from
				(
				select * from ".$this->get_table_name("mis_billing_details")." 
				where draftNumber='$draftNumber' and description like 'Discharging%'
				) as tmp
				group by payCustomerId,Particulars";
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";	
				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_dischargebill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_dischargebill',$data);	
				}	
				
			
			}
			else if($draft_view=='pdfDraftICDInvoice')
			{
				$bill_sql="select qty,amt,vat,status,draftNumber,ibCarrierName,customerId,customerName,
				payCustomerId,payCustomername,Particulars,
				if(exchangeRate=1,ifnull((SELECT rate FROM billing.bil_currency_exchange_rates WHERE DATE(effective_date)=DATE(final.final.currency_date)),(SELECT rate FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC LIMIT 1)),exchangeRate) as exchangeRate,
				rateBilled,chargeEventTypeId,
				date(billingDate) as billingDate,invoiceDesc,ibVisitId,height,berth,atd,ata,size,qtytot20,qtytot40,qtytot45,
				usd,eventPerformDate,comments,discharge_done,storage_days
				from
				(
				SELECT count(description) AS qty ,sum(amt) AS amt,IFNULL(sum(vat),0) AS vat,
				'DRAFT' as status,draftNumber,vsl_name as ibCarrierName,mlo as customerId,
				mlo_name as customerName,agent_code as payCustomerId,agent as payCustomername,
				description as Particulars,
				CAST((select max(exchangeRate) from ".$this->get_table_name("mis_billing_details")." dtl where draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,

				cast((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							substring(cast(Tarif_rate as DECIMAL(10,4)),1,length(cast(Tarif_rate as DECIMAL(10,4))))
					END
				)as CHAR) AS rateBilled,

				id as chargeEventTypeId,billingDate,'ICD Bill' as invoiceDesc,
				rotation as ibVisitId,height,berth,atd,ata,size,ata as currency_date,


				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=20 and dtl.mlo=details.mlo
				and fcy_time_in is not null
				) as qtytot20,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=40 and dtl.mlo=details.mlo
				and fcy_time_in is not null
				) as qtytot40,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=45 and dtl.mlo=details.mlo
				and fcy_time_in is not null
				) as qtytot45,


				if(currency_gkey=4861,'$','') as usd,
				max(fcy_time_out) AS eventPerformDate,

				'' AS comments,
				cl_date as discharge_done,if(storage_days = 0 ,NULL,storage_days) AS storage_days

				FROM
				(
				select * from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber'
				)

				AS details group by draftNumber,Particulars,height order by payCustomerId,Particulars,height asc
				)as final";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
			
				 //view missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_icdbill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_icdbill',$data);	
				}	
				
			
			}
			else if($draft_view=='pdfPangoanStatusChangeInvoice')		//Status Change (CPA to PCT)
			{
				  //bill complete
			    $bill_sql="SELECT * FROM (
				SELECT mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,berth,wpn,
				IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))=UCASE('rectified'),CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				IF(description LIKE 'Load%',CONCAT(description,' (',wpn,')'),description) AS Particulars,
				size,ROUND(height,1) AS height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,
				IF(depo_date IS NOT NULL,IF(description LIKE 'Storage%',days,0),IF(description LIKE '%1 to 7 days%',IF(days>=7,7,days),IF(description LIKE '%8 to 20 days%',IF(days-7>=13,13,days-7),IF(description LIKE 'Storage%',days-20,0)))) AS days2,
				ROUND(IFNULL(SUM(vat),0),2) AS vat,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,billingDate AS eta ,fcy_time_out AS etd,
				IF(currency_gkey=4861,'$','') AS usd,
				'Status Change Bill (CPA to PCT)' AS invoiceDesc,
				'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END)AS CHAR) AS rateBilled,
				(SELECT exrate FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS exchangeRate,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber' AND size=20) AS qtytot20,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber' AND size=40) AS qtytot40,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber' AND size=45) AS qtytot45,
				vatperc,
				(SELECT created_user 
				FROM ".$this->get_table_name("mis_billing")."
				WHERE draft_id='$draftNumber') AS created_user
				FROM
				(
				SELECT *,IF(DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days 
				FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber' AND active=1) AS tmp
				GROUP BY payCustomerId,Particulars,height,days2,vatperc 
				ORDER BY payCustomerId,Particulars ASC,WPN DESC) AS tbl";
				

				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);	
				$status="";
				$draftNumber="";
				$invoiceDesc="";
				$created_user="";
				$vesselName="";
				$billingDate="";
				$payCustomerId="";
				$ibVisitId="";
				$payCustomername="";
				$ibCarrierName="";
				$customerId="";
				$eta="";
				$customerName="";
				$etd="";
				$exchangeRateTmp="";
				$berth="";
				$exchangeRate="";
				$qtytot20="";
				$qtytot40="";
				$qtytot45="";
				$qtytot45="";
				$remarks="";
				for($i=0;$i<count($bill_rslt);$i++){
                $status=$bill_rslt[$i]['STATUS'];
				$invoiceDesc=$bill_rslt[$i]['invoiceDesc'];
				$draftNumber=$bill_rslt[$i]['draftNumber'];
				$created_user=$bill_rslt[$i]['created_user'];
				$vesselName=$bill_rslt[$i]['vsl_name'];
				$billingDate =$bill_rslt[$i]['billingDate'];
				$payCustomerId=$bill_rslt[$i]['payCustomerId'];
				$ibVisitId=$bill_rslt[$i]['ibVisitId'];
				$payCustomername=$bill_rslt[$i]['payCustomername'];
				$ibCarrierName=$bill_rslt[$i]['ibCarrierName'];
				$customerId=$bill_rslt[$i]['customerId'];
				$eta=$bill_rslt[$i]['eta'];
				$customerName=$bill_rslt[$i]['customerName'];
				$etd=$bill_rslt[$i]['etd'];
			    $exchangeRateTmp=$bill_rslt[$i]['exchangeRate'];
			    $exchangeRate=number_format($exchangeRateTmp,4);
				$berth=$bill_rslt[$i]['berth'];
				$qtytot20=$bill_rslt[$i]['qtytot20'];
				$qtytot40=$bill_rslt[$i]['qtytot40'];
				$qtytot45=$bill_rslt[$i]['qtytot45'];
				$remarks=$bill_rslt[$i]['remarks'];
				}
					
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				$data['status']=$status;
				$data['invoiceDesc']=$invoiceDesc;
				$data['draftNumber']=$draftNumber;
				$data['created_user']=$created_user;
				$data['billingDate']=$billingDate;
				$data['vesselName']=$vesselName;
				$data['payCustomerId']=$payCustomerId;
				$data['ibVisitId']=$ibVisitId;
				$data['payCustomername']=$payCustomername;
				$data['ibCarrierName']=$ibCarrierName;
				$data['customerId']=$customerId;
				$data['eta']=$eta;
				$data['customerName']=$customerName;
				$data['etd']=$etd;
				$data['exchangeRate']=$exchangeRate;
				$data['berth']=$berth;
				$data['qtytot20']=$qtytot20;
				$data['qtytot40']=$qtytot40;
				$data['qtytot45']=$qtytot45;
				$data['remarks']=$remarks;
				
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_statusChange(CPAtoPCT)',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="containerBill/print_statusChange(CPAtoPCT)-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', ''
					); // margin footer
					
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
						
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
				
				$this->load->view('containerBill/print_statusChange(CPAtoPCT)',$data);

					
				
			
			}
			else if($draft_view=='pdfStatucChangeCPAToICDInvoice')
			{
				$bill_sql="SELECT 'STATUS CHANGE INVOICE (CPA TO ICD)' AS invoiceDesc,'DRAFT' AS STATUS,draftNumber AS draftNumber,mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,DATE(billingDate) AS billingDate,rotation AS ibVisitId,vsl_name AS ibCarrierName,ata AS eta,atd AS etd,berth AS berth,cl_date AS discharge_done,DATE(fcy_time_out) AS icd_yardin_date,description AS Particulars,id AS unitId,size AS size,ROUND(height,1) AS height,COUNT(DISTINCT id) AS qtyUnit,IF(SUM(storage_days)=0,NULL,SUM(storage_days)) AS qty,(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS comments,
				IF(currency_gkey=4861,'$','') AS usd,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				)AS CHAR) AS rateBilled,
				SUM(amt) AS amt,SUM(vat) AS vat,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45
				FROM(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' 
				) AS tbl GROUP BY Particulars ORDER BY usd DESC,Particulars";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
			
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_statusChange(CPAtoICD)',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_statusChange(CPAtoICD)',$data);	
				}	
				
			
			}
			else if($draft_view=='pdfStatucChangeFCLToLCLInvoice')
			{
				$bill_sql="SELECT 'STATUS CHANGE INVOICE (FCL TO LCL)' AS invoiceDesc,'DRAFT' AS STATUS,draftNumber AS draftNumber,mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,DATE(billingDate) AS billingDate,rotation AS ibVisitId,vsl_name AS ibCarrierName,ata AS eta,atd AS etd,berth AS berth,cl_date AS discharge_done,DATE(fcy_time_out) AS unstuffing_date,description AS Particulars,id AS unitId,size AS size,ROUND(height,1) AS height,COUNT(DISTINCT id) AS qtyUnit,IF(SUM(storage_days)=0,NULL,SUM(storage_days)) AS qty,(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS comments,
				IF(currency_gkey=4861,'$','') AS usd,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				) AS CHAR) AS rateBilled,SUM(amt) AS amt,SUM(vat) AS vat,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,
				(SELECT COUNT(DISTINCT id) 
				FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45
				FROM(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' 
				) AS tbl GROUP BY Particulars";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				  
				  //view missing all
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_statusChange(FCLtoLCL)',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_statusChange(FCLtoLCL)',$data);	
				}	
				
			
			}	
			else if($draft_view=='pdfStatucChangeLCLToFCLInvoice')
			{
				$bill_sql="SELECT 'STATUS CHANGE INVOICE (LCL TO FCL)' AS invoiceDesc,'DRAFT' AS STATUS,draftNumber AS draftNumber,mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,DATE(billingDate) AS billingDate,rotation AS ibVisitId,vsl_name AS ibCarrierName,ata AS eta,atd AS etd,berth AS berth,cl_date AS discharge_done,DATE(fcy_time_out) AS fcl_declaration_date,description AS Particulars,id AS unitId,size AS size,height AS height,COUNT(DISTINCT id) AS qtyUnit,IF(SUM(storage_days)=0,NULL,SUM(storage_days)) AS qty,(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS comments,
				IF(currency_gkey=4861,'$','') AS usd,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				) AS CHAR) AS rateBilled,SUM(amt) AS amt,SUM(vat) AS vat,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45
				FROM(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' 
				) AS tbl GROUP BY Particulars";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				 //view missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_statusChange(LCLtoFCL)',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_statusChange(LCLtoFCL)',$data);	
				}	
				
			
			}
			else if($draft_view=='pdfPangoanStatusChangePCTToCPAInvoice')		//Status Change (PCT to CPA)
			{
				$bill_sql="SELECT * FROM (
				SELECT mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,berth,wpn,IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))=UCASE('rectified'),CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				IF(description LIKE 'Load%',CONCAT(description,' (',wpn,')'),description) AS Particulars,size,ROUND(height,1) AS height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,
				IF(depo_date IS NOT NULL,IF(description LIKE 'Storage%',days,0),IF(description LIKE '%1 to 7 days%',IF(days>=7,7,days),IF(description LIKE '%8 to 20 days%',IF(days-7>=13,13,days-7),IF(description LIKE 'Storage%',days-20,0)))) AS days2,
				ROUND(IFNULL(SUM(vat),0),2) AS vat,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,billingDate AS eta ,pre_imp_ata AS etd,
				IF(currency_gkey=4861,'$','') AS usd,
				'Status Change Bill (PCT to CPA)' AS invoiceDesc,
				'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				)AS CHAR) AS rateBilled,
				(SELECT exrate FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS exchangeRate,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45
				) AS qtytot45
				,
				vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				FROM
				(
				SELECT *,IF(DATEDIFF(cl_date,IFNULL(fcy_time_in,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(cl_date,IFNULL(fcy_time_in,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber' AND active=1
				) AS tmp
				GROUP BY payCustomerId,Particulars,height,days2,vatperc ORDER BY payCustomerId,Particulars ASC,WPN DESC
				) AS tbl";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);	
				$status="";
				$invoiceDesc="";
				$draftNumber="";
				$created_user="";
				$billingDate="";
				$payCustomerId="";
				$ibVisitId="";
				$payCustomername="";
				$ibCarrierName="";
				$customerId="";
				$eta="";
				$customerName="";
				$etd="";
				$exchangeRateTmp="";
				$exchangeRate="";
				$berth="";
				$qtytot20="";
				$qtytot40="";
				$qtytot45="";
				$remarks="";
                for($i=0;$i<count($bill_rslt);$i++){
					$status=$bill_rslt[$i]['STATUS'];
					$invoiceDesc=$bill_rslt[$i]['invoiceDesc'];
					$draftNumber=$bill_rslt[$i]['draftNumber'];
					$created_user=$bill_rslt[$i]['created_user'];
					$billingDate=$bill_rslt[$i]['billingDate'];
					$payCustomerId=$bill_rslt[$i]['payCustomerId'];
					$ibVisitId=$bill_rslt[$i]['ibVisitId'];
					$payCustomername=$bill_rslt[$i]['payCustomername'];
					$ibCarrierName=$bill_rslt[$i]['ibCarrierName'];
					$customerId=$bill_rslt[$i]['customerId'];
					$eta=$bill_rslt[$i]['eta'];
					$customerName=$bill_rslt[$i]['customerName'];
					$etd=$bill_rslt[$i]['etd'];
					$exchangeRateTmp=$bill_rslt[$i]['exchangeRate'];
					$exchangeRate=number_format($exchangeRateTmp,4);
					$berth=$bill_rslt[$i]['berth'];
					$qtytot20=$bill_rslt[$i]['qtytot20'];
					$qtytot40=$bill_rslt[$i]['qtytot40'];
					$qtytot45=$bill_rslt[$i]['qtytot45'];
					$remarks=$bill_rslt[$i]['remarks'];
					
				}	

                $data['status']=$status;  				
                $data['invoiceDesc']=$invoiceDesc;  				
                $data['draftNumber']=$draftNumber;  				
                $data['created_user']=$created_user;  				
                $data['billingDate']=$billingDate;  				
                $data['payCustomerId']=$payCustomerId;  				
                $data['ibVisitId']=$ibVisitId;  				
                $data['payCustomername']=$payCustomername;  				
                $data['ibCarrierName']=$ibCarrierName;  				
                $data['customerId']=$customerId;  				
                $data['eta']=$eta;  				
                $data['customerName']=$customerName;  				
                $data['etd']=$etd;  				
                $data['exchangeRate']=$exchangeRate;  				
                $data['berth']=$berth;  				
                $data['qtytot20']=$qtytot20;  				
                $data['qtytot40']=$qtytot40;  				
                $data['qtytot45']=$qtytot45;  				
                $data['remarks']=$remarks;  				
				$print_time=$this->bm->dataSelectDb2($bill_print_time);
				
				$time="";
                for($j=0;$j<count($print_time);$j++){
					$time=$print_time[$j]['Time'];
				}				
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				$data['time']=$time;
				//pdf start here
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_statusChange(PCTtoCPA)',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="containerBill/print_statusChange(PCTtoCPA)-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
				'', '', '', ''
				); // margin footer

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf				
				//pdf end here
						
			}
			else if($draft_view=='pdfOffhireInvoice')
			{
				$bill_sql="select 'OFFHIRE CHARGES ON CONTAINER' as type,if(sum(storage_days)=0,NULL,sum(storage_days)) as qty,sum(amt) AS amt,IFNULL(sum(vat),0) AS vat,
				'DRAFT' as status,draftNumber,vsl_name as ibCarrierName,mlo as customerId,
				mlo_name as customerName,agent_code as payCustomerId,agent as payCustomername,
				description as Particulars,

				/*cast(rateBilled as DECIMAL(10,4)) as rateBilled,*/

				CAST(IFNULL((SELECT rate FROM billing.bil_currency_exchange_rates WHERE DATE(effective_date)=date(min(fcy_time_out))),    
								(SELECT rate FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC LIMIT 1)) AS DECIMAL(10,4)) AS exchangeRate,

				billingDate,'Offhire Charges Bill' as invoiceDesc,
				height,size,

				count(description) as qtyUnit,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=20 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot20,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=40 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot40,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber='$draftNumber'
				and size=45 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot45,

				if(currency_gkey=4861,'$','') as usd,
				cast((
				 CASE
				  WHEN
				   currency_gkey=4861
				  THEN
				   CAST(Tarif_rate AS DECIMAL(10,4))
				  ELSE
				   substring(cast(Tarif_rate as DECIMAL(10,4)),1,length(cast(Tarif_rate as DECIMAL(10,4))))

				  END
				)as CHAR) AS rateBilled,

				date(min(fcy_time_out)) as yardout,
				'' as comments,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				from 
				(

				select gkey,draftNumber,fcy_time_in,fcy_time_out,mlo,mlo_name,agent_code,agent,
				storage_days,
				invoice_type,vsl_name,rotation,berth,billingDate,id,description,size,height,freight_kind,
				vatperc,Tarif_rate,exchangeRate,currency_gkey,amt,vat
				 from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber' order by id

				)as tbl group by Particulars";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				//view missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_offhireChargesContainerInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_offhireChargesContainerInvoice',$data);	
				}	
				
			
			}
			else if($draft_view=='pdfDraftOffdockToMuktarpurStatusChangeInvoice')
			{
				
				$bill_sql="select 'STATUS CHANGE INVOICE (OFFDOCK TO MUKTARPUR)' as invoiceDesc,'DRAFT' as status,draftNumber as draftNumber,
				mlo as payCustomerId,mlo_name as payCustomername,agent_code as customerId,agent as customerName,
				CAST((select max(exchangeRate) from ".$this->get_table_name("mis_billing_details")." dtl where draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,
				(select billing_Date from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as billingDate,
				rotation as ibVisitId,vsl_name as ibCarrierName,argo_visist_dtls_eta as eta,argo_visist_dtls_etd as etd,
				berth as berth,cl_date as discharge_done,fcy_time_out as icd_yardin_date,description as Particulars,id as unitId,size as size,
				height as height,count(distinct id) as qtyUnit,if(sum(storage_days)=0,null,sum(storage_days)) as qty,
				(select remarks from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as comments,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user,
				if(currency_gkey=4861,'$','') as usd,billingDate AS ata,pre_imp_ata AS atd,
				cast((
				 CASE
				  WHEN
				   currency_gkey=4861
				  THEN
				   CAST(Tarif_rate AS DECIMAL(10,4))
				  ELSE
				   substring(cast(Tarif_rate as DECIMAL(10,4)),1,length(cast(Tarif_rate as DECIMAL(10,4))))

				  END
				)as CHAR) AS rateBilled,sum(amt) as amt,sum(vat) as vat,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber=  '$draftNumber'
				and size=20 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot20,
				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber=  '$draftNumber'
				and size=40 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot40,

				(select count(distinct id) from ".$this->get_table_name("mis_billing_details")." dtl
				where draftNumber=  '$draftNumber'
				and size=45 and dtl.mlo=tbl.mlo
				and fcy_time_in is not null
				) as qtytot45
				from(
				select * from ".$this->get_table_name("mis_billing_details")." where draftNumber= '$draftNumber' 
				) as tbl group by Particulars order by usd desc,Particulars";

				//echo $bill_sql;
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectdb2($bill_sql);		
				$print_time=$this->bm->dataSelectdb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;

				//missing all view
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_statusChange(OFFDOCK TO MUKTARPUR)Invoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_statusChange(OFFDOCK TO MUKTARPUR)Invoice',$data);	
				}	
			
			
			}
			else if($draft_view=='pdfDraftMukterpulDischargeInvoice')		//MUKTARPUR CONT DISCHARGE - 07-01-2019	//intakhab
			{
				$bill_sql="SELECT * FROM (
				SELECT mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,berth,wpn,IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))='RECTIFIED',CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				CONCAT(description,'(',wpn,')') AS Particulars,size,height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,
				IFNULL(ROUND(SUM(vat),2),0) AS vat,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,argo_visist_dtls_eta AS eta ,argo_visist_dtls_etd AS etd,
				IF(currency_gkey=4861,'$','') AS usd,
				'Container Discharging Bill (Muktarpur)' AS invoiceDesc,
				'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS comments,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				)AS CHAR) AS rateBilled,
				exchangeRate,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45
				) AS qtytot45
				,
				vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber'
				) AS tmp
				GROUP BY payCustomerId,Particulars,vatperc,rateBilled ORDER BY payCustomerId,Particulars ASC,WPN DESC
				) AS tbl";
				//return;
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectdb2($bill_sql);		
				$print_time=$this->bm->dataSelectdb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;

				//view missing all
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_mukhtarpur_cont_discharge_bill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_mukhtarpur_cont_discharge_bill',$data);	
				}
				
			
			}
			else if($draft_view=='pdfMukhterpoleLoadingInvoice')		//MUKTARPUR CONT LOAD - 07-01-2019	//intakhab
			{
				$bill_sql="SELECT * FROM (
				SELECT mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,berth,wpn,IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))=UCASE('rectified'),CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				IF(description LIKE 'Load%',CONCAT(description,' (',wpn,')'),description) AS Particulars,size,height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,
				IF(depo_date IS NOT NULL,IF(description LIKE 'Storage%',days,0),IF(description LIKE '%1 to 7 days%',IF(days>=7,7,days),IF(description LIKE '%8 to 20 days%',IF(days-7>=13,13,days-7),IF(description LIKE 'Storage%',days-20,0)))) AS days2,
				ROUND(IFNULL(SUM(vat),0),2) AS vat,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,billingDate AS eta ,fcy_time_out AS etd,
				IF(currency_gkey=4861,'$','') AS usd,
				'LOADING BILL (Muktarpur)' AS invoiceDesc,
				'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				CAST((
					CASE
						WHEN currency_gkey=4861
						THEN CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END)AS CHAR) AS rateBilled,
				(SELECT exrate FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS exchangeRate,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45
				) AS qtytot45
				,
				vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				FROM
				(
				SELECT *,IF(DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber' AND active=1
				) AS tmp
				GROUP BY payCustomerId,Particulars,height,days2,vatperc ORDER BY payCustomerId,Particulars ASC,WPN DESC
				) AS tbl";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
			
				//view missing all
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_mukhtarpur_cont_load_bill',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_mukhtarpur_cont_load_bill',$data);	
				}
				
			
			}
			else if($draft_view=='pdfExportStorageInvoice')
			{
				$bill_sql="SELECT 'EXPORT STORAGE INVOICE' AS TYPE,draftNumber,'DRAFT' AS STATUS,
				'EXPORT STORAGE INVOICE' AS invoiceDesc, DATE_FORMAT(billingDate,'%d-%m-%Y') as billingDate,agent_code AS conCustomerId,
				agent AS conCustomerName,mlo AS payCustomerId,
				mlo_name AS payCustomerName,
				CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,
				COUNT(id) AS quantityUnit,SUM(amt) AS totalCharged,
				description,

				CAST((
					CASE 
						WHEN 
							currency_gkey=4861 
						THEN 
							CAST(Tarif_rate AS DECIMAL(10,4)) 
						ELSE 
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
							
						END 
				)AS CHAR) AS rateBilled,


				SUM(vat) AS totalvatamount,SUM(amt)+SUM(vat) AS netTotal,vsl_name AS obCarrierName,ata AS obCarrierATA,atd AS obCarrierATD,
				height,

				 berth,
				arcar_id AS vslId,size,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45,


				IF(currency_gkey=4861,'$','') AS usd,
				IF(SUM(storage_days)=0,NULL,SUM(storage_days)) AS qty ,
				COUNT(description) AS qtyUnit,'' AS comments

				FROM

				(
					SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE ".$this->get_table_name("mis_billing_details").".draftNumber='$draftNumber'
				)AS tbl GROUP BY description";
								
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";

				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				 
				 //view missing all
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/container_rptExportStorageDraftInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_rptExportStorageDraftInvoice',$data);	
				}
				$this->load->view('rptExportStorageDraftInvoice',$data); 
				
			

			}
			else if($draft_view=='pdfDraftICDToPCTStatusChangeInvoice')
			{
				$bill_sql="SELECT * FROM (
				SELECT mlo AS payCustomerId,mlo_name AS payCustomername,agent_code AS customerId,agent AS customerName,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,berth,wpn,IF(UCASE((SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber'))=UCASE('rectified'),CONCAT(draftNumber,'/R'),draftNumber) AS draftNumber,
				IF(description LIKE 'Load%',CONCAT(description,' (',wpn,')'),description) AS Particulars,size,ROUND(height,1) AS height,COUNT(description) AS qty,ROUND(SUM(amt),2) AS amt,
				IF(depo_date IS NOT NULL,IF(description LIKE 'Storage%',days,0),IF(description LIKE '%1 to 7 days%',IF(days>=7,7,days),IF(description LIKE '%8 to 20 days%',IF(days-7>=13,13,days-7),IF(description LIKE 'Storage%',days-20,0)))) AS days2,
				ROUND(IFNULL(SUM(vat),0),2) AS vat,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billingDate,billingDate AS eta ,pre_imp_ata AS etd,
				IF(currency_gkey=4861,'$','') AS usd,
				'Status Change Bill (ICD to PCT)' AS invoiceDesc,
				'DRAFT' AS STATUS,
				(SELECT remarks FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS remarks,
				CAST((
					CASE
						WHEN
							currency_gkey=4861
						THEN
							CAST(Tarif_rate AS DECIMAL(10,4))
						ELSE
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4))))
					END
				)AS CHAR) AS rateBilled,
				(SELECT exrate FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS exchangeRate,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45
				) AS qtytot45
				,
				vatperc,
				(SELECT created_user FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created_user
				FROM
				(
				SELECT *,IF(DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1<1,'0',DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1) AS days  FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber' AND active=1
				) AS tmp
				GROUP BY payCustomerId,Particulars,height,days2,vatperc ORDER BY payCustomerId,Particulars ASC,WPN DESC
				) AS tbl";
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
				$bill_rslt=$this->bm->dataSelectDb2($bill_sql);	
				$invoiceDesc="";
				$draftNumber="";
				$created_user="";
				$billingDate="";
				$payCustomerId="";
				$ibVisitId="";
				$payCustomername="";
				$ibCarrierName="";
				$customerId="";
				$eta="";
				$customerName="";
				$etd="";
				$tmpExchangeRate="";
				$exchangeRate="";
				$berth="";
				$qtytot20="";
				$qtytot40="";
				$qtytot45="";
				$remarks="";
                for($i=0;$i<count($bill_rslt);$i++){
				$invoiceDesc=$bill_rslt[$i]['invoiceDesc'];
				$draftNumber=$bill_rslt[$i]['draftNumber'];
				$created_user=$bill_rslt[$i]['created_user'];
				$billingDate=$bill_rslt[$i]['billingDate'];
				$payCustomerId=$bill_rslt[$i]['payCustomerId'];
				$ibVisitId=$bill_rslt[$i]['ibVisitId'];
				$payCustomername=$bill_rslt[$i]['payCustomername'];
				$ibCarrierName=$bill_rslt[$i]['ibCarrierName'];
				$customerId=$bill_rslt[$i]['customerId'];
				$eta=$bill_rslt[$i]['eta'];
				$customerName=$bill_rslt[$i]['customerName'];
				$etd=$bill_rslt[$i]['etd'];
				$tmpExchangeRate=$bill_rslt[$i]['exchangeRate'];
				$exchangeRate=number_format($tmpExchangeRate,4);
				$berth=$bill_rslt[$i]['berth'];
				$qtytot20=$bill_rslt[$i]['qtytot20'];
				$qtytot40=$bill_rslt[$i]['qtytot40'];
				$qtytot45=$bill_rslt[$i]['qtytot45'];
				$remarks=$bill_rslt[$i]['remarks'];
				}
                $data['invoiceDesc']=$invoiceDesc;				
                $data['draftNumber']=$draftNumber;
                $data['created_user']=$created_user;
                $data['billingDate']=$billingDate;
                $data['payCustomerId']=$payCustomerId;
                $data['ibVisitId']=$ibVisitId;
                $data['payCustomername']=$payCustomername;
                $data['ibCarrierName']=$ibCarrierName;
                $data['customerId']=$customerId;
                $data['eta']=$eta;
                $data['customerName']=$customerName;
                $data['etd']=$etd;
                $data['exchangeRate']=$exchangeRate;
                $data['berth']=$berth;
                $data['remarks']=$remarks;
                $data['qtytot20']=$qtytot20;
                $data['qtytot40']=$qtytot40;
                $data['qtytot45']=$qtytot45;
				
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$time="";
				for($j=0;$j<count($print_time);$j++){
				   $time=$print_time[$j]['Time'];
				}
				$data['time']=$time;
				$data['bill_rslt']=$bill_rslt;			
				$data['print_time']=$print_time;
				//pdf start here 
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_rptICDToPCTStatusChangeInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="containerBill/print_rptICDToPCTStatusChangeInvoice(CPAtoPCT)-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
				'', '', '', ''
				); // margin footer

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
				//pdf end here
			
			}
	
		}
	}

	//container bill end
	


	// container detail start
	function viewContainerDetail()
	{
		
		$session_id = $this->session->userdata('value');			
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
		    $draftNumber=$this->input->post('draftNumberDetail');
		    $draft_detail_view=$this->input->post('draft_detail_view');			
		    $printBtnValue=$this->input->post('printBtnValue');
						
			if($draft_detail_view=='pdfReeferInvoice')
			{
				$sql_detail="select 'Reefer Charges Bill' as invoiceDesc,draftNumber as draftNumber,
				DATE(billingDate) as created,mlo as customerId,mlo_name as customerName,agent_code as concustomerid,agent as concustomername,
				id as unitId,rotation as ibVisitId,vsl_name as ibCarrierName,
				size as isoLength,height as isoHeight,freight_kind as freightKind,rfr_connect as eventFrom,rfr_disconnect as eventTo,
				ceil((TIMESTAMPDIFF(SECOND, rfr_connect,rfr_disconnect))/3600) as hours,storage_days as quantity,vatperc as vatperc,'DRAFT' as status,yard
				from ".$this->get_table_name("mis_billing_details")." 
				where draftNumber='$draftNumber' 
				order by draftNumber";

				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
			
				$data['rslt_detail']=$rslt_detail;

				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_reeferdetail',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_reeferdetail',$data);	
				}
			}
			else if($draft_detail_view=='pdfLoadingInvoice')
			{
				
				$sql_detail="SELECT draftNumber AS draftNumber,rotation AS obVisitId,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,vsl_name AS obCarrierName,mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,id AS unitId,size AS isoLength,height AS isoHeight,freight_kind AS freightKind,vatperc AS vatperc, DATE(atd) AS landingDate,description,wpn,IF(wpn='W','PORT','DEPO') AS loc,'DRAFT' AS STATUS
				FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE 'Load%' ORDER BY draftNumber";
				
				$sql_detail_summary="SELECT 
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(w) AS  w,
				COUNT(p) AS  p,
				COUNT(n) AS n,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95
				 FROM
				(SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc=0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc!=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN wpn ='W' THEN 1
				ELSE NULL END) AS w,

				(CASE WHEN wpn ='P' THEN 1
				ELSE NULL END) AS p,

				(CASE WHEN wpn ='N' THEN 1
				ELSE NULL END) AS n,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = '20'  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = '20' THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = '40'  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = '40'  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = '45'  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = '45'  THEN 1
				ELSE NULL END) AS tot_45_95

				FROM ".$this->get_table_name("mis_billing_details")." 
				WHERE draftNumber='$draftNumber' ) AS destais";
				//$rslt_detail_summary=$this->bm->dataSelect($sql_detail_summary);
				$rslt_detail_summary=$this->bm->dataSelectDb2($sql_detail_summary);
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
				$data['rslt_detail_summary']=$rslt_detail_summary;
				$data['rslt_detail']=$rslt_detail;	
			
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_loadingdetail',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_loadingdetail',$data);	
				}	
				
			
			}
			else if($draft_detail_view=='pdfDischargeInvoice')
			{
			
                $sql_detail="select id as unitId,gkey,freight_kind as freightKind,'DRAFT' as status,'Container Discharging Bill' as invoiceDesc,draftNumber,vsl_name as ibCarrierName,
				mlo as customerId,mlo_name as customerName,agent_code as concustomerid,agent as concustomername,
				rotation as ibVisitId,date(billingDate) as created,size as isoLength,height as isoHeight,fcy_time_in as timeIn,
				wpn as equipment,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
						WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
						WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype
				from ".$this->get_table_name("mis_billing_details")."
				where draftNumber='$draftNumber' and description like'DISCH%'
				order by draftNumber";

				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
				for($t=0;$t<count($rslt_detail);$t++){
					$gkey="";
					$gkey=$rslt_detail[$t]['gkey'];
					$sql_detail2="select inv_goods.destination 
					from inv_unit 
					inner join inv_goods on inv_goods.gkey=inv_unit.goods
					where inv_unit.gkey=$gkey";
					$rslt_detail2=$this->bm->dataSelect($sql_detail2);
				}


				
				$sql_detail_summary="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(select (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				from
				(
				SELECT DISTINCT id as chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				if(destination not in('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') and freight_kind !='MTY',1,NULL) as LON
				from
				(
				select * from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber' and description like'DISCH%'
				) tbl
				) final";
				
				//$rslt_detail_summary=$this->bm->dataSelect($sql_detail_summary);
				//dataSelectdb2
				$rslt_detail_summary=$this->bm->dataSelectDb2($sql_detail_summary);
				$data['rslt_detail_summary']=$rslt_detail_summary;
				$data['rslt_detail']=$rslt_detail;

			
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_dischargedetail',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_dischargedetail',$data);	
				}	
				
			
			}
			else if($draft_detail_view=='pdfPangoanDischargeInvoice')		//PCT Discharge
			{
			     $sql_detail="SELECT id AS unitId,gkey,freight_kind AS freightKind,'DRAFT' AS status,'Container Discharging Bill (PCT)' AS invoiceDesc,draftNumber,vsl_name AS ibCarrierName,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,billingDate AS created,size AS isoLength,height AS isoHeight,
				wpn AS equipment,DATE(fcy_time_in) AS fcy_time_in,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
					WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
					WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype,
				(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billing_date

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' ORDER BY draftNumber";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);
				
				
		
			    $summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(SELECT (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				FROM
				(
				SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W' THEN 1 ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				IF(destination NOT IN('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') AND freight_kind !='MTY',1,NULL) AS LON
				FROM
				(
					SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND 
					description LIKE (
						CASE 
						WHEN invoice_type=112 THEN 'Load%' 
						WHEN invoice_type=120 THEN 'Load%' 
						WHEN invoice_type=108 THEN 'Discharging%' 
						WHEN invoice_type=128 THEN 'Discharging%' 
						ELSE 'Status%' 
						END 
						)
				) tbl
				) final";	
				
             	
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";	
				$print_time=$this->bm->dataSelectDb2($bill_print_time);		
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
			
				$data['print_time']=$print_time;
				$data['summary_bill_detail']=$summary_bill_detail;	
				$data['rslt_detail']=$rslt_detail;
				
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_containerBill_rptPangoanDischargingDraftDetailsInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
					
				$pdfFilePath ="print_containerBill_rptPangoanDischargingDraftDetailsInvoice-".time()."-download.pdf";

				$pdf = $this->m_pdf->load();
				
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', ''
					); // margin footer
					
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
						
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
								
			}
			
            else if($draft_detail_view=='pdfPangoanLoadingInvoice')	//PCT Loading...
			{		
				$sql_detail="select distinct id as unitId,id,fcy_time_in,fcy_time_out,gkey,freight_kind as freightKind,'DRAFT' as status,'Container Loading Bill (PCT)' as invoiceDesc,draftNumber,if(depo_date is null,draftNumber,concat(draftNumber,'R')) as billNumber,vsl_name as ibCarrierName, DATE_FORMAT(depo_date,'%d-%m-%Y') as depo_date,
				DATE_ADD(depo_date,INTERVAL 1 DAY) AS depoInt1Day,DATE_ADD(depo_date,INTERVAL 0 DAY) AS depo_Int_Zero_Day,DATE_FORMAT(port_date,'%d-%m-%Y') AS port_date,
				DATE_ADD(port_date,INTERVAL 1 DAY) AS portInt1Day,cl_date,DATE_ADD(cl_date,INTERVAL 4 DAY)AS clDateInt4Day,
				mlo as customerId,mlo_name as customerName,agent_code as concustomerid,agent as concustomername,
				rotation as ibVisitId,(select DATE_FORMAT(billing_date,'%d-%m-%Y') from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as created,size as isoLength,height as isoHeight, DATE_FORMAT(cl_date,'%d-%m-%Y') AS timeIn, DATE_FORMAT(fcy_time_out,'%d-%m-%Y') as timeOut,
				pre_imp_rot as imp_rot,
				DATE_FORMAT(pre_imp_ata,'%d-%m-%Y') as imp_ata,
				wpn as equipment,
				vatperc,iso_grp,
				(CASE
					WHEN iso_grp = 'UT' THEN 'OPEN TOP'
					WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
					WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
					WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
					ELSE NULL
				END) AS cnttype
				from ".$this->get_table_name("mis_billing_details")."
				where draftNumber='$draftNumber' and description like 'Load%' order by draftNumber";
                $rslt_detail=$this->bm->dataSelectDb2($sql_detail);
				
				$invoiceDesc="";
				$draftNumber="";
				$ibVisitId="";
				$created="";
				$ibCarrierName="";
				$customerId="";
				$concustomerid="";
				$customerName="";
				$concustomername="";
				for($i=0;$i<count($rslt_detail);$i++){
					$invoiceDesc=$rslt_detail[$i]['invoiceDesc'];
					$draftNumber=$rslt_detail[$i]['draftNumber'];
					$ibVisitId=$rslt_detail[$i]['ibVisitId'];
					$created=$rslt_detail[$i]['created'];
					$ibCarrierName=$rslt_detail[$i]['ibCarrierName'];
					$customerId=$rslt_detail[$i]['customerId'];
					$concustomerid=$rslt_detail[$i]['concustomerid'];
					$customerName=$rslt_detail[$i]['customerName'];
					$concustomername=$rslt_detail[$i]['concustomername'];

				}
					$data['invoiceDesc']=$invoiceDesc;
					$data['draftNumber']=$draftNumber;
					$data['ibVisitId']=$ibVisitId;
					$data['created']=$created;
					$data['ibCarrierName']=$ibCarrierName;
					$data['customerId']=$customerId;
					$data['concustomerid']=$concustomerid;
					$data['customerName']=$customerName;
					$data['concustomername']=$concustomername;
				
				    $summary_bill="SELECT
								COUNT(fcl_20_85) AS  fcl_20_85,
								COUNT(fcl_20_95) AS  fcl_20_95,
								COUNT(fcl_40_85) AS  fcl_40_85,
								COUNT(fcl_40_95) AS  fcl_40_95,
								COUNT(fcl_45_85) AS  fcl_45_85,
								COUNT(fcl_45_95) AS  fcl_45_95,

								COUNT(lcl_20_85) AS  lcl_20_85,
								COUNT(lcl_20_95) AS  lcl_20_95,
								COUNT(lcl_40_85) AS  lcl_40_85,
								COUNT(lcl_40_95) AS  lcl_40_95,
								COUNT(lcl_45_85) AS  lcl_45_85,
								COUNT(lcl_45_95) AS  lcl_45_95,

								COUNT(mty_20_85) AS  mty_20_85,
								COUNT(mty_20_95) AS  mty_20_95,
								COUNT(mty_40_85) AS  mty_40_85,
								COUNT(mty_40_95) AS  mty_40_95,
								COUNT(mty_45_85) AS  mty_45_85,
								COUNT(mty_45_95) AS  mty_45_95,
								COUNT(nonvat) AS  nonvat,
								COUNT(vat) AS  vat,
								COUNT(chargeEntityId) AS tot,
								COUNT(fcl) AS  fcl,
								COUNT(lcl) AS  lcl,
								COUNT(mty) AS  mty,
								COUNT(tot_20_85) AS  tot_20_85,
								COUNT(tot_20_95) AS  tot_20_95,
								COUNT(tot_40_85) AS  tot_40_85,
								COUNT(tot_40_95) AS  tot_40_95,
								COUNT(tot_45_85) AS  tot_45_85,
								COUNT(tot_45_95) AS  tot_45_95,
								COUNT(equipmentW) AS equipmentW,
								COUNT(equipmentP) AS equipmentP,
								COUNT(equipmentN) AS equipmentN,
								COUNT(LON) AS LON,
								(select (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
								from
								(
								SELECT DISTINCT id as chargeEntityId,

								(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_20_85,

								(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_20_95,

								(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_40_85,

								(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_40_95,

								(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_45_85,

								(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl_45_95,

								(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_20_85,

								(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_20_95,

								(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_40_85,

								(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_40_95,

								(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_45_85,

								(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl_45_95,

								(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_20_85,

								(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_20_95,

								(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_40_85,

								(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_40_95,

								(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_45_85,

								(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty_45_95,

								(CASE WHEN vatperc =0 THEN 1
								ELSE NULL END) AS nonvat,

								(CASE WHEN vatperc !=0 THEN 1
								ELSE NULL END) AS vat,

								(CASE WHEN freight_kind = 'FCL' THEN 1
								ELSE NULL END) AS fcl,

								(CASE WHEN freight_kind = 'LCL' THEN 1
								ELSE NULL END) AS lcl,

								(CASE WHEN freight_kind = 'EMPTY' THEN 1
								ELSE NULL END) AS mty,

								(CASE WHEN height='8.6' AND size = 20  THEN 1
								ELSE NULL END) AS tot_20_85,

								(CASE WHEN height='9.6' AND size = 20 THEN 1
								ELSE NULL END) AS tot_20_95,

								(CASE WHEN height='8.6' AND size = 40  THEN 1
								ELSE NULL END) AS tot_40_85,

								(CASE WHEN height='9.6' AND size = 40  THEN 1
								ELSE NULL END) AS tot_40_95,

								(CASE WHEN height='8.6' AND size = 45  THEN 1
								ELSE NULL END) AS tot_45_85,

								(CASE WHEN height='9.6' AND size = 45  THEN 1
								ELSE NULL END) AS tot_45_95,

								(CASE WHEN wpn='W'   THEN 1
								ELSE NULL END) AS equipmentW,

								(CASE WHEN wpn='P'   THEN 1
								ELSE NULL END) AS equipmentP,

								(CASE WHEN wpn='N'   THEN 1
								ELSE NULL END) AS equipmentN,
								if(destination not in('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') and freight_kind !='MTY',1,NULL) as LON
								from
								(
								select * from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber' and description like 'Load%'
								) tbl
								) final";	
                               							
								$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
								$print_time=$this->bm->dataSelectdb2($bill_print_time);
								
                                 	
                                $time="";									
								for($j=0;$j<count($print_time);$j++){
								   $time=$print_time[$j]['Time'];
								}
								
								
								$data['print_time']=$print_time;			
								$data['time']=$time;			
								$summary_bill_detail=$this->bm->dataSelectdb2($summary_bill);
								
								
								$equipmentW=0;
								$equipmentN=0;
								$equipmentP=0;
								$vat=0;
								$nonvat=0;
								$lon=0;
								$nlon=0;
								$fcl_20_85=0;
								$fcl_20_95=0;
								$fcl_40_85=0;
								$fcl_40_95=0;
								$fcl_45_85=0;
								$fcl_45_95=0;
								$fcl=0;
								$lcl_20_85=0;
								$lcl_20_95=0;
								$lcl_40_85=0;
								$lcl_40_95=0;
								$lcl_45_85=0;
								$lcl_45_95=0;
								$lcl=0;
								$mty_20_85=0;
								$mty_20_95=0;
								$mty_40_85=0;
								$mty_40_95=0;
								$mty_45_85=0;
								$mty_45_95=0;
								$mty=0;
								$tot_20_85=0;
								$tot_20_95=0;
								$tot_40_85=0;
								$tot_40_95=0;
								$tot_45_85=0;
								$tot_45_95=0;
								$tot=0;
                                for($t=0;$t<count($summary_bill_detail);$t++){
									$equipmentW=$summary_bill_detail[$t]['equipmentW'];
									$equipmentN=$summary_bill_detail[$t]['equipmentN'];
									$equipmentP=$summary_bill_detail[$t]['equipmentP'];
									$vat=$summary_bill_detail[$t]['vat'];
                                    $nonvat=$summary_bill_detail[$t]['nonvat'];	
                                    $lon=$summary_bill_detail[$t]['LON'];
                                    $nlon=$summary_bill_detail[$t]['NLON'];	
                                    $fcl_20_85=$summary_bill_detail[$t]['fcl_20_85'];	
                                    $fcl_20_95=$summary_bill_detail[$t]['fcl_20_95'];
                                    $fcl_40_85=$summary_bill_detail[$t]['fcl_40_85'];	
                                    $fcl_40_95=$summary_bill_detail[$t]['fcl_40_95'];	
                                    $fcl_45_85=$summary_bill_detail[$t]['fcl_45_85'];	
                                    $fcl_45_95=$summary_bill_detail[$t]['fcl_45_95'];
                                    $fcl=$summary_bill_detail[$t]['fcl'];	
                                    $lcl_20_85=$summary_bill_detail[$t]['lcl_20_85'];
                                    $lcl_20_95=$summary_bill_detail[$t]['lcl_20_95'];
                                    $lcl_40_85=$summary_bill_detail[$t]['lcl_40_85'];	
                                    $lcl_40_95=$summary_bill_detail[$t]['lcl_40_95'];	
                                    $lcl_45_85=$summary_bill_detail[$t]['lcl_45_85'];
                                    $lcl_45_95=$summary_bill_detail[$t]['lcl_45_95'];
                                    $lcl=$summary_bill_detail[$t]['lcl'];
                                    $mty_20_85=$summary_bill_detail[$t]['mty_20_85'];
									$mty_20_95=$summary_bill_detail[$t]['mty_20_95'];
									$mty_40_85=$summary_bill_detail[$t]['mty_40_85'];
									$mty_40_95=$summary_bill_detail[$t]['mty_40_95'];	
									$mty_45_85=$summary_bill_detail[$t]['mty_45_85'];
									$mty_45_95=$summary_bill_detail[$t]['mty_45_95'];
									$mty=$summary_bill_detail[$t]['mty'];
									$tot_20_85=$summary_bill_detail[$t]['tot_20_85'];
									$tot_20_95=$summary_bill_detail[$t]['tot_20_95'];
									$tot_40_85=$summary_bill_detail[$t]['tot_40_85'];
									$tot_40_95=$summary_bill_detail[$t]['tot_40_95']; 
									$tot_45_85=$summary_bill_detail[$t]['tot_45_85'];
									$tot_45_95=$summary_bill_detail[$t]['tot_45_95'];
									$tot=$summary_bill_detail[$t]['tot'];
								}	
								$data['equipmentW']=$equipmentW;
								$data['equipmentN']=$equipmentN;
								$data['equipmentP']=$equipmentP;
								$data['vat']=$vat;
								$data['nonvat']=$nonvat;
								$data['lon']=$lon;
								$data['nlon']=$nlon;
								$data['fcl_20_85']=$fcl_20_85;
								$data['fcl_20_95']=$fcl_20_95;
								$data['fcl_40_85']=$fcl_40_85;
								$data['fcl_40_95']=$fcl_40_95;
								$data['fcl_45_85']=$fcl_45_85;
								$data['fcl_45_95']=$fcl_45_95;
								$data['fcl']=$fcl;
								$data['lcl_20_85']=$lcl_20_85;
								$data['lcl_20_95']=$lcl_20_95;
								$data['lcl_40_85']=$lcl_40_85;
								$data['lcl_40_95']=$lcl_40_95;
								$data['lcl_45_85']=$lcl_45_85;
								$data['lcl_45_95']=$lcl_45_95;
								$data['lcl']=$lcl;
								$data['mty_20_85']=$mty_20_85;
								$data['mty_20_95']=$mty_20_95;
								$data['mty_40_85']=$mty_40_85;
								$data['mty_40_95']=$mty_40_95;
								$data['mty_45_85']=$mty_45_85;
								$data['mty_45_95']=$mty_45_95;
								$data['mty']=$mty;
								$data['tot_20_85']=$tot_20_85;
								$data['tot_20_95']=$tot_20_95;
								$data['tot_40_85']=$tot_40_85;
								$data['tot_40_95']=$tot_40_95;
								$data['tot_45_85']=$tot_45_85;
								$data['tot_45_95']=$tot_45_95;
								$data['tot']=$tot;
								
								
								$data['summary_bill_detail']=$summary_bill_detail;	
								$data['rslt_detail']=$rslt_detail;	
									
								//pdf start here
							
								$this->load->library('m_pdf');
								$html=$this->load->view('containerBill/print_containerBill_rptPangoanLoadingDraftDetailsInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
								$pdfFilePath ="print_containerBill_rptPangoanLoadingDraftDetailsInvoice-".time()."-download.pdf";
								$pdf = $this->m_pdf->load();
							
								//$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
								
								$pdf->AddPage('P', // L - landscape, P - portrait
									'', '', '', ''
									); // margin footer
									
								//$pdf->WriteHTML($stylesheet,1);
								$pdf->WriteHTML($html,2);	
								$pdf->Output($pdfFilePath, "I"); // For Show Pdf*/
								//pdf end here
								
								
								//$this->load->view('containerBill/print_containerBill_rptPangoanLoadingDraftDetailsInvoice',$data);
							
														
			}	
			else if($draft_detail_view=='pdfDraftICDInvoice')
			{  
					$sql_detail="SELECT draftNumber,'DRAFT' AS STATUS,description AS invoiceDesc,id AS unitId,freight_kind AS freightKind,height AS isoHeight,size AS isoLength,
							vsl_name AS ibCarrierName,rotation AS ibVisitId,
							vatperc,DATE(billingDate) AS created,
							agent AS concustomername,
							agent_code AS concustomerid,
							mlo_name AS customerName,mlo AS customerId,
							DATE(fcy_time_in) AS timeIn,DATE(fcy_time_out) AS timeOut,ctmsmis.mis_billing_details.gkey,seal_nbr1

							from ".$this->get_table_name("mis_billing_details")."
							where draftNumber='$draftNumber' and description like 'LIFT%'";

							$rslt_detail=$this->bm->dataSelectdb2($sql_detail);
							$gkey="";
                             
							 $j=0;
							 $result;
							for($i=0;$i<count($rslt_detail);$i++){
								$gkey=$rslt_detail[$i]['gkey'];
                                $sql_detail2="SELECT last_pos_locid FROM inv_unit_fcy_visit  WHERE unit_gkey=$gkey";
								$rslt_detail2=$this->bm->dataSelect($sql_detail2);

								if(count($rslt_detail2)>0){
									$result[$j]['draftNumber']=$rslt_detail[$i]['draftNumber'];
									$result[$j]['STATUS']=$rslt_detail[$i]['STATUS'];
									$result[$j]['invoiceDesc']=$rslt_detail[$i]['invoiceDesc'];
									$result[$j]['unitId']=$rslt_detail[$i]['unitId'];
									$result[$j]['freightKind']=$rslt_detail[$i]['freightKind'];
									$result[$j]['freightKind']=$rslt_detail[$i]['isoHeight'];
									$result[$j]['isoLength']=$rslt_detail[$i]['isoLength'];
									$result[$j]['ibCarrierName']=$rslt_detail[$i]['ibCarrierName'];
									$result[$j]['ibVisitId']=$rslt_detail[$i]['ibVisitId'];
									$result[$j]['vatperc']=$rslt_detail[$i]['vatperc'];
									$result[$j]['created']=$rslt_detail[$i]['created'];
									$result[$j]['concustomername']=$rslt_detail[$i]['concustomername'];
									$result[$j]['concustomerid']=$rslt_detail[$i]['concustomerid'];
									$result[$j]['customerName']=$rslt_detail[$i]['customerName'];
									$result[$j]['customerId']=$rslt_detail[$i]['customerId'];
									$result[$j]['timeIn']=$rslt_detail[$i]['timeIn'];
									$result[$j]['timeOut']=$rslt_detail[$i]['timeOut'];
									$result[$j]['timeOut']=$rslt_detail[$i]['timeOut'];
									$result[$j]['gkey']=$rslt_detail[$i]['gkey'];
									$result[$j]['seal_nbr1']=$rslt_detail[$i]['seal_nbr1'];
									$result[$j]['wagon']=$strResult2[$i]['LAST_POS_LOCID'];//wagon
									$j++;
									
								}
							}
							
				$summary_bill="select 
							count(fcl_20_85) as  fcl_20_85,
							count(fcl_20_95) as  fcl_20_95,
							count(fcl_40_85) as  fcl_40_85,
							count(fcl_40_95) as  fcl_40_95,
							count(fcl_45_85) as  fcl_45_85,
							count(fcl_45_95) as  fcl_45_95,

							count(lcl_20_85) as  lcl_20_85,
							count(lcl_20_95) as  lcl_20_95,
							count(lcl_40_85) as  lcl_40_85,
							count(lcl_40_95) as  lcl_40_95,
							count(lcl_45_85) as  lcl_45_85,
							count(lcl_45_95) as  lcl_45_95,

							count(mty_20_85) as  mty_20_85,
							count(mty_20_95) as  mty_20_95,
							count(mty_40_85) as  mty_40_85,
							count(mty_40_95) as  mty_40_95,
							count(mty_45_85) as  mty_45_85,
							count(mty_45_95) as  mty_45_95,

							count(chargeEntityId) as tot,
							count(fcl) as  fcl,
							count(lcl) as  lcl,
							count(mty) as  mty,
							count(tot_20_85) as  tot_20_85,
							count(tot_20_95) as  tot_20_95,
							count(tot_40_85) as  tot_40_85,
							count(tot_40_95) as  tot_40_95,
							count(tot_45_85) as  tot_45_85,
							count(tot_45_95) as  tot_45_95
							 from
							(select distinct id as chargeEntityId,

							(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_20_85,

							(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_20_95,

							(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_40_85,

							(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_40_95,

							(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_45_85,

							(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl_45_95,

							(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_20_85,

							(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_20_95,

							(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_40_85,

							(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_40_95,

							(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_45_85,

							(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl_45_95,

							(CASE WHEN height='8.6' AND size = '20' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_20_85,

							(CASE WHEN height='9.6' AND size = '20' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_20_95,

							(CASE WHEN height='8.6' AND size = '40' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_40_85,

							(CASE WHEN height='9.6' AND size = '40' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_40_95,

							(CASE WHEN height='8.6' AND size = '45' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_45_85,

							(CASE WHEN height='9.6' AND size = '45' AND freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty_45_95,

							(CASE WHEN freight_kind = 'FCL' THEN 1
							ELSE NULL END) AS fcl,

							(CASE WHEN freight_kind = 'LCL' THEN 1
							ELSE NULL END) AS lcl,

							(CASE WHEN freight_kind = 'MTY' THEN 1
							ELSE NULL END) AS mty,


							(CASE WHEN height='8.6' AND size = '20'  THEN 1
							ELSE NULL END) AS tot_20_85,

							(CASE WHEN height='9.6' AND size = '20' THEN 1
							ELSE NULL END) AS tot_20_95,

							(CASE WHEN height='8.6' AND size = '40'  THEN 1
							ELSE NULL END) AS tot_40_85,

							(CASE WHEN height='9.6' AND size = '40'  THEN 1
							ELSE NULL END) AS tot_40_95,

							(CASE WHEN height='8.6' AND size = '45'  THEN 1
							ELSE NULL END) AS tot_45_85,

							(CASE WHEN height='9.6' AND size = '45'  THEN 1
							ELSE NULL END) AS tot_45_95

							from ".$this->get_table_name("mis_billing_details")."
							where draftNumber='$draftNumber' and description like 'LIFT%' ) as destais";	
				
				$summary_bill_detail=$this->bm->dataSelectdb2($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectdb2($bill_print_time);	
				$data['print_time']=$print_time;	
	
				 //$data['rslt_detail']=$rslt_detail;
				 $data['rslt_detail']=$result;
			
			    //views are missing from ctmsmis
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_rptICDDraftDetailsInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_rptICDDraftDetailsInvoice',$data);	
				}	
			
			}	
			else if($draft_detail_view=='pdfPangoanStatusChangeInvoice')	//Status Change (CPA to PCT)
			{
				
			  $sql_detail="SELECT DISTINCT id AS unitId,gkey,freight_kind AS freightKind,'DRAFT' AS STATUS,'Status Change Bill (CPA to PCT)' AS invoiceDesc,draftNumber,IF(depo_date IS NULL,draftNumber,CONCAT(draftNumber,'R')) AS billNumber,vsl_name AS ibCarrierName,depo_date,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,(SELECT DATE_FORMAT(billing_date, '%d-%m-%Y') AS billing_date FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,size AS isoLength,LEFT(height,3) AS isoHeight,DATE_FORMAT(cl_date, '%d-%m-%Y') AS timeIn,
				DATE_FORMAT(fcy_time_out, '%d-%m-%Y') AS timeOut, pre_imp_rot AS imp_rot, 
				DATE_FORMAT(pre_imp_ata, '%d-%m-%Y')  AS imp_ata,
				IF(DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days,
				wpn AS equipment,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
						WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
						WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' AND description LIKE 'Status%' ORDER BY draftNumber";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
				       
				$summary_bill="SELECT
					COUNT(fcl_20_85) AS  fcl_20_85,
					COUNT(fcl_20_95) AS  fcl_20_95,
					COUNT(fcl_40_85) AS  fcl_40_85,
					COUNT(fcl_40_95) AS  fcl_40_95,
					COUNT(fcl_45_85) AS  fcl_45_85,
					COUNT(fcl_45_95) AS  fcl_45_95,

					COUNT(lcl_20_85) AS  lcl_20_85,
					COUNT(lcl_20_95) AS  lcl_20_95,
					COUNT(lcl_40_85) AS  lcl_40_85,
					COUNT(lcl_40_95) AS  lcl_40_95,
					COUNT(lcl_45_85) AS  lcl_45_85,
					COUNT(lcl_45_95) AS  lcl_45_95,

					COUNT(mty_20_85) AS  mty_20_85,
					COUNT(mty_20_95) AS  mty_20_95,
					COUNT(mty_40_85) AS  mty_40_85,
					COUNT(mty_40_95) AS  mty_40_95,
					COUNT(mty_45_85) AS  mty_45_85,
					COUNT(mty_45_95) AS  mty_45_95,
					COUNT(nonvat) AS  nonvat,
					COUNT(vat) AS  vat,
					COUNT(chargeEntityId) AS tot,
					COUNT(fcl) AS  fcl,
					COUNT(lcl) AS  lcl,
					COUNT(mty) AS  mty,
					COUNT(tot_20_85) AS  tot_20_85,
					COUNT(tot_20_95) AS  tot_20_95,
					COUNT(tot_40_85) AS  tot_40_85,
					COUNT(tot_40_95) AS  tot_40_95,
					COUNT(tot_45_85) AS  tot_45_85,
					COUNT(tot_45_95) AS  tot_45_95,
					COUNT(equipmentW) AS equipmentW,
					COUNT(equipmentP) AS equipmentP,
					COUNT(equipmentN) AS equipmentN,
					COUNT(LON) AS LON,
					(select (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
					from
					(
					SELECT DISTINCT id as chargeEntityId,

					(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_85,

					(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_95,

					(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_85,

					(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_95,

					(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_85,

					(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_95,

					(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_85,

					(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_95,

					(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_85,

					(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_95,

					(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_85,

					(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_95,

					(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_85,

					(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_95,

					(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_85,

					(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_95,

					(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_85,

					(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_95,

					(CASE WHEN vatperc =0 THEN 1
					ELSE NULL END) AS nonvat,

					(CASE WHEN vatperc !=0 THEN 1
					ELSE NULL END) AS vat,

					(CASE WHEN freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl,

					(CASE WHEN freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl,

					(CASE WHEN freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty,


					(CASE WHEN height='8.6' AND size = 20  THEN 1
					ELSE NULL END) AS tot_20_85,

					(CASE WHEN height='9.6' AND size = 20 THEN 1
					ELSE NULL END) AS tot_20_95,

					(CASE WHEN height='8.6' AND size = 40  THEN 1
					ELSE NULL END) AS tot_40_85,

					(CASE WHEN height='9.6' AND size = 40  THEN 1
					ELSE NULL END) AS tot_40_95,

					(CASE WHEN height='8.6' AND size = 45  THEN 1
					ELSE NULL END) AS tot_45_85,

					(CASE WHEN height='9.6' AND size = 45  THEN 1
					ELSE NULL END) AS tot_45_95,

					(CASE WHEN wpn='W'   THEN 1
					ELSE NULL END) AS equipmentW,

					(CASE WHEN wpn='P'   THEN 1
					ELSE NULL END) AS equipmentP,

					(CASE WHEN wpn='N'   THEN 1
					ELSE NULL END) AS equipmentN,
					if(destination not in('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') and freight_kind !='MTY',1,NULL) as LON
					from
					(
					select * from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber' and description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' ELSE 'Status%' END )
					) tbl
					) final";
				    $summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				    $data['summary_bill_detail']=$summary_bill_detail;				
					$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
					$print_time=$this->bm->dataSelectDb2($bill_print_time);	
					$invoiceDesc="";
					$draftNumber="";
					$ibVisitId="";
					$created="";
					$ibCarrierName="";
					$customerId="";
					$concustomerid="";
					$customerName="";
					$concustomername="";
					$gkey="";
					$preloc="";
					
			       
					for($i=0;$i<count($rslt_detail);$i++){	 
					$invoiceDesc=$rslt_detail[$i]['invoiceDesc'];
					$draftNumber=$rslt_detail[$i]['draftNumber'];
					$ibVisitId=$rslt_detail[$i]['ibVisitId'];
					$created=$rslt_detail[$i]['created'];
					$ibCarrierName=$rslt_detail[$i]['ibCarrierName'];
					$customerId=$rslt_detail[$i]['customerId'];
					$concustomerid=$rslt_detail[$i]['concustomerid'];
					$customerName=$rslt_detail[$i]['customerName'];
					$concustomername=$rslt_detail[$i]['concustomername'];
				
					}
					
					$data['print_time']=$print_time;	
					$data['rslt_detail']=$rslt_detail;
					$data['invoiceDesc']=$invoiceDesc;
					$data['draftNumber']=$draftNumber;
					$data['ibVisitId']=$ibVisitId;
					$data['created']=$created;
					$data['ibCarrierName']=$ibCarrierName;
					$data['customerId']=$customerId;
					$data['concustomerid']=$concustomerid;
					$data['customerName']=$customerName;
					$data['concustomername']=$concustomername;
					$data['preloc']=$preloc;
					
					//pdf start here 
					$this->load->library('m_pdf');
					$html=$this->load->view('containerBill/print_containerBill_statusChangeRptPangaonLoadingDraftDetailsInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
					$pdfFilePath ="containerBill/print_containerBill_statusChangeRptPangaonLoadingDraftDetailsInvoice-".time()."-download.pdf";
					$pdf = $this->m_pdf->load();
					$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
					$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', ''
					); // margin footer

					$pdf->WriteHTML($stylesheet,1);
					$pdf->WriteHTML($html,2);
					$pdf->Output($pdfFilePath, "I"); // For Show Pdf
					
					//pdf End here 
					
				   /*if($printBtnValue=="0")
					{
						$this->load->view('containerBill/containerBill_statusChangeRptPangaonLoadingDraftDetailsInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
					}
					else
					{
						$this->load->view('containerBill/print_containerBill_statusChangeRptPangaonLoadingDraftDetailsInvoice',$data);	
					}*/	
				
		   
			}
			else if($draft_detail_view=='pdfStatucChangeCPAToICDInvoice')
			{
				$sql_detail="SELECT DISTINCT id AS unitId,draftNumber AS draftNumber,
				   'STATUS CHANGE INVOICE (CPA TO ICD)' AS invoiceList,vsl_name AS ibCarrierName,
				   rotation AS ibVisitId,mlo AS customerId,mlo_name AS customerName,DATE_FORMAT(billingDate, '%d-%m-%Y') AS created,
				   agent_code AS concustomerid,agent AS concustomername,'DRAFT' AS STATUS,size AS isoLength,
				   LEFT(height,3) AS isoHeight,freight_kind AS freightKind,DATE_FORMAT(fcy_time_in, '%d-%m-%Y') AS timeIn,cl_date AS cl_date,
				   DATE_ADD(cl_date,INTERVAL 4 DAY) AS eventFrom,DATE_FORMAT(fcy_time_out, '%d-%m-%Y') AS eventTo,
				   DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1 AS days,vatperc AS vatperc
				   FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber=$draftNumber";
			
				$summary_bill="SELECT
					COUNT(fcl_20_85) AS  fcl_20_85,
					COUNT(fcl_20_95) AS  fcl_20_95,
					COUNT(fcl_40_85) AS  fcl_40_85,
					COUNT(fcl_40_95) AS  fcl_40_95,
					COUNT(fcl_45_85) AS  fcl_45_85,
					COUNT(fcl_45_95) AS  fcl_45_95,

					COUNT(lcl_20_85) AS  lcl_20_85,
					COUNT(lcl_20_95) AS  lcl_20_95,
					COUNT(lcl_40_85) AS  lcl_40_85,
					COUNT(lcl_40_95) AS  lcl_40_95,
					COUNT(lcl_45_85) AS  lcl_45_85,
					COUNT(lcl_45_95) AS  lcl_45_95,

					COUNT(mty_20_85) AS  mty_20_85,
					COUNT(mty_20_95) AS  mty_20_95,
					COUNT(mty_40_85) AS  mty_40_85,
					COUNT(mty_40_95) AS  mty_40_95,
					COUNT(mty_45_85) AS  mty_45_85,
					COUNT(mty_45_95) AS  mty_45_95,
					COUNT(chargeEntityId) AS  vat,
					COUNT(chargeEntityId) AS tot,
					COUNT(fcl) AS  fcl,
					COUNT(lcl) AS  lcl,
					COUNT(mty) AS  mty,
					COUNT(tot_20_85) AS  tot_20_85,
					COUNT(tot_20_95) AS  tot_20_95,
					COUNT(tot_40_85) AS  tot_40_85,
					COUNT(tot_40_95) AS  tot_40_95,
					COUNT(tot_45_85) AS  tot_45_85,
					COUNT(tot_45_95) AS  tot_45_95
					 FROM
					(SELECT DISTINCT  id AS chargeEntityId,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_95,


					(CASE WHEN  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl,

					(CASE WHEN  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl,

					(CASE WHEN  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty,


					(CASE WHEN  height='8.600' AND  size = '20'  THEN 1
					ELSE NULL END) AS tot_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' THEN 1
					ELSE NULL END) AS tot_20_95,

					(CASE WHEN  height='8.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_85,

					(CASE WHEN  height='9.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_95,

					(CASE WHEN  height='8.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_85,

					(CASE WHEN  height='9.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_95

					FROM ".$this->get_table_name("mis_billing_details")."
					WHERE  draftNumber='$draftNumber') AS cancel";
					$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
					$data['summary_bill_detail']=$summary_bill_detail;				
			
					$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
					$print_time=$this->bm->dataSelectDb2($bill_print_time);	
					$data['print_time']=$print_time;	
					$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
			        $data['rslt_detail']=$rslt_detail;
					
					   // views are missing
					   if($printBtnValue=="0")
						{
							$this->load->view('containerBill/containerBill_rptStatusDraftDetailsInvoiceCPAtoICD',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
						}
						else
						{
							$this->load->view('containerBill/print_containerBill_rptStatusDraftDetailsInvoiceCPAtoICD',$data);	
						}	
						

			}	
			else if($draft_detail_view=='pdfStatucChangeLCLToFCLInvoice')
			{
				$sql_detail="SELECT DISTINCT id AS unitId,draftNumber AS draftNumber,'STATUS CHANGE INVOICE (LCL TO FCL)' AS invoiceList,
				vsl_name AS ibCarrierName,rotation AS ibVisitId,mlo AS customerId,mlo_name AS customerName,
				DATE_FORMAT(billingDate, '%d-%m-%Y') AS created,agent_code AS concustomerid,agent AS concustomername,'DRAFT' AS STATUS,
				size AS isoLength,LEFT(height,3) AS isoHeight,freight_kind AS freightKind,DATE_FORMAT(fcy_time_in, '%Y-%m-%d')  AS timeIn,
				cl_date AS cl_date,DATE_ADD(cl_date,INTERVAL 4 DAY) AS eventFrom,DATE_FORMAT(fcy_time_out, '%Y-%m-%d') AS eventTo,
				DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1 AS days,vatperc AS vatperc
				FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber=$draftNumber";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
			
				$summary_bill="SELECT
					COUNT(fcl_20_85) AS  fcl_20_85,
					COUNT(fcl_20_95) AS  fcl_20_95,
					COUNT(fcl_40_85) AS  fcl_40_85,
					COUNT(fcl_40_95) AS  fcl_40_95,
					COUNT(fcl_45_85) AS  fcl_45_85,
					COUNT(fcl_45_95) AS  fcl_45_95,

					COUNT(lcl_20_85) AS  lcl_20_85,
					COUNT(lcl_20_95) AS  lcl_20_95,
					COUNT(lcl_40_85) AS  lcl_40_85,
					COUNT(lcl_40_95) AS  lcl_40_95,
					COUNT(lcl_45_85) AS  lcl_45_85,
					COUNT(lcl_45_95) AS  lcl_45_95,

					COUNT(mty_20_85) AS  mty_20_85,
					COUNT(mty_20_95) AS  mty_20_95,
					COUNT(mty_40_85) AS  mty_40_85,
					COUNT(mty_40_95) AS  mty_40_95,
					COUNT(mty_45_85) AS  mty_45_85,
					COUNT(mty_45_95) AS  mty_45_95,
					COUNT(chargeEntityId) AS  vat,
					COUNT(chargeEntityId) AS tot,
					COUNT(fcl) AS  fcl,
					COUNT(lcl) AS  lcl,
					COUNT(mty) AS  mty,
					COUNT(tot_20_85) AS  tot_20_85,
					COUNT(tot_20_95) AS  tot_20_95,
					COUNT(tot_40_85) AS  tot_40_85,
					COUNT(tot_40_95) AS  tot_40_95,
					COUNT(tot_45_85) AS  tot_45_85,
					COUNT(tot_45_95) AS  tot_45_95
					 FROM
					(SELECT DISTINCT  id AS chargeEntityId,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_95,


					(CASE WHEN  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl,

					(CASE WHEN  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl,

					(CASE WHEN  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty,


					(CASE WHEN  height='8.600' AND  size = '20'  THEN 1
					ELSE NULL END) AS tot_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' THEN 1
					ELSE NULL END) AS tot_20_95,

					(CASE WHEN  height='8.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_85,

					(CASE WHEN  height='9.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_95,

					(CASE WHEN  height='8.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_85,

					(CASE WHEN  height='9.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_95

					FROM ".$this->get_table_name("mis_billing_details")."
					WHERE  draftNumber='$draftNumber') AS cancel";
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
		
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['print_time']=$print_time;	
			
			     $data['rslt_detail']=$rslt_detail;
				
				//views are missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_rptStatusDraftDetailsInvoiceLCLtoFCL',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_rptStatusDraftDetailsInvoiceLCLtoFCL',$data);	
				}	
                
               
			}	
			else if($draft_detail_view=='pdfStatucChangeFCLToLCLInvoice')
			{
				   $sql_detail="SELECT DISTINCT id AS unitId,draftNumber AS draftNumber,
				   'STATUS CHANGE INVOICE (FCL TO LCL)' AS invoiceList,vsl_name AS ibCarrierName,
				   rotation AS ibVisitId,mlo AS customerId,mlo_name AS customerName,
				   DATE_FORMAT(billingDate, '%Y-%m-%d') AS created,agent_code AS concustomerid,agent AS concustomername,
				   'DRAFT' AS STATUS,size AS isoLength,LEFT(height,3) AS isoHeight,freight_kind AS freightKind,
				   DATE_FORMAT(fcy_time_in, '%Y-%m-%d') AS timeIn,cl_date AS cl_date,
				   DATE_ADD(cl_date,INTERVAL 4 DAY) AS eventFrom,DATE_FORMAT(fcy_time_out, '%Y-%m-%d') AS eventTo,
				   DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1 AS days,vatperc AS vatperc
				   FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber'";
				
				    $summary_bill="SELECT
					COUNT(fcl_20_85) AS  fcl_20_85,
					COUNT(fcl_20_95) AS  fcl_20_95,
					COUNT(fcl_40_85) AS  fcl_40_85,
					COUNT(fcl_40_95) AS  fcl_40_95,
					COUNT(fcl_45_85) AS  fcl_45_85,
					COUNT(fcl_45_95) AS  fcl_45_95,

					COUNT(lcl_20_85) AS  lcl_20_85,
					COUNT(lcl_20_95) AS  lcl_20_95,
					COUNT(lcl_40_85) AS  lcl_40_85,
					COUNT(lcl_40_95) AS  lcl_40_95,
					COUNT(lcl_45_85) AS  lcl_45_85,
					COUNT(lcl_45_95) AS  lcl_45_95,

					COUNT(mty_20_85) AS  mty_20_85,
					COUNT(mty_20_95) AS  mty_20_95,
					COUNT(mty_40_85) AS  mty_40_85,
					COUNT(mty_40_95) AS  mty_40_95,
					COUNT(mty_45_85) AS  mty_45_85,
					COUNT(mty_45_95) AS  mty_45_95,
					COUNT(chargeEntityId) AS  vat,
					COUNT(chargeEntityId) AS tot,
					COUNT(fcl) AS  fcl,
					COUNT(lcl) AS  lcl,
					COUNT(mty) AS  mty,
					COUNT(tot_20_85) AS  tot_20_85,
					COUNT(tot_20_95) AS  tot_20_95,
					COUNT(tot_40_85) AS  tot_40_85,
					COUNT(tot_40_95) AS  tot_40_95,
					COUNT(tot_45_85) AS  tot_45_85,
					COUNT(tot_45_95) AS  tot_45_95
					 FROM
					(SELECT DISTINCT  id AS chargeEntityId,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl_45_95,

					(CASE WHEN  height='8.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_20_95,

					(CASE WHEN  height='8.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_85,

					(CASE WHEN  height='9.600' AND  size = '40' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_40_95,

					(CASE WHEN  height='8.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_85,

					(CASE WHEN  height='9.600' AND  size = '45' AND  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty_45_95,


					(CASE WHEN  freight_kind = 'FCL' THEN 1
					ELSE NULL END) AS fcl,

					(CASE WHEN  freight_kind = 'LCL' THEN 1
					ELSE NULL END) AS lcl,

					(CASE WHEN  freight_kind = 'MTY' THEN 1
					ELSE NULL END) AS mty,


					(CASE WHEN  height='8.600' AND  size = '20'  THEN 1
					ELSE NULL END) AS tot_20_85,

					(CASE WHEN  height='9.600' AND  size = '20' THEN 1
					ELSE NULL END) AS tot_20_95,

					(CASE WHEN  height='8.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_85,

					(CASE WHEN  height='9.600' AND  size = '40'  THEN 1
					ELSE NULL END) AS tot_40_95,

					(CASE WHEN  height='8.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_85,

					(CASE WHEN  height='9.600' AND  size = '45'  THEN 1
					ELSE NULL END) AS tot_45_95

					FROM ".$this->get_table_name("mis_billing_details")."
					WHERE  draftNumber='$draftNumber') AS cancel";
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
			
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['print_time']=$print_time;
				
				 $rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
			     $data['rslt_detail']=$rslt_detail;

				    //views are missing
					if($printBtnValue=="0")
					{
						$this->load->view('containerBill/containerBill_rptStatusDraftDetailsInvoiceFCLtoLCL',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
					}
					else
					{
						$this->load->view('containerBill/print_containerBill_rptStatusDraftDetailsInvoiceFCLtoLCL',$data);	
					}	
					  
								
			}
			else if($draft_detail_view=='pdfPangoanStatusChangePCTToCPAInvoice')		//Status Change (PCT to CPA)
			{
				
				$sql_detail="SELECT DISTINCT id AS unitId,gkey,freight_kind AS freightKind,
				'DRAFT' AS STATUS,'Status Change Bill (PCT to CPA)' AS invoiceDesc,draftNumber,
				IF(depo_date IS NULL,draftNumber,CONCAT(draftNumber,'R')) AS billNumber,vsl_name AS ibCarrierName,depo_date,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,(SELECT DATE_FORMAT(billing_date, '%d-%m-%Y') AS billing_date 
				FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,size AS isoLength,LEFT(height,3) AS isoHeight,DATE_FORMAT(cl_date, '%d-%m-%Y') AS timeIn,fcy_time_out AS timeOut,
				pre_imp_rot AS imp_rot,
				DATE_FORMAT(fcy_time_in, '%d-%m-%Y') AS imp_ata,
				IF(DATEDIFF(cl_date,IFNULL(fcy_time_in,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(cl_date,IFNULL(fcy_time_in,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days,
				wpn AS equipment,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
						WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
						WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' AND description LIKE 'Status%' ORDER BY draftNumber";
                        
				$summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(SELECT (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				FROM
				(
				SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				IF(destination NOT IN('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') AND freight_kind !='MTY',1,NULL) AS LON
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' ELSE 'Status%' END )
				) tbl
				) final";
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				$equipmentW=0;
				$equipmentN=0;
				$equipmentP=0;
				$vat=0;
				$nonvat=0;
				$lon=0;
				$nlon=0;
				$fcl_20_85=0;
				$fcl_20_95=0;
				$fcl_40_85=0;
				$fcl_40_95=0;
				$fcl_45_85=0;
				$fcl_45_95=0;
				$fcl=0;
				$lcl_20_85=0;
				$lcl_20_95=0;
				$lcl_40_85=0;
				$lcl_40_95=0;
				$lcl_45_85=0;
				$lcl_45_95=0;
				$lcl=0;
				$mty_20_85=0;
				$mty_20_95=0;
				$mty_40_85=0;
				$mty_40_95=0;
				$mty_45_85=0;
				$mty_45_95=0;
				$mty=0;
				$tot_20_85=0;
				$tot_20_95=0;
				$tot_40_85=0;
				$tot_40_95=0;
				$tot_45_85=0;
				$tot_45_95=0;
				$tot=0;
				
				for($j=0;$j<count($summary_bill_detail);$j++){
					$equipmentW=$summary_bill_detail[$j]['equipmentW'];
					$equipmentN=$summary_bill_detail[$j]['equipmentN'];
					$equipmentP=$summary_bill_detail[$j]['equipmentP'];
					$vat=$summary_bill_detail[$j]['vat'];
					$nonvat=$summary_bill_detail[$j]['nonvat'];
					$lon=$summary_bill_detail[$j]['LON'];
					$nlon=$summary_bill_detail[$j]['NLON'];
					$fcl_20_85=$summary_bill_detail[$j]['fcl_20_85'];
					$fcl_20_95=$summary_bill_detail[$j]['fcl_20_95'];
					$fcl_40_85=$summary_bill_detail[$j]['fcl_40_85'];
					$fcl_40_95=$summary_bill_detail[$j]['fcl_40_95'];
					$fcl_45_85=$summary_bill_detail[$j]['fcl_45_85'];
					$fcl_45_95=$summary_bill_detail[$j]['fcl_45_95'];
					$fcl=$summary_bill_detail[$j]['fcl'];
					$lcl_20_85=$summary_bill_detail[$j]['lcl_20_85'];
					$lcl_20_95=$summary_bill_detail[$j]['lcl_20_95'];
					$lcl_40_85=$summary_bill_detail[$j]['lcl_40_85'];
					$lcl_40_95=$summary_bill_detail[$j]['lcl_40_95'];
					$lcl_45_85=$summary_bill_detail[$j]['lcl_45_85'];
					$lcl_45_95=$summary_bill_detail[$j]['lcl_45_95'];
					$lcl=$summary_bill_detail[$j]['lcl'];
					$mty_20_85=$summary_bill_detail[$j]['mty_20_85'];
					$mty_20_95=$summary_bill_detail[$j]['mty_20_95'];
					$mty_40_85=$summary_bill_detail[$j]['mty_40_85'];
					$mty_40_95=$summary_bill_detail[$j]['mty_40_95'];
					$mty_45_85=$summary_bill_detail[$j]['mty_45_85'];
					$mty_45_95=$summary_bill_detail[$j]['mty_45_95'];
					$mty=$summary_bill_detail[$j]['mty'];
					$tot_20_85=$summary_bill_detail[$j]['tot_20_85'];
					$tot_20_95=$summary_bill_detail[$j]['tot_20_95'];
					$tot_40_85=$summary_bill_detail[$j]['tot_40_85'];
					$tot_40_95=$summary_bill_detail[$j]['tot_40_95'];
					$tot_45_85=$summary_bill_detail[$j]['tot_45_85'];
					$tot_45_95=$summary_bill_detail[$j]['tot_45_95'];
					$tot=$summary_bill_detail[$j]['tot'];
				}
				$data['equipmentW']=$equipmentW;				
				$data['equipmentN']=$equipmentN;				
				$data['equipmentP']=$equipmentP;				
				$data['vat']=$vat;	
				$data['nonvat']=$nonvat;	
				$data['lon']=$lon;	
				$data['nlon']=$nlon;	
				$data['fcl_20_85']=$fcl_20_85;	
				$data['fcl_20_95']=$fcl_20_95;	
				$data['fcl_40_85']=$fcl_40_85;	
				$data['fcl_40_95']=$fcl_40_95;	
				$data['fcl_45_85']=$fcl_45_85;	
				$data['fcl_45_95']=$fcl_45_95;	
				$data['fcl']=$fcl;	
				$data['lcl_20_85']=$lcl_20_85;	
				$data['lcl_20_95']=$lcl_20_95;	
				$data['lcl_40_85']=$lcl_40_85;	
				$data['lcl_45_85']=$lcl_45_85;	
				$data['lcl_45_95']=$lcl_45_95;	
				$data['lcl']=$lcl;	
				$data['mty_20_85']=$mty_20_85;	
				$data['mty_20_95']=$mty_20_95;	
				$data['mty_40_85']=$mty_40_85;	
				$data['mty_40_95']=$mty_40_95;	
				$data['mty_45_85']=$mty_45_85;	
				$data['mty_45_95']=$mty_45_95;	
				$data['mty']=$mty;	
				$data['tot_20_85']=$tot_20_85;	
				$data['tot_20_95']=$tot_20_95;	
				$data['tot_40_85']=$tot_40_85;	
				$data['tot_40_95']=$tot_40_95;	
				$data['tot_45_85']=$tot_45_85;	
				$data['tot_45_95']=$tot_45_95;	
				$data['tot']=$tot;	
					
				
				$data['summary_bill_detail']=$summary_bill_detail;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);
				 $time="";
                 for($k=0;$k<count($print_time);$k++){
					 $time=$print_time[$k]['Time'];
				 }	
                $data['time']=$time;				 
				$data['print_time']=$print_time;
                $rslt_detail=$this->bm->dataSelectDb2($sql_detail);
				$invoiceDesc="";
				$draftNumber="";
				$ibVisitId="";
				$created="";
				$ibCarrierName="";
				$customerId="";
				$concustomerid="";
				$customerName="";
				$concustomername="";
                 for($i=0;$i<count($rslt_detail);$i++){
					$invoiceDesc=$rslt_detail[$i]['invoiceDesc'];
                    $draftNumber=$rslt_detail[$i]['draftNumber'];	
                    $ibVisitId=$rslt_detail[$i]['ibVisitId'];
                    $created=$rslt_detail[$i]['created'];
                    $ibCarrierName=$rslt_detail[$i]['ibCarrierName'];
                    $customerId=$rslt_detail[$i]['customerId'];	
                    $concustomerid=$rslt_detail[$i]['concustomerid'];	
                    $customerName=$rslt_detail[$i]['customerName'];
                    $concustomername=$rslt_detail[$i]['concustomername'];					
				 }				 
			     $data['rslt_detail']=$rslt_detail;					
			     $data['invoiceDesc']=$invoiceDesc;					
			     $data['draftNumber']=$draftNumber;					
			     $data['ibVisitId']=$ibVisitId;					
			     $data['created']=$created;					
			     $data['ibCarrierName']=$ibCarrierName;					
			     $data['customerId']=$customerId;					
			     $data['concustomerid']=$concustomerid;					
			     $data['customerName']=$customerName;					
			     $data['concustomername']=$concustomername;	
				 //pdf start here 
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_containerBill_rptPangoanStatusChangePCTToCPADraftDetailsInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="containerBill/print_containerBill_rptPangoanStatusChangePCTToCPADraftDetailsInvoice-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
				'', '', '', ''
				); // margin footer

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf	
                //pdf end here				
					       
            }
			else if($draft_detail_view=='pdfOffhireInvoice')
			{				
			
				$sql_detail="SELECT DISTINCT unitId,STATUS,draftNumber,ibCarrierName
				FROM(

				SELECT id AS unitId,details.gkey AS unit_gkey,details.gkey,'OFFHIRE CHARGES ON CONTAINER' AS invoiceList
				,'DRAFT' AS STATUS,draftNumber,vsl_name AS ibCarrierName,
				agent AS concustomername,
				mlo AS customerId,mlo_name AS customerName,
				rotation AS ibVisitId,height AS isoHeight,
				berth,size AS isoLength,DATE(billingDate) AS billingDate,
				(SELECT MAX(exchangeRate) FROM mis_billing_details WHERE draftNumber='462') AS exchangeRate,details.id,
				DATE(fcy_time_out) AS yardOutDate1,
				(SELECT DATE(MIN(fcy_time_out)) FROM mis_billing_details WHERE draftNumber='462') AS yardOutDate


				FROM
				(
				SELECT * FROM mis_billing_details WHERE draftNumber='462' AND description LIKE 'LIFT%' ORDER BY id
				)AS details

				) AS final";
				
                
				//echo $sql_detail;
				
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelect($bill_print_time);	
				$data['print_time']=$print_time;
                          				
				    //views are missing
					if($printBtnValue=="0")
					{
						$this->load->view('containerBill/containerBill_OffhireDraftDetailsInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
					}
					else
					{
						$this->load->view('containerBill/print_containerBill_OffhireDraftDetailsInvoice',$data);	
					}	
					
				
			}else if($draft_detail_view=='pdfDraftOffdockToMuktarpurStatusChangeInvoice')
			{
				
				
				$sql_detail="select distinct id as unitId,draftNumber as draftNumber,'STATUS CHANGE INVOICE (OFFDOCK TO MUKTARPUR)' as invoiceDesc,
				vsl_name as ibCarrierName,rotation as ibVisitId,mlo as customerId,mlo_name as customerName,
				(select billing_Date from ".$this->get_table_name("mis_billing")." where draft_id='$draftNumber') as created,
				agent_code as concustomerid,agent as concustomername,'DRAFT' as status,size as isoLength,height as isoHeight,
				freight_kind as freightKind,date(fcy_time_in) as timeIn,cl_date as cl_date,DATE_ADD(cl_date,INTERVAL 4 DAY) as eventFrom,
				fcy_time_out as eventTo,
				DATEDIFF(cl_date,fcy_time_in)+1 AS days,
				datediff(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1 as days_old,
				vatperc as vatperc
				from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber'";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);
                
			
                $summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(select (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				from
				(
				SELECT DISTINCT id as chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				if(destination not in('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') and freight_kind !='MTY',1,NULL) as LON
				from
				(
				select * from ".$this->get_table_name("mis_billing_details")." where draftNumber='$draftNumber' and description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' WHEN invoice_type=128 THEN 'Discharging%' ELSE 'Status%' END )
				) tbl
				) final";
                $summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['print_time']=$print_time;	
				$data['rslt_detail']=$rslt_detail;
				//views are missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/containerBill_rptStatusChangeOffdockToMuktarpurDraftDetailsInvoice',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_containerBill_rptStatusChangeOffdockToMuktarpurDraftDetailsInvoice',$data);	
				}	
			
			
			}else if($draft_detail_view=='pdfDraftMukterpulDischargeInvoice')		//MUKTARPUR CONT DISCHARGE - 07-01-2019		//intakhab
			{
				$sql_detail="SELECT id AS unitId,gkey,freight_kind AS freightKind,'DRAFT' AS STATUS,'Container Discharging Bill (Muktarpur)' AS invoiceDesc,draftNumber,vsl_name AS ibCarrierName,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,billingDate AS created,size AS isoLength,height AS isoHeight,
				wpn AS equipment,DATE(fcy_time_in) AS fcy_time_in,
				(SELECT sparcsn4.inv_goods.destination 
				FROM sparcsn4.inv_unit 
				INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
				WHERE sparcsn4.inv_unit.gkey=".$this->get_table_name("mis_billing_details").".gkey) AS preloc,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
					WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
					WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype,
				(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS billing_date

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' ORDER BY draftNumber";
				
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);
				
				$summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(SELECT (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				FROM
				(
				SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				IF(destination NOT IN('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') AND freight_kind !='MTY',1,NULL) AS LON
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' WHEN invoice_type=128 THEN 'Discharging%' ELSE 'Status%' END )
				) tbl
				) final";
				
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);	
				$data['print_time']=$print_time;
				$data['rslt_detail']=$rslt_detail;
				
			    //views are missing
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/mukhtarpur_containerBill_discharge_detail',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_mukhtarpur_containerBill_discharge_detail',$data);	
				}	
				
			
				
			}else if($draft_detail_view=='pdfMukhterpoleLoadingInvoice')		//MUKTARPUR CONT LOAD - 07-01-2019		//intakhab
			{
				$sql_detail="SELECT DISTINCT id AS unitId,gkey,freight_kind AS freightKind,'DRAFT' AS STATUS,'Container Loading Bill (Muktarpur)' AS invoiceDesc,draftNumber,IF(depo_date IS NULL,draftNumber,CONCAT(draftNumber,'R')) AS billNumber,vsl_name AS ibCarrierName,depo_date,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,(SELECT DATE(billing_date) FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,size AS isoLength,height AS isoHeight,cl_date AS timeIn,DATE(fcy_time_out) AS timeOut,
				pre_imp_rot AS imp_rot,
				DATE(pre_imp_ata) AS imp_ata,
				IF(DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1<1,'0',DATEDIFF(fcy_time_out,IFNULL(depo_date,DATE_ADD(cl_date,INTERVAL 4 DAY)))+1) AS days,
				wpn AS equipment,
				(SELECT sparcsn4.inv_goods.destination 
				FROM sparcsn4.inv_unit 
				INNER JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
				WHERE sparcsn4.inv_unit.gkey=".$this->get_table_name("mis_billing_details").".gkey) AS preloc,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
					WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
					WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' AND description LIKE 'Load%' ORDER BY draftNumber";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);	
				
				$summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(SELECT (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				FROM
				(
				SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				IF(destination NOT IN('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') AND freight_kind !='MTY',1,NULL) AS LON
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' WHEN invoice_type=128 THEN 'Discharging%' ELSE 'Status%' END )
				) tbl
				) final";
				
				$summary_bill_detail=$this->bm->dataSelect($summary_bill);	
				$data['summary_bill_detail']=$summary_bill_detail;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelect($bill_print_time);	
				$data['print_time']=$print_time;
				$data['rslt_detail']=$rslt_detail;
				
				if($printBtnValue=="0")
				{
					$this->load->view('containerBill/mukhtarpur_containerBill_load_detail',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
				}
				else
				{
					$this->load->view('containerBill/print_mukhtarpur_containerBill_load_detail',$data);	
				}	
				
				
			
			}else if($draft_view=='pdfExportStorageInvoice')
			{
				$bill_sql="SELECT 'EXPORT STORAGE INVOICE' AS TYPE,draftNumber,'DRAFT' AS STATUS,
				'EXPORT STORAGE INVOICE' AS invoiceDesc, DATE_FORMAT(billingDate,'%d-%m-%Y') as billingDate,agent_code AS conCustomerId,
				agent AS conCustomerName,mlo AS payCustomerId,
				mlo_name AS payCustomerName,
				CAST((SELECT MAX(exchangeRate) FROM ".$this->get_table_name("mis_billing_details")." dtl WHERE draftNumber='$draftNumber') AS DECIMAL(10,4)) AS exchangeRate,
				COUNT(id) AS quantityUnit,SUM(amt) AS totalCharged,
				description,

				CAST((
					CASE 
						WHEN 
							currency_gkey=2 
						THEN 
							CAST(Tarif_rate AS DECIMAL(10,4)) 
						ELSE 
							SUBSTRING(CAST(Tarif_rate AS DECIMAL(10,4)),1,LENGTH(CAST(Tarif_rate AS DECIMAL(10,4)))-2)
							
						END 
				)AS CHAR) AS rateBilled,


				SUM(vat) AS totalvatamount,SUM(amt)+SUM(vat) AS netTotal,vsl_name AS obCarrierName,ata AS obCarrierATA,atd AS obCarrierATD,
				height,

				 berth,
				arcar_id AS vslId,size,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=20 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot20,
				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=40 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot40,

				(SELECT COUNT(DISTINCT id) FROM ".$this->get_table_name("mis_billing_details")." dtl
				WHERE draftNumber='$draftNumber'
				AND size=45 AND dtl.mlo=tbl.mlo
				AND fcy_time_in IS NOT NULL
				) AS qtytot45,


				IF(currency_gkey=2,'$','') AS usd,
				IF(SUM(storage_days)=0,NULL,SUM(storage_days)) AS qty ,
				COUNT(description) AS qtyUnit,'' AS comments

				FROM

				(
					SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE ".$this->get_table_name("mis_billing_details").".draftNumber='$draftNumber'
				)AS tbl GROUP BY description";
								
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";
				//echo $bill_sql;

			}else if($draft_detail_view=='pdfDraftICDToPCTStatusChangeInvoice')
			{
			
				$sql_detail="SELECT DISTINCT id AS unitId,gkey,freight_kind AS freightKind,'DRAFT' AS STATUS,'Status Change Bill (ICD to PCT)' AS invoiceDesc,draftNumber,IF(depo_date IS NULL,draftNumber,CONCAT(draftNumber,'R')) AS billNumber,vsl_name AS ibCarrierName,depo_date,
				mlo AS customerId,mlo_name AS customerName,agent_code AS concustomerid,agent AS concustomername,
				rotation AS ibVisitId,(SELECT DATE_FORMAT(billing_date, '%d-%m-%Y') AS billing_date FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draftNumber') AS created,size AS isoLength,LEFT(height,3) AS isoHeight,DATE_FORMAT(cl_date, '%d-%m-%Y') AS timeIn,fcy_time_out AS timeOut,
				pre_imp_rot AS imp_rot,
				DATE_FORMAT(fcy_time_in, '%d-%m-%Y') AS imp_ata,
				IF(DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1<1,'0',DATEDIFF(fcy_time_out,DATE_ADD(cl_date,INTERVAL 4 DAY))+1) AS days,
				wpn AS equipment,
				vatperc,iso_grp,
				(CASE
						WHEN iso_grp = 'UT' THEN 'OPEN TOP'
						WHEN iso_grp IN ('RE','RT') THEN 'REEFER'
						WHEN iso_grp IN ('PL','PC','PC') THEN 'F-RACK'
						WHEN iso_grp IN ('TN','TD','TG') THEN 'TANK'
						ELSE NULL
				END) AS cnttype

				FROM ".$this->get_table_name("mis_billing_details")."
				WHERE draftNumber='$draftNumber' AND description LIKE 'Status%' ORDER BY draftNumber";
				$rslt_detail=$this->bm->dataSelectDb2($sql_detail);
				
				$invoiceDesc="";
				$draftNumber="";
				$ibVisitId="";
				$created="";
				$ibCarrierName="";
				$customerId="";
				$concustomerid="";
				$customerName="";
				$concustomername="";
				for($i=0;$i<count($rslt_detail);$i++){
					$invoiceDesc=$rslt_detail[$i]['invoiceDesc'];
					$draftNumber=$rslt_detail[$i]['draftNumber'];
					$ibVisitId=$rslt_detail[$i]['ibVisitId'];
					$created=$rslt_detail[$i]['created'];
					$ibCarrierName=$rslt_detail[$i]['ibCarrierName'];
					$customerId=$rslt_detail[$i]['customerId'];
					$concustomerid=$rslt_detail[$i]['concustomerid'];
					$customerName=$rslt_detail[$i]['customerName'];
					$concustomername=$rslt_detail[$i]['concustomername'];
				}
				$data['invoiceDesc']=$invoiceDesc;
				$data['draftNumber']=$draftNumber;
				$data['ibVisitId']=$ibVisitId;
				$data['ibCarrierName']=$ibCarrierName;
				$data['customerId']=$customerId;
				$data['concustomerid']=$concustomerid;
				$data['customerName']=$customerName;
				$data['concustomername']=$concustomername;
                  
				$summary_bill="SELECT
				COUNT(fcl_20_85) AS  fcl_20_85,
				COUNT(fcl_20_95) AS  fcl_20_95,
				COUNT(fcl_40_85) AS  fcl_40_85,
				COUNT(fcl_40_95) AS  fcl_40_95,
				COUNT(fcl_45_85) AS  fcl_45_85,
				COUNT(fcl_45_95) AS  fcl_45_95,

				COUNT(lcl_20_85) AS  lcl_20_85,
				COUNT(lcl_20_95) AS  lcl_20_95,
				COUNT(lcl_40_85) AS  lcl_40_85,
				COUNT(lcl_40_95) AS  lcl_40_95,
				COUNT(lcl_45_85) AS  lcl_45_85,
				COUNT(lcl_45_95) AS  lcl_45_95,

				COUNT(mty_20_85) AS  mty_20_85,
				COUNT(mty_20_95) AS  mty_20_95,
				COUNT(mty_40_85) AS  mty_40_85,
				COUNT(mty_40_95) AS  mty_40_95,
				COUNT(mty_45_85) AS  mty_45_85,
				COUNT(mty_45_95) AS  mty_45_95,
				COUNT(nonvat) AS  nonvat,
				COUNT(vat) AS  vat,
				COUNT(chargeEntityId) AS tot,
				COUNT(fcl) AS  fcl,
				COUNT(lcl) AS  lcl,
				COUNT(mty) AS  mty,
				COUNT(tot_20_85) AS  tot_20_85,
				COUNT(tot_20_95) AS  tot_20_95,
				COUNT(tot_40_85) AS  tot_40_85,
				COUNT(tot_40_95) AS  tot_40_95,
				COUNT(tot_45_85) AS  tot_45_85,
				COUNT(tot_45_95) AS  tot_45_95,
				COUNT(equipmentW) AS equipmentW,
				COUNT(equipmentP) AS equipmentP,
				COUNT(equipmentN) AS equipmentN,
				COUNT(LON) AS LON,
				(SELECT (COUNT(chargeEntityId)-COUNT(LON))) AS NLON
				FROM
				(
				SELECT DISTINCT id AS chargeEntityId,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl_45_95,

				(CASE WHEN height='8.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_85,

				(CASE WHEN height='9.6' AND size = 20 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_20_95,

				(CASE WHEN height='8.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_85,

				(CASE WHEN height='9.6' AND size = 40 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_40_95,

				(CASE WHEN height='8.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_85,

				(CASE WHEN height='9.6' AND size = 45 AND freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty_45_95,

				(CASE WHEN vatperc =0 THEN 1
				ELSE NULL END) AS nonvat,

				(CASE WHEN vatperc !=0 THEN 1
				ELSE NULL END) AS vat,

				(CASE WHEN freight_kind = 'FCL' THEN 1
				ELSE NULL END) AS fcl,

				(CASE WHEN freight_kind = 'LCL' THEN 1
				ELSE NULL END) AS lcl,

				(CASE WHEN freight_kind = 'MTY' THEN 1
				ELSE NULL END) AS mty,


				(CASE WHEN height='8.6' AND size = 20  THEN 1
				ELSE NULL END) AS tot_20_85,

				(CASE WHEN height='9.6' AND size = 20 THEN 1
				ELSE NULL END) AS tot_20_95,

				(CASE WHEN height='8.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_85,

				(CASE WHEN height='9.6' AND size = 40  THEN 1
				ELSE NULL END) AS tot_40_95,

				(CASE WHEN height='8.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_85,

				(CASE WHEN height='9.6' AND size = 45  THEN 1
				ELSE NULL END) AS tot_45_95,

				(CASE WHEN wpn='W'   THEN 1
				ELSE NULL END) AS equipmentW,

				(CASE WHEN wpn='P'   THEN 1
				ELSE NULL END) AS equipmentP,

				(CASE WHEN wpn='N'   THEN 1
				ELSE NULL END) AS equipmentN,
				IF(destination NOT IN('2591','2592','5230','5231','5232','5233','5234','5235','5236','5237','5238') AND freight_kind !='MTY',1,NULL) AS LON
				FROM
				(
				SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draftNumber' AND description LIKE (CASE WHEN invoice_type=112 THEN 'Load%' WHEN invoice_type=120 THEN 'Load%' WHEN invoice_type=108 THEN 'Discharging%' ELSE 'Status%' END )
				) tbl
				) final";
				$summary_bill_detail=$this->bm->dataSelectDb2($summary_bill);
				
				 $equipmentW=0;
				 $equipmentN=0;
				 $equipmentP=0;
				 $vat=0;
				 $nonvat=0;
				 $lon=0;
				 $nlon=0;
				 $fcl_20_85=0;
				 $fcl_20_95=0;
				 $fcl_40_85=0;
				 $fcl_40_95=0;
				 $fcl_45_85=0;
				 $fcl_45_95=0;
				 $fcl=0;
				 $lcl_20_85=0;
				 $lcl_20_95=0;
				 $lcl_40_85=0;
				 $lcl_40_95=0;
				 $lcl_45_85=0;
				 $lcl_45_95=0;
				 $lcl=0;
				 $mty_20_85=0;
				 $mty_20_95=0;
				 $mty_40_85=0;
				 $mty_45_85=0;
				 $mty_45_95=0;
				 $mty=0;
				 $tot_20_85=0;
				 $tot_20_95=0;
				 $tot_40_85=0;
				 $tot_40_95=0;
				 $tot_45_85=0;
				 $tot_45_95=0;
				 $tot=0;
				 for($t=0;$t<count($summary_bill_detail);$t++){
					 //for zero index(view for outside loop)
					 $equipmentW=$summary_bill_detail[$t]['equipmentW'];
					 $equipmentN=$summary_bill_detail[$t]['equipmentN'];
					 $equipmentP=$summary_bill_detail[$t]['equipmentP'];
					 $vat=$summary_bill_detail[$t]['vat'];
					 $nonvat=$summary_bill_detail[$t]['nonvat'];
					 $lon=$summary_bill_detail[$t]['LON'];
					 $nlon=$summary_bill_detail[$t]['NLON'];
					 $fcl_20_85=$summary_bill_detail[$t]['fcl_20_85'];
					 $fcl_20_95=$summary_bill_detail[$t]['fcl_20_95'];
					 $fcl_40_85=$summary_bill_detail[$t]['fcl_40_85'];
					 $fcl_40_95=$summary_bill_detail[$t]['fcl_40_95'];
					 $fcl_45_85=$summary_bill_detail[$t]['fcl_45_85'];
					 $fcl_45_95=$summary_bill_detail[$t]['fcl_45_95'];
					 $fcl=$summary_bill_detail[$t]['fcl'];
					 $lcl_20_85=$summary_bill_detail[$t]['lcl_20_85'];
					 $lcl_20_95=$summary_bill_detail[$t]['lcl_20_95'];
					 $lcl_40_85=$summary_bill_detail[$t]['lcl_40_85'];
					 $lcl_40_95=$summary_bill_detail[$t]['lcl_40_95'];
					 $lcl_45_85=$summary_bill_detail[$t]['lcl_45_85'];
					 $lcl_45_95=$summary_bill_detail[$t]['lcl_45_95'];
					 $lcl=$summary_bill_detail[$t]['lcl'];
					 $mty_20_85=$summary_bill_detail[$t]['mty_20_85'];
					 $mty_20_95=$summary_bill_detail[$t]['mty_20_95'];
					 $mty_40_85=$summary_bill_detail[$t]['mty_40_85'];
					 $mty_40_95=$summary_bill_detail[$t]['mty_40_95'];
					 $mty_45_85=$summary_bill_detail[$t]['mty_45_85'];
					 $mty_45_95=$summary_bill_detail[$t]['mty_45_95'];
					 $mty=$summary_bill_detail[$t]['mty'];
					 $tot_20_85=$summary_bill_detail[$t]['tot_20_85'];
					 $tot_20_95=$summary_bill_detail[$t]['tot_20_95'];
					 $tot_40_85=$summary_bill_detail[$t]['tot_40_85'];
					 $tot_40_95=$summary_bill_detail[$t]['tot_40_95'];
					 $tot_45_85=$summary_bill_detail[$t]['tot_45_85'];
					 $tot_45_95=$summary_bill_detail[$t]['tot_45_95'];
					 $tot=$summary_bill_detail[$t]['tot'];
					 
				 }	
                  				 
				$data['summary_bill_detail']=$summary_bill_detail;				
				$data['equipmentW']=$equipmentW;				
				$data['equipmentN']=$equipmentN;				
				$data['equipmentP']=$equipmentP;				
				$data['vat']=$vat;				
				$data['nonvat']=$nonvat;				
				$data['lon']=$lon;				
				$data['nlon']=$nlon;				
				$data['fcl_20_85']=$fcl_20_85;				
				$data['fcl_20_95']=$fcl_20_95;				
				$data['fcl_40_85']=$fcl_40_85;				
				$data['fcl_40_95']=$fcl_40_95;				
				$data['fcl_45_85']=$fcl_45_85;				
				$data['fcl_45_95']=$fcl_45_95;				
				$data['fcl']=$fcl;				
				$data['lcl_20_85']=$lcl_20_85;				
				$data['lcl_20_95']=$lcl_20_95;				
				$data['lcl_40_85']=$lcl_40_85;				
				$data['lcl_40_95']=$lcl_40_95;				
				$data['lcl_45_85']=$lcl_45_85;				
				$data['lcl_45_95']=$lcl_45_95;				
				$data['lcl']=$lcl;				
				$data['mty_20_85']=$mty_20_85;				
				$data['mty_20_95']=$mty_20_95;				
				$data['mty_45_85']=$mty_45_85;				
				$data['mty_45_95']=$mty_45_95;				
				$data['tot_20_85']=$tot_20_85;				
				$data['tot_20_95']=$tot_20_95;				
				$data['tot_40_85']=$tot_40_85;				
				$data['tot_40_95']=$tot_40_95;				
				$data['tot_45_85']=$tot_45_85;				
				$data['tot_45_95']=$tot_45_95;				
				$data['tot']=$tot;				
					
				$bill_print_time="SELECT DATE_FORMAT(now(), '%W %M %e %Y %H:%i') as Time";		
				$print_time=$this->bm->dataSelectDb2($bill_print_time);
                $time="";				
				for($j=0;$j<count($print_time);$j++){
					$time=$print_time[$j]['Time'];
				}
				$data['print_time']=$print_time;	
				$data['rslt_detail']=$rslt_detail;
				$data['time']=$time;
				//pdf start here
				$this->load->library('m_pdf');
				$html=$this->load->view('containerBill/print_containerBill_pdfDraftICDToPCTStatusChangeDetailInvoice',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.	
				$pdfFilePath ="print_containerBill_pdfDraftICDToPCTStatusChangeDetailInvoice(CPAtoPCT)-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
				$pdf->AddPage('P', // L - landscape, P - portrait
				'', '', '', ''
				); // margin footer

				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);

				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
				//pdf end here
				
				
			}
               
		
		}
	}
	//container detail end
	
	
	//delete bill - start
	function delete_bil()
	{   
		
		$session_id = $this->session->userdata('value');			
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
		$draft_id=$this->input->post('draft_id');
		$bill_type=$this->input->post('bill_type');		
							
		  $delete_tarif_sql="DELETE ".$this->get_table_name("mis_inv_tarrif").".* FROM ".$this->get_table_name("mis_billing_details")."
							INNER JOIN ".$this->get_table_name("mis_inv_tarrif")." ON ".$this->get_table_name("mis_inv_tarrif").".gkey=".$this->get_table_name("mis_billing_details").".gkey
							WHERE draftNumber='$draft_id' AND ".$this->get_table_name("mis_inv_tarrif").".invoice_type='$bill_type'";
						
		//$delTariffStat=$this->bm->dataDelete($delete_tarif_sql);	
		$delTariffStat=$this->bm->dataDeleteDb2($delete_tarif_sql);	
		
		
		 $delete_detail_sql="DELETE FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='$draft_id'";
		//$delDetailStat=$this->bm->dataDelete($delete_detail_sql);	
		$delDetailStat=$this->bm->dataDeleteDb2($delete_detail_sql);
        		
		
		$delete_sql="DELETE FROM ".$this->get_table_name("mis_billing")." WHERE draft_id='$draft_id'";
		
		//$delstat=$this->bm->dataDelete($delete_sql);
		$delstat=$this->bm->dataDeleteDb2($delete_sql);

		if($delTariffStat==1 and $delstat==1 and $delDetailStat==1)
		{
			$data['msg']="<font size=3 color=green>Data Deleted.</font>";
		}
		else {
		   $data['msg']="<font size=3 color=red>Data not deleted. Please! Make sure it is deleted from others entry.</font>";
		}

		$data['title']="Billing List";
		
		$cont_bill_list="SELECT imp_rot,exp_rot,bill_type,mlo_code,draft_id as draft,IFNULL(created_user,'') AS created_user, 
		draft_final_status,pdf_draft_view_name,pdf_detail_view_name,DATE(billing_date) AS billing_date,br.billtype   
		FROM ".$this->get_table_name("mis_billing")." mb INNER JOIN ctmsmis.billingreport br ON br.id = mb.bill_type 
		ORDER BY mb.draft_id DESC limit 200";
		
		// $cont_bill_list="SELECT imp_rot,exp_rot,bill_type,mlo_code,draft_id AS draft,IFNULL(created_user,'') AS created_user, draft_final_status,pdf_draft_view_name,pdf_detail_view_name,DATE(billing_date) AS billing_date,br.billtype   
		// FROM ctmsmis.mis_billing mb 
		// INNER JOIN ctmsmis.billingreport br ON br.id = mb.bill_type 
		// WHERE imp_rot='2017/PKSL06'";
		
		$bill_list=$this->bm->dataSelectDb2($cont_bill_list);
		
		$data['bill_list']=$bill_list;
	
		$this->load->view('containerBill/containerBillingList',$data);
		
		}
	}
	//delete bill - end
		
	function new_bill_generation()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$data['title']="Bill Generation Form";
					
			$sql_bill_type="SELECT id,IF(id=75,'SHUT OUT INVOICE',billtype) AS billtype 
			FROM ctmsmis.billingreport
			WHERE prefix = 'draft' AND id IN (108,112,116,124,135)
			ORDER BY billtype";			
			$rslt_bill_type=$this->bm->dataSelectDb2($sql_bill_type);
			
			$sql_mlo_data="SELECT r.gkey,r.id,r.name AS mlo_name 
			FROM ref_bizunit_scoped r 
			INNER JOIN ( ref_agent_representation X 
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )ON r.gkey=X.bzu_gkey 
			ORDER BY r.id";			
			$rslt_mlo_data=$this->bm->dataselect($sql_mlo_data);
			
			$data['rslt_bill_type']=$rslt_bill_type;
			$data['rslt_mlo_data']=$rslt_mlo_data;
			$data['msg']="";
		
			// $this->load->view('cssAssets');
			// $this->load->view('headerTop');
			//$this->load->view('sidebar');
			$this->load->view('containerBill/bill_generation_form',$data);
			// $this->load->view('jsAssets');
		}
	}
	
	function bill_generation_action()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$bill_type=$this->input->post('bill_type');
			$rotation=$this->input->post('rotation');
			$container_id=$this->input->post('container_id');
			$rotation_type=$this->input->post('rotation_type');			
			$mlo_code=$this->input->post('mlo');
			$containers=$this->input->post('containers');
			$from_date="";
						
			if($bill_type==22 or $bill_type==11 or $bill_type==132)
			{
				$from_date=$this->input->post('date');
			}			
			if($bill_type==47 or $bill_type==51 or $bill_type==59 or $bill_type==116 or $bill_type==63 or $bill_type==124 or $bill_type==135)
			{
				$container_id=$containers;
			}
			else
			{
				$container_id=$container_id;
			}
			
			$msg="";
			
			if($this->cbq->is_bill_exists($bill_type,$rotation,$from_date,$mlo_code))
			{
				if($bill_type==2 or $bill_type==3 or $bill_type==27 or $bill_type==47 or $bill_type==51 or $bill_type==63 or $bill_type==67 or $bill_type==59 or $bill_type==108 or $bill_type==112 or $bill_type==120 or $bill_type==124 or $bill_type==132)    
					$msg = "<font color='red'><b>Bill already generated for rotation : ".$rotation."</b></font>";
				else if($bill_type==11 or $bill_type==22) 
					$msg = "<font color='red'><b>Bill already generated for date : ".$from_date."</b></font>";
				else if($bill_type==75) 
					$msg = "<font color='red'><b>Bill already generated for container : ".$container_id."</b></font>"; 
			}
			else if($this->cbq->is_commonlanding_date_exists($bill_type,$rotation,$from_date,$mlo_code,$container_id))
			{
				if($bill_type==2 or $bill_type==27) 
					$msg = "<font color='red'><b>Common Landing Date Required For The Rotation : ".$rotation."</b></font>";
				else if($bill_type==116 or $bill_type==124) 
					$msg = "<font color='red'><b>Bill already generated for rotation : ".$rotation."</b></font>";
			}			
			else
			{	
				$stat=$this->cbq->Generate_Bill($bill_type,$mlo_code,$rotation,$container_id,$from_date,$rotation_type);
				if($stat==true)
				{
					$msg = "<font color='green'><b>Bill generated successfully for rotation : ".$rotation."</b></font>";
				}					
				else
				{
					$msg = "<font color='red'><b>Bill did not generate successfully for rotation : ".$rotation."</b></font>";
				}
			}
			
			$data['title']="Bill Generation Form";
					
			$sql_bill_type="SELECT id,IF(id=75,'SHUT OUT INVOICE',billtype) AS billtype 
			FROM ctmsmis.billingreport
			WHERE prefix = 'draft' AND id IN (108,112,116,124,135)
			ORDER BY billtype";			
			$rslt_bill_type=$this->bm->dataSelectDb2($sql_bill_type);
			
			$sql_mlo_data="SELECT r.gkey,r.id,r.name AS mlo_name 
			FROM ref_bizunit_scoped r 
			INNER JOIN ( ref_agent_representation X 
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )ON r.gkey=X.bzu_gkey 
			ORDER BY r.id";			
			$rslt_mlo_data=$this->bm->dataselect($sql_mlo_data);
			
			$data['rslt_bill_type']=$rslt_bill_type;
			$data['rslt_mlo_data']=$rslt_mlo_data;
			$data['msg']=$msg;
		
			// $this->load->view('cssAssets');
			// $this->load->view('headerTop');
			//$this->load->view('sidebar');
			$this->load->view('containerBill/bill_generation_form',$data);
			// $this->load->view('jsAssets');
			
			
		}
	}
	
           //  bill  forwarding start
		   function bill_forwarding()
			{
				
				$session_id = $this->session->userdata('value');
				if($session_id!=$this->session->userdata('session_id'))
				{
					$this->logout();
				}
				else
				{
					//echo "hello";
					//return;
					$data['title']="Bill Generation Form";
				
					$sql_bill_type="SELECT id,IF(id=75,'SHUT OUT INVOICE',billtype) AS billtype 
					FROM ctmsmis.billingreport
					WHERE prefix = 'draft' AND id IN (108,112,116,124,135)
					ORDER BY billtype";
					
					$rslt_bill_type=$this->bm->dataSelectDb2($sql_bill_type);
					
					$data['rslt_bill_type']=$rslt_bill_type;
			
					$data['msg']="";
				
					$this->load->view('containerBill/bill_forwarding',$data);
				}
				
			}
			function bill_forwarding_view()
			{
				$session_id = $this->session->userdata('value');
				if($session_id!=$this->session->userdata('session_id'))
				{
					$this->logout();
				}
				else
				{
				    $bill_type=$this->input->post('bill_type');
					$fromDate=$this->input->post('fromDate');
					$toDate=$this->input->post('toDate');
					
					 $sql_bill_forwarding_details="SELECT date(billing_date) as billing_date ,sl,vsl_name,imp_rot,date(billingDate) as arv_date,agent_code,bill_no,mlo_code,SUM(amt) AS amt,SUM(vat) AS vat,(SUM(amt)+SUM(vat)) AS tot,description,paid
					FROM(
					SELECT billing_date,1 AS sl,vsl_name,UCASE(imp_rot) AS imp_rot,agent_code,draft_id AS bill_no,mlo_code,ROUND(SUM(amt),2) AS amt,ROUND(SUM(vat),2) AS vat,billingDate,
					(SELECT amt+vat ) AS tot,
					(SELECT billtype FROM ctmsmis.billingreport WHERE ctmsmis.billingreport.id=ctmsmis.mis_billing.bill_type LIMIT 1)AS description,'' AS paid
					FROM ctmsmis.mis_billing
					INNER JOIN ctmsmis.mis_billing_details ON ctmsmis.mis_billing_details.draftNumber=ctmsmis.mis_billing.draft_id
					WHERE DATE(billing_date) BETWEEN '$fromDate' AND '$toDate'  AND ctmsmis.mis_billing.bill_type='$bill_type'
					GROUP BY bill_no,description
					ORDER BY imp_rot,bill_no
					) AS tbl GROUP BY bill_no";
					
							
					$rslt_bill_dtl=$this->bm->dataSelectDb2($sql_bill_forwarding_details);			
					$data['rslt_bill_dtl']=$rslt_bill_dtl;
					
					$bill_description="";
					for($i=0;$i<count($rslt_bill_dtl);$i++){
						$bill_description=$rslt_bill_dtl[$i]['description'];
					}
					
					$data['bill_description']=$bill_description;
					$data['fromDate']=$fromDate;
					$data['toDate']=$toDate;
					$data['title']="Bill Forwarding";
					$data['msg']="";
					
					$this->load->library('m_pdf');
					$html=$this->load->view('containerBill/bill_forwarding_view',$data, true); 
			        $pdfFilePath ="containerBill/bill_forwarding_view'";
					
					

					$pdf = $this->m_pdf->load();
					$pdf->SetWatermarkText('CPA CTMS');
					$pdf->showWatermarkText = false;
					
					$stylesheet = file_get_contents('resources/styles/test.css'); 
				
		
					$pdf->WriteHTML($stylesheet,1);
					$pdf->WriteHTML($html,2);
						
					$pdf->Output($pdfFilePath, "I");
				
					//$this->load->view('containerBill/bill_forwarding_view',$data);			
				}
			}
		   
		     //  bill  forwarding End
			 
		function testController(){
			$this->cbq->testingConnection();
		}
		
		function update_bill_number()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title'] = "Bill Number Update Form";
				$data['msg'] = "";

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('containerBill/update_bill_number',$data);
				$this->load->view('jsAssets');
			}
		}
		
		function update_bill_number_perform()
		{
			$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{
				$msg = "";
				$data['title']="Bill Number Update Form";
				$oldBillNumber=$this->input->post('oldBillNumber');
				$newBillNumber=$this->input->post('newBillNumber');

				$login_id = $this->session->userdata('login_id');
								
				$updateDraft = "UPDATE ctmsmis.mis_billing SET draft_id='$newBillNumber',modify_user='$login_id' 
							WHERE draft_id='$oldBillNumber'";
				$updateBillStat=$this->bm->dataUpdatedb2($updateDraft);
				
				$query = "UPDATE ctmsmis.mis_billing_details SET draftNumber='$newBillNumber' WHERE draftNumber='$oldBillNumber'";
				$updateDetailStat=$this->bm->dataUpdatedb2($query);	
								
				if($updateBillStat==1 && $updateDetailStat==1 )
					$msg="<font color='blue'><strong>Updated Sucessfully</strong></font>";					
				else
					$msg="<font color='red'><strong>Not Updated</strong></font>";
						
				$data['title'] = "Bill Number Update Form";
				$data['msg'] = $msg;

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('containerBill/update_bill_number',$data);
				$this->load->view('jsAssets');
			}
			
		}
		
	function update_bill_date()
		{
			$session_id = $this->session->userdata('value');
			$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
			{
				$this->logout();
			}
			else
			{
				$data['title']="Bill Date Update Form";
				
				$sql_bill_type="SELECT id,IF(id=75,'SHUT OUT INVOICE',billtype) AS billtype 
				FROM ctmsmis.billingreport
				WHERE prefix = 'draft' AND id IN (108,112,116,124,135)
				ORDER BY billtype";
				
				$rslt_bill_type=$this->bm->dataSelectDb2($sql_bill_type);

				$data['rslt_bill_type']=$rslt_bill_type;
				$data['msg']="";

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('containerBill/update_bill_date',$data);
				$this->load->view('jsAssets');
			}		
		}
	
	function update_bill_date_perform()
		{
			$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{
				$msg = "";
				$data['title']="Bill Date Update Form";
				$bill_type=$this->input->post('bill_type');
				$rotation=$this->input->post('rotation');
				$date=$this->input->post('date');
				$login_id = $this->session->userdata('login_id');	
								
				$strDraft = "select draft_id from ctmsmis.mis_billing where imp_rot='$rotation' AND bill_type='$bill_type'";
				$rslt_draft_id=$this->bm->dataSelectDb2($strDraft);
				
				// echo "<pre>";
				// print_r($rslt_draft_id);
				// echo "</pre>";
				// die();
				
				$updateStat_sum=0;
				for($i=0; $i<count($rslt_draft_id); $i++)
				{
					$draft_id=$rslt_draft_id[$i]['draft_id'];
					$query = "UPDATE ctmsmis.mis_billing SET billing_date='$date', modify_user='$login_id' WHERE draft_id='$draft_id'";
					$updateStat=$this->bm->dataUpdatedb2($query);		
					$updateStat_sum+=$updateStat;
					
				}

				if($updateStat_sum==count($rslt_draft_id))
					$msg="<font color='blue'><strong>Updated Sucessfully</strong></font>";					
				else
					$msg="<font color='red'><strong>Not Updated</strong></font>";
				
				$sql_bill_type="SELECT id,IF(id=75,'SHUT OUT INVOICE',billtype) AS billtype 
				FROM ctmsmis.billingreport
				WHERE prefix = 'draft' AND id IN (108,112,116,124,135)
				ORDER BY billtype";
				
				$rslt_bill_type=$this->bm->dataSelectDb2($sql_bill_type);

				$data['rslt_bill_type']=$rslt_bill_type;
				$data['msg']=$msg;

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('containerBill/update_bill_date',$data);
				$this->load->view('jsAssets');
			}
			
		}
		
	function update_bill_mlo_agent()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Bill MLO & Agent Update Form";
			$data['msg']="";	

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('containerBill/update_bill_mlo_agent',$data);
			$this->load->view('jsAssets');
		}		
	}
	
	function update_bill_mlo_agent_perform()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$billNumber=$this->input->post('billNumber');
			$mloCode=$this->input->post('mloCode');
			$mloName=$this->input->post('mloName');
			$agentCode=$this->input->post('agentCode');
			$agentName=$this->input->post('agentName');

			$login_id = $this->session->userdata('login_id');	
			
			$updateDraft = "UPDATE ctmsmis.mis_billing SET mlo_code='$mloCode',modify_user='$login_id' WHERE draft_id='$billNumber'";
			$updateBillStat=$this->bm->dataUpdatedb2($updateDraft);
	        
			$query = "UPDATE ctmsmis.mis_billing_details SET mlo='$mloCode',mlo_name='$mloName',agent_code='$agentCode',agent='$agentName' 
					WHERE draftNumber='$billNumber'";
			$updateDetailStat=$this->bm->dataUpdatedb2($query);	
			
			if($updateBillStat==1 && $updateDetailStat==1)
				$msg="<font color='blue'><strong>Updated Sucessfully</strong></font>";
			else
				$msg="<font color='red'><strong>Not Updated</strong></font>";
					
			$data['title']="Bill MLO & Agent Update Form";
			$data['msg']=$msg;	

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('containerBill/update_bill_mlo_agent',$data);
			$this->load->view('jsAssets');
		}
		
	}
}
?>