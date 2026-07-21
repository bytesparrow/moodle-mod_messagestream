<?php
/**
 * @package    mod_messagestream
 * @copyright  2025 Bernhard Strehl <moodle@bytesparrow.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Messagetream Activity';
$string['modulenameplural'] = 'Messagestream Activities';
$string['modulename_help'] = 'The Messagestream Activity enables threaded discussions between students and an AI assistant. Students can post contributions and receive points for doing so. Additionally, the AI can respond to questions or provide feedback on their statements. This activity is designed to be used in combination with local_nmstream for interactive and automated dialogue-based learning.';
$string['messagestreamname'] = 'Activity name';
$string['adminprivacyactive'] = 'Allow private posts and comments';
$string['adminprivacyactive_help'] = 'Controls whether users can mark their entries/comments as private.';
$string['enableai'] = 'Enable AI';
$string['adminenableai'] = 'Enable AI';
$string['adminenableai_help'] = 'If activated, an AI can answer messages';
$string['adminaidefaulton'] = 'Default "Use AI" enabled';
$string['adminaidefaulton_help'] = 'Controls whether the "Use AI" option is enabled or disabled when composing a post.';
$string['promptrefinement'] = 'Additional prompt instructions for the AI. However, all configuration should be handled through the persona.';
$string['points'] = 'Points awarded (for gradebook)';
$string['pluginname'] = 'Messagestream Activity';
$string['clicktogetpoints'] = 'Click to receive your points!';
$string['youreceivedpoints'] = 'You received {$a} points!';
$string['pluginadministration'] = 'pluginadministration';
$string['getpoints'] = 'Get points';
$string['pointsawarded'] = 'Points have been awarded!';
$string['adminpoints'] = 'Points awarded';
$string['adminpoints_help'] = 'When a learner posts a contribution, they can receive points for it (once). Specify the number of points here.';
$string['aienabledcourses'] = 'AI enabled in courses';
$string['aienabledcourses_desc'] = 'Comma-separated list of course IDs where the Message Stream AI feature is enabled. Example: 2,5,8';
$string['statinfo'] = '{$a->count_posts} posts & {$a->count_comments} comments';
$string['statinfo_with_ai'] = '{$a->count_posts} posts, {$a->count_comments} comments & {$a->count_ai_comments} AI-generated answers';
$string['persona_id'] = 'NMStream persona';
$string['persona_id_help'] = 'Optional. Uses the compiled persona as an extra system instruction before the default course agent prompt and activity refinement text.';
$string['persona_none'] = '— None —';
$string['persona_overrides_json'] = 'Persona overrides (JSON, advanced)';
$string['persona_overrides_json_help'] = 'Optional sparse JSON object overriding fields from the selected persona (same keys as NMStream persona config, except encrypted solution hints). Example: {"task":"Focus on reflective writing."}';
$string['persona_overrides_json_invalid'] = 'Invalid JSON.';
$string['viewtab_stream'] = 'Activity';
$string['coachsettings:tab'] = 'Coach settings';
$string['coachsettings:persona'] = 'NMStream persona';
$string['coachsettings:configure_persona'] = 'Configure persona in activity';
$string['coachsettings:overrides_heading'] = 'Field overrides';
$string['coachsettings:override_hint'] = 'Enable a checkbox to override that field for this activity only. Values are stored as sparse JSON on the instance.';
$string['coachsettings:override_activate'] = 'Override this field';
$string['coachsettings:field'] = 'Field';
$string['coachsettings:value'] = 'Value';
$string['coachsettings:persona_default'] = 'Persona default';
$string['coachsettings:save'] = 'Save coach settings';
$string['coachsettings:saved'] = 'Coach settings saved.';
$string['coachsettings:edit_persona'] = 'Edit persona';
$string['coachsettings:new_persona'] = 'New persona';
$string['coachsettings:avatar_hint'] = 'Emoji, image URL, or upload an image (stored as data URL).';
$string['coachsettings:section_heading_style'] = 'A — Style';
$string['coachsettings:section_heading_feedback'] = 'B — Feedback';
$string['coachsettings:section_heading_knowledge'] = 'C — Knowledge';
$string['coachsettings:section_heading_interaction'] = 'D — Interaction';
$string['coachsettings:avatar_placeholder'] = 'Emoji or image URL';
$string['coachsettings:error_save'] = 'Error saving settings.';
$string['coachsettings:error_request'] = 'Request failed.';