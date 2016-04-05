<?php

/**
 * @param string $ip IPv4 or IPv6 address to convert
 *
 * @return string 128bit string that can be used with DECIMNAL(39,0) or false
 */
function inetPtoi($ip)
{
    // make sure it is an ip
    if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
        return false;
    }

    $parts = unpack('N*', inet_pton($ip));

    // fix IPv4
    if (strpos($ip, '.') !== false) {
        $parts = [1 => 0, 2 => 0, 3 => 0, 4 => $parts[1]];
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
