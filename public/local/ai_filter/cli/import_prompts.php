<?php
require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/clilib.php');

list($options, $unrecognised) = cli_get_params(
    ['file' => null],
    ['f' => 'file']
);

if (!$options['file']) {
    cli_error("Usage: php import_prompts.php --file=/path/to/list.txt");
}

$file = $options['file'];

if (!file_exists($file)) {
    cli_error("File not found");
}

$lines = file($file, FILE_IGNORE_NEW_LINES);

foreach ($lines as $line) {
    $normalized = mb_strtolower($line);
    $normalized = trim($normalized);
    $hash = hash('sha256', $normalized);

    $exists = $DB->record_exists('local_ai_prompts', ['prompthash' => $hash]);
    if ($exists) continue;

    $DB->insert_record('local_ai_prompts', [
        'prompt'      => $line,
        'prompthash'  => $hash,
        'normalized'  => $normalized,
        'timecreated' => time()
    ]);
}

cli_writeln("Imported prompts successfully.");
