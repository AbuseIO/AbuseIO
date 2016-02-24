<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{
    private $updatedPassword;

    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('user', inputArgument::REQUIRED, 'The user id or e-mail of you want to edit'),
            new InputOption('email', null, InputOption::VALUE_OPTIONAL, 'The new e-mail address and login username'),
            new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The new password for the account '),
            new InputOption('autopassword', null, InputOption::VALUE_OPTIONAL,'Generate a new password and set it for the account'),
            new InputOption('first_name', null, InputOption::VALUE_OPTIONAL,'The new first name of the users account.'),
            new InputOption('last_name', null, InputOption::VALUE_OPTIONAL, 'The new last name of the users account'),
            new InputOption('language', null, InputOption::VALUE_OPTIONAL, 'The default language for the users account, in country code '),
            new InputOption('account', null, InputOption::VALUE_OPTIONAL, 'The new account name where this user is linked to'),
            new InputOption('disable', null, InputOption::VALUE_OPTIONAL, 'Set the new account status to be disabled'),
            new InputOption('enable', null, InputOption::VALUE_OPTIONAL, 'Set the new account status to be enabled'),
        ]);
    }

    public function getAsNoun()
    {
        return 'user';
    }

    protected function getModelFromRequest()
    {
        return User::where('id',$this->argument('user'))
                ->orWhere('email', $this->argument('user'))
            ->first();
    }


    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'first_name');
        $this->updateFieldWithOption($model, 'last_name');
        $this->updateFieldWithOption($model, 'email');

        $this->handleLanguageUpdate($model);

        $this->handleEnableDisable($model);

        if (!empty($this->option('account'))) {
            $newAccount = Account::find($this->option('account'));
            if (null === $newAccount) {
                $this->error('Unable to find account with this criteria');

                return false;
            }
            $model->account_id = $newAccount->id;
        }

        $this->handlePasswordUpdate($model);

        return true;
    }

    protected function getValidator($model)
    {
        $user = $model->toArray();

        dd($user);

        if ($this->updatedPassword) {
            $user['password'] = $this->updatedPassword;
            $user['password_confirmation'] = $this->updatedPassword;
        }

        dd($user);

        return Validator::make($user, User::updateRules($model));
    }

    /**
     * @param $model
     */
    protected function handleEnableDisable($model)
    {
        if ($this->option('enable')) {
            /** @var User $model */
            $model->disabled = false;
        }

        if ($this->option('disable')) {
            /** @var User $model */
            $model->disabled = true;
        }
    }

    /**
     * @param $model
     */
    protected function handlePasswordUpdate($model)
    {
        if (!empty($this->option('password'))) {
            $model->password = $this->option('password');

            $this->updatedPassword = $this->option('password');
        }
        if (!empty($this->option('autopassword'))) {
            $generatedPassword = substr(md5(rand()), 0, 8);
            $this->info("Using auto generated password: {$generatedPassword}");
            $model->password = $generatedPassword;

            $this->updatedPassword = $generatedPassword;
        }
    }

    /**
     * @param $model
     */
    protected function handleLanguageUpdate($model)
    {
        if (!empty($this->option('language'))) {
            $model->locale = $this->option('language');
        }
    }

}



