<?php
declare(strict_types=1);

/**
 * This file is part of the Happyr Doctrine Specification package.
 *
 * (c) Tobias Nyholm <tobias@happyr.com>
 *     Kacper Gunia <kacper@gunia.me>
 *     Peter Gribanov <info@peter-gribanov.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Happyr\DoctrineSpecification\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\Filter\Filter;
use Happyr\DoctrineSpecification\Filter\LessThan;
use PhpSpec\ObjectBehavior;
use tests\Happyr\DoctrineSpecification\Player;

/**
 * @mixin LessThan
 */
final class LessThanSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('age', 18, 'a');
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(Filter::class);
    }

    public function it_returns_comparison_object(QueryBuilder $qb, ArrayCollection $parameters): void
    {
        $qb->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $qb->setParameter('comparison_10', 18, null)->shouldBeCalled();

        $comparison = $this->getFilter($qb, 'a');

        $comparison->shouldReturn('a.age < :comparison_10');
    }

    public function it_uses_comparison_specific_dql_alias_if_passed(
        QueryBuilder $qb,
        ArrayCollection $parameters
    ): void {
        $this->beConstructedWith('age', 18, null);

        $qb->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $qb->setParameter('comparison_10', 18, null)->shouldBeCalled();

        $this->getFilter($qb, 'x')->shouldReturn('x.age < :comparison_10');
    }

    public function it_filter_array_collection(): void
    {
        $this->beConstructedWith('points', 2500, null);

        $players = [
            ['pseudo' => 'Joe',   'gender' => 'M', 'points' => 2500],
            ['pseudo' => 'Moe',   'gender' => 'M', 'points' => 1230],
            ['pseudo' => 'Alice', 'gender' => 'F', 'points' => 9001],
        ];

        $this->filterCollection($players)->shouldYield([$players[1]]);
    }

    public function it_filter_object_collection(): void
    {
        $this->beConstructedWith('points', 2500, null);

        $players = [
            new Player('Joe',   'M', 2500),
            new Player('Moe',   'M', 1230),
            new Player('Alice', 'F', 9001),
        ];

        $this->filterCollection($players)->shouldYield([$players[1]]);
    }

    public function it_is_satisfied_with_array(): void
    {
        $this->beConstructedWith('points', 2500, null);

        $playerA = ['pseudo' => 'Joe',   'gender' => 'M', 'points' => 2500];
        $playerB = ['pseudo' => 'Moe',   'gender' => 'M', 'points' => 1230];
        $playerC = ['pseudo' => 'Alice', 'gender' => 'F', 'points' => 9001];

        $this->isSatisfiedBy($playerA)->shouldBe(false);
        $this->isSatisfiedBy($playerB)->shouldBe(true);
        $this->isSatisfiedBy($playerC)->shouldBe(false);
    }

    public function it_is_satisfied_with_object(): void
    {
        $this->beConstructedWith('points', 2500, null);

        $playerA = new Player('Joe',   'M', 2500);
        $playerB = new Player('Moe',   'M', 1230);
        $playerC = new Player('Alice', 'F', 9001);

        $this->isSatisfiedBy($playerA)->shouldBe(false);
        $this->isSatisfiedBy($playerB)->shouldBe(true);
        $this->isSatisfiedBy($playerC)->shouldBe(false);
    }
}
