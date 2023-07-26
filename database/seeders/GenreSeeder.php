<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['title' => 'Action', 'slug' => 'action','user_id'=>1, 'status' => '1'],
            ['title' => 'Adventure', 'slug' => 'adventure','user_id'=>1, 'status' => '1'],
            ['title' => 'Animation', 'slug' => 'animation','user_id'=>1, 'status' => '1'],
            ['title' => 'Art', 'slug' => 'art','user_id'=>1, 'status' => '1'],
            ['title' => 'Astrology', 'slug' => 'astrology,','user_id'=>1, 'status' => '1'],
            ['title' => 'Audio Channel', 'slug' => 'audio_channel','user_id'=>1, 'status' => '1'],
            ['title' => 'Awareness', 'slug' => 'awareness','user_id'=>1, 'status' => '1'],
            ['title' => 'Break Free', 'slug' => 'break_free','user_id'=>1, 'status' => '1'],
            ['title' => 'Comedy', 'slug' => 'comedy','user_id'=>1, 'status' => '1'],
            ['title' => 'Cookery', 'slug' => 'cookery','user_id'=>1, 'status' => '1'],
            ['title' => 'Crime', 'slug' => 'crime','user_id'=>1, 'status' => '1'],
            ['title' => 'Devotional', 'slug' => 'devotional','user_id'=>1, 'status' => '1'],
            ['title' => 'Documentary', 'slug' => 'documentary','user_id'=>1, 'status' => '1'],
            ['title' => 'Drama', 'slug' => 'drama','user_id'=>1, 'status' => '1'],
            ['title' => 'Education', 'slug' => 'education','user_id'=>1, 'status' => '1'],
            ['title' => 'Edutainment', 'slug' => 'edutainment','user_id'=>1, 'status' => '1'],
            ['title' => 'Encyclo', 'slug' => 'encyclo','user_id'=>1, 'status' => '1'],
            ['title' => 'Entertainment', 'slug' => 'entertainment','user_id'=>1, 'status' => '1'],
            ['title' => 'Epic', 'slug' => 'epic','user_id'=>1, 'status' => '1'],
            ['title' => 'Expository', 'slug' => 'expository','user_id'=>1, 'status' => '1'],
            ['title' => 'Food & Cookery', 'slug' => 'food_cookery','user_id'=>1, 'status' => '1'],
            ['title' => 'Fantasy', 'slug' => 'fantasy','user_id'=>1, 'status' => '1'],
            ['title' => 'Fashion Show', 'slug' => 'fashion_show','user_id'=>1, 'status' => '1'],
            ['title' => 'Festivals', 'slug' => 'festivals','user_id'=>1, 'status' => '1'],
            ['title' => 'Fiction', 'slug' => 'fiction','user_id'=>1, 'status' => '1'],
            ['title' => 'Fun', 'slug' => 'fun','user_id'=>1, 'status' => '1'],
            ['title' => 'General Entertainment', 'slug' => 'general_entertainment','user_id'=>1, 'status' => '1'],
            ['title' => 'General News', 'slug' => 'general_news','user_id'=>1, 'status' => '1'],
            ['title' => 'Gossips', 'slug' => 'gossips','user_id'=>1, 'status' => '1'],
            ['title' => 'Gyan', 'slug' => 'gyan','user_id'=>1, 'status' => '1'],
            ['title' => 'Hashtag', 'slug' => 'hashtag','user_id'=>1, 'status' => '1'],
            ['title' => 'Health & Fitness', 'slug' => 'health_fitness','user_id'=>1, 'status' => '1'],
            ['title' => 'Horror', 'slug' => 'horror','user_id'=>1, 'status' => '1'],
            ['title' => 'Infotainment', 'slug' => 'infotainment','user_id'=>1, 'status' => '1'],
            ['title' => 'Infotainment & Lifestyle', 'slug' => 'infotainment_lifestyle','user_id'=>1, 'status' => '1'],
            ['title' => 'Interview Talk Show', 'slug' => 'interview_talk_show','user_id'=>1, 'status' => '1'],
            ['title' => 'Kids', 'slug' => 'kids','user_id'=>1, 'status' => '1'],
            ['title' => 'Livgnews ', 'slug' => 'livgnews','user_id'=>1, 'status' => '1'],
            ['title' => 'Lifestyle', 'slug' => 'lifestyle','user_id'=>1, 'status' => '1'],
            ['title' => 'Mantra', 'slug' => 'mantra','user_id'=>1, 'status' => '1'],
            ['title' => 'Modem', 'slug' => 'modem','user_id'=>1, 'status' => '1'],
            ['title' => 'Movie', 'slug' => 'movie','user_id'=>1, 'status' => '1'],
            ['title' => 'Music', 'slug' => 'music','user_id'=>1, 'status' => '1'],
            ['title' => 'Mystery', 'slug' => 'mystery','user_id'=>1, 'status' => '1'],
            ['title' => 'Mythological', 'slug' => 'mythological','user_id'=>1, 'status' => '1'],
            ['title' => 'News', 'slug' => 'news','user_id'=>1, 'status' => '1'],
            ['title' => 'News Special', 'slug' => 'news_special','user_id'=>1, 'status' => '1'],
            ['title' => 'Obituary', 'slug' => 'obituary','user_id'=>1, 'status' => '1'],
            ['title' => 'Popular Movies', 'slug' => 'popular_movies','user_id'=>1, 'status' => '1'],
            ['title' => 'Positive', 'slug' => 'positive','user_id'=>1, 'status' => '1'],
            ['title' => 'Prayer', 'slug' => 'prayer','user_id'=>1, 'status' => '1'],
            ['title' => 'Puja', 'slug' => 'puja','user_id'=>1, 'status' => '1'],
            ['title' => 'RRR', 'slug' => 'rrr','user_id'=>1, 'status' => '1'],
            ['title' => 'Reality', 'slug' => 'reality','user_id'=>1, 'status' => '1'],
            ['title' => 'Regional News', 'slug' => 'regional_news','user_id'=>1, 'status' => '1'],
            ['title' => 'Religious', 'slug' => 'religious','user_id'=>1, 'status' => '1'],
            ['title' => 'Revenue', 'slug' => 'revenue','user_id'=>1, 'status' => '1'],
            ['title' => 'Reviews', 'slug' => 'reviews','user_id'=>1, 'status' => '1'],
            ['title' => 'Rhymes', 'slug' => 'rhymes','user_id'=>1, 'status' => '1'],
            ['title' => 'Romance', 'slug' => 'romance','user_id'=>1, 'status' => '1'],
            ['title' => 'Romantic', 'slug' => 'romantic','user_id'=>1, 'status' => '1'],
            ['title' => 'Sci-Fi', 'slug' => 'sci_fi','user_id'=>1, 'status' => '1'],
            ['title' => 'Science', 'slug' => 'science','user_id'=>1, 'status' => '1'],
            ['title' => 'Shows', 'slug' => 'shows','user_id'=>1, 'status' => '1'],
            ['title' => 'Spiritual', 'slug' => 'spiritual','user_id'=>1, 'status' => '1'],
            ['title' => 'Sport', 'slug' => 'sport','user_id'=>1, 'status' => '1'],
            ['title' => 'Sports', 'slug' => 'sports','user_id'=>1, 'status' => '1'],
            ['title' => 'Summer', 'slug' => 'summer','user_id'=>1, 'status' => '1'],
            ['title' => 'Suspense', 'slug' => 'suspense','user_id'=>1, 'status' => '1'],
            ['title' => 'Thriller', 'slug' => 'thriller','user_id'=>1, 'status' => '1'],
            ['title' => 'Vision', 'slug' => 'vision','user_id'=>1, 'status' => '1'],
            ['title' => 'Yoga', 'slug' => 'yoga','user_id'=>1, 'status' => '1'],
            ['title' => 'Anime', 'slug' => 'anime','user_id'=>1, 'status' => '1'],
            ['title' => 'Automate', 'slug' => 'automate','user_id'=>1, 'status' => '1'],
            ['title' => 'Punjabi Regional', 'slug' => 'punjabi_regional','user_id'=>1, 'status' => '1']
        ];

        foreach ($genres as $row) {
            DB::table('genres')->insert([
                'title' => $row['title'],
                'slug' => $row['slug'],
                'user_id' => $row['user_id'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
