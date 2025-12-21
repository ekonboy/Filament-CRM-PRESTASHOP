<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking soft_group_lang for group 4...\n";
$lang = \DB::table('soft_group_lang')->where('id_group', 4)->get();
foreach($lang as $l) {
    echo "Lang: {$l->id_lang}, Name: {$l->name}\n";
}

echo "\nChecking all groups...\n";
$groups = \DB::table('soft_group_lang')->get();
foreach($groups as $g) {
    echo "Group {$g->id_group}, Lang {$g->id_lang}: {$g->name}\n";
}
