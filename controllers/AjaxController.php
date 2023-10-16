<?php
//awal
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Ronnie - 8 Jul 2011
 */

class AjaxController extends CI_Controller {
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

	function ajaxValue(){
		$serch_by = $_GET["serch_by"];
		$query = "";
		if($serch_by=="offdoc")
		{
			$query = "select distinct code_ctms as id,name from ctmsmis.offdoc";
		}
		
		if($serch_by=="pod")
		{
			$query = "select * from
			(
			select
			(select id from sparcsn4.ref_unloc_code where gkey=tbl.unloc_gkey) as id,
			(select place_name from sparcsn4.ref_unloc_code where gkey=tbl.unloc_gkey) as name
			from 
			(
			select distinct unloc_gkey from sparcsn4.ref_routing_point
			) as tbl 
			) as final WHERE id REGEXP '[a-z]+' order by id";
		}
		
		$rtnVehicleList=$this->bm->dataSelect($query);
		echo json_encode($rtnVehicleList);
	}
	
	function logEquipName()
	{
		$serch_by = $_GET["serch_by"];
		$query = "";
		if($serch_by=="equip")
		{
			$query = "select distinct logEquip as id from ctmsmis.mis_equip_log_in_out_info where logEquip like'RTG%'";
		}
		
		if($serch_by=="euser")
		{
			$query = "select distinct logBy as id from ctmsmis.mis_equip_log_in_out_info where logEquip like'RTG%'";
		}
		
		$rtnVehicleList=$this->bm->dataSelectDb2($query);
		echo json_encode($rtnVehicleList);
	}




	function fetchDataShiftingContainer()
	{
		$rotation = $_GET["rotation"];
		$contNo = $_GET["contNo"];


		$query = "
		SELECT  DISTINCT igm_details.Import_Rotation_No,igm_detail_container.cont_number,mlocode,cont_iso_type,cont_status
		FROM igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotation' AND igm_detail_container.cont_number='$contNo'";	
		
		$holdShiftContainer=$this->bm->dataSelectDb1($query);
		$mlocode = "";
		$cont_iso_type = "";
		$cont_status="";
		for($i=0;$i<count($holdShiftContainer);$i++){
			$mlocode = $holdShiftContainer[$i]['mlocode'];
			$cont_iso_type = $holdShiftContainer[$i]['cont_iso_type'];
			$cont_status = $holdShiftContainer[$i]['cont_status'];
			
		}
			
		$data['mlocode']=$mlocode;
		$data['cont_iso_type']=$cont_iso_type;
		$data['cont_status']=$cont_status;


		echo json_encode($data);

	}


	function getEquipmentDemand()
	{
		$rotation = str_replace("_","/",$_GET["rotation"]);
		$bl_no = $_GET["bl_no"];

		$query = "SELECT cnf_name,cnf_lic_no, Vessel_Name, Pack_Number, Pack_Description, be_no, cont_number,cont_size,cont_weight,
			Cont_gross_weight, cont_height,cont_status,id
			FROM (SELECT organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,
			igm_masters.Vessel_Name, igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description, (SELECT be_no FROM oracle_nts_data WHERE imp_rot_no='2021/3934' AND bl_no='920708546') AS be_no,
			igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size, 
			igm_sup_detail_container.cont_weight, igm_sup_detail_container.Cont_gross_weight,igm_sup_detail_container.cont_height,             igm_sup_detail_container.cont_status,igm_supplimentary_detail.id
			FROM igm_supplimentary_detail 
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
			LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
			LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation'  AND igm_supplimentary_detail.BL_No='$bl_no' GROUP BY igm_sup_detail_container.id

			UNION ALL

			SELECT organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,
			igm_masters.Vessel_Name, igm_details.Pack_Number, igm_details.Pack_Description, (SELECT be_no FROM oracle_nts_data WHERE imp_rot_no='2021/3934' AND bl_no='920708546') AS be_no,
			igm_detail_container.cont_number,
			igm_detail_container.cont_size, igm_detail_container.cont_weight, igm_detail_container.cont_gross_weight,igm_detail_container.cont_height,igm_detail_container.cont_status,igm_details.id
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id

			WHERE igm_details.Import_Rotation_No='$rotation'  AND igm_details.BL_No='$bl_no'
			GROUP BY igm_detail_container.id) AS tbl";

		$cnfEquipmentDemand=$this->bm->dataSelectDb1($query);

		$contMain ="";
		$contStatus="";
		$igm_id = "";

		for($k=0;$k<count($cnfEquipmentDemand);$k++)
		{
			$contMain = $cnfEquipmentDemand[$k]['cont_number'];
			$contStatus = $cnfEquipmentDemand[$k]['cont_status'];
			$igm_id = $cnfEquipmentDemand[$k]['id'];

			$cnfEquipment = null;

			if($contStatus=="FCL")
			{
				$strQuerypos="SELECT inv_unit_fcy_visit.last_pos_slot AS pos
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
				WHERE inv_unit.id='$contMain' AND ib_vyg='$rotation'";
				$cnfEquipment=$this->bm->dataSelect($strQuerypos);
			}
			else if($contStatus=="FCL/PART")
			{
				$strQuerypos="SELECT inv_unit_fcy_visit.last_pos_slot AS pos
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
				WHERE inv_unit.id='$contMain' AND ib_vyg='$rotation'";
				$cnfEquipment=$this->bm->dataSelect($strQuerypos);
			}
			else if($contStatus=="LCL")
			{
				$strQuerypos = "SELECT shed_loc AS pos,shed_yard AS Yard_No 
				FROM shed_tally_info 
				WHERE import_rotation = '$rotation' AND cont_number = '$contMain' AND 
				(igm_sup_detail_id='$igm_id' OR igm_detail_id='$igm_id')
				ORDER BY id DESC LIMIT 1";
				$cnfEquipment=$this->bm->dataSelectDb1($strQuerypos);
			}

			// print_r($strQuerypos);
			// print_r($cnfEquipment);
			
			$pos="";
			$yard_No="";

			if(0<count($cnfEquipment))
			{
				$pos = $cnfEquipment[0]['POS'];
				$last_pos_slot=$cnfEquipment[0]['LAST_POS_SLOT'];
				
				$strQueryposn4="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
				$pos_rslt = $this->bm->dataSelectDb2($strQueryposn4);
				if(0<count($pos_rslt))
				{
					$yard_No = $pos_rslt[0]['Yard_No'];
				}
			}

			$cnfEquipmentDemand[$k]['pos'] = $pos;
			$cnfEquipmentDemand[$k]['yard_No'] = $yard_No;
		}

		$data['cnfEquipmentDemand']=$cnfEquipmentDemand;
		echo json_encode($data);

	}
	
	function getMlo()
	{
		$rotation = $_GET["rot"];
		$rot = str_replace("_","/",$rotation);
		$login_id = $this->session->userdata('login_id');
		$ofdock = $this->Offdock($login_id);
		$query = "select distinct cont_mlo from ctmsmis.mis_exp_unit_preadv_req where rotation='$rot' and transOp=$
		";	
		
		$rtnVehicleList=$this->bm->dataSelectDb2($query);
		echo json_encode($rtnVehicleList);
	}
	
	function getAgentOrg()
	{
		
		$qry=$_GET['q'];
		
		$sql_igmOrgId="select  distinct Organization_Name,organization_profiles.id from organization_profiles inner join 
		igm_details 
		on igm_details.Submitee_Org_Id=organization_profiles.id
		where igm_details.Import_Rotation_No='$qry'";
		$igmOrgId = $this->bm->dataSelectDb1($sql_igmOrgId);
			
		
		$org_name = "";
		$org_id = "";
		
		for($i=0;$i<count($igmOrgId);$i++){
			$org_id = $igmOrgId[$i]['id'];
			$org_name = $igmOrgId[$i]['Organization_Name'];
		}
			
		$data['org_name']=$org_name;
		$data['org_id']=$org_id;
		$data['orgCount']=count($igmOrgId);
		
		
		
		echo json_encode($data);

		//$this->load->view('getAgent');

	}


	function getOrgSection()
	{
		$org_id = $_GET["orgId"];
		$query = "SELECT section_value,section_lebel FROM tbl_org_section WHERE org_type_id=$org_id";	
		
		$rtnSectionList=$this->bm->dataSelectDb1($query);
		echo json_encode($rtnSectionList);
	}
	
	
	
	function getLCLContInfo()			
	{
		$cont = $_GET['cont'];
		$ival = $_GET['ival'];
		
		
		    $strDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
		    $rtnValDtlCont = $this->bm->dataReturnDb1($strDtlCont);
		
		    $strSupDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_sup_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
		    $rtnValSupDtl = $this->bm->dataReturnDb1($strSupDtlCont);
		
			if($rtnValDtlCont==0 and $rtnValSupDtl==0)
		   {
			$strN4check="select count(*) as rtnValue from inv_unit where freight_kind='LCL' AND id='$cont'";
			$contN4check = $this->bm->dataReturn($strN4check); 
			
			if($contN4check>0)
			{
				$strUpdateDetail="update igm_detail_container set cont_status='LCL' where cont_number='$cont' and cont_status='FCL'";
				$strUpdateStat1 = $this->bm->dataUpdateDB1($strUpdateDetail);
				
				$strUpdateSupDetail="update igm_sup_detail_container set cont_status='LCL' where cont_number='$cont' and cont_status='FCL'";
				$strUpdateStat2 = $this->bm->dataUpdateDB1($strUpdateSupDetail);
				
				
					$strDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
					$rtnValDtlCont = $this->bm->dataReturnDb1($strDtlCont);
			
					$strSupDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_sup_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
					$rtnValSupDtl = $this->bm->dataReturnDb1($strSupDtlCont);
			}
  
             else
			 {
			    echo "Container not found.";
			 }
			
		}
						
		if($rtnValDtlCont>0 or $rtnValSupDtl>0)
		{
			$strContDetail = "select cont_size,cont_height,Vessel_Name,igm_details.Import_Rotation_No,mlocode,igm_detail_container.id,igm_detail_id,
						    	LEFT(Description_of_Goods,20) as Description_of_Goods
                              from igm_detail_container 
						      inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id
							  inner join igm_masters on igm_masters.id=igm_details.IGM_id
							  where cont_number='$cont' order by igm_detail_container.id desc limit 1";
			//echo $strContDetail;
			$contInfo = $this->bm->dataSelectDb1($strContDetail);
			 
		    //json_encode($contInfo);
			$cont_size="";
			$cont_height="";
			$Vessel_Name="";
			$Import_Rotation_No="";
			$mlocode="";
			$igmDetailContId="";
			$igmDetailId="";
			for($i=0;$i<count($contInfo);$i++) {
				$cont_size=$contInfo[$i]['cont_size'];
				$cont_height=$contInfo[$i]['cont_height'];
				$Vessel_Name=$contInfo[$i]['Vessel_Name'];
				$Import_Rotation_No=$contInfo[$i]['Import_Rotation_No'];
				$mlocode=$contInfo[$i]['mlocode'];
				$igmDetailContId=$contInfo[$i]['id'];
				$igmDetailId=$contInfo[$i]['igm_detail_id'];
				$des_of_goods=$contInfo[$i]['Description_of_Goods'];
			}
			
			
			$strBerth="select flex_string02 as rtnValue from vsl_vessel_visit_details where ib_vyg='$Import_Rotation_No'";
			$berthOp = $this->bm->dataReturn($strBerth);
			
		
						$strTimeIn="SELECT inv_unit_fcy_visit.time_in AS rtnValue FROM inv_unit_fcy_visit
						INNER JOIN inv_unit ON inv_unit_fcy_visit.gkey=inv_unit.gkey
						WHERE inv_unit.category='IMPRT' 
						AND id='$cont' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
						
			$timeInfo = $this->bm->dataReturn($strTimeIn);			
			
			echo "|".$cont_size."|".$cont_height."|".$Vessel_Name."|".$Import_Rotation_No."|".$mlocode."|".$igmDetailContId."|".$igmDetailId."|".$berthOp."|".$timeInfo."|".$ival."|".$des_of_goods;
		}
		else
		{
			echo "Container not found";
		}
	
	}
	
	/*function getLCLContInfo()			
	{
		$cont = $_GET['cont'];
		$ival = $_GET['ival'];
		
		
		    $strDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
		    $rtnValDtlCont = $this->bm->dataReturnDb1($strDtlCont);
		
		    $strSupDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_sup_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
		    $rtnValSupDtl = $this->bm->dataReturnDb1($strSupDtlCont);
		
			if($rtnValDtlCont==0 and $rtnValSupDtl==0)
		   {
			$strN4check="select count(*) as rtnValue from inv_unit where freight_kind='LCL' AND id='$cont'";
			$contN4check = $this->bm->dataReturn($strN4check); 
			
			if($contN4check>0)
				{
					$strUpdateDetail="update igm_detail_container set cont_status='LCL' where cont_number='$cont' and cont_status='FCL'";
					$strUpdateStat1 = $this->bm->dataUpdateDB1($strUpdateDetail);
					
					$strUpdateSupDetail="update igm_sup_detail_container set cont_status='LCL' where cont_number='$cont' and cont_status='FCL'";
					$strUpdateStat2 = $this->bm->dataUpdateDB1($strUpdateSupDetail);
					
					
					 $strDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
					 $rtnValDtlCont = $this->bm->dataReturnDb1($strDtlCont);
				
					 $strSupDtlCont = "SELECT COUNT(*) as rtnValue FROM igm_sup_detail_container WHERE cont_number='$cont' AND cont_status='LCL'";
					 $rtnValSupDtl = $this->bm->dataReturnDb1($strSupDtlCont);
				}
  
             else
			 {
			    echo "Container not found.";
			 }
			
		}
						
		if($rtnValDtlCont>0 or $rtnValSupDtl>0)
		{
			$strContDetail = "select cont_size,cont_height,Vessel_Name,igm_details.Import_Rotation_No,mlocode,igm_detail_container.id,igm_detail_id,
						    	LEFT(Description_of_Goods,20) as Description_of_Goods
                              from igm_detail_container 
						      inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id
							  inner join igm_masters on igm_masters.id=igm_details.IGM_id
							  where cont_number='$cont' order by igm_detail_container.id desc limit 1";
			//echo $strContDetail;
			$contInfo = $this->bm->dataSelectDb1($strContDetail);
			 
		    //json_encode($contInfo);
			$cont_size="";
			$cont_height="";
			$Vessel_Name="";
			$Import_Rotation_No="";
			$mlocode="";
			$igmDetailContId="";
			$igmDetailId="";
			for($i=0;$i<count($contInfo);$i++) {
				$cont_size=$contInfo[$i]['cont_size'];
				$cont_height=$contInfo[$i]['cont_height'];
				$Vessel_Name=$contInfo[$i]['Vessel_Name'];
				$Import_Rotation_No=$contInfo[$i]['Import_Rotation_No'];
				$mlocode=$contInfo[$i]['mlocode'];
				$igmDetailContId=$contInfo[$i]['id'];
				$igmDetailId=$contInfo[$i]['igm_detail_id'];
				$des_of_goods=$contInfo[$i]['Description_of_Goods'];
			}
			
			$strBerth = "select flex_string02 as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$Import_Rotation_No'";
			$berthOp = $this->bm->dataReturn($strBerth);
			
			$strTimeIn="select inv_unit_fcy_visit.time_in as rtnValue from inv_unit_fcy_visit
						inner join inv_unit on 
						inv_unit_fcy_visit.gkey=inv_unit.gkey
						where inv_unit.category='IMPRT' 
						and id='$cont' ORDER BY inv_unit.gkey desc limit 1";
						
			$timeInfo = $this->bm->dataReturn($strTimeIn);			
			
			echo "|".$cont_size."|".$cont_height."|".$Vessel_Name."|".$Import_Rotation_No."|".$mlocode."|".$igmDetailContId."|".$igmDetailId."|".$berthOp."|".$timeInfo."|".$ival."|".$des_of_goods;
		}
		else
		{
			echo "Container not found";
		}
	
	}*/
	
	
  function getShedDtlInfo()
	{
		    $shed = $_GET["shed"];
			$query = "select lcl_assignment_detail.id,igm_detail_container.cont_number,
			igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
			igm_details.mlocode,berthOp as stv,cont_loc_shed,cargo_loc_shed,description_cargo,
			lcl_assignment_detail.remarks,date(lcl_assignment_detail.landing_time)as landing_time,if(assignment_date<=date(now()),1,0) as st 
			from lcl_assignment_detail
			inner join igm_details on igm_details.id=lcl_assignment_detail.igm_detail_id 
			inner join igm_masters on igm_masters.id=igm_details.IGM_id 
			inner join igm_detail_container on igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id where unstuff_flag=0 and cont_loc_shed='$shed' order by assignment_date";	
            $rtnShedList = $this->bm->dataSelectDb1($query);
            echo json_encode($rtnShedList);	   
	}

	function getAssignmentInfo()
	{
		//$this->syncLclAssignment();
		$dt = $_GET['dt'];
		$shed = $_GET['shed'];
		$strDtlCont = "SELECT lcl_assignment_detail.id,igm_detail_container.cont_number,sl,igm_cont_detail_id,lcl_assignment_detail.igm_detail_id,
		igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Import_Rotation_No,Vessel_Name,assignment_date,
		igm_details.mlocode,berthOp AS stv,cont_loc_shed,cargo_loc_shed,description_cargo,
		lcl_assignment_detail.remarks,lcl_assignment_detail.landing_time FROM lcl_assignment_detail
		INNER JOIN igm_details ON igm_details.id=lcl_assignment_detail.igm_detail_id 
		INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
		INNER JOIN igm_detail_container ON igm_detail_container.id=lcl_assignment_detail.igm_cont_detail_id WHERE unstuff_flag=0
		AND assignment_date=DATE_SUB('$dt', INTERVAL 1 DAY) and cont_loc_shed='$shed' order by sl asc";
		$rtnValDtlCont = $this->bm->dataSelectDb1($strDtlCont);
		echo json_encode($rtnValDtlCont);
	}
	
	
	function getDeliveryInfo()
	{
		$verifyNo = $_GET['verifyNo'];
		$query = "select shed_tally_info.import_rotation, igm_supplimentary_detail.BL_No,master_Line_No,igm_supplimentary_detail.Pack_Marks_Number, mlocode,
				igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Consignee_name, Cont_gross_weight,
				ABS(Cont_gross_weight-cont_weight) as Net_weight, shed_tally_info.cont_number,
				igm_sup_detail_container.cont_height,igm_sup_detail_container.cont_size, igm_sup_detail_container.cont_type, verify_other_data.be_no,
				verify_other_data.be_date,do_date,wr_upto_date,cnf_lic_no, cnf_name,cont_number_packaages,rcv_pack
				from shed_tally_info 
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				left join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
				where verify_number=$verifyNo";
           $deliveryList = $this->bm->dataSelectDb1($query);   
           echo json_encode($deliveryList);	 
	}
	
