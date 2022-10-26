<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools\CommandTest;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

trait CommandWithChoiceQuestionTrait
{
    protected function pickFirstChoiceInAnyChoiceQuestion(Command $command): void
    {
        $command->setHelperSet(
            new HelperSet([
                new class() extends QuestionHelper {
                    public function ask(InputInterface $input, OutputInterface $output, Question $question): mixed
                    {
                        assert($question instanceof ChoiceQuestion);

                        return $question->getChoices()[0];
                    }
                },
            ]),
        );
    }
}
