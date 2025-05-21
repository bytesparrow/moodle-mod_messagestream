<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete stream structure for backup.
 */
class backup_stream_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Define each element to be processed
        $stream = new backup_nested_element('stream', ['id'], [
            'name', 'intro', 'introformat', 'promptrefinement', 'points', 'timecreated', 'timemodified'
        ]);

        // Set sources
        $stream->set_source_table('stream', ['id' => backup::VAR_ACTIVITYID]);

        // Annotate files (e.g., intro content)
        $stream->annotate_files('mod_stream', 'intro', null);

        return $this->prepare_activity_structure($stream);
    }
}
