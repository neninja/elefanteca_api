<?php

namespace App\Repositories\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Core\Models\Papel;

// https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/cookbook/custom-mapping-types.html
class PapelType extends Type
{
    const MYTYPE = 'papel'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // return the SQL used to create your column type. To create a portable column type, use the $platform.
        return "VARCHAR(15)";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is read from the database. Make your conversions here, optionally using the $platform.
        return new Papel($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // This is executed when the value is written to the database. Make your conversions here, optionally using the $platform.
        if (!$value instanceof Papel) {
            throw new \DomainException('Invalid type');
        }
        return $value->get();
    }

    public function getName()
    {
        return self::MYTYPE; // modify to match your constant name
    }
}
