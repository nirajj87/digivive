<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezone = [
            ['title'=> '(UTC-12:00) International Date Line West', 'timezone'=>'Etc/GMT+12', 'order'=> '10', 'zone_name'=>'Dateline Standard Time'],
            ['title'=> '(UTC-11:00) Coordinated Universal Time-11', 'timezone'=>'Etc/GMT+11', 'order'=> '20', 'zone_name'=>'UTC-11'],
            ['title'=> '(UTC-10:00) Aleutian Islands', 'timezone'=>'America/Adak', 'order'=> '30', 'zone_name'=>'Aleutian Standard Time'],
            ['title'=> '(UTC-10:00) Hawaii', 'timezone'=>'Pacific/Honolulu', 'order'=> '40', 'zone_name'=>'Hawaiian Standard Time'],
            ['title'=> '(UTC-09:30) Marquesas Islands', 'timezone'=>'Pacific/Marquesas', 'order'=> '50', 'zone_name'=>'Marquesas Standard Time'],
            ['title'=> '(UTC-09:00) Alaska', 'timezone'=>'America/Anchorage', 'order'=> '60', 'zone_name'=>'Alaskan Standard Time'],
            ['title'=> '(UTC-09:00) Coordinated Universal Time-09', 'timezone'=>'America/Anchorage', 'order'=> '70', 'zone_name'=>'UTC-09'],
            ['title'=> '(UTC-08:00) Pacific Time (US & Canada)', 'timezone'=>'America/Los_Angeles', 'order'=> '80', 'zone_name'=>'Pacific Standard Time'],
            ['title'=> '(UTC-08:00) Baja California', 'timezone'=>'America/Santa_Isabel', 'order'=> '90', 'zone_name'=>'Pacific Standard Time (Mexico)'],
            ['title'=> '(UTC-08:00) Coordinated Universal Time-08', 'timezone'=>'America/Los_Angeles', 'order'=> '100', 'zone_name'=>'UTC-08'],
            ['title'=> '(UTC-07:00) Arizona', 'timezone'=>'America/Phoenix', 'order'=> '110', 'zone_name'=>'US Mountain Standard Time'],
            ['title'=> '(UTC-07:00) Chihuahua, La Paz, Mazatlan', 'timezone'=>'America/Chihuahua', 'order'=> '120', 'zone_name'=>'Mountain Standard Time (Mexico)'],
            ['title'=> '(UTC-07:00) Mountain Time (US & Canada)', 'timezone'=>'America/Denver', 'order'=> '130', 'zone_name'=>'Mountain Standard Time'],
            ['title'=> '(UTC-06:00) Saskatchewan', 'timezone'=>'America/Regina', 'order'=> '140', 'zone_name'=>'Canada Central Standard Time'],
            ['title'=> '(UTC-06:00) Central America', 'timezone'=>'America/Guatemala', 'order'=> '150', 'zone_name'=>'Central America Standard Time'],
            ['title'=> '(UTC-06:00) Guadalajara, Mexico City, Monterrey', 'timezone'=>'America/Mexico_City', 'order'=> '160', 'zone_name'=>'Central Standard Time (Mexico)'],
            ['title'=> '(UTC-06:00) Easter Island', 'timezone'=>'Pacific/Easter', 'order'=> '170', 'zone_name'=>'Easter Island Standard Time'],
            ['title'=> '(UTC-06:00) Central Time (US & Canada)', 'timezone'=>'America/Chicago', 'order'=> '180', 'zone_name'=>'Central Standard Time'],
            ['title'=> '(UTC-05:00) Haiti', 'timezone'=>'America/Port-au-Prince', 'order'=> '190', 'zone_name'=>'Haiti Standard Time'],
            ['title'=> '(UTC-05:00) Havana', 'timezone'=>'America/Havana', 'order'=> '200', 'zone_name'=>'Cuba Standard Time'],
            ['title'=> '(UTC-05:00) Indiana (East)', 'timezone'=>'America/Indiana/Indianapolis', 'order'=> '210', 'zone_name'=>'US Eastern Standard Time'],
            ['title'=> '(UTC-05:00) Bogota, Lima, Quito, Rio Branco', 'timezone'=>'America/Bogota', 'order'=> '220', 'zone_name'=>'SA Pacific Standard Time'],
            ['title'=> '(UTC-05:00) Chetumal', 'timezone'=>'America/Cancun', 'order'=> '230', 'zone_name'=>'Eastern Standard Time (Mexico)'],
            ['title'=> '(UTC-05:00) Eastern Time (US & Canada)', 'timezone'=>'America/New_York', 'order'=> '240', 'zone_name'=>'Eastern Standard Time'],
            ['title'=> '(UTC-05:00) Turks and Caicos', 'timezone'=>'America/Grand_Turk', 'order'=> '250', 'zone_name'=>'Turks And Caicos Standard Time'],
            ['title'=> '(UTC-04:00) Santiago', 'timezone'=>'America/Santiago', 'order'=> '260', 'zone_name'=>'Pacific SA Standard Time'],
            ['title'=> '(UTC-04:00) Asuncion', 'timezone'=>'America/Asuncion', 'order'=> '270', 'zone_name'=>'Paraguay Standard Time'],
            ['title'=> '(UTC-04:00) Atlantic Time (Canada)', 'timezone'=>'America/Halifax', 'order'=> '280', 'zone_name'=>'Atlantic Standard Time'],
            ['title'=> '(UTC-04:00) Caracas', 'timezone'=>'America/Caracas', 'order'=> '290', 'zone_name'=>'Venezuela Standard Time'],
            ['title'=> '(UTC-04:00) Cuiaba', 'timezone'=>'America/Cuiaba', 'order'=> '300', 'zone_name'=>'Central Brazilian Standard Time'],
            ['title'=> '(UTC-04:00) Georgetown, La Paz, Manaus, San Juan', 'timezone'=>'America/La_Paz', 'order'=> '310', 'zone_name'=>'SA Western Standard Time'],
            ['title'=> '(UTC-03:30) Newfoundland', 'timezone'=>'America/St_Johns', 'order'=> '320', 'zone_name'=>'Newfoundland Standard Time'],
            ['title'=> '(UTC-03:00) Salvador', 'timezone'=>'America/Bahia', 'order'=> '330', 'zone_name'=>'Bahia Standard Time'],
            ['title'=> '(UTC-03:00) Araguaina', 'timezone'=>'America/Araguaina', 'order'=> '340', 'zone_name'=>'Tocantins Standard Time'],
            ['title'=> '(UTC-03:00) Brasilia', 'timezone'=>'America/Sao_Paulo', 'order'=> '350', 'zone_name'=>'E. South America Standard Time'],
            ['title'=> '(UTC-03:00) Cayenne, Fortaleza', 'timezone'=>'America/Cayenne', 'order'=> '360', 'zone_name'=>'SA Eastern Standard Time'],
            ['title'=> '(UTC-03:00) Buenos Aires', 'timezone'=>'America/Argentina/Buenos_Aires', 'order'=> '370', 'zone_name'=>'Argentina Standard Time'],
            ['title'=> '(UTC-03:00) Greenland', 'timezone'=>'America/Godthab', 'order'=> '380', 'zone_name'=>'Greenland Standard Time'],
            ['title'=> '(UTC-03:00) Montevideo', 'timezone'=>'America/Montevideo', 'order'=> '390', 'zone_name'=>'Montevideo Standard Time'],
            ['title'=> '(UTC-03:00) Punta Arenas', 'timezone'=>'America/Punta_Arenas', 'order'=> '400', 'zone_name'=>'Magallanes Standard Time'],
            ['title'=> '(UTC-03:00) Saint Pierre and Miquelon', 'timezone'=>'America/Miquelon', 'order'=> '410', 'zone_name'=>'Saint Pierre Standard Time'],
            ['title'=> '(UTC-02:00) Coordinated Universal Time-02', 'timezone'=>'Etc/GMT+2', 'order'=> '420', 'zone_name'=>'UTC-02'],
            ['title'=> '(UTC-01:00) Azores', 'timezone'=>'Atlantic/Azores', 'order'=> '430', 'zone_name'=>'Azores Standard Time'],
            ['title'=> '(UTC-01:00) Cabo Verde Is.', 'timezone'=>'Atlantic/Cape_Verde', 'order'=> '440', 'zone_name'=>'Cape Verde Standard Time'],
            ['title'=> '(UTC) Coordinated Universal Time', 'timezone'=>'Etc/GMT', 'order'=> '450', 'zone_name'=>'UTC'],
            ['title'=> '(UTC+00:00) Dublin, Edinburgh, Lisbon, London', 'timezone'=>'Europe/London', 'order'=> '460', 'zone_name'=>'GMT Standard Time'],
            ['title'=> '(UTC+00:00) Sao Tome', 'timezone'=>'Africa/Sao_Tome', 'order'=> '470', 'zone_name'=>'Sao Tome Standard Time'],
            ['title'=> '(UTC+00:00) Monrovia, Reykjavik', 'timezone'=>'Atlantic/Reykjavik', 'order'=> '480', 'zone_name'=>'Greenwich Standard Time'],
            ['title'=> '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague', 'timezone'=>'Europe/Budapest', 'order'=> '490', 'zone_name'=>'Central Europe Standard Time'],
            ['title'=> '(UTC+01:00) Casablanca', 'timezone'=>'Africa/Casablanca', 'order'=> '500', 'zone_name'=>'Morocco Standard Time'],
            ['title'=> '(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb', 'timezone'=>'Europe/Warsaw', 'order'=> '510', 'zone_name'=>'Central European Standard Time'],
            ['title'=> '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna', 'timezone'=>'Europe/Berlin', 'order'=> '520', 'zone_name'=>'W. Europe Standard Time'],
            ['title'=> '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris', 'timezone'=>'Europe/Paris', 'order'=> '530', 'zone_name'=>'Romance Standard Time'],
            ['title'=> '(UTC+01:00) West Central Africa', 'timezone'=>'Africa/Lagos', 'order'=> '540', 'zone_name'=>'W. Central Africa Standard Time'],
            ['title'=> '(UTC+02:00) Damascus', 'timezone'=>'Asia/Damascus', 'order'=> '550', 'zone_name'=>'Syria Standard Time'],
            ['title'=> '(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius', 'timezone'=>'Europe/Kiev', 'order'=> '560', 'zone_name'=>'FLE Standard Time'],
            ['title'=> '(UTC+02:00) Jerusalem', 'timezone'=>'Asia/Jerusalem', 'order'=> '570', 'zone_name'=>'Israel Standard Time'],
            ['title'=> '(UTC+02:00) Kaliningrad', 'timezone'=>'Europe/Kaliningrad', 'order'=> '580', 'zone_name'=>'Kaliningrad Standard Time'],
            ['title'=> '(UTC+02:00) Khartoum', 'timezone'=>'Africa/Khartoum', 'order'=> '590', 'zone_name'=>'Sudan Standard Time'],
            ['title'=> '(UTC+02:00) Tripoli', 'timezone'=>'Africa/Tripoli', 'order'=> '600', 'zone_name'=>'Libya Standard Time'],
            ['title'=> '(UTC+02:00) Windhoek', 'timezone'=>'Africa/Windhoek', 'order'=> '610', 'zone_name'=>'Namibia Standard Time'],
            ['title'=> '(UTC+02:00) Amman', 'timezone'=>'Asia/Amman', 'order'=> '620', 'zone_name'=>'Jordan Standard Time'],
            ['title'=> '(UTC+02:00) Athens, Bucharest', 'timezone'=>'Europe/Istanbul', 'order'=> '630', 'zone_name'=>'GTB Standard Time'],
            ['title'=> '(UTC+02:00) Beirut', 'timezone'=>'Asia/Beirut', 'order'=> '640', 'zone_name'=>'Middle East Standard Time'],
            ['title'=> '(UTC+02:00) Cairo', 'timezone'=>'Africa/Cairo', 'order'=> '650', 'zone_name'=>'Egypt Standard Time'],
            ['title'=> '(UTC+02:00) Chisinau', 'timezone'=>'Europe/Chisinau', 'order'=> '660', 'zone_name'=>'E. Europe Standard Time'],
            ['title'=> '(UTC+02:00) Gaza, Hebron', 'timezone'=>'Asia/Hebron', 'order'=> '670', 'zone_name'=>'West Bank Standard Time'],
            ['title'=> '(UTC+02:00) Harare, Pretoria', 'timezone'=>'Africa/Johannesburg', 'order'=> '680', 'zone_name'=>'South Africa Standard Time'],
            ['title'=> '(UTC+03:00) Baghdad', 'timezone'=>'Asia/Baghdad', 'order'=> '690', 'zone_name'=>'Arabic Standard Time'],
            ['title'=> '(UTC+03:00) Istanbul', 'timezone'=>'Europe/Istanbul', 'order'=> '700', 'zone_name'=>'Turkey Standard Time'],
            ['title'=> '(UTC+03:00) Moscow, St. Petersburg', 'timezone'=>'Europe/Moscow', 'order'=> '710', 'zone_name'=>'Russian Standard Time'],
            ['title'=> '(UTC+03:00) Nairobi', 'timezone'=>'Africa/Nairobi', 'order'=> '720', 'zone_name'=>'E. Africa Standard Time'],
            ['title'=> '(UTC+03:00) Kuwait, Riyadh', 'timezone'=>'Asia/Riyadh', 'order'=> '730', 'zone_name'=>'Arab Standard Time'],
            ['title'=> '(UTC+03:00) Minsk', 'timezone'=>'Europe/Minsk', 'order'=> '740', 'zone_name'=>'Belarus Standard Time'],
            ['title'=> '(UTC+03:30) Tehran', 'timezone'=>'Asia/Tehran', 'order'=> '750', 'zone_name'=>'Iran Standard Time'],
            ['title'=> '(UTC+04:00) Yerevan', 'timezone'=>'Asia/Yerevan', 'order'=> '760', 'zone_name'=>'Caucasus Standard Time'],
            ['title'=> '(UTC+04:00) Baku', 'timezone'=>'Asia/Baku', 'order'=> '770', 'zone_name'=>'Azerbaijan Standard Time'],
            ['title'=> '(UTC+04:00) Izhevsk, Samara', 'timezone'=>'Europe/Samara', 'order'=> '780', 'zone_name'=>'Russia Time Zone 3'],
            ['title'=> '(UTC+04:00) Port Louis', 'timezone'=>'Indian/Mauritius', 'order'=> '790', 'zone_name'=>'Mauritius Standard Time'],
            ['title'=> '(UTC+04:00) Saratov', 'timezone'=>'Europe/Saratov', 'order'=> '800', 'zone_name'=>'Saratov Standard Time'],
            ['title'=> '(UTC+04:00) Abu Dhabi, Muscat', 'timezone'=>'Asia/Dubai', 'order'=> '810', 'zone_name'=>'Arabian Standard Time'],
            ['title'=> '(UTC+04:00) Astrakhan, Ulyanovsk', 'timezone'=>'Europe/Astrakhan', 'order'=> '820', 'zone_name'=>'Astrakhan Standard Time'],
            ['title'=> '(UTC+04:00) Tbilisi', 'timezone'=>'Asia/Tbilisi', 'order'=> '830', 'zone_name'=>'Georgian Standard Time'],
            ['title'=> '(UTC+04:00) Volgograd', 'timezone'=>'Europe/Volgograd', 'order'=> '840', 'zone_name'=>'Volgograd Standard Time'],
            ['title'=> '(UTC+04:30) Kabul', 'timezone'=>'Asia/Kabul', 'order'=> '850', 'zone_name'=>'Afghanistan Standard Time'],
            ['title'=> '(UTC+05:00) Ashgabat, Tashkent', 'timezone'=>'Asia/Tashkent', 'order'=> '860', 'zone_name'=>'West Asia Standard Time'],
            ['title'=> '(UTC+05:00) Ekaterinburg', 'timezone'=>'Asia/Yekaterinburg', 'order'=> '870', 'zone_name'=>'Ekaterinburg Standard Time'],
            ['title'=> '(UTC+05:00) Islamabad, Karachi', 'timezone'=>'Asia/Karachi', 'order'=> '880', 'zone_name'=>'Pakistan Standard Time'],
            ['title'=> '(UTC+05:00) Qyzylorda', 'timezone'=>'Asia/Qyzylorda', 'order'=> '890', 'zone_name'=>'Qyzylorda Standard Time'],
            ['title'=> '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'timezone'=>'Asia/Kolkata', 'order'=> '900', 'zone_name'=>'India Standard Time'],
            ['title'=> '(UTC+05:30) Sri Jayawardenepura', 'timezone'=>'Asia/Colombo', 'order'=> '910', 'zone_name'=>'Sri Lanka Standard Time'],
            ['title'=> '(UTC+05:45) Kathmandu', 'timezone'=>'Asia/Kathmandu', 'order'=> '920', 'zone_name'=>'Nepal Standard Time'],
            ['title'=> '(UTC+06:00) Astana', 'timezone'=>'Asia/Almaty', 'order'=> '930', 'zone_name'=>'Central Asia Standard Time'],
            ['title'=> '(UTC+06:00) Dhaka', 'timezone'=>'Asia/Dhaka', 'order'=> '940', 'zone_name'=>'Bangladesh Standard Time'],
            ['title'=> '(UTC+06:00) Omsk', 'timezone'=>'Asia/Omsk', 'order'=> '950', 'zone_name'=>'Omsk Standard Time'],
            ['title'=> '(UTC+06:30) Yangon (Rangoon)', 'timezone'=>'Asia/Yangon', 'order'=> '960', 'zone_name'=>'Myanmar Standard Time'],
            ['title'=> '(UTC+07:00) Bangkok, Hanoi, Jakarta', 'timezone'=>'Asia/Bangkok', 'order'=> '970', 'zone_name'=>'SE Asia Standard Time'],
            ['title'=> '(UTC+07:00) Krasnoyarsk', 'timezone'=>'Asia/Krasnoyarsk', 'order'=> '980', 'zone_name'=>'North Asia Standard Time'],
            ['title'=> '(UTC+07:00) Novosibirsk', 'timezone'=>'Asia/Novosibirsk', 'order'=> '990', 'zone_name'=>'N. Central Asia Standard Time'],
            ['title'=> '(UTC+07:00) Tomsk', 'timezone'=>'Asia/Tomsk', 'order'=> '1000', 'zone_name'=>'Tomsk Standard Time'],
            ['title'=> '(UTC+07:00) Barnaul, Gorno-Altaysk', 'timezone'=>'Asia/Barnaul', 'order'=> '1010', 'zone_name'=>'Altai Standard Time'],
            ['title'=> '(UTC+07:00) Hovd', 'timezone'=>'Asia/Hovd', 'order'=> '1020', 'zone_name'=>'W. Mongolia Standard Time'],
            ['title'=> '(UTC+08:00) Ulaanbaatar', 'timezone'=>'Asia/Ulaanbaatar', 'order'=> '1030', 'zone_name'=>'Ulaanbaatar Standard Time'],
            ['title'=> '(UTC+08:00) Taipei', 'timezone'=>'Asia/Taipei', 'order'=> '1040', 'zone_name'=>'Taipei Standard Time'],
            ['title'=> '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi', 'timezone'=>'Asia/Shanghai', 'order'=> '1050', 'zone_name'=>'China Standard Time'],
            ['title'=> '(UTC+08:00) Irkutsk', 'timezone'=>'Asia/Irkutsk', 'order'=> '1060', 'zone_name'=>'North Asia East Standard Time'],
            ['title'=> '(UTC+08:00) Kuala Lumpur, Singapore', 'timezone'=>'Asia/Singapore', 'order'=> '1070', 'zone_name'=>'Singapore Standard Time'],
            ['title'=> '(UTC+08:00) Perth', 'timezone'=>'Australia/Perth', 'order'=> '1080', 'zone_name'=>'W. Australia Standard Time'],
            ['title'=> '(UTC+08:45) Eucla', 'timezone'=>'Australia/Eucla', 'order'=> '1090', 'zone_name'=>'Aus Central W. Standard Time'],
            ['title'=> '(UTC+09:00) Chita', 'timezone'=>'Asia/Chita', 'order'=> '1100', 'zone_name'=>'Transbaikal Standard Time'],
            ['title'=> '(UTC+09:00) Osaka, Sapporo, Tokyo', 'timezone'=>'Asia/Tokyo', 'order'=> '1110', 'zone_name'=>'Tokyo Standard Time'],
            ['title'=> '(UTC+09:00) Pyongyang', 'timezone'=>'Asia/Pyongyang', 'order'=> '1120', 'zone_name'=>'North Korea Standard Time'],
            ['title'=> '(UTC+09:00) Seoul', 'timezone'=>'Asia/Seoul', 'order'=> '1130', 'zone_name'=>'Korea Standard Time'],
            ['title'=> '(UTC+09:00) Yakutsk', 'timezone'=>'Asia/Yakutsk', 'order'=> '1140', 'zone_name'=>'Yakutsk Standard Time'],
            ['title'=> '(UTC+09:30) Adelaide', 'timezone'=>'Australia/Adelaide', 'order'=> '1150', 'zone_name'=>'Cen. Australia Standard Time'],
            ['title'=> '(UTC+09:30) Darwin', 'timezone'=>'Australia/Darwin', 'order'=> '1160', 'zone_name'=>'AUS Central Standard Time'],
            ['title'=> '(UTC+10:00) Brisbane', 'timezone'=>'Australia/Brisbane', 'order'=> '1170', 'zone_name'=>'E. Australia Standard Time'],
            ['title'=> '(UTC+10:00) Canberra, Melbourne, Sydney', 'timezone'=>'Australia/Sydney', 'order'=> '1180', 'zone_name'=>'AUS Eastern Standard Time'],
            ['title'=> '(UTC+10:00) Guam, Port Moresby', 'timezone'=>'Pacific/Port_Moresby', 'order'=> '1190', 'zone_name'=>'West Pacific Standard Time'],
            ['title'=> '(UTC+10:00) Hobart', 'timezone'=>'Australia/Hobart', 'order'=> '1200', 'zone_name'=>'Tasmania Standard Time'],
            ['title'=> '(UTC+10:00) Vladivostok', 'timezone'=>'Asia/Vladivostok', 'order'=> '1210', 'zone_name'=>'Vladivostok Standard Time'],
            ['title'=> '(UTC+10:30) Lord Howe Island', 'timezone'=>'Australia/Lord_Howe', 'order'=> '1220', 'zone_name'=>'Lord Howe Standard Time'],
            ['title'=> '(UTC+11:00) Bougainville Island', 'timezone'=>'Pacific/Bougainville', 'order'=> '1230', 'zone_name'=>'Bougainville Standard Time'],
            ['title'=> '(UTC+11:00) Sakhalin', 'timezone'=>'Asia/Sakhalin', 'order'=> '1240', 'zone_name'=>'Sakhalin Standard Time'],
            ['title'=> '(UTC+11:00) Solomon Is., New Caledonia', 'timezone'=>'Pacific/Guadalcanal', 'order'=> '1250', 'zone_name'=>'Central Pacific Standard Time'],
            ['title'=> '(UTC+11:00) Norfolk Island', 'timezone'=>'Pacific/Norfolk', 'order'=> '1260', 'zone_name'=>'Norfolk Standard Time'],
            ['title'=> '(UTC+11:00) Chokurdakh', 'timezone'=>'Asia/Srednekolymsk', 'order'=> '1270', 'zone_name'=>'Russia Time Zone 10'],
            ['title'=> '(UTC+11:00) Magadan', 'timezone'=>'Asia/Magadan', 'order'=> '1280', 'zone_name'=>'Magadan Standard Time'],
            ['title'=> '(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky', 'timezone'=>'Asia/Kamchatka', 'order'=> '1290', 'zone_name'=>'Russia Time Zone 11'],
            ['title'=> '(UTC+12:00) Auckland, Wellington', 'timezone'=>'Pacific/Auckland', 'order'=> '1300', 'zone_name'=>'New Zealand Standard Time'],
            ['title'=> '(UTC+12:00) Coordinated Universal Time+12', 'timezone'=>'Etc/GMT-12', 'order'=> '1310', 'zone_name'=>'UTC+12'],
            ['title'=> '(UTC+12:00) Fiji', 'timezone'=>'Pacific/Fiji', 'order'=> '1320', 'zone_name'=>'Fiji Standard Time'],
            ['title'=> '(UTC+12:45) Chatham Islands', 'timezone'=>'Pacific/Chatham', 'order'=> '1330', 'zone_name'=>'Chatham Islands Standard Time'],
            ['title'=> '(UTC+13:00) Coordinated Universal Time+13', 'timezone'=>'Pacific/Auckland', 'order'=> '1340', 'zone_name'=>'UTC+13'],
            ['title'=> '(UTC+13:00) Nuku’alofa', 'timezone'=>'Pacific/Tongatapu', 'order'=> '1350', 'zone_name'=>'Tonga Standard Time'],
            ['title'=> '(UTC+13:00) Samoa', 'timezone'=>'Pacific/Apia', 'order'=> '1360', 'zone_name'=>'Samoa Standard Time'],
            ['title'=> '(UTC+14:00) Kiritimati Island', 'timezone'=>'Pacific/Kiritimati', 'order'=> '1370', 'zone_name'=>'Line Islands Standard Time'],
        ];

        foreach($timezone as $row){
            DB::table('timezones')->insert([
                'title' => $row['title'],
                'timezone' => $row['timezone'],
                'order' => $row['order'],
                'zone_name'=> $row['zone_name'],
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s')
            ]);
        }
    }
}
