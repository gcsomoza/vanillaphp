<?php
use PHPUnit\Framework\TestCase;
use vanillaphp\Services\Units\DatabaseUnit;

class MockDatabase {
    use DatabaseUnit;
}

class DatabaseUnitTest extends TestCase {
    public function testBindParamPlaceholders() {
        $Database = new MockDatabase();
        $placeholders = $Database->_bindParamPlaceholders([
            'column1' => 'value1',
            'column2' => 'value2',
        ]);
        $this->assertEquals(
            'ss',
            $placeholders
        );
    }

    public function testInsert() {
        $Database = new MockDatabase();
        $sql = $Database->_insert(
            table: 'mytable',
            data: [
                'column1' => 'value1',
                'column2' => 'value2',
            ],
        );
        $this->assertEquals(
            'INSERT INTO `mytable` (`column1`,`column2`) VALUES(?,?)',
            $sql
        );
    }

    public function testDelete() {
        $Database = new MockDatabase();
        $sql = $Database->_delete(
            table: 'mytable', 
            where: [ 'id' => '99' ],
        );
        $this->assertEquals(
            'DELETE FROM `mytable` WHERE `id` = ?',
            $sql
        );
    }
}