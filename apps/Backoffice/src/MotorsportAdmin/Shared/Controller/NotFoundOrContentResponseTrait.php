<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Shared\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait NotFoundOrContentResponseTrait
{
    protected function notFoundOrContent(mixed $content): JsonResponse
    {
        if (null === $content) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($content->data());
    }
}
