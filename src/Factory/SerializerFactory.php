<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class SerializerFactory
{
    public function createSerializer(): SerializerInterface
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }
}
