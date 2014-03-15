<?php
namespace Toucan\Component\Pager\Styling;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Dependency\Container;

class Elastic
{
    protected $pager;
    protected $container;
    
    public function __construct($pager)
    {
        $this->pager = $pager;
    }
    
    public function render()
    {
        $this->container = Registry::get('container');
        $root = $this->get('config')->get('root');

        $endPrintPages = array_slice($this->pager->pages, $this->pager->count()-$this->pager->paging,$this->pager->count());
        if($this->pager->getCurrentPage() < $this->pager->paging) {
            $pages = array_slice($this->pager->pages, 0,$this->pager->paging);
        }elseif(in_array($this->pager->getCurrentPage(), $endPrintPages)){
            $pages = $endPrintPages;
        }else{
            $pages = array_slice($this->pager->pages,$this->pager->getCurrentPage()-2,$this->pager->paging);
        }
        $html = '';
        $html .= '<ul>';
        if ($this->pager->getCurrentPage() != 1) { 
            $html .= '<li><a href="/'. $root .'/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getPreviousPage().'" title="Page précédente" >&laquo;</a></li>';
        }
        $printOne = $this->pager->paging-1;
        if($this->pager->getCurrentPage() > $printOne){
            $html .= '<li><a href="/'.$root.'/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getFirstPage().'">'.$this->pager->getFirstPage().'</a></li><li class="disabled"><a href="#">...</a></li>';
        }
         foreach ($pages as $page) {
            if ($page == $this->pager->getCurrentPage()) {
                $html .= '<li class="active"><a href="#">'.$page.'</a></li>';
            } else {
                $html .= '<li><a href="/' . $root . '/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$page.'">'.$page.'</a></li>';
            }
         }
         if ($this->pager->getCurrentPage()!=$this->pager->getLastPage() && !in_array($this->pager->getLastPage(),$pages)) $html .= '<li class="disabled"><a href="#">...</a></li><li><a href="/'.$root.'/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getLastPage().'">'.$this->pager->getLastPage().'</a></li>';
         if ($this->pager->getCurrentPage() != $this->pager->count()) {
             $html .= '<li><a href="/' . $root . '/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getNextPage().'" title="Page suivante">&raquo;</a></li>';
         }
          $html .= '</ul>';
        return $html;   
    }
    
    public function get($key)
    {
        return $this->container->getService($key);
    }
    
}
?>
