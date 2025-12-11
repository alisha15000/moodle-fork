<?php
defined('MOODLE_INTERNAL') || die();

$settings->add(new admin_setting_configcheckbox(
    'local_ai_filter/enabled',
    'Enable AI Filter',
    'If enabled, user prompts will be checked for jailbreak/prompt injection',
    1
));
