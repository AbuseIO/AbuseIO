$(document).ready(function() {
    $('#first_ip').change(function () {
        if ($('#first_ip').val().indexOf("/") > -1) {
            // CIDR format found
            if ($('#first_ip').val().indexOf(":") > -1) {   // IPv6
                var ip_range = cidr6ToRange($('#first_ip').val());
            } else if ($('#first_ip').val().indexOf(".") > -1) {    // IPv4
                var ip_range = cidr4ToRange($('#first_ip').val());
            }
            if (ip_range != false) {
                $('#first_ip').val(ip_range[0]);
                $('#last_ip').val(ip_range[1]);
            }
        }
    });
});

function ip2long(IP) {
    // discuss at: http://phpjs.org/functions/ip2long/
    // original by: Waldo Malqui Silva (http://waldo.malqui.info)

    var i = 0;
    // PHP allows decimal, octal, and hexadecimal IP components.
    // PHP allows between 1 (e.g. 127) to 4 (e.g 127.0.0.1) components.
    IP = IP.match(
        /^([1-9]\d*|0[0-7]*|0x[\da-f]+)(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?$/i
    ); // Verify IP format.
    if (!IP) {
        // Invalid format.
        return false;
    }
    // Reuse IP variable for component counter.
    IP[0] = 0;
    for (i = 1; i < 5; i += 1) {
        IP[0] += !!((IP[i] || '').length);
        IP[i] = parseInt(IP[i]) || 0;
    }
    // Continue to use IP for overflow values.
    // PHP does not allow any component to overflow.
    IP.push(256, 256, 256, 256);
    // Recalculate overflow of last component supplied to make up for missing components.
    IP[4 + IP[0]] *= Math.pow(256, 4 - IP[0]);
    if (IP[1] >= IP[5] || IP[2] >= IP[6] || IP[3] >= IP[7] || IP[4] >= IP[8]) {
        return false;
    }
    return IP[1] * (IP[0] === 1 || 16777216) + IP[2] * (IP[0] <= 2 || 65536) + IP[3] * (IP[0] <= 3 || 256) + IP[4] * 1;
}

function long2ip(ip) {
    if (!isFinite(ip))
        return false;
    return [ip >>> 24, ip >>> 16 & 0xFF, ip >>> 8 & 0xFF, ip & 0xFF].join('.');
}

function createMask(maskbits) {
    var mask = '';
    for (i=1; i <= 128; i++) {
        mask += (i <= maskbits) ? '1' : '0';
    }
    return mask.match(/.{16}/g);
}

function createInvertedMask(maskbits) {
    var mask = '';
    for (i=1; i <= 128; i++) {
        mask += (i <= maskbits) ? '0' : '1';
    }
    return mask.match(/.{16}/g);
}

function convertHexToBin(ip) {
    parts = ip.split(':');

    parts.forEach(function (element, index, array) {
        array[index] = lzero16(parseInt(element, 16).toString(2));
    });
    return parts;
}

function convertBinToHex(ip) {
    parts = ip.split(':');

    parts.forEach(function (element, index, array) {
        array[index] = parseInt(element, 2).toString(16);
    });
    return parts;
}

function convertToFullIp(ip) {
    var i = 0;
    var parts = ip.split(':').length;

    do{
        ip = (parts == 8) ? ip.replace('::', ':0000:') : ip.replace('::', ':0000::');
        parts = ip.split(':').length;

        // Failsave
        i++; if (i >= 20) break;
    } while (parts <= 8);

    return ip;
}

function isValidIp4(ipaddress)
{
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
        return true;
    }
    return false;
}

function isValidIp6(ipaddress) {
    if(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/.test(ipaddress)) {
        return true;
    }
    return false;
}

function lzero16(text) { return "0000000000000000".substr(text.length) + text; }

function cidr4ToRange(cidr) {
    var range = [2];
    cidr = cidr.split('/');
    var cidr_1 = parseInt(cidr[1])
    if (isValidIp4(cidr[0])) {
        range[0] = long2ip((ip2long(cidr[0])) & ((-1 << (32 - cidr_1))));
        start = ip2long(range[0])
        range[1] = long2ip( start + Math.pow(2, (32 - cidr_1)) - 1);
        return range;
    } else {
        return false;
    }
}

function cidr6ToRange(cidr) {
    var range = [2];
    cidr = cidr.split('/');
    var maskbits = parseInt(cidr[1])
    if (isValidIp6(cidr[0])) {
        var ip = convertHexToBin(convertToFullIp(cidr[0]));
        mask = createMask(maskbits);
        invertedMark = createInvertedMask(maskbits);

        first_ip = [];
        last_ip = [];
        ip.forEach(function(element, index) {
            first_ip[index] = lzero16((parseInt(element, 2) & parseInt(mask[index], 2)).toString(2));
            last_ip[index] = lzero16((parseInt(element, 2) | parseInt(invertedMark[index], 2)).toString(2));
        });
        range[0] = convertBinToHex(first_ip.join(':')).join(':');
        range[1] = convertBinToHex(last_ip.join(':')).join(':');
        return range;
    } else {
        return false;
    }
}
