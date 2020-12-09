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

namespace tests\Happyr\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\Query\IndexBy;
use Happyr\DoctrineSpecification\Query\QueryModifier;
use PhpSpec\ObjectBehavior;

/**
 * @mixin IndexBy
 */
final class IndexBySpec extends ObjectBehavior
{
    private $field = 'the_field';

    private $alias = 'f';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->alias);
    }

    public function it_is_a_result_modifier(): void
    {
        $this->shouldBeAnInstanceOf(QueryModifier::class);
    }

    public function it_indexes_with_default_dql_alias(QueryBuilder $qb): void
    {
        $this->beConstructedWith('something', 'x');
        $qb->indexBy('x', 'x.something')->shouldBeCalled();
        $this->modify($qb, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $qb): void
    {
        $this->beConstructedWith('thing');
        $qb->indexBy('b', 'b.thing')->shouldBeCalled();
        $this->modify($qb, 'b');
    }
}
