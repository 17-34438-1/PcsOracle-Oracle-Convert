<?php


class ReportModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       $this->load->database();
       $this->n4db=$this->load->database('third', TRUE);
   }

function getDateWisePilotEntryReport($todate,$fromDate){
    $query="SELECT  pilot_name,u_name,
    (SELECT COUNT(*) FROM doc_vsl_arrival WHERE pilot_name = t.pilot_name AND (DATE(date_modified) BETWEEN '$fromDate' AND '$todate') ) AS total_doc_vsl_arrival,  
    (SELECT COUNT(*) FROM doc_vsl_shift WHERE pilot_name = t.pilot_name AND (DATE(date_modified) BETWEEN '$fromDate' AND '$todate')) AS total_doc_vsl_shift, 
    (SELECT COUNT(*) FROM doc_vsl_depart WHERE pilot_name = t.pilot_name AND (DATE(date_modified) BETWEEN '$fromDate' AND '$todate')) AS total_doc_vsl_depart, 
    (SELECT COUNT(*) FROM doc_vsl_cancel WHERE pilot_name = t.pilot_name AND (DATE(date_modified) BETWEEN '$fromDate' AND '$todate')) AS total_doc_vsl_cancel
    FROM(
    SELECT DISTINCT pilot_name FROM doc_vsl_arrival WHERE pilot_name IS NOT NULL AND pilot_name !=''  AND pilot_name !='p685003' AND  pilot_name !='devpilot'
    UNION 
    SELECT DISTINCT pilot_name FROM doc_vsl_shift WHERE pilot_name IS NOT NULL AND pilot_name !=''  AND pilot_name !='p685003' AND  pilot_name !='devpilot'
    UNION 
    SELECT DISTINCT pilot_name FROM doc_vsl_depart WHERE pilot_name IS NOT NULL AND pilot_name !='' AND pilot_name !='p685003' AND  pilot_name !='devpilot'
    UNION 
    SELECT DISTINCT pilot_name  FROM doc_vsl_cancel WHERE pilot_name IS NOT NULL AND pilot_name !='' AND pilot_name !='p685003' AND  pilot_name !='devpilot'
    ) AS t 
    INNER JOIN users ON users.`login_id` = t.pilot_name  
    ORDER BY t.pilot_name ASC";
    $queryData=$this->db->query($query);
    return $queryData->result_array();
}

function getDateWiseVesselHandledReport($todate,$fromDate){
    $query="SELECT * FROM (
        SELECT pilot_name,u_name,doc_vsl_info.vsl_name, doc_vsl_info.rotation,DATE(date_modified) AS entry_date
        FROM doc_vsl_arrival
        INNER JOIN users ON users.login_id = pilot_name 
        LEFT JOIN  doc_vsl_info ON doc_vsl_info.vvd_gkey = doc_vsl_arrival.vvd_gkey
        UNION 
        SELECT pilot_name,u_name,doc_vsl_info.vsl_name, doc_vsl_info.rotation,DATE(date_modified) AS entry_date
        FROM doc_vsl_shift
        INNER JOIN users ON users.login_id = pilot_name 
        LEFT JOIN  doc_vsl_info ON doc_vsl_info.vvd_gkey = doc_vsl_shift.vvd_gkey
        UNION 
        SELECT pilot_name,u_name,doc_vsl_info.vsl_name, doc_vsl_info.rotation,DATE(date_modified) AS entry_date
        FROM doc_vsl_depart
        INNER JOIN users ON users.login_id = pilot_name 
        LEFT JOIN  doc_vsl_info ON doc_vsl_info.vvd_gkey = doc_vsl_depart.vvd_gkey
        UNION
        SELECT pilot_name,u_name,doc_vsl_info.vsl_name, doc_vsl_info.rotation,DATE(date_modified) AS entry_date
        FROM doc_vsl_cancel
        INNER JOIN users ON users.login_id = pilot_name 
        LEFT JOIN  doc_vsl_info ON doc_vsl_info.vvd_gkey = doc_vsl_cancel.vvd_gkey) AS t 
        WHERE t.entry_date  BETWEEN '$fromDate' AND '$todate'
        ORDER BY t.entry_date ASC";
    $queryData=$this->db->query($query);
    return $queryData->result_array();
}

}

?>