<?php
/**
 * Listener of our behavior.
 *
 * @package    sfDoctrineGuardObjectAuthorInfoPlugin
 * @subpackage lib.model.template
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class Doctrine_Template_Listener_ObjectAuthor extends Doctrine_Record_Listener
{
    public function postInsert(Doctrine_Event $event)
    {
        if (sfContext::hasInstance()) {
            $this->addObjectAuthorInfo($event->getInvoker(), 'insert');
        }
    }

    public function postUpdate(Doctrine_Event $event)
    {
        if (sfContext::hasInstance()) {
            $this->addObjectAuthorInfo($event->getInvoker(), 'update');
        }
    }

    public function postDelete(Doctrine_Event $event)
    {
        if (sfContext::hasInstance()) {
            $this->addObjectAuthorInfo($event->getInvoker(), 'delete');
        }
    }

    private function addObjectAuthorInfo($invoker, $eventType)
    {
        $request = sfContext::getInstance()->getRequest();
        $user    = sfContext::getInstance()->getUser();

        $_serverInfo = $request->getPathInfoArray();

        $sfGuardObjectAuthorInfo = new sfGuardObjectAuthorInfo();
        $sfGuardObjectAuthorInfo->setSfGuardUserId($user->getGuardUser()->getPrimaryKey());
        $sfGuardObjectAuthorInfo->setObjectType(get_class($invoker));
        $sfGuardObjectAuthorInfo->setObjectPrimary($invoker->getPrimaryKey());
        $sfGuardObjectAuthorInfo->setEventType($eventType);
        $sfGuardObjectAuthorInfo->setRemoteAddr($request->getRemoteAddress());
        $sfGuardObjectAuthorInfo->setHttpReferer($request->getReferer());
        $sfGuardObjectAuthorInfo->setHttpUserAgent(is_array($_serverInfo['HTTP_USER_AGENT']) ? $_serverInfo['HTTP_USER_AGENT'] : '');
        $sfGuardObjectAuthorInfo->save();
    }
}