<?php


class PilotageModel extends CI_Model
{
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->n4db=$this->load->database('fifth', TRUE);
    }
    function getVslDetailsFromIGM($ddl_imp_rot_no){
        $query="SELECT Name_of_Master,Deck_cargo FROM igm_masters WHERE Import_Rotation_No='$ddl_imp_rot_no'";
        //echo $query;
        $queryData=$this->db->query($query);
        return $queryData->result_array();
    }
    function getVesselDetailsFromN4_28($ddl_imp_rot_no){
        $query=" SELECT vsl_vessels.name,vsl_vessel_visit_details.vvd_gkey,vsl_vessels.radio_call_sign,vsl_vessel_classes.loa_cm,vsl_vessel_classes.gross_registered_ton, 
        vsl_vessel_classes.net_registered_ton,ref_bizunit_scoped.id AS localagent,ref_country.cntry_name AS flag, vsl_vessel_classes.beam_cm 
        ,NVL((SELECT NAME FROM argo_facility WHERE gkey=argo_carrier_visit.fcy_gkey) ,'') AS last_port,NVL((SELECT NAME FROM argo_facility WHERE gkey=argo_carrier_visit.next_fcy_gkey),'') AS next_port
        FROM vsl_vessels 
        INNER JOIN vsl_vessel_visit_details ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
        INNER JOIN vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey 
        INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey 
        INNER JOIN ref_country ON ref_country.cntry_code=vsl_vessels.country_code 
        INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey =vsl_vessel_visit_details.vvd_gkey
        WHERE vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no' ORDER BY vvd_gkey   DESC fetch FIRST 1 rows only	";
            // echo $query;
        $queryData=$this->n4db->query($query);
        return $queryData->result_array();
    }
    function getVesselDetailsFromN4_38($ddl_imp_rot_no){
        $query="SELECT vsl_vessels.name,vsl_vessel_visit_details.vvd_gkey,vsl_vessels.radio_call_sign,vsl_vessel_classes.loa_cm,vsl_vessel_classes.gross_registered_ton, 
            vsl_vessel_classes.net_registered_ton,ref_bizunit_scoped.id AS localagent,ref_country.cntry_name AS flag, vsl_vessel_classes.beam_cm 
            ,IFNULL((SELECT NAME FROM argo_facility WHERE gkey=sparcsn4.argo_carrier_visit.fcy_gkey) ,'') AS last_port,IFNULL((SELECT NAME FROM argo_facility WHERE gkey=sparcsn4.argo_carrier_visit.next_fcy_gkey),'') AS next_port
            FROM sparcsn4.vsl_vessels 
            INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey 
            INNER JOIN sparcsn4.vsl_vessel_classes ON vsl_vessel_classes.gkey=vsl_vessels.vesclass_gkey 
            INNER JOIN sparcsn4.ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey 
            INNER JOIN sparcsn4.ref_country ON ref_country.cntry_code=vsl_vessels.country_code 
            INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey =sparcsn4.vsl_vessel_visit_details.vvd_gkey
            WHERE vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no' ORDER BY vvd_gkey  DESC LIMIT 1	";
        $queryData=$this->n4db->query($query);
        return $queryData->result_array();
    }
    function getVslArrivalData($getVvdGkey,$pilot_user_id){
        $query="SELECT users.u_name,igm_id, vvd_gkey, pilot_name, pilot_on_board, pilot_off_board, pilot_frm, pilot_to, 
        mooring_frm_time, mooring_to_time, tug_name, assit_frm, assit_to, oa_dt, oa_dt, ata, photo_base_64,
        DATE(pilot_off_board) AS sign_arraival,aditional_tug+1 AS aditional_tug,
        is_main_engine_ok,is_acnchors_ok,is_rudder_indicator_ok,is_rpm_indicator_ok,is_bow_therster_available,is_complying_soal_convention,
        remarks,is_night,is_holiday FROM doc_vsl_arrival 
        INNER JOIN users ON users.login_id = doc_vsl_arrival.pilot_name 
        WHERE vvd_gkey='$getVvdGkey'  AND doc_vsl_arrival.pilot_name ='$pilot_user_id' ORDER BY doc_vsl_arrival.id DESC LIMIT 1";
        $queryData=$this->db->query($query);
        return $queryData->result_array();
    }
    function getVslShiftingData($getVvdGkey,$pilot_user_id){
        $query="SELECT  users.u_name,pilot_name,pilot_on_board,pilot_off_board,shift_frm,shift_to,mooring_frm_time,
        mooring_to_time,tug_name,assit_frm,assit_to,shift_dt,DATE(pilot_off_board) AS sign_shift,aditional_tug+1 AS aditional_tug,photo_base_64,
        is_main_engine_ok,is_acnchors_ok,is_rudder_indicator_ok,is_rpm_indicator_ok,is_bow_therster_available,is_complying_soal_convention,
        remarks,is_night,is_holiday				
        FROM doc_vsl_shift 
        INNER JOIN users ON users.login_id = doc_vsl_shift.pilot_name 
        WHERE vvd_gkey='$getVvdGkey' AND doc_vsl_shift.pilot_name ='$pilot_user_id' ORDER BY doc_vsl_shift.id DESC LIMIT 1";
        $queryData=$this->db->query($query);
        return $queryData->result_array();
    }
    function getVslDepartedData($getVvdGkey,$pilot_user_id){
        $query="SELECT users.u_name,igm_id,vvd_gkey,pilot_name,pilot_on_board,pilot_off_board,pilot_frm,pilot_to,mooring_frm_time,
        mooring_to_time,tug_name,assit_frm,assit_to,atd,DATE(pilot_off_board) AS sign_depart,aditional_tug+1 AS aditional_tug,photo_base_64,
        is_main_engine_ok,is_acnchors_ok,is_rudder_indicator_ok,is_rpm_indicator_ok,is_bow_therster_available,is_complying_soal_convention,
        remarks,is_night,is_holiday
        FROM doc_vsl_depart
        INNER JOIN users ON users.login_id = doc_vsl_depart.pilot_name 
        WHERE  vvd_gkey='$getVvdGkey' AND doc_vsl_depart.pilot_name ='$pilot_user_id' ORDER BY doc_vsl_depart.id DESC LIMIT 1";
        $queryData=$this->db->query($query);
        return $queryData->result_array();
    }
    function getVslCacelData($getVvdGkey,$pilot_user_id){
        $query="SELECT users.u_name ,pilot_name,vvd_gkey,pilot_on_board,pilot_off_board,DATE(pilot_off_board) AS sign_cancel,cancel_from,cancel_to,cancel_at,remarks
        FROM doc_vsl_cancel 
        INNER JOIN users ON users.login_id = doc_vsl_cancel.pilot_name 
        WHERE vvd_gkey='$getVvdGkey' AND doc_vsl_cancel.pilot_name ='$pilot_user_id' ORDER BY doc_vsl_cancel.id DESC LIMIT 1";
        $queryData=$this->db->query($query);
        return $queryData->result_array();
    }
}
