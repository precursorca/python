<?php 

/**
 * python class
 *
 * @package munkireport
 * @author 
 **/
class Python_controller extends Module_controller
{
	    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
	
    /**
     * Default method
     *
     * @author AvB
     **/
    public function index()
    {
        echo "You've loaded the python module!";
    }

    /**
     * Get python information for python widget
     *
     * 
     **/
    public function get_python()
    {
        $sql = "SELECT COUNT(*) AS count, label
                FROM python
                LEFT JOIN reportdata USING (serial_number)
                " . get_machine_group_filter() . "
                GROUP BY label
                ORDER BY count DESC";

        $queryobj = new Python_model;
        $out = [];

        foreach ($queryobj->query($sql) as $obj) {
            // Normalize empty labels
            $obj->label = $obj->label ?: 'Unknown';

            if ($obj->count > 0) {
                $out[] = $obj;
            }
        }

        jsonView($out);
    }
    
    public function get_python_versions()
{
    $sql = "SELECT COUNT(*) AS count, version
            FROM python
            LEFT JOIN reportdata USING (serial_number)
            " . get_machine_group_filter() . "
            GROUP BY version
            ORDER BY count DESC";

    $queryobj = new Python_model;
    $out = [];

    foreach ($queryobj->query($sql) as $obj) {
        // Normalize empty versions
        $obj->version = $obj->version ?: 'Unknown';

        if ($obj->count > 0) {
            $out[] = $obj;
        }
    }

    jsonView($out);
}

    public function get_scroll_widget($column)
    {
        // Remove non-column name characters
        $column = preg_replace("/[^A-Za-z0-9_\-]]/", '', $column);

        $sql = "SELECT COUNT(CASE WHEN ".$column." <> '' AND ".$column." IS NOT NULL THEN 1 END) AS count, ".$column." 
                FROM python
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                AND ".$column." <> '' AND ".$column." IS NOT NULL 
                GROUP BY ".$column."
                ORDER BY count DESC";

        $queryobj = new Python_model;
        jsonView($queryobj->query($sql));
    }
    
     public function get_python_instances()
     {
        $sql = "SELECT COUNT(CASE WHEN name <> '' AND name IS NOT NULL THEN 1 END) AS count, name 
                FROM python
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                GROUP BY name
                ORDER BY count DESC";

        $out = array();
        $queryobj = new Python_model;
        foreach ($queryobj->query($sql) as $obj) {
            if ("$obj->count" !== "0") {
                $obj->name = $obj->name ? $obj->name : 'Unknown';
                $out[] = $obj;
            }
        }

        jsonView($out);
     }


    public function get_data($serial_number = '')
    {
        jsonView(
            Python_model::select('python.*')
            ->whereSerialNumber($serial_number)
            ->filter()
            ->limit(1)
            ->first()
            ->toArray()
        );
    }

    public function get_list($column = '')
    {
        jsonView(
            Python_model::select("python.$column AS label")
                ->selectRaw('count(*) AS count')
                ->filter()
                ->groupBy($column)
                ->orderBy('count', 'desc')
                ->get()
                ->toArray()
        );
    }
} // END class Python_controller
