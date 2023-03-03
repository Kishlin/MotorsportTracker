<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\ConsoleApplication\Helper\Results;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class ResultsQuestionHelper extends QuestionHelper
{
    public function __construct(
        private ResultsHolderInterface $resultContext,
    ) {
    }

    public function ask(InputInterface $input, OutputInterface $output, Question $question): string
    {
        preg_match('/#([\d]+):$/', $question->getQuestion(), $matches);

        $result = $this->resultContext->getResultsForCar($matches[1]);

        return "{$result['position']} {$result['points']}";
    }
}
