<?php

namespace Toucan\Component\Session;

class Session {

    protected static $isSession;
    protected static $IsSessionRegenerate;
    protected $options;

    public function __construct($options = array()) {
        //TODO implementer les options session_get_cookie_params();
        // si la session est declare en natif dans web/app.php
        if (session_id()) {
            self::$isSession = true;
        }
    }

    public function start() {
        if (self::$isSession) {
            return;
        }

        if (!session_id()) {
            session_start();
        }

        self::$isSession = true;
    }

    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null) {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : $default;
    }

    public function delete($name = null) {
        if ($_SESSION) {
            if ($name != null) {
                unset($_SESSION[$name]);
            } else {
                unset($_SESSION);
            }
        }
    }

    public function getId() {
        if (!self::$isSession) {
            throw new \Exception('La session n\'est pas démarré pour la lecture de l\'ID');
        }
        return session_id();
    }

    public function regenerate($destroy = false) {
        if (self::$isSessionRegenerate) {
            return;
        }
        session_regenerate_id($destroy);

        self::$isSessionRegenerate = true;
    }

    public function destroy() {
        if (session_id()) {
            session_destroy();
        }
    }

}

?>