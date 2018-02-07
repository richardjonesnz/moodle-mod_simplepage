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
 * Functions for inserting and displaying content
 * @package    filter
 * @subpackage panel
 * @copyright  2017 Richard Jones (https://richardnz.net/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/**
 * Custom renderer class for simplepage
 * @copyright  2018 Richard Jones (https://richardnz.net/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_simplepage_renderer extends plugin_renderer_base {

    public function get_comment_section($content, $contextid, $courseid) {
        
        $html = '';

        // Div for any comments
        $content_div_attributes = 
                array('class' => 'mod_simplepage_comments');
        $html .= html_writer::start_tag('div', $content_div_attributes);
        $html .= $content; 

        // Now the comment boxes
        $cmt = new stdClass();
        $cmt->contextid = $contextid;
        $cmt->area      = 'page_comments';
        $cmt->component = 'mod_simplepage';
        $cmt->itemid    = 0;
        $cmt->showcount = true;
        $comment = new comment($cmt);
        $html .= $comment->output(false);   
    }
}