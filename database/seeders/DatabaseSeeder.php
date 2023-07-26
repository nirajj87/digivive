<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            AdsManagementSeeder::class,
            AnalyticsManagementSeeder::class,
            AssetTypeSeeder::class,
            AwsStorageSettingSeeder::class,
            BitframeSeeder::class,
            BroadcasterSeeder::class,
            ContentAdvisorieSeeder::class,
            ContentAvailabilitySeeder::class,
            CountriesSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            DateFormatsSeeder::class,
            EmailTemplateSeeder::class,
            GenreSeeder::class,
            GroupAndPackageManagementSeeder::class,
            LanguageSeeder::class,
            ModuleSeeder::class,
            PaymentModesSeeder::class,
            PlatformsSeeder::class,
            S3SettingSeeder::class,
            SearchSeeder::class,
            StreamingSettingSeeder::class,
            TimezonesSeeder::class,
            ViewerRatingSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
