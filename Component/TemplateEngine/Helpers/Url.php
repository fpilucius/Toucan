<?php
namespace Toucan\Component\TemplateEngine\Helpers;

use Toucan\Component\TemplateEngine\Helpers\TemplateHelperInterface;

class Url implements TemplateHelperInterface
{
    public function get()
    {
        $args = func_get_args();
        return '<a href="'.$args[0].'">'.$args[1].'</a>';
    }
}
?>
