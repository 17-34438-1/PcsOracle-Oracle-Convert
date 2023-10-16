<?php


class BBIGMCheckModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       //$this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }
   
   
   function searchRotationWiseIGM($keyword){
   
     $query=$this->db->query("SELECT im.Vessel_Name AS VESSEL_NAME,
     idts.Import_Rotation_No AS ROTATION,idts.Line_No AS LINE_NO,idts.BL_No AS MASTER_BL_NO,IFNULL(subdtls.BL_NO,idts.BL_No) AS BL_NO ,idts.Pack_Marks_Number AS PACK_MARKS,idts.Description_of_Goods AS DESC_GOODS, IFNULL(CAST(subdtls.weight AS SIGNED), CAST(idts.weight AS SIGNED)) AS TOTAL_WEIGHT,
      IFNULL(subdtls.ConsigneeDesc,idts.ConsigneeDesc ) AS CONSIGNEE, IFNULL(idts.NotifyDesc,idts.NotifyDesc) AS NOTIFY
     ,idts.Pack_Description AS PACKAGE_TYPE,org.Organization_Name AS SHIPING_AGENT_NAME,org.Agent_Code AS AGENT_CODE,idts.mlocode AS MLO,idts.Pack_Number AS QUENTITY
     FROM  igm_masters im 
     LEFT JOIN igm_details idts ON idts.IGM_id = im.id
     LEFT JOIN igm_supplimentary_detail subdtls ON subdtls.igm_detail_id = idts.id
     LEFT JOIN organization_profiles org ON org.id = im.Submitee_Org_Id
     WHERE idts.Import_Rotation_No='$keyword' ORDER BY ABS(idts.Line_No) ASC");
  
     return $query->result_array();
    }

    function searchRotationWiseCargoManifestReport($keyword){
   
      $query=$this->db->query("SELECT im.Port_of_Shipment,idts.Line_No AS LINE_NO,idts.BL_No AS MASTER_BL_NO,idts.Exporter_name,
      IFNULL(subdtls.ConsigneeDesc,idts.ConsigneeDesc ) AS CONSIGNEE, IFNULL(idts.NotifyDesc,idts.NotifyDesc) AS NOTIFY,
      idts.Description_of_Goods AS DESC_GOODS,DATE(im.file_clearence_date) AS date_of_arrival,DATE(im.Submission_Date) AS reg_date,
      im.Vessel_Name AS VESSEL_NAME,im.Voy_no,idts.Import_Rotation_No AS ROTATION,IFNULL(subdtls.BL_NO,idts.BL_No) AS BL_NO,idts.Pack_Marks_Number AS PACK_MARKS, IFNULL(CAST(subdtls.weight AS SIGNED), CAST(idts.weight AS SIGNED)) AS TOTAL_WEIGHT,
       idts.Pack_Description AS PACKAGE_TYPE,org.Organization_Name AS SHIPING_AGENT_NAME,org.Agent_Code AS AGENT_CODE,idts.mlocode AS MLO,CEIL(idts.Pack_Number) AS number_of_pack
            FROM  igm_masters im 
            LEFT JOIN igm_details idts ON idts.IGM_id = im.id
            LEFT JOIN igm_supplimentary_detail subdtls ON subdtls.igm_detail_id = idts.id
            LEFT JOIN organization_profiles org ON org.id = im.Submitee_Org_Id
            WHERE idts.Import_Rotation_No='$keyword' ORDER BY ABS(idts.Line_No) ASC");
   
      return $query->result_array();
     }

     function GetFlagForCargoManifestReport($keyword){
   
      $str="SELECT  VSL_VESSELS.NAME,REF_COUNTRY.CNTRY_NAME
      FROM VSL_VESSEL_VISIT_DETAILS 
      INNER JOIN  VSL_VESSELS  ON VSL_VESSELS.gkey = vsl_vessel_visit_details.vessel_gkey
      INNER JOIN REF_COUNTRY ON REF_COUNTRY.CNTRY_CODE=VSL_VESSELS.COUNTRY_CODE
      WHERE vsl_vessel_visit_details.ib_vyg='$keyword'";
       
      $query=$this->oracledb->query($str);
   
      return $query->result_array();
     }

     function GetPlaceNameByRotation($keyword){
      $str="SELECT REF_UNLOC_CODE.* FROM INV_UNIT
      INNER JOIN ARGO_CARRIER_VISIT ON ARGO_CARRIER_VISIT.GKEY = INV_UNIT.DECLRD_IB_CV
      INNER JOIN VSL_VESSEL_VISIT_DETAILS ON VSL_VESSEL_VISIT_DETAILS.VVD_GKEY = ARGO_CARRIER_VISIT.CVCVD_GKEY
      INNER JOIN REF_ROUTING_POINT ON REF_ROUTING_POINT.GKEY=INV_UNIT.POD1_GKEY
      INNER JOIN REF_UNLOC_CODE ON REF_UNLOC_CODE.GKEY=REF_ROUTING_POINT.UNLOC_GKEY
      WHERE VSL_VESSEL_VISIT_DETAILS.IB_VYG='$keyword' FETCH FIRST 1 ROWS ONLY";
    
      $query=$this->oracledb->query($str);
      return $query->result_array();

     }

  
}