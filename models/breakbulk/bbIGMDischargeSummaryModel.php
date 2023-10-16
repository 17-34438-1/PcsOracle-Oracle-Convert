<?php
class bbIGMDischargeSummaryModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       //$this->load->library('session'); 
        echo "Helllo";
      // $this->load->database();
	   //$this->load->helper('url');
	  //$this->load->helper('file');
      echo "Helllo 1";
       
   }

   function searchRotation($keyword){
    $query=[];
    // $query=$this->db->query("SELECT Vessel_Name AS VESSEL_NAME,im.Import_Rotation_No AS ROTATION,idts.BL_No AS BL_NO,Total_gross_mass AS TOTAL_WEIGHT,
    // Total_number_of_packages AS TOTAL_QTY,Port_Ship_ID AS PORT_OF_ORIGIN,idts.Pack_Description AS PACKAGE_TYPE,org.Organization_Name AS SHIPING_AGENT_NAME,
    // org.Agent_Code AS AGENT_CODE,idts.mlocode AS MLO
    // FROM  igm_masters im 
    // LEFT JOIN igm_details idts ON idts.IGM_id = im.id
    // LEFT JOIN organization_profiles org ON org.id = im.Submitee_Org_Id
    // WHERE im.Import_Rotation_No = '$keyword'");
 
    return $query->result_array();
}

}