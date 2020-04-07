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
    
    public function get_pro_of_area($tour_lat,$tour_long)
    {
        $query = $this->db->query("SELECT * FROM Informations;");

        //$this->db->select('INFO_LAT,INFO_LONG')->from('Informations');
    
        $area_info = array();
        if($area_info== null)
        {
            if($query->num_rows()>0)
            {
                
                foreach($query->result_array() as $row)
                {
                    $info_long = $row['INFO_LONG'];
                    $info_lat = $row['INFO_LAT'];
                   
                    $theta = $tour_long-$info_long;
                    $dist = sin(deg2rad($tour_lat))*sin(deg2rad($info_lat))+cos(deg2rad($tour_lat))*cos(deg2rad($info_lat))*cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist*60*1.1515; //miles
                    $kilometers = $miles*1.609344; //kilometers

                    if($kilometers <.200)
                    {
                        
                        return $area_info =  $this->Generate_Promotion_area($row['INFO_LAT'],$row['INFO_LONG']);
                        //return $area_info= $row;
                       // return $row->result_array();
                    }
                    
                }
            }
            else
            {
                 $area_info=null;
            }
        }else{
            return $area_info;
        }

       // return $area_info->result_array();
    }

    public function Generate_Promotion_area($lat,$long)
    {
        $radius= $this->rand_float(0.0,0.1);//in miles
        $newradius = $radius*1.609344;//in kilometers
        $newlong = number_format( $long-$newradius/abs(cos(deg2rad($lat))*69),6);
        $newlat = number_format($lat+($newradius/69),6);
        return [$newlat,$newlong];
        //echo $newlat.','.$newlong;

    }
    function rand_float($st_num=0,$end_num=1,$mul=1000000)
    {
    if ($st_num>$end_num) return false;
    return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }
    public function get_info_all()
    {
        $query = $this->db->get('Informations');
        return $query->result_array();
    }

    public function register_fb($email,$name)
    {
        $this->table ='Tourists';
        if(!empty($email))
        {
           
        $this ->db->select('*')->from('Tourists')->where('TOURIS_EMAIL',$email);
        $fb_email = $this->db->get();
        if($fb_email->num_rows()==0)
        {
            $data_insert =[
                'TOURIS_NAME'=> $name,
                'TOURIS_EMAIL'=> $email,
                'TOURIS_PASSWORD'=>null,
                'TOURIS_TYPE'=>null,
                'TOURIS_ACTIVE_FLAG'=>null
            ];
            //$insertdata = $this->db->table('Tourists')->insert($data_insert);
            $insertdata = $this->db->insert($this->table,$data_insert);
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
        $this->db->select('TOURIS_ID,TOURIS_NAME,TOURIS_TYPE,TOURIS_ACTIVE_FLAG')->from('Tourists')->where('TOURIS_EMAIL',$email);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function CheckIn($touris_id,$info_id,$promotion )
    {
       $data_checkin =[
           'TOURIS_ID'=>$touris_id,
           'INFO_ID'=>$info_id
       ];
       $data_get_promotion=[
        'TOURIS_ID'=>$touris_id,
        'PRO_ID'=>$promotion
       ];
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