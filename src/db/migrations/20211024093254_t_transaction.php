<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TTransaction extends AbstractMigration
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
    public function change(): void
    {
        $table = $this->table('t_transaction');
        $table->addColumn('references_id', 'uuid')
            ->addcolumn('invoice_id', 'string', ['limit' => 20])
            ->addcolumn('item_name', 'string')
            ->addcolumn('amount', 'integer')
            ->addcolumn('payment_type', 'string', ['limit' => 15])
            ->addcolumn('customer_name', 'string', ['limit' => 50])
            ->addColumn('number_va', 'string', ['limit' => 15])
            ->addColumn('merchant_id', 'uuid')
            ->addcolumn('status', 'string', ['limit' => 7])
            ->addTimestamps('create_at')
            ->create();
    }
}
