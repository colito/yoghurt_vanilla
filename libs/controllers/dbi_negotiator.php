<?php
include_lib(T_MODEL.'db_interrogator');
class DbINegotiator extends DbInterrogator
{
    public $columns = array();

    function __construct($table)
    {
        parent::__construct();

        $this->db_table = $table;
        $columns = $this->table_columns($this->db_table);

        foreach($columns as $col)
            $this->columns[$col] = null;
    }

    public function columns_to_save()
    {
        $columns = $this->columns;

        $columns_to_save = array();
        foreach($columns as $key => $column)
        {
            if(!empty($column))
            {
                $columns_to_save[$key] = $column;
            }
        }
        return $columns_to_save;
    }

    public function save($check = 0, $check_column = null, $check_value = null)
    {
        $table = $this->db_table;
        $columns = $this->columns_to_save();

        if($check == 1)
        {
            $where = $check_column .' = "' . $check_value.'"';

            $exists = $this->record_exists($where);
            if($exists) { return false; }
        }

        $this->insert_data($table, $columns);

        return true;
    }
}