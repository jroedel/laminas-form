<?php

/**
 * @see       https://github.com/laminas/laminas-form for the canonical source repository
 * @copyright https://github.com/laminas/laminas-form/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-form/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Form\Element;

use DateTime;
use Laminas\Form\Element\DateSelect as DateSelectElement;
use LaminasTest\Form\TestAsset\CustomTraversable;
use PHPUnit\Framework\TestCase;

use function get_class;

class DateSelectTest extends TestCase
{
    public function testProvidesInputSpecificationThatIncludesValidatorsBasedOnAttributes()
    {
        $element = new DateSelectElement();

        $inputSpec = $element->getInputSpecification();
        $this->assertArrayHasKey('validators', $inputSpec);
        $this->assertInternalType('array', $inputSpec['validators']);

        $expectedClasses = [
            'Laminas\Validator\Date',
        ];
        foreach ($inputSpec['validators'] as $validator) {
            $class = get_class($validator);
            $this->assertContains($class, $expectedClasses, $class);
            switch ($class) {
                case 'Laminas\Validator\Date':
                    $this->assertEquals('Y-m-d', $validator->getFormat());
                    break;
                default:
                    break;
            }
        }
    }

    public function testCanSetDateFromDateTime()
    {
        $element  = new DateSelectElement();
        $element->setValue(new DateTime('2012-09-24'));

        $this->assertEquals('2012', $element->getYearElement()->getValue());
        $this->assertEquals('09', $element->getMonthElement()->getValue());
        $this->assertEquals('24', $element->getDayElement()->getValue());
    }

    public function testCanSetDateFromString()
    {
        $element  = new DateSelectElement();
        $element->setValue('2012-09-24');

        $this->assertEquals('2012', $element->getYearElement()->getValue());
        $this->assertEquals('09', $element->getMonthElement()->getValue());
        $this->assertEquals('24', $element->getDayElement()->getValue());
    }

    public function testCanGetValue()
    {
        $element  = new DateSelectElement();
        $element->setValue(new DateTime('2012-09-24'));

        $this->assertEquals('2012-09-24', $element->getValue());
    }

    /**
     * @expectedException \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testThrowsOnInvalidValue()
    {
        $element  = new DateSelectElement();
        $element->setValue('hello world');
    }

    public function testConstructAcceptsDayAttributes()
    {
        $sut = new DateSelectElement('dateSelect', ['day_attributes' => ['class' => 'test']]);
        $dayAttributes = $sut->getDayAttributes();
        $this->assertEquals('test', $dayAttributes['class']);
    }

    public function testConstructAcceptsTraversableOptions()
    {
        $options = new CustomTraversable([
            'day_attributes' => ['class' => 'test'],
        ]);
        $sut = new DateSelectElement('dateSelect', $options);

        $this->assertSame('test', $sut->getDayAttributes()['class']);
    }

    public function testSetOptionsAcceptsTraversableObject()
    {
        $options = new CustomTraversable([
            'day_attributes' => ['class' => 'test'],
        ]);
        $sut = new DateSelectElement();
        $sut->setOptions($options);

        $this->assertSame('test', $sut->getDayAttributes()['class']);
    }

    /**
     * @group 7114
     */
    public function testValueSetterReturnsSameObjectType()
    {
        $element = new DateSelectElement();

        $this->assertSame($element, $element->setValue('2014-01-01'));
    }
    
    public function testNullSetValueIsSemanticallyTodayWithoutEmptyOption()
    {
        $element  = new DateSelectElement('foo');
        $element->setShouldCreateEmptyOption(false);
        $now = new \DateTime();
        $element->setValue(null);
        $value = $element->getValue();
        // the getValue() function returns the date in 'Y-m-d' format
        $this->assertEquals($now->format('Y-m-d'), $value);
    }
    
    public function testNullSetValueIsNullWithEmptyOption()
    {
        $element  = new DateSelectElement('foo');
        $element->setShouldCreateEmptyOption(true);
        $element->setValue(null);
        $value = $element->getValue();
        $this->assertEquals(null, $value);
    }
}
