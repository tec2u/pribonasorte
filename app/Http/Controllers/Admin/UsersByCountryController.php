<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\SearchRequest;
use App\Http\Controllers\Controller;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use App\Traits\CustomLogTrait;
use Illuminate\Support\Facades\DB;


class UsersByCountryController extends Controller
{

    public function index()
    {
    $country_search = User::select('country',DB::raw('count(*) as amount'))
                        ->groupBy('country')
                        ->orderBy('amount','DESC')

                        ->get();



     foreach ($country_search as $key => $value) {

            $country_sigla = $value['country'];

            if($value['country'] == 'AlandIslands'){$sigla['sigla'] = 'AX';}
            if($value['country'] == 'Albania'){$sigla['sigla'] = 'AL';}
            if($value['country'] == 'Algeria'){$sigla['sigla'] = 'DZ';}
            if($value['country'] == 'AmericanSamoa'){$sigla['sigla'] = 'AS';}
            if($value['country'] == 'Andorra'){$sigla['sigla'] = 'AD';}
            if($value['country'] == 'Angola'){$sigla['sigla'] = 'AO';}
            if($value['country'] == 'Anguilla'){$sigla['sigla'] = 'AI';}
            if($value['country'] == 'Antarctica'){$sigla['sigla'] = 'AQ';}
            if($value['country'] == 'Antigua and Barbuda'){$sigla['sigla'] = 'AG';}
            if($value['country'] == 'Argentina'){$sigla['sigla'] = 'AR';}
            if($value['country'] == 'Armenia'){$sigla['sigla'] = 'AM';}
            if($value['country'] == 'Aruba'){$sigla['sigla'] = 'AW';}
            if($value['country'] == 'Australia'){$sigla['sigla'] = 'AU';}
            if($value['country'] == 'Austria'){$sigla['sigla'] = 'AT';}
            if($value['country'] == 'Azerbaijan'){$sigla['sigla'] = 'AZ';}
            if($value['country'] == 'Bahamas'){$sigla['sigla'] = 'BS';}
            if($value['country'] == 'Bahrain'){$sigla['sigla'] = 'BH';}
            if($value['country'] == 'Bangladesh'){$sigla['sigla'] = 'BD';}
            if($value['country'] == 'Barbados'){$sigla['sigla'] = 'BB';}
            if($value['country'] == 'Belarus'){$sigla['sigla'] = 'BY';}
            if($value['country'] == 'Belgium'){$sigla['sigla'] = 'BE';}
            if($value['country'] == 'Belize'){$sigla['sigla'] = 'BZ';}
            if($value['country'] == 'Benin'){$sigla['sigla'] = 'BJ';}
            if($value['country'] == 'Bermuda'){$sigla['sigla'] = 'BM';}
            if($value['country'] == 'Bhutan'){$sigla['sigla'] = 'BT';}
            if($value['country'] == 'Bolivia'){$sigla['sigla'] = 'BO';}
            if($value['country'] == 'Bonaire, Sint Eustatiusand Saba'){$sigla['sigla'] = 'BQ';}
            if($value['country'] == 'Bosniaand Herzegovina'){$sigla['sigla'] = 'BA';}
            if($value['country'] == 'Botswana'){$sigla['sigla'] = 'BW';}
            if($value['country'] == 'Bouvet Island'){$sigla['sigla'] = 'BV';}
            if($value['country'] == 'Brazil'){$sigla['sigla'] = 'BR';}
            if($value['country'] == 'brazil'){$sigla['sigla'] = 'BR';}
            if($value['country'] == 'British Indian OceanTerritory'){$sigla['sigla'] = 'IO';}
            if($value['country'] == 'Brunei Darussalam'){$sigla['sigla'] = 'BN';}
            if($value['country'] == 'Bulgaria'){$sigla['sigla'] = 'BG';}
            if($value['country'] == 'Burkina Faso'){$sigla['sigla'] = 'BF';}
            if($value['country'] == 'Burundi'){$sigla['sigla'] = 'BI';}
            if($value['country'] == 'Cambodia'){$sigla['sigla'] = 'KH';}
            if($value['country'] == 'Cameroon'){$sigla['sigla'] = 'CM';}
            if($value['country'] == 'Canada'){$sigla['sigla'] = 'CA';}
            if($value['country'] == 'Cape Verde'){$sigla['sigla'] = 'CV';}
            if($value['country'] == 'Cayman Islands'){$sigla['sigla'] = 'KY';}
            if($value['country'] == 'Central African Republic'){$sigla['sigla'] = 'CF';}
            if($value['country'] == 'Chad'){$sigla['sigla'] = 'TD';}
            if($value['country'] == 'Chile'){$sigla['sigla'] = 'CL';}
            if($value['country'] == 'China'){$sigla['sigla'] = 'CN';}
            if($value['country'] == 'Christmas Island'){$sigla['sigla'] = 'CX';}
            if($value['country'] == 'Cocos(Keeling) Islands'){$sigla['sigla'] = 'CC';}
            if($value['country'] == 'Colombia'){$sigla['sigla'] = 'CO';}
            if($value['country'] == 'Comoros'){$sigla['sigla'] = 'KM';}
            if($value['country'] == 'Congo'){$sigla['sigla'] = 'CG';}
            if($value['country'] == 'Congo, DemocraticRepublic of the Congo'){$sigla['sigla'] = 'CD';}
            if($value['country'] == 'Cook Islands'){$sigla['sigla'] = 'CK';}
            if($value['country'] == 'Costa Rica'){$sigla['sigla'] = 'CR';}
            if($value['country'] == 'Cote DIvoire'){$sigla['sigla'] = 'CI';}
            if($value['country'] == 'Croatia'){$sigla['sigla'] = 'HR';}
            if($value['country'] == 'Cuba'){$sigla['sigla'] = 'CU';}
            if($value['country'] == 'Curacao'){$sigla['sigla'] = 'CW';}
            if($value['country'] == 'Cyprus'){$sigla['sigla'] = 'CY';}
            if($value['country'] == 'Czech Republic'){$sigla['sigla'] = 'CZ';}
            if($value['country'] == 'Denmark'){$sigla['sigla'] = 'DK';}
            if($value['country'] == 'Djibouti'){$sigla['sigla'] = 'DJ';}
            if($value['country'] == 'Dominica'){$sigla['sigla'] = 'DM';}
            if($value['country'] == 'Dominican Republic'){$sigla['sigla'] = 'DO';}
            if($value['country'] == 'Ecuador'){$sigla['sigla'] = 'EC';}
            if($value['country'] == 'Egypt'){$sigla['sigla'] = 'EG';}
            if($value['country'] == 'El Salvador'){$sigla['sigla'] = 'SV';}
            if($value['country'] == 'Equatorial Guinea'){$sigla['sigla'] = 'GQ';}
            if($value['country'] == 'Eritrea'){$sigla['sigla'] = 'ER';}
            if($value['country'] == 'Estonia'){$sigla['sigla'] = 'EE';}
            if($value['country'] == 'Ethiopia'){$sigla['sigla'] = 'ET';}
            if($value['country'] == 'Falkland Islands(Malvinas)'){$sigla['sigla'] = 'FK';}
            if($value['country'] == 'Faroe Islands'){$sigla['sigla'] = 'FO';}
            if($value['country'] == 'Fiji'){$sigla['sigla'] = 'FJ';}
            if($value['country'] == 'Finland'){$sigla['sigla'] = 'FI';}
            if($value['country'] == 'France'){$sigla['sigla'] = 'FR';}
            if($value['country'] == 'French Guiana'){$sigla['sigla'] = 'GF';}
            if($value['country'] == 'French Polynesia'){$sigla['sigla'] = 'PF';}
            if($value['country'] == 'French SouthernTerritories'){$sigla['sigla'] = 'TF';}
            if($value['country'] == 'Gabon'){$sigla['sigla'] = 'GA';}
            if($value['country'] == 'Gambia'){$sigla['sigla'] = 'GM';}
            if($value['country'] == 'Georgia'){$sigla['sigla'] = 'GE';}
            if($value['country'] == 'Germany'){$sigla['sigla'] = 'DE';}
            if($value['country'] == 'Ghana'){$sigla['sigla'] = 'GH';}
            if($value['country'] == 'Gibraltar'){$sigla['sigla'] = 'GI';}
            if($value['country'] == 'Greece'){$sigla['sigla'] = 'GR';}
            if($value['country'] == 'Greenland'){$sigla['sigla'] = 'GL';}
            if($value['country'] == 'Grenada'){$sigla['sigla'] = 'GD';}
            if($value['country'] == 'Guadeloupe'){$sigla['sigla'] = 'GP';}
            if($value['country'] == 'Guam'){$sigla['sigla'] = 'GU';}
            if($value['country'] == 'Guatemala'){$sigla['sigla'] = 'GT';}
            if($value['country'] == 'Guernsey'){$sigla['sigla'] = 'GG';}
            if($value['country'] == 'Guinea'){$sigla['sigla'] = 'GN';}
            if($value['country'] == 'Guinea-Bissau'){$sigla['sigla'] = 'GW';}
            if($value['country'] == 'Guyana'){$sigla['sigla'] = 'GY';}
            if($value['country'] == 'Haiti'){$sigla['sigla'] = 'HT';}
            if($value['country'] == 'HeardIsland and McdonaldIslands'){$sigla['sigla'] = 'HM';}
            if($value['country'] == 'HolySee (Vatican CityState)'){$sigla['sigla'] = 'VA';}
            if($value['country'] == 'Honduras'){$sigla['sigla'] = 'HN';}
            if($value['country'] == 'Hong Kong'){$sigla['sigla'] = 'HK';}
            if($value['country'] == 'Hungary'){$sigla['sigla'] = 'HU';}
            if($value['country'] == 'Iceland'){$sigla['sigla'] = 'IS';}
            if($value['country'] == 'India'){$sigla['sigla'] = 'IN';}
            if($value['country'] == 'Indonesia'){$sigla['sigla'] = 'ID';}
            if($value['country'] == 'Iran, Islamic Republicof'){$sigla['sigla'] = 'IR';}
            if($value['country'] == 'Iraq'){$sigla['sigla'] = 'IQ';}
            if($value['country'] == 'Ireland'){$sigla['sigla'] = 'IE';}
            if($value['country'] == 'Isle of Man'){$sigla['sigla'] = 'IM';}
            if($value['country'] == 'Israel'){$sigla['sigla'] = 'IL';}
            if($value['country'] == 'Italy'){$sigla['sigla'] = 'IT';}
            if($value['country'] == 'Jamaica'){$sigla['sigla'] = 'JM';}
            if($value['country'] == 'Japan'){$sigla['sigla'] = 'JP';}
            if($value['country'] == 'Jersey'){$sigla['sigla'] = 'JE';}
            if($value['country'] == 'Jordan'){$sigla['sigla'] = 'JO';}
            if($value['country'] == 'Kazakhstan'){$sigla['sigla'] = 'KZ';}
            if($value['country'] == 'Kenya'){$sigla['sigla'] = 'KE';}
            if($value['country'] == 'Kiribati'){$sigla['sigla'] = 'KI';}
            if($value['country'] == 'Korea, DemocraticPeoples Republic of'){$sigla['sigla'] = 'KP';}
            if($value['country'] == 'Korea, Republic of'){$sigla['sigla'] = 'KR';}
            if($value['country'] == 'Kosovo'){$sigla['sigla'] = 'XK';}
            if($value['country'] == 'Kuwait'){$sigla['sigla'] = 'KW';}
            if($value['country'] == 'Kyrgyzstan'){$sigla['sigla'] = 'KG';}
            if($value['country'] == 'LaoPeoples DemocraticRepublic'){$sigla['sigla'] = 'LA';}
            if($value['country'] == 'Latvia'){$sigla['sigla'] = 'LV';}
            if($value['country'] == 'Lebanon'){$sigla['sigla'] = 'LB';}
            if($value['country'] == 'Lesotho'){$sigla['sigla'] = 'LS';}
            if($value['country'] == 'Liberia'){$sigla['sigla'] = 'LR';}
            if($value['country'] == 'Libyan Arab Jamahiriya'){$sigla['sigla'] = 'LY';}
            if($value['country'] == 'Liechtenstein'){$sigla['sigla'] = 'LI';}
            if($value['country'] == 'Lithuania'){$sigla['sigla'] = 'LT';}
            if($value['country'] == 'Luxembourg'){$sigla['sigla'] = 'LU';}
            if($value['country'] == 'Macao'){$sigla['sigla'] = 'MO';}
            if($value['country'] == 'Macedonia, the FormerYugoslav Republic of'){$sigla['sigla'] = 'MK';}
            if($value['country'] == 'Madagascar'){$sigla['sigla'] = 'MG';}
            if($value['country'] == 'Malawi'){$sigla['sigla'] = 'MW';}
            if($value['country'] == 'Malaysia'){$sigla['sigla'] = 'MY';}
            if($value['country'] == 'Maldives'){$sigla['sigla'] = 'MV';}
            if($value['country'] == 'Mali'){$sigla['sigla'] = 'ML';}
            if($value['country'] == 'Malta'){$sigla['sigla'] = 'MT';}
            if($value['country'] == 'Marshall Islands'){$sigla['sigla'] = 'MH';}
            if($value['country'] == 'Martinique'){$sigla['sigla'] = 'MQ';}
            if($value['country'] == 'Mauritania'){$sigla['sigla'] = 'MR';}
            if($value['country'] == 'Mauritius'){$sigla['sigla'] = 'MU';}
            if($value['country'] == 'Mayotte'){$sigla['sigla'] = 'YT';}
            if($value['country'] == 'Mexico'){$sigla['sigla'] = 'MX';}
            if($value['country'] == 'Micronesia, FederatedStates of'){$sigla['sigla'] = 'FM';}
            if($value['country'] == 'Moldova, Republic of'){$sigla['sigla'] = 'MD';}
            if($value['country'] == 'Monaco'){$sigla['sigla'] = 'MC';}
            if($value['country'] == 'Mongolia'){$sigla['sigla'] = 'MN';}
            if($value['country'] == 'Montenegro'){$sigla['sigla'] = 'ME';}
            if($value['country'] == 'Montserrat'){$sigla['sigla'] = 'MS';}
            if($value['country'] == 'Morocco'){$sigla['sigla'] = 'MA';}
            if($value['country'] == 'Mozambique'){$sigla['sigla'] = 'MZ';}
            if($value['country'] == 'Myanmar'){$sigla['sigla'] = 'MM';}
            if($value['country'] == 'Namibia'){$sigla['sigla'] = 'NA';}
            if($value['country'] == 'Nauru'){$sigla['sigla'] = 'NR';}
            if($value['country'] == 'Nepal'){$sigla['sigla'] = 'NP';}
            if($value['country'] == 'Netherlands'){$sigla['sigla'] = 'NL';}
            if($value['country'] == 'Netherlands Antilles'){$sigla['sigla'] = 'AN';}
            if($value['country'] == 'NewCaledonia'){$sigla['sigla'] = 'NC';}
            if($value['country'] == 'NewZealand'){$sigla['sigla'] = 'NZ';}
            if($value['country'] == 'Nicaragua'){$sigla['sigla'] = 'NI';}
            if($value['country'] == 'Niger'){$sigla['sigla'] = 'NE';}
            if($value['country'] == 'Nigeria'){$sigla['sigla'] = 'NG';}
            if($value['country'] == 'Niue'){$sigla['sigla'] = 'NU';}
            if($value['country'] == 'Norfolk Island'){$sigla['sigla'] = 'NF';}
            if($value['country'] == 'Northern MarianaIslands'){$sigla['sigla'] = 'MP';}
            if($value['country'] == 'Norway'){$sigla['sigla'] = 'NO';}
            if($value['country'] == 'Oman'){$sigla['sigla'] = 'OM';}
            if($value['country'] == 'Pakistan'){$sigla['sigla'] = 'PK';}
            if($value['country'] == 'Palau'){$sigla['sigla'] = 'PW';}
            if($value['country'] == 'Palestinian Territory,Occupied'){$sigla['sigla'] = 'PS';}
            if($value['country'] == 'Panama'){$sigla['sigla'] = 'PA';}
            if($value['country'] == 'Papua New Guinea'){$sigla['sigla'] = 'PG';}
            if($value['country'] == 'Paraguay'){$sigla['sigla'] = 'PY';}
            if($value['country'] == 'Peru'){$sigla['sigla'] = 'PE';}
            if($value['country'] == 'Philippines'){$sigla['sigla'] = 'PH';}
            if($value['country'] == 'Pitcairn'){$sigla['sigla'] = 'PN';}
            if($value['country'] == 'Poland'){$sigla['sigla'] = 'PL';}
            if($value['country'] == 'Portugal'){$sigla['sigla'] = 'PT';}
            if($value['country'] == 'Puerto Rico'){$sigla['sigla'] = 'PR';}
            if($value['country'] == 'Qatar'){$sigla['sigla'] = 'QA';}
            if($value['country'] == 'Reunion'){$sigla['sigla'] = 'RE';}
            if($value['country'] == 'Romania'){$sigla['sigla'] = 'RO';}
            if($value['country'] == 'Russian Federation'){$sigla['sigla'] = 'RU';}
            if($value['country'] == 'Rwanda'){$sigla['sigla'] = 'RW';}
            if($value['country'] == 'Saint Barthelemy'){$sigla['sigla'] = 'BL';}
            if($value['country'] == 'Saint Helena'){$sigla['sigla'] = 'SH';}
            if($value['country'] == 'Saint Kitts and Nevis'){$sigla['sigla'] = 'KN';}
            if($value['country'] == 'Saint Lucia'){$sigla['sigla'] = 'LC';}
            if($value['country'] == 'Saint Martin'){$sigla['sigla'] = 'MF';}
            if($value['country'] == 'Saint Pierre andMiquelon'){$sigla['sigla'] = 'PM';}
            if($value['country'] == 'Saint Vincent and theGrenadines'){$sigla['sigla'] = 'VC';}
            if($value['country'] == 'Samoa'){$sigla['sigla'] = 'WS';}
            if($value['country'] == 'SanMarino'){$sigla['sigla'] = 'SM';}
            if($value['country'] == 'SaoTome and Principe'){$sigla['sigla'] = 'ST';}
            if($value['country'] == 'Saudi Arabia'){$sigla['sigla'] = 'SA';}
            if($value['country'] == 'Senegal'){$sigla['sigla'] = 'SN';}
            if($value['country'] == 'Serbia'){$sigla['sigla'] = 'RS';}
            if($value['country'] == 'Serbia and Montenegro'){$sigla['sigla'] = 'CS';}
            if($value['country'] == 'Seychelles'){$sigla['sigla'] = 'SC';}
            if($value['country'] == 'Sierra Leone'){$sigla['sigla'] = 'SL';}
            if($value['country'] == 'Singapore'){$sigla['sigla'] = 'SG';}
            if($value['country'] == 'Sint Maarten'){$sigla['sigla'] = 'SX';}
            if($value['country'] == 'Slovakia'){$sigla['sigla'] = 'SK';}
            if($value['country'] == 'Slovenia'){$sigla['sigla'] = 'SI';}
            if($value['country'] == 'Solomon Islands'){$sigla['sigla'] = 'SB';}
            if($value['country'] == 'Somalia'){$sigla['sigla'] = 'SO';}
            if($value['country'] == 'South Africa'){$sigla['sigla'] = 'ZA';}
            if($value['country'] == 'South Georgia and theSouth Sandwich Islands'){$sigla['sigla'] = 'GS';}
            if($value['country'] == 'South Sudan'){$sigla['sigla'] = 'SS';}
            if($value['country'] == 'Spain'){$sigla['sigla'] = 'ES';}
            if($value['country'] == 'SriLanka'){$sigla['sigla'] = 'LK';}
            if($value['country'] == 'Sudan'){$sigla['sigla'] = 'SD';}
            if($value['country'] == 'Suriname'){$sigla['sigla'] = 'SR';}
            if($value['country'] == 'Svalbard and Jan Mayen'){$sigla['sigla'] = 'SJ';}
            if($value['country'] == 'Swaziland'){$sigla['sigla'] = 'SZ';}
            if($value['country'] == 'Sweden'){$sigla['sigla'] = 'SE';}
            if($value['country'] == 'Switzerland'){$sigla['sigla'] = 'CH';}
            if($value['country'] == 'Syrian Arab Republic'){$sigla['sigla'] = 'SY';}
            if($value['country'] == 'Taiwan, Province ofChina'){$sigla['sigla'] = 'TW';}
            if($value['country'] == 'Tajikistan'){$sigla['sigla'] = 'TJ';}
            if($value['country'] == 'Tanzania, UnitedRepublic of'){$sigla['sigla'] = 'TZ';}
            if($value['country'] == 'Thailand'){$sigla['sigla'] = 'TH';}
            if($value['country'] == 'Timor-Leste'){$sigla['sigla'] = 'TL';}
            if($value['country'] == 'Togo'){$sigla['sigla'] = 'TG';}
            if($value['country'] == 'Tokelau'){$sigla['sigla'] = 'TK';}
            if($value['country'] == 'Tonga'){$sigla['sigla'] = 'TO';}
            if($value['country'] == 'Trinidad and Tobago'){$sigla['sigla'] = 'TT';}
            if($value['country'] == 'Tunisia'){$sigla['sigla'] = 'TN';}
            if($value['country'] == 'Turkey'){$sigla['sigla'] = 'TR';}
            if($value['country'] == 'Turkmenistan'){$sigla['sigla'] = 'TM';}
            if($value['country'] == 'Turks and CaicosIslands'){$sigla['sigla'] = 'TC';}
            if($value['country'] == 'Tuvalu'){$sigla['sigla'] = 'TV';}
            if($value['country'] == 'Uganda'){$sigla['sigla'] = 'UG';}
            if($value['country'] == 'Ukraine'){$sigla['sigla'] = 'UA';}
            if($value['country'] == 'United Arab Emirates'){$sigla['sigla'] = 'AE';}
            if($value['country'] == 'United Kingdom'){$sigla['sigla'] = 'GB';}
            if($value['country'] == 'United States of America'){$sigla['sigla'] = 'US';}
            if($value['country'] == 'Uruguay'){$sigla['sigla'] = 'UY';}
            if($value['country'] == 'Uzbekistan'){$sigla['sigla'] = 'UZ';}
            if($value['country'] == 'Vanuatu'){$sigla['sigla'] = 'VU';}
            if($value['country'] == 'Venezuela'){$sigla['sigla'] = 'VE';}
            if($value['country'] == 'Viet Nam'){$sigla['sigla'] = 'VN';}
            if($value['country'] == 'Virgin Islands, British'){$sigla['sigla'] = 'VG';}
            if($value['country'] == 'Virgin Islands, U.s.'){$sigla['sigla'] = 'VI';}
            if($value['country'] == 'Wallis and Futuna'){$sigla['sigla'] = 'WF';}
            if($value['country'] == 'Western Sahara'){$sigla['sigla'] = 'EH';}
            if($value['country'] == 'Yemen'){$sigla['sigla'] = 'YE';}
            if($value['country'] == 'Zambia'){$sigla['sigla'] = 'ZM';}
            if($value['country'] == 'Zimbabwe'){$sigla['sigla'] = 'ZW';}

        $labelsChart[$key] = $value['country'];
        $dataChart[$key]   = $value['amount'];

        $data[$key]=["country"=>$value['country'],
                     "amount" =>$value['amount'],
                     "flag"   =>"https://flagcdn.com/w20/".strtolower($sigla['sigla']).".jpg"];
    }




  // $data=array_reverse($data);


    return view('admin.reports.UsersByCountry', compact('data','labelsChart','dataChart'));

    }

