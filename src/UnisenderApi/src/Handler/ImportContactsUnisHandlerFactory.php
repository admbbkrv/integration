<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use AmoApiClient\Handler\AbstractApiHandlerFactory;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use Psr\Container\ContainerInterface;
use UnisenderApi\Handler\Traits\GetUnisenderApiServiceTrait;
use UnisenderApi\Services\ImportContactsInterface;
use UnisenderApi\Services\PrepareForImportInterface;

class ImportContactsUnisHandlerFactory extends AbstractApiHandlerFactory
{
    use GetUnisenderApiServiceTrait;

    public function __invoke(ContainerInterface $container) : ImportContactsUnisHandler
    {
        return new ImportContactsUnisHandler(
            $this->getApiClient($container),
            $this->getUnisenderApi(),
            $container->get(GetAllContactsInterface::class),
            $container->get(FilterWithEmailInterface::class),
            $container->get(PrepareForImportInterface::class),
            $container->get(GetTokenInterface::class),
            $container->get(ImportContactsInterface::class)
        );
    }
}
