<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Biodata;
use App\Models\MemberStatusLog;

class TestApprovalLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:approval-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test approval logging functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Approval Logging...');
        
        try {
            // Check existing status logs
            $totalLogs = MemberStatusLog::count();
            $this->info('✓ Total status logs: ' . $totalLogs);
            
            // Check logs by action
            $pendingLogs = MemberStatusLog::where('action', 'pending')->count();
            $approvedLogs = MemberStatusLog::where('action', 'approved')->count();
            $rejectedLogs = MemberStatusLog::where('action', 'rejected')->count();
            
            $this->info('✓ Pending logs: ' . $pendingLogs);
            $this->info('✓ Approved logs: ' . $approvedLogs);
            $this->info('✓ Rejected logs: ' . $rejectedLogs);
            
            // Check users with biodata
            $usersWithBiodata = User::whereHas('biodata')->count();
            $this->info('✓ Users with biodata: ' . $usersWithBiodata);
            
            // Check active vs inactive biodatas
            $activeBiodatas = Biodata::where('is_active', true)->count();
            $inactiveBiodatas = Biodata::where('is_active', false)->count();
            
            $this->info('✓ Active biodatas: ' . $activeBiodatas);
            $this->info('✓ Inactive biodatas: ' . $inactiveBiodatas);
            
            // Check if each biodata has corresponding logs
            $biodatas = Biodata::with('user')->get();
            $this->info('✓ Checking biodata logs...');
            
            foreach ($biodatas as $biodata) {
                $logs = MemberStatusLog::where('biodata_id', $biodata->id)->get();
                $this->line('  - ' . $biodata->nama_lengkap . ': ' . $logs->count() . ' logs');
                
                foreach ($logs as $log) {
                    $this->line('    * ' . $log->action . ' -> ' . $log->status . ' (' . $log->created_at->format('Y-m-d H:i') . ')');
                }
            }
            
            $this->info('✓ All tests completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            return 1;
        }
    }
}
