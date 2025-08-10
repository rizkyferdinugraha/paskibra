<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialStatusLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::with('biodata')->get();
        
        foreach ($users as $user) {
            if ($user->biodata) {
                // Create initial pending log
                \App\Models\MemberStatusLog::create([
                    'user_id' => $user->id,
                    'biodata_id' => $user->biodata->id,
                    'action' => 'pending',
                    'status' => 'pending',
                    'reason' => 'Pendaftaran disubmit',
                    'admin_name' => null,
                    'admin_id' => null,
                    'created_at' => $user->biodata->created_at,
                ]);
                
                // If active, create approval log
                if ($user->biodata->is_active) {
                    \App\Models\MemberStatusLog::create([
                        'user_id' => $user->id,
                        'biodata_id' => $user->biodata->id,
                        'action' => 'approved',
                        'status' => 'active',
                        'reason' => 'Pendaftaran disetujui oleh admin',
                        'admin_name' => 'System',
                        'admin_id' => 1,
                        'created_at' => $user->biodata->updated_at,
                    ]);
                }
            }
        }
        
        echo "Initial status logs created for " . $users->count() . " users.\n";
    }
}
