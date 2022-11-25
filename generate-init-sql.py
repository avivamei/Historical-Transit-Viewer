#!/usr/bin/env python3

# This script will read the sql commands from reset-tables.php
# where we adjust starter data, and paste these commands into
# sql/reset-autogen.sql, which is required for grading.
#
# to use:
# python3 generate-init-sql.py

import re

f = open('304-app/reset-tables.php').read()


f_no_comments = re.sub(r'[\t\s]*[//]+.*\n', 'a', f)
commands = re.findall(r'executePlainSQL\("([\w\W]*?)"\);', f_no_comments)

outfile = open('304-app/sql/reset-autogen.sql', 'w')

for c in commands:
    c = c.strip()
    print(c, end='')
    outfile.write(c)
    if c[-1] != ';':
        print(';', end='')
        outfile.write(';')
    print('\n\n')
    outfile.write('\n\n')
