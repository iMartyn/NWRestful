<?php
class RoutingController
{
    public function getAction($request) {
        $context = new NWManagedObjectContext();
        $entityName = str_replace("Controller", "", get_class($this));
        if(isset($request->url_elements[2]) && $request->url_elements[2] != "") {
            $instanceID = (int)$request->url_elements[2];
            $entities = $context->getEntityForInstance($entityName, $instanceID);
            $entities = $entities[0];
        } else {
            $entities = $context->getEntitiesForStatement($entityName);

        }
        return $entities;
    }
 
    public function postAction($request) {
         if (isset($request->url_elements[2]) || isset($request->parameters['instance'])) {
            $entityName = str_replace("Controller", "", get_class($this));
            $entityName = $entityName . "Model";
            $entity = new $entityName();
            $entity->instance = $request->url_elements[2];
            foreach ($request->parameters as $key => $value) {
                if (property_exists($entity, $key)) {
                    $entity->$key = $value;
                }
            }
            $entity->save();
            return $entity;
        }
        else {
            return $this->putAction($request);
        }
    }
    public function putAction($request) {
        $entityName = str_replace("Controller", "", get_class($this));
        $entityName = $entityName . "Model";
        $entity = new $entityName();
        foreach ($request->parameters as $key => $value) {
            if (property_exists($entity, $key) && $key != "instance") {
                $entity->$key = $value;
            }
        }
        $entity->save();
        return $entity;
    }
    public function deleteAction($request) {
        $context = new NWManagedObjectContext();
        $entityName = str_replace("Controller", "", get_class($this));
        if (isset($request->url_elements[2])) {
            $instanceID = (int)$request->url_elements[2];
            $entities = $context->getEntityForInstance($entityName, $instanceID);
            // $entities[0]->purge;
            // $entities->purge();
            if (count($entities) > 0) {
                $entity = $entities[0];
                print_r($entity->purge());
                return $entity;
            }

        }
    }
}
?>