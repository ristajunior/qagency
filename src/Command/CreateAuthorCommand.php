<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\User;
use App\QSSAPI\Login;
use App\QSSAPI\Request\AuthorRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

class CreateAuthorCommand extends Command
{
    /**
     * @var Login
     */
    private $login;

    /**
     * @var Author
     */
    private $author;
    /**
     * @var AuthorRequest
     */
    private $authorRequest;

    /**
     * @var string
     */
    private $token = null;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    protected static $defaultName = 'q:create-author';

    /**
     * CreateAuthorCommand constructor.
     * @param Login $login
     * @param Author $author
     * @param AuthorRequest $authorRequest
     * @param User $user
     */
    public function __construct(Login $login, Author $author, AuthorRequest $authorRequest, User $user)
    {
        $this->login = $login;
        $this->authorRequest = $authorRequest;
        $this->author = $author;
        $this->user = $user;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new author.')
            ->setHelp('This command allows you to create a new author')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // Login and get token
        $this->token = $this->getToken($helper, $input, $output);

        if (!$this->token) {
            $this->printLoginError($output);
            return 0;
        }

        $this->printLoginSuccess($output);

        // Input author data
        $this->inputDataForNewAuthor($helper, $input, $output);

        $this->displayEnteredAuthor($output);

        // Confirm author creation
        $question = new ConfirmationQuestion('Continue with creating author ([y]es or [n]o)?  ', false);

        if (!$helper->ask($input, $output, $question)) {
            $this->printCancelAuthorMessage($output);
        } else {
            // Create new author
            $response = $this->authorRequest->addAuthorFromConsole(json_encode($this->author), $this->token);
            if (!$response) {
                $this->printAuthorNotCreatedError($output);
            } else {
                $this->printAuthorCreatedSuccessfullyMessage($output);
            }
        }

        return 0;
    }

    private function getToken($helper, $input, $output): ?string
    {
        $output->writeln([
            '',
            '<bg=blue;options=bold>         </>',
            '<bg=blue;options=bold>  Login  </>',
            '<bg=blue;options=bold>         </>',
            '',
        ]);
        $question = new Question('Please enter your <fg=yellow;options=bold>email:</> ');
        $this->user->setEmail($helper->ask($input, $output, $question));

        $question = new Question('Please enter your password <fg=yellow;options=bold>password:</> ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $this->user->setPassword($helper->ask($input, $output, $question));

        return $this->login->loginConsole($this->user);
    }

    private function printLoginSuccess($output)
    {
        $output->writeln([
            '',
            '<bg=green;options=bold>                                   </>',
            '<bg=green;options=bold>  You are logged in successfully!  </>',
            '<bg=green;options=bold>                                   </>',
            ''
        ]);
    }

    private function printLoginError($output)
    {
        $output->writeln([
            '',
            '<bg=red;options=bold>                                                 </>',
            '<bg=red;options=bold>  Email or password incorrect! You didn\'t login  </>',
            '<bg=red;options=bold>                                                 </>',
            ''
        ]);
    }

    private function inputDataForNewAuthor($helper, $input, $output)
    {
        $output->writeln([
            '',
            '<bg=blue;options=bold>                     </>',
            '<bg=blue;options=bold>  Create new author  </>',
            '<bg=blue;options=bold>                     </>',
            '',
        ]);

        $question = new Question('Please enter authors <fg=yellow;options=bold>first name:</> ');
        $this->author->setFirstName($helper->ask($input, $output, $question));

        $question = new Question('Please enter authors <fg=yellow;options=bold>last name:</> ');
        $this->author->setLastName($helper->ask($input, $output, $question));

        $question = new Question('Please enter authors <fg=yellow;options=bold>birthday:</> ');
        $this->author->setBirthday($helper->ask($input, $output, $question));

        $question = new Question('Please enter authors <fg=yellow;options=bold>biography:</> ');
        $this->author->setBiography($helper->ask($input, $output, $question));

        $question = new ChoiceQuestion(
            'Please choose authors <fg=yellow;options=bold>gender:</>',
            ['male', 'female'],
            0
        );
        $question->setErrorMessage('Gender %s is invalid.');
        $this->author->setGender($helper->ask($input, $output, $question));

        $question = new Question('Please enter authors <fg=yellow;options=bold>place of birth:</> ');
        $this->author->setPlaceOfBirth($helper->ask($input, $output, $question));
    }

    private function displayEnteredAuthor($output)
    {
        $output->writeln([
            '',
            '----------------------',
            'You entered this data:',
            'First name (string): <fg=yellow;options=bold>' . $this->author->getFirstName() . '</>',
            'Last name (string): <fg=yellow;options=bold>' . $this->author->getLastName() . '</>',
            'Birthday (date: dd-mm-YYYY): <fg=yellow;options=bold>' . $this->author->getBirthday() . '</>',
            'Biography: (text) <fg=yellow;options=bold>' . $this->author->getBiography() . '</>',
            'Gender: <fg=yellow;options=bold>' . $this->author->getGender() . '</>',
            'Place of birth (string): <fg=yellow;options=bold>' . $this->author->getPlaceOfBirth() . '</>',
            ''
        ]);
    }

    private function printCancelAuthorMessage($output)
    {
        $output->writeln([
            '',
            '<bg=red;options=bold>                                                          </>',
            '<bg=red;options=bold>  You have canceled request. New author was not created!  </>',
            '<bg=red;options=bold>                                                          </>',
            ''
        ]);
    }

    private function printAuthorNotCreatedError($output)
    {
        $output->writeln([
            '',
            '<bg=red;options=bold>                                                 </>',
            '<bg=red;options=bold>  Author has not been created. Please try again! </>',
            '<bg=red;options=bold>                                                 </>',
            ''
        ]);
    }

    private function printAuthorCreatedSuccessfullyMessage($output)
    {
        $output->writeln([
            '',
            '<bg=green;options=bold>                                      </>',
            '<bg=green;options=bold>  New author is successfully created  </>',
            '<bg=green;options=bold>                                      </>',
            ''
        ]);
    }
}