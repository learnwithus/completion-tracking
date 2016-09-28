<?php

$observers = array(
    array(
        'eventname'   => '\core\event\course_completed',
        'callback'    => 'TestCompletion::TestCourseCompletion',
        'includefile' => '/local/tincantracking/classes/TestCompletion.php'
    ),
    array(
        'eventname'   => '\core\event\course_module_completion_updated',
        'callback'    => 'TestCompletion::SendActivity',
        'includefile' => '/local/tincantracking/classes/TestCompletion.php'
    ),
);

?>
