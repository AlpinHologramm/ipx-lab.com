<?php

class Push_MessageController extends Core_Controller_Default
{

    public function sendAction() {

        $message = new Push_Model_Message();
        $now = Zend_Date::now()->toString('y-MM-dd HH:mm:ss');
        $errors = array();

        if($id = $this->getRequest()->getParam('message_id')) {
            $message->find($id);
            $messages = array();
            if($message->getId() AND $message->getStatus() == "queued") {
                $messages[] = $message;
            }
        } else {
            $messages = $message->findAll(array('status IN (?)' => array('queued'), 'send_at IS NULL OR send_at <= ?' => $now, 'send_until IS NULL OR send_until >= ?' => $now), 'created_at DESC');
        }

        if(count($messages) > 0) {
            foreach($messages as $message) {
                try {
                    // Envoi et sauvegarde du message
                    $message->push();
                    if($message->getErrors()) {
                        $errors[$message->getId()] = $message->getErrors();
                    }
                }
                catch(Exception $e) {
                    $message->updateStatus('failed');
                    $errors[$message->getId()] = $e;
                }
            }
        }

        Zend_Debug::dump('Erreurs :');
        Zend_Debug::dump($errors);

        die('done');
    }

}