#!/usr/bin/env python

# coding: utf-8
# @see https://gist.github.com/macghriogair/4976b8e6ea6d20a61cdeb95effb73364
# Adapted from https://gist.github.com/black-silence/35b958fe92c704de551a3ca4ea082b87

import xml.etree.ElementTree as ET

# Fichier d'entree = fichier de sortie
report_file_path = 'report.xml'

et = ET.parse(report_file_path)
# Il recupere 'testsuites' (<Element 'testsuites' at 0x7f727652cd50>)
root = et.getroot()

# Il recupere LA mastersuite 'testsuite' principale dans 'testsuites' (appelee name="My Test Suite")
for mastersuites in root:
    # Pour chaque 'testsuite' dans la mastersuite principale
    for testsuite in mastersuites:
        # Si jamais il n'y a pas de fichier, il passe a la testsuite suivante
        if not "file" in testsuite.attrib:
            continue
        # Pour chaque sous-suite de la testsuite (qui peut etre un 'testsuite', un 'testcase')
        for subsuite in testsuite:
            # Si c'est pas un 'testsuite', c'est un 'testcase', on le laisse
            if subsuite.tag != "testsuite":
                continue
            # Ici c'est donc un 'testsuite', il remonte chaque 'testcase' dans la 'testsuite' parente
            for testcase in subsuite:
                testsuite.append(testcase)
            # A la fin de la boucle, il enleve la sous-suite, qui est censee etre videe
            testsuite.remove(subsuite)

# Il finit par ecrire le fichier
et.write('python_result_'+report_file_path)
