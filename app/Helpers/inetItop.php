<?php

/**
 * @param string $decimal 128bit int
 *
 * @return string IPv4 or IPv6
 */
function inetItop($decimal)
{
    // QuickFix: Decimal 0 is both for ::0 and 0.0.0.0, however it defaults to IPv6, while there is now way a
    // ::/64 will ever be used.
    if ($decimal < 255) {
        return '0.0.0.'.$decimal;
    }

    $parts = [];

    if (function_exists('bcadd')) {
        // Use BCMath if available
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
        list($parts[1]) = $decimal->divide(new Math_BigInteger('79228162514264337593543950336'));
        $decimal = $decimal->subtract($parts[1]->multiply(new Math_BigInteger('79228162514264337593543950336')));
        list($parts[2]) = $decimal->divide(new Math_BigInteger('18446744073709551616'));
        $decimal = $decimal->subtract($parts[2]->multiply(new Math_BigInteger('18446744073709551616')));
        list($parts[3]) = $decimal->divide(new Math_BigInteger('4294967296'));
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
