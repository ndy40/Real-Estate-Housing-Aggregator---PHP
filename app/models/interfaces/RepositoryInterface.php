<?php

/*
 * Interface for repositories
 */

namespace models\interfaces;

/**
 * Description of RepositoryInterface
 *
 * @author ndy40
 */
interface RepositoryInterface 
{
    public function fetch($id);
    
    public function delete($id);
    
    public function update($entity);
}
