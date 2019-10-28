<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Country::insert([
            [
                'name' => 'Tajikistan',
                'abbreviation' => 'TJ'
            ],
            [
                'name' => 'Kazakhstan',
                'abbreviation' => 'KZ'
            ],
            [
                'name' => 'Uzbekistan',
                'abbreviation' => 'UZ'
            ],
            [
                'name' => 'Georgia',
                'abbreviation' => 'GE'
            ],
            [
                'name' => 'Afghanistan',
                'abbreviation' => 'AFG'
            ],
            [
                'name' => 'Myanmar',
                'abbreviation' => 'MMR'
            ],
            [
                'name' => 'Mongolia',
                'abbreviation' => 'MNG'
            ],
            
            [
                'name' => 'Turkmenistan',
                'abbreviation' => 'TKM'
            ],
            [
                'name' => 'Philippines',
                'abbreviation' => 'PHL'
            ],
            [
                'name' => 'Russia',
                'abbreviation' => 'RUS'
            ],
            [
                'name' => 'Armenia',
                'abbreviation' => 'ARM'
            ],
            [
                'name' => 'Vietnam',
                'abbreviation' => 'VNM'
            ],
            [
                'name' => 'Hong Kong',
                'abbreviation' => 'HKG'
            ],
            [
                'name' => 'Azerbaijan',
                'abbreviation' => 'AZE'
            ],
            [
                'name' => 'Latvia',
                'abbreviation' => 'LVA'
            ],
            [
                'name' => 'Dominican Republic',
                'abbreviation' => 'DOM'
            ],
            [
                'name' => 'Nicaragua',
                'abbreviation' => 'NIC'
            ],
            [
                'name' => 'Ecuador',
                'abbreviation' => 'ECU'
            ],
            [
                'name' => 'Guatemala',
                'abbreviation' => 'GTM'
            ],
            [
                'name' => 'Bolivia',
                'abbreviation' => 'BOL'
            ],
            [
                'name' => 'El Salvador',
                'abbreviation' => 'SLV'
            ],
            [
                'name' => 'Uruguay',
                'abbreviation' => 'URY'
            ],
            [
                'name' => 'Peru',
                'abbreviation' => 'PER'
            ],
            [
                'name' => 'Lithuania',
                'abbreviation' => 'LTU'
            ],
            [
                'name' => 'Estonia',
                'abbreviation' => 'EST'
            ],
            [
                'name' => 'Kyrgyzstan',
                'abbreviation' => 'kg'
            ],
        ]);
    }
}
