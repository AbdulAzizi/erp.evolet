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
        $afg = Country::where('abbreviation', 'AF')->first()->id;
        $KHM = Country::where('abbreviation', 'KH')->first()->id;
        $MMR = Country::where('abbreviation', 'MM')->first()->id;
        $MNG = Country::where('abbreviation', 'MN')->first()->id;
        $tkm = Country::where('abbreviation', 'TM')->first()->id;
        $PHL = Country::where('abbreviation', 'PH')->first()->id;
        $rus = Country::where('abbreviation', 'RU')->first()->id;
        $ARM = Country::where('abbreviation', 'AM')->first()->id;
        $vnm = Country::where('abbreviation', 'VN')->first()->id;
        $hkg = Country::where('abbreviation', 'HK')->first()->id;
        $aze = Country::where('abbreviation', 'AZ')->first()->id;
        $lva = Country::where('abbreviation', 'LV')->first()->id;
        $dom = Country::where('abbreviation', 'DO')->first()->id;
        $nic = Country::where('abbreviation', 'NI')->first()->id;
        $ecu = Country::where('abbreviation', 'EC')->first()->id;
        $gtm = Country::where('abbreviation', 'GT')->first()->id;
        $bol = Country::where('abbreviation', 'BO')->first()->id;
        $slv = Country::where('abbreviation', 'SV')->first()->id;
        $ury = Country::where('abbreviation', 'UY')->first()->id;
        $per = Country::where('abbreviation', 'PE')->first()->id;
        $ltu = Country::where('abbreviation', 'LT')->first()->id;
        $est = Country::where('abbreviation', 'EE')->first()->id;

        $product = Project::insert([
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
                'pc_id' => $V,
                'country_id' => $KHM

            ],
            [
                'pc_id' => $S,
                'country_id' => $KHM

            ],
            [
                'pc_id' => $B,
                'country_id' => $KHM

            ],
            [
                'pc_id' => $N,
                'country_id' => $KHM

            ],
            [
                'pc_id' => $V,
                'country_id' => $MMR

            ],
            [
                'pc_id' => $S,
                'country_id' => $MMR

            ],
            [
                'pc_id' => $B,
                'country_id' => $MMR

            ],
            [
                'pc_id' => $N,
                'country_id' => $MMR

            ],
            [
                'pc_id' => $V,
                'country_id' => $MNG

            ],
            [
                'pc_id' => $S,
                'country_id' => $MNG

            ],
            [
                'pc_id' => $B,
                'country_id' => $MNG

            ],
            [
                'pc_id' => $N,
                'country_id' => $MNG

            ],
            [
                'pc_id' => $S,
                'country_id' => $PHL

            ],
            [
                'pc_id' => $V,
                'country_id' => $ARM

            ],
            [
                'pc_id' => $S,
                'country_id' => $ARM

            ],
            [
                'pc_id' => $B,
                'country_id' => $ARM

            ],
        ]);    
    }
}
