<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TypePersons extends Enum
{
    public const CLIENT = 'client';
    public const TEACHER = 'teacher';
    public const STUDENT = 'student';
}
