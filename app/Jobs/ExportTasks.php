<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Collection|array $records)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (is_array($this->records)) {
            $this->records = Task::query()->whereKey($this->records)->get();
        }

        $this->records->loadMissing(['user', 'project', 'status']);
        Storage::disk('exports'); // Makes sure that disk directory created.
        $filename = 'tasks_export_' . now()->timestamp . '.csv';
        $stream = fopen(storage_path('exports/' . $filename), 'wb');

        fputcsv($stream, ['title', 'description', 'estimation', 'user', 'project', 'status']);

        // @see https://github.com/phpstan/phpstan/issues/5060
        // @phpstan-ignore-next-line
        $this->records->each(function (Task $record) use ($stream): void {
            fputcsv($stream, [
                $record->title,
                $record->description,
                $record->getAttributes()['estimation'],
                $record->user->name,
                $record->project->title,
                $record->status->status
            ]);
        });
    }
}
