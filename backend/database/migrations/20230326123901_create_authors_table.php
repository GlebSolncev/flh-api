<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAuthorsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('authors');

        $table->addColumn('username', 'string', ['limit' => 100])
            ->addColumn('first_name', 'string', ['limit' => 100])
            ->addColumn('last_name', 'string', ['limit' => 100])
            ->addColumn('link', 'text', ['limit' => 200])
            ->create();
    }


    public function down()
    {
        $this->table('authors')->drop()->save();
    }
}
