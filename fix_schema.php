<?php
$c = file_get_contents('photo_schema.sql');
$c = preg_replace('/TABLE ([a-zA-Z0-9_]+)/', 'TABLE `$1`', $c);
$c = preg_replace('/REFERENCES ([a-zA-Z0-9_]+)/', 'REFERENCES `$1`', $c);
file_put_contents('photo_schema.sql', $c);
echo 'Updated schema with backticks';