   	function getDeliveryByBLInfo()
	{	//deliveryList
		$blNo = $_GET['blNo'];
		$rotNo = $_GET['rotNo'];
		
		$rotNo = str_replace("_","/",$rotNo);
		
		$tmp_rotNo=str_replace("/"," ",$rotNo);			
		
		$sql_regNno="SELECT distinct reg_no AS rtnValue
		FROM sad_info
		INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		WHERE manif_num LIKE '%$tmp_rotNo%' AND sum_declare='$blNo'";
		
		//$seaNo = $this->bm->dataReturnDb1($sql_regNno);
		$chk=0;
		$seaNo=0;
		$sql_regNnoRst = $this->bm->dataSelectDb1($sql_regNno);
		for($i=0; $i<count($sql_regNnoRst); $i++)	
		{		
			 $seaNo=$sql_regNnoRst[$i]['rtnValue'];
			 $chk++;
		} 
		// echo $seaNo;
		// return;
			
		 $seaInfo="";
		// echo count($sql_regNnoRst);
	
		if($chk>0)	
		{
		    $querySea="SELECT DISTINCT reg_no, office_code, reg_date, sum_declare AS blNo, manif_num, total_value, recp_no,recp_date, consignee_name,
			dec_code AS cnf_lic,dec_name AS cnf_name,goods_desc,place_dec AS exit_note_number,DATE(entry_dt) AS paper_file_date     
			FROM sad_info
			INNER JOIN sad_item ON  sad_info.id=sad_item.sad_id
			WHERE reg_no='$seaNo' AND sum_declare!=''
			ORDER BY entry_dt DESC LIMIT 1";
			
			$seaInfo = $this->bm->dataSelectDb1($querySea);
			$data['querySea']=$querySea;
		}
		$data['blNo']="bl : ".$blNo;
		$data['rotNo']="rot : ".$rotNo;
		
		$deliveryList=[];
		$commLandDate=[];		
		
		$login_id = $this->session->userdata('login_id');	
		
		// FCL
		
		
		/* $existStatusRst = $this->bm->dataSelectDb1($existStatus);
		$isExist=$existStatusRst[0]['rtnValue']; */
		
			//Added Sumon---------- Dated: 11/01/2021			
	
		$contStat=$this->chkContainerStatus($blNo,$rotNo);   //check cont status
		if($contStat=="")
		{
			$data['msg']='The Rotation or BL is not matched.';
			$data['exchnStatus']="";
			$data['cont_igm']="";		
			$data['igmContList']="";		
			$data['beContList']="";		
			$data['seaInfo']="";	
			$data['deliveryList']="";
			$data['commLandDate']="";	

			$data['imageCount']="";		
			//return $data;	
		}
		else
		{
			
 		
		if($contStat=='LCL')
		{
			$existStatus="select count(shed_tally_info.id) AS rtnValue from shed_tally_info 
			inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";								
			$isExistLCL = $this->bm->dataReturnDb1($existStatus);
			
			if($isExistLCL>0)
			{
				$exchageStatusQueryDtl="select count(*) AS rtnValue from shed_tally_info 
				INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				where shed_tally_info.berth_exchange_done_status=1 AND shed_tally_info.cpa_exchange_done_status=1 
				AND shed_tally_info.ff_exchange_done_status=1 and shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";				
				$exchageStatusRslt = $this->bm->dataReturnDb1($exchageStatusQueryDtl);
			}
			else{
				$exchageStatusQueryDtl="select count(*) AS rtnValue from shed_tally_info 
				inner join igm_details on shed_tally_info.igm_detail_id=igm_details.id
				where shed_tally_info.berth_exchange_done_status=1 AND shed_tally_info.cpa_exchange_done_status=1 
				AND shed_tally_info.ff_exchange_done_status=1 and shed_tally_info.import_rotation='$rotNo' and igm_details.BL_No='$blNo'";				
				$exchageStatusRslt = $this->bm->dataReturnDb1($exchageStatusQueryDtl);
			}
			
			if($exchageStatusRslt==0)
			{
				$data['exchnStatus']='Exchange Not Done!';
			}
			else
			{
				$data['exchnStatus']='Exchange Done';
			}
		}
		else if($contStat=='FCL')
		{
			$existStatus="SELECT COUNT(certify_info_fcl.id) AS rtnValue
			FROM certify_info_fcl 
			INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			
			UNION 

			SELECT COUNT(certify_info_fcl.id) AS rtnValue
			FROM certify_info_fcl 
			INNER JOIN igm_supplimentary_detail ON certify_info_fcl.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
			$isExist = $this->bm->dataReturnDb1($existStatus);
			
			if($isExist>0)
			{
				$data['exchnStatus']='';
				//$data['exchnStatus']='Certified';
			}
			else
			{
				$data['exchnStatus']='FCL: not certified';

			}
		}

			$cont_igm="SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo' AND cont_number NOT IN(SELECT cont_number 
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo')
			UNION
			SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
			$igmContList = $this->bm->dataSelectDb1($cont_igm);
			
			if(!$chk>0)
			{
				$cont_be="SELECT cont_number,freight_kind FROM sad_info
				INNER JOIN sad_container ON sad_info.id=sad_container.sad_id
				WHERE reg_no='$seaNo'";
				$beContList = $this->bm->dataSelectDb1($cont_be);
			}
			else
			{
				$beContList="";
			}
			$data['cont_igm']=$cont_igm;		
			$data['igmContList']=$igmContList;		
			$data['beContList']=$beContList;		
			$data['seaInfo']=$seaInfo;	

			/*  if($exchnStatus==0)
			{
				$data['exchnStatus']='no';			
			} */
				
			//check if cont data is in sad_container
			$sql_chk_sad_cont="SELECT COUNT(*) AS rtnValue 
			FROM sad_info
			INNER JOIN sad_container ON sad_container.sad_id=sad_info.id
			WHERE reg_no='$seaNo'";
			
			/* $sql_chk_sad_contRslt = $this->bm->dataSelectDb1($sql_chk_sad_cont);
			$rslt_chk_sad_cont=$sql_chk_sad_contRslt[0]['rtnValue']; */
				
			//$rslt_chk_sad_cont=$this->bm->dataReturnDb1($sql_chk_sad_cont);
			$sql_chk_sad_contRslt = $this->bm->dataSelectDb1($sql_chk_sad_cont);
			for($i=0; $i<count($sql_chk_sad_contRslt); $i++)	
			{
				$rslt_chk_sad_cont=$sql_chk_sad_contRslt[$i]['rtnValue']; 
			}

			if($contStat=='FCL')		// FCL
			{	
				$query="SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
				(SELECT cont_gross_weight 
				FROM igm_detail_container
				WHERE igm_detail_id=igm_details.id
				LIMIT 1) AS Cont_gross_weight,
				(SELECT SUM(cont_weight) 
				FROM igm_detail_container 
				WHERE igm_detail_id=igm_details.id) AS cont_weight,
				igm_details.mlocode,organization_profiles.Organization_Name,
				(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
				igm_details.Description_of_Goods AS description,
				certify_info_fcl.id,DATE(NOW()) AS DATE,
				verify_info_fcl.verify_number,IFNULL(verify_info_fcl.be_no,shed_mlo_do_info.be_no) AS be_no, IFNULL(verify_info_fcl.be_date,shed_mlo_do_info.be_date) AS be_date, verify_info_fcl.paper_file_date, verify_info_fcl.exit_note_number, verify_info_fcl.cus_rel_odr_no , verify_info_fcl. cus_rel_odr_date,
				shed_mlo_do_info.id AS do_no,
				shed_mlo_do_info.do_date,
				shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
				verify_info_fcl.date AS cnfDate,IFNULL(verify_info_fcl.no_of_truck,(SELECT no_of_truck FROM assignment_request_data WHERE rotation = '$rotNo' AND bl='$blNo')) AS no_of_truck,
				certify_info_fcl.cnf_lic_no,
				(SELECT Organization_Name FROM organization_profiles WHERE License_No=certify_info_fcl.cnf_lic_no) AS cnf_name
				FROM igm_details
				LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
				INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
				LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
				
				
				UNION				

				SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.	Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
				(SELECT cont_gross_weight 
				FROM igm_detail_container
				WHERE igm_detail_id=igm_details.id
				LIMIT 1) AS Cont_gross_weight,
				(SELECT SUM(cont_weight) 
				FROM igm_detail_container 
				WHERE igm_detail_id=igm_details.id) AS cont_weight,
				igm_details.mlocode,organization_profiles.Organization_Name,
				(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
				igm_details.Description_of_Goods AS description,
				certify_info_fcl.id,DATE(NOW()) AS DATE,
				verify_info_fcl.verify_number,IFNULL(verify_info_fcl.be_no,shed_mlo_do_info.be_no) AS be_no, IFNULL(verify_info_fcl.be_date,shed_mlo_do_info.be_date) AS be_date, verify_info_fcl.paper_file_date, verify_info_fcl.exit_note_number, verify_info_fcl.cus_rel_odr_no , verify_info_fcl. cus_rel_odr_date,
				shed_mlo_do_info.id AS do_no,
				shed_mlo_do_info.do_date,
				shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
				verify_info_fcl.date AS cnfDate,IFNULL(verify_info_fcl.no_of_truck,(SELECT no_of_truck FROM assignment_request_data WHERE rotation = '$rotNo' AND bl='$blNo')) AS no_of_truck,
				certify_info_fcl.cnf_lic_no,
				(SELECT Organization_Name FROM organization_profiles WHERE License_No=certify_info_fcl.cnf_lic_no) AS cnf_name
				FROM igm_supplimentary_detail
				INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
				INNER JOIN organization_profiles ON igm_supplimentary_detail.Submitee_Org_Id=organization_profiles.id
				LEFT JOIN certify_info_fcl ON igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=igm_supplimentary_detail.BL_No AND shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No	
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
			}
			else					// LCL
			{
				$query="SELECT shed_tally_info.id,shed_tally_info.igm_detail_id, shed_tally_info.igm_sup_detail_id,
				verify_other_data.shed_tally_id, igm_supplimentary_detail.id AS sup_id,igm_supplimentary_detail.igm_master_id,
				igm_supplimentary_detail.igm_detail_id,igm_supplimentary_detail.Import_Rotation_No AS rotNo,igm_supplimentary_detail.Pack_Number,
				igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.master_BL_No,igm_supplimentary_detail.Notify_name,
				(SELECT Cont_gross_weight
				FROM igm_sup_detail_container
				WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS Cont_gross_weight,
				(SELECT SUM(cont_weight) 
				FROM igm_sup_detail_container 
				WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS cont_weight,igm_details.mlocode,
				organization_profiles.Organization_Name,
				(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
				igm_supplimentary_detail.Description_of_Goods AS description,		
				shed_mlo_do_info.id AS do_no,
				shed_mlo_do_info.do_date,
				shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
				shed_tally_info.id,DATE(NOW()) AS DATE,IFNULL(verify_other_data.no_of_truck,(SELECT no_of_truck FROM assignment_request_data WHERE rotation = '$rotNo' AND bl='$blNo')) AS no_of_truck,verify_other_data.cus_rel_odr_no,verify_other_data.cus_rel_odr_date,shed_tally_info.verify_number,verify_other_data.cnf_lic_no,verify_other_data.be_no,
				verify_other_data.be_date,verify_other_data.cnf_name,verify_other_data.exit_note_number,verify_other_data.paper_file_date, verify_other_data.date,
				verify_other_data.date AS cnfDate
				FROM igm_supplimentary_detail
				INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
				INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
				LEFT JOIN shed_tally_info ON 
				shed_tally_info.igm_detail_id = igm_details.id OR shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
				LEFT JOIN assigned_unit ON assigned_unit.rotation=shed_tally_info.import_rotation
				INNER JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No AND  igm_supplimentary_detail.BL_No=shed_mlo_do_info.bl_no
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
			}
			
			$deliveryList = $this->bm->dataSelectDb1($query);

			// kawsar added this code to get verify no. if FCL data found in igm_details table on 2nd oct 2023
			if(strlen(trim($deliveryList[0]['verify_number'])) == 0){
				$query = "SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.	Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
				(SELECT cont_gross_weight 
				FROM igm_detail_container
				WHERE igm_detail_id=igm_details.id
				LIMIT 1) AS Cont_gross_weight,
				(SELECT SUM(cont_weight) 
				FROM igm_detail_container 
				WHERE igm_detail_id=igm_details.id) AS cont_weight,
				igm_details.mlocode,organization_profiles.Organization_Name,
				(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
				igm_details.Description_of_Goods AS description,
				certify_info_fcl.id,DATE(NOW()) AS DATE,
				verify_info_fcl.verify_number,IFNULL(verify_info_fcl.be_no,shed_mlo_do_info.be_no) AS be_no, IFNULL(verify_info_fcl.be_date,shed_mlo_do_info.be_date) AS be_date, verify_info_fcl.paper_file_date, verify_info_fcl.exit_note_number, verify_info_fcl.cus_rel_odr_no , verify_info_fcl. cus_rel_odr_date,
				shed_mlo_do_info.id AS do_no,
				shed_mlo_do_info.do_date,
				shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
				verify_info_fcl.date AS cnfDate,IFNULL(verify_info_fcl.no_of_truck,(SELECT no_of_truck FROM assignment_request_data WHERE rotation = '$rotNo' AND bl='$blNo')) AS no_of_truck,
				certify_info_fcl.cnf_lic_no,
				(SELECT Organization_Name FROM organization_profiles WHERE License_No=certify_info_fcl.cnf_lic_no) AS cnf_name
				FROM igm_supplimentary_detail
				INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
				INNER JOIN organization_profiles ON igm_supplimentary_detail.Submitee_Org_Id=organization_profiles.id
				LEFT JOIN certify_info_fcl ON igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_supplimentary_detail.id
				LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
				LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=igm_supplimentary_detail.BL_No AND shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No	
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
				$tempDeliveryList = $this->bm->dataSelectDb1($query);
				if(strlen(trim($tempDeliveryList[0]['verify_number'])) > 0){
					$deliveryList = $tempDeliveryList;
				}
			}

			// kawsar added this code to get cnf name and lic on 9th july 2023

			$cnfQuery = "SELECT cnf_name,cnf_lic_no
			FROM (SELECT organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no 
			FROM igm_supplimentary_detail 
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id 
			LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
			LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo' GROUP BY igm_sup_detail_container.id
			
			UNION ALL
			
			SELECT organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			GROUP BY igm_detail_container.id) AS tbl";
			$cnfData = $this->bm->dataSelectDb1($cnfQuery);
			$data['cnfData']=$cnfData;

		
			//$subQuery="select date(sparcsn4.vsl_vessel_visit_details.flex_date08) as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotNo'";
			$subQuery="select to_char(vsl_vessel_visit_details.flex_date08,'YYYY-MM-DD')as rtnValue from vsl_vessel_visit_details where ib_vyg='$rotNo'";
			$commLandDate = $this->bm->dataSelect($subQuery);
			
			$doImage="SELECT COUNT(*) AS rtnValue,id,imp_rot,bl_no FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND  bl_no='$blNo' LIMIT 1";
			$imageCount = $this->bm->dataSelectDb1($doImage);
			
			$doInfoStr="SELECT id,rotation,bl,igm_type,bl_type,mlo,ff_stat,cpa_approve_st,ff_assoc_st,ff_org_id,sh_agent_org_id,do_upload_st,entry_time,sumitted_by FROM edo_application_by_cf WHERE rotation='$rotNo' AND  bl='$blNo' LIMIT 1";
			$doData = $this->bm->dataSelectDb1($doInfoStr);
				
			$data['deliveryList']=$deliveryList;
			$data['doData']=$doData;
			$data['commLandDate']=$commLandDate;	

			$data['imageCount']=$imageCount;		
			$data['msg']="";
			
			$manif_num = str_replace("/"," ",$rotNo);
			$sqlContainer = "SELECT igm_supplimentary_detail.id,IFNULL(SUM(rcv_pack)+SUM(loc_first),0) AS rcv_pack,
			igm_sup_detail_container.cont_number,igm_supplimentary_detail.Import_Rotation_No,
			igm_supplimentary_detail.Pack_Marks_Number,shed_loc,shed_yard, igm_supplimentary_detail.Description_of_Goods,
			igm_supplimentary_detail.ConsigneeDesc, igm_supplimentary_detail.NotifyDesc,cont_size,
			cont_weight,cont_seal_number,igm_sup_detail_container.cont_status,cont_height,cont_iso_type,
			IFNULL(shed_tally_info.verify_number,0) AS verify_number, 
			IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',shed_tally_info.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
			
			shed_tally_info.wr_date as ustuffing_dt, shed_tally_info.verify_by,shed_tally_info.verify_time, IFNULL(shed_tally_info.id,0) AS verify_id,off_dock_id, 
			IF(shed_mlo_do_info.be_no IS NULL OR shed_mlo_do_info.be_no=' ',
			IF(certify_info_fcl.be_no IS NULL OR certify_info_fcl.be_no=' ',verify_other_data.be_no,certify_info_fcl.be_no),
			shed_mlo_do_info.be_no) AS be_no, 
			IF(shed_mlo_do_info.be_date IS NULL OR shed_mlo_do_info.be_date='0000-00-00' OR shed_mlo_do_info.be_date=' ',
				IF(certify_info_fcl.be_date IS NULL OR certify_info_fcl.be_date='0000-00-00' OR certify_info_fcl.be_date=' ',
					verify_other_data.be_date,certify_info_fcl.be_date),
			shed_mlo_do_info.be_date) AS be_date, 
			(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_sup_detail_container.off_dock_id) AS offdock_name, organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no, shed_mlo_do_info.id AS agent_do, DATE(shed_mlo_do_info.upload_time) AS do_date, IFNULL(verify_other_data.update_ton,certify_info_fcl.update_ton) AS update_ton 
			FROM igm_supplimentary_detail 
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN edo_application_by_cf ON igm_supplimentary_detail.Import_Rotation_No=edo_application_by_cf.rotation AND igm_supplimentary_detail.BL_No=edo_application_by_cf.bl 
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by 
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id 
			LEFT JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id 
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=edo_application_by_cf.bl AND shed_mlo_do_info.imp_rot=edo_application_by_cf.rotation
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			LEFT JOIN certify_info_fcl ON igm_details.id=certify_info_fcl.igm_detail_id OR igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id 
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo' GROUP BY igm_sup_detail_container.id

			UNION ALL

			SELECT igm_details.id,'' AS rcv_pack,igm_detail_container.cont_number,igm_details.Import_Rotation_No,Pack_Marks_Number,'' AS shed_loc,'' AS shed_yard,Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,igm_detail_container.cont_status,cont_height,cont_iso_type,
			verify_number, 
			IF(shed_mlo_do_info.valid_upto_dt IS NULL OR shed_mlo_do_info.valid_upto_dt='0000-00-00',certify_info_fcl.wr_upto_date,
				shed_mlo_do_info.valid_upto_dt)
				AS wr_upto_date,
			
			certify_info_fcl.wr_upto_date as ustuffing_dt,
			verify_by,verify_time,IFNULL(certify_info_fcl.id,0) AS verify_id,
			off_dock_id,
			IFNULL(shed_mlo_do_info.be_no,(SELECT reg_no
			FROM sad_info
			INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$blNo' LIMIT 1)) AS be_no,
			IFNULL(shed_mlo_do_info.be_date,(SELECT reg_date
			FROM sad_info
			INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE '%$manif_num%' AND sum_declare = '$blNo' LIMIT 1)) AS be_date,

			(SELECT Organization_Name FROM organization_profiles WHERE organization_profiles.id=igm_detail_container.off_dock_id) AS offdock_name,
			organization_profiles.Organization_Name AS cnf_name, organization_profiles.License_No AS cnf_lic_no,

			shed_mlo_do_info.id AS do_no, DATE(shed_mlo_do_info.upload_time) AS do_date,
			certify_info_fcl.update_ton AS update_ton

			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN edo_application_by_cf ON igm_details.Import_Rotation_No=edo_application_by_cf.rotation AND igm_details.BL_No=edo_application_by_cf.bl
			LEFT JOIN users ON users.login_id=edo_application_by_cf.sumitted_by
			LEFT JOIN organization_profiles ON organization_profiles.id=users.org_id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			GROUP BY igm_detail_container.id";
			$rtnContainerList = $this->bm->dataSelectDb1($sqlContainer);
			$data['rtnContainerList']=$rtnContainerList;
		
			$approveQuery = "SELECT id, check_st FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no = '$blNo'";
			$approveRslt = $this->bm->dataSelectDb1($approveQuery);
			$data['approveRslt']=$approveRslt;
		} 	
		echo json_encode($data);	
		
	}

	
	function chkContainerStatus($blNo,$rotNo)
	{
		 $sql_contStatus="SELECT IFNULL((SELECT cont_status AS rtnValue   
		FROM  igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo' 
		GROUP BY igm_sup_detail_container.id

		UNION

		SELECT cont_status AS rtnValue
		FROM  igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo' 
		GROUP BY igm_detail_container.id),'') AS rtnValue";
		
		$contStatRslt=$this->bm->dataSelectDb1($sql_contStatus);	
		$contStatus=$contStatRslt[0]['rtnValue'];
		return $contStatus;
	}

function getDeliveryBySeaInfo()
	{
		//$data['stat']="stat";	
		$seaNo = $_GET['seaNo'];	
							
		$querySea="SELECT DISTINCT reg_no, office_code, reg_date, sum_declare AS blNo,manif_num,total_value,recp_no,recp_date,consignee_name,dec_code AS cnf_lic,dec_name AS cnf_name,goods_desc,place_dec AS exit_note_number,DATE(entry_dt) AS paper_file_date     
		FROM sad_info
		INNER JOIN sad_item ON  sad_info.id=sad_item.sad_id
		WHERE reg_no='$seaNo' AND sum_declare!=''
		ORDER BY entry_dt DESC LIMIT 1";
		//$sql_regNnoRst = $this->bm->dataSelectDb1($querySea);
		
		$chk;
		$chk=0;
		//$seaNo=0;
		$seaInfo = $this->bm->dataSelectDb1($querySea);
		if(count($seaInfo)>0)
		{
			$chk++;
		}

		
		
		$blNo = $seaInfo[0]['blNo'];
		
		$manif_num = $seaInfo[0]['manif_num'];
		
		$manif_exp=explode(" ",$manif_num);
		
		if(count($manif_exp)==3)
			$rotNo=$manif_exp[1]."/".$manif_exp[2];
		else
			$rotNo=$manif_exp[0]."/".$manif_exp[1];
		
		$data['blNo']="bl : ".$blNo;
		$data['rotNo']="rot : ".$rotNo;
		
		
		$data['querySea']=$querySea;
		$deliveryList=[];
		$commLandDate=[];		
		
		$login_id = $this->session->userdata('login_id');	
		$deliveryList=[];
		$commLandDate=[];		
		
		$login_id = $this->session->userdata('login_id');	
		
		// FCL
		
		
		/* $existStatusRst = $this->bm->dataSelectDb1($existStatus);
		$isExist=$existStatusRst[0]['rtnValue']; */
		
			//Added Sumon---------- Dated: 11/01/2021			
	
		$contStat=$this->chkContainerStatus($blNo,$rotNo);   //check cont status
		if($contStat=="")
		{
			$data['msg']='The Rotation or BL is not matched.';
			$data['exchnStatus']='';
			$data['cont_igm']="";		
			$data['igmContList']="";		
			$data['beContList']="";		
			$data['seaInfo']="";	
			$data['deliveryList']="";
			$data['commLandDate']="";	

			$data['imageCount']="";		
			//return $data;	
		}
		else
		{
			
 		
		if($contStat=='LCL')
		{
			$existStatus="select count(shed_tally_info.id) AS rtnValue from shed_tally_info 
			inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";
				//return;				
			$isExistLCL = $this->bm->dataReturnDb1($existStatus);
		//echo "1";
		
			if($isExistLCL>0)
			{
				$exchageStatusQueryDtl="select count(*) AS rtnValue from shed_tally_info 
								inner join igm_details on shed_tally_info.igm_detail_id=igm_details.id
								where shed_tally_info.berth_exchange_done_status=1 AND shed_tally_info.cpa_exchange_done_status=1 AND shed_tally_info.ff_exchange_done_status=1 and shed_tally_info.import_rotation='$rotNo' and igm_details.BL_No='$blNo'";
				
				$exchageStatusRslt = $this->bm->dataReturnDb1($exchageStatusQueryDtl);
			}
			
			if($exchageStatusRslt==0)
			{
				$data['exchnStatus']='Exchange Not Done!';
			}
			else
			{
				$data['exchnStatus']='Exchange Done';
			}
		}
		else if($contStat=='FCL')
		{
			$existStatus="SELECT COUNT(certify_info_fcl.id) AS rtnValue
			FROM certify_info_fcl 
			INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			UNION 

			SELECT COUNT(certify_info_fcl.id) AS rtnValue
			FROM certify_info_fcl 
			INNER JOIN igm_supplimentary_detail ON certify_info_fcl.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
			$isExist = $this->bm->dataReturnDb1($existStatus);
			
			if($isExist>0)
			{
				//$data['exchnStatus']='Certified';
				$data['exchnStatus']='';
			}
			else
			{
				$data['exchnStatus']='FCL: not certified';

			}
		}

		$cont_igm="SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_detail_container
		INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo' AND cont_number NOT IN(SELECT cont_number 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'
		)
		UNION
		SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		$igmContList = $this->bm->dataSelectDb1($cont_igm);
		
		if(!$chk>0)
		{
			$cont_be="SELECT cont_number,freight_kind FROM sad_info
			INNER JOIN sad_container ON sad_info.id=sad_container.sad_id
			WHERE reg_no='$seaNo'";
			$beContList = $this->bm->dataSelectDb1($cont_be);
		}
		else
		{
			$beContList="";
		}
		$data['cont_igm']=$cont_igm;		
		$data['igmContList']=$igmContList;		
		$data['beContList']=$beContList;		
		$data['seaInfo']=$seaInfo;	

		/*  if($exchnStatus==0)
		{
			$data['exchnStatus']='no';			
		} */
			 
		//check if cont data is in sad_container
		$sql_chk_sad_cont="SELECT COUNT(*) AS rtnValue 
		FROM sad_info
		INNER JOIN sad_container ON sad_container.sad_id=sad_info.id
		WHERE reg_no='$seaNo'";
		
		/* $sql_chk_sad_contRslt = $this->bm->dataSelectDb1($sql_chk_sad_cont);
		$rslt_chk_sad_cont=$sql_chk_sad_contRslt[0]['rtnValue']; */
			
		//$rslt_chk_sad_cont=$this->bm->dataReturnDb1($sql_chk_sad_cont);
		$sql_chk_sad_contRslt = $this->bm->dataSelectDb1($sql_chk_sad_cont);
		for($i=0; $i<count($sql_chk_sad_contRslt); $i++)	
		{
			$rslt_chk_sad_cont=$sql_chk_sad_contRslt[$i]['rtnValue']; 
		}

		if($rslt_chk_sad_cont>0)		// FCL
		{	
			 $query="SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
			(SELECT cont_gross_weight 
			FROM igm_detail_container
			WHERE igm_detail_id=igm_details.id
			LIMIT 1) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_detail_container 
			WHERE igm_detail_id=igm_details.id) AS cont_weight,
			igm_details.mlocode,organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_details.Description_of_Goods AS description,
			certify_info_fcl.id,DATE(NOW()) AS DATE,
			verify_info_fcl.verify_number,
			shed_mlo_do_info.id as do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			verify_info_fcl.date AS cnfDate,verify_info_fcl.no_of_truck
			FROM igm_details
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'
			
		
			UNION				

			SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.	Pack_Description, igm_supplimentary_detail.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
			(SELECT cont_gross_weight 
			FROM igm_detail_container
			WHERE igm_detail_id=igm_details.id
			LIMIT 1) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_detail_container 
			WHERE igm_detail_id=igm_details.id) AS cont_weight,
			igm_details.mlocode,organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_details.Description_of_Goods AS description,
			certify_info_fcl.id,DATE(NOW()) AS DATE,
			verify_info_fcl.verify_number,IFNULL(verify_info_fcl.be_no,shed_mlo_do_info.be_no) AS be_no, IFNULL(verify_info_fcl.be_date,shed_mlo_do_info.be_date) AS be_date, verify_info_fcl.paper_file_date, verify_info_fcl.exit_note_number, verify_info_fcl.cus_rel_odr_no , verify_info_fcl. cus_rel_odr_date,
			shed_mlo_do_info.id AS do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			verify_info_fcl.date AS cnfDate,IFNULL(verify_info_fcl.no_of_truck,(SELECT no_of_truck FROM assignment_request_data WHERE rotation = '$rotNo' AND bl='$blNo')) AS no_of_truck,
			certify_info_fcl.cnf_lic_no,
			(SELECT Organization_Name FROM organization_profiles WHERE License_No=certify_info_fcl.cnf_lic_no) AS cnf_name
			FROM igm_supplimentary_detail
			INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
			INNER JOIN organization_profiles ON igm_supplimentary_detail.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN certify_info_fcl ON igm_supplimentary_detail.id=certify_info_fcl.igm_sup_detail_id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.bl_no=igm_supplimentary_detail.BL_No AND shed_mlo_do_info.imp_rot=igm_supplimentary_detail.Import_Rotation_No	
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		}
		else					// LCL
		{
			$query="SELECT igm_supplimentary_detail.id AS sup_id,igm_supplimentary_detail.igm_master_id,igm_supplimentary_detail.igm_detail_id,igm_supplimentary_detail.Import_Rotation_No AS rotNo,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.master_BL_No,igm_supplimentary_detail.Notify_name,
			(SELECT Cont_gross_weight
			FROM igm_sup_detail_container
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_sup_detail_container 
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS cont_weight,igm_details.mlocode,
			organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_supplimentary_detail.Description_of_Goods AS description,		
			shed_mlo_do_info.id as do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			shed_tally_info.id,DATE(NOW()) AS DATE,verify_other_data.no_of_truck,verify_other_data.cus_rel_odr_no, verify_other_data.cus_rel_odr_date,shed_tally_info.verify_number,verify_other_data.date AS cnfDate
			FROM igm_supplimentary_detail
			INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=shed_tally_info.import_rotation
			LEFT JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		}
		
		$deliveryList = $this->bm->dataSelectDb1($query);   
	   
		//$subQuery="select date(sparcsn4.vsl_vessel_visit_details.flex_date08) as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotNo'";
		$subQuery="select to_char(vsl_vessel_visit_details.flex_date08) as rtnValue from vsl_vessel_visit_details where ib_vyg='$rotNo'";
		$commLandDate = $this->bm->dataSelect($subQuery);
		
		$doImage="SELECT COUNT(*) AS rtnValue,id,imp_rot,bl_no,do_image_loc FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND  bl_no='$blNo' LIMIT 1";
		$imageCount = $this->bm->dataSelectDb1($doImage);
	  
		$data['deliveryList']=$deliveryList;
		$data['commLandDate']=$commLandDate;	

		$data['imageCount']=$imageCount;		
	  	$data['msg']="";

		
		} 	
		echo json_encode($data);			
	}
	/* function getDeliveryByBLInfo()
	{
		$blNo = $_GET['blNo'];
		$rotNo = $_GET['rotNo'];
		
		$rotNo = str_replace("_","/",$rotNo);
		
		$tmp_rotNo=str_replace("/"," ",$rotNo);			
		
		$sql_regNno="SELECT IFNULL( (SELECT reg_no AS rtnValue
		FROM sad_info
		INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		WHERE manif_num LIKE '%$tmp_rotNo%' AND sum_declare='$blNo'),'') AS rtnValue";
		//$seaNo = $this->bm->dataReturnDb1($sql_regNno);
		$sql_regNnoRst = $this->bm->dataSelectDb1($sql_regNno);
		for($i=0; $i<count($sql_regNnoRst); $i++)	
		{
			$seaNo=$sql_regNnoRst[$i]['rtnValue']; 
		}
		
		$data['stat']="stat";				
		
			$querySea="SELECT DISTINCT reg_no, office_code, reg_date, sum_declare AS blNo, manif_num, total_value, recp_no,recp_date,consignee_name,dec_code AS cnf_lic,dec_name AS cnf_name,goods_desc,place_dec AS exit_note_number,DATE(entry_dt) AS paper_file_date     
			FROM sad_info
			INNER JOIN sad_item ON  sad_info.id=sad_item.sad_id
			WHERE reg_no='$seaNo' AND sum_declare!=''
			ORDER BY entry_dt DESC LIMIT 1";
			$seaInfo = $this->bm->dataSelectDb1($querySea);
		//}
		$data['blNo']="bl : ".$blNo;
		$data['rotNo']="rot : ".$rotNo;
		$data['querySea']=$querySea;
		$deliveryList=[];
		$commLandDate=[];		
		
		$login_id = $this->session->userdata('login_id');	
		
		// FCL
		$existStatus="SELECT COUNT(certify_info_fcl.id) AS rtnValue
		FROM certify_info_fcl 
		INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'";
		$isExist = $this->bm->dataReturnDb1($existStatus);

		
		if($isExist<1)
		{
			// LCL
			$existStatus="select count(shed_tally_info.id) AS rtnValue from shed_tally_info 
			inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";
								
			$isExist = $this->bm->dataReturnDb1($existStatus);

		}
		
		$data['isExist']=$isExist;		
		
		$exchageStatusQueryDtl="select exchange_done_status AS rtnValue from shed_tally_info 
							inner join igm_details on shed_tally_info.igm_detail_id=igm_details.id
							where shed_tally_info.import_rotation='$rotNo' and igm_details.BL_No='$blNo'";
		$exchageStatusRslt = $this->bm->dataSelectDb1($exchageStatusQueryDtl);
		
		$exchnStatus=0;
		for($i=0; $i<count($exchageStatusRslt); $i++)	
		{
			$exchnStatus=$exchageStatusRslt[$i]['rtnValue'];
		}		
		// if($exchnStatus<1)
		if($exchnStatus < 1 or $exchnStatus=="")
		{
			$exchageStatusQuery="select exchange_done_status AS rtnValue from shed_tally_info 
								inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
								where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";
			$exchageStatus_rslt = $this->bm->dataSelectDb1($exchageStatusQuery);
		
			for($i=0; $i<count($exchageStatus_rslt); $i++)	
			{
				$exchnStatus=$exchageStatus_rslt[$i]['rtnValue'];
			}		
								
			//$exchnStatus = $this->bm->dataReturnDb1($exchageStatusQuery);
		} 
		
		$cont_igm="SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_detail_container
		INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo' AND cont_number NOT IN(SELECT cont_number 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'
		)
		UNION
		SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		$igmContList = $this->bm->dataSelectDb1($cont_igm);
		
		$cont_be="SELECT cont_number,freight_kind FROM sad_info
		INNER JOIN sad_container ON sad_info.id=sad_container.sad_id
		WHERE reg_no='$seaNo'";
		$beContList = $this->bm->dataSelectDb1($cont_be);
		
		$data['cont_igm']=$cont_igm;		
		$data['igmContList']=$igmContList;		
		$data['beContList']=$beContList;		
		$data['seaInfo']=$seaInfo;	

		 if($exchnStatus==0)
		{
			$data['exchnStatus']='no';			
		}
			 
		//check if cont data is in sad_container
		$sql_chk_sad_cont="SELECT COUNT(*) AS rtnValue 
		FROM sad_info
		INNER JOIN sad_container ON sad_container.sad_id=sad_info.id
		WHERE reg_no='$seaNo'";
		

		$sql_chk_sad_contRslt = $this->bm->dataSelectDb1($sql_chk_sad_cont);
		for($i=0; $i<count($sql_chk_sad_contRslt); $i++)	
		{
			$rslt_chk_sad_cont=$sql_chk_sad_contRslt[$i]['rtnValue']; 
		}

		if($rslt_chk_sad_cont>0)		// FCL
		{														
			
			$query="SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
			(SELECT cont_gross_weight 
			FROM igm_detail_container
			WHERE igm_detail_id=igm_details.id
			LIMIT 1) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_detail_container 
			WHERE igm_detail_id=igm_details.id) AS cont_weight,
			igm_details.mlocode,organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_details.Description_of_Goods AS description,
			certify_info_fcl.id,DATE(NOW()) AS DATE,
			verify_info_fcl.verify_number,
			shed_mlo_do_info.do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			verify_info_fcl.date AS cnfDate,verify_info_fcl.no_of_truck
			FROM igm_details
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'";
		}
		else					// LCL
		{
			
			
			$query="SELECT igm_supplimentary_detail.id AS sup_id,igm_supplimentary_detail.igm_master_id,igm_supplimentary_detail.igm_detail_id,igm_supplimentary_detail.Import_Rotation_No AS rotNo,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.master_BL_No,igm_supplimentary_detail.Notify_name,
			(SELECT Cont_gross_weight
			FROM igm_sup_detail_container
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_sup_detail_container 
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS cont_weight,igm_details.mlocode,
			organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_supplimentary_detail.Description_of_Goods AS description,		
			shed_mlo_do_info.do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			shed_tally_info.id,DATE(NOW()) AS DATE,verify_other_data.no_of_truck,verify_other_data.cus_rel_odr_no, verify_other_data.cus_rel_odr_date,shed_tally_info.verify_number,verify_other_data.date AS cnfDate
			FROM igm_supplimentary_detail
			INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=shed_tally_info.import_rotation
			LEFT JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		}
		
		$deliveryList = $this->bm->dataSelectDb1($query);   
	   
		$subQuery="select date(sparcsn4.vsl_vessel_visit_details.flex_date08) as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotNo'";
		$commLandDate = $this->bm->dataSelect($subQuery);
		
		$doImage="SELECT COUNT(*) AS rtnValue,id,imp_rot,bl_no FROM do_info_image WHERE be_no='$seaNo' LIMIT 1";
		$imageCount = $this->bm->dataSelectDb1($doImage);
	  
		$data['deliveryList']=$deliveryList;
		$data['commLandDate']=$commLandDate;	

		$data['imageCount']=$imageCount;		
	  
		echo json_encode($data);	 		
	}
 */

	
	/* function getDeliveryBySeaInfo()
	{
		//$data['stat']="stat";	
		$seaNo = $_GET['seaNo'];	
							
		$querySea="SELECT DISTINCT reg_no, office_code, reg_date, sum_declare AS blNo,manif_num,total_value,recp_no,recp_date,consignee_name,dec_code AS cnf_lic,dec_name AS cnf_name,goods_desc,place_dec AS exit_note_number,DATE(entry_dt) AS paper_file_date     
		FROM sad_info
		INNER JOIN sad_item ON  sad_info.id=sad_item.sad_id
		WHERE reg_no='$seaNo' AND sum_declare!=''
		ORDER BY entry_dt DESC LIMIT 1";
		$seaInfo = $this->bm->dataSelectDb1($querySea);
		
		
		$blNo = $seaInfo[0]['blNo'];
		
		$manif_num = $seaInfo[0]['manif_num'];
		
		$manif_exp=explode(" ",$manif_num);
		
		if(count($manif_exp)==3)
			$rotNo=$manif_exp[1]."/".$manif_exp[2];
		else
			$rotNo=$manif_exp[0]."/".$manif_exp[1];
		
		$data['blNo']="bl : ".$blNo;
		$data['rotNo']="rot : ".$rotNo;
		$data['querySea']=$querySea;
		$deliveryList=[];
		$commLandDate=[];		
		
		$login_id = $this->session->userdata('login_id');			
		
		// FCL
		$isExist = 0;
		
		$existStatus="SELECT COUNT(certify_info_fcl.id) AS rtnValue
		FROM certify_info_fcl 
		INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'";
		
		//$isExist = $this->bm->dataReturnDb1($existStatus);
		if(@$this->bm->dataReturnDb1($existStatus)){
			$isExist = $this->bm->dataReturnDb1($existStatus);
		}
	
		if($isExist<1)
		{
			// LCL
			$existStatus="select count(shed_tally_info.id) AS rtnValue from shed_tally_info 
			inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";
								
			//$isExist = $this->bm->dataReturnDb1($existStatus);
			if(@$this->bm->dataReturnDb1($existStatus)){
				$isExist = $this->bm->dataReturnDb1($existStatus);
			}
		}
		
		$data['isExist']=$isExist;
		
		$exchnStatus = 0;
		
		$exchageStatusQueryDtl="select exchange_done_status AS rtnValue from shed_tally_info 
							inner join igm_details on shed_tally_info.igm_detail_id=igm_details.id
							where shed_tally_info.import_rotation='$rotNo' and igm_details.BL_No='$blNo'";
		//$exchnStatus = $this->bm->dataReturnDb1($exchageStatusQueryDtl);	
		
		if(@$this->bm->dataReturnDb1($exchageStatusQueryDtl)){
			$exchnStatus = $this->bm->dataReturnDb1($exchageStatusQueryDtl);
		}

		
		
		if($exchnStatus<1)
		{
			$exchageStatusQuery="select exchange_done_status AS rtnValue from shed_tally_info 
								inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
								where shed_tally_info.import_rotation='$rotNo' and igm_supplimentary_detail.BL_No='$blNo'";
								
			//$exchnStatus = $this->bm->dataReturnDb1($exchageStatusQuery);
			while(@$this->bm->dataReturnDb1($exchageStatusQueryDtl)){
				$exchnStatus++;
			}
		}
		
		
		$cont_igm="SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_detail_container
		INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo' AND cont_number NOT IN(SELECT cont_number 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'
		)
		UNION
		SELECT  cont_number,cont_size,cont_weight,cont_height,cont_status 
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		$igmContList = $this->bm->dataSelectDb1($cont_igm);
		
		$cont_be="SELECT cont_number,freight_kind FROM sad_info
		INNER JOIN sad_container ON sad_info.id=sad_container.sad_id
		WHERE reg_no=$seaNo";
		$beContList = $this->bm->dataSelectDb1($cont_be);
		
		$data['igmContList']=$igmContList;		
		$data['beContList']=$beContList;		
		$data['seaInfo']=$seaInfo;		
		
		if($exchnStatus==0)
		{
			$data['exchnStatus']='no';			
		}
			
		//check if cont data is in sad_container
		$rslt_chk_sad_cont = 0;
		
		$sql_chk_sad_cont="SELECT COUNT(*) AS rtnValue 
		FROM sad_info
		INNER JOIN sad_container ON sad_container.sad_id=sad_info.id
		WHERE reg_no='$seaNo'";
		
		//$rslt_chk_sad_cont=$this->bm->dataReturnDb1($sql_chk_sad_cont);
		if($this->bm->dataReturnDb1($sql_chk_sad_cont)){
			$rslt_chk_sad_cont = $this->bm->dataReturnDb1($sql_chk_sad_cont);
		}
			
		if($rslt_chk_sad_cont>0)		// FCL
		{
			
			$query="SELECT igm_details.id AS dtl_id,igm_details.IGM_id,igm_details.Import_Rotation_No AS rotNo,igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No AS blNo,igm_details.Pack_Marks_Number,igm_details.BL_No AS master_BL_No,igm_details.Notify_name,
			(SELECT cont_gross_weight 
			FROM igm_detail_container
			WHERE igm_detail_id=igm_details.id
			LIMIT 1) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_detail_container 
			WHERE igm_detail_id=igm_details.id) AS cont_weight,
			igm_details.mlocode,organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_details.Description_of_Goods AS description,
			certify_info_fcl.id,DATE(NOW()) AS DATE,
			verify_info_fcl.verify_number,
			shed_mlo_do_info.do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			verify_info_fcl.date AS cnfDate,verify_info_fcl.no_of_truck
			FROM igm_details
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blNo'";
		}
		else					// LCL
		{
			
			$query="SELECT igm_supplimentary_detail.id AS sup_id,igm_supplimentary_detail.igm_master_id,igm_supplimentary_detail.igm_detail_id,igm_supplimentary_detail.Import_Rotation_No AS rotNo,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.BL_No AS blNo,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.master_BL_No,igm_supplimentary_detail.Notify_name,
			(SELECT Cont_gross_weight
			FROM igm_sup_detail_container
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS Cont_gross_weight,
			(SELECT SUM(cont_weight) 
			FROM igm_sup_detail_container 
			WHERE igm_sup_detail_id=igm_supplimentary_detail.id) AS cont_weight,igm_details.mlocode,
			organization_profiles.Organization_Name,
			(SELECT unit_no FROM assigned_unit WHERE rotation=rotNo) AS one_stop_point,
			igm_supplimentary_detail.Description_of_Goods AS description,		
			shed_mlo_do_info.do_no,
			shed_mlo_do_info.do_date,
			shed_mlo_do_info.valid_upto_dt AS valid_up_to_date,
			shed_tally_info.id,DATE(NOW()) AS DATE,verify_other_data.no_of_truck,verify_other_data.cus_rel_odr_no, verify_other_data.cus_rel_odr_date,shed_tally_info.verify_number,verify_other_data.date AS cnfDate
			FROM igm_supplimentary_detail
			INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			INNER JOIN organization_profiles ON igm_details.Submitee_Org_Id=organization_profiles.id
			LEFT JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
			LEFT JOIN assigned_unit ON assigned_unit.rotation=shed_tally_info.import_rotation
			LEFT JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
			LEFT JOIN shed_mlo_do_info ON shed_mlo_do_info.igm_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blNo'";
		}
			
		$deliveryList = $this->bm->dataSelectDb1($query);   
	   
		$subQuery="select date(sparcsn4.vsl_vessel_visit_details.flex_date08) as rtnValue from sparcsn4.vsl_vessel_visit_details where ib_vyg='$rotNo'";
		$commLandDate = $this->bm->dataSelect($subQuery);	

		$doImage="SELECT COUNT(*) AS rtnValue,id,do_image_loc,imp_rot,bl_no FROM shed_mlo_do_info WHERE be_no='$seaNo' LIMIT 1";
		$imageCount = $this->bm->dataSelectDb1($doImage);
	    $cntImgCount = count($imageCount);
	   
		$data['deliveryList']=$deliveryList;
		$data['commLandDate']=$commLandDate;
		
		$data['imageCount']=$imageCount;
		$data['cntImgCount']=$cntImgCount;		
	  
		echo json_encode($data);	    		   
	} */
	
	
	
	
	function getVerifyInfo()
	{
		$verifyNo = $_GET['verifyNo'];
		//$verify = substr($verifyNo, -4);
		$query ="SELECT verify_number,verify_unit,DATE_FORMAT(date(verify_time),'%d%m%y')as verify_time, shed_tally_info.import_rotation,
		shed_bill_master.bill_no, shed_bill_master.bill_date,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit, date(recv_time) as bank_cp_date, 
		date(verify_time)as verifyDate,shed_tally_info.tally_sheet_number,
		        shed_tally_info.wr_date,rcv_pack,shed_loc, igm_sup_detail_container.cont_number, appraise_date,
		        igm_sup_detail_container.cont_height, igm_sup_detail_container.cont_type,appraise_date,
				igm_supplimentary_detail.BL_No, igm_supplimentary_detail.master_BL_No,
				igm_sup_detail_container.cont_status, igm_sup_detail_container.cont_size, shed_tally_info.shed_yard, shed_tally_info.actual_marks, 
				igm_masters.Vessel_Name, Registration_number_of_transport_code, igm_supplimentary_detail.Pack_Marks_Number, igm_supplimentary_detail.Description_of_Goods, 
				igm_sup_detail_container.Cont_gross_weight,igm_sup_detail_container.cont_weight,
                (shed_tally_info.rcv_pack+shed_tally_info.loc_first) as un_rcv_qty, igm_details.Pack_Description,
				igm_supplimentary_detail.Pack_Number, verify_other_data.be_no, verify_other_data.be_date,
                shed_bill_master.grand_total,shed_loc, if(shed_bill_master.bill_rcv_stat=1,'Paid','Not Paid')as bill_rcv_stat, 
				igm_supplimentary_detail.weight, igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Notify_name, 
				igm_supplimentary_detail.Notify_address, shed_tally_id,
				no_of_truck, verify_other_data.cnf_lic_no, verify_other_data.cnf_name,cus_rel_odr_no,cus_rel_odr_date, paper_file_date,exit_note_number,date, 
				verify_other_data.clerk_assign
				from shed_tally_info 
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				left join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
		 		left join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id 
                                left join appraisement_info on igm_supplimentary_detail.BL_No=appraisement_info.BL_NO
                                left join shed_bill_master on shed_bill_master.verify_no=shed_tally_info.verify_number
                                left join bank_bill_recv on shed_bill_master.bill_no=bank_bill_recv.bill_no
				WHERE shed_tally_info.verify_number='$verifyNo'";

      	
        $deliveryList = $this->bm->dataSelectDb1($query);   
        echo json_encode($deliveryList);	 	 
	
	
	}
	
	
	
	//get clerk
	
	function getClerk()
	{
	    $clerkQuery = "SELECT u_name FROM users WHERE org_Type_id=63";						
		$clerkList = $this->bm->dataSelectDb1($clerkQuery);
		echo json_encode($clerkList);
		
	}
	

	
	function Offdock($login_id)
	{
		$offdoc ="";
		if($login_id=='gclt')
		{
		 $offdoc = "3328";
		}
		elseif($login_id=='saplw')
		{
		 $offdoc = "3450";
		}
		elseif($login_id=='ebil')
		{
		 $offdoc = "2594";
		}
		elseif($login_id=='cctcl')
		{
		 $offdoc = "2595";
		}
		elseif($login_id=='ktlt')
		{
		 $offdoc = "2596";
		}
		elseif($login_id=='qnsc')
		{
		 $offdoc = "2597";
		}
		elseif($login_id=='ocl')
		{
		 $offdoc = "2598";
		}
		elseif($login_id=='vlsl')
		{
		 $offdoc = "2599";
		}
		elseif($login_id=='shml')
		{
		 $offdoc = "2600";
		}
		elseif($login_id=='iqen')
		{
		 $offdoc = "2601";
		}
		elseif($login_id=='iltd')
		{
		 $offdoc = "2620";
		}
		
		elseif($login_id=='plcl')
		{
		 $offdoc = "2643";
		}
		elseif($login_id=='shpm')
		{
		 $offdoc = "2646";
		}
		elseif($login_id=='hsat')
		{
		 $offdoc = "3697";
		}
		elseif($login_id=='ellt')
		{
		 $offdoc = "3709";
		}
		elseif($login_id=='bmcd')
		{
		 $offdoc = "3725";
		}
		elseif($login_id=='nclt')
		{
		 $offdoc = "4013";
		}		
		else
		{
		 $offdoc = "";
		}
		return $offdoc;
	}
	// Sourav ....................
	function getBlockList()
	{
		    $yard = $_GET["yard"];
		    $jval = $_GET["jval"];
			$query = "select  $jval as myval,block
						from(
								select block from ctmsmis.yard_block where terminal='$yard'
							) as tt";	
            $rtnBlockList = $this->bm->dataSelectDb2($query);
            echo json_encode($rtnBlockList);
		//$terminal = $_POST["terminalName"];	   
	}
	
	
	function getBlock()
	{
		 $yard = $_GET["yard"];
		 $query = "select distinct block_cpa as block from ctmsmis.yard_block where terminal='$yard' and  block_cpa!='NULL' ORDER BY block ASC";
         $rtnBlockList = $this->bm->dataSelectDb2($query);
         echo json_encode($rtnBlockList);		 
	}
	/*function getBlockCpa()
	{
		 $yard = $_GET["yard"];
		 $query = "select distinct block_cpa as block from ctmsmis.yard_block where terminal='$yard' and  block_cpa!='NULL' ORDER BY block ASC";
         $rtnBlockList = $this->bm->dataSelect($query);
         echo json_encode($rtnBlockList);		 
	}*/
	function getBlockCpa()
	{
		 $yard = $_GET["yard"];
		 $query = "select distinct block_cpa as block from ctmsmis.yard_block where terminal='$yard' and  block_cpa!='NULL' ORDER BY block ASC";
         $rtnBlockList = $this->bm->dataSelectDb2($query);
         echo json_encode($rtnBlockList);		 
	}
	
	/*function getAllYard()
	{
		 //$yard = $_GET["yard"];
		 $query = "select distinct current_position from ctmsmis.mis_exp_unit
					where  mis_exp_unit.delete_flag='0' and mis_exp_unit.snx_type=2 and current_position!=''";
         $rtnYardList = $this->bm->dataSelect($query);
         echo json_encode($rtnYardList);		 
	}*/
	function getAllBlockYard()
	{
		 //$yard = $_GET["yard"];
		 $query = "select distinct block from ctmsmis.yard_block where block is not null ORDER BY block ASC";
         $rtnBlockList = $this->bm->dataSelectDb2($query);
         echo json_encode($rtnBlockList);		 
	}
	function getAllBlock()
	{
		 //$yard = $_GET["yard"];
		 $query = "select distinct block_cpa as block from ctmsmis.yard_block where block_cpa is not null ORDER BY block_cpa";
         $rtnBlockList = $this->bm->dataSelect($query);
         echo json_encode($rtnBlockList);		 
	}
	
	function getCnfCode()
	{
		$cnf_lic_no= $_GET["cnf_lic_no"];
		//$jval = $_GET["jval"];
		$getCnfNameQuery= "SELECT distinct(ref_bizunit_scoped.name) as name
		FROM inv_unit 
		INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
		LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
		WHERE ref_bizunit_scoped.id LIKE '$cnf_lic_no'";
		$getCnfName = $this->bm->dataSelect($getCnfNameQuery);
		//$getCnfNameValue=$getCnfName[0]['name'];


		//$query = "select  $jval as myval,block
		//from(
		//	select block from ctmsmis.yard_block where terminal='$yard'
		//) as tt";	
		//$rtnBlockList = $this->bm->dataSelect($query);
		echo json_encode($getCnfName);
		//$terminal = $_POST["terminalName"];	   
	}
	
	
	
	function contEventDetails()
	{
		$gkey= $_GET["gkey"];	
		$contHistorySql="SELECT srv_event_types.id, srv_event_types.description,srv_event.placed_by,
		to_char(srv_event.placed_time,'YYYY-MM-DD') AS placed_time,
		srv_event.creator, to_char(srv_event.created,'YYYY-MM-DD') AS created,
		srv_event_field_changes.PRIOR_VALUE, SRV_EVENT_FIELD_CHANGES.NEW_VALUE
		FROM srv_event
		INNER JOIN srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
		INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
		WHERE srv_event.applied_to_gkey='$gkey'";
	    $contHistory = $this->bm->dataSelect($contHistorySql);
        echo json_encode($contHistory);

	}	
	
	

	// Sourav ......Bill Data Start..............
	function getBillInfo()
	{
		    $tarrif_id= $_GET["tarrif_id"];
			$getBillInfoQuery= "select id as tarrif_id,bil_tariffs.description,long_description,bil_tariffs.gl_code,rate_type,amount as tarrif_rate from bil_tariffs
								inner join bil_tariff_rates on
								bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
								where id='$tarrif_id'";
			$getBillInfo = $this->bm->dataSelectDb1($getBillInfoQuery);
			$data['getBillInfo']=$getBillInfo;
	
            echo json_encode($data);		
	}
	
	function checkVerifyNumberExist()
	{
		$strVerifyNum= $_GET["verify_num"];
		$strChkQry="SELECT Count(verify_no) as chkNum from shed_bill_master where verify_no='$strVerifyNum'";
		$rtnChkList = $this->bm->dataSelectDb1($strChkQry);
		if($rtnChkList[0]['chkNum']<1)
		{
			$strChkShedTallyQry="SELECT count(verify_number) as chkNum from shed_tally_info where verify_number='$strVerifyNum'";
			$rtnChkShedTallyList = $this->bm->dataSelectDb1($strChkShedTallyQry);
			if($rtnChkShedTallyList[0]['chkNum']<1)
			{
				$strChkVerifyFcl="SELECT COUNT(*) AS chkNum FROM verify_info_fcl WHERE verify_number='$strVerifyNum'";
				$rtnChkVerifyFcl = $this->bm->dataSelectDb1($strChkVerifyFcl);
				if($rtnChkVerifyFcl[0]['chkNum']<1)
					$data['rtnChkList']=0;  // Not Found
				else
					$data['rtnChkList']=1; // Exist in FCL Verification
			}
			else
			{
				$data['rtnChkList']=1; // Exist in Shed Tally Info
			}
		}
		else
		{
			$data['rtnChkList']=2; // Exist in Shed Bill Master
		}

		echo json_encode($data);
	}
	

	function getBillDetails()
	{
		$strVerifyNum= $_GET["verify_num"];
		$unstfDt= $_GET["unstfDt"];
		
	 	$uptoDt= $_GET["uptoDt"];
		$rpc= $_GET["rpc"];
		$hcCharge= $_GET["hcCharge"];
		$scCharge= $_GET["scCharge"];
		$vatInfo= $_GET["vatInfo"]; 
		$mlwf= $_GET["mlwf"];
		$comm_dt= $_GET["comm_dt"];

		$section = $this->session->userdata('section');
		
		$cont_status="";

		$this->tariffGenerate($strVerifyNum,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge); 

		$str="SELECT  import_rotation,shed_tally_info.cont_number,verify_number,Vessel_Name,
			IFNULL(igm_supplimentary_detail.Line_No, igm_details.Line_No) AS  Line_No,
			IFNULL(igm_supplimentary_detail.BL_No, igm_details.BL_No) AS BL_No,  
			IFNULL(igm_detail_container.cont_gross_weight, igm_sup_detail_container.Cont_gross_weight) AS cont_weight,
			IFNULL(igm_detail_container.cont_size, igm_sup_detail_container.cont_size) AS cont_size,
			IFNULL(igm_detail_container.cont_height, igm_sup_detail_container.cont_height) AS cont_height,
			IFNULL(igm_detail_container.cont_type, igm_sup_detail_container.cont_type) AS cont_type,
			IFNULL(igm_supplimentary_detail.Consignee_code, igm_details.Consignee_code) AS Consignee_code,  
			IFNULL(igm_supplimentary_detail.Consignee_name, igm_details.Consignee_name) AS Consignee_name,  
			IFNULL(igm_supplimentary_detail.Pack_Number, igm_details.Pack_Number) AS Pack_Number,  
			IFNULL(igm_supplimentary_detail.notify_name, igm_details.notify_name) AS notify_name, 
			wr_date,wr_upto_date,cnf_lic_no,be_no, be_date, cnf_name,rcv_pack,loc_first,total_pack,
			igm_supplimentary_detail.Pack_Number,
			verify_other_data.valid_up_to_date,verify_other_data.do_no,verify_other_data.do_date,
			verify_other_data.comm_landing_date,rcv_unit,equipment,
			used_equipment.equipment_id,used_equipment.equipment_charge,used_equipment.equipment_name,used_equipment.remarks,
			bil_tariffs.id AS tariffid,bil_tariffs.gl_code AS glcode,bil_tariff_rates.amount AS tamt
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
			LEFT JOIN igm_sup_detail_container ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
			LEFT JOIN  igm_details ON igm_details.id = shed_tally_info.igm_detail_id
			LEFT JOIN igm_detail_container ON shed_tally_info.igm_detail_id=igm_detail_container.igm_detail_id
			INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			LEFT JOIN appraisement_info ON (appraisement_info.rotation=igm_supplimentary_detail.Import_Rotation_No
			AND appraisement_info.BL_NO=igm_supplimentary_detail.BL_No)
			OR (appraisement_info.rotation=igm_details.Import_Rotation_No AND appraisement_info.BL_NO=igm_details.BL_No)
			LEFT JOIN used_equipment ON appraisement_info.equipment_id=used_equipment.equipment_id
			LEFT JOIN bil_tariffs ON used_equipment.equipment_name=bil_tariffs.description
			LEFT JOIN bil_tariff_rates ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			WHERE verify_number='$strVerifyNum'";
		$rtnBillList = $this->bm->dataSelectDb1($str);
		
		if(count($rtnBillList)==0)
		{
			// $str="select  import_rotation,shed_tally_info.cont_number,verify_number,Vessel_Name,Line_No,igm_details.BL_No,igm_detail_container.Cont_gross_weight as cont_weight,igm_detail_container.cont_size,cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,wr_date,wr_upto_date,cnf_lic_no,be_no,be_date,notify_name,cnf_name,rcv_pack,loc_first,total_pack,
			// igm_details.Pack_Number,igm_details.Consignee_code,igm_details.Consignee_name,
			// verify_other_data.valid_up_to_date,verify_other_data.do_no,verify_other_data.do_date,verify_other_data.comm_landing_date,rcv_unit,equipment
			// from shed_tally_info 
			// inner join igm_details on igm_details.id = shed_tally_info.igm_detail_id
			// inner join igm_detail_container on shed_tally_info.igm_detail_id=igm_detail_container.igm_detail_id
			// inner join igm_masters on igm_details.IGM_id=igm_masters.id
			// left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
			// left join appraisement_info on appraisement_info.rotation=igm_details.Import_Rotation_No and appraisement_info.BL_NO=igm_details.BL_No
			// where verify_number='$strVerifyNum'";
			
			$cont_status="FCL";
			
			$str="SELECT  DISTINCT igm_detail_container.cont_number, igm_details.Import_Rotation_No AS import_rotation, 
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,common_land_date AS comm_landing_date,warfrent_start_date AS wr_date, igm_details.DG_status
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			WHERE verify_info_fcl.verify_number='$strVerifyNum'";					
			$rtnBillList = $this->bm->dataSelectDb1($str);
			
			
			$strTotContainer="select count(*)
			as totCont from (
			SELECT DISTINCT igm_detail_container.cont_number,igm_details.Import_Rotation_No AS import_rotation,
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,'' as comm_landing_date,'' as wr_date
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			WHERE verify_info_fcl.verify_number='$strVerifyNum') as tble";
			$rtnTotContainer = $this->bm->dataSelectDb1($strTotContainer);
			$data['rtnTotContainer']=$rtnTotContainer;
			$total_container = $rtnTotContainer[0]['totCont'];
			$data['total_container']=$total_container;
		/* 	echo json_encode($data);
			return; */

		}
		else
		{
			$cont_status="LCL";
		}
		
		$import_rotation = $rtnBillList[0]['import_rotation'];
		$container = $rtnBillList[0]['cont_number'];
		$blNo= $rtnBillList[0]['BL_No'];
		$comm_dt = $rtnBillList[0]['comm_landing_date'];	// new added line 30-09-2023
				
	
		$arraivalDateQry="select to_char(argo_carrier_visit.ata,'YYYY-MM-DD') as ata from vsl_vessel_visit_details
		inner join argo_carrier_visit 
		on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		where vsl_vessel_visit_details.ib_vyg='$import_rotation'";
		$arraivalDate = $this->bm->dataSelect($arraivalDateQry);
		$arraivalDateValue=$arraivalDate[0]['ATA'];
			
		if($cont_status=="LCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for 
			FROM appraisement_info WHERE rotation='$import_rotation' AND BL_NO='$blNo'";
		}
		else if($cont_status=="FCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for
			FROM appraisement_info_fcl WHERE rotation='$import_rotation' AND BL_NO='$blNo'";
		}
	
		
		$appraisalData = $this->bm->dataSelectDb1($getDataAppraisalQry);
		$appraisalDataCount=count($appraisalData);

		//	$getExRateQuery= "select rate from bil_currency_exchange_rates where DATE(effective_date)= '$arraivalDateValue'";
		
		//	==========
				
		$sql_dollarRateN4 = "SELECT rate AS RTNVALUE FROM BIL_CURRENCY_EXCHANGE_RATES 
							WHERE to_char(EFFECTIVE_DATE,'yyyy-mm-dd') ='".$comm_dt."'
							ORDER BY GKEY DESC FETCH FIRST 1 ROWS ONLY";
		$getExRateValue = $this->bm->dataReturnDb3($sql_dollarRateN4); 
		
		// $this->updateDollarRateIn42($arraivalDateValue);

		// Update Dollar Rate in N4 -- starts

		$login_id = $this->session->userdata('login_id');
		$sql_DollarDate= "SELECT DATE('".$comm_dt."') AS rtnValue";		
		$dollarDate = $this->bm->dataReturnDb1($sql_DollarDate);			
		// get dollar rate from N4
		$sql_dollarRateN4 = "SELECT rate AS rtnValue FROM BIL_CURRENCY_EXCHANGE_RATES 
							WHERE to_char(EFFECTIVE_DATE,'yyyy-mm-dd') ='".$dollarDate ."'
							ORDER BY GKEY DESC FETCH FIRST 1 ROWS ONLY";
		$dollarRateN4 = $this->bm->dataReturnDb3($sql_dollarRateN4);
		
		if($dollarRateN4 == null)
		{
			echo "Please update dollar rate in n4 for : <strong>".$comm_dt."</strong>";
			die(); // or return;
		}
		else
		{
			// check if dollar is in pcs for that date
			$sql_cntPCSDollarRate = "SELECT COUNT(*) AS rtnValue
									FROM cchaportdb.bil_currency_exchange_rates
									WHERE DATE(effective_date)=DATE('$comm_dt')";
			$cntPCSDollarRate = $this->bm->dataReturnDb1($sql_cntPCSDollarRate);			
			if($cntPCSDollarRate == 0)		// if no, insert
			{
				$sql_insertDollarRate = "INSERT INTO bil_currency_exchange_rates(rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator,currency_gkey)
				VALUES('$dollarRateN4','Value from N4',DATE('$comm_dt'),'2','1',NOW(),'$login_id','1')";
				$this->bm->dataInsertDB1($sql_insertDollarRate);
			}
			else		// if yes, update
			{			
				$sql_updateDollarRate = "UPDATE bil_currency_exchange_rates
										SET rate='$dollarRateN4',notes='Update from N4',changed=NOW(),changer='$login_id'
										WHERE effective_date=DATE('$comm_dt')";
				$this->bm->dataUpdateDb1($sql_updateDollarRate);
			}
		}

		// Update Dollar Rate in N4 -- ends
		
		// $sql_dollarRate = "SELECT rate FROM bil_currency_exchange_rates 
		// 					WHERE to_char(effective_date,'yyyy-mm-dd')='$arraivalDateValue' 
		// 					ORDER BY gkey DESC fetch first 1 rows only";					
		// $rslt_dollarRate = $this->bm->dataSelectDb3($sql_dollarRate);
		// for($i=0;$i<count($rslt_dollarRate);$i++)
		// {
		// 	$getExRateValue = $rslt_dollarRate[$i]['RATE'];
		// }

		// echo "Rate: $getExRateValue";
		// return;

		// ===========
	 	
		
 		$getExRateQuery= "SELECT IFNULL((SELECT rate FROM bil_currency_exchange_rates WHERE DATE(effective_date)= '$comm_dt'),(SELECT rate FROM bil_currency_exchange_rates ORDER BY gkey DESC LIMIT 1)) AS rate";
		$getExRate = $this->bm->dataSelectDb1($getExRateQuery);
		$getExRateValue=$getExRate[0]['rate'];

		// echo "Rate: $getExRateValue";
		// return;
		
			/* echo json_encode($getExRateQuery);
			return; */
		
		/**********************Auto Bill Start*******************************/
		//if($unstfDt=="")
		/*{
		$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(verify_other_data.valid_up_to_date,DATE_ADD(wr_date,INTERVAL 4 day)),0) as dif from shed_tally_info
		left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
		where shed_tally_info.verify_number='$strVerifyNum'";
		}
		else
		{*/
	
		// 2020-04-06 - start
		// $getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif from shed_tally_info
		// left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
		// where shed_tally_info.verify_number='$strVerifyNum'";
		
		$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 3 DAY)),0) AS dif";
		// 2020-04-06 - start
	
		/*}*/
		/*$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
		from sparcsn4.inv_unit
		inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		where sparcsn4.inv_unit.id='$container' and sparcsn4.inv_unit.category='STRGE'";*/
		
		// 2020-04-06 - start
		$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
		$dateDiffValue=$getDateDiff[0]['dif'];
		
		// 2020-04-06 - end
		
		//$dateDiffValue=15;					
					
		// 2020-04-06 - start	
		if($cont_status=="LCL")
		{
			$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
			ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight /1000)) as Qty,
			igm_sup_detail_container.Cont_gross_weight as cont_weight,
			(case 
				when 
					tarrif_id like '%1ST%'
				then 
					if($dateDiffValue<7,$dateDiffValue,7)
				else 
					case 
						when 
							tarrif_id like '%2ND%'
						then 
							if($dateDiffValue<14,$dateDiffValue-7,7)
						else  
							if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
					end
			end) as qday,
			(select tarrif_rate*Qty*qday) as amt,
			(select if($vatInfo='0',0,(select amt*15/100))) as vatTK
			from shed_bill_tarrif
			inner join bil_tariffs on 
			shed_bill_tarrif.tarrif_id= bil_tariffs.id
			inner join bil_tariff_rates on
			bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			inner join shed_tally_info on
			shed_tally_info.verify_number=shed_bill_tarrif.verify_no
			inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id = shed_tally_info.igm_sup_detail_id
			inner join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
			where verify_no='$strVerifyNum'";
		}
		else if($cont_status=="FCL")
		{						//amt
			$qry="SELECT DISTINCT bil_tariffs.gl_code AS gl_code,bil_tariff_rates.currency_gkey,verify_number,tarrif_id,
			IF(currency_gkey='2',CONCAT('$',bil_tariffs.description),bil_tariffs.description) AS description,
			IFNULL(bil_tariff_rates.amount,0) AS tarrif_rate,
			certify_info_fcl.update_ton,
			(CASE
				WHEN tarrif_id='HOSTING_CHARGES'
				THEN hosting_charge
				ELSE
					CASE
					WHEN tarrif_id='WEIGHMENT_CHARGE'
					THEN scale_for
					ELSE
						CASE
							WHEN tarrif_id='REPAIRING_CHARGE'
							THEN carpainter_use
							ELSE
								CASE
									WHEN tarrif_id='FLT_1_5_TON'
									THEN 
							(SELECT COUNT(*) FROM igm_details INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id WHERE verify_number='$strVerifyNum')
									ELSE 
										CASE
											WHEN tarrif_id='FLT_6_20_TON'
											THEN 
								(SELECT COUNT(*) FROM igm_details INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id WHERE verify_number='$strVerifyNum')
											ELSE
												CASE
													WHEN tarrif_id='FLT_21_50_TON'
													THEN 
									(SELECT COUNT(*) FROM igm_details INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE verify_number='$strVerifyNum')
												   -- ELSE add remaining charges
												END
										END
								END
						END
				END		
			END) AS Qty,
			'1' AS qday,
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*84.96),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF($vatInfo=0,0,(SELECT amt*15/100))) AS vatTK
			FROM verify_info_fcl
			INNER JOIN shed_bill_tarrif ON shed_bill_tarrif.verify_no=verify_info_fcl.verify_number
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
			INNER JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No
			AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE verify_info_fcl.verify_number='$strVerifyNum' AND (bil_tariffs.gl_code IN('503000N','204002N','309000') OR (tarrif_id LIKE 'FLT_%' OR tarrif_id LIKE 'CRANE_%'))

			UNION ALL

			SELECT DISTINCT bil_tariffs.gl_code,currency_gkey,verify_number,shed_bill_tarrif.tarrif_id,bil_tariffs.description,
			IFNULL(IF(igm_details.DG_status='DG' AND bil_tariffs.description LIKE '%STORAGE%', bil_tariff_rates.amount*4, bil_tariff_rates.amount),0) AS tarrif_rate,
			-- igm_details.id AS igm_dtls_id,
			certify_info_fcl.update_ton,
			-- appraisement_info_fcl.gkey AS appInfoFCL_gkey,
			-- cont_size,
			(CASE
				WHEN (bil_tariffs.gl_code='501001' OR bil_tariffs.gl_code='505001')		-- 20
				THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
				igm_detail_container.cont_height	 
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl WHERE cont_size='20')
				ELSE
				CASE
					WHEN (bil_tariffs.gl_code='501002' OR bil_tariffs.gl_code='505002')		-- 40 8.6
					THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
						igm_detail_container.cont_height	 
						FROM igm_details
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
						WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl WHERE cont_size='40' AND cont_height='8.6')
					ELSE
						CASE
							WHEN (bil_tariffs.gl_code='501003' OR bil_tariffs.gl_code='505006')		-- 40 9.6 45
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
								WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl 
								WHERE cont_size='45' OR (cont_size='40' AND cont_height='9.6'))
								ELSE -- slab
						CASE	-- slab 20 
							WHEN (bil_tariffs.gl_code='403017' OR bil_tariffs.gl_code='403019' OR bil_tariffs.gl_code='403021')
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
								igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
								WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl 
								WHERE cont_size='20')
							ELSE
								CASE 	-- slab 40
									WHEN (bil_tariffs.gl_code='403023' OR bil_tariffs.gl_code='403025' OR bil_tariffs.gl_code='403027')
									THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
										igm_detail_container.cont_size,
										igm_detail_container.cont_height	 
										FROM igm_details
										INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
										WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl 
										WHERE cont_size='40')
									ELSE
										CASE	-- slab 45
											WHEN (bil_tariffs.gl_code='403029' OR bil_tariffs.gl_code='403031' OR bil_tariffs.gl_code='403033')
											THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
												igm_detail_container.cont_size,
												igm_detail_container.cont_height	 
												FROM igm_details
												INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
												LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
												WHERE verify_info_fcl.verify_number='$strVerifyNum') AS tbl 
												WHERE cont_size='45')
										END
								END			                
						END
					END   
					END	       
			END) AS Qty,

			(CASE 
				WHEN 
					tarrif_id LIKE '%1ST%'
				THEN 
					IF($dateDiffValue<=7,$dateDiffValue,7)
				ELSE 
					CASE 
						WHEN 
							tarrif_id LIKE '%2ND%'
						THEN 
							IF($dateDiffValue<=20,$dateDiffValue-7,7)
						ELSE  
							IF(tarrif_id LIKE '%3RD%',$dateDiffValue-20,1)							
					END
			END) AS qday,		
			
			-- New amt Calculation for DG container -- Sumon --
			-- IF(currency_gkey='2',IF(igm_details.DG_status='DG' AND bil_tariffs.description LIKE '%STORAGE%',(SELECT tarrif_rate*Qty*qday*$getExRateValue*4), (SELECT tarrif_rate*Qty*qday*$getExRateValue)),  IF(igm_details.DG_status='DG' AND bil_tariffs.description LIKE '%STORAGE%',(SELECT tarrif_rate*Qty*qday*4), (SELECT tarrif_rate*Qty*qday))) AS amt,
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*$getExRateValue),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF($vatInfo='0',0,(SELECT amt*15/100))) AS vatTK
			FROM igm_details 
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN shed_bill_tarrif ON shed_bill_tarrif.verify_no=verify_info_fcl.verify_number
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			WHERE verify_info_fcl.verify_number='$strVerifyNum' AND 
			bil_tariffs.gl_code IN('501001','501002','501003','505001','505002','505006','403017','403019','403021','403023','403025','403027','403029','403031','403033')";
		}
		
		// //echo $qry; vatTK
		 $chargeList = $this->bm->dataSelectDb1($qry);
		// 2020-04-06 - end				
		/**********************Auto Bill End*******************************/
		
		// 2020-04-06 - start
		// $qryTotalBill= "select SUM(amt) as totAmount,sum(vatTK) as totVat ,'0.0' as totMlwf from (select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
		// ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight/1000)) as Qty,
		// igm_sup_detail_container.Cont_gross_weight,
		// (case 
			// when 
				// tarrif_id like '%1ST%'
			// then 
				// if($dateDiffValue<7,$dateDiffValue,7)
			// else 
				// case 
					// when 
						// tarrif_id like '%2ND%'
					// then 
						// if($dateDiffValue<14,$dateDiffValue-7,7)
					// else  
						// if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
				// end
		// end) as qday,
		// (select tarrif_rate*Qty*qday) as amt,
		// (select if($vatInfo='0',0,(select amt*15/100))) as vatTK
		// from shed_bill_tarrif
		// inner join bil_tariffs on 
		// shed_bill_tarrif.tarrif_id= bil_tariffs.id
		// inner join bil_tariff_rates on
		// bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
		// inner join shed_tally_info on
		// shed_tally_info.verify_number=shed_bill_tarrif.verify_no
		// inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id = shed_tally_info.igm_sup_detail_id
		// inner join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
		// where verify_no='$strVerifyNum') tbl";
		// //echo $qry;
		// $totalBillList = $this->bm->dataSelectDb1($qryTotalBill);
		// 2020-04-06 - end
		//}
		$oneStopPoint="select distinct unit_no from assigned_unit where rotation='$import_rotation'";
		$oneStopList = $this->bm->dataSelectDb1($oneStopPoint);
		$oneStop=$oneStopList[0]['unit_no'];
				
		//	$data['totalBillList']=$totalBillList;	// 2020-04-06
		if($appraisalDataCount==0)
		{
			$data['appraisalData']=null; 
		}
		else{
			$data['appraisalData']=$appraisalData; 
		}
		
		$data['rtnBillList']=$rtnBillList;
		$data['chargeList']=$chargeList;		// 2020-04-06
		$data['arraivalDateValue']=$arraivalDateValue;
		$data['getExRateValue']=$getExRateValue;
		//$data['sectionValue']=$this->session->userdata('section');
		$data['sectionValue']=$oneStop;
		$data['unstfDt']=$unstfDt;
		
		$data['cont_status']=$cont_status;
		
		$data['uptoDt']=$uptoDt;
		$data['rpc']=$rpc;
		$data['hcCharge']=$hcCharge;
		$data['scCharge']=$scCharge;
		
		$data['dateDiffValue']=$dateDiffValue;
		echo json_encode($data);
		//$terminal = $_POST["terminalName"];	   
	}
	
	
	
	function updateDollarRateIn42($effectiveDate)
	{
		$login_id = $this->session->userdata('login_id');
		$sql_DollarDate= "SELECT DATE('".$effectiveDate."') AS rtnValue";		
		$dollarDate = $this->bm->dataReturnDb1($sql_DollarDate);			
		// get dollar rate from N4
		$sql_dollarRateN4 = "SELECT rate AS rtnValue FROM BIL_CURRENCY_EXCHANGE_RATES 
							WHERE to_char(EFFECTIVE_DATE,'yyyy-mm-dd') ='".$dollarDate ."'
							ORDER BY GKEY DESC FETCH FIRST 1 ROWS ONLY";
		$dollarRateN4 = $this->bm->dataReturnDb3($sql_dollarRateN4);
		
		if($dollarRateN4 == null)
		{
			echo "Please update dollar rate in n4 for : <strong>".$effectiveDate."</strong>";
			die(); // or return;
		}
		else
		{
			// check if dollar is in pcs for that date
			$sql_cntPCSDollarRate = "SELECT COUNT(*) AS rtnValue
									FROM cchaportdb.bil_currency_exchange_rates
									WHERE DATE(effective_date)=DATE('$effectiveDate')";
			$cntPCSDollarRate = $this->bm->dataReturnDb1($sql_cntPCSDollarRate);			
			if($cntPCSDollarRate == 0)		// if no, insert
			{
				$sql_insertDollarRate = "INSERT INTO bil_currency_exchange_rates(rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator,currency_gkey)
				VALUES('$dollarRateN4','Value from N4',DATE('$effectiveDate'),'2','1',NOW(),'$login_id','1')";
				$this->bm->dataInsertDB1($sql_insertDollarRate);
			}
			else		// if yes, update
			{			
				$sql_updateDollarRate = "UPDATE bil_currency_exchange_rates
										SET rate='$dollarRateN4',notes='Update from N4',changed=NOW(),changer='$login_id'
										WHERE effective_date=DATE('$effectiveDate')";
				$this->bm->dataUpdateDb1($sql_updateDollarRate);
			}
		}	
	}


	
	// ------Sumon Traiff Generate ----
	
	function tariffGenerate($billVerify,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge)
	{
		/*$qry="select igm_sup_detail_container.cont_status,loc_first,shed_tally_info.cont_number	 
				from  igm_supplimentary_detail
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				where shed_tally_info.verify_number='$billVerify'
				group by igm_sup_detail_container.id";*/
		$qry="select igm_sup_detail_container.cont_status,rcv_pack,loc_first,shed_tally_info.cont_number,equipment,appraisement_info.equipment_id,used_equipment.equipment_name	 
		from  igm_supplimentary_detail
		inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		left join appraisement_info on igm_supplimentary_detail.Import_Rotation_No=appraisement_info.rotation and igm_supplimentary_detail.BL_No=appraisement_info.BL_NO
		left join used_equipment on used_equipment.equipment_id=appraisement_info.equipment_id
		where shed_tally_info.verify_number='$billVerify'
		group by igm_sup_detail_container.id";
		
		$conStatus = $this->bm->dataSelectDb1($qry); 
		//echo $conStatus[0]['cont_status']; 
		
		if(count($conStatus)==0)
		{
			$qry="SELECT igm_detail_container.cont_status,cont_size,cont_height,igm_detail_container.cont_number,equipment,appraisement_info_fcl.equipment_id,used_equipment.equipment_name	 
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON igm_details.Import_Rotation_No=appraisement_info_fcl.rotation AND igm_details.BL_No=appraisement_info_fcl.BL_NO
			LEFT JOIN used_equipment ON used_equipment.equipment_id=appraisement_info_fcl.equipment_id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE verify_info_fcl.verify_number='$billVerify'
			GROUP BY igm_detail_container.id";
			$conStatus = $this->bm->dataSelectDb1($qry);
		}
		
		$cont_status = $conStatus[0]['cont_status'];
		if($cont_status=='LCL')
		{
			$loc_first = $conStatus[0]['loc_first'];
			$rcv_pack = $conStatus[0]['rcv_pack'];
		}
		$cont_number = $conStatus[0]['cont_number'];
		//echo "Starus==".$loc_first;
		$equip_charge = $conStatus[0]['equipment'];
		$equip_id = $conStatus[0]['equipment_id'];
		$equip_name = $conStatus[0]['equipment_name'];
		if($cont_status=="LCL")
		{
			$strRiverDues="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',1)),1,1)";
			$statRiverDues=$this->bm->dataInsertDB1($strRiverDues);
				
			$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',2)),1,2)";
			$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
			
			if($hcCharge!=0)
			{
				$strHostingCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',3)),1,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)
			{
				$strScaleCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',12)),1,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)
			{				
				$strWeightmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',10)),1,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			if($loc_first>0)
			{
				/********************Add 4 Days*************************/
				/*if($unstfDt=="")
				{
					$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(valid_up_to_date,DATE_ADD(wr_date,INTERVAL 4 day)),0) as dif from shed_tally_info
										left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
										where shed_tally_info.verify_number='$billVerify'";
				}
				else
				{*/
					$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif from shed_tally_info
										left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
										where shed_tally_info.verify_number='$billVerify'";
				/*}*/
				
				$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
				
				$dateDiffValue=$getDateDiff[0]['dif'];
				if($dateDiffValue>14)
				{
					//9
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',9)),1,9)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
					//7
					$strStuffUnStuff2="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',7)),1,7)";
					$statStuffUnStuff2=$this->bm->dataInsertDB1($strStuffUnStuff2);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
			//else if($loc_first<=0)
			//{
				/********************Add 4 Days*************************/
			if($rcv_pack>0)
			{
				if($unstfDt=="")
				{
					$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif from shed_tally_info
										left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
										where shed_tally_info.verify_number='$billVerify'";
				}
				else
				{
					$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif from shed_tally_info
										left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
										where shed_tally_info.verify_number='$billVerify'";
				}
				$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
				$dateDiffValue=$getDateDiff[0]['dif'];
				//$dateDiffValue = 18;
				if($dateDiffValue>14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//6
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',6)),1,6)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
				//echo "Diff :  ".$getDateDiff[0]['dif'];
			//}
			$eqipmentStr="SELECT equip_for_assignment.equip_id 
						FROM shed_tally_info 
						INNER JOIN assignment_request_data ON assignment_request_data.igm_detail_id=shed_tally_info.igm_detail_id 
														OR assignment_request_data.igm_sup_dtl_id=shed_tally_info.igm_sup_detail_id
						INNER JOIN equip_for_assignment ON equip_for_assignment.assign_id=assignment_request_data.id
						WHERE shed_tally_info.verify_number='$billVerify'";

			$eqipmentRslt = $this->bm->dataSelectDb1($eqipmentStr);
			for($i=0; $i<count($eqipmentRslt); $i++)
			{
				$equip_id=$eqipmentRslt[$i]['equip_id'];
				if($equip_id==1)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',13)),1,13)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==2)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',14)),1,14)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==3)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',15)),1,15)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==4)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',16)),1,16)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==5)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif('$billVerify',17)),1,17)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else
				{
					$strDelUsedEquipmentCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type in (13,14,15,16,17)";
					$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
				}
			}
		}
		else if($cont_status=="FCL")			// --------------------- FCL ---------------------
		{
			//echo '11111111111';
			// $cont_size = $conStatus[0]['cont_size'];
			// $cont_height = $conStatus[0]['cont_height'];
			
			// use loop to check size and height. then check the current chargeList query.
			
			$riverDues20_cnt=0;
			$riverDues40_cnt=0;
			$riverDues40HQ_cnt=0;
			$riverDues45_cnt=0;
			
			$liftOn20_cnt=0;
			$liftOn40_cnt=0;
			$liftOn40HQ_cnt=0;
			$liftOn45_cnt=0;
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($cont_size==20)			// container wise separate
				{
					if($riverDues20_cnt==0)
					{
						// RIVER_DUES_FCL_20
						$strRiverDues20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',18)),3,18)";
						$statRiverDues20=$this->bm->dataInsertDB1($strRiverDues20);
						$riverDues20_cnt++;
					}
					
					if($liftOn20_cnt==0)
					{
						// LIFT_ON_FCL_20
						$strLiftOn20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',21)),3,21)";
						$statLiftOn20=$this->bm->dataInsertDB1($strLiftOn20);
						$liftOn20_cnt++;					
					}
				}
				else if($cont_size==40 and $cont_height=="8.6")		// container wise separate
				{
					if($riverDues40_cnt==0)
					{
						// RIVER_DUES_FCL_40
						$strRiverDues40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',19)),3,19)";
						$statRiverDues40=$this->bm->dataInsertDB1($strRiverDues40);
						$riverDues40_cnt++;
					}
					
					if($liftOn40_cnt==0)
					{
						// LIFT_ON_FCL_40
						$strLiftOn40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',22)),3,22)";
						$strLiftOn40=$this->bm->dataInsertDB1($strLiftOn40);
						$liftOn40_cnt++;
					}
				}
				else if($cont_size==40 and $cont_height=="9.6")		// container wise separate
				{
					if($riverDues40HQ_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues40HQ_cnt++;
					}
					
					if($liftOn40HQ_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn40HQ_cnt++;
					}
				}
				else if($cont_size==45)		// container wise separate
				{
					if($riverDues45_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues45_cnt++;
					}
					
					if($liftOn45_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn45_cnt++;
					}
				}
			}
												
			// hc, rpc, sc start
			if($hcCharge!=0)		// one tarrif for total bill
			{
				// HOSTING_CHARGES
				$strHostingCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',3)),3,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)				// one tarrif for total bill
			{
				// REPAIRING_CHARGE
				$strScaleCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',12)),3,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)		// one tarrif for total bill	- WEIGHMENT CHARGE
			{				
				// WEIGHMENT_CHARGE
				$strWeightmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
					values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',10)),3,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			// hc, rpc, sc end
			
			// equipment tarrif start	
			if($equip_id==1)  //USED EQUIPMENT				// container wise separate
			{
				// FLT_1_5_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',13)),3,13)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==2)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_6_20_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',14)),3,14)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==3)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_21_50_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',15)),3,15)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==4)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_1_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',16)),3,16)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==5)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_ABOVE_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',17)),3,17)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else
			{
				$strDelUsedEquipmentCharge="delete from shed_bill_tarrif where verify_no='$billVerify' and event_type in (13,14,15,16,17)";
				$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
			}
			
			// slab - start
			$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif";
			
			$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
			$dateDiffValue=$getDateDiff[0]['dif'];
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($dateDiffValue>20)		// over 20 days				// container wise separate
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
						
						// 26 - over 20
						$strStorage3rdSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',26)),3,26)";
						$statStorage3rdSlab20=$this->bm->dataInsertDB1($strStorage3rdSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
						
						// 29 - over 20
						$strStorage3rdSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',29)),3,29)";
						$statStorage3rdSlab40=$this->bm->dataInsertDB1($strStorage3rdSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
						
						// 32 - over 20
						$strStorage3rdSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',32)),3,32)";
						$statStorage3rdSlab45=$this->bm->dataInsertDB1($strStorage3rdSlab45);
					}
					// else if($cont_size==40 and $cont_height==9.6)
					// {
						// // 33 - 1-7
						// $strStorage1stSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',33)),3,33)";
						// $statStorage1stSlab40HQ=$this->bm->dataInsertDB1($strStorage1stSlab40HQ);
						
						// // 34 - 8-20
						// $strStorage2ndSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',34)),3,34)";
						// $statStorage2ndSlab40HQ=$this->bm->dataInsertDB1($strStorage2ndSlab40HQ);
						
						// //35 - over 20
						// $strStorage3rdSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',35)),3,35)";
						// $statStorage3rdSlab40HQ=$this->bm->dataInsertDB1($strStorage3rdSlab40HQ);
					// }
				}
				else if($dateDiffValue>7 and $dateDiffValue<=20)		// 8 to 20 days
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
					}
					// else if($cont_size==40 and $cont_height==9.6)
					// {
						// // 33 - 1-7
						// $strStorage1stSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',33)),3,33)";
						// $statStorage1stSlab40HQ=$this->bm->dataInsertDB1($strStorage1stSlab40HQ);
						
						// // 34 - 8-20
						// $strStorage2ndSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',34)),3,34)";
						// $statStorage2ndSlab40HQ=$this->bm->dataInsertDB1($strStorage2ndSlab40HQ);
					// }
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)			// 1 to 7 days
				{	
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
					}
					// else if($cont_size==40 and $cont_height==9.6)
					// {
						// // 33 - 1-7
						// $strStorage1stSlab40HQ="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						// values('$billVerify',(select get_shed_bill_tarrif_FCL('$billVerify',33)),3,33)";
						// $statStorage1stSlab40HQ=$this->bm->dataInsertDB1($strStorage1stSlab40HQ);
					// }				
				}
			}		// for($i=0;$i<count($conStatus);$i++) - loop ends
			
			
			// slab - end
		}
	}
	
	
	function getDataFromShedBill()
	{
			$strVerifyNum= $_GET["verify_num"];
			
			$chkDataExist="select count(bill_no) as countBill from shed_bill_master where verify_no='$strVerifyNum'";
			$rtnExistData = $this->bm->dataSelectDb1($chkDataExist);
			$dataStat=$rtnExistData[0]['countBill'];
			if($dataStat>0)
			{
					$shedBillMasterQry="select concat(right(YEAR(bill_date),2),'/',
								concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
								if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,
								verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,shed_bill_master.import_rotation,vessel_name,cl_date,bl_no,shed_bill_master.wr_date,
								shed_bill_master.wr_upto_date as valid_up_to_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,
								shed_bill_master.cont_size,shed_bill_master.cont_height,bill_rcv_stat,shed_bill_master.cont_weight,da_bill_no,bill_for,less,part_bl,shed_bill_master.remarks,total_port,total_vat,total_mlwf,less_amt_port,
								less_amt_vat,grand_total,cont_type,rcv_pack,loc_first,extra_movement from shed_bill_master 
								left join shed_tally_info on verify_number=shed_bill_master.verify_no
								inner join igm_sup_detail_container on shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
								where verify_no='$strVerifyNum'";
					$shedBillMasterList = $this->bm->dataSelectDb1($shedBillMasterQry);
					
					
					$shedBillDetailQry="select shed_bill_details.id,bil_tariffs.id as tarrif_id,verify_no,bill_no,shed_bill_details.gl_code,shed_bill_details.description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK 
										from shed_bill_details 
										left join bil_tariffs on bil_tariffs.gl_code=shed_bill_details.gl_code
										where verify_no='$strVerifyNum' and bill_no in (select MAX(bill_no) from shed_bill_details where verify_no='$strVerifyNum')";
										
					$shedBillDetailList = $this->bm->dataSelectDb1($shedBillDetailQry);
					
					$data['shedBillDetailList']=$shedBillDetailList;
					$data['shedBillMasterList']=$shedBillMasterList;
					$data['dataExist']=1;
			}
			else
			{
				$data['dataExist']=0;
				$data['shedBillDetailList']="";
				$data['shedBillMasterList']="";
			}
			
			
            echo json_encode($data);
	}
	// Sourav ......Bill Data End..............
			 
	function getDeliveryByVerifyInfo()
	{	
		$verify_num = $_GET['verify_num'];
		
		$sql_chkContStatus="SELECT IFNULL((SELECT cont_status
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
						INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
						WHERE verify_info_fcl.verify_number='$verify_num' LIMIT 1),'LCL') AS cont_status";
		
		$rslt_chkContStatus=$this->bm->dataSelectDb1($sql_chkContStatus);
		$contStatus=$rslt_chkContStatus[0]['cont_status'];
				
		if($contStatus=="FCL")
		{
			$query = "SELECT igm_details.id AS igmDtlId,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,igm_details.Pack_Marks_Number,igm_details.Description_of_Goods,verify_info_fcl.verify_number,certify_info_fcl.cnf_name,certify_info_fcl.be_no,certify_info_fcl.be_date,igm_details.Consignee_name,igm_details.Pack_Description,igm_details.BL_No AS mloline,igm_details.BL_No AS ffwline,
			(SELECT mlocode
			FROM igm_details 
			WHERE igm_details.id=igmDtlId) AS mlocode,
			igm_details.Pack_Number,
			(SELECT igm_details.Pack_Number-IFNULL((SELECT SUM(delv_pack) AS delv_pack FROM do_information WHERE verify_no='$verify_num' AND delv_status=1),0)) AS bal_pack,igm_details.Notify_name,igm_details.Notify_address,reg_no AS be_no,reg_date AS be_date,sad_info.dec_name AS cnf_name
			FROM igm_details
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN sad_info ON sad_info.reg_no=verify_info_fcl.be_no
			WHERE verify_info_fcl.verify_number='$verify_num'";
		}
		else
		{
			$query = "SELECT * FROM (SELECT igm_supplimentary_detail.id,igm_masters.Vessel_Name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,IFNULL(shed_tally_info.verify_number,0) AS verify_number,verify_other_data.cnf_name,verify_other_data.be_no,verify_other_data.be_date,igm_details.Consignee_name,igm_supplimentary_detail.Pack_Description,igm_details.BL_No AS mloline,igm_supplimentary_detail.BL_No AS ffwline,(SELECT mlocode FROM igm_details 
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
		}
		           
		$deliveryList = $this->bm->dataSelectDb1($query);
        echo json_encode($deliveryList);	 
	}
	
	function getTruck()
	{
		$verify_num = $_GET['verify_num'];
		
		$sql_chkContStatus = "SELECT COUNT(*) AS rtnValue FROM verify_info_fcl WHERE verify_number='$verify_num'";
		$chkVerify = $this->bm->dataReturnDb1($sql_chkContStatus);
		// $sql_chkContStatus="SELECT IFNULL((SELECT cont_status
		
						// FROM igm_detail_container
						// INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
						// INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
						// WHERE verify_info_fcl.verify_number='$verify_num' LIMIT 1),'LCL') AS cont_status";
		
		// $rslt_chkContStatus=$this->bm->dataSelectDb1($sql_chkContStatus);
		// $contStatus=$rslt_chkContStatus[0]['cont_status'];
		if($chkVerify==1)
		{
			$query = "SELECT truck_id FROM do_truck_details_entry WHERE do_truck_details_entry.verify_number='$verify_num'";
		}
		else
		{
			$query = "SELECT truck_id FROM do_information WHERE verify_no='$verify_num' AND delv_status='0'";
		}
		
           
		$rtntrkno = $this->bm->dataSelectDb1($query);  
        echo json_encode($rtntrkno);	 
	}
	
	/* function getbalance()
	{
		$truck_id=$_GET['truck_id'];
		$verify_num=$_GET['verify_num'];
		
	//	$query = "SELECT delv_pack,truck_id,gate_no FROM do_information WHERE truck_id='$truck_id'";
		$packQuery = "SELECT IFNULL(SUM(delv_pack),0) AS delv_pack FROM do_information WHERE delv_status=1 AND verify_no='$verify_num'";		
		$rtndelvPack = $this->bm->dataSelectDb1($packQuery); 
        $data['rtndelvPack']=$rtndelvPack;	
		
		$query = "SELECT gate_no,truck_id FROM do_information WHERE delv_status=0 AND verify_no='$verify_num' and truck_id='$truck_id'";		
		$rtndelv = $this->bm->dataSelectDb1($query);  
		$data['rtndelv']=$rtndelv;	
		
        echo json_encode($data);
	} */
	
	function getbalance()
	{
		$truck_id=$_GET['truck_id'];
	//	$verify_num=$_GET['verify_num'];
		
	//	$query = "SELECT delv_pack,truck_id,gate_no FROM do_information WHERE truck_id='$truck_id'";
		/* $packQuery = "SELECT IFNULL(SUM(delv_pack),0) AS delv_pack FROM do_information WHERE delv_status=1 AND verify_no='$verify_num'";		
		$rtndelvPack = $this->bm->dataSelectDb1($packQuery); 
        $data['rtndelvPack']=$rtndelvPack;	 */
		$queryCnt = "SELECT COUNT(*) AS rtnValue FROM do_information WHERE delv_status=0 AND truck_id='$truck_id'";
		$resCnt = $this->bm->dataReturnDb1($queryCnt);
		$query="";
		if($resCnt!=0)
		{
			$query = "SELECT gate_no,truck_id,delv_pack FROM do_information WHERE delv_status=0 AND truck_id='$truck_id'";
		}
		else
		{
			$query = "SELECT do_truck_details_entry.gate_no,do_truck_details_entry.truck_id,do_truck_details_entry.delv_pack 
					FROM do_truck_details_entry 
					WHERE do_truck_details_entry.truck_id='$truck_id' AND do_truck_details_entry.gate_out_status='0'";
		}
		// echo 	$query;
		$rtndelv = $this->bm->dataSelectDb1($query);  
		
	//	$data['rtndelv']=$rtndelv;	
		
        echo json_encode($rtndelv);
	}
	
	function ExchangeDoneStatusChange()
	{
		
		$login_id = $this->session->userdata('login_id');
		$org_Type_id = $this->session->userdata('org_Type_id');
		
		//$rotation=str_replace("_","/",$this->uri->segment(3));
		//$container=str_replace("_","/",$this->uri->segment(4));
		$rotation= $_GET["rotation"];
		$container= $_GET["container"];
		//$stat=0;
		// $str = "update shed_tally_info set exchange_done_status=1
		// where import_rotation='$rotation' and cont_number='$container' ";
		// $stat = $this->bm->dataInsertDB1($str);
		
		if($org_Type_id==59)			//CPA Shed Users
		{
			$sql_updateStatus = "UPDATE shed_tally_info
			SET cpa_exchange_done_status = '1',cpa_exchange_done_by = '$login_id',cpa_exchange_done_at = NOW()
			WHERE import_rotation='$rotation' AND cont_number='$container'";
		}
		else if($org_Type_id==30)		// Berth Operator
		{
			$sql_updateStatus = "UPDATE shed_tally_info
			SET berth_exchange_done_status = '1',berth_exchange_done_by = '$login_id',berth_exchange_done_at = NOW()
			WHERE import_rotation='$rotation' AND cont_number='$container'";
		}
		else if($org_Type_id==2)		// C&F
		{
			$sql_updateStatus = "UPDATE shed_tally_info
			SET ff_exchange_done_status = '1',ff_exchange_done_by = '$login_id',ff_exchange_done_at = NOW()
			WHERE import_rotation='$rotation' AND cont_number='$container'";
		}
		else if($org_Type_id==4)		//FF
		{
			$sql_updateStatus = "UPDATE shed_tally_info
			SET ff_exchange_done_status = '1',ff_exchange_done_by = '$login_id',ff_exchange_done_at = NOW()
			WHERE import_rotation='$rotation' AND cont_number='$container'";
		}
		else if($org_Type_id==5)		//cpacs1
		{
			$sql_updateStatus = "UPDATE shed_tally_info
			SET cpa_exchange_done_status = '1',cpa_exchange_done_by = '$login_id',cpa_exchange_done_at = NOW()
			WHERE import_rotation='$rotation' AND cont_number='$container'";
		}
		
		
		//$stat=1;
		if($org_Type_id==59 or $org_Type_id==30 or $org_Type_id==2 or $org_Type_id==4 or $org_Type_id==5)
		{
			$stat = $this->bm->dataUpdateDB1($sql_updateStatus);
		}
		else
		{
			$stat=0;
		}
		
		if($stat>0)
		{
			$data['stat']="1";			
		}
		else
		{
			$data['stat']="0";			
		}		
		
		echo json_encode($data);
	}
	
	function getEquipmentCharge()
	{
		$equipID= $_GET["equipID"];
		$getEquipChargeQuery= "select equipment_charge from used_equipment where equipment_id='$equipID'";
		$getEquipCharge = $this->bm->dataSelectDb1($getEquipChargeQuery);
        echo json_encode($getEquipCharge);   
	}
	// UPLOAD SIGNATURE START//
		function uploadSignatureSrOfficer()
		{
			$preRot=$_GET["rotation"];
			$rot=str_replace('/','_',$preRot);
			$cont=$_GET["container"];
			$user=$_GET["user"];
			
			$upload_dir = FCPATH . 'resources/images/Signature/';  //implement this function yourself
			
			$img = $_GET['hiddenPath'];
			$img_ff = $_GET['hiddenPath_ff'];
			$img_cpa = $_GET['hiddenPath_cpa'];
			
							//echo "Image : ".$img;
							//echo "Rot : ".$rot;
							
			$imgBs = str_replace('data:image/png;base64,', '', $img);
			$imgRep = str_replace(' ', '+', $imgBs);
			$dataImg = base64_decode($imgRep);
			
			$imgBs_ff = str_replace('data:image/png;base64,', '', $img_ff);
			$imgRep_ff = str_replace(' ', '+', $imgBs_ff);
			$dataImg_ff = base64_decode($imgRep_ff);
			
			$imgBs_cpa = str_replace('data:image/png;base64,', '', $img_cpa);
			$imgRep_cpa = str_replace(' ', '+', $imgBs_cpa);
			$dataImg_cpa= base64_decode($imgRep_cpa);
			
			//echo "Data : ".$data;
			$sign_name="sign_".$rot."_".$cont."_"."bo"."_".$user.".png";
			$file = $upload_dir.$sign_name;
			
			$sign_name_ff="sign_".$rot."_".$cont."_"."ff"."_".$user.".png";
			$file_ff = $upload_dir.$sign_name_ff;
			
			$sign_name_cpa="sign_".$rot."_".$cont."_"."cpa"."_".$user.".png";
			$file_cpa = $upload_dir.$sign_name_cpa;
					
			$str = "update shed_tally_info set signature_path_berth='$sign_name',signature_path_freight='$sign_name_ff',signature_path_cpa='$sign_name_cpa' where import_rotation='$preRot' and cont_number='$cont'";				
							//echo $str;
			$stat = $this->bm->dataInsertDB1($str);  //comment out to stop insertion
			if($stat==1)
			{
				$data['stat']="1";
				$success = file_put_contents($file, $dataImg);
				$success_ff = file_put_contents($file_ff, $dataImg_ff);
				$success_cpa = file_put_contents($file_cpa, $dataImg_cpa);
			}
			else
			{
				$data['stat']="0";	
			}
			echo json_encode($data);
		}
		// UPLOAD SIGNATURE END//
	function getAllYard()
	{
		 //$yard = $_GET["yard"];
		 $query = "select distinct current_position from ctmsmis.mis_exp_unit
					where  mis_exp_unit.delete_flag='0' and mis_exp_unit.snx_type=2 and current_position!=''";
         $rtnYardList = $this->bm->dataSelectDb2($query);
         echo json_encode($rtnYardList);		 
	}
	
	//yard start
	function loadBlock()
	{
		$terminal = $_GET["terminal"];
	//	$query = "select distinct Block_No from ctmsmis.tmp_assignment_type_new where Yard_No='$terminal' order by Block_No";
		$query = "SELECT DISTINCT block_cpa as Block_No FROM ctmsmis.yard_block WHERE terminal='$terminal' ORDER BY id";
        
		$rtnBlockList = $this->bm->dataSelect($query);        
		echo json_encode($rtnBlockList);		 
	}
	
	// function getAssignmentType() 	//old query backup
	// {
		// $terminal = $_GET["terminal"];
		// $assignDt = $_GET["assignDt"];
		// $strCheck = "select count(*) as rtnValue from ctmsmis.tmp_assignment_type_new where date(flex_date01)='$assignDt'";
		// $rtnValue = $this->bm->dataReturn($strCheck);
		// //if($rtnValue<1500)
		// //{
			// $strCallProc = "CALL ctmsmis.update_assignment_type_new('$assignDt')";
			// $this->bm->dataUpdate($strCallProc);
		// //}
		// $query = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_assignment_type_new where Yard_No='$terminal'";
        
		// $rtnAssignmentList = $this->bm->dataSelect($query);        
		// echo json_encode($rtnAssignmentList);		 
	// }
	
	function getAssignmentType()
	{
		$terminal = $_GET["terminal"];
		$assignDt = $_GET["assignDt"];
		
		$query = "SELECT DISTINCT 
			(CASE
				WHEN mfdch_value='APPDLV2H' THEN 'APPDLV2HG'
				WHEN mfdch_value='APPDLVGRD' THEN 'APPDLV2HG'
				WHEN mfdch_value='DLVREF2H' THEN 'DLVREF2H'
				WHEN mfdch_value='DLVREFGRD' THEN 'DLVREF2H'
				ELSE mfdch_value END ) AS mfdch_value,(
				CASE
				WHEN mfdch_desc='Appraise Cum Delivery 2 High' THEN 'Appraise Cum DLV/GROUND/2H'
				WHEN mfdch_desc='Appraise Cum Delivery Ground' THEN 'Appraise Cum DLV/GROUND/2H'
				WHEN mfdch_desc='Delivery Reefer 2 High' THEN 'Reefer GROUND/2H'
				WHEN mfdch_desc='Delivery Reefer Ground' THEN 'Reefer GROUND/2H'
				ELSE
				mfdch_desc
				END
			) AS mfdch_desc
			FROM
			(
			SELECT (CASE
					WHEN
						config_metafield_lov.mfdch_value='APPREF'
					THEN
						'APPCUS'
					WHEN
						'$terminal'='GCB' AND (mfdch_value='DLVGRD' OR mfdch_value='DLVOTH' OR mfdch_value='DLVHYS')
					THEN
						'DLV2H'
					WHEN
						'$terminal'!='GCB' AND (mfdch_value='DLVGRD' OR mfdch_value='DLVOTH')
					THEN
						'DLV2H'
					ELSE
						config_metafield_lov.mfdch_value
					END		
				) AS mfdch_value,
				(CASE
					WHEN
						config_metafield_lov.mfdch_desc='Appraise Reefer'
					THEN
						'Customs Appraise'
					WHEN
						'$terminal'='GCB' AND (mfdch_desc='Delivery Ground' OR mfdch_desc='Delivery 2 High' OR mfdch_desc='Delivery Others' OR mfdch_desc='Delivery Hyster')
					THEN
						'Delivery Others/Ground/2 High/Hyster'
					WHEN
						'$terminal'!='GCB' AND (mfdch_desc='Delivery Ground' OR mfdch_desc='Delivery 2 High' OR mfdch_desc='Delivery Others')
					THEN
						'Delivery Others/Ground/2 High'
					ELSE
						config_metafield_lov.mfdch_desc
					END		
				) AS mfdch_desc
				
			FROM sparcsn4.config_metafield_lov WHERE mfdch_metafield=331 AND mfdch_value!='--'
			) AS tmp";
        
		$rtnAssignmentList = $this->bm->dataSelect($query);        
		echo json_encode($rtnAssignmentList);		 
	}
	
	function onblockchange()
	{
		$terminal = $_GET["terminal"];
		$assignDt = $_GET["assignDt"];
		$yard = $_GET["yard"];
		
		if($yard=='ALLBLOCK')
			$query = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_assignment_type_new where Yard_No='$terminal'";
		else
			$query = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_assignment_type_new where Yard_No='$terminal' and Block_No='$yard'";
        
		$rtnBlock = $this->bm->dataSelect($query);        
		echo json_encode($rtnBlock);		 
	}
	//yard end
	
	function updatetable()
	{
		$date = $_GET["date"];
		$block = $_GET["regblock"];
		$strCheck = "select count(*) as rtnValue from ctmsmis.tmp_assignment_type_new where date(flex_date01)='$date'";
		$rtnValue = $this->bm->dataReturn($strCheck);
		if($rtnValue<1000)
		{
			//$strCallProc = "CALL ctmsmis.update_assignment_type_new('$date')";
			//$this->bm->dataUpdate($strCallProc);
		}		
		
		$query = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_assignment_type_new where Block_No='$block'";
        
		$rtnAssignmentList = $this->bm->dataSelect($query);        
		echo json_encode($rtnAssignmentList);		
	}
	function getContainerInfo()
	{
		    $cont_number= $_GET["cont_number"];

			$getContainerInfoQuery= "select (select right(sparcsn4.ref_equip_type.nominal_length,2) from ref_equip_type 
									INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
									INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
									where sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
									) as cont_size,
									(select right(sparcsn4.ref_equip_type.nominal_height,2)/10 from ref_equip_type 
									INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
									INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
									where sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
									) as cont_height,
									freight_kind as cont_status,
									inv_unit_fcy_visit.flex_string10 ib_vyg
									FROM sparcsn4.inv_unit
									INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
									INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
									
									where inv_unit.id='$cont_number'";
			$getContainerInfoRslt = $this->bm->dataSelect($getContainerInfoQuery);
            echo json_encode($getContainerInfoRslt);
	   
	}
	
	// Get MLO using Rotation Start
		function getMloByRotation()
		{
			$rotation = $_GET["rotation"];
			$query = "select distinct igm_details.mlocode
								from igm_detail_container 
								inner join igm_details on igm_details.id=igm_detail_container.igm_detail_id  
								where igm_details.Import_Rotation_No='$rotation'";
			$rtnMloList = $this->bm->dataSelectDb1($query);
			
			//$data['rtnMloList']=$rtnMloList; 
			echo json_encode($rtnMloList);		 
		}
		// Get MLO using Rotation End
	
	//sidebar start
	function getOrgTypeSection()
	{
		$login_id=$_GET['login_id'];
		
		$sql_OrgTypeSection="select users.login_id,tbl_org_types.id as tbl_org_types_id,Org_Type,users_section_detail.id as users_section_detail_id,full_name
		from users 
		inner join tbl_org_types on tbl_org_types.id=users.org_Type_id
		inner join users_section_detail on users_section_detail.id=users.section
		where login_id='$login_id'";
		
		$rslt_OrgTypeSection = $this->bm->dataSelectDb1($sql_OrgTypeSection);
		
		echo json_encode($rslt_OrgTypeSection);	
	}
	
	function getURL()
	{
		$menu_id=$_GET['menu_id'];
	
		$sql_URL="select distinct url_id,panel_menu_id,url_title 
		from url_details
		where panel_menu_id='$menu_id'";
		
		$rslt_URL = $this->bm->dataSelectDb1($sql_URL);
		
		echo json_encode($rslt_URL);
	}
	//sidebar end
	// Get IGM Detail Information
	function getIGMDtlInfo()
		{
			$rotation_no = $_GET["rotation_no"];
			$bl_no = $_GET["bl_no"];
			$rtnIGMDtlList="";
			
			$queryChkExist="select count(id) as cntData from igm_masters where Import_Rotation_No='$rotation_no'";
			$rtnChkList = $this->bm->dataSelectDb1($queryChkExist);
			$rowChk=$rtnChkList[0]['cntData'];
			
			if($rowChk==0)
			{
				$status_mst=0;
				$status_dtl=0;
			}
			else
			{
				$status_mst=1;
				$queryIgmDtl = "SELECT id,IGM_id,Import_Rotation_No,Line_No,BL_No,IFNULL(Pack_Number,'') AS Pack_Number,
				IFNULL(Pack_Description,'') AS Pack_Description,IFNULL(Pack_Marks_Number,'') AS Pack_Marks_Number,
				IFNULL(Description_of_Goods,'') AS Description_of_Goods,IFNULL(weight,'') AS weight,IFNULL(Remarks,'') AS Remarks,
				IFNULL(ConsigneeDesc,'') AS ConsigneeDesc,IFNULL(NotifyDesc,'') AS NotifyDesc,IFNULL(Submitee_Id,'') AS Submitee_Id,
				IFNULL(Submission_Date,'') AS Submission_Date,IFNULL(Submitee_Org_Id,'') AS Submitee_Org_Id,
				IFNULL(last_update,'') AS last_update,IFNULL(type_of_igm,'') AS type_of_igm,IFNULL(weight_unit,'') AS weight_unit,IFNULL(mlocode,'') AS mlocode,IFNULL(Exporter_name,'') AS Exporter_name,IFNULL(Exporter_address,'') AS Exporter_address,IFNULL(Notify_code,'') AS Notify_code,
				IFNULL(Notify_name,'') AS Notify_name,IFNULL(Notify_address,'') AS Notify_address,
				IFNULL(Consignee_code,'') AS Consignee_code,IFNULL(Consignee_name,'') AS Consignee_name,IFNULL(Consignee_address,'') AS Consignee_address,IFNULL(DG_status,'') AS DG_status,IFNULL(place_of_unloading,'') AS place_of_unloading,IFNULL(port_of_origin,'') AS port_of_origin
				FROM igm_details 
				where Import_Rotation_No='$rotation_no' and BL_No='$bl_no'";
				$rtnIGMDtlList = $this->bm->dataSelectDb1($queryIgmDtl);
				
				$queryIgmMst = "select id,Submitee_Id,Submission_Date,Port_of_Destination,Submitee_Org_Id from igm_masters where Import_Rotation_No='$rotation_no'";
				$rtnIGMMstList = $this->bm->dataSelectDb1($queryIgmMst);
				
				$queryComment = "SELECT COMMENT FROM igm_log_manual_entry WHERE Import_Rotation_No='$rotation_no' AND  BL_No='$bl_no'";
				$rtnComment = $this->bm->dataSelectDb1($queryComment);
				$data['rtnComment']=$rtnComment;
				
				if(count($rtnIGMDtlList)>0)
				{
					$status_dtl=1;
					$data['rtnIGMDtlList']=$rtnIGMDtlList;
				}
				else
				{
					$status_dtl=0;
					$data['rtnIGMMstList']=$rtnIGMMstList;
				}
				
			}
			
			
			$data['status_mst']=$status_mst; 
			$data['status_dtl']=$status_dtl; 
			 
			echo json_encode($data);	
		}
	
	//cnf info start
	function get_cnf_info()
	{
		$license_no=$_GET['license_no'];
		
		$sql_cnf_info="SELECT gkey,id,NAME AS u_name,ct_name,address_line1 AS Address_1,address_line2 AS Address_2,city,telephone AS Telephone_No_Land,sms_number AS Cell_No_1,email_address AS email
		FROM ref_bizunit_scoped 
		WHERE id='$license_no'";
		
		$rslt_user_data=$this->bm->dataSelect($sql_cnf_info);
		
		echo json_encode($rslt_user_data);
	}
	//cnf info end
	
	//assignment type and delivery time - start
	function get_assignment_dlvtime()
	{
		$container_no=$_GET['container_no'];
		
		$sql_assignment_dlvtime="SELECT id,gkey,mfdch_desc AS assign_type,CONCAT(delDT,' ',delTime) AS dlv_time_slot
								FROM (
								SELECT a.id,a.gkey,config_metafield_lov.mfdch_value,mfdch_desc,b.flex_date01,
								CAST(DATE(flex_date01) AS CHAR) AS delDT,
								(CASE 
									WHEN UCASE(mfdch_desc) LIKE 'APPRAISE CUM DEL%' THEN '2PM-5PM'
									WHEN UCASE(mfdch_desc) LIKE '%REEFER%' THEN '4PM-7PM'
									ELSE '10AM-1PM' END) AS delTime
								FROM sparcsn4.inv_unit a 
								INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
								INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value 
								INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods 
								WHERE a.id='$container_no' AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
								) AS tbl";
		
		$rslt_assignment_dlvtime=$this->bm->dataSelect($sql_assignment_dlvtime);
	//	$rslt_assignment_dlvtime=$this->bm->dataSelectDb2($sql_assignment_dlvtime);
		
		echo json_encode($rslt_assignment_dlvtime);
	}
	//assignment type and delivery time - end
	
	
	function getBLInfoForCnF()
	  {
		$blNo = $_GET['blNo'];
		//$rotNo = $_GET['rotNo'];
		$login_id = $this->session->userdata('login_id');
		
		$query1="SELECT Import_Rotation_No,BL_No, Bill_of_Entry_No, Bill_of_Entry_Date, Pack_Number, office_code, jetty_sirkar_lic, Pack_Description,Pack_Marks_Number
					FROM cchaportdb.igm_details WHERE BL_No='$blNo'";
							
		$blInfo1 = $this->bm->dataSelectDb1($query1);
		
		$query2="SELECT igm_detail_container.id, igm_detail_container.igm_detail_id,cont_number,cont_size,cont_gross_weight,cont_height,truck_no
				FROM igm_details 
				INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id 
				LEFT JOIN igm_truck_detail ON igm_detail_container.id=igm_truck_detail.igm_detail_cont_id
				WHERE igm_details.BL_No='$blNo'";
							
		$blInfo2 = $this->bm->dataSelectDb1($query2);
		
		$data['blInfo1']=$blInfo1;	
		$data['blInfo2']=$blInfo2;	
	
        echo json_encode($data);	    
		   
	 }
	 
	 //product name - start
	function get_product_name()
	{
		$product_type_id=$_GET['product_type_id'];
		
		$sql_product_name="SELECT id, CONCAT(prod_name,'---',prod_serial) AS prod_name FROM inventory_product_info WHERE type_id='$product_type_id' ORDER BY prod_name ASC";
		
		$rslt_product_name=$this->bm->dataSelectDb1($sql_product_name);
		
		echo json_encode($rslt_product_name);
	}
	//product name - end
	
	//handover to - start
	function get_handover_to()
	{
		$handover_cat=$_GET['handover_cat'];
		
		if($handover_cat=="new")
		{
			$sql_handover_to="SELECT id,full_name FROM inventory_product_owner ORDER BY full_name ASC";
		}
		else if($handover_cat=="damaged" or $handover_cat=="repaired")
		{
			$sql_handover_to="SELECT id,company_name FROM inventory_product_user";	
		}
		
		$rslt_handover_to=$this->bm->dataSelectDb1($sql_handover_to);
		
		echo json_encode($rslt_handover_to);
	}
	//handover to - end
	
	
	function getNetworkProduct()
        {
            $typeId=$_GET['typeid'];
		$query="SELECT id, CONCAT(prod_name,'---',prod_serial) AS prod_name FROM inventory_product_info WHERE type_id='$typeId' ORDER BY prod_name ASC";
							
            $productName = $this->bm->dataSelectDb1($query);		
         //   $data['productName']=$productName;	
           echo json_encode($productName);	    
        }
		
		
	function getLocationInfo()
        {
            	$location_id=$_GET['locid'];
		$sql_location="SELECT inventory_product_location_details.id, location_details FROM inventory_product_location_details
                             WHERE `inventory_product_location_details`.`location_id`='$location_id'";
		
		$loc_dtl=$this->bm->dataSelectDb1($sql_location);
		
		echo json_encode($loc_dtl);
        }
		
			
	function getComboValForNetworkList()
	{
		$colName = $_GET["colName"];
		$query = "";
		if($colName=="category")
			$query = "SELECT id ,IF(short_name='Monitor',CONCAT(short_name,'-',product_desc),short_name) as detl FROM cchaportdb.inventory_product_type ORDER BY short_name ASC";		
		else if($colName=="location")
			$query = "SELECT location_name as id, location_name as detl FROM inventory_product_location";
		else if($colName=="user")
			$query = "SELECT login_id as id, login_id as detl FROM `users` WHERE `org_Type_id`='66'";
		
		$rtnComboValList=$this->bm->dataSelectDb1($query);
		echo json_encode($rtnComboValList);
	}	
	function getIndentYard()
	{
		 $query = "select id,yard_name from ctmsmis.mis_equip_indent ORDER BY yard_name ASC";
         $rtnBlockList = $this->bm->dataSelectDb2($query);
         echo json_encode($rtnBlockList);		 
	}
	
	// function getVslName()
	// {
	// 	 $rot_no = $_GET["rot_no"];

	// 	 $query = "SELECT DISTINCT vsl_vessels.name AS vsl_name
	// 		FROM vsl_vessels
	// 		INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey = vsl_vessel_visit_details.vessel_gkey
	// 		WHERE vsl_vessel_visit_details.ib_vyg='$rot_no' OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY";
    //      $vsselName = $this->bm->dataSelect($query);
    //      echo json_encode($vsselName);		 
	// }

	function getVslName()
	{
		
		        $rot_no = $_GET["rot_no"];
		        $query = "SELECT vsl_vessels.name AS vsl_name, vsl_vessel_visit_details.ib_vyg as rotation, vsl_vessels.lloyds_id AS vsl_imo,
				ref_bizunit_scoped.name as agent_name, CONCAT(COALESCE(ref_bizunit_scoped.address_line1,''), COALESCE(ref_bizunit_scoped.address_line2,'')) AS address,
				ref_bizunit_scoped.email_address, ref_bizunit_scoped.bizu_gkey, 
				COALESCE (ref_bizunit_scoped.sms_number,ref_bizunit_scoped.telephone) AS contact_num,
				ref_agent_representation.agent_gkey
				FROM vsl_vessels
				INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
				LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=vsl_vessel_visit_details.bizu_gkey
				LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=ref_agent_representation.agent_gkey
				WHERE vsl_vessel_visit_details.ib_vyg='$rot_no'";
				
         $vsselName = $this->bm->dataSelect($query);
         echo json_encode($vsselName);		 
	}
	
	/*function getVslNameTest()
	{
		 $rot_no = $_GET["rot_no"];
		 echo $query = "SELECT vsl_vessels.name AS vsl_name, vsl_vessel_visit_details.ib_vyg as rotation, vsl_vessels.lloyds_id AS vsl_imo,
				ref_bizunit_scoped.name as agent_name, CONCAT(COALESCE(ref_bizunit_scoped.address_line1,''), COALESCE(ref_bizunit_scoped.address_line2,'')) AS address,
				ref_bizunit_scoped.email_address, ref_bizunit_scoped.bizu_gkey, 
				COALESCE (ref_bizunit_scoped.sms_number,ref_bizunit_scoped.telephone) AS contact_num,
				ref_agent_representation.agent_gkey
				FROM vsl_vessels
				INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
				LEFT JOIN ref_agent_representation ON ref_agent_representation.bzu_gkey=vsl_vessel_visit_details.bizu_gkey
				LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=ref_agent_representation.agent_gkey
				WHERE vsl_vessel_visit_details.ib_vyg='$rot_no'";
				return;
         $vsselName = $this->bm->dataSelect($query);
         echo json_encode($vsselName);		 
	}*/

	function getVesselName()
	{
		 $rot_no = $_GET["rotation"];
		 $query = "SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No = '$rot_no'";
         $vsselName = $this->bm->dataSelectDB1($query);
         echo json_encode($vsselName);		 
	}
	
	function getberthOp()
	{
		 $query = "SELECT DISTINCT(vsl_vessel_visit_details.flex_string03) AS berthop 
		 FROM vsl_vessel_visit_details WHERE vsl_vessel_visit_details.flex_string03 IS NOT NULL FETCH FIRST 8 ROWS ONLY";
         $berthOpList = $this->bm->dataSelect($query);
         echo json_encode($berthOpList);		 
	}
	//container wise truck - start
	function get_cont_truck_info()
	{
		$container_no = $_GET["container_no"];
		
		$sql_cont_truck_n4="SELECT sparcsn4.inv_unit.id AS cont_no,sparcsn4.inv_unit.gkey AS unit_gkey,sparcsn4.config_metafield_lov.mfdch_desc AS assign_type,DATE(sparcsn4.inv_unit_fcy_visit.flex_date01) AS assign_date,sparcsn4.vsl_vessel_visit_details.ib_vyg AS rotation,sparcsn4.vsl_vessels.name AS vessel_name,sparcsn4.ref_bizunit_scoped.name AS cnf,sparcsn4.ref_bizunit_scoped.gkey AS bizu_gkey
		FROM sparcsn4.inv_unit  
		INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
		INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
		INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
		INNER JOIN sparcsn4.config_metafield_lov ON sparcsn4.inv_unit.flex_string01=config_metafield_lov.mfdch_value 
		INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op 
		WHERE sparcsn4.inv_unit.id='$container_no'";
		
		$rslt_cont_truck_n4=$this->bm->dataSelect($sql_cont_truck_n4);
		
		$sql_cont_truck_igm="SELECT cont_size,cont_height,Pack_Description,Pack_Number
		FROM igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		WHERE cont_number='$container_no'";
		
		$rslt_cont_truck_igm=$this->bm->dataSelectDb1($sql_cont_truck_igm);
		
		//---
		$sql_chk_entered_truck="SELECT id,number_of_truck 
		FROM ctmsmis.mis_cf_assign_truck 
		WHERE cont_id='$container_no'";
		
		$rslt_chk_entered_truck=$this->bm->dataSelect($sql_chk_entered_truck);
		
		$cf_assign_truck_id=$rslt_chk_entered_truck[0]['number_of_truck'];
		
		$sql_truck_number_list="SELECT truck_number AS rtnValue FROM ctmsmis.cont_wise_truck_dtl WHERE cf_assign_truck_id='$cf_assign_truck_id'";
		
		$rslt_truck_number_list=$this->bm->dataReturn($sql_truck_number_list);
		//---
		
		$data['rslt_cont_truck_n4']=$rslt_cont_truck_n4;
		$data['rslt_cont_truck_igm']=$rslt_cont_truck_igm;
		$data['rslt_chk_entered_truck']=$rslt_chk_entered_truck;
		$data['rslt_truck_number_list']=$rslt_truck_number_list;
		
		echo json_encode($data);	
	}
	//container wise truck - end
	
	
	function getGateList()
	{
		 $query = "SELECT DISTINCT gkey, id FROM sparcsn4.road_gates WHERE life_cycle_state='ACT'";
         $gateList = $this->bm->dataSelect($query);
         echo json_encode($gateList);		 
	}
	// Sourav Dispute Comments Entry
	function saveDisputeComments()
	{
		//$rotation=str_replace("_","/",$this->uri->segment(3));
		//$container=str_replace("_","/",$this->uri->segment(4));
		$bill_no= $_GET["bill_no"];
		$comment= $_GET["comment"];
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$str = "INSERT INTO ctmsmis.billingdisputecont(invoicerefno,disputeDetails,disputeDate,ipaddress,createdby,createdtime) 
		VALUES ('$bill_no','$comment',now(),'$ipaddr','$login_id',now())";
		$stat = $this->bm->dataInsert($str);
		
		if($stat==1)
		{
			$data['stat']="1";
			
		}
		else
		{
			$data['stat']="0";
			
		}
		
		echo json_encode($data);
	}
	
	function getOrgResult()
	{
		//$searchkey=$_GET["search_by"];	

		$sqlQuery="SELECT id, Org_Type as type from tbl_org_types";
		
		$list = $this->bm->dataSelectDb1($sqlQuery);
		echo json_encode($list);
	}	
	//cnf info start
	function get_org_info()
	{
		$org_type=$_GET['org_type'];
		$org_name=$_GET['org_name'];
		
		$sql_org_info="SELECT Address_1,Address_2,Cell_No_1,Cell_No_2,email FROM organization_profiles WHERE Organization_Name='$org_name' AND Org_Type_id='$org_type'";
		
		$rslt_user_data=$this->bm->dataSelectDb1($sql_org_info);
		
		echo json_encode($rslt_user_data);
	}
	
	function saveViewNotice()
	{
		//$rotation=str_replace("_","/",$this->uri->segment(3));
		//$container=str_replace("_","/",$this->uri->segment(4));
		$notice_id= $_GET["val"];
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		$org_id = $this->session->userdata('org_Type_id');
		$stat=0;
		$sql_row_num="SELECT count(*) as rtnValue 
			FROM view_notice_log
			WHERE notice_id=$notice_id and user_id='$login_id' and org_id=$org_id";
		
		$row_rtn=$this->bm->dataReturnDb1($sql_row_num);

		if($row_rtn<1)
		{
			
			$str = "INSERT INTO view_notice_log (notice_id,view_stat,user_id,org_id,ip_addr,entry_date) VALUES ($notice_id,1,'$login_id',$org_id,'$ipaddr',now())";
			$stat = $this->bm->dataInsertDB1($str);
		}
		
		if($stat==1)
		{
			$data['stat']="1";
			
		}
		else
		{
			$data['stat']="0";
			
		}
		
		echo json_encode($data);
	}
	
	//get cnf name - break bulk
	function getCNFName()
	{
		$bb_cnf_lic_no=$_GET['bb_cnf_lic_no'];
		
		$sql_CNFName="SELECT id,name 
		FROM ref_bizunit_scoped 
		WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$bb_cnf_lic_no'";
		
		$rslt_CNFName=$this->bm->dataSelect($sql_CNFName);
		
		echo json_encode($rslt_CNFName);
	}
	
	function getFFName()
	{
		$ain_no=$_GET['ain_no'];		
		$sql_FFName="SELECT * FROM organization_profiles WHERE AIN_No_New='$ain_no' AND Org_Type_id='4'";		
		$rslt_FFName=$this->bm->dataSelectDb1($sql_FFName);
		// if(count($rslt_FFName==0))
		// {
			// $sql_FFName="SELECT * FROM organization_profiles WHERE AIN_No='$ain_no' AND Org_Type_id='4'";		
			// $rslt_FFName=$this->bm->dataSelectDb1($sql_FFName);
		// }
		echo json_encode($rslt_FFName);
	}
	
	function getTarrifID (){
        $billType = $_GET['r'];
        $sql = "SELECT gkey,id FROM bil_tariffs WHERE bill_type='$billType'";
        $result = $this->bm->dataSelectDb1($sql);
        echo json_encode($result);
    }
	
	// function getJettySrkrInfo()
	// {
		// $jsId = $_GET['jsId'];
		
		// // $sql_jsInfo = "SELECT id,js_name,js_lic_no,cell_no,adress,lic_copy_path,gate_pass_path,js_img_path,lic_val_dt,gate_pass_val_dt
		// // FROM vcms_jetty_sirkar
		// // WHERE id='$jsId'";
		
		// $sql_jsInfo = "SELECT id,agent_name AS js_name,agent_code AS js_lic_no,mobile_number AS cell_no
		// FROM vcms_vehicle_agent
		// WHERE id='$jsId'";
		// $rslt_jsInfo = $this->bm->dataSelectDb1($sql_jsInfo);
		
		// $data['rslt_jsInfo']=$rslt_jsInfo;
		
		// echo json_encode($data);
	// }
	
	function getJettySrkrInfo()
	{
		// $org_license = $this->session->userdata('org_license');
		$jsGatePass = $_GET['jsGatePass'];			
		
		$sql_jsInfo = "SELECT id,agent_name AS js_name,agent_code AS js_lic_no,mobile_number AS cell_no
		FROM vcms_vehicle_agent
		WHERE card_number='$jsGatePass'";
		
		// $sql_jsInfo = "SELECT id,agent_name AS js_name,agent_code AS js_lic_no,mobile_number AS cell_no
		// FROM vcms_vehicle_agent
		// INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
		// WHERE card_number='$jsGatePass' AND agency_code='$org_license'";
				
		$rslt_jsInfo = $this->bm->dataSelectDb1($sql_jsInfo);
				
		$data['rslt_jsInfo']=$rslt_jsInfo;
		
		if(count($rslt_jsInfo)>0)
		{
			echo json_encode($data);
			/*$strChckValidity = "SELECT IF(valid_till_dt<DATE(NOW()),1,0) AS rtnValue FROM vcms_vehicle_agent 
			WHERE card_number='$jsGatePass' ORDER BY id DESC LIMIT 1";
			$isExpire = $this->bm->dataReturnDb1($strChckValidity);
			if($isExpire==0)
			{
				echo json_encode($data);
			}
			else
			{
				$this->updateValidity($jsGatePass);
				$sql_jsInfo = "SELECT id,agent_name AS js_name,agent_code AS js_lic_no,mobile_number AS cell_no
				FROM vcms_vehicle_agent
				WHERE card_number='$jsGatePass'";
				$rslt_jsInfo = $this->bm->dataSelectDb1($sql_jsInfo);

				if(count($rslt_jsInfo)>0)
				{
					$data['rslt_jsInfo']=$rslt_jsInfo;				
				}
				else
				{
					$data['rslt_jsInfo']="";				
				}
				
				echo json_encode($data);
			}*/
		}
		else
		{
			$this->getBiometricData($jsGatePass);
			$sql_jsInfo = "SELECT id,agent_name AS js_name,agent_code AS js_lic_no,mobile_number AS cell_no
			FROM vcms_vehicle_agent
			WHERE card_number='$jsGatePass'";
			$rslt_jsInfo = $this->bm->dataSelectDb1($sql_jsInfo);

			if(count($rslt_jsInfo)>0)
			{
				$data['rslt_jsInfo']=$rslt_jsInfo;				
			}
			else
			{
				$data['rslt_jsInfo']="";				
			}
			
			echo json_encode($data);
		}
	}
	
	function getDriverInfo()
	{
		$driverPassNo = $_GET['driverPassNo'];				
		
		$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number,agent_photo
		FROM vcms_vehicle_agent 
		WHERE agent_type = 'Driver' AND card_number = '$driverPassNo'";
		$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
		
		$data['rslt_driverInfo']=$rslt_driverInfo;
		if(count($rslt_driverInfo)>0)
		{	
			$this->updateValidity($driverPassNo);
			echo json_encode($data);
			/*$strChckValidity = "SELECT IF(valid_till_dt<DATE(NOW()),1,0) AS rtnValue FROM vcms_vehicle_agent 
			WHERE card_number='$driverPassNo' ORDER BY id DESC LIMIT 1";
			$isExpire = $this->bm->dataReturnDb1($strChckValidity);
			if($isExpire==0)
			{
				echo json_encode($data);
			}
			else
			{
				$this->updateValidity($driverPassNo);
				$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number
				FROM vcms_vehicle_agent 
				WHERE agent_type = 'Driver' AND card_number = '$driverPassNo'";
				$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
				
				if(count($rslt_driverInfo)>0)
				{
					$data['rslt_driverInfo']=$rslt_driverInfo;				
				}
				else
				{
					$data['rslt_driverInfo']="";				
				}
				
				echo json_encode($data);
			}*/
		}
		else
		{
			$this->getBiometricData($driverPassNo);
			$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number,agent_photo
			FROM vcms_vehicle_agent 
			WHERE agent_type = 'Driver' AND card_number = '$driverPassNo'";
			$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
			
			if(count($rslt_driverInfo)>0)
			{
				$data['rslt_driverInfo']=$rslt_driverInfo;				
			}
			else
			{
				$data['rslt_driverInfo']="";				
			}
			
			echo json_encode($data);
		}
	}
	
	function getAssistantInfo()
	{
		$driverPassNo = $_GET['driverPassNo'];				
		
		$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number,agent_photo
		FROM vcms_vehicle_agent 
		WHERE agent_type = 'Helper' AND card_number = '$driverPassNo'";
		
		$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
		
		$data['rslt_driverInfo']=$rslt_driverInfo;
		if(count($rslt_driverInfo)>0)
		{
			$this->updateValidity($driverPassNo);
			echo json_encode($data);
			/*$strChckValidity = "SELECT IF(valid_till_dt<DATE(NOW()),1,0) AS rtnValue FROM vcms_vehicle_agent 
			WHERE card_number='$driverPassNo' ORDER BY id DESC LIMIT 1";
			$isExpire = $this->bm->dataReturnDb1($strChckValidity);
			if($isExpire==0)
			{
				echo json_encode($data);
			}
			else
			{
				$this->updateValidity($driverPassNo);
				$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number
				FROM vcms_vehicle_agent 
				WHERE agent_type = 'Helper' AND card_number = '$driverPassNo'";
				$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
				
				if(count($rslt_driverInfo)>0)
				{
					$data['rslt_driverInfo']=$rslt_driverInfo;				
				}
				else
				{
					$data['rslt_driverInfo']="";				
				}
				
				echo json_encode($data);
			}*/
		}
		else
		{
			$this->getBiometricData($driverPassNo);
			$sql_driverInfo = "SELECT agent_name AS driver_assist_name,mobile_number,agent_photo
			FROM vcms_vehicle_agent 
			WHERE agent_type = 'Helper' AND card_number = '$driverPassNo'";
			
			$rslt_driverInfo = $this->bm->dataSelectDb1($sql_driverInfo);
						
			if(count($rslt_driverInfo)>0)
			{
				$data['rslt_driverInfo']=$rslt_driverInfo;
				echo json_encode($data);
			}
			else
			{
				$data['rslt_driverInfo']="";
				echo json_encode($data);
			}
		}
	}
	
	function updateValidity($cardNum)
	{
		/*$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$cardNum;
		$json = file_get_contents($url);
		$obj = json_decode($json);
		if(count($obj)>0)
		{
			$CARDNUMBER = $obj->CARDNUMBER;	
			
			$VALID_TILL = $obj->VALID_TILL;					 
			$VALID_TILL = strtotime($VALID_TILL);  
			$VALID_TILL = date("Y-m-d", $VALID_TILL);*/
			$strUpdateValidity = "UPDATE vcms_vehicle_agent SET valid_till_dt=DATE(ADDDATE(NOW(),INTERVAL 1 DAY)),last_update=NOW() WHERE card_number='$cardNum'";
			$this->bm->dataUpdateDB1($strUpdateValidity);
		//}
	}

	function getCardNuberByScan()
	{
		$cardNum = $_GET['passNo'];
		$url = "http://10.1.100.105:8095/hash2card.php?HASH=".$cardNum;
		$json = file_get_contents($url);
		$obj = json_decode($json);
		if(count($obj)>0)
		{			
			$cardnumber = $obj->cardnumber;
			echo $cardnumber;
		}
	}
	
	function getBiometricData($cardNum)
	{
		$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$cardNum;
		$json = file_get_contents($url);
		$obj = json_decode($json);
		if(count($obj)>0)
		{			
			$CARDNUMBER = $obj->CARDNUMBER;	
			
			$VALID_TILL = $obj->VALID_TILL;					 
			$VALID_TILL = strtotime($VALID_TILL);  
			$VALID_TILL = date("Y-m-d", $VALID_TILL);  
			
			$NATIONALID = $obj->NATIONALID;			
			$AGENCY_NAME = $obj->AGENCY_NAME;			
			$AGENT_NAME = $obj->AGENT_NAME;			
			$AGENT_TYPE_NAME = $obj->AGENT_TYPE_NAME;			
			$MOBILE = $obj->MOBILE;			
			$AGENT_CODE = $obj->AGENT_CODE;			
			$JSLNO = $obj->JSLNO;			
			$PHOTO = $obj->PHOTO;			
			$photobase64 = $obj->photobase64;
			
			//create folder & saving photo

			$im = $photobase64;
			$path = $_SERVER['DOCUMENT_ROOT'].'/biometricPhoto/'.$cardNum;
			$output_file = "";

			if(!file_exists($path)){
				mkdir($path, 0777, true);
				chmod($path, 0777);

				$image_path = $cardNum.'.png';
				$output_file=$_SERVER['DOCUMENT_ROOT'].'/biometricPhoto/'.$cardNum."/".$cardNum.'.png';	
				$ifp = fopen( $output_file, 'wb' ); 

				$data = explode( ',', $im );

				// we could add validation here with ensuring count( $data ) > 1
				fwrite( $ifp, base64_decode( $data[ 1 ] ) );

				// clean up the file resource
				fclose( $ifp );

				// save image on 192.168.16.42 -- start

				$pcsResult = file_get_contents('http://192.168.16.42/pcs/index.php/ShedBillController/pullfile?cardNum='.$cardNum);

				// save image on 192.168.16.42 -- end
			}
			
			// insert agency if not exist
			$strAgencyChk = "SELECT * FROM vcms_vehicle_agency WHERE agency_name='$AGENCY_NAME'";
			$rsltAgencyChk = $this->bm->dataSelectDb1($strAgencyChk);
			$agency_id = "";
			if(count($rsltAgencyChk)>0)
			{
				$agency_id = $rsltAgencyChk[0]["id"];
			}
			else
			{
				$strInsertAgency = "INSERT INTO vcms_vehicle_agency(agency_name,agency_code) VALUES ('$AGENCY_NAME','$AGENT_CODE')";
				$rsltInsertAgency = $this->bm->dataInsertDB1($strInsertAgency);
				if($rsltInsertAgency)
				{
					$strAgencyChk = "SELECT * FROM vcms_vehicle_agency WHERE agency_name='$AGENCY_NAME'";
					$rsltAgencyChk = $this->bm->dataSelectDb1($strAgencyChk);
					if(count($rsltAgencyChk)>0)
					{
						$agency_id = $rsltAgencyChk[0]["id"];
					}
				}
			}
			
			if($AGENT_TYPE_NAME=="Jetty Sircar")
			{
				// insert agent
				$strInsertAgent = "INSERT INTO vcms_vehicle_agent(agency_id,card_number,nid_number,agent_name,agent_type,mobile_number,agent_code,agent_photo,valid_till_dt,last_update) 
				VALUES ($agency_id,'$CARDNUMBER','$NATIONALID','$AGENT_NAME','$AGENT_TYPE_NAME','$MOBILE','$JSLNO','$image_path','$VALID_TILL',NOW())";
				$rsltInsertAgent = $this->bm->dataInsertDB1($strInsertAgent);
			}
			else
			{
				// insert agent
				$strInsertAgent = "INSERT INTO vcms_vehicle_agent(agency_id,card_number,nid_number,agent_name,agent_type,mobile_number,agent_code,agent_photo,valid_till_dt,last_update) 
				VALUES ($agency_id,'$CARDNUMBER','$NATIONALID','$AGENT_NAME','$AGENT_TYPE_NAME','$MOBILE','$JSLNO','$image_path',DATE(ADDDATE(NOW(),INTERVAL 1 DAY)),NOW())";
				$rsltInsertAgent = $this->bm->dataInsertDB1($strInsertAgent);
			}
		}
	}
	
	function addTruckToTmp()
	{
		$vrfyInfoFclId = $_GET['vrfyInfoFclId'];
		$truckNo = $_GET['truckNo'];
		//$pkQty = $_GET['pkQty'];
		//$gateNo = $_GET['gateNo'];
		$driverName = $_GET['driverName'];
		$driverPassNo = $_GET['driverPassNo'];
		$assistantName = $_GET['assistantName'];
		$assistantPassNo = $_GET['assistantPassNo'];
		$emrgncyTrk = $_GET['emrgncyTrk'];
		
		$emrgncy_flag = 0;
		$emrgncy_approve_stat = 0;
			
		if(trim($emrgncyTrk) == "Emergency")
		{
			$emrgncy_flag = 1;
			$emrgncy_approve_stat = 0;
		}
		// else if($emrgncyTrk == "Add")
		// {
			// $emrgncy_flag = 0;
			// $emrgncy_approve_stat = 0;
		// }
		
		// $sql_insertTruckTmp = "INSERT INTO vcms_tmp_truck_dtl(verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass)
		// VALUES('$vrfyInfoFclId','$truckNo','$pkQty','$gateNo','$driverName','$driverPassNo','$assistantName','$assistantPassNo')";
		// $stat = $this->bm->dataInsertDB1($sql_insertTruckTmp);
		
		$sql_insertTruckTmp = "INSERT INTO vcms_tmp_truck_dtl(verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,emrgncy_flag,emrgncy_approve_stat)
		VALUES('$vrfyInfoFclId','$truckNo','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$emrgncy_flag','$emrgncy_approve_stat')";
		$stat = $this->bm->dataInsertDB1($sql_insertTruckTmp);
		
		// $sql_tmpRsltSet = "SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass 
		// FROM vcms_tmp_truck_dtl 
		// WHERE verify_info_fcl_id = '$vrfyInfoFclId'";
		
		// $sql_tmpRsltSet = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
		// FROM vcms_tmp_truck_dtl 
		// WHERE verify_info_fcl_id = '$vrfyInfoFclId'
		// UNION ALL
		// SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
		// FROM do_truck_details_entry
		// WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
		
		$sql_tmpRsltSet = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
		FROM vcms_tmp_truck_dtl 
		WHERE verify_info_fcl_id = '$vrfyInfoFclId'
		UNION ALL
		SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
		FROM do_truck_details_entry
		WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
		$rslt_tmpRsltSet = $this->bm->dataSelectDb1($sql_tmpRsltSet);
		
		$data['stat'] = $stat;
		$data['rslt_tmpRsltSet'] = $rslt_tmpRsltSet;
		
		echo json_encode($data);
		
	}
	
	function deleteTmpData()
	{
		$id = $_GET['id'];
		$vrfyInfoFclId = $_GET['vrfyInfoFclId'];
		
		$sql_deleteTmpData = "DELETE FROM vcms_tmp_truck_dtl WHERE id='$id'";
		$stat = $this->bm->dataDeleteDB1($sql_deleteTmpData);
		
		// $sql_tmpRsltSet = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
		// FROM vcms_tmp_truck_dtl 
		// WHERE verify_info_fcl_id = '$vrfyInfoFclId'
		// UNION ALL
		// SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
		// FROM do_truck_details_entry
		// WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
		
		$sql_tmpRsltSet = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
		FROM vcms_tmp_truck_dtl 
		WHERE verify_info_fcl_id = '$vrfyInfoFclId'
		UNION ALL
		SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
		FROM do_truck_details_entry
		WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
		$rslt_tmpRsltSet = $this->bm->dataSelectDb1($sql_tmpRsltSet);
		
		$data['stat'] = $stat;
		$data['rslt_tmpRsltSet'] = $rslt_tmpRsltSet;		
		
		echo json_encode($data);
	}

	function getSearchInfo(){
		$searchBy = $_GET['searchBy'];
		$searchValue = $_GET['searchValue'];
			
		if($searchBy == 'container'){
			$searchQuery = "SELECT distinct id,CONCAT(id,' - ',truck_id) AS details FROM do_truck_details_entry WHERE cont_no='$searchValue'";
		}
		else if($searchBy == 'bl')
		{
			$searchQuery = "SELECT distinct id,CONCAT(id,' - ',truck_id) AS details FROM do_truck_details_entry WHERE bl='$searchValue'";
		}
		else if($searchBy == 'id')
		{
			$searchQuery = "SELECT distinct id,CONCAT(id,' - ',truck_id) AS details FROM do_truck_details_entry WHERE id='$searchValue'";
		}

		$searchResult = $this->bm->dataSelectDB1($searchQuery);
		$data['searchResult'] = $searchResult;		
		
		echo json_encode($data);
	}
	
	function getIGMInfo()
	{
		$rot_no = $_GET['rot_no'];
		$bl_no = $_GET['bl_no'];
			
		$cnt_str="SELECT COUNT(*) as rtnValue FROM igm_details WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
		$cntResult = $this->bm->dataReturnDb1($cnt_str);
		
		$type_of_igm = "";
		$blType_BB = "";
		$mloName = "";
		$mloId = "";
		$shippingAgentName = "";
		$ffName = "";
		$msgFlag = 1;
		
		if($cntResult==0)
		{
			$cnt_str_sup="SELECT COUNT(*) AS rtnValue FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
			$cntSupResult = $this->bm->dataReturnDb1($cnt_str_sup);
			if($cntSupResult==0)
			{
				$msgFlag = 0;
			}
			else
			{
				$type_str_sup="SELECT igm_supplimentary_detail.type_of_igm AS rtnValue FROM igm_supplimentary_detail 
						WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
				$type_of_igm = $this->bm->dataReturnDb1($type_str_sup);
				$blType_BB = "HB";
			}
		}
		else
		{
			$type_str="SELECT igm_details.type_of_igm as rtnValue FROM igm_details 
					WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
			$type_of_igm = $this->bm->dataReturnDb1($type_str);
			$blType_BB = "MB";
		}
		
		if($type_of_igm!="")
		{
			if($type_of_igm=='BB')
			{	
				// "BB";
				$Submitee_Org_Id = "";
				
				$str="SELECT igm_details.Submitee_Org_Id
					FROM igm_details  
					WHERE igm_details.BL_No='$bl_no' AND igm_details.Import_Rotation_No='$rot_no'";
					
				$resltStr = $this->bm->dataSelectDb1($str);					
				for($i=0;$i<count($resltStr);$i++)
				{
					$Submitee_Org_Id=$resltStr[$i]['Submitee_Org_Id'];
				}
				if($Submitee_Org_Id!="")
					{
						$saDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$Submitee_Org_Id'";
						$resSHDtls = $this->bm->dataSelectDb1($saDtls);
						for($i=0;$i<count($resSHDtls);$i++)
						{
							$shippingAgentName=$resSHDtls[$i]['Organization_Name']." - (".$resSHDtls[$i]['ain'].")";
						}
					}
				// $strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,sh_agent_org_id,entry_time,sumitted_by)
				// VALUES('$rot_no','$bl_no','$blType_BB','$type_of_igm','$Submitee_Org_Id', NOW(),'$login_id')";
			}
			else
			{
				$queryStr="SELECT igm_supplimentary_detail.Submitee_Org_Id AS sup_org,igm_details.Submitee_Org_Id AS master_org,
				igm_supplimentary_detail.Submitee_Id
				FROM igm_supplimentary_detail 
				INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
				WHERE igm_supplimentary_detail.BL_No='$bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";
				$rsltStr = $this->bm->dataSelectDb1($queryStr);
				if(count($rsltStr)>0)
				{
					$bl_type = "HB";
					$sup_org = "";
					$master_org = "";
					
					for($i=0;$i<count($rsltStr);$i++)
					{
						$sup_org=$rsltStr[$i]['sup_org'];
						$master_org=$rsltStr[$i]['master_org'];
						$mloId=$rsltStr[$i]['Submitee_Id'];
					}
					
					
					if($sup_org!="")
					{
						$ffDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$sup_org'";
						$rowFFDtls = $this->bm->dataSelectDb1($ffDtls);
						for($i=0;$i<count($rowFFDtls);$i++)
						{
							$ffName=$rowFFDtls[$i]['Organization_Name']." - (".$rowFFDtls[$i]['ain'].")";
						}
						
						
					}

					if($master_org!="")
					{

						$mloDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$master_org'";
						$resMloDtls = $this->bm->dataSelectDb1($mloDtls);
						for($i=0;$i<count($resMloDtls);$i++)
						{
							$mloName= $resMloDtls[$i]['Organization_Name']." - (".$resMloDtls[$i]['ain'].")";
						}
						
					}


					//For finding container type & master bl when it's a house bl............Starts
					$strQry="select igm_supplimentary_detail.master_BL_No,igm_sup_detail_container.cont_status
					from igm_supplimentary_detail 
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
					INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					where igm_supplimentary_detail.Import_Rotation_No='$rot_no' and igm_supplimentary_detail.BL_No='$bl_no'";
					$rsltQry = $this->bm->dataSelectDb1($strQry);
					
					for($k=0;$k<count($rsltQry);$k++)
					{
						$cont_status=$rsltQry[$k]['cont_status'];
						$master_bl=$rsltQry[$k]['master_BL_No'];
					}
					$data['cont_status'] = $cont_status;
					$data['master_bl'] = $master_bl;
					//For finding container type & master bl when it's a house bl............Ends
					// $strInsert = "INSERT INTO edo_application_by_cf( rotation, bl, bl_type, igm_type, mlo, ff_org_id, entry_time, sumitted_by) 
					// VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org', NOW(), '$login_id')";
				}
				else
				{
					$queryStr="SELECT igm_details.Submitee_Org_Id AS master_org	FROM igm_details 
					WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
					$rsltStr = $this->bm->dataSelectDb1($queryStr);
					$bl_type = "MB";
					$master_org = "";
					
					for($i=0;$i<count($rsltStr);$i++)
					{
						$master_org=$rsltStr[$i]['master_org'];
					}
					if($master_org!="")
					{
						$mloDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$master_org'";
						$resMloDtls = $this->bm->dataSelectDb1($mloDtls);
						for($i=0;$i<count($resMloDtls);$i++)
						{
							$mloName= $resMloDtls[$i]['Organization_Name']." - (".$resMloDtls[$i]['ain'].")";
						}
						
					}
					// $strInsert = "INSERT INTO edo_application_by_cf( rotation, bl, bl_type, igm_type, mlo,  entry_time, sumitted_by) 
					// VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org', NOW(), '$login_id')";
				}					
			}
			
			//$resInsert = $this->bm->dataInsertDB1($strInsert);
			// if($resInsert==1)
			// {
				// $data['msg']='<font color="blue">Inserted Sucessfully.</font>';
			// }
		}

		//$searchResult = $this->bm->dataSelectDB1($type_str);
		$data['blType_BB'] = $blType_BB;		
		$data['type_of_igm'] = $type_of_igm;		
		$data['mloName'] = $mloName;		
		$data['mloId'] = $mloId;
		$data['shippingAgentName'] = $shippingAgentName;
		$data['ffName'] = $ffName;
		$data['msgFlag'] = $msgFlag;
		
		echo json_encode($data);
	}
	
	function getCNFAIN(){
		$edo_number = $_GET['edo_number'];
		$msgFlag = 0;
		$ain = "";
		$orgName = "";
		$cnt_str="SELECT COUNT(*) AS rtnValue
				FROM shed_mlo_do_info 
				INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
				INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
				INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
				WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";						
		$cntResult = $this->bm->dataReturnDb1($cnt_str);
		if($cntResult == 0){
			$msgFlag = 0;
		} else {
			$msgFlag = 1;			
			$str_ain="SELECT organization_profiles.AIN_No_New,Organization_Name
					FROM shed_mlo_do_info 
					INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
					INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
					INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
					WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";
			$cnf = $this->bm->dataSelectDb1($str_ain);
			for($i=0;$i<count($cnf);$i++) {
				$ain=$cnf[$i]['AIN_No_New'];
				$orgName=$cnf[$i]['Organization_Name'];
			}
			if($ain==""){
				$str_ain="SELECT organization_profiles.AIN_No,Organization_Name
					FROM shed_mlo_do_info 
					INNER JOIN edo_application_by_cf ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
					INNER JOIN users ON edo_application_by_cf.sumitted_by=users.login_id
					INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
					WHERE CONCAT(edo_mlo,LPAD(edo_sl,6,0),edo_year)='$edo_number'";
				$cnf = $this->bm->dataSelectDb1($str_ain);
				for($i=0;$i<count($cnf);$i++) {
					$ain=$cnf[$i]['AIN_No'];
					$orgName=$cnf[$i]['Organization_Name'];
				}
			}
		}
		// echo $msgFlag;
		$data['msgFlag'] = $msgFlag;
		$data['ain'] = $ain;
		$data['orgName'] = $orgName;
		
		echo json_encode($data);
	}
	
	function getCorrectCnfInfo(){
		$correct_cnf_ain = $_GET['correct_cnf_ain'];
		$ainValidityFlag = 0;
		$cnf_name = "";
		
		$cnt_str="SELECT COUNT(*) AS rtnValue FROM organization_profiles WHERE AIN_No='$correct_cnf_ain' OR AIN_No_New='$correct_cnf_ain'";						
		$cntResult = $this->bm->dataReturnDb1($cnt_str);
		if($cntResult == 0){
			$ainValidityFlag = 0;
		} else {
			$ainValidityFlag = 1;			
			$str_cnf_info="SELECT Organization_Name FROM organization_profiles WHERE AIN_No='$correct_cnf_ain' OR AIN_No_New='$correct_cnf_ain'";	
			$cnf_info = $this->bm->dataSelectDb1($str_cnf_info);
			for($i=0;$i<count($cnf_info);$i++) {
				$cnf_name=$cnf_info[$i]['Organization_Name'];
			}
		}
		
		$data['ainValidityFlag'] = $ainValidityFlag;
		$data['cnf_name'] = $cnf_name;
		
		echo json_encode($data);
	}

	function chkValidDriver(){
		$driverPassNo = $_GET['driverPassNo'];
		$truckSlot = $_GET['slot'];

		$sqlValidDriver = "SELECT COUNT(do_truck_details_entry.id) AS rtnValue FROM do_truck_details_entry 
		LEFT JOIN verify_info_fcl ON verify_info_fcl.id = do_truck_details_entry.verify_info_fcl_id
		WHERE do_truck_details_entry.driver_gate_pass = '$driverPassNo' AND verify_info_fcl.truck_slot = '$truckSlot' AND DATE(do_truck_details_entry.last_update) = DATE(NOW())";

		$rslt_validDriver = $this->bm->dataSelectDb1($sqlValidDriver);
		$data['rslt_validDriver']=$rslt_validDriver;
		
		echo json_encode($data);
	}

	function isCNFExist()
	{
		$cnf_lic_no=$_GET['cnf_lic_no'];
		
		$sql_CNFName="SELECT COUNT(*) AS rtnValue
		FROM sparcsn4.ref_bizunit_scoped 
		WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id LIKE '%$cnf_lic_no%'  LIMIT 1";
		
		$rslt_CNFName = $this->bm->dataSelect($sql_CNFName);
		$data['rslt_CNFName'] = $rslt_CNFName;

		echo json_encode($data);
	}
	
	// function changeChkState(){
	// 	$uploadId = $_GET['uploadId'];
	// 	$ipaddr = $_SERVER['REMOTE_ADDR'];
	// 	$msgFlag = 0;
	// 	$login_id = $this->session->userdata('login_id');
	// 	$strUpdateDetail="update shed_mlo_do_info set check_st='1', cpa_check_ip='$ipaddr',cpa_checked_by='$login_id',cpa_check_time=NOW() 
	// 					where id='$uploadId'";
	// 	$strUpdateStat = $this->bm->dataUpdateDB1($strUpdateDetail);	
		
		
	// 	$data['strUpdateStat'] = $strUpdateStat;
		
	// 	echo json_encode($data);
	// }

	function changeChkState()
	{
		$uploadId = $_GET['uploadId'];
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$msgFlag = 0;	
		$login_id = $this->session->userdata('login_id');
		$strUpdateDetail="update shed_mlo_do_info set check_st='1', cpa_check_ip='$ipaddr',cpa_checked_by='$login_id', cpa_check_time=NOW() 
					where id='$uploadId'";
		$strUpdateStat = $this->bm->dataUpdateDB1($strUpdateDetail);	
		$data['strUpdateStat'] = $strUpdateStat;

		// NTS API - start
		
		// /*
		$sql_chkBLType = "SELECT bl_type AS rtnValue
						FROM shed_mlo_do_info
						WHERE id='$uploadId'";
		$chkBLType = $this->bm->dataReturnDB1($sql_chkBLType);
		
		$sql_apprData = "";
		
		if($chkBLType=="MB")			// MBL
		{
			$sql_apprData = "SELECT imp_rot AS reg_no,shed_mlo_do_info.bl_no,
			edo_mlo AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
			shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,shed_mlo_do_info.office_code AS be_office_code,
			CONCAT(edo_mlo,LPAD(edo_sl,6,'0'),edo_year) AS do_no,
			shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
			(SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,
			igm_details.BL_No AS mlo_line_no,
			'' AS ff_line,
			igm_details.Pack_Number AS do_qty,
			igm_details.Pack_Description AS do_unit,

			igm_details.weight AS do_weight,

			REPLACE(imp_rot,'/',' ') AS manif_no,
			(SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no) AS cus_ro_no,

			IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no),'0000-00-00') AS cus_ro_dt,

			(SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no) AS be_exit_no,
			
			REPLACE(edo_application_by_cf.sumitted_by,'CF','') AS AIN,
			(SELECT u_name FROM users WHERE login_id=sumitted_by) AS cnf_name

			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			INNER JOIN igm_details ON igm_details.id=shed_mlo_do_info.igm_detail_id
			WHERE shed_mlo_do_info.id='$uploadId' AND shed_mlo_do_info.bl_type='MB'";
		}
		else
		{
			$sql_apprData = "SELECT imp_rot AS reg_no,shed_mlo_do_info.bl_no,
			igm_details.mlocode AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
			shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,shed_mlo_do_info.office_code AS be_office_code,
			CONCAT(edo_mlo,LPAD(edo_sl,6,'0'),edo_year) AS do_no,
			shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
			(SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,
			igm_details.BL_No AS mlo_line_no,
			igm_supplimentary_detail.BL_No AS ff_line,
			igm_supplimentary_detail.Pack_Number AS do_qty,
			igm_supplimentary_detail.Pack_Description AS do_unit,

			igm_supplimentary_detail.weight AS do_weight,

			REPLACE(imp_rot,'/',' ') AS manif_no,
			(SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no) AS cus_ro_no,

			IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no),'0000-00-00') AS cus_ro_dt,

			(SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
			WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=shed_mlo_do_info.bl_no) AS be_exit_no,
			
			REPLACE(edo_application_by_cf.sumitted_by,'CF','') AS AIN,
			(SELECT u_name FROM users WHERE login_id=sumitted_by) AS cnf_name
			
			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=shed_mlo_do_info.igm_detail_id
			INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			WHERE shed_mlo_do_info.id='$uploadId' AND shed_mlo_do_info.bl_type='HB'";
		}
		
		// $sql_apprData = "SELECT imp_rot AS reg_no,bl_no,edo_mlo AS mlo_code,edo_id,edo_application_by_cf.mlo,edo_application_by_cf.ff_org_id,
		// (SELECT AIN_No FROM organization_profiles WHERE id=mlo) AS mlo_line_no,
		// (SELECT AIN_No FROM organization_profiles WHERE id=ff_org_id) AS ff_line,
		// shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,office_code AS be_office_code,

		// (CASE
			// WHEN (LENGTH(edo_sl)=1) THEN CONCAT(edo_mlo,'00000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=2) THEN CONCAT(edo_mlo,'0000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=3) THEN CONCAT(edo_mlo,'000',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=4) THEN CONCAT(edo_mlo,'00',edo_sl,edo_year)
			// WHEN (LENGTH(edo_sl)=5) THEN CONCAT(edo_mlo,'0',edo_sl,edo_year)
			// ELSE CONCAT(edo_mlo,edo_sl,edo_year)
		// END) AS do_no,

		// shed_mlo_do_info.bl_type,do_date,valid_upto_dt AS do_valid_upto,
		// (SELECT u_name FROM users WHERE login_id=user_id) AS do_issued_by,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT Pack_Number FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT Pack_Number FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_qty,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT Pack_Description FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT Pack_Description FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_unit,

		// IF(shed_mlo_do_info.bl_type='HB',(SELECT weight FROM igm_supplimentary_detail WHERE Import_Rotation_No=reg_no AND BL_No=bl),(SELECT weight FROM igm_details WHERE Import_Rotation_No=reg_no AND BL_No=bl)) AS do_weight,

		// REPLACE(imp_rot,'/',' ') AS manif_no,
		// (SELECT recp_no FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id 
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no) AS cus_ro_no,

		// IFNULL((SELECT recp_date FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no),'0000-00-00') AS cus_ro_dt,

		// (SELECT place_dec FROM sad_info INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
		// WHERE manif_num LIKE CONCAT('%',manif_no,'%') AND sum_declare=bl_no) AS be_exit_no
		// FROM shed_mlo_do_info
		// INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
		// WHERE shed_mlo_do_info.id='$uploadId'";
		
		$rslt_apprData = $this->bm->dataSelectDB1($sql_apprData);
		
		$reg_no = $rslt_apprData[0]['reg_no'];
		$bl_no = $rslt_apprData[0]['bl_no'];
		$mlo_code = $rslt_apprData[0]['mlo_code'];
		$mlo_line_no = $rslt_apprData[0]['mlo_line_no'];
		$ff_line = $rslt_apprData[0]['ff_line'];
		$be_no = $rslt_apprData[0]['be_no'];
		$be_date = $rslt_apprData[0]['be_date'];
		$be_office_code = $rslt_apprData[0]['be_office_code'];
		$do_no = $rslt_apprData[0]['do_no'];
		$do_date = $rslt_apprData[0]['do_date'];
		$do_valid_upto = $rslt_apprData[0]['do_valid_upto'];
		$do_issued_by = $rslt_apprData[0]['do_issued_by'];
		$do_qty = $rslt_apprData[0]['do_qty'];
		$do_unit = $rslt_apprData[0]['do_unit'];
		$do_weight = $rslt_apprData[0]['do_weight'];
		$cus_ro_no = $rslt_apprData[0]['cus_ro_no'];
		$cus_ro_dt = $rslt_apprData[0]['cus_ro_dt'];
		$be_exit_no = $rslt_apprData[0]['be_exit_no'];
		$AIN = $rslt_apprData[0]['AIN'];
		$cnf_name = $rslt_apprData[0]['cnf_name'];
		
		/*		-- API stop
		$url = "http://192.168.16.243:8082/edoInfo/add";

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST						
		$jsonData = '{
				"reg_no": "'.$reg_no.'",
				"bl_no": "'.$bl_no.'",
				"mlo_code": "'.$mlo_code.'",
				"mlo_line_no": "'.$mlo_line_no.'",
				"ff_line": "'.$ff_line.'",
				"be_no": "'.$be_no.'",
				"be_dt": "'.$be_date.'",
				"be_office_code": "'.$be_office_code.'",
				"do_no": "'.$do_no.'",
				"do_dt": "'.$do_date.'",
				"do_valid_upto": "'.$do_valid_upto.'",
				"do_issued_by": "'.$do_issued_by.'",
				"do_qty": "'.$do_qty.'",
				"do_unit": "'.$do_unit.'",
				"do_weight": "'.$do_weight.'",
				"cus_ro_no": "'.$cus_ro_no.'",
				"cus_ro_dt": "'.$cus_ro_dt.'",
				"be_exit_no": "'.$be_exit_no.'",
				"cnf_ain": "'.$AIN.'",
				"cnf_name": "'.$cnf_name.'"
				}';						
				
		//print_r($jsonData);
		
		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$result = curl_exec($ch);
		
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		
		//print_r("content : ".$content); 
		
		//print_r("code : ".$code); 
		
		// Close cURL resource
		curl_close($ch);
		*/	// -- API stop
		
		// */
		
		// NTS API - end


		// Generate Notification

		$cpa_org_id = $this->session->userdata('org_id');
		$user = $this->session->userdata('login_id');
		$edoId = $_GET['edoId'];

		// $query_bl_type="SELECT bl_type AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $bl_type=$this->bm->dataReturnDb1($query_bl_type);
		//$submitted_by_query="SELECT sumitted_by AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $submitted_by=$this->bm->dataReturnDb1($submitted_by_query);
		// $cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
		//$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);
		// $mlo_org_id_query="SELECT mlo AS rtnValue FROM edo_application_by_cf WHERE id='$edoId'";
		// $mlo_org_id=$this->bm->dataReturnDB1($mlo_org_id_query);

		$queryEDODtls="select * from edo_application_by_cf where id='$edoId'";
		$edoDtls = $this->bm->dataSelectDb1($queryEDODtls);

		$org_notified = "";
		$bl_type = "";
		$submitted_by = "";
		$mlo_org_id = "";
		
		for($i=0;$i<count($edoDtls);$i++)
		{
			$org_notified = $edoDtls[$i]['ff_org_id'];
			$bl_type = $edoDtls[$i]['bl_type'];
			$submitted_by = $edoDtls[$i]['sumitted_by'];
			$mlo_org_id = $edoDtls[$i]['mlo'];
		}

		$cf_org_id_query="SELECT org_id  AS rtnValue FROM users WHERE login_id='$submitted_by'";
		$cf_org_id=$this->bm->dataReturnDb1($cf_org_id_query);
		
		if($bl_type == "HB")
		{
			$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$cf_org_id',4,0,'$user',NOW())";
			$this->bm->dataInsertDB1($edoNotifyCfQuery);

			$edoNotifyMloQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$mlo_org_id',4,0,'$user',NOW())";
			$this->bm->dataInsertDB1($edoNotifyMloQuery);

			$edoNotifyFFQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$org_notified',4,0,'$user',NOW())";
			$this->bm->dataInsertDB1($edoNotifyFFQuery);

			$cpaLifeStatQuery = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edoId' AND org_notified='$cpa_org_id' AND notification_st = 3";
			$this->bm->dataUpdateDB1($cpaLifeStatQuery);

		}
		else
		{

			$edoNotifyCfQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$cf_org_id',4,0,'$user',NOW())";
			$this->bm->dataInsertDB1($edoNotifyCfQuery);

			$edoNotifyMloQuery = "INSERT INTO edo_notification(application_id,org_notified,notification_st,seen_st,org_notify_by,generate_time)
			VALUES('$edoId','$mlo_org_id',4,0,'$user',NOW())";
			$this->bm->dataInsertDB1($edoNotifyMloQuery);

			$cpaLifeStatQuery = "UPDATE edo_notification SET life_st = 1 WHERE application_id='$edoId' AND org_notified='$cpa_org_id' AND notification_st = 3";
			$this->bm->dataUpdateDB1($cpaLifeStatQuery);
		}
		
		echo json_encode($data);
	}

	function getuserName(){
		$loginId = $_GET['loginId'];

		$userNameQuery = "SELECT u_name FROM users WHERE login_id='$loginId'";

		$userName = $this->bm->dataSelectDb1($userNameQuery);
		$data['userName']=$userName;

		echo json_encode($data);
	}

	function chkAssignmentInfo(){
		$blNo = $_GET['blNo'];
		$rotNo = $_GET['rotNo'];

		$rotNo = str_replace("_","/",$rotNo);	

		$chkAssignmentQuery = "SELECT COUNT(*) as rtnValue FROM assignment_request_data WHERE rotation = '$rotNo' AND bl = '$blNo'";
		$dataChkAssignment = $this->bm->dataSelectDB1($chkAssignmentQuery);
		$chkAssignment = 0;
		for($i=0;$i<count($chkAssignment);$i++){
			$chkAssignment = $dataChkAssignment[0]['rtnValue'];
		}

		$data['chkAssignment'] = $chkAssignment;
		
		echo json_encode($data);
	}

	/*function getJettySrkr()
	{
		
		$cont = $_GET['cont'];
		$rot = $_GET['rot'];
		$rot = str_replace("_","/",$rot);

		$sql_card_number = "SELECT card_number,importer_mobile_no
		FROM verify_info_fcl
		INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=verify_info_fcl.jetty_sirkar_id
		WHERE rotation = '$rot' AND cont_number = '$cont'";

		$data_card_number = $this->bm->dataSelectDB1($sql_card_number);
		$card_number = null;
		$mobile = null;
		for($i=0;$i<count($data_card_number);$i++){
			$card_number = $data_card_number[$i]['card_number'];
			$mobile = $data_card_number[$i]['importer_mobile_no'];
		}

		$sql_agentInfo = "SELECT agent_name,agent_code FROM vcms_vehicle_agent WHERE card_number='$card_number'";
		$data_agentInfo = $this->bm->dataSelectDB1($sql_agentInfo);
		$agent_name = null;
		$agent_code = null;
		for($i=0;$i<count($data_agentInfo);$i++){
			$agent_name = $data_agentInfo[$i]['agent_name'];
			$agent_code = $data_agentInfo[$i]['agent_code'];
		}

		$sql_truck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$cont' AND DATE(last_update) = DATE(NOW())";
		$data_truck = $this->bm->dataSelectDB1($sql_truck);
		$truckAdded = null;

		for($i=0;$i<count($data_truck);$i++)
		{
			$truckAdded = $data_truck[$i]['rtnValue'];
		}

		 $sql_slot = "SELECT assignment_slot FROM ctmsmis.tmp_oracle_assignment WHERE cont_no='$cont'";
		$data_slot = $this->bm->dataSelectDb2($sql_slot);
		$slot = null;

		for($i=0;$i<count($data_slot);$i++)
		{
			$slot = $data_slot[$i]['assignment_slot'];
		}

		$data['slot'] = $slot;
		$data['truckAdded'] = $truckAdded;
		$data['card_number'] = $card_number;
		$data['mobile'] = $mobile;
		$data['agent_name'] = $agent_name;
		$data['agent_code'] = $agent_code;
		
		echo json_encode($data);
	}*/
	
	function getJettySrkr()
	{
		$cont = $_GET['cont'];
		
		$rot = $_GET['rot'];
		$rot = str_replace("_","/",$rot);

		$sql_card_number = "SELECT card_number,importer_mobile_no
		FROM verify_info_fcl
		INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=verify_info_fcl.jetty_sirkar_id
		WHERE rotation = '$rot' AND cont_number = '$cont'";

		$data_card_number = $this->bm->dataSelectDB1($sql_card_number);
		$card_number = null;
		$mobile = null;
		for($i=0;$i<count($data_card_number);$i++){
			$card_number = $data_card_number[$i]['card_number'];
			$mobile = $data_card_number[$i]['importer_mobile_no'];
		}

		$sql_agentInfo = "SELECT agent_name,agent_code FROM vcms_vehicle_agent WHERE card_number='$card_number'";
		$data_agentInfo = $this->bm->dataSelectDB1($sql_agentInfo);
		$agent_name = null;
		$agent_code = null;
		for($i=0;$i<count($data_agentInfo);$i++){
			$agent_name = $data_agentInfo[$i]['agent_name'];
			$agent_code = $data_agentInfo[$i]['agent_code'];
		}

		$sql_truck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$cont' AND DATE(last_update) = DATE(NOW())";
		$data_truck = $this->bm->dataSelectDB1($sql_truck);
		$truckAdded = null;

		for($i=0;$i<count($data_truck);$i++)
		{
			$truckAdded = $data_truck[$i]['rtnValue'];
		}

		$sql_slot = "SELECT assignment_slot FROM ctmsmis.tmp_vcms_assignment WHERE cont_no='$cont'";
		 //$data_slot = $this->bm->dataSelect($sql_slot);
		$data_slot = $this->bm->dataSelectDb2($sql_slot);
		$slot = null;

		for($i=0;$i<count($data_slot);$i++)
		{
			$slot = $data_slot[$i]['assignment_slot'];
		}

		$data['slot'] = $slot;
		$data['truckAdded'] = $truckAdded;
		$data['card_number'] = $card_number;
		$data['mobile'] = $mobile;
		$data['agent_name'] = $agent_name;
		$data['agent_code'] = $agent_code;
		
		echo json_encode($data);
	}

	function getJettySrkrLcl()
	{
		$cont = $_GET['cont'];
		$rot = $_GET['rot'];
		$rot = str_replace("_","/",$rot);
		$bl = $_GET['bl'];

		$sql_card_number = "SELECT card_number,importer_mobile_no
		FROM lcl_dlv_assignment
		INNER JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=lcl_dlv_assignment.jetty_sirkar_id
		WHERE rot_no = '$rot' AND bl_no = '$bl'";

		$data_card_number = $this->bm->dataSelectDB1($sql_card_number);
		$card_number = null;
		$mobile = null;
		for($i=0;$i<count($data_card_number);$i++){
			$card_number = $data_card_number[$i]['card_number'];
			$mobile = $data_card_number[$i]['importer_mobile_no'];
		}

		$sql_agentInfo = "SELECT agent_name,agent_code FROM vcms_vehicle_agent WHERE card_number='$card_number'";
		$data_agentInfo = $this->bm->dataSelectDB1($sql_agentInfo);
		$agent_name = null;
		$agent_code = null;
		for($i=0;$i<count($data_agentInfo);$i++){
			$agent_name = $data_agentInfo[$i]['agent_name'];
			$agent_code = $data_agentInfo[$i]['agent_code'];
		}

		$sql_truck = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no='$cont' AND DATE(last_update) = DATE(NOW())";
		$data_truck = $this->bm->dataSelectDB1($sql_truck);
		$truckAdded = null;

		for($i=0;$i<count($data_truck);$i++)
		{
			$truckAdded = $data_truck[$i]['rtnValue'];
		}

		$sql_slot = "SELECT truck_slot FROM lcl_dlv_assignment WHERE rot_no = '$rot' AND bl_no = '$bl'";
		$data_slot = $this->bm->dataSelectDB1($sql_slot);
		$slot = null;

		for($i=0;$i<count($data_slot);$i++)
		{
			$slot = $data_slot[$i]['truck_slot'];
		}

		$data['slot'] = $slot;
		$data['truckAdded'] = $truckAdded;
		$data['card_number'] = $card_number;
		$data['mobile'] = $mobile;
		$data['agent_name'] = $agent_name;
		$data['agent_code'] = $agent_code;
		
		echo json_encode($data);
	}

	function getAssignment(){
		$cf = $_GET['cf'];

		$sql_lic = "SELECT License_No FROM organization_profiles 
		INNER JOIN users ON users.org_id = organization_profiles.id
		WHERE login_id = '$cf'";

		$data_lic = $this->bm->dataSelectDB1($sql_lic);
		$org_license = "";
		for($i=0;$i<count($data_lic);$i++){
			$org_license = $data_lic[0]['License_No'];
		}

		$data_assignment = "";
		if($org_license != ""){
			$sql_assignment = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
			FROM ctmsmis.tmp_oracle_assignment 
			WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ORDER BY assignmentDate DESC";

			$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
		}

		$data_jsInfo = "";
		if($org_license != ""){
			$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
			FROM vcms_vehicle_agent
			INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
			WHERE agency_code = '$org_license' AND agent_type = 'Jetty Sircar'";

			$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
		}
		
		$data['data_jsInfo'] = $data_jsInfo;
		$data['data_assignment'] = $data_assignment;
		
		echo json_encode($data);
	}
	
	function getCollectedBy()
	{		
		$visit_id = $_GET['visit_id'];
		
		$sql_chkCnt = "SELECT COUNT(*) AS rtnValue
		FROM do_truck_details_entry
		WHERE id=$visit_id";
		$chkCnt= $this->bm->dataReturnDb1($sql_chkCnt);					
		
		$collectedBy = "";
		$resMsg = "";
		
		if($chkCnt>0)		// exists
		{
			$sql_collectedBy = "SELECT paid_collect_by AS rtnValue
			FROM do_truck_details_entry
			WHERE id=$visit_id";
			$collectedBy = $this->bm->dataReturnDb1($sql_collectedBy);
			
			if($collectedBy!=null or $collectedBy!="")		// collection done
			{
				$data['collectedBy']=$collectedBy;
				
				$resMsg = "valid";
				
				$data['collectedBy']=$collectedBy;
				$data['resMsg']=$resMsg;
				echo json_encode($data);
			}
			else											// not collected before
			{
				$resMsg = "Payment was not collected";
				$data["resMsg"]=$resMsg;
				echo json_encode($data);
			}					
		}
		else		// not exists
		{
			$resMsg = "Please give a valid visit id";
			$data["resMsg"]=$resMsg;
			echo json_encode($data);
		}				
	}

	function showJsPic()
	{
		$jsGatePass=$_GET['jsGatePass'];
		
		$sql_Jspic="SELECT agent_photo FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
		$data_Jspic = $this->bm->dataSelectDb1($sql_Jspic);
		$jsPic = "";
		for($i=0;$i<count($data_Jspic);$i++){
			$jsPic = $data_Jspic[$i]['agent_photo'];
		}

		$data['result']=$jsPic;
		echo json_encode($data);
	}

	function getSectionDetail()
	{
		$orgType = $_GET['orgType'];

		$sql_SelectSectionDetails="SELECT * FROM users_section_detail WHERE org_type_id = '$orgType'";
		$data_SelectSectionDetails = $this->bm->dataSelectDb1($sql_SelectSectionDetails);

		$data['result']=$data_SelectSectionDetails;
		echo json_encode($data);
	}
	function getContainer()
	{
	     $rotation=$_GET['rotation'];
		 $bl= $_GET['bl'];
		 
		 $query="SELECT cont_number FROM igm_details
			INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$bl' AND igm_detail_container.late_submit_flag='0'";
		 $result = $this->bm->dataSelectDb1($query);
		 
		 // $query="SELECT cont_number FROM igm_details
			// INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
			// WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$bl' AND igm_detail_container.late_submit_flag='0'
			// AND cont_number NOT IN(
			// SELECT cont_number FROM igm_sup_detail_container
			// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			// INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			// WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$bl'
			// )";
		 // $result = $this->bm->dataSelectDb1($query);
		 
		 $queryForBl="SELECT BL_No
		 FROM igm_supplimentary_detail
		 WHERE Import_Rotation_No='$rotation' AND master_BL_No='$bl'";
		 //echo $count=count($result);
		 $resultForBl=$this->bm->dataSelectDb1($queryForBl);
		 $data['containers']= $result;
		 $data['resultBl']=$resultForBl;
		 echo json_encode($data);


	}
	
	function getIgmMasterContainer()
	{
	     $rotation=$_GET['rotation'];
		 $bl= $_GET['bl'];
		 $query="SELECT cont_number FROM igm_details
		 INNER JOIN igm_detail_container ON igm_details.id=igm_detail_container.igm_detail_id
		 WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$bl'";
		 $result = $this->bm->dataSelectDb1($query);
		 $data['containers']= $result;
		 echo json_encode($data);
	}
	
	
	function getUnitInfo()
	{
		$rot = $_GET["rot"];
		$query = "SELECT unit AS rtnValue FROM auction_handover WHERE rotation_no='$rot' LIMIT 1 ";		
		$unit=$this->bm->dataReturnDB1($query);
		echo json_encode($unit);
	}
	
	
	// igm correction - start
	
	public function blCheck()
	{
		$rotation=$_GET['rotation'];
		$bl= $_GET['bl'];

		$sql_igmDtlId="SELECT id
		FROM igm_supplimentary_detail
		WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
		$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
		 //$igmType="sup";
		$data["igmType"]="sup";
		$row=count($igmDtlId);

		if($row==0)
		{
			$sql_igmDtlId="SELECT id 
			FROM igm_details
			WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
			$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			$data["igmType"]="dtl";					
		}

		$data['result']=$igmDtlId;
		echo json_encode($data);				
	}
	
	public function containerCheck()
	{		 
		$rotation=$_GET['rotation'];
		$container= $_GET['container'];
		$bl= $_GET['bl'];
		 
 
		// $qr="SELECT cont_number,cont_size,cont_height,Cont_gross_weight,cont_seal_number,cont_status,cont_number_packaages
		// FROM igm_detail_container
		//INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
		// WHERE igm_details.Import_Rotation_No='$rotation' AND cont_number='$container'";
		$qr="SELECT igm_sup_detail_container.id
		FROM igm_sup_detail_container
		INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND cont_number='$container' AND
		igm_supplimentary_detail.BL_No='$bl'";
		$r = $this->bm->dataSelectDb1($qr);
		//$igmType="sup";
		$data["igmType"]="sup";
		 
		$row=count($r);
 
		if($row==0)
		{
			$qr="SELECT igm_detail_container.id
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
			WHERE igm_details.Import_Rotation_No='$rotation' AND cont_number='$container' AND igm_details.BL_No='$bl' ";
			$r=$this->bm->dataSelectDB1($qr);
			$data["igmType"]="dtl";
		}	
 
		$data['result']=$r;
		echo json_encode($data);				
	}
	
	public function getSelectedDetail()
	{			
		$rotation=$_GET['rotation'];
		$bl= $_GET['bl'];
		$value=$_GET['value'];
		$igm_type=$_GET['igm_type'];
		
		if($value=="Pack_Info")		// or consignee, notify, exporter
		{
			if($igm_type=="dtl")
			{
				$sql_igmDtlId="SELECT Pack_Number,Pack_Description,Pack_Marks_Number
				FROM igm_details
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			}
			else
			{
				$igmDtlId="SELECT Pack_Number,Pack_Description,Pack_Marks_Number
				FROM igm_supplimentary_detail
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($igmDtlId);
			}
		}
		else if($value=="Exporter_Info")		// or consignee, notify, exporter
		{
			if($igm_type=="dtl")
			{
				$sql_igmDtlId="SELECT Exporter_name,Exporter_address
				FROM igm_details
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			}
			else
			{
				$igmDtlId="SELECT Exporter_name,Exporter_address
				FROM igm_supplimentary_detail
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($igmDtlId);
			}
		}
		else if($value=="Notify_Info")		// or consignee, notify, exporter
		{
			if($igm_type=="dtl")
			{
				$sql_igmDtlId="SELECT Notify_code,Notify_name,Notify_address,NotifyDesc
				FROM igm_details
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			}
			else
			{
				$igmDtlId="SELECT Notify_code,Notify_name,Notify_address,NotifyDesc
				FROM igm_supplimentary_detail
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($igmDtlId);
			}
		}
		else if($value=="Consignee_Info")		// or consignee, notify, exporter
		{
			if($igm_type=="dtl")
			{
				$sql_igmDtlId="SELECT Consignee_code,Consignee_name,Consignee_address,ConsigneeDesc
				FROM igm_details
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			}
			else
			{
				$igmDtlId="SELECT Consignee_code,Consignee_name,Consignee_address,ConsigneeDesc
				FROM igm_supplimentary_detail
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($igmDtlId);
			}
		}
		else		// for others
		{
			if($igm_type=="dtl")
			{
				$sql_igmDtlId="SELECT $value as rnvalue
				FROM igm_details
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
			}
			else
			{
				$igmDtlId="SELECT $value as  rnvalue
				FROM igm_supplimentary_detail
				WHERE Import_Rotation_No='$rotation' AND BL_No='$bl'";
				$igmDtlId = $this->bm->dataSelectDb1($igmDtlId);
			}	
		}
		
	
		$data['result']=$igmDtlId;
		$data['column']=$value;
		echo json_encode($data);						
	}
	
	public function getSelectedContainerDetail()
	{		
		$rotation=$_GET['rotation'];
		$container= $_GET['container'];
		$bl= $_GET['bl'];
		$value=$_GET['value'];
		$igm_type=$_GET['igm_type'];
		if($igm_type=="dtl")
		{
			$sql_igmDtlId="SELECT $value as rnvalue 
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
			WHERE igm_details.Import_Rotation_No='$rotation' AND cont_number='$container' AND igm_details.BL_No='$bl'";
			$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
		}
		else
		{

			$sql_igmDtlId="SELECT $value AS rnvalue 
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND cont_number='$container'  AND
			igm_supplimentary_detail.BL_No='$bl'";
			$igmDtlId = $this->bm->dataSelectDb1($sql_igmDtlId);
		}
	
		$data['result']=$igmDtlId;
		$data['column']=$value;
		echo json_encode($data);						
	}
	
	
	function getBLlist()
	{
		$rot_no = $_GET['rot_no'];
		$impRot = str_replace("_","/",$rot_no);
		$auctionBLstr="SELECT  DISTINCT auction_handover.house_bl AS bl_no, obpc_number FROM auction_handover 
						WHERE auction_handover.rotation_no='$impRot' AND auction_handover.house_bl!=''  AND  obpc_number=''
						UNION
						SELECT  DISTINCT  auction_handover.bl_no AS bl_no, obpc_number FROM auction_handover 
						WHERE auction_handover.rotation_no='$impRot' AND auction_handover.house_bl=''  AND  obpc_number=''";
		$auctionBLs = $this->bm->dataSelectDb1($auctionBLstr);
		echo json_encode($auctionBLs);	
	}
	
	function getRLinfo()
	{
		$bl = $_GET['bl'];
		$rot_no = $_GET['rot_no'];
		$impRot = str_replace("_","/",$rot_no);
		$auctionRLstr="SELECT id, rl_no,rl_date, 'mbl' AS bl_type  FROM auction_handover WHERE auction_handover.rotation_no='$impRot' AND auction_handover.bl_no='$bl'
						UNION
						SELECT id, rl_no,rl_date, 'hbl' AS bl_type FROM auction_handover WHERE auction_handover.rotation_no='$impRot' AND auction_handover.house_bl='$bl'";
		$auctionRLs = $this->bm->dataSelectDb1($auctionRLstr);
		echo json_encode($auctionRLs);	
	}
	
	
	function getContStatus()
	{
		$impRot = $_GET['impRot'];
		$blNo = $_GET['blNo'];
		$contNo = $_GET['contNo'];
		
		$impRot = str_replace("_","/",$impRot);
		
		$igmType = "";
		$igmContId = "";
		$contStatus = "";
		
		$sql_contStatus = "SELECT igm_sup_detail_container.id,cont_status
		FROM igm_supplimentary_detail 
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$impRot' AND BL_No='$blNo' AND cont_number='$contNo'";
		$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);
		
		if(count($rslt_contStatus)>0)
		{
			$igmType = "supDtl";
			$igmContId = $rslt_contStatus[0]['id'];
			$contStatus = $rslt_contStatus[0]['cont_status'];
		}
		
		if($contStatus=="" or $contStatus==null)
		{
			$sql_contStatus = "SELECT igm_details.id,cont_status
			FROM igm_details 
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$impRot' AND BL_No='$blNo' AND cont_number='$contNo'";
			$rslt_contStatus = $this->bm->dataSelectDB1($sql_contStatus);
			
			$igmType = "dtl";
			$igmContId = $rslt_contStatus[0]['id'];
			$contStatus = $rslt_contStatus[0]['cont_status'];
		}
		
		$data['igmType']=$igmType;
		$data['igmContId']=$igmContId;
		$data['contStatus']=$contStatus;
		echo json_encode($data);
	}
	 
	// igm correction - end
	
	function insertAppQty()
	{
		$id = $_GET['id'];
		$apprvQty = $_GET['qty'];
		$login_id = $this->session->userdata('login_id');

		$apprvQuery = "UPDATE ctmsmis.water_demand_info SET dockMaster_aprv_qty = '$apprvQty', dockMaster_apprv_st = 1, dockMaster_aprv_by = '$login_id', dockMaster_aprv_time = NOW() WHERE id = '$id'";
		$insertSts = $this->bm->dataInsertDb2($apprvQuery);

		$data['status'] = $insertSts;
		echo json_encode($data);
	}

	function insertBurge()
	{
		$id = $_GET['id'];
		$burge = $_GET['burge'];
		$login_id = $this->session->userdata('login_id');
		
		$apprvQuery = "UPDATE ctmsmis.water_demand_info SET burge_name = '$burge' WHERE id = '$id'";
		$insertSts = $this->bm->dataInsertDb2($apprvQuery);	

		$data['status'] = $insertSts;
		echo json_encode($data);
	}
	 
	
	
	
}
