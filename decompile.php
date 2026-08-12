<?php
$content = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index_backup.blade.php');

$replacements = [
    '/<\?php echo e\((.*?)\); \?>/' => '{{ $1 }}',
    '/<\?php if\((.*?)\): \?>/' => '@if($1)',
    '/<\?php elseif\((.*?)\): \?>/' => '@elseif($1)',
    '/<\?php else: \?>/' => '@else',
    '/<\?php endif; \?>/' => '@endif',
    '/<\?php foreach\((.*?) as (.*?)\): \?>/' => '@foreach($1 as $2)',
    '/<\?php endforeach; \?>(?:\$__env->popLoop\(\); \$loop = \$__env->getLastLoop\(\);)?/' => '@endforeach',
    '/<\?php \$__env->startSection\((.*?)\); \?>/' => '@section($1)',
    '/<\?php \$__env->stopSection\(\); \?>/' => '@endsection',
    '/<\?php \$__env->startPush\((.*?)\); \?>/' => '@push($1)',
    '/<\?php \$__env->stopPush\(\); \?>/' => '@endpush',
    '/<\?php echo csrf_field\(\); \?>/' => '@csrf',
    '/<\?php \$__errorArgs = \[(.*?)\];.*?\?>/' => '@error($1)',
    '/<\?php unset\(\$__errorArgs, \$__bag\); \?>/' => '@enderror',
    '/<\?php echo \$__env->make\((.*?)\).*?\?>/' => '@extends($1)',
];

foreach ($replacements as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content);
}

// Clean up some specific long generated code blocks for @error and foreach loops
$content = preg_replace('/<\?php \$__currentLoopData = (.*?);.*?\?>/s', '', $content);
$content = preg_replace('/<\?php \$__messageOriginal = \$message;.*?\?>/s', '', $content);
$content = preg_replace('/<\?php if \(isset\(\$__messageOriginal\)\) \{ \$message = \$__messageOriginal; \} \?>/s', '', $content);
$content = preg_replace('/<\?php \/\*\*PATH .*? \*\*\//', '', $content);

// Move @extends to the top if found
if (preg_match('/@extends\((.*?)\)/', $content, $matches)) {
    $content = str_replace($matches[0], '', $content);
    $content = "@extends(" . $matches[1] . ")\n" . $content;
}

file_put_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index_restored.blade.php', trim($content));
echo "Decompiled.";
