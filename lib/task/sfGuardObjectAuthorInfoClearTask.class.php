<?php
/**
 * Task for cleaning information about authors of objects.
 *
 * @package    sfDoctrineGuardObjectAuthorInfoPlugin
 * @subpackage lib.task
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class sfGuardObjectAuthorInfoClearTask extends sfBaseTask
{
    protected function configure()
    {
        // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('model',      sfCommandArgument::OPTIONAL, 'For which model we must to remove information - default all models will be cleared.'),
            new sfCommandArgument('older_than', sfCommandArgument::OPTIONAL, 'Remove records older than "older_than" parameter, default is 7 days.')
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env',         null, sfCommandOption::PARAMETER_REQUIRED, 'The environment',     'dev'),
            new sfCommandOption('connection',  null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace           = 'objectAuthorInfo';
        $this->name                = 'clear';
        $this->briefDescription    = 'Clears information about authors of object.';
        $this->detailedDescription = <<<EOF
The [sfGuardObjectAuthorInfoClear|INFO] clean records about authors of specified model.
Call it with:

  [php symfony sfGuardObjectAuthorInfoClear|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $dateTime = new DateTime('now');
        if (isset($arguments['older_than'])) {
            $dateTime->modify('-'.(int)$arguments['older_than'].' days');
        } else {
            $dateTime->modify('-7 days');
        }

        $olderThan = $dateTime->format('Y-m-d G:i:s');
        $model     = isset($arguments['model']) ? $arguments['model'] : '';

        $query = sfGuardObjectAuthorInfoTable::getInstance()
                 ->createQuery('sgg')
                 ->where('sgg.created_at <= ?', $olderThan);

        if ($model) {
            $query->addWhere('sgg.object_type = ?', $model);
        }

        $count = $query->delete()->execute();

        $this->logSection('objectAuthorInfo', sprintf('We have removed %u records', $count));
    }
}