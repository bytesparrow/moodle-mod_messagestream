<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/choice/backup/moodle2/backup_choice_stepslib.php'); // Because it exists (must)
require_once($CFG->dirroot . '/mod/choice/backup/moodle2/backup_choice_settingslib.php'); // Because it exists (optional)
require_once(__DIR__.'/backup_messagestream_stepslib.php'); // Sollte per autoload gehen. Gibt aber nen Fehler, dass nicht vorhanden.
/**
 * Backup task for the Messagestream activity.
 */
class backup_messagestream_activity_task extends backup_activity_task {

    protected function define_my_settings() {
        // No specific settings for this activity
    }

    protected function define_my_steps() {
        $this->add_step(new backup_messagestream_activity_structure_step('messagestream_structure', 'messagestream.xml'));
    }

    public static function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Encode links to the view page
        $pattern = "/({$base}\/mod\/messagestream\/view.php\?id=)([0-9]+)/";
        $replacement = '$@MESSAGESTREAMVIEWBYID*$2@$';
        $content = preg_replace($pattern, $replacement, $content);

        return $content;
    }
}
