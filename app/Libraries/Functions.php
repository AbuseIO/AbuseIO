<?php namespace AbuseIO;

class ICF
{

    /**
     * @param string $ip IPv4 or IPv6 address to convert
     * @return string 128bit string that can be used with DECIMNAL(39,0) or false
     */
    static public function inet_ptoi($ip)
    {

        // make sure it is an ip
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {

            return false;

        }

        $parts = unpack('N*', inet_pton($ip));

        // fix IPv4
        if (strpos($ip, '.') !== false) {

            $parts = array(1 => 0, 2 => 0, 3 => 0, 4 => $parts[1]);

        }

        foreach ($parts as &$part) {

            // convert any unsigned ints to signed from unpack.
            // this should be OK as it will be a PHP float not an int
            if ($part < 0) {

                $part = 4294967296;

            }

        }

        if (function_exists('bcadd')) {

            // Use BCMath if available

            $decimal = $parts[4];
            $decimal = bcadd($decimal, bcmul($parts[3], '4294967296'));
            $decimal = bcadd($decimal, bcmul($parts[2], '18446744073709551616'));
            $decimal = bcadd($decimal, bcmul($parts[1], '79228162514264337593543950336'));

        } else {

            // Otherwise use the pure PHP BigInteger class

            $decimal = new Math_BigInteger($parts[4]);
            $partTree = new Math_BigInteger($parts[3]);
            $partTwo = new Math_BigInteger($parts[2]);
            $partOne = new Math_BigInteger($parts[1]);

            $decimal = $decimal->add($partTree->multiply(new Math_BigInteger('4294967296')));
            $decimal = $decimal->add($partTwo->multiply(new Math_BigInteger('18446744073709551616')));
            $decimal = $decimal->add($partOne->multiply(new Math_BigInteger('79228162514264337593543950336')));

            $decimal = $decimal->toString();

        }

        return $decimal;
    }


    /**
     * @param string $decimal 128bit int
     * @return string IPv4 or IPv6
     */
    static public function inet_itop($decimal)
    {

        $parts = array();

        // Use BCMath if available
        if (function_exists('bcadd')) {

            $parts[1] = bcdiv($decimal, '79228162514264337593543950336', 0);
            $decimal = bcsub($decimal, bcmul($parts[1], '79228162514264337593543950336'));
            $parts[2] = bcdiv($decimal, '18446744073709551616', 0);
            $decimal = bcsub($decimal, bcmul($parts[2], '18446744073709551616'));
            $parts[3] = bcdiv($decimal, '4294967296', 0);
            $decimal = bcsub($decimal, bcmul($parts[3], '4294967296'));
            $parts[4] = $decimal;

        } else {

            // Otherwise use the pure PHP BigInteger class

            $decimal = new Math_BigInteger($decimal);
            list($parts[1],) = $decimal->divide(new Math_BigInteger('79228162514264337593543950336'));
            $decimal = $decimal->subtract($parts[1]->multiply(new Math_BigInteger('79228162514264337593543950336')));
            list($parts[2],) = $decimal->divide(new Math_BigInteger('18446744073709551616'));
            $decimal = $decimal->subtract($parts[2]->multiply(new Math_BigInteger('18446744073709551616')));
            list($parts[3],) = $decimal->divide(new Math_BigInteger('4294967296'));
            $decimal = $decimal->subtract($parts[3]->multiply(new Math_BigInteger('4294967296')));
            $parts[4] = $decimal;

            $parts[1] = $parts[1]->toString();
            $parts[2] = $parts[2]->toString();
            $parts[3] = $parts[3]->toString();
            $parts[4] = $parts[4]->toString();
        }

        foreach ($parts as &$part) {

            // convert any signed ints to unsigned for pack
            // this should be fine as it will be treated as a float
            if ($part > 2147483647) {

                $part -= 4294967296;

            }
        }

        $ip = inet_ntop(pack('N4', $parts[1], $parts[2], $parts[3], $parts[4]));

        // fix IPv4 by removing :: from the beginning
        if (strpos($ip, '.') !== false) {

            return substr($ip, 2);

        }

        return $ip;

    }

}