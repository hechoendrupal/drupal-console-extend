<?php

/**
 * @file
 * Contains \Drupal\Console\Extend\Command\ExampleContainerAwareCommand.
 */

namespace Drupal\Console\GlobalExtend\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Style\DrupalStyle;

/**
 * Class ExampleContainerAwareCommand
 * @package Drupal\Console\GlobalExtend\Command
 */
class ExampleContainerAwareCommand extends Command
{
    use ContainerAwareCommandTrait;

    /**
     * ExampleContainerAwareCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('extend:example:container:aware');
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new DrupalStyle($input, $output);
        $io->commentBlock('Extend container aware example command.');
        $io->warning('This is a warning');
    }
}
