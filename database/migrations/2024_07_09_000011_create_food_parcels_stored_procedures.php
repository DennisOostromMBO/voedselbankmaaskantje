<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Create Food Parcels Stored Procedures Migration
 * 
 * This migration automatically creates all stored procedures required
 * for the food parcels CRUD system with complex joins and validations.
 * 
 * @author Wassim
 * @version 1.0
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        try {
            // Get the SQL file path
            $sqlFilePath = database_path('sp/Wassim/food_parcels_stored_procedures.sql');
            
            // Check if the SQL file exists
            if (!File::exists($sqlFilePath)) {
                throw new \Exception("Stored procedures file not found at: {$sqlFilePath}");
            }

            // Read the SQL file content
            $sqlContent = File::get($sqlFilePath);
            
            // Log the operation
            Log::info('Creating food parcels stored procedures from file: ' . $sqlFilePath);
            
            // Execute the SQL content
            // Note: We need to split by delimiter and execute each statement separately
            $statements = $this->parseSqlStatements($sqlContent);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && $statement !== '//') {
                    DB::unprepared($statement);
                }
            }
            
            Log::info('Successfully created food parcels stored procedures');
            
        } catch (\Exception $e) {
            Log::error('Failed to create food parcels stored procedures: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        try {
            // Drop all stored procedures
            $procedures = [
                'sp_get_food_parcels_with_details',
                'sp_get_food_parcel_by_id',
                'sp_create_food_parcel',
                'sp_update_food_parcel',
                'sp_delete_food_parcel',
                'sp_get_food_parcel_stats'
            ];

            foreach ($procedures as $procedure) {
                DB::unprepared("DROP PROCEDURE IF EXISTS {$procedure}");
                Log::info("Dropped stored procedure: {$procedure}");
            }
            
            Log::info('Successfully dropped all food parcels stored procedures');
            
        } catch (\Exception $e) {
            Log::error('Failed to drop food parcels stored procedures: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Parse SQL statements from file content.
     * 
     * @param string $sqlContent
     * @return array
     */
    private function parseSqlStatements(string $sqlContent): array
    {
        // Remove comments that start with --
        $sqlContent = preg_replace('/^--.*$/m', '', $sqlContent);
        
        // Split by delimiter statements
        $parts = explode('DELIMITER', $sqlContent);
        $statements = [];
        
        if (count($parts) > 1) {
            // Handle delimiter changes
            for ($i = 1; $i < count($parts); $i++) {
                $part = trim($parts[$i]);
                
                if (strpos($part, '//') === 0) {
                    // This part starts with // delimiter
                    $content = substr($part, 2); // Remove the // at the start
                    
                    // Split by // to get individual procedures
                    $procedures = explode('//', $content);
                    
                    foreach ($procedures as $procedure) {
                        $procedure = trim($procedure);
                        if (!empty($procedure) && !preg_match('/^\s*DELIMITER\s*;?\s*$/i', $procedure)) {
                            $statements[] = $procedure;
                        }
                    }
                } else if (strpos($part, ';') === 0) {
                    // This part starts with ; delimiter (back to normal)
                    // Just add any remaining statements
                    $remaining = substr($part, 1);
                    if (!empty(trim($remaining))) {
                        $statements[] = trim($remaining);
                    }
                }
            }
        } else {
            // No delimiter changes, split by semicolon
            $statements = explode(';', $sqlContent);
        }
        
        return array_filter($statements, function($stmt) {
            $stmt = trim($stmt);
            return !empty($stmt) && 
                   !preg_match('/^\s*USE\s+/i', $stmt) && 
                   !preg_match('/^\s*DELIMITER\s*/i', $stmt) &&
                   !preg_match('/^\s*--/i', $stmt);
        });
    }
};
