<?php
include_once './src/utils/autoload.php';
include_once './src/utils/uuid.php';

class TransactionController
{
    public function __construct()
    {
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_NAME');

        $this->db = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
    }

    public function create($param)
    {
        $referenceId = guidv4();
        $numberVa = $param['payment_type'] == 'virtual_account' ? random_int(1000000000, 9999999999) : '-';
        $status = 'Pending';
        if (
            isset($param['invoice_id']) &&
            isset($param['item_name']) &&
            isset($param['amount']) &&
            isset($param['payment_type']) &&
            isset($param['customer_name']) &&
            isset($param['merchant_id']) && ($param['payment_type'] == 'virtual_account' || $param['payment_type'] == 'credit_card')
        ) {
            $data = $this->db->prepare('INSERT INTO t_transaction (references_id, invoice_id, item_name, amount, payment_type, customer_name, number_va, merchant_id, `status`) VALUES (?,?,?,?,?,?,?,?,?)');

            $data->bindParam(1, $referenceId);
            $data->bindParam(2, $param['invoice_id']);
            $data->bindParam(3, $param['item_name']);
            $data->bindParam(4, $param['amount']);
            $data->bindParam(5, $param['payment_type']);
            $data->bindParam(6, $param['customer_name']);
            $data->bindParam(7, $numberVa);
            $data->bindParam(8, $param['merchant_id']);
            $data->bindParam(9, $status);

            $historyQuery = $this->db->prepare('INSERT INTO t_transaction_history (transaction_references_id, `status`) VALUES (?,?)');

            $historyQuery->bindParam(1, $referenceId);
            $historyQuery->bindParam(2, $status);

            $data->execute();
            $historyQuery->execute();

            $result = array(
                'references_id' => $referenceId,
                'number_va' => $numberVa,
                'status' => $status
            );

            if ($data->rowCount() === 1 && $historyQuery->rowCount() === 1) {
                return json_encode(array(
                    'data' => $result,
                    'message' => 'Insert data success!'
                ));
            } else {
                http_response_code(404);
                return json_encode(array(
                    'data' => $result,
                    'message' => 'Insert data failed!'
                ));
            }
        } else {
            http_response_code(404);
            return json_encode(array(
                'data' => false,
                'message' => 'query param invoice_id, item_name, amount payment_type, customer_name, merchant_id is required!'
            ));
        }
    }

    public function getAll()
    {
        $query = $this->db->prepare("SELECT * FROM t_transaction");
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        return json_encode(array(
            'data' => $data ? $data : [],
            'message' => null
        ));
    }

    public function getById($param)
    {
        if (isset($param['references_id']) && isset($param['merchant_id'])) {
            $query = $this->db->prepare(
                "SELECT references_id, invoice_id, `status` 
                FROM t_transaction where references_id=? and merchant_id=?"
            );

            $query->bindParam(1, $param['references_id']);
            $query->bindParam(2, $param['merchant_id']);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

            $historyQuery = $this->db->prepare(
                "SELECT `status`, create_at
                FROM t_transaction_history where transaction_references_id=?"
            );

            $historyQuery->bindParam(1, $param['references_id']);
            $historyQuery->execute();

            $data['History'] = $historyQuery->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');

            return json_encode(array(
                'data' => $data ? $data : null,
                'message' => null
            ));
        } else {
            http_response_code(404);
            return json_encode(array(
                'data' => false,
                'message' => 'query param references_id & merchant_id is required!'
            ));
        }
    }

    public function update($referenceId, $status)
    {
        if ($status === 'Paid' || $status === 'Failed') {
            $query = $this->db->prepare("UPDATE t_transaction set `status`= '$status' where `references_id` = '$referenceId'");

            $query->execute();

            $data = $this->db->prepare('INSERT INTO t_transaction_history (transaction_references_id, `status`) VALUES (?,?)');

            $data->bindParam(1, $referenceId);
            $data->bindParam(2, $status);

            $data->execute();

            return ($query->rowCount() === 1 && $data->rowCount() === 1) ?
                "update " . $status . " transaction " . $referenceId . " berhasil!" :
                "update " . $status . " transaction " . $referenceId . " gagal!";
        } else {
            return "Status is only Paid/Failed";
        }
    }
}
