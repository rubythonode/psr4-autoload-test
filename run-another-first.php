<?php

require __DIR__ . '/./vendor/autoload.php';

// 이건 실패합니다.

echo \App\Violate\Another::say(), PHP_EOL;
echo \App\Violate\Other::say(), PHP_EOL;
