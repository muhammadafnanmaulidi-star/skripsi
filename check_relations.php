<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$dbName = DB::getDatabaseName();
$tables = DB::select('SHOW TABLES');
$output = [];

foreach ($tables as $t) {
    $tableName = array_values((array)$t)[0];
    $columns = Schema::getColumnListing($tableName);
    
    $fks = DB::select("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
    ", [$dbName, $tableName]);
    
    $fkCols = [];
    foreach ($fks as $fk) {
        $fkCols[] = $fk->COLUMN_NAME . ' -> ' . $fk->REFERENCED_TABLE_NAME . '.' . $fk->REFERENCED_COLUMN_NAME;
    }
    
    $potentialFks = array_filter($columns, function($col) {
        return str_ends_with($col, '_id');
    });
    
    $missingFks = [];
    $existingFkColNames = array_map(function($fk) { return $fk->COLUMN_NAME; }, $fks);
    foreach ($potentialFks as $col) {
        if (!in_array($col, $existingFkColNames)) {
            $missingFks[] = $col;
        }
    }
    
    $output[] = [
        'table' => $tableName,
        'columns' => $columns,
        'foreign_keys' => $fkCols,
        'missing_fk_columns' => $missingFks,
    ];
}

file_put_contents(__DIR__ . '/check_result.json', json_encode($output, JSON_PRETTY_PRINT));
echo "Done! See check_result.json";
