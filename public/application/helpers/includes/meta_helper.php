<?php

function add_post_meta ($post_id, $key, $value) {

    $CI = & get_instance();
    $CI->load->model('Post_model');

    $data = array(
        'post_id' => $post_id,
        'meta_key' => $key,
        'meta_value' => $value
    );

    $data = apply_filters( 'before_add_post_meta', $data, $CI->input->post());
    $should_add_post_meta = apply_filters( 'should_add_post_meta', $data, $CI->input->post());

    if (!$should_add_post_meta) {
        return;
    }

    do_action('pre.add.post_meta', $data, $CI->input->post());

    $meta_id = $CI->Post_model->create_post_meta($data);

    do_action('post.add.post_meta', $post_id, $data, $CI->input->post());
}

/**
 * to get postmeta data from postmeta table by post_id or meta_key.
 * 
 * @param  post_id $post_id The id of the post corresponds to the postmeta data.
 * @param  key $key Optional, the key of postmeta data.
 * @return mixed Either array or null, if postmeta data is available or no error occurs, then array else null.
 * 
 */
function get_post_meta(int $post_id = null, string $key = null)
{
    $post_meta_data = null;
    $CI = & get_instance();
    $CI->load->model('Post_model');

    // filters
    $data = apply_filters( 'before_get_post_meta', $post_id, $CI->input->post());
    $should_get_post_meta = apply_filters( 'should_get_post_meta', $post_id, $CI->input->post());

    if (!$should_get_post_meta) {
        return;
    }

    if(isset($post_id) && $post_id == null){
        return null;
    }

    // before getting postmeta 
    do_action('pre.get.post_meta', $post_id, $CI->input->post());

    $post_meta_data = $CI->Post_model->get_post_meta($post_id, $key);

    // after getting postmeta
    do_action('post.get.post_meta', $post_id, $post_id, $CI->input->post());
     
    return $post_meta_data;

}

/**
 * edit postmeta data. 
 * 
 * @param array $post_meta An array of postmeta data to be updated.
 * @param int $post_id The id of the post corresponds to the postmeta data. 
 * @return mixed Either true or null, if postmeta data is updated then true else null.
 */
function edit_post_meta(array $post_meta = null, int $post_id = null)
{
    $post_meta_data = null;
    $CI = & get_instance();
    $CI->load->model('Post_model');

    //filters
    $data = apply_filters( 'before_edit_post_meta', $post_id, $CI->input->post());
    $should_edit_post_meta = apply_filters( 'should_edit_post_meta', $post_id, $CI->input->post());

    if (!$should_edit_post_meta) {
        return;
    }

    if(empty($post_meta)){
        return null;
    }
   
    // before editing postmeta 
    do_action('pre.edit.post_meta', $post_id, $CI->input->post());
    
    $updated_flag = $CI->Post_model->edit_post_meta($post_meta,$post_id);
    if(empty($updated_flag)){
        return null;
    }

    // before editing postmeta 
    do_action('post.edit.post_meta', $post_id, $post_id, $CI->input->post());
     
    return true;
}

/**
 * delete a postmeta data.
 * 
 * @param int $post_id The id of the post corresponds to the postmeta data.
 * @return mixed Either true or null, if postmeta data is deleted then true else null.
 * 
 */
function delete_post_meta(int $post_id = null)
{ 
    $CI = & get_instance();
    $CI->load->model('Post_model');

    // filters 
    $post = apply_filters( 'before_delete_post_meta', $post_id, $CI->input->post());
    $should_delete_post_meta = apply_filters( 'should_delete_post_meta', $post_id, $CI->input->post());
    if (!$should_delete_post_meta) {
        return;
    }

    if(isset($post_id) && $post_id == null){
        return null;
    }

    //for action befor deleteting the postmeta
    do_action('pre.delete.post_meta', $post_id, $CI->input->post()); 
  
    $delete_flag = $CI->Post_model->delete_post_meta($post_id);
    if(empty($delete_flag)){
        return null;
    }
    
    //for action after deleteting the postmeta
    do_action('post.delete.post_meta', $post_id, $CI->input->post());
  
    return true;
}