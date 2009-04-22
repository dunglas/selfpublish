<?php

class ArticlePeer extends BaseArticlePeer
{
	/**
	 * Retrieve an article by its slug
	 *
	 * @param string $slug
	 * @param boolean $active
	 * @return Article
	 */
	public static function retrieveBySlug($slug, $active = true) {
		$c = new Criteria();
		$c->add(self::SLUG, $slug);
		if (!is_null($active)) $c->add(self::IS_ACTIVE, $active);
		
		return self::doSelectOne($c);
	}
	
	public static function mostRecent($page = 1, $nb = 10, $active = true) {
		$c = new Criteria();
		if (!is_null($active)) $c->add(self::IS_ACTIVE, $active);
		$c->addDescendingOrderByColumn(self::CREATED_AT);
		
		$pager = new sfPropelPager('Article', $nb);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();
    
    return $pager;
	}
	
	public static function mostVoted($start = null, $end = null, $promote = null, $page = 1, $nb = 10, $active = true) {
		/* SELECT article.id, COUNT( vote.id ) AS votes
		FROM article, vote
		WHERE vote.article_id = article.id
		GROUP BY article.id; */
		
		$c = new Criteria();
		if (!is_null($active)) $c->add(self::IS_ACTIVE, $active);
		
		if (!is_null($start) && !is_null($end)) {
			$criterion = $c->getNewCriterion(self::CREATED_AT, $start, Criteria::GREATER_EQUAL);
			$criterion->addAnd($c->getNewCriterion(self::CREATED_AT, $end, Criteria::LESS_EQUAL));
	    $c->add($criterion);
		}
		
    $c->addJoin(self::ID, VotePeer::ARTICLE_ID);
     
    if (!is_null($promote)) $c->addJoin(VotePeer::PROMOTE, $promote);
		
		$c->addGroupByColumn(self::ID);
		$c->addDescendingOrderByColumn('COUNT('.VotePeer::ID.')');
		
		$pager = new sfPropelPager('Article', $nb);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();
    
    return $pager;
	}
}