<?php
declare(strict_types=1);

namespace ShlinkioTest\Shlink\Common\IpGeolocation;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\City;
use MaxMind\Db\Reader\InvalidDatabaseException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Shlinkio\Shlink\Common\Exception\WrongIpException;
use Shlinkio\Shlink\Common\IpGeolocation\GeoLite2LocationResolver;

class GeoLite2LocationResolverTest extends TestCase
{
    /** @var GeoLite2LocationResolver */
    private $resolver;
    /** @var ObjectProphecy */
    private $reader;

    public function setUp()
    {
        $this->reader = $this->prophesize(Reader::class);
        $this->resolver = new GeoLite2LocationResolver($this->reader->reveal());
    }

    /**
     * @test
     * @dataProvider provideReaderExceptions
     */
    public function exceptionIsThrownIfReaderThrowsException(string $e, string $message)
    {
        $ipAddress = '1.2.3.4';

        $cityMethod = $this->reader->city($ipAddress)->willThrow($e);

        $this->expectException(WrongIpException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(0);
        $cityMethod->shouldBeCalledOnce();

        $this->resolver->resolveIpLocation($ipAddress);
    }

    public function provideReaderExceptions(): array
    {
        return [
            [AddressNotFoundException::class, 'Provided IP "1.2.3.4" is invalid'],
            [InvalidDatabaseException::class, 'Provided GeoLite2 db file is invalid'],
        ];
    }

    /**
     * @test
     */
    public function resolvedCityIsProperlyMapped()
    {
        $ipAddress = '1.2.3.4';
        $city = new City([]);

        $cityMethod = $this->reader->city($ipAddress)->willReturn($city);

        $result = $this->resolver->resolveIpLocation($ipAddress);

        $this->assertEquals([
            'country_code' => '',
            'country_name' => '',
            'region_name' => '',
            'city' => '',
            'latitude' => '',
            'longitude' => '',
            'time_zone' => '',
        ], $result);
        $cityMethod->shouldHaveBeenCalledOnce();
    }
}
