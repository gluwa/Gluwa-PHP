<?php

namespace Gluwa;

class GluwaSDKException extends \Exception
{

    private $innerErrors = array();

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // make sure everything is assigned properly
        if (is_array($message)) {
            if (array_key_exists('InnerErrors', $message)) {
                $this->innerErrors = $message['InnerErrors'];
            }
            if (array_key_exists('Message', $message)) {
                $message = $message['Message'];
            } else {
                $message = json_encode($message);
            }
            if (count($this->innerErrors) > 0) {
                $temp = [];
                foreach ($this->innerErrors as $innerError) {
                    $text = [];
                    if (array_key_exists('Code', $innerError)) {
                        $text[] = $innerError['Code'];
                    }
                    if (array_key_exists('Message', $innerError)) {
                        $text[] = $innerError['Message'];
                    }
                    if (count($text) > 0) {
                        $text = implode(' : ', $text);
                        $temp[] = $text;
                    }
                }
                $this->innerErrors = $temp;
            }
        }
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getInnerErrors() {
        return $this->innerErrors;
    }
}