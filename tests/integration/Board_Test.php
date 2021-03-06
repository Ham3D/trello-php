<?php

class Board_Test extends IntegrationTestCase
{
    protected $board_name = 'Hank Hill';
    protected $new_board_name = 'Hank Hill 2';
    protected $board_description = 'The patriarch of the Arlen, TX community.';
    protected $new_board_description = 'The patriarch of the Arlen, TX community. And master griller';
    protected $checklist_name = 'To complete';
    protected $list_name = 'To do';

    public function test_It_Can_Create_A_New_Board_With_Only_A_Name_Provided()
    {
        $attributes = ['name' => $this->board_name];

        $result = Trello_Board::create($attributes);

        $this->assertInstanceOf('Trello_Board', $result);
        $this->assertEquals($this->board_name, $result->name);
        $this->assertNull($result->cash_money);

        return $result->id;
    }

    /**
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Create_A_Board_Without_Name()
    {
        $results = Trello_Board::create();
    }

    public function test_It_Can_Create_A_New_Board_With_A_Name_And_Description_Provided()
    {
        $attributes = [
            'name' => $this->board_name,
            'desc' => $this->board_description
        ];

        $result = Trello_Board::create($attributes);

        $this->assertInstanceOf('Trello_Board', $result);
        $this->assertEquals($this->board_name, $result->name);
        $this->assertEquals($this->board_description, $result->desc);
    }

    /**
     * @depends test_It_Can_Create_A_New_Board_With_Only_A_Name_Provided
     */
    public function test_It_Can_Get_A_Board($board_id)
    {
        $result = Trello_Board::fetch($board_id);

        $this->assertInstanceOf('Trello_Board', $result);

        return $result;
    }

