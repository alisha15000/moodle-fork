<?php
namespace local_ai_filter;

defined('MOODLE_INTERNAL') || die();

class observer {

    public static function ai_pre_request(\core\event\ai_request_prepared $event) {
        global $USER;

        $data = $event->get_data();

        // Prompt is stored inside "other"
        $prompt = $data['other']['prompt'] ?? '';

        // Run your filter
        $result = filter::check_prompt($prompt, $USER->id);

        if (!$result['allowed']) {
            throw new \moodle_exception("Blocked by AI Safety Filter: " . $result['reason']);
        }
    }
}
