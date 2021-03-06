<?php

class IntegrationTestHelper
{
    private $test_org = null;
    private $test_board = null;
    private $test_list = null;
    private $test_card = null;
    private $test_checklist = null;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    public function getOrganization($force = false)
    {
        if (is_null($this->test_org) || $force) {
            $this->test_org = Trello_Organization::create(['displayName' => 'test']);
        }
        return $this->test_org;
    }

    public function getBoard($force = false)
    {
        $org = $this->getOrganization($force);
        if (is_null($this->test_board) || $force) {
            $this->test_board = Trello_Board::create(['name' => 'test', 'idOrganization' => $org->id]);
        }
        return $this->test_board;
    }

    public function getList($force = false)
    {
        $board = $this->getBoard($force);
        if (is_null($this->test_list) || $force) {
            $this->test_list = Trello_List::create(['name' => 'test', 'idBoard' => $board->id]);
        }
        return $this->test_list;
    }

    public function getCard($force = false)
    {
        $list = $this->getList($force);
        if (is_null($this->test_card) || $force) {
            $this->test_card = Trello_Card::create(['name' => 'test', 'idList' => $list->id]);
        }
        return $this->test_card;
    }

    public function getChecklist($force = false)
    {
        $board = $this->getBoard($force);
        $card = $this->getCard($force);
        if (is_null($this->test_checklist) || $force) {
            $this->test_checklist = Trello_Checklist::create(['idBoard' => $board->id, 'idCard' => $card->id]);
        }
        return $this->test_checklist;
    }

    public static function emptyAccount()
    {
        $organizations = Trello_Organization::search('test', ['organizations_limit' => 1000]);
        foreach ($organizations as $org) {
            $result = Trello_Organization::deleteOrganization($org->id);
        }
        $boards = Trello_Board::search('test', ['boards_limit' => 1000]);
        foreach ($boards as $board) {
            $result = Trello_Board::closeBoard($board->id);
        }
    }
}
