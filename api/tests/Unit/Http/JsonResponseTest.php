<?php
declare(strict_types=1);

namespace Test\Unit\Http;

use App\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    public function testInt(): void
    {
        $response = new JsonResponse(12);

        self::assertEquals('12', $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testString(): void
    {
        $response = new JsonResponse('12');

        self::assertEquals('"12"', $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testIntWithCode(): void
    {
        $response = new JsonResponse(12, 201);

        self::assertEquals('12', $response->getBody()->getContents());
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testNull(): void
    {
        $response = new JsonResponse(null);

        self::assertEquals('null', $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testObject(): void
    {
        $object = new \StdClass();
        $object->str = 'value';
        $object->int = 1;
        $object->none = null;

        $response = new JsonResponse($object, 200);

        self::assertEquals('{"str":"value","int":1,"none":null}', $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testArray(): void
    {
        $array = ['str'=>'value', 'int'=>1, 'none'=>null];

        $response = new JsonResponse($array, 200);

        self::assertEquals('{"str":"value","int":1,"none":null}', $response->getBody()->getContents());
        self::assertEquals(200, $response->getStatusCode());
    }
}