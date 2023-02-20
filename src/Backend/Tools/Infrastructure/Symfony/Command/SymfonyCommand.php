<?php

declare(strict_types=1);

namespace Kishlin\Backend\Tools\Infrastructure\Symfony\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

abstract class SymfonyCommand extends Command
{
    private ?QuestionHelper $questionHelper = null;

    protected function stringFromPrompt(InputInterface $input, OutputInterface $output, string $promptText): string
    {
        $prompt = new Question($promptText);

        do {
            $response = $this->questionHelper()->ask($input, $output, $prompt);
        } while (false === is_string($response) || empty($response));

        return $response;
    }

    protected function intFromPrompt(InputInterface $input, OutputInterface $output, string $promptText): int
    {
        $prompt = new Question($promptText);

        do {
            /** @var string $response */
            $response = $this->questionHelper()->ask($input, $output, $prompt);
        } while (empty($response) && false === is_numeric($response));

        return (int) $response;
    }

    protected function floatFromPrompt(InputInterface $input, OutputInterface $output, string $promptText): float
    {
        $prompt = new Question($promptText);

        do {
            /** @var string $response */
            $response = $this->questionHelper()->ask($input, $output, $prompt);
        } while (empty($response) && false === is_numeric($response));

        return (float) $response;
    }

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

        return $this->stringFromPrompt($input, $output, $promptText);
    }

    protected function intFromArgumentsOrPrompt(
        InputInterface $input,
        OutputInterface $output,
        string $argument,
        string $promptText,
    ): int {
        /** @var null|string $response */
        $response = $input->getArgument($argument);

        if (is_numeric($response)) {
            return (int) $response;
        }

        return $this->intFromPrompt($input, $output, $promptText);
    }

    /**
     * @param array|object[] $items
     */
    protected function selectItemInList(
        InputInterface $input,
        OutputInterface $output,
        string $question,
        array $items,
        string $errorMessage,
    ): mixed {
        $question = new ChoiceQuestion($question, $items, 0);

        $question->setErrorMessage($errorMessage);

        return $this->questionHelper()->ask($input, $output, $question);
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
