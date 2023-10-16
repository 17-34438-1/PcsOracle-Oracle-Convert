<?php


class BBSingleBLWiseModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       //$this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }
   
   
   function searchSingleBLWiseIGM($keyword){
   
     $query=$this->db->query("SELECT Vessel_Name AS VESSEL_NAME,
     idts.Import_Rotation_No AS ROTATION,idts.Line_No AS LINE_NO,idts.BL_No AS BL_NO,idts.Pack_Marks_Number AS PACK_MARKS,idts.Description_of_Goods AS DESC_GOODS, CAST(weight AS SIGNED) AS TOTAL_WEIGHT,CONCAT('CONSIGNEE: ',ConsigneeDesc,'  \NOTIFY :',NotifyDesc) AS CONSINEE_NOTIFY
     ,idts.Pack_Description AS PACKAGE_TYPE,org.Organization_Name AS SHIPING_AGENT_NAME,org.Agent_Code AS AGENT_CODE,idts.mlocode AS MLO,idts.Pack_Number AS QUENTITY
     FROM  igm_masters im 
     LEFT JOIN igm_details idts ON idts.IGM_id = im.id
     LEFT JOIN organization_profiles org ON org.id = im.Submitee_Org_Id
     WHERE idts.BL_No='$keyword'");
  
     return $query->result_array();
    }

    function searchAgentCode($keyword){
        $query=$this->oracledb->query("SELECT  (CASE WHEN REF_BIZUNIT_SCOPED.SCAC IS NULL THEN REF_BIZUNIT_SCOPED.SCAC ELSE REF_BIZUNIT_SCOPED.BIC END) AS AGENT_CODE  FROM VSL_VESSEL_VISIT_DETAILS 
        INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = VSL_VESSEL_VISIT_DETAILS.BIZU_GKEY
        WHERE IB_VYG= '$keyword'");
    
        return $query->result_array();
    }

}