<?php
namespace Toucan\Component\Orm\Convention;

interface AdapterInterface
{
    public function dsn();
    public function limit($count, $offset);
}
?>
