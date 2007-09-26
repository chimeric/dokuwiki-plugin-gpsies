<?php
/**
 * DokuWiki Syntax Plugin Gpsies
 *
 * Embeds a gpsies track from gpsies.com in a dokuwiki page.
 *
 * Syntax:  {{gpsies>[track]}}
 *
 *   [gpsies] - the "field" value of the track URL
 * 
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Michael Klier <chi@chimeric.de>
 */
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_INC.'inc/parserutils.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_gpsies extends DokuWiki_Syntax_Plugin {


    /**
     * General Info
     */
    function getInfo(){
        return array(
            'author' => 'Michael Klier',
            'email'  => 'chi@chimeric.de',
            'date'   => '2007-09-26',
            'name'   => 'Gpsies',
            'desc'   => 'Embeds a track from gpsies into a dokuwiki page.',
            'url'    => 'http://www.chimeric.de/projects/dokuwiki/plugin/gpsies'
        );
    }

    /**
     * Syntax Type
     *
     * Needs to return one of the mode types defined in $PARSER_MODES in parser.php
     */
    function getType()  { return 'substition'; }
    function getPType() { return 'block'; }
    function getSort()  { return 316; }
    
    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{gpsies>.+?\}\}',$mode,'plugin_gpsies');
    }

    /**
     * Handler to prepare matched data for the rendering process
     */
    function handle($match, $state, $pos, &$handler){
        global $ID;
        $match = substr($match,9,-2); //strip {{gpsies> from start and }} from end
        return array($match);
    }

    /**
     * Handles the actual output creation.
     */
    function render($mode, &$renderer, $data) {
        global $lang;

        if($mode == 'xhtml'){
            // disable caching
            $renderer->info['cache'] = false;
            $renderer->doc .= $this->gpsies_xhtml($data[0]);
            return true;
        }
        return false;
    }

    /**
     * returns the XHTML output
     *
     * @author Michael Klier <chi@chimeric.de>
     */
    function gpsies_xhtml($track) {
        $out  = '<div class="plugin_gpsies">' . DOKU_LF;
        $out .= '  <div class="plugin_gpsies_header">' . DOKU_LF;
        $out .= '    <a href="http://gpsies.com/map.do?fileId=' . $track . '" class="plugin_gpsies" title="' . $this->getLang('visit_track') . '">' . $this->getLang('visit_track') . '</a>' . DOKU_LF;
        $out .= ' &middot; ';
        $out .= '<a href="http://gpsies.com/mapOnly.do?fileId=' . $track . '" target="_blank" title="' . $this->getLang('fullscreen') . '">' . $this->getLang('fullscreen') . '</a>';
        $out .= '  </div>' . DOKU_LF;
        $out .= '    <iframe src="http://gpsies.de/mapOnly.do?fileId=' . $track . '" width="500" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" title="GPSies - Heidenheim - Ötterlesbrunnen - Eselsburger Tal - Ugental - Heidenheim"></iframe>' . DOKU_LF;
        $out .= '    <img src="http://gpsies.de/charts/' . $track . '_map.png" alt="http://gpsies.com/map.do?fileId=' . $track . '" />' . DOKU_LF;
        $out .= '  <div class="plugin_gpsies_footer">' . DOKU_LF;
        $out .= '  </div>' . DOKU_LF;
        $out .= '</div>' . DOKU_LF;
        return ($out);
    }
}

//setup vim:ts=4:sw=4:enc=utf-8:
