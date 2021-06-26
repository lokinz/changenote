<?php


namespace Harvest\Tests\unit\ChangeNote;


use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeParser;

trait ChangeNoteParserSetup
{
    /**
     * @var ChangeParser
     */
    protected $changeParser;

    public function setUp()
    {
        $this->changeParser = new ChangeParser(new AnnotationReader());
    }
}
