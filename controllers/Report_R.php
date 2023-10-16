<?php

class Report_R extends CI_Controller {
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
        
    function releaseOrderPDF_static()
	{
		// $verify_num = "U32109210001";
		$verify_num = "U20107210001";
		// $this->data['verify_num']=$verify_num;
		
		$strBill="SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack+loc_first),0) AS rcv_pack,

		rcv_unit,igm_masters.Vessel_Name,Voy_No,igm_supplimentary_detail.Volume_in_cubic_meters,
		(SELECT actual_delv_pack FROM do_truck_details_entry WHERE verify_number='$verify_num') AS actual_delv_pack,	
		(SELECT gate_out_time FROM do_truck_details_entry WHERE verify_number='$verify_num') AS gate_out_time,		
		igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_code,

		igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,shed_loc,
		shed_yard,Description_of_Goods,Notify_name,IFNULL(shed_tally_info.verify_number,0) AS verify_number,
		IFNULL(shed_tally_info.id,0) AS verify_id,igm_supplimentary_detail.Pack_Number,
		igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No,

		igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
		igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_weight,igm_sup_detail_container.cont_iso_type,

		verify_other_data.cnf_name,IFNULL(NULLIF(shed_mlo_do_info.be_no,' '),verify_other_data.be_no) AS be_no,
		verify_other_data.be_date,bank_bill_recv.bill_no,bank_bill_recv.cp_no,
		RIGHT(bank_bill_recv.cp_year,2) AS cp_year,bank_bill_recv.cp_bank_code,bank_bill_recv.cp_unit,
		DATE(bank_bill_recv.recv_time) AS cp_date,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.Line_No,
		total_port,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',
		IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',
		IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS master_bill_no,shed_bill_master.bill_date,VoyNo,
		verify_other_data.exit_note_number,pr_number
		FROM  igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
		LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
		LEFT JOIN shed_bill_master ON shed_bill_master.verify_no=shed_tally_info.verify_number
		LEFT JOIN bank_bill_recv ON bank_bill_recv.bill_no=shed_bill_master.bill_no
		LEFT JOIN vessels_berth_detail ON shed_bill_master.import_rotation=vessels_berth_detail.Import_Rotation_No
		LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No AND igm_supplimentary_detail.BL_No=shed_mlo_do_info.bl_no
		WHERE shed_tally_info.verify_number='$verify_num' LIMIT 1";
		// echo $strBill;return;
		$rtnContainerList = $this->bm->dataSelectDb1($strBill);		
		// $this->data['rtnContainerList']=$rtnContainerList;

		$strBillRcvInfo="select description,gl_code 
		from shed_bill_details 
		inner join shed_bill_master on shed_bill_master.bill_no=shed_bill_details.bill_no
		where shed_bill_master.verify_no='$verify_num'";
											
		$rtnBillRcvInfo = $this->bm->dataSelectDb1($strBillRcvInfo);
		// $this->data['rtnBillRcvInfo']=$rtnBillRcvInfo;		
					
		$str="select concat(right(YEAR(bill_date),2),'/',concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height,bill_rcv_stat,if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status 
		from shed_bill_master where verify_no='$verify_num'"; 
			
		$rtnBillList = $this->bm->dataSelectDb1($str);
		// $this->data['rtnBillList']=$rtnBillList;			
			
		$unit_no="";
		$cpa_vat_reg_no="";
		$ex_rate="";
		$bill_rcv_stat="";
			
		if(count($rtnBillList)>0)
		{
			$unit_no=$rtnBillList[0]['unit_no'];
			$cpa_vat_reg_no=$rtnBillList[0]['cpa_vat_reg_no'];
			$ex_rate=$rtnBillList[0]['ex_rate'];
			$bill_rcv_stat=$rtnBillList[0]['bill_rcv_stat'];
		}			

		$this->load->library('m_pdf');
		// $mpdf->use_kwt = true;

		$this->data['rtnBillList']=$rtnBillList;
		$this->data['verify_num']=$verify_num;
		$this->data['rtnContainerList']=$rtnContainerList;
		$this->data['rtnBillRcvInfo']=$rtnBillRcvInfo;		

		$pdf = $this->m_pdf->load();
				
		$pdf->SetWatermarkText('CPA CTMS');
		$pdf->showWatermarkText = true;

		$stylesheet = file_get_contents('assets/stylesheets/fontFamily.css'); // external css
			
		$pdf->AddPage('P', // L - landscape, P - portrait
					'', '', '', '',
					5, // margin_left
					5, // margin right
					10, // margin top
					10, // margin bottom
					10, // margin header
					10); // margin footer
					
		$html=$this->load->view('releaseOrderFormViewPDF_static',$this->data, true);
									
		$pdfFilePath ="ReleaseOrder-".time()."-download.pdf";

		// $pdf->useSubstitutions = true; 					
		// $pdf->setFooter('|Page {PAGENO} of {nb}|');   
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
				
		$pdf->Output($pdfFilePath, "I");
		//--
	}
		
}
?>