//namespace AbuseIO\Console\Commands\User;
//
//USE ILLUMINATE\CONSOLE\COMMAND;
//USE ABUSEIO\MODELS\USER;
//USE ABUSEIO\MODELS\ACCOUNT;
//USE VALIDATOR;
//USE CARBON;
//
//CLASS EDITCOMMAND EXTENDS COMMAND
//{
//
//    /**
//     * THE CONSOLE COMMAND NAME.
//     * @VAR STRING
//     */
//    PROTECTED $SIGNATURE = 'USER:EDIT
//                            {--USER= : THE USER ID OR E-MAIL OF YOU WANT TO CHANGE }
//                            {--EMAIL= : THE NEW E-MAIL ADDRESS AND LOGIN USERNAME }
//                            {--PASSWORD= : THE NEW PASSWORD FOR THE ACCOUNT }
//                            {--AUTOPASSWORD : GENERATE A NEW PASSWORD AND SET IT FOR THE ACCOUNT }
//                            {--FIRSTNAME= : THE NEW FIRST NAME OF THE USERS ACCOUNT }
//                            {--LASTNAME= : THE NEW LAST NAME OF THE USERS ACCOUNT }
//                            {--LANGUAGE= : THE DEFAULT LANGUAGE FOR THE USERS ACCOUNT, IN COUNTRY CODE }
//                            {--ACCOUNT= : THE NEW ACCOUNT NAME WHERE THIS USER IS LINKED TO }
//                            {--DISABLE : SET THE NEW ACCOUNT STATUS TO BE DISABLED }
//                            {--ENABLE : SET THE NEW ACCOUNT STATUS TO BE ENABLED }
//    ';
//
//    /**
//     * THE CONSOLE COMMAND DESCRIPTION.
//     * @VAR STRING
//     */
//    PROTECTED $DESCRIPTION = 'CHANGES INFORMATION FROM A USER';
//
//    /**
//     * CREATE A NEW COMMAND INSTANCE.
//     * @RETURN VOID
//     */
//    PUBLIC FUNCTION __CONSTRUCT()
//    {
//        PARENT::__CONSTRUCT();
//    }
//
//    /**
//     * EXECUTE THE CONSOLE COMMAND.
//     *
//     * @RETURN BOOLEAN
//     */
//    PUBLIC FUNCTION HANDLE()
//    {
//        IF (EMPTY($THIS->OPTION('USER'))) {
//            $THIS->WARN('THE REQUIRED USER ARGUMENT WAS NOT PASSED, TRY --HELP');
//            RETURN FALSE;
//        }
//
//        $USER = FALSE;
//        IF (!IS_OBJECT($USER)) {
//            $USER = USER::WHERE('EMAIL', $THIS->OPTION('USER'))->FIRST();
//        }
//
//        IF (!IS_OBJECT($USER)) {
//            $USER = USER::FIND($THIS->OPTION('USER'));
//        }
//
//        IF (!IS_OBJECT($USER)) {
//            $THIS->ERROR('UNABLE TO FIND USER WITH THIS CRITERIA');
//            RETURN FALSE;
//        }
//
//        // APPLY CHANGES TO THE USER OBJECT
//        IF (!EMPTY($THIS->OPTION('EMAIL'))) {
//            $USER->EMAIL = $THIS->OPTION('EMAIL');
//        }
//        IF (!EMPTY($THIS->OPTION('PASSWORD'))) {
//            $USER->PASSWORD = $THIS->OPTION('PASSWORD');
//        }
//        IF (!EMPTY($THIS->OPTION('AUTOPASSWORD'))) {
//            $GENERATEDPASSWORD = SUBSTR(MD5(RAND()), 0, 8);
//            $THIS->INFO("USING AUTO GENERATED PASSWORD: {$GENERATEDPASSWORD}");
//            $USER->PASSWORD = $GENERATEDPASSWORD;
//        }
//        IF (!EMPTY($THIS->OPTION('FIRSTNAME'))) {
//            $USER->FIRST_NAME = $THIS->OPTION('FIRSTNAME');
//        }
//        IF (!EMPTY($THIS->OPTION('LASTNAME'))) {
//            $USER->LAST_NAME = $THIS->OPTION('LASTNAME');
//        }
//        IF (!EMPTY($THIS->OPTION('ACCOUNT'))) {
//            $ACCOUNT = ACCOUNT::WHERE('NAME', '=', $THIS->OPTION('ACCOUNT'))->FIRST();
//            IF (!IS_OBJECT($ACCOUNT)) {
//                $THIS->ERROR("THE ACCOUNT NAMED {$THIS->OPTION('ACCOUNT')} WAS NOT FOUND");
//                RETURN FALSE;
//            }
//
//            $USER->ACCOUNT_ID = $ACCOUNT->ID;
//        }
//        IF (!EMPTY($THIS->OPTION('LANGUAGE'))) {
//            $USER->LOCALE = $THIS->OPTION('LANGUAGE');
//        }
//        IF (!EMPTY($THIS->OPTION('DISABLE'))) {
//            $USER->DISABLED = "TRUE";
//        }
//        IF (!EMPTY($THIS->OPTION('ENABLE'))) {
//            $USER->DISABLED = "FALSE";
//        }
//
//        // VALIDATE THE CHANGES
//        $VALIDATIONUSER = $USER->TOARRAY();
//        $VALIDATIONUSER['PASSWORD'] = EMPTY($THIS->OPTION('PASSWORD')) ? $GENERATEDPASSWORD : $THIS->OPTION('PASSWORD');
//        $VALIDATIONUSER['PASSWORD_CONFIRMATION'] = $VALIDATIONUSER['PASSWORD'];
//
//
//
//        $VALIDATION = VALIDATOR::MAKE($VALIDATIONUSER, USER::UPDATERULES($USER));
//
//        IF ($VALIDATION->FAILS()) {
//            FOREACH ($VALIDATION->MESSAGES()->ALL() AS $MESSAGE) {
//                $THIS->WARN($MESSAGE);
//            }
//
//            $THIS->ERROR('FAILED TO CREATE THE USER DUE TO VALIDATION WARNINGS');
//
//            RETURN FALSE;
//        }
//
//        // SAVE THE OBJECT
//        $USER->SAVE();
//
//        $THIS->INFO("USER HAS BEEN SUCCESSFULLY UPDATED");
//
//        RETURN TRUE;
//    }
//}
