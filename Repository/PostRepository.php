<?php

namespace Parabol\AdminCoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Parabol\BaseBundle\Entity\Base\BaseRepository
{
	

	public function allByLocale($locale)
	{
		$qb = $this->createQueryBuilder('p')
                ->leftJoin('p.translations', 'pt')
                ->where('p.isEnabled = 1')
                ->andWhere('pt.locale = :locale')
                ->andWhere('(p.displayFrom IS NULL OR p.displayFrom <= CURRENT_TIMESTAMP()) AND (p.displayTo IS NULL OR CURRENT_TIMESTAMP() >= p.displayTo)')
                ->setParameter(':locale', $locale)
                ->orderBy('p.displayFrom', 'DESC')
                ->addOrderBy('p.createdAt', 'DESC');

        return $qb;        
	}

	public function allByTypeAndLocale($type, $locale)
	{
		$qb = $this->allByLocale($locale)
                ->andWhere('p.type = :type')
                ->setParameters(':type', $type)
                ;

        return $qb;        
	}

	public function lastByTypeAndLocale($type, $locale)
	{
		$qb = $this->allByTypeAndLocale($type, $locale)->setMaxResults(1);

        return $qb;            
	}

	public function findOne($slug, $type, $locale)
	{
		return $this->allByTypeAndLocale($type, $locale)->setMaxResults(1)
				->andWhere('pt.slug = :slug')
				->setParameter(':slug', $slug)
				->getQuery()
				->getOneOrNullResult();

        
	}

	public function findLastByTypeAndLocale($type, $locale)
	{
		return $this->lastByTypeAndLocale($type, $locale)->getQuery()->getOneOrNullResult();
	}
	
	

}
