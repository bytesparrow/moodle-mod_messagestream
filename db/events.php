<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\local_nmstream\event\comment_created',
        'callback'    => '\mod_messagestream\observer::handle_nmstream_entry_written',
        #'includefile' => '/mod/messagestream/classes/observer.php',
        'priority'    => 1000,
    ],
  [
        'eventname'   => '\local_nmstream\event\comment_deleted',
        'callback'    => '\mod_messagestream\observer::handle_nmstream_entry_deleted',
        #'includefile' => '/mod/messagestream/classes/observer.php',
        'priority'    => 1000,
    ],
];
