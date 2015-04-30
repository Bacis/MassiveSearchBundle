<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Massive\Bundle\SearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Massive\Bundle\SearchBundle\Search\Event\IndexRebuildEvent;
use Massive\Bundle\SearchBundle\Search\SearchEvents;

/**
 * Comand to build (or rebuild) the search index
 */
class IndexRebuildCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->setName('massive:search:index:rebuild');
        $this->addOption('filter', null, InputOption::VALUE_OPTIONAL, 'Filter classes which will be indexed (regex)');
        $this->addOption('purge', null, InputOption::VALUE_NONE, 'Purge the index before reindexing');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $purge = $input->getOption('purge');
        $filter = $input->getOption('filter');

        $event = new IndexRebuildEvent($filter, $purge, $output);
        $eventDispatcher->dispatch(SearchEvents::INDEX_REBUILD, $event);
    }
}