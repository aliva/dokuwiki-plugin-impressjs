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

class renderer_plugin_impressjs extends Doku_Renderer_xhtml {
    private $data_x = 0;
    private $data_y = 0;
    
    public function document_start(){
        global $conf;
        global $lang;
        
        $this->doc .= '<!DOCTYPE html>
            <html lang="'.$conf['lang'].'" dir="'.$lang['direction'].'">
            <head>
                <meta name="viewport" content="width=1024" />
                <meta charset="utf-8" />
                <title>'.hsc($title).'</title>
                
                <meta name="generator" content="impress.js" />
                <meta name="version" content="impress.js ab44798b081997319f4207dabbb052736acfc512
" />
                
                <link href="'.DOKU_BASE.'lib/plugins/impressjs/impress.css" rel="stylesheet" />
            </head>
        <body>
        <div id="impress">';
    }
    
    public function document_end(){
        $this->doc .= '</div>
        <script src="'.DOKU_BASE.'lib/plugins/impressjs/impress.js"></script>
        <script>impress().init();</script></body></html>';
    }
    public function section_close() {
        $this->doc .= "</div>";
        parent::section_close();
    }
    public function php($text, $wrapper='code') {
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
    public function phpblock($text) {
        $this->php($text, 'pre');
    }
    public function header($text, $level, $pos) {
        $this->data_x += 1000;
        $this->doc .= "<div class='".($level == 1 ? '' : 'slide ')."step' ";
        $this->doc .= "data-x='$this->data_x' data-y='$this->data_y'>";
        $this->doc .= "<h$level>$text</h$level>";
    }
}
