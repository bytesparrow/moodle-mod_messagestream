<?php
/**
 * @package    mod_messagestream
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
$cm = get_coursemodule_from_id('messagestream', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$messagestream = $DB->get_record('messagestream', ['id' => $cm->instance], '*', MUST_EXIST);
require_login($course, true, $cm);
$context = context_module::instance($cm->id);
$messagestream->coursemodule = $cm->id;


$PAGE->set_url(new moodle_url('/mod/messagestream/view.php', ['id' => $id]));
$PAGE->set_title($messagestream->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_context($context);

$output = $PAGE->get_renderer('mod_messagestream');
$renderable = new \mod_messagestream\output\view($messagestream, $context);

echo $output->header();
echo $output->render($renderable);
echo $output->footer();
