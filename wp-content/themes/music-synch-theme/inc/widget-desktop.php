<?php

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
  
function my_custom_dashboard_widgets() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('wpstars_desktop_widget', 'Kontakt: WP-Stars', 'wps_dashboard_info_widget');
}
 
function wps_dashboard_info_widget() {

    $json = file_get_contents('https://wp-stars.com/cdn/agency.json');
    
    $html = '';
    $css = '<style>';
    $css .= 'div#wpstars_desktop_widget.postbox{background: linear-gradient(90deg, rgba(33, 75, 65, 1) 1%, rgba(53, 93, 59, 1) 12%, rgba(102, 139, 59, 1) 41%, rgba(125, 162, 57, 1) 56%, rgba(153, 189, 55, 1) 74%, rgba(140, 175, 55, 1) 86%, rgba(118, 155, 59, 1) 100%); color: #fff;}';
    $css .= 'div#wpstars_desktop_widget.postbox h2.hndle.ui-sortable-handle {display: none;}';
    $css .= 'div#wpstars_desktop_widget.postbox button.handlediv {display: none;}';
    $css .= '</style>';

    if($json != false){
        $agency_api_data = json_decode($json);
        $iso_lang = get_locale();
        $iso_lang = explode('_',$iso_lang);

        if(!isset($iso_lang[0]) || $iso_lang[0] != 'de'){
            $iso_lang = 'en';
        }else{
            $iso_lang = 'de';
        }

        $html .= '<div><img src="'.$agency_api_data->logo_src.'" alt="'.$agency_api_data->$iso_lang->logo_alt_text.'"></div><br>';
        $html .= '<strong>'.$agency_api_data->$iso_lang->name.'</strong><br>';
        $html .= $agency_api_data->$iso_lang->email . '<br>';
        $html .= $agency_api_data->$iso_lang->phone . '<br>';
        $html .= $agency_api_data->$iso_lang->address . '<br>';
        $html .= $agency_api_data->$iso_lang->zip . ' ' . $agency_api_data->$iso_lang->city . ', ' . $agency_api_data->$iso_lang->country . '<br>';

    }
    
    echo $html . $css;
}