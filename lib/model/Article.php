<?php

class Article extends BaseArticle
{
	/**
	 * Article title.
	 * 
	 * @return String
	 */
	public function __toString() {
		return $this->getTitle();
	}
	
	/**
	 * Get the number of votes for this article.
	 * 
	 * @param bool $promote true for Promotions, false for demotions.
	 * @return int
	 */
	private function countVotesWithPromote($promote, PropelPDO $con = null) {
		$c = new Criteria();
		$c->add(VotePeer::PROMOTE, $promote);
		
		return $this->countVotes($c, false, $con);
	}
	
	
	/**
	 * Get the number of promotions for this article.
	 * 
	 * @return void
	 */
	public function countPromotions(PropelPDO $con = null) {
		return $this->countVotesWithPromote(true, $con);
	}
	
	/**
	 * Get the number of demotions for this article.
	 * 
	 * @return void
	 */
	public function countDemotions(PropelPDO $con = null) {
		return $this->countVotesWithPromote(false, $con);
	}
}

$columns_map = array('from'   => ArticlePeer::TITLE,
  'to'     => ArticlePeer::SLUG);
 
sfPropelBehavior::add('Article', array('sfPropelActAsSluggableBehavior' =>
  array('columns' => $columns_map, 'separator' => '-', 'permanent' => true)));
sfPropelBehavior::add('Article', array('sfPropelActAsCommentableBehavior'));