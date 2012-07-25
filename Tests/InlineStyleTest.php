<?php

namespace InlineStyler\Tests;

use InlineStyler\InlineStyle;

/**
 * Test class for InlineStyle.
 * Generated by PHPUnit on 2010-03-10 at 21:52:44.
 */
class InlineStyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InlineStyle
     */
    protected $object;
    protected $basedir;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->basedir = __DIR__."/testfiles";
        $this->object = new InlineStyle($this->basedir."/test.html");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    public function testGetHTML()
    {
        $this->assertEquals(
            file_get_contents($this->basedir."/testGetHTML.html"),
            $this->object->getHTML());
    }

    public function testApplyStyleSheet()
    {
        $this->object->applyStyleSheet("p:not(.p2) { color: red }");
        $this->assertEquals(
            file_get_contents($this->basedir."/testApplyStylesheet.html"),
            $this->object->getHTML());
    }

    public function testApplyRule()
    {
        $this->object->applyRule("p:not(.p2)", "color: red");
        $this->assertEquals(
            file_get_contents($this->basedir."/testApplyStylesheet.html"),
            $this->object->getHTML());
    }

    public function testExtractStylesheets()
    {
        $stylesheets = $this->object->extractStylesheets(null, $this->basedir);
        $expected = array(
'p{
    margin:0;
    padding:0 0 10px 0;
    background-image: url("someimage.jpg");
}
a:hover{
    color:Red;
}
p:hover{
    color:blue;
}
',
'
    h1{
        color:yellow
    }
    p {
        color:yellow !important;
    }
    p {
        color:blue
    }
',
);
        $this->assertEquals($expected, $stylesheets);
    }

    public function testApplyExtractedStylesheet()
    {
        $stylesheets = $this->object->extractStylesheets(null, $this->basedir);
        $this->object->applyStylesheet($stylesheets);

        $this->assertEquals(
            file_get_contents($this->basedir."/testApplyExtractedStylesheet.html"),
            $this->object->getHTML());
    }

    public function testParseStyleSheet()
    {
        $parsed = $this->object->parseStylesheet("p:not(.p2) { color: red }");
        $this->assertEquals(
            array(array("p:not(.p2)", "color: red")),
            $parsed);
    }

    public function testParseStyleSheetWithComments()
    {
        $parsed = $this->object->parseStylesheet("p:not(.p2) { /* blah */ color: red }");
        $this->assertEquals(
            array(array("p:not(.p2)", "color: red")),
            $parsed);
    }

    public function testIllegalXmlUtf8Chars()
    {
        // check an exception is not thrown when loading up illegal XML UTF8 chars
        new InlineStyle("<html><body>".chr(2).chr(3).chr(4).chr(5)."</body></html>");
    }
}
