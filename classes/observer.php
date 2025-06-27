<?php
namespace mod_messagestream;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/messagestream/lib.php');

class observer {
    public static function handle_nmstream_entry_written(\local_nmstream\event\comment_created $event) {
        $userid = $event->userid;
        $messagestreamid = $event->objectid;
       
        $rootid = $event->other['rootid'];
        $roottype = $event->other['roottype'];
        $courseid = $event->courseid;

        $timecreated = $event->timecreated;
        
        // Determine the correct course module ID based on roottype
        $cmid = null;
        if ($roottype === 'activity') {
            // For activity context, rootid is the course module ID
            $cmid = $rootid;
        } else if ($roottype === 'course') {
            // For course context, we need to find the messagestream module in this course
            // This is a fallback - ideally comments should be associated with specific activities
            debugging("Course-level comments not supported for messagestream points", DEBUG_DEVELOPER);
            return;
        }
        
        if ($cmid) {
            \messagestream_set_points_for_user($cmid, $userid);
        }
        
        // Do something – for example, log or update something
       //debugging("local_nmstream received stream_entry_written event for user $userid and stream $messagestreamid in Context (cmid) $cmid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
    }
    
    public static function handle_nmstream_entry_deleted(\local_nmstream\event\comment_deleted $event) {
        $userid = $event->userid;
        $messagestreamid = $event->objectid;
       
        $rootid = $event->other['rootid'];
        $roottype = $event->other['roottype'];
        $courseid = $event->courseid;

        $timecreated = $event->timecreated;
        
        // Determine the correct course module ID based on roottype
        $cmid = null;
        if ($roottype === 'activity') {
            // For activity context, rootid is the course module ID
            $cmid = $rootid;
        } else if ($roottype === 'course') {
            // For course context, we need to find the messagestream module in this course
            // This is a fallback - ideally comments should be associated with specific activities
            debugging("Course-level comments not supported for messagestream points", DEBUG_DEVELOPER);
            return;
        }
        
        if ($cmid) {
            return \messagestream_set_points_for_user($cmid, $userid, true);
        }
        
        // Do something – for example, log or update something
     #  var_dump("local_nmstream received comment_deleted event for user $userid and stream $messagestreamid in Context (cmid) $cmid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
      # exit;
    }
}
