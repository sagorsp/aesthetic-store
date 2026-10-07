<?php
return ['default' => env('CACHE_STORE', 'database'), 'stores' => ['array'=>['driver'=>'array','serialize'=>false], 'database'=>['driver'=>'database','connection'=>env('DB_CACHE_CONNECTION'),'table'=>env('DB_CACHE_TABLE','cache'),'lock_connection'=>env('DB_CACHE_LOCK_CONNECTION'),'lock_table'=>env('DB_CACHE_LOCK_TABLE')]], 'prefix'=>env('CACHE_PREFIX','aesthetic_store_cache_')];
