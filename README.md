# sonar-phpunit-report-adapter

[![Build Status](https://travis-ci.com/atierant/sonar-phpunit-report-adapter.svg?branch=master)](https://travis-ci.com/atierant/sonar-phpunit-report-adapter)

phpunit xml result patcher so sonarphp doesn't choke

Refonte de certains scripts python que j'ai trouvé en DOMDocument php pour reformater un fichier report.xml afin qu'il puisse être pris en compte dans Sonarqube  
Besoin d'analyse en profondeur du xml, mes testsuites sont sur plusieurs niveaux  

Erreurs Sonar : 
1. `WARN: Test cases must always be descendants of a file-based suite, skipping`  
Les cas de test doivent toujours être des descendants d'une suite basée sur des fichiers, il faut donc une structure du type :
````xml
<testsuites>
  <testsuite name="My Test Suite">
    <testsuite assertions="x" errors="0" failures="0"
               file="./tests/FirstTest.php"
               name="Tests\FirstTest" skipped="0" tests="x" time="45.803655">
      <testcase assertions="1"
                class="Tests\FirstTest"
                classname="Tests.FirstTest"
                file="./tests/FirstTest.php" line="16"
                name="testExecute" time="45.803655" />
    </testsuite>
  </testsuite>
</testsuites>
````
2. `ERROR: Error during SonarQube Scanner execution java.lang.UnsupportedOperationException: Can not add the same measure twice`  
Impossible d'ajouter deux fois la même mesure, en cas d'utilisation d'un provider par exemple, cf. les 2x2 tests du MyHelperTest.  
 
 Le bloc suivant n'est pas compris par Sonar :

````xml
<testsuites>
  <testsuite assertions="527" errors="0" failures="0" name="My Test Suite" skipped="4" tests="299" time="157.837608">
    <testsuite assertions="75" errors="0" failures="0" file="./tests/Unitary/Helper/MyHelperTest.php" name="Tests\Unitary\App\Helper\MyHelperTest" skipped="0" tests="74" time="0.130271">
      <testsuite assertions="21" errors="0" failures="0" file="Tests\Unitary\App\Helper\MyHelperTest::testFirst" name="Tests\Unitary\App\Helper\MyHelperTest::testFirst" skipped="0" tests="21" time="0.021404">
        <testcase assertions="1" class="Tests\Unitary\App\Helper\MyHelperTest" classname="Tests.Unitary.App.Helper.MyHelperTest" file="./tests/Unitary/Helper/MyHelperTest.php" line="86" name="testFirst with data set &quot;10 Mai 2018, ok&quot;" time="0.001112" />
        <testcase assertions="1" class="Tests\Unitary\App\Helper\MyHelperTest" classname="Tests.Unitary.App.Helper.MyHelperTest" file="./tests/Unitary/Helper/MyHelperTest.php" line="86" name="testFirst with data set &quot;Dernier jour du mois en cours, ok&quot;" time="0.000974" />
      </testsuite>
    </testsuite>
    <!-- other test in MyHelperTest -->
  </testsuite>
</testsuites>
````

Usage : `./cmd convert data/report.xml`  

https://gist.github.com/black-silence/35b958fe92c704de551a3ca4ea082b87  
https://community.sonarsource.com/t/sonarphp-doesnt-analyze-php-unit-tests-with-dataprovider/2775/5  
https://gerrit.wikimedia.org/r/#/c/integration/config/+/508019/9/dockerfiles/quibble-stretch-php70/phpunit-junit-edit.py  
https://gist.github.com/macghriogair/4976b8e6ea6d20a61cdeb95effb73364  
https://www.php.net/manual/fr/book.dom.php  

https://github.com/hakre/Iterator-Garden
