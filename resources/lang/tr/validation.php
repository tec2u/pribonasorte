<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':attribute, :other :value olduğunda kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL değil.',
    'after' => ':attribute, :date den sonra bir tarih olmalıdır.',
    'after_or_equal' => ':attribute, :date tarihinden sonra veya ona eşit bir tarih olmalıdır.',
    'alpha' => ':attribute yalnızca harf içermelidir.',
    'alpha_dash' => ':attribute yalnızca harf, sayı, kısa çizgi ve alt çizgi içermelidir.',
    'alpha_num' => ':attribute yalnızca harf ve rakamlardan oluşmalıdır.',
    'array' => ':attribute bir dizi olmalıdır.',
    'before' => ':attribute, :date den önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute, :date den önce veya bu tarihe eşit bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute, :min ve :max arasında öğelere sahip olmalıdır.',
        'file' => ':attribute :min ve :max kilobayt arasında olmalıdır.',
        'numeric' => ':attribute, :min ve :max arasında olmalıdır.',
        'string' => ':attribute, :min ve :max karakterleri arasında olmalıdır.',
    ],
    'boolean' => ':attribute alanı doğru veya yanlış olmalıdır.',
    'confirmed' => ':attribute onayı eşleşmiyor.',
    'current_password' => 'Şifre yanlış.',
    'date' => ':attribute geçerli bir tarih değil.',
    'date_equals' => ':attribute, :date değerine eşit bir tarih olmalıdır.',
    'date_format' => ':attribute, :format biçimiyle eşleşmiyor.',
    'declined' => ':attribute reddedilmeli.',
    'declined_if' => ':attribute, :other :value olduğunda reddedilmelidir.',
    'different' => ':attribute ve :other farklı olmalıdır.',
    'digits' => ':attribute :digits basamak olmalıdır.',
    'digits_between' => ':attribute, :min ve :max rakamları arasında olmalıdır.',
    'dimensions' => ':attribute geçersiz resim boyutlarına sahip.',
    'distinct' => ':attribute alanında yinelenen bir değer var.',
    'doesnt_end_with' => ':attribute aşağıdakilerden biriyle bitemez: :values.',
    'doesnt_start_with' => ':attribute aşağıdakilerden biriyle başlamayabilir: :values.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => ':attribute aşağıdakilerden biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanı bir değere sahip olmalıdır.',
    'gt' => [
        'array' => ':attribute birden fazla :value öğesi içermelidir.',
        'file' => ':attribute :value kilobayttan büyük olmalıdır.',
        'numeric' => ':attribute, :value dan büyük olmalıdır.',
        'string' => ':attribute, :value karakterlerinden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute, :value öğelerini veya daha fazlasını içermelidir.',
        'file' => ':attribute, :value kilobayttan büyük veya bu değere eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük veya ona eşit olmalıdır.',
        'string' => ':attribute, :value karakterlerinden büyük veya bu karakterlere eşit olmalıdır.',
    ],
    'image' => ':attribute bir resim olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute bir tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON dizesi olmalıdır.',
    'lt' => [
        'array' => ':attribute, :value öğesinden daha azına sahip olmalıdır.',
        'file' => ':attribute :value kilobayttan küçük olmalıdır.',
        'numeric' => ':attribute, :value dan küçük olmalıdır.',
        'string' => ':attribute, :value karakterlerinden küçük olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, :value öğesinden fazlasına sahip olmamalıdır.',
        'file' => ':attribute, :value kilobayttan küçük veya bu değere eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük veya ona eşit olmalıdır.',
        'string' => ':attribute, :value karakterlerinden küçük veya bu karakterlere eşit olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute, :max tan fazla öğeye sahip olmamalıdır.',
        'file' => ':attribute :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute, :max değerinden büyük olmamalıdır.',
        'string' => ':attribute, :max karakterlerinden büyük olmamalıdır.',
    ],
    'max_digits' => ':attribute :max rakamdan fazla olmamalıdır.',
    'mimes' => ':attribute, :values ​​türünde bir dosya olmalıdır.',
    'mimetypes' => ':attribute, :values ​​türünde bir dosya olmalıdır.',
    'min' => [
        'array' => ':attribute en az :min öğeye sahip olmalıdır.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
    ],
    'min_digits' => ':attribute en az :min basamak içermelidir.',
    'multiple_of' => ':attribute, :value nun katı olmalıdır.',
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => ':attribute biçimi geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir büyük ve bir küçük harf içermelidir.',
        'numbers' => ':attribute en az bir sayı içermelidir.',
        'symbols' => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında göründü. Lütfen farklı bir :özellik seçin.',
    ],
    'present' => ':attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanı yasaktır.',
    'prohibited_if' => ':attribute alanı, :other :value olduğunda yasaktır.',
    'prohibited_unless' => ':attribute alanı, :other, :values ​​içinde olmadığı sürece yasaktır.',
    'prohibits' => ':attribute alanı, :other ın bulunmasını yasaklar.',
    'regex' => ':attribute biçimi geçersiz.',
    'required' => ':attribute alanı zorunludur.',
    'required_array_keys' => ':attribute alanı şu girdileri içermelidir: :değerler.',
    'required_if' => ':attribute alanı, :other :value olduğunda gereklidir.',
    'required_unless' => ':attribute alanı, :other :values ​​içinde olmadığı sürece gereklidir.',
    'required_with' => ':attribute alanı, :values ​​mevcut olduğunda gereklidir.',
    'required_with_all' => ':attribute alanı, :values ​​mevcut olduğunda gereklidir.',
    'required_without' => ':attribute alanı, :values ​​olmadığında gereklidir.',
    'required_without_all' => ':attribute alanı, hiçbir :values ​​mevcut olmadığında gereklidir.',
    'same' => ':attribute ve :other eşleşmelidir.',
    'size' => [
        'array' => ':attribute, :size öğelerini içermelidir.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'numeric' => ':attribute :size olmalıdır.',
        'string' => ':attribute, :size karakterlerinden oluşmalıdır.',
    ],
    'starts_with' => ':attribute aşağıdakilerden biriyle başlamalıdır: :values.',
    'string' => ':attribute bir dize olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'özel mesaj',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
