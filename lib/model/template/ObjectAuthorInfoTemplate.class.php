<?php
/**
 * Template of our behavior
 *
 * @package    sfDoctrineGuardObjectAuthorInfoPlugin
 * @subpackage lib.model.template
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class Doctrine_Template_ObjectAuthorInfo extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->addListener(new Doctrine_Template_Listener_ObjectAuthor());
    }
}