<?php

use Illuminate\Support\Facades\Artisan;
use App\Jobs\CollectPagesInfo;

Artisan::command('collect-pages-info', function () {
    CollectPagesInfo::dispatchSync();
    $this->info('Pages info collection job created');
})->purpose('Collects pages ranks from api')->daily();
