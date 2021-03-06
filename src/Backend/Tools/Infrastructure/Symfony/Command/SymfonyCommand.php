<?php

declare(strict_types=1);

namespace Kishlin\Backend\Tools\Infrastructure\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class SymfonyCommand extends Command
{
    private ?QuestionHelper $questionHelper = null;

    protected function stringFromArgumentsOrPrompt(
        InputInterface $input,
        OutputInterface $output,
        string $argument,
        string $promptText,
    ): string {
        /** @var null|string $response */
        $response = $input->getArgument($argument);
        if (false === empty($response)) {
            return $response;
        }

        $prompt = new Question($promptText);

        do {
            $response = $this->questionHelper()->ask($input, $output, $prompt);
        } while (false === is_string($response) || empty($response));

        return $response;
    }

    protected function intFromArgumentsOrPrompt(
        InputInterface $input,
        OutputInterface $output,
        string $argument,
        string $promptText,
    ): int {
        /** @var null|string $response */
        $response = $input->getArgument($argument);
        if (false === empty($response) && is_numeric($response)) {
            return (int) $response;
        }

        $prompt = new Question($promptText);

        do {
            /** @var string $response */
            $response = $this->questionHelper()->ask($input, $output, $prompt);
        } while (empty($response) && false === is_numeric($response));

        return (int) $response;
    }

    private function questionHelper(): QuestionHelper
    {
        if (null === $this->questionHelper) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');

            $this->questionHelper = $helper;
        }

        return $this->questionHelper;
    }
}
