#!/usr/local/munkireport/munkireport-python3
import plistlib
import subprocess
import os
import sys

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist

# Labels and paths
NAMES = [
    "Xcode_Python",
    "Homebrew_Python",
    "Munki_Python",
    "Munkireport_Python2",
    "Munkireport_Python3",
    "AutoPkgr_Python",
]

PATHS = [
    "/usr/bin/python3",
    "/opt/homebrew/bin/python3",
    "/usr/local/munki/Python.framework/Versions/Current/bin/python3",
    "/usr/local/munkireport/munkireport-python2",
    "/usr/local/munkireport/munkireport-python3",
    "/Library/AutoPkg/Python3/Python.framework/Versions/Current/bin/python3",
]

results = []

for name, path in zip(NAMES, PATHS):

    if not os.access(path, os.X_OK):
        continue  # skip non-existent or non-executable paths

    entry = {
        "label": name,
        "path": path,
        "version": "",
        "notes": ""
    }

    try:
        proc = subprocess.run(
            [path, "--version"],
            capture_output=True,
            text=True
        )
        output = proc.stderr.strip() if proc.stderr else proc.stdout.strip()

        # Handle Xcode Python reporting Xcode message
        if name == "Xcode_Python" and "xcode" in output.lower():
            entry["notes"] = output
        else:
            parts = output.split()
            if len(parts) >= 2:
                entry["version"] = parts[1]

    except Exception as e:
        entry["notes"] = f"Error running python: {e}"

    # Only append entries that actually exist
    results.append(entry)


def main():
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'python.plist')

    # Write only the list or empty dict if nothing exists
    data = results if results else {}

    with open(output_plist, "wb") as fp:
        plistlib.dump(data, fp)


if __name__ == "__main__":
    main()
