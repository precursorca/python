<?php

use CFPropertyList\CFPropertyList;

class Python_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'python'); // primary key, table name

        // Define columns
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['label'] = '';
        $this->rs['path'] = '';
        $this->rs['version'] = '';
        $this->rs['notes'] = '';

        // Required for multi-row modules
        $this->serial = $serial;
    }

    // ------------------------------------------------------------------------

        public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing python module: No data found");
        } else {

            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming python.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process each python instance
            foreach ($plist as $python) {
                // Process each key in the account, if it exists
                foreach (array('label', 'path', 'version', 'notes') as $item) {

                    // If key does not exist in $python, null it
                    if ( ! array_key_exists($item, $python)) {
                        $this->$item = null;
                    // Set the db fields to be the same as those in the preference file
                    } else {
                        $this->$item = $python[$item];
                    }
                }

			// Save the data
            $this->id = '';
            $this->save(); 
            }
        }
	}
}
