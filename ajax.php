<?php
/**
 * Ajax endpoints for mod_messagestream (coach settings).
 *
 * @package    mod_messagestream
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('NO_DEBUG_DISPLAY')) {
    define('NO_DEBUG_DISPLAY', true);
}
require $_SERVER ["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'config.php';

require_login();

header('Content-Type: application/json; charset=utf-8');

$action = optional_param('action', '', PARAM_ALPHANUMEXT);

// Read-only actions could be added later; all current actions require sesskey.
require_sesskey();

if ($action !== 'save_coach_settings') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

$cmid = required_param('cmid', PARAM_INT);

list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'messagestream');
$context = context_module::instance($cm->id);
require_capability('moodle/course:manageactivities', $context);

$instance = $DB->get_record('messagestream', ['id' => $cm->instance], '*', MUST_EXIST);

$personaid = optional_param('persona_id', 0, PARAM_INT);
$overridesraw = optional_param('overrides', '{}', PARAM_RAW);

$overrides = json_decode($overridesraw, true);
if (!is_array($overrides)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid overrides JSON']);
    exit;
}

$DB->set_field('messagestream', 'persona_id', $personaid > 0 ? $personaid : null, ['id' => $instance->id]);

$service = new \local_nmstream\local\persona\PersonaService();
$service->save_messagestream_overrides((int) $instance->id, $overrides);

$compiled = $service->get_compiled_prompt_for_messagestream_instance((int) $instance->id);

echo json_encode([
    'success' => true,
    // Full prompt is returned so the UI preview isn't misleadingly cut off.
    // The UI already constrains height and is scrollable.
    'compiled_preview' => $compiled,
]);
