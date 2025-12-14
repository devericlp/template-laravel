<?php

namespace Database\Seeders;

use App\Models\{Country, State};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Http};

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (app()->environment('testing')) {
            return;
        }

        DB::transaction(function () {

            $this->generateCountries();

            $brazil = Country::query()->where('name', '=', 'brazil')->get()->first();

            if ($brazil !== null) {
                $states = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome');

                if (!empty($states)) {
                    foreach ($states->json() as $state) {

                        $state = State::create([
                            'name' => $state['nome'],
                            'code' => $state['sigla'],
                            'country_id' => $brazil->id,
                        ]);

                        $cities = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state['id']}/municipios");

                        foreach ($cities->json() as $city) {

                            $state->cities()->create(['name' => $city['nome']]);
                        }
                    }
                }
            }
        });

    }

    public function generateCountries(): void
    {
        $countries = [
            'afghanistan',
            'albania',
            'algeria',
            'andorra',
            'angola',
            'antigua_and_barbuda',
            'argentina',
            'armenia',
            'australia',
            'austria',
            'azerbaijan',
            'bahamas',
            'bahrain',
            'bangladesh',
            'barbados',
            'belarus',
            'belgium',
            'belize',
            'benin',
            'bolivia',
            'bosnia_and_herzegovina',
            'botswana',
            'brazil',
            'brunei',
            'bulgaria',
            'burkina_faso',
            'burundi',
            'cape_verde',
            'cameroon',
            'cambodia',
            'canada',
            'central_african_republic',
            'chad',
            'chile',
            'china',
            'colombia',
            'comoros',
            'cote_divoire',
            'croatia',
            'cuba',
            'czech_republic',
            'denmark',
            'djibouti',
            'dominica',
            'dominican_republic',
            'ecuador',
            'egypt',
            'equatorial_guinea',
            'eritrea',
            'estonia',
            'eswatini',
            'ethiopia',
            'fiji',
            'finland',
            'france',
            'gabon',
            'gambia',
            'georgia',
            'ghana',
            'greece',
            'grenada',
            'guatemala',
            'guinea',
            'guinea_bissau',
            'guyana',
            'haiti',
            'hungary',
            'iceland',
            'india',
            'indonesia',
            'iraq',
            'ireland',
            'israel',
            'italy',
            'jamaica',
            'japan',
            'jordan',
            'kazakhstan',
            'kenya',
            'kosovo',
            'kuwait',
            'laos',
            'latvia',
            'lebanon',
            'lesotho',
            'liberia',
            'libya',
            'liechtenstein',
            'lithuania',
            'luxembourg',
            'madagascar',
            'malawi',
            'malaysia',
            'maldives',
            'mali',
            'malta',
            'marshall_islands',
            'mauritania',
            'mauritius',
            'mexico',
            'micronesia',
            'moldova',
            'monaco',
            'mongolia',
            'montenegro',
            'mozambique',
            'namibia',
            'nauru',
            'nepal',
            'netherlands',
            'new_zealand',
            'nicaragua',
            'niger',
            'nigeria',
            'north_korea',
            'norway',
            'oman',
            'pakistan',
            'palau',
            'panama',
            'papua_new_guinea',
            'paraguay',
            'peru',
            'philippines',
            'poland',
            'portugal',
            'qatar',
            'romania',
            'rwanda',
            'russia',
            'samoa',
            'saint_kitts_and_nevis',
            'saint_lucia',
            'saint_vincent_and_the_grenadines',
            'sao_tome_and_principe',
            'saudi_arabia',
            'senegal',
            'serbia',
            'seychelles',
            'sierra_leone',
            'singapore',
            'slovakia',
            'slovenia',
            'solomon_islands',
            'somalia',
            'south_africa',
            'south_korea',
            'spain',
            'sri_lanka',
            'sudan',
            'suriname',
            'sweden',
            'switzerland',
            'syria',
            'tajikistan',
            'tanzania',
            'thailand',
            'timor_leste',
            'togo',
            'tonga',
            'trinidad_and_tobago',
            'tunisia',
            'turkey',
            'turkmenistan',
            'tuvalu',
            'uganda',
            'ukraine',
            'united_arab_emirates',
            'united_kingdom',
            'united_states',
            'uruguay',
            'uzbekistan',
            'vanuatu',
            'venezuela',
            'vietnam',
            'yemen',
            'zambia',
            'zimbabwe',
        ];

        $data = [];

        foreach ($countries as $country) {
            $data[] = [
                'name' => $country,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Country::insert($data);
    }
}
