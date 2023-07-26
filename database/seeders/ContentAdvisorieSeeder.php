<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentAdvisorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contentAdvisories = [
            ['title' => 'Violence', 'slug' => 'violence', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Sexual Content', 'slug' => 'sexual_content', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Foul Language', 'slug' => 'foul_language', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Drug Use', 'slug' => 'drug_use', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Drug References', 'slug' => 'drug_references', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Sexual References', 'slug' => 'sexual_references', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Battle Scenes', 'slug' => 'battle_scenes', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Mature Content', 'slug' => 'mature_content', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Coarse Language', 'slug' => 'coarse_language', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Language', 'slug' => 'language', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Gore', 'slug' => 'gore', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Horror', 'slug' => 'horror', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Threat', 'slug' => 'threat', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Supernatural Theme', 'slug' => 'supernatural_theme', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Intimate Scene', 'slug' => 'intimate_scene', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Crude Sexual Humor', 'slug' => 'crude_sexual_humor', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Action Violence', 'slug' => 'action_violence', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Some Sexual Content', 'slug' => 'some_sexual_sontent', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Sexual Scenes and Drug Use', 'slug' => 'sexual_scenes_and_srug_use', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Strong Coarse Language', 'slug' => 'strong_coarse_language', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Intense Sequences', 'slug' => 'intense_sequences', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Mature Themes Gambling Content', 'slug' => 'mature_themes_gambling_content', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Scene of Intimacy', 'slug' => 'scene_of_intimacy', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Some Distrubing Scenes', 'slug' => 'some_distrubing_scenes', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Brief Nudity', 'slug' => 'brief_nudity', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Some Disturbing Imagery', 'slug' => 'some_disturbing_imagery', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Some Scary Images', 'slug' => 'some_scary_images', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Supernatural Content', 'slug' => 'supernatural_content', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Some Disturbing Elements', 'slug' => 'some_disturbing_elements', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Suitable for All', 'slug' => 'suitable_for_all', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Nudity', 'slug' => 'nudity', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Sexual Humour', 'slug' => 'sexual_humour', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Crude Humour', 'slug' => 'crude_humour', 'view_order' => 0, 'status' => '1'],
            ['title' => 'Crude Sexual Content', 'slug' => 'crude_sexual_content', 'view_order' => 0, 'status' => '1']
        ];

        foreach ($contentAdvisories as $row) {
            DB::table('content_advisories')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'view_order' => $row['view_order'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
