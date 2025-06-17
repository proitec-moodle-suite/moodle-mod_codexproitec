<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants.
 *
 * @package     mod_codexproitec
 * @copyright   2025 DEAD/ZL/IFRN <dead.zl@ifrn.edu.br>, Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../medalhasproitec/lib.php');

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function codexproitec_supports($feature)
{
    return match ($feature) {
        FEATURE_MOD_ARCHETYPE => MOD_ARCHETYPE_RESOURCE,
        FEATURE_GROUPS => false,
        FEATURE_GROUPINGS => false,
        FEATURE_MOD_INTRO => false,
        FEATURE_COMPLETION => false,
        FEATURE_COMPLETION_TRACKS_VIEWS => false,
        FEATURE_GRADE_HAS_GRADE => false,
        FEATURE_GRADE_OUTCOMES => false,
        FEATURE_BACKUP_MOODLE2 => true,
        FEATURE_SHOW_DESCRIPTION => false,
        FEATURE_MOD_PURPOSE => MOD_PURPOSE_CONTENT,
        FEATURE_MODEDIT_DEFAULT_COMPLETION => false,
        FEATURE_QUICKCREATE => true,
        default => null,
    };
}

/**
 * Saves a new instance of the mod_codexproitec into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_codexproitec_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function codexproitec_add_instance($moduleinstance, $mform = null)
{
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('codexproitec', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_codexproitec in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_codexproitec_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function codexproitec_update_instance($moduleinstance, $mform = null)
{
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('codexproitec', $moduleinstance);
}

/**
 * Removes an instance of the mod_codexproitec from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function codexproitec_delete_instance($id)
{
    global $DB;

    $exists = $DB->get_record('codexproitec', ['id' => $id]);
    if (!$exists) {
        return false;
    }

    $DB->delete_records('codexproitec', ['id' => $id]);

    return true;
}

/**
 * Is a given scale used by the instance of mod_codexproitec?
 *
 * This function returns if a scale is being used by one mod_codexproitec
 * if it has support for grading and scales.
 *
 * @param int $moduleinstanceid ID of an instance of this module.
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by the given mod_codexproitec instance.
 */
function codexproitec_scale_used($moduleinstanceid, $scaleid)
{
    global $DB;

    if ($scaleid && $DB->record_exists('codexproitec', ['id' => $moduleinstanceid, 'grade' => -$scaleid])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of mod_codexproitec.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by any mod_codexproitec instance.
 */
function codexproitec_scale_used_anywhere($scaleid)
{
    global $DB;

    if ($scaleid && $DB->record_exists('codexproitec', ['grade' => -$scaleid])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given mod_codexproitec instance.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param bool $reset Reset grades in the gradebook.
 * @return void.
 */
function codexproitec_grade_item_update($moduleinstance, $reset = false)
{
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    $item = [];
    $item['itemname'] = clean_param($moduleinstance->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;

    if ($moduleinstance->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $moduleinstance->grade;
        $item['grademin']  = 0;
    } else if ($moduleinstance->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$moduleinstance->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }
    if ($reset) {
        $item['reset'] = true;
    }

    grade_update('/mod/codexproitec', $moduleinstance->course, 'mod', 'mod_codexproitec', $moduleinstance->id, 0, null, $item);
}

/**
 * Delete grade item for given mod_codexproitec instance.
 *
 * @param stdClass $moduleinstance Instance object.
 * @return grade_item.
 */
function codexproitec_grade_item_delete($moduleinstance)
{
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    return grade_update(
        '/mod/codexproitec',
        $moduleinstance->course,
        'mod',
        'codexproitec',
        $moduleinstance->id,
        0,
        null,
        ['deleted' => 1]
    );
}

/**
 * Update mod_codexproitec grades in the gradebook.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param int $userid Update grade of specific user only, 0 means all participants.
 */
function codexproitec_update_grades($moduleinstance, $userid = 0)
{
    global $CFG, $DB;
    require_once($CFG->libdir . '/gradelib.php');

    // Populate array of grade objects indexed by userid.
    $grades = [];
    grade_update('/mod/codexproitec', $moduleinstance->course, 'mod', 'mod_codexproitec', $moduleinstance->id, 0, $grades);
}

/**
 * Returns the lists of all browsable file areas within the given module context.
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@see file_browser::get_file_info_context_module()}.
 *
 * @package     mod_codexproitec
 * @category    files
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return string[].
 */
function codexproitec_get_file_areas($course, $cm, $context)
{
    return [];
}

/**
 * File browsing support for mod_codexproitec file areas.
 *
 * @package     mod_codexproitec
 * @category    files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info Instance or null if not found.
 */
function codexproitec_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename)
{
    return null;
}

/**
 * Serves the files from the mod_codexproitec file areas.
 *
 * @package     mod_codexproitec
 * @category    files
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param stdClass $context The mod_codexproitec's context.
 * @param string $filearea The name of the file area.
 * @param array $args Extra arguments (itemid, path).
 * @param bool $forcedownload Whether or not force download.
 * @param array $options Additional options affecting the file serving.
 */
function codexproitec_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = [])
{
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);
    send_file_not_found();
}

/**
 * Extends the global navigation tree by adding mod_codexproitec nodes if there is a relevant content.
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $codexproitecnode An object representing the navigation tree node.
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function codexproitec_extend_navigation($codexproitecnode, $course, $module, $cm) {}

/**
 * Extends the settings navigation with the mod_codexproitec settings.
 *
 * This function is called when the context for the page is a mod_codexproitec module.
 * This is not called by AJAX so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@see settings_navigation}
 * @param navigation_node $codexproitecnode {@see navigation_node}
 */
function codexproitec_extend_settings_navigation($settingsnav, $codexproitecnode = null) {}




function codexproitec_cm_info_view(cm_info $cm)
{
    global $OUTPUT;


    $data = get_courses_progress_as_dict();
    $data["jornada"]->alcancada = $data["jornada"]->concluida == 100;
    $data["etica"]->alcancada = $data["etica"]->concluida == 100;
    $data["matematica"]->alcancada = $data["matematica"]->concluida == 100;
    $data["portugues"]->alcancada = $data["portugues"]->concluida == 100;

    // $data["jornada"]->alcancada = true;
    // $data["matematica"]->alcancada = true;
    // $data["etica"]->alcancada = true;
    // $data["portugues"]->alcancada = true;

    $data["meta_alcancada"] = $data["jornada"]->alcancada && $data["etica"]->alcancada && $data["matematica"]->alcancada && $data["portugues"]->alcancada;
    $data["svg_codecxproitec"] = $OUTPUT->render_from_template('mod_codexproitec/codex', $data);
    $content = $OUTPUT->render_from_template('mod_codexproitec/activitycard', $data);
    $cm->set_content($content, true);
}