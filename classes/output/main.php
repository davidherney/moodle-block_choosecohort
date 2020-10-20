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
 * Class containing renderers for the block.
 *
 * @package   block_choosecohort
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_choosecohort\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for the block.
 *
 * @copyright 2020 David Herney @ BambuCo
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class main implements renderable, templatable {

    /**
     * Block configuration.
     * @var object
     */
    public $config;

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $OUTPUT, $PAGE, $DB, $USER;

        $cohorts = get_config('block_choosecohort', 'cohorts');

        $cohorts = explode("\n", $cohorts);

        $cohorttosearch = array();
        foreach ($cohorts as $cohort) {
            $cohort = trim($cohort);

            if (!empty($cohort)) {
                $cohorttosearch[] = $cohort;
            }
        }

        $cohortslist = $DB->get_records_list('cohort', 'idnumber', $cohorttosearch, 'name');
        $member = $DB->get_records('cohort_members', array('userid' => $USER->id), '', 'cohortid AS id');

        $cohortsres = array();
        foreach ($cohortslist as $cohort) {
            if ($cohort->visible == 1) {
                $cohort->selected = isset($member[$cohort->id]);
                $cohortsres[] = $cohort;
            }
        }
//        $PAGE->requires->string_for_js('alternate_small', 'block_choosecohort');

        $defaultvariables = [
            'loadingimg' => $OUTPUT->pix_icon('i/loading', get_string('loadinghelp')),
            'cohorts' => $cohortsres
        ];

        $PAGE->requires->js_call_amd('block_choosecohort/main', 'init');

        return $defaultvariables;
    }
}
