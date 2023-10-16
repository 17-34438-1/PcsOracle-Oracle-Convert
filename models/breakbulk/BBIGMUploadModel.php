<?php


class BBIGMUploadModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       $this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }

   function uploadVesselDeclaration($rot,$imp_voyage,$exp_voyage,$vslName,$grt,$nrt,$imo_no,$loa,$flag,$call_sign,$beam,$filename,$login_id) {
    try {
        
        
        $strid="SELECT id as rtnValue FROM igm_masters WHERE Import_Rotation_No='$rot'";
        $igm_masters_query = $this->db->query($strid);
        $igm_masters_query_data=$igm_masters_query->result_array();
        $igm_masters_id=$igm_masters_query_data[0]['rtnValue'];
        
        $count_id="SELECT COUNT(igm_masters_id) AS rtnValue FROM edi_stow_info WHERE igm_masters_id='$igm_masters_id'";
        $rtn_count_query = $this->db->query($count_id);
        $rtn_count_query_data=$rtn_count_query->result_array();
        $rtn_count_id=$rtn_count_query_data[0]['rtnValue'];
    
        if($rtn_count_id>0){
            $stow_info_update="UPDATE edi_stow_info SET file_name_stow='$filename',file_upload_by='$login_id',file_upload_date=NOW() WHERE igm_masters_id='$igm_masters_id'";
                
            $update=$this->db->query($stow_info_update);
        }else{
            $stow_info_insert="INSERT INTO edi_stow_info(igm_masters_id,file_name_stow,file_upload_by,file_upload_date) VALUES('$igm_masters_id','$filename','$login_id',NOW())";
            $insert=$this->db->query($stow_info_insert);
        }
        
        $v_grt=(float)$grt;
        $v_nrt=(float)$nrt;
       
        $igm_masters_update="UPDATE igm_masters
        SET Voy_No='$imp_voyage',VoyNoExp='$exp_voyage',Vessel_Name='$vslName',grt='$v_grt',nrt='$v_nrt',imo='$imo_no',loa_cm='$loa',flag='$flag',radio_call_sign='$call_sign',beam_cm='$beam' WHERE id='$igm_masters_id'";
        $update=$this->db->query($igm_masters_update);
        $stat=$update;
        return $stat;

    } catch (Exception $ex) {
        return FALSE;
    }
   }
}