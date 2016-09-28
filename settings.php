<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_tincantracking', 'TinCan Completion Tracking');     
    $ADMIN->add('localplugins', $settings);
    
    // Endpoint.
    $settings->add(new admin_setting_configtext('local_tincantracking/endpoint',
        get_string('endpoint', 'local_tincantracking'), '',
        'http://example.com/endpoint/location/', PARAM_URL));
    // Username.
    $settings->add(new admin_setting_configtext('local_tincantracking/username',
        get_string('username', 'local_tincantracking'), '', 'username', PARAM_TEXT));
    // Key or password.
    $settings->add(new admin_setting_configtext('local_tincantracking/password',
        get_string('password', 'local_tincantracking'), '', 'password', PARAM_TEXT));
     
    $settings->add(new admin_setting_configcheckbox('local_tincantracking/enable_course',
        get_string('enable_label', 'local_tincantracking'), get_string('enable_course', 'local_tincantracking'), 1));
    $settings->add(new admin_setting_configcheckbox('local_tincantracking/enable_mod',
        get_string('enable_label', 'local_tincantracking'), get_string('enable_mod', 'local_tincantracking'), 0));
}

