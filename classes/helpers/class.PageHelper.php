<?php

class Page
{
    private $page;
    private $pageSize;
    private $urlHelper;

    public function __construct()
    {
        $this->urlHelper = HelperFactory::getUrlHelper();
        $this -> pageSize =  $this->urlHelper->getValue('page_size');
    }

    public function setPage()
    {
        $this->page = $this->urlHelper->getValue('page');

        if (!$this->page || (int)$this->page <1 || (int)$this->page != $this->page ) {
            $this->page = YfjConst::beginPage;
        }

    }

    public function setPageSize()
    {
        $this->pageSize = $this->urlHelper->getValue('page_size');
        if (!$this->pageSize || (int)$this->pageSize != $this->pageSize) {
            $this->pageSize = YfjConst::productCategroiesPage;
        }
    }

    public function init()
    {
        $this->setPage();
        $this->setPageSize();
    }

    public function getPage()
    {
        if ((int)$this->page != $this->page) {
            $this->page = YfjConst::beginPage;
        }
        return $this->page;
    }

    public function getPageSize()
    {
        if ((int)$this->pageSize != $this->pageSize) {
            $this->pageSize = YfjConst::productCategroiesPage;
        }
        return $this->pageSize;
    }
   //如果有传sql就返回sql如果没传就返回limit字符串
    public function getLimit($sql='')
    {
        $beginRecord = ($this->page - 1) * $this->pageSize;
        $limitSql = ' limit '.$beginRecord.','. $this->pageSize;
        return $sql.$limitSql;
    }


}
