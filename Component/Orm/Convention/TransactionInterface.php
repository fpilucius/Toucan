<?php
namespace Toucan\Component\Orm\Convention;

interface TransactionInterface
{
    public function beginTransaction();
    public function commit();
    public function rollBack();
}
?>
