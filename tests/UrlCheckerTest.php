<?php

namespace tests;

use Sabre\Uri as Uri;

class UrlCheckerTest extends TestCase
{
    /**
     * @param $url
     * @param $result
     *
     * @dataProvider getNormalizerDataSet
     */
    public function testNormalizeUrl($url, $result)
    {
        $this->assertEquals(Uri\normalize($url), $result);
    }

    public function getNormalizerDataSet()
    {
        return [
            ['http://www.example.com', 'http://www.example.com/'],
            ['https://WWW.examPle.com', 'https://www.example.com/'],
            ['http://www.example.com/a/b/c/d', 'http://www.example.com/a/b/c/d'],
            ['HTTPS://abuseio.examPle.com', 'https://abuseio.example.com/'],
        ];
    }

    /**
     * @param $url
     * @param $relativeUrl
     * @param $result
     *
     * @dataProvider getResolverDataset
     */
    public function testResolveUrl($basePath, $relativeUrl, $result)
    {
        $this->assertEquals(
            Uri\resolve($basePath, $relativeUrl),
            $result
        );
    }

    public function getResolverDataset()
    {
        return [
            ['http://www.example.com', '/api', 'http://www.example.com/api'],
            ['https://WWW.examPle.com', '/api/', 'https://WWW.examPle.com/api/'],
            ['https://WWW.examPle.com/hier/', '../api/', 'https://WWW.examPle.com/api/'],
        ];
    }
}