    /**
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Get_A_Board_Without_Id()
    {
        $results = Trello_Board::fetch();
    }

    public function test_It_Can_Not_Get_A_Board_With_Invalid_Id()
    {
        $id = uniqid();
        $result = Trello_Board::fetch($id);

        $this->assertCount(0, $result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Update_A_Board_Name($board)
    {
        $result = $board->updateName($this->new_board_name);

        $this->assertInstanceOf('Trello_Board', $result);
        $this->assertEquals($this->new_board_name, $result->name);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Update_A_Board_Name_With_No_Name($board)
    {
        $result = $board->updateName();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Update_A_Board_Description($board)
    {
        $result = $board->updateDescription($this->new_board_description);

        $this->assertInstanceOf('Trello_Board', $result);
        $this->assertEquals($this->new_board_description, $result->desc);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_Checklist_With_A_Name($board)
    {
        $result = $board->addChecklist($this->checklist_name);

        $this->assertInstanceOf('Trello_Checklist', $result);
        $this->assertEquals($this->checklist_name, $result->name);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Add_A_Checklist_Without_A_Name($board)
    {
        $result = $board->addChecklist();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_List_With_A_Name($board)
    {
        $attributes = ['name' => $this->list_name];
        $result = $board->addList($attributes);

        $this->assertInstanceOf('Trello_List', $result);
        $this->assertEquals($this->list_name, $result->name);

        return $board;
    }

    /**
     * @depends test_It_Can_Get_A_Board
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Add_A_List_Without_A_Name($board)
    {
        $result = $board->addList();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_List_With_A_Name_And_Position($board)
    {
        $attributes = ['name' => $this->list_name, 'position' => 1];
        $result = $board->addList($attributes);

        $this->assertInstanceOf('Trello_List', $result);
        $this->assertEquals($this->list_name, $result->name);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_List_With_A_Name_And_Invalid_Position($board)
    {
        $attributes = ['name' => $this->list_name, 'position' => uniqid()];
        $result = $board->addList($attributes);

        $this->assertInstanceOf('Trello_List', $result);
        $this->assertEquals($this->list_name, $result->name);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_List_With_A_Name_And_Top_Position($board)
    {
        $attributes = ['name' => $this->list_name, 'position' => 'top'];
        $result = $board->addList($attributes);

        $this->assertInstanceOf('Trello_List', $result);
        $this->assertEquals($this->list_name, $result->name);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_A_List_With_A_Name_And_Bottom_Position($board)
    {
        $attributes = ['name' => $this->list_name, 'position' => 'bottom'];
        $result = $board->addList($attributes);

        $this->assertInstanceOf('Trello_List', $result);
        $this->assertEquals($this->list_name, $result->name);
    }

    /**
     * @depends test_It_Can_Add_A_List_With_A_Name
     */
    public function test_It_Can_Get_All_Lists($board)
    {
        $result = $board->getLists();

        $this->assertInstanceOf('Trello_Collection', $result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_Powerup_With_Valid_Powerup_Name($board)
    {
        $powerup = 'cardAging';
        $result = $board->addPowerUp($powerup);

        $this->assertEquals('array', gettype($result));
        $this->assertContains($powerup, $result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Add_Powerup_Without_Valid_Powerup_Name($board)
    {
        $result = $board->addPowerUp();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Remove_Powerup_With_Valid_Powerup_Name($board)
    {
        $powerup = 'cardAging';
        $result = $board->removePowerUp($powerup);

        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Remove_Powerup_Without_Valid_Powerup_Name($board)
    {
        $result = $board->removePowerUp();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_Powerup_Card_Aging($board)
    {
        $result = $board->addPowerUpCardAging();

        $this->assertTrue(is_array($result));
        $this->assertContains('cardAging', $result);

        return $board;
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_Powerup_Calendar($board)
    {
        $result = $board->addPowerUpCalendar();

        $this->assertTrue(is_array($result));
        $this->assertContains('calendar', $result);

        return $board;
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_Powerup_Recap($board)
    {
        $result = $board->addPowerUpRecap();

        $this->assertTrue(is_array($result));
        $this->assertContains('recap', $result);

        return $board;
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Add_Powerup_Voting($board)
    {
        $result = $board->addPowerUpVoting();

        $this->assertTrue(is_array($result));
        $this->assertContains('voting', $result);

        return $board;
    }

    /**
     * @depends test_It_Can_Add_Powerup_Card_Aging
     */
    public function test_It_Can_Remove_Powerup_Card_Aging($board)
    {
        $result = $board->removePowerUpCardAging();

        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Add_Powerup_Calendar
     */
    public function test_It_Can_Remove_Powerup_Calendar($board)
    {
        $result = $board->removePowerUpCalendar();

        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Add_Powerup_Recap
     */
    public function test_It_Can_Remove_Powerup_Recap($board)
    {
        $result = $board->removePowerUpRecap();

        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Add_Powerup_Voting
     */
    public function test_It_Can_Remove_Powerup_Voting($board)
    {
        $result = $board->removePowerUpVoting();

        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Generate_A_Calendar_Key($board)
    {
        $result = $board->generateCalendarKey();

        $this->assertInstanceOf('stdClass', $result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Generate_An_Email_Key($board)
    {
        $result = $board->generateEmailKey();

        $this->assertInstanceOf('stdClass', $result);
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Be_Marked_As_Viewed($board)
    {
        $result = $board->markAsViewed();

        $this->assertNotNull($result);
    }

    public function test_It_Can_Search_Boards_With_Valid_Keyword()
    {
        $keyword = $this->board_name;

        $results = Trello_Board::search($keyword);

        $this->assertInstanceOf('Trello_Collection', $results);
        return $results;
    }

    public function test_It_Can_Search_Boards_With_Valid_Keyword_And_No_Results()
    {
        $keyword = uniqid();

        $results = Trello_Board::search($keyword);

        $this->assertInstanceOf('Trello_Collection', $results);
    }

    /**
     * @expectedException Trello_Exception_Unexpected
     */
    public function test_It_Can_Not_Search_Boards_Without_Valid_Keyword()
    {
        $results = Trello_Board::search();
    }

    /**
     * @depends test_It_Can_Get_A_Board
     */
    public function test_It_Can_Close_A_Current_Board($board)
    {
        $result = $board->close();
        $this->assertTrue($result);
    }

    /**
     * @depends test_It_Can_Search_Boards_With_Valid_Keyword
     */
    public function test_It_Can_Close_A_Specific_Board_When_Id_Provided($boards)
    {
        foreach ($boards as $board) {
            $result = Trello_Board::closeBoard($board->id);
            $this->assertTrue($result);
        }
    }

    /**
     * @expectedException Trello_Exception_ValidationsFailed
     */
    public function test_It_Can_Not_Close_A_Specific_Board_When_Id_Not_Provided()
    {
        $result = Trello_Board::closeBoard();
    }

    public function test_It_Can_Get_Cards_For_A_Board()
    {
        $card = $this->createTestCard();
        $board = $card->getBoard();

        $result = $board->getCards();

        if (count($result) == 1) {
            $this->assertInstanceOf('Trello_Card', $result);
        } else {
            $this->assertInstanceOf('Trello_Collection', $result);
        }
    }
}
