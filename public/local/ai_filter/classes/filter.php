<?php
namespace local_ai_filter;

defined('MOODLE_INTERNAL') || die();

class filter {

    // Normalize prompt for comparison
    private static function normalize($text) {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
        return trim($text);
    }

    // Main function that checks user prompts
    public static function check_prompt($prompt, $userid = 0) {
        global $DB;

        // If filter disabled, allow everything
        if (!get_config('local_ai_filter', 'enabled')) {
            return ['allowed' => true];
        }

        // Create normalized & hashed version
        $normalized = self::normalize($prompt);
        $hash = hash('sha256', $normalized);

        // Look for exact match in DB
        $record = $DB->get_record('local_ai_prompts', ['prompthash' => $hash]);

        if ($record) {
            // Log block
            $DB->insert_record('local_ai_blocks', [
                'userid'      => $userid,
                'prompttext'  => $prompt,
                'reason'      => 'Exact match',
                'timecreated' => time()
            ]);

            return [
                'allowed' => false,
                'reason'  => 'Matched adversarial prompt'
            ];
        }

        return ['allowed' => true];
    }
}
