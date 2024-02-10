<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimezonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timezones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value');
            $table->tinyInteger('valid')->default(1);
        });

        DB::table('timezones')->insert(array(
            array('id' => 1,  'name' => '(UTC-11: 00) Midway Island', 'value'                   => 'Pacific/Midway', 'valid'                 => 1),
            array('id' => 2,  'name' => '(UTC-11: 00) Samoa', 'value'                           => 'Pacific/Samoa', 'valid'                  => 1),
            array('id' => 3,  'name' => '(UTC-10: 00) Hawaii', 'value'                          => 'Pacific/Honolulu', 'valid'               => 1),
            array('id' => 4,  'name' => '(UTC-09: 00) Alaska', 'value'                          => 'US/Alaska', 'valid'                      => 1),
            array('id' => 5,  'name' => '(UTC-08: 00) Pacific Time (US &amp; Canada)', 'value'  => 'America/Los_Angeles', 'valid'            => 1),
            array('id' => 6,  'name' => '(UTC-08: 00) Tijuana', 'value'                         => 'America/Tijuana', 'valid'                => 1),
            array('id' => 7,  'name' => '(UTC-07: 00) Arizona', 'value'                         => 'US/Arizona', 'valid'                     => 1),
            array('id' => 8,  'name' => '(UTC-07: 00) Chihuahua', 'value'                       => 'America/Chihuahua', 'valid'              => 1),
            array('id' => 9,  'name' => '(UTC-07: 00) La Paz', 'value'                          => 'America/Chihuahua', 'valid'              => 1),
            array('id' => 10, 'name' => '(UTC-07: 00) Mazatlan', 'value'                        => 'America/Mazatlan', 'valid'               => 1),
            array('id' => 11, 'name' => '(UTC-07: 00) Mountain Time (US &amp; Canada)', 'value' => 'US/Mountain', 'valid'                    => 1),
            array('id' => 12, 'name' => '(UTC-06: 00) Central America', 'value'                 => 'America/Managua', 'valid'                => 1),
            array('id' => 13, 'name' => '(UTC-06: 00) Central Time (US &amp; Canada)', 'value'  => 'US/Central', 'valid'                     => 1),
            array('id' => 14, 'name' => '(UTC-06: 00) Guadalajara', 'value'                     => 'America/Mexico_City', 'valid'            => 1),
            array('id' => 15, 'name' => '(UTC-06: 00) Mexico City', 'value'                     => 'America/Mexico_City', 'valid'            => 1),
            array('id' => 16, 'name' => '(UTC-06: 00) Monterrey', 'value'                       => 'America/Monterrey', 'valid'              => 1),
            array('id' => 17, 'name' => '(UTC-06: 00) Saskatchewan', 'value'                    => 'Canada/Saskatchewan', 'valid'            => 1),
            array('id' => 18, 'name' => '(UTC-05: 00) Bogota', 'value'                          => 'America/Bogota', 'valid'                 => 1),
            array('id' => 19, 'name' => '(UTC-05: 00) Eastern Time (US &amp; Canada)', 'value'  => 'US/Eastern', 'valid'                     => 1),
            array('id' => 20, 'name' => '(UTC-05: 00) Indiana (East)', 'value'                  => 'US/East-Indiana', 'valid'                => 1),
            array('id' => 21, 'name' => '(UTC-05: 00) Lima', 'value'                            => 'America/Lima', 'valid'                   => 1),
            array('id' => 22, 'name' => '(UTC-05: 00) Quito', 'value'                           => 'America/Bogota', 'valid'                 => 1),
            array('id' => 23, 'name' => '(UTC-04: 00) Atlantic Time (Canada)', 'value'          => 'Canada/Atlantic', 'valid'                => 1),
            array('id' => 24, 'name' => '(UTC-04: 30) Caracas', 'value'                         => 'America/Caracas', 'valid'                => 1),
            array('id' => 25, 'name' => '(UTC-04: 00) La Paz', 'value'                          => 'America/La_Paz', 'valid'                 => 1),
            array('id' => 26, 'name' => '(UTC-04: 00) Santiago', 'value'                        => 'America/Santiago', 'valid'               => 1),
            array('id' => 27, 'name' => '(UTC-03: 30) Newfoundland', 'value'                    => 'Canada/Newfoundland', 'valid'            => 1),
            array('id' => 28, 'name' => '(UTC-03: 00) Brasilia', 'value'                        => 'America/Sao_Paulo', 'valid'              => 1),
            array('id' => 29, 'name' => '(UTC-03: 00) Buenos Aires', 'value'                    => 'America/Argentina/Buenos_Aires', 'valid' => 1),
            array('id' => 30, 'name' => '(UTC-03: 00) Georgetown', 'value'                      => 'America/Argentina/Buenos_Aires', 'valid' => 1),
            array('id' => 31, 'name' => '(UTC-03: 00) Greenland', 'value'                       => 'America/Godthab', 'valid'                => 1),
            array('id' => 32, 'name' => '(UTC-02: 00) Mid-Atlantic', 'value'                    => 'America/Noronha', 'valid'                => 1),
            array('id' => 33, 'name' => '(UTC-01: 00) Azores', 'value'                          => 'Atlantic/Azores', 'valid'                => 1),
            array('id' => 34, 'name' => '(UTC-01: 00) Cape Verde Is.', 'value'                  => 'Atlantic/Cape_Verde', 'valid'            => 1),
            array('id' => 35, 'name' => '(UTC+00: 00) Casablanca', 'value'                      => 'Africa/Casablanca', 'valid'              => 1),
            array('id' => 36, 'name' => '(UTC+00: 00) Edinburgh', 'value'                       => 'Europe/London', 'valid'                  => 1),
            array('id' => 37, 'name' => '(UTC+00: 00) Greenwich Mean Time                       :D ublin', 'value'                           => 'Etc/Greenwich', 'valid' => 1),
            array('id' => 38, 'name' => '(UTC+00: 00) Lisbon', 'value'                          => 'Europe/Lisbon', 'valid'                  => 1),
            array('id' => 39, 'name' => '(UTC+00: 00) London', 'value'                          => 'Europe/London', 'valid'                  => 1),
            array('id' => 40, 'name' => '(UTC+00: 00) Monrovia', 'value'                        => 'Africa/Monrovia', 'valid'                => 1),
            array('id' => 41, 'name' => '(UTC+00: 00) UTC', 'value'                             => 'UTC', 'valid'                            => 1),
            array('id' => 42, 'name' => '(UTC+01: 00) Amsterdam', 'value'                       => 'Europe/Amsterdam', 'valid'               => 1),
            array('id' => 43, 'name' => '(UTC+01: 00) Belgrade', 'value'                        => 'Europe/Belgrade', 'valid'                => 1),
            array('id' => 44, 'name' => '(UTC+01: 00) Berlin', 'value'                          => 'Europe/Berlin', 'valid'                  => 1),
            array('id' => 45, 'name' => '(UTC+01: 00) Bern', 'value'                            => 'Europe/Berlin', 'valid'                  => 1),
            array('id' => 46, 'name' => '(UTC+01: 00) Bratislava', 'value'                      => 'Europe/Bratislava', 'valid'              => 1),
            array('id' => 47, 'name' => '(UTC+01: 00) Brussels', 'value'                        => 'Europe/Brussels', 'valid'                => 1),
            array('id' => 48, 'name' => '(UTC+01: 00) Budapest', 'value'                        => 'Europe/Budapest', 'valid'                => 1),
            array('id' => 49, 'name' => '(UTC+01: 00) Copenhagen', 'value'                      => 'Europe/Copenhagen', 'valid'              => 1),
            array('id' => 50, 'name' => '(UTC+01: 00) Ljubljana', 'value'                       => 'Europe/Ljubljana', 'valid'               => 1),
            array('id' => 51, 'name' => '(UTC+01: 00) Madrid', 'value'                          => 'Europe/Madrid', 'valid'                  => 1),
            array('id' => 52, 'name' => '(UTC+01: 00) Paris', 'value'                           => 'Europe/Paris', 'valid'                   => 1),
            array('id' => 53, 'name' => '(UTC+01: 00) Prague', 'value'                          => 'Europe/Prague', 'valid'                  => 1),
            array('id' => 54, 'name' => '(UTC+01: 00) Rome', 'value'                            => 'Europe/Rome', 'valid'                    => 1),
            array('id' => 55, 'name' => '(UTC+01: 00) Sarajevo', 'value'                        => 'Europe/Sarajevo', 'valid'                => 1),
            array('id' => 56, 'name' => '(UTC+01: 00) Skopje', 'value'                          => 'Europe/Skopje', 'valid'                  => 1),
            array('id' => 57, 'name' => '(UTC+01: 00) Stockholm', 'value'                       => 'Europe/Stockholm', 'valid'               => 1),
            array('id' => 58, 'name' => '(UTC+01: 00) Vienna', 'value'                          => 'Europe/Vienna', 'valid'                  => 1),
            array('id' => 59, 'name' => '(UTC+01: 00) Warsaw', 'value'                          => 'Europe/Warsaw', 'valid'                  => 1),
            array('id' => 60, 'name' => '(UTC+01: 00) West Central Africa', 'value'             => 'Africa/Lagos', 'valid'                   => 1),
            array('id' => 61, 'name' => '(UTC+01: 00) Zagreb', 'value'                          => 'Europe/Zagreb', 'valid'                  => 1),
            array('id' => 62, 'name' => '(UTC+02: 00) Athens', 'value'                          => 'Europe/Athens', 'valid'                  => 1),
            array('id' => 63, 'name' => '(UTC+02: 00) Bucharest', 'value'                       => 'Europe/Bucharest', 'valid'               => 1),
            array('id' => 64, 'name' => '(UTC+02: 00) Cairo', 'value'                           => 'Africa/Cairo', 'valid'                   => 1),
            array('id' => 65, 'name' => '(UTC+02: 00) Harare', 'value'                          => 'Africa/Harare', 'valid'                  => 1),
            array('id' => 66, 'name' => '(UTC+02: 00) Helsinki', 'value'                        => 'Europe/Helsinki', 'valid'                => 1),
            array('id' => 67, 'name' => '(UTC+02: 00) Istanbul', 'value'                        => 'Europe/Istanbul', 'valid'                => 1),
            array('id' => 68, 'name' => '(UTC+02: 00) Jerusalem', 'value'                       => 'Asia/Jerusalem', 'valid'                 => 1),
            array('id' => 69, 'name' => '(UTC+02: 00) Kyiv', 'value'                            => 'Europe/Helsinki', 'valid'                => 1),
            array('id' => 70, 'name' => '(UTC+02: 00) Pretoria', 'value'                        => 'Africa/Johannesburg', 'valid'            => 1),
            array('id' => 71, 'name' => '(UTC+02: 00) Riga', 'value'                            => 'Europe/Riga', 'valid'                    => 1),
            array('id' => 72, 'name' => '(UTC+02: 00) Sofia', 'value'                           => 'Europe/Sofia', 'valid'                   => 1),
            array('id' => 73, 'name' => '(UTC+02: 00) Tallinn', 'value'                         => 'Europe/Tallinn', 'valid'                 => 1),
            array('id' => 74, 'name' => '(UTC+02: 00) Vilnius', 'value'                         => 'Europe/Vilnius', 'valid'                 => 1),
            array('id' => 75, 'name' => '(UTC+03: 00) Baghdad', 'value'                         => 'Asia/Baghdad', 'valid'                   => 1),
            array('id' => 76, 'name' => '(UTC+03: 00) Kuwait', 'value'                          => 'Asia/Kuwait', 'valid'                    => 1),
            array('id' => 77, 'name' => '(UTC+03: 00) Minsk', 'value'                           => 'Europe/Minsk', 'valid'                   => 1),
            array('id' => 78, 'name' => '(UTC+03: 00) Nairobi', 'value'                         => 'Africa/Nairobi', 'valid'                 => 1),
            array('id' => 79, 'name' => '(UTC+03: 00) Riyadh', 'value'                          => 'Asia/Riyadh', 'valid'                    => 1),
            array('id' => 80, 'name' => '(UTC+03: 00) Volgograd', 'value'                       => 'Europe/Volgograd', 'valid'               => 1),
            array('id' => 81, 'name' => '(UTC+03: 30) Tehran', 'value'                          => 'Asia/Tehran', 'valid'                    => 1),
            array('id' => 82, 'name' => '(UTC+04: 00) Abu Dhabi', 'value'                       => 'Asia/Muscat', 'valid'                    => 1),
            array('id' => 83, 'name' => '(UTC+04: 00) Baku', 'value'                            => 'Asia/Baku', 'valid'                      => 1),
            array('id' => 84, 'name' => '(UTC+04: 00) Moscow', 'value'                          => 'Europe/Moscow', 'valid'                  => 1),
            array('id' => 85, 'name' => '(UTC+04: 00) Muscat', 'value'                          => 'Asia/Muscat', 'valid'                    => 1),
            array('id' => 86, 'name' => '(UTC+04: 00) St. Petersburg', 'value'                  => 'Europe/Moscow', 'valid'                  => 1),
            array('id' => 87, 'name' => '(UTC+04: 00) Tbilisi', 'value'                         => 'Asia/Tbilisi', 'valid'                   => 1),
            array('id' => 88, 'name' => '(UTC+04: 00) Yerevan', 'value'                         => 'Asia/Yerevan', 'valid'                   => 1),
            array('id' => 89, 'name' => '(UTC+04: 30) Kabul', 'value'                           => 'Asia/Kabul', 'valid'                     => 1),
            array('id' => 90, 'name' => '(UTC+05: 00) Islamabad', 'value'                       => 'Asia/Karachi', 'valid'                   => 1),
            array('id' => 91, 'name' => '(UTC+05: 00) Karachi', 'value'                         => 'Asia/Karachi', 'valid'                   => 1),
            array('id' => 92, 'name' => '(UTC+05: 00) Tashkent', 'value'                        => 'Asia/Tashkent', 'valid'                  => 1),
            array('id' => 93, 'name' => '(UTC+05: 30) Chennai', 'value'                         => 'Asia/Calcutta', 'valid'                  => 1),
            array('id' => 94, 'name' => '(UTC+05: 30) Kolkata', 'value'                         => 'Asia/Kolkata', 'valid'                   => 1),
            array('id' => 95, 'name' => '(UTC+05: 30) Mumbai', 'value'                          => 'Asia/Calcutta', 'valid'                  => 1),
            array('id' => 96, 'name' => '(UTC+05: 30) New Delhi', 'value'                       => 'Asia/Calcutta', 'valid'                  => 1),
            array('id' => 97, 'name' => '(UTC+05: 30) Sri Jayawardenepura', 'value'             => 'Asia/Calcutta', 'valid'                  => 1),
            array('id' => 98, 'name' => '(UTC+05: 45) Kathmandu', 'value'                       => 'Asia/Katmandu', 'valid'                  => 1),
            array('id' => 99, 'name' => '(UTC+06: 00) Almaty', 'value'                          => 'Asia/Almaty', 'valid'                    => 1),
            array('id' => 100,'name' => '(UTC+06: 00) Astana', 'value'                          => 'Asia/Dhaka', 'valid'                     => 1),
            array('id' => 101,'name' => '(UTC+06: 00) Dhaka', 'value'                           => 'Asia/Dhaka', 'valid'                     => 1),
            array('id' => 102,'name' => '(UTC+06: 00) Ekaterinburg', 'value'                    => 'Asia/Yekaterinburg', 'valid'             => 1),
            array('id' => 103,'name' => '(UTC+06: 30) Rangoon', 'value'                         => 'Asia/Rangoon', 'valid'                   => 1),
            array('id' => 104,'name' => '(UTC+07: 00) Bangkok', 'value'                         => 'Asia/Bangkok', 'valid'                   => 1),
            array('id' => 105,'name' => '(UTC+07: 00) Hanoi', 'value'                           => 'Asia/Bangkok', 'valid'                   => 1),
            array('id' => 106,'name' => '(UTC+07: 00) Jakarta', 'value'                         => 'Asia/Jakarta', 'valid'                   => 1),
            array('id' => 107,'name' => '(UTC+07: 00) Novosibirsk', 'value'                     => 'Asia/Novosibirsk', 'valid'               => 1),
            array('id' => 108,'name' => '(UTC+08: 00) Beijing', 'value'                         => 'Asia/Hong_Kong', 'valid'                 => 1),
            array('id' => 109,'name' => '(UTC+08: 00) Chongqing', 'value'                       => 'Asia/Chongqing', 'valid'                 => 1),
            array('id' => 110,'name' => '(UTC+08: 00) Hong Kong', 'value'                       => 'Asia/Hong_Kong', 'valid'                 => 1),
            array('id' => 111,'name' => '(UTC+08: 00) Krasnoyarsk', 'value'                     => 'Asia/Krasnoyarsk', 'valid'               => 1),
            array('id' => 112,'name' => '(UTC+08: 00) Kuala Lumpur', 'value'                    => 'Asia/Kuala_Lumpur', 'valid'              => 1),
            array('id' => 113,'name' => '(UTC+08: 00) Perth', 'value'                           => 'Australia/Perth', 'valid'                => 1),
            array('id' => 114,'name' => '(UTC+08: 00) Singapore', 'value'                       => 'Asia/Singapore', 'valid'                 => 1),
            array('id' => 115,'name' => '(UTC+08: 00) Taipei', 'value'                          => 'Asia/Taipei', 'valid'                    => 1),
            array('id' => 116,'name' => '(UTC+08: 00) Ulaan Bataar', 'value'                    => 'Asia/Ulan_Bator', 'valid'                => 1),
            array('id' => 117,'name' => '(UTC+08: 00) Urumqi', 'value'                          => 'Asia/Urumqi', 'valid'                    => 1),
            array('id' => 118,'name' => '(UTC+09: 00) Irkutsk', 'value'                         => 'Asia/Irkutsk', 'valid'                   => 1),
            array('id' => 119,'name' => '(UTC+09: 00) Osaka', 'value'                           => 'Asia/Tokyo', 'valid'                     => 1),
            array('id' => 120,'name' => '(UTC+09: 00) Sapporo', 'value'                         => 'Asia/Tokyo', 'valid'                     => 1),
            array('id' => 121,'name' => '(UTC+09: 00) Seoul', 'value'                           => 'Asia/Seoul', 'valid'                     => 1),
            array('id' => 122,'name' => '(UTC+09: 00) Tokyo', 'value'                           => 'Asia/Tokyo', 'valid'                     => 1),
            array('id' => 123,'name' => '(UTC+09: 30) Adelaide', 'value'                        => 'Australia/Adelaide', 'valid'             => 1),
            array('id' => 124,'name' => '(UTC+09: 30) Darwin', 'value'                          => 'Australia/Darwin', 'valid'               => 1),
            array('id' => 125,'name' => '(UTC+10: 00) Brisbane', 'value'                        => 'Australia/Brisbane', 'valid'             => 1),
            array('id' => 126,'name' => '(UTC+10: 00) Canberra', 'value'                        => 'Australia/Canberra', 'valid'             => 1),
            array('id' => 127,'name' => '(UTC+10: 00) Guam', 'value'                            => 'Pacific/Guam', 'valid'                   => 1),
            array('id' => 128,'name' => '(UTC+10: 00) Hobart', 'value'                          => 'Australia/Hobart', 'valid'               => 1),
            array('id' => 129,'name' => '(UTC+10: 00) Melbourne', 'value'                       => 'Australia/Melbourne', 'valid'            => 1),
            array('id' => 130,'name' => '(UTC+10: 00) Port Moresby', 'value'                    => 'Pacific/Port_Moresby', 'valid'           => 1),
            array('id' => 131,'name' => '(UTC+10: 00) Sydney', 'value'                          => 'Australia/Sydney', 'valid'               => 1),
            array('id' => 132,'name' => '(UTC+10: 00) Yakutsk', 'value'                         => 'Asia/Yakutsk', 'valid'                   => 1),
            array('id' => 133,'name' => '(UTC+11: 00) Vladivostok', 'value'                     => 'Asia/Vladivostok', 'valid'               => 1),
            array('id' => 134,'name' => '(UTC+12: 00) Auckland', 'value'                        => 'Pacific/Auckland', 'valid'               => 1),
            array('id' => 135,'name' => '(UTC+12: 00) Fiji', 'value'                            => 'Pacific/Fiji', 'valid'                   => 1),
            array('id' => 136,'name' => '(UTC+12: 00) International Date Line West', 'value'    => 'Pacific/Kwajalein', 'valid'              => 1),
            array('id' => 137,'name' => '(UTC+12: 00) Kamchatka', 'value'                       => 'Asia/Kamchatka', 'valid'                 => 1),
            array('id' => 138,'name' => '(UTC+12: 00) Magadan', 'value'                         => 'Asia/Magadan', 'valid'                   => 1),
            array('id' => 139,'name' => '(UTC+12: 00) Marshall Is.', 'value'                    => 'Pacific/Fiji', 'valid'                   => 1),
            array('id' => 140,'name' => '(UTC+12: 00) New Caledonia', 'value'                   => 'Asia/Magadan', 'valid'                   => 1),
            array('id' => 141,'name' => '(UTC+12: 00) Solomon Is.', 'value'                     => 'Asia/Magadan', 'valid'                   => 1),
            array('id' => 142,'name' => '(UTC+12: 00) Wellington', 'value'                      => 'Pacific/Auckland', 'valid'               => 1),
            array('id' => 143,'name' => '(UTC+13: 00) Nuku\'alofa', 'value'                     => 'Pacific/Tongatapu', 'valid'              => 1),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timezones');
    }
}
