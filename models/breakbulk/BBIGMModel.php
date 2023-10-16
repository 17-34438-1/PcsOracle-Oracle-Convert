<?php


class BBIGMModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       $this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }

   function BBIGMList($type) {
    try {
        
        // $this->db->limit(5, 0);
        // $this->db->select('igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
        // igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
        // igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
        // igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
        // igm_masters.file_clearence_date,Organization_Name as org_name,igm_masters.Submitee_Org_Type 
        // as Submitee_Org_Type,igm_masters.S_Org_License_Number as S_Org_License_Number,igm_masters.Submission_Date 
        // as Submission_Date,igm_masters.flag as flag,igm_masters.imo as imo,igm_masters.line_belongs_to as line_belongs_to ');
        // $this->db->from('igm_masters');
        // $this->db->join('vessels_berth_detail', 'vessels_berth_detail.igm_id = igm_masters.id', 'left');
        // $this->db->join('organization_profiles', 'organization_profiles.id = igm_masters.Submitee_Org_Id', 'left');
        // $this->db->where('vsl_dec_type', $type );
        // $this->db->order_by('file_clearence_date', 'desc');

        $generalIGM_QueryForBB =  "SELECT  igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
        igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
        igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
        igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
        igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type 
        AS Submitee_Org_Type,igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date 
        AS Submission_Date,igm_masters.flag AS flag,igm_masters.imo AS imo,igm_masters.line_belongs_to AS line_belongs_to,
        igm_masters.VoyNoExp,igm_masters.grt,igm_masters.nrt,igm_masters.loa_cm,igm_masters.radio_call_sign,igm_masters.beam_cm
        FROM igm_masters
	    LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
	    LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
	    WHERE vsl_dec_type = 'BB' AND DATE(Submission_Date) > '2022-01-01' ORDER BY file_clearence_date DESC";

        $query = $this->db->query($generalIGM_QueryForBB);

       
        $data=$query->result_array();

    //    $data = $this->db->get()->result_array();
        
        return $data;

    } catch (Exception $ex) {
        return FALSE;
    }
}

