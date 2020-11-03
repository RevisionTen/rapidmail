<?php

namespace RevisionTen\Rapidmail;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RapidmailBundle extends Bundle
{
    public const VERSION = '0.0.1';

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
    }
}
