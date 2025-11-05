<?php

namespace tests;

use Sabre\Uri;

class UrlCheckerTest extends TestCase
{
    /**
     * Validate URI normalization across dataset.
     */
    public function testNormalizeUrl()
    {
        foreach ($this->getNormalizerDataSet() as [$url, $result]) {
            $this->assertEquals(Uri\normalize($url), $result);
        }
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
     * Validate URI resolution across dataset.
     */
    public function testResolveUrl()
    {
        foreach ($this->getResolverDataset() as [$basePath, $relativeUrl, $result]) {
            $this->assertEquals(
                Uri\resolve($basePath, $relativeUrl),
                $result
            );
        }
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