function GetBBVesselInformation($rot){
    $query="SELECT  VSL_VESSELS.NAME,VSL_VESSELS.LLOYDS_ID,VSL_VESSELS.RADIO_CALL_SIGN,REF_COUNTRY.CNTRY_NAME,
    VSL_VESSEL_CLASSES.LOA_CM,VSL_VESSEL_CLASSES.BEAM_CM,VSL_VESSEL_CLASSES.GROSS_REGISTERED_TON,
    VSL_VESSEL_CLASSES.NET_REGISTERED_TON
    FROM VSL_VESSEL_VISIT_DETAILS 
    INNER JOIN  VSL_VESSELS  ON VSL_VESSELS.gkey = vsl_vessel_visit_details.vessel_gkey
    INNER JOIN REF_COUNTRY ON REF_COUNTRY.CNTRY_CODE=VSL_VESSELS.COUNTRY_CODE
    INNER JOIN  VSL_VESSEL_CLASSES ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey
    WHERE vsl_vessel_visit_details.ib_vyg='$rot'";
    $queryData=$this->oracledb->query($query);
    return $queryData->result_array();
}


   function ViewIGMSubList($CODE,$type) {
    try {
         $getDataQueryFromIGM =  "select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
         Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
         as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
         Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
         as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
         igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
         as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
         as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
         delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
         igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
         (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
         as Organization_Name,
         (select AIN_No from organization_profiles orgs where 
         orgs.id=igms.Submitee_Org_Id) as AIN_No,

         imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,
         Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
         Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
         from  igm_details igms 
         left outer Join igm_navy_response Navyresponse 
         on Navyresponse.igm_details_id =igms.id where igms.IGM_id=$CODE and (igms.type_of_igm='$type') 
         and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10)  ORDER BY Line_No ASC";	
        
       
        $query=$this->db->query($getDataQueryFromIGM);
        $data=$query->result_array();
        return $data;

    } catch (Exception $ex) {
        return FALSE;
    }
}   
function ViewIGMSubDetailsAllData($type) {
    try {
         $getDataQueryFromIGM =  "SELECT igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
         Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
         as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
         Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
         as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
         igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
         as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
         as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
         delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
         igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
         (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
         as Organization_Name,(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No,imco,un,extra_remarks,igms.Submission_Date
         FROM  igm_details igms WHERE igms.type_of_igm='$type' AND igms.final_submit=1 AND (igms.PFstatus=1 OR igms.PFstatus=10) AND DATE(igms.Submission_Date) > '2023-01-01' ORDER BY igms.Line_No ASC";	
       
       //echo $getDataQueryFromIGM;
       
       $query=$this->db->query($getDataQueryFromIGM);
        $data=$query->result_array();
        return $data;

    } catch (Exception $ex) {
        return FALSE;
    }
}   


function ViewIGMSubDetailsSearchData($type,$serach_id,$search_data,$rotation){
    
    if($serach_id ==1){//Search BL NO
        $search="AND igms.BL_No='$search_data'".''."AND igms.Import_Rotation_No='$rotation'".'';
    }else if($serach_id ==2){//Search Line No
        $search="AND igms.Line_No='$search_data'".''."AND igms.Import_Rotation_No='$rotation'".'';
    }else{//Search Rotation
        $search="AND igms.Import_Rotation_No='$search_data'".'';
    }
    try {
        $getDataQueryFromIGM =  "SELECT igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
        Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
        as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
        Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
        as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
        igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
        as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
        as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
        delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
        igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
        (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
        as Organization_Name,(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No,imco,un,extra_remarks,igms.Submission_Date
        FROM  igm_details igms WHERE igms.type_of_igm='$type' AND igms.final_submit=1 AND (igms.PFstatus=1 OR igms.PFstatus=10) AND DATE(igms.Submission_Date) > '2023-01-01' $search ORDER BY igms.Line_No ASC";	
      
      //echo $getDataQueryFromIGM;
      
      $query=$this->db->query($getDataQueryFromIGM);
       $data=$query->result_array();
       return $data;

   } catch (Exception $ex) {
       return FALSE;
   }
}
function ViewIGMSubDetailsSearchDataForView($type,$serach_id,$search_data,$rotation){
    
    if($serach_id ==1){//Search BL NO
        $search="AND igms.BL_No='$search_data'".'';
    }else if($serach_id ==2){//Search Line No
        $search="AND igms.Line_No='$search_data'".'';
    }else{//Search Rotation
        $search="AND igms.Import_Rotation_No='$search_data'".'';
    }
    try {
        $getDataQueryFromIGM =  "SELECT igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
        Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
        as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
        Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
        as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
        igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
        as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
        as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
        delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
        igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
        (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
        as Organization_Name,(select AIN_No from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) as AIN_No,imco,un,extra_remarks,igms.Submission_Date
        FROM  igm_details igms WHERE igms.type_of_igm='$type' AND igms.final_submit=1 AND (igms.PFstatus=1 OR igms.PFstatus=10) AND DATE(igms.Submission_Date) > '2023-01-01' $search ORDER BY igms.Line_No ASC";	
      
      //echo $getDataQueryFromIGM;
      
      $query=$this->db->query($getDataQueryFromIGM);
       $data=$query->result_array();
       return $data;

   } catch (Exception $ex) {
       return FALSE;
   }
}
function ViewIGMSubAllList2($type) {
    //    echo "select igms.id as id,igms.IGM_id as IGM_id,igms.Import_Rotation_No as 
//        Import_Rotation_No,igms.Line_No as Line_No, igms.BL_No as BL_No,igms.Pack_Number 
//        as Pack_Number,igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as 
//        Pack_Marks_Number, igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods 
//        as Date_of_Entry_of_Goods,igms.weight as weight,igms.weight_unit,igms.net_weight,
//        igms.net_weight_unit, igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date 
//        as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered, igms.No_of_Pack_Discharged 
//        as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.AFR as AFR,igms.int_block as int_block,igms.R_No as R_No,igms.R_Date as R_Date,
//        delivery_block_stat,igms.ConsigneeDesc,final_submit_date,
//        igms.NotifyDesc,igms.navy_comments,igms.Submitee_Org_Id,igms.mlocode,igms.type_of_igm as type_of_igm,
//        (select Organization_Name from organization_profiles orgs where orgs.id=igms.Submitee_Org_Id) 
//        as Organization_Name,
//        (select AIN_No from organization_profiles orgs where 
//        orgs.id=igms.Submitee_Org_Id) as AIN_No,
//        imco,un,extra_remarks,Navyresponse.response_details1,Navyresponse.response_details3,Navyresponse.response_details2,
//        Navyresponse.hold_application,Navyresponse.rejected_application,Navyresponse.auto_no as submitId,
//        Navyresponse.final_amendment , Navyresponse.appsubmitdate ,Navyresponse.navy_response_to_port,Navyresponse.to_custom,Navyresponse.permission_no,Submission_Date
//        from  igm_details igms 
//        left outer Join igm_navy_response Navyresponse 
//        on Navyresponse.igm_details_id =igms.id 
//        where igms.type_of_igm='$type'
//        and igms.final_submit=1 and (igms.PFstatus=1 or igms.PFstatus=10)  ORDER BY Line_No ASC";
// break;
        $query = $this->db->query("SELECT igms.id AS id,igms.IGM_id AS IGM_id,igms.Import_Rotation_No AS Import_Rotation_No,igms.Line_No AS 
        Line_No FROM igm_details igms");
        // echo $type;
        $data=$query->result_array();
       // echo  $data;
        return $data;
}   
   
function searchRotation($keyword){
       $query=$this->db->query("SELECT DISTINCT Import_Rotation_No FROM igm_masters WHERE id='$keyword'");
       return $query->result_array();
   }


function searchBLBYState($keyword){
    $query=$this->oracledb->query("SELECT TSTATE,COUNT(ID),SUM(LOT_WEIGHT) as lot_weight,SUM(LOT_QTY) as  LOT_QTY
    FROM (
        SELECT INV_UNIT.ID ,SUBSTR(inv_unit_fcy_visit.transit_state, INSTR(inv_unit_fcy_visit.transit_state, '_')+1) as TSTATE,
        (SELECT CRG_LOTS.pos_name FROM CRG_LOTS where CRG_LOTS.ID = ID fetch FIRST 1 rows only) as POSITION,
        (SELECT NVL(CRG_LOTS.quantity,0) FROM CRG_LOTS where CRG_LOTS.ID = INV_UNIT.ID  fetch FIRST 1 rows only) as LOT_QTY,
        (SELECT CAST(CRG_LOTS.weight_total_kg as INT) as weight_total_kg  FROM CRG_LOTS where CRG_LOTS.ID = INV_UNIT.ID  fetch FIRST 1 rows only) as LOT_WEIGHT,
        INV_UNIT.category, CRG_BILLS_OF_LADING.NBR AS BL,
        validate_conversion(CRG_BL_ITEM.WEIGHT_TOTAL_KG as number)   as TOTAL_WAIGHT,CRG_BL_ITEM.QUANTITY as TOTAL_QTY,
        argo_carrier_visit.id as IBACTUALVISIT,REF_BIZUNIT_SCOPED.ID AS AGENT_CODE
        FROM CRG_BILLS_OF_LADING 
        INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
        INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
        INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
        INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
        INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
        INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
        WHERE CRG_BILLS_OF_LADING.NBR='$keyword') GROUP BY TSTATE");

    return $query->result_array();
    }

    function searchBLBYShedYard($keyword){
        $query=$this->oracledb->query("SELECT listagg(INV_UNIT_FCY_VISIT.LAST_POS_NAME,',')   WITHIN GROUP (ORDER BY INV_UNIT.ID) as YARD
        FROM CRG_BILLS_OF_LADING 
        INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
        INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
        INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
        INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
        WHERE CRG_BILLS_OF_LADING.NBR='$keyword'  AND SUBSTR(inv_unit_fcy_visit.transit_state, INSTR(inv_unit_fcy_visit.transit_state, '_')+1) = 'YARD'");
    
        return $query->result_array();
        }

        function IGMSubDetails($CODE,$SubCODE,$TM) {
            try {    
                    $query = $this->db->query("select igms.id as id,
                    igms.igm_master_id as igm_master_id,igms.igm_detail_id as igm_detail_id,igms.master_Line_No as master_Line_No,igms.master_BL_No as master_BL_No,
                    igms.Import_Rotation_No as Import_Rotation_No,
                    igms.Line_No as Line_No,
                    igms.BL_No as BL_No,
                    igms.Pack_Number as Pack_Number,igms.AFR as AFR,
                    igms.Pack_Description as Pack_Description,igms.Pack_Marks_Number as Pack_Marks_Number,igms.Description_of_Goods as Description_of_Goods,igms.Date_of_Entry_of_Goods as Date_of_Entry_of_Goods,
                    igms.weight as weight,igms.Bill_of_Entry_No as Bill_of_Entry_No,igms.Bill_of_Entry_Date as Bill_of_Entry_Date,igms.office_code as office_code,igms.No_of_Pack_Delivered as No_of_Pack_Delivered,
                    igms.No_of_Pack_Discharged as No_of_Pack_Discharged,igms.Remarks as Remarks,igms.ConsigneeDesc,igms.NotifyDesc,igms.igm_sup_detail_id,igms.weight as weight,igms.weight_unit as weight_unit,igms.net_weight as net_weight,igms.net_weight_unit as net_weight_unit,igms.Submission_Date,
                    navyresponse.navy_response_to_port,
                    organization_profiles.Organization_Name as Agent_Name,
                    organization_profiles.AIN_No as AIN_No,AIN_No_New,
                    navyresponse.response_details1,navyresponse.response_details2,navyresponse.secondapprovaltime,
                        navyresponse.response_details3,navyresponse.thirdapprovaltime,
                        navyresponse.hold_application,navyresponse.hold_date,
                        navyresponse.rejected_application,navyresponse.rejected_date,navyresponse.final_amendment
                    from igm_supplimentary_detail igms LEFT join organization_profiles on igms.Submitee_Org_Id=organization_profiles.id 
                    LEFT JOIN igm_navy_response navyresponse on navyresponse.egm_details_id=igms.id where igms.igm_master_id='$CODE' and igms.igm_detail_id='$SubCODE' and type_of_igm='$TM'");
    
                $data=$query->result_array();
               
                return $data;
    
            } catch (Exception $ex) {
                return FALSE;
            }
        }


        function GETBBVesselData($keyword){
            $query=$this->db->query("SELECT Import_Rotation_No,Voy_No,VoyNoExp,Vessel_Name,grt,nrt,imo,loa_cm,flag,radio_call_sign,beam_cm 
            FROM igm_masters WHERE id='$keyword'");
            return $query->result_array();
            }

     
function VesselDeclarationDetailsSearch($type,$SearchCriteria, $Searchdata){
            try {
                $search="";
                if($SearchCriteria=="VName"){//Search BL NO
                    $search="AND igm_masters.Vessel_Name='$Searchdata'".'';
                }else if($SearchCriteria=="port"){//Search Port
                    $search="AND igm_masters.Port_of_Shipment='$Searchdata'".'';
                }else if($SearchCriteria=="Voy"){//Search Voyage No
                    $search="AND igm_masters.Voy_No='$Searchdata'".'';
                }else if($SearchCriteria=="Import"){//Search Import Rotation
                    $search="AND igm_masters.Import_Rotation_No='$Searchdata'".'';
                }else if($SearchCriteria=="Export"){//Search Export Rotation
                    $search="AND igm_masters.Export_Rotation_No='$Searchdata'".'';
                }else{
                    $search="";
                }
                        $query ="SELECT  igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
                        igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
                        igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
                        igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
                        igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type 
                        AS Submitee_Org_Type,igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date 
                        AS Submission_Date,igm_masters.flag AS flag,igm_masters.imo AS imo,igm_masters.line_belongs_to AS line_belongs_to,
                        igm_masters.VoyNoExp,igm_masters.grt,igm_masters.nrt,igm_masters.loa_cm,igm_masters.radio_call_sign,igm_masters.beam_cm
                        FROM igm_masters
                        LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
                        LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
                        WHERE vsl_dec_type = 'BB' AND DATE(Submission_Date) > '2022-01-01' $search ORDER BY file_clearence_date DESC";
        
                    $data=$this->db->query($query);
                    return $data->result_array();
        
                } catch (Exception $ex) {
                    return FALSE;
                }
            }        
}