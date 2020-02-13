<?php

namespace App\Formater;

use App\Iterator\RecursiveDOMIterator;
use CachingIterator;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use IteratorIterator;
use RecursiveIteratorIterator;

/**
 * Class SonarPhpUnitFormater
 * @package App\Command
 * @see     https://gist.github.com/black-silence/35b958fe92c704de551a3ca4ea082b87
 */
class SonarPhpUnitFormater
{
    /** @var DOMDocument */
    protected $dom;
    /** @var string */
    private $file;

    /**
     * SonarPhpUnitFormater constructor.
     *
     * @param string $file report.xml file path
     */
    public function __construct(string $file)
    {
        $this->file = $file;
        $this->dom = new DomDocument();
        $this->dom->formatOutput = true;
        $this->dom->preserveWhiteSpace = false;
        $this->dom->load($file);
    }

    /**
     * Format file
     */
    public function format()
    {
        $mastersuites = $this->dom->getElementsByTagName('testsuite');

        /** @var DOMNode $testsuite */
        foreach ($mastersuites as $key => $testsuite) {
            if (empty($testsuite->getAttribute('file'))) {
                continue;
            }

            foreach ($testsuite->childNodes as $childNode) {
                $this->iterateChildren($childNode);
            }
        }

        $output = "data/actual_report.xml";
        $this->dom->save($output);

        return $output;
    }

    /**
     * Format file with recursive DOM iterator
     */
    public function formatWithRecursiveIterator()
    {
        $mastersuites = $this->dom->getElementsByTagName('testsuite');

        /** @var DOMNode $testsuite */
        foreach ($mastersuites as $key => $testsuite) {
            if (empty($testsuite->getAttribute('file'))) {
                continue;
            }

            foreach ($testsuite->childNodes as $childNode) {
                $dit = new RecursiveIteratorIterator(
                    new RecursiveDOMIterator($childNode),
                    RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ($dit as $node) {
                    if ($node->nodeType === XML_ELEMENT_NODE) {
                        echo $node->nodeName, PHP_EOL;
                    }
                }
            }
        }

        $output = "data/actual_report_with_recursive.xml";
        $this->dom->save($output);

        return $output;
    }

    /**
     * @param DOMNode $node
     */
    protected function iterateChildren(DOMNode &$node)
    {
        // If it is a text node, we don't need it, i.e. format
        if ($node instanceof DOMText) {
            return;
        }

        // If it is a testcase node, we keep it into parent
        if ($node instanceof DOMElement and "testcase" === $node->tagName) {
            return;
        }

        // If it is a testcase node, we keep it into parent
        if ($node instanceof DOMElement and "testcase" === $node->tagName) {
            $parent = $node->parentNode;
            $parent->appendChild($node);
        }

        $children = $node->childNodes;
        if (!$children) {
            print_r("$node->nodeName de tag $node->tagName n'a pas d'enfant".PHP_EOL);

            return;
        }

        foreach ($children as $child) {
            $children = new IteratorIterator($children);
            $children = new CachingIterator($children, CachingIterator::TOSTRING_USE_KEY);
            $this->iterateChildren($child);
        }
    }
}
