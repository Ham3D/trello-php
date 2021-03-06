<?php

class Card_Test extends IntegrationTestCase
{
    /**
     * @expectedException Trello_Exception_ValidationsFailed
     **/
    public function test_It_Can_Not_Fetch_A_Card_When_Id_Not_Provided()
    {
        $card = Trello_Card::fetch();
    }

    public function test_It_Can_Fetch_A_Card_When_Id_Provided()
    {
        $card = $this->createTestCard();

        $result = Trello_Card::fetch($card->id);

        $this->assertInstanceOf('Trello_Card', $result);
        $this->assertEquals($card->id, $result->id);
    }

    public function test_It_Can_Fetch_Multiple_Card_When_Ids_Provided()
    {
        $card1 = $this->createTestCard();
        $card2 = $this->createTestCard(true);
        $card_ids = [$card1->id, $card2->id];

        $result = Trello_Card::fetch($card_ids);

        $this->assertInstanceOf('Trello_Collection', $result);
        $this->assertEquals($card1->id, $result[0]->id);
        $this->assertEquals($card2->id, $result[1]->id);
    }

    /**
     * @expectedException Trello_Exception_ValidationsFailed
     **/
    public function test_It_Can_Not_Create_A_New_Card_When_No_Attributes_Provided()
    {
        $card = Trello_Card::create();
    }

    /**
     * @expectedException Trello_Exception_ValidationsFailed
     **/
    public function test_It_Can_Not_Create_A_New_Card_When_List_Id_Not_Provided()
    {
        $attributes = ['name' => 'test card'];

        $card = Trello_Card::create($attributes);
    }

    public function test_It_Can_Create_A_New_Card_When_Name_List_Id_Provided()
    {
        $list = $this->createTestList();
        $attributes = ['name' => 'test card', 'idList' => $list->id];

        $card = Trello_Card::create($attributes);

        $this->assertInstanceOf('Trello_Card', $card);
        $this->assertEquals($list->id, $card->idList);

        return $card;
    }

    /**
     * @depends test_It_Can_Create_A_New_Card_When_Name_List_Id_Provided
     **/
    public function test_It_Can_Update_Parent_List_When_List_Model_Provided($card)
    {
        $list = $this->createTestList();

        $result = $card->updateList($list);

        $this->assertEquals($list->id, $result->idList);
    }

    public function test_It_Can_Fetch_Parent_List()
    {
        $card = $this->createTestCard();

        $list = $card->getList();

        $this->assertInstanceOf('Trello_List', $list);
    }
}
