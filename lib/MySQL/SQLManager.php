<?php
    
    namespace MySQL;
    
    interface SQLManager
    {
        private function getDataWithoutBinding ($db, $sqlQuery)
        {
            if (! $db->prepareStatement ($sqlQuery)) return json_encode (array ('error' => 'failed to preprare sql statement'));
            if (! $db->executeStatement (array (), '')) return json_encode (array ('error' => 'failed to execute sql statement'));
            
            $data = $db->fetchAll();
            $db->closeConnection();
            
            return json_encode ($data);
        }
        
        private function getDataWithBinding ($db, $values, $binding, $sqlQuery)
        {
            if (! $db->prepareStatement ($sqlQuery)) return json_encode (array ('error' => 'failed to preprare sql statement'));
            if (! $db->executeStatement ($values, $binding)) return json_encode (array ('error' => 'failed to execute sql statement'));
            
            $data = $db->fetchAll();
            $db->closeConnection();
            
            return json_encode ($data);
        }
        
        private function executeQuery ($db, $values, $binding, $sqlQuery)
        {
            if (! $db->prepareStatement ($sqlQuery)) return json_encode (array ('error' => 'failed to preprare sql statement'));
            if (! $db->executeStatement ($values, $binding)) return json_encode (array ('error' => 'failed to execute sql statement'));
            
            $db->closeConnection();
            
            return json_encode (array ('results' => 'success'));
        }
        
        private function getRow ($db, $id, $sqlQuery)
        {
            if (! $db->prepareStatement ($sqlQuery)) return json_encode (array ('error' => 'failed to preprare sql statement'));
            if (! $db->executeStatement (array ($id), 's')) return json_encode (array ('error' => 'failed to execute sql statement'));
            
            $data = $db->fetchRow();
            $db->closeConnection();
            
            return json_encode ($data);
        }
        
        public function __call ($method, $arguments) 
        {
            if ($method == 'getData') 
            {
                switch (count ($arguments)) 
                {
                    case 2:
                        return $this->getDataWithoutBinding ($arguments[0], $arguments[1]);
                    case 3:
                        return $this->getRow ($arguments[0], $arguments[1], $arguments[2]);
                    case 4:
                        return $this->getDataWithBinding ($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                }
            }
            
            if ($method == 'setData')
            {
                return $this->executeQuery ($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
            }
        }    
    }
    
?>