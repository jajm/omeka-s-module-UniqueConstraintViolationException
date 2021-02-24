<?php

namespace UniqueConstraintViolationException;

use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{
    public function attachListeners($events)
    {
        $events->attach('Omeka\Api\Adapter\ItemAdapter', 'api.update.post', [$this, 'updateItemPost']);
    }

    public function updateItemPost($event)
    {
        $apiAdapters = $this->getServiceLocator()->get('Omeka\ApiAdapterManager');

        $resource = $event->getParam('response')->getContent();
        $apiAdapter = $apiAdapters->get($resource->getResourceName());
        $representation = $apiAdapter->getRepresentation($resource);

        //* When the following block is executed, an exception will be thrown later
        $template = $representation->resourceTemplate();
        if ($template) {
            $template->resourceTemplateProperties();
        }

        /*/ // but no exception will be thrown if only the following block is executed
        $template = $resource->getResourceTemplate();
        if ($template) {
            $template->getResourceTemplateProperties();
        }
        //*/
    }
}
