<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use ZipArchive;

class MaintenanceController extends Controller {

    public function index() {
        $this->requireSuperuser();
        return $this->render('maintenance.index');
    }

    public function backup() {
        $this->requireSuperuser();

        $schoolName = Setting::getValue(1) ?: 'database';
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $schoolName);
        $timestamp  = now()->format('Y-m-d_H-i-s');
        $sqlFile    = $schoolName . '-' . $timestamp . '.sql';
        $zipFile    = $schoolName . '-' . $timestamp . '.zip';

        $tmpDir = storage_path('app/backup');
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $sqlPath = $tmpDir . '/' . $sqlFile;
        $zipPath = $tmpDir . '/' . $zipFile;

        // Generate SQL dump
        $output = "-- Backup database: " . config('database.connections.mysql.database') . "\n";
        $output .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Structure
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0];
            $createSql = $createTable->{'Create Table'};
            $output .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $output .= $createSql . ";\n\n";

            // Data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                foreach ($rows->chunk(100) as $chunk) {
                    $output .= "INSERT INTO `$tableName` VALUES\n";
                    $values = [];
                    foreach ($chunk as $row) {
                        $rowArr = (array) $row;
                        $escaped = array_map(function($v) {
                            if ($v === null) return 'NULL';
                            return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string)$v) . "'";
                        }, array_values($rowArr));
                        $values[] = '(' . implode(',', $escaped) . ')';
                    }
                    $output .= implode(",\n", $values) . ";\n\n";
                }
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        file_put_contents($sqlPath, $output);

        // Zip it
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($sqlPath, $sqlFile);
        $zip->close();

        $this->writeLog('BACKUP', 'maintenance', 'Backup database: ' . $zipFile);

        return response()->download($zipPath, $zipFile)->deleteFileAfterSend(false);
    }
}
