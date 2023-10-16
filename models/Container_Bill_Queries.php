<?php
class Container_Bill_Queries extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session'); 
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('file');												
	}
	
	public function dataSelectCtmsMis($str)
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		$data=$query->result_array();
		return $data;
	}
	
	public function dataInsertCtmsmis($str) 
	{
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		return $query;
	}
	
	public function dataUpdateCtmsmis($str) {
		// ctmsmis
		$CI = &get_instance();
		$this->db2 = $CI->load->database('third', TRUE);
		$query=$this->db2->query($str);
		return $query;
		 
    }
	
	public function dataSelectOracle($str)
	{
		// Oracle(sparcsn4)
		$CI = &get_instance();
		$this->db2 = $CI->load->database('second', TRUE);
		$query=$this->db2->query($str);
		$data=$query->result_array();
		return $data;
	}
	
	public function dataSelectBilling($str)
	{		
		$CI = &get_instance();
		$this->db2 = $CI->load->database('sixth', TRUE);
		$query=$this->db2->query($str);
		$data=$query->result_array();
		return $data;
	}
	
	function getTariffId($unit_gkey,$event_gkey)
	{		
		$str = "SELECT  inv_unit.gkey,category,freight_kind as frt,is_oog,
		substr(ref_equip_type.nominal_length,-2) AS sz,
		substr(ref_equip_type.nominal_height,-2) AS ht,ref_equip_type.iso_group AS iso_grp  
		FROM inv_unit
		INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		WHERE inv_unit.gkey='$unit_gkey'";
		
		$contList = $this->dataSelectOracle($str);
		
		$gkey = "";
		$iso_grp = "";
		$size = "";
		$height = "";
		$freight_kind = "";
		$is_oog = "";
		$category = "";
		for($i=0;$i<count($contList);$i++)
		{
			$gkey = $contList[$i]["GKEY"];
			$iso_grp = $contList[$i]["ISO_GRP"];
			$size = $contList[$i]["SZ"];
			$height = $contList[$i]["HT"];
			$freight_kind = $contList[$i]["FRT"];
			$category = $contList[$i]["CATEGORY"];
			$is_oog = $contList[$i]["IS_OOG"];
		}
		
		if($freight_kind=='MTY')
			$freight_kind = "EMPTY";
				
		$tarrifId = "";
		if($event_gkey==1800) // Pangoan Discharge, Status Change Invoice (PCT to CPA)
		{
			if($iso_grp=="UT")
			{
				if($height=="96")
					$tarrifId = "DISCHARGING_".$freight_kind."_OT_OH_".$size;
				else
					$tarrifId = "DISCHARGING_".$freight_kind."_OT_".$size;
			}
			else if($iso_grp=="PL" or $iso_grp=="PF")
			{
				if($height=="96")
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_OH_".$size;
				else
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_".$size;
			}
			else if($iso_grp=="PC" and $freight_kind='FCL')
			{
				if($height=="96")
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_OH_".$size;
				else
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_".$size;
			}
			else if($is_oog)
			{
				if($height=="96")
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_OH_".$size;
				else
					$tarrifId = "DISCHARGING_".$freight_kind."_FT_".$size;
			}
			else if($height=="96")
				$tarrifId = "DISCHARGING_".$freight_kind."_OH_".$size;
			else
				$tarrifId = "DISCHARGING_".$freight_kind."_".$size;
		} 
		else if($event_gkey==1780) // Pangoan Loading
		{
			if($freight_kind=="FCL" or $freight_kind=="LCL")
			{
				$tarrifId = "RIVER_DUES_LOAD_".$size;
			}
			else
			{
				$tarrifId = "RIVER_DUES_".$freight_kind."_".$size;
			}
		}	
		else if($event_gkey==25 or $event_gkey==29 or $event_gkey==27) // 27=Pangoan Loading, Status Change Invoice(CPA to PCT),Status Change Invoice(ICD to PCT)
		{
			if($category=="IMPRT" OR $category=="EXPRT")
			{
				if($freight_kind=="FCL" or $freight_kind=="LCL")
				{
					if($iso_grp=="UT")
					{
						$tarrifId = $size."_FEET_STORAGE_OT";
					}
					else
					{
						$tarrifId = $size."_FEET_STORAGE";
					}
				}
				else
				{
					if($iso_grp=="UT" OR $iso_grp=="PL" OR $iso_grp=="PF" OR $iso_grp=="PC" OR $iso_grp=="PS")
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY_OT";
					}
					else
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY";
					}
				}
			}
			if($category=="STRGE")
			{
				if($freight_kind=="EMPTY")
				{
					if($iso_grp=="UT" OR $iso_grp=="PL" OR $iso_grp=="PF" OR $iso_grp=="PC" OR $iso_grp=="PS")
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY_OT";
					}
					else
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY";
					}
				}
			}
			if($category=="TRSHP")
			{
				if($freight_kind=="FCL" or $freight_kind=="LCL")
				{
					if($iso_grp=="UT")
					{
						$tarrifId = $size."_FEET_STORAGE_OT_TS";
					}
					else
					{
						$tarrifId = $size."_FEET_STORAGE_TS";
					}
				}
				else
				{
					if($iso_grp=="UT" OR $iso_grp=="PL" OR $iso_grp=="PF" OR $iso_grp=="PC" OR $iso_grp=="PS")
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY_OT_TS";
					}
					else
					{
						$tarrifId = $size."_FEET_STORAGE_EMPTY_TS";
					}
				}
			}
		}
		else if($event_gkey==1700) // Pangoan Loading
		{
			if($iso_grp=="UT")
			{
				$tarrifId = "LOAD_".$freight_kind."_OT_".$size;
			}
			else if($iso_grp=="PL" and $height=="96") //PL - Platform, PF-Flatrack
			{
				$tarrifId = "LOAD_".$freight_kind."_FT_OH_".$size;
			}
			else if($iso_grp=="PL") //PL - Platform, PF-Flatrack
			{
				$tarrifId = "LOAD_".$freight_kind."_FT_".$size;
			}
			else if(($is_oog) or ($is_oog=="1"))
			{
				$tarrifId = "LOAD_".$freight_kind."_OH_".$size;
			}
			else if($height=="96")
			{
				$tarrifId = "LOAD_".$freight_kind."_OH_".$size;
			}
			else
			{
				$tarrifId = "LOAD_".$freight_kind."_".$size;
			}
		}
		else if($event_gkey==411) // Status Change Invoice (CPA to PCT)
		{
			if($freight_kind=="EMPTY")
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_CPA_PNGA_MTY_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_CPA_TO_PNGA_MTY_".$size;
				}
			}
			else
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_CPA_PNGA_LOAD_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_CPA_TO_PNGA_LOAD_".$size;
				}
			}
		}
		else if($event_gkey==455) // Status Change Invoice (ICD to PCT)
		{
			if($freight_kind=="EMPTY")
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_ICD_PNGA_MTY_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_ICD_TO_PNGA_MTY_".$size;
				}
			}
			else
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_ICD_PNGA_LOAD_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_ICD_PNGA_MTY_OH_".$size;
				}
			}
		}
		else if($event_gkey==427) // Status Change Invoice (PCT to CPA)
		{
			if($freight_kind=="EMPTY")
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_PNGA_TO_CPA_MT_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_PNGA_TO_CPA_MTY_".$size;
				}
			}
			else
			{
				if($size=="40" and $height=="96")
				{
					$tarrifId = "EXTRA_MOV_PNGA_CPA_LOAD_OH_".$size;
				}
				else
				{
					$tarrifId = "EXTRA_MOV_PNGA_TO_CPA_LOAD_".$size;
				}
			}
		}
		// need to add more event
		return $tarrifId;
	}
	
	function is_bill_exists($bill_type,$rotation,$from_date,$mlo_code)		
	{		
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$cond = "";
		
			if($bill_type==2 or $bill_type==27 or $bill_type==67)
				$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."'";  
			else if($bill_type==3)
				$cond = " WHERE bill_type = '".$bill_type."' AND exp_rot = '".$rotation."'";  
			else if($bill_type==108 or $bill_type==112)		// added on 2019-08-28. Not in Bill s/w
				$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."'";  
			else if($bill_type==29)
				$cond = " WHERE bill_type = '".$bill_type."' AND exp_rot = '".$rotation."' mlo_code = '".$mlo_code."'";  
			else if($bill_type==11 or $bill_type==22)
				$cond = " WHERE bill_type = '".$bill_type."' AND DATE(bill_generation_date) = '".$from_date."'";  
			else            
				return false;

			$sql_chk_bill = "SELECT * FROM ".$this->get_table_name("mis_billing").$cond;	
			$rslt_chk_bill=$this->dataSelectCtmsMis($sql_chk_bill);
			
			if(count($rslt_chk_bill)>0)
				return true;
			else
				return false;
		}		
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
	
	function is_commonlanding_date_exists($bill_type,$rotation,$from_date,$mlo_code,$container_id)
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');	
		if($LoginStat!="yes")
		{
			$this->logout();			
		}
		else
		{
			return $this->is_bill_exists_common($bill_type,$rotation,$from_date,$mlo_code,$container_id);	
		}
	}
	
	function is_bill_exists_common($bill_type,$rotation,$from_date,$mlo_code,$container_id)	//is_commonlanding_date_exists
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$containerArray=explode(",",$container_id);
			sort($containerArray);
			$cont="'";
			foreach($containerArray as $name)
			{
				$cont=$cont.$name."','";
			}		
			$container_id=substr($cont,0,-2);
			$container_id=str_replace("'","",$container_id);
			//2019-08-28
			
			if($bill_type==2 or $bill_type==27 or $bill_type==67 or $bill_type==108 or $bill_type==112 or $bill_type==120 or $bill_type==128)
			{
				if($mlo_code=="")
					$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."'";
				else
					$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."' AND mlo_code= '".$mlo_code."'" ;
			}
			else if($bill_type==3)
			{
				if($mlo_code=="")
					$cond = " WHERE bill_type = '".$bill_type."' AND exp_rot = '".$rotation."'";
				else
					$cond = " WHERE bill_type = '".$bill_type."' AND exp_rot = '".$rotation."' AND mlo_code= '".$mlo_code."'" ;
			}
			else if($bill_type==11 or $bill_type==22) 
				$cond = " WHERE bill_type = '".$bill_type."' AND DATE(bill_generation_date) = '".$from_date."' AND mlo_code = '".$mlo_code."'";
			else if($bill_type==63 or $bill_type==47 or $bill_type==51 or $bill_type==59 or $bill_type==116 or $bill_type==124)            
				$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."' AND mlo_code='".$mlo_code."' AND containers in ('".$container_id."')" ;
			else if($bill_type==132)            
            	$cond = " WHERE bill_type = '".$bill_type."' AND imp_rot = '".$rotation."' AND containers in ('".$container_id."')" ;
			else if($bill_type==135)
				return false;
			
			if($bill_type==75)                
				$sql_chk_bill = "SELECT * FROM ".$this->get_table_name("mis_billing_details")." WHERE invoice_type = '".$bill_type."' AND id = '".$container_id."'";
			else        
				$sql_chk_bill = "SELECT * FROM ".$this->get_table_name("mis_billing").$cond;
				
			$rslt_chk_bill=$this->dataSelectCtmsMis($sql_chk_bill);
			
			if(count($rslt_chk_bill)>0)
				return true;
			else
				return false;
		}
	}
	
	function Generate_Bill($bill_type,$mlo_code,$rotation,$container_id,$from_date,$rotation_type)
	{		
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			
			if($bill_type==18)
			{
				//Insert_Miscellaneous_Bill(bill);             
				$sql_miscellaneous_bill = "INSERT INTO ".$this->get_table_name("mis_billing")." (bill_type,mlo_code,pdf_draft_view_name,pdf_detail_view_name,billing_date) VALUES('".$bill_type."','".$mlo_code."','pdfMiscellineousDraftInvoice','pdfMiscellineousDetailsDraftInvoice',NOW())";
				
				//	$this->bm->dataInsertDb2($sql_miscellaneous_bill);		//commented to not insert
			}
			else
			{		
				$tmp_draft_list=$this->Load_Draft_Info($rotation,$bill_type,$mlo_code,$container_id,$from_date,$rotation_type);//done 
				
				$draft_list=$tmp_draft_list[0];		//get values[] from rtn_value[]
				$ImpRot=$tmp_draft_list[1];			
				$ExpRot=$tmp_draft_list[2];
								
				$this->Insert_Draft_Info($draft_list,$ImpRot,$ExpRot,$bill_type,$mlo_code,$container_id);//done
				// return;
				$this->Insert_Bill_Details($bill_type,$mlo_code,$rotation,$container_id); //done
				return true;
			}
		}
	}
	
	function Load_Draft_Info($rotation,$bill_type,$mlo_code,$container_id,$from_date,$rotation_type)
	{
		
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			if($bill_type==108) //DISCHARGE
			{		
				
				$category = "EXPRT";
				$this->genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id);//done				
				$query = $this->getPangoanDischargeQuery($rotation,$bill_type,$mlo_code);//done 		
				
				$ImpRot = $rotation;	
				$ExpRot = "";
			}
			if($bill_type==112) //LOADING
			{
				$category = "IMPRT";
				
				$this->genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id);//done
				$query = $this->getPangoanLoadingQuery($rotation,$bill_type,$mlo_code);//done
				
				$ImpRot = $rotation;	
				$ExpRot = "";
			}	
			if($bill_type==116)
			{
				$category = "IMPRT";
				
				$this->genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id);//done				
				$query = $this->getPangoanStatusChangeQuery($rotation,$bill_type,$mlo_code);//done
				$ImpRot = $rotation;	
				$ExpRot = "";
			}
			if($bill_type==124) // Status Change Invoice (PCT to CPA)
			{
				//	echo "inside PCTToCPA";
				$category = "IMPRT";
				
				$this->genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id);//done
				$query = $this->getPangoanStatusChangePCTToCPAQuery($rotation,$bill_type,$mlo_code);//done
				
				$ImpRot = $rotation;	
				$ExpRot = "";
			}
			if($bill_type==135)    //Status Change Invoice (ICD to PCT)
			{
				$category = "IMPRT";
				$this->genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id);//done
				$query = $this->getICDToPangoanStatusChangeQuery($rotation,$bill_type,$mlo_code);//done

				$ImpRot = $rotation;
				$ExpRot = "";			
			}                                       //2019-10-23 - end
			
			//----------after geting $query - start
			$rslt_query=$this->dataSelectOracle($query);
			
						
			$values=array();
			
			//	$draft_imp_rot="";
			//	$draft_exp_rot="";
			
			$draft_mlo_code="";
			$draft_view_name="";
			$draft_details_view_name="";
			$draft_exchange_rate="";

				
			for($i=0;$i<count($rslt_query);$i++)
			{	
				// $inv_unit_gkey = $rslt_query[$i]['GKEY'];
				// $ata = $rslt_query[$i]['ATA'];						
				// $sql_get_invoice_data = "SELECT ctmsmis.mis_inv_tarrif.gkey,ctmsmis.mis_inv_tarrif.invoice_type
					// FROM ctmsmis.mis_inv_tarrif
					// INNER JOIN billing.bil_tariffs ON billing.bil_tariffs.id=ctmsmis.mis_inv_tarrif.tarrif_id
					// WHERE ctmsmis.mis_inv_tarrif.invoice_type='$bill_type' AND ctmsmis.mis_inv_tarrif.gkey='$inv_unit_gkey'";
				// $rslt_invoice_data=$this->dataSelectCtmsMis($sql_get_invoice_data);		
				//if(count($rslt_invoice_data) > 0){
					
					$draft_info=array();
					$draft_imp_rot="";
					$draft_exp_rot="";
					
					if($bill_type==29)
					{
						$draft_imp_rot=$rslt_query[$i]['vsl_visit_dtls_ib_vyg'];
						$draft_exp_rot=$rslt_query[$i]['vsl_visit_dtls_ob_vyg'];
						
					}				
					
					$draft_mlo_code = $rslt_query[$i]['MLO'];
					$draft_view_name = $rslt_query[$i]['DRAFTVIEWNAME'];
					$draft_details_view_name = $rslt_query[$i]['DRAFTDETAILSVIEWNAME'];
					
					$draft_exchange_rate = "";
					$sql_get_rate = "";
					
					$ata=$rslt_query[$i]['ATA'];
					
					$ata_dt = date("Y-m-d", strtotime($ata));					
					
					$sql_get_rate = "SELECT rate FROM bil_currency_exchange_rates 
											WHERE to_char(effective_date,'yyyy-mm-dd')='$ata_dt'";
					$rslt_rate=$this->dataSelectBilling($sql_get_rate);
					
					if(count($rslt_rate)==0){
						$sql_get_rate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						$rslt_rate=$this->dataSelectBilling($sql_get_rate);
					}
					for($r=0;$r<count($rslt_rate);$r++){
						$draft_exchange_rate = $rslt_rate[$r]['RATE'];
					}
									
					
					array_push($draft_info,$draft_imp_rot,$draft_exp_rot,$draft_mlo_code,$draft_view_name,
								$draft_details_view_name,$draft_exchange_rate);
								
					array_push($values,$draft_info);
					
				//}		
			}
			
			
			$rtn_value=array();
			array_push($rtn_value,$values,$ImpRot,$ExpRot);
						
			return $rtn_value; 			
		}
	}
	
	function genarate_pangoan_dish_and_load_tariff_new_rate($rotation,$bill_type,$mlo_code,$category,$container_id)
	{	
		$extraCond = "";
	
		$containerArray=explode(",",$container_id);
		$cont="'";
		foreach($containerArray as $name)
		{
			$cont=$cont.$name."','";
		}		
		
		$container_id=substr($cont,0,-2);
		//	$container_id=sort($container_id);
		
		//	echo "container id in tarrif_new_rate : ".$container_id;
        if($mlo_code!="")
        {
			$extraCond = " AND r.id = '".$mlo_code."'";
        }
		
		if($bill_type==112)
		{
			$sql_get_n4_data = "SELECT inv_unit.gkey,r.id AS mlo,category,freight_kind,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata,destination 
			FROM inv_unit
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			LEFT JOIN inv_goods on inv_goods.gkey=inv_unit.goods
			INNER JOIN  ( ref_bizunit_scoped r 
			LEFT JOIN ( ref_agent_representation X 
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey ) ON r.gkey=X.bzu_gkey) ON r.gkey = inv_unit.line_op
			WHERE ib_vyg='".$rotation."'".$extraCond;
		}
		else if($bill_type==116)
		{
			$sql_get_n4_data = "SELECT inv_unit.gkey,r.id AS mlo,category,freight_kind,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata
			FROM inv_unit
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN  (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv_unit.line_op
			WHERE inv_unit.id IN(".$container_id.") AND ib_vyg='".$rotation."'".$extraCond;
		}
		else if($bill_type==124)
		{
			$sql_get_n4_data = "SELECT inv_unit.gkey,r.id AS mlo,category,freight_kind,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata 
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv_unit.line_op 
			WHERE inv_unit.id IN(".$container_id.") AND  ib_vyg='".$rotation."'".$extraCond;
		}
		else if($bill_type==135)    //Status Change Invoice (ICD to PCT)
		{
			$sql_get_n4_data = "select inv_unit.gkey,r.id as mlo,category,freight_kind,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata
			from inv_unit
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
			inner join vsl_vessel_visit_details on vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN  ( ref_bizunit_scoped r
			LEFT JOIN ( ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey) ON r.gkey = inv_unit.line_op
			where inv_unit.id in(".$container_id.") and  ib_vyg='".$rotation."'".$extraCond;
		}                            //2019-10-23 - end
		else
		{
			$sql_get_n4_data = "SELECT inv_unit.gkey,r.id as mlo,category,freight_kind,
			to_char(argo_carrier_visit.ata,'yyyy-mm-dd') as ata
			FROM inv_unit
			INNER join inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER join argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
			INNER join vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv_unit.line_op 
			where ib_vyg='".$rotation."' AND inv_unit.flex_string12 IS NULL ".$extraCond;
			//,if(date(argo_carrier_visit.ata)<'2017-01-17',1,0) as atast
			
		}
		
		$rslt_get_n4_data=$this->dataSelectOracle($sql_get_n4_data);
		
		$count = 0;
		$cnt=0;
		for($i=0;$i<count($rslt_get_n4_data);$i++)
		{
			$cnt++;
			$gkey = $rslt_get_n4_data[$i]['GKEY'];
			$catagory = $rslt_get_n4_data[$i]['CATEGORY'];
			$freight_kind = $rslt_get_n4_data[$i]['FREIGHT_KIND'];
			$ata = $rslt_get_n4_data[$i]['ATA'];
			
			$atast = 0;
			if( date("Y-m-d", strtotime($ata)) < date("2017-01-17", strtotime($ata)) ){
				$atast = 1;
			} else {
				$atast = 0;
			}
			
			//$atast = $rslt_get_n4_data[$i]['ATAST'];
			//$atast = 0;// forcely putted values as 0
			//echo "gkey : ".$gkey;
			//echo "<br>";	
			
			if($bill_type==112) //loading - here
			{
				$destination = $rslt_get_n4_data[$i]['DESTINATION'];
				$tarrifId = 0;
				if($freight_kind!="MTY")	
				{
					$tarrifId = $this->getTariffId($gkey,1780);	//here
					
					$sql_replace_1 = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
						VALUES('".$gkey."','".$tarrifId."','".$bill_type."',178)";
						
					$this->dataUpdateCtmsmis($sql_replace_1); //to check PCT Cont Load
					
					
				}
							
				if(($atast == 1) or ($freight_kind=="MTY") or ($destination==2592))
				{
					$tarrifId = $this->getTariffId($gkey,27);	
					
					$sql_replace_2 = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
					VALUES('".$gkey."','".$tarrifId."','".$bill_type."',27)";
					
					$this->dataUpdateCtmsmis($sql_replace_2); //to check PCT Cont Load
					
					/* commented by awal, will not apply for offdoc cont
					if($catagory=="EXPRT")			
					{
						Lift OFF
						$sql_replace_3="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."',(SELECT ctmsmis.get_mis_bill_tarrif('".$gkey."',3410)),'".$bill_type."',3410)";
						
						$this->bm->dataUpdate($sql_replace_3);
					
						// Lift ON
						$sql_replace_4="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) values('".$gkey."',(select ctmsmis.get_mis_bill_tarrif('".$gkey."',172)),'".$bill_type."',172)";
						
						$this->bm->dataUpdate($sql_replace_4);
					}
					*/
				}
				
				
				$tarrifId = $this->getTariffId($gkey,1700);
				
				$sql_replace_5="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
				VALUES ('".$gkey."','".$tarrifId."','".$bill_type."',17)";
				
            	$this->dataUpdateCtmsmis($sql_replace_5); //to check PCT Cont Load
				
			}
			else if($bill_type==116)
			{
				$sql_extraEvent = "SELECT srv_event_types.gkey,srv_event_types.id 
				FROM srv_event
				INNER JOIN srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
				WHERE srv_event.applied_to_gkey = '".$gkey."' AND event_type_gkey IN(6029182,6092093)";
				
				$rslt_extraEvent=$this->dataSelectOracle($sql_extraEvent);
				$st = 0;
				
				for($j=0;$j<count($rslt_extraEvent);$j++)
				{
					$st++;
					$event_gkey = $rslt_extraEvent[$j]['GKEY'];
					$event_id = $rslt_extraEvent[$j]['ID'];
					
					if ($event_gkey == 6029182)
                    {
						$tarrifId = $this->getTariffId($gkey,411);	
						$sqlEM="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."','".$tarrifId."','".$bill_type."',411)";
						
                    	$this->dataUpdateCtmsmis($sqlEM); // to chk STATUS CHANGE INVOICE(CPA TO PCT)
                    }
					else
                    {
						$sqlEST="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."','".$event_id."','".$bill_type."',413)";
						
						$this->dataUpdateCtmsmis($sqlEST); // to chk STATUS CHANGE INVOICE(CPA TO PCT)	
                    }
					
					$tarrifId = $this->getTariffId($gkey,27);
					$sqlSTRG="REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."','".$tarrifId."','".$bill_type."',27)";
					
					$this->dataUpdateCtmsmis($sqlSTRG); // to chk STATUS CHANGE INVOICE(CPA TO PCT)	
				}				
			}
			else if($bill_type==124) 
			{
				$sql_extraEvent = "SELECT srv_event_types.gkey,srv_event_types.id 
				FROM srv_event
				INNER JOIN srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
				WHERE srv_event.applied_to_gkey = '".$gkey."' AND event_type_gkey IN(6029198,6092119,6092180)";
				// 427 = 6029198, 429 = 6092119, 435 = 6092180

				$rslt_extraEvent=$this->dataSelectOracle($sql_extraEvent);
				$st = 0;
				
				for($j=0;$j<count($rslt_extraEvent);$j++)		
				{
					$st++;
					
					$event_gkey = $rslt_extraEvent[$j]['GKEY'];
					$event_id = $rslt_extraEvent[$j]['ID'];
				
					if($event_gkey == 6029198)
					{
						$tarrifId = $this->getTariffId($gkey,427);	
						$sqlEM = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
						VALUES('".$gkey."','".$tarrifId."','".$bill_type."',427)";
					
						$this->dataUpdateCtmsmis($sqlEM);
					}
					else if($event_gkey == 6092180) 
					{
						$tarrifId = $this->getTariffId($gkey,1800);
						$sql4 = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
						VALUES('".$gkey."','".$tarrifId."','".$bill_type."',435)";
					
						$this->dataUpdateCtmsmis($sql4);
					}
					else
					{
						$sqlEST = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type)
						VALUES('".$gkey."','".$event_id."','".$bill_type."',429)";
					
						$this->dataUpdateCtmsmis($sqlEST);
					}
					
					$tarrifId = $this->getTariffId($gkey,27);
					$sqlSTRG = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
					VALUES('".$gkey."','".$tarrifId."','".$bill_type."',27)";
				
					$this->dataUpdateCtmsmis($sqlSTRG);
				}
			}
			// ICD to Pangoan
			else if ($bill_type==135)
			{
				//439,441
				$sql_extraEvent = "SELECT srv_event_types.gkey,srv_event_types.id FROM srv_event
				INNER JOIN srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
				WHERE srv_event.applied_to_gkey = '".$gkey."' AND event_type_gkey IN(6092112,6029190)"; //453=>6092112,455=>6029190

				$rslt_extraEvent=$this->dataSelectOracle($sql_extraEvent);
				$st = 0;
				
				for($j=0;$j<count($rslt_extraEvent);$j++)	
				{
					$st++;
					
					$event_gkey = $rslt_extraEvent[$j]['GKEY'];
					$event_id = $rslt_extraEvent[$j]['ID'];

					
					if($event_gkey==6092112)
					{
						// 453=>6092112
						$sql453 = "replace into ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
						values ('".$gkey."','".$event_id."','".$bill_type."',453)";
					
						$this->dataUpdateCtmsmis($sql453);					
					}
					
					if($event_gkey==6029190)
					{
						// 455
						$tarrifId = $this->getTariffId($gkey,455);	
						$sql455 = "replace into ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
						values ('".$gkey."','".$tarrifId."','".$bill_type."',455)";
					
						$this->dataUpdateCtmsmis($sql455);	
					}					
				}
				
				// Storage
				$tarrifId = $this->getTariffId($gkey,27);
				$sqlSTRG = "replace into ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) 
				values ('".$gkey."','".$tarrifId."','".$bill_type."',27)";
				
				$this->dataUpdateCtmsmis($sqlSTRG);						
			}
			// ICD to Pangoan
			else
			{
				$tarrifId = $this->getTariffId($gkey,1800);		
				
				//$sql4 = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."',(SELECT ctmsmis.get_mis_bill_tarrif('".$gkey."',1800)),'".$bill_type."',18)";
				$sql4 = "REPLACE INTO ".$this->get_table_name("mis_inv_tarrif")."(gkey,tarrif_id,invoice_type,event_type) VALUES('".$gkey."','".$tarrifId."','".$bill_type."',18)";
				$this->dataUpdateCtmsmis($sql4); //to check PCT Cont Discharge
				
			}
			
			
			
			$count++;
			
		}
		
	}
	
	function getPangoanDischargeQuery($rotation,$bill_type,$mlo_code)		
    {
        $extraCond = "";

        if($mlo_code!="") 
		{
			$extraCond = " AND r.id = '".$mlo_code."'";
        }
		
		return  "SELECT DISTINCT r.id AS mlo,'pdfPangoanDischargeInvoice' AS draftViewName,
				'pdfPangoanDischargeDraftDetailsInvoice' AS draftDetailsViewName,ib_vyg AS vsl_visit_dtls_ib_vyg,
				to_char(arcar.ata,'yyyy-mm-dd') AS ATA,ob_vyg AS vsl_visit_dtls_ob_vyg
				FROM inv_unit inv
				INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey
				INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=arcar.cvcvd_gkey
				INNER JOIN (ref_bizunit_scoped r LEFT JOIN (
				ref_agent_representation X LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) 
				ON r.gkey = inv.line_op
				WHERE ib_vyg = '".$rotation."'".$extraCond." ORDER BY r.id";		
    }
	
	function getPangoanLoadingQuery($rotation,$bill_type,$mlo_code)
    {		
        $extraCond = "";

        if($mlo_code!="")
		{
			$extraCond = " AND r.id = '".$mloCode."' ";
        }
		
        return "SELECT DISTINCT r.id AS mlo,'pdfPangoanLoadingInvoice' AS draftViewName,
				'pdfPangoanLoadingDraftDetailsInvoice' AS draftDetailsViewName,ib_vyg AS vsl_visit_dtls_ib_vyg,
				to_char(arcar.ata,'yyyy-mm-dd') AS ATA,ob_vyg AS vsl_visit_dtls_ob_vyg
				FROM inv_unit inv
				INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey
				INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=arcar.cvcvd_gkey
				INNER JOIN (ref_bizunit_scoped r
				LEFT JOIN (ref_agent_representation X
				LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op		
				WHERE ib_vyg = '".$rotation."'".$extraCond." ORDER BY r.id";
    }
	
	function getPangoanStatusChangeQuery($rotation,$bill_type,$mlo_code)		//CPA to PCT
    {
		$extraCond = "";

		if($mlo_code!="")
		{
			$extraCond = " AND r.id = '".$mlo_code."'";
		}
		return "SELECT DISTINCT r.id AS mlo,'pdfPangoanStatusChangeInvoice' AS draftViewName,
				'pdfPangoanStatusChangeDraftDetailsInvoice' AS draftDetailsViewName,ib_vyg AS vsl_visit_dtls_ib_vyg,
				to_char(arcar.ata,'yyyy-mm-dd') AS ATA,inv.gkey,ob_vyg AS vsl_visit_dtls_ob_vyg
				FROM inv_unit inv 
				INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey
				INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=arcar.cvcvd_gkey
				INNER JOIN (ref_bizunit_scoped r
				LEFT JOIN (ref_agent_representation X
				LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op
				WHERE ib_vyg = '".$rotation."'".$extraCond." ORDER BY r.id";
    }

	function getPangoanStatusChangePCTToCPAQuery($rotation,$bill_type,$mlo_code)
    {
		$extraCond = "";

		if($mlo_code!="") 
		{
			$extraCond = " AND r.id = '".$mlo_code."'";
		}
		
		return "SELECT DISTINCT r.id AS mlo,'pdfPangoanStatusChangePCTToCPAInvoice' AS draftViewName, 
		'pdfPangoanStatusChangePCTToCPADraftDetailsInvoice' AS draftDetailsViewName,ib_vyg AS vsl_visit_dtls_ib_vyg,
		to_char(arcar.ata,'yyyy-mm-dd') AS ATA,inv.gkey,ob_vyg AS vsl_visit_dtls_ob_vyg
		FROM inv_unit inv 
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=arcar.cvcvd_gkey 
		INNER JOIN (ref_bizunit_scoped r
		LEFT JOIN (ref_agent_representation X
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op
		WHERE ib_vyg = '".$rotation."'".$extraCond." ORDER BY r.id";
    }
	
	function getICDToPangoanStatusChangeQuery($rotation,$bill_type,$mlo_code)
	{
		$extraCond = "";

		if($mlo_code!="") 
		{
			$extraCond = " AND r.id = '".$mlo_code."'";
		}
		
		return "SELECT DISTINCT r.id AS mlo,'pdfDraftICDToPCTStatusChangeInvoice' AS draftViewName,
		'pdfDraftICDToPCTStatusChangeDetailInvoice' AS draftDetailsViewName,ib_vyg AS vsl_visit_dtls_ib_vyg,
		to_char(arcar.ata,'yyyy-mm-dd') AS ATA,ob_vyg AS vsl_visit_dtls_ob_vyg		
		FROM inv_unit inv
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=arcar.cvcvd_gkey
		INNER JOIN  ( ref_bizunit_scoped r
		LEFT JOIN ( ref_agent_representation X
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op
		WHERE ib_vyg = '".$rotation."'".$extraCond." ORDER BY r.id";
	}

	function Insert_Draft_Info($draft_list,$ImpRot,$ExpRot,$bill_type,$mlo_code,$container_id)
	{
		//2019-08-28
		$containerArray=explode(",",$container_id);
		sort($containerArray);
		$cont="'";
		foreach($containerArray as $name)
		{
			$cont=$cont.$name."','";		//or $cont=$cont.$name.",";
		}		
		$container_id=substr($cont,0,-2);
		$container_id=str_replace("'","",$container_id);
		//2019-08-28
		$login_id = $this->session->userdata('login_id');	
				
				
		for($i=0;$i<count($draft_list);$i++)
		{
			$draft_mlocode=$draft_list[$i][2];
			$draft_DraftViewName=$draft_list[$i][3];
			$draft_DraftDetailsViewName=$draft_list[$i][4];
			$draft_ExChgRate=$draft_list[$i][5];			
						
			if($bill_type==47 or $bill_type==51 or $bill_type==59 or $bill_type==116 or $bill_type==124 or $bill_type==132)  
			{
				$sql_mis_billing_insert = "INSERT INTO ".$this->get_table_name("mis_billing")." (imp_rot,exp_rot,bill_type,mlo_code,pdf_draft_view_name,pdf_detail_view_name,billing_date,created_user,exrate,containers) 
				VALUES('".$ImpRot."','".$ExpRot."','".$bill_type."','".$draft_mlocode."','".$draft_DraftViewName."','".$draft_DraftDetailsViewName."',NOW(),'".$login_id."','".$draft_ExChgRate."','".$container_id."')";
			}
			else if($bill_type==135)   //2019-10-23 - start
			{				
				$sql_mis_billing_insert = " INSERT INTO  ".$this->get_table_name("mis_billing")." (imp_rot,exp_rot,bill_type,mlo_code,
												pdf_draft_view_name,pdf_detail_view_name,billing_date,created_user,exrate,containers) 
											VALUES('".$ImpRot."','".$ExpRot."','".$bill_type."','".$draft_mlocode."',
											'".$draft_DraftViewName."','".$draft_DraftDetailsViewName."',NOW(),'".$login_id."',
											'".$draft_ExChgRate."','".$container_id."')";
			}   						//2019-10-23 - end

			else	//for bill_type 108 and 112. add if_else for other bills
			{
				$sql_mis_billing_insert = "INSERT INTO ".$this->get_table_name("mis_billing")." (imp_rot,exp_rot,bill_type,mlo_code,pdf_draft_view_name,pdf_detail_view_name,billing_date,created_user,exrate) 
				VALUES('".$ImpRot."','".$ExpRot."','".$bill_type."','".$draft_mlocode."','".$draft_DraftViewName."','".$draft_DraftDetailsViewName."',NOW(),'".$login_id."','".$draft_ExChgRate."')";
			}				
			
			$this->dataInsertCtmsmis($sql_mis_billing_insert); 
			
		}	
		
	}

	function Insert_Bill_Details($bill_type,$mlo_code,$rotation,$container_id)
	{
		if($bill_type==108)
        {
            $this->Insert_Pangoan_Discharch_Details_Data($rotation,$bill_type,$mlo_code);//done
        }
		else if($bill_type==112)
        {
			$this->Insert_Pangoan_Load_Details_Data($rotation,$bill_type,$mlo_code);//done
        }		
		else if($bill_type==116)
        {
            $this->Insert_Pangoan_StatusChange_Details_Data($rotation,$bill_type,$mlo_code,$container_id);//done
        }
		else if($bill_type==124)
        {
            $this->Insert_Pangoan_StatusChange_PCTToCPA_Details_Data($rotation,$bill_type,$mlo_code,$container_id);//done
        }
		else if($bill_type==135)   //2019-10-23 - start
        {
            $this->Insert_ICDToPangoan_StatusChange_Details_Data($rotation,$bill_type,$mlo_code,$container_id);//done
        }							//2019-10-23 - end
	}
	
	function Insert_Pangoan_Discharch_Details_Data($rot,$bill_type,$mlo_code)
	{
		$extraCond="";
		
		if($mlo_code!="")
			$extraCond = " AND r.id = '".$mlo_code."' ";
		
		$query="select inv.gkey AS gkey, to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in ,
		(select ref_equip_type.iso_group from ref_equip_type
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
		'' AS destination,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
		(SELECT qua.id FROM vsl_vessel_berthings brt INNER JOIN argo_quay qua ON qua.gkey=brt.quay WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,
		to_char(arcar.ata,'yyyy-mm-dd') AS billingDate,
		to_char(arcar.ata,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
		to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,
		inv.id AS id,
		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,

		((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
		inv.freight_kind,'15.00' AS vatperc,NVL(fcy.flex_string06,'W') AS wpn,inv.flex_string12

		FROM inv_unit inv
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey
		INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey
		INNER JOIN (ref_bizunit_scoped r
		LEFT JOIN (ref_agent_representation X
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op
		INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
		WHERE dtl.ib_vyg='".$rot."' ".$extraCond;
		
		$rslt=$this->dataSelectOracle($query);
		
		for($i=0;$i<count($rslt);$i++)
		{
			$gkey = $rslt[$i]['GKEY'];
			$flex_string12 = $rslt[$i]['FLEX_STRING12'];
			$vatperc = $rslt[$i]['VATPERC'];
			$billingDate = $rslt[$i]['BILLINGDATE'];
			$billing_dt = date("Y-m-d", strtotime($billingDate));
						
			// $sql_get_invoice_data = "SELECT mis_inv_tarrif.gkey,mis_inv_tarrif.invoice_type FROM ctmsmis.mis_inv_tarrif
				// INNER JOIN billing.bil_tariffs ON billing.bil_tariffs.id=ctmsmis.mis_inv_tarrif.tarrif_id
				// WHERE ctmsmis.mis_inv_tarrif.invoice_type='108' AND ctmsmis.mis_inv_tarrif.gkey='$gkey'";
			// $rslt_invoice_data=$this->dataSelectCtmsMis($sql_get_invoice_data);			
			// if(count($rslt_invoice_data) > 0){
				
				$description="";
				$Tarif_rate="";
				$queryTarifRate="";
				
				
				// if($flex_string12=="5239"){
					// $queryTarifRate="SELECT amount*75/100 AS Tarif_rate FROM billing.bil_tariff_rates 
						// INNER JOIN billing.bil_tariffs ON billing.bil_tariffs.gkey=billing.bil_tariff_rates.tariff_gkey
						// WHERE tariff_gkey = billing.bil_tariffs.gkey ORDER BY effective_date DESC LIMIT 1";
				// } else {
					// $queryTarifRate="SELECT amount*75/100 AS Tarif_rate FROM billing.bil_tariff_rates 
						// INNER JOIN billing.bil_tariffs ON billing.bil_tariffs.gkey=billing.bil_tariff_rates.tariff_gkey
						// WHERE tariff_gkey = billing.bil_tariffs.gkey ORDER BY effective_date DESC LIMIT 1";
				// }
				// $rslt_tarif_rate=$this->dataSelectCtmsMis($queryTarifRate);				
				// for($tr=0;$tr<count($rslt_tarif_rate);$tr++){
					// $Tarif_rate = $rslt_tarif_rate[$tr]['Tarif_rate'];					
				// }
				
				$tarrif_id_data = "";
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id 
								FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='108'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
				}
				
				$bil_tariffs_gkey = "";
				$queryBillingDescription = "SELECT bil_tariffs.description,gkey
											FROM bil_tariffs WHERE id='$tarrif_id_data'";
				$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
				for($desc=0;$desc<count($rslt_billing_desc);$desc++){
					$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
				}
				
				if($flex_string12=="5239"){
					$queryTarifRate="SELECT trunc(amount*75/100, 4) AS Tarif_rate FROM bil_tariff_rates 
									WHERE tariff_gkey='$bil_tariffs_gkey'
									ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
				} else {
					$queryTarifRate="SELECT trunc(amount*75/100, 4) AS Tarif_rate FROM bil_tariff_rates 
									WHERE tariff_gkey='$bil_tariffs_gkey'
									ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
				}
				$rslt_tarif_rate=$this->dataSelectBilling($queryTarifRate);
				for($tr=0;$tr<count($rslt_tarif_rate);$tr++){
					$Tarif_rate = $rslt_tarif_rate[$tr]['TARIF_RATE'];	//4 digits after demical point				
				}
				
				$exchangeRate = "";
				$rslt_exchange_rate = "";
				$queryExchangeRate = "";
				$currency_gkey = "";
				$query_currency_gkey = "";
				$query_currency_gkey = "SELECT currency_gkey FROM bil_tariff_rates 
										INNER JOIN bil_tariffs ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
										WHERE tariff_gkey='$bil_tariffs_gkey' ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
				$rslt_currency_gkey=$this->dataSelectBilling($query_currency_gkey);
				for($cgkey=0;$cgkey<count($rslt_currency_gkey);$cgkey++){
					$currency_gkey = $rslt_currency_gkey[$cgkey]['CURRENCY_GKEY'];
				}
				
				if($currency_gkey != "370"){
					$queryExchangeRate = "SELECT rate FROM bil_currency_exchange_rates 
						WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
					$rslt_exchange_rate=$this->dataSelectBilling($queryExchangeRate);
					if(count($rslt_exchange_rate) == "0"){
						$queryExchangeRate = "SELECT rate FROM bil_currency_exchange_rates 
								ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						$rslt_exchange_rate=$this->dataSelectBilling($queryExchangeRate);
					}
					
					for($er=0;$er<count($rslt_exchange_rate);$er++){
						$exchangeRate = $rslt_exchange_rate[$er]['RATE'];
					}
					
				} else {
					$exchangeRate = "1";
				}
				
				//echo $billing_dt." - ".$queryExchangeRate." = ".$exchangeRate."<br>";
				
				$currency_gkey = "";
				$sql_currency_gkey="SELECT currency_gkey FROM bil_tariff_rates
									INNER JOIN bil_tariffs ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
									WHERE tariff_gkey=bil_tariffs.gkey ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
				$rslt_cgkey=$this->dataSelectBilling($sql_currency_gkey);
				for($cg=0;$cg<count($rslt_cgkey);$cg++){
						$currency_gkey = $rslt_cgkey[$cg]['CURRENCY_GKEY'];
					}
				
				$amt = 0;
				$amt = $Tarif_rate*$exchangeRate;
				
				$vat = 0;
				$vat = ($amt*$vatperc)/100;
								
				$fcy_time_in = $rslt[$i]['FCY_TIME_IN'];
				$destination = $rslt[$i]['DESTINATION'];
				$mlo = $rslt[$i]['MLO'];
				
				$mlo_name = $rslt[$i]['MLO_NAME'];
				$agent_code = $rslt[$i]['AGENT_CODE'];
				$agent = $rslt[$i]['AGENT'];
				
				$vsl_name = $rslt[$i]['VSL_NAME'];
				$rotation = $rslt[$i]['ROTATION'];
				$berth = $rslt[$i]['BERTH'];
				
				$argo_visist_dtls_eta = $rslt[$i]['ARGO_VISIST_DTLS_ETA'];
				$argo_visist_dtls_etd = $rslt[$i]['ARGO_VISIST_DTLS_ETD'];
				$id = $rslt[$i]['ID'];
				$size = $rslt[$i]['CONT_SIZE'];
				$height = $rslt[$i]['HEIGHT'];
				$freight_kind = $rslt[$i]['FREIGHT_KIND'];
				
				$wpn = $rslt[$i]['WPN'];
				//$Tarif_rate = $rslt[$i]['TARIF_RATE'];
				
				$isoGroup = $rslt[$i]['ISO_GRP'];
				
				$tues = 0;				
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
			
				$mlo_name=str_replace("'","\\\\'",$mlo_name);
				$agent=str_replace("'","\\\\'",$agent);

				$queryForDraftId = "SELECT DISTINCT draft_id FROM ".$this->get_table_name("mis_billing")." WHERE imp_rot = '".$rotation."' AND mlo_code = '".$mlo."' AND bill_type = '108'";
				$rslt_queryForDraftId=$this->dataSelectCtmsMis($queryForDraftId);
				$draftNo = $rslt_queryForDraftId[0]['draft_id'];
								
				if($fcy_time_in == "")
					$fcy_time_in = "NULL";
				else 
				{
					$fcy_time_in = "'".$fcy_time_in."'";
				}
				
				
				$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")." (gkey,draftNumber,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,fcy_time_in) VALUES('".$gkey."','".$draftNo."','".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','108','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."',".$fcy_time_in.")";

				$this->dataInsertCtmsmis($queryForInsert); //to check PCT Cont Discharge
				
			//}			
		}
		
		
		
		$strDraftId = "SELECT draft_id FROM ".$this->get_table_name("mis_billing")." WHERE imp_rot='".$rot."' AND bill_type=108";
		
		$rsDraftId=$this->dataSelectCtmsMis($strDraftId);		//gets multiple rows
		
		for($i=0;$i<count($rsDraftId);$i++)
		{
			$DraftId = $rsDraftId[$i]['draft_id'];
		
			$strQuery = "SELECT SUM(amt) as totAmt,SUM(vat) AS totVat FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber=".$DraftId;
			$rsAmt = $this->dataSelectCtmsMis($strQuery);			
			
			for($j=0;$j<count($rsAmt);$j++)
			{
				$totAmt=$rsAmt[$j]['totAmt'];
				$totVat=$rsAmt[$j]['totVat'];
				
				$strUpdate = "UPDATE ".$this->get_table_name("mis_billing")." 
				SET totAmt='".$totAmt."',totVat='".$totVat."' 
				WHERE draft_id=".$DraftId;
				
				$this->dataUpdateCtmsmis($strUpdate); //to check PCT Cont Discharge
			}
		}
	}

	function Insert_Pangoan_Load_Details_Data($rot,$bill_type,$mlo_code)
    {
		
		
        $extraCond = "";

		if($mlo_code!="")
			$extraCond = " AND r.id = '".$mlo_code."' ";
		
		//iso_grp
        $query="SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
			to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			fcy.flex_string10,'' AS destination,r.id AS mlo,r.name AS mlo_name,
			NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			(SELECT qua.id FROM vsl_vessel_berthings brt INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			inv.freight_kind,inv.category,'15.00' AS vatperc,
			NVL(fcy.flex_string06,'W') AS wpn,
			(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 fetch first 1 rows only) AS cl_date,
			null AS diff,'1' AS flag
			FROM inv_unit inv  
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
			INNER JOIN ( ref_bizunit_scoped r        
			LEFT JOIN ( ref_agent_representation X        
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey ) ON r.gkey=X.bzu_gkey ) ON r.gkey = inv.line_op  
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey  
			WHERE dtl.ib_vyg='$rot' ".$extraCond."

			UNION ALL

			SELECT * FROM (
			SELECT gkey,fcy_time_in,fcy_time_out,flex_string10,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,
				argo_visist_dtls_etd,id,cont_size,height,iso_grp,freight_kind,category,vatperc,'' AS wpn,cl_date,diff,flag FROM (
					SELECT inv.gkey AS gkey,
					to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
					to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
					fcy.flex_string10,
					(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
					FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,inv.freight_kind,inv.category,'' AS dray_status,'' AS origin,
					to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
					vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
					to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
					to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,
					inv.id AS id,
					(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
					((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
					(SELECT ref_equip_type.iso_group from ref_equip_type
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
					arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
					(SELECT qua.id FROM vsl_vessel_berthings brt INNER JOIN argo_quay qua ON qua.gkey=brt.quay   
					WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc, 
					
					to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
					SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
					)+1 diff,

					
					'2' AS flag
					FROM inv_unit inv   
					INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey   
					INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv   
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
							
					INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey   
					INNER JOIN  ( ref_bizunit_scoped r         
					LEFT JOIN ( ref_agent_representation X         
					LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey     )  ON r.gkey = inv.line_op   
					INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey  
					WHERE dtl.ib_vyg='$rot' AND inv.freight_kind!='MTY' ".$extraCond."
				) WHERE diff >0
			) tbl


			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,flex_string10,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
			billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,iso_grp,freight_kind,category,vatperc,'' AS wpn,cl_date,diff,'3' AS flag FROM (
					SELECT inv.gkey AS gkey,
					to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
					fcy.flex_string10,
					(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
					FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
					to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
					SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
					)+1 diff,inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
					r.id AS mlo,r.name AS mlo_name,
					NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
					to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
					to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,
					inv.id AS id,
					(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
					((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
					(SELECT ref_equip_type.iso_group from ref_equip_type
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
					inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
					(SELECT qua.id FROM vsl_vessel_berthings brt  INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
					WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,
					'15.00' AS vatperc
					FROM inv_unit inv  
					INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
					INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 
					
					INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
					INNER JOIN  ( ref_bizunit_scoped r        
					LEFT JOIN ( ref_agent_representation X        
					LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey     )  ON r.gkey = inv.line_op  
					INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey  
					WHERE dtl.ib_vyg='$rot' AND inv.freight_kind!='MTY' ".$extraCond."
			) tbl WHERE diff >7

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,flex_string10,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
				billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,iso_grp,freight_kind,category,vatperc,'' AS wpn,cl_date,
				diff,FLAG FROM (
					SELECT inv.gkey AS gkey,
					to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
					fcy.flex_string10,    
					(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
					FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
					to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
					SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
					)+1 diff,inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
					r.id AS mlo,r.name AS mlo_name,
					NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
					to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
					to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,
					inv.id AS id,
					(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
					((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
					(SELECT ref_equip_type.iso_group from ref_equip_type
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
					inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
					(SELECT qua.id FROM vsl_vessel_berthings brt  INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
					WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
					'4' AS flag
					FROM inv_unit inv  
					INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
					INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
					
					INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
					INNER JOIN  ( ref_bizunit_scoped r        
					LEFT JOIN ( ref_agent_representation X         
					LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey    )  ON r.gkey = inv.line_op  
					INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey   
					WHERE dtl.ib_vyg='$rot' AND inv.freight_kind!='MTY' ".$extraCond."
			) tbl WHERE diff >20

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,flex_string10,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
				billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,iso_grp,freight_kind,category,vatperc,'' AS wpn,
				cl_date,null AS diff,'5' AS flag FROM (
					SELECT inv.gkey AS gkey,
					to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
					fcy.flex_string10,
					(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
					FROM vsl_vessel_visit_details
					INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
					WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
					inv.category,
					'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
					r.id AS mlo,r.name AS mlo_name,
					NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
					to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
					to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,
					inv.id AS id,
					(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
					((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
					(SELECT ref_equip_type.iso_group from ref_equip_type
					INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
					INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
					inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd, 
					(SELECT qua.id FROM vsl_vessel_berthings brt   INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
					WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
					'5' AS flag 
					FROM inv_unit inv   
					INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey   
					INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv   
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey     
					INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey   
					INNER JOIN  ( ref_bizunit_scoped r         
					LEFT JOIN ( ref_agent_representation X         
					LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey     )  ON r.gkey = inv.line_op   
					INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey   
					WHERE dtl.ib_vyg='$rot' AND inv.freight_kind='MTY' ".$extraCond.")";
		
		$rslt=$this->dataSelectOracle($query);
				
		for($i=0;$i<count($rslt);$i++)
		{		
			$gkey = $rslt[$i]['GKEY'];
			$fcy_time_in = $rslt[$i]['FCY_TIME_IN'];
			$fcy_time_out = $rslt[$i]['FCY_TIME_OUT'];			
			$flex_string10 = $rslt[$i]['FLEX_STRING10'];			
			$destination = $rslt[$i]['DESTINATION'];
			$mlo = $rslt[$i]['MLO'];			
			$mlo_name = $rslt[$i]['MLO_NAME'];
			$agent_code = $rslt[$i]['AGENT_CODE'];   
            $agent = $rslt[$i]['AGENT'];
			$vsl_name = $rslt[$i]['VSL_NAME'];
            $rotation = $rslt[$i]['ROTATION'];
			$berth = $rslt[$i]['BERTH'];
			$billingDate = $rslt[$i]['BILLINGDATE'];
            $argo_visist_dtls_eta = $rslt[$i]['ARGO_VISIST_DTLS_ETA'];
            $argo_visist_dtls_etd = $rslt[$i]['ARGO_VISIST_DTLS_ETD'];
			$id = $rslt[$i]['ID'];
            $size = $rslt[$i]['CONT_SIZE'];
			$height = $rslt[$i]['HEIGHT'];
			$isoGroup = $rslt[$i]['ISO_GRP'];
			$freight_kind = $rslt[$i]['FREIGHT_KIND'];
			$category = $rslt[$i]['CATEGORY'];
			$vatperc = $rslt[$i]['VATPERC'];
			$wpn = $rslt[$i]['WPN'];
			$cl_date = $rslt[$i]['CL_DATE'];
			$diff = $rslt[$i]['DIFF'];
			$flag = $rslt[$i]['FLAG'];
						
			$tues = 0;
			$from_depo = "";
			$from_port = "";
			$invoice_type = "112"; // 112 is for PCT Cont Load
			
			$tarrif_id_data = "";
			
			
			if($freight_kind=="MTY" and $category=="EXPRT"){
				$from_depo = $fcy_time_in;
			} else {
				$from_depo = NULL;
			}
			
			if($freight_kind=="MTY" and $category=="STRGE"){
				$from_port = $fcy_time_in;
			} else {
				$from_port = NULL;
			}
			
			$mlo_name=str_replace("'","\\\\'",$mlo_name);
			$agent=str_replace("'","\\\\'",$agent);
			
			$queryForDraftId = "SELECT DISTINCT draft_id 
			FROM ".$this->get_table_name("mis_billing")." 
			WHERE imp_rot = '".$rotation."' AND mlo_code = '".$mlo."' AND bill_type = '".$invoice_type."'";
			$rslt_queryForDraftId=$this->dataSelectCtmsMis($queryForDraftId);	
			
			$draftNo = $rslt_queryForDraftId[0]['draft_id'];
			
			$billing_dt = date("Y-m-d", strtotime($billingDate));
			
			if($flag==1)
			{
				//calculating size starts...				
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
				//calculating size ends...
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id NOT LIKE '%storage%' 
								AND tarrif_id NOT LIKE 'EXTRA%' AND tarrif_id NOT LIKE 'STATUS%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$description = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey,description
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
						$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					}
					
					
					
					$queryBillingCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey' 
												ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_billing_currency_gkey=$this->dataSelectBilling($queryBillingCurrencyGkey);
					
					$currency_gkey = 0;
					for($cgkey=0;$cgkey<count($rslt_billing_currency_gkey);$cgkey++){
						$currency_gkey = $rslt_billing_currency_gkey[$currency_gkey]['CURRENCY_GKEY'];
					}
					
					
										
					//calculating Tarif Rate Starts...
					$tarrif_rate = "";
					
					
					
					if($freight_kind=="MTY")
					{						
						$queryAmt = "";
						if($currency_gkey != 370){
							$queryAmt = "SELECT amount*75/100 AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						} else {
							$queryAmt = "SELECT amount AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						}
						$rslt_amt=$this->dataSelectBilling($queryAmt);
						for($amt=0;$amt<count($rslt_amt);$amt++){
							$tarrif_rate = $rslt_amt[$amt]['AMOUNT'];
						}
					}
					else
					{						
						$queryAmt = "";
						if($currency_gkey != 370){
							$queryAmt = "SELECT amount*75/100 AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						} else {
							$queryAmt = "SELECT amount AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						}
						
						$rslt_amt=$this->dataSelectBilling($queryAmt);
						for($amt=0;$amt<count($rslt_amt);$amt++){
							$tarrif_rate = $rslt_amt[$amt]['AMOUNT'];
						}
					}
					
					//calculating Tarif Rate Ends...
					
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					//calculating Exchange Rate Ends...
					
					$amt = $tarrif_rate*$exchangeRate;					
					$vat = ($amt*$vatperc)/100;
					
					
					$imp_rot = "";
					$imp_ata = "";
					if($freight_kind=="MTY")
					{
						$queryRot = "SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit  
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT' ORDER BY inv_unit.gkey FETCH FIRST 1 ROWS ONLY";
						$rsltRot=$this->dataSelectOracle($queryRot);		
						for($rot=0;$rot<count($rsltRot);$rot++)
						{
							$imp_rot = $rsltRot[$rot]['FLEX_STRING10'];
						}
						
						$queryAta = "SELECT  to_char(ata,'yyyy-mm-dd') AS ata FROM argo_carrier_visit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg=(SELECT inv_unit_fcy_visit.flex_string10 
							FROM inv_unit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
							WHERE id='$id' AND category='IMPRT' 
							ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
						
					}
					else
					{
						$imp_rot = $flex_string10;
						
						$queryAta = "SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
							FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg='$flex_string10' 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,cl_date,depo_date,port_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."','".$fcy_time_in."','".$fcy_time_out."','".$cl_date."',DATE('".$from_depo."'),DATE('".$from_port."'),'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."','".$imp_rot."','".$imp_ata."')";
					
					$this->dataInsertCtmsmis($queryForInsert); // to chk PCT Cont Load
					
					// echo "INV UNIT GKEY : ".$gkey." TARRIF ID : ".$tarrif_id_data.
						// " BILL TARRIF GKEY : ".$bil_tariffs_gkey." Currency Gkey : ".$currency_gkey." Tarif Rate : ".$tarrif_rate."<br>";
					
				}
			}
			
			else if($flag==2)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=1 AND bltire.min_quantity <8)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
										
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
								WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}					
					//calculating Exchange Rate Ends...
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if($diff > 7)
					{
						$amt = $temp_amt*7;
						$vat = $temp_vat*7;
					}
					else
					{
						$amt = $temp_amt*$diff;
						$vat = $temp_vat*$diff;
					}
					
					$imp_rot = "";
					$imp_ata = "";
					if($freight_kind=="MTY")
					{
						$queryRot = "SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit  
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT' ORDER BY inv_unit.gkey FETCH FIRST 1 ROWS ONLY";
						$rsltRot=$this->dataSelectOracle($queryRot);		
						for($rot=0;$rot<count($rsltRot);$rot++)
						{
							$imp_rot = $rsltRot[$rot]['FLEX_STRING10'];
						}
						
						$queryAta = "SELECT  to_char(ata,'yyyy-mm-dd') AS ata FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg=(SELECT inv_unit_fcy_visit.flex_string10 
							FROM inv_unit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
							WHERE id='$id' AND category='IMPRT' 
							ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
						
					}
					else
					{
						$imp_rot = $flex_string10;
						
						$queryAta = "SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
							FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg='$flex_string10' 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,cl_date,depo_date,port_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."','".$fcy_time_in."','".$fcy_time_out."','".$cl_date."',DATE('".$from_depo."'),DATE('".$from_port."'),'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."','".$imp_rot."','".$imp_ata."')";
					
					$this->dataInsertCtmsmis($queryForInsert); // to chk PCT Cont Load
					
				}
			}
			else if($flag==3)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=8 AND bltire.min_quantity <21)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}					
					//calculating Exchange Rate Ends...
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if(($diff-7) > 13)
					{
						$amt = $temp_amt*13;
						$vat = $temp_vat*13;
					}
					else
					{
						$amt = $temp_amt*($diff-7);
						$vat = $temp_vat*($diff-7);
					}
					
					$imp_rot = "";
					$imp_ata = "";
					if($freight_kind=="MTY")
					{
						$queryRot = "SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit  
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT' ORDER BY inv_unit.gkey FETCH FIRST 1 ROWS ONLY";
						$rsltRot=$this->dataSelectOracle($queryRot);		
						for($rot=0;$rot<count($rsltRot);$rot++)
						{
							$imp_rot = $rsltRot[$rot]['FLEX_STRING10'];
						}
						
						$queryAta = "SELECT  to_char(ata,'yyyy-mm-dd') AS ata FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg=(SELECT inv_unit_fcy_visit.flex_string10 
							FROM inv_unit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
							WHERE id='$id' AND category='IMPRT' 
							ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
						
					}
					else
					{
						$imp_rot = $flex_string10;
						
						$queryAta = "SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
							FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg='$flex_string10' 
							AND inv_unit_fcy_visit.unit_gkey='$gkey' 
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,cl_date,depo_date,port_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."','".$fcy_time_in."','".$fcy_time_out."','".$cl_date."',DATE('".$from_depo."'),DATE('".$from_port."'),'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."','".$imp_rot."','".$imp_ata."')";
					
					$this->dataInsertCtmsmis($queryForInsert); // to chk PCT Cont Load
					
				}
			}
			else if($flag==4)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND bltire.min_quantity >=21";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
								WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM billing.bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}					
					//calculating Exchange Rate Ends...
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					
					$amt = $temp_amt*($diff-20);
					$vat = $temp_vat*($diff-20);
					
					$imp_rot = "";
					$imp_ata = "";
					if($freight_kind=="MTY")
					{
						$queryRot = "SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit  
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT' ORDER BY inv_unit.gkey FETCH FIRST 1 ROWS ONLY";
						$rsltRot=$this->dataSelectOracle($queryRot);		
						for($rot=0;$rot<count($rsltRot);$rot++)
						{
							$imp_rot = $rsltRot[$rot]['FLEX_STRING10'];
						}
						
						$queryAta = "SELECT  to_char(ata,'yyyy-mm-dd') AS ata FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg=(SELECT inv_unit_fcy_visit.flex_string10 
							FROM inv_unit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
							WHERE id='$id' AND category='IMPRT' 
							ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
						
					}
					else
					{
						$imp_rot = $flex_string10;
						
						$queryAta = "SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
							FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg='$flex_string10' 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,cl_date,depo_date,port_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."','".$fcy_time_in."','".$fcy_time_out."','".$cl_date."',DATE('".$from_depo."'),DATE('".$from_port."'),'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."','".$imp_rot."','".$imp_ata."')";
					
					$this->dataInsertCtmsmis($queryForInsert); // to chk PCT Cont Load
				}
			}
			else if($flag==5)
			{
				$tues = 0;
				
				if($freight_kind=="MTY" and $category=="EXPRT")
				{
					$queryDiff = "SELECT DATEDIFF('$fcy_time_out', '$fcy_time_in')+1 AS diff";
					$rslt_diff=$this->dataSelectCtmsMis($queryDiff);
					for($rowDiff=0;$rowDiff<count($rslt_diff);$rowDiff++){
						$diff = $rslt_diff[$rowDiff]['diff'];
					}
				}
				else
				{
					$chk_freight_kind = "";
					$queryChkFreightKind = "SELECT inv_unit.freight_kind FROM inv_unit    
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT'  
						AND to_char(inv_unit_fcy_visit.time_in,'yyyy-mm-dd HH24:MI:SS') < '$fcy_time_in'  
						ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY";
					$rsltChkFreightKind = $this->dataSelectOracle($queryChkFreightKind);		
					for($row_cfk=0;$row_cfk<count($rsltChkFreightKind);$row_cfk++)
					{
						$chk_freight_kind = $rsltChkFreightKind[$row_cfk]['FREIGHT_KIND'];
					}
					
					if($chk_freight_kind=="LCL")
					{
						$queryDiff = "SELECT DATEDIFF('$fcy_time_out', '$fcy_time_in')+1 AS diff";
						$rslt_diff=$this->dataSelectCtmsMis($queryDiff);
						for($rowDiff=0;$rowDiff<count($rslt_diff);$rowDiff++){
							$diff = $rslt_diff[$rowDiff]['diff'];
						}
					}
					else
					{
						$queryDiff = "SELECT DATEDIFF('$fcy_time_out', '$fcy_time_in') AS diff";
						$rslt_diff=$this->dataSelectCtmsMis($queryDiff);
						for($rowDiff=0;$rowDiff<count($rslt_diff);$rowDiff++){
							$diff = $rslt_diff[$rowDiff]['diff'];
						}
					}
				}
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					if($diff <= "7")
					{
						
						$description = "";
						$Tarif_rate = "";
						$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
						FROM bil_tariff_rate_tiers bltire
						INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
						WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' 
						AND (bltire.min_quantity >=1 AND bltire.min_quantity <8)";
						$rslt_description=$this->dataSelectBilling($queryDescription);
						for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
							$description = $rslt_description[$rowDesc]['DESCRIPTION'];
							$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
						}
					
					}
					else
					{
						if($diff < "7" and $diff < "21")
						{
							$description = "";
							$Tarif_rate = "";
							$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
							FROM bil_tariff_rate_tiers bltire
							INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
							WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' 
							AND (bltire.min_quantity >=8 AND bltire.min_quantity <21)";
							$rslt_description=$this->dataSelectBilling($queryDescription);
							for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
								$description = $rslt_description[$rowDesc]['DESCRIPTION'];
								$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
							}
						}
						else
						{
							$description = "";
							$Tarif_rate = "";
							$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
							FROM bil_tariff_rate_tiers bltire
							INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
							WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' 
							AND bltire.min_quantity >20";
							$rslt_description=$this->dataSelectBilling($queryDescription);
							for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
								$description = $rslt_description[$rowDesc]['DESCRIPTION'];
								$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
							}
						}
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}					
					//calculating Exchange Rate Ends...
					$amt = $Tarif_rate*$exchangeRate*$diff;
					$vat = ($amt*$vatperc)/100;
					
					$imp_rot = "";
					$imp_ata = "";
					if($freight_kind=="MTY")
					{
						$queryRot = "SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit  
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT' ORDER BY inv_unit.gkey FETCH FIRST 1 ROWS ONLY";
						$rsltRot=$this->dataSelectOracle($queryRot);		
						for($rot=0;$rot<count($rsltRot);$rot++)
						{
							$imp_rot = $rsltRot[$rot]['FLEX_STRING10'];
						}
						
						$queryAta = "SELECT  to_char(ata,'yyyy-mm-dd') AS ata FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg=(SELECT inv_unit_fcy_visit.flex_string10 
							FROM inv_unit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
							WHERE id='$id' AND category='IMPRT' 
							ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
						
					}
					else
					{
						$imp_rot = $flex_string10;
						
						$queryAta = "SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
							FROM argo_carrier_visit  
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
							WHERE vsl_vessel_visit_details.ib_vyg='$flex_string10' 
							AND inv_unit_fcy_visit.unit_gkey='$gkey'
							AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY";
						$rsltAta=$this->dataSelectOracle($queryAta);		
						for($ata_data=0;$ata_data<count($rsltAta);$ata_data++)
						{
							$imp_ata = $rsltAta[$ata_data]['ATA'];
						}
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,cl_date,depo_date,port_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."','".$fcy_time_in."','".$fcy_time_out."','".$cl_date."',DATE('".$from_depo."'),DATE('".$from_port."'),'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."','".$vat."','".$isoGroup."','".$tues."','".$imp_rot."','".$imp_ata."')";
					
					$this->dataInsertCtmsmis($queryForInsert); // to chk PCT Cont Load
					
				}
			}
			
			
		}
		
		$strDraftId = "SELECT draft_id FROM ".$this->get_table_name("mis_billing")." WHERE imp_rot='".$rot."' AND bill_type=112";
		$rsDraftId=$this->dataSelectCtmsMis($strDraftId);
		
		
		
		for($i=0;$i<count($rsDraftId);$i++)
		{
			$totAmt="";
			$totVat="";
			
			$DraftId = $rsDraftId[$i]['draft_id'];
		
			$strQuery = "SELECT SUM(amt) AS totAmt,sum(vat) AS totVat FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber=".$DraftId;
			
			$rsAmt = $this->bm->dataSelectDb2($strQuery);
			
			for($j=0;$j<count($rsAmt);$j++)
			{
				$totAmt=$rsAmt[$j]['totAmt'];
				$totVat=$rsAmt[$j]['totVat'];
				
				$strUpdate="UPDATE ".$this->get_table_name("mis_billing")." 
				SET totAmt='".$totAmt."',totVat='".$totVat."' 
				WHERE draft_id=".$DraftId;
				
				$this->dataUpdateCtmsmis($strUpdate);
			}
		}
    }

	function Insert_Pangoan_StatusChange_Details_Data($rot,$bill_type,$mlo_code,$container_id)		//CPA to PCT
	{
		$extraCond = "";

		$tmp_container_id=$container_id;
		

		$containerArray=explode(",",$container_id);
		$cont="'";
		foreach($containerArray as $name)
		{
			$cont=$cont.$name."','";
		}		
		$container_id=substr($cont,0,-2);		


		$containerArray_2=explode(",",$tmp_container_id);
		sort($containerArray_2);
		$cont_2="'";
		foreach($containerArray_2 as $name_2)
		{
			$cont_2=$cont_2.$name_2."','";
		}		
		$cont_2=substr($cont_2,0,-2);
		$cont_2=str_replace("'","",$cont_2);
		$cont_for_draft_id="'".$cont_2."'";

		
		if($mlo_code!="") 
		{
            $extraCond = " AND r.id = '".$mlo_code."'";
        }
		
		$query="SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
		(SELECT to_char(srv_event.created,'yyyy-mm-dd HH24:MI:SS') 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND 
		srv_event_field_changes.metafield_id='gdsDestination'
		AND prior_value='2591' AND new_value='5235'
		) AS fcy_time_out,
		(SELECT ref_equip_type.iso_group from ref_equip_type
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
		'' AS destination,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
		vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
		(SELECT qua.id 
		FROM vsl_vessel_berthings brt 
		INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
		WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
		to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
		'0' AS TUES,
		((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
		inv.freight_kind,'15.00' AS vatperc,NVL(fcy.flex_string06,'W') AS wpn,
		(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
		FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
		fcy.flex_string10 AS imp_rot,
		(SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata FROM argo_carrier_visit 
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
		WHERE vsl_vessel_visit_details.ib_vyg=fcy.flex_string10 AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY) AS imp_ata,
		null AS diff,'1' as FLAG
		FROM inv_unit inv 
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv 
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
		INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
		INNER JOIN  ( ref_bizunit_scoped r       
		LEFT JOIN ( ref_agent_representation X       
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey   )  ON r.gkey = inv.line_op 
		INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
		WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='2023/PUNN58N'".$extraCond."

		UNION ALL

		SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,
		vsl_name,rotation,berth,  billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size, '0' AS tues,height,
		freight_kind,vatperc,'' AS wpn,cl_date,imp_rot,imp_ata,diff,FLAG
		FROM 
		( SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
		(SELECT to_char(srv_event.created,'yyyy-mm-dd HH24:MI:SS') 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND 
		srv_event_field_changes.metafield_id='gdsDestination' AND prior_value='2591' AND new_value=5235) AS fcy_time_out,
		(SELECT ref_equip_type.iso_group from ref_equip_type
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
		(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
		FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
		((SELECT to_date(to_char(srv_event.created, 'yyyy-mm-dd'), 'yyyy-mm-dd') FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' 
		AND srv_event_field_changes.metafield_id='gdsDestination' AND prior_value='2591' AND new_value='5235') -
		(SELECT time_discharge_complete+4 FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY))+1 AS diff,
		inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
		r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
		vsl.name AS vsl_name, dtl.ib_vyg AS rotation,
		to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
		((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
		inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
		(SELECT qua.id 
		FROM vsl_vessel_berthings brt  
		INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
		WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth, 
		'15.00' AS vatperc,fcy.flex_string10 AS imp_rot,
		(SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
		FROM argo_carrier_visit  
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
		WHERE vsl_vessel_visit_details.ib_vyg=fcy.flex_string10 AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY) AS imp_ata,
		'2' AS FLAG 
		FROM inv_unit inv  
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
		INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
		INNER JOIN (ref_bizunit_scoped r        
		LEFT JOIN (ref_agent_representation X        
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op  
		INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
		WHERE inv.id IN (".$container_id.")  AND dtl.ib_vyg='2023/PUNN58N'".$extraCond.") tbl WHERE diff>0

		UNION ALL

		SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,  
		billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,'0' AS tues,height,freight_kind,vatperc,'' AS wpn,
		cl_date,imp_rot,imp_ata,diff,FLAG
		FROM
		(SELECT inv.gkey AS gkey,
		(SELECT ref_equip_type.iso_group from ref_equip_type
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
		to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
		(SELECT to_char(srv_event.created,'yyyy-mm-dd HH24:MI:SS') 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND 
		srv_event_field_changes.metafield_id='gdsDestination' 
		AND prior_value='2591' AND new_value='5235') AS fcy_time_out,
		(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
		FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,

		((SELECT to_date(to_char(srv_event.created, 'yyyy-mm-dd'), 'yyyy-mm-dd') FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' 
		AND srv_event_field_changes.metafield_id='gdsDestination' AND prior_value='2591' AND new_value='5235') -
		(SELECT time_discharge_complete+4 FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY))+1 AS diff,
		inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,r.id AS mlo,
		r.name AS mlo_name,
		NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
		to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
		((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
		inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,

		(SELECT qua.id 
		FROM vsl_vessel_berthings brt  
		INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
		WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,fcy.flex_string10 AS imp_rot,
		(SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
		FROM argo_carrier_visit  
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
		WHERE vsl_vessel_visit_details.ib_vyg=fcy.flex_string10 AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY) AS imp_ata,
		'3' AS FLAG
		FROM inv_unit inv  
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey   
		INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
		INNER JOIN (ref_bizunit_scoped r        
		LEFT JOIN (ref_agent_representation X        
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op  
		INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey  
		WHERE inv.id IN (".$container_id.")  AND dtl.ib_vyg='2023/PUNN58N'".$extraCond." 
		) tbl WHERE diff>7

		UNION ALL

		SELECT gkey,fcy_time_in,fcy_time_out AS fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,
		vsl_name,rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,'0' AS tues,height,freight_kind,vatperc,
		'' AS wpn,cl_date,imp_rot,imp_ata,diff,FLAG
		FROM (SELECT inv.gkey AS gkey,  
		(SELECT ref_equip_type.iso_group from ref_equip_type
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
		to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
		(SELECT to_char(srv_event.created,'yyyy-mm-dd HH24:MI:SS') 
		FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND 
		srv_event_field_changes.metafield_id='gdsDestination' AND prior_value='2591' AND new_value='5235') AS fcy_time_out,
		(SELECT to_char(time_discharge_complete,'yyyy-mm-dd HH24:MI:SS') AS time_discharge_complete
		FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
		((SELECT to_date(to_char(srv_event.created, 'yyyy-mm-dd'), 'yyyy-mm-dd') FROM srv_event 
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' 
		AND srv_event_field_changes.metafield_id='gdsDestination' AND prior_value='2591' AND new_value='5235') -
		(SELECT time_discharge_complete+4 FROM vsl_vessel_visit_details
		INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY))+1 AS diff,inv.category,'' AS dray_status,'' AS origin,
		to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,r.id AS mlo,r.name AS mlo_name,
		NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
		to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
		to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
		(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
		((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
		INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
		INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
		INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
		inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
		(SELECT qua.id 
		FROM vsl_vessel_berthings brt  
		INNER JOIN argo_quay qua ON qua.gkey=brt.quay  
		WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,
		'15.00' AS vatperc,fcy.flex_string10 AS imp_rot,
		(SELECT to_char(ata,'yyyy-mm-dd HH24:MI:SS') AS ata 
		FROM argo_carrier_visit  
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey  
		WHERE vsl_vessel_visit_details.ib_vyg=fcy.flex_string10 AND PHASE!='80CANCELED' FETCH FIRST 1 ROWS ONLY) AS imp_ata,
		'4' AS FLAG 
		FROM inv_unit inv  
		INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey  
		INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv  
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey  
		  
		INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey  
		INNER JOIN (ref_bizunit_scoped r 
		LEFT JOIN (ref_agent_representation X        
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op  
		INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey  
		WHERE inv.id IN (".$container_id.")  
		AND dtl.ib_vyg='2023/PUNN58N'".$extraCond." ) tbl WHERE diff>20";
	
		$rslt=$this->dataSelectOracle($query);
		
		for($i=0;$i<count($rslt);$i++)
		{
			$gkey = $rslt[$i]['GKEY'];
			$fcy_time_in = $rslt[$i]['FCY_TIME_IN'];
			$fcy_time_out = $rslt[$i]['FCY_TIME_OUT'];			
			$iso_grp = $rslt[$i]['ISO_GRP'];			
			$destination = $rslt[$i]['DESTINATION'];
			$mlo = $rslt[$i]['MLO'];			
			$mlo_name = $rslt[$i]['MLO_NAME'];
			$agent_code = $rslt[$i]['AGENT_CODE'];   
            $agent = $rslt[$i]['AGENT'];
			$vsl_name = $rslt[$i]['VSL_NAME'];
            $rotation = $rslt[$i]['ROTATION'];
			$berth = $rslt[$i]['BERTH'];
			$billingDate = $rslt[$i]['BILLINGDATE'];
            $argo_visist_dtls_eta = $rslt[$i]['ARGO_VISIST_DTLS_ETA'];
            $argo_visist_dtls_etd = $rslt[$i]['ARGO_VISIST_DTLS_ETD'];
			$id = $rslt[$i]['ID'];
            $size = $rslt[$i]['CONT_SIZE'];
			$height = $rslt[$i]['HEIGHT'];
			$freight_kind = $rslt[$i]['FREIGHT_KIND'];
			$vatperc = $rslt[$i]['VATPERC'];
			$wpn = $rslt[$i]['WPN'];
			$cl_date = $rslt[$i]['CL_DATE'];
			$imp_rot = $rslt[$i]['IMP_ROT'];
			$imp_ata = $rslt[$i]['IMP_ATA'];
			$diff = $rslt[$i]['DIFF'];
			$flag = $rslt[$i]['FLAG'];
			
			$billing_dt = date("Y-m-d", strtotime($billingDate));
			
			$mlo_name=str_replace("'","\\\\'",$mlo_name);
			$agent=str_replace("'","\\\\'",$agent);
			
			$tues = 0;
			$Tarif_rate = 0;
			$exchangeRate = 0;
			$from_depo = "";
			$invoice_type = "116"; // 116 is for Status Change Invoice (CPA to PCT)
			
			if($freight_kind=="MTY"){
				$from_depo = $fcy_time_in;
			} else {
				$from_depo = NULL;
			}

			$queryForDraftId = "SELECT DISTINCT draft_id FROM ".$this->get_table_name("mis_billing")." 
				WHERE imp_rot = '".$rotation."' AND mlo_code = '".$mlo."' AND bill_type = '".$invoice_type."' 
				AND containers=".$cont_for_draft_id;
			$rslt_queryForDraftId=$this->dataSelectCtmsMis($queryForDraftId);
			$draftNo="";
			$draftNo = $rslt_queryForDraftId[0]['draft_id'];
			
			if($flag=="1")
			{
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
							WHERE gkey='$gkey' AND invoice_type='116' AND tarrif_id NOT LIKE '%storage%' 
							AND (tarrif_id LIKE 'EXTRA%' OR tarrif_id LIKE 'STATUS%' OR tarrif_id LIKE 'LOAD%')";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$description = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey,description
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
						$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					}
					
					$queryBillingCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey' 
												ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_billing_currency_gkey=$this->dataSelectBilling($queryBillingCurrencyGkey);
					
					$currency_gkey = 0;
					for($cgkey=0;$cgkey<count($rslt_billing_currency_gkey);$cgkey++){
						$currency_gkey = $rslt_billing_currency_gkey[$currency_gkey]['CURRENCY_GKEY'];
					}
					
					$queryAmt = "";
					if($currency_gkey != 370)
					{
						if(str_contains($tarrif_id_data, 'LOAD'))
						{
							$queryAmt = "SELECT amount*45/100 AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						} 
						else 
						{
							$queryAmt = "SELECT amount AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						}
						
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
					} 
					else 
					{
						if(str_contains($tarrif_id_data, 'LOAD'))
						{
							$queryAmt = "SELECT amount*45/100 AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						} 
						else 
						{
							$queryAmt = "SELECT amount AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
										ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
						}
						
						
						
						$exchangeRate = 1;
					}
					$rslt_amt=$this->dataSelectBilling($queryAmt);
					for($amt=0;$amt<count($rslt_amt);$amt++){
						$Tarif_rate = $rslt_amt[$amt]['AMOUNT'];
					}
					
					$amt = $tarrif_rate*$exchangeRate;					
					$vat = ($amt*$vatperc)/100;
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,
										fcy_time_out,cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,
										rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,
										vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,
										iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";

					$this->dataInsertCtmsmis($queryForInsert); // to chk Status Change Invoice (CPA to PCT)
					
				}
			}
			else if($flag==2)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='116' AND intrf.tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$queryBillingCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey' 
												ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_billing_currency_gkey=$this->dataSelectBilling($queryBillingCurrencyGkey);
					
					$currency_gkey = 0;
					for($cgkey=0;$cgkey<count($rslt_billing_currency_gkey);$cgkey++){
						$currency_gkey = $rslt_billing_currency_gkey[$currency_gkey]['CURRENCY_GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=1 AND bltire.min_quantity <8)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if($diff > 7)
					{
						$amt = $temp_amt*7;
						$vat = $temp_vat*7;
					}
					else
					{
						$amt = $temp_amt*$diff;
						$vat = $temp_vat*$diff;
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,
										fcy_time_out,cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,
										rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,
										vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,
										iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";

					$this->dataInsertCtmsmis($queryForInsert); // to chk Status Change Invoice (CPA to PCT)
					
				}
			}
			else if($flag==3)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='116' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=8 AND bltire.min_quantity <21)";
					$rslt_description=$this->dataSelectCtmsMis($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if(($diff-7) > 13)
					{
						$amt = $temp_amt*13;
						$vat = $temp_vat*13;
					}
					else
					{
						$amt = $temp_amt*($diff-7);
						$vat = $temp_vat*($diff-7);
					}
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,
										fcy_time_out,cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,
										rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,
										vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,
										iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";

					$this->dataInsertCtmsmis($queryForInsert); // to chk Status Change Invoice (CPA to PCT)
				}
			}
			else if($flag==4)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='116' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND bltire.min_quantity >=21";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					
					$amt = $temp_amt*($diff-20);
					$vat = $temp_vat*($diff-20);
					
					$queryForInsert = "INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,
										fcy_time_out,cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,
										rotation,berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,
										vatperc,wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,
										iso_grp,tues,pre_imp_rot,pre_imp_ata) 
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";

					$this->dataInsertCtmsmis($queryForInsert); // to chk Status Change Invoice (CPA to PCT)
				}
			}
			
		}	//for loop ends with insertion
				
		$strDraftId = "SELECT draft_id FROM ".$this->get_table_name("mis_billing")." WHERE imp_rot='".$rot."' AND bill_type=116";		
		$rsDraftId=$this->dataSelectCtmsMis($strDraftId);
		
		for($i=0;$i<count($rsDraftId);$i++)
		{
			$totAmt="";
			$totVat="";
			
			$DraftId = $rsDraftId[$i]['draft_id'];
			
			$strQuery = "SELECT SUM(amt) AS totAmt,SUM(vat) AS totVat 
						FROM ".$this->get_table_name("mis_billing_details")." WHERE draftNumber='".$DraftId."'";			
			$rsAmt = $this->dataSelectCtmsMis($strQuery);
			
			for($j=0;$j<count($rsAmt);$j++)
			{

				$totAmt=$rsAmt[$j]['totAmt'];
				$totVat=$rsAmt[$j]['totVat'];
				
				$strUpdate = "UPDATE ".$this->get_table_name("mis_billing")." 
				SET totAmt='".$totAmt."',totVat='".$totVat."' 
				WHERE draft_id='".$DraftId."'";
				
				$this->dataUpdateCtmsmis($strUpdate);
			}
		}
	}

	function Insert_Pangoan_StatusChange_PCTToCPA_Details_Data($rot,$bill_type,$mlo_code,$container_id)
	{
		$extraCond = "";
		
		//---------
		$tmp_container_id=$container_id;
		
		//---
		$containerArray=explode(",",$container_id);
		$cont="'";
		foreach($containerArray as $name)
		{
			$cont=$cont.$name."','";
		}		
		$container_id=substr($cont,0,-2);
		
		// commented on 2018-08-28
		// $containerArray_2=explode(",",$tmp_container_id);
		// $cont_2="'";
		// foreach($containerArray_2 as $name_2)
		// {
			// $cont_2=$cont_2.$name_2.",";
		// }		
	
		// $cont_2=substr($cont_2,0,-1);
		// $cont_for_draft_id=$cont_2."'";
		
		//2019-08-28
		$containerArray_2=explode(",",$tmp_container_id);
		sort($containerArray_2);
		$cont_2="'";
		foreach($containerArray_2 as $name_2)
		{
			$cont_2=$cont_2.$name_2."','";
		}		
		$cont_2=substr($cont_2,0,-2);
		$cont_2=str_replace("'","",$cont_2);
		$cont_for_draft_id="'".$cont_2."'";
		//2019-08-28
	
		//---
		
		//---------
		
		if($mlo_code!="") 
		{
            $extraCond = " AND r.id = '".$mlo_code."'";
        }
		
		$query="SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			'' AS destination,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			(SELECT qua.id 
			FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,'15.00' AS vatperc,NVL(fcy.flex_string06,'W') AS wpn,
			(SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591) AS cl_date,fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'1' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='".$rot."' ".$extraCond."

			UNION ALL

			SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			'' AS destination,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			(SELECT qua.id 
			FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,'15.00' AS vatperc,NVL(fcy.flex_string06,'W') AS wpn,
			(SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591) AS cl_date,fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'2' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey=inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='".$rot."' ".$extraCond."

			UNION ALL

			SELECT gkey,fcy_time_in, fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
			billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,freight_kind,vatperc,'' AS wpn,
			cl_date,imp_rot,imp_ata,FLAG
			FROM
			(SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			(SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591) AS cl_date,

			(to_date((SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591),'yyyy-mm-dd') -
			to_date(to_char(fcy.time_in,'yyyy-mm-dd'),'yyyy-mm-dd'))+1 as diff,inv.category,'' AS dray_status,'' AS origin,
			to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
			vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS atd,
			(SELECT qua.id 
			FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,
			'15.00' AS vatperc,fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,

			'3' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='".$rot."' ".$extraCond.") tbl WHERE diff>0

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth, 
			billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,freight_kind,vatperc,'' AS wpn, 
			cl_date,imp_rot,imp_ata,FLAG
			FROM
			(SELECT inv.gkey AS gkey,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591) AS cl_date,
			(to_date((SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591),'yyyy-mm-dd') -
			to_date(to_char(fcy.time_in,'yyyy-mm-dd'),'yyyy-mm-dd'))+1 as diff,
			inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
			vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS atd,
			(SELECT qua.id 
			FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
			fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'4' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='".$rot."' ".$extraCond.") tbl WHERE diff>7

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,
			berth, billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,height,freight_kind,vatperc,'' AS wpn, 
			cl_date,imp_rot,imp_ata,FLAG
			FROM
			(SELECT inv.gkey AS gkey,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591) AS cl_date,
			(to_date((SELECT srv_event.created 
			FROM srv_event
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
			WHERE srv_event.applied_to_gkey=inv.gkey AND srv_event.event_type_gkey='31426' AND srv_event_field_changes.metafield_id='gdsDestination' 
			AND prior_value=5235 AND new_value=2591),'yyyy-mm-dd') -
			to_date(to_char(fcy.time_in,'yyyy-mm-dd'),'yyyy-mm-dd'))+1 as diff,
			inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS atd,
			(SELECT qua.id 
			FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
			fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'5' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 
			 
			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN (ref_bizunit_scoped r
			LEFT JOIN (ref_agent_representation X
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey=inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id IN (".$container_id.") AND dtl.ib_vyg='".$rot."' ".$extraCond.") tbl WHERE diff>20";
		
		$rslt=$this->dataSelectOracle($query);
		//	echo "count : ".count($rslt);
		for($i=0;$i<count($rslt);$i++)
		{
			$gkey = $rslt[$i]['GKEY'];
			$fcy_time_in = $rslt[$i]['FCY_TIME_IN'];
			$fcy_time_out = $rslt[$i]['FCY_TIME_OUT'];			
			$iso_grp = $rslt[$i]['ISO_GRP'];			
			$destination = $rslt[$i]['DESTINATION'];
			$mlo = $rslt[$i]['MLO'];			
			$mlo_name = $rslt[$i]['MLO_NAME'];
			$agent_code = $rslt[$i]['AGENT_CODE'];   
            $agent = $rslt[$i]['AGENT'];
			$vsl_name = $rslt[$i]['VSL_NAME'];
            $rotation = $rslt[$i]['ROTATION'];
			$berth = $rslt[$i]['BERTH'];
			$billingDate = $rslt[$i]['BILLINGDATE'];
            $argo_visist_dtls_eta = $rslt[$i]['ARGO_VISIST_DTLS_ETA'];
            $argo_visist_dtls_etd = $rslt[$i]['ARGO_VISIST_DTLS_ETD'];
			$id = $rslt[$i]['ID'];
            $size = $rslt[$i]['CONT_SIZE'];
			$height = $rslt[$i]['HEIGHT'];
			$freight_kind = $rslt[$i]['FREIGHT_KIND'];
			$vatperc = $rslt[$i]['VATPERC'];
			$wpn = $rslt[$i]['WPN'];
			$cl_date = $rslt[$i]['CL_DATE'];
			$imp_rot = $rslt[$i]['IMP_ROT'];
			$imp_ata = $rslt[$i]['IMP_ATA'];
			$flag = $rslt[$i]['FLAG'];
			
			$billing_dt = date("Y-m-d", strtotime($billingDate));
						
			$tues = 0;
			$from_depo = "";
			$from_port = "";
			$invoice_type = "124"; // 124 is for Status Change Invoice (PCT to CPA)
			
			$tarrif_id_data = "";
			
			
			if($freight_kind=="MTY"){
				$from_depo = $fcy_time_in;
			} else {
				$from_depo = NULL;
			}
			
			$mlo_name=str_replace("'","\\\\'",$mlo_name);
			$agent=str_replace("'","\\\\'",$agent);
			
			$queryForDraftId = "SELECT DISTINCT draft_id FROM ".$this->get_table_name("mis_billing")." 
			WHERE imp_rot = '".$rotation."' AND mlo_code = '".$mlo."' AND bill_type = '".$invoice_type."' 
			AND containers=".$cont_for_draft_id;			
			$rslt_queryForDraftId=$this->dataSelectCtmsMis($queryForDraftId);
			$draftNo="";
			$draftNo = $rslt_queryForDraftId[0]['draft_id'];
			
			if($flag==1)
			{
				//calculating size starts...				
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
				//calculating size ends...
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='124' AND tarrif_id LIKE 'DISCHARGING%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$description = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey,description
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
						$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					}
					
					$queryBillingCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey' 
												ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_billing_currency_gkey=$this->dataSelectBilling($queryBillingCurrencyGkey);
					
					$currency_gkey = 0;
					for($cgkey=0;$cgkey<count($rslt_billing_currency_gkey);$cgkey++){
						$currency_gkey = $rslt_billing_currency_gkey[$currency_gkey]['CURRENCY_GKEY'];
					}
					
					$Tarrif_rate = "";					
					$queryAmt = "SELECT amount*70/100 AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
								ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					
					$rslt_amt=$this->dataSelectBilling($queryAmt);
					for($amt=0;$amt<count($rslt_amt);$amt++){
						$Tarrif_rate = $rslt_amt[$amt]['AMOUNT'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
								WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = $tarrif_rate*$exchangeRate;					
					$vat = ($amt*$vatperc)/100;
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,
										billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,
										Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
									VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",
										'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."',
										'".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."',
										'".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."',
										'".$wpn."','".$Tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."',
										'".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
					
				}
			}
			else if($flag==2)
			{
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type=124 AND 
								(tarrif_id LIKE 'EXTRA%' OR tarrif_id LIKE 'STATUS%' OR tarrif_id LIKE 'LOAD%')";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$description = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey,description
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
						$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					}
					
					$Tarrif_rate = "";
					$queryDescription = "SELECT amount FROM bil_tariff_rates AS Tarif_rate
										WHERE tariff_gkey='$bil_tariffs_gkey' ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_tarif_rate=$this->dataSelectBilling($queryDescription);
					for($rowTr=0;$rowTr<count($rslt_tarif_rate);$rowTr++){
						$Tarrif_rate = $rslt_tarif_rate[$rowTr]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
										
					//calculating Exchange Rate Starts...
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}					
					//calculating Exchange Rate Ends...
					$amt = $Tarrif_rate*$exchangeRate;					
					$vat = ($amt*$vatperc)/100;
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,
										billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,
										Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
									VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",
										'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."',
										'".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."',
										'".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."',
										'".$wpn."','".$Tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."',
										'".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
					
				}
			}
			else if($flag==3)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='112' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarrif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=8 AND bltire.min_quantity <21)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarrif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if($diff > 7)
					{
						$amt = $temp_amt*7;
						$vat = $temp_vat*7;
					}
					else
					{
						$amt = $temp_amt*$diff;
						$vat = $temp_vat*$diff;
					}
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,
										billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,
										Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
									VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",
										'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."',
										'".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."',
										'".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."',
										'".$wpn."','".$Tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."',
										'".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			else if($flag==4)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='124' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarrif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=8 AND bltire.min_quantity <21)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarrif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if(($diff-7) > 13)
					{
						$amt = $temp_amt*13;
						$vat = $temp_vat*13;
					}
					else
					{
						$amt = $temp_amt*($diff-7);
						$vat = $temp_vat*($diff-7);
					}
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,
										billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,
										Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
									VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",
										'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."',
										'".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."',
										'".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."',
										'".$wpn."','".$Tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."',
										'".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			else if($flag==5)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='124' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarrif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND bltire.min_quantity>=21";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarrif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					
					$amt = $temp_amt*($diff-20);
					$vat = $temp_vat*($diff-20);
					
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,berth,
										billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,wpn,
										Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
									VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",
										'".$destination."','".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."',
										'".$vsl_name."','".$rotation."','".$berth."','".$billingDate."','".$argo_visist_dtls_eta."',
										'".$argo_visist_dtls_etd."','".$id."','".$size."','".$height."','".$freight_kind."','".$vatperc."',
										'".$wpn."','".$Tarrif_rate."','".$exchangeRate."','".$description."','".$currency_gkey."','".$amt."',
										'".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			
			
		}	//for loop ends with insertion
		
		$strDraftId = "SELECT draft_id 
		FROM ".$this->get_table_name("mis_billing")." 
		WHERE imp_rot='".$rot."' AND bill_type=124";		//check if bill_type=116 or 124
		//	return;
		$rsDraftId=$this->dataSelectCtmsMis($strDraftId);
		
		for($i=0;$i<count($rsDraftId);$i++)
		{
			$totAmt="";
			$totVat="";
			
			$DraftId = $rsDraftId[$i]['draft_id'];
			
			$strQuery = "SELECT SUM(amt) AS totAmt,SUM(vat) AS totVat 
			FROM ".$this->get_table_name("mis_billing_details")."
			WHERE draftNumber='".$DraftId."'";
			
			$rsAmt = $this->dataSelectCtmsMis($strQuery);
			
			for($j=0;$j<count($rsAmt);$j++)
			{
				$totAmt=$rsAmt[$j]['totAmt'];
				$totVat=$rsAmt[$j]['totVat'];
				
				$strUpdate = "UPDATE ".$this->get_table_name("mis_billing")."
				SET totAmt='".$totAmt."',totVat='".$totVat."' 
				WHERE draft_id='".$DraftId."'";
				
				$this->dataUpdateCtmsmis($strUpdate);
			}
		}
	}
	function Insert_ICDToPangoan_StatusChange_Details_Data($rot,$bill_type,$mlo_code,$container_id)
	{
		$extraCond = "";
		
		//---------
		$tmp_container_id=$container_id;
		
		//---
		$containerArray=explode(",",$container_id);
		$cont="'";
		foreach($containerArray as $name)
		{
			$cont=$cont.$name."','";
		}		
		$container_id=substr($cont,0,-2);
		
		
		//2019-08-28
		$containerArray_2=explode(",",$tmp_container_id);
		sort($containerArray_2);
		$cont_2="'";
		foreach($containerArray_2 as $name_2)
		{
			$cont_2=$cont_2.$name_2."','";
		}		
		$cont_2=substr($cont_2,0,-2);
		$cont_2=str_replace("'","",$cont_2);
		$cont_for_draft_id="'".$cont_2."'";
		//2019-08-28
	
		//---
		
		//---------
		
		if($mlo_code!="") 
		{
            $extraCond = " AND r.id = '".$mlo_code."'";
        }
		
		$query="SELECT inv.gkey AS gkey,to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,
			to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			'' AS destination,r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,
			vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			(SELECT qua.id FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			'' as tues,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,'15.00' AS vatperc,NVL(fcy.flex_string06,'W') AS wpn,
			(SELECT time_discharge_complete
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
			fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			null AS diff,'1' AS FLAG
			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN  ( ref_bizunit_scoped r       
			LEFT JOIN ( ref_agent_representation X       
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey   
			)  ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id in (".$container_id.")  AND dtl.ib_vyg='".$rot."' ".$extraCond.";

			UNION ALL

			SELECT gkey,fcy_time_in, fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
			billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,'0' AS tues,height,freight_kind,vatperc,'' AS wpn,
			cl_date,imp_rot,imp_ata,diff,FLAG
			FROM (   
			SELECT inv.gkey AS gkey,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			to_char(fcy.time_in, 'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT time_discharge_complete
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
			to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
			SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
			)+1 diff,inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
			(SELECT qua.id FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'2' AS FLAG

			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 
			 
			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN  ( ref_bizunit_scoped r       
			LEFT JOIN ( ref_agent_representation X       
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey   
			)  ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey
			WHERE inv.id in (".$container_id.")  AND dtl.ib_vyg='".$rot."' ".$extraCond."       
			) tbl WHERE diff >0

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,billingDate,
			argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,'0' AS tues,height,freight_kind,vatperc,'' AS wpn,cl_date,
			imp_rot,imp_ata,diff,FLAG
			FROM   
			(   
			SELECT inv.gkey AS gkey,
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT time_discharge_complete
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
			to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
			SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
			)+1 diff,inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd, 
			(SELECT qua.id FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
			fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata ,
			'3' AS FLAG


			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 

			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN  ( ref_bizunit_scoped r       
			LEFT JOIN ( ref_agent_representation X       
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey   
			)  ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey
			WHERE inv.id in (".$container_id.")  AND dtl.ib_vyg='".$rot."' ".$extraCond."  
			) tbl WHERE diff >7

			UNION ALL

			SELECT gkey,fcy_time_in,fcy_time_out,iso_grp,'' AS destination,mlo,mlo_name,agent_code,agent,vsl_name,rotation,berth,
			billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,cont_size,'0' AS tues,height,freight_kind,vatperc,'' AS wpn,
			cl_date,imp_rot,imp_ata,diff,FLAG
			FROM   
			(   
			SELECT inv.gkey AS gkey, 
			(SELECT ref_equip_type.iso_group from ref_equip_type
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS iso_grp,
			to_char(fcy.time_in,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_in,to_char(fcy.time_out,'yyyy-mm-dd HH24:MI:SS') AS fcy_time_out,
			(SELECT time_discharge_complete
			FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date,
			to_date(to_char(fcy.time_out, 'yyyy-mm-dd'), 'yyyy-mm-dd') - (
			SELECT to_date(time_discharge_complete+4, 'yyyy-mm-dd') time_discharge_complete FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg=fcy.flex_string10 FETCH FIRST 1 ROWS ONLY
			)+1 diff,inv.category,'' AS dray_status,'' AS origin,to_char(arcar.ata, 'yyyy-mm-dd HH24:MI:SS') AS billingDate,
			r.id AS mlo,r.name AS mlo_name,NVL(Y.id,'') AS agent_code,NVL(Y.name,'') AS agent,vsl.name AS vsl_name,dtl.ib_vyg AS rotation,
			to_char(argo_visit_details.eta, 'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_eta,
			to_char(argo_visit_details.etd, 'yyyy-mm-dd HH24:MI:SS') AS argo_visist_dtls_etd,inv.id AS id,
			(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only) AS cont_size,
			((select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
			INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
			INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only)/10) AS height,
			inv.freight_kind,arcar.ata AS ata,NVL(arcar.atd,argo_visit_details.etd) AS atd,
			(SELECT qua.id FROM vsl_vessel_berthings brt 
			INNER JOIN argo_quay qua ON qua.gkey=brt.quay 
			WHERE brt.vvd_gkey=dtl.vvd_gkey FETCH FIRST 1 ROWS ONLY) AS berth,'15.00' AS vatperc,
			fcy.flex_string10 AS imp_rot,
			NVL(to_char(arcar.atd,'yyyy-mm-dd HH24:MI:SS'),to_char(argo_visit_details.etd,'yyyy-mm-dd HH24:MI:SS')) AS imp_ata,
			'4' AS FLAG

			FROM inv_unit inv 
			INNER JOIN inv_unit_fcy_visit fcy ON fcy.unit_gkey=inv.gkey 
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=fcy.actual_ob_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=arcar.cvcvd_gkey 
			 
			INNER JOIN vsl_vessel_visit_details dtl ON dtl.vvd_gkey=arcar.cvcvd_gkey 
			INNER JOIN  ( ref_bizunit_scoped r       
			LEFT JOIN ( ref_agent_representation X       
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey   
			)  ON r.gkey = inv.line_op 
			INNER JOIN vsl_vessels vsl ON vsl.gkey=dtl.vessel_gkey 
			WHERE inv.id in (".$container_id.")  AND dtl.ib_vyg='".$rot."'".$extraCond."       
			) tbl WHERE diff >20";
		
		$rslt=$this->dataSelectOracle($query);
		
		for($i=0;$i<count($rslt);$i++)
		{
			$gkey = $rslt[$i]['GKEY'];
			$fcy_time_in = $rslt[$i]['FCY_TIME_IN'];
			$fcy_time_out = $rslt[$i]['FCY_TIME_OUT'];			
			$iso_grp = $rslt[$i]['ISO_GRP'];			
			$destination = $rslt[$i]['DESTINATION'];
			$mlo = $rslt[$i]['MLO'];			
			$mlo_name = $rslt[$i]['MLO_NAME'];
			$agent_code = $rslt[$i]['AGENT_CODE'];   
            $agent = $rslt[$i]['AGENT'];
			$vsl_name = $rslt[$i]['VSL_NAME'];
            $rotation = $rslt[$i]['ROTATION'];
			$berth = $rslt[$i]['BERTH'];
			$billingDate = $rslt[$i]['BILLINGDATE'];
            $argo_visist_dtls_eta = $rslt[$i]['ARGO_VISIST_DTLS_ETA'];
            $argo_visist_dtls_etd = $rslt[$i]['ARGO_VISIST_DTLS_ETD'];
			$id = $rslt[$i]['ID'];
            $size = $rslt[$i]['CONT_SIZE'];
			$height = $rslt[$i]['HEIGHT'];
			$freight_kind = $rslt[$i]['FREIGHT_KIND'];			
			$vatperc = $rslt[$i]['VATPERC'];
			$wpn = $rslt[$i]['WPN'];
			$cl_date = $rslt[$i]['CL_DATE'];
			$imp_rot = $rslt[$i]['IMP_ROT'];
			$imp_ata = $rslt[$i]['IMP_ATA'];
			$diff = $rslt[$i]['DIFF'];
			$flag = $rslt[$i]['FLAG'];

			$billing_dt = date("Y-m-d", strtotime($billingDate));			
			
			$mlo_name=str_replace("'","\\\\'",$mlo_name);
			$agent=str_replace("'","\\\\'",$agent);
			
			$queryForDraftId = "SELECT DISTINCT draft_id 
			FROM ".$this->get_table_name("mis_billing")." 
			WHERE imp_rot = '".$rotation."' AND mlo_code = '".$mlo."' AND bill_type = '".$invoice_type."' AND containers=".$cont_for_draft_id;
			
			$rslt_queryForDraftId=$this->dataSelectCtmsMis($queryForDraftId);
			$draftNo = $rslt_queryForDraftId[0]['draft_id'];

			$tues = 0;
			$invoice_type = "135"; // 112 is for Status Change Invoice (ICD to PCT)
			
			$tarrif_id_data = "";
			$Tarif_rate = "";
			$from_depo = "";
			
			if($freight_kind=="MTY"){
				$from_depo = $fcy_time_in;
			} else {
				$from_depo = NULL;
			}
			
			if($flag==1)
			{
				if($size=="20"){
					$tues = 1;
				} else {
					$tues = 2;
				}
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='135' 
								AND (intrf.tarrif_id LIKE 'EXTRA%' OR intrf.tarrif_id LIKE 'STATUS%')";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$description = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey,description
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
						$description = $rslt_billing_desc[$desc]['DESCRIPTION'];
					}
					
					$queryBillingCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey' 
												ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_billing_currency_gkey=$this->dataSelectBilling($queryBillingCurrencyGkey);
					
					$currency_gkey = 0;
					for($cgkey=0;$cgkey<count($rslt_billing_currency_gkey);$cgkey++){
						$currency_gkey = $rslt_billing_currency_gkey[$currency_gkey]['CURRENCY_GKEY'];
					}					
					
					$queryAmt = "SELECT amount AS amount FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
								ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					
					$rslt_amt=$this->dataSelectBilling($queryAmt);
					for($amt=0;$amt<count($rslt_amt);$amt++){
						$Tarif_rate = $rslt_amt[$amt]['AMOUNT'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = $Tarif_rate*$exchangeRate;					
					$vat = ($amt*$vatperc)/100;
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,
										berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,
										wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			else if($flag==2)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='135' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);	
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=1 AND bltire.min_quantity <8)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if($diff > 7)
					{
						$amt = $temp_amt*7;
						$vat = $temp_vat*7;
					}
					else
					{
						$amt = $temp_amt*$diff;
						$vat = $temp_vat*$diff;
					}
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,
										berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,
										wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			else if($flag==3)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='135' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND 
					(bltire.min_quantity >=8 AND bltire.min_quantity <21)";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					if(($diff-7) > 13)
					{
						$amt = $temp_amt*13;
						$vat = $temp_vat*13;
					}
					else
					{
						$amt = $temp_amt*($diff-7);
						$vat = $temp_vat*($diff-7);
					}
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,
										berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,
										wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			else if($flag==4)
			{
				$tues = 0;
				
				$queryTarrifId = "SELECT ctmsmis.mis_inv_tarrif.tarrif_id FROM ctmsmis.mis_inv_tarrif 
								WHERE gkey='$gkey' AND invoice_type='135' AND tarrif_id LIKE '%storage%'";
				$rslt_tarrif_id_data=$this->dataSelectCtmsMis($queryTarrifId);
				
				for($trid=0;$trid<count($rslt_tarrif_id_data);$trid++){
					$tarrif_id_data = $rslt_tarrif_id_data[$trid]['tarrif_id'];
					
					$bil_tariffs_gkey = "";
					$queryBillingDescription = "SELECT bil_tariffs.gkey
												FROM bil_tariffs WHERE id='$tarrif_id_data'";
					$rslt_billing_desc=$this->dataSelectBilling($queryBillingDescription);
					for($desc=0;$desc<count($rslt_billing_desc);$desc++){
						$bil_tariffs_gkey = $rslt_billing_desc[$desc]['GKEY'];
					}
					
					$description = "";
					$Tarif_rate = "";
					$queryDescription = "SELECT bltire.description AS description,bltire.amount AS tarif_rate
					FROM bil_tariff_rate_tiers bltire
					INNER JOIN bil_tariff_rates ON bil_tariff_rates.gkey=bltire.rate_gkey
					WHERE  bil_tariff_rates.tariff_gkey='$bil_tariffs_gkey' AND bltire.min_quantity >=21";
					$rslt_description=$this->dataSelectBilling($queryDescription);
					for($rowDesc=0;$rowDesc<count($rslt_description);$rowDesc++){
						$description = $rslt_description[$rowDesc]['DESCRIPTION'];
						$Tarif_rate = $rslt_description[$rowDesc]['TARIF_RATE'];
					}
					
					$currency_gkey = "";
					$queryCurrencyGkey = "SELECT currency_gkey FROM bil_tariff_rates WHERE tariff_gkey='$bil_tariffs_gkey'
											ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
					$rslt_currency_gkey=$this->dataSelectBilling($queryCurrencyGkey);
					for($rowCGkey=0;$rowCGkey<count($rslt_currency_gkey);$rowCGkey++){
						$currency_gkey = $rslt_currency_gkey[$rowCGkey]['CURRENCY_GKEY'];
					}
					
					$exchangeRate = 0;
					if($currency_gkey != 370){
						$queryRate = "SELECT rate FROM bil_currency_exchange_rates 
							WHERE to_char(effective_date,'yyyy-mm-dd')='$billing_dt'";
						$rslt_rate=$this->dataSelectBilling($queryRate);
						for($rt=0;$rt<count($rslt_rate);$rt++){
							$exchangeRate = $rslt_rate[$rt]['RATE'];
						}
						
						if($exchangeRate == null or count($rslt_rate)==0){
							$queryRate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
							$rslt_rate=$this->dataSelectBilling($queryRate);
							for($rt=0;$rt<count($rslt_rate);$rt++){
								$exchangeRate = $rslt_rate[$rt]['RATE'];
							}
						}
						
					} else {
						$exchangeRate = 1;
					}
					
					$amt = 0;
					$vat = 0;
					$temp_amt = $Tarif_rate*$exchangeRate;
					$temp_vat = ($temp_amt*$vatperc)/100;
					
					$amt = $temp_amt*($diff-20);
					$vat = $temp_vat*($diff-20);
					
					$queryForInsert="INSERT INTO ".$this->get_table_name("mis_billing_details")."(gkey,draftNumber,fcy_time_in,fcy_time_out,
										cl_date,depo_date,destination,mlo,mlo_name,agent_code,agent,invoice_type,vsl_name,rotation,
										berth,billingDate,argo_visist_dtls_eta,argo_visist_dtls_etd,id,size,height,freight_kind,vatperc,
										wpn,Tarif_rate,exchangeRate,description,currency_gkey,amt,vat,iso_grp,tues,pre_imp_rot,pre_imp_ata)
					VALUES('".$gkey."','".$draftNo."',".$fcy_time_in.",".$fcy_time_out.",".$cl_date.",".$from_depo.",'".$destination."',
					'".$mlo."','".$mlo_name."','".$agent_code."','".$agent."','".$invoice_type."','".$vsl_name."','".$rotation."',
					'".$berth."','".$billingDate."','".$argo_visist_dtls_eta."','".$argo_visist_dtls_etd."','".$id."','".$size."',
					'".$height."','".$freight_kind."','".$vatperc."','".$wpn."','".$Tarif_rate."','".$exchangeRate."','".$description."',
					'".$currency_gkey."','".$amt."','".$vat."','".$iso_grp."','".$tues."',".$imp_rot.",".$imp_ata.")";
					$this->dataInsertCtmsmis($queryForInsert);
				}
			}
			
		}//for loop ends with insertion
		
		$strDraftId = "SELECT draft_id 
		FROM ".$this->get_table_name("mis_billing")." 
		WHERE imp_rot='".$rot."' AND bill_type=135";		
	//	return;
		$rsDraftId=$this->dataSelectCtmsMis($strDraftId);
		
		for($i=0;$i<count($rsDraftId);$i++)
		{
			$totAmt="";
			$totVat="";
			
			$DraftId = $rsDraftId[$i]['draft_id'];
			
			$strQuery = "SELECT SUM(amt) AS totAmt,SUM(vat) AS totVat 
			FROM ".$this->get_table_name("mis_billing_details")."
			WHERE draftNumber='".$DraftId."'";
			
			$rsAmt = $this->dataSelectCtmsMis($strQuery);
			
			for($j=0;$j<count($rsAmt);$j++)
			{
				$totAmt=$rsAmt[$j]['totAmt'];
				$totVat=$rsAmt[$j]['totVat'];
				
				$strUpdate = "UPDATE ".$this->get_table_name("mis_billing")."
				SET totAmt='".$totAmt."',totVat='".$totVat."' 
				WHERE draft_id='".$DraftId."'";
				
				$this->dataUpdateCtmsmis($strUpdate);
			}
		}
	}
	
	function testingConnection(){				
		$sql_get_rate = "SELECT rate FROM bil_currency_exchange_rates ORDER BY effective_date DESC FETCH FIRST 1 ROWS ONLY";
		$rslt_rate=$this->dataSelectBilling($sql_get_rate);
		$draft_exchange_rate = "";
		for($r=0;$r<count($rslt_rate);$r++){
			$draft_exchange_rate = $rslt_rate[$r]['RATE'];
		}
		echo $draft_exchange_rate;	
	}
}
?>