    public function ListUserCountry($id)
    {
        $country = $id;

        $flag_country    = str_replace('_', ' ', $country);
        $explode_country = explode(' ', $flag_country);
        $count_explode   = count($explode_country);
        $name_coutry     = "";

        if ($count_explode >= 2){
            for ($i = 0; $i < $count_explode; $i++) {
                if ($explode_country[$i] != ""){

                    $flag_country = ucfirst($explode_country[$i]);

                    if ($i == 0){
                        $name_coutry = $flag_country;
                    }
                    else{
                        $name_coutry = $name_coutry." ".$flag_country;
                    }
                }
            }
        }
        else{
            $name_coutry = ucfirst($explode_country[0]);
        }

        $modal_country = User::where('country', $name_coutry)->get();



        $country_search = User::select('country',DB::raw('count(*) as amount'))
                        ->groupBy('country')
                        ->orderBy('amount','DESC')

                        ->get();


        foreach ($country_search as $key => $value) {

                $country_sigla = $value['country'];

                if($value['country'] == 'AlandIslands'){$sigla['sigla'] = 'AX';}
                if($value['country'] == 'Albania'){$sigla['sigla'] = 'AL';}
                if($value['country'] == 'Algeria'){$sigla['sigla'] = 'DZ';}
                if($value['country'] == 'AmericanSamoa'){$sigla['sigla'] = 'AS';}
                if($value['country'] == 'Andorra'){$sigla['sigla'] = 'AD';}
                if($value['country'] == 'Angola'){$sigla['sigla'] = 'AO';}
                if($value['country'] == 'Anguilla'){$sigla['sigla'] = 'AI';}
                if($value['country'] == 'Antarctica'){$sigla['sigla'] = 'AQ';}
                if($value['country'] == 'Antigua and Barbuda'){$sigla['sigla'] = 'AG';}
                if($value['country'] == 'Argentina'){$sigla['sigla'] = 'AR';}
                if($value['country'] == 'Armenia'){$sigla['sigla'] = 'AM';}
                if($value['country'] == 'Aruba'){$sigla['sigla'] = 'AW';}
                if($value['country'] == 'Australia'){$sigla['sigla'] = 'AU';}
                if($value['country'] == 'Austria'){$sigla['sigla'] = 'AT';}
                if($value['country'] == 'Azerbaijan'){$sigla['sigla'] = 'AZ';}
                if($value['country'] == 'Bahamas'){$sigla['sigla'] = 'BS';}
                if($value['country'] == 'Bahrain'){$sigla['sigla'] = 'BH';}
                if($value['country'] == 'Bangladesh'){$sigla['sigla'] = 'BD';}
                if($value['country'] == 'Barbados'){$sigla['sigla'] = 'BB';}
                if($value['country'] == 'Belarus'){$sigla['sigla'] = 'BY';}
                if($value['country'] == 'Belgium'){$sigla['sigla'] = 'BE';}
                if($value['country'] == 'Belize'){$sigla['sigla'] = 'BZ';}
                if($value['country'] == 'Benin'){$sigla['sigla'] = 'BJ';}
                if($value['country'] == 'Bermuda'){$sigla['sigla'] = 'BM';}
                if($value['country'] == 'Bhutan'){$sigla['sigla'] = 'BT';}
                if($value['country'] == 'Bolivia'){$sigla['sigla'] = 'BO';}
                if($value['country'] == 'Bonaire, Sint Eustatiusand Saba'){$sigla['sigla'] = 'BQ';}
                if($value['country'] == 'Bosniaand Herzegovina'){$sigla['sigla'] = 'BA';}
                if($value['country'] == 'Botswana'){$sigla['sigla'] = 'BW';}
                if($value['country'] == 'Bouvet Island'){$sigla['sigla'] = 'BV';}
                if($value['country'] == 'Brazil'){$sigla['sigla'] = 'BR';}
                if($value['country'] == 'brazil'){$sigla['sigla'] = 'BR';}
                if($value['country'] == 'British Indian OceanTerritory'){$sigla['sigla'] = 'IO';}
                if($value['country'] == 'Brunei Darussalam'){$sigla['sigla'] = 'BN';}
                if($value['country'] == 'Bulgaria'){$sigla['sigla'] = 'BG';}
                if($value['country'] == 'Burkina Faso'){$sigla['sigla'] = 'BF';}
                if($value['country'] == 'Burundi'){$sigla['sigla'] = 'BI';}
                if($value['country'] == 'Cambodia'){$sigla['sigla'] = 'KH';}
                if($value['country'] == 'Cameroon'){$sigla['sigla'] = 'CM';}
                if($value['country'] == 'Canada'){$sigla['sigla'] = 'CA';}
                if($value['country'] == 'Cape Verde'){$sigla['sigla'] = 'CV';}
                if($value['country'] == 'Cayman Islands'){$sigla['sigla'] = 'KY';}
                if($value['country'] == 'Central African Republic'){$sigla['sigla'] = 'CF';}
                if($value['country'] == 'Chad'){$sigla['sigla'] = 'TD';}
                if($value['country'] == 'Chile'){$sigla['sigla'] = 'CL';}
                if($value['country'] == 'China'){$sigla['sigla'] = 'CN';}
                if($value['country'] == 'Christmas Island'){$sigla['sigla'] = 'CX';}
                if($value['country'] == 'Cocos(Keeling) Islands'){$sigla['sigla'] = 'CC';}
                if($value['country'] == 'Colombia'){$sigla['sigla'] = 'CO';}
                if($value['country'] == 'Comoros'){$sigla['sigla'] = 'KM';}
                if($value['country'] == 'Congo'){$sigla['sigla'] = 'CG';}
                if($value['country'] == 'Congo, DemocraticRepublic of the Congo'){$sigla['sigla'] = 'CD';}
                if($value['country'] == 'Cook Islands'){$sigla['sigla'] = 'CK';}
                if($value['country'] == 'Costa Rica'){$sigla['sigla'] = 'CR';}
                if($value['country'] == 'Cote DIvoire'){$sigla['sigla'] = 'CI';}
                if($value['country'] == 'Croatia'){$sigla['sigla'] = 'HR';}
                if($value['country'] == 'Cuba'){$sigla['sigla'] = 'CU';}
                if($value['country'] == 'Curacao'){$sigla['sigla'] = 'CW';}
                if($value['country'] == 'Cyprus'){$sigla['sigla'] = 'CY';}
                if($value['country'] == 'Czech Republic'){$sigla['sigla'] = 'CZ';}
                if($value['country'] == 'Denmark'){$sigla['sigla'] = 'DK';}
                if($value['country'] == 'Djibouti'){$sigla['sigla'] = 'DJ';}
                if($value['country'] == 'Dominica'){$sigla['sigla'] = 'DM';}
                if($value['country'] == 'Dominican Republic'){$sigla['sigla'] = 'DO';}
                if($value['country'] == 'Ecuador'){$sigla['sigla'] = 'EC';}
                if($value['country'] == 'Egypt'){$sigla['sigla'] = 'EG';}
                if($value['country'] == 'El Salvador'){$sigla['sigla'] = 'SV';}
                if($value['country'] == 'Equatorial Guinea'){$sigla['sigla'] = 'GQ';}
                if($value['country'] == 'Eritrea'){$sigla['sigla'] = 'ER';}
                if($value['country'] == 'Estonia'){$sigla['sigla'] = 'EE';}
                if($value['country'] == 'Ethiopia'){$sigla['sigla'] = 'ET';}
                if($value['country'] == 'Falkland Islands(Malvinas)'){$sigla['sigla'] = 'FK';}
                if($value['country'] == 'Faroe Islands'){$sigla['sigla'] = 'FO';}
                if($value['country'] == 'Fiji'){$sigla['sigla'] = 'FJ';}
                if($value['country'] == 'Finland'){$sigla['sigla'] = 'FI';}
                if($value['country'] == 'France'){$sigla['sigla'] = 'FR';}
                if($value['country'] == 'French Guiana'){$sigla['sigla'] = 'GF';}
                if($value['country'] == 'French Polynesia'){$sigla['sigla'] = 'PF';}
                if($value['country'] == 'French SouthernTerritories'){$sigla['sigla'] = 'TF';}
                if($value['country'] == 'Gabon'){$sigla['sigla'] = 'GA';}
                if($value['country'] == 'Gambia'){$sigla['sigla'] = 'GM';}
                if($value['country'] == 'Georgia'){$sigla['sigla'] = 'GE';}
                if($value['country'] == 'Germany'){$sigla['sigla'] = 'DE';}
                if($value['country'] == 'Ghana'){$sigla['sigla'] = 'GH';}
                if($value['country'] == 'Gibraltar'){$sigla['sigla'] = 'GI';}
                if($value['country'] == 'Greece'){$sigla['sigla'] = 'GR';}
                if($value['country'] == 'Greenland'){$sigla['sigla'] = 'GL';}
                if($value['country'] == 'Grenada'){$sigla['sigla'] = 'GD';}
                if($value['country'] == 'Guadeloupe'){$sigla['sigla'] = 'GP';}
                if($value['country'] == 'Guam'){$sigla['sigla'] = 'GU';}
                if($value['country'] == 'Guatemala'){$sigla['sigla'] = 'GT';}
                if($value['country'] == 'Guernsey'){$sigla['sigla'] = 'GG';}
                if($value['country'] == 'Guinea'){$sigla['sigla'] = 'GN';}
                if($value['country'] == 'Guinea-Bissau'){$sigla['sigla'] = 'GW';}
                if($value['country'] == 'Guyana'){$sigla['sigla'] = 'GY';}
                if($value['country'] == 'Haiti'){$sigla['sigla'] = 'HT';}
                if($value['country'] == 'HeardIsland and McdonaldIslands'){$sigla['sigla'] = 'HM';}
                if($value['country'] == 'HolySee (Vatican CityState)'){$sigla['sigla'] = 'VA';}
                if($value['country'] == 'Honduras'){$sigla['sigla'] = 'HN';}
                if($value['country'] == 'Hong Kong'){$sigla['sigla'] = 'HK';}
                if($value['country'] == 'Hungary'){$sigla['sigla'] = 'HU';}
                if($value['country'] == 'Iceland'){$sigla['sigla'] = 'IS';}
                if($value['country'] == 'India'){$sigla['sigla'] = 'IN';}
                if($value['country'] == 'Indonesia'){$sigla['sigla'] = 'ID';}
                if($value['country'] == 'Iran, Islamic Republicof'){$sigla['sigla'] = 'IR';}
                if($value['country'] == 'Iraq'){$sigla['sigla'] = 'IQ';}
                if($value['country'] == 'Ireland'){$sigla['sigla'] = 'IE';}
                if($value['country'] == 'Isle of Man'){$sigla['sigla'] = 'IM';}
                if($value['country'] == 'Israel'){$sigla['sigla'] = 'IL';}
                if($value['country'] == 'Italy'){$sigla['sigla'] = 'IT';}
                if($value['country'] == 'Jamaica'){$sigla['sigla'] = 'JM';}
                if($value['country'] == 'Japan'){$sigla['sigla'] = 'JP';}
                if($value['country'] == 'Jersey'){$sigla['sigla'] = 'JE';}
                if($value['country'] == 'Jordan'){$sigla['sigla'] = 'JO';}
                if($value['country'] == 'Kazakhstan'){$sigla['sigla'] = 'KZ';}
                if($value['country'] == 'Kenya'){$sigla['sigla'] = 'KE';}
                if($value['country'] == 'Kiribati'){$sigla['sigla'] = 'KI';}
                if($value['country'] == 'Korea, DemocraticPeoples Republic of'){$sigla['sigla'] = 'KP';}
                if($value['country'] == 'Korea, Republic of'){$sigla['sigla'] = 'KR';}
                if($value['country'] == 'Kosovo'){$sigla['sigla'] = 'XK';}
                if($value['country'] == 'Kuwait'){$sigla['sigla'] = 'KW';}
                if($value['country'] == 'Kyrgyzstan'){$sigla['sigla'] = 'KG';}
                if($value['country'] == 'LaoPeoples DemocraticRepublic'){$sigla['sigla'] = 'LA';}
                if($value['country'] == 'Latvia'){$sigla['sigla'] = 'LV';}
                if($value['country'] == 'Lebanon'){$sigla['sigla'] = 'LB';}
                if($value['country'] == 'Lesotho'){$sigla['sigla'] = 'LS';}
                if($value['country'] == 'Liberia'){$sigla['sigla'] = 'LR';}
                if($value['country'] == 'Libyan Arab Jamahiriya'){$sigla['sigla'] = 'LY';}
                if($value['country'] == 'Liechtenstein'){$sigla['sigla'] = 'LI';}
                if($value['country'] == 'Lithuania'){$sigla['sigla'] = 'LT';}
                if($value['country'] == 'Luxembourg'){$sigla['sigla'] = 'LU';}
                if($value['country'] == 'Macao'){$sigla['sigla'] = 'MO';}
                if($value['country'] == 'Macedonia, the FormerYugoslav Republic of'){$sigla['sigla'] = 'MK';}
                if($value['country'] == 'Madagascar'){$sigla['sigla'] = 'MG';}
                if($value['country'] == 'Malawi'){$sigla['sigla'] = 'MW';}
                if($value['country'] == 'Malaysia'){$sigla['sigla'] = 'MY';}
                if($value['country'] == 'Maldives'){$sigla['sigla'] = 'MV';}
                if($value['country'] == 'Mali'){$sigla['sigla'] = 'ML';}
                if($value['country'] == 'Malta'){$sigla['sigla'] = 'MT';}
                if($value['country'] == 'Marshall Islands'){$sigla['sigla'] = 'MH';}
                if($value['country'] == 'Martinique'){$sigla['sigla'] = 'MQ';}
                if($value['country'] == 'Mauritania'){$sigla['sigla'] = 'MR';}
                if($value['country'] == 'Mauritius'){$sigla['sigla'] = 'MU';}
                if($value['country'] == 'Mayotte'){$sigla['sigla'] = 'YT';}
                if($value['country'] == 'Mexico'){$sigla['sigla'] = 'MX';}
                if($value['country'] == 'Micronesia, FederatedStates of'){$sigla['sigla'] = 'FM';}
                if($value['country'] == 'Moldova, Republic of'){$sigla['sigla'] = 'MD';}
                if($value['country'] == 'Monaco'){$sigla['sigla'] = 'MC';}
                if($value['country'] == 'Mongolia'){$sigla['sigla'] = 'MN';}
                if($value['country'] == 'Montenegro'){$sigla['sigla'] = 'ME';}
                if($value['country'] == 'Montserrat'){$sigla['sigla'] = 'MS';}
                if($value['country'] == 'Morocco'){$sigla['sigla'] = 'MA';}
                if($value['country'] == 'Mozambique'){$sigla['sigla'] = 'MZ';}
                if($value['country'] == 'Myanmar'){$sigla['sigla'] = 'MM';}
                if($value['country'] == 'Namibia'){$sigla['sigla'] = 'NA';}
                if($value['country'] == 'Nauru'){$sigla['sigla'] = 'NR';}
                if($value['country'] == 'Nepal'){$sigla['sigla'] = 'NP';}
                if($value['country'] == 'Netherlands'){$sigla['sigla'] = 'NL';}
                if($value['country'] == 'Netherlands Antilles'){$sigla['sigla'] = 'AN';}
                if($value['country'] == 'NewCaledonia'){$sigla['sigla'] = 'NC';}
                if($value['country'] == 'NewZealand'){$sigla['sigla'] = 'NZ';}
                if($value['country'] == 'Nicaragua'){$sigla['sigla'] = 'NI';}
                if($value['country'] == 'Niger'){$sigla['sigla'] = 'NE';}
                if($value['country'] == 'Nigeria'){$sigla['sigla'] = 'NG';}
                if($value['country'] == 'Niue'){$sigla['sigla'] = 'NU';}
                if($value['country'] == 'Norfolk Island'){$sigla['sigla'] = 'NF';}
                if($value['country'] == 'Northern MarianaIslands'){$sigla['sigla'] = 'MP';}
                if($value['country'] == 'Norway'){$sigla['sigla'] = 'NO';}
                if($value['country'] == 'Oman'){$sigla['sigla'] = 'OM';}
                if($value['country'] == 'Pakistan'){$sigla['sigla'] = 'PK';}
                if($value['country'] == 'Palau'){$sigla['sigla'] = 'PW';}
                if($value['country'] == 'Palestinian Territory,Occupied'){$sigla['sigla'] = 'PS';}
                if($value['country'] == 'Panama'){$sigla['sigla'] = 'PA';}
                if($value['country'] == 'Papua New Guinea'){$sigla['sigla'] = 'PG';}
                if($value['country'] == 'Paraguay'){$sigla['sigla'] = 'PY';}
                if($value['country'] == 'Peru'){$sigla['sigla'] = 'PE';}
                if($value['country'] == 'Philippines'){$sigla['sigla'] = 'PH';}
                if($value['country'] == 'Pitcairn'){$sigla['sigla'] = 'PN';}
                if($value['country'] == 'Poland'){$sigla['sigla'] = 'PL';}
                if($value['country'] == 'Portugal'){$sigla['sigla'] = 'PT';}
                if($value['country'] == 'Puerto Rico'){$sigla['sigla'] = 'PR';}
                if($value['country'] == 'Qatar'){$sigla['sigla'] = 'QA';}
                if($value['country'] == 'Reunion'){$sigla['sigla'] = 'RE';}
                if($value['country'] == 'Romania'){$sigla['sigla'] = 'RO';}
                if($value['country'] == 'Russian Federation'){$sigla['sigla'] = 'RU';}
                if($value['country'] == 'Rwanda'){$sigla['sigla'] = 'RW';}
                if($value['country'] == 'Saint Barthelemy'){$sigla['sigla'] = 'BL';}
                if($value['country'] == 'Saint Helena'){$sigla['sigla'] = 'SH';}
                if($value['country'] == 'Saint Kitts and Nevis'){$sigla['sigla'] = 'KN';}
                if($value['country'] == 'Saint Lucia'){$sigla['sigla'] = 'LC';}
                if($value['country'] == 'Saint Martin'){$sigla['sigla'] = 'MF';}
                if($value['country'] == 'Saint Pierre andMiquelon'){$sigla['sigla'] = 'PM';}
                if($value['country'] == 'Saint Vincent and theGrenadines'){$sigla['sigla'] = 'VC';}
                if($value['country'] == 'Samoa'){$sigla['sigla'] = 'WS';}
                if($value['country'] == 'SanMarino'){$sigla['sigla'] = 'SM';}
                if($value['country'] == 'SaoTome and Principe'){$sigla['sigla'] = 'ST';}
                if($value['country'] == 'Saudi Arabia'){$sigla['sigla'] = 'SA';}
                if($value['country'] == 'Senegal'){$sigla['sigla'] = 'SN';}
                if($value['country'] == 'Serbia'){$sigla['sigla'] = 'RS';}
                if($value['country'] == 'Serbia and Montenegro'){$sigla['sigla'] = 'CS';}
                if($value['country'] == 'Seychelles'){$sigla['sigla'] = 'SC';}
                if($value['country'] == 'Sierra Leone'){$sigla['sigla'] = 'SL';}
                if($value['country'] == 'Singapore'){$sigla['sigla'] = 'SG';}
                if($value['country'] == 'Sint Maarten'){$sigla['sigla'] = 'SX';}
                if($value['country'] == 'Slovakia'){$sigla['sigla'] = 'SK';}
                if($value['country'] == 'Slovenia'){$sigla['sigla'] = 'SI';}
                if($value['country'] == 'Solomon Islands'){$sigla['sigla'] = 'SB';}
                if($value['country'] == 'Somalia'){$sigla['sigla'] = 'SO';}
                if($value['country'] == 'South Africa'){$sigla['sigla'] = 'ZA';}
                if($value['country'] == 'South Georgia and theSouth Sandwich Islands'){$sigla['sigla'] = 'GS';}
                if($value['country'] == 'South Sudan'){$sigla['sigla'] = 'SS';}
                if($value['country'] == 'Spain'){$sigla['sigla'] = 'ES';}
                if($value['country'] == 'SriLanka'){$sigla['sigla'] = 'LK';}
                if($value['country'] == 'Sudan'){$sigla['sigla'] = 'SD';}
                if($value['country'] == 'Suriname'){$sigla['sigla'] = 'SR';}
                if($value['country'] == 'Svalbard and Jan Mayen'){$sigla['sigla'] = 'SJ';}
                if($value['country'] == 'Swaziland'){$sigla['sigla'] = 'SZ';}
                if($value['country'] == 'Sweden'){$sigla['sigla'] = 'SE';}
                if($value['country'] == 'Switzerland'){$sigla['sigla'] = 'CH';}
                if($value['country'] == 'Syrian Arab Republic'){$sigla['sigla'] = 'SY';}
                if($value['country'] == 'Taiwan, Province ofChina'){$sigla['sigla'] = 'TW';}
                if($value['country'] == 'Tajikistan'){$sigla['sigla'] = 'TJ';}
                if($value['country'] == 'Tanzania, UnitedRepublic of'){$sigla['sigla'] = 'TZ';}
                if($value['country'] == 'Thailand'){$sigla['sigla'] = 'TH';}
                if($value['country'] == 'Timor-Leste'){$sigla['sigla'] = 'TL';}
                if($value['country'] == 'Togo'){$sigla['sigla'] = 'TG';}
                if($value['country'] == 'Tokelau'){$sigla['sigla'] = 'TK';}
                if($value['country'] == 'Tonga'){$sigla['sigla'] = 'TO';}
                if($value['country'] == 'Trinidad and Tobago'){$sigla['sigla'] = 'TT';}
                if($value['country'] == 'Tunisia'){$sigla['sigla'] = 'TN';}
                if($value['country'] == 'Turkey'){$sigla['sigla'] = 'TR';}
                if($value['country'] == 'Turkmenistan'){$sigla['sigla'] = 'TM';}
                if($value['country'] == 'Turks and CaicosIslands'){$sigla['sigla'] = 'TC';}
                if($value['country'] == 'Tuvalu'){$sigla['sigla'] = 'TV';}
                if($value['country'] == 'Uganda'){$sigla['sigla'] = 'UG';}
                if($value['country'] == 'Ukraine'){$sigla['sigla'] = 'UA';}
                if($value['country'] == 'United Arab Emirates'){$sigla['sigla'] = 'AE';}
                if($value['country'] == 'United Kingdom'){$sigla['sigla'] = 'GB';}
                if($value['country'] == 'United States of America'){$sigla['sigla'] = 'US';}
                if($value['country'] == 'Uruguay'){$sigla['sigla'] = 'UY';}
                if($value['country'] == 'Uzbekistan'){$sigla['sigla'] = 'UZ';}
                if($value['country'] == 'Vanuatu'){$sigla['sigla'] = 'VU';}
                if($value['country'] == 'Venezuela'){$sigla['sigla'] = 'VE';}
                if($value['country'] == 'Viet Nam'){$sigla['sigla'] = 'VN';}
                if($value['country'] == 'Virgin Islands, British'){$sigla['sigla'] = 'VG';}
                if($value['country'] == 'Virgin Islands, U.s.'){$sigla['sigla'] = 'VI';}
                if($value['country'] == 'Wallis and Futuna'){$sigla['sigla'] = 'WF';}
                if($value['country'] == 'Western Sahara'){$sigla['sigla'] = 'EH';}
                if($value['country'] == 'Yemen'){$sigla['sigla'] = 'YE';}
                if($value['country'] == 'Zambia'){$sigla['sigla'] = 'ZM';}
                if($value['country'] == 'Zimbabwe'){$sigla['sigla'] = 'ZW';}

            $labelsChart[$key] = $value['country'];
            $dataChart[$key]   = $value['amount'];

            $data[$key]=["country"=>$value['country'],
                        "amount" =>$value['amount'],
                        "flag"   =>"https://flagcdn.com/w20/".strtolower($sigla['sigla']).".jpg"];
        }

        return view('admin.reports.UsersByCountry', compact('data','labelsChart','dataChart', 'modal_country', 'name_coutry'));
    }

}
