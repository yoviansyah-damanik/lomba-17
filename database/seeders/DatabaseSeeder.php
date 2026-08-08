<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\Criterion;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'username' => 'admin',
        ]);

        $judges = collect(['Juri Satu', 'Juri Dua', 'Juri Tiga'])->map(
            fn (string $name, int $i) => User::factory()->create([
                'name' => $name,
                'username' => 'juri'.($i + 1),
            ])
        );

        [$osis, $pramuka] = collect(['Deville OSIS', 'Deville Pramuka'])->map(function (string $name) use ($judges) {
            $competition = Competition::create([
                'name' => $name,
                'start_time' => now()->subHour(),
                'end_time' => now()->addHours(6),
            ]);

            collect(['Kerapian', 'Kekompakan', 'Kreativitas'])
                ->map(fn (string $criterionName) => Criterion::create([
                    'competition_id' => $competition->id,
                    'name' => $criterionName,
                    'description' => null,
                    'school_types' => ['SD', 'SMP', 'SMA'],
                ]))
                ->each(function (Criterion $criterion) use ($judges) {
                    $rows = [];
                    foreach ($criterion->school_types as $type) {
                        foreach ($judges as $judge) {
                            $rows[] = ['user_id' => $judge->id, 'school_type' => $type];
                        }
                    }
                    $criterion->judges()->newPivotStatement()->insert(array_map(
                        fn (array $row) => $row + ['criterion_id' => $criterion->id, 'created_at' => now(), 'updated_at' => now()],
                        $rows
                    ));
                });

            return $competition;
        })->all();

        // Deville OSIS: didaftarkan via slot massal, identitas belum disinkronkan.
        foreach (['SD' => 10, 'SMP' => 10, 'SMA' => 10] as $schoolType => $count) {
            for ($seq = 1; $seq <= $count; $seq++) {
                Registration::create([
                    'competition_id' => $osis->id,
                    'school_type' => $schoolType,
                    'npp' => "{$schoolType}-{$seq}",
                    'label' => "{$schoolType} Peserta {$seq}",
                ]);
            }
        }

        // Deville Pramuka: identitas sudah diketahui saat registrasi (alur lama).
        $prefixes = ['SD' => ['SDN', 1000], 'SMP' => ['SMPN', 2000], 'SMA' => ['SMAN', 3000]];

        foreach ($prefixes as $schoolType => [$label, $nppBase]) {
            for ($i = 1; $i <= 10; $i++) {
                $participant = Participant::create([
                    'school_name' => "{$label} {$i} Merdeka",
                    'school_type' => $schoolType,
                ]);

                Registration::create([
                    'competition_id' => $pramuka->id,
                    'participant_id' => $participant->id,
                    'school_type' => $schoolType,
                    'npp' => (string) ($nppBase + $i),
                ]);
            }
        }
    }
}
