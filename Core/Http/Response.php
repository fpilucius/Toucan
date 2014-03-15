<?php

namespace Toucan\Core\Http;
//TODO abstract ResponseType 
class Response
{
    public $headers;
    protected $content;
    protected $statusText;
    protected $statusCode;
    protected $charset = 'UTF-8';
    protected $statusCodeText = array(   '100' => 'CONTINUE',
                                     '101' => 'SWITCHING PROTOCOLS',
                                     '200' => 'OK',
                                     '201' => 'CREATED',
                                     '202' => 'ACCEPTED',
                                     '203' => 'NON-AUTHORITATIVE INFORMATION',
                                     '204' => 'NO CONTENT',
                                     '205' => 'RESET CONTENT',
                                     '206' => 'PARTIAL CONTENT',
                                     '300' => 'MULTIPLE CHOICES',
                                     '301' => 'MOVED PERMANENTLY',
                                     '302' => 'FOUND',
                                     '303' => 'SEE OTHER',
                                     '304' => 'NOT MODIFIED',
                                     '305' => 'USE PROXY',
                                     '306' => 'RESERVED',
                                     '307' => 'TEMPORARY REDIRECT',
                                     '400' => 'BAD REQUEST',
                                     '401' => 'UNAUTHORIZED',
                                     '402' => 'PAYMENT REQUIRED',
                                     '403' => 'FORBIDDEN',
                                     '404' => 'NOT FOUND',
                                     '405' => 'METHOD NOT ALLOWED',
                                     '406' => 'NOT ACCEPTABLE',
                                     '407' => 'PROXY AUTHENTICATION REQUIRED',
                                     '408' => 'REQUEST TIMEOUT',
                                     '409' => 'CONFLICT',
                                     '410' => 'GONE',
                                     '411' => 'LENGTH REQUIRED',
                                     '412' => 'PRECONDITION FAILED',
                                     '413' => 'REQUEST ENTITY TOO LARGE',
                                     '414' => 'REQUEST-URI TOO LONG',
                                     '415' => 'UNSUPPORTED MEDIA TYPE',
                                     '416' => 'REQUESTED RANGE NOT SATISFIABLE',
                                     '417' => 'EXPECTATION FAILED',
                                     '500' => 'INTERNAL SERVER ERROR',
                                     '501' => 'NOT IMPLEMENTED',
                                     '502' => 'BAD GATEWAY',
                                     '503' => 'SERVICE UNAVAILABLE',
                                     '504' => 'GATEWAY TIMEOUT',
                                     '505' => 'HTTP VERSION NOT SUPPORTED'
                                     );

    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.1');
        $this->headers = $headers;
    }
    
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int) $code;
        if (!array_key_exists($code, $this->statusCodeText)) {
            throw new \Exception(sprintf('Le code http "%s" n\'est pas valide.', $code));
        }
        $this->statusText = false === $text ? '' : (null === $text ? $this->statusCodeText[$this->statusCode] : $text); 
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function sendContent()
    {
        echo $this->getContent();
    }
    
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    
    public function outputHeaders()
    {
        if(!array_key_exists('Content-Type',$this->headers)) {
        $this->setContentType('Content-Type', 'text/html; charset=' . $this->charset);
        }
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusCodeText));

            foreach ($this->headers as $header => $ch) {
                header($header.': '.$ch);
            }
    }
    
     public function setProtocolVersion($version)
    {
        $this->version = $version;
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }
    
    public function setContentType($key, $values)
    {
        $this->headers[$key] = $values;
    }
    
    public function addHttpHeader($type, $content){ 
        $this->headers[$type] = $content;
    }
    
    public function outPut()
    {
        if ($this->statusCode >= 200 
            && $this->statusCode != 204 
            && $this->statusCode != 304) {
            $this->addHttpHeader('Content-Length', strlen($this->content));
        }
        $this->outputHeaders();
        $this->sendContent();
    }

}
?>
