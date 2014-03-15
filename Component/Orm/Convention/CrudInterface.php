<?php
namespace Toucan\Component\Orm\Convention;
 
interface CrudInterface
{
    public function read($object, array $options = null);
    public function create($object, array &$data);
    public function update($object, array &$data, $id);
    public function delete($object, $id);
    public function count($object, $countTable = null, array $options = null);
}
?>
