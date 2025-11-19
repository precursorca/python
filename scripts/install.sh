#!/bin/bash

# python controller
CTL="${BASEURL}index.php?/module/python/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/python.py" -o "${MUNKIPATH}preflight.d/python.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/python.py"

	# Set preference to include this file in the preflight check
	setreportpref "python" "${CACHEPATH}python.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/python.py"

	# Signal that we had an error
	ERR=1
fi
