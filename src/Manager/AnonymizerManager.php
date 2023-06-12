<?php

namespace App\Manager;

use App\Annotation\Anonymize;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionProperty;


class AnonymizerManager
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function anonymize(): void
    {
        $entities = $this->em->getMetadataFactory()->getAllMetadata();

        foreach ($entities as $entity) {
            $className = $entity->getName();
            $class = new ReflectionClass($className);

            foreach ($class->getProperties() as $property) {
                try {
                    $annotations = $this->getAnonymizePropertyAnnotation($property);

                    $anonymizeValue = $this->getRandomValue($annotations->type);
                    $this->updatePropertyValues($class, $property, $anonymizeValue);
                } catch (\Exception $exception){}
            }
        }

        $this->em->flush();
    }

    /**
     * @throws \Exception
     */
    private function getAnonymizePropertyAnnotation(ReflectionProperty $property): Anonymize
    {
        $anonymizeAnnotations = $property->getAttributes(Anonymize::class);

        if (count($anonymizeAnnotations) === 0) {
            throw new \Exception('Anonymize Annotation not found on ' . $property->getName());
        }

        return $anonymizeAnnotations[0]->newInstance();
    }

    private function updatePropertyValues(ReflectionClass $class, ReflectionProperty $property, mixed $value): void
    {
        $entities = $this->em->getRepository($class->getName())->findAll();
        foreach ($entities as $entity) {
            $property->setValue($entity, $value);
        }
    }

    public function getRandomValue(string $type): mixed
    {
        return match ($type) {
            'email' => $this->randomString() . '@anonymise.com',
            'phone' => $this->randomPhone(),
            'int' => rand(1,100),
            default => $this->randomString(),
        };
    }
    protected function randomPhone(): string
    {
        return $this->randomStringFromCharList('0123456789');
    }

    protected function randomString(): string
    {
        return $this->randomStringFromCharList('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }

    protected function randomStringFromCharList(string $characters): string
    {
        $randomString = '';
        $n = 10;
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}
