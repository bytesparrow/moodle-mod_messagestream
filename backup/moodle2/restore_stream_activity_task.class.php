<?php

defined('MOODLE_INTERNAL') || die();
// Sollte per autoload gehen. Gibt aber den Fehler: Class "restore_stream_activity_structure_step" not found
require_once(__DIR__.'/restore_stream_stepslib.php');
/**
 * Restore task for the stream activity.
 */
class restore_stream_activity_task extends restore_activity_task {

    protected function define_my_settings() {
        // No special settings
    }

    protected function define_my_steps() {
        $this->add_step(new restore_stream_activity_structure_step('stream_structure', 'stream.xml'));
    }

    public static function define_decode_contents() {
        return [
            new restore_decode_content('stream', ['intro'], 'stream')
        ];
    }

    public static function define_decode_rules() {
        return [
            new restore_decode_rule('STREAMVIEWBYID', '/mod/stream/view.php?id=$1', 'course_module')
        ];
    }
}
