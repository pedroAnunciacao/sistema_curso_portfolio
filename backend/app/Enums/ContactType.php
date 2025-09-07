<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContactType extends Enum
{
    public const Email = 1;
    public const Landline = 2;        // Telefone Fixo
    public const CommercialPhone = 3; // Telefone Comercial
    public const Cellphone = 4;       // Celular
}
