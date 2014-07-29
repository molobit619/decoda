<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Hook\EmoticonHook;
use Decoda\Test\TestCase;

class EmoticonHookTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->setLineBreaks(true);
        $this->object->setXhtml(false);

        $hook = new EmoticonHook();
        $this->object->addHook($hook);
    }

    /**
     * Test smiley detection according to the positioning.
     *
     * @dataProvider getSmileyDetectionData
     */
    public function testSmileyDetection($value, $expected) {
        $this->assertEquals($expected, $this->object->reset($value)->parse());
    }

    /**
     * Provide all smiley patterns.
     *
     * @return array
     */
    public function getSmileyDetectionData() {
        return array(
            array(':/ at the beginning', '<img src="/images/hm.png" alt=""> at the beginning'),
            array('Smiley at the end :O', 'Smiley at the end <img src="/images/gah.png" alt="">'),
            array('Smiley in the middle :P of a string', 'Smiley in the middle <img src="/images/tongue.png" alt=""> of a string'),
            array(':):):)', ':):):)'),
            array('At the :)start of the word', 'At the :)start of the word'),
            array('At the mid:)dle of the word', 'At the mid:)dle of the word'),
            array('At the end:) of the word', 'At the end:) of the word'),
            array('http://', 'http://'),
            array("With a :/\n linefeed", 'With a <img src="/images/hm.png" alt=""><br> linefeed'),
            array("With a :/\r carriage return", 'With a <img src="/images/hm.png" alt=""><br> carriage return'),
            array("With a :/\t tab", 'With a <img src="/images/hm.png" alt="">' . "\t" . ' tab'),
            array(':/ :/', '<img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt="">'),
            array(':/ :/', '<img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt="">'),
            array(':/ :/ :/', '<img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt="">'),
        );
    }

    /**
     * Test that smiley faces are converted to emoticon images.
     *
     * @dataProvider getSmileyConversionData
     */
    public function testSmileyConversion($value, $expected) {
        $this->assertEquals($expected, $this->object->reset($value)->parse());
    }

    /**
     * Provide a mapping of smiley and rendered form.
     *
     * @return array
     */
    public function getSmileyConversionData() {
        $this->setUp();
        $hook = $this->object->getHook('Emoticon');
        $hook->startup();

        $data = array();

        foreach ($hook->getSmilies() as $smile) {
            $data[] = array($smile, $hook->render($smile, $this->object->getConfig('xhtmlOutput')));
        }

        return $data;
    }
}