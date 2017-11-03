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

    'accepted'   => 'Πρέπει να αποδεχτείτε το :attribute.',
    'active_url' => 'Το :attribute δεν είναι έγκυρο URL.',
    'after'      => 'Η :attribute πρέπει να είναι μια ημερομηνία μετα την :date.',
    'alpha'      => 'Το :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash' => 'Το :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς και παύλες.',
    'alpha_num'  => 'Το :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array'      => 'Το :attribute πρέπει να είναι ένας πίνακας.',
    'before'     => 'Η :attribute πρέπει να είναι μια ημερομηνία πριν την :date.',
    'between'    => [
        'numeric' => 'Το :attribute πρέπει να είναι μεταξύ :min και :max.',
        'file'    => 'Το :attribute πρέπει να είναι μεταξύ :min και :max kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι μεταξύ :min και :max χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να είναι μεταξύ :min και :max αντικείμενα.',
    ],
    'boolean'        => 'The πεδίο :attribute πρέπει να είναι true ή false.',
    'confirmed'      => 'Η επιβεβαίωση του :attribute δεν ταιρίαζει.',
    'date'           => 'To :attribute δεν είναι έγκυρη ημερομηνία',
    'date_format'    => 'To :attribute δεν ταριάζει με το φορματ :format.',
    'different'      => 'Τα :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits'         => 'Το :attribute πρεπει να αποτελείται από :digits ψηφία.',
    'digits_between' => 'To :attribute πρέπει να είναι μεταξύ :min και :max ψηφία.',
    'email'          => 'To :attribute πρεπει να είναι μια έγκυρη διεύθυνση email.',
    'filled'         => 'Το πεδίο :attribute είναι απαραίτητο.',
    'exists'         => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'image'          => 'Το :attribute πρέπει να είναι μια εικόνα.',
    'in'             => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'integer'        => 'Το :attribute πρέπει να είναι ένας ακέραιος.',
    'ip'             => 'To :attribute πρέπει να είναι μια έγκυρη IP διεύθυνση.',
    'max'            => [
        'numeric' => 'To :attribute δεν μπορεί να είναι μεγαλύτερο από :max.',
        'file'    => 'To :attribute δεν μπορεί να είναι μεγαλύτερο από :max kilobytes.',
        'string'  => 'To :attribute δεν μπορεί να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array'   => 'To :attribute δεν μπορεί να είναι μεγαλύτερο από :max αντικείμενα.',
    ],
    'mimes' => 'To :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min'   => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον :min.',
        'file'    => 'Tο :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string'  => 'Tο :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array'   => 'Tο :attribute πρέπει να έχει τουλάχιστον :min αντικείμενα.',
    ],
    'not_in'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'numeric'              => 'Tο :attribute πρέπει να είναι αριθμός.',
    'regex'                => 'Tο φορματ του :attribute δεν είναι έγκυρο.',
    'required'             => 'Το πεδίο :attribute είναι απαραίτητο.',
    'required_if'          => 'Το πεδίο :attribute είναι απαραίτητο όταν το :other είναι :value.',
    'required_with'        => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχει το :values.',
    'required_with_all'    => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχει το :values.',
    'required_without'     => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει το :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχει κανένα απο τα  :values.',
    'same'                 => 'Το πεδίο :attribute και το :other πρέπει να είναι ίδια.',
    'size'                 => [
        'numeric' => 'Tο :attribute πρέπει να είναι :size.',
        'file'    => 'Tο :attribute πρέπει να είναι :size kilobytes.',
        'string'  => 'Tο :attribute πρέπει να είναι :size χαρακτήρες.',
        'array'   => 'Tο :attribute πρέπει να περιέχει :size αντικείμενα.',
    ],
    'unique'   => 'Tο :attribute έχει ήδη χρησιμοποιηθεί.',
    'url'      => 'Tο φορμάτ του :attribute δεν είναι έγκυρο.',
    'timezone' => 'Tο :attribute πρέπει να είναι μια έγκυρη ζώνη ώρας.',

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
        'first_ip' => [
            'unique' => 'Ο συνδιασμός πρώτης και τελευταίας IP διεύθυνσης υπάρχει ήδη.',
        ],
        'last_ip' => [
            'unique' => 'Ο συνδιασμός πρώτης και τελευταίας IP διεύθυνσης υπάρχει ήδη.',
        ],
    ],
    'abuseclass'      => 'The :attribute πρέπει να είναι ένα γνωστό :attribute',
    'abusetype'       => 'The :attribute πρέπει να είναι ένα γνωστό :attribute',
    'json'            => 'To πεδίο :attribute δεν μπορεί να μετασχηματιστεί σε έγκυρο JSON αντικείμενο',
    'uri'             => 'Tο πεδίο :attribute πρέπει να είναι ένα έγκυρο URI ή Path',
    'uniqueflag'      => 'Tο :attribute flag έχει ήδη προστεθεί σε κάποιο άλλο αντικείμενο',
    'domain'          => 'Tο πεδίο :attribute πρέπει να είναι ένα έγκυρο domain name',
    'string'          => 'Tο πεδίο :attribute πρέπει να είναι τύπου string',
    'stringorboolean' => 'Tο πεδίο :attribute πρέπει να είναι τύπου string ή boolean',
    'file'            => 'Tο πεδίο :attribute πρέπει να περιέχει μια έγκυρη τοποθεσία αρχείου',
    'emails'          => 'Όλες οι :attribute διευθύνσεις πρέπει να είναι έγκυρες.',
    'timestamp'       => 'Το πεδίο :attribute πρέπει να περιέχει ένα έγκυρος ακέραιος αριθμός τύπου timestamp',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
