<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete messagestream structure for backup.
 */
class backup_messagestream_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Define each element to be processed
        $messagestream = new backup_nested_element('messagestream', ['id'], [
            'name', 'intro', 'introformat', 'promptrefinement', 'points', 'timecreated', 'timemodified'
        ]);

        // Set sources
        $messagestream->set_source_table('messagestream', ['id' => backup::VAR_ACTIVITYID]);

        // Annotate files (e.g., intro content)
        $messagestream->annotate_files('mod_messagestream', 'intro', null);

        return $this->prepare_activity_structure($messagestream);
    }
}
