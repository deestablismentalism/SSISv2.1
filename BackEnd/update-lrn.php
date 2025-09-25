<?php

$normal = 'PHYSICAL EDUCATION';
$trimmed = trim($normal);

$pregged = preg_replace('/\s+/', '', $trimmed);

echo $normal . '<br>';
echo $trimmed . '<br>';
echo $pregged; 