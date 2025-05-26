<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\local_nmstream\event\comment_created',
        'callback'    => '\mod_stream\observer::handle_stream_entry_written',
        'includefile' => '/mod/stream/classes/observer.php',
        'priority'    => 1000,
    ],
];
