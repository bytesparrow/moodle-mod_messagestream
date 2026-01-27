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
$string['enableai'] = 'KI aktivieren';
$string['adminenableai'] = 'KI aktivieren';
$string['adminenableai_help'] = 'If activated, an AI can answer messages';
$string['adminaidefaulton'] = 'Default "Use AI" enabled';
$string['adminaidefaulton_help'] = 'Controls whether the "Use AI" option is enabled or disabled when composing a post.';
$string['promptrefinement'] = 'Prompt refinement (used for AI). E.g. "Provide your opinion on the user\'s post."';
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