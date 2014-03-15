<?php
namespace Toucan\Component\Pager\Styling;

use Toucan\Component\Registre\Registry;
use Toucan\Component\Dependency\Container;

class Sliding
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

        $page_prev = $this->pager->getPreviousPage();
        $page_next = $this->pager->getNextPage();
        $html .= '<ul>';
        if ($this->pager->getCurrentPage() != 1) {
            $html .= '<li><a href="/' . $root . '/' . $this->pager->getClass() . '/' . $this->pager->getAction() . '/' . $this->pager->getPreviousPage() . '">&laquo;</a></li>';
        }

        $page_count = $this->pager->count();

        $number_paging = $this->pager->paging;
        $page_padding = floor($number_paging / 2);

        if ($page_count > $number_paging) {
            if ($this->pager->getCurrentPage() >= ($page_padding + 1)) {
                if ($this->pager->getCurrentPage() > ($page_count - $page_padding)) {
                    $page_start = $page_count - ($page_padding * 2);
                    $page_end = $page_count;
                } else {
                    $page_start = $this->pager->getCurrentPage() - $page_padding;
                    $page_end = $this->pager->getCurrentPage() + $page_padding;
                }
            } else {
                $page_start = 1;
                $page_end = ($page_padding * 2) + 1;
            }
        } else {
            $page_start = 1;
            $page_end = $page_count;
        }
        if ($this->pager->getCurrentPage() >= $number_paging) {
            $html .= '<li><a href="/' . $root . '/' . $this->pager->getClass() . '/' . $this->pager->getAction() . '/' . $this->pager->getFirstPage() . '">' . $this->pager->getFirstPage() . '</a></li><li class="disabled"><a href="#">...</a></li>';
        }
        $pages = array();
        for ($t = $page_start; $t <= $page_end; $t++) {
            $pages[] = $t;
            if ($this->pager->getCurrentPage() == $t) {
                $html .= '<li class="active">';
                $html .= '<a href="#">'.$t.'</a>';
                $html .= '</li>';
            } else {
                $html .= '<li><a href="/' . $root . '/' . $this->pager->getClass() . '/' . $this->pager->getAction() . '/' . $t . '">' . $t . '</a></li>';
            }
        }
        if ($this->pager->getCurrentPage() != $this->pager->getLastPage() && !in_array($this->pager->getLastPage(), $pages))
            $html .= '<li class="disabled"><a href="#">...</a></li><li><a href="/' . $root . '/' . $this->pager->getClass() . '/' . $this->pager->getAction() . '/' . $this->pager->getLastPage() . '">' . $this->pager->getLastPage() . '</a></li>';
        if ($this->pager->getCurrentPage() != $page_count)
            $html .= '<li><a href="/' . $root . '/' . $this->pager->getClass() . '/' . $this->pager->getAction() . '/' . $this->pager->getNextPage() . '">&raquo;</a></li>';
        $html .= '</ul>';
        return $html;
    }
    
    public function get($key)
    {
        return $this->container->getService($key);
    }
}

?>
