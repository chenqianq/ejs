<?php
class ArticleWidget extends ZcWidget {
    /**
     *
     * @var ArticleService
     */
    private $articleService;
	/*
	 * (non-PHPdoc) @see ZcWidget::render()
	 */
    public function __construct(){
        $this -> articleService = ServiceFactory::getArticleService();
    }
    
	public function render($renderData = '') {
	    
	    $article = $this -> getArticle();
	    $renderData['articleCategoriesArray'] = $article ? $article['articleCategoriesArray'] : false;
	    $renderData['articleArray'] = $article ? $article['articleArray'] : false;
		return $this->renderFile('common/article', $renderData);
	}
	
	private function getArticle(){
	    $articleCategoriesArray = $this -> articleService -> getArticleCategories();
	    if( !$articleCategoriesArray ){
	        return false;
	    }
	     
	    $acIdArray = array();
	    foreach ( $articleCategoriesArray as $acId => $articleCategories ){
	        $acIdArray[$acId] = $acId;
	    }
	     
	    $return = array('articleCategoriesArray' => $articleCategoriesArray,'articleArray' => array());
	     
	    $articleArray = $this -> articleService -> getArticleByCategoriesIds($acIdArray);
	    if( !$articleArray ){
	        return $return;
	    }
	     
	    $articleData = [];
	    foreach ( $articleArray as $articleId => $article ){
	        $acId = $article['ac_id'];
	         
	        $articleData[$acId][$articleId] = $article;
	        
	    }
	    
	    $return['articleArray'] = $articleData;
	    return $return;
	}
}
