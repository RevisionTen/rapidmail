<?php

namespace RevisionTen\Rapidmail;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RapidmailBundle extends Bundle
{
    public const VERSION = '0.0.2';

    public function boot()
    {
    }

    public function build(ContainerBuilder $container)
    {
    }
}
