<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Source;
use App\Enums\NewsSource;

class SourceAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:source
                            {action : The action to perform (list, activate, deactivate, delete)}
                            {identifier? : The source identifier (for activate/deactivate/delete actions)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage news sources (list, activate, deactivate, delete)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listSources();
                break;
            case 'activate':
                $this->activateSource();
                break;
            case 'deactivate':
                $this->deactivateSource();
                break;
            case 'delete':
                $this->deleteSource();
                break;
            default:
                $this->error("Unknown action: {$action}");
                return 1;
        }

        return 0;
    }

    /**
     * List all sources with their status
     */
    private function listSources()
    {
        $sources = Source::withTrashed()->get();

        if ($sources->isEmpty()) {
            $this->info('No sources found.');
            return;
        }

        $rows = [];
        foreach ($sources as $source) {
            $status = 'Active';
            if ($source->trashed()) {
                $status = 'Deleted';
            } elseif (!$source->is_active) {
                $status = 'Inactive';
            }

            $rows[] = [
                $source->identifier,
                $source->name,
                $status,
                $source->deleted_at ? $source->deleted_at->format('Y-m-d H:i:s') : '',
            ];
        }

        $this->table(['Identifier', 'Name', 'Status', 'Deleted At'], $rows);
    }

    /**
     * Activate a source
     */
    private function activateSource()
    {
        $identifier = $this->argument('identifier');
        if (!$identifier) {
            $this->error('Please provide a source identifier.');
            return;
        }

        // Validate identifier against enum
        if (!$this->isValidSourceIdentifier($identifier)) {
            $this->error("Invalid source identifier: '{$identifier}'");
            $this->showValidSources();
            return;
        }

        $source = Source::withTrashed()->where('identifier', $identifier)->first();
        if (!$source) {
            $this->error("Source '{$identifier}' not found in database.");
            return;
        }

        // Restore if soft deleted
        if ($source->trashed()) {
            $source->restore();
        }

        $source->is_active = true;
        $source->save();

        $this->info("Source '{$identifier}' has been activated.");
    }

    /**
     * Deactivate a source
     */
    private function deactivateSource()
    {
        $identifier = $this->argument('identifier');
        if (!$identifier) {
            $this->error('Please provide a source identifier.');
            return;
        }

        // Validate identifier against enum
        if (!$this->isValidSourceIdentifier($identifier)) {
            $this->error("Invalid source identifier: '{$identifier}'");
            $this->showValidSources();
            return;
        }

        $source = Source::where('identifier', $identifier)->first();
        if (!$source) {
            $this->error("Source '{$identifier}' not found in database.");
            return;
        }

        $source->is_active = false;
        $source->save();

        $this->info("Source '{$identifier}' has been deactivated.");
    }

    /**
     * Soft delete a source
     */
    private function deleteSource()
    {
        $identifier = $this->argument('identifier');
        if (!$identifier) {
            $this->error('Please provide a source identifier.');
            return;
        }

        // Validate identifier against enum
        if (!$this->isValidSourceIdentifier($identifier)) {
            $this->error("Invalid source identifier: '{$identifier}'");
            $this->showValidSources();
            return;
        }

        $source = Source::where('identifier', $identifier)->first();
        if (!$source) {
            $this->error("Source '{$identifier}' not found in database.");
            return;
        }

        if (!$this->confirm("Are you sure you want to delete source '{$identifier}'?")) {
            $this->info('Operation cancelled.');
            return;
        }

        $source->delete();

        $this->info("Source '{$identifier}' has been deleted.");
    }

    /**
     * Check if the identifier is a valid news source
     */
    private function isValidSourceIdentifier(string $identifier): bool
    {
        foreach (NewsSource::cases() as $case) {
            if ($case->value === $identifier) {
                return true;
            }
        }
        return false;
    }

    /**
     * Show valid sources
     */
    private function showValidSources(): void
    {
        $validSources = array_map(fn($case) => $case->value, NewsSource::cases());
        $this->line("Valid sources: " . implode(', ', $validSources));
    }
}
