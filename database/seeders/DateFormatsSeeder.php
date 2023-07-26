<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateFormatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fotmats = [
            ['title'=>'23:10:10', 'format_key'=> 'H:i:s', 'flag'=> '1', 'order'=>'10','status'=>1],
            ['title'=>'10:59 AM', 'format_key'=> 'h:i A', 'flag'=> '1', 'order'=>'20','status'=>1],

            // full year
            ['title'=>'2022-01-01 (YYYY-MM-DD)', 'format_key'=> 'Y-m-d', 'flag'=> '0', 'order'=>'10','status'=>1],
            ['title'=>'2022.01.01 (YYYY.MM.DD)', 'format_key'=> 'Y.m.d', 'flag'=> '0', 'order'=>'20','status'=>1],
            ['title'=>'2022/01/01 (YYYY/MM/DD)', 'format_key'=> 'Y/m/d', 'flag'=> '0', 'order'=>'30','status'=>1],

            ['title'=>'01-12-2022 (DD-MM-YYYY)', 'format_key'=> 'd-m-Y', 'flag'=> '0', 'order'=>'40','status'=>1],
            ['title'=>'01.12.2022 (DD.MM.YYYY)', 'format_key'=> 'd.m.Y', 'flag'=> '0', 'order'=>'50','status'=>1],
            ['title'=>'01/12/2022 (DD/MM/YYYY)', 'format_key'=> 'd/m/Y', 'flag'=> '0', 'order'=>'60','status'=>1],
            
            ['title'=>'22-March-2022', 'format_key'=> 'd-F-Y', 'flag'=> '0', 'order'=>'70','status'=>1],
            ['title'=>'22 March 2022', 'format_key'=> 'd F Y', 'flag'=> '0', 'order'=>'80','status'=>1],
            ['title'=>'22-Mar-2022', 'format_key'=> 'd-M-Y', 'flag'=> '0', 'order'=>'90','status'=>1],
            ['title'=>'22 Mar 2022', 'format_key'=> 'd M Y', 'flag'=> '0', 'order'=>'100','status'=>1],

            ['title'=>'Monday, 12 March, 2022', 'format_key'=> 'l, d F, Y', 'flag'=> '0', 'order'=>'110','status'=>1],
            ['title'=>'Monday, 12 Mar, 2022', 'format_key'=> 'l, d M, Y', 'flag'=> '0', 'order'=>'120','status'=>1],
            ['title'=>'Mon, 12 March, 2022', 'format_key'=> 'D, d F, Y', 'flag'=> '0', 'order'=>'130','status'=>1],
            ['title'=>'Mon, 12 Mar, 2022', 'format_key'=> 'D, d M, Y', 'flag'=> '0', 'order'=>'140','status'=>1],

            // 2 digit years
            ['title'=>'99-01-01 (YY-MM-DD)', 'format_key'=> 'y-m-d', 'flag'=> '0', 'order'=>'150','status'=>1],
            ['title'=>'99.01.01 (YY.MM.DD)', 'format_key'=> 'y.m.d', 'flag'=> '0', 'order'=>'160','status'=>1],
            ['title'=>'99/01/01 (YY/MM/DD)', 'format_key'=> 'y/m/d', 'flag'=> '0', 'order'=>'170','status'=>1],

            ['title'=>'01-12-99 (DD-MM-YY)', 'format_key'=> 'd-m-y', 'flag'=> '0', 'order'=>'180','status'=>1],
            ['title'=>'01.12.99 (DD.MM.YY)', 'format_key'=> 'd.m.y', 'flag'=> '0', 'order'=>'190','status'=>1],
            ['title'=>'01/12/99 (DD/MM/YY)', 'format_key'=> 'd/m/y', 'flag'=> '0', 'order'=>'200','status'=>1],
            
            ['title'=>'22-March-99', 'format_key'=> 'd-F-y', 'flag'=> '0', 'order'=>'210','status'=>1],
            ['title'=>'22 March 99', 'format_key'=> 'd F y', 'flag'=> '0', 'order'=>'220','status'=>1],
            ['title'=>'22-Mar-99', 'format_key'=> 'd-M-y', 'flag'=> '0', 'order'=>'230','status'=>1],
            ['title'=>'22 Mar 99', 'format_key'=> 'd M y', 'flag'=> '0', 'order'=>'240','status'=>1],

            ['title'=>'Monday, 12 March, 99', 'format_key'=> 'l, d F, y', 'flag'=> '0', 'order'=>'250','status'=>1],
            ['title'=>'Monday, 12 Mar, 99', 'format_key'=> 'l, d M, y', 'flag'=> '0', 'order'=>'260','status'=>1],
            ['title'=>'Mon, 12 March, 99', 'format_key'=> 'D, d F, y', 'flag'=> '0', 'order'=>'270','status'=>1],
            ['title'=>'Mon, 12 Mar, 99', 'format_key'=> 'D, d M, y', 'flag'=> '0', 'order'=>'280','status'=>1],
        ];

        foreach($fotmats as $row){
            DB::table('date_formats')->insert([
                'title' => $row['title'],
                'format_key' => $row['format_key'],
                'flag' => $row['flag'],
                'order' => $row['order'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
