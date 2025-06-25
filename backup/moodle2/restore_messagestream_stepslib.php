<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Restore structure step for messagestream activity.
 */
class restore_messagestream_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {
        $paths = [];

        $paths[] = new restore_path_element('messagestream', '/activity/messagestream');

        return $this->prepare_activity_structure($paths);
    }

    protected function process_messagestream($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $newitemid = $DB->insert_record('messagestream', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute() {
        // Add files (intro, etc.)
        $this->add_related_files('mod_messagestream', 'intro', null);
    }
}
