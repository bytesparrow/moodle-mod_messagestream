<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Restore structure step for stream activity.
 */
class restore_stream_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {
        $paths = [];

        $paths[] = new restore_path_element('stream', '/activity/stream');

        return $this->prepare_activity_structure($paths);
    }

    protected function process_stream($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $newitemid = $DB->insert_record('stream', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute() {
        // Add files (intro, etc.)
        $this->add_related_files('mod_stream', 'intro', null);
    }
}
