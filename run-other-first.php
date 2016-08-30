<?php

require __DIR__ . '/./vendor/autoload.php';

// 이건 성공합니다.
// 문득 PHP에는 private class라는 개념이 없는데
// 이 원리를 이용하면 private class를 사용할 수 있겠다는 생각이 드네요.

echo \App\Violate\Other::say(), PHP_EOL;
echo \App\Violate\Another::say(), PHP_EOL;
