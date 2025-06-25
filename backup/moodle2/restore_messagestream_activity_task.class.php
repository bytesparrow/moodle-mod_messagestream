<?php

defined('MOODLE_INTERNAL') || die();
// Sollte per autoload gehen. Gibt aber den Fehler: Class "restore_messagestream_activity_structure_step" not found
require_once(__DIR__.'/restore_messagestream_stepslib.php');
/**
 * Restore task for the messagestream activity.
 */
class restore_messagestream_activity_task extends restore_activity_task {

    protected function define_my_settings() {
        // No special settings
    }

    protected function define_my_steps() {
        $this->add_step(new restore_messagestream_activity_structure_step('messagestream_structure', 'messagestream.xml'));
    }

    public static function define_decode_contents() {
        return [
            new restore_decode_content('messagestream', ['intro'], 'messagestream')
        ];
    }

    public static function define_decode_rules() {
        return [
            new restore_decode_rule('MESSAGESTREAMVIEWBYID', '/mod/messagestream/view.php?id=$1', 'course_module')
        ];
    }
}
