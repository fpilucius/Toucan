<?php
namespace Toucan\Component\Pager;

use Toucan\Component\Pager\PagerIterator;

/**
 * @author Franck Pichot
 * @copyright 2012
 *
 * ROADMAP
 * - OBJECTIFS: 1 query -> gestion requete et array
 *         
 * 
 */


class Pager implements \Countable, \IteratorAggregate {

    protected $maxPerPage = 1;
    
    public $paging = 7;
    
    protected $styling = 'all';
    
    protected $stylingPager = array('all', 'elastic', 'sliding');

    public $pages = array();

    protected $offset = 0;

    protected $currentPage = null;

    protected $countData;

    protected $controller;

    protected $action;

    protected $query;

    protected $requete;
    
    public function __construct($query, $setCurrentpageNumber, array $options = null)
    {
        $this->currentPage = $setCurrentpageNumber;
        $this->offset = ($this->getCurrentPage() - 1) * $this->maxPerPage;
        $this->_setQuery($query);
        if(isset($options['styling'])){
            $this->setStyling($options['styling']);
        }
        if(isset($options['paging'])){
            $this->setPaging($options['paging']);
        }
    }

    private function _initiatilize()
    {
        $this->countData = count($this->getQuery()->execute());
        $this->requete = $this->getQuery()->limit($this->maxPerPage, $this->offset);
        $this->_setCountPage($this->countData);
    }

    private function _setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
   // initialisé dans le constructeur
    public function getData()
    {
        $this->_initiatilize();
        return $this->requete->execute();
    }
    /**
     *
     * @param numeric $data
     */
    private function _setCountPage($data)
    {
        $count = ceil($data / $this->maxPerPage);
        for ($i = 1; $i <= $count; $i++) $arrPages[$i - 1] = $i;
        $num = $arrPages;
        $this->pages = $num;
    }
    /**
     * Paramétrage controller et action pour le lien
     * 
     * @param array $params
     *
     */
    public function setParams(array $params)
    {
        if(!isset($params['controller']) or !isset($params['action']) ) {
               throw new Exception('un paramètre n\'est pas définit');
        } else {
            $this->controller = $params['controller'];
            $this->action = $params['action'];
        }
    }
    
    public function setPaging($paging)
    {
        $this->paging = $paging;
    }
    
    public function setStyling($style)
    {
        $this->styling = $style;
    }
    
    public function getStyling()
    {
        return $this->styling;
    }

    public function getClass()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getNbResult()
    {
        return $this->countData;
    }

    public function count()
    {
        return count($this->pages);
    }

    public function getIterator()
    {
        return new PagerIterator($this->pages);
    }

    public function getFirstPage()
    {
        return 1;
    }

    public function getLastPage()
    {
        return $this->count();
    }

    public function getCurrentPage()
    {
        $page = $this->currentPage;
        return $page;
    }

    public function getPreviousPage()
    {
        return $this->currentPage - 1;
    }

    public function getNextPage()
    {
        return $this->currentPage + 1;
    }

    public function getLinksPage()
    {
        return $this->getIterator();
    }
    
    public function render()
    {
        if(!in_array($this->getStyling(), $this->stylingPager)) {
            throw new \Exception('Rendu de style de pagination invalide');
        }
        $styling = $className = "Toucan\\Component\\Pager\\Styling\\" . ucfirst($this->getStyling());
        $style = new $styling($this);
        return $style->render();
        
    }  
}
?>