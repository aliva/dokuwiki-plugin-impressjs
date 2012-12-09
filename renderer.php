<?php
/**
 * Renderer for XHTML output
 *
 * @author Harry Fuecks <hfuecks@gmail.com>
 * @author Andreas Gohr <andi@splitbrain.org>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

// we inherit from the XHTML renderer instead directly of the base renderer
require_once DOKU_INC.'inc/parser/xhtml.php';
require_once DOKU_INC.'inc/template.php';

class renderer_plugin_impressjs extends Doku_Renderer_xhtml {
    private $data_x = 0;
    private $data_y = 0;
    
    public function document_start(){
        $this->doc .= '<!DOCTYPE html>
        <html>
            <head>
                <meta name="viewport" content="width=1024" />
                <meta charset="utf-8" />
                
                 <link href="'.DOKU_BASE.'lib/plugins/impressjs/impress.css" rel="stylesheet" />
            </head>'.///tpl_metaheaders(false).'
        '<body>
        <div id="impress"><div class="step">';
    }
    public function document_end(){
        $this->doc .= '</div>
            <script src="'.DOKU_BASE.'lib/plugins/impressjs/impress.js"></script>
            <script>impress().init();</script></body></html>';
    }
    function section_open($level) {
        $this->doc .= '';
    }
    function section_close() {
        $this->data_x += 1000;
        $this->doc .= '</div><div class="step slide" data-x="'.$this->data_x.'" data-y="'.$this->data_y.'">';
    }
        function php($text, $wrapper='code') {
        global $conf;

        if($conf['phpok']){
          ob_start();
          eval($text);
          $this->doc .= ob_get_contents();
          ob_end_clean();
        } else {
          $this->doc .= p_xhtml_cached_geshi($text, 'php', $wrapper);
        }
    }
        function phpblock($text) {
        $this->php($text, 'pre');
    }
}
