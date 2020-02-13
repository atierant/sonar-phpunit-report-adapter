# sonar-phpunit-report-adapter

[![Build Status](https://travis-ci.com/atierant/sonar-phpunit-report-adapter.svg?branch=master)](https://travis-ci.com/atierant/sonar-phpunit-report-adapter)

phpunit xml result patcher so sonarphp doesn't choke

## IT IS A NON-FUNCTIONAL WORK IN PROGRESS !!!!

## Usage

`./cmd convert data/report.xml`  

## Description

Formatage d'un [`report.xml`](./data/report.xml) via php [DOMDocument](https://www.php.net/manual/fr/book.dom.php) afin qu'il puisse être pris en compte dans Sonarqube.

[J'ai trouvé chez IteratorGarden](https://github.com/hakre/Iterator-Garden) une référence vers [RecursiveDOMIterator](https://github.com/salathe/spl-examples/wiki/RecursiveDOMIterator) qui pourrait être intéressante à utiliser.

## Description des erreurs Sonar rencontrées

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

[Une solution](https://github.com/Codeception/Codeception/issues/5004#issuecomment-419493813) + [une autre partie de la solution, ajouter un name](https://github.com/Codeception/Codeception/issues/5004#issuecomment-450865682)

2. `ERROR: Error during SonarQube Scanner execution java.lang.UnsupportedOperationException: Can not add the same measure twice`  
Impossible d'ajouter deux fois la même mesure, en cas d'utilisation d'un provider par exemple, cf. les 2x2 tests du MyHelperTest.  
 
 Le bloc suivant n'est pas compris par Sonar :

````xml
<testsuites>
  <testsuite assertions="527" errors="0" failures="0" name="My Test Suite" skipped="4" tests="299" time="157.837608">
    <testsuite assertions="20" errors="0" failures="0" file="./tests/Unitary/Helper/MyHelperTest.php" name="Tests\Unitary\App\Helper\MyHelperTest" skipped="0" tests="20" time="0.130271">
      <testsuite assertions="12" errors="0" failures="0" file="Tests\Unitary\App\Helper\MyHelperTest::testFirst" name="Tests\Unitary\App\Helper\MyHelperTest::testFirst" skipped="0" tests="12" time="0.021404">
        <testcase assertions="1" class="Tests\Unitary\App\Helper\MyHelperTest" classname="Tests.Unitary.App.Helper.MyHelperTest" file="./tests/Unitary/Helper/MyHelperTest.php" line="86" name="testFirst with data set &quot;10 Mai 2018, ok&quot;" time="0.001112" />
        <testcase assertions="1" class="Tests\Unitary\App\Helper\MyHelperTest" classname="Tests.Unitary.App.Helper.MyHelperTest" file="./tests/Unitary/Helper/MyHelperTest.php" line="86" name="testFirst with data set &quot;Dernier jour du mois en cours, ok&quot;" time="0.000974" />
        <!-- 10 other tests for MyHelperTest:86 -->
      </testsuite>
    </testsuite>
    <!-- 8 other tests in MyHelperTest -->
  </testsuite>
</testsuites>
````

## Liens

+ [2015-02-18 Une discussion sur Graddle au sujet de cette erreur](https://discuss.gradle.org/t/sonar-runner-same-project-analyzed-twice-causing-sonar-exception-can-not-add-the-same-measure-twice/5259)
+ [2018-09-21 Une discussion sur la communauté Sonarsource](https://community.sonarsource.com/t/sonarphp-doesnt-analyze-php-unit-tests-with-dataprovider/2775/5)
+ [2019-04-19 Black Silence a fait un script python pour corriger](https://gist.github.com/black-silence/35b958fe92c704de551a3ca4ea082b87)
+ [2019-05-09 Kostajh a eu l'erreur `Can not add the same measure twice` avec le code de BlackSilence et a modifié le script de BS qui ignore la couverture des tests des providers](https://gerrit.wikimedia.org/r/c/integration/config/+/508019/9/dockerfiles/quibble-stretch-php70/phpunit-junit-edit.py)
+ [2019-06-17 macghriogair a adapté le script de Kostajh pour déplacer les nœuds de testcase dans la testsuite parente à la place](https://gist.github.com/macghriogair/4976b8e6ea6d20a61cdeb95effb73364)
