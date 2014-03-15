<?php
namespace Toucan\Component\Debuging;

class ErrorHandler{
    
    private $debug;
    
    public function __construct($debug)
    {
        $this->debug = $debug;
    }
    
    public function register()
    {
        set_error_handler(array($this, 'callBack'));
        set_exception_handler(array($this, 'callBack'));
    }
    
    public function callBack($errno, $errstr = '', $errfile = '', $errline = '')
    {
        if (error_reporting() == 0) {
            return;
        }

        if (func_num_args() == 5) {
            $exception = null;
            list($errno, $errstr, $errfile, $errline) = func_get_args();

            $backtrace = array_reverse(debug_backtrace());
        } else {
            $exc = func_get_arg(0);
            $errno = $exc->getCode();
            $errstr = $exc->getMessage();
            $errfile = $exc->getFile();
            $errline = $exc->getLine();

            $backtrace = $exc->getTrace();
        }

        $errorType = array(E_ERROR => 'ERROR - Erreur',
            E_WARNING => 'WARNING - Alerte',
            E_PARSE => 'PARSING ERROR - Erreur d\' analyse',
            E_NOTICE => 'NOTICE - Note',
            E_CORE_ERROR => 'CORE ERROR',
            E_CORE_WARNING => 'CORE WARNING',
            E_COMPILE_ERROR => 'COMPILE ERROR',
            E_COMPILE_WARNING => 'COMPILE WARNING',
            E_USER_ERROR => 'USER ERROR - Erreur spÃ©cifique',
            E_USER_WARNING => 'USER WARNING - Alerte spÃ©cifique',
            E_USER_NOTICE => 'USER NOTICE - Note spÃ©cifique',
            E_STRICT => 'STRICT NOTICE - Runtime Notice',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR - Catchable Fatal Error');

        if (array_key_exists($errno, $errorType)) {
            $err = $errorType[$errno];
        } else {
            $err = 'Exception';
        }
        if($this->debug) {
            $error = $err;
            $message = $errstr;
            $fichier = $errfile;
            $ligne = $errline;
            //$tracing = $trace;
            
           echo  $error .'<br>'. $message .'<br>'. $fichier .'<br>'. $ligne;
        }
    }

}
?>