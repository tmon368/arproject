<?php

class ApiModel extends CI_Model {

    public function get_pro_all(){
        //$this->db->where('PRO_START',$gameID);
        //$this->db->where('code_id',$userID);
        $query = $this->db->get('v_promotion_active');
        return $query->result_array();
    }

    public function get_info_by_tagID($id){
        $this->db->where('job_id',$gameID);
        $query = $this->db->get('bingo_value');
        return $query->result_array();
        
    }
    
    public function get_pro_of_tourID($tourID)
    {
        $this ->db->select('PRO_ID')->from('GET_Promotions')->where($tourID);
        $proid = $this->db->get();
        if($proid->num_rows()>0)
        {
            $this->db->havingIn('Promotions',$proid);
            $promotion =$this->db->get();
            return $promotion->result_array();
        }
    }
    
    public function get_pro_of_area($lat,$long)
    {

    }

    public function get_info_all()
    {
        $query = $this->db->get('Informations');
        return $query->result_array();
    }

    public function register_fb($email,$name)
    {
        if(!empty($email))
        {
           
        $this ->db->select('TOURIS_EMAIL')->from('Tourists')->where(".$email");
        $fb_email = $this->db->get();
        if($fb_email->num_rows()==0)
        {
            $data_insert =[
                'TOURIS_NAME'=> $name,
                'TOURIS_EMAIL'=> $email
            ];
            $insertdata = $this->db->table('Tourists')->insert($data_insert);
            return $insertdata;
        }
        else
        {
            return "null";
        }
    }
    else{
        echo "Error Email is null";
    }

    }
    
    public function getNewSheetID($gameID){
        
    }
    public function login_fb($email)
    {
        $this->db->from('Tourists')->where('TOURIS_EMAIL',$email);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function getSheetDetailArray($sheetID){
        // $this->db->where('sheet_id', $sheetID);
        // $query = $this->db->get('bingo_sheet');
        // $sheet = array();
        // $i = 0;
        // foreach ($query->result() as $row)
        // {
        //     $sheetRow = array("position"=>$row->position, "row"=>$row->rows, "col"=>$row->cols,"data"=>$row->data_bi);
        //     $sheet[$i] = $sheetRow;
        //     $i++;
        // }
        // return  $sheet;
        
    }
    
    public function getBingoValue(){
        // $this->db->select('value');
        // $query = $this->db->get('bingo_value');
        // //$values = $query->result_array();
        // $values = array();
        // $i = 0;
        // foreach($query->result() as $row){
        //     $values[$i] = $row->value;
        //     $i++;
        // }
        // return $values;
    }
}