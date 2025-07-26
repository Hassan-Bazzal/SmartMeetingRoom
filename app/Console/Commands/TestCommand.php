<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Minute;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\Room;
use App\Models\Attendee;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    $this->info('Testing command execution...');
    $employee = new Employee();
    $employee->name = 'Hassan Bazzal';
    $employee->email = 'hassanbazzal@example.com';
    $employee->password = bcrypt('hassan@12345');
    $employee->role = 'admin';
    $employee->save();
 if ($employee->wasRecentlyCreated) {
        $this->info('Employee created successfully: ' . $employee->name);
    } else {
        $this->error('Failed to create employee.');
    }
    return Command::SUCCESS;
}
}
