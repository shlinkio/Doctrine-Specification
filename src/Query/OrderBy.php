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

namespace Happyr\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\Operand\Alias;
use Happyr\DoctrineSpecification\Operand\Field;

class OrderBy implements QueryModifier
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';

    /**
     * @var Field|Alias
     */
    private $field;

    /**
     * @var string
     */
    private $order;

    /**
     * @var string|null
     */
    private $dqlAlias;

    /**
     * @param Field|Alias|string $field
     * @param string             $order
     * @param string|null        $dqlAlias
     */
    public function __construct($field, string $order = self::ASC, ?string $dqlAlias = null)
    {
        if (!($field instanceof Field) && !($field instanceof Alias)) {
            $field = new Field($field);
        }

        $this->field = $field;
        $this->order = $order;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $dqlAlias
     */
    public function modify(QueryBuilder $qb, string $dqlAlias): void
    {
        if (null !== $this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $qb->addOrderBy($this->field->transform($qb, $dqlAlias), $this->order);
    }
}
