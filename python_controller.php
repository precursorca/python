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


public function get_data($serial_number = '')
{
    $python = new Python_model();

    // Minimal SQL — get all Python rows for this machine
    $sql = "SELECT * FROM python WHERE serial_number = '".$serial_number."'";

    $out = [];
    foreach ($python->query($sql) as $obj) {
        $out[] = [
            'label' => $obj->label,
            'path' => $obj->path,
            'version' => $obj->version,
            'notes' => $obj->notes
        ];
    }

    jsonView($out);
}

public function get_count($serial_number = '')
{
    $python = new Python_model();

    $sql = "SELECT COUNT(*) AS count FROM python WHERE serial_number = '".$serial_number."'";

    $out = 0;
    foreach ($python->query($sql) as $obj) {
        $out = (int) $obj->count;
    }

    // Return as a JSON object with numberofissues
    jsonView(['numberofissues' => $out]);
}

} // END class Python_controller
