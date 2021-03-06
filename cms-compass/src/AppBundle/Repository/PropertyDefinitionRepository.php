<?php

/*
 * Copyright (C) 2017 <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\CMS;
use Doctrine\ORM\EntityRepository;

/**
 * Description of PorpertyDefinitionRepository
 *
 * @author <a href="mailto:jens.pelzetter@googlemail.com">Jens Pelzetter</a>
 */
class PropertyDefinitionRepository extends EntityRepository
{

    public function findPropertyDefinitionByName($name)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->where($queryBuilder->expr()->eq('p.name', ':name'))
                        ->setParameter('name', $name)
                        ->getQuery()
                        ->getOneOrNullResult();
    }

    public function filterPropertyDefinitionsByName($filter)
    {

        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->where($queryBuilder->expr()->like('p.name', ':name'))
                        ->orderBy('p.name', 'ASC')
                        ->setParameter('name', '%' . $filter . '%')
                        ->getQuery()
                        ->getResult();
    }

    public function findDefinitionsForRequiredProperties()
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->where($queryBuilder->expr()->eq('p.required', ':value'))
                        ->setParameter('value', true)
                        ->getQuery()
                        ->getResult();
    }

    public function findUnusedDefinitions(CMS $cms)
    {

        $usedDefs = array();
        foreach ($cms->getProperties() as $property) {
            array_push($usedDefs, $property->getPropertyDefinition());
        }

        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder
                        ->where($queryBuilder->expr()->notIn('p', $usedDefs))
                        ->getQuery()
                        ->getResult();
    }

}
