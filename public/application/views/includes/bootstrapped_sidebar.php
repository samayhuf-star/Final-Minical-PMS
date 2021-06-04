<?php 
$module_menus = $this->module_menus;

$primary_menus = $this->Menu_model->get_menus(array('parent_id' => 0, 'partner_type_id' => 1));
$side_menus = $sub_menus = $sidebar_menus = array(); 

$first_segment= $this->uri->segment(1);
$second_segment= $this->uri->segment(2);
$third_segment= $this->uri->segment(3);

$my_companies = $this->Company_model->get_companies($this->user_id);

?>
<div class="app-sidebar sidebar-shadow">

    <div class="scrollbar-sidebar ps ps--active-y">
        <div class="sidebar__logo ">
         
            <ul class="multi-properties hide_properties <?=$this->session->userdata('user_role') == "is_housekeeping" ? "hidden" : "";?>">
                <?php if(count($my_companies) > 1){ ?>
                    <a href='#' id="myPropertyMenu"  data-toggle="dropdown" aria-expanded="true" tabindex="-1">
                    <?php } else { ?>
                        <a href='#'  id="myPropertyMenu">
                        <?php } ?>

                        <span class="logo"> <b><?php echo substr($this->company_name, 0, 30).((strlen($this->company_name)>30)?'...':''); ?></b>
                        </span>
                        <?php if(count($my_companies) > 1) { ?><span class="caret multi-prop"></span> <?php } ?>

                    </a>
                    <?php

                    $user_permissions = $this->session->userdata('user_permissions');
                    if (!$user_permissions) {
                        $user_permissions = $this->Permission_model->_get_user_permissions($this->user_id, $this->company_id);
                        $this->session->set_userdata(array('user_permissions' => $user_permissions));
                    }
                    ?>

                    <?php if(count($my_companies) > 1){ ?>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="myAccountMenu">
                            <?php 
                            foreach($my_companies as $key => $values){ 
                                echo '<li>
                                <a class="my-companies" role="menuitem" tabindex="-1"  href="'.base_url().'menu/select_hotel/'.$values['company_id'].'">
                                '.$values['name'].'
                                </a></li>';
                            }
                            ?>
                        </ul>
                    <?php } ?>
                </ul>
                
            </div>
            <div class="app-sidebar__inner">
                <ul class="vertical-nav-menu metismenu">
                    <?php foreach($primary_menus as $m_menu){ ?>

                        <?php $sub_menus = $this->Menu_model->get_menus(array('parent_id' => $m_menu['id'], 'partner_type_id' => 1)); ?>
                        <?php 
                        
                        $sub_menu_max_key = count($sub_menus) > 0 ? max(array_keys($sub_menus)) : 1;
                        $sub_menu_max_key = $sub_menu_max_key + 1;

                        foreach ($module_menus as $module_sub_menus) {
                            foreach ($module_sub_menus as $key => $value) {
                                if($value['location'] == "SECONDARY"){
                                    if(isset($value['parent_menu']) && ucwords($value['parent_menu']) == ucwords($m_menu['name'])){
                                        $sub_menus[$sub_menu_max_key]['id'] = null;
                                        $sub_menus[$sub_menu_max_key]['name'] = $value['label'];
                                        $sub_menus[$sub_menu_max_key]['link'] = $value['link'];
                                        $sub_menus[$sub_menu_max_key]['icon'] = null;
                                        $sub_menus[$sub_menu_max_key]['parent_id'] = null;
                                        $sub_menus[$sub_menu_max_key]['partner_type_id'] = null;

                                        $sub_menu_max_key = $sub_menu_max_key + 1;
                                    }
                                }
                            }
                        }

                        if(count($sub_menus) > 0){ ?>
                            <li class="<?php if ($first_segment == $m_menu['link']) echo 'mm-active'; ?>">
                                <a href="#" > 
                                    <i class="<?php echo $m_menu['icon']; ?>"></i>
                                    <?php echo ucwords(l($m_menu['name'])); ?> 
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul class="mm-collapse <?php if ($first_segment ==  $m_menu['link']) echo 'mm-show'; ?>">
                                    <?php 
                                    $unique_submenu = array_unique($sub_menus,SORT_REGULAR);

                                    foreach($unique_submenu as $m_menu_one) { 
                                        if(isset($m_menu_one['id']) && $m_menu_one['id'])
                                            $sidebar_menus = $this->Menu_model->get_menus(array('parent_id' => $m_menu_one['id'], 'partner_type_id' => 1));


                                        $sidebar_menu_max_key = count($sidebar_menus) > 0 ? max(array_keys($sidebar_menus)) : 1;
                                        $sidebar_menu_max_key = $sidebar_menu_max_key + 1;

                                        foreach ($module_menus as $module_sidebar_menus) {
                                            foreach ($module_sidebar_menus as $key => $value) {
                                                if($value['location'] == "THIRD"){
                                                    if(isset($value['parent_menu']) && $value['parent_menu']){
                                                        $ext_menu = explode('/', $value['parent_menu']);
                                                    }
                                                    if(isset($value['parent_menu']) && 
                                                        ucwords($ext_menu[0]) == ucwords($m_menu['name']) && 
                                                        ucwords($ext_menu[1]) == ucwords($m_menu_one['name'])
                                                    ) 
                                                    {
                                                        $side_menus['id'] = null;
                                                        $side_menus['name'] = $value['label'];
                                                        $side_menus['link'] = $value['link'];
                                                        $side_menus['icon'] = null;
                                                        $side_menus['parent_id'] = null;
                                                        $side_menus['partner_type_id'] = null;

                                                        $sidebar_menus[] = $side_menus;
                                                    }
                                                }
                                            }
                                        }

                                        if(count($sidebar_menus) > 0) { ?>
                                            <li class="<?php if ($first_segment.'/'.$second_segment ==  $m_menu_one['link']) echo 'mm-active'; ?>">
                                                <a href="#">
                                                    <i class="metismenu-icon"></i>
                                                    <?php echo ucwords(l($m_menu_one['name'])); ?>
                                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                                </a>
                                                <ul class="mm-collapse <?php if ($first_segment.'/'.$second_segment ==  $m_menu_one['link']) echo 'mm-show'; ?>">
                                                    <?php  
                                                    foreach($sidebar_menus as $m_menu_two) { ?>
                                                        <li class="<?php if ($first_segment.'/'.$second_segment.'/'.$third_segment == $m_menu_two['link']) echo 'mm-active'; ?>">
                                                            <a class="<?php if ($first_segment.'/'.$second_segment.'/'.$third_segment ==  $m_menu_two['link']) echo 'mm-active'; ?>" href="<?php echo base_url().$m_menu_two['link']; ?>">
                                                                <i class="metismenu-icon"></i>
                                                                <?php echo ucwords(l($m_menu_two['name'])); ?>
                                                            </a>
                                                        </li>
                                                    <?php } $sidebar_menus = array(); ?>
                                                </ul>
                                            </li>
                                        <?php } else { ?>

                                            <li class="<?php if ($first_segment.'/'.$second_segment == $m_menu_one['link']) echo 'mm-active'; ?>">
                                                <a class="<?php if ($first_segment.'/'.$second_segment ==  $m_menu_one['link']) echo 'mm-active'; ?>" href="<?php echo base_url().$m_menu_one['link']; ?>">
                                                    <i class="<?php echo $m_menu_one['icon']; ?>"></i>
                                                    <?php
                                                    echo ucwords(l($m_menu_one['name']));
                                                    ?>
                                                </a>
                                            </li>

                                        <?php } } ?>
                                    </ul>
                                </li>
                            <?php } else { ?>

                                <li class="<?php if ($first_segment == $m_menu['link']) echo 'mm-active'; ?>">
                                    <a class="<?php if ($first_segment == $m_menu['link']) echo 'mm-active'; ?>" href="<?php echo base_url().$m_menu['link']; ?>">
                                        <i class="<?php echo $m_menu['icon']; ?>"></i>
                                        <?php
                                        echo ucwords(l($m_menu['name']));
                                        ?>
                                    </a>
                                </li>

                            <?php } } ?>

                           <?php 
                            if(count($module_menus) > 0){
                                foreach($module_menus as $key => $mod_menu){ 
                                    foreach($mod_menu as $key1 => $m_menu){ 
                                        if($m_menu['location'] == 'PRIMARY'){ ?>
                                            <li class="<?php if ($first_segment  == $m_menu['link']) echo 'mm-active'; ?>">
                                                <?php if($m_menu['link'] == ''){ ?> 
                                                    <a href="#" aria-expanded="<?php if ($first_segment.'/'.$second_segment  ==  $m_menu['link'])  echo true; ?>">
                                                        <i class="metismenu-icon pe-7s-note2"></i>
                                                        <?php echo l($m_menu['label']);?>
                                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                                    </a>
                                                <?php } else { 
                                                    if($m_menu["label"] == 'Hoteli.pay') {
                                                        if( $this->selected_payment_gateway == 'asaas' ){ ?>
                                                            <a class="<?php if ($first_segment.'/'.$second_segment  ==  $m_menu['link']) 
                                                            echo 'mm-active'; ?>" href="<?php echo base_url().$m_menu['link'];?>">
                                                            <?php echo l($m_menu["label"]);?>
                                                            <i class="metismenu-icon pe-7s-cash"></i>
                                                        </a>
                                                    <?php } } else { ?>

                                                        <a class="<?php if ($first_segment.'/'.$second_segment  ==  $m_menu['link']) 
                                                        echo 'mm-active'; ?>" href="<?php echo base_url().$m_menu['link'];?>">
                                                        <?php echo l($m_menu["label"]);?>
                                                        <i class="metismenu-icon pe-7s-menu"></i>
                                                    </a>
                                                <?php } } }
                                                elseif($m_menu['location'] == 'SECONDARY'){
                                                    if( ucwords($m_menu['parent_menu']) == $mod_menu[0]['label']){ ?>
                                                        <ul class="mm-collapse <?php if ($first_segment.'/'.$second_segment ==  $m_menu['link']) echo   'mm-show'; ?>">
                                                            <li class="<?php if ($first_segment.'/'.$second_segment  == $m_menu['link']) echo 'mm-active'; ?>">
                                                                <a class="<?php if ($first_segment.'/'.$second_segment  ==  $m_menu['link']) 
                                                                echo 'mm-active'; ?>" 
                                                                href="<?php echo base_url().$m_menu['link'];?>">
                                                                <?php echo l($m_menu['label']);?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                <?php }
                                            } 
                                        } ?>
                                    </li>
                                <?php  } 
                            } ?>
                        </ul>
                    </div>

                    
                    

                </div>

                <ul class="ui-theme-settings">

                    <?php $languages = get_enabled_languages();
                    $current_language = $this->session->userdata('language'); ?>
                    <li class='nav-link current_language <?=$this->session->userdata('user_role') == "is_housekeeping" ? "hidden" : "";?>'
                        data-toggle="popover" 
                        data-placement="bottom" 
                        data-trigger="manual" 
                        data-animation="true" 
                        data-content="Click here to change language.">
                        <a href='#' id="languageSelection" data-toggle="dropdown" aria-expanded="true">
                            <img class="rounded-circle" src="<?php echo base_url().'images/language_flags/'.$current_language.'.png'; ?>">
                            <span class="menu-title ml-2"><?php echo ' '.ucfirst($current_language); ?></span>
                        </a>
                        
                        <ul class="dropdown-menu" role="menu" aria-labelledby="languageSelection">
                            <?php if(!empty($languages)):
                                foreach($languages as $key => $value): ?>
                                    <li class="change-language" id="<?php echo $value['id'].','.strtolower($value['language_name']); ?>">
                                        <a  role="menuitem" tabindex="-1" href="javascript:void(0)">
                                            <img  width="42" class="rounded-circle" src="<?php echo base_url().'images/language_flags/'.$value['flag'].'.png'; ?>"><?php echo ' '.$value['language_name']; ?>
                                        </a>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </li>


                    <li class="dropdown d-inline-block">
                        <a type="button" id="myAccountMenu" aria-haspopup="true" aria-expanded="true" data-toggle="dropdown" class="m-2"><span id="user_email" class="profile-setting"><?php echo strtoupper(substr($this->session->userdata('first_name'),0,1).substr($this->session->userdata('last_name'),0,1)); ?></span>
                            <input id='user_id' class="" value='<?php echo $this->user_id; ?>' style='display:none;' />
                            <span class="menu-title ml-2"><?php echo($this->session->userdata('email'));?></span>
                        </a>
                        
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right dropdown-menu-rounded dropdown-menu " x-placement="top-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-124px, -303px, 0px);">
                            <div class="dropdown-menu-header ">
                                <div class="dropdown-menu-header-inner bg-dark">
                                    <div class="menu-header-content">
                                        <h5 class="menu-header-title"><?php echo($this->session->userdata('email'));?></h5>
                                        <hr/>
                                    </div>
                                </div>
                            </div>
                            

                            <button class="dropdown-item">
                                <a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>account_settings" id="account-link" class="<?=$this->session->userdata('user_role') == "is_housekeeping" ? "hidden" : "";?>">
                                    <i class="glyphicon glyphicon-user"></i> <?php echo l('my_account'); ?>
                                </a>
                            </button>



                            <button class="dropdown-item">  <a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>auth/logout" >
                                <i class="glyphicon glyphicon-log-out"></i>
                                <?php echo l('logout'); ?>
                            </a>
                        </button>


                    </div>
                </li>
            </ul>
            
        </div>
        