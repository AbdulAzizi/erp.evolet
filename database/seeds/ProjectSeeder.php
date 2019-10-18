<?php

use App\Division;
use Illuminate\Database\Seeder;
use App\Country;
use App\Project;
use Illuminate\Foundation\Auth\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PC id's

        $Et = Division::where('abbreviation', 'Evolet')->first()->id;
        $V = Division::where('abbreviation', 'V')->first()->id;
        $S = Division::where('abbreviation', 'S')->first()->id;
        $B = Division::where('abbreviation', 'B')->first()->id;
        $N = Division::where('abbreviation', 'N')->first()->id;
        $L = Division::where('abbreviation', 'L')->first()->id;
        $BO = Division::where('abbreviation', 'BO')->first()->id;

        //Country id's

        $tj = Country::where('abbreviation', 'TJ')->first()->id;
        $kz = Country::where('abbreviation', 'KZ')->first()->id;
        $uz = Country::where('abbreviation', 'UZ')->first()->id;
        $ge = Country::where('abbreviation', 'GE')->first()->id;
        $kg = Country::where('abbreviation', 'KG')->first()->id;
        $afg = Country::where('abbreviation', 'AFG')->first()->id;
        $mmr = Country::where('abbreviation', 'MMR')->first()->id;
        $mng = Country::where('abbreviation', 'MNG')->first()->id;
        $tkm = Country::where('abbreviation', 'TKM')->first()->id;
        $phl = Country::where('abbreviation', 'PHL')->first()->id;
        $rus = Country::where('abbreviation', 'RUS')->first()->id;
        $arm = Country::where('abbreviation', 'ARM')->first()->id;
        $vnm = Country::where('abbreviation', 'VNM')->first()->id;
        $hkg = Country::where('abbreviation', 'HKG')->first()->id;
        $aze = Country::where('abbreviation', 'AZE')->first()->id;
        $lva = Country::where('abbreviation', 'LVA')->first()->id;
        $dom = Country::where('abbreviation', 'DOM')->first()->id;
        $nic = Country::where('abbreviation', 'NIC')->first()->id;
        $ecu = Country::where('abbreviation', 'ECU')->first()->id;
        $gtm = Country::where('abbreviation', 'GTM')->first()->id;
        $bol = Country::where('abbreviation', 'BOL')->first()->id;
        $slv = Country::where('abbreviation', 'SLV')->first()->id;
        $ury = Country::where('abbreviation', 'URY')->first()->id;
        $per = Country::where('abbreviation', 'PER')->first()->id;
        $ltu = Country::where('abbreviation', 'LTU')->first()->id;
        $est = Country::where('abbreviation', 'EST')->first()->id;

        $product = Project::insert([
            [
                'pc_id' => $Et,
                'country_id' => $tj

            ],
            [
                'pc_id' => $V,
                'country_id' => $tj

            ],
            [
                'pc_id' => $S,
                'country_id' => $tj

            ],
            [
                'pc_id' => $B,
                'country_id' => $tj

            ],
            [
                'pc_id' => $N,
                'country_id' => $tj

            ],
            [
                'pc_id' => $L,
                'country_id' => $tj

            ],
            [
                'pc_id' => $BO,
                'country_id' => $tj

            ],
            [
                'pc_id' => $Et,
                'country_id' => $kz

            ],
            [
                'pc_id' => $S,
                'country_id' => $kz

            ],
            [
                'pc_id' => $B,
                'country_id' => $kz

            ],
            [
                'pc_id' => $V,
                'country_id' => $kz

            ],
            [
                'pc_id' => $N,
                'country_id' => $kz

            ],
            [
                'pc_id' => $Et,
                'country_id' => $uz

            ],
            [
                'pc_id' => $V,
                'country_id' => $uz

            ],
            [
                'pc_id' => $S,
                'country_id' => $uz

            ],
            [
                'pc_id' => $B,
                'country_id' => $uz

            ],
            [
                'pc_id' => $N,
                'country_id' => $uz

            ],
            [
                'pc_id' => $Et,
                'country_id' => $ge

            ],
            [
                'pc_id' => $L,
                'country_id' => $ge

            ],
            [
                'pc_id' => $V,
                'country_id' => $ge

            ],
            [
                'pc_id' => $S,
                'country_id' => $ge

            ],
            [
                'pc_id' => $B,
                'country_id' => $ge

            ],
            [
                'pc_id' => $Et,
                'country_id' => $kg

            ],
            [
                'pc_id' => $V,
                'country_id' => $kg

            ],
            [
                'pc_id' => $N,
                'country_id' => $kg

            ],
            [
                'pc_id' => $S,
                'country_id' => $kg

            ],
            [
                'pc_id' => $B,
                'country_id' => $kg

            ],
            [
                'pc_id' => $Et,
                'country_id' => $afg

            ],
        ]);
    }
}
