<?php

defined('MOODLE_INTERNAL') || die();

require_once 'TinCanPHP/autoload.php';

class TestCompletion {
    public static function TestCourseCompletion($event) {
        global $DB, $CFG;
        if (get_config('local_tincantracking', 'enable_course')) {
            $lrs = new TinCan\RemoteLRS(
                get_config('local_tincantracking', 'endpoint'),
                '1.0.1',
                get_config('local_tincantracking', 'username'),
                get_config('local_tincantracking', 'password')
            );
            $eventdata = $event->get_data();

            $user     = $DB->get_record('user',   array('id'=>$eventdata['other']['relateduserid']));
            $course   = $DB->get_record('course', array('id'=>$eventdata['contextinstanceid']));
            $actor = new TinCan\Agent();
            $actor
                ->setMbox($user->email);
            $actor
                ->setName($user->username);
            $verb = new TinCan\Verb(
                    [ 'id' => 'http://adlnet.gov/expapi/verbs/completed' ]
                );
            $activity = new TinCan\Activity();
            $activity
                ->setId($CFG->wwwroot . '/course/view.php?id=' . $course->id)
                ->setDefinition([]);

            $activity->getDefinition()
                ->setType('http://activitystrea.ms/schema/1.0/page')
                ->getName()
                ->set('en-US', $course->fullname);
             
            $statement = new TinCan\Statement(
                [
                    'actor' => $actor,
                    'verb'  => $verb,
                    'object' => $activity,
                ]
            );
            
            $response = $lrs->saveStatement($statement);
            if ($response->success) {
                print "Statement sent successfully!\n";
            }
            else {
                print "Error statement not sent: " . $response->content . "\n";
            }
        }
    }

    public static function SendActivity($event) {
        global $DB, $CFG;
        if (get_config('local_tincantracking', 'enable_mod')) {
            $lrs = new TinCan\RemoteLRS(
                get_config('local_tincantracking', 'endpoint'),
                '1.0.1',
                get_config('local_tincantracking', 'username'),
                get_config('local_tincantracking', 'password')
            );
            $eventdata = $event->get_data(); 
            $eventdata = $event->get_record_snapshot($eventdata['objecttable'],$event->objectid);
            $userid = $eventdata->userid;
            
            $user     = $DB->get_record('user',   array('id'=>$userid));
            
            $cmodules             = $DB->get_record('course_modules',             array('id'=>$eventdata->coursemoduleid));
            $course               = $DB->get_record('course',                     array('id'=>$cmodules->course));
            $gradeitem            = $DB->get_record('grade_items',                array('iteminstance'=>$cmodules->instance, 'courseid'=>$course->id));

            $actor = new TinCan\Agent();
            $actor
                ->setMbox($user->email);
            $actor
                ->setName($user->username);
            $verb = new TinCan\Verb(
                    [ 'id' => 'http://adlnet.gov/expapi/verbs/completed' ]
                );
            $activity = new TinCan\Activity();
            $activity
                ->setId($CFG->wwwroot . '/course/view.php?id=' . $course->id)
                ->setDefinition([]);

            $activity->getDefinition()
                ->setType('http://activitystrea.ms/schema/1.0/page')
                ->getName()
                ->set('en-US', $gradeitem->itemname);
             
            $statement = new TinCan\Statement(
                [
                    'actor' => $actor,
                    'verb'  => $verb,
                    'object' => $activity,
                ]
            );
            
            $response = $lrs->saveStatement($statement);
            if ($response->success) {
                print "Statement sent successfully!\n";
            }
            else {
                print "Error statement not sent: " . $response->content . "\n";
            }
        }
    }
}

