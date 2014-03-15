<?php

namespace Toucan\Core\Http;

class ResponseRedirect extends Response
{

    public function __construct($url, $status = 302)
    {
        if (empty($url)) {
            throw new \Exception('Redirection impossible. l\'url est vide.');
        }
        
        parent::__construct(
            sprintf('<html><head><meta http-equiv="refresh" content="1;url=%s"/></head></html>', htmlspecialchars($url, ENT_QUOTES)),
            $status,
            array('Location' => $url)
        );
    }

}

?>
