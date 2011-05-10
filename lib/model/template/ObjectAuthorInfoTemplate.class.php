<?php
class Doctrine_Template_ObjectAuthorInfo extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->addListener(new Doctrine_Template_Listener_ObjectAuthor());
    }
}