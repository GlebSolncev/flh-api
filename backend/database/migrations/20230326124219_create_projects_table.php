<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProjectsTable extends AbstractMigration
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
        $table = $this->table('projects');

        $table->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('api_id', 'integer')
            ->addColumn('description', 'text')
            ->addColumn('link', 'string')
            ->addColumn('published_at', 'datetime')
            ->addColumn('amount', 'double', ['null' => true])
            ->addColumn('currency', 'string', ['null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('author_id', 'integer', ['signed' => false, 'null' => false])

            ->addForeignKey('author_id', 'authors', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }

    public function down()
    {
        $this->table('projects')->drop()->save();
    }
}
