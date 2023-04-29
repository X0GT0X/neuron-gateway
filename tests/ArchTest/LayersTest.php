<?php

declare(strict_types=1);

namespace App\Tests\ArchTest;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class LayersTest
{
    public function testThat_DomainLayer_DoesNotHaveDependency_ToOtherLayers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Application'),
                Selector::namespace('App\Infrastructure'),
                Selector::namespace('App\UserInterface'),
            );
    }

    public function testThat_ApplicationLayer_DoesNotHaveDependency_ToInfrastructureAndUILayer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Infrastructure'),
                Selector::namespace('App\UserInterface'),
            );
    }

    public function testThat_InfrastructureLayer_DoesNotHaveDependency_ToUILayer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Infrastructure'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\UserInterface'),
            );
    }
}
