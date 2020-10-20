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
 * Form for editing choosecohort block instances.
 *
 * @package   block_choosecohort
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_choosecohort extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_choosecohort');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $CFG, $OUTPUT, $USER;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = '';
        $this->content->footer = '';

        if (!$USER || !$USER->id || isguestuser()) {
            return $this->content;
        }

        // Load templates and other general information.
        $renderable = new \block_choosecohort\output\main();
        $renderer = $this->page->get_renderer('block_choosecohort');

        $this->content->text = $renderer->render($renderable);


        return $this->content;
    }

    public function instance_can_be_docked() {
        return false;
    }

}
