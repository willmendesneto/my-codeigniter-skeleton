<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generate FCKEDITOR
 * Ex:
 * $data = array(
 *      'name' => 'text',
 *      'id' => 'text',
 *      'toolbarset' => 'Default',
 *      'basepath' => base_url().'assets/plugins/fckeditor/',
 *      'width' => '100%',
 *      'height' => '400'
 * );
 * echo form_fckeditor($data, $initial_value);
 *
 * @param array $data
 * @param string $value
 * @return type
 */
function form_fckeditor($data = '', $value = '')
{
    $CI =& get_instance();

    $fckeditor_basepath = $CI->config->item('fckeditor_basepath');
    $server_basepath = $CI->config->item('server_basepath');

    require_once( $fckeditor_basepath. 'fckeditor.php' );
    $instanceName = ( is_array($data) && isset($data['name'])  ) ? $data['name'] : $data;
    $fckeditor = new FCKeditor($instanceName);

    if( $fckeditor->IsCompatible() )
    {
        $fckeditor->Value = html_entity_decode($value);
        $fckeditor->BasePath = $fckeditor_basepath;
        if( $fckeditor_toolbarset = $CI->config->item('fckeditor_toolbarset_default'))
            $fckeditor->ToolbarSet = $fckeditor_toolbarset;

        if( is_array($data) )
        {
            if( isset($data['value']) )
                $fckeditor->Value = html_entity_decode($data['value']);
            if( isset($data['basepath']) )
                $fckeditor->BasePath = $data['basepath'];
            if( isset($data['toolbarset']) )
                $fckeditor->ToolbarSet = $data['toolbarset'];
            if( isset($data['width']) )
                $fckeditor->Width = $data['width'];
            if( isset($data['height']) )
                $fckeditor->Height = $data['height'];
        }


        return $fckeditor->CreateHtml();
    }
    return form_textarea( $data, $value );
}