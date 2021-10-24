<?php


use Phinx\Seed\AbstractSeed;

include_once './src/utils/uuid.php';

class TTransactionSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $referenceId = guidv4();
        $dataTransaction = [
            [
                'references_id' => $referenceId,
                'invoice_id' => 'INV-20211025',
                'item_name' => 'Sepeda',
                'amount' => 200000,
                'payment_type' => 'credit_card',
                'customer_name' => 'Test',
                'number_va' => '',
                'merchant_id' => guidv4(),
                'status' => 'Pending'
            ]
        ];

        $dataTransactionHisotry = [
            [
                'transaction_references_id' => $referenceId,
                'status' => 'Pedning'
            ]
        ];

        $transaction = $this->table('t_transaction');
        $transaction->insert($dataTransaction)
            ->saveData();

        $transactionHistory = $this->table('t_transaction_history');
        $transactionHistory->insert($dataTransactionHisotry)
            ->saveData();
    }
}
