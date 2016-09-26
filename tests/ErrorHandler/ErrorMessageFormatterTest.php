<?php

namespace Bence\Tests\ErrorHandler;

use Bence\ErrorHandler\ErrorMessageFormatter;

/**
 * Class ErrorMessageFormatterTest
 *
 * @author Bence Borbély
 */
class ErrorMessageFormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Bence\ErrorHandler\ErrorMessageFormatter::format
     *
     * @dataProvider errorDataProvider
     */
    public function testFormat($type, $code, $file, $line, $message)
    {
        $error = $this
            ->getMockBuilder('Bence\ErrorHandler\Error')
            ->setMethods([
                'getType',
                'getCode',
                'getFile',
                'getLine',
                'getMessage',
            ])
            ->getMock();

        $error
            ->expects($this->once())
            ->method('getType')
            ->willReturn($type);

        $error
            ->expects($this->once())
            ->method('getCode')
            ->willReturn($code);

        $error
            ->expects($this->once())
            ->method('getFile')
            ->willReturn($file);

        $error
            ->expects($this->once())
            ->method('getLine')
            ->willReturn($line);

        $error
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn($message);

        $formatter = new ErrorMessageFormatter();

        $expected = $type . " - Code: " . $code . "\n"
            . "Message: " . $message . "\n"
            . "File: " . $file . "\n"
            . "Line: " . $line . "\n";

        $this->assertEquals($expected, $formatter->format($error));
    }

    /**
     * @return array
     */
    public function errorDataProvider()
    {
        return [
            ['NOTICE', E_NOTICE, '/www/data/index.php', 50, "Undefined index 'user_id'"],
        ];
    }

}
