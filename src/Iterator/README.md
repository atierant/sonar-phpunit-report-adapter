# RecursiveDOMIterator

COPIED FROM https://github.com/salathe/spl-examples/wiki/RecursiveDOMIterator

PHP provides several extensions to work with XML. One of them is `DOM`, which is an implementation of the W3C DOM Interface. While quite powerful, `DOM` lacks the capability to iterate over the entire DOM tree at once. To get around this limitation, we can create a custom class that implements SPL's `RecursiveIterator` interface to allow us to easily and fully traverse the DOM tree with `foreach`.

Internally, `DOM` uses the `DOMNodeList` class to collect and provide access to children of a `DOMNode`. The `RecursiveDOMIterator` will aggregate the `DOMNodeList` referenced in a `DOMNode::childNodes` property. When iterating over the `DOMNodeList`, the iterator has to keep track of the current position internally. The remaining work is implementing the methods demanded by the `RecursiveIterator` interface then.

## Class Code

```php
<?php

class RecursiveDOMIterator implements RecursiveIterator
{
    /**
     * Current Position in DOMNodeList
     * @var Integer
     */
    protected $_position;

    /**
     * The DOMNodeList with all children to iterate over
     * @var DOMNodeList
     */
    protected $_nodeList;

    /**
     * @param DOMNode $domNode
     * @return void
     */
    public function __construct(DOMNode $domNode)
    {
        $this->_position = 0;
        $this->_nodeList = $domNode->childNodes;
    }

    /**
     * Returns the current DOMNode
     * @return DOMNode
     */
    public function current()
    {
        return $this->_nodeList->item($this->_position);
    }

    /**
     * Returns an iterator for the current iterator entry
     * @return RecursiveDOMIterator
     */
    public function getChildren()
    {
        return new self($this->current());
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @return Boolean
     */
    public function hasChildren()
    {
        return $this->current()->hasChildNodes();
    }

    /**
     * Returns the current position
     * @return Integer
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * Moves the current position to the next element.
     * @return void
     */
    public function next()
    {
        $this->_position++;
    }

    /**
     * Rewind the Iterator to the first element
     * @return void
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * Checks if current position is valid
     * @return Boolean
     */
    public function valid()
    {
        return $this->_position < $this->_nodeList->length;
    }
}
```

## Usage

Like any other `RecursiveIterator`, the `RecursiveDOMIterator` has to be wrapped into a `RecursiveIteratorIterator` to go over children recursively. The example below would load the contents of a book.xml file into a DOMDocument, wrap it's instance into the appropriate iterators and then output all element nodes. Of course, this could be combined with additional iterators, like `FilterIterator`.

```php
<?php
$dom = new DOMDocument; // create new DOMDocument instance
$dom->load('books.xml');       // load DOMDocument with XML data

$dit = new RecursiveIteratorIterator(
            new RecursiveDOMIterator($dom),
            RecursiveIteratorIterator::SELF_FIRST);

foreach($dit as $node) {
    if($node->nodeType === XML_ELEMENT_NODE) {
        echo $node->nodeName, PHP_EOL;
    }
}

```
## References

- [[PHP Manual: DOM|http://php.net/manual/en/refs.xml.php]]
- [[PHP Manual: RecursiveIterator Interface|http://uk2.php.net/manual/en/class.recursiveiterator.php]]
- [[W3C Document Object Model (DOM) Level 3 Core Specification|http://www.w3.org/TR/DOM-Level-3-Core]]
- [[Zend Developer Zone: XML and PHP 5|http://devzone.zend.com/article/2387]]
- [[Zend Developer Zone: XML in PHP 5 - What's New?|http://devzone.zend.com/article/1713]]