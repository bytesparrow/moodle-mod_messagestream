<?php
/**
 * @package    mod_stream
 * @copyright  2025 Bernhard Strehl <moodle@bytesparrow.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
// phpcs:disable moodle.Files.RequireLogin.Missing
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require $_SERVER ["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'config.php';

require_once(__DIR__ . '/lib.php');


$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('stream', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$stream = $DB->get_record('stream', ['id' => $cm->instance], '*', MUST_EXIST);
require_login($course, true, $cm);
$context = context_module::instance($cm->id);
$stream->coursemodule = $cm->id;

$action = optional_param('action', '', PARAM_ALPHA);

//TODO temporary to check the point-feature
if (optional_param('action', '', PARAM_ALPHA) === 'submit' && confirm_sesskey()) {
  require_login($course, false, $cm);

  stream_update_grades($stream, $USER->id);
  
  redirect(
    new moodle_url('/mod/stream/view.php', ['id' => $cm->id]),
    get_string('pointsawarded', 'mod_stream'),
    null,
    \core\output\notification::NOTIFY_SUCCESS
  );
}
$PAGE->set_url(new moodle_url('/mod/stream/view.php', ['id' => $id]));
$PAGE->set_title($stream->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_context($context);

$output = $PAGE->get_renderer('mod_stream');
$renderable = new \mod_stream\output\view($stream, $context);

echo $output->header();
echo $output->render($renderable);
echo $output->footer();
