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

namespace Happyr\DoctrineSpecification\Result;

use Doctrine\ORM\AbstractQuery;

final class ResultModifierCollection implements ResultModifier
{
    /**
     * @var ResultModifier[]
     */
    private $children;

    /**
     * Construct it with one or more instances of ResultModifier.
     *
     * @param ResultModifier ...$children
     */
    public function __construct(...$children)
    {
        $this->children = $children;
    }

    /**
     * @param AbstractQuery $query
     */
    public function modify(AbstractQuery $query): void
    {
        foreach ($this->children as $child) {
            $child->modify($query);
        }
    }
}
