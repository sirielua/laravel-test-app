<?php

namespace App\domain\service\Contest\helpers;

use App\domain\service\Contest\dto\ContestDescriptionDto;
use App\domain\entities\Contest\Description;

class ContestDtoHelper
{
    public static function dto2Description(ContestDescriptionDto $dto)
    {
        return new Description(
            $dto->getLanguage(),
            $dto->getHeadline(),
            $dto->getSubheadline() ?? null,
            $dto->getExplainingText() ?? null,
            $dto->getBanner() ? new Banner($dto->getBanner()) : null
        );
    }
}
