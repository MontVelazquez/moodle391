<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Core renderer.
 *
 * @package    theme_indivsa
 * @copyright  2017 Eduardo Kraus
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_indivisa\output;
defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_indivisa
 * @copyright  2012 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use html_writer;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use stdClass;
use moodle_url;
use action_menu;
use pix_icon;
use theme_config;
use core_text;
use help_icon;
use context_system;
use core_course_list_element;

class core_renderer extends \theme_boost\output\core_renderer{

    public function get_logo_universidad() {
        global $OUTPUT;
        $imageurl= $OUTPUT->pix_url('logo-universidad','theme');
        return $imageurl;
    }

    public function get_logo_plataforma(){
        global $OUTPUT;
        $imageurl= $OUTPUT->pix_url('logo-plataforma','theme');
        return $imageurl;

    }

    public function search_box($id = false) {
        global $CFG;

        // Accessing $CFG directly as using \core_search::is_global_search_enabled would
        // result in an extra included file for each site, even the ones where global search
        // is disabled.
        if (empty($CFG->enableglobalsearch) || !has_capability('moodle/search:query', context_system::instance())) {
            return '';
        }

        if ($id == false) {
            $id = uniqid();
        } else {
            // Needs to be cleaned, we use it for the input id.
            $id = clean_param($id, PARAM_ALPHANUMEXT);
        }

        // JS to animate the form.
        $this->page->requires->js_call_amd('core/search-input', 'init', array($id));

      /*  $searchicon = html_writer::tag('div', $this->pix_icon('a/search', get_string('search', 'search'), 'moodle'),
            array('role' => 'button', 'tabindex' => 0));*/
        
        
            $iconattrs = array(
                'class' => 'slicon-magnifier',
                'title' => get_string('search', 'search'),
                'aria-label' => get_string('search', 'search'),
                'aria-hidden' => 'true');

            $searchicon = html_writer::tag('i', '', $iconattrs);

            $divarr= array(
                'role' => 'button',
                'tabindex' => 0);

            $divicon= html_writer::tag('div',$searchicon,$divarr);

        
        $formattrs = array('class' => 'search-input-form', 'action' => $CFG->wwwroot . '/search/index.php');
        $inputattrs = array('type' => 'text', 'name' => 'q', 'placeholder' => get_string('search', 'search'),
            'size' => 13, 'tabindex' => -1, 'id' => 'id_q_' . $id, 'class' => 'form-control');

        $contents = html_writer::tag('label', get_string('enteryoursearchquery', 'search'),
            array('for' => 'id_q_' . $id, 'class' => 'accesshide')) . html_writer::empty_tag('input', $inputattrs);
        if ($this->page->context && $this->page->context->contextlevel !== CONTEXT_SYSTEM) {
            $contents .= html_writer::empty_tag('input', ['type' => 'hidden',
                    'name' => 'context', 'value' => $this->page->context->id]);
        }
        $searchinput = html_writer::tag('form', $contents, $formattrs);

        return html_writer::tag('div', $divicon . $searchinput, array('class' => 'search-input-wrapper nav-link', 'id' => $id));
    }
}