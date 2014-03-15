<?php
namespace Toucan\Component\Pager\Styling;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Dependency\Container;

class All
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
        
        $html = '';
        $html .= '<ul>';
        if ($this->pager->getCurrentPage() != 1) { 
            $html .= '<li><a href="/'. $root .'/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getPreviousPage().'" title="Page précédente">&laquo;</a></li><li class="disabled"><a href="#">...</a></li> ';
        }
         foreach ($this->pager->getLinksPage() as $page) {
            if ($page == $this->pager->getCurrentPage()) {
                $html .= '<li class="active"><a href="#">'.$page.'</a></li>';
            } else {
                $html .= '<li><a href="/' . $root . '/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$page.'">'.$page.'</a></li>';
            }
         }
         if ($this->pager->getCurrentPage() != $this->pager->count()) {
             $html .= '<li class="disabled"><a href="#">...</a></li><li><a href="/' . $root . '/'.$this->pager->getClass().'/'.$this->pager->getAction().'/'.$this->pager->getNextPage().'" title="Page suivante">&raquo;</a></li>';
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
