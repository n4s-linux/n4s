<?php

require("../vendor/autoload.php");
use League\CommonMark\GithubFlavoredMarkdownConverter;

$converter = new GithubFlavoredMarkdownConverter([
    'html_input' => 'strip',
    'allow_unsafe_links' => false,
]);

$data = file_get_contents($argv[1]);
echo $converter->convert($data);

// <h1>Hello World!</h1>

?>
