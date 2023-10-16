<?php


class BBVesselOperationModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       $this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }

   function VesselDeclarationViewUpdate($igm_masters_id,$login_id) {
    try {
         $stow_info_update="UPDATE edi_stow_info SET file_status=1,file_download_by='$login_id',file_download_date=NOW() WHERE igm_masters_id='$igm_masters_id'";
         $update=$this->db->query($stow_info_update);
        return $update;

    }catch (Exception $ex) {
        return FALSE;
    }
   }

   function CountVesselDeclarationUploadFile() {
    try {
         $fileDt="SELECT COUNT(*) AS totalFile FROM igm_masters 
         INNER JOIN  edi_stow_info ON igm_masters.id = edi_stow_info.igm_masters_id
         WHERE igm_masters.vsl_dec_type='BB' AND edi_stow_info.file_status=0";
         $data=$this->db->query($fileDt);
         $dataFile= $data->result_array();
         return $dataFile[0]['totalFile'];

    }catch (Exception $ex) {
        return FALSE;
    }
   }

    function searchBerthWiseVesselWithDateRange($fromDate,$toDate){
        try {
                $query=$this->oracledb->query("SELECT  VSL_VESSELS.NAME,VSL_VESSEL_VISIT_DETAILS.IB_VYG,VSL_VESSEL_VISIT_DETAILS.FLEX_STRING02 AS BOPTR,
                to_char(ARGO_CARRIER_VISIT.ATA, 'YYYY-MM-DD') as ATA ,to_char(ARGO_CARRIER_VISIT.ATD, 'YYYY-MM-DD') as ATD,
                VSL_VESSEL_BERTHINGS.POS_SLOT AS BERTH,SUBSTR(argo_carrier_visit.phase,3) AS PHASE
         FROM VSL_VESSEL_VISIT_DETAILS 
                INNER JOIN VSL_VESSEL_BERTHINGS ON VSL_VESSEL_VISIT_DETAILS.VVD_GKEY = VSL_VESSEL_BERTHINGS.VVD_GKEY
                INNER JOIN VSL_VESSELS ON VSL_VESSELS.GKEY= VSL_VESSEL_VISIT_DETAILS.VESSEL_GKEY
                INNER JOIN ARGO_CARRIER_VISIT ON argo_carrier_visit.cvcvd_gkey =vsl_vessel_visit_details.vvd_gkey
                WHERE  to_char(ARGO_CARRIER_VISIT.ATA, 'YYYY-MM-DD')  >=  '$fromDate'  AND  to_char(ARGO_CARRIER_VISIT.ATA, 'YYYY-MM-DD') <= '$toDate' ORDER BY VSL_VESSEL_BERTHINGS.POS_SLOT ASC");
                return $query->result_array();
        }catch (Exception $ex) {
            return  $ex;
        }
    }

     function searchBerthWiseVesselWithDateRangeRotaionIGM($fromDate,$toDate){
        try {
                $IgmaDataForRotation="SELECT Import_Rotation_No,Total_gross_mass AS TOTAL_WEIGHT,Total_number_of_packages AS TOTAL_QUENTITY FROM igm_masters";
                $query=$this->db->query($IgmaDataForRotation);
                return $query->result_array();
                

        }catch (Exception $ex) {
            return  $ex;
        }
    }    

    function BBIGMVesselDeclarationList() {
        try {
            
            $generalIGM_QueryForBB =  "SELECT  igm_masters.id,igm_masters.Import_Rotation_No,igm_masters.Export_Rotation_No,igm_masters.Sailed_Year,
            igm_masters.Sailed_Date,vessels_berth_detail.ETA_Date,Actual_Berth,final_clerance_files_ref_number,igm_masters.Vessel_Id,
            igm_masters.Vessel_Name,igm_masters.Voy_No,igm_masters.Net_Tonnage,
            igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
            igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type 
            AS Submitee_Org_Type,igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date 
            AS Submission_Date,igm_masters.flag AS flag,igm_masters.imo AS imo,igm_masters.line_belongs_to AS line_belongs_to,
            igm_masters.VoyNoExp,igm_masters.grt,igm_masters.nrt,igm_masters.loa_cm,igm_masters.radio_call_sign,igm_masters.beam_cm,edi_stow_info.file_status,edi_stow_info.file_name_stow
            FROM igm_masters
            LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
            LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
            INNER JOIN  edi_stow_info ON edi_stow_info.igm_masters_id=igm_masters.id
            WHERE vsl_dec_type = 'BB' AND DATE(Submission_Date) > '2022-01-01' ORDER BY edi_stow_info.file_status ASC";
    
            $query = $this->db->query($generalIGM_QueryForBB);
            $data=$query->result_array();
    
            return $data;
    
        } catch (Exception $ex) {
            return FALSE;
        }
    }

  

}