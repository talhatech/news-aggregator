<?php

namespace Database\Seeders;

use App\Enums\NewsSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'id' => Str::uuid()->toString(),
                'identifier' => NewsSource::NEWSAPI->value,
                'name' => 'NewsAPI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'identifier' => NewsSource::GUARDIAN->value,
                'name' => 'The Guardian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'identifier' => NewsSource::NYTIMES->value,
                'name' => 'The New York Times',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('sources')->insert($sources);
    }
}
