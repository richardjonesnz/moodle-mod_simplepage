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
 * Prints a particular instance of simplepage
 *
 *
 * @package    mod_simplepage
 * @copyright  2018 Richard Jones https://richardnz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... simplepage instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('simplepage', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $simplepage  = $DB->get_record('simplepage', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $simplepage  = $DB->get_record('simplepage', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $simplepage->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('simplepage', $simplepage->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_simplepage\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $simplepage);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/simplepage/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($simplepage->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($simplepage->intro) {
    echo $OUTPUT->box(format_module_intro('simplepage', $simplepage, $cm->id), 'generalbox mod_introbox', 'simplepageintro');
}

$renderer = $PAGE->get_renderer('mod_simplepage');
$content = "Some content";
$qid = 2;
echo $renderer->get_comment_section($content, $cm->id, $course);


// Finish the page.
echo $OUTPUT->footer();
