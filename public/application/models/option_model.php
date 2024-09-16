<?php

class Option_model extends CI_Model {

    function add_option($option){
        $this->db->insert('options', $option);
        return $this->db->insert_id();
    }


    function get_option($option){

        $this->db->select('*');
        $this->db->where('option_name', $option);
        $query = $this->db->get('options');
        if ($this->db->_error_message())
        {
            show_error($this->db->_error_message());
        }
        $result_array = $query->result_array();

        return $result_array;

    }
    
    function get_option_by_company($option, $company_id){

        $this->db->select('*');
        $this->db->where('option_name', $option);

        if(is_array($company_id)){
            $this->db->where_in('company_id', $company_id);
        } else {
            $this->db->where('company_id', $company_id);
        }
        $query = $this->db->get('options');
        if ($this->db->_error_message())
        {
            show_error($this->db->_error_message());
        }
        $result_array = $query->result_array();

        return $result_array;
    }

    function get_option_by_user($option, $user_id){

        $this->db->select('*');
        $this->db->where('option_name', $option);
        $this->db->like('option_value', $user_id, 'both');
        $query = $this->db->get('options');
        if ($this->db->_error_message())
        {
            show_error($this->db->_error_message());
        }
        $result_array = $query->result_array();

        return $result_array;
    }

    function get_options(){

        $this->db->select('*');
        $query = $this->db->get('options');
        $result_array = $query->result_array();
        return $result_array;

    }

    
    function update_option($option, $value, $autoload)
    {
        $data = array(
            "option_name" => $option,
            "option_value" => $value,
            "autoload" => $autoload
        );
        $this->db->where('option_name', $option);
        $this->db->update('options', $data);
        if ($this->db->_error_message())
        {
            show_error($this->db->_error_message());
        }else{
            if ($this->db->affected_rows() > 0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    function update_option_company($option, $value, $company_id)
    {
        $data = array(
            "option_name" => $option,
            "option_value" => $value,
            "autoload" => 0
        );
        $this->db->where('company_id', $company_id);
        $this->db->where('option_name', $option);
        $this->db->update('options', $data);
        if ($this->db->_error_message())
        {
            show_error($this->db->_error_message());
        }else{
            if ($this->db->affected_rows() > 0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    function delete_option($option, $user_id = null)
    {
        $this->db->where('option_name', $option);

        if($user_id){
            $this->db->like('option_value', $user_id, 'both');
        }
        $this->db->delete('options');
        if ($this->db->affected_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }   
    }
}

?